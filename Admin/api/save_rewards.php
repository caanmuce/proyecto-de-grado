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

if (!isset($input['id']) || !isset($input['name']) || !isset($input['cost'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$id = $input['id'];
$name = $input['name'];
$cost = $input['cost'];
$img = $input['img'] ?? '';
$description = $input['description'] ?? '';

try {
    $pdo = getPDO();

    // Verificar si la recompensa ya existe
    $stmt = $pdo->prepare("SELECT id_recompensa FROM recompensas WHERE id_recompensa = ?");
    $stmt->execute([$id]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Actualizar recompensa existente
        $stmt = $pdo->prepare("
            UPDATE recompensas 
            SET nombre = ?, costo_puntos = ?, imagen_url = ?, descripcion = ?
            WHERE id_recompensa = ?
        ");
        $stmt->execute([$name, $cost, $img, $description, $id]);
    } else {
        // Insertar nueva recompensa
        $stmt = $pdo->prepare("
            INSERT INTO recompensas (id_recompensa, nombre, costo_puntos, imagen_url, descripcion, activa) 
            VALUES (?, ?, ?, ?, ?, 1)
        ");
        $stmt->execute([$id, $name, $cost, $img, $description]);
    }

    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar la recompensa: ' . $e->getMessage()]);
}
?>