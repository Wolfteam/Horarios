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
		$result->closeCursor();
  		return $horarios;
	}

	public function getHorarioProfesoresByDiaAula($idDia,$idAula){
		$horarioProfesores=[];
		$query= "SELECT * from horario_profesores WHERE id_dia=$idDia AND id_aula=$idAula";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		$result->closeCursor();
		return $horarioProfesores;
	}

	public function getHorarioProfesoresByCedulaDia($cedula,$idDia){
		$horarioProfesores=[];
		$query= "SELECT * from horario_profesores WHERE cedula=$cedula AND id_dia=$idDia";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		$result->closeCursor();
		return $horarioProfesores;
	}

	public function getHorarioProfesoresBySemestreDia($idSemestre,$idDia){
		$horarioProfesores=[];
		$query = "SELECT hp.codigo,hp.id_hora_inicio,hp.id_hora_fin FROM horario_profesores hp INNER JOIN materias m on hp.codigo = m.codigo INNER JOIN semestre sem ON m.id_semestre = sem.id_semestre WHERE sem.id_semestre=$idSemestre AND hp.id_dia=$idDia";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		$result->closeCursor();
		return $horarioProfesores;		
	}

	public function getHorarioProfesoresByCodigoDia($codigo,$idDia){
		$horarioProfesores=[];
		$query = "SELECT * FROM horario_profesores WHERE codigo=$codigo AND id_dia=$idDia";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		$result->closeCursor();
		return $horarioProfesores;	
	}

	public function findHorarioProfesor($cedula,$idHoraInicio,$idHoraFin,$idDia){
		$horarioProfesores=[];
		$query = "SELECT * from horario_profesores WHERE cedula = $cedula AND id_hora_inicio = $idHoraInicio AND id_hora_fin = $idHoraFin AND id_dia=$idDia";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		$result->closeCursor();
		if (count($horarioProfesores)>0) {

			return true;
		}
		return false;
	}

	public function createHorarioProfesores($cedula,$codigo,$idDia,$idHoraInicio,$idHoraFin,$idAula,$numeroSeccion){
		$query = "INSERT INTO horario_profesores VALUES($cedula,$codigo,$idDia,$idHoraInicio,$idHoraFin,$idAula,$numeroSeccion)";
		$result = $this->link->query($query);
		$result->closeCursor();
		return;
	}
	
}
?>