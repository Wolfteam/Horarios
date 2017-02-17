<?php
	/**
	  * 
	  */
	class ProfesoresMateriasModel {
	  	private $link;

	  	function __construct($link){
	  		$this->link = $link;
	  	}

	  	public function getProfesoresMaterias(){
			$profesoresMaterias=array();
	  		$result = $this->link->query("SELECT * FROM profesores_materias");
			while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
				$profesoresMaterias[]=$rows;
			}
			return $profesoresMaterias;
		}
		
		public function getProfesoresMaterias2($codigo){
			$profesoresMaterias=array();
			$result = $this->link->query("SELECT cedula FROM profesores_materias WHERE codigo=$codigo");
			while ($rows = $result->fetch(PDO::FETCH_NUM)) {
				$profesoresMaterias[]=$rows;
			}			
			return $profesoresMaterias;
			//$fila=count($profesoresMaterias);
			//return ($fila);
		}

		public function getProfesoresMaterias3(){
			$profesoresMaterias=array();
	  		$result = $this->link->query("SELECT m.codigo,m.asignatura, m.semestre, p.cedula ,p.nombre, p.apellido FROM materias m,profesores p,profesores_materias pm WHERE pm.cedula=p.cedula AND pm.codigo=m.codigo ORDER BY m.semestre,m.asignatura,p.nombre");
			while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
				$profesoresMaterias[]=$rows;
			}
			return $profesoresMaterias;
		}

		//Notese que con un solo metodo puedo actualizar los 3 casos a la vez, siempre y cuando reciba todos los parametros
		public function setProfesoresMaterias ($cedula,$codigo,$cedulaNueva,$codigoNuevo) {
			$result = $this->link->query("UPDATE profesores_materias SET cedula=$cedulaNueva,codigo=$codigoNuevo WHERE cedula=$cedula AND codigo=$codigo");
	  		return;
		}  
	  
		public function createProfesorMaterias ($cedula,$codigo){
			$result = $this->link->query("INSERT INTO profesores_materias (cedula,codigo) VALUES ($cedula,$codigo)");
			return;
		}

		public function deleteProfesorMaterias ($cedula,$codigo){
			$result = $this->link->query("DELETE FROM profesores_materias WHERE cedula=$cedula AND codigo=$codigo");
			return;
		}
	}
?>