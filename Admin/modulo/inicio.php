<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body>

    <div class="container-fluid">
        <center>
            <h1>Inicio</h1>
        </center>
        
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Bienvenido a EducaSex</h1>
        </div>

        
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Recursos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Ver Recursos
                                </div>
                            </div>
                            <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <a href="dashboard.php?mod=recursos_educativos" class="card-footer text-decoration-none small">
                    <span class="text-primary">Ir a Recursos &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Foro de dudas -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Foro de dudas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Participar en Foro</div>
                                </div>
                            <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <a href="dashboard.php?mod=foro_dudas" class="card-footer text-decoration-none small">
                    <span class="text-success">Ir al Foro &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Asistente Virtual -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Asistente Virtual
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Chatea Ahora

                                </div>
                            </div>
                            <div class="col-auto">
                            <i class="fas fa-robot fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <a href="dashboard.php?mod=chatbot" class="card-footer text-decoration-none small">
                    <span class="text-info">Ir al Chatbot &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Agenda con Psicóloga -->
            <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Cita con Psicóloga</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Agendar Cita</div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
                </div>
                <a href="dashboard.php?mod=agendar_cita" class="card-footer text-decoration-none small">
                <span class="text-warning">Agendar &rarr;</span>
                </a>
            </div>
            </div>

        </div>
        <!-- Fin primera fila -->

        <!-- Segunda fila: Texto informativo y novedades -->
        <div class="row">

            <!-- Columna izquierda: descripción de la plataforma -->
            <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">¿Por qué EducaSex?</h6>
                </div>
                <div class="card-body">
                <p>
                    EducaSex es una plataforma diseñada para ofrecer información confiable y acompañamiento
                    en temas de sexualidad y salud emocional. Aquí encontrarás:
                </p>
                <ul>
                    <li><strong>Recursos educativos:</strong> Artículos, guías y videos sobre salud sexual, consentimiento, métodos anticonceptivos, etc.</li>
                    <li><strong>Foro de dudas:</strong> Un espacio donde preguntar y compartir experiencias con otros usuarios y expertos.</li>
                    <li><strong>Asistente Virtual:</strong> Un chatbot para resolver consultas rápidas y sugerir contenidos relevantes.</li>
                    <li><strong>Psicóloga escolar:</strong> Agenda tus citas, recibe orientación personalizada y apoyo emocional.</li>
                </ul>
                <p>
                    Utiliza el menú lateral para navegar entre cada sección y acceder a las funcionalidades específicas.
                </p>
                </div>
            </div>
            </div>

            <!-- Columna derecha: tarjetas de novedades y estadísticas simples -->
            <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Novedades</h6>
                </div>
                <div class="card-body">
                <p><i class="fas fa-check-circle text-success"></i> 10 nuevos artículos publicados esta semana</p>
                <p><i class="fas fa-users text-info"></i> 5 usuarios participaron en el foro hoy</p>
                <p><i class="fas fa-calendar-check text-warning"></i> 2 citas ya agendadas con la psicóloga</p>
                </div>
            </div>
            </div>

        </div>
    <!-- Fin segunda fila -->

    </div>
</body>
</html>