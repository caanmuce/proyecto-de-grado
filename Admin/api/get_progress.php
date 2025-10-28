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
    
    // Obtener progreso de casos
    $stmt = $pdo->query("
        SELECT ec.id_caso, ec.puntos, ec.progreso 
        FROM estudiantes_casos ec 
        WHERE ec.id_estudiante = 1
    ");
    $progress = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener decisiones tomadas
    $stmt = $pdo->query("
        SELECT de.id_caso, de.id_paso, de.id_opcion 
        FROM decisiones_estudiantes de 
        WHERE de.id_estudiante = 1
    ");
    $choices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Estructurar los datos como espera el frontend
    $caseProgress = [];
    $caseChoices = [];
    
    foreach ($progress as $row) {
        $caseProgress[$row['id_caso']] = $row['progreso'];
    }
    
    foreach ($choices as $row) {
        if (!isset($caseChoices[$row['id_caso']])) {
            $caseChoices[$row['id_caso']] = [];
        }
        $caseChoices[$row['id_caso']][$row['id_paso']] = $row['id_opcion'];
    }
    
    echo json_encode([
        'progress' => $caseProgress,
        'choices' => $caseChoices
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener progreso: ' . $e->getMessage()]);
}
?>