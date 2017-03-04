<?php  
/**
 * 
 */
class HorarioProfesoresModel{
	private $link;

	function __construct($link){
		$this->link=$link;
	}
/*
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
*/
	public function getProfesoresConDisponbilidad($idPrioridad){
		$profesores=[];
		//SELECT campo_con_duplicados FROM tabla GROUP BY campo_con_duplicados
		$query = "SELECT dp.cedula FROM profesores p, prioridad_profesor pp, disponibilidad_profesores dp WHERE p.cedula = dp.cedula AND p.id_prioridad = pp.id_prioridad AND p.id_prioridad=$idPrioridad GROUP BY dp.cedula";
		$result = $this->link->query($query);
		foreach ($result as $key) {
			$profesores[]=$key['cedula'];
		}
		return $profesores;
	}

	public function createHorarioProfesores($cedula,$codigo,$numeroSeccion,$idDia,$idHoraInicio,$idHoraFin,$idAula){
		$query = "INSERT INTO horario_profesores VALUES($cedula,$codigo,$numeroSeccion,$idDia,$idHoraInicio,$idHoraFin,$idAula)";
		$result = $this->link->query($query);
		return;
	}
	
}
?>