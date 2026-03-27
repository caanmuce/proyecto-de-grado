<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "educasex";

$conexion = mysqli_connect($host, $user, $pass, $dbname);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");
?>