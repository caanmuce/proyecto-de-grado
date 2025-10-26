<?php
// Admin/modulo/chatbot_advanced.php
// NO colocar salida antes de <?php en archivos que incluyan este módulo.
?>

<style>
/* Estilos mejorados para el chatbot */
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

<!-- Chatbot Module Avanzado -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                SEIN - Asistente Virtual Avanzado
                <span id="connectionStatus" class="connection-status status-offline float-end">
                    <span class="status-dot"></span>
                    Conectando...
                </span>
            </h1>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-lg chatbot-container">
                <div class="chat-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-0 text-white font-weight-bold">🤖 SEIN - Asistente Virtual</h6>
                            <small class="text-white-50">Especialista en educación sexual y salud reproductiva</small>
                        </div>
                        <div class="d-flex gap-2">
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

        <div class="col-lg-4">
            <!-- Panel de Estado y Analytics -->
            <div class="analytics-panel mb-4">
                <h6 class="font-weight-bold mb-3">📊 Estado del Sistema</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="h4 mb-1" id="messageCount">0</div>
                            <small>Mensajes hoy</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="h4 mb-1" id="responseTime">~0.8s</div>
                            <small>Tiempo respuesta</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <small>🧠 IA Avanzada: <span id="aiStatus" class="badge badge-success">Activa</span></small><br>
                    <small>📚 Base conocimientos: <span class="badge badge-info">Actualizada</span></small><br>
                    <small>🔄 Última actualización: <span id="lastUpdate">Hace 2 min</span></small>
                </div>
            </div>

            <!-- Panel de Ayuda -->
            <div class="card shadow">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="m-0">💡 Consejos de Uso</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">✨ <strong>Sé específico:</strong> Cuanta más información proporciones, mejor será mi respuesta</li>
                        <li class="mb-2">🎯 <strong>Usa palabras clave:</strong> anticonceptivos, ITS, embarazo, etc.</li>
                        <li class="mb-2">🗣 <strong>Activa la voz:</strong> Puedes hablarme usando el micrófono</li>
                        <li class="mb-2">⚡ <strong>Acciones rápidas:</strong> Usa los botones para consultas comunes</li>
                        <li>🔒 <strong>Privacidad:</strong> Tus conversaciones son confidenciales</li>
                    </ul>
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
    </div>
</div>

<!-- Modal de Configuración -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">⚙ Configuración del Chatbot</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">🗣 Síntesis de Voz</label>
                    <select id="voiceSelect" class="form-select">
                        <option value="">Seleccionar voz...</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">🔊 Volumen</label>
                    <input type="range" class="form-range" id="volumeSlider" min="0" max="1" step="0.1" value="0.8">
                </div>
                <div class="mb-3">
                    <label class="form-label">⚡ Velocidad de respuesta</label>
                    <select id="responseSpeed" class="form-select">
                        <option value="fast">Rápida</option>
                        <option value="normal" selected>Normal</option>
                        <option value="detailed">Detallada</option>
                    </select>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="autoScroll" checked>
                    <label class="form-check-label" for="autoScroll">
                        📜 Auto-scroll al final del chat
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveSettings">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    // API_PATH relativo: desde Admin/dashboard.php el módulo está en Admin/modulo,
    // por eso 'modulo/api_chatbot.php' apunta a Admin/modulo/api_chatbot.php
    const API_PATH = 'modulo/api_chatbot.php';
    
    // Estado
    let isConnected = false;
    let isTyping = false;
    let messageCount = 0;
    let lastResponseTime = 0;
    
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

    // Voz
    let speechRecognition = null;
    let speechSynthesis = window.speechSynthesis;
    let settings = {
        volume: 0.8,
        autoScroll: true,
        responseSpeed: 'normal',
        voiceEnabled: false
    };

    function init() {
        loadSettings();
        initSpeech();
        initEventListeners();
        updateConnectionStatus(false);
        loadMessages();
        populateVoices();
        setInterval(loadMessages, 30000);
    }

    function loadSettings() {
        const saved = localStorage.getItem('chatbot_settings');
        if (saved) {
            try { settings = { ...settings, ...JSON.parse(saved) }; } catch(e){ /* ignore */ }
        }
    }
    function saveSettings() { localStorage.setItem('chatbot_settings', JSON.stringify(settings)); }

    function initSpeech() {
        if ('webkitSpeechRecognition' in window) {
            speechRecognition = new webkitSpeechRecognition();
            speechRecognition.continuous = false;
            speechRecognition.interimResults = false;
            speechRecognition.lang = 'es-ES';
            speechRecognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                if (messageInput) { messageInput.value = transcript; updateCharCount(); }
            };
            speechRecognition.onerror = function(event) {
                showError('Error en reconocimiento de voz: ' + (event.error || 'unknown'));
            };
        }
    }

    function populateVoices() {
        const voiceSelect = document.getElementById('voiceSelect');
        const voices = (speechSynthesis.getVoices ? speechSynthesis.getVoices() : []).filter(v => v.lang && v.lang.includes('es'));
        if (!voiceSelect) return;
        voiceSelect.innerHTML = '<option value="">Sin voz</option>';
        voices.forEach((voice, index) => {
            const option = document.createElement('option');
            option.value = index;
            option.textContent = `${voice.name} (${voice.lang})`;
            voiceSelect.appendChild(option);
        });
    }

    function initEventListeners() {
        if (messageInput) messageInput.addEventListener('input', updateCharCount);
        if (quickActions) quickActions.addEventListener('click', (e) => {
            if (e.target.classList.contains('quick-btn')) {
                if (messageInput) { messageInput.value = e.target.dataset.text || ''; updateCharCount(); messageInput.focus(); }
            }
        });
        if (voiceBtn) voiceBtn.addEventListener('click', toggleVoice);
        if (settingsBtn) settingsBtn.addEventListener('click', () => {
            const modalEl = document.getElementById('settingsModal');
            if (modalEl) new bootstrap.Modal(modalEl).show();
        });
        const saveBtn = document.getElementById('saveSettings');
        if (saveBtn) saveBtn.addEventListener('click', () => {
            const voiceSelect = document.getElementById('voiceSelect');
            const volumeSlider = document.getElementById('volumeSlider');
            const responseSpeed = document.getElementById('responseSpeed');
            const autoScroll = document.getElementById('autoScroll');
            settings.voice = voiceSelect ? voiceSelect.value : settings.voice;
            settings.volume = volumeSlider ? parseFloat(volumeSlider.value) : settings.volume;
            settings.responseSpeed = responseSpeed ? responseSpeed.value : settings.responseSpeed;
            settings.autoScroll = autoScroll ? autoScroll.checked : settings.autoScroll;
            saveSettings();
            const modal = bootstrap.Modal.getInstance(document.getElementById('settingsModal'));
            if (modal) modal.hide();
            showNotification('Configuración guardada', 'success');
        });
        if (sendForm) sendForm.addEventListener('submit', handleSendMessage);
        if (refreshBtn) refreshBtn.addEventListener('click', loadMessages);
        if (clearAllBtn) clearAllBtn.addEventListener('click', handleClearAll);
        if (messageInput) messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) { e.preventDefault(); handleSendMessage(e); }
        });
    }

    function updateCharCount() {
        const count = messageInput ? messageInput.value.length : 0;
        if (charCount) {
            charCount.textContent = count;
            charCount.style.color = count > 400 ? '#dc3545' : count > 300 ? '#ffc107' : '#6c757d';
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

    function showTyping() { if (!isTyping) { isTyping = true; if (typingIndicator) typingIndicator.style.display = 'block'; if (settings.autoScroll) scrollToBottom(); } }
    function hideTyping() { isTyping = false; if (typingIndicator) typingIndicator.style.display = 'none'; }
    function scrollToBottom() { if (chat) chat.scrollTop = chat.scrollHeight; }

    function escapeHtml(text) { const d = document.createElement('div'); d.textContent = text; return d.innerHTML; }
    function formatMessage(text) {
        text = String(text || '');
        text = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener">$1</a>');
        text = text.replace(/\n/g, '<br>');
        text = text.replace(/\*(.*?)\*/g, '<strong>$1</strong>');
        const emojiMap = { ':)': '😊', ':-)': '😊', ':(': '😢', ':-(': '😢', ':D': '😃', ':-D': '😃', ';)': '😉', ';-)': '😉' };
        for (const [k, v] of Object.entries(emojiMap)) text = text.split(k).join(v);
        return text;
    }

    async function api(action, data = {}) {
        const startTime = Date.now();
        try {
            const form = new FormData();
            form.append('action', action);
            for (const [key, value] of Object.entries(data)) form.append(key, value);

            const response = await fetch(API_PATH, { method: 'POST', body: form });
            lastResponseTime = (Date.now() - startTime) / 1000;
            const rtEl = document.getElementById('responseTime');
            if (rtEl) rtEl.textContent = `${lastResponseTime.toFixed(1)}s`;

            if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            const text = await response.text();

            try {
                const json = JSON.parse(text);
                updateConnectionStatus(true);
                hideError();
                return json;
            } catch (parseError) {
                console.error('Respuesta no JSON:', text);
                showError(`Respuesta no JSON del servidor.\n\nRespuesta recibida:\n${String(text).substring(0, 500)}...`);
                updateConnectionStatus(false);
                return { success: false, error: 'Respuesta no JSON', raw: text };
            }
        } catch (error) {
            console.error('Error API:', error);
            showError(`Error de conexión: ${error.message}`);
            updateConnectionStatus(false);
            return { success: false, error: error.message };
        }
    }

    function showError(message) {
        if (!errorContent || !errorPanel) return;
        errorContent.textContent = message;
        errorPanel.style.display = 'block';
    }
    function hideError() { if (!errorPanel) return; errorPanel.style.display = 'none'; }

    function showNotification(message, type = 'info') {
        const toast = document.createElement('div');
        const bg = type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info');
        toast.className = `toast align-items-center text-white bg-${bg} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${escapeHtml(message)}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        toast.addEventListener('hidden.bs.toast', () => { if (toast.parentNode) toast.parentNode.removeChild(toast); });
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
        if (result.success) renderMessages(result.messages);
        else showError(`Error al cargar mensajes: ${result.error || 'desconocido'}`);
    }

    function toggleVoice() {
        if (!speechRecognition) { showNotification('Reconocimiento de voz no disponible', 'error'); return; }
        if (settings.voiceEnabled) {
            speechRecognition.stop();
            settings.voiceEnabled = false;
            if (voiceBtn) { voiceBtn.textContent = '🎤'; voiceBtn.classList.remove('btn-danger'); voiceBtn.classList.add('btn-outline-light'); }
        } else {
            speechRecognition.start();
            settings.voiceEnabled = true;
            if (voiceBtn) { voiceBtn.textContent = '🔴'; voiceBtn.classList.remove('btn-outline-light'); voiceBtn.classList.add('btn-danger'); }
        }
    }

    function speakMessage(text) {
        if (!settings.voice || !speechSynthesis) return;
        const voices = speechSynthesis.getVoices();
        const voice = voices[parseInt(settings.voice)];
        if (voice) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.voice = voice;
            utterance.volume = settings.volume;
            utterance.rate = settings.responseSpeed === 'fast' ? 1.1 : (settings.responseSpeed === 'detailed' ? 0.85 : 0.95);
            utterance.pitch = 1;
            speechSynthesis.speak(utterance);
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
            if (messageInput) { messageInput.value = ''; updateCharCount(); }
            await loadMessages();
            if (result.bot_message && settings.voice) speakMessage(result.bot_message);
            showNotification('Mensaje enviado', 'success');
        } else {
            showError(`Error al enviar: ${result.error || 'desconocido'}`);
            showNotification('Error al enviar mensaje', 'error');
        }
    }

    async function handleClearAll() {
        if (!confirm('¿Estás seguro de que quieres eliminar toda la conversación?')) return;
        const result = await api('read');
        if (!result.success) { showError('No se pudieron obtener los mensajes'); return; }
        for (const message of result.messages) await api('delete', { id: message.id });
        await loadMessages();
        showNotification('Conversación limpiada', 'success');
    }

    if (chat) {
        chat.addEventListener('click', async (e) => {
            if (e.target.classList.contains('delete-btn')) {
                const id = e.target.dataset.id;
                if (!confirm('¿Eliminar este mensaje?')) return;
                const result = await api('delete', { id });
                if (result.success) { await loadMessages(); showNotification('Mensaje eliminado', 'success'); }
                else showError(`Error al eliminar: ${result.error || 'desconocido'}`);
            }
            if (e.target.classList.contains('edit-btn')) {
                const id = e.target.dataset.id;
                const newMessage = prompt('Editar mensaje:');
                if (newMessage && newMessage.trim()) {
                    const result = await api('update', { id: id, message: newMessage.trim() });
                    if (result.success) { await loadMessages(); showNotification('Mensaje editado', 'success'); }
                    else showError(`Error al editar: ${result.error || 'desconocido'}`);
                }
            }
        });
    }

    if (speechSynthesis && typeof speechSynthesis.onvoiceschanged !== 'undefined') {
        speechSynthesis.onvoiceschanged = populateVoices;
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();

    setInterval(() => {
        const last = document.getElementById('lastUpdate');
        if (last) last.textContent = 'Hace ' + Math.floor(Math.random() * 5 + 1) + ' min';
    }, 60000);

})();
</script>
