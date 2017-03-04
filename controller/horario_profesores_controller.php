<?php
require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/HorarioProfesoresModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/SeccionesModel.php');

//agregar una condicion para acceder a todo esto, la cual se pasa x post
$link = DBConnection::connection();
$objHorarioProfesores = new HorarioProfesoresModel($link);
$objProfesores = new ProfesoresModel($link);
$objSecciones = new SeccionesModel($link);

$objDisponibilidad->deleteAllDisponibilidadProfesores();
$objSecciones->deleteAllSecciones();
//Aca se deberia guardar la data sobre el nombreSemestre y su duracion
//y llamar a los metodos de sus respectiva clase

for ($prioridad=1; $prioridad<=6; $prioridad++) {
	//Quienes son los prof. que tienen disponibilidad y la prioridad en la que me encuentro
	$profesores = $objHorarioProfesores->getProfesoresConDisponbilidad($prioridad);
	$numeroProfesoresObtenidos=count($profesores);
	
}
?>