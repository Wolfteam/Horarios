<?php
require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php'); 

$link = DBConnection::connection();
$object = new ProfesoresModel($link);
if (isset($_POST['operacion']) && $_POST['operacion']=="read") {
	//$datos = array();
	$profesores = $object->getProfesores();
	$data='<option value="0">Seleccione profesor</option>';
	foreach ($profesores as $key) {
		$data.="<option value=".$key['cedula'].">".$key['nombre']." ".$key['apellido']."</option>";
	}
	echo $data;
	
}

if (isset($_POST['cedula']) && $_POST['cedula']!="0") {	
	$cedula = $_POST['cedula'];
	$horasACumplir = $object->getHorasACumplir($cedula);
	echo json_encode($horasACumplir);
}



?>
