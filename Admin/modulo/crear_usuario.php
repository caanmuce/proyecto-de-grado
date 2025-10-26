<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario</title>
</head>
<body>
    <center>
        <h1>Crear usuario</h1>
    </center>
    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12 d-lg-block bg-login-image text-center" style="align-content: center;"><img src="img/EducaSex.png" alt="" style="border radius: 30px;"></div>
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Crea una cuenta!</h1>
                            </div>
                            <form action="../codigo.php" method="post" class="user">
                                <div class="form-group">
                                    <label>Tipo de Documento</label>
                                    <select name="Tipo_documento" class="form-control" id="" required>
                                    <option value="">Seleccione</option>
                                    <option value="TI">TI</option>
                                    <option value="CC">CC</option>
                                    <option value="RC">RC</option>
                                </select>
                                </div>
                                

                                <div class="form-group">
                                    <input type="num" class="form-control form-control-user" name="numero_documento" placeholder="Documento" required pattern="^[0-9]{7,11}$" title="Minimo 8 números, maximo 11">
                                    
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="txt_PN" placeholder="Primer Nombre" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" name="txt_SN" placeholder="Segundo Nombre">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="txt_PA" placeholder="Primer Apellido" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" name="txt_SA" placeholder="Segundo Apellido" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="Number" class="form-control form-control-user" name="numero_telefono" placeholder="Teléfono" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control form-control-user" name="correo" placeholder="Email" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="Password" class="form-control form-control-user" name="clave" placeholder="Contraseña" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Mínimo 8 caracteres, al menos 1 número, 1 minúscula y 1 mayúscula.">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="Password" class="form-control form-control-user" id="c_clave" name="c_clave" placeholder="Confirmar contraseña" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Rol</label>
                                    <select name="Cmb_rol" class="form-control" id="" placeholder="Id_rol" required>
                                        <option value="">Seleccione</option>
                                        <option value="1">Administrador</option>
                                        <option value="2">Operario</option>
                                        <option value="3">Asesor</option>
                                    </select>
                                </div>
                                
                                <input type="submit" name="BtnRegistrar" value="Registrarse" class="btn btn-primary btn-user btn-block">

                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Olvidaste la contraseña?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="index.php">Ya tienes cuenta? Inicia sesión!</a>
                            </div>
                        </div>
                    </div>
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
</body>
</html>