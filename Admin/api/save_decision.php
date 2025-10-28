<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['caseId']) || !isset($input['stepIndex']) || !isset($input['optionIndex']) || !isset($input['puntos'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$caseId = $input['caseId'];
$stepIndex = $input['stepIndex'];
$optionIndex = $input['optionIndex'];
$puntos = $input['puntos'];

try {
    $pdo = getPDO();
    
    // Guardar la decisión (usando estudiante ID 1 como ejemplo)
    $stmt = $pdo->prepare("
        INSERT INTO decisiones_estudiantes (id_estudiante, id_caso, id_paso, id_opcion, puntos_obtenidos, fecha_decision) 
        VALUES (1, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE id_opcion = ?, puntos_obtenidos = ?, fecha_decision = NOW()
    ");
    $stmt->execute([$caseId, $stepIndex, $optionIndex, $puntos, $optionIndex, $puntos]);
    
    // Actualizar progreso del caso
    $stmt = $pdo->prepare("
        INSERT INTO estudiantes_casos (id_estudiante, id_caso, puntos, progreso, fecha_actualizacion) 
        VALUES (1, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE puntos = puntos + ?, progreso = ?, fecha_actualizacion = NOW()
    ");
    $stmt->execute([$caseId, $puntos, $stepIndex + 1, $puntos, $stepIndex + 1]);
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar decisión: ' . $e->getMessage()]);
}
?>