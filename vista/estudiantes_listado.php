<?php
require_once('../modelo/clsEstudiante.php');

$objEst = new clsEstudiante();


$nombre = $_POST['nombre'];
$estado = $_POST['estado'];
$idcarrera = $_POST['idcarrera'];
$idturno = $_POST['idturno'];


$dataEstudiante = $objEst->listarEstudiante($nombre, $estado, $idcarrera, $idturno);


?>
<link rel="stylesheet" href="css/estilo_exportar.css">

<table class="table table-hover text-nowrap table-striped table-sm" id="tablaEstudiante">
	<thead>
		<tr>
			<th>COD</th>
			<th>NOMBRE</th>
			<th>CARRERA</th>
			<th>TURNO</th>
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dataEstudiante as $k=>$v){ 
			if($v['estado']==1){
				$estado = "Activo";
				$class = "";
			}else{
				$estado = 'Anulado';
				$class = "text-danger";
			}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $v['idestudiante']; ?></td>
			<td><?php echo $v['nombre']; ?></td>
			<td><?= $v['carrera'] ?></td>
			<td><?= $v['turno'] ?></td>
			<td><?php echo $estado; ?></td>
			<td>
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Estudiante" onclick="editarEstudiante(<?php echo $v['idestudiante']; ?>)" ><i class="fa fa-edit"></i></button>
			</td>
			<td>
				<?php if($v['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Estudiante" onclick="cambiarEstadoEstudiante(<?php echo $v['idestudiante']; ?>,0)"><i class="fa fa-trash"></i> </button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Estudiante" onclick="cambiarEstadoEstudiante(<?php echo $v['idestudiante']; ?>,1)"><i class="fa fa-check"></i> </button>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Estudiante" onclick="cambiarEstadoEstudiante(<?php echo $v['idestudiante']; ?>,2)"><i class="fa fa-times"></i> </button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<script>
	
	function editarEstudiante(id){
		abrirModal('vista/estudiantes_formulario','accion=ACTUALIZAR&&idestudiante='+id,'divmodal1','Editar Estudiante');
	}

	function cambiarEstadoEstudiante(idestudiante, estado){
		proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
		mensaje = "¿Estás Seguro de "+proceso[estado]+" al estudiante?";
		accion = "EjecutarCambiarEstadoEstudiante("+idestudiante+","+estado+")";

		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoEstudiante(idestudiante, estado) {

		$.ajax({
			method: 'POST',
			url: 'controlador/contEstudiante.php',
			data: {
				accion: 'CAMBIAR_ESTADO_ESTUDIANTE',
				idestudiante: idestudiante,
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

	$("#tablaEstudiante").DataTable({
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
            			columns: [0, 1, 2, 3, 4] 
       					}
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> ',
                        className: 'btn-pdf',
                        titleAttr: 'PDF',
						exportOptions: {
            			columns: [0, 1, 2, 3, 4] 
       					}
                    }
                ]
    }).buttons().container().appendTo('#tablaEstudiante_wrapper .col-md-6:eq(0)');

</script>