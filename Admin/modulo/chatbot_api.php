<?php
// api_chatbot.php (versión mejorada y segura)
// HAZ BACKUP antes de reemplazar

define('DEBUG', true); // pasar a false en producción

// Preferible: configurar la clave en una variable de entorno y no en el archivo
$OPENAI_API_KEY = getenv('OPENAI_API_KEY') ?: (isset($OPENAI_API_KEY) ? $OPENAI_API_KEY : null);

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar conexión (rutas comunes)
// El archivo conexion.php debe dejar disponible una variable $conexion tipo mysqli
$possible_paths = [
    __DIR__ . '/../conexion.php',
    dirname(__DIR__) . '/../conexion.php',
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

// DEBUG log
$debugLogFile = __DIR__ . '/chatbot_debug.log';
function dbg_log($msg) {
    global $debugLogFile;
    if (defined('DEBUG') && DEBUG) {
        @file_put_contents($debugLogFile, "[".date('Y-m-d H:i:s')."] ".$msg.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
dbg_log("API iniciada. REMOTE=" . ($_SERVER['REMOTE_ADDR'] ?? 'cli'));

// -----------------------
// Clase AdvancedChatbot
// -----------------------
class AdvancedChatbot {
    private $conexion;
    private $openai_api_key;
    private $context_memory = [];

    private $knowledge_base = [ /* mantuve tu KB interna tal cual */ 
        'salud_sexual' => [
            'anticonceptivos' => [
                'preservativo' => 'El preservativo o condón es un método anticonceptivo de barrera que previene embarazos y enfermedades de transmisión sexual (ETS). Tiene una efectividad del 98% cuando se usa correctamente.',
                'pildora' => 'La píldora anticonceptiva es un método hormonal que previene la ovulación. Debe tomarse diariamente a la misma hora y tiene una efectividad del 99% cuando se usa correctamente.',
                'diu' => 'El DIU (Dispositivo Intrauterino) es un método de larga duración que se coloca en el útero. Puede ser hormonal o de cobre, con efectividad superior al 99%.'
            ],
            'its_ets' => [
                'vih' => 'El VIH es un virus que ataca el sistema inmunitario. Se transmite por contacto sexual, sangre infectada o de madre a hijo. La prevención incluye uso de preservativo y pruebas regulares.',
                'sifilis' => 'La sífilis es una ITS bacterial tratable con antibióticos. Los síntomas pueden aparecer en etapas y, sin tratamiento, puede causar complicaciones graves.',
                'gonorrea' => 'La gonorrea es una ITS bacterial que puede infectar genitales, recto y garganta. Es tratable con antibióticos, pero algunas cepas son resistentes.'
            ]
        ],
        'educacion_sexual' => [
            'consentimiento' => 'El consentimiento es la aceptación libre, voluntaria e informada para participar en una actividad sexual. Debe ser claro, puede retirarse en cualquier momento y es fundamental para relaciones saludables.',
            'comunicacion' => 'La comunicación abierta en las relaciones incluye expresar deseos, límites y preocupaciones. Es clave para el bienestar sexual y emocional.',
            'diversidad' => 'La diversidad sexual y de género incluye diferentes orientaciones sexuales, identidades de género y expresiones. Todas son válidas y merecen respeto.'
        ],
        'bienestar_emocional' => [
            'autoestima' => 'La autoestima sexual implica valorarse positivamente y tener confianza en la propia sexualidad. Se desarrolla con autoconocimiento y experiencias positivas.',
            'estres' => 'El estrés puede afectar la respuesta sexual. Técnicas como mindfulness, ejercicio y comunicación pueden ayudar a manejarlo.',
            'relaciones' => 'Las relaciones saludables se basan en respeto mutuo, comunicación abierta, confianza y apoyo emocional.'
        ]
    ];

    private $exact_overrides = [
        'hola' => 'greeting',
        'holaa' => 'greeting',
        'buenos dias' => 'greeting',
        'buenas' => 'greeting',
        'buenas tardes' => 'greeting',
        'que eres' => 'personal_info',
        'quien eres' => 'personal_info',
        'quién eres' => 'personal_info',
        'como te llamas' => 'personal_info',
        'tu nombre' => 'personal_info',
        'como estas' => 'greeting'
    ];

    public function __construct($conexion, $openai_key = null) {
        $this->conexion = $conexion;
        $this->openai_api_key = $openai_key;
        $this->loadUserContext();
    }

    private function loadUserContext() {
        $sql = "SELECT sender, message, created_at FROM messages ORDER BY created_at DESC LIMIT 10";
        $result = mysqli_query($this->conexion, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) $this->context_memory[] = $row;
            $this->context_memory = array_reverse($this->context_memory);
            if ($result) mysqli_free_result($result);
        }
    }

    /**
     * generateResponse:
     * - Puede devolver string (texto) O array ['text'=>..., 'kb_record'=>...]
     */
    public function generateResponse($userMessage, $userId = null) {
        $original = (string)$userMessage;
        $processed = $this->preprocessMessage($original);

        dbg_log("generateResponse original=\"".substr($original,0,200)."\" processed=\"$processed\"");

        // 0) Exact overrides
        if (isset($this->exact_overrides[$processed])) {
            $intent = $this->exact_overrides[$processed];
            if ($intent === 'greeting') return $this->getPersonalizedGreeting();
            if ($intent === 'personal_info') return "Soy SEIN, tu asistente virtual especializado en educación sexual y salud reproductiva. Estoy aquí para ayudarte.";
        }

        // 1) Priorizar KB en BD (SP)
        $dbKb = $this->searchKnowledgeBaseDB($original, 1);
        if ($dbKb) {
            dbg_log("Decision: DB KB response topic=" . ($dbKb['topic_key'] ?? 'n/a') . " score=" . ($dbKb['score'] ?? 'n/a'));
            $resp = $this->formatKBRecordAsResponse($dbKb);
            // devolvemos el record para que la API lo incluya
            return ['text' => $resp['text'], 'kb_record' => $resp['record']];
        }

        // 2) Detección de intención (NLP simple)
        $nlpResponse = $this->getNLPResponse($processed, $original);
        if ($nlpResponse) {
            dbg_log("Decision: NLP response");
            return $nlpResponse;
        }

        // 3) KB local en memoria (fallback local)
        $wordCount = count(array_filter(explode(' ', $processed)));
        $possibleKB = true;
        if ($wordCount < 3) {
            $foundKey=false;
            foreach ($this->knowledge_base as $cat => $topics) {
                foreach ($topics as $topic => $items) {
                    if (is_array($items)) {
                        foreach ($items as $k => $v) {
                            if (mb_strpos($processed, $k) !== false) { $foundKey=true; break 3; }
                        }
                    } else {
                        if (mb_strpos($processed, $topic) !== false) { $foundKey=true; break 2; }
                    }
                }
            }
            if (!$foundKey) $possibleKB = false;
        }

        if ($possibleKB) {
            $kbResp = $this->searchKnowledgeBase($processed);
            if ($kbResp) {
                dbg_log("Decision: KB response (local)");
                return $this->addPersonalization($kbResp, $original);
            }
        } else {
            dbg_log("KB skipped due to short message and no KB keyword");
        }

        // 4) OpenAI (opcional) -> solo si key disponible
        if ($this->openai_api_key) {
            $ai = $this->getOpenAIResponse($original);
            if ($ai) {
                dbg_log("Decision: OpenAI response");
                return $ai;
            } else {
                dbg_log("OpenAI no devolvió respuesta segura/útil");
            }
        }

        // 5) fallback NLP (segunda pasada)
        $nlp2 = $this->getNLPResponse($processed, $original);
        if ($nlp2) return $nlp2;

        // Default inteligente
        return $this->getIntelligentDefault($original);
    }

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

    // ---------------------
    // Búsqueda KB local
    // ---------------------
    private function searchKnowledgeBase($message) {
        $userWords = array_values(array_filter(array_map('trim', explode(' ', $message))));
        $stopwords = [
            'que','como','cuando','donde','por','para','y','o','el','la','los','las',
            'un','una','de','en','es','tu','te','yo','me','mi','si','no','hola','buenos','buenas','gracias'
        ];
        $filtered = [];
        foreach ($userWords as $w) {
            if (mb_strlen($w, 'UTF-8') > 2 && !in_array($w, $stopwords)) $filtered[] = $w;
        }

        if (count($filtered) === 0) {
            dbg_log("KB aborted: no filtered words");
            return null;
        }

        $bestMatch = null;
        $bestScore = 0;
        $bestMatchesCount = 0;

        foreach ($this->knowledge_base as $category => $topics) {
            foreach ($topics as $topic => $items) {
                if (is_array($items)) {
                    foreach ($items as $key => $content) {
                        $text = mb_strtolower($this->removeAccents($key . ' ' . $content), 'UTF-8');
                        $result = $this->kbMatchScore($filtered, $text);
                        if ($result['score'] > $bestScore) {
                            $bestScore = $result['score'];
                            $bestMatch = $content;
                            $bestMatchesCount = $result['matches'];
                        }
                    }
                } else {
                    $text = mb_strtolower($this->removeAccents($topic . ' ' . $items), 'UTF-8');
                    $result = $this->kbMatchScore($filtered, $text);
                    if ($result['score'] > $bestScore) {
                        $bestScore = $result['score'];
                        $bestMatch = $items;
                        $bestMatchesCount = $result['matches'];
                    }
                }
            }
        }

        dbg_log("KB bestScore={$bestScore} matches={$bestMatchesCount} filteredWords=" . implode(',', $filtered));

        if ($bestMatch && $bestMatchesCount >= 2 && $bestScore >= 0.6) {
            return $bestMatch;
        }
        return null;
    }

    private function kbMatchScore($filteredUserWords, $text) {
        $norm = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);
        $contentWords = array_values(array_unique(array_filter(array_map('trim', explode(' ', $norm)))));
        $matches = 0;
        foreach ($filteredUserWords as $uw) {
            if (in_array($uw, $contentWords, true)) $matches++;
        }
        $score = $matches / max(1, count($filteredUserWords));
        return ['score' => $score, 'matches' => $matches];
    }

    // ---------------------
    // Búsqueda KB en BD via SP
    // ---------------------
    private function searchKnowledgeBaseDB($query, $limit = 1) {
        $q = trim((string)$query);
        if ($q === '') return null;

        // Escape seguro y llamada al SP
        $esc = mysqli_real_escape_string($this->conexion, $q);
        $sql = "CALL FindKnowledgeMatches('" . $esc . "', " . intval($limit) . ")";

        $res = mysqli_query($this->conexion, $sql);
        if ($res === false) {
            dbg_log("searchKnowledgeBaseDB FAILED SQL={$sql} ERR=" . mysqli_error($this->conexion));
            // consumir posibles resultados residuales para mantener conexión OK
            while (mysqli_more_results($this->conexion) && mysqli_next_result($this->conexion)) {
                $extra = mysqli_store_result($this->conexion);
                if ($extra) mysqli_free_result($extra);
            }
            return null;
        }

        // Tomar primera fila del resultset (si la hay)
        $row = mysqli_fetch_assoc($res);
        if ($res) mysqli_free_result($res);

        // Consumir cualquier resultset adicional para no dejar la conexión en mal estado
        while (mysqli_more_results($this->conexion) && mysqli_next_result($this->conexion)) {
            $extra = mysqli_store_result($this->conexion);
            if ($extra) mysqli_free_result($extra);
        }

        if ($row) return $row;
        return null;
    }

    private function formatKBRecordAsResponse(array $kbRow) {
        $answer = $kbRow['answer'] ?? '';
        $structured = [
            'topic_key' => $kbRow['topic_key'] ?? null,
            'category' => $kbRow['category'] ?? null,
            'subcategory' => $kbRow['subcategory'] ?? null,
            'question' => $kbRow['question'] ?? null,
            'answer' => $answer,
            'keywords' => $kbRow['keywords'] ?? null,
            'confidence' => $kbRow['confidence_threshold'] ?? null,
            'score' => $kbRow['score'] ?? null
        ];
        return ['text' => $answer, 'record' => $structured];
    }

    // ---------------------
    // OpenAI integration (básica)
    // ---------------------
    private function getOpenAIResponse($userMessage) {
        if (!$this->openai_api_key) return null;

        // Simple safety check on input
        if ($this->isUnsafe($userMessage)) {
            dbg_log("Input marcado como peligroso por isUnsafe()");
            return null;
        }

        $context = $this->buildContextForAI();
        $system = "Eres SEIN, un asistente virtual especializado en educación sexual y salud reproductiva. Responde de manera profesional, empática y educativa. Si la pregunta no está relacionada con tu área, redirige amablemente. Sé conciso y claro.";

        $messages = [
            ['role' => 'system', 'content' => $system . "\n\nContexto:\n" . $context],
            ['role' => 'user', 'content' => $userMessage]
        ];

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 300,
            'temperature' => 0.7
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->openai_api_key
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        dbg_log("OPENAI HTTP $httpCode " . substr($response ?? '',0,1000));
        if ($curlErr) dbg_log("OPENAI ERR $curlErr");

        if ($httpCode === 200 && $response) {
            $decoded = json_decode($response, true);
            $generated = $decoded['choices'][0]['message']['content'] ?? null;
            if ($generated) {
                $generated = trim($generated);
                // filtro de seguridad sobre la salida del modelo
                if ($this->isUnsafe($generated)) {
                    dbg_log("OpenAI generó contenido marcado como inseguro; se descarta respuesta.");
                    return null;
                }
                return $generated;
            }
        }

        return null;
    }

    private function buildContextForAI() {
        $context = "";
        $recent = array_slice($this->context_memory, -6);
        foreach ($recent as $m) $context .= ($m['sender'] ?? 'User').": ".($m['message'] ?? '')."\n";
        return $context;
    }

    // ---------------------
    // Detección de intención simple
    // ---------------------
    private function getNLPResponse($processedMessage, $originalMessage) {
        $intentions = [
            'greeting'=>['hola','buenos','buenas','hey','saludos','como estas'],
            'farewell'=>['adios','bye','chao','hasta','nos vemos'],
            'emergency'=>['emergencia','urgente','ayuda ya','peligro'],
            'personal_info'=>['quien eres','que eres','quién eres','como te llamas','tu nombre']
        ];

        $detected = $this->detectIntention($processedMessage, $intentions);
        dbg_log("detected_intent={$detected} processed={$processedMessage}");

        switch ($detected) {
            case 'greeting': return $this->getPersonalizedGreeting();
            case 'farewell': return $this->getPersonalizedFarewell();
            case 'emergency': return "🚨 Si es una emergencia real, contacta servicios de emergencia inmediatamente.";
            case 'personal_info': return "Soy SEIN, tu asistente virtual especializado en educación sexual y salud reproductiva. Estoy aquí para ayudarte.";
        }

        $lower = mb_strtolower($originalMessage, 'UTF-8');
        if (mb_stripos($lower, 'embarazo') !== false) {
            return "Puedo orientarte sobre signos tempranos, pruebas y cuidados prenatales. ¿Qué te preocupa sobre el embarazo?";
        }

        return null;
    }

    private function detectIntention($message, $intentions) {
        $best=null; $bestScore=0;
        foreach ($intentions as $intent=>$keywords) {
            $score=0;
            foreach ($keywords as $kw) {
                if (mb_strpos($message, $kw) !== false) {
                    $score += mb_strlen($kw,'UTF-8');
                }
            }
            if ($score> $bestScore) { $bestScore=$score; $best=$intent; }
        }
        return $bestScore>0 ? $best : null;
    }

    // ---------------------
    // Mensajes personalizados / defaults
    // ---------------------
    private function getPersonalizedGreeting() {
        $a = [
            "¡Hola! Soy SEIN, tu asistente en educación sexual y salud reproductiva. ¿En qué puedo orientarte hoy?",
            "¡Hola! Me alegra verte. ¿Tienes alguna pregunta sobre salud sexual o reproductiva?",
            "¡Saludos! Estoy aquí para ayudarte con información confiable sobre salud sexual."
        ];
        return $a[array_rand($a)];
    }

    private function getPersonalizedFarewell() {
        $a = [
            "¡Hasta pronto! Si necesitas más información, aquí estaré.",
            "¡Cuídate! Vuelve si tienes más dudas.",
            "¡Nos vemos! Recuerda cuidar tu salud sexual."
        ];
        return $a[array_rand($a)];
    }

    private function addPersonalization($response, $original) {
        $prefixes = ["Basándome en tu consulta, ", "Según lo que me comentas, ", ""];
        $sufs = ["\n\n¿Quieres que profundice en algo?",""];
        return $prefixes[array_rand($prefixes)].$response.$sufs[array_rand($sufs)];
    }

    private function getIntelligentDefault($msg) {
        $len = strlen($msg);
        if ($len < 12) return "Veo tu mensaje corto. ¿Puedes dar más detalles para ayudarte mejor?";
        if (str_word_count($msg) > 60) return "Tu consulta es extensa. ¿Cuál es el punto principal que quieres resolver?";
        return "No estoy seguro de la intención exacta. ¿Puedes reformular o pedir un tema específico (anticonceptivos, ITS, embarazo, consentimiento)?";
    }

    // ---------------------
    // Logging de conversaciones (analytics)
    // ---------------------
    public function logConversation($userMessage, $botResponse, $userId = null) {
        $sql = "INSERT INTO conversation_analytics (user_id, user_message, bot_response, response_type, created_at) VALUES (?, ?, ?, 'advanced', NOW())";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if ($stmt) {
            $br = is_array($botResponse) ? ($botResponse['text'] ?? '') : $botResponse;
            mysqli_stmt_bind_param($stmt, "iss", $userId, $userMessage, $br);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            dbg_log("logConversation prepare failed: " . mysqli_error($this->conexion));
        }
    }

    // ---------------------
    // Seguridad: filtrado simple (entrada/salida)
    // ---------------------
    // NO es un filtro exhaustivo. Para producción usar soluciones más completas.
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
// Instanciar chatbot
// -----------------
$chatbot = new AdvancedChatbot($conexion, $OPENAI_API_KEY);

// Acciones API
$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            $sender = $_POST['sender'] ?? '';
            $message = $_POST['message'] ?? '';
            $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

            // Evitar que frontend inserte 'Bot'
            if (strcasecmp($sender,'Bot')===0) $sender = 'Usuario';

            // Limitar tamaño razonable del mensaje (evita abuse)
            if (mb_strlen($message, 'UTF-8') > 5000) $message = mb_substr($message,0,5000,'UTF-8');

            // Preparar insert (mensaje usuario)
            $sql = "INSERT INTO messages (sender, message, created_at) VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($conexion, $sql);
            if (!$stmt) {
                echo json_encode(['success'=>false,'error'=>'Error preparing insert: '.mysqli_error($conexion)], JSON_UNESCAPED_UNICODE);
                exit;
            }
            mysqli_stmt_bind_param($stmt,"ss",$sender,$message);
            $res = mysqli_stmt_execute($stmt);
            $insertId = $res ? mysqli_insert_id($conexion) : null;
            mysqli_stmt_close($stmt);

            if (!$insertId) {
                echo json_encode(['success'=>false,'error'=>'Error al insertar mensaje: '.mysqli_error($conexion)], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Generar respuesta si no vino del bot
            if (strcasecmp($sender,'Bot')!==0) {
                $botResponse = $chatbot->generateResponse($message,$userId) ?? "Lo siento, no pude generar una respuesta ahora.";

                // Soportar cuando generateResponse devuelve array con kb_record
                $kb_record = null;
                if (is_array($botResponse)) {
                    $kb_record = $botResponse['kb_record'] ?? null;
                    $botText = $botResponse['text'] ?? '';
                } else {
                    $botText = (string)$botResponse;
                }

                // Evitar respuestas vacías
                if (trim($botText) === '') $botText = "Lo siento, no tengo una respuesta clara en este momento.";

                // Insertar respuesta del bot
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

                // Analytics log (no bloquear si falla)
                $chatbot->logConversation($message, $botResponse, $userId);

                // Responder con kb_record si existe
                $out = ['success'=>true,'id'=>$insertId,'bot_id'=>$botId,'bot_message'=>$botText, 'response_type'=>'advanced'];
                if ($kb_record) $out['kb_record'] = $kb_record;
                echo json_encode($out, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                echo json_encode(['success'=>true,'id'=>$insertId], JSON_UNESCAPED_UNICODE);
                exit;
            }
            break;

        case 'read':
            $sql = "SELECT id, sender, message, created_at FROM messages ORDER BY created_at ASC";
            $result = mysqli_query($conexion,$sql);
            if ($result) {
                $messages=[];
                while ($row = mysqli_fetch_assoc($result)) $messages[]=$row;
                if ($result) mysqli_free_result($result);
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
                echo json_encode(['success'=>$res,'error'=>$res?null:mysqli_error($conexion)], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['success'=>false,'error'=>'Error preparing update: '.mysqli_error($conexion)], JSON_UNESCAPED_UNICODE);
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
                echo json_encode(['success'=>$res,'error'=>$res?null:mysqli_error($conexion)], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['success'=>false,'error'=>'Error preparing delete: '.mysqli_error($conexion)], JSON_UNESCAPED_UNICODE);
            }
            break;

        default:
            echo json_encode(['success'=>false,'error'=>'Acción no válida'], JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception $e) {
    dbg_log("EXCEPTION: ".$e->getMessage());
    echo json_encode(['success'=>false,'error'=>'Error en servidor: '.$e->getMessage()], JSON_UNESCAPED_UNICODE);
}

mysqli_close($conexion);