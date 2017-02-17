<?php  
	/**
	* Quizas aca poner cualquier acceso a la base de datos para generar los archivos?
	*/
/*
	class HomeModel{
		private $link;

		function __construct($link){
			$this->link = $link;
		}

		public function getMaterias () {
			$materias=array();
	  		$result = $this->link->query("SELECT * FROM materias ORDER BY semestre ASC,codigo ASC");
			while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
				$materias[]=$rows;
			}
			return $materias;
		}

		public function getSecciones($codigo){
			$secciones=array();
			$result = $this->link->query("SELECT * FROM secciones WHERE Codigo=$codigo ORDER BY Numero_Seccion");
	  		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
	  			$secciones[]=$rows;
	  		}	
	  		return $secciones;
		}

		public function getHorariosVista($id){
			$result = $this->link->query("SELECT * FROM secciones_view WHERE ID='$id'");
			$rows = $result->fetchAll(PDO::FETCH_ASSOC);
			return $rows;
		}

		public function getCantidadSecciones($codigo){
			$result = $this->link->query("SELECT * FROM secciones WHERE Codigo=$codigo ORDER BY Numero_Seccion");
			$cantidadSecciones = $result->rowCount();
			return $cantidadSecciones;
		}


		public function getCantidadHorariosVista($id){
			$result = $this->link->query("SELECT * FROM secciones_view WHERE ID='$id'");
			$filas = $result->rowCount();
			return $filas;
		}
	}
	/*
?>