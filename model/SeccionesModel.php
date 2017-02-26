<?php
/**
 * Esta clase contiene metodos para leer/editar/crear/borrar
 * en la tabla "secciones" de la DB
 */
class SeccionesModel{
  	private $link;

  	function __construct($link){
  		$this->link = $link;
  	}

  	public function getSecciones(){
  		$secciones=array();
  		$result = $this->link->query("SELECT s.codigo, s.numero_seccion,s.cantidad_alumnos,m.asignatura,m.semestre FROM secciones s, materias m WHERE s.codigo=m.Codigo ORDER BY m.semestre,m.codigo ASC,s.numero_seccion");
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$secciones[]=$rows;
		}
		return $secciones;
  	}

  	public function getSeccion2($id){
  		$result = $this->link->query("SELECT * FROM secciones WHERE ID=$id");
  		$query = $result->fetch(PDO::FETCH_ASSOC);
  		return json_encode($query);
  	}

  	public function getSeccion($codigo){
  		$secciones=array();
  		$result = $this->link->query("SELECT * FROM secciones WHERE Codigo=$codigo ORDER BY Numero_Seccion");
  		$filas = $result->rowCount();
  		if ($filas==0){
  			return $secciones[]=false;
  		}
  		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
  			$secciones[]=$rows;
  		}	
  		return $secciones;
  	}

  	public function getCantidadAlumno($idSeccion){
  		$result = $this->link->query("SELECT Cantidad_Alumnos FROM secciones WHERE ID='$idSeccion'");
  		$filas = $result->fetch(PDO::FETCH_ASSOC);
  		$dato = $filas['Cantidad_Alumnos'];
  		return $dato;
  	}
  	//Aca si ya hay una seccion creada de una materia, no permito que pueda cambiar dicha materia
  	public function setSecciones($id,$numeroSeccion,$cantidadAlumnos){
  		$result = $this->link->query("UPDATE secciones SET Numero_Seccion=$numeroSeccion,Cantidad_Alumnos = $cantidadAlumnos WHERE ID=$id");
  		return;
  	}

  	public function createSecciones ($numeroSeccion,$cantidadAlumnos,$codigo){
		$result = $this->link->query("INSERT INTO secciones (Numero_Seccion,Cantidad_Alumnos,Codigo) VALUES ($numeroSeccion,$cantidadAlumnos,$codigo)");
		return;
  	}

  	public function deleteSecciones ($id){
		$result = $this->link->query("DELETE FROM secciones WHERE ID=$id");
		return;
  	}
  	//Esto debe ser probado para ver si resetea los ids
  	public function deleteAllSecciones (){
		$result = $this->link->query("TRUNCATE secciones");
		return;
  	}	  	
}  
?>