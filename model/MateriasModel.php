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
  		//$result = $this->link->query("SELECT * FROM materias ORDER BY semestre ASC,codigo ASC");
  		$result = $this->link->query("SELECT m.codigo,m.asignatura,s.nombre_semestre,tam.nombre_tipo,c.nombre_carrera,m.horas_academicas_totales,m.horas_academicas_semanales FROM materias m INNER JOIN semestre s ON m.id_semestre=s.id_semestre INNER JOIN tipo_aula_materia tam ON m.id_tipo=tam.id_tipo INNER JOIN carreras c ON m.id_carrera=c.id_carrera ORDER BY s.id_semestre ASC,codigo ASC");
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

	public function getMateriasBySemestre($idSemestre){
		$query = "SELECT m.codigo FROM materias m INNER JOIN semestre sem ON m.id_semestre=sem.id_semestre WHERE m.id_semestre=$idSemestre";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$materias[]=$rows;
		}
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

	public function setMaterias ($codigo,$codigoNuevo,$asignatura,$idSemestre,$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera) {
		$result = $this->link->query("UPDATE materias SET codigo=$codigoNuevo, asignatura='$asignatura',id_semestre='$idSemestre', horas_academicas_totales = $horasAcademicasTotales,horas_academicas_semanales=$horasAcademicasSemanales,id_tipo=$idTipo, id_carrera = $idCarrera WHERE codigo=$codigo");
  		return;
	}

	public function createMaterias ($codigo,$asignatura,$idSemestre,$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera) {
		$result = $this->link->query("INSERT INTO materias (codigo,asignatura,id_semestre,horas_academicas_totales,horas_academicas_semanales,id_tipo,id_carrera) VALUES ($codigo,'$asignatura','$idSemestre',$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera)");
		return;
	}

	public function deleteMaterias ($codigo) {
		$result = $this->link->query("DELETE FROM materias WHERE codigo='$codigo'");
		return;
	}
}
?>