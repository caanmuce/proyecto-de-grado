<?php
require_once 'config.php';

try {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('Datos inválidos');
    }
    
    // Por ahora solo devolvemos éxito
    // En el futuro esto se puede conectar a una tabla de usuarios
    echo json_encode(['success' => true, 'message' => 'Estudiante guardado correctamente']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>