<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarios</title>
</head>
<body>
    
    <div class="container-fluid">
        <center>
            <h1>Foro de dudas</h1>
        </center>
        <h1 class="h3 mb-4 text-gray-800">Comentarios</h1>

        <!-- Formulario de nuevo comentario -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Dejar un comentario</h6>
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="comentarioText">Tu comentario</label>
                        <textarea class="form-control" id="comentarioText" rows="3" placeholder="Escribe aquí..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>

        <!-- Lista de comentarios -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Comentarios recientes</h6>
            </div>
            <div class="card-body">
            <!-- Comentario individual -->
            <div class="media mb-4">
                <img class="mr-3 rounded-circle" src="img/undraw_profile.svg" width="50" alt="Avatar">
                <div class="media-body">
                <h6 class="mt-0 mb-1">Camilo Pérez <small class="text-muted">· Hace 2 horas</small></h6>
                ¡Excelente contenido! Me ha sido muy útil.
                </div>
            </div>
            <div class="media mb-4">
                <img class="mr-3 rounded-circle" src="img/undraw_profile.svg" width="50" alt="Avatar">
                <div class="media-body">
                <h6 class="mt-0 mb-1">María Gómez <small class="text-muted">· Ayer</small></h6>
                Gracias por compartir, espero más actualizaciones.
                </div>
            </div>
            <div class="media">
                <img class="mr-3 rounded-circle" src="img/undraw_profile.svg" width="50" alt="Avatar">
                <div class="media-body">
                <h6 class="mt-0 mb-1">Juan Rodríguez <small class="text-muted">· Hace 3 días</small></h6>
                Muy buen diseño, ¡felicidades al equipo!
                </div>
            </div>
            </div>
        </div>
    </div>

</body>
</html>