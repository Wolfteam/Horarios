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
		$disponibilidad=[];
		if ($cedula!=false) {
			$result = $this->link->query($queryB);
		}else{
			$result = $this->link->query($queryA);
		}
  		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$disponibilidad[]=$rows;
		}
  		return $disponibilidad;
  	}

	//saca solamente un array con las cedulas 
	public function getDisponiblidadProfesoresByPrioridad($idPrioridad){
		$profesores=[];
		//SELECT campo_con_duplicados FROM tabla GROUP BY campo_con_duplicados
		$query = "SELECT dp.cedula FROM profesores p, prioridad_profesor pp, disponibilidad_profesores dp WHERE p.cedula = dp.cedula AND p.id_prioridad = pp.id_prioridad AND p.id_prioridad=$idPrioridad GROUP BY dp.cedula";
		$result = $this->link->query($query);
		foreach ($result as $key) {
			$profesores[]=$key['cedula'];
		}
		return $profesores;
	}

	public function findProfesoresByDisponibilidadPrioridadMateria($idPrioridad,$codigo){
		$query = "SELECT dp.cedula FROM disponibilidad_profesores dp, profesores p, profesores_materias pm WHERE dp.cedula=p.cedula AND dp.cedula=pm.cedula AND p.id_prioridad=$idPrioridad AND pm.codigo=$codigo GROUP BY dp.cedula";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$disponibilidad[]=$rows;
		}
  		return $disponibilidad;
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