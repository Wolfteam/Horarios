<?php
	/**
	* En todas las clases deberia llamar a closeCursor antes del return
	*/
	
	class ProfesoresModel{
	  	private $link;

	  	function __construct($link){
	  		$this->link=$link;		
	  	}

	  	public function getProfesores(){
	  		$profesor=array();
	  		$result = $this->link->query("SELECT * FROM profesores ORDER BY id_prioridad ASC, nombre ASC");
			while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
				$profesor[]=$rows;
			}
			return $profesor;
	  	}

	  	//A esta funcion le puede llegar un array de ids 
	  	public function getProfesor2($cedula){
	  		$filas = count($cedula);
	  		for ($i=0; $i < $filas; $i++) { 
	  			$valor = $cedula[$i][0];
	  			$result = $this->link->prepare("SELECT * FROM profesores WHERE cedula=$valor");
	  			$result->execute();
	  			$profesor[]= $result->fetch(PDO::FETCH_ASSOC);
	  		}
			return $profesor;
	  	}

	  	public function getProfesores3($cedula){
	  		$result = $this->link->query("SELECT * FROM profesores WHERE cedula=$cedula");
	  		$profesor=$result->fetch(PDO::FETCH_ASSOC);
	  		return $profesor;
	  	}

	  	public function setProfesores($cedula,$nombre,$apellido,$idPrioridad){
	  		$result = $this->link->query("UPDATE profesores SET nombre='$nombre',apellido='$apellido', id_prioridad = $idPrioridad WHERE cedula=$cedula");
	  		return;
	  	}


		public function createProfesores ($nombre,$apellido,$idPrioridad){
			$result = $this->link->query("INSERT INTO profesores (nombre,apellido,id_prioridad) VALUES ('$nombre','$apellido',$idPrioridad)");
			return;
		}

		public function deleteProfesores ($cedula) {
			$result = $this->link->query("DELETE FROM profesores WHERE cedula=$cedula");
			return;
		}
	}  
?>