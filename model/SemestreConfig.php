<?php  
/**
 * 
 */
class Semestre{
	private $nombreSemestre;
	private $duracion;
	function __construct($nombreSemestre,$duracion){
		$this->nombreSemestre=$nombreSemestre;
		$this->duracion=$duracion;
	}

	//Falta crear un metodo que actualize la duracion de las materias
	//Ademas de aca se debe tomar el nombreSemestre para generar los archivos
}
?>