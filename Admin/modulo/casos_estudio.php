<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casos de estudio</title>
</head>
<body>
    <center>
        <h1>Casos de estudio</h1>
    </center>
    <div class="container py-5">
    <!-- Avatar y Puntos -->
    <div class="d-flex align-items-center mb-5">
      <img src="https://via.placeholder.com/80" alt="Avatar" class="rounded-circle me-3">
      <div>
        <h5 class="mb-0">Estudiante: <strong>Nombre</strong></h5>
        <p class="mb-0">Puntos acumulados: <span id="puntos-total" class="badge bg-success">0 pts</span></p>
      </div>
    </div>

    <!-- Contenedor de casos -->
    <div id="casos">
      <!-- Caso 1 - Paso 1 -->
      <div class="card mb-4 shadow-sm" id="caso1-paso1">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">Caso 1: La Fiesta Inesperada - Parte 1</h5>
        </div>
        <div class="card-body">
          <p class="card-text">Sofía está en una fiesta con sus amigos en la casa de Ana. La música está alta, todos ríen y bailan. De repente, su pareja le sugiere subir a una habitación privada para estar a solas.</p>
          <div class="list-group">
            <button class="list-group-item list-group-item-action" data-puntos="15" data-siguiente="caso1-paso2">Comunicar límites y hablar de protección</button>
            <button class="list-group-item list-group-item-action" data-puntos="5" data-siguiente="caso1-paso2">Aceptar sin pensarlo</button>
            <button class="list-group-item list-group-item-action" data-puntos="10" data-siguiente="caso1-paso2">Buscar consejo de una amiga</button>
          </div>
        </div>
      </div>
      
      <!-- Caso 1 - Paso 2 (oculto inicialmente) -->
      <div class="card mb-4 shadow-sm d-none" id="caso1-paso2">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">Caso 1: La Fiesta Inesperada - Parte 2</h5>
        </div>
        <div class="card-body">
          <p class="card-text">Dependiendo de tu decisión, la situación avanza:</p>
          <ul>
            <li>Si hablaste de protección, tu pareja agradece la claridad y propone buscar condones juntos.</li>
            <li>Si aceptaste sin pensar, notas que tu pareja parece incómoda al hablar de protección.</li>
            <li>Si pediste consejo, tu amiga te ayuda a sentirte más segura y tu pareja lo respeta.</li>
          </ul>
          <p class="card-text">Ahora, ¿qué harás a continuación?</p>
          <div class="list-group">
            <button class="list-group-item list-group-item-action" data-puntos="20" data-siguiente="caso2-paso1">Buscar condones antes de continuar</button>
            <button class="list-group-item list-group-item-action" data-puntos="5" data-siguiente="caso2-paso1">Seguir sin protección</button>
            <button class="list-group-item list-group-item-action" data-puntos="15" data-siguiente="caso2-paso1">Pausar la situación y conversar de nuevo</button>
          </div>
        </div>
      </div>

      <!-- Caso 2 - Paso 1 (inicia oculto) -->
      <div class="card mb-4 shadow-sm d-none" id="caso2-paso1">
        <div class="card-header bg-secondary text-white">
          <h5 class="card-title mb-0">Caso 2: La Conversación Difícil - Parte 1</h5>
        </div>
        <div class="card-body">
          <p class="card-text">Luis y Carla llevan saliendo unos meses. Carla siente que es momento de hablar sobre métodos anticonceptivos, pero teme la reacción de Luis.</p>
          <div class="list-group">
            <button class="list-group-item list-group-item-action" data-puntos="20" data-siguiente="caso2-paso2">Preparar información y datos</button>
            <button class="list-group-item list-group-item-action" data-puntos="10" data-siguiente="caso2-paso2">Mencionarlo de forma casual</button>
            <button class="list-group-item list-group-item-action" data-puntos="5" data-siguiente="caso2-paso2">Evitar el tema</button>
          </div>
        </div>
      </div>

      <!-- Caso 2 - Paso 2 (oculto inicialmente) -->
      <div class="card mb-4 shadow-sm d-none" id="caso2-paso2">
        <div class="card-header bg-secondary text-white">
          <h5 class="card-title mb-0">Caso 2: La Conversación Difícil - Parte 2</h5>
        </div>
        <div class="card-body">
          <p class="card-text">Según tu enfoque:</p>
          <ul>
            <li>Con datos: Luis aprecia tu preparación y propone ir juntos a la clínica.</li>
            <li>Casual: Luis parece sorprendido y pide tiempo para pensar.</li>
            <li>Evitar: Carla se queda con dudas y la relación puede tensarse.</li>
          </ul>
          <p class="card-text">¿Qué harás ahora?</p>
          <div class="list-group">
            <button class="list-group-item list-group-item-action" data-puntos="25">Solicitar apoyo profesional juntos</button>
            <button class="list-group-item list-group-item-action" data-puntos="10">Buscar más información por separado</button>
            <button class="list-group-item list-group-item-action" data-puntos="5">Dejarlo por ahora</button>
          </div>
        </div>
      </div>

    </div>

    <!-- Progreso de Puntos para Recompensas -->
    <div class="mb-5">
      <h6>Desbloquea Recompensas para tu Avatar</h6>
      <div class="progress" style="height: 25px;">
        <div id="barra-progreso" class="progress-bar bg-info" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
      </div>
      <small class="text-muted">Necesitas 100 pts para la primera recompensa</small>
    </div>

    <!-- Recompensas Disponibles -->
    <div class="row gx-3 gy-3">
      <div class="col-md-4">
        <div class="card text-center">
          <img src="https://via.placeholder.com/150" class="card-img-top" alt="Icono Corazón">
          <div class="card-body">
            <h6 class="card-title">Icono Corazón</h6>
            <p class="card-text">100 pts</p>
            <button class="btn btn-sm btn-outline-primary">Reclamar</button>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center opacity-50">
          <img src="https://via.placeholder.com/150" class="card-img-top" alt="Sombrero Divertido">
          <div class="card-body">
            <h6 class="card-title">Sombrero Divertido</h6>
            <p class="card-text">200 pts</p>
            <button class="btn btn-sm btn-outline-secondary" disabled>No disponible</button>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center opacity-50">
          <img src="https://via.placeholder.com/150" class="card-img-top" alt="Gafas Cool">
          <div class="card-body">
            <h6 class="card-title">Gafas Cool</h6>
            <p class="card-text">300 pts</p>
            <button class="btn btn-sm btn-outline-secondary" disabled>No disponible</button>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.list-group-item-action').forEach(btn => {
      btn.addEventListener('click', () => {
        const puntos = parseInt(btn.getAttribute('data-puntos'), 10);
        const siguiente = btn.getAttribute('data-siguiente');
        // Actualizar puntos totales
        const totalElem = document.getElementById('puntos-total');
        let total = parseInt(totalElem.textContent, 10) + puntos;
        totalElem.textContent = total + ' pts';
        // Actualizar barra de progreso
        const barra = document.getElementById('barra-progreso');
        const porcentaje = Math.min((total / 100) * 100, 100);
        barra.style.width = porcentaje + '%';
        barra.textContent = porcentaje + '%';
        barra.setAttribute('aria-valuenow', porcentaje);
        // Ocultar tarjeta actual
        btn.closest('.card').classList.add('d-none');
        // Mostrar siguiente paso si existe
        if (siguiente) {
          document.getElementById(siguiente).classList.remove('d-none');
        }
      });
    });
  </script>
</body>
</html>