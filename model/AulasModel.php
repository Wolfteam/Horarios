<?php  
	/**
	* 
	*/
	
	class AulasModel{
		private $link;	
		
		function __construct($link){
			$this->link =$link;
		}

		public function getAulas(){
			$aulas=array();
			$result = $this->link->query("SELECT a.id_aula,a.nombre_aula,a.capacidad,a.id_tipo,tam.nombre_tipo FROM aulas a, tipo_aula_materia tam WHERE a.id_tipo=tam.id_tipo ORDER BY id_aula ASC");
	  		//$result = $this->link->query("SELECT * FROM aulas");	
			while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
				$aulas[]=$rows;
			}
			return $aulas;
		}

		public function getAulas2($idTipo){
			$aulas=array();
	  		$result = $this->link->query("SELECT * FROM aulas WHERE id_tipo=$idTipo");
			while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
				$aulas[]=$rows;
			}
			return $aulas;
		}

		public function getAulas3($idAula){
	  		$result = $this->link->query("SELECT * FROM aulas WHERE id_aula=$idAula");
	  		$aulas = $result->fetch(PDO::FETCH_ASSOC);
	  		return $aulas;
		}

		public function setAulas ($idAula,$nombreAula,$capacidad,$idTipo) {
			$result = $this->link->query("UPDATE aulas SET nombre_aula='$nombreAula',capacidad=$capacidad, id_tipo = $idTipo WHERE id_aula=$idAula");
	  		return;
		}

		public function createAulas ($nombreAula,$capacidad,$idTipo) {
			$result = $this->link->query("INSERT INTO aulas (nombre_aula,capacidad,id_tipo) VALUES ('$nombreAula',$capacidad,$idTipo)");
			return;
		}

		public function deleteAulas ($id_aula) {
			$result = $this->link->query("DELETE FROM aulas WHERE id_aula=$id_aula");
			return;
		}
	}
?>