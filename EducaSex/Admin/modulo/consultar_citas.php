<?php
if (isset($_POST['btn_eliminar_cita'])) {
    include '../conexion.php';
    $id_cita = $_POST['id_cita'];

    $eliminar = mysqli_query($conexion, "DELETE FROM citas WHERE id_cita = $id_cita") or die("Error al eliminar: " . mysqli_error($conexion));

    echo "<script>alert('Cita eliminada con éxito');</script>";
    echo "<script>window.location='dashboard.php?mod=consultar_citas';</script>";
}

if (isset($_POST['btn_update_cita'])) {
    include '../conexion.php';

    $id = $_POST['id_cita'];
    $fecha = $_POST['fecha_hora'];
    $motivo = $_POST['motivo'];
    $doc = $_POST['documento'];

    if ($motivo === "otro" && !empty($_POST['otro_motivo'])) {
        $motivo = $_POST['otro_motivo'];
    }

    $actualizar = mysqli_query($conexion, "UPDATE citas 
        SET fecha_hora = '$fecha', motivo = '$motivo', documento = '$doc' 
        WHERE id_cita = '$id'") 
        or die("Error al actualizar: " . mysqli_error($conexion));

    echo "<script>alert('Cita actualizada con éxito');</script>";
    echo "<script>window.location='dashboard.php?mod=consultar_citas';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Citas</title>
</head>
<body>
    <center>
        <a href="dashboard.php?mod=agendar_cita">Agendar</a> | <a href="dashboard.php?mod=consultar_citas">Consultar</a>
        <br><br>
        <h1>Consultar Citas</h1>

        <form action="dashboard.php?mod=consultar_citas" method="post">
            <input type="text" name="txt_doc" placeholder="Buscar por documento" style="border-radius: 5px;">
            <button type="submit" name="btn_buscar_cita" class="btn btn-primary">Buscar</button>
        </form>

        <br>

        <?php
        if (isset($_POST['btn_buscar_cita'])) {
            include "../conexion.php";
            $doc = $_POST['txt_doc'];

            $consulta = mysqli_query($conexion, "
                SELECT * FROM citas 
                WHERE documento LIKE '%$doc%'
            ") or die("Error en la consulta: " . mysqli_error($conexion));
        ?>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <table border="1" class="table table-bordered table-responsive" id="dataTable">
                        <tr class="bg-gradient-primary" style="color: white;">
                            <td>ID Cita</td>
                            <td>Fecha y Hora</td>
                            <td>Motivo</td>
                            <td>Documento</td>
                            <td>Modificar</td>
                            <td>Eliminar</td>
                        </tr>

                        <?php while ($cita = mysqli_fetch_array($consulta)) { ?>
                            <tr>
                                <td><?= $cita['id_cita'] ?></td>
                                <td><?= $cita['fecha_hora'] ?></td>
                                <td><?= $cita['motivo'] ?></td>
                                <td><?= $cita['documento'] ?></td>
                                <td>
                                    <center>
                                        <form action="dashboard.php?mod=consultar_citas" method="post">
                                            <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                            <button type="submit" name="btn_modificar_cita" style="background-color:#ccc; border: 0px;">
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
                                        <form action="dashboard.php?mod=consultar_citas" method="post">
                                            <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                            <button type="submit" name="btn_eliminar_cita" onclick="return confirm('¿Estás seguro de eliminar esta cita?')" style="background-color:#ccc; border: 0px;">
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
                </div>
            </div>
        </div>            
        <?php
        } else {
            echo "<p>Ingrese un documento para buscar citas.</p>";
        }

        if (isset($_POST['btn_modificar_cita'])) {
            include "../conexion.php";
            $id = $_POST['id_cita'];

            $consulta2 = mysqli_query($conexion,"SELECT * FROM citas WHERE id_cita = '$id'") or die ($conexion."Error en la consulta");

            while ($cita2 = mysqli_fetch_array($consulta2)) {
        ?>
        <center>
            <h1>Modificar Cita</h1>
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12 d-lg-block bg-login-image text-center">
                            <img src="img/EducaSex.png" alt="" style="border-radius: 30px;">
                        </div>
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">¡Modificación de datos de la cita!</h1>
                                </div>
                                <form action="dashboard.php?mod=consultar_citas" method="post" class="user">
                                    <input type="hidden" name="id_cita" value="<?= $cita2['id_cita'] ?>">

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label>Fecha y Hora</label>
                                            <input type="datetime-local" name="fecha_hora" class="form-control"
                                            value="<?= date('Y-m-d\TH:i', strtotime($cita2['fecha_hora'])) ?>" required>
                                        </div>
                                    
                                        <br>

                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label>Motivo de la cita</label><br>
                                                
                                            <?php
                                                $motivo_actual = $cita2['motivo'];
                                                ?>

                                            <input type="radio" name="motivo" value="asesoria_psicologica" 
                                                    <?= ($motivo_actual == 'asesoria_psicologica') ? 'checked' : '' ?>> Asesoría psicológica <br>

                                            <input type="radio" name="motivo" value="orientacion_sexual" 
                                                    <?= ($motivo_actual == 'orientacion_sexual') ? 'checked' : '' ?>> Orientación sexual <br>

                                            <input type="radio" name="motivo" value="consulta_medica" 
                                                    <?= ($motivo_actual == 'consulta_medica') ? 'checked' : '' ?>> Consulta médica <br>

                                            <input type="radio" name="motivo" value="prevencion_its" 
                                                    <?= ($motivo_actual == 'prevencion_its') ? 'checked' : '' ?>> Prevención ITS <br>

                                            <input type="radio" name="motivo" value="otro" 
                                                    <?= (!in_array($motivo_actual, ['asesoria_psicologica','orientacion_sexual','consulta_medica','prevencion_its'])) ? 'checked' : '' ?>> Otro <br>

                                            <!-- Campo de texto que aparece solo si eligió "Otro" -->
                                            <input type="text" class="form-control" name="otro_motivo" id="otro_motivo" placeholder="Especifique otro motivo "
                                                    value="<?= (!in_array($motivo_actual, ['asesoria_psicologica','orientacion_sexual','consulta_medica','prevencion_its'])) ? $motivo_actual : '' ?>"
                                                    style="display: <?= (!in_array($motivo_actual, ['asesoria_psicologica','orientacion_sexual','consulta_medica','prevencion_its'])) ? 'block' : 'none' ?>;">
                                        </div>
                                            <br>
                                    </div>
                                            <div class="form-group">
                                                <label>Documento</label>
                                                <input type="text" name="documento" class="form-control" value="<?= $cita2['documento'] ?>" readonly>
                                            </div>
                                            <br>

                                            <input type="submit" name="btn_update_cita" value="Modificar" class="btn btn-primary">
                                        </form>
                                    

                                    <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const radios = document.querySelectorAll("input[name='motivo']");
                                        const otroInput = document.getElementById("otro_motivo");

                                        radios.forEach(radio => {
                                            radio.addEventListener("change", function() {
                                                if (this.value === "otro") {
                                                    otroInput.style.display = "block";
                                                } else {
                                                    otroInput.style.display = "none";
                                                    otroInput.value = "";
                                                }
                                            });
                                        });
                                    });
                                    </script>
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
