<?php
include "../conexion.php";

// Verificar que el usuario haya iniciado sesión
if(!isset($_SESSION['user'])){
    echo "<script>alert('Debes iniciar sesión primero');</script>";
    echo "<script>window.location='index.php';</script>";
    exit();
}

$documento = $_SESSION['user'];

// ============================
// CARGAR DATOS DEL USUARIO
// ============================
$consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE documento='$documento'");
$usuario = mysqli_fetch_assoc($consulta);

// ============================
// ACTUALIZAR DATOS PERSONALES
// ============================
if(isset($_POST['actualizar_datos'])){
    $td = $_POST['tipo_documento'];
    $pn = $_POST['primer_nombre'];
    $sn = $_POST['segundo_nombre'];
    $pa = $_POST['primer_apellido'];
    $sa = $_POST['segundo_apellido'];
    $tel = $_POST['telefono'];
    $ema = $_POST['correo'];


    $update = mysqli_query($conexion, "UPDATE usuarios SET 
        tipo_documento='$td',
        primer_nombre='$pn',
        segundo_nombre='$sn',
        primer_apellido='$pa',
        segundo_apellido='$sa',
        telefono='$tel',
        correo='$ema'
        WHERE documento='$documento'");

    if($update){
        echo "<script>alert('Datos actualizados correctamente');</script>";
        echo "<script>window.location='dashboard.php?mod=perfil';</script>";
    }else{
        echo "<script>alert('Error al actualizar los datos');</script>";
    }
}

// ============================
// CAMBIAR CONTRASEÑA
// ============================
if(isset($_POST['actualizar_clave'])){
    $actual = md5($_POST['clave_actual']);
    $nueva = md5($_POST['clave_nueva']);
    $confirmar = md5($_POST['clave_confirmar']);

    $verificar = mysqli_query($conexion, "SELECT * FROM usuarios WHERE documento='$documento' AND clave='$actual'");
    if(mysqli_num_rows($verificar) == 0){
        echo "<script>alert('La contraseña actual no es correcta');</script>";
    } elseif($nueva != $confirmar){
        echo "<script>alert('Las contraseñas nuevas no coinciden');</script>";
    } else {
        $update_pass = mysqli_query($conexion, "UPDATE usuarios SET clave='$nueva' WHERE documento='$documento'");
        if($update_pass){
            echo "<script>alert('Contraseña actualizada correctamente');</script>";
            echo "<script>window.location='dashboard.php?mod=perfil';</script>";
        } else {
            echo "<script>alert('Error al cambiar la contraseña');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="Admin/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid mt-4">

    <h3 class="text-center mb-4">Perfil de Usuario</h3>

    <div class="row">
        <!-- COLUMNA IZQUIERDA -->
        <div class="col-lg-4 mb-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <img src="img/undraw_profile.svg" alt="Avatar" class="rounded-circle mb-3" style="width:120px;">
                    <h5 class="font-weight-bold mb-1">
                        <?php echo $usuario['primer_nombre'].' '.$usuario['primer_apellido']; ?>
                    </h5>
                    <p class="text-muted"><?php echo $usuario['correo']; ?></p>
                    <p><i class="fas fa-id-card"></i> <?php echo $usuario['tipo_documento'].' '.$usuario['documento']; ?></p>
                    <p><i class="fas fa-user-tag"></i> Rol: <?php 
                      if ($usuario['ID_rol'] == 1) {
                          echo "Administrador";
                      } elseif ($usuario['ID_rol'] == 2) {
                          echo "Estudiante";
                      } elseif ($usuario['ID_rol'] == 3) {
                          echo "Psicólogo";
                      } else {
                          echo "Desconocido";
                      }
                    ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA -->
        <div class="col-lg-8 mb-4">

            <!-- FORMULARIO DE DATOS -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-2 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Actualizar Datos</h6>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tipo de Documento</label>
                                <select name="tipo_documento" class="form-control form-control-sm" required>
                                    <option value="<?php echo $usuario['tipo_documento']; ?>"><?php echo $usuario['tipo_documento'] ?></option>
                                    <option value="">Seleccione...</option>
                                    <option value="TI" <?php if($usuario['tipo_documento'] == 'Cédula de Ciudadanía') echo 'selected'; ?>>TI</option>
                                    <option value="CC" <?php if($usuario['tipo_documento'] == 'Tarjeta de Identidad') echo 'selected'; ?>>CC</option>
                                    <option value="RC" <?php if($usuario['tipo_documento'] == 'Cédula de Extranjería') echo 'selected'; ?>>RC</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Teléfono</label>
                                <input type="number" name="telefono" class="form-control form-control-sm" value="<?php echo $usuario['telefono']; ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Primer Nombre</label>
                                <input type="text" name="primer_nombre" class="form-control form-control-sm" value="<?php echo $usuario['primer_nombre']; ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="form-control form-control-sm" value="<?php echo $usuario['segundo_nombre']; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Primer Apellido</label>
                                <input type="text" name="primer_apellido" class="form-control form-control-sm" value="<?php echo $usuario['primer_apellido']; ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Segundo Apellido</label>
                                <input type="text" name="segundo_apellido" class="form-control form-control-sm" value="<?php echo $usuario['segundo_apellido']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Correo</label>
                            <input type="email" name="correo" class="form-control form-control-sm" value="<?php echo $usuario['correo']; ?>" required>
                        </div>

                        <button type="submit" name="actualizar_datos" class="btn btn-primary btn-sm" onclick="return confirm('¿Estás seguro de que deseas guardar los cambios en tu perfil?');">Guardar Cambios</button>
                    </form>
                </div>
            </div>

            <!-- FORMULARIO DE CONTRASEÑA -->
            <div class="card shadow-sm">
                <div class="card-header py-2 text-white" style="background: #556eb1ff;">
                    <h6 class="m-0 font-weight-bold">Cambiar Contraseña</h6>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-group">
                            <label>Contraseña Actual</label>
                            <input type="password" name="clave_actual" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group">
                            <label>Nueva Contraseña</label>
                            <input type="password" name="clave_nueva" class="form-control form-control-sm" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Mínimo 8 caracteres, al menos 1 número, 1 minúscula y 1 mayúscula.">
                        </div>
                        <div class="form-group">
                            <label>Confirmar Contraseña</label>
                            <input type="password" name="clave_confirmar" class="form-control form-control-sm" required>
                        </div>
                        <button type="submit" name="actualizar_clave" class="btn btn-sm text-white" style="background: #556eb1ff;">Actualizar Contraseña</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
