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

if (!isset($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$id = $input['id'];

try {
    $pdo = getPDO();

    // Opción 1: Eliminar físicamente la recompensa
    $stmt = $pdo->prepare("DELETE FROM recompensas WHERE id_recompensa = ?");
    $stmt->execute([$id]);

    // Opción 2: Marcar como inactiva (si prefieres no eliminar)
    // $stmt = $pdo->prepare("UPDATE recompensas SET activa = 0 WHERE id_recompensa = ?");
    // $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Recompensa no encontrada']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar la recompensa: ' . $e->getMessage()]);
}
?>