<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear recursos educativos</title>

    <style>

  #archivo {
    display: none;
  }

  .file-label {
    display: inline-block;
    padding: 10px 20px;
    background-color: #e75494; 
    color: white;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    transition: background 0.3s;
  }

  .file-label:hover {
    background-color: #c13d75; 
  }


  #file-chosen {
    margin-left: 10px;
    font-style: italic;
    color: #555;
  }
</style>
</head>
<body>
<div class="container">
    <center>
        <a href="dashboard.php?mod=recursos_educativos">Ver</a> | <a href="dashboard.php?mod=crear_recurso">Crear</a> | <a href="dashboard.php?mod=consultar_recurso">Consultar</a>
        <br><br>
        <h1>Crear recursos educativos</h1>
    </center>
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12 p-4">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Añade un recurso educativo</h1>
                    </div>

                    <form action="../cod_recurso.php" method="post" class="user" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" name="titulo" class="form-control form-control-user" placeholder="Título" maxlength="100" required>
                        </div>

                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="Id_tipo" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="1">Texto</option>
                                <option value="2">Imagen</option>
                                <option value="3">Audio</option>
                                <option value="4">Video</option>
                                <option value="5">Documento</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Categoría</label>
                            <select name="Id_categoria" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="1">Anatomía y fisiología sexual</option>
                                <option value="2">Desarrollo sexual y etapas de la vida (ciclo vital)</option>
                                <option value="3">Pubertad y cambios físicos/psicológicos</option>
                                <option value="4">Reproducción, embarazo y opciones reproductivas</option>
                                <option value="5">Anticoncepción y planificación familiar</option>
                                <option value="6">Infecciones de transmisión sexual (ITS) y prevención</option>
                                <option value="7">Consentimiento, límites y relaciones saludables</option>
                                <option value="8">Violencia sexual, abuso y protección</option>
                                <option value="9">Orientación sexual y identidad de género</option>
                                <option value="10">Placer sexual, intimidad y afectividad</option>
                                <option value="11">Salud mental, imagen corporal y autoestima sexual</option>
                                <option value="12">Derechos sexuales y reproductivos, marco legal y ético</option>
                                <option value="13">Educación para la prevención y respuesta ante riesgos (incluye drogas y sexualidad)</option>
                                <option value="14">Salud sexual y servicios (acceso y uso)</option>
                                <option value="15">Tecnología, pornografía y sexualidad digital</option>
                                <option value="16">Cultura, religiones, valores y diversidad sociocultural</option>
                                <option value="17">Alfabetización mediática y pornografía</option>
                                <option value="18">Sexualidad y discapacidad / inclusión</option>
                                <option value="19">Ética, toma de decisiones y responsabilidades sociales</option>
                                <option value="20">Evaluación, investigación y monitoreo en educación sexual</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <textarea name="resumen" class="form-control" rows="4" maxlength="1000" placeholder="Resumen (máx 1000 caracteres)" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="archivo" class="form-control">Archivo:</label>
                            <input type="file" name="archivo" id="archivo">
                            <span id="file-chosen">Ningún archivo seleccionado</span>

                            <script>
                            const fileInput = document.getElementById("archivo");
                            const fileChosen = document.getElementById("file-chosen");

                            fileInput.addEventListener("change", function () {
                                if (fileInput.files.length > 0) {
                                fileChosen.textContent = fileInput.files[0].name;
                                } else {
                                fileChosen.textContent = "Ningún archivo seleccionado";
                                }
                            });
                            </script>
                        </div>


                        <input type="submit" name="BtnCrearRecurso" value="Crear recurso" class="btn btn-primary btn-user btn-block">
                    </form>

                    <hr>
                    <div class="text-center">
                        <a class="small" href="dashboard.php">Volver al dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

