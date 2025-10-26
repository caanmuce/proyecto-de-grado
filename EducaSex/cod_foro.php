<?php
session_start();

if(isset($_POST['BtnCrearForo'])){
    include "conexion.php";

    $titulo = $_POST['titulo_foro'];
    $contenido = $_POST['contenido'];
    $categoria = $_POST['id_categoria'];
    $documento = $_POST['documento'];


    $insertar = mysqli_query($conexion, 
        "INSERT INTO `foro` (`Id_foro`, `titulo_foro`, `contenido`, `Id_categoria`, `documento`) VALUES (NULL, '$titulo', '$contenido', '$categoria', '$documento');") or die("Error al insertar: " . mysqli_error($conexion));

    if($insertar){
        echo "<script>alert('Foro creado con éxito');</script>";
        echo "<script>window.location='index.php';</script>";
    } else {
        echo "<script>alert('Hubo un error al crear el foro');</script>";
        echo "<script>window.location='crear_foro.php';</script>";
    }

}else{
    echo "<script>alert('Acceso inválido');</script>";
    echo "<script>window.location='crear_foro.php';</script>";
}
?>
