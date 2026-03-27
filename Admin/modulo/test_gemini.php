<?php
// test_gemini_detailed.php
header('Content-Type: text/plain; charset=utf-8');

// Verificar si el archivo principal existe
$main_api_file = __DIR__ . '/chatbot_api.php';
if (!file_exists($main_api_file)) {
    die("Error: No se encuentra api_chatbot_merged.php");
}

require_once $main_api_file;

echo "=== DIAGNÓSTICO DETALLADO DE GEMINI ===\n\n";

// 1. Verificar API Key
echo "1. VERIFICANDO API KEY:\n";
echo "API_KEY presente: " . ($API_KEY ? 'SÍ (' . substr($API_KEY, 0, 10) . '...)' : 'NO') . "\n\n";

// 2. Probar mensajes específicos
$test_messages = [
    "¿Cuál es la diferencia entre sexo y género?",
    "Explícame qué son los métodos anticonceptivos hormonales",
    "¿Qué significa ITS?",
    "Háblame sobre el consentimiento en las relaciones sexuales"
];

foreach ($test_messages as $msg) {
    echo "--- Probando: '$msg' ---\n";
    
    // Probar directamente generateWithAI
    $start = microtime(true);
    $response = $chatbot->generateWithAI($msg);
    $time = round((microtime(true) - $start) * 1000, 2);
    
    if ($response) {
        echo "✅ GEMINI FUNCIONA - Tiempo: {$time}ms\n";
        echo "Respuesta: " . substr($response, 0, 150) . "...\n\n";
    } else {
        echo "❌ GEMINI FALLÓ - Tiempo: {$time}ms\n";
        echo "Razón: No se generó respuesta\n\n";
    }
}

// 3. Probar el flujo completo
echo "--- Probando flujo completo ---\n";
$test_query = "¿Cuál es la diferencia entre sexo y género?";
$full_response = $chatbot->generateResponse($test_query);

echo "Fuente: " . ($full_response['response_metadata']['source'] ?? 'desconocida') . "\n";
echo "Confianza: " . ($full_response['response_metadata']['confidence'] ?? '0') . "\n";
echo "Tiempo: " . ($full_response['response_metadata']['response_time_ms'] ?? '0') . "ms\n";
echo "Respuesta: " . substr($full_response['text'] ?? 'Sin respuesta', 0, 200) . "\n";

// 4. Forzar Gemini
echo "\n--- Forzando Gemini con parámetro ---\n";
$_GET['force_gemini'] = 1; // Simular el parámetro
$forced_response = $chatbot->generateResponse($test_query);
echo "Fuente forzada: " . ($forced_response['response_metadata']['source'] ?? 'desconocida') . "\n";
?>