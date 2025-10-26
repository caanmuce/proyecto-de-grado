<?php
if(isset($_POST['btn_eliminar'])){
    include '../conexion.php';
    $doc = $_POST['doc'];

    $eliminar = mysqli_query($conexion, "DELETE FROM usuarios WHERE `usuarios`.`documento` = $doc")or die($conexion."Error al eliminar");

    echo "<script>alert('Registro eliminado con exito');</script>";
    echo "<script>window.location='dashboard.php?mod=gestion_usuario';</script>";
}
if(isset($_POST['btn_update'])){
    include '../conexion.php';

$td=$_POST['Tipo_documento'];
$doc=$_POST['numero_documento'];
$pn=$_POST['txt_PN'];
$sn=$_POST['txt_SN'];
$pa=$_POST['txt_PA'];
$sa=$_POST['txt_SA'];
$tel=$_POST['numero_telefono'];
$ema=$_POST['correo'];
$rol=$_POST['Cmb_rol'];   

$actualizar = mysqli_query($conexion, "UPDATE `usuarios` SET `tipo_documento` = '$td', `primer_nombre` = '$pn', `segundo_nombre` = '$sn', `primer_apellido` = '$pa', `segundo_apellido` = '$sa', `telefono` = '$tel', `correo` = '$ema', `ID_rol` = '$rol' WHERE `usuarios`.`documento` = '$doc';") or die ("Error al enviar la actualización: " . mysqli_error($conexion));

echo "<script>alert('Registro actualizado con exito');</script>";
echo "<script>window.location='dashboard.php?mod=gestion_usuario';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion usuario</title>
</head>
<body>

    

        <center>
            
            <a href="dashboard.php?mod=crear_usuario">Crear</a> | <a href="dashboard.php?mod=gestion_usuario">Gestión</a>
            <br><br>
            <h1>Consultar usuarios</h1>

            <form action="dashboard.php?mod=gestion_usuario" method="post" class="">
                <input type="text" placeholder="Buscar por nombre" name="txt_nom" class="bg-light border-0.5" style="border-radius: 5px;">
                <button type="submit" name="btn_buscar" class="btn btn-primary">Buscar</button>
            </form>

            <br>

            <?php
                if(isset($_POST['btn_buscar'])){
                    
                    include "../conexion.php";
                    $dato = $_POST['txt_nom'];

                    $consulta = mysqli_query($conexion,"SELECT * FROM usuarios WHERE primer_nombre LIKE '%$dato%'") or die ($conexion."Error en la consulta");
            ?>

            
            
                
                    
                        <table border="1" class="table table-bordered table-responsive" id="dataTable">
                            <tr class="bg-gradient-primary" style="color: white;">
                                <td>Tipo documento</td>
                                <td>Documento</td>
                                <td>Primer Nombre</td>
                                <td>Segundo Nombre</td>
                                <td>Primer Apellido</td>
                                <td>Segundo Apellido</td>
                                <td>Correo</td>
                                <td>Teléfono</td>
                                <td>Rol</td>
                                <td>Modificar</td>
                                <td>Eliminar</td>
                            </tr>
                        
                        <?php

                        while($row = mysqli_fetch_array($consulta)){
                            ?>

                        <tr>
                            <td><?php echo $row['tipo_documento']; ?></td>
                            <td><?php echo $row['documento']; ?></td>
                            <td><?php echo $row['primer_nombre']; ?></td>
                            <td><?php echo $row['segundo_nombre']; ?></td>
                            <td><?php echo $row['primer_apellido']; ?></td>
                            <td><?php echo $row['segundo_apellido']; ?></td>
                            <td><?php echo $row['correo']; ?></td>
                            <td><?php echo $row['telefono']; ?></td>
                            <td><?php echo $row['ID_rol']; ?></td>
                            <td>
                                <center>
                                    <form action="dashboard.php?mod=gestion_usuario" method="post">
                                        <input type="text" name="doc" value="<?php echo $row['documento']; ?>" hidden>
                                            <button type="submit" name="btn_modificar" style="background-color:#ccc; border: 0px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                </svg>
                                            </button>
                                    </form>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <form action="dashboard.php?mod=gestion_usuario" method="post">
                                        <input type="text" name="doc" value="<?php echo $row['documento']; ?>" hidden>
                                            <button type="submit" style="background-color:#ccc; border: 0px;" name="btn_eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                </svg>
                                            </button>
                                    </form>
                                </center>
                            </td>
                        </tr>
                            <?php


                        }
                    }else{
                        echo "Ingrese datos";
                    }
                ?>
                        </table>
                        <?php
    if(isset($_POST['btn_modificar'])){

    include "../conexion.php";
        $dato2 = $_POST['doc'];

        $consulta2 = mysqli_query($conexion,"SELECT * FROM usuarios WHERE documento = '$dato2'") or die ($conexion."Error en la consulta");
        
    while ($row2 = mysqli_fetch_array($consulta2)){
?>
<center>
    <h1>Modificar Usuario</h1>

    <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12 d-lg-block bg-login-image text-center" style="align-content: center;"><img src="img/EducaSex.png" alt="" style="border radius: 30px;"></div>
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">¡Modificación de datos del usuario!</h1>
                            </div>
                            <form action="dashboard.php?mod=gestion_usuario" method="post" class="user">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label>Tipo de Documento</label>
                                        <select name="Tipo_documento" class="form-control" id="" required readonly>
                                        <option value=""><?php echo $row2['tipo_documento'] ?></option>
                                        <option value="">Seleccione</option>
                                        <option value="TI">TI</option>
                                        <option value="CC">CC</option>
                                        <option value="RC">RC</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        Número de Documento
                                        <input type="num" class="form-control form-control-user" name="numero_documento" value="<?php echo $row2['documento'] ?>" required title="Minimo 8 números, maximo 11" readonly>                                    
                                    </div>
                                </div>
                               

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        Primer Nombre
                                        <input type="text" class="form-control form-control-user" name="txt_PN" placeholder="Primer Nombre" value="<?php echo $row2['primer_nombre'] ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        Segundo Nombre
                                        <input type="text" class="form-control form-control-user" name="txt_SN" placeholder="Segundo Nombre" value="<?php echo $row2['segundo_nombre'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        Primer Apellido
                                        <input type="text" class="form-control form-control-user" name="txt_PA" placeholder="Primer Apellido" value="<?php echo $row2['primer_apellido'] ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        Segundo Apellido
                                        <input type="text" class="form-control form-control-user" name="txt_SA" placeholder="Segundo Apellido" value="<?php echo $row2['segundo_apellido'] ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        Número de teléfono
                                        <input type="Number" class="form-control form-control-user" name="numero_telefono" placeholder="Teléfono" value="<?php echo $row2['telefono'] ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        Email
                                        <input type="email" class="form-control form-control-user" name="correo" placeholder="Email" value="<?php echo $row2['correo'] ?>" required>
                                    </div>
                                </div>
                                
                               
                                <div class="form-group">
                                    <label>Rol</label>
                                    <select name="Cmb_rol" class="form-control" id="" placeholder="Id_rol" required>
                                        <option value=""><?php echo $row2['ID_rol'] ?></option>
                                        <option value="">Seleccione</option>
                                        <option value="1">Administrador(1)</option>
                                        <option value="2">Operario(2)</option>
                                        <option value="3">Asesor(3)</option>
                                    </select>
                                </div>
                                
                                <input type="submit" name="btn_update" value="Modificar" class="btn btn-primary btn-user btn-block">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</center>



<?php
    }   
    }
?>
        </center>

</body>
</html>