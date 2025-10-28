<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $pdo = getPDO();
    
    $stmt = $pdo->query("
        SELECT 
            id_recompensa as id,
            nombre as name, 
            costo_puntos as cost, 
            imagen_url as img,
            descripcion,
            activa
        FROM recompensas 
        WHERE activa = 1
        ORDER BY costo_puntos ASC
    ");
    $rewards = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rewards);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener recompensas: ' . $e->getMessage()]);
}
?>