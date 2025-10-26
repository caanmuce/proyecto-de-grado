<?php

    session_start();
    if(isset($_POST['BtnRegistrar'])){
        include "conexion.php";

        //Recibir de contraseñas
        $pass=$_POST['clave'];
        $pass2=$_POST['c_clave'];
        //Validación de contraseñas
        if($pass==$pass2){
            //Encriptar contraseña
            $clave=md5($pass);
            //Recibir los demas datos
            $td=$_POST['Tipo_documento'];
            $doc=$_POST['numero_documento'];
            $pn=$_POST['txt_PN'];
            $sn=$_POST['txt_SN'];
            $pa=$_POST['txt_PA'];
            $sa=$_POST['txt_SA'];
            $tel=$_POST['numero_telefono'];
            $ema=$_POST['correo'];
            $rol=$_POST['Cmb_rol'];

            
            if ($doc > 2147483647) {
                $pagina_origen = $_SERVER['HTTP_REFERER']; 
                echo "<script>alert('El número de documento no puede superar 2147483647');</script>";
                echo "<script>window.location='$pagina_origen';</script>";
                exit();
            }

            
            $verificar = mysqli_query($conexion, "SELECT * FROM usuarios WHERE documento = '$doc'");
            if (mysqli_num_rows($verificar) > 0) {
                $pagina_origen = $_SERVER['HTTP_REFERER'];
                echo "<script>alert('Este número de documento ya está registrado.');</script>";
                echo "<script>window.location='$pagina_origen';</script>";
                exit();
            }           


            $registrar = mysqli_query($conexion, "INSERT INTO `usuarios` (`documento`, `tipo_documento`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `telefono`, `correo`, `clave`, `ID_rol`) VALUES ('$doc', '$td', '$pn', '$sn', '$pa', '$sa', '$tel', '$ema', '$clave', '$rol');") or die("Problemas para insertar: " . mysqli_error($conexion));
            //Mensaje exitoso
            echo "<script>alert('Usuario registrado con exito');</script>";
            //Redireccion pos mensaje existoso
            echo "<script>window.location='index.php';</script>";
        }else{
            //Mensaje tipo alerta
            echo "<script>alert('Las contraseñas no coinciden');</script>";
            //Redireccion al formulario
            echo "<script>window.location='registrar.php';</script>";
        }
    }else{
        echo "<script>alert('Error');</script>";
        echo "<script>window.location='registrar.php';</script>";
    }
?>