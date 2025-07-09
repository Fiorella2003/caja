<?php
require_once('../modelo/clsCarrera.php');

controlador($_POST['accion']);

function controlador($accion){
	$objCat = new clsCarrera();

	switch ($accion) {
		case 'NUEVO':
			$resultado = array();
			try {
				
				$nombre_ca = mb_strtoupper($_POST['nombre_ca']);
				$estado = $_POST['estado'];

				$existeCarrera = $objCat->verificarDuplicado($nombre_ca);
				if($existeCarrera->rowCount()>0){
					throw new Exception("Existe una categoria con el mismo nombre");
				}

				$objCat->insertarCarrera($nombre_ca,$estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Carrera Registrada de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo registrar la Carrera. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CONSULTAR_CARRERA':
			try {

				$idcarrera = $_POST['idcarrera'];
				$resultado = $objCat->consultarCarreraPorId($idcarrera);
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
				
				$nombre_ca = mb_strtoupper($_POST['nombre_ca']);
				$estado = $_POST['estado'];
				$idcarrera = $_POST['idcarrera'];

				$existeCarrera = $objCat->verificarDuplicado($nombre_ca,$idcarrera);
				if($existeCarrera->rowCount()>0){
					throw new Exception("Existe una carrera con el mismo nombre_ca");
				}

				$objCat->actualizarCarrera($idcarrera,$nombre_ca,$estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Carrera Actualizada de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo actualizar la Carrera. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CAMBIAR_ESTADO_CARRERA':
			try {

				$idcarrera = $_POST['idcarrera'];
				$estado = $_POST['estado'];

				$arrayEstado = array('ANULADA','ACTIVADA','ELIMINADA');

				$objCat->actualizarEstadoCarrera($idcarrera, $estado);
				$resultado = array('correcto'=>1, 'mensaje'=>'La Carrera ha sido '.$arrayEstado[$estado].' de forma satisfactoria.');

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