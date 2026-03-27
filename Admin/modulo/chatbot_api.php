<?php
// api_chatbot_merged.php
// Integración: AdvancedChatbotML + estrategia NORMALIZACIÓN+MARGEN para priorizar Gemini cuando convenga.
// NOTA: Define GEMINI_API_KEY en las Variables de Entorno. No incluyas claves en el código.
define('DEBUG', true);

// Obtener API key (prioriza GEMINI_API_KEY, fallback a OPENAI_API_KEY para compatibilidad)
$API_KEY = getenv('GEMINI_API_KEY') ?: (getenv('OPENAI_API_KEY') ?: (isset($OPENAI_API_KEY) ? $OPENAI_API_KEY : null));

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar conexión
$possible_paths = [
    __DIR__ . '/conexion.php',
    dirname(__DIR__) . '/conexion.php',
    dirname(dirname(__DIR__)) . '/conexion.php',
];

$conexion = null;
foreach ($possible_paths as $p) {
    if (file_exists($p)) {
        require_once($p);
        break;
    }
}

if (!isset($conexion) || !$conexion) {
    echo json_encode(['success' => false, 'error' => 'No se pudo cargar la conexión MySQLi. Buscado en: ' . implode(', ', $possible_paths)], JSON_UNESCAPED_UNICODE);
    exit;
}

// Sistema de logging mejorado
$debugLogFile = __DIR__ . '/chatbot_advanced_debug.log';
function dbg_log($msg) {
    global $debugLogFile;
    if (defined('DEBUG') && DEBUG) {
        @file_put_contents($debugLogFile, "[".date('Y-m-d H:i:s')."] ".$msg.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
function log_metric($k) {
    @file_put_contents(__DIR__.'/chatbot_metrics.log', "[".date('Y-m-d H:i:s')."] $k\n", FILE_APPEND | LOCK_EX);
}

dbg_log("API iniciada. REMOTE=" . ($_SERVER['REMOTE_ADDR'] ?? 'cli'));
dbg_log("GEMINI_API_KEY present? " . ($API_KEY ? 'YES (masked)' : 'NO'));

// -----------------------
// Clase AdvancedChatbotML (unificada)
// -----------------------
class AdvancedChatbotML {
    private $conexion;
    private $gemini_api_key;
    private $context_memory = [];
    private $learning_rate = 0.1;
    private $ml_models = [];

    public function __construct($conexion, $api_key = null) {
        $this->conexion = $conexion;
        $this->gemini_api_key = $api_key;
        $this->loadUserContext();
        $this->initializeMLSystem();
    }

    /**
     * Inicializar sistema de ML
     */
    private function initializeMLSystem() {
        // Crear tablas si no existen
        $this->createMLTables();
        // Cargar modelos pre-entrenados
        $this->loadMLModels();
    }

    private function createMLTables() {
        $tables = [
            "CREATE TABLE IF NOT EXISTS ml_models (
                id INT AUTO_INCREMENT PRIMARY KEY,
                model_name VARCHAR(100) NOT NULL,
                model_type VARCHAR(50) NOT NULL,
                accuracy DECIMAL(5,4) DEFAULT 0.0,
                training_data_count INT DEFAULT 0,
                last_trained DATETIME,
                is_active BOOLEAN DEFAULT TRUE,
                model_parameters JSON,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS user_behavior (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                session_id VARCHAR(100),
                action_type VARCHAR(50) NOT NULL,
                action_data JSON,
                response_time_ms INT,
                success_rate DECIMAL(5,4),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_session (user_id, session_id),
                INDEX idx_action_type (action_type)
            )",
            "CREATE TABLE IF NOT EXISTS knowledge_weights (
                id INT AUTO_INCREMENT PRIMARY KEY,
                knowledge_id INT NOT NULL,
                topic_key VARCHAR(100) NOT NULL,
                weight DECIMAL(5,4) DEFAULT 1.0,
                usage_count INT DEFAULT 0,
                success_count INT DEFAULT 0,
                failure_count INT DEFAULT 0,
                last_used DATETIME,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_topic_weight (topic_key, weight)
            )"
        ];

        foreach ($tables as $tableSql) {
            @mysqli_query($this->conexion, $tableSql);
        }
    }

    private function loadMLModels() {
        $sql = "SELECT * FROM ml_models WHERE is_active = 1";
        $result = mysqli_query($this->conexion, $sql);
        if ($result) {
            $this->ml_models = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
        }
    }

    private function loadUserContext() {
        $this->context_memory = [];
        $sql = "SELECT sender, message, created_at FROM messages ORDER BY created_at DESC LIMIT 10";
        $result = mysqli_query($this->conexion, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $this->context_memory[] = $row;
            }
            $this->context_memory = array_reverse($this->context_memory);
            mysqli_free_result($result);
        }
    }

    /**
     * generateResponse (pipeline ML mejorado)
     */
    public function generateResponse($userMessage, $userId = null) {
        $startTime = microtime(true);
        $original = (string)$userMessage;
        $processed = $this->preprocessMessage($original);

        dbg_log("ML generateResponse: \"".substr($original,0,200)."\" processed=\"$processed\"");

        // DEBUG: Forzar Gemini vía ?force_gemini=1 (solo si DEBUG)
        if (defined('DEBUG') && DEBUG && !empty($_GET['force_gemini'])) {
            dbg_log("FORCE_GEMINI detected via ?force_gemini=1");
            if ($this->gemini_api_key) {
                $g = $this->generateWithAI($original);
                if ($g) {
                    dbg_log("FORCE_GEMINI: Gemini returned response, using it.");
                    log_metric('used_gemini_force');
                    $responseTime = (microtime(true) - $startTime) * 1000;
                    $this->logUserBehavior($userId, 'generate_response', ['query'=>$original,'response_time_ms'=>$responseTime,'selected_source'=>'ai_force','final_score'=>0.6]);
                    return ['text'=>$g,'kb_record'=>null,'response_metadata'=>['source'=>'ai_force','confidence'=>0.6,'response_time_ms'=>$responseTime]];
                }
            }
        }

        // PRIMERO: Búsqueda rápida en KB
        $kbCandidates = $this->searchKnowledgeBaseML($processed, 3);
        $useGemini = true; // Por defecto intentar Gemini

        if (!empty($kbCandidates)) {
            usort($kbCandidates, function($a,$b){ 
                return ($b['base_score'] ?? 0) <=> ($a['base_score'] ?? 0); 
            });
            $top = $kbCandidates[0];
            $dbScoreRaw = isset($top['base_score']) ? floatval($top['base_score']) : 0;

            // Umbral más bajo para usar KB - solo si es muy confidente
            if ($dbScoreRaw > 0.9) {
                dbg_log("Decision: USING DB KB (high confidence: $dbScoreRaw)");
                log_metric('used_db_high_confidence');
                $respText = $top['text'] ?? ($top['kb_record']['answer'] ?? '');
                $responseTime = (microtime(true) - $startTime) * 1000;

                $this->learnFromInteraction($original, [
                    'text' => $respText,
                    'source' => 'knowledge_base', 
                    'final_score' => $dbScoreRaw,
                    'kb_record' => $top['kb_record'] ?? null
                ], $userId);

                $this->logUserBehavior($userId, 'generate_response', [
                    'query' => $original,
                    'response_time_ms' => $responseTime,
                    'selected_source' => 'knowledge_db',
                    'final_score' => $dbScoreRaw
                ]);

                return [
                    'text' => $respText, 
                    'kb_record' => $top['kb_record'] ?? null, 
                    'response_metadata' => [
                        'source' => 'knowledge_db',
                        'confidence' => $dbScoreRaw,
                        'response_time_ms' => $responseTime
                    ]
                ];
            } else {
                dbg_log("Decision: DB score too low ($dbScoreRaw), will try Gemini");
            }
        } else {
            dbg_log("Decision: No DB candidates found, will try Gemini");
        }

        // INTENTAR GEMINI directamente para preguntas fuera de KB
        dbg_log("Attempting Gemini generation for query: " . substr($original, 0, 100));
        $geminiResponse = $this->generateWithAI($original);

        if ($geminiResponse) {
            dbg_log("Gemini generation SUCCESS");
            log_metric('used_gemini_generation');
            $responseTime = (microtime(true) - $startTime) * 1000;

            $this->learnFromInteraction($original, [
                'text' => $geminiResponse,
                'source' => 'ai_generation',
                'final_score' => 0.7
            ], $userId);

            $this->logUserBehavior($userId, 'generate_response', [
                'query' => $original,
                'response_time_ms' => $responseTime, 
                'selected_source' => 'ai_generation',
                'final_score' => 0.7
            ]);

            return [
                'text' => $geminiResponse,
                'kb_record' => null,
                'response_metadata' => [
                    'source' => 'ai_generation',
                    'confidence' => 0.7,
                    'response_time_ms' => $responseTime
                ]
            ];
        }

        // FALLBACK a búsqueda ML tradicional si Gemini falla
        dbg_log("Gemini failed, falling back to traditional ML pipeline");
        $candidates = $this->getResponseCandidates($processed, $original);
        $rankedCandidates = $this->rankCandidatesWithML($candidates, $processed, $userId);
        $bestCandidate = $this->selectBestCandidate($rankedCandidates);

        if (!$bestCandidate) {
            $bestCandidate = [
                'text' => $this->getIntelligentDefault($original),
                'source' => 'fallback',
                'final_score' => 0.1,
                'kb_record' => null
            ];
        }

        $this->learnFromInteraction($original, $bestCandidate, $userId);
        $responseTime = (microtime(true) - $startTime) * 1000;

        $this->logUserBehavior($userId, 'generate_response', [
            'query' => $original,
            'response_time_ms' => $responseTime,
            'selected_source' => $bestCandidate['source'],
            'final_score' => $bestCandidate['final_score']
        ]);

        return [
            'text' => $bestCandidate['text'],
            'kb_record' => $bestCandidate['kb_record'] ?? null,
            'response_metadata' => [
                'source' => $bestCandidate['source'],
                'confidence' => $bestCandidate['final_score'],
                'response_time_ms' => $responseTime
            ]
        ];
    }

    /**
     * Obtener candidatos de respuesta desde múltiples fuentes
     */
    private function getResponseCandidates($processedMessage, $originalMessage) {
        $candidates = [];
        // 1. Búsqueda en knowledge base con ML
        $kbCandidates = $this->searchKnowledgeBaseML($processedMessage);
        $candidates = array_merge($candidates, $kbCandidates);
        // 2. Búsqueda semántica
        $semanticCandidates = $this->semanticSearch($originalMessage);
        $candidates = array_merge($candidates, $semanticCandidates);
        // 3. Respuestas predefinidas
        $predefinedCandidates = $this->getPredefinedResponsesML($processedMessage);
        $candidates = array_merge($candidates, $predefinedCandidates);
        return $candidates;
    }

    /**
     * Búsqueda en knowledge base con ML
     */
    private function searchKnowledgeBaseML($query, $limit = 10) {
        $candidates = [];
        // Verificar si existe la columna ft_text
        $checkColumn = mysqli_query($this->conexion, "SHOW COLUMNS FROM knowledge_base LIKE 'ft_text'");
        $hasFulltext = $checkColumn && mysqli_num_rows($checkColumn) > 0;
        if ($checkColumn) mysqli_free_result($checkColumn);

        if ($hasFulltext) {
            $sql = "SELECT kb.*, COALESCE(kw.weight, 1.0) as ml_weight,
                           (MATCH(kb.ft_text) AGAINST(? IN BOOLEAN MODE) * 0.6 + COALESCE(kw.weight, 1.0) * 0.4) as relevance_score
                    FROM knowledge_base kb
                    LEFT JOIN knowledge_weights kw ON kb.id = kw.knowledge_id
                    WHERE kb.is_active = 1 
                    AND MATCH(kb.ft_text) AGAINST(? IN BOOLEAN MODE)
                    ORDER BY relevance_score DESC
                    LIMIT ?";
        } else {
            $sql = "SELECT kb.*, COALESCE(kw.weight, 1.0) as ml_weight, 0.5 as relevance_score
                    FROM knowledge_base kb
                    LEFT JOIN knowledge_weights kw ON kb.id = kw.knowledge_id
                    WHERE kb.is_active = 1 
                    AND (kb.question LIKE ? OR kb.answer LIKE ? OR kb.keywords LIKE ?)
                    ORDER BY kw.weight DESC, kb.usage_count DESC
                    LIMIT ?";
        }

        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            if ($hasFulltext) {
                mysqli_stmt_bind_param($stmt, 'ssi', $query, $query, $limit);
            } else {
                $searchTerm = "%$query%";
                mysqli_stmt_bind_param($stmt, 'sssi', $searchTerm, $searchTerm, $searchTerm, $limit);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $candidates[] = [
                    'text' => $row['answer'],
                    'source' => 'knowledge_base',
                    'base_score' => floatval($row['relevance_score'] ?? 0.5),
                    'kb_record' => $row,
                    'ml_weight' => floatval($row['ml_weight'] ?? 1.0)
                ];
            }
            mysqli_stmt_close($stmt);
        }
        return $candidates;
    }

    /**
     * Búsqueda semántica (fallback simple)
     */
    private function semanticSearch($query) {
        $candidates = [];
        $similarQuestions = $this->findSimilarQuestions($query);
        foreach ($similarQuestions as $question) {
            $candidates[] = [
                'text' => $question['answer'],
                'source' => 'semantic_search',
                'base_score' => $question['similarity_score'],
                'kb_record' => $question
            ];
        }
        return $candidates;
    }

    private function findSimilarQuestions($query, $limit = 5) {
        $similar = [];
        $sql = "SELECT kb.* FROM knowledge_base kb WHERE kb.is_active = 1 LIMIT ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $limit);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $similarity = $this->calculateSimilarity($query, $row['question']);
                if ($similarity > 0.3) {
                    $row['similarity_score'] = $similarity;
                    $similar[] = $row;
                }
            }
            mysqli_stmt_close($stmt);
        }
        usort($similar, function($a,$b){ return ($b['similarity_score'] ?? 0) <=> ($a['similarity_score'] ?? 0); });
        return array_slice($similar, 0, $limit);
    }

    private function calculateSimilarity($text1, $text2) {
        $words1 = array_count_values(str_word_count(mb_strtolower($text1), 1));
        $words2 = array_count_values(str_word_count(mb_strtolower($text2), 1));
        $intersection = array_intersect_key($words1, $words2);
        $union = $words1 + $words2;
        if (count($union) === 0) return 0;
        return count($intersection) / count($union);
    }

    private function getPredefinedResponsesML($processedMessage) {
        $candidates = [];
        $intent = $this->detectIntention($processedMessage, [
            'greeting' => ['hola', 'buenos', 'buenas', 'hey', 'saludos', 'como estas'],
            'farewell' => ['adios', 'bye', 'chao', 'hasta', 'nos vemos'],
            'thanks' => ['gracias', 'agradecido', 'agradecida', 'merci'],
            'help' => ['ayuda', 'help', 'socorro', 'asistencia']
        ]);
        if ($intent === 'greeting') {
            $candidates[] = ['text'=>$this->getPersonalizedGreeting(),'source'=>'predefined','base_score'=>0.9,'kb_record'=>null];
        } elseif ($intent === 'farewell') {
            $candidates[] = ['text'=>$this->getPersonalizedFarewell(),'source'=>'predefined','base_score'=>0.9,'kb_record'=>null];
        } elseif ($intent === 'thanks') {
            $candidates[] = ['text'=>'¡De nada! Estoy aquí para ayudarte. ¿Hay algo más en lo que pueda asistirte?','source'=>'predefined','base_score'=>0.9,'kb_record'=>null];
        } elseif ($intent === 'help') {
            $candidates[] = ['text'=>'Puedo ayudarte con información sobre salud sexual, anticonceptivos, ITS, consentimiento y más. ¿Qué tema te interesa?','source'=>'predefined','base_score'=>0.9,'kb_record'=>null];
        }
        return $candidates;
    }

    private function detectIntention($message, $intentions) {
        $best = null; $bestScore = 0;
        foreach ($intentions as $intent => $keywords) {
            $score = 0;
            foreach ($keywords as $kw) {
                if (mb_stripos($message, $kw) !== false) $score += mb_strlen($kw, 'UTF-8');
            }
            if ($score > $bestScore) { $bestScore = $score; $best = $intent; }
        }
        return $bestScore > 0 ? $best : null;
    }

    /**
     * Ranking de candidatos con ML (ajustes simples)
     */
    private function rankCandidatesWithML($candidates, $query, $userId) {
        foreach ($candidates as &$candidate) {
            $finalScore = floatval($candidate['base_score'] ?? 0.0);
            $userPreferenceScore = $this->getUserPreferenceScore($userId, $candidate);
            $finalScore *= (1 + $userPreferenceScore);
            $feedbackScore = $this->getFeedbackScore($candidate);
            $finalScore *= (1 + $feedbackScore);
            $contextScore = $this->getContextScore($query, $candidate);
            $finalScore *= (1 + $contextScore);
            $candidate['final_score'] = min(1.0, $finalScore);
        }
        usort($candidates, function($a,$b){ return ($b['final_score'] ?? 0) <=> ($a['final_score'] ?? 0); });
        return $candidates;
    }

    private function getUserPreferenceScore($userId, $candidate) {
        if (!$userId) return 0;
        $sql = "SELECT AVG(rating) as avg_rating FROM conversation_feedback WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            if ($row && $row['avg_rating']) return ($row['avg_rating'] - 3) / 10;
        }
        return 0;
    }

    private function getFeedbackScore($candidate) {
        if (!isset($candidate['kb_record']['id'])) return 0;
        $knowledgeId = $candidate['kb_record']['id'];
        $sql = "SELECT (success_count / GREATEST(usage_count, 1)) as success_rate FROM knowledge_weights WHERE knowledge_id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $knowledgeId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            if ($row && $row['success_rate']) return ($row['success_rate'] - 0.5) * 0.4;
        }
        return 0;
    }

    private function getContextScore($query, $candidate) {
        $queryWords = array_filter(explode(' ', mb_strtolower($query)));
        $responseWords = array_filter(explode(' ', mb_strtolower($candidate['text'] ?? '')));
        $commonWords = array_intersect($queryWords, $responseWords);
        $score = count($commonWords) / max(1, count($queryWords)) * 0.3;
        return $score;
    }

    private function selectBestCandidate($candidates) {
        foreach ($candidates as $candidate) {
            if (($candidate['final_score'] ?? 0) >= 0.3) return $candidate;
        }
        return $candidates[0] ?? null;
    }

    /**
     * Generar con IA externa (Gemini) - MEJORADO
     */
    private function generateWithAI($message) {
        if (!$this->gemini_api_key) {
            dbg_log("generateWithAI: NO GEMINI KEY available.");
            return null;
        }

        dbg_log("generateWithAI: Starting with message: " . substr($message, 0, 100));

        if ($this->isUnsafe($message)) {
            dbg_log("generateWithAI: input marked unsafe by isUnsafe()");
            return null;
        }

        $context = $this->buildContextForAI();
        $system = "Eres SEIN, un asistente virtual especializado en educación sexual y salud reproductiva. Responde de manera profesional, empática y educativa. Si la pregunta no está relacionada con tu área, redirige amablemente. Sé conciso y claro.";
        $full_prompt = $system . "\n\nContexto:\n" . $context . "\n\nUsuario: " . $message;

        dbg_log("generateWithAI: Full prompt length: " . strlen($full_prompt));

        // Usar el modelo correcto - versión estable
        $model = "gemini-1.5-flash"; // Cambiar a modelo más estable
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $full_prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'maxOutputTokens' => 800,
                'temperature' => 0.7,
                'topP' => 0.8,
                'topK' => 40
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH', 
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ]
            ]
        ];

        dbg_log("generateWithAI: Payload prepared for model: " . $model);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'x-goog-api-key: ' . $this->gemini_api_key
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        $curlErrNo = curl_errno($ch);
        curl_close($ch);

        // Log detallado
        dbg_log("generateWithAI: HTTP Code: $httpCode, Curl Error: $curlErr ($curlErrNo)");
        dbg_log("generateWithAI: Response: " . substr($response ?? 'NULL', 0, 500));

        if ($curlErrNo !== 0) {
            dbg_log("generateWithAI: CURL Error - " . $curlErr);
            return null;
        }

        if ($httpCode !== 200 || !$response) {
            dbg_log("generateWithAI: HTTP Error $httpCode or empty response");
            return null;
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            dbg_log("generateWithAI: JSON decode error: " . json_last_error_msg());
            return null;
        }

        // Extraer texto - método más robusto
        $generated = null;

        // Método principal
        if (!empty($decoded['candidates'][0]['content']['parts'][0]['text'])) {
            $generated = $decoded['candidates'][0]['content']['parts'][0]['text'];
            dbg_log("generateWithAI: Text extracted via primary method");
        }
        // Método alternativo
        elseif (!empty($decoded['candidates'][0]['content']['parts'])) {
            foreach ($decoded['candidates'][0]['content']['parts'] as $part) {
                if (!empty($part['text'])) {
                    $generated = $part['text'];
                    dbg_log("generateWithAI: Text extracted via parts iteration");
                    break;
                }
            }
        }
        // Método de respaldo
        elseif (!empty($decoded['candidates'][0]['content']['text'])) {
            $generated = $decoded['candidates'][0]['content']['text'];
            dbg_log("generateWithAI: Text extracted via content.text");
        }

        // Verificar si fue bloqueado por seguridad
        if (!empty($decoded['promptFeedback']['blockReason'])) {
            dbg_log("generateWithAI: Blocked by safety - " . $decoded['promptFeedback']['blockReason']);
            return "No puedo responder a esa consulta debido a restricciones de contenido. Por favor, reformula tu pregunta.";
        }

        if ($generated) {
            $generated = trim($generated);
            dbg_log("generateWithAI: SUCCESS - Generated: " . substr($generated, 0, 200));
            return $generated;
        }

        dbg_log("generateWithAI: FAILED - No text generated. Full structure: " . json_encode($decoded));
        return null;
    }

    private function buildContextForAI() {
        $context = "";
        $recent = array_slice($this->context_memory, -6);
        foreach ($recent as $m) $context .= ($m['sender'] ?? 'User').": ".($m['message'] ?? '')."\n";
        return $context;
    }

    // ---------------- ML learning helpers ----------------
    private function learnFromInteraction($userQuery, $selectedResponse, $userId) {
        if (isset($selectedResponse['kb_record']['id'])) {
            $this->updateKnowledgeWeight($selectedResponse['kb_record']['id'], true);
        }
        $this->learnQueryPatterns($userQuery, $selectedResponse['source'] ?? null);
        if (mt_rand(1, 100) <= 10) $this->trainMLModel();
    }

    private function updateKnowledgeWeight($knowledgeId, $success) {
        // verify exist
        $checkSql = "SELECT id FROM knowledge_weights WHERE knowledge_id = ?";
        $checkStmt = mysqli_prepare($this->conexion, $checkSql);
        if ($checkStmt) {
            mysqli_stmt_bind_param($checkStmt, 'i', $knowledgeId);
            mysqli_stmt_execute($checkStmt);
            $res = mysqli_stmt_get_result($checkStmt);
            $exists = ($res && mysqli_num_rows($res) > 0);
            if ($res) mysqli_free_result($res);
            mysqli_stmt_close($checkStmt);
        } else {
            $exists = false;
        }

        $topicKey = $this->extractTopicKey($knowledgeId);
        $successIncrement = $success ? 1 : 0;
        $failureIncrement = $success ? 0 : 1;
        $weightAdjustment = $success ? $this->learning_rate : -$this->learning_rate;

        if ($exists) {
            $sql = "UPDATE knowledge_weights 
                    SET usage_count = usage_count + 1,
                        success_count = success_count + ?,
                        failure_count = failure_count + ?,
                        weight = GREATEST(0.1, LEAST(2.0, weight + ?)),
                        last_used = NOW()
                    WHERE knowledge_id = ?";
            $stmt = mysqli_prepare($this->conexion, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'iidi', $successIncrement, $failureIncrement, $weightAdjustment, $knowledgeId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        } else {
            $sql = "INSERT INTO knowledge_weights (knowledge_id, topic_key, weight, usage_count, success_count, failure_count, last_used)
                    VALUES (?, ?, 1.0, 1, ?, ?, NOW())";
            $stmt = mysqli_prepare($this->conexion, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'isii', $knowledgeId, $topicKey, $successIncrement, $failureIncrement);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    private function extractTopicKey($knowledgeId) {
        $sql = "SELECT topic_key FROM knowledge_base WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $knowledgeId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $row['topic_key'] ?? 'general';
        }
        return 'general';
    }

    private function learnQueryPatterns($query, $source) {
        // placeholder: almacenar consultas exitosas para mejorar matching futuro
    }

    public function trainMLModel() {
        $sql = "UPDATE knowledge_weights kw
                JOIN (
                    SELECT knowledge_id, (success_count / GREATEST(usage_count, 1)) as success_rate
                    FROM knowledge_weights
                    WHERE usage_count > 0
                ) stats ON kw.knowledge_id = stats.knowledge_id
                SET kw.weight = 0.5 + (stats.success_rate * 0.5)";
        mysqli_query($this->conexion, $sql);
        dbg_log("ML Model trained: " . mysqli_affected_rows($this->conexion) . " weights updated");
        return true;
    }

    public function logUserBehavior($userId, $actionType, $actionData) {
        $sql = "INSERT INTO user_behavior (user_id, session_id, action_type, action_data, created_at)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            $sessionId = session_id();
            $actionDataJson = json_encode($actionData, JSON_UNESCAPED_UNICODE);
            mysqli_stmt_bind_param($stmt, 'isss', $userId, $sessionId, $actionType, $actionDataJson);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // ----------------- Utilities & small helpers -----------------
    private function preprocessMessage($message) {
        $m = trim($message);
        $m = $this->removeAccents($m);
        $m = mb_strtolower($m, 'UTF-8');
        $m = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $m);
        $m = preg_replace('/\s+/', ' ', $m);
        return trim($m);
    }

    private function removeAccents($string) {
        $accents = [
            'Á'=>'A','À'=>'A','Ä'=>'A','Â'=>'A','á'=>'a','à'=>'a','ä'=>'a','â'=>'a','ª'=>'a','ã'=>'a','å'=>'a','æ'=>'ae',
            'Ç'=>'C','ç'=>'c',
            'É'=>'E','È'=>'E','Ë'=>'E','Ê'=>'E','é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
            'Í'=>'I','Ì'=>'I','Ï'=>'I','Î'=>'I','í'=>'i','ì'=>'i','ï'=>'i','î'=>'i',
            'Ñ'=>'N','ñ'=>'n',
            'Ó'=>'O','Ò'=>'O','Ö'=>'O','Ô'=>'O','ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o','º'=>'o','ø'=>'o',
            'Ú'=>'U','Ù'=>'U','Ü'=>'U','Û'=>'U','ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u',
            'Ý'=>'Y','ý'=>'y','ÿ'=>'y'
        ];
        return strtr($string, $accents);
    }

    private function getPersonalizedGreeting() {
        $greetings = [
            "¡Hola! Soy SEIN, tu asistente en educación sexual y salud reproductiva. ¿En qué puedo orientarte hoy?",
            "¡Hola! Me alegra verte. ¿Tienes alguna pregunta sobre salud sexual o reproductiva?",
            "¡Saludos! Estoy aquí para ayudarte con información confiable sobre salud sexual."
        ];
        return $greetings[array_rand($greetings)];
    }

    private function getPersonalizedFarewell() {
        $farewells = [
            "¡Hasta pronto! Si necesitas más información, aquí estaré.",
            "¡Cuídate! Vuelve si tienes más dudas.",
            "¡Nos vemos! Recuerda cuidar tu salud sexual."
        ];
        return $farewells[array_rand($farewells)];
    }

    private function getIntelligentDefault($msg) {
        $len = strlen($msg);
        if ($len < 12) return "Veo tu mensaje corto. ¿Puedes dar más detalles para ayudarte mejor?";
        if (str_word_count($msg) > 60) return "Tu consulta es extensa. ¿Cuál es el punto principal que quieres resolver?";
        return "No estoy seguro de la intención exacta. ¿Puedes reformular o pedir un tema específico (anticonceptivos, ITS, embarazo, consentimiento)?";
    }

    private function isUnsafe($text) {
        if (!$text) return false;
        $t = mb_strtolower($text, 'UTF-8');
        $blacklist = ['suicid', 'matar', 'explos', 'bomba', 'bombas', 'drogas', 'cianuro', 'peligro', 'porn', 'abuso', 'violar'];
        foreach ($blacklist as $b) if (mb_stripos($t, $b) !== false) return true;
        return false;
    }

    // ----------------- Public utilities used by API -----------------
    public function exportKnowledge($format = 'json') {
        $sql = "SELECT kb.*, kw.weight, kw.usage_count, kw.success_count
                FROM knowledge_base kb
                LEFT JOIN knowledge_weights kw ON kb.id = kw.knowledge_id
                WHERE kb.is_active = 1
                ORDER BY kw.weight DESC, kb.usage_count DESC";
        $result = mysqli_query($this->conexion, $sql);
        $knowledge = [];
        while ($row = mysqli_fetch_assoc($result)) $knowledge[] = $row;
        return $knowledge;
    }

    public function searchKnowledge($filters = []) {
        $sql = "SELECT kb.*, COALESCE(kw.weight, 1.0) as ml_weight
                FROM knowledge_base kb
                LEFT JOIN knowledge_weights kw ON kb.id = kw.knowledge_id
                WHERE kb.is_active = 1";
        $params = []; $types = '';
        if (!empty($filters['category'])) { $sql .= " AND kb.category = ?"; $params[] = $filters['category']; $types .= 's'; }
        if (!empty($filters['search'])) {
            $sql .= " AND (kb.question LIKE ? OR kb.answer LIKE ? OR kb.keywords LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm; $types .= 'sss';
        }
        $sql .= " ORDER BY kw.weight DESC, kb.usage_count DESC LIMIT 100";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt && !empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $knowledge = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            return $knowledge;
        } elseif ($stmt) {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $knowledge = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            return $knowledge;
        }
        return [];
    }

    // Exponer findSimilarQuestions públicamente
    public function findSimilarQuestionsPublic($q, $limit=5) { return $this->findSimilarQuestions($q,$limit); }
    public function getAdvancedAnalytics($days = 7) {
        $analytics = [];
        $sql = "SELECT COUNT(*) as total_messages, COUNT(DISTINCT user_id) as unique_users, AVG(response_time_ms) as avg_response_time
                FROM conversation_analytics WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $days);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $analytics['general'] = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        $sql = "SELECT topic_detected, COUNT(*) as count FROM conversation_analytics WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND topic_detected IS NOT NULL GROUP BY topic_detected ORDER BY count DESC LIMIT 10";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $days);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $analytics['top_topics'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        $sql = "SELECT (SELECT COUNT(*) FROM conversation_feedback WHERE rating >= 4) as positive_feedback, (SELECT COUNT(*) FROM conversation_feedback) as total_feedback, (SELECT COUNT(*) FROM knowledge_base WHERE is_active = 1) as knowledge_count, (SELECT COUNT(*) FROM knowledge_weights WHERE weight > 1.0) as high_quality_knowledge";
        $result = mysqli_query($this->conexion, $sql);
        $accuracyData = mysqli_fetch_assoc($result);
        $analytics['accuracy'] = $accuracyData['total_feedback'] > 0 ? $accuracyData['positive_feedback'] / $accuracyData['total_feedback'] : 0.85;
        $analytics['knowledge_stats'] = $accuracyData;
        return $analytics;
    }
}

// ----------------- Punto de entrada API -----------------
$chatbot = new AdvancedChatbotML($conexion, $API_KEY);

// Leer request (POST JSON preferido)
$request_data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
    $input = file_get_contents('php://input');
    dbg_log("Input JSON detectado: " . $input);
    $request_data = json_decode($input, true) ?? [];
} else {
    dbg_log("Input POST/traditional detectado.");
    // merge _POST/_GET into request_data for convenience
    $request_data = $_POST + $request_data;
}

// Determinar acción
$action = $request_data['action'] ?? ($_POST['action'] ?? $_GET['action'] ?? 'create');

try {
    switch ($action) {
        case 'chat':
        case 'create':
            $sender = $request_data['sender'] ?? ($_POST['sender'] ?? 'Usuario');
            $message = $request_data['message'] ?? ($_POST['message'] ?? '');
            $userId = isset($request_data['user_id']) ? intval($request_data['user_id']) : (isset($_POST['user_id']) ? intval($_POST['user_id']) : null);

            if (strcasecmp($sender,'Bot')===0) $sender = 'Usuario';
            if (mb_strlen($message, 'UTF-8') > 5000) $message = mb_substr($message,0,5000,'UTF-8');

            // Insertar mensaje
            $sql = "INSERT INTO messages (sender, message, created_at) VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($conexion, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt,"ss",$sender,$message);
                mysqli_stmt_execute($stmt);
                $insertId = mysqli_insert_id($conexion);
                mysqli_stmt_close($stmt);
            } else {
                dbg_log("Insert message prepare failed: " . mysqli_error($conexion));
                echo json_encode(['success'=>false,'error'=>'Error al insertar mensaje'], JSON_UNESCAPED_UNICODE); exit;
            }

            if (strcasecmp($sender,'Bot')!==0) {
                $botResponse = $chatbot->generateResponse($message,$userId);
                $botText = is_array($botResponse) ? ($botResponse['text'] ?? "Lo siento, no pude generar una respuesta ahora.") : (string)$botResponse;
                if (trim($botText) === '') $botText = "Lo siento, no tengo una respuesta clara en este momento.";

                // Insertar respuesta bot
                $sql2 = "INSERT INTO messages (sender, message, created_at) VALUES ('Bot', ?, NOW())";
                $stmt2 = mysqli_prepare($conexion,$sql2);
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2,"s",$botText);
                    mysqli_stmt_execute($stmt2);
                    $botId = mysqli_insert_id($conexion);
                    mysqli_stmt_close($stmt2);
                } else {
                    $botId = null;
                }

                // Log user behavior with response_metadata if available
                $chatbot->logUserBehavior($userId, 'chat_response', [
                    'query' => $message,
                    'response' => $botText,
                    'response_metadata' => is_array($botResponse) ? ($botResponse['response_metadata'] ?? []) : []
                ]);

                $out = ['success'=>true,'id'=>$insertId,'bot_id'=>$botId??null,'bot_message'=>$botText];
                if (is_array($botResponse)) {
                    $out['response_metadata'] = $botResponse['response_metadata'] ?? [];
                    if (isset($botResponse['kb_record'])) $out['kb_record'] = $botResponse['kb_record'];
                }
                echo json_encode($out, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                echo json_encode(['success'=>true,'id'=>$insertId], JSON_UNESCAPED_UNICODE);
                exit;
            }
            break;

        case 'train':
            $required = ['category', 'question', 'answer'];
            foreach ($required as $r) {
                if (empty($request_data[$r] ?? $_POST[$r] ?? null)) {
                    echo json_encode(['success' => false, 'error' => "Falta el parámetro: $r"]); exit;
                }
            }
            $data = [
                'category' => $request_data['category'] ?? $_POST['category'],
                'subcategory' => $request_data['subcategory'] ?? ($_POST['subcategory'] ?? ''),
                'question' => $request_data['question'] ?? $_POST['question'],
                'answer' => $request_data['answer'] ?? $_POST['answer'],
                'keywords' => $request_data['keywords'] ?? ($_POST['keywords'] ?? ''),
                'topic_key' => $request_data['topic_key'] ?? ($_POST['topic_key'] ?? ''),
                'confidence_threshold' => floatval($request_data['confidence_threshold'] ?? ($_POST['confidence_threshold'] ?? 0.7))
            ];
            $sql = "INSERT INTO knowledge_base (category, subcategory, question, answer, keywords, topic_key, confidence_threshold, usage_count, is_active, created_by, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 0, 1, 'manual', NOW(), NOW())";
            $stmt = mysqli_prepare($conexion, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ssssssd', $data['category'], $data['subcategory'], $data['question'], $data['answer'], $data['keywords'], $data['topic_key'], $data['confidence_threshold']);
                $result = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else $result = false;
            echo json_encode(['success' => $result], JSON_UNESCAPED_UNICODE);
            break;

        case 'get_similar':
            $query = $request_data['query'] ?? $_POST['query'] ?? $_GET['query'] ?? '';
            if (empty($query)) { echo json_encode(['success' => false, 'error' => 'Falta el parámetro query']); exit; }
            $similar = $chatbot->findSimilarQuestionsPublic($query, 10);
            echo json_encode(['success' => true, 'similar' => $similar], JSON_UNESCAPED_UNICODE);
            break;

        case 'analytics':
            $days = intval($request_data['days'] ?? $_POST['days'] ?? $_GET['days'] ?? 7);
            $analytics = $chatbot->getAdvancedAnalytics($days);
            echo json_encode(['success' => true, 'analytics' => $analytics], JSON_UNESCAPED_UNICODE);
            break;

        case 'export_knowledge':
            $format = $request_data['format'] ?? $_POST['format'] ?? $_GET['format'] ?? 'json';
            $knowledge = $chatbot->exportKnowledge($format);
            echo json_encode(['success' => true, 'data' => $knowledge], JSON_UNESCAPED_UNICODE);
            break;

        case 'get_knowledge':
            $filters = ['category'=>$request_data['category'] ?? $_POST['category'] ?? $_GET['category'] ?? '', 'search'=>$request_data['search'] ?? $_POST['search'] ?? $_GET['search'] ?? ''];
            $knowledge = $chatbot->searchKnowledge($filters);
            echo json_encode(['success'=>true,'knowledge'=>$knowledge], JSON_UNESCAPED_UNICODE);
            break;

        case 'train_model':
            $result = $chatbot->trainMLModel();
            echo json_encode(['success'=>$result], JSON_UNESCAPED_UNICODE);
            break;

        case 'save_feedback':
            $messageId = intval($request_data['message_id'] ?? $_POST['message_id'] ?? 0);
            $rating = intval($request_data['rating'] ?? $_POST['rating'] ?? 0);
            $feedbackType = $request_data['feedback_type'] ?? $_POST['feedback_type'] ?? 'rating';
            $userId = intval($request_data['user_id'] ?? $_POST['user_id'] ?? 0);
            $sql = "INSERT INTO conversation_feedback (message_id, user_id, rating, feedback_text, feedback_type, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($conexion, $sql);
            $feedbackText = $request_data['feedback_text'] ?? $_POST['feedback_text'] ?? '';
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'iiiss', $messageId, $userId, $rating, $feedbackText, $feedbackType);
                $result = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else $result = false;
            echo json_encode(['success'=>$result], JSON_UNESCAPED_UNICODE);
            break;

        case 'read':
            $sql = "SELECT id, sender, message, created_at FROM messages ORDER BY created_at ASC";
            $result = mysqli_query($conexion,$sql);
            if ($result) {
                $messages=[]; while ($row = mysqli_fetch_assoc($result)) $messages[]=$row; mysqli_free_result($result);
                echo json_encode(['success'=>true,'messages'=>$messages], JSON_UNESCAPED_UNICODE);
            } else echo json_encode(['success'=>false,'error'=>'Error al leer mensajes: '.mysqli_error($conexion)], JSON_UNESCAPED_UNICODE);
            break;

        case 'update':
            $id = intval($request_data['id'] ?? $_POST['id'] ?? 0);
            $message = $request_data['message'] ?? $_POST['message'] ?? '';
            $sql = "UPDATE messages SET message = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexion,$sql);
            if ($stmt) { mysqli_stmt_bind_param($stmt,"si",$message,$id); $res = mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); echo json_encode(['success'=>$res], JSON_UNESCAPED_UNICODE); }
            else echo json_encode(['success'=>false,'error'=>'Error preparing update'], JSON_UNESCAPED_UNICODE);
            break;

        case 'delete':
            $id = intval($request_data['id'] ?? $_POST['id'] ?? 0);
            $sql = "DELETE FROM messages WHERE id = ?";
            $stmt = mysqli_prepare($conexion,$sql);
            if ($stmt) { mysqli_stmt_bind_param($stmt,"i",$id); $res = mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); echo json_encode(['success'=>$res], JSON_UNESCAPED_UNICODE); }
            else echo json_encode(['success'=>false,'error'=>'Error preparing delete'], JSON_UNESCAPED_UNICODE);
            break;

        default:
            echo json_encode(['success'=>false,'error'=>'Acción no válida: ' . htmlspecialchars($action)], JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception $e) {
    dbg_log("EXCEPTION: ".$e->getMessage());
    echo json_encode(['success'=>false,'error'=>'Error en servidor: '.$e->getMessage()], JSON_UNESCAPED_UNICODE);
}

mysqli_close($conexion);