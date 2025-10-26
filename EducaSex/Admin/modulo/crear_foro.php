<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Foro</title>
</head>
<body>
    <center>
        <a href="dashboard.php?mod=ver_foro">Ver</a> | <a href="dashboard.php?mod=crear_foro">Crear</a>
        <?php if ($rol == 1 || $rol == 3) { ?> | <a href="dashboard.php?mod=foro">Gestionar</a>
        <?php } ?>
        <br><br>
        <h1>Crear foro</h1>
    </center>
    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12 d-lg-block bg-login-image text-center" style="align-content: center;">
                        <img src="img/EducaSex.png" alt="" style="border-radius: 30px; max-width: 200px;">
                    </div>
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">¡Crea un nuevo foro!</h1>
                            </div>

                            <form action="../cod_foro.php" method="post" class="user">
                                
                               
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" 
                                           name="titulo_foro" placeholder="Título del foro" required>
                                </div>

                           
                                <div class="form-group">
                                    <textarea class="form-control" 
                                              name="contenido" rows="4" placeholder="Contenido del foro" required></textarea>
                                </div>

     
                                <div class="form-group">
                                    <label>Categoría</label>
                                    <select name="id_categoria" class="form-control" required>
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
                                    <label for="documento">Documento del solicitante</label>
                                    <input type="text" class="form-control" name="documento" placeholder="Documento del creador" value="<?php echo $_SESSION['user']; ?>" readonly>
                                </div>

                                
                                
                                <input type="submit" name="BtnCrearForo" value="Crear foro" 
                                       class="btn btn-primary btn-user btn-block">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
