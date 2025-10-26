<?php
    session_start();
    if(isset($_POST['BtnCrearRecurso'])){
        include "conexion.php";

        $titulo=mysqli_real_escape_string($conexion, $_POST['titulo']);

        $Id_tipo=mysqli_real_escape_string($conexion, $_POST['Id_tipo']);

        $Id_categoria=mysqli_real_escape_string($conexion, $_POST['Id_categoria']);

        $resumen=mysqli_real_escape_string($conexion, $_POST['resumen']);

        $archivo = "";
    if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0){
        $archivo = basename($_FILES['archivo']['name']); // solo nombre
        $ruta = "uploads/" . $archivo; // carpeta uploads/
        
        // Crear carpeta si no existe
        if(!is_dir("uploads")){
            mkdir("uploads", 0777, true);
        }

        // Mover archivo subido
        if(move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)){
            // éxito
        } else {
            echo "<script>alert('Error al subir el archivo');</script>";
            echo "<script>window.location='crear_recurso.php';</script>";
            exit;
        }
    }



        if ($titulo === '' || $Id_tipo <= 0 || $Id_categoria <= 0 || $resumen === '') {
        echo "<script>alert('Por favor completa todos los campos requeridos');</script>";
        echo "<script>window.location='crear_recurso.php';</script>";
        exit;
        }


        $crearrec = mysqli_query($conexion, "INSERT INTO `recursos_educativos` (`Id_recurso`, `titulo`, `Id_tipo`, `Id_categoria`, `resumen`, `archivo`) VALUES (NULL, '$titulo', '$Id_tipo', '$Id_categoria', '$resumen', '$archivo');") or die("Problemas para insertar: " . mysqli_error($conexion)); 


        if($crearrec){
            echo "<script>alert('Recurso educativo creado con éxito');</script>";
            echo "<script>window.location='index.php';</script>";
        }else{
            echo "<script>alert('Error al crear recurso educativo: ".mysqli_error($conexion)."');</script>";
            echo "<script>window.location='crear_recurso.php';</script>";
        }

    }else{
        echo "<script>alert('Error: acceso no permitido');</script>";
        echo "<script>window.location='crear_recurso.php';</script>";
    }
?>