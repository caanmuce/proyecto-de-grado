<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
</head>
<body>
    <center>
        <h1>Perfil</h1>
    </center>
    <div class="container-fluid">

  <!-- Título de la página -->
  <div class="row mb-4">
    <div class="col-lg-12">
      <h1 class="h3 mb-0 text-gray-800">Perfil de Usuario</h1>
    </div>
  </div>

  <div class="row">

    <!-- ====================================
         SECCIÓN IZQUIERDA: INFORMACIÓN BÁSICA
         ==================================== -->
    <div class="col-lg-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <img src="img/undraw_profile.svg" alt="Avatar Usuario" class="rounded-circle mb-3" style="width:120px; height:120px;">
          <h5 class="font-weight-bold mb-1">Nombre Apellido</h5>
          <p class="text-muted mb-2">usuario@example.com</p>
          <p class="text-muted"><i class="fas fa-map-marker-alt"></i> Medellín, Colombia</p>
          <button class="btn btn-primary btn-sm mt-2">
            <i class="fas fa-edit"></i> Editar Perfil
          </button>
        </div>
      </div>

      <!-- Tarjeta: Información Extra -->
      <div class="card shadow-sm mt-4">
        <div class="card-header py-2">
          <h6 class="m-0 font-weight-bold text-primary">Sobre mí</h6>
        </div>
        <div class="card-body">
          <p>
            Hola, soy desarrollador de software aficionado a la tecnología y al diseño de experiencias
            de usuario. Me interesa la educación sexual y el apoyo a jóvenes.
          </p>
        </div>
      </div>

      <!-- Tarjeta: Redes Sociales -->
      <div class="card shadow-sm mt-4">
        <div class="card-header py-2">
          <h6 class="m-0 font-weight-bold text-success">Redes Sociales</h6>
        </div>
        <div class="card-body">
          <a href="#" class="btn btn-outline-primary btn-sm btn-block mb-2">
            <i class="fab fa-facebook-f"></i> Facebook
          </a>
          <a href="#" class="btn btn-outline-info btn-sm btn-block mb-2">
            <i class="fab fa-twitter"></i> Twitter
          </a>
          <a href="#" class="btn btn-outline-danger btn-sm btn-block mb-2">
            <i class="fab fa-instagram"></i> Instagram
          </a>
        </div>
      </div>
    </div>

    <!-- ====================================
         SECCIÓN DERECHA: EDITAR DATOS Y SEGURIDAD
         ==================================== -->
    <div class="col-lg-8 mb-4">
      <!-- Tarjeta: Editar Información Personal -->
      <div class="card shadow-sm mb-4">
        <div class="card-header py-2">
          <h6 class="m-0 font-weight-bold text-info">
            <i class="fas fa-user-cog"></i> Ajustes de Perfil
          </h6>
        </div>
        <div class="card-body">
          <form>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputNombre">Nombre</label>
                <input type="text" class="form-control form-control-sm" id="inputNombre" placeholder="Nombre">
              </div>
              <div class="form-group col-md-6">
                <label for="inputApellido">Apellido</label>
                <input type="text" class="form-control form-control-sm" id="inputApellido" placeholder="Apellido">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail">Correo Electrónico</label>
              <input type="email" class="form-control form-control-sm" id="inputEmail" placeholder="usuario@example.com">
            </div>
            <div class="form-group">
              <label for="inputBio">Biografía</label>
              <textarea class="form-control form-control-sm" id="inputBio" rows="3"
                        placeholder="Cuéntanos sobre ti…"></textarea>
            </div>
            <div class="form-group">
              <label for="inputUbicacion">Ubicación</label>
              <input type="text" class="form-control form-control-sm" id="inputUbicacion" placeholder="Medellín, Colombia">
            </div>
            <button type="submit" class="btn btn-info btn-sm">
              <i class="fas fa-save"></i> Guardar Cambios
            </button>
          </form>
        </div>
      </div>

      <!-- Tarjeta: Cambiar Contraseña -->
      <div class="card shadow-sm mb-4">
        <div class="card-header py-2">
          <h6 class="m-0 font-weight-bold text-warning">
            <i class="fas fa-key"></i> Cambiar Contraseña
          </h6>
        </div>
        <div class="card-body">
          <form>
            <div class="form-group">
              <label for="inputActual">Contraseña Actual</label>
              <input type="password" class="form-control form-control-sm" id="inputActual" placeholder="********">
            </div>
            <div class="form-group">
              <label for="inputNueva">Contraseña Nueva</label>
              <input type="password" class="form-control form-control-sm" id="inputNueva" placeholder="********">
            </div>
            <div class="form-group">
              <label for="inputConfirmar">Confirmar Contraseña</label>
              <input type="password" class="form-control form-control-sm" id="inputConfirmar" placeholder="********">
            </div>
            <button type="submit" class="btn btn-warning btn-sm">
              <i class="fas fa-lock"></i> Actualizar Contraseña
            </button>
          </form>
        </div>
      </div>

      <!-- Tarjeta: Actividad Reciente (Ejemplo) -->
      <div class="card shadow-sm">
        <div class="card-header py-2">
          <h6 class="m-0 font-weight-bold text-secondary">
            <i class="fas fa-history"></i> Actividad Reciente
          </h6>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item px-0">
              <i class="fas fa-file-alt text-primary"></i> Publicaste un artículo sobre consentimiento <span class="text-muted small">– 2h atrás</span>
            </li>
            <li class="list-group-item px-0">
              <i class="fas fa-comments text-success"></i> Comentaste en el foro de dudas <span class="text-muted small">– 1d atrás</span>
            </li>
            <li class="list-group-item px-0">
              <i class="fas fa-user-plus text-info"></i> Agregaste a Juan Pérez como amigo <span class="text-muted small">– 3d atrás</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

  </div>
  <!-- /.row -->

</div>
</body>
</html>