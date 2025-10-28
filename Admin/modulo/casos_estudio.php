<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Casos de estudio — Con Recap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #f790cc;
      --success-color: #198754;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
      --light-bg: #f8f9fa;
      --transition: all 0.3s ease;
    }

    body { 
      background: var(--light-bg); 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .card-step + .card-step { 
      margin-top: .75rem; 
    }
    
    .avatar-sm { 
      width: 80px; 
      height: 80px; 
      object-fit: cover; 
      border-radius: 50%; 
      transition: var(--transition);
    }
    
    .avatar-sm:hover {
      transform: scale(1.05);
    }

    .case-card {
      transition: var(--transition);
      border: 1px solid #dee2e6;
    }

    .case-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }

    .case-choice {
      transition: var(--transition);
      border: none !important;
    }

    .case-choice:hover {
      background-color: var(--primary-color) !important;
      color: white !important;
      transform: translateX(5px);
    }

    .progress {
      border-radius: 10px;
      background-color: #e9ecef;
    }

    .progress-bar {
      border-radius: 10px;
      transition: width 0.6s ease;
    }

    .reward-card {
      transition: var(--transition);
    }

    .reward-card:hover {
      transform: scale(1.02);
    }

    .btn {
      transition: var(--transition);
    }

    .loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      transition: var(--transition);
    }

    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .step-indicator {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .step-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #dee2e6;
    }

    .step-dot.active {
      background-color: var(--primary-color);
    }

    .step-dot.completed {
      background-color: var(--success-color);
    }

    .step-container {
      border: 1px solid #dee2e6;
      border-radius: 8px;
      background: #f8f9fa;
      margin-bottom: 1rem;
      transition: all 0.3s ease;
    }

    .step-container:hover {
      border-color: #f790cc;
    }

    .step-header {
      background: linear-gradient(45deg, #f790cc, #6610f2);
      color: white;
      padding: 0.75rem;
      border-radius: 8px 8px 0 0;
      font-weight: bold;
    }

    .step-content {
      padding: 1rem;
    }

    .option-input {
      margin-bottom: 0.5rem;
    }

    .option-input .input-group-text {
      background: #e3f2fd;
      border-color: #2196f3;
      color: #f790cc;
      font-weight: 500;
    }

    .add-step-btn {
      border: 2px dashed #f790cc;
      color: #f790cc;
      background: transparent;
      transition: all 0.3s ease;
    }

    .add-step-btn:hover {
      background: #f790cc;
      color: white;
    }

    @media (max-width: 768px) {
      .navbar .d-flex {
        flex-wrap: wrap;
        gap: 0.5rem;
      }
      
      .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#" aria-label="Casos de Estudio">
        📚 Casos de Estudio
      </a>
      <div class="d-flex gap-2 align-items-center">
        <button class="btn btn-outline-light btn-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#modalAddCase"
                aria-label="Crear nuevo caso">
          ➕ Nuevo caso
        </button>
        <button class="btn btn-outline-light btn-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#modalAddReward"
                aria-label="Crear nueva recompensa">
          🎁 Recompensa
        </button>
        <button class="btn btn-light btn-sm" 
                id="btn-reset-data" 
                title="Resetear datos"
                aria-label="Resetear todos los datos">
          🔄 Reset
        </button>
      </div>
    </div>
  </nav>

  <main class="container mb-5">
    <div class="row g-4">
      <aside class="col-lg-4">
        <!-- Student Panel -->
        <div class="card mb-3 shadow-sm">
          <div class="card-body d-flex gap-3 align-items-center">
            <img id="avatar-img" 
                 class="avatar-sm" 
                 src="https://placehold.co/80x80" 
                 alt="Avatar del estudiante"
                 loading="lazy"
                 onerror="this.onerror=null; this.src='https://placehold.co/80x80'">
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <h6 class="mb-0">
                    Estudiante: <span id="student-name">Nombre</span>
                  </h6>
                  <small class="text-muted" id="student-email"></small>
                </div>
                <div class="text-end">
                  <span id="puntos-total" class="badge bg-success fs-6">0 pts</span>
                </div>
              </div>
              <div class="mt-3">
                <div class="progress" style="height:18px;" role="progressbar" aria-label="Progreso de puntos">
                  <div id="barra-progreso" 
                       class="progress-bar" 
                       style="width:0%;"
                       aria-valuenow="0"
                       aria-valuemin="0"
                       aria-valuemax="100">
                    0%
                  </div>
                </div>
                <small class="text-muted">
                  Meta: <span id="meta-puntos">100</span> pts
                </small>
              </div>
            </div>
          </div>
          <div class="card-footer d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" 
                    id="btn-edit-student" 
                    data-bs-toggle="modal" 
                    data-bs-target="#modalEditStudent"
                    aria-label="Editar información del estudiante">
              ✏ Editar
            </button>
            <button class="btn btn-sm btn-outline-danger" 
                    id="btn-reset-points"
                    aria-label="Resetear puntos a cero">
              🔄 Reset puntos
            </button>
          </div>
        </div>

        <!-- Rewards Panel -->
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="d-flex align-items-center gap-2">
              🏆 Recompensas
            </h6>
            <div id="rewards-list" class="d-grid gap-2">
              <div class="text-center text-muted py-3">
                <small>Cargando recompensas...</small>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <section class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="m-0">🎯 Casos Disponibles</h4>
          <small class="text-muted">
            Haz clic en una opción para avanzar y ganar puntos
          </small>
        </div>

        <div id="casos" class="row row-cols-1 g-3">
          <div class="col">
            <div class="alert alert-info text-center">
              <div class="spinner-border spinner-border-sm me-2" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
              Cargando casos de estudio...
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Notification Container -->
  <div id="notification-container"></div>

  <!-- Recap Modal -->
  <div class="modal fade" id="modalRecap" tabindex="-1" aria-labelledby="recapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="recapModalLabel">📊 Resumen de decisiones</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div id="recap-content"></div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button id="recap-reiniciar" class="btn btn-primary">🔄 Reiniciar caso</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Case Modal -->
  <div class="modal fade" id="modalAddCase" tabindex="-1" aria-labelledby="addCaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <form id="form-case" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="caseModalTitle">➕ Crear nuevo caso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="case-id">
          
          <!-- Información básica del caso -->
          <div class="mb-4">
            <label for="case-title" class="form-label fw-bold">📝 Título del caso</label>
            <input id="case-title" 
                   required 
                   class="form-control form-control-lg" 
                   placeholder="Ej: La Fiesta Inesperada"
                   maxlength="100">
            <div class="form-text">Un título atractivo y descriptivo para tu caso de estudio</div>
          </div>

          <!-- Contenedor de pasos -->
          <div id="steps-container">
            <!-- Los pasos se generan dinámicamente -->
          </div>

          <!-- Botón para agregar más pasos -->
          <div class="text-center mb-3">
            <button type="button" class="btn add-step-btn" id="add-step-btn">
              ➕ Agregar paso
            </button>
            <div class="form-text mt-2">
              Los casos necesitan al menos 2 pasos. Máximo 5 pasos.
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-lg">💾 Guardar caso</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Add Reward Modal -->
  <div class="modal fade" id="modalAddReward" tabindex="-1" aria-labelledby="addRewardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="form-reward" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addRewardModalLabel">🎁 Crear / Editar Recompensa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="reward-id">
          <div class="mb-3">
            <label for="reward-name" class="form-label">Nombre</label>
            <input id="reward-name" 
                   class="form-control" 
                   required
                   maxlength="50"
                   placeholder="Ej: Icono Corazón">
          </div>
          <div class="mb-3">
            <label for="reward-cost" class="form-label">Costo (puntos)</label>
            <input id="reward-cost" 
                   type="number" 
                   min="1" 
                   max="9999"
                   class="form-control" 
                   required
                   placeholder="100">
          </div>
          <div class="mb-3">
            <label for="reward-img" class="form-label">URL de imagen (opcional)</label>
            <input id="reward-img" 
                   class="form-control" 
                   type="url"
                   placeholder="https://ejemplo.com/imagen.jpg">
            <div class="form-text">
              Deja vacío para usar imagen por defecto
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">💾 Guardar</button>
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Student Modal -->
  <div class="modal fade" id="modalEditStudent" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="form-student" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editStudentModalLabel">👤 Editar Estudiante</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="student-name-input" class="form-label">Nombre</label>
            <input id="student-name-input" 
                   class="form-control" 
                   required
                   maxlength="50"
                   placeholder="Nombre del estudiante">
          </div>
          <div class="mb-3">
            <label for="student-email-input" class="form-label">Email (opcional)</label>
            <input id="student-email-input" 
                   type="email"
                   class="form-control"
                   maxlength="100"
                   placeholder="estudiante@ejemplo.com">
          </div>
          <div class="mb-3">
            <label for="student-avatar-input" class="form-label">URL del avatar</label>
            <input id="student-avatar-input" 
                   type="url"
                   class="form-control" 
                   placeholder="https://ejemplo.com/avatar.jpg">
          </div>
          <div class="mb-3">
            <label for="student-meta-input" class="form-label">Meta de puntos</label>
            <input id="student-meta-input" 
                   type="number" 
                   min="10" 
                   max="99999"
                   class="form-control" 
                   value="100"
                   required>
            <div class="form-text">
              Puntos necesarios para completar la barra de progreso
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">💾 Guardar</button>
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // ==================== CONFIGURACIÓN Y CONSTANTES ====================
    const CONFIG = {
      DEFAULT_META: 100,
      NOTIFICATION_DURATION: 3000,
      DEFAULT_AVATAR: 'https://placehold.co/80x80',
      DEFAULT_REWARD_IMG: 'https://placehold.co/150x150',
      MIN_STEPS: 2,
      MAX_STEPS: 5,
      MAX_OPTIONS: 4,
      API_BASE_URL: 'http://localhost/EducaSex/Admin/api/'
    };

    // ==================== SISTEMA DE API ====================
    const DatabaseAPI = {
      async request(endpoint, options = {}) {
        const url = CONFIG.API_BASE_URL + endpoint;
        try {
          console.log('Making API request to:', url); // Debug log
          
          const response = await fetch(url, {
            headers: {
              'Content-Type': 'application/json',
              ...options.headers
            },
            ...options
          });
          
          if (!response.ok) {
            throw new Error(`HTTP Error ${response.status}: ${response.statusText}`);
          }
          
          const contentType = response.headers.get('content-type');
          if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Respuesta no es JSON:', text);
            throw new Error('La respuesta del servidor no es JSON válido');
          }
          
          return await response.json();
        } catch (error) {
          console.error('API request failed:', error);
          console.error('URL:', url);
          throw error;
        }
      },

      // Estudiantes
      async getStudent() {
        return this.request('get_student.php');
      },

      async saveStudent(studentData) {
        return this.request('save_student.php', {
          method: 'POST',
          body: JSON.stringify(studentData)
        });
      },

      // Casos
      async getCases() {
        return this.request('get_cases.php');
      },

      async saveCase(caseData) {
        const dataToSend = {
          title: caseData.title,
          steps: caseData.steps,
          categoria: 'educacion_sexual',
          dificultad: 'media',
          creado_por: 1
        };
        
        // Solo incluir ID si es numérico (para actualización)
        if (caseData.id && !isNaN(caseData.id)) {
          dataToSend.id = parseInt(caseData.id);
        }
        
        return this.request('save_case.php', {
          method: 'POST',
          body: JSON.stringify(dataToSend)
        });
      },

      async deleteCase(caseId) {
        return this.request('delete_case.php', {
          method: 'POST',
          body: JSON.stringify({ id: caseId })
        });
      },

      // Recompensas
      async getRewards() {
        return this.request('get_rewards.php');
      },

      async saveReward(rewardData) {
        return this.request('save_rewards.php', {
          method: 'POST',
          body: JSON.stringify(rewardData)
        });
      },

      async deleteReward(rewardId) {
        return this.request('delete_reward.php', {
          method: 'POST',
          body: JSON.stringify({ id: rewardId })
        });
      },

      // Progreso
      async getProgress() {
        return this.request('get_progress.php');
      },

      async saveProgress(progressData) {
        return this.request('save_progress.php', {
          method: 'POST',
          body: JSON.stringify(progressData)
        });
      },

      // Decisiones
      async saveDecision(decisionData) {
        return this.request('save_decision.php', {
          method: 'POST',
          body: JSON.stringify(decisionData)
        });
      }
    };

    // ==================== ALMACENAMIENTO EN MEMORIA ====================
    const AppData = {
      student: {
        id: null,
        name: 'Nombre',
        email: '',
        avatar: CONFIG.DEFAULT_AVATAR,
        puntos: 0,
        meta: CONFIG.DEFAULT_META
      },
      cases: [],
      rewards: [],
      caseProgress: {},
      caseChoices: {}
    };

    // ==================== FUNCIONES UTILITARIAS ====================
    const Utils = {
      uid: (prefix = 'id') => `${prefix}-${Date.now()}-${Math.random().toString(36).slice(2, 9)}`,
      
      parseOptionInput: (text) => {
        const parts = text.split('|').map(s => s.trim());
        return {
          text: parts[0] || '',
          puntos: Math.max(0, parseInt(parts[1] || '0', 10) || 0)
        };
      },
      
      escapeHtml: (str = '') => {
        return String(str)
          .replaceAll('&', '&amp;')
          .replaceAll('<', '&lt;')
          .replaceAll('>', '&gt;')
          .replaceAll('"', '&quot;')
          .replaceAll("'", '&#39;');
      },
      
      debounce: (func, delay) => {
        let timeoutId;
        return (...args) => {
          clearTimeout(timeoutId);
          timeoutId = setTimeout(() => func.apply(null, args), delay);
        };
      }
    };

    // ==================== SISTEMA DE NOTIFICACIONES ====================
    const NotificationSystem = {
      show: (message, type = 'info', duration = CONFIG.NOTIFICATION_DURATION) => {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification fade-in alert-dismissible`;
        notification.setAttribute('role', 'alert');
        notification.innerHTML = `
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        container.appendChild(notification);
        
        setTimeout(() => {
          if (notification.parentNode) {
            notification.remove();
          }
        }, duration);
      }
    };

    // ==================== SISTEMA DE MODALES ====================
    const ModalManager = {
      instances: {},
      
      init() {
        const modalElements = document.querySelectorAll('.modal');
        modalElements.forEach(modalEl => {
          this.instances[modalEl.id] = new bootstrap.Modal(modalEl, {
            backdrop: true,
            keyboard: true
          });
        });

        document.addEventListener('click', (e) => {
          if (e.target.matches('[data-bs-dismiss="modal"]')) {
            const modal = e.target.closest('.modal');
            if (modal && this.instances[modal.id]) {
              this.instances[modal.id].hide();
            }
          }
        });
      },

      show(modalId) {
        if (this.instances[modalId]) {
          this.instances[modalId].show();
        }
      },

      hide(modalId) {
        if (this.instances[modalId]) {
          this.instances[modalId].hide();
        }
      }
    };

    // ==================== CONSTRUCTOR DE CASOS ====================
    const CaseBuilder = {
      currentStepCount: 2,

      init() {
        this.renderSteps();
        document.getElementById('add-step-btn').addEventListener('click', () => {
          this.addStep();
        });
      },

      renderSteps() {
        const container = document.getElementById('steps-container');
        container.innerHTML = '';

        for (let i = 0; i < this.currentStepCount; i++) {
          this.createStepElement(i + 1, container);
        }

        this.updateAddStepButton();
      },

      createStepElement(stepNumber, container) {
        const stepDiv = document.createElement('div');
        stepDiv.className = 'step-container';
        stepDiv.dataset.stepNumber = stepNumber;

        stepDiv.innerHTML = `
          <div class="step-header d-flex justify-content-between align-items-center">
            <span>📍 Paso ${stepNumber}</span>
            ${stepNumber > CONFIG.MIN_STEPS ? 
              `<button type="button" class="btn btn-sm btn-outline-light remove-step-btn" data-step="${stepNumber}">
                ✕ Eliminar
              </button>` : 
              ''
            }
          </div>
          <div class="step-content">
            <div class="mb-3">
              <label class="form-label fw-bold">Descripción del escenario</label>
              <textarea class="form-control step-desc" 
                        rows="3" 
                        placeholder="Describe la situación que enfrentará el estudiante..." 
                        required
                        maxlength="500"
                        data-step="${stepNumber}"></textarea>
              <div class="form-text">Máximo 500 caracteres</div>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-bold">Opciones de decisión</label>
              <div class="options-container" data-step="${stepNumber}">
                ${this.createOptionInputs(stepNumber)}
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary add-option-btn mt-2" data-step="${stepNumber}">
                ➕ Agregar opción
              </button>
            </div>
          </div>
        `;

        container.appendChild(stepDiv);
        this.addStepEventListeners(stepDiv, stepNumber);
      },

      createOptionInputs(stepNumber, count = 3) {
        let html = '';
        for (let i = 0; i < count; i++) {
          const optionIndex = i + 1;
          html += `
            <div class="input-group option-input mb-2" data-option="${optionIndex}">
              <span class="input-group-text">Opción ${optionIndex}</span>
              <input type="text" 
                     class="form-control option-text" 
                     placeholder="Texto de la opción|puntos" 
                     required
                     maxlength="150"
                     data-step="${stepNumber}"
                     data-option="${optionIndex}">
              ${optionIndex > 3 ? 
                `<button type="button" class="btn btn-outline-danger remove-option-btn" data-step="${stepNumber}" data-option="${optionIndex}">
                  ✕
                </button>` : 
                ''
              }
            </div>
          `;
        }
        return html;
      },

      addStepEventListeners(stepDiv, stepNumber) {
        const removeStepBtn = stepDiv.querySelector('.remove-step-btn');
        if (removeStepBtn) {
          removeStepBtn.addEventListener('click', () => {
            this.removeStep(stepNumber);
          });
        }

        const addOptionBtn = stepDiv.querySelector('.add-option-btn');
        if (addOptionBtn) {
          addOptionBtn.addEventListener('click', () => {
            this.addOption(stepNumber);
          });
        }

        stepDiv.querySelectorAll('.remove-option-btn').forEach(btn => {
          btn.addEventListener('click', (e) => {
            const optionNumber = parseInt(e.currentTarget.dataset.option, 10);
            this.removeOption(stepNumber, optionNumber);
          });
        });
      },

      addStep() {
        if (this.currentStepCount < CONFIG.MAX_STEPS) {
          this.currentStepCount++;
          this.renderSteps();
          NotificationSystem.show(`✅ Paso ${this.currentStepCount} agregado`, 'success');
        } else {
          NotificationSystem.show(`⚠ Máximo ${CONFIG.MAX_STEPS} pasos permitidos`, 'warning');
        }
      },

      removeStep(stepNumber) {
        if (this.currentStepCount > CONFIG.MIN_STEPS) {
          this.currentStepCount--;
          this.renderSteps();
          NotificationSystem.show('✅ Paso eliminado', 'success');
        } else {
          NotificationSystem.show(`⚠ Mínimo ${CONFIG.MIN_STEPS} pasos requeridos`, 'warning');
        }
      },

      addOption(stepNumber) {
        const optionsContainer = document.querySelector(`.options-container[data-step="${stepNumber}"]`);
        if (!optionsContainer) return;
        const currentOptions = optionsContainer.querySelectorAll('.option-input').length;
        
        if (currentOptions < CONFIG.MAX_OPTIONS) {
          const newOptionIndex = currentOptions + 1;
          const newOptionHtml = `
            <div class="input-group option-input mb-2" data-option="${newOptionIndex}">
              <span class="input-group-text">Opción ${newOptionIndex}</span>
              <input type="text" 
                     class="form-control option-text" 
                     placeholder="Texto de la opción|puntos" 
                     required
                     maxlength="150"
                     data-step="${stepNumber}"
                     data-option="${newOptionIndex}">
              ${newOptionIndex > 3 ? 
                `<button type="button" class="btn btn-outline-danger remove-option-btn" data-step="${stepNumber}" data-option="${newOptionIndex}">
                  ✕
                </button>` : 
                ''
              }
            </div>
          `;
          
          optionsContainer.insertAdjacentHTML('beforeend', newOptionHtml);
          
          const newRemoveBtn = optionsContainer.querySelector(`.remove-option-btn[data-option="${newOptionIndex}"]`);
          if (newRemoveBtn) {
            newRemoveBtn.addEventListener('click', (e) => {
              const optionNumber = Number(e.currentTarget.dataset.option);
              this.removeOption(stepNumber, optionNumber);
            });
          }
          
          NotificationSystem.show('✅ Opción agregada', 'success');
        } else {
          NotificationSystem.show(`⚠ Máximo ${CONFIG.MAX_OPTIONS} opciones por paso`, 'warning');
        }
      },

      removeOption(stepNumber, optionNumber) {
        const optionsContainer = document.querySelector(`.options-container[data-step="${stepNumber}"]`);
        if (!optionsContainer) return;
        const currentOptions = optionsContainer.querySelectorAll('.option-input').length;
        
        if (currentOptions > 3) {
          const optionToRemove = optionsContainer.querySelector(`.option-input[data-option="${optionNumber}"]`);
          if (optionToRemove) optionToRemove.remove();
          this.reorderOptions(stepNumber);
          NotificationSystem.show('✅ Opción eliminada', 'success');
        } else {
          NotificationSystem.show('⚠ Mínimo 3 opciones requeridas', 'warning');
        }
      },

      reorderOptions(stepNumber) {
        const optionsContainer = document.querySelector(`.options-container[data-step="${stepNumber}"]`);
        if (!optionsContainer) return;
        const options = optionsContainer.querySelectorAll('.option-input');
        
        options.forEach((option, index) => {
          const newNumber = index + 1;
          option.dataset.option = newNumber;
          const label = option.querySelector('.input-group-text');
          if (label) label.textContent = `Opción ${newNumber}`;
          const input = option.querySelector('.option-text');
          if (input) input.dataset.option = newNumber;
          
          const removeBtn = option.querySelector('.remove-option-btn');
          if (removeBtn) {
            removeBtn.dataset.option = newNumber;
          }
        });
      },

      updateAddStepButton() {
        const addBtn = document.getElementById('add-step-btn');
        if (this.currentStepCount >= CONFIG.MAX_STEPS) {
          addBtn.disabled = true;
          addBtn.textContent = `➕ Máximo ${CONFIG.MAX_STEPS} pasos`;
        } else {
          addBtn.disabled = false;
          addBtn.textContent = '➕ Agregar paso';
        }
      },

      collectCaseData() {
        const title = document.getElementById('case-title').value.trim();
        const steps = [];

        for (let i = 1; i <= this.currentStepCount; i++) {
          const descTextarea = document.querySelector(`.step-desc[data-step="${i}"]`);
          const optionInputs = document.querySelectorAll(`.option-text[data-step="${i}"]`);
          
          if (!descTextarea || !descTextarea.value.trim()) {
            throw new Error(`La descripción del paso ${i} es obligatoria`);
          }

          const options = [];
          optionInputs.forEach(input => {
            if (input.value.trim()) {
              options.push(Utils.parseOptionInput(input.value));
            }
          });

          if (options.length < 3) {
            throw new Error(`El paso ${i} debe tener al menos 3 opciones`);
          }

          steps.push({
            desc: descTextarea.value.trim(),
            options: options
          });
        }

        return { title, steps };
      },

      loadCaseData(caseData) {
        document.getElementById('case-title').value = caseData.title || '';
        
        this.currentStepCount = Math.max(CONFIG.MIN_STEPS, caseData.steps?.length || CONFIG.MIN_STEPS);
        this.renderSteps();

        if (caseData.steps) {
          caseData.steps.forEach((step, stepIndex) => {
            const stepNumber = stepIndex + 1;
            
            const descTextarea = document.querySelector(`.step-desc[data-step="${stepNumber}"]`);
            if (descTextarea) {
              descTextarea.value = step.desc || '';
            }

            const optionsContainer = document.querySelector(`.options-container[data-step="${stepNumber}"]`);
            if (optionsContainer && step.options) {
              optionsContainer.innerHTML = '';
              
              step.options.forEach((option, optionIndex) => {
                const optionNumber = optionIndex + 1;
                const optionHtml = `
                  <div class="input-group option-input mb-2" data-option="${optionNumber}">
                    <span class="input-group-text">Opción ${optionNumber}</span>
                    <input type="text" 
                           class="form-control option-text" 
                           placeholder="Texto de la opción|puntos" 
                           required
                           maxlength="150"
                           data-step="${stepNumber}"
                           data-option="${optionNumber}"
                           value="${Utils.escapeHtml(option.text)}|${option.puntos}">
                    ${optionIndex >= 3 ? 
                      `<button type="button" class="btn btn-outline-danger remove-option-btn" data-step="${stepNumber}" data-option="${optionNumber}">
                        ✕
                      </button>` : 
                      ''
                    }
                  </div>
                `;
                optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
              });

              optionsContainer.querySelectorAll('.remove-option-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                  const optionNumber = parseInt(e.currentTarget.dataset.option, 10);
                  this.removeOption(stepNumber, optionNumber);
                });
              });
            }
          });
        }
      },

      reset() {
        this.currentStepCount = CONFIG.MIN_STEPS;
        document.getElementById('case-title').value = '';
        this.renderSteps();
      }
    };

    // ==================== ESTADO DE LA APLICACIÓN ====================
    class AppState {
      constructor() {
        this.initialized = false;
      }

      async initialize() {
        if (this.initialized) return;
        
        try {
          await this.loadPersistedData();
          this.initialized = true;
        } catch (error) {
          console.error('Error initializing app:', error);
          this.initializeDefaultData();
        }
      }

      initializeDefaultData() {
        if (AppData.cases.length === 0) {
          AppData.cases = this.getDefaultCases();
        }
        
        if (AppData.rewards.length === 0) {
          AppData.rewards = this.getDefaultRewards();
        }
      }

      async loadPersistedData() {
        try {
          // Cargar casos desde la base de datos
          const casesData = await DatabaseAPI.getCases();
          if (casesData && casesData.length > 0) {
            AppData.cases = casesData;
          }

          // Cargar recompensas
          const rewardsData = await DatabaseAPI.getRewards();
          if (rewardsData && rewardsData.length > 0) {
            AppData.rewards = rewardsData;
          }

          // Cargar progreso
          const progressData = await DatabaseAPI.getProgress();
          if (progressData) {
            AppData.caseProgress = progressData.progress || {};
            AppData.caseChoices = progressData.choices || {};
          }
        } catch (error) {
          console.error('Error loading data from database:', error);
          // Fallback a localStorage para progreso
          this.loadFromLocalStorage();
        }
      }

      loadFromLocalStorage() {
        try {
          const studentData = localStorage.getItem('studentData');
          if (studentData) {
            AppData.student = JSON.parse(studentData);
          }

          const casesData = localStorage.getItem('casesData');
          if (casesData) {
            AppData.cases = JSON.parse(casesData);
          }

          const rewardsData = localStorage.getItem('rewardsData');
          if (rewardsData) {
            AppData.rewards = JSON.parse(rewardsData);
          }

          const progressData = localStorage.getItem('caseProgress');
          if (progressData) AppData.caseProgress = JSON.parse(progressData);
          const choicesData = localStorage.getItem('caseChoices');
          if (choicesData) AppData.caseChoices = JSON.parse(choicesData);
        } catch (error) {
          console.error('Error loading from localStorage:', error);
        }
      }

      async saveDataToStorage() {
        try {
          await DatabaseAPI.saveProgress({
            progress: AppData.caseProgress,
            choices: AppData.caseChoices
          });
        } catch (error) {
          console.error('Error saving to database, falling back to localStorage:', error);
          this.saveToLocalStorage();
        }
      }

      saveToLocalStorage() {
        try {
          localStorage.setItem('studentData', JSON.stringify(AppData.student));
          localStorage.setItem('casesData', JSON.stringify(AppData.cases));
          localStorage.setItem('rewardsData', JSON.stringify(AppData.rewards));
          localStorage.setItem('caseProgress', JSON.stringify(AppData.caseProgress));
          localStorage.setItem('caseChoices', JSON.stringify(AppData.caseChoices));
        } catch (error) {
          console.error('Error saving to localStorage:', error);
        }
      }

      getDefaultRewards() {
        return [
          { 
            id: Utils.uid('r'), 
            name: '🎖 Icono Corazón', 
            cost: 100, 
            img: CONFIG.DEFAULT_REWARD_IMG 
          },
          { 
            id: Utils.uid('r'), 
            name: '🎩 Sombrero Divertido', 
            cost: 200, 
            img: CONFIG.DEFAULT_REWARD_IMG 
          },
          { 
            id: Utils.uid('r'), 
            name: '🕶 Gafas Cool', 
            cost: 300, 
            img: CONFIG.DEFAULT_REWARD_IMG 
          }
        ];
      }

      getDefaultCases() {
        return [
          {
            id: null, // La base de datos asignará el ID
            title: 'La Fiesta Inesperada',
            steps: [
              {
                desc: 'Estás en una fiesta y tu pareja te propone subir a un lugar privado. Quieres cuidar tu seguridad y bienestar.',
                options: [
                  { text: 'Comunicar mis límites y hablar de protección', puntos: 15 },
                  { text: 'Aceptar para evitar un conflicto', puntos: 5 },
                  { text: 'Buscar a una amiga o quedarme en un espacio con más gente', puntos: 10 }
                ]
              },
              {
                desc: 'Tras tu reacción, la situación avanza. Decide cómo actuar para protegerte y respetar fronteras.',
                options: [
                  { text: 'Buscar condones o protección antes de continuar', puntos: 20 },
                  { text: 'Pausar y decir que prefiero no continuar ahora', puntos: 15 },
                  { text: 'Seguir sin protección por miedo a discutir', puntos: 5 }
                ]
              }
            ]
          }
        ];
      }

      getPointsNumber() {
        const el = document.getElementById('puntos-total');
        return parseInt((el.textContent || '0').replace(/\D/g, ''), 10) || 0;
      }

      setPointsNumber(n) {
        const el = document.getElementById('puntos-total');
        el.textContent = `${n} pts`;
        
        const meta = Number(document.getElementById('meta-puntos').textContent) || CONFIG.DEFAULT_META;
        const porcentaje = Math.round(Math.min(100, (n / meta) * 100));
        
        const barra = document.getElementById('barra-progreso');
        barra.style.width = `${porcentaje}%`;
        barra.textContent = `${porcentaje}%`;
        barra.setAttribute('aria-valuenow', porcentaje.toString());
        
        AppData.student.puntos = n;
      }

      renderStudentPanel() {
        document.getElementById('student-name').textContent = AppData.student.name;
        document.getElementById('student-email').textContent = AppData.student.email || '';
        document.getElementById('avatar-img').src = AppData.student.avatar || CONFIG.DEFAULT_AVATAR;
        document.getElementById('student-name-input').value = AppData.student.name;
        document.getElementById('student-email-input').value = AppData.student.email || '';
        document.getElementById('student-avatar-input').value = AppData.student.avatar || '';
        document.getElementById('student-meta-input').value = AppData.student.meta || CONFIG.DEFAULT_META;
        document.getElementById('meta-puntos').textContent = AppData.student.meta || CONFIG.DEFAULT_META;
        this.setPointsNumber(AppData.student.puntos || 0);
      }

      renderCases() {
        const container = document.getElementById('casos');
        container.innerHTML = '';
        
        if (AppData.cases.length === 0) {
          container.innerHTML = `
            <div class="col">
              <div class="alert alert-info text-center">
                📝 No hay casos disponibles. Crea uno con "➕ Nuevo caso".
              </div>
            </div>
          `;
          return;
        }

        AppData.cases.forEach(caso => {
          const currentStepIndex = AppData.caseProgress[caso.id] ?? 0;
          const step = caso.steps[currentStepIndex] || caso.steps[caso.steps.length - 1];
          const choices = AppData.caseChoices[caso.id] || [];
          const isCompleted = currentStepIndex >= caso.steps.length - 1 && 
                            choices.length === caso.steps.length && 
                            choices.every(choice => choice !== undefined);
          
          const col = document.createElement('div');
          col.className = 'col';
          
          let stepIndicators = '';
          caso.steps.forEach((_, idx) => {
            let dotClass = 'step-dot';
            if (idx < currentStepIndex) dotClass += ' completed';
            else if (idx === currentStepIndex) dotClass += ' active';
            stepIndicators += `<div class="${dotClass}" title="Paso ${idx + 1}"></div>`;
          });

          const optionsHtml = (step.options || []).map((opt, idx) => {
            return `
              <button class="list-group-item list-group-item-action case-choice d-flex justify-content-between align-items-center" 
                      data-cid="${caso.id}" 
                      data-step="${currentStepIndex}" 
                      data-idx="${idx}"
                      ${isCompleted ? 'disabled' : ''}>
                <span>${Utils.escapeHtml(opt.text)}</span>
                <span class="badge bg-primary">${opt.puntos} pts</span>
              </button>
            `;
          }).join('');

          col.innerHTML = `
            <div class="card shadow-sm case-card fade-in">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                  <strong>${Utils.escapeHtml(caso.title)}</strong>
                  ${isCompleted ? '<span class="badge bg-success ms-2">✅ Completado</span>' : ''}
                </div>
                <div class="d-flex gap-1">
                  <button class="btn btn-sm btn-outline-secondary btn-edit-case" 
                          data-id="${caso.id}"
                          title="Editar caso">
                    ✏
                  </button>
                  <button class="btn btn-sm btn-outline-danger btn-delete-case" 
                          data-id="${caso.id}"
                          title="Eliminar caso">
                    🗑
                  </button>
                </div>
              </div>
              <div class="card-body">
                <p class="card-text">${Utils.escapeHtml(step.desc || '')}</p>
                <div class="list-group">
                  ${optionsHtml}
                </div>
              </div>
              <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="step-indicator">
                  ${stepIndicators}
                </div>
                <small class="text-muted">
                  Paso ${Math.min(currentStepIndex + 1, caso.steps.length)} de ${caso.steps.length}
                </small>
              </div>
            </div>
          `;
          container.appendChild(col);
        });
      }

      renderRewards() {
        const list = document.getElementById('rewards-list');
        list.innerHTML = '';
        const pts = this.getPointsNumber();

        if (AppData.rewards.length === 0) {
          list.innerHTML = `
            <div class="text-center text-muted py-3">
              🎁 No hay recompensas disponibles
            </div>
          `;
          return;
        }

        AppData.rewards.forEach(r => {
          const card = document.createElement('div');
          card.className = 'reward-card d-flex align-items-center gap-2 p-3 border rounded bg-white mb-2';
          
          const canClaim = pts >= (r.cost || 0);
          const imgSrc = r.img && r.img.trim() ? r.img : CONFIG.DEFAULT_REWARD_IMG;
          
          const needText = !canClaim ? `<br><small class="text-danger">Necesitas ${ (r.cost || 0) - pts } pts más</small>` : '';

          card.innerHTML = `
            <img src="${Utils.escapeHtml(imgSrc)}" 
                 alt="${Utils.escapeHtml(r.name)}" 
                 width="60" 
                 height="60" 
                 style="object-fit:cover;border-radius:8px;"
                 loading="lazy"
                 onerror="this.src='${CONFIG.DEFAULT_REWARD_IMG}'">
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <strong>${Utils.escapeHtml(r.name)}</strong><br>
                  <small class="text-muted">${r.cost} pts</small>
                  ${needText}
                </div>
                <div class="d-flex gap-1 flex-wrap">
                  <button class="btn btn-sm ${canClaim ? 'btn-success' : 'btn-outline-secondary'} btn-claim-reward" 
                          data-id="${r.id}" 
                          ${!canClaim ? 'disabled' : ''}
                          title="${canClaim ? 'Reclamar recompensa' : 'No tienes suficientes puntos'}">
                    ${canClaim ? '🎁' : '🔒'}
                  </button>
                  <button class="btn btn-sm btn-outline-primary btn-edit-reward" 
                          data-id="${r.id}"
                          title="Editar recompensa">
                    ✏
                  </button>
                  <button class="btn btn-sm btn-outline-danger btn-delete-reward" 
                          data-id="${r.id}"
                          title="Eliminar recompensa">
                    🗑
                  </button>
                </div>
              </div>
            </div>
          `;
          list.appendChild(card);
        });
      }

      async handleCaseChoice(caseId, stepIndex, optionIndex) {
        const caso = AppData.cases.find(c => c.id === caseId);
        if (!caso) return;

        const option = caso.steps[stepIndex]?.options[optionIndex];
        if (!option) return;

        // Add points
        const newPts = this.getPointsNumber() + (option.puntos || 0);
        this.setPointsNumber(newPts);

        // Save choice
        if (!Array.isArray(AppData.caseChoices[caseId])) {
          AppData.caseChoices[caseId] = [];
        }
        AppData.caseChoices[caseId][stepIndex] = optionIndex;

        // Save to database
        try {
          await DatabaseAPI.saveDecision({
            caseId,
            stepIndex,
            optionIndex,
            puntos: option.puntos
          });
        } catch (error) {
          console.error('Error saving decision:', error);
        }

        NotificationSystem.show(`✨ ¡Ganaste ${option.puntos} puntos! Decisión: "${Utils.escapeHtml(option.text)}"`, 'success');

        const lastStepIndex = caso.steps.length - 1;
        
        if (stepIndex >= lastStepIndex) {
          AppData.caseProgress[caseId] = lastStepIndex;
          setTimeout(() => this.showRecapForCase(caseId), 500);
        } else {
          const nextIndex = stepIndex + 1;
          AppData.caseProgress[caseId] = nextIndex;
        }

        await this.saveDataToStorage();
        this.renderCases();
      }

      showRecapForCase(caseId) {
        const c = AppData.cases.find(x => x.id === caseId);
        if (!c) return;

        const choices = AppData.caseChoices[caseId] || [];
        const content = document.getElementById('recap-content');
        content.innerHTML = '';

        const summaryDiv = document.createElement('div');
        summaryDiv.innerHTML = '<h5>📋 Decisiones tomadas</h5>';
        
        const list = document.createElement('ol');
        list.className = 'list-group list-group-numbered';
        
        let totalPoints = 0;
        
        c.steps.forEach((step, stepIndex) => {
          const chosenIdx = choices[stepIndex];
          const chosen = typeof chosenIdx === 'number' ? step.options[chosenIdx] : null;
          
          const li = document.createElement('li');
          li.className = 'list-group-item';
          
          li.innerHTML = `
            <strong>${Utils.escapeHtml(step.desc)}</strong><br>
            <em>Tu elección:</em> ${chosen ? 
              `${Utils.escapeHtml(chosen.text)} <span class="badge bg-primary">${chosen.puntos} pts</span>` : 
              '<span class="text-muted">❌ No seleccionaste</span>'
            }
          `;
          
          list.appendChild(li);
          
          if (chosen) {
            totalPoints += chosen.puntos;
          }
        });
        
        summaryDiv.appendChild(list);
        content.appendChild(summaryDiv);

        const evalDiv = document.createElement('div');
        evalDiv.className = 'mt-4';
        evalDiv.innerHTML = '<h5>📊 Evaluación por decisión</h5>';
        
        const evalList = document.createElement('ul');
        evalList.className = 'list-group';
        
        c.steps.forEach((step, stepIndex) => {
          const chosenIdx = AppData.caseChoices[caseId] ? AppData.caseChoices[caseId][stepIndex] : undefined;
          const chosen = typeof chosenIdx === 'number' ? step.options[chosenIdx] : null;
          const pts = chosen ? chosen.puntos : 0;
          
          let evaluation, badgeClass;
          if (!chosen) {
            evaluation = '❌ No seleccionaste una opción en este paso.';
            badgeClass = 'bg-secondary';
          } else if (pts >= 20) {
            evaluation = '🌟 Excelente — decisión que favorece la seguridad y el respeto.';
            badgeClass = 'bg-success';
          } else if (pts >= 15) {
            evaluation = '✅ Buena — promueve cuidado y comunicación.';
            badgeClass = 'bg-info';
          } else if (pts >= 10) {
            evaluation = '⚠ Aceptable — hay aspectos a mejorar.';
            badgeClass = 'bg-warning';
          } else {
            evaluation = '🚨 Riesgo — esta decisión puede traer consecuencias negativas.';
            badgeClass = 'bg-danger';
          }
          
          const li = document.createElement('li');
          li.className = 'list-group-item d-flex justify-content-between align-items-start';
          li.innerHTML = `
            <div>
              <strong>Paso ${stepIndex + 1}:</strong> ${evaluation}
            </div>
            <span class="badge ${badgeClass}">${pts} pts</span>
          `;
          evalList.appendChild(li);
        });
        
        evalDiv.appendChild(evalList);
        content.appendChild(evalDiv);

        const scoreDiv = document.createElement('div');
        scoreDiv.className = 'mt-4 text-center';
        
        let scoreClass = 'success';
        if (totalPoints < 20) scoreClass = 'danger';
        else if (totalPoints < 35) scoreClass = 'warning';
        
        scoreDiv.innerHTML = `
          <div class="alert alert-${scoreClass}">
            <h5 class="mb-0">🏆 Puntuación total del caso: ${totalPoints} pts</h5>
          </div>
        `;
        content.appendChild(scoreDiv);

        ModalManager.show('modalRecap');

        document.getElementById('recap-reiniciar').onclick = () => {
          AppData.caseProgress[caseId] = 0;
          AppData.caseChoices[caseId] = [];
          this.renderCases();
          ModalManager.hide('modalRecap');
          NotificationSystem.show('🔄 Caso reiniciado correctamente', 'info');
        };
      }

      openCaseModalForEdit(id) {
        const c = AppData.cases.find(x => x.id === id);
        if (!c) return;

        document.getElementById('case-id').value = c.id;
        document.getElementById('caseModalTitle').textContent = '✏ Editar caso';
        
        CaseBuilder.loadCaseData(c);
        ModalManager.show('modalAddCase');
      }

      async deleteCase(id) {
        if (confirm('🗑 ¿Estás seguro de que quieres eliminar este caso?')) {
          try {
            await DatabaseAPI.deleteCase(id);
            AppData.cases = AppData.cases.filter(c => c.id !== id);
            delete AppData.caseProgress[id];
            delete AppData.caseChoices[id];
            this.renderCases();
            NotificationSystem.show('✅ Caso eliminado correctamente', 'success');
          } catch (error) {
            NotificationSystem.show('❌ Error al eliminar el caso', 'danger');
          }
        }
      }

      async deleteReward(id) {
        if (confirm('🗑 ¿Estás seguro de que quieres eliminar esta recompensa?')) {
          try {
            await DatabaseAPI.deleteReward(id);
            AppData.rewards = AppData.rewards.filter(r => r.id !== id);
            this.renderRewards();
            NotificationSystem.show('✅ Recompensa eliminada correctamente', 'success');
          } catch (error) {
            NotificationSystem.show('❌ Error al eliminar la recompensa', 'danger');
          }
        }
      }

      resetAllData() {
        if (confirm('⚠ ¿Estás seguro de que quieres resetear toda la aplicación? Se perderán todos los datos.')) {
          AppData.student = {
            name: 'Nombre',
            email: '',
            avatar: CONFIG.DEFAULT_AVATAR,
            puntos: 0,
            meta: CONFIG.DEFAULT_META
          };
          AppData.cases = this.getDefaultCases();
          AppData.rewards = this.getDefaultRewards();
          AppData.caseProgress = {};
          AppData.caseChoices = {};
          
          this.saveDataToStorage();
          NotificationSystem.show('🔄 Aplicación reseteada correctamente', 'success');
          setTimeout(() => location.reload(), 1000);
        }
      }

      resetPoints() {
        if (confirm('🔄 ¿Estás seguro de que quieres resetear los puntos a 0?')) {
          this.setPointsNumber(0);
          this.saveDataToStorage();
          NotificationSystem.show('✅ Puntos reseteados correctamente', 'info');
        }
      }
    }

    // ==================== INICIALIZACIÓN DE LA APLICACIÓN ====================
    const app = new AppState();

    // Event Delegation
    document.addEventListener('click', (e) => {
      const choiceBtn = e.target.closest('.case-choice');
      if (choiceBtn && !choiceBtn.disabled) {
        choiceBtn.classList.add('loading');
        const caseId = choiceBtn.getAttribute('data-cid');
        const stepIndex = Number(choiceBtn.getAttribute('data-step'));
        const optionIndex = Number(choiceBtn.getAttribute('data-idx'));
        
        setTimeout(() => {
          app.handleCaseChoice(caseId, stepIndex, optionIndex);
          choiceBtn.classList.remove('loading');
        }, 100);
        return;
      }

      const editCaseBtn = e.target.closest('.btn-edit-case');
      if (editCaseBtn) {
        app.openCaseModalForEdit(editCaseBtn.getAttribute('data-id'));
        return;
      }

      const deleteCaseBtn = e.target.closest('.btn-delete-case');
      if (deleteCaseBtn) {
        const id = deleteCaseBtn.getAttribute('data-id');
        app.deleteCase(id);
        return;
      }

      const claimBtn = e.target.closest('.btn-claim-reward');
      if (claimBtn) {
        const id = claimBtn.getAttribute('data-id');
        const reward = AppData.rewards.find(r => r.id === id);
        if (!reward) return;

        const pts = app.getPointsNumber();
        if (pts >= reward.cost) {
          if (confirm(`🎁 ¿Deseas reclamar "${reward.name}" por ${reward.cost} pts?`)) {
            app.setPointsNumber(pts - reward.cost);
            app.saveDataToStorage();
            NotificationSystem.show(`🎉 ¡Recompensa reclamada: ${reward.name}!`, 'success');
          }
        } else {
          NotificationSystem.show('❌ No tienes suficientes puntos', 'warning');
        }
        return;
      }

      const editRewardBtn = e.target.closest('.btn-edit-reward');
      if (editRewardBtn) {
        const id = editRewardBtn.getAttribute('data-id');
        const reward = AppData.rewards.find(r => r.id === id);
        if (!reward) return;

        document.getElementById('reward-id').value = reward.id;
        document.getElementById('reward-name').value = reward.name;
        document.getElementById('reward-cost').value = reward.cost;
        document.getElementById('reward-img').value = reward.img || '';

        ModalManager.show('modalAddReward');
        return;
      }

      const deleteRewardBtn = e.target.closest('.btn-delete-reward');
      if (deleteRewardBtn) {
        const id = deleteRewardBtn.getAttribute('data-id');
        app.deleteReward(id);
        return;
      }
    });

    // Form submissions
    document.getElementById('form-case').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      try {
        const caseData = CaseBuilder.collectCaseData();
        const idInput = document.getElementById('case-id');
        const existingId = idInput.value;

        const caseObj = {
          title: caseData.title,
          steps: caseData.steps
        };

        // Solo incluir ID si existe (para edición)
        if (existingId) {
          caseObj.id = parseInt(existingId);
        }

        const response = await DatabaseAPI.saveCase(caseObj);

        if (response.success) {
          // Actualizar o agregar el caso en AppData
          const index = AppData.cases.findIndex(c => c.id === response.id);
          if (index >= 0) {
            AppData.cases[index] = { ...AppData.cases[index], ...caseObj, id: response.id };
          } else {
            AppData.cases.push({ ...caseObj, id: response.id });
          }

          app.renderCases();
          NotificationSystem.show('✅ Caso guardado correctamente', 'success');
          
          ModalManager.hide('modalAddCase');
          CaseBuilder.reset();
          document.getElementById('case-id').value = '';
          document.getElementById('caseModalTitle').textContent = '➕ Crear nuevo caso';
        }
        
      } catch (error) {
        NotificationSystem.show(`❌ Error: ${error.message}`, 'danger');
      }
    });

    document.getElementById('form-reward').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const id = document.getElementById('reward-id').value || Utils.uid('r');
      const name = document.getElementById('reward-name').value.trim();
      const cost = Math.max(1, Number(document.getElementById('reward-cost').value || 1));
      const img = document.getElementById('reward-img').value.trim() || CONFIG.DEFAULT_REWARD_IMG;

      if (!name) {
        NotificationSystem.show('❌ El nombre es obligatorio', 'danger');
        return;
      }

      const rewardObj = { id, name, cost, img };
      
      try {
        await DatabaseAPI.saveReward(rewardObj);
        
        const existsIndex = AppData.rewards.findIndex(r => r.id === id);
        if (existsIndex >= 0) {
          AppData.rewards[existsIndex] = rewardObj;
          NotificationSystem.show('✅ Recompensa actualizada correctamente', 'success');
        } else {
          AppData.rewards.push(rewardObj);
          NotificationSystem.show('✅ Recompensa creada correctamente', 'success');
        }

        app.renderRewards();
        
        ModalManager.hide('modalAddReward');
        e.target.reset();
        document.getElementById('reward-id').value = '';
      } catch (error) {
        NotificationSystem.show('❌ Error al guardar la recompensa', 'danger');
      }
    });

    document.getElementById('form-student').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const name = document.getElementById('student-name-input').value.trim();
      if (!name) {
        NotificationSystem.show('❌ El nombre es obligatorio', 'danger');
        return;
      }

      AppData.student.name = name;
      AppData.student.email = document.getElementById('student-email-input').value.trim();
      AppData.student.avatar = document.getElementById('student-avatar-input').value.trim() || CONFIG.DEFAULT_AVATAR;
      AppData.student.meta = Math.max(10, Number(document.getElementById('student-meta-input').value || CONFIG.DEFAULT_META));
      
      document.getElementById('meta-puntos').textContent = AppData.student.meta;
      
      try {
        await DatabaseAPI.saveStudent(AppData.student);
        app.renderStudentPanel();
        ModalManager.hide('modalEditStudent');
        NotificationSystem.show('✅ Estudiante actualizado correctamente', 'success');
      } catch (error) {
        NotificationSystem.show('❌ Error al guardar el estudiante', 'danger');
      }
    });

    // Button event listeners
    document.getElementById('btn-reset-points').addEventListener('click', () => {
      app.resetPoints();
    });

    document.getElementById('btn-reset-data').addEventListener('click', () => {
      app.resetAllData();
    });

    // Modal event listeners
    document.getElementById('modalAddCase').addEventListener('hidden.bs.modal', () => {
      if (!document.getElementById('case-id').value) {
        CaseBuilder.reset();
        document.getElementById('caseModalTitle').textContent = '➕ Crear nuevo caso';
      }
    });

    document.getElementById('modalAddReward').addEventListener('hidden.bs.modal', () => {
      document.getElementById('form-reward').reset();
      document.getElementById('reward-id').value = '';
    });

    // Initialize the application
    async function initializeApp() {
      ModalManager.init();
      CaseBuilder.init();
      
      await app.initialize();
      
      if (typeof AppData.student.puntos !== 'number') {
        AppData.student.puntos = 0;
      }
      
      app.renderStudentPanel();
      app.renderCases();
      app.renderRewards();
      
      setTimeout(() => {
        NotificationSystem.show('🎯 ¡Bienvenido a Casos de Estudio! Selecciona un caso para comenzar.', 'info');
      }, 500);
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initializeApp);
    } else {
      initializeApp();
    }
  </script>
</body>
</html>