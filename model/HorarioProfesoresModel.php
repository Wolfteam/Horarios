<?php  
/**
 * 
 */
class HorarioProfesoresModel{
	private $link;

	function __construct($link){
		$this->link=$link;
	}

	public function getHorarioProfesores($cedula=false){
		$queryA = "SELECT * FROM horario_profesores";
		$queryB = "SELECT * FROM horario_profesores WHERE cedula=$cedula";
		$horarios=[];
		if ($cedula!=false) {
			$result = $this->link->query($queryB);
		}else{
			$result = $this->link->query($queryA);
		}
  		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarios[]=$rows;
		}
  		return $horarios;
	}

	public function getHorarioProfesoresByDiaAula($idDia,$idAula){
		$query= "SELECT * from horario_profesores WHERE id_dia=$idDia AND id_aula=$idAula";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		return $horarioProfesores;
	}

	public function getHorarioProfesoresByCedulaDia($cedula,$idDia){
		$query= "SELECT * from horario_profesores WHERE cedula=$cedula AND id_dia=$idDia";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		return $horarioProfesores;
	}

	public function getHorarioProfesoresBySemestreDia($idSemestre,$idDia){
		$query = "SELECT hp.codigo,hp.id_hora_inicio,hp.id_hora_fin FROM horario_profesores hp INNER JOIN materias m on hp.codigo = m.codigo INNER JOIN semestre sem ON m.id_semestre = sem.id_semestre WHERE sem.id_semestre=$idSemestre AND hp.id_dia=$idDia";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		return $horarioProfesores;		
	}

	public function getHorarioProfesoresByCodigoDia($codigo,$idDia){
		$query = "SELECT * FROM horario_profesores WHERE codigo=$codigo AND id_dia=$idDia";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		return $horarioProfesores;	
	}

	public function findHorarioProfesor($cedula,$idHoraInicio,$idHoraFin,$idDia){
		$query = "SELECT * from horario_profesores WHERE cedula = $cedula AND id_hora_inicio = $idHoraInicio AND id_hora_fin = $idHoraFin AND id_dia=$idDia";
		$result = $this->link->query($query);
		$horarioProfesor = $result->fetch(PDO::FETCH_ASSOC);
		if (count($horarioProfesor)>0) {
			return true;
		}
		return false;
	}

	public function createHorarioProfesores($cedula,$codigo,$idAula,$idDia,$idHoraInicio,
		$idHoraFin,$numeroSeccion){
		$query = "INSERT INTO horario_profesores VALUES($cedula,$codigo,$idAula,$idDia,$idHoraInicio,$idHoraFin,$numeroSeccion)";
		$result = $this->link->query($query);
		return;
	}
	
}
?>