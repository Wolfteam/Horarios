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
	  		$result = $this->link->query("SELECT p.cedula,p.nombre,p.apellido,p.id_prioridad,pp.codigo_prioridad FROM profesores p, prioridad_profesor pp WHERE p.id_prioridad=pp.id_prioridad ORDER BY p.id_prioridad ASC");
	  		//$result = $this->link->query("SELECT * FROM profesores ORDER BY id_prioridad ASC, nombre ASC");
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

	  	public function getHorasACumplir($cedula){
	  		//devuelve puros 7 en horas por asignar
	  		$result = $this->link->query("SELECT pp.horas_a_cumplir FROM profesores p, prioridad_profesor pp WHERE p.cedula=$cedula AND p.id_prioridad=pp.id_prioridad");
	  		$profesor=$result->fetch(PDO::FETCH_ASSOC);
	  		return $profesor;	  		
	  	}

	  	public function setProfesores($cedula,$cedulaNueva,$nombre,$apellido,$idPrioridad){
	  		$result = $this->link->query("UPDATE profesores SET cedula=$cedulaNueva,nombre='$nombre',apellido='$apellido', id_prioridad = $idPrioridad WHERE cedula=$cedula");
	  		return;
	  	}


		public function createProfesores ($cedula,$nombre,$apellido,$idPrioridad){
			$result = $this->link->query("INSERT INTO profesores (cedula,nombre,apellido,id_prioridad) VALUES ($cedula,'$nombre','$apellido',$idPrioridad)");
			return;
		}

		public function deleteProfesores ($cedula) {
			$result = $this->link->query("DELETE FROM profesores WHERE cedula=$cedula");
			return;
		}
	}  
?>