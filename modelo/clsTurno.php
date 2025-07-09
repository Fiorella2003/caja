<?php
require_once('conexion.php');

class clsTurno{


    function listarTurno($nombre_tu, $estado){
		$sql = "SELECT * FROM turno WHERE estado<2";
		$parametros = array();

		if($nombre_tu!=""){
			$sql .= " AND nombre_tu LIKE :nombre_tu ";
			$parametros[':nombre_tu'] = '%'.$nombre_tu.'%';
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



}


?>