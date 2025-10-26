<?php
//Abro sesiones
session_start();
if(isset($_SESSION['user'])){
    echo "<script>window.location='Admin/dashboard.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EducaSex - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

<?php

        
        //Verificar boton
        if (isset($_POST['btnIngresar'])){
            //Incluir conexion
            include "conexion.php";
            //Recibir variables
            $pass = $_POST['txtPass'];
            $user = $_POST['txtDoc'];
            //Verificar campo vacio
            if(!empty($pass) and !empty($user)){
                //Encriptar contraseña
                $clave=md5($pass);
                //Consultar en la Base de Datos el usuario y clave
                $consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE documento=$user and clave='$clave'") or die ($conexion."Problemas en la consulta");

                // Verificar si encontró 1 registro
                if(mysqli_num_rows($consulta) > 0){
                    // Obtener los datos
                    $fila = mysqli_fetch_array($consulta);
                    
                    // Crear las variables de sesión
                    $_SESSION['user'] = $fila['documento'];
                    $_SESSION['pn'] = $fila['primer_nombre'];
                    $_SESSION['pa'] = $fila['primer_apellido'];

                    // Redirigir al dashboard
                    echo "<script> window.location='Admin/dashboard.php' </script>";
                } else {
                    echo "<script>alert('El usuario no existe o las contraseñas no coinciden.')</script>";
                    echo "<script>window.location='index.php'</script>";
                }

            }else{
                echo "<font color='red'>Rellene todos los campos</font>";
            }
        }
        ?>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-lg-block bg-login-image text-center" style="align-content: center;"><img src="img/EducaSex.png" alt=""></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido!</h1>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            <input type="num" class="form-control form-control-user" 
                                                id="exampleInputDoc" aria-describedby="docHelp"
                                                placeholder="Número de documento" name="txtDoc" required pattern="^[0-9]{7,11}$" title="Minimo 8 números, maximo 11">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" 
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Correo electronico" name="correo" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Minimo 8 números, maximo 11">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Clave" name="txtPass" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Mínimo 8 caracteres, al menos 1 número, 1 minúscula y 1 mayúscula.">
                                        </div>
                                        
                                        <a href="dashboard.php" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </a>
                                        
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
