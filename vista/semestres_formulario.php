<?php
	require_once('../modelo/clsSemestre.php');

	$objSem = new clsSemestre();
	$accion = $_GET['accion'];
	$id = 0;
	if($accion=='ACTUALIZAR'){
		$id = $_GET['idsemestre'];
		$semestre = $objSem->consultarSemestrePorId($id);
		$semestre = $semestre->fetch(PDO::FETCH_NAMED);
	}

?>
<form name="formSemestre" id="formSemestre">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="nombre">Semestre</label>
				<input type="text"  autocomplete="off" class="form-control obligatorio" id="nombre" name="nombre" value="<?php if($accion=='ACTUALIZAR'){ echo $semestre['nombre']; } ?>">
				<input type="hidden" class="form-control" id="idsemestre" name="idsemestre" value="<?= $id ?>">
			</div>
            <div class="form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Fecha de inicio</span>
                </div>
                <input type="date" class="form-control obligatorio" id="fecha_inicio" name="fecha_inicio" value="<?= $idsemestre > 0 ? $semestre['fecha_inicio'] : date('Y-m-d') ?>" />
            </div>
            <div class="form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Fecha de cierre</span>
                </div>
                <input type="date" class="form-control obligatorio" id="fecha_fin" name="fecha_fin" value="<?= $idsemestre > 0 ? $semestre['fecha_fin'] : date('Y-m-d') ?>" />
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
			<button type="button" class="btn btn-primary" onclick="registrarSemestre()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>
<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $semestre['estado'] ?>');
	<?php } ?>

	function registrarSemestre(){
		if(verificarFormulario()){
			var datax = $('#formSemestre').serializeArray();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contSemestre.php',
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