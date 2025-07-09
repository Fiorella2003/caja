<?php
require_once('../modelo/clsUsuario.php');
$accion = $_POST['accion'];

controlador($accion);

function controlador($accion){
	$objUsu = new clsUsuario();

	switch ($accion) {	
		case 'INICIAR_SESION':
			$usuario = $_POST['usuario'];
			$clave = $_POST['clave'];

			$respuesta = array();

			$datoUsuario = $objUsu->verificarUsuario($usuario, $clave);
			if($datoUsuario->rowCount()>0){

				$datos = $datoUsuario->fetch(PDO::FETCH_NAMED);
				$_SESSION['idusuario'] = $datos['idusuario'];
				$_SESSION['nombre'] = $datos['nombre'];
				$_SESSION['usuario'] = $datos['usuario'];
				$_SESSION['idperfil'] = $datos['idperfil'];
				$_SESSION['perfil'] = $datos['perfil'];

				$respuesta['correcto']=1;
				$respuesta['mensaje'] = 'Usuario y contraseña Correcta';
			}else{
				$respuesta['correcto']=0;
				$respuesta['mensaje'] = 'Usuario o contraseña Incorrecta';
			}

			echo json_encode($respuesta);
			break;

		case 'NUEVO':
			$resultado = array();
			try {
				
				$nombre = $_POST['nombre'];
				$usuario = $_POST['usuario'];
				$clave = $_POST['clave'];
				$idperfil = $_POST['idperfil'];
				$estado = $_POST['estado'];

				$existeUsuario = $objUsu->verificarDuplicado($usuario);
				if($existeUsuario->rowCount()>0){
					throw new Exception("Existe un Usuario con el mismo Login");
				}

				$objUsu->insertarUsuario($nombre, $usuario, $clave, $idperfil, $estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Usuario Registrado de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo registrar el Usuario. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'ACTUALIZAR':
			$resultado = array();
			try {
				
				$nombre = $_POST['nombre'];
				$usuario = $_POST['usuario'];
				$clave = $_POST['clave'];
				$idperfil = $_POST['idperfil'];
				$estado = $_POST['estado'];
				$idusuario = $_POST['idusuario'];

				$existeUsuario = $objUsu->verificarDuplicado($usuario, $idusuario);
				if($existeUsuario->rowCount()>0){
					throw new Exception("Existe un Usuario con el mismo Login");
				}

				$objUsu->actualizarUsuario($nombre, $usuario, $clave, $idperfil, $estado, $idusuario);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Usuario Actualizado de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo actualizar el Usuario. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CAMBIAR_ESTADO_USUARIO':
			try {

				$idusuario = $_POST['idusuario'];
				$estado = $_POST['estado'];

				$arrayEstado = array('ANULADO','ACTIVADO','ELIMINADO');

				$objUsu->actualizarEstadoUsuario($idusuario, $estado);
				$resultado = array('correcto'=>1, 'mensaje'=>'El usuario ha sido '.$arrayEstado[$estado].' de forma satisfactoria.');

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado = array('correcto'=>0, 'mensaje'=>$e->getMessage());
				echo json_encode($resultado);
			}
			break;
		
		default:
			// code...
			break;
	}


}


?>