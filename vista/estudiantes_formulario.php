<?php
	require_once('../modelo/clsEstudiante.php');
	require_once("../modelo/clsCarrera.php");
	require_once("../modelo/clsTurno.php");


	$objEst = new clsEstudiante();
	$objCar = new clsCarrera();
	$objTur = new clsTurno();

	$carrera = $objCar->listarCarrera("", 1);
	$turno = $objTur->listarTurno("", 1);
	

	$accion = $_GET['accion'];
	$id = 0;

	$arrayTipoDoc = $objEst->listaTipoDocumento();
	$arrayTipoDoc = $arrayTipoDoc->fetchAll(PDO::FETCH_NAMED);

	if($accion=='ACTUALIZAR'){
		$id = $_GET['idestudiante'];
		$estudiante = $objEst->consultarEstudiantePorId($id);
		$estudiante = $estudiante->fetch(PDO::FETCH_NAMED);
	}

?>
<form name="formEstudiante" id="formEstudiante">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="idtipodocumento">Tipo de Documento</label>
				<select class="form-control obligatorio" name="idtipodocumento" id="idtipodocumento">
					<option value="">- SELECCIONE -</option>
					<?php foreach($arrayTipoDoc as $k=>$v){ ?>
					<option value="<?= $v['idtipodocumento'] ?>"><?= $v['nombre'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label for="nrodocumento">Numero de Documento</label>
				<div class="input-group">
					<input type="text" class="form-control obligatorio" id="nrodocumento" name="nrodocumento" value="<?php if($accion=='ACTUALIZAR'){ echo $estudiante['nrodocumento']; } ?>">
					<div class="input-group-prepend">
						<span style="cursor: pointer;" class="input-group-text btn" onclick="consultarDatoEstudiante()">
							<i class="fas fa-search"></i>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="nombre">Nombre</label>
				<input type="text" class="form-control obligatorio" id="nombre" name="nombre" value="<?php if($accion=='ACTUALIZAR'){ echo $estudiante['nombre']; } ?>">
				<input type="hidden" class="form-control" id="idestudiante" name="idestudiante" value="<?= $id ?>">
			</div>
			<div class="form-group">
				<label for="direccion">Dirección</label>
				<textarea class="form-control obligatorio" name="direccion" id="direccion"><?php if($accion=='ACTUALIZAR'){ echo $estudiante['direccion']; } ?></textarea>
			</div>
			<div class="form-group">
				<label for="genero">Género</label>
					<select class="form-control obligatorio" id="genero" name="genero">
						<option value="">- SELECCIONE -</option>
						<option value="Femenino">Femenino</option>
						<option value="Masculino">Masculino</option>
					</select>
			</div>
			<div class="form-group">
				<label for="idcarrera">Carrera</label>
				<select class="form-control obligatorio" name="idcarrera" id="idcarrera">
					<option value="">- SELECCIONE -</option>
					<?php foreach($carrera as $k=>$v){ ?>
					<option value="<?= $v['idcarrera'] ?>"><?= $v['nombre_ca'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label for="ciclo">Ciclo</label>
					<select class="form-control obligatorio" id="ciclo" name="ciclo">
						<option value="">- SELECCIONE -</option>
						<option value="1">I</option>
						<option value="2">II</option>
						<option value="3">III</option>
						<option value="4">IV</option>						
						<option value="5">V</option>
						<option value="6">VI</option>
					</select>
			</div>
			<div class="form-group">
				<label for="idturno">Turno</label>
				<select class="form-control obligatorio" name="idturno" id="idturno">
					<option value="">- SELECCIONE -</option>
					<?php foreach($turno as $k=>$v){ ?>
					<option value="<?= $v['idturno'] ?>"><?= $v['nombre_tu'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label for="celular">Celular</label>
				<input type="number" class="form-control obligatorio" id="celular" name="celular" value="<?php if($accion=='ACTUALIZAR'){ echo $estudiante['celular']; } ?>">
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
			<button type="button" class="btn btn-primary" onclick="registrarEstudiante()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>

<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $estudiante['estado'] ?>');
		$('#idtipodocumento').val('<?php echo $estudiante['idtipodocumento'] ?>');
	<?php } ?>

	function registrarEstudiante(){
		if(verificarFormulario()){
			var datax = $('#formEstudiante').serializeArray();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contEstudiante.php',
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

		return correcto;
	}


	function consultarDatoEstudiante(){
		$('#formEstudiante').LoadingOverlay('show');
		$.ajax({
			method: 'POST',
			url: 'controlador/contEstudiante.php',
			data: {
				accion: 'CONSULTAR_DATOS_WS',
				idtipodocumento: $('#idtipodocumento').val(),
				nrodocumento: $('#nrodocumento').val()
			},
			dataType: 'json'
		})
		.done(function(resultado){
			$('#formEstudiante').LoadingOverlay('hide');
			$('#nombre').val(resultado.nombre);
			$('#direccion').val(resultado.direccion);
		})
	}

//estu
</script>