<?php
if(isset($_POST['btn_eliminar'])){
    include '../conexion.php';
    $Id_foro = $_POST['Id_foro'];

    $eliminar = mysqli_query($conexion, "DELETE FROM foro WHERE Id_foro = $Id_foro")
        or die("Error al eliminar: " . mysqli_error($conexion));

    echo "<script>alert('Foro eliminado con éxito');</script>";
    echo "<script>window.location='dashboard.php?mod=foro';</script>";
}

if(isset($_POST['btn_update'])){
    include '../conexion.php';

    $Id_foro = $_POST['Id_foro'];
    $titulo = $_POST['titulo_foro'];
    $contenido = $_POST['contenido'];
    $categoria = $_POST['Id_categoria'];
    $documento = $_POST['documento'];

    $actualizar = mysqli_query($conexion, 
        "UPDATE foro 
         SET titulo_foro = '$titulo', contenido = '$contenido', Id_categoria = '$categoria', documento = '$documento' 
         WHERE Id_foro = '$Id_foro'")
        or die ("Error al actualizar: " . mysqli_error($conexion));

    echo "<script>alert('Foro actualizado con éxito');</script>";
    echo "<script>window.location='dashboard.php?mod=foro';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Foros</title>
</head>
<body>
    <center>
        <a href="dashboard.php?mod=ver_foro">Ver</a> | <a href="dashboard.php?mod=crear_foro">Crear</a> | <a href="dashboard.php?mod=foro">Gestionar</a>
        <br><br>
        <h1>Consultar Foros</h1>

        
        <form action="dashboard.php?mod=foro" method="post">
            <input type="text" placeholder="Buscar por título" name="txt_titulo" class="bg-light border-0.5" style="border-radius: 5px;">
            <button type="submit" name="btn_buscar" class="btn btn-primary">Buscar</button>
        </form>

        <br>

        <?php
        if(isset($_POST['btn_buscar'])){
            include "../conexion.php";
            $dato = $_POST['txt_titulo'];

            $consulta = mysqli_query($conexion,
            "SELECT f.Id_foro, f.titulo_foro, f.contenido, f.Id_categoria, f.documento, c.Nombre_categoria
             FROM foro f
             LEFT JOIN categoria c ON f.Id_categoria = c.Id_categoria
             WHERE f.titulo_foro LIKE '%$dato%'") 
                        or die ("Error en la consulta: " . mysqli_error($conexion));
        ?>

        
        <table border="1" class="table table-bordered table-responsive">
            <tr class="bg-gradient-primary" style="color: white;">
                <td>ID Foro</td>
                <td>Título</td>
                <td>Contenido</td>
                <td>Nombre Categoría</td>
                <td>Documento</td>
                <td>Modificar</td>
                <td>Eliminar</td>
            </tr>
            
            <?php while($row = mysqli_fetch_array($consulta)){ ?>
            <tr>
                <td><?php echo $row['Id_foro']; ?></td>
                <td><?php echo $row['titulo_foro']; ?></td>
                <td><?php echo $row['contenido']; ?></td>
                <td><?php echo $row['Nombre_categoria']; ?></td>
                <td><?php echo $row['documento']; ?></td>
                <td>
                    <center>
                        <form action="dashboard.php?mod=foro" method="post">
                            <input type="hidden" name="Id_foro" value="<?php echo $row['Id_foro']; ?>">
                            <button type="submit" name="btn_modificar" style="background-color:#ccc; border:0px;">
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
                        <form action="dashboard.php?mod=foro" method="post">
                            <input type="hidden" name="Id_foro" value="<?php echo $row['Id_foro']; ?>">
                            <button type="submit" name="btn_eliminar" style="background-color:#ccc; border:0px;" onclick="return confirm('¿Estás seguro de eliminar este foro?')">
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
            echo "Ingrese datos";
        }
        ?>

        <?php
        
        if(isset($_POST['btn_modificar'])){
            include "../conexion.php";
            $Id_foro = 0;
            $Id_foro = $_POST['Id_foro'];

            $consulta2 = mysqli_query($conexion,
            "SELECT f.Id_foro, f.titulo_foro, f.contenido, f.Id_categoria, f.documento, c.Nombre_categoria
             FROM foro f
             LEFT JOIN categoria c ON f.Id_categoria = c.Id_categoria
             WHERE f.Id_foro = '$Id_foro'"
        ) 
                         or die ("Error en la consulta: " . mysqli_error($conexion));

            while ($row2 = mysqli_fetch_array($consulta2)){
        ?>
        <center>
    <h1>Modificar Foro</h1>

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12 d-lg-block bg-login-image text-center" style="align-content: center;">
                    <img src="img/EducaSex.png" alt="" style="border-radius: 30px; max-width:150px;">
                </div>
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">¡Modificación de datos del foro!</h1>
                        </div>
                        <form action="dashboard.php?mod=foro" method="post" class="user">
                            <input type="hidden" name="Id_foro" value="<?php echo $row2['Id_foro']; ?>">

                            <div class="form-group row">
                                <div class="col-md-6 mb-3 mb-sm-0">
                                    <label>Título del Foro</label>
                                    <input type="text" class="form-control form-control-user" 
                                           name="titulo_foro" value="<?php echo $row2['titulo_foro']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3 mb-sm-0">
                                    <label>Contenido</label>
                                <textarea class="form-control" name="contenido" rows="4" required><?php echo $row2['contenido']; ?></textarea>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
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
                                <div class="col-sm-6">
                                    <label>Documento</label>
                                    <input type="text" class="form-control form-control-user" 
                                           name="documento" value="<?php echo $row2['documento']; ?>" required readonly>
                                </div>
                            </div>

                            <input type="submit" name="btn_update" value="Modificar" 
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
        ?>
    </center>
</body>
</html>
