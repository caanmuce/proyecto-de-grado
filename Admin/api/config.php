<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Desactivar la salida de errores de PHP para evitar HTML en la respuesta JSON
ini_set('display_errors', 0);
error_reporting(0);

$host = '127.0.0.1:3307';
$dbname = 'kaboom';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Compatibilidad: algunos endpoints esperan getPDO()
if (!function_exists('getPDO')) {
    function getPDO() {
        global $pdo;
        return $pdo;
    }
}
?>