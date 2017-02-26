<?php
require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php'); 
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DisponibilidadProfesoresModel.php');

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
	$registros = count($disponibilidad->getDisponiblidadProfesores($cedula));
	$resultado=["horas_a_cumplir"=>$horasACumplir,
				"disponibilidad"=>$registros,
	];
	echo json_encode($resultado);
}

if (isset($_POST['operacion']) && $_POST['operacion']=="create") {
	$data = $_POST['data'];
	decodificarData($data);
	//error_log(print_r($data,true));
	//error_log(print_r($data,true));
	echo "string";
}

function decodificarData($data){
	error_log(print_r($data,true));
	$resultado =[];
	for ($j=1; $j < 7; $j++) {
		$horas = 0;
		for ($i=1; $i < 13; $i++) {
			$indexA = $i+","+$j;
			$indexB = ($i+1)+","+$j;
			$indexC = ($i-1)+","+$j;
			if (in_array($indexA,$data) && in_array($indexB, $data)) {
				$horas+=2;
				$i++;
			}else if (in_array($indexA,$data) && in_array($indexC, $data) && $i>1){
				$horas+=1;
			}else{
				if ($horas!=0) {
					$horaInicio = $i;
					$horaFin=$horaInicio + $horas;
					$dia = $j;
					$horas = 0;
					error_log("Dia:".$dia.",hora inicio:".$horaInicio.",hora fin:".$horaFin);
				}
			}
		}
	}
	return;
}

?>

