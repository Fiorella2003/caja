<?php
session_start();
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Gestión de pagos</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="imagen/iconos/usb.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(imagen/image.jpg);">
					<span class="login100-form-title-1">
						Iniciar sesión
					</span>
				</div>

				<form class="login100-form validate-form">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Se requiere usuario">
						<span class="label-input100">Usuario</span>
						<input class="input100" type="text" name="txtuser" id="txtuser" placeholder="Ingresar usuario">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate="Se requiere contraseña">
						<span class="label-input100">Contraseña</span>
						<input class="input100" type="text" name="txtpass" id="txtpass" placeholder="Ingresar contraseña">
						<span class="focus-input100"></span>
					</div>

					<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Recordar
							</label>
						</div>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="button" onclick="ingresarSistema()">
							Ingresar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>



  <script>
  
  function ingresarSistema(){
    if(verificarFormulario()){
      $.ajax({
        method: 'POST',
        url: 'controlador/contUsuario.php',
        data: {
            accion: 'INICIAR_SESION',
            usuario : $('#txtuser').val(),
            clave : $('#txtpass').val()
        },
        dataType: 'json'
      })
      .done(function(retorno){
        if(retorno.correcto==1){
          window.open('principal.php','_self');
        }else{
          alert(retorno.mensaje);
        }
      })
    }
  }


  function verificarFormulario(){
    let condicion = true;
    let usuario = $('#txtuser').val();
    let clave = $('#txtpass').val();

    if(usuario==''){
      condicion=false;
      alert('Por favor ingresa tu usuario...');
    }else if(clave==''){
      condicion = false;
      alert('Por favor ingresa tu contraseña...');
    }

    return condicion;
  }

</script>
</body>
</html>