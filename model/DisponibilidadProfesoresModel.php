<?php 
	/**
	 * 
	 */
	class DisponibilidadProfesoresModel {
	  	private $link;

	 	function __construct($link){
	 		$this->link = $link;
	 	}

	 	public function getDisponibilidadProfesores (){
	 		$disponibilidadProfesores=array();
	  		$result = $this->link->query("SELECT * FROM disponibilidad_profesores");
	  		//quizas agregar un rowcount, el cual si da 0 hacer un return con un string
	  		//para luego en el controlador usar isArray para validarlo
			while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
				$disponibilidadProfesores[]=$rows;
			}
			return $disponibilidadProfesores;
	 	}

	 	public function getDisponibilidadProfesoresPorID($id_seccion){
	 		$disponibilidadProfesores=array();
	  		$result = $this->link->query("SELECT * FROM disponibilidad_profesores WHERE ID_seccion = $id_seccion");
			while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
				$disponibilidadProfesores[]=$rows;
			}
			return $disponibilidadProfesores;
	 	}

	 	public function setDisponibilidadProfesores($cedula,$idDia,$idDiaDB,$idHoraInicio,$idHoraInicioDB,$idHoraFin,$idHoraFinDB){
	  		$result = $this->link->query("UPDATE disponibilidad_profesores SET id_dia=$idDia, id_hora_inicio=$idHoraInicio, id_hora_fin=$idHoraFin WHERE cedula=$cedula AND
	  			id_dia=$idDiaDB AND idHoraInicio=$idHoraInicioDB AND idHoraFin=$idHoraFinDB");
	  		return;	 		
	 	}

	 	public function createDisponibilidadProfesores ($cedula,$idDia,$idHoraInicio,$idHoraFin){
	 		$result = $this->link->query("INSERT INTO disponibilidad_profesores (cedula,id_dia,id_hora_inicio,id_hora_fin) VALUES ($cedula,$idDia,$idHoraInicio,$idHoraFin)");
			return;
	 	}

	 	public function deleteDsponibilidadProfesores ($cedula,$idDia,$idHoraInicio,$idHoraFin) {
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