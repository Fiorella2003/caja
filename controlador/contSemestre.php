<?php
require_once('../modelo/clsSemestre.php'); 

controlador($_POST['accion']);

function controlador($accion){
	$objSem = new clsSemestre();

	switch ($accion) {
		case 'NUEVO':
			$resultado = array();
			try {
				
				$nombre = mb_strtoupper($_POST['nombre']);
				$fecha_inicio = $_POST['fecha_inicio'];
				$fecha_fin = $_POST['fecha_fin'];
				$estado = $_POST['estado'];

				$existeSemestre = $objSem->verificarDuplicado($nombre);
				if($existeSemestre->rowCount()>0){
					throw new Exception("Existe un semestre con el mismo nombre");
				}

				$objSem->insertarSemestre($nombre, $fecha_inicio, $fecha_fin, $estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Semestre Registrado de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo registrar el semestre. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CONSULTAR_SEMESTRE':
			try {

				$idsemestre = $_POST['idsemestre'];
				$resultado = $objSem->consultarSemestrePorId($idsemestre);
				$resultado = $resultado->fetch(PDO::FETCH_NAMED);

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado = array('correcto'=>0, 'mensaje'=>$e->getMessage());
				echo json_encode($resultado);
			}
			break;

		case 'ACTUALIZAR':
			$resultado = array();
			try {
				
				$nombre = mb_strtoupper($_POST['nombre']);
				$fecha_inicio = $_POST['fecha_inicio'];
				$fecha_fin = $_POST['fecha_fin'];
				$estado = $_POST['estado'];
				$idsemestre = $_POST['idsemestre'];

				$existeSemestre = $objSem->verificarDuplicado($nombre,$idsemestre);
				if($existeSemestre->rowCount()>0){
					throw new Exception("Existe un semestre con el mismo nombre");
				}

				$objSem->actualizarSemestre($idsemestre,$nombre, $fecha_inicio, $fecha_fin, $estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Semestre actualizado de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo actualizar el semestre. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CAMBIAR_ESTADO_SEMESTRE':
			try {

				$idsemestre = $_POST['idsemestre'];
				$estado = $_POST['estado'];

				$arrayEstado = array('ANULADA','ACTIVADA','ELIMINADA');

				$objSem->actualizarEstadoSemestre($idsemestre, $estado);
				$resultado = array('correcto'=>1, 'mensaje'=>'El Semestre ha sido '.$arrayEstado[$estado].' de forma satisfactoria.');

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado = array('correcto'=>0, 'mensaje'=>$e->getMessage());
				echo json_encode($resultado);
			}
			break;
		
		default:
			echo 'ACCION NO EXISTE';
			break;

	}

}

?>