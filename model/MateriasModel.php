<?php 
/**
 * Esta clase contiene metodos para leer/editar/crear/borrar
 * registros en la tabla "materias" de la DB
 */
class MateriasModel{
	private $link;

	function __construct($link){
		$this->link=$link;
	}

	public function getMaterias () {
		$materias=array();
  		$result = $this->link->query("SELECT * FROM materias ORDER BY semestre ASC,codigo ASC");
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$materias[]=$rows;
		}
		return $materias;
	}

	public function getMaterias2 ($codigo) {
  		$result = $this->link->query("SELECT * FROM materias WHERE codigo=$codigo");
  		$materias = $result->fetch(PDO::FETCH_ASSOC);
  		return $materias;
	}

	public function getHorasSemanales($codigo){
		$result = $this->link->query("SELECT horas_academicas_semanales FROM materias WHERE codigo=$codigo");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$dato= $row['horas_academicas_semanales'];
		return $dato;
	}

	public function getTipoMateria($codigo){
		$result = $this->link->query("SELECT id_tipo FROM materias WHERE codigo=$codigo");
		$fila = $result->fetch(PDO::FETCH_ASSOC);
		$dato= $fila['id_tipo'];
		return $dato;
	}

	public function setMaterias ($codigo,$codigoNuevo,$asignatura,$semestre,$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera) {
		$result = $this->link->query("UPDATE materias SET codigo=$codigoNuevo, asignatura='$asignatura',semestre='$semestre', horas_academicas_totales = $horasAcademicasTotales,horas_academicas_semanales=$horasAcademicasSemanales,id_tipo=$idTipo, id_carrera = $idCarrera WHERE codigo=$codigo");
  		return;
	}

	public function createMaterias ($codigo,$asignatura,$semestre,$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera) {
		$result = $this->link->query("INSERT INTO materias (codigo,asignatura,semestre,horas_academicas_totales,horas_academicas_semanales,id_tipo,id_carrera) VALUES ($codigo,'$asignatura','$semestre',$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera)");
		return;
	}

	public function deleteMaterias ($codigo) {
		$result = $this->link->query("DELETE FROM materias WHERE codigo='$codigo'");
		return;
	}
}
?>