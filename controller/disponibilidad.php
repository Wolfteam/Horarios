<?php
require($_SERVER['DOCUMENT_ROOT'].'/Model/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Model/ProfesoresModel.php'); 
require_once($_SERVER['DOCUMENT_ROOT'].'/Model/DisponibilidadProfesoresModel.php');

$link = DBConnection::connection();
$profesores = new ProfesoresModel($link);
$disponibilidad = new DisponibilidadProfesoresModel($link);
if (isset($_POST['operacion']) && $_POST['operacion']=="read") {
	$resultado = $profesores->getProfesores();
	$data='<option value="0">Seleccione profesor</option>';
	foreach ($resultado as $key) {
		$data.="<option value=".$key['cedula'].">".$key['nombre']." ".$key['apellido']."</option>";
	}
	echo $data;
	
}

if (isset($_POST['cedula']) && $_POST['cedula']!="0") {	
	$resultado = [];
	$cedula = $_POST['cedula'];
	//error_log($cedula);
	$horasACumplir = $profesores->getHorasACumplir($cedula);
	$registros = $disponibilidad->getDisponiblidadProfesores($cedula);
	$numeroRegistros = count($registros);
	$resultado=["horas_a_cumplir"=>$horasACumplir,
				"registros"=>$registros,
				"numero_registros"=>$numeroRegistros
	];
	echo json_encode($resultado);
}

if (isset($_POST['operacion']) && $_POST['operacion']=="create_update" && isset($_POST['cedula']) && $_POST['cedula'] !="" && isset($_POST['data'])) {
	$data = $_POST['data'];
	$cedula = $_POST['cedula'];
	$arrayData = decodificarData($data);
	$disponibilidad->deleteDisponibilidadProfesores($cedula);
	$disponibilidad->createDisponibilidadProfesores($cedula,$arrayData);
}

if (isset($_POST['operacion']) && $_POST['operacion']=="delete" && isset($_POST['cedula']) && $_POST['cedula'] !="") {
	$disponibilidad->deleteDisponibilidadProfesores($cedula);
}


function decodificarData($data){
	error_log(print_r($data,true));
	$resultado =[];
	for ($j=1; $j < 7; $j++) {
		$horas = 0;
		for ($i=1; $i <= 13; $i++) {
			$indexA = $i.",".$j;
			$indexB = ($i+1).",".$j;
			$indexC = ($i-1).",".$j;
			if (in_array($indexA,$data) && in_array($indexB, $data)) {
				$horas+=2;
				$i++;
			}else if (in_array($indexA,$data) && in_array($indexC, $data) && $i>1){
				$horas+=1;
			}else{
				if ($horas!=0) {
					$horaInicio = $i-$horas;
					$horaFin=$horaInicio + $horas;
					$dia = $j;
					$resultado[] =array("dia" => $dia,
										"hora_inicio"=> $horaInicio,
										"hora_fin"=> $horaFin);
					$horas = 0;
				}
			}
		}
	}
	return $resultado;
}

?>

