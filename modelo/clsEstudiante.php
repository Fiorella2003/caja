<?php
require_once('conexion.php');

class clsEstudiante{
	function listarEstudiante($nombre, $estado, $idcarrera, $idturno){
        $sql = "SELECT
                es.idestudiante,
				es.nombre,
                ca.nombre_ca as carrera,
                tu.nombre_tu as turno,
                es.estado
            FROM
                estudiante es
            LEFT JOIN carrera ca ON es.idcarrera = ca.idcarrera
            LEFT JOIN turno tu ON es.idturno = tu.idturno
            WHERE es.estado < 2";		
		$parametros = array();

		if($nombre!=""){
			$sql .= " AND nombre LIKE :nombre ";
			$parametros[':nombre'] = '%'.$nombre.'%';
		}

		if($estado!=""){
			$sql .= " AND estado=:estado ";
			$parametros[':estado']=$estado;
		}

		if($idcarrera!=""){
			$sql .= " AND ca.idcarrera LIKE :idcarrera ";
			$parametros[':idcarrera'] = '%'.$idcarrera.'%';
		}

		if($idturno!=""){
			$sql .= " AND tu.idturno LIKE :idturno ";
			$parametros[':idturno'] = '%'.$idturno.'%';
		}
		

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}


	function insertarEstudiante($idtipodocumento, $nrodocumento, $nombre, $direccion, $genero, $idcarrera, $ciclo, $idturno, $celular, $estado){
		$sql = "INSERT INTO estudiante VALUES(null, :nombre, :idtipodocumento, :nrodocumento, :direccion, :genero, :idcarrera, :ciclo, :idturno, :celular, :estado)";
		$parametros = array(':nombre'=>$nombre, ':estado'=>$estado, ':idtipodocumento'=>$idtipodocumento, ':nrodocumento'=>$nrodocumento, ':direccion'=>$direccion, ':genero'=>$genero, ':idcarrera'=>$idcarrera, ':ciclo'=>$ciclo, ':idturno'=>$idturno, ':celular'=>$celular);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;

	}

	function verificarDuplicado($nrodocumento, $idestudiante=0){
		$sql = "SELECT * FROM estudiante WHERE nrodocumento=:nrodocumento AND estado<2 AND idestudiante<>:idestudiante";
		$parametros = array(':nrodocumento'=>$nrodocumento, ':idestudiante'=>$idestudiante);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function consultarEstudiantePorId($idestudiante){
		$sql = "SELECT * FROM estudiante WHERE idestudiante=:idestudiante";
		$parametros = array(':idestudiante'=>$idestudiante);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function actualizarEstudiante($idestudiante, $idtipodocumento, $nrodocumento, $nombre, $direccion, $genero, $idcarrera, $ciclo, $idturno, $celular, $estado){
		$sql = "UPDATE estudiante SET nombre = :nombre, idtipodocumento = :idtipodocumento, nrodocumento = :nrodocumento, direccion = :direccion, genero = :genero, idcarrera = :idcarrera, ciclo = :ciclo, idturno =:idturno, celular = :celular, estado = :estado WHERE idestudiante=:idestudiante";
		$parametros = array(':nombre'=>$nombre, ':estado'=>$estado, ':idtipodocumento'=>$idtipodocumento, ':nrodocumento'=>$nrodocumento, ':direccion'=>$direccion,':genero'=>$genero, ':idcarrera'=>$idcarrera, ':ciclo'=>$ciclo, ':idturno'=>$idturno, ':celular'=>$celular ,':idestudiante'=>$idestudiante);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function actualizarEstadoEstudiante($idestudiante, $estado){
		$sql = "UPDATE estudiante SET estado=:estado WHERE idestudiante=:idestudiante";
		$parametros = array(':estado'=>$estado, ':idestudiante'=>$idestudiante);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function listaTipoDocumento(){
		$sql = "SELECT * FROM tipodocumento WHERE estado=1 ";

		global $cnx;
		$pre= $cnx->query($sql);

		return $pre;
	}

	function listaCarrera(){
		$sql = "SELECT * FROM carrera WHERE estado=1 ";

		global $cnx;
		$pre= $cnx->query($sql);

		return $pre;
	}

	function listaTurno(){
		$sql = "SELECT * FROM turno WHERE estado=1 ";

		global $cnx;
		$pre= $cnx->query($sql);

		return $pre;
	}

}

?>