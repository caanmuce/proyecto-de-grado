<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$documento = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendar cita</title>
</head>
<body>
    <center>
        <?php if ($rol == 1 || $rol == 3) { ?>
            <a href="dashboard.php?mod=agendar_cita">Agendar</a> | <a href="dashboard.php?mod=consultar_citas">Consultar</a>
            <br><br>
        <?php } ?>
        <h1>Agendar Cita</h1>
    </center>
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12 d-lg-block bg-login-image text-center">
                        <img src="img/EducaSex.png" alt="" style="border-radius: 30px;">
                    </div>
                    <div class="col-lg-12">
                        <div class="p-4">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Programa tu cita</h1>
                            </div>
                            <form action="../cod_cita.php" method="post" class="user">

                                
                                <div class="form-group">
                                    <label for="documento">Documento del solicitante</label>
                                    <input type="text" class="form-control" 
                                    name="documento" 
                                    value="<?php echo $_SESSION['user']; ?>" 
                                    readonly>
                                </div>

                                
                                <div class="form-group">
                                    <label>Motivo de la cita</label><br>

                                    <div>
                                        <input type="radio" name="motivo" value="Asesoria_psicologica" id="motivo_psicologica" required>
                                        <label for="motivo_psicologica">Asesoría psicológica</label>
                                    </div>

                                    <div>
                                        <input type="radio" name="motivo" value="Orientacion_sexual" id="motivo_sexual">
                                        <label for="motivo_sexual">Orientación sexual</label>
                                    </div>

                                    <div>
                                        <input type="radio" name="motivo" value="Consulta_medica" id="motivo_medica">
                                        <label for="motivo_medica">Consulta médica</label>
                                    </div>

                                    <div>
                                        <input type="radio" name="motivo" value="Prevencion_its" id="motivo_its">
                                        <label for="motivo_its">Prevención ITS</label>
                                    </div>

                                    <div>
                                        <input type="radio" name="motivo" value="otro" id="motivo_otro">
                                        <label for="motivo_otro">Otro</label>
                                    </div>

                                    <!-- Campo oculto que aparece solo si selecciona "Otro" -->
                                    <div id="otro_texto" style="display:none; margin-top:8px;">
                                        <input type="text" class="form-control" name="motivo_otro" placeholder="Especifique el motivo">
                                    </div>
                                </div>

                                <script>
                                    // Mostrar el campo de texto si se selecciona "Otro"
                                    document.querySelectorAll('input[name="motivo"]').forEach((radio) => {
                                        radio.addEventListener('change', function() {
                                            const otro = document.getElementById('motivo_otro').checked;
                                            document.getElementById('otro_texto').style.display = otro ? 'block' : 'none';
                                        });
                                    });
                                </script>
                             

                               
                                <div class="form-group">
                                <label>Fecha y hora de la cita</label>
                                <input type="datetime-local" name="fecha_hora" class="form-control" required>
                                </div>
                                <br>
                                <input type="submit" name="BtnAgendar" value="Agendar Cita" class="btn btn-primary btn-user btn-block">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>

