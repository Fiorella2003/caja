<?php
require_once('../modelo/clsPerfil.php');

controlador($_POST['accion']);

function controlador($accion){
	$objPer = new clsPerfil();

	switch ($accion) {
		case 'NUEVO':
			$resultado = array();
			try {
				
				$nombre = mb_strtoupper($_POST['nombre']);
				$estado = $_POST['estado'];

				$existePerfil = $objPer->verificarDuplicado($nombre);
				if($existePerfil->rowCount()>0){
					throw new Exception("Existe un Perfil con el mismo nombre");
				}

				$objPer->insertarPerfil($nombre,$estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Perfil Registrado de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo registrar el Perfil. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CONSULTAR_PERFIL':
			try {

				$idperfil = $_POST['idperfil'];
				$resultado = $objPer->consultarPerfilPorId($idperfil);
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
				$estado = $_POST['estado'];
				$idperfil = $_POST['idperfil'];

				$existePerfil = $objPer->verificarDuplicado($nombre,$idperfil);
				if($existePerfil->rowCount()>0){
					throw new Exception("Existe un Perfil con el mismo nombre");
				}

				$objPer->actualizarPerfil($idperfil,$nombre,$estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Perfil Actualizado de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo actualizar el perfil. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CAMBIAR_ESTADO_PERFIL':
			try {

				$idperfil = $_POST['idperfil'];
				$estado = $_POST['estado'];

				$arrayEstado = array('ANULADO','ACTIVADO','ELIMINADO');

				$objPer->actualizarEstadoPerfil($idperfil, $estado);
				$resultado = array('correcto'=>1, 'mensaje'=>'El Perfil ha sido '.$arrayEstado[$estado].' de forma satisfactoria.');

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado = array('correcto'=>0, 'mensaje'=>$e->getMessage());
				echo json_encode($resultado);
			}
			break;

		case 'VERIFICAR_ACCESO':
			try {

				$idperfil = $_POST['idperfil'];
				$idopcion = $_POST['idopcion'];
				$estado = $_POST['estado'];

				$existePermiso = $objPer->verificarPermiso($idperfil, $idopcion);
				if($existePermiso->rowCount()>0){
					$objPer->actualizarPermiso($idperfil, $idopcion, $estado);
				}else{
					$objPer->insertarPermiso($idperfil, $idopcion);
				}

				$resultado = array('correcto'=>1, 'mensaje'=>'El Perfil Actualizado de forma satisfactoria.');

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