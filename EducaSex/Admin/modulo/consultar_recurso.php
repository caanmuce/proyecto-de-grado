<?php
if(isset($_POST['btn_eliminar_recurso'])){
    include '../conexion.php';
    $id = $_POST['Id_recurso'];

    $eliminar = mysqli_query($conexion, "DELETE FROM recursos_educativos WHERE Id_recurso = $id") 
        or die("Error al eliminar: " . mysqli_error($conexion));

    echo "<script>alert('Recurso eliminado con éxito');</script>";
    echo "<script>window.location='dashboard.php?mod=consultar_recurso';</script>";
}

if(isset($_POST['btn_update_recurso'])){
    include '../conexion.php';

    $id = $_POST['Id_recurso'];
    $titulo = $_POST['titulo'];
    $resumen = $_POST['resumen'];
    $tipo = $_POST['Id_tipo'];
    $categoria = $_POST['Id_categoria'];

    $actualizar = mysqli_query($conexion, 
        "UPDATE recursos_educativos 
         SET titulo = '$titulo', resumen = '$resumen', Id_tipo = '$tipo', Id_categoria = '$categoria' 
         WHERE Id_recurso = '$id'") 
         or die("Error al actualizar: " . mysqli_error($conexion));

    echo "<script>alert('Recurso actualizado con éxito');</script>";
    echo "<script>window.location='dashboard.php?mod=consultar_recurso';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Recursos Educativos</title>
</head>
<body>
<center>
    <a href="dashboard.php?mod=recursos_educativos">Ver</a> | <a href="dashboard.php?mod=crear_recurso">Crear</a> | <a href="dashboard.php?mod=consultar_recurso">Consultar</a>
    <br><br>
    <h1>Consultar Recursos Educativos</h1>

    <form action="dashboard.php?mod=consultar_recurso" method="post">
        <input type="text" placeholder="Buscar por título" name="txt_titulo" style="border-radius: 5px;" class="bg-light border-0.5" style="border-radius: 5px;">
        <button type="submit" name="btn_buscar_recurso" class="btn btn-primary">Buscar</button>
    </form>
    <br>

    <?php
    if(isset($_POST['btn_buscar_recurso'])){
        include "../conexion.php";
        $dato = $_POST['txt_titulo'];
        

        $consulta = mysqli_query($conexion,
            "SELECT r.*, t.Nombre_tipo, c.Nombre_categoria
            FROM recursos_educativos r
            JOIN tipo t ON r.id_tipo = t.id_tipo
            JOIN categoria c ON r.id_categoria = c.id_categoria
            WHERE r.titulo LIKE '%$dato%'
            ORDER BY r.id_recurso ASC") 
            or die("Error en la consulta: " . mysqli_error($conexion));
            ?>
    
    <table border="1" class="table table-bordered table-responsive" id="dataTable">
        <tr class="bg-gradient-primary" style="color: white;">
            <td>ID</td>
            <td>Título</td>
            <td>Resumen</td>
            <td>Tipo</td>
            <td>Categoría</td>
            <td>Archivo</td>
            <td>Modificar</td>
            <td>Eliminar</td>
        </tr>

        <?php while($row = mysqli_fetch_array($consulta)){ ?>
        <tr>
            <td><?php echo $row['Id_recurso']; ?></td>
            <td><?php echo $row['titulo']; ?></td>
            <td><?php echo $row['resumen']; ?></td>
            <td><?php echo $row['Nombre_tipo']; ?></td>
            <td><?php echo $row['Nombre_categoria']; ?></td>
            <td><?php echo $row['archivo']; ?></td>
            <td>
                <center>
                <form action="dashboard.php?mod=consultar_recurso" method="post">
                    <input type="hidden" name="Id_recurso" value="<?php echo $row['Id_recurso']; ?>">
                    <button type="submit" style="background-color:#ccc; border: 0px;" name="btn_modificar_recurso">
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
                <form action="dashboard.php?mod=consultar_recurso" method="post">
                    <input type="hidden" name="Id_recurso" value="<?php echo $row['Id_recurso']; ?>">
                    <button type="submit" style="background-color:#ccc; border: 0px;" name="btn_eliminar_recurso" onclick="return confirm('¿Estás seguro de eliminar este recurso?')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                        </svg>
                    </button>
                </form>
                </center>
            </td>
        </tr>
        <?php } ?>
    </table>

    <?php
    } else {
        echo "Ingrese un título para buscar.";
    }
    ?>

    <?php
    if(isset($_POST['btn_modificar_recurso'])){
        include "../conexion.php";
        $id = $_POST['Id_recurso'];

        $consulta2 = mysqli_query($conexion,
            "SELECT r.Id_recurso, r.titulo, r.resumen, 
                    r.Id_tipo, t.Nombre_tipo, 
                    r.Id_categoria, c.Nombre_categoria,
                    r.archivo
            FROM recursos_educativos r
            JOIN categoria c ON r.Id_categoria = c.Id_categoria
            JOIN tipo t ON r.Id_tipo = t.Id_tipo
            WHERE r.Id_recurso = '$id'") 
            or die('Error en la consulta: ' . mysqli_error($conexion));



        while ($row2 = mysqli_fetch_array($consulta2)){
    ?>
    <center>
    <h1>Modificar Recurso</h1>
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <!-- Imagen superior -->
                <div class="col-lg-12 d-lg-block bg-login-image text-center">
                    <img src="img/EducaSex.png" alt="" style="border-radius: 30px; max-width:200px;">
                </div>

                <!-- Formulario -->
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">¡Modificación de datos del recurso!</h1>
                        </div>

                        <form action="dashboard.php?mod=consultar_recurso" method="post" class="user" enctype="multipart/form-data">
                            
                            <input type="hidden" name="Id_recurso" value="<?= $row2['Id_recurso'] ?>">

                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Título</label>
                                        <input type="text" name="titulo" class="form-control"
                                            value="<?= $row2['titulo'] ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Resumen</label>
                                <textarea name="resumen" class="form-control" rows="3" required><?= $row2['resumen'] ?></textarea>
                                    </div>
                                </div>
                                
                            </div>                            
                            
                            

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Tipo</label>
                                        <select name="Id_tipo" class="form-control" required>
                                            <option value="<?= $row2['Id_tipo'] ?>"><?= $row2['Nombre_tipo'] ?></option>
                                            <option value="">Seleccione</option>
                                            <option value="1">Texto</option>
                                            <option value="2">Imagen</option>
                                            <option value="3">Audio</option>
                                            <option value="4">Video</option>
                                            <option value="5">Documento</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">                                    
                                        <label>Categoría</label>
                                        <select name="Id_categoria" class="form-control" required>
                                            <option value="<?= $row2['Id_categoria'] ?>"><?= $row2['Nombre_categoria'] ?></option>
                                            <option value="">Seleccione</option>
                                            <option value="1">Anatomía y fisiología sexual</option>
                                            <option value="2">Desarrollo sexual y etapas de la vida (ciclo vital)</         option>
                                            <option value="3">Pubertad y cambios físicos/psicológicos</option>
                                            <option value="4">Reproducción, embarazo y opciones reproductivas</option>
                                            <option value="5">Anticoncepción y planificación familiar</option>
                                            <option value="6">Infecciones de transmisión sexual (ITS) y prevención</            option>
                                            <option value="7">Consentimiento, límites y relaciones saludables</option>
                                            <option value="8">Violencia sexual, abuso y protección</option>
                                            <option value="9">Orientación sexual y identidad de género</option>
                                            <option value="10">Placer sexual, intimidad y afectividad</option>
                                            <option value="11">Salud mental, imagen corporal y autoestima sexual</option>
                                            <option value="12">Derechos sexuales y reproductivos, marco legal y ético</         option>
                                            <option value="13">Educación para la prevención y respuesta ante riesgos            (incluye drogas y sexualidad)</option>
                                            <option value="14">Salud sexual y servicios (acceso y uso)</option>
                                            <option value="15">Tecnología, pornografía y sexualidad digital</option>
                                            <option value="16">Cultura, religiones, valores y diversidad sociocultural</            option>
                                            <option value="17">Alfabetización mediática y pornografía</option>
                                            <option value="18">Sexualidad y discapacidad / inclusión</option>
                                            <option value="19">Ética, toma de decisiones y responsabilidades sociales</         option>
                                            <option value="20">Evaluación, investigación y monitoreo en educación           sexual</option>

                                        </select>   
                                    </div>

                    

                                </div>

                                <br>
                                    
                                <div class="form-group">
                                    <label>Archivo actual:</label><br>
                                    <?php if (!empty($row2['archivo'])): ?>
                                        <a href="../uploads/<?= $row2['archivo'] ?>" target="_blank"><?=$row2['archivo'] ?></a>
                                    <?php else: ?>
                                        <p>No hay archivo cargado</p>
                                    <?php endif; ?>
    
                                    <br><br>
                                    <label>Reemplazar archivo:</label>
                                    <input type="file" name="archivo" class="form-control">
                                </div>
                                
                            </div>
                            <br>

                            

                            <input type="submit" name="btn_update_recurso" value="Modificar"
                                   class="btn btn-primary btn-user btn-block">
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
    if (isset($_POST['btn_update_recurso'])) {
    include "../conexion.php";

        $id = $_POST['Id_recurso'];
        $titulo = $_POST['titulo'];
        $resumen = $_POST['resumen'];
        $tipo = $_POST['Id_tipo'];
        $categoria = $_POST['Id_categoria'];

    // Archivo (opcional)
    if (!empty($_FILES['archivo']['name'])) {
        $archivo = $_FILES['archivo']['name'];
        $tmp = $_FILES['archivo']['tmp_name'];

        // Carpeta donde guardas los archivos
        $ruta = "../uploads/" . $archivo;

        // Mover archivo nuevo
        move_uploaded_file($tmp, $ruta);

        // Actualizar con nuevo archivo
        $sql = "UPDATE recursos_educativos 
                SET titulo='$titulo', resumen='$resumen', Id_tipo='$tipo', Id_categoria='$categoria', archivo='$archivo'
                WHERE Id_recurso='$id'";
    } else {
        // Actualizar sin tocar el archivo
        $sql = "UPDATE recursos_educativos 
                SET titulo='$titulo', resumen='$resumen', Id_tipo='$tipo', Id_categoria='$categoria'
                WHERE Id_recurso='$id'";
    }

    $resultado = mysqli_query($conexion, $sql);

    if ($resultado) {
        echo "Recurso actualizado correctamente";
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}

    ?>
</center>
</body>
</html>
