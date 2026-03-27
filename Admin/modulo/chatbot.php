<?php
// Admin/modulo/chatbot_advanced.php
// NO colocar salida antes de <?php en archivos que incluyan este módulo.
?>

<style>
/* Estilos mejorados para el chatbot - Mantengo los existentes y añado nuevos */
.chatbot-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 0;
    overflow: hidden;
}

.chat-header {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    padding: 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.chat-area {
    background: #f8f9fa;
    height: 500px;
    overflow-y: auto;
    padding: 1rem;
    scroll-behavior: smooth;
}

.msg-bubble {
    max-width: 80%;
    margin-bottom: 1rem;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.msg-user {
    margin-left: auto;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border-radius: 20px 20px 5px 20px;
    padding: 12px 16px;
    box-shadow: 0 2px 10px rgba(79, 172, 254, 0.3);
}

.msg-bot {
    margin-right: auto;
    background: white;
    color: #333;
    border-radius: 20px 20px 20px 5px;
    padding: 12px 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #667eea;
}

/* Nuevos estilos para funcionalidades avanzadas */
.training-panel {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.analytics-chart {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.knowledge-item {
    border-left: 4px solid #667eea;
    background: #f8f9fa;
    margin-bottom: 0.5rem;
    padding: 0.75rem;
    border-radius: 0 5px 5px 0;
}

.feedback-buttons {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.feedback-btn {
    padding: 0.25rem 0.75rem;
    border: 1px solid #ddd;
    border-radius: 15px;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.8rem;
}

.feedback-btn:hover {
    background: #f8f9fa;
}

.feedback-btn.active {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

/* Mantengo el resto de estilos existentes... */
.typing-indicator {
    display: none;
    background: white;
    border-radius: 20px;
    padding: 12px 16px;
    margin: 1rem 0;
    max-width: 80px;
}

.typing-dots {
    display: flex;
    gap: 4px;
}

.typing-dots span {
    height: 8px;
    width: 8px;
    background: #999;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-dots span:nth-child(2) { animation-delay: 0.2s; }
.typing-dots span:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-10px); }
}

.chat-input {
    background: rgba(255,255,255,0.95);
    border: none;
    border-radius: 25px;
    padding: 12px 20px;
    outline: none;
    backdrop-filter: blur(10px);
}

.chat-input:focus {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
}

.send-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.send-btn:hover {
    transform: scale(1.05);
}

.quick-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 1rem;
}

.quick-btn {
    background: rgba(255,255,255,0.8);
    border: 1px solid rgba(102, 126, 234, 0.3);
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
}

.quick-btn:hover {
    background: rgba(102, 126, 234, 0.1);
    border-color: #667eea;
}

.message-meta {
    font-size: 0.75rem;
    color: #666;
    margin-top: 8px;
    opacity: 0;
    transition: opacity 0.2s;
}

.msg-bubble:hover .message-meta {
    opacity: 1;
}

.analytics-panel {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
}

.stat-card {
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    backdrop-filter: blur(10px);
}

.connection-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-online {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.status-offline {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>

<!-- Chatbot Module Avanzado con Machine Learning -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                🤖 SEIN - Sistema de IA Avanzado con Machine Learning
                <span id="connectionStatus" class="connection-status status-offline float-end">
                    <span class="status-dot"></span>
                    Conectando...
                </span>
            </h1>
        </div>

        <!-- Panel Principal del Chat -->
        <div class="col-lg-8">
            <?php if ($rol == 1 || $rol == 3) { ?>
            <!-- Panel de Entrenamiento - SOLO ADMINISTRADORES -->
            <div class="training-panel mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="text-white mb-2">🎯 Sistema de Entrenamiento de IA</h6>
                        <small class="text-white-80">La IA aprende automáticamente de cada interacción</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#trainingModal">
                            🏋️ Entrenar IA
                        </button>
                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#knowledgeModal">
                            📚 Ver Conocimiento
                        </button>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="card shadow-lg chatbot-container">
                <div class="chat-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-0 text-white font-weight-bold">🧠 SEIN - IA Avanzada</h6>
                            <small class="text-white-50">Sistema de ML integrado - Aprendiendo continuamente</small>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success me-2">ML Activo</span>
                            <button id="voiceBtn" class="btn btn-outline-light btn-sm" title="Activar voz">🎤</button>
                            <button id="settingsBtn" class="btn btn-outline-light btn-sm" title="Configuración">⚙</button>
                        </div>
                    </div>
                </div>

                <div id="chat" class="chat-area"></div>

                <div id="typingIndicator" class="typing-indicator ms-3">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <div class="p-3" style="background: rgba(255,255,255,0.95);">
                    <!-- Botones de acción rápida -->
                    <div id="quickActions" class="quick-actions">
                        <button class="quick-btn" data-text="¿Qué métodos anticonceptivos existen?">💊 Anticonceptivos</button>
                        <button class="quick-btn" data-text="Información sobre ITS/ETS">🦠 ITS/ETS</button>
                        <button class="quick-btn" data-text="¿Qué es el consentimiento?">🤝 Consentimiento</button>
                        <button class="quick-btn" data-text="Salud emocional y sexual">💚 Bienestar</button>
                    </div>

                    <form id="sendForm" class="d-flex gap-2 mt-3 align-items-end">
                        <div class="flex-grow-1">
                            <input id="messageInput" 
                                   type="text" 
                                   class="form-control chat-input" 
                                   placeholder="Escribe tu mensaje aquí..." 
                                   required 
                                   autocomplete="off"
                                   maxlength="500" />
                            <div class="text-muted small mt-1">
                                <span id="charCount">0</span>/500 caracteres
                            </div>
                        </div>
                        <button id="sendBtn" class="send-btn" type="submit" title="Enviar mensaje">
                            ➤
                        </button>
                        <div class="d-flex flex-column gap-1">
                            <button id="refreshBtn" type="button" class="btn btn-outline-secondary btn-sm" title="Actualizar chat">↻</button>
                            <button id="clearAll" type="button" class="btn btn-outline-danger btn-sm" title="Limpiar chat">🗑</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($rol == 1 || $rol == 3) { ?>
        <!-- Panel Lateral de Analíticas y Control - SOLO ADMINISTRADORES -->
        <div class="col-lg-4">
            <!-- Panel de Estado y Analytics -->
            <div class="analytics-panel mb-4">
                <h6 class="font-weight-bold mb-3">📊 Métricas en Tiempo Real</h6>
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="stat-card">
                            <div class="h4 mb-1" id="messageCount">0</div>
                            <small>Mensajes hoy</small>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <div class="stat-card">
                            <div class="h4 mb-1" id="responseTime">~0.8s</div>
                            <small>Tiempo respuesta</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="h4 mb-1" id="accuracyScore">95%</div>
                            <small>Precisión IA</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="h4 mb-1" id="learningCount">128</div>
                            <small>Datos aprendidos</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <small>🧠 Machine Learning: <span id="mlStatus" class="badge badge-success">Activo</span></small><br>
                    <small>📚 Base conocimientos: <span id="kbSize" class="badge badge-info">1,247 items</span></small><br>
                    <small>🔄 Último entrenamiento: <span id="lastTraining">Hace 2 min</span></small>
                </div>
            </div>

            <!-- Gráfico de Temas Populares -->
            <div class="analytics-chart">
                <h6 class="font-weight-bold mb-3">📈 Temas Más Consultados</h6>
                <canvas id="topicsChart" height="150"></canvas>
            </div>

            <!-- Panel de Control de IA -->
            <div class="card shadow mb-3">
                <div class="card-header bg-gradient-info text-white">
                    <h6 class="m-0">🛠 Control de IA</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small">Nivel de Aprendizaje:</label>
                        <select id="learningLevel" class="form-select form-select-sm">
                            <option value="basic">Básico</option>
                            <option value="standard" selected>Estándar</option>
                            <option value="advanced">Avanzado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Umbral de Confianza:</label>
                        <input type="range" class="form-range" id="confidenceThreshold" min="0.1" max="1.0" step="0.1" value="0.7">
                        <small class="text-muted" id="thresholdValue">70%</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-primary" id="exportKnowledge">
                            💾 Exportar Conocimiento
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="trainModel">
                            🏋️ Entrenar Modelo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Panel de Error/Debug -->
            <div id="errorPanel" class="card shadow mt-4" style="display: none;">
                <div class="card-header bg-danger text-white">
                    <h6 class="m-0">⚠ Información del Sistema</h6>
                </div>
                <div class="card-body">
                    <pre id="errorContent" class="mb-0 small text-danger"></pre>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php if ($rol == 1 || $rol == 3) { ?>
<!-- Modal de Entrenamiento - SOLO ADMINISTRADORES -->
<div class="modal fade" id="trainingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">🏋️ Entrenamiento Manual de IA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="trainingForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select class="form-select" name="category" required>
                                    <option value="">Seleccionar categoría...</option>
                                    <option value="salud_sexual">Salud Sexual</option>
                                    <option value="anticonceptivos">Anticonceptivos</option>
                                    <option value="its_ets">ITS/ETS</option>
                                    <option value="consentimiento">Consentimiento</option>
                                    <option value="bienestar_emocional">Bienestar Emocional</option>
                                    <option value="relaciones">Relaciones</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Subcategoría</label>
                                <input type="text" class="form-control" name="subcategory" placeholder="Ej: metodos_barrera">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pregunta/Consulta</label>
                        <textarea class="form-control" name="question" rows="2" placeholder="¿Qué métodos anticonceptivos existen?" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Respuesta</label>
                        <textarea class="form-control" name="answer" rows="3" placeholder="Existen varios tipos de métodos anticonceptivos..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Palabras Clave (separadas por comas)</label>
                        <input type="text" class="form-control" name="keywords" placeholder="anticonceptivos, preservativo, píldora, diu">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Umbral de Confianza</label>
                                <input type="range" class="form-range" name="confidence_threshold" min="0.1" max="1.0" step="0.1" value="0.7">
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Clave del Tema</label>
                                <input type="text" class="form-control" name="topic_key" placeholder="anticonceptivos_general">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="mt-2">
                    <small class="text-muted">Nota: el guardado enviará JSON al endpoint y mostrará errores claros si ocurre algo.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="saveTraining">💾 Guardar Conocimiento</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Modal de Base de Conocimiento - VISIBLE PARA TODOS -->
<div class="modal fade" id="knowledgeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title">📚 Base de Conocimiento de la IA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchKnowledge" class="form-control" placeholder="Buscar en conocimiento...">
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-sm btn-outline-primary" id="refreshKnowledge">🔄 Actualizar</button>
                    </div>
                </div>
                <div id="knowledgeList" class="row">
                    <!-- Aquí se cargará la lista de conocimiento -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- En el head de chatbot.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Dependencias adicionales (Chart.js) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/*
  Sistema de IA Avanzado con Machine Learning - VERSIÓN CORREGIDA CON RUTAS
  Y FUNCIÓN saveTrainingData IMPLEMENTADA
*/
(function () {
    // LÓGICA MEJORADA PARA RUTAS - Compatible con estudiantes y admin
    const API_PATH = (function() {
        // Si estamos en el área de Admin, usar ruta de Admin
        // Si estamos en el dashboard principal, usar ruta pública
        const currentPath = window.location.pathname;
        
        if (currentPath.includes('/Admin/')) {
            // Usuario en área de administración
            return 'modulo/chatbot_api.php';
        } else {
            // Usuario estudiante - usar ruta relativa al Admin
            return '../Admin/modulo/chatbot_api.php';
        }
    })();

    console.log('API Path:', API_PATH); // Para debug

    // Variables globales
    let isConnected = false;
    let isTyping = false;
    let messageCount = 0;
    let lastResponseTime = 0;
    let analyticsData = {};
    let speechRecognition = null; // Variable para reconocimiento de voz
    
    let settings = {
        volume: 0.8,
        autoScroll: true,
        responseSpeed: 'normal',
        voiceEnabled: false,
        voice: null,
        confidenceThreshold: 0.7,
        learningLevel: 'standard'
    };

    // Elementos DOM
    const chat = document.getElementById('chat');
    const sendForm = document.getElementById('sendForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const refreshBtn = document.getElementById('refreshBtn');
    const clearAllBtn = document.getElementById('clearAll');
    const typingIndicator = document.getElementById('typingIndicator');
    const connectionStatus = document.getElementById('connectionStatus');
    const charCount = document.getElementById('charCount');
    const quickActions = document.getElementById('quickActions');
    const voiceBtn = document.getElementById('voiceBtn');
    const settingsBtn = document.getElementById('settingsBtn');
    const errorPanel = document.getElementById('errorPanel');
    const errorContent = document.getElementById('errorContent');

    // Solo definir estos elementos si existen (para estudiantes no existirán)
    const trainingForm = document.getElementById('trainingForm');
    const knowledgeList = document.getElementById('knowledgeList');
    const topicsChart = document.getElementById('topicsChart');

    // ---------- FUNCIÓN updateCharCount ----------
    function updateCharCount() {
        if (!messageInput || !charCount) return;
        const count = messageInput.value.length;
        charCount.textContent = count;
        // Cambiar color según la cantidad de caracteres
        if (count > 400) {
            charCount.style.color = '#dc3545'; // Rojo
        } else if (count > 300) {
            charCount.style.color = '#ffc107'; // Amarillo
        } else {
            charCount.style.color = '#6c757d'; // Gris
        }
    }

    // ---------- FUNCIÓN saveTrainingData FALTANTE ----------
    async function saveTrainingData() {
        <?php if ($rol == 1 || $rol == 3) { ?>
        if (!trainingForm) return;
        
        const formData = new FormData(trainingForm);
        const data = {
            category: formData.get('category'),
            subcategory: formData.get('subcategory'),
            question: formData.get('question'),
            answer: formData.get('answer'),
            keywords: formData.get('keywords'),
            confidence_threshold: formData.get('confidence_threshold'),
            topic_key: formData.get('topic_key')
        };

        // Validación básica
        if (!data.category || !data.question || !data.answer) {
            showNotification('Por favor completa los campos requeridos', 'error');
            return;
        }

        try {
            const result = await api('train_manual', data);
            if (result.success) {
                showNotification('Conocimiento guardado exitosamente', 'success');
                // Cerrar modal y limpiar formulario
                const modal = bootstrap.Modal.getInstance(document.getElementById('trainingModal'));
                if (modal) modal.hide();
                trainingForm.reset();
                // Actualizar la base de conocimiento
                loadKnowledgeBase();
            } else {
                showError(`Error al guardar: ${result.error || 'Error desconocido'}`);
                showNotification('Error al guardar el conocimiento', 'error');
            }
        } catch (error) {
            console.error('Error en saveTrainingData:', error);
            showError(`Error de conexión: ${error.message}`);
            showNotification('Error de conexión', 'error');
        }
        <?php } ?>
    }

    // ---------- UTILIDADES MEJORADAS ----------
    function loadSettings() {
        const saved = localStorage.getItem('chatbot_settings');
        if (saved) {
            try { 
                const savedSettings = JSON.parse(saved);
                settings = { ...settings, ...savedSettings }; 
            } catch(e){ 
                console.error('Error loading settings:', e); 
            }
        }
    }

    function saveSettings() { 
        localStorage.setItem('chatbot_settings', JSON.stringify(settings)); 
    }

    // Función initSpeech 
    function initSpeech() {
        if ('webkitSpeechRecognition' in window) {
            speechRecognition = new webkitSpeechRecognition();
            speechRecognition.continuous = false;
            speechRecognition.interimResults = false;
            speechRecognition.lang = 'es-ES';
            speechRecognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                if (messageInput) { 
                    messageInput.value = transcript; 
                    updateCharCount(); 
                }
            };
            speechRecognition.onerror = function(event) {
                showError('Error en reconocimiento de voz: ' + (event.error || 'unknown'));
            };
        }
    }

    function toggleVoice() {
        if (!speechRecognition) { 
            showNotification('Reconocimiento de voz no disponible', 'error'); 
            return; 
        }
        if (settings.voiceEnabled) {
            speechRecognition.stop();
            settings.voiceEnabled = false;
            if (voiceBtn) { 
                voiceBtn.textContent = '🎤'; 
                voiceBtn.classList.remove('btn-danger'); 
                voiceBtn.classList.add('btn-outline-light'); 
            }
        } else {
            speechRecognition.start();
            settings.voiceEnabled = true;
            if (voiceBtn) { 
                voiceBtn.textContent = '🔴'; 
                voiceBtn.classList.remove('btn-outline-light'); 
                voiceBtn.classList.add('btn-danger'); 
            }
        }
    }

    function updateConnectionStatus(connected) {
        isConnected = connected;
        if (!connectionStatus) return;
        if (connected) {
            connectionStatus.className = 'connection-status status-online';
            connectionStatus.innerHTML = '<span class="status-dot"></span> Conectado';
        } else {
            connectionStatus.className = 'connection-status status-offline';
            connectionStatus.innerHTML = '<span class="status-dot"></span> Desconectado';
        }
    }

    function showTyping() {
        if (!isTyping) {
            isTyping = true;
            if (typingIndicator) typingIndicator.style.display = 'block';
            if (settings.autoScroll) scrollToBottom();
        }
    }

    function hideTyping() { 
        isTyping = false; 
        if (typingIndicator) typingIndicator.style.display = 'none'; 
    }

    function scrollToBottom() { 
        if (chat) chat.scrollTop = chat.scrollHeight; 
    }

    function escapeHtml(text) { 
        const d = document.createElement('div'); 
        d.textContent = text; 
        return d.innerHTML; 
    }

    function formatMessage(text) {
        text = String(text || '');
        text = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener">$1</a>');
        text = text.replace(/\n/g, '<br>');
        text = text.replace(/\*(.*?)\*/g, '<strong>$1</strong>');
        const emojiMap = { ':)': '😊', ':-)': '😊', ':(': '😢', ':-(': '😢', ':D': '😃', ':-D': '😃', ';)': '😉', ';-)': '😉' };
        for (const [k, v] of Object.entries(emojiMap)) text = text.split(k).join(v);
        return text;
    }

    // API helper mejorado con mejor manejo de errores
    async function api(action, data = {}) {
        const startTime = Date.now();
        try {
            const form = new FormData();
            form.append('action', action);
            for (const [key, value] of Object.entries(data)) form.append(key, value);

            console.log('Enviando solicitud a:', API_PATH, 'Action:', action);
            
            const response = await fetch(API_PATH, { 
                method: 'POST', 
                body: form 
            });
            
            lastResponseTime = (Date.now() - startTime) / 1000;
            const rtEl = document.getElementById('responseTime');
            if (rtEl) rtEl.textContent = `${lastResponseTime.toFixed(1)}s`;

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const text = await response.text();

            try {
                const json = JSON.parse(text);
                updateConnectionStatus(true);
                hideError();
                return json;
            } catch (parseError) {
                console.error('Respuesta no JSON:', text);
                showError(`Respuesta no JSON del servidor.\n\nRespuesta recibida:\n${String(text).substring(0, 1000)}...`);
                updateConnectionStatus(false);
                return { success: false, error: 'Respuesta no JSON', raw: text };
            }
        } catch (error) {
            console.error('Error API:', error);
            showError(`Error de conexión: ${error.message}\n\nRuta intentada: ${API_PATH}`);
            updateConnectionStatus(false);
            return { success: false, error: error.message };
        }
    }

    function showError(message) {
        if (!errorContent || !errorPanel) return;
        errorContent.textContent = message;
        errorPanel.style.display = 'block';
    }

    function hideError() { 
        if (!errorPanel) return; 
        errorPanel.style.display = 'none'; 
    }

    function showNotification(message, type = 'info') {
        const toast = document.createElement('div');
        const bg = type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info');
        toast.className = `toast align-items-center text-white bg-${bg} border-0`;
        toast.setAttribute('role', 'alert');
        toast.style.position = 'fixed';
        toast.style.right = '20px';
        toast.style.bottom = '20px';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${escapeHtml(message)}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
            </div>
        `;
        document.body.appendChild(toast);
        try {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            toast.addEventListener('hidden.bs.toast', () => { 
                if (toast.parentNode) toast.parentNode.removeChild(toast); 
            });
        } catch (e) {
            setTimeout(() => { 
                if (toast.parentNode) toast.parentNode.removeChild(toast); 
            }, 4000);
        }
    }

    function renderMessages(messages) {
        if (!chat) return;
        chat.innerHTML = '';
        if (!messages || !messages.length) {
            chat.innerHTML = `
                <div class="text-center text-muted py-5">
                    <h4>👋 ¡Hola! Soy SEIN</h4>
                    <p>Tu asistente virtual especializado en educación sexual y salud reproductiva.<br>¿En qué puedo ayudarte hoy?</p>
                </div>`;
            return;
        }
        messages.forEach((message) => {
            const messageEl = document.createElement('div');
            messageEl.className = 'msg-bubble d-flex flex-column';
            const isBot = message.sender === 'Bot';
            const bubbleClass = isBot ? 'msg-bot' : 'msg-user';
            const formattedMessage = formatMessage(escapeHtml(message.message));
            const time = message.created_at ? new Date(message.created_at).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' }) : '';
            messageEl.innerHTML = `
                <div class="${bubbleClass}">
                    <div class="message-text">${formattedMessage}</div>
                    <div class="message-meta">
                        <span>${time}</span>
                        <span class="float-end">
                            <button class="btn btn-sm btn-link text-muted edit-btn p-0 me-1" data-id="${message.id}" title="Editar">✏</button>
                            <button class="btn btn-sm btn-link text-muted delete-btn p-0" data-id="${message.id}" title="Eliminar">🗑</button>
                        </span>
                    </div>
                </div>`;
            chat.appendChild(messageEl);
        });
        messageCount = messages.length;
        const msgCountEl = document.getElementById('messageCount');
        if (msgCountEl) msgCountEl.textContent = Math.floor(messageCount / 2);
        if (settings.autoScroll) setTimeout(scrollToBottom, 100);
    }

    async function loadMessages() {
        const result = await api('read');
        if (result.success) {
            renderMessages(result.messages);
        } else {
            console.error('Error al cargar mensajes:', result.error);
            // Mostrar mensaje de error amigable
            if (!chat) return;
            chat.innerHTML = `
                <div class="text-center text-muted py-5">
                    <h4>👋 ¡Hola! Soy SEIN</h4>
                    <p>Tu asistente virtual especializado en educación sexual y salud reproductiva.<br>¿En qué puedo ayudarte hoy?</p>
                    <small class="text-warning">⚠️ Estado: Conectando con el servidor...</small>
                </div>`;
        }
    }

    async function handleSendMessage(e) {
        e.preventDefault();
        const text = messageInput ? messageInput.value.trim() : '';
        if (!text || isTyping) return;
        
        if (sendBtn) sendBtn.disabled = true;
        showTyping();
        
        const result = await api('create', { sender: 'Tú', message: text });
        hideTyping();
        
        if (sendBtn) sendBtn.disabled = false;
        if (result.success) {
            if (messageInput) { 
                messageInput.value = ''; 
                updateCharCount(); 
            }
            await loadMessages();
            showNotification('Mensaje enviado', 'success');
        } else {
            showError(`Error al enviar: ${result.error || 'desconocido'}`);
            showNotification('Error al enviar mensaje', 'error');
        }
    }

    async function handleClearAll() {
        if (!confirm('¿Estás seguro de que quieres eliminar toda la conversación?')) return;
        const result = await api('read');
        if (!result.success) { 
            showError('No se pudieron obtener los mensajes'); 
            return; 
        }
        for (const message of result.messages) {
            await api('delete', { id: message.id });
        }
        await loadMessages();
        showNotification('Conversación limpiada', 'success');
    }

    // ---------- FUNCIONES SOLO PARA ADMINISTRADORES ----------
    async function loadAnalytics() {
        // Solo cargar analytics si el usuario es admin
        <?php if ($rol == 1 || $rol == 3) { ?>
        try {
            const result = await api('analytics');
            if (result.success) {
                analyticsData = result.analytics;
                updateAnalyticsDisplay();
                updateCharts();
            }
        } catch (error) { 
            console.error('Error loading analytics:', error); 
        }
        <?php } ?>
    }

    async function loadKnowledgeBase() {
        // Solo cargar knowledge base si el usuario es admin
        <?php if ($rol == 1 || $rol == 3) { ?>
        try {
            const result = await api('get_knowledge');
            if (result.success && knowledgeList) {
                displayKnowledgeList(result.knowledge);
            }
        } catch (error) { 
            console.error('Error loading knowledge base:', error); 
        }
        <?php } ?>
    }

    function displayKnowledgeList(knowledge) {
        if (!knowledgeList) return;
        knowledgeList.innerHTML = knowledge.map(item => `
            <div class="col-md-6 mb-3">
                <div class="knowledge-item">
                    <h6 class="mb-1">${escapeHtml(item.question)}</h6>
                    <p class="mb-1 small text-muted">${escapeHtml((item.answer||'').substring(0, 100))}...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-primary">${item.category}</small>
                        <small class="text-muted">Usos: ${item.usage_count||0}</small>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function updateAnalyticsDisplay() {
        <?php if ($rol == 1 || $rol == 3) { ?>
        const accuracyEl = document.getElementById('accuracyScore');
        const learningEl = document.getElementById('learningCount');
        const kbSizeEl = document.getElementById('kbSize');
        if (accuracyEl) accuracyEl.textContent = `${Math.round((analyticsData.accuracy || 0.85) * 100)}%`;
        if (learningEl) learningEl.textContent = analyticsData.total_learned || 0;
        if (kbSizeEl) kbSizeEl.textContent = `${analyticsData.knowledge_count || 0} items`;
        <?php } ?>
    }

    function initCharts() {
        <?php if ($rol == 1 || $rol == 3) { ?>
        if (!topicsChart) return;
        const ctx = topicsChart.getContext('2d');
        window.topicsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Anticonceptivos', 'ITS/ETS', 'Consentimiento', 'Embarazo', 'Bienestar'],
                datasets: [{
                    label: 'Consultas',
                    data: [65, 45, 38, 32, 28]
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
        <?php } ?>
    }

    function updateCharts() {
        <?php if ($rol == 1 || $rol == 3) { ?>
        if (window.topicsChart && analyticsData.topics) {
            window.topicsChart.data.datasets[0].data = analyticsData.topics;
            window.topicsChart.update();
        }
        <?php } ?>
    }

    // ---------- FUNCIONES DE ENTRENAMIENTO (SOLO ADMIN) ----------
    async function trainModel() {
        <?php if ($rol == 1 || $rol == 3) { ?>
        const result = await api('train_model');
        if (result.success) { 
            showNotification('Modelo de IA entrenado exitosamente', 'success'); 
            loadAnalytics(); 
        }
        <?php } ?>
    }

    async function exportKnowledge() {
        <?php if ($rol == 1 || $rol == 3) { ?>
        const result = await api('export_knowledge');
        if (result.success) {
            const blob = new Blob([JSON.stringify(result.data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url; 
            a.download = 'knowledge_base_export.json'; 
            a.click(); 
            URL.revokeObjectURL(url);
        }
        <?php } ?>
    }

    // ---------- EVENT LISTENERS MEJORADOS ----------
    function initEventListeners() {
        // Listeners básicos para todos los usuarios
        if (messageInput) {
            messageInput.addEventListener('input', updateCharCount);
            messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    handleSendMessage(e);
                }
            });
        }

        if (quickActions) {
            quickActions.addEventListener('click', (e) => {
                const btn = e.target.closest('.quick-btn');
                if (!btn) return;
                if (messageInput) {
                    messageInput.value = btn.dataset.text || '';
                    updateCharCount();
                    messageInput.focus();
                }
            });
        }

        if (voiceBtn) voiceBtn.addEventListener('click', toggleVoice);

        if (sendForm) sendForm.addEventListener('submit', handleSendMessage);
        if (refreshBtn) refreshBtn.addEventListener('click', loadMessages);
        
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', handleClearAll);
        }

        // Listeners solo para administradores
        <?php if ($rol == 1 || $rol == 3) { ?>
        document.getElementById('trainModel')?.addEventListener('click', trainModel);
        document.getElementById('exportKnowledge')?.addEventListener('click', exportKnowledge);
        document.getElementById('saveTraining')?.addEventListener('click', saveTrainingData);
        document.getElementById('refreshKnowledge')?.addEventListener('click', loadKnowledgeBase);
        <?php } ?>

        // Chat click handlers para todos
        if (chat) {
            chat.addEventListener('click', async (e) => {
                if (e.target.classList.contains('delete-btn')) {
                    const id = e.target.dataset.id;
                    if (!confirm('¿Eliminar este mensaje?')) return;
                    const result = await api('delete', { id });
                    if (result.success) { 
                        await loadMessages(); 
                        showNotification('Mensaje eliminado', 'success'); 
                    } else {
                        showError(`Error al eliminar: ${result.error || 'desconocido'}`);
                    }
                }
                if (e.target.classList.contains('edit-btn')) {
                    const id = e.target.dataset.id;
                    const newMessage = prompt('Editar mensaje:');
                    if (newMessage && newMessage.trim()) {
                        const result = await api('update', { id: id, message: newMessage.trim() });
                        if (result.success) { 
                            await loadMessages(); 
                            showNotification('Mensaje editado', 'success'); 
                        } else {
                            showError(`Error al editar: ${result.error || 'desconocido'}`);
                        }
                    }
                }
            });
        }
    }

    function init() {
        loadSettings();
        initSpeech(); // Inicializar reconocimiento de voz
        initEventListeners();
        updateConnectionStatus(false);
        updateCharCount(); // Inicializar el contador de caracteres
        loadMessages();
        
        // Solo inicializar funciones de admin si es necesario
        <?php if ($rol == 1 || $rol == 3) { ?>
        loadAnalytics();
        loadKnowledgeBase();
        initCharts();
        setInterval(loadAnalytics, 30000);
        setInterval(loadKnowledgeBase, 60000);
        <?php } ?>
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
</script>