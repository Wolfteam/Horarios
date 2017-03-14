<?php
/**
 * Esta clase contiene metodos para leer/crear/borrar
 * registros en la tabla "secciones" de la DB
 */
class SeccionesModel{
    private $link;

    function __construct($link){
        $this->link = $link;   
    }

    public function getSecciones($codigo=false){
        //$queryA="SELECT s.codigo, s.numero_secciones,s.cantidad_alumnos,m.asignatura,m.id_semestre FROM secciones s, materias m WHERE s.codigo=m.Codigo ORDER BY m.id_semestre,m.codigo ASC,s.numero_secciones";
        $queryA = "SELECT sec.codigo, sec.numero_secciones,sec.cantidad_alumnos,m.asignatura,m.id_semestre,sem.nombre_semestre FROM materias m INNER JOIN semestre sem ON m.id_semestre=sem.id_semestre INNER JOIN secciones sec ON m.codigo=sec.codigo ORDER BY m.id_semestre,m.codigo ASC";
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
        $result->closeCursor();
        return $secciones;
    }

    public function getNumeroSeccionesCreadas($codigo){
        //SELECT COUNT(*) FROM secciones
        $result = $this->link->query("SELECT numero_secciones FROM secciones WHERE codigo=$codigo");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if (count($row)==0) {
        	$numeroSeccionesCreadas=0;
        }else{
			$numeroSeccionesCreadas = $row['numero_secciones'];
        }
        $result->closeCursor();
        return $numeroSeccionesCreadas;
    }

    public function getCantidadAlumnos($codigo){
        $result = $this->link->query("SELECT cantidad_alumnos FROM secciones WHERE codigo=$codigo");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if (count($row)==0) {
        	$dato=0;
        }else{
        	$dato = $row['cantidad_alumnos'];
        }
        $result->closeCursor();
        return $dato;
    }

    public function createSecciones($codigo,$cantidadSecciones,$cantidadAlumnos){
    	$result = $this->link->query("INSERT INTO secciones VALUES ($codigo,$cantidadSecciones,$cantidadAlumnos)");
    	$result->closeCursor();
        return;
    }

    public function deleteSecciones($codigo){
        $queryA="DELETE FROM secciones WHERE codigo=$codigo";
        $result = $this->link->query($queryA);
        $result->closeCursor();    
        return;
    }

    public function deleteAllSecciones(){
        $result = $this->link->query("TRUNCATE secciones");
        $result->closeCursor();
        return;
    }     
}  
?>