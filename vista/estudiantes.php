<?php
require_once('../modelo/clsEstudiante.php');
require_once('../modelo/clsCarrera.php');
require_once('../modelo/clsTurno.php');


$objEst = new clsEstudiante();
$objCar = new clsCarrera();
$objTur = new clsTurno();
    
$arrayTipoDoc = $objEst->listaTipoDocumento();
$arrayTipoDoc = $arrayTipoDoc->fetchAll(PDO::FETCH_NAMED);

$listaCarrera = $objCar->listarCarrera('', 1);
$listaCarrera = $listaCarrera->fetchAll(PDO::FETCH_NAMED);

$listaTurno = $objTur->listarTurno('', 1);
$listaTurno = $listaTurno->fetchAll(PDO::FETCH_NAMED);
?>
<section class="content-header">
     <div class="container-fluid">
		<div class="card card-primary">
			<div class="card-header" style="background-color: #2C3E50; color: #FFFFFF;">
				<h3 class="card-title">Listado de Estudiantes</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Tipo Doc.</span>
							</div>
							<select class="form-control" name="cboBusquedaTipoDoc" id="cboBusquedaTipoDoc" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach($arrayTipoDoc as $k=>$v){ ?>
								<option value="<?= $v['idtipodocumento'] ?>"><?= $v['nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Nro Doc</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaDocumento" id="txtBusquedaDocumento" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Nombre</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaNombre" id="txtBusquedaNombre" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Carrera.</span>
							</div>
							<select class="form-control" name="cboBusquedaCarrera" id="cboBusquedaCarrera" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach($listaCarrera as $k=>$v){ ?>
								<option value="<?= $v['idcarrera'] ?>"><?= $v['nombre_ca'] ?></option>
								<?php } ?>
							</select>
					    </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Turno</span>
							</div>
							<select class="form-control" name="cboBusquedaTurno" id="cboBusquedaTurno" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach($listaTurno as $k=>$v){ ?>
								<option value="<?= $v['idturno'] ?>"><?= $v['nombre_tu'] ?></option>
								<?php } ?>
							</select>
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
						<button type="button" class="btn btn-success" onclick="nuevoEstudiante()"><i class="fa fa-plus"></i> Nuevo</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="divListadoEstudiante">
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
			url: 'vista/estudiantes_listado.php',
			data:{
				nombre: $('#txtBusquedaNombre').val(),
				estado: $('#cboBusquedaEstado').val(),
				idtipodocumento: $('#cboBusquedaTipoDoc').val(),
				documento: $('#txtBusquedaDocumento').val(),
                idcarrera: $('#cboBusquedaCarrera').val(),
                idturno: $('#cboBusquedaTurno').val()
			}
		})
		.done(function(resultado){
			$('#divListadoEstudiante').html(resultado);
		});
	}

	verListado();

	
	function nuevoEstudiante(){
		abrirModal('vista/estudiantes_formulario','accion=NUEVO','divmodal1','Registro de Estudiantes');
	}


</script>