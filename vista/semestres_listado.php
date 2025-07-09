<?php
require_once('../modelo/clsSemestre.php');

$objSem = new clsSemestre();

$nombre = $_POST['nombre'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$estado = $_POST['estado'];


$dataSemestre = $objSem->listarSemestre($nombre, $fecha_inicio, $fecha_fin, $estado);
$dataSemestre = $dataSemestre->fetchAll(PDO::FETCH_ASSOC);


?>
<link rel="stylesheet" href="css/estilo_exportar.css">

<div class="table-responsive">
<table class="table table-hover text-nowrap table-striped table-sm" id="tablaSemestre">
	<thead>
		<tr>
			<th>COD</th>
			<th>DESCRIPCION</th>
			<th>FECHA DE INICIO</th>
			<th>FECHA DE CIERRE</th>            
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dataSemestre as $k=>$v){ 
			if($v['estado']==1){
				$estado = "Activo";
				$class = "";
			}else{
				$estado = 'Anulado';
				$class = "text-danger";
			}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $v['idsemestre']; ?></td>
			<td><?php echo $v['nombre']; ?></td>
			<td><?php echo $v['fecha_inicio']; ?></td>
            <td><?php echo $v['fecha_fin']; ?></td>
			<td><?php echo $estado; ?></td>
			<td>
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Semestre" onclick="editarSemestre(<?php echo $v['idsemestre']; ?>)" ><i class="fa fa-edit"></i> </button>
			</td>
			<td>
				<?php if($v['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Semestre" onclick="cambiarEstadoSemestre(<?php echo $v['idsemestre']; ?>,0)"><i class="fa fa-trash"></i> </button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Semestre" onclick="cambiarEstadoSemestre(<?php echo $v['idsemestre']; ?>,1)"><i class="fa fa-check"></i> </button>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Semestre" onclick="cambiarEstadoSemestre(<?php echo $v['idsemestre']; ?>,2)"><i class="fa fa-times"></i> </button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
<script>
	
	function editarSemestre(id){
		abrirModal('vista/semestres_formulario','accion=ACTUALIZAR&&idsemestre='+id,'divmodal1','Editar Semestre');
	}

	function cambiarEstadoSemestre(idsemestre, estado){
		proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
		mensaje = "¿Estás Seguro de "+proceso[estado]+" el Semestre?";
		accion = "EjecutarCambiarEstadoSemestre("+idsemestre+","+estado+")";

		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoSemestre(idsemestre, estado) {

		$.ajax({
			method: 'POST',
			url: 'controlador/contSemestre.php',
			data: {
				accion: 'CAMBIAR_ESTADO_SEMESTRE',
				idsemestre: idsemestre,
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

	$("#tablaSemestre").DataTable({
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
    }).buttons().container().appendTo('#tablaSemestre_wrapper .col-md-6:eq(0)');

</script>