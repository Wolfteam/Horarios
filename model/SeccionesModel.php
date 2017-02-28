<?php
/**
 * Esta clase contiene metodos para leer/crear/borrar
 * en la tabla "secciones" de la DB
 */
class SeccionesModel{
  	private $link;

  	function __construct($link){
  		$this->link = $link;
  	}

  	public function getSecciones($codigo=false){
        $queryA="SELECT s.codigo, s.numero_seccion,s.cantidad_alumnos,m.asignatura,m.semestre FROM secciones s, materias m WHERE s.codigo=m.Codigo ORDER BY m.semestre,m.codigo ASC,s.numero_seccion";
        $queryB="SELECT * FROM secciones WHERE codigo=$codigo";  
        $secciones=[];
        if ($codigo!=false) {
            $result = $this->link->query($queryB);
        }else{
            $result = $this->link->query($queryA);
        } 		
  		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
  			$secciones[]=$rows;
  		}
		return $secciones;
  	}

  	public function getNumeroSeccionesCreadas($codigo){
  		$numeroSeccionesCreadas=0;
  		$result = $this->link->query("SELECT * FROM secciones WHERE codigo=$codigo");
        //no se si usar rowCount me genere algun problema
  		$filas = $result->rowCount();
  		if ($filas!=0){
            $numeroSeccionesCreadas=$filas;
  			return $numeroSeccionesCreadas;
  		}
  		return $secciones;
  	}

  	public function getCantidadAlumnos($codigo,$numeroSeccion){
  		$result = $this->link->query("SELECT cantidad_alumnos FROM secciones WHERE codigo=$codigo AND numero_seccion=$numeroSeccion");
  		$filas = $result->fetch(PDO::FETCH_ASSOC);
  		$dato = $filas['cantidad_alumnos'];
  		return $dato;
  	}

  	public function createSecciones($codigo,$cantidadSecciones,$cantidadAlumnos){
        $result = $this->link->query("SELECT * FROM secciones WHERE codigo=$codigo");
        $filas = $result->rowCount();
        if ($filas!=0) {
            $result = $this->link->query("DELETE FROM secciones WHERE codigo=$codigo");
        }
        for ($i=1; $i<=$cantidadSecciones; $i++) { 
            $result = $this->link->query("INSERT INTO secciones VALUES ($codigo,$i,$cantidadAlumnos)"); 
        }
		return;
  	}

  	public function deleteSecciones($codigo=false,$numeroSeccion=false){
        if ($codigo!=false && $numeroSeccion==false) {
            $queryA="DELETE FROM secciones WHERE codigo=$codigo";
            $result = $this->link->query($queryA);
        }else{
            $queryB="DELETE FROM secciones WHERE codigo=$codigo AND numero_seccion=$numeroSeccion";
            $result = $this->link->query($queryB);
        }	
		return;
  	}

  	public function deleteAllSecciones(){
		$result = $this->link->query("TRUNCATE secciones");
		return;
  	}	  	
}  
?>