<?php
require_once 'config.php';

try {
    // Por ahora devolvemos datos por defecto del estudiante
    // En el futuro esto se puede conectar a una tabla de usuarios
    $student = [
        'id' => 1,
        'name' => 'Estudiante',
        'email' => '',
        'avatar' => 'https://placehold.co/80x80',
        'puntos' => 0,
        'meta' => 100
    ];
    
    echo json_encode($student);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>