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

    // Eliminar el caso
    $stmt = $pdo->prepare("DELETE FROM casos_estudio WHERE id_caso = ?");
    $stmt->execute([$id]);

    // También eliminar decisiones relacionadas
    $stmt = $pdo->prepare("DELETE FROM decisiones_estudiantes WHERE id_progreso IN (SELECT id_progreso FROM estudiantes_casos WHERE id_caso = ?)");
    $stmt->execute([$id]);

    // Eliminar progreso relacionado
    $stmt = $pdo->prepare("DELETE FROM estudiantes_casos WHERE id_caso = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar el caso: ' . $e->getMessage()]);
}
?>