<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
</head>
<body>
<div class="container-fluid">


<div class="row mb-4">
  <div class="col-lg-12">
    <h1 class="h3 mb-0 text-gray-800">Chats</h1>
  </div>
</div>

<div class="row">


  <div class="col-lg-4 mb-4">
    <div class="card shadow-sm">
      <div class="card-header py-2">
        <input type="text" class="form-control form-control-sm" placeholder="Buscar amigos…">
      </div>
      <div class="card-body p-0" style="height: 600px; overflow-y: auto;">
        <!-- Conversación 1 -->
        <a href="#" class="d-flex align-items-center px-3 py-2 text-decoration-none text-dark border-bottom">
          <img src="img/undraw_profile.svg" alt="Carlos" class="rounded-circle mr-3" style="width:50px; height:50px;">
          <div>
            <strong>Carlos Martínez</strong><br>
            <small class="text-muted">¿Estás allí?</small>
          </div>
          <span class="ml-auto badge badge-danger badge-pill">2</span>
        </a>
        <!-- Conversación 2 -->
        <a href="#" class="d-flex align-items-center px-3 py-2 text-decoration-none text-dark border-bottom">
          <img src="img/undraw_profile.svg" alt="María" class="rounded-circle mr-3" style="width:50px; height:50px;">
          <div>
            <strong>María López</strong><br>
            <small class="text-muted">¡Gracias!</small>
          </div>
        </a>
        <!-- Conversación 3 -->
        <a href="#" class="d-flex align-items-center px-3 py-2 text-decoration-none text-dark border-bottom">
          <img src="img/undraw_profile.svg" alt="Ana" class="rounded-circle mr-3" style="width:50px; height:50px;">
          <div>
            <strong>Ana García</strong><br>
            <small class="text-muted">¿Viste mi mensaje?</small>
          </div>
          <span class="ml-auto text-muted"><small>5h</small></span>
        </a>
        <!-- Conversación 4 -->
        <a href="#" class="d-flex align-items-center px-3 py-2 text-decoration-none text-dark border-bottom">
          <img src="img/undraw_profile.svg" alt="Luis" class="rounded-circle mr-3" style="width:50px; height:50px;">
          <div>
            <strong>Luis Rodríguez</strong><br>
            <small class="text-muted">OK</small>
          </div>
        </a>
        <!-- Conversación 5 -->
        <a href="#" class="d-flex align-items-center px-3 py-2 text-decoration-none text-dark">
          <img src="img/undraw_profile.svg" alt="Sofía" class="rounded-circle mr-3" style="width:50px; height:50px;">
          <div>
            <strong>Sofía Pérez</strong><br>
            <small class="text-muted">Nos vemos mañana</small>
          </div>
        </a>
      </div>
    </div>
  </div>

 <!-- ventana chat -->
  <div class="col-lg-8 mb-4">
    <div class="card shadow-sm" style="height: 650px; display: flex; flex-direction: column;">
      <!-- Header del chat -->
      <div class="card-header d-flex align-items-center py-2">
        <img src="img/undraw_profile.svg" alt="Carlos" class="rounded-circle mr-3" style="width:50px; height:50px;">
        <div>
          <strong>Carlos Martínez</strong><br>
          <small class="text-muted">En línea</small>
        </div>
        <div class="ml-auto">
          <button class="btn btn-link btn-sm text-muted">
            <i class="fas fa-camera"></i>
          </button>
          <button class="btn btn-link btn-sm text-muted">
            <i class="fas fa-info-circle"></i>
          </button>
        </div>
      </div>

      <!-- Cuerpo del chat -->
      <div class="card-body" style="flex: 1; overflow-y: auto; background-color: #f7f7f7;">
        <!-- Mensaje recibido -->
        <div class="d-flex mb-3">
          <img src="img/undraw_profile.svg" alt="Carlos" class="rounded-circle mr-2" style="width:40px; height:40px;">
          <div>
            <div class="bg-white p-2 rounded-right rounded-bottom" style="max-width: 70%;">
              <p class="mb-1">¡Hola! ¿Cómo estás?</p>
              <small class="text-muted">10:15 AM</small>
            </div>
          </div>
        </div>

        <!-- Mensaje enviado -->
        <div class="d-flex justify-content-end mb-3">
          <div>
            <div class="bg-primary text-white p-2 rounded-left rounded-bottom" style="max-width: 70%;">
              <p class="mb-1">Bien, gracias. ¿Y tú?</p>
              <small class="text-light">10:17 AM</small>
            </div>
          </div>
          <img src="img/undraw_profile.svg" alt="Tu Avatar" class="rounded-circle ml-2" style="width:40px; height:40px;">
        </div>

        <!-- Mensaje recibido con imagen -->
        <div class="d-flex mb-3">
          <img src="img/undraw_profile.svg" alt="Carlos" class="rounded-circle mr-2" style="width:40px; height:40px;">
          <div>
            <div class="bg-white p-2 rounded-right rounded-bottom" style="max-width: 70%;">
              <p class="mb-2">Mira esta infografía sobre salud sexual:</p>
              <img src="img/ejemplo_infografia.jpg" alt="Infografía" class="img-fluid rounded mb-1">
              <small class="text-muted">10:20 AM</small>
            </div>
          </div>
        </div>

        <!-- Mensaje enviado -->
        <div class="d-flex justify-content-end mb-3">
          <div>
            <div class="bg-primary text-white p-2 rounded-left rounded-bottom" style="max-width: 70%;">
              <p class="mb-1">¡Gracias, se ve muy útil!</p>
              <small class="text-light">10:22 AM</small>
            </div>
          </div>
          <img src="img/undraw_profile.svg" alt="Tu Avatar" class="rounded-circle ml-2" style="width:40px; height:40px;">
        </div>
      </div>

      <!-- Barra de envío de mensaje -->
      <div class="card-footer py-2">
        <form class="d-flex align-items-center">
          <button class="btn btn-link text-muted mr-2">
            <i class="fas fa-plus-circle fa-lg"></i>
          </button>
          <input type="text" class="form-control form-control-sm mr-2" placeholder="Escribe un mensaje...">
          <button class="btn btn-primary btn-sm">
            <i class="fas fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div>
  </div>

</div>


</div>
</body>
</html>