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

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos JSON inválidos']);
    exit;
}

if (!isset($input['title']) || !isset($input['steps'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos obligatorios: title o steps']);
    exit;
}

$id = isset($input['id']) ? $input['id'] : null;
$title = $input['title'];
$steps = json_encode($input['steps'], JSON_UNESCAPED_UNICODE);
$categoria = $input['categoria'] ?? 'educacion_sexual';
$dificultad = $input['dificultad'] ?? 'media';
$creado_por = $input['creado_por'] ?? null; // Puede ser NULL

// Validar JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'JSON de steps inválido: ' . json_last_error_msg()]);
    exit;
}

try {
    $pdo = getPDO();

    if ($id && is_numeric($id)) {
        // ACTUALIZAR caso existente (usando el ID numérico real)
        $stmt = $pdo->prepare("
            UPDATE casos_estudio 
            SET titulo = ?, descripcion = ?, categoria = ?, dificultad = ?
            WHERE id_caso = ?
        ");
        $stmt->execute([$title, $steps, $categoria, $dificultad, $id]);
        
        echo json_encode([
            'success' => true, 
            'id' => $id,
            'action' => 'updated'
        ]);
        
    } else {
        // INSERTAR nuevo caso (la BD asignará el ID automáticamente)
        $stmt = $pdo->prepare("
            INSERT INTO casos_estudio 
            (titulo, descripcion, categoria, dificultad, puntos_totales, activo, creado_por) 
            VALUES (?, ?, ?, ?, 0, 1, ?)
        ");
        $stmt->execute([$title, $steps, $categoria, $dificultad, $creado_por]);
        
        $newId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true, 
            'id' => $newId,
            'action' => 'created'
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error de base de datos',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al guardar el caso',
        'message' => $e->getMessage()
    ]);
}
?>