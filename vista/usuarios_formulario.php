<?php
	require_once('../modelo/clsUsuario.php');
	require_once('../modelo/clsPerfil.php');

	$objUsu = new clsUsuario();
	$objPer = new clsPerfil();
	$accion = $_GET['accion'];
	$id = 0;

	$listaPerfil = $objPer->listarPerfil('',1);
	$listaPerfil = $listaPerfil->fetchAll(PDO::FETCH_NAMED);

	if($accion=='ACTUALIZAR'){
		$id = $_GET['idusuario'];
		$usuario = $objUsu->consultarUsuarioPorId($id);
		$usuario = $usuario->fetch(PDO::FETCH_NAMED);
	}

?>

<form name="formUsuario" id="formUsuario">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="nombre">Nombre</label>
				<input type="text" class="form-control obligatorio" id="nombre" name="nombre" value="<?php if($accion=='ACTUALIZAR'){ echo $usuario['nombre']; } ?>">
				<input type="hidden" class="form-control" id="idusuario" name="idusuario" value="<?= $id ?>">
			</div>
			<div class="form-group">
				<label for="usuario">Usuario</label>
				<input type="text" class="form-control obligatorio" id="usuario" name="usuario" value="<?php if($accion=='ACTUALIZAR'){ echo $usuario['usuario']; } ?>">
			</div>
			<div class="form-group" style="position: relative;">
				<label for="clave">Clave</label>
				<input type="password" class="form-control obligatorio" id="clave" name="clave" value="" onkeyup="checkPassword()">
				<i class="fas fa-eye" id="mostrar-contra" style="position: absolute; right: 10px; top: 38px; cursor: pointer;"></i>
				<!-- Para mostrar la fortaleza -->
				<div id="password-strength" class="mt-2" style="display:none; text-align:center;"></div> 
			</div>
			<div class="form-group">
				<label for="idperfil">Perfil</label>
				<select class="form-control obligatorio" name="idperfil" id="idperfil">
					<option value="">- SELECCIONE -</option>
					<?php foreach($listaPerfil as $k=>$v){ ?>
					<option value="<?= $v['idperfil'] ?>"><?= $v['nombre'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group" style="display: none;">
				<label for="estado">Estado</label>
				<select class="form-control obligatorio" name="estado" id="estado">
					<option value="1">ACTIVO</option>
					<option value="0">ANULADO</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
			<button type="button" class="btn btn-primary" onclick="registrarUsuario()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>
<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $usuario['estado'] ?>');
		$('#idperfil').val('<?php echo $usuario['idperfil'] ?>');
	<?php } ?>

	function registrarUsuario(){
		if(verificarFormulario()){
			var datax = $('#formUsuario').serializeArray();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contUsuario.php',
				data: datax,
				dataType: 'json'
			})
			.done(function(resultado){
				if(resultado.correcto==1){
					toastCorrecto(resultado.mensaje);
					CloseModal('divmodal1');
					verListado();
				}else{
					toastError(resultado.mensaje);
				}
			})
		}else{
			toastError('Existen errores en su formulario.');
		}
	}

	function verificarFormulario(){
		correcto = true;

		$(".obligatorio").each(function(){
			$(this).removeClass('is-invalid');
			if($(this).val()=="" || $(this).val()=="0"){
				$(this).addClass('is-invalid');
				correcto = false;
			}
		})

		// Validar contraseña
		var contraseña = $('#clave').val();
		if (checkPasswordStrength(contraseña).startsWith('Fácil de adivinar')) {
			toastError('La contraseña es demasiado débil.');
			correcto = false;
		}

		return correcto;
	}

	function checkPassword() {
    var password = $('#clave').val();
    var strengthElement = $('#password-strength');
    var strengthMessage = checkPasswordStrength(password);
    
    // Mostrar el div de la fortaleza de la contraseña
    strengthElement.css('display', 'block');

    // Ajustar el texto y el color basado en la fortaleza de la contraseña
    if (strengthMessage.startsWith("Fácil de adivinar")) {
        strengthElement.text(strengthMessage);
        strengthElement.css('background-color', '#f8d7da');
        strengthElement.css('color', '#721c24');
    } else if (strengthMessage.startsWith("Dificultad media")) {
        strengthElement.text(strengthMessage);
        strengthElement.css('background-color', '#fff3cd');
        strengthElement.css('color', '#856404');
    } else if (strengthMessage.startsWith("Difícil")) {
        strengthElement.text(strengthMessage);
        strengthElement.css('background-color', '#d4edda');
        strengthElement.css('color', '#155724');
    } else if (strengthMessage.startsWith("Extremadamente difícil")) {
        strengthElement.text(strengthMessage);
        strengthElement.css('background-color', '#c3e6cb');
        strengthElement.css('color', '#155724');
    }
}

function checkPasswordStrength(password) {
    var strength = 0;
    var tips = "";

    // Check para la longitud de caracteres
    if (password.length < 8) {
        tips += "Al menos 8 caracteres de longitud (0...9). ";
    } else {
        strength += 1;
    }

    // Check para letras minusculas y mayus
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
        strength += 1;
    } else {
        tips += "Al menos 1 letra minúscula y mayúscula (a...Z).  ";
    }

    // Check para numeros
    if (password.match(/\d/)) {
        strength += 1;
    } else {
        tips += "Al menos 1 número (0...9). ";
    }

    // Check para caracteres especiales
    if (password.match(/[^a-zA-Z\d]/)) {
        strength += 1;
    } else {
        tips += "Al menos un símbolo especial (!...$). ";
    }

    // Retorna el resultado
    if (strength < 2) {
        return "Fácil de adivinar. " + tips;
    } else if (strength === 2) {
        return "Dificultad media. " + tips;
    } else if (strength === 3) {
        return "Difícil. " + tips;
    } else {
        return "Extremadamente difícil. " + tips;
    }
}

// Función para alternar la visibilidad de la contraseña
document.getElementById('mostrar-contra').addEventListener('click', function () {
    var passwordField = document.getElementById('clave');
    var icon = this;

    // Cambiar el tipo de input entre 'password' y 'text'
    if (passwordField.type === 'text') {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');  // Cambiar el ícono al de ojo con barra
    } else {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');  // Cambiar el ícono al de ojo
    }
});

</script>
