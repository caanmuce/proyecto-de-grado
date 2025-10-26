<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar cita</title>
</head>
<body>
    <center>
        <h1>Agendar cita</h1>
    </center>
    <div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Agenda de Psicología</h1>

  <!-- Perfil del Psicólogo -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
      <h6 class="m-0 font-weight-bold text-white">Información del Psicólogo</h6>
    </div>
    <div class="card-body d-flex align-items-center">
      <img src="img/psicologo_avatar.jpg" alt="Avatar Psicólogo" class="rounded-circle mr-4" width="100" height="100">
      <div>
        <h5 class="font-weight-bold mb-2">Dra. Laura Martínez</h5>
        <p class="mb-1"><strong>Especialidad:</strong> Psicología Escolar</p>
        <p class="mb-1"><strong>Ubicación:</strong> Consultorio 3, Edificio Principal</p>
        <p class="mb-0 text-muted">La Dra. Laura Martínez está a cargo de ofrecer acompañamiento psicológico, orientación en temas de sexualidad y bienestar emocional. Si necesitas asistencia, revisa su disponibilidad y solicita una cita.</p>
      </div>
    </div>
  </div>

  <!-- Tabla de Disponibilidad -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
      <h6 class="m-0 font-weight-bold text-white">Horarios de Disponibilidad</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="thead-light">
            <tr>
              <th>Día</th>
              <th>Hora de Inicio</th>
              <th>Hora de Finalización</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Lunes</td>
              <td>09:00</td>
              <td>12:00</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary">Solicitar Cita</button>
              </td>
            </tr>
            <tr>
              <td>Miércoles</td>
              <td>13:00</td>
              <td>16:00</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary">Solicitar Cita</button>
              </td>
            </tr>
            <tr>
              <td>Viernes</td>
              <td>09:00</td>
              <td>11:00</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary">Solicitar Cita</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>