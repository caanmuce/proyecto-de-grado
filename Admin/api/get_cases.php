<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("
        SELECT c.*, 
               GROUP_CONCAT(
                   JSON_OBJECT(
                       'descripcion', p.descripcion,
                       'opciones', (
                           SELECT GROUP_CONCAT(
                               JSON_OBJECT(
                                   'text', o.texto_opcion,
                                   'puntos', o.puntos
                               )
                           )
                           FROM opciones_pasos o 
                           WHERE o.id_paso = p.id_paso
                       )
                   )
               ) as pasos_json
        FROM casos_estudio c
        LEFT JOIN pasos_casos p ON c.id_caso = p.id_caso
        WHERE c.activo = 1
        GROUP BY c.id_caso
    ");
    
    $cases = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $case = [
            'id' => $row['id_caso'],
            'title' => $row['titulo'],
            'steps' => []
        ];
        
        if ($row['pasos_json']) {
            $steps = json_decode('[' . $row['pasos_json'] . ']', true);
            foreach ($steps as $step) {
                $case['steps'][] = [
                    'desc' => $step['descripcion'],
                    'options' => json_decode('[' . $step['opciones'] . ']', true)
                ];
            }
        }
        
        $cases[] = $case;
    }
    
    echo json_encode($cases);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>