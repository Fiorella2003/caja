<?php
require_once('conexion.php');

class clsSemestre{

function listarSemestre($nombre, $fecha_inicio, $fecha_fin, $estado){
    $sql = "SELECT *, 
            DATE_FORMAT(fecha_inicio,'%d/%m/%Y') as fecha_inicio, 
            DATE_FORMAT(fecha_fin,'%d/%m/%Y') as fecha_fin 
            FROM semestre WHERE estado < 2";
    $parametros = array();

    if($nombre != ""){
        $sql .= " AND nombre LIKE :nombre ";
        $parametros[':nombre'] = '%'.$nombre.'%';
    }

    if($fecha_inicio != ""){
        $sql .= " AND fecha_inicio >= :fecha_inicio ";
        $parametros[':fecha_inicio'] = $fecha_inicio;
    }

    if($fecha_fin != ""){
        $sql .= " AND fecha_fin <= :fecha_fin ";
        $parametros[':fecha_fin'] = $fecha_fin;
    }        

    if($estado != ""){
        $sql .= " AND estado = :estado ";
        $parametros[':estado'] = $estado;
    }

    global $cnx;
    $pre = $cnx->prepare($sql);
    $pre->execute($parametros);

    return $pre;
}


	function insertarSemestre($nombre, $fecha_inicio, $fecha_fin, $estado){
		$sql = "INSERT INTO semestre VALUES(null, :nombre, :fecha_inicio, :fecha_fin, :estado)";
		$parametros = array(':nombre'=>$nombre, ':fecha_inicio'=>$fecha_inicio, ':fecha_fin'=>$fecha_fin, ':estado'=>$estado);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;

	}

	function verificarDuplicado($nombre, $idsemestre=0){
		$sql = "SELECT idsemestre, nombre FROM semestre WHERE nombre=:nombre AND estado<2 AND idsemestre<>:idsemestre";
		$parametros = array(':nombre'=>$nombre, ':idsemestre'=>$idsemestre);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function consultarSemestrePorId($idsemestre){
		$sql = "SELECT * FROM semestre WHERE idsemestre=:idsemestre";
		$parametros = array(':idsemestre'=>$idsemestre);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function actualizarSemestre($idsemestre, $nombre, $fecha_inicio, $fecha_fin, $estado){
		$sql = "UPDATE semestre SET nombre=:nombre, fecha_inicio=:fecha_inicio, fecha_fin=:fecha_fin, estado=:estado WHERE idsemestre=:idsemestre";
		$parametros = array(':nombre'=>$nombre, ':fecha_inicio'=>$fecha_inicio, ':fecha_fin'=>$fecha_fin, ':estado'=>$estado, ':idsemestre'=>$idsemestre);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function actualizarEstadoSemestre($idsemestre, $estado){
		$sql = "UPDATE semestre SET estado=:estado WHERE idsemestre=:idsemestre";
		$parametros = array(':estado'=>$estado, ':idsemestre'=>$idsemestre);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

}

?>