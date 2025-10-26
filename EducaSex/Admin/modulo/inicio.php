<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - EducaSex</title>

  <style>
    body {
      background-color: #f8f9fc;
    }
    .card-img-top {
      height: 150px;
      object-fit: cover;
    }
    .post {
      background: #fff;
      border-radius: 12px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0px 2px 8px rgba(0,0,0,0.05);
    }
    .post img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .bot:hover{
      transform: scale(1.2);
      transition: .4s;

    }
  </style>
</head>
<body>
<div class="container mt-4">
  <h1 class="text-center mb-4">Bienvenido a <span class="text-primary">EducaSex</span></h1>

  <!-- Tarjetas principales -->
  <div class="row text-center mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <img src="img/salud.jpeg" class="card-img-top" alt="Recursos">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-book-open text-primary"></i> Recursos</h5>
          <p class="card-text">Artículos y videos sobre salud sexual.</p>
          <a href="dashboard.php?mod=recursos_educativos" class="btn btn-primary btn-sm bot">Ver más</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <img src="img/foro.jpg" class="card-img-top" alt="Foro">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-comments text-success"></i> Foro</h5>
          <p class="card-text">Participa y comparte tus dudas.</p>
          <a href="dashboard.php?mod=ver_foro" class="btn btn-success btn-sm bot">Entrar</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <img src="img/chatbot.jpeg" class="card-img-top" alt="Asistente Virtual">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-robot text-info"></i> Asistente Virtual</h5>
          <p class="card-text">Chatea ahora con nuestro bot.</p>
          <a href="dashboard.php?mod=chatbot" class="btn btn-info btn-sm text-white bot">Abrir</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <img src="img/cita.jpg" class="card-img-top" alt="Cita">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-calendar-alt text-warning"></i> Agendar cita</h5>
          <p class="card-text">Agenda tu cita con el psicólogo.</p>
          <a href="dashboard.php?mod=agendar_cita" class="btn btn-warning btn-sm bot">Agendar</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Sección estilo "red social" -->
  <div class="row">
    <div class="col-md-8">
      <h5 class="mb-3"><i class="fas fa-rss text-danger"></i> Lo que está pasando ahora</h5>
      
      <div class="post">
        <div class="d-flex align-items-center mb-2">
          <img src="img/laura.jpeg" alt="user">
          <strong>@Laura</strong>
        </div>
        <p>Hoy aprendí sobre métodos anticonceptivos naturales en los recursos de EducaSex 🌱 ¡Súper útil!</p>
      </div>

      <div class="post">
        <div class="d-flex align-items-center mb-2">
          <img src="img/carlos.jpeg" alt="user">
          <strong>@Carlos</strong>
        </div>
        <p>Me animé a participar en el foro y recibí muy buenos consejos. Recomiendo mucho unirse 💬</p>
      </div>
    </div>

    <!-- Columna derecha -->
    <div class="col-md-4">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-success text-white">Novedades</div>
        <div class="card-body">
          <p><i class="fas fa-check-circle text-success"></i> 10 nuevos artículos publicados esta semana</p>
          <p><i class="fas fa-users text-info"></i> 5 usuarios participaron en el foro hoy</p>
          <p><i class="fas fa-calendar-check text-warning"></i> 2 citas ya agendadas con la psicóloga</p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
