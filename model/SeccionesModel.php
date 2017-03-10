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

    public function getCantidadAlumnos($codigo){
        $result = $this->link->query("SELECT cantidad_alumnos FROM secciones WHERE codigo=$codigo");
        $filas = $result->fetch(PDO::FETCH_ASSOC);
        $dato = $filas['cantidad_alumnos'];
        return $dato;
    }

    public function createSecciones($codigo,$cantidadSecciones,$cantidadAlumnos){
    	$result = $this->link->query("INSERT INTO secciones VALUES ($codigo,$cantidadSecciones,$cantidadAlumnos)");
        return;
    }

    public function deleteSecciones($codigo){
        $queryA="DELETE FROM secciones WHERE codigo=$codigo";
        $result = $this->link->query($queryA);     
        return;
    }

    public function deleteAllSecciones(){
        $result = $this->link->query("TRUNCATE secciones");
        return;
    }     
}  
?>