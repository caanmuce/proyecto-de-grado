<?php
// Obtener la clave API de la variable de entorno
$geminiApiKey = getenv('GEMINI_API_KEY');

echo "<h2>Estado de la Clave API de Gemini en PHP</h2>";
echo "Fecha y hora del servidor: " . date("Y-m-d H:i:s") . "<br>";
echo "---------------------------------------------------<br>";

if ($geminiApiKey) {
    $longitud = strlen($geminiApiKey);
    
    // Mostramos solo los primeros y últimos 4 caracteres por seguridad.
    $claveVisible = substr($geminiApiKey, 0, 4) . str_repeat('*', $longitud - 8) . substr($geminiApiKey, -4);
    
    echo "<h3>✅ Éxito: Variable de Entorno Encontrada</h3>";
    echo "<p>El script PHP encontró la variable `GEMINI_API_KEY`.</p>";
    echo "<ul>";
    echo "<li>**Clave (Parcial):** <code>" . htmlspecialchars($claveVisible) . "</code></li>";
    echo "<li>**Longitud de la Clave:** <strong>" . $longitud . " caracteres</strong></li>";
    echo "</ul>";

    if ($longitud < 30) {
        echo "<p style='color: orange; font-weight: bold;'>⚠️ Advertencia: Las claves de API suelen ser largas. Verifica que la clave copiada esté completa.</p>";
    }
    
} else {
    echo "<h3>❌ Error: Variable de Entorno NO Encontrada</h3>";
    echo "<p>Tu clave no está disponible. Revisa:</p>";
    echo "<ul>";
    echo "<li>Que Apache esté corriendo.</li>";
    echo "<li>La línea <code>SetEnv GEMINI_API_KEY \"TU_CLAVE_AQUÍ\"</code> en el archivo <code>httpd-xampp.conf</code>.</li>";
    echo "<li>Que hayas reiniciado Apache después de guardar los cambios.</li>";
    echo "</ul>";
}

echo "---------------------------------------------------<br>";

?>