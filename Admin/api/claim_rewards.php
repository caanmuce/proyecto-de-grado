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

if (!isset($input['rewardId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de recompensa no proporcionado']);
    exit;
}

$rewardId = $input['rewardId'];
$studentId = 1; // Asumiendo estudiante ID 1 por ahora

try {
    $pdo = getPDO();

    // Obtener información de la recompensa
    $stmt = $pdo->prepare("SELECT costo_puntos FROM recompensas WHERE id_recompensa = ? AND activa = 1");
    $stmt->execute([$rewardId]);
    $reward = $stmt->fetch();

    if (!$reward) {
        http_response_code(404);
        echo json_encode(['error' => 'Recompensa no encontrada']);
        exit;
    }

    // Obtener puntos del estudiante
    $stmt = $pdo->prepare("SELECT puntos FROM estudiantes_casos WHERE id_estudiante = ? ORDER BY fecha_actualizacion DESC LIMIT 1");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();

    $currentPoints = $student ? $student['puntos'] : 0;

    if ($currentPoints < $reward['costo_puntos']) {
        http_response_code(400);
        echo json_encode(['error' => 'Puntos insuficientes']);
        exit;
    }

    // Registrar la reclamación (necesitarías una tabla para esto)
    // Por ahora, simplemente restamos los puntos
    $newPoints = $currentPoints - $reward['costo_puntos'];
    
    $stmt = $pdo->prepare("
        INSERT INTO estudiantes_casos (id_estudiante, id_caso, puntos, progreso, fecha_actualizacion) 
        VALUES (?, NULL, ?, 0, NOW())
        ON DUPLICATE KEY UPDATE puntos = ?, fecha_actualizacion = NOW()
    ");
    $stmt->execute([$studentId, $newPoints, $newPoints]);

    echo json_encode([
        'success' => true, 
        'message' => 'Recompensa reclamada exitosamente',
        'newPoints' => $newPoints
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al reclamar la recompensa: ' . $e->getMessage()]);
}
?>