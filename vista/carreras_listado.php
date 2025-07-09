<?php
require_once('../modelo/clsCarrera.php');

$objCar = new clsCarrera();

$nombre_ca = $_POST['nombre_ca'];
$estado = $_POST['estado'];


$dataCarrera = $objCar->listarCarrera($nombre_ca, $estado);
$dataCarrera = $dataCarrera->fetchAll(PDO::FETCH_NAMED);

// echo '<pre>';
// print_r($dataCarrera);
// echo '</pre>';

?>
<link rel="stylesheet" href="css/estilo_exportar.css">

<div class="table-responsive">
<table class="table table-hover text-nowrap table-striped table-sm" id="tablaCarrera">
	<thead>
		<tr>
			<th>COD</th>
			<th>DESCRIPCION</th>
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dataCarrera as $k=>$v){ 
			if($v['estado']==1){
				$estado = "Activo";
				$class = "";
			}else{
				$estado = 'Anulado';
				$class = "text-danger";
			}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $v['idcarrera']; ?></td>
			<td><?php echo $v['nombre_ca']; ?></td>
			<td><?php echo $estado; ?></td>
			<td>
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Carrera" onclick="editarCarrera(<?php echo $v['idcarrera']; ?>)" ><i class="fa fa-edit"></i> </button>
			</td>
			<td>
				<?php if($v['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Carrera" onclick="cambiarEstadoCarrera(<?php echo $v['idcarrera']; ?>,0)"><i class="fa fa-trash"></i> </button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Carrera" onclick="cambiarEstadoCarrera(<?php echo $v['idcarrera']; ?>,1)"><i class="fa fa-check"></i> </button>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Carrera" onclick="cambiarEstadoCarrera(<?php echo $v['idcarrera']; ?>,2)"><i class="fa fa-times"></i> </button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
<script>
	
	function editarCarrera(id){
		abrirModal('vista/carreras_formulario','accion=ACTUALIZAR&&idcarrera='+id,'divmodal1','Editar Carrera');
	}

	function cambiarEstadoCarrera(idcarrera, estado){
		proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
		mensaje = "¿Estás Seguro de "+proceso[estado]+" la Carrera?";
		accion = "EjecutarCambiarEstadoCarrera("+idcarrera+","+estado+")";

		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoCarrera(idcarrera, estado) {

		$.ajax({
			method: 'POST',
			url: 'controlador/contCarrera.php',
			data: {
				accion: 'CAMBIAR_ESTADO_CARRERA',
				idcarrera: idcarrera,
				estado: estado
			},
			dataType: 'json'
		})
		.done(function(resultado){
			if(resultado.correcto==1){
				toastCorrecto(resultado.mensaje);
				verListado();
			}else{
				toastError(resultado.mensaje);
			}
		})
	}

	$("#tablaCarrera").DataTable({
		"responsive": true,
		"lengthChange": false,
		"autoWidth": false,
		"searching": false,
		"ordering": true,
		"lengthMenu": [
			[5, 25, 50, 100, -1],
			[5, 25, 50, 100, "Todos"]
		],
		"language": {
			"decimal": "",
			"emptyTable": "Sin datos",
			"info": "Del _START_ al _END_ de _TOTAL_ filas",
			"infoEmpty": "Del 0 a 0 de 0 filas",
			"infoFiltered": "(filtro de _MAX_ filas totales)",
			"infoPostFix": "",
			"thousands": ",",
			"lengthMenu": "Ver _MENU_ filas",
			"loadingRecords": "Cargando...",
			"processing": "Procesando...",
			"search": "Buscar:",
			"zeroRecords": "No se encontraron resultados",
			"paginate": {
				"first": "Primero",
				"last": "Ultimo",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": orden ascendente",
				"sortDescending": ": orden descendente"
			}
		},
		buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> ',
                        className: 'btn-excel',
                        titleAttr: 'Excel',
						exportOptions: {
            			columns: [0, 1, 2] 
       					}
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> ',
                        className: 'btn-pdf',
                        titleAttr: 'PDF',
						exportOptions: {
            			columns: [0, 1, 2] 
       					}
                    }
                ]
    }).buttons().container().appendTo('#tablaCarrera_wrapper .col-md-6:eq(0)');

</script>