<?php
require_once 'config.php';

try {
    // Verificar que la conexión PDO existe
    if (!isset($pdo)) {
        throw new Exception('Error de conexión a la base de datos');
    }

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
            if ($steps && is_array($steps)) {
                foreach ($steps as $step) {
                    if (isset($step['descripcion']) && isset($step['opciones'])) {
                        $opciones = json_decode('[' . $step['opciones'] . ']', true);
                        $case['steps'][] = [
                            'desc' => $step['descripcion'],
                            'options' => is_array($opciones) ? $opciones : []
                        ];
                    }
                }
            }
        }
        
        $cases[] = $case;
    }
    
    echo json_encode($cases);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>