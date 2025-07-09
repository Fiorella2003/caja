<section class="content-header">
     <div class="container-fluid">
		<div class="card card-primary">
			<div class="card-header" style="background-color: #2C3E50; color: #FFFFFF;">
				<h3 class="card-title">Listado de Semestre</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Semestre</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaNombre" id="txtBusquedaNombre" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Fecha de inicio</span>
							</div>
							<input type="date" class="form-control" name="dateFecha_inicio" id="dateFecha_inicio" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Fecha de cierre</span>
							</div>
							<input type="date" class="form-control" name="dateFecha_fin" id="dateFecha_fin" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Estado</span>
							</div>
							<select class="form-control" name="cboBusquedaEstado" id="cboBusquedaEstado" onchange="verListado()">
								<option value="">- Todos -</option>
								<option value="1">Activos</option>
								<option value="0">Anulados</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<button type="button" class="btn btn-primary" onclick="verListado()"><i class="fa fa-search"></i> Buscar</button>
						<button type="button" class="btn btn-success" onclick="nuevoSemestre()"><i class="fa fa-plus"></i> Nuevo</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="divListadoSemestre">
						CONTENEDOR TABLA
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
	function verListado(){
		$.ajax({
			method: 'POST',
			url: 'vista/semestres_listado.php',
			data:{
				nombre: $('#txtBusquedaNombre').val(),
                fecha_inicio: $('#dateFecha_inicio').val(),
                fecha_fin: $('#dateFecha_fin').val(),
				estado: $('#cboBusquedaEstado').val()
			}
		})
		.done(function(resultado){
			$('#divListadoSemestre').html(resultado);
		});
	}

	verListado();



	function nuevoSemestre(){
		abrirModal('vista/semestres_formulario','accion=NUEVO','divmodal1','Registro de Semestre');
	}

</script>