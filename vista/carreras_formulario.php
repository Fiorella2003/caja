<?php
	require_once('../modelo/clsCarrera.php');

	$objCar = new clsCarrera();
	$accion = $_GET['accion'];
	$id = 0;
	if($accion=='ACTUALIZAR'){
		$id = $_GET['idcarrera'];
		$carrera = $objCar->consultarCarreraPorId($id);
		$carrera = $carrera->fetch(PDO::FETCH_NAMED);
	}

?>
<form name="formCarrera" id="formCarrera">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="nombre_ca">Carrera</label>
				<input type="text"  autocomplete="off" class="form-control obligatorio" id="nombre_ca" name="nombre_ca" value="<?php if($accion=='ACTUALIZAR'){ echo $carrera['nombre_ca']; } ?>">
				<input type="hidden" class="form-control" id="idcarrera" name="idcarrera" value="<?= $id ?>">
			</div>
			<div class="form-group" style="display: none;">
				<label for="estado">Estado</label>
				<select class="form-control" name="estado" id="estado">
					<option value="1">ACTIVO</option>
					<option value="0">ANULADO</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
			<button type="button" class="btn btn-primary" onclick="registrarCarrera()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>
<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $carrera['estado'] ?>');
	<?php } ?>

	function registrarCarrera(){
		if(verificarFormulario()){
			var datax = $('#formCarrera').serializeArray();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contCarrera.php',
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

</script>