<?php
require_once('../modelo/clsEstudiante.php');

controlador($_POST['accion']);

function controlador($accion){
	$objEst = new clsEstudiante();

	switch ($accion) {
		case 'NUEVO':
			$resultado = array();
			try {
				
				$nombre = mb_strtoupper($_POST['nombre']);
				$estado = $_POST['estado'];
				$idtipodocumento = $_POST['idtipodocumento'];
				$nrodocumento = $_POST['nrodocumento'];
				$direccion = $_POST['direccion'];
				$genero = $_POST['genero']; 
				$idcarrera = $_POST['idcarrera'];
				$ciclo = $_POST['ciclo'];
				$idturno = $_POST['idturno'];
				$celular = $_POST['celular'];
				$existeEstudiante = $objEst->verificarDuplicado($nrodocumento);
				if($existeEstudiante->rowCount()>0){
					throw new Exception("Existe un Estudiante con el mismo numero de documento");
				}

				$objEst->insertarEstudiante($idtipodocumento, $nrodocumento, $nombre, $direccion, $genero, $idcarrera, $ciclo, $idturno, $celular, $estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Estudiante Registrado de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo registrar al Estudiante. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CONSULTAR_ESTUDIANTE':
			try {

				$idestudiante = $_POST['idestudiante'];
				$resultado = $objEst->consultarEstudiantePorId($idestudiante);
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
				$idtipodocumento = $_POST['idtipodocumento'];
				$nrodocumento = $_POST['nrodocumento'];
				$direccion = $_POST['direccion'];
				$genero = $_POST['genero']; 
				$idcarrera = $_POST['idcarrera'];
				$ciclo = $_POST['ciclo'];
				$idturno = $_POST['idturno'];
				$celular = $_POST['celular'];
				$idestudiante = $_POST['idestudiante'];

				$existeEstudiante = $objEst->verificarDuplicado($nrodocumento, $idestudiante);
				if($existeEstudiante->rowCount()>0){
					throw new Exception("Existe un Estudiante con el mismo numero de documento");
				}

				$objEst->actualizarEstudiante($idestudiante, $idtipodocumento, $nrodocumento, $nombre, $direccion,$genero, $idcarrera, $ciclo, $idturno, $celular, $estado);
				$resultado['correcto']=1;
				$resultado['mensaje']= "Estudiante Actualizado de forma satisfactoria.";

				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']= "No se pudo actualizar al estudiante. ".$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CAMBIAR_ESTADO_ESTUDIANTE':
			try {

				$idestudiante = $_POST['idestudiante'];
				$estado = $_POST['estado'];

				$arrayEstado = array('ANULADO','ACTIVADO','ELIMINADO');

				$objEst->actualizarEstadoEstudiante($idestudiante, $estado);
				$resultado = array('correcto'=>1, 'mensaje'=>'El Estudiante ha sido '.$arrayEstado[$estado].' de forma satisfactoria.');

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado = array('correcto'=>0, 'mensaje'=>$e->getMessage());
				echo json_encode($resultado);
			}
			break;


		case 'CONSULTAR_DATOS_WS':
			$retorno = array();
            try{
                $idtipodoc = $_POST['idtipodocumento'];
                $nrodocumento =  $_POST['nrodocumento'];
                $retorno = array(
                        "idtipodocumento"=>$_POST['idtipodocumento'],
                        "nombre"=>"",
                        "direccion"=>""
                    );
                
                $existe = $objEst->verificarDuplicado($_POST['nrodocumento']);
                $consultarws = true;
                if($existe->rowCount()>0){
                    $estudiante = $existe->fetch(PDO::FETCH_NAMED);
                    $retorno = array(
                        "idtipodocumento"=>$estudiante["idtipodocumento"],
                        "nombre"=>$estudiante['nombre'],
                        "direccion"=>$estudiante['direccion']
                    );
                    $consultarws = false;
                }   

                if($idtipodoc==1 && $consultarws){
                    $ws = "https://dniruc.apisperu.com/api/v1/dni/".$nrodocumento."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imx1aXN0aW1hbmFnb256YWdhQGhvdG1haWwuY29tIn0.IxAceLS9puCS0LdM3yLtHZwzsstZAX6ot6RZdTVAiZc";
                    
                    $opts = array(
					    'ssl' => array(
					        'verify_peer' => false,
					        'verify_peer_name' => false
					    )
					);

					$context = stream_context_create($opts);
                    $datos = file_get_contents($ws,false,$context);
                    $datos = json_decode($datos,true);
                    if(isset($datos['nombres'])){
                        $retorno["nombre"]=$datos['nombres'].' '.$datos['apellidoPaterno'].' '.$datos['apellidoMaterno'];
                    }
                }

                if($idtipodoc==6 && $consultarws){
                    $ws = "https://api.apis.net.pe/v1/ruc?numero=$nrodocumento";
                    $datos = file_get_contents($ws);
                    $datos = json_decode($datos,true);
                    if(isset($datos['nombre'])){
                        $retorno['nombre'] = $datos['nombre'];
                        $retorno['direccion'] = $datos['direccion'].' '.$datos['distrito'].'-'.$datos['provincia'].'-'.$datos['departamento'];
                    }
                }                    
            }catch(Exception $ex){
                $retorno = array();
            }
            echo json_encode($retorno);
            break;


		default:
			echo 'ACCION NO EXISTE';
			break;

	}

}

?>