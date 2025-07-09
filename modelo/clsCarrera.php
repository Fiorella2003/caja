<?php
require_once('conexion.php');

class clsCarrera{

	function listarCarrera($nombre_ca, $estado){
		$sql = "SELECT * FROM carrera WHERE estado<2";
		$parametros = array();

		if($nombre_ca!=""){
			$sql .= " AND nombre_ca LIKE :nombre_ca ";
			$parametros[':nombre_ca'] = '%'.$nombre_ca.'%';
		}

		if($estado!=""){
			$sql .= " AND estado=:estado ";
			$parametros[':estado']=$estado;
		}

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}


	function insertarCarrera($nombre_ca, $estado){
		$sql = "INSERT INTO carrera VALUES(null, :nombre_ca, :estado)";
		$parametros = array(':nombre_ca'=>$nombre_ca, ':estado'=>$estado);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;

	}

	function verificarDuplicado($nombre, $idcarrera=0){
		$sql = "SELECT idcarrera, nombre_ca FROM carrera WHERE nombre_ca=:nombre_ca AND estado<2 AND idcarrera<>:idcarrera";
		$parametros = array(':nombre'=>$nombre, ':idcarrera'=>$idcarrera);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function consultarCarreraPorId($idcarrera){
		$sql = "SELECT * FROM carrera WHERE idcarrera=:idcarrera";
		$parametros = array(':idcarrera'=>$idcarrera);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function actualizarCarrera($idcarrera, $nombre_ca, $estado){
		$sql = "UPDATE carrera SET nombre_ca=:nombre_ca, estado=:estado WHERE idcarrera=:idcarrera";
		$parametros = array(':nombre_ca'=>$nombre_ca, ':estado'=>$estado, ':idcarrera'=>$idcarrera);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function actualizarEstadoCarrera($idcarrera, $estado){
		$sql = "UPDATE carrera SET estado=:estado WHERE idcarrera=:idcarrera";
		$parametros = array(':estado'=>$estado, ':idcarrera'=>$idcarrera);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

}

?>