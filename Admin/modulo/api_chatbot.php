<?php
// api_chatbot_advanced.php - Sistema de IA Avanzado con Machine Learning
define('DEBUG', true);



// Buscar la API key (prioriza GEMINI_API_KEY, pero acepta OPENAI_API_KEY para compatibilidad)
$OPENAI_API_KEY = getenv('GEMINI_API_KEY') ?: (getenv('OPENAI_API_KEY') ?: (isset($OPENAI_API_KEY) ? $OPENAI_API_KEY : null));

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
    echo json_encode(['success' => false, 'error' => 'No se pudo cargar la conexión MySQLi'], JSON_UNESCAPED_UNICODE);
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

// -----------------------
// Clase AdvancedChatbotML
// -----------------------
class AdvancedChatbotML {
    private $conexion;
    private $openai_api_key; // mantiene nombre por compatibilidad
    private $gemini_api_key;
    private $context_memory = [];
    private $learning_rate = 0.1;
    private $ml_models = [];

    public function __construct($conexion, $openai_key = null) {
        $this->conexion = $conexion;
        $this->openai_api_key = $openai_key;
        $this->gemini_api_key = $openai_key; // alias: usamos la misma clave para Gemini
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

    /**
     * Crear tablas para ML si no existen
     */
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

    /**
     * Cargar modelos de ML
     */
    private function loadMLModels() {
        // Cargar modelos desde la base de datos
        $sql = "SELECT * FROM ml_models WHERE is_active = 1";
        $result = mysqli_query($this->conexion, $sql);
        
        if ($result) {
            $this->ml_models = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
        }
    }

    /**
     * Cargar contexto del usuario
     */
    private function loadUserContext() {
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
     * Generar respuesta con sistema de scoring avanzado
     */
    public function generateResponse($userMessage, $userId = null) {
        $startTime = microtime(true);
        $original = (string)$userMessage;
        $processed = $this->preprocessMessage($original);

        dbg_log("ML generateResponse: \"".substr($original,0,200)."\"");

        // 1. Sistema de scoring múltiple
        $candidates = $this->getResponseCandidates($processed, $original);
        
        // 2. Aplicar machine learning para ranking
        $rankedCandidates = $this->rankCandidatesWithML($candidates, $processed, $userId);
        
        // 3. Seleccionar mejor candidato
        $bestCandidate = $this->selectBestCandidate($rankedCandidates);
        
        // 4. Si no hay buenos candidatos, usar generación
        if (!$bestCandidate || $bestCandidate['final_score'] < 0.3) {
            $generatedResponse = $this->generateWithAI($original);
            if ($generatedResponse) {
                $bestCandidate = [
                    'text' => $generatedResponse,
                    'source' => 'ai_generation',
                    'final_score' => 0.5,
                    'kb_record' => null
                ];
            }
        }

        // 5. Fallback inteligente
        if (!$bestCandidate) {
            $bestCandidate = [
                'text' => $this->getIntelligentDefault($original),
                'source' => 'fallback',
                'final_score' => 0.1,
                'kb_record' => null
            ];
        }

        // 6. Aprender de la interacción
        $this->learnFromInteraction($original, $bestCandidate, $userId);

        // 7. Log de performance
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

        // 2. Búsqueda por similitud semántica
        $semanticCandidates = $this->semanticSearch($originalMessage);
        $candidates = array_merge($candidates, $semanticCandidates);

        // 3. Respuestas predefinidas con ML
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
            // Búsqueda FULLTEXT si existe
            $sql = "SELECT kb.*, 
                           COALESCE(kw.weight, 1.0) as ml_weight,
                           (MATCH(kb.ft_text) AGAINST(? IN BOOLEAN MODE) * 0.6 + 
                            COALESCE(kw.weight, 1.0) * 0.4) as relevance_score
                    FROM knowledge_base kb
                    LEFT JOIN knowledge_weights kw ON kb.id = kw.knowledge_id
                    WHERE kb.is_active = 1 
                    AND MATCH(kb.ft_text) AGAINST(? IN BOOLEAN MODE)
                    ORDER BY relevance_score DESC
                    LIMIT ?";
        } else {
            // Búsqueda LIKE como fallback
            $sql = "SELECT kb.*, 
                           COALESCE(kw.weight, 1.0) as ml_weight,
                           0.5 as relevance_score
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
                    'base_score' => $row['relevance_score'],
                    'kb_record' => $row,
                    'ml_weight' => $row['ml_weight']
                ];
            }
            mysqli_stmt_close($stmt);
        }

        return $candidates;
    }

    /**
     * Búsqueda semántica mejorada
     */
    private function semanticSearch($query) {
        $candidates = [];
        
        // Usar embeddings o técnicas de similitud
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

    /**
     * Encontrar preguntas similares
     */
    private function findSimilarQuestions($query, $limit = 5) {
        $similar = [];
        
        // Implementar algoritmo de similitud simple
        $sql = "SELECT kb.* FROM knowledge_base kb WHERE kb.is_active = 1 LIMIT ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $limit);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            while ($row = mysqli_fetch_assoc($result)) {
                // Calcular similitud simple
                $similarity = $this->calculateSimilarity($query, $row['question']);
                if ($similarity > 0.3) {
                    $row['similarity_score'] = $similarity;
                    $similar[] = $row;
                }
            }
            mysqli_stmt_close($stmt);
        }

        // Ordenar por similitud
        usort($similar, function($a, $b) {
            return $b['similarity_score'] <=> $a['similarity_score'];
        });

        return array_slice($similar, 0, $limit);
    }

    /**
     * Calcular similitud entre textos
     */
    private function calculateSimilarity($text1, $text2) {
        $words1 = array_count_values(str_word_count(mb_strtolower($text1), 1));
        $words2 = array_count_values(str_word_count(mb_strtolower($text2), 1));
        
        $intersection = array_intersect_key($words1, $words2);
        $union = $words1 + $words2;
        
        if (count($union) === 0) return 0;
        
        return count($intersection) / count($union);
    }

    /**
     * Respuestas predefinidas con ML
     */
    private function getPredefinedResponsesML($processedMessage) {
        $candidates = [];
        
        // Detectar intenciones básicas
        $intent = $this->detectIntention($processedMessage, [
            'greeting' => ['hola', 'buenos', 'buenas', 'hey', 'saludos', 'como estas'],
            'farewell' => ['adios', 'bye', 'chao', 'hasta', 'nos vemos'],
            'thanks' => ['gracias', 'agradecido', 'agradecida', 'merci'],
            'help' => ['ayuda', 'help', 'socorro', 'asistencia']
        ]);

        if ($intent === 'greeting') {
            $candidates[] = [
                'text' => $this->getPersonalizedGreeting(),
                'source' => 'predefined',
                'base_score' => 0.9,
                'kb_record' => null
            ];
        } elseif ($intent === 'farewell') {
            $candidates[] = [
                'text' => $this->getPersonalizedFarewell(),
                'source' => 'predefined',
                'base_score' => 0.9,
                'kb_record' => null
            ];
        } elseif ($intent === 'thanks') {
            $candidates[] = [
                'text' => '¡De nada! Estoy aquí para ayudarte. ¿Hay algo más en lo que pueda asistirte?',
                'source' => 'predefined',
                'base_score' => 0.9,
                'kb_record' => null
            ];
        } elseif ($intent === 'help') {
            $candidates[] = [
                'text' => 'Puedo ayudarte con información sobre salud sexual, anticonceptivos, ITS, consentimiento y más. ¿Qué tema te interesa?',
                'source' => 'predefined',
                'base_score' => 0.9,
                'kb_record' => null
            ];
        }

        return $candidates;
    }

    /**
     * Detectar intención
     */
    private function detectIntention($message, $intentions) {
        $best = null;
        $bestScore = 0;
        
        foreach ($intentions as $intent => $keywords) {
            $score = 0;
            foreach ($keywords as $kw) {
                if (mb_stripos($message, $kw) !== false) {
                    $score += mb_strlen($kw, 'UTF-8');
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $intent;
            }
        }
        
        return $bestScore > 0 ? $best : null;
    }

    /**
     * Ranking de candidatos con ML
     */
    private function rankCandidatesWithML($candidates, $query, $userId) {
        foreach ($candidates as &$candidate) {
            $finalScore = $candidate['base_score'];
            
            // Ajustar score por historial de usuario
            $userPreferenceScore = $this->getUserPreferenceScore($userId, $candidate);
            $finalScore *= (1 + $userPreferenceScore);
            
            // Ajustar por feedback histórico
            $feedbackScore = $this->getFeedbackScore($candidate);
            $finalScore *= (1 + $feedbackScore);
            
            // Ajustar por contexto de conversación
            $contextScore = $this->getContextScore($query, $candidate);
            $finalScore *= (1 + $contextScore);
            
            $candidate['final_score'] = min(1.0, $finalScore);
        }

        // Ordenar por score final
        usort($candidates, function($a, $b) {
            return $b['final_score'] <=> $a['final_score'];
        });

        return $candidates;
    }

    /**
     * Obtener score de preferencias de usuario
     */
    private function getUserPreferenceScore($userId, $candidate) {
        if (!$userId) return 0;
        
        $sql = "SELECT AVG(rating) as avg_rating 
                FROM conversation_feedback 
                WHERE user_id = ?";
        
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            if ($row && $row['avg_rating']) {
                return ($row['avg_rating'] - 3) / 10; // Normalizar a -0.2 a +0.2
            }
        }
        
        return 0;
    }

    /**
     * Obtener score de feedback
     */
    private function getFeedbackScore($candidate) {
        if (!isset($candidate['kb_record']['id'])) return 0;
        
        $knowledgeId = $candidate['kb_record']['id'];
        $sql = "SELECT 
                    (success_count / GREATEST(usage_count, 1)) as success_rate
                FROM knowledge_weights 
                WHERE knowledge_id = ?";
        
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $knowledgeId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            if ($row && $row['success_rate']) {
                return ($row['success_rate'] - 0.5) * 0.4; // -0.2 a +0.2
            }
        }
        
        return 0;
    }

    /**
     * Obtener score de contexto
     */
    private function getContextScore($query, $candidate) {
        // Análisis simple del contexto basado en palabras clave coincidentes
        $queryWords = array_filter(explode(' ', mb_strtolower($query)));
        $responseWords = array_filter(explode(' ', mb_strtolower($candidate['text'])));
        
        $commonWords = array_intersect($queryWords, $responseWords);
        $score = count($commonWords) / max(1, count($queryWords)) * 0.3;
        
        return $score;
    }

    /**
     * Seleccionar mejor candidato
     */
    private function selectBestCandidate($candidates) {
        foreach ($candidates as $candidate) {
            if ($candidate['final_score'] >= 0.3) {
                return $candidate;
            }
        }
        return $candidates[0] ?? null;
    }

    /**
     * Generar con IA externa (AHORA: Gemini / Generative Language API)
     *
     * Uso del endpoint REST: POST https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent
     * Se envía un payload con "contents" -> "parts" -> "text". La API key se pasa en la cabecera 'x-goog-api-key'.
     * (Basado en la documentación oficial de Gemini / Google Generative AI).
     */
    private function generateWithAI($message) {
        if (!$this->gemini_api_key) return null;
        
        // Simple safety check on input
        if ($this->isUnsafe($message)) {
            dbg_log("Input marcado como peligroso por isUnsafe()");
            return null;
        }

        $context = $this->buildContextForAI();
        $system = "Eres SEIN, un asistente virtual especializado en educación sexual y salud reproductiva. Responde de manera profesional, empática y educativa. Si la pregunta no está relacionada con tu área, redirige amablemente. Sé conciso y claro.";

        // Construimos el prompt como un único bloque de texto
        $full_prompt = $system . "\n\nContexto:\n" . $context . "\n\nUsuario: " . $message;

        // Modelo recomendado (puedes cambiar a otro disponible)
        $model = "gemini-2.5-flash";

        // Endpoint REST (v1beta tal como en la documentación de ejemplo)
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $full_prompt]
                    ]
                ]
            ],
            // puedes añadir opciones adicionales aquí según necesites (temperature, maxOutputTokens, safetySettings, etc.)
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        $headers = [
            'Content-Type: application/json',
            'x-goog-api-key: ' . $this->gemini_api_key
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        dbg_log("GEMINI HTTP $httpCode " . substr($response ?? '',0,1000));
        if ($curlErr) dbg_log("GEMINI ERR $curlErr");

        if ($httpCode === 200 && $response) {
            $decoded = json_decode($response, true);
            // Estructura típica: candidates[0].content.parts[0].text
            $generated = null;
            if (isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                $generated = $decoded['candidates'][0]['content']['parts'][0]['text'];
            } elseif (isset($decoded['candidates'][0]['content'][0]['text'])) {
                $generated = $decoded['candidates'][0]['content'][0]['text'];
            } elseif (isset($decoded['candidates'][0]['content'])) {
                // tratar de concatenar partes si vienen en otro formato
                $parts = $decoded['candidates'][0]['content'];
                if (is_array($parts)) {
                    $txtPieces = [];
                    foreach ($parts as $p) {
                        if (is_array($p) && isset($p['parts'])) {
                            foreach ($p['parts'] as $pp) {
                                if (isset($pp['text'])) $txtPieces[] = $pp['text'];
                            }
                        } elseif (isset($p['text'])) {
                            $txtPieces[] = $p['text'];
                        }
                    }
                    if (!empty($txtPieces)) $generated = implode("\n", $txtPieces);
                }
            }

            if ($generated) {
                $generated = trim($generated);
                // filtro de seguridad sobre la salida del modelo
                if ($this->isUnsafe($generated)) {
                    dbg_log("Gemini generó contenido marcado como inseguro; se descarta respuesta.");
                    return null;
                }
                return $generated;
            } else {
                dbg_log("Gemini: No se encontró campo de texto esperado en la respuesta.");
            }
        } else {
            dbg_log("Gemini call failed HTTP={$httpCode} response=" . substr($response ?? '',0,200));
        }

        return null;
    }

    private function buildContextForAI() {
        $context = "";
        $recent = array_slice($this->context_memory, -6);
        foreach ($recent as $m) $context .= ($m['sender'] ?? 'User').": ".($m['message'] ?? '')."\n";
        return $context;
    }

    /**
     * Sistema de aprendizaje automático
     */
    private function learnFromInteraction($userQuery, $selectedResponse, $userId) {
        // Actualizar pesos basado en esta interacción
        if (isset($selectedResponse['kb_record']['id'])) {
            $this->updateKnowledgeWeight($selectedResponse['kb_record']['id'], true);
        }
        
        // Aprender patrones de consulta
        $this->learnQueryPatterns($userQuery, $selectedResponse['source']);
        
        // Entrenar modelo periódicamente
        if (mt_rand(1, 100) <= 10) { // 10% de probabilidad
            $this->trainMLModel();
        }
    }

    /**
     * Actualizar peso del conocimiento
     */
    private function updateKnowledgeWeight($knowledgeId, $success) {
        // Primero verificar si existe
        $checkSql = "SELECT id FROM knowledge_weights WHERE knowledge_id = ?";
        $checkStmt = mysqli_prepare($this->conexion, $checkSql);
        mysqli_stmt_bind_param($checkStmt, 'i', $knowledgeId);
        mysqli_stmt_execute($checkStmt);
        $exists = mysqli_stmt_get_result($checkStmt)->num_rows > 0;
        mysqli_stmt_close($checkStmt);

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
            mysqli_stmt_bind_param($stmt, 'iidi', $successIncrement, $failureIncrement, $weightAdjustment, $knowledgeId);
        } else {
            $sql = "INSERT INTO knowledge_weights (knowledge_id, topic_key, weight, usage_count, success_count, failure_count, last_used)
                    VALUES (?, ?, 1.0, 1, ?, ?, NOW())";
            $stmt = mysqli_prepare($this->conexion, $sql);
            mysqli_stmt_bind_param($stmt, 'isii', $knowledgeId, $topicKey, $successIncrement, $failureIncrement);
        }
        
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    /**
     * Extraer clave de tema
     */
    private function extractTopicKey($knowledgeId) {
        $sql = "SELECT topic_key FROM knowledge_base WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $knowledgeId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        return $row['topic_key'] ?? 'general';
    }

    /**
     * Aprender patrones de consulta
     */
    private function learnQueryPatterns($query, $source) {
        // Implementación básica para aprender patrones
        // Podría almacenar consultas exitosas para mejorar matching futuro
    }

    /**
     * Entrenar modelo de ML
     */
    public function trainMLModel() {
        // Recalcular pesos basado en feedback histórico
        $sql = "UPDATE knowledge_weights kw
                JOIN (
                    SELECT knowledge_id, 
                           (success_count / GREATEST(usage_count, 1)) as success_rate
                    FROM knowledge_weights
                    WHERE usage_count > 0
                ) stats ON kw.knowledge_id = stats.knowledge_id
                SET kw.weight = 0.5 + (stats.success_rate * 0.5)";
        
        mysqli_query($this->conexion, $sql);
        
        // Log del entrenamiento
        dbg_log("ML Model trained: " . mysqli_affected_rows($this->conexion) . " weights updated");
        
        return true;
    }

    /**
     * Log de comportamiento de usuario
     */
    public function logUserBehavior($userId, $actionType, $actionData) {
        $sql = "INSERT INTO user_behavior (user_id, session_id, action_type, action_data, created_at)
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            $sessionId = session_id();
            $actionDataJson = json_encode($actionData);
            mysqli_stmt_bind_param($stmt, 'isss', $userId, $sessionId, $actionType, $actionDataJson);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    /**
     * Obtener analytics avanzadas
     */
    public function getAdvancedAnalytics($days = 7) {
        $analytics = [];
        
        // Métricas generales
        $sql = "SELECT 
                    COUNT(*) as total_messages,
                    COUNT(DISTINCT user_id) as unique_users,
                    AVG(response_time_ms) as avg_response_time
                FROM conversation_analytics 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
        
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $days);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $analytics['general'] = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        // Temas más populares
        $sql = "SELECT topic_detected, COUNT(*) as count 
                FROM conversation_analytics 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
                AND topic_detected IS NOT NULL
                GROUP BY topic_detected 
                ORDER BY count DESC 
                LIMIT 10";
        
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $days);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $analytics['top_topics'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        // Precisión del sistema
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM conversation_feedback WHERE rating >= 4) as positive_feedback,
                    (SELECT COUNT(*) FROM conversation_feedback) as total_feedback,
                    (SELECT COUNT(*) FROM knowledge_base WHERE is_active = 1) as knowledge_count,
                    (SELECT COUNT(*) FROM knowledge_weights WHERE weight > 1.0) as high_quality_knowledge";
        
        $result = mysqli_query($this->conexion, $sql);
        $accuracyData = mysqli_fetch_assoc($result);
        
        $analytics['accuracy'] = $accuracyData['total_feedback'] > 0 ? 
            $accuracyData['positive_feedback'] / $accuracyData['total_feedback'] : 0.85;
        
        $analytics['knowledge_stats'] = $accuracyData;

        return $analytics;
    }

    /**
     * Exportar base de conocimiento
     */
    public function exportKnowledge($format = 'json') {
        $sql = "SELECT kb.*, kw.weight, kw.usage_count, kw.success_count
                FROM knowledge_base kb
                LEFT JOIN knowledge_weights kw ON kb.id = kw.knowledge_id
                WHERE kb.is_active = 1
                ORDER BY kw.weight DESC, kb.usage_count DESC";
        
        $result = mysqli_query($this->conexion, $sql);
        $knowledge = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $knowledge[] = $row;
        }

        return $knowledge;
    }

    /**
     * Buscar conocimiento con filtros
     */
    public function searchKnowledge($filters = []) {
        $sql = "SELECT kb.*, COALESCE(kw.weight, 1.0) as ml_weight
                FROM knowledge_base kb
                LEFT JOIN knowledge_weights kw ON kb.id = kw.knowledge_id
                WHERE kb.is_active = 1";
        
        $params = [];
        $types = '';
        
        if (!empty($filters['category'])) {
            $sql .= " AND kb.category = ?";
            $params[] = $filters['category'];
            $types .= 's';
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (kb.question LIKE ? OR kb.answer LIKE ? OR kb.keywords LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'sss';
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

    // Métodos de utilidad existentes
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

    /**
     * Seguridad: filtrado simple (entrada/salida)
     */
    private function isUnsafe($text) {
        if (!$text) return false;
        $t = mb_strtolower($text, 'UTF-8');
        $blacklist = [
            'suicid', 'matar', 'explos', 'bomba', 'bombas', 'drogas', 'cianuro', 'peligro', 'porn', 'abuso', 'violar'
        ];
        foreach ($blacklist as $b) {
            if (mb_stripos($t, $b) !== false) return true;
        }
        return false;
    }
}

// -----------------
// Instanciar chatbot ML (se pasa la API key GEMINI_API_KEY si está configurada)
$chatbot = new AdvancedChatbotML($conexion, $OPENAI_API_KEY);

// Manejar acciones API extendidas
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'chat':
        case 'create':
            // Chat normal con IA mejorada
            $sender = $_POST['sender'] ?? '';
            $message = $_POST['message'] ?? '';
            $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

            if (strcasecmp($sender,'Bot')===0) $sender = 'Usuario';

            if (mb_strlen($message, 'UTF-8') > 5000) $message = mb_substr($message,0,5000,'UTF-8');

            // Insertar mensaje usuario
            $sql = "INSERT INTO messages (sender, message, created_at) VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt,"ss",$sender,$message);
            $res = mysqli_stmt_execute($stmt);
            $insertId = $res ? mysqli_insert_id($conexion) : null;
            mysqli_stmt_close($stmt);

            if (!$insertId) {
                echo json_encode(['success'=>false,'error'=>'Error al insertar mensaje'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Generar respuesta con ML
            if (strcasecmp($sender,'Bot')!==0) {
                $botResponse = $chatbot->generateResponse($message,$userId);
                
                $botText = is_array($botResponse) ? 
                    ($botResponse['text'] ?? "Lo siento, no pude generar una respuesta ahora.") : 
                    (string)$botResponse;

                if (trim($botText) === '') $botText = "Lo siento, no tengo una respuesta clara en este momento.";

                // Insertar respuesta del bot
                $sql2 = "INSERT INTO messages (sender, message, created_at) VALUES ('Bot', ?, NOW())";
                $stmt2 = mysqli_prepare($conexion,$sql2);
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2,"s",$botText);
                    mysqli_stmt_execute($stmt2);
                    $botId = mysqli_insert_id($conexion);
                    mysqli_stmt_close($stmt2);
                }

                // Analytics
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
            }
            break;

        case 'train':
            // Entrenamiento manual
            $required = ['category', 'question', 'answer'];
            foreach ($required as $r) {
                if (empty($_POST[$r])) {
                    echo json_encode(['success' => false, 'error' => "Falta el parámetro: $r"]);
                    exit;
                }
            }

            $data = [
                'category' => $_POST['category'],
                'subcategory' => $_POST['subcategory'] ?? '',
                'question' => $_POST['question'],
                'answer' => $_POST['answer'],
                'keywords' => $_POST['keywords'] ?? '',
                'topic_key' => $_POST['topic_key'] ?? '',
                'confidence_threshold' => floatval($_POST['confidence_threshold'] ?? 0.7)
            ];

            // Insertar en knowledge_base
            $sql = "INSERT INTO knowledge_base (category, subcategory, question, answer, keywords, topic_key, confidence_threshold, usage_count, is_active, created_by, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 0, 1, 'manual', NOW(), NOW())";
            
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, 'ssssssd', 
                $data['category'], $data['subcategory'], $data['question'], $data['answer'],
                $data['keywords'], $data['topic_key'], $data['confidence_threshold']);
            
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo json_encode(['success' => $result], JSON_UNESCAPED_UNICODE);
            break;

        case 'get_similar':
            // Búsqueda de preguntas similares
            $query = $_POST['query'] ?? $_GET['query'] ?? '';
            if (empty($query)) {
                echo json_encode(['success' => false, 'error' => 'Falta el parámetro query']);
                exit;
            }

            $similar = $chatbot->findSimilarQuestions($query, 10);
            echo json_encode(['success' => true, 'similar' => $similar]);
            break;

        case 'analytics':
            // Analytics avanzadas
            $days = intval($_POST['days'] ?? $_GET['days'] ?? 7);
            $analytics = $chatbot->getAdvancedAnalytics($days);
            echo json_encode(['success' => true, 'analytics' => $analytics]);
            break;

        case 'export_knowledge':
            // Exportar conocimiento
            $format = $_POST['format'] ?? $_GET['format'] ?? 'json';
            $knowledge = $chatbot->exportKnowledge($format);
            echo json_encode(['success' => true, 'data' => $knowledge]);
            break;

        case 'get_knowledge':
            // Obtener conocimiento con filtros
            $filters = [
                'category' => $_POST['category'] ?? $_GET['category'] ?? '',
                'search' => $_POST['search'] ?? $_GET['search'] ?? ''
            ];
            $knowledge = $chatbot->searchKnowledge($filters);
            echo json_encode(['success' => true, 'knowledge' => $knowledge]);
            break;

        case 'train_model':
            // Entrenar modelo ML
            $result = $chatbot->trainMLModel();
            echo json_encode(['success' => $result]);
            break;

        case 'save_feedback':
            // Guardar feedback
            $messageId = intval($_POST['message_id'] ?? 0);
            $rating = intval($_POST['rating'] ?? 0);
            $feedbackType = $_POST['feedback_type'] ?? 'rating';
            $userId = intval($_POST['user_id'] ?? 0);

            $sql = "INSERT INTO conversation_feedback (message_id, user_id, rating, feedback_text, feedback_type, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($conexion, $sql);
            $feedbackText = $_POST['feedback_text'] ?? '';
            mysqli_stmt_bind_param($stmt, 'iiiss', $messageId, $userId, $rating, $feedbackText, $feedbackType);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo json_encode(['success' => $result]);
            break;

        // Mantener casos existentes (read, update, delete)...
        case 'read':
            $sql = "SELECT id, sender, message, created_at FROM messages ORDER BY created_at ASC";
            $result = mysqli_query($conexion,$sql);
            if ($result) {
                $messages=[];
                while ($row = mysqli_fetch_assoc($result)) $messages[]=$row;
                mysqli_free_result($result);
                echo json_encode(['success'=>true,'messages'=>$messages], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['success'=>false,'error'=>'Error al leer mensajes: '.mysqli_error($conexion)], JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'update':
            $id = intval($_POST['id'] ?? 0);
            $message = $_POST['message'] ?? '';
            $sql = "UPDATE messages SET message = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexion,$sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt,"si",$message,$id);
                $res = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                echo json_encode(['success'=>$res], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['success'=>false,'error'=>'Error preparing update'], JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'delete':
            $id = intval($_POST['id'] ?? 0);
            $sql = "DELETE FROM messages WHERE id = ?";
            $stmt = mysqli_prepare($conexion,$sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt,"i",$id);
                $res = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                echo json_encode(['success'=>$res], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['success'=>false,'error'=>'Error preparing delete'], JSON_UNESCAPED_UNICODE);
            }
            break;

        default:
            echo json_encode(['success'=>false,'error'=>'Acción no válida: ' . $action], JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception $e) {
    dbg_log("EXCEPTION: ".$e->getMessage());
    echo json_encode(['success'=>false,'error'=>'Error en servidor: '.$e->getMessage()], JSON_UNESCAPED_UNICODE);
}

mysqli_close($conexion);
?>
