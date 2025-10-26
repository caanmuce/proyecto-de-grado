<?php
    session_start();
    if(isset($_POST['BtnAgendar'])){
        include "conexion.php";

        $fecha_hora=mysqli_real_escape_string($conexion, $_POST['fecha_hora']);

        $motivo=mysqli_real_escape_string($conexion, $_POST['motivo']);

        $documento=mysqli_real_escape_string($conexion, $_POST['documento']);



        if ($motivo === "otro" && !empty($_POST['motivo_otro'])) {
        $motivo = $_POST['motivo_otro']; 
        }



        $agendar = mysqli_query($conexion, "INSERT INTO `citas` (`id_cita`, `fecha_hora`, `motivo`, `documento`) VALUES (NULL, '$fecha_hora', '$motivo', '$documento');") or die("Problemas para insertar: " . mysqli_error($conexion)); 


        if($agendar){
            echo "<script>alert('Cita agendada con éxito');</script>";
            echo "<script>window.location='index.php';</script>";
        }else{
            echo "<script>alert('Error al agendar la cita: ".mysqli_error($conexion)."');</script>";
            echo "<script>window.location='agendar_cita.php';</script>";
        }

    }else{
        echo "<script>alert('Error: acceso no permitido');</script>";
        echo "<script>window.location='agendar_cita.php';</script>";
    }
?>