<!DOCTYPE html>
<?php
//Abro sesiones
session_start();
if (isset($_SESSION['user'])){
    

?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Inicio</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="img/EducaSex_Pro.png">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-icon">
                    <img src="img/EducaSex_Pro.png" style="width: 80px;" alt="">
                </div>
                <div class="sidebar-brand-text mx-3">EducaSex</div>
            </a>

            <br>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Inicio</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Participa
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Usuarios</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="dashboard.php?mod=crear_usuario">Crear usuario</a>
                        <a class="collapse-item" href="dashboard.php?mod=gestion_usuario">Gestion usuario</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?mod=foro_dudas">
                    <i class="fas fa-fw fa-comments"></i>
                    <span>Foro de dudas</span></a>
            </li>

            <hr class="sidebar-divider">


            <div class="sidebar-heading">
                Acompañamiento
            </div>

            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?mod=chatbot">
                    <i class="fas fa-fw fa-robot"></i>
                    <span>Chatbot</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?mod=recursos_educativos">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Recursos educativos</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Social
            </div>

            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?mod=interacciones">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Interacciones sociales</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?mod=mensajes">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Mensajes</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Otros
            </div>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?mod=agendar_cita">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Agendar cita</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?mod=casos_estudio">
                    <i class="fas fa-fw fa-"></i>
                    <span>Casos de estudio</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Buscar..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alertas -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                Centro de Alertas
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                    <i class="fas fa-info-circle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">Hoy</div>
                                    <span class="font-weight-bold">Nuevo recurso sobre consentimiento añadido.</span>
                                </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">Ayer</div>
                                    <span>Actualiza tu perfil para ver contenido personalizado.</span>
                                </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Ver todas las alertas</a>
                            </div>
                            </li>

            <!-- Ítem: Mensajes -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <span class="badge badge-danger badge-counter">5</span>
              </a>
              <!-- Dropdown - Mensajes -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                   aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Centro de Mensajes
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-truncate">¿Dónde puedo encontrar información sobre métodos anticonceptivos?</div>
                    <div class="small text-gray-500">Ana · 10m</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="">
                    <div class="status-indicator"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Gracias por la charla con la psicóloga, fue muy útil.</div>
                    <div class="small text-gray-500">Luis · 1d</div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Leer más mensajes</a>
              </div>
            </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['pn']," ",$_SESSION['pa'];  ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="dashboard.php?mod=perfil">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <a class="dropdown-item" href="dashboard.php?mod=configuracion">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configuración
                                </a>
                                <a class="dropdown-item" href="dashboard.php?mod=centro_actividad">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Centro de actividad
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                <?php
                    if(@ $_GET['mod']=="")
                        {
                            require_once("modulo/inicio.php");
                        }
                    else
                        if(@ $_GET['mod']=="inicio")
                        {
                            require_once("modulo/inicio.php");
                        }
                    else
                        if(@ $_GET['mod']=="crear_usuario")
                        {
                            require_once("modulo/crear_usuario.php");
                        }
                    else
                        if(@ $_GET['mod']=="gestion_usuario")
                        {
                            require_once("modulo/gestion_usuario.php");
                        }
                    else
                        if(@ $_GET['mod']=="foro_dudas")
                        {
                            require_once("modulo/foro_dudas.php");
                        }
                    else
                        if(@ $_GET['mod']=="chatbot")
                        {
                            require_once("modulo/chatbot.php");
                        }
                    else
                        if(@ $_GET['mod']=="recursos_educativos")
                        {
                            require_once("modulo/recursos_educativos.php");
                        }
                    else
                        if(@ $_GET['mod']=="agendar_cita")
                        {
                            require_once("modulo/agendar_cita.php");
                        }
                    else
                        if(@ $_GET['mod']=="perfil")
                        {
                            require_once("modulo/perfil.php");
                        }
                    else
                        if(@ $_GET['mod']=="configuracion")
                        {
                            require_once("modulo/configuracion.php");
                        }
                    else
                        if(@ $_GET['mod']=="centro_actividad")
                        {
                            require_once("modulo/centro_actividad.php");
                        }
                    else
                        if(@ $_GET['mod']=="interacciones")
                        {
                            require_once("modulo/interacciones.php");
                        }
                    else
                        if(@ $_GET['mod']=="mensajes")
                        {
                            require_once("modulo/mensajes.php");
                        }
                    else
                        if(@ $_GET['mod']=="casos_estudio")
                        {
                            require_once("modulo/casos_estudio.php");
                        }
                ?>

                </div>
            

            </div>
            <!-- Footer -->

                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; EducaSex 2025</span>
                        </div>
                    </div>
                </footer>
            
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Listo para salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecciona "Cerrar sesión" abajo si desesas cerrar la sesión actual.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="../exit.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>

<?php
}else{
    echo "<script>window.location='../index.php';</script>";
}
?>
