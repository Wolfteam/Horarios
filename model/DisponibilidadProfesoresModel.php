<?php 
/**
 * Esta clase contiene metodos para leer/crear/borrar disponibilidad de profesores.
 * No existe metodo para actualizar puesto que lo que se hace es borrar los
 * registros anteriores y crear nuevos, esto permite prescindir de un metodo
 * update/set.
 */
class DisponibilidadProfesoresModel {
  	private $link;

 	function __construct($link){
 		$this->link = $link;
 	}

	public function getDisponiblidadProfesores($cedula=false) {
		$queryA = "SELECT * FROM disponibilidad_profesores";
		$queryB = "SELECT * FROM disponibilidad_profesores WHERE cedula=$cedula";
		$profesores=[];
		if ($cedula!=false) {
			$result = $this->link->query($queryB);
		}else{
			$result = $this->link->query($queryA);
		}
  		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$profesores[]=$rows;
		}
  		return $profesores;
  	}

 	public function createDisponibilidadProfesores($cedula,$arrayData) {
 		foreach ($arrayData as $key => $value) {
 			$dia = $value["dia"];
 			$horaInicio = $value["hora_inicio"];
 			$horaFin = $value["hora_fin"];
 			$query="INSERT INTO disponibilidad_profesores VALUES ($cedula,$dia,$horaInicio,$horaFin)";
 			$result = $this->link->query($query);
 		} 		
		return;
 	}

 	public function deleteDisponibilidadProfesores($cedula) {
		$result = $this->link->query("DELETE FROM disponibilidad_profesores WHERE cedula=$cedula");
		return;
	}
	//Esto debe ser probado para ver si resetea los ids (es decir que com iencen desde 0)
	public function deleteAllDisponibilidadProfesores () {
		$result = $this->link->query("TRUNCATE disponibilidad_profesores");
		return;
	}
} 
?>