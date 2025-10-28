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

if (!isset($input['progress']) || !isset($input['choices'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$progress = $input['progress'];
$choices = $input['choices'];

try {
    $pdo = getPDO();
    
    // Guardar progreso por caso (usando estudiante ID 1 como ejemplo)
    foreach ($progress as $caseId => $step) {
        $stmt = $pdo->prepare("
            INSERT INTO estudiantes_casos (id_estudiante, id_caso, puntos, progreso, fecha_actualizacion) 
            VALUES (1, ?, 0, ?, NOW())
            ON DUPLICATE KEY UPDATE progreso = ?, fecha_actualizacion = NOW()
        ");
        $stmt->execute([$caseId, $step, $step]);
    }
    
    // Guardar decisiones (estructura simplificada)
    foreach ($choices as $caseId => $steps) {
        foreach ($steps as $stepIndex => $optionIndex) {
            $stmt = $pdo->prepare("
                INSERT INTO decisiones_estudiantes (id_estudiante, id_caso, id_paso, id_opcion, puntos_obtenidos, fecha_decision) 
                VALUES (1, ?, ?, ?, 0, NOW())
                ON DUPLICATE KEY UPDATE id_opcion = ?, fecha_decision = NOW()
            ");
            $stmt->execute([$caseId, $stepIndex, $optionIndex, $optionIndex]);
        }
    }
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar progreso: ' . $e->getMessage()]);
}
?>