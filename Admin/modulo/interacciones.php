<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interacciones sociales</title>
</head>
<body>
<div class="container-fluid">

<!-- Título de la página -->
<div class="row mb-4">
  <div class="col-lg-12">
    <h1 class="h3 mb-0 text-gray-800">Interacciones Sociales</h1>
  </div>
</div>

<!-- ==============================
     FORMULARIO DE NUEVA PUBLICACIÓN
     ============================== -->
<div class="row mb-4">
  <div class="col-lg-12">
    <div class="card shadow">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">
          <i class="fas fa-pencil-alt"></i> ¿Qué estás pensando?
        </h6>
      </div>
      <div class="card-body">
        <form>
          <div class="form-group d-flex align-items-start">
            <img src="img/undraw_profile.svg" alt="Tu Avatar" class="rounded-circle mr-3" style="width:50px; height:50px;">
            <textarea class="form-control" rows="3" placeholder="Escribe algo…"></textarea>
          </div>
          <div class="text-right">
            <button class="btn btn-success btn-sm">
              <i class="fas fa-paper-plane"></i> Publicar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ==============================
     MURO DE PUBLICACIONES
     ============================== -->
<div class="row">

  <!-- Publicación #1 -->
  <div class="col-lg-12 mb-4">
    <div class="card shadow-sm">
      <!-- Encabezado de la publicación -->
      <div class="card-header d-flex align-items-center">
        <img src="img/undraw_profile.svg" alt="Avatar Carlos" class="rounded-circle mr-3" style="width:50px; height:50px;">
        <div>
          <div class="d-flex align-items-center">
            <strong>Carlos Martínez</strong>
            <button class="btn btn-outline-primary btn-sm ml-3">
              <i class="fas fa-user-plus"></i> Agregar Amigo
            </button>
          </div>
          <small class="text-muted">Publicado el 18 May 2025 a las 10:45</small>
        </div>
        <div class="ml-auto dropdown">
          <a href="#" class="text-muted" data-toggle="dropdown">
            <i class="fas fa-ellipsis-v"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#">Editar</a>
            <a class="dropdown-item" href="#">Eliminar</a>
            <a class="dropdown-item" href="#">Reportar</a>
          </div>
        </div>
      </div>
      <!-- Cuerpo de la publicación -->
      <div class="card-body">
        <p>¡Hoy aprendí mucho sobre consentimiento y la importancia de respetar los límites de los demás! 😊</p>
        <img src="img/ejemplo_consentimiento.jpg" alt="Consentimiento" class="img-fluid rounded mb-3">
      </div>
      <!-- Footer con botones de interacción -->
      <div class="card-footer d-flex justify-content-between align-items-center">
        <div>
          <button class="btn btn-light btn-sm">
            <i class="far fa-thumbs-up"></i> Me gusta <span class="badge badge-light">24</span>
          </button>
          <button class="btn btn-light btn-sm">
            <i class="far fa-comment"></i> Comentar <span class="badge badge-light">5</span>
          </button>
          <button class="btn btn-light btn-sm">
            <i class="fas fa-share"></i> Compartir
          </button>
        </div>
        <a href="#" class="small text-primary">Ver comentarios</a>
      </div>
    </div>
  </div>

  <!-- Publicación #2 -->
  <div class="col-lg-12 mb-4">
    <div class="card shadow-sm">
      <div class="card-header d-flex align-items-center">
        <img src="img/undraw_profile.svg" alt="Avatar María" class="rounded-circle mr-3" style="width:50px; height:50px;">
        <div>
          <div class="d-flex align-items-center">
            <strong>Martin López</strong>
            <button class="btn btn-outline-primary btn-sm ml-3">
              <i class="fas fa-user-plus"></i> Agregar Amigo
            </button>
          </div>
          <small class="text-muted">Publicado el 17 May 2025 a las 16:20</small>
        </div>
        <div class="ml-auto dropdown">
          <a href="#" class="text-muted" data-toggle="dropdown">
            <i class="fas fa-ellipsis-v"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#">Editar</a>
            <a class="dropdown-item" href="#">Eliminar</a>
            <a class="dropdown-item" href="#">Reportar</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <p>Les comparto este artículo sobre métodos anticonceptivos: <a href="https://ejemplo.com/metodos">https://ejemplo.com/metodos</a></p>
      </div>
      <div class="card-footer d-flex justify-content-between align-items-center">
        <div>
          <button class="btn btn-light btn-sm">
            <i class="far fa-thumbs-up"></i> Me gusta <span class="badge badge-light">15</span>
          </button>
          <button class="btn btn-light btn-sm">
            <i class="far fa-comment"></i> Comentar <span class="badge badge-light">3</span>
          </button>
          <button class="btn btn-light btn-sm">
            <i class="fas fa-share"></i> Compartir
          </button>
        </div>
        <a href="#" class="small text-primary">Ver comentarios</a>
      </div>
    </div>
  </div>

  <!-- Publicación #3 -->
  <div class="col-lg-12 mb-4">
    <div class="card shadow-sm">
      <div class="card-header d-flex align-items-center">
        <img src="img/undraw_profile.svg" alt="Avatar Ana" class="rounded-circle mr-3" style="width:50px; height:50px;">
        <div>
          <div class="d-flex align-items-center">
            <strong>Anonimo</strong>
            <button class="btn btn-outline-primary btn-sm ml-3">
              <i class="fas fa-user-plus"></i> Agregar Amigo
            </button>
          </div>
          <small class="text-muted">Publicado el 16 May 2025 a las 09:10</small>
        </div>
        <div class="ml-auto dropdown">
          <a href="#" class="text-muted" data-toggle="dropdown">
            <i class="fas fa-ellipsis-v"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#">Editar</a>
            <a class="dropdown-item" href="#">Eliminar</a>
            <a class="dropdown-item" href="#">Reportar</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <p>Me interesa saber más sobre salud emocional. ¿Alguien recomienda un buen recurso?</p>
      </div>
      <div class="card-footer d-flex justify-content-between align-items-center">
        <div>
          <button class="btn btn-light btn-sm">
            <i class="far fa-thumbs-up"></i> Me gusta <span class="badge badge-light">9</span>
          </button>
          <button class="btn btn-light btn-sm">
            <i class="far fa-comment"></i> Comentar <span class="badge badge-light">2</span>
          </button>
          <button class="btn btn-light btn-sm">
            <i class="fas fa-share"></i> Compartir
          </button>
        </div>
        <a href="#" class="small text-primary">Ver comentarios</a>
      </div>
    </div>
  </div>

</div>
<!-- FIN muro de publicaciones -->

</div>
</body>
</html>