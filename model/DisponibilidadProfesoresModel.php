<?php 
/**
 * 
 */
class DisponibilidadProfesoresModel {
  	private $link;

 	function __construct($link){
 		$this->link = $link;
 	}

	public function getDisponiblidadProfesores($cedula=false){
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

 	public function setDisponibilidadProfesores($cedula,$idDia,$idDiaDB,$idHoraInicio,$idHoraInicioDB,$idHoraFin,$idHoraFinDB){
  		$result = $this->link->query("UPDATE disponibilidad_profesores SET id_dia=$idDia, id_hora_inicio=$idHoraInicio, id_hora_fin=$idHoraFin WHERE cedula=$cedula AND
  			id_dia=$idDiaDB AND id_hora_inicio=$idHoraInicioDB AND id_hora_fin=$idHoraFinDB");
  		return;	 		
 	}

 	public function createDisponibilidadProfesores($cedula,$idDia,$idHoraInicio,$idHoraFin){
 		$result = $this->link->query("INSERT INTO disponibilidad_profesores VALUES ($cedula,$idDia,$idHoraInicio,$idHoraFin)");
		return;
 	}

 	public function deleteDisponibilidadProfesores($cedula,$idDia,$idHoraInicio,$idHoraFin) {
		$result = $this->link->query("DELETE FROM disponibilidad_profesores WHERE cedula=$cedula AND id_dia=$idDia AND id_hora_inicio=$idHoraInicio AND id_hora_fin=$idHoraFin");
		return;
	}
	//Esto debe ser probado para ver si resetea los ids (es decir que com iencen desde 0)
	public function deleteAllDisponibilidadProfesores () {
		$result = $this->link->query("TRUNCATE disponibilidad_profesores");
		return;
	}
} 
?>