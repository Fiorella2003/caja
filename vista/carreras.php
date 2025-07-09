<section class="content-header">
     <div class="container-fluid">
		<div class="card card-primary">
			<div class="card-header" style="background-color: #2C3E50; color: #FFFFFF;">
				<h3 class="card-title">Listado de Carreras</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Carrera</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaNombre_ca" id="txtBusquedaNombre_ca" onkeyup="if(event.keyCode=='13'){ verListado(); }">
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
						<button type="button" class="btn btn-success" onclick="nuevaCarrera()"><i class="fa fa-plus"></i> Nuevo</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="divListadoCarrera">
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
			url: 'vista/carreras_listado.php',
			data:{
				nombre_ca: $('#txtBusquedaNombre_ca').val(),
				estado: $('#cboBusquedaEstado').val()
			}
		})
		.done(function(resultado){
			$('#divListadoCarrera').html(resultado);
		});
	}

	verListado();



	function nuevaCarrera(){
		abrirModal('vista/carreras_formulario','accion=NUEVO','divmodal1','Registro de Carrera');
	}


</script>