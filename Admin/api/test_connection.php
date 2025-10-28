<?php
require_once 'config.php';

try {
    $pdo = getPDO();
    echo "✅ Conexión a la base de datos EXITOSA<br>";
    
    // Verificar tabla casos_estudio
    $stmt = $pdo->query("DESCRIBE casos_estudio");
    $columns = $stmt->fetchAll();
    
    echo "✅ Tabla 'casos_estudio' existe<br>";
    echo "Columnas:<br>";
    foreach ($columns as $col) {
        echo "- {$col['Field']} ({$col['Type']})<br>";
    }
    
    // Verificar que podemos insertar
    $testId = 'test-' . time();
    $stmt = $pdo->prepare("INSERT INTO casos_estudio (id_caso, titulo, descripcion, categoria, dificultad, puntos_totales, activo) VALUES (?, 'Test', '{}', 'educacion_sexual', 'media', 0, 1)");
    $result = $stmt->execute([$testId]);
    
    if ($result) {
        echo "✅ Inserción de prueba EXITOSA<br>";
        
        // Limpiar
        $pdo->prepare("DELETE FROM casos_estudio WHERE id_caso = ?")->execute([$testId]);
        echo "✅ Datos de prueba limpiados<br>";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "<br>";
}
?>