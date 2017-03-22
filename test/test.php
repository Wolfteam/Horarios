<!DOCTYPE html>
<html>
<head>
	<title>+</title>
</head>
<body>
<?php 
/*
	$array=[
		"cedula" => "21255727",
		"Nombre" => "efrain",
	];

	$horarioGenerado = [
		array(
			"cedula"=>21255727,
			"codigo"=>41541233,
			"id_aula"=>17,
			"id_dia"=>3,
			"id_hora_inicio"=>1,
			"id_hora_fin"=>5						
		)
	];
	error_log(print_r($horarioGenerado,true));

//	error_log(print_r($dias,true));
	for ($i=1; $i < 7; $i++) { 
		$dias[]=$i;
	}
//	error_log(print_r($dias,true));
	foreach ($dias as $key => $value) {
		echo $value;
	}


for ($i=0; $i < 4; $i++) { 
	foreach ($array as $key) {
		echo $key."<br>";
	}
}

	$numero = 5;
	$numero2 = $numero/2;
	echo "<br>".$numero2;
	if (is_float($numero2)) {
		$numeroRedondeado=round($numero2);
		echo "Era un flotante";
		echo $numeroRedondeado;
	}
	$numeroRedondeado=round($numero2);
	echo $numeroRedondeado."<br>"."<br>";

	$k=4;
	$j=5;
	while ($k <= 3) {
		echo "entre al while";
		$k++;
	}

	$horarioGenerado[]=		array(
			"cedula"=>10260224,
			"codigo"=>4325,
			"id_aula"=>9,
			"id_dia"=>5,
			"id_hora_inicio"=>3,
			"id_hora_fin"=>8						
		);
	error_log(print_r($horarioGenerado,true));
	*/
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DisponibilidadProfesoresModel.php');
	$link = DBConnection::connection();
	$objDisponibilidadProfesores = new DisponibilidadProfesoresModel($link);
	$result = $objDisponibilidadProfesores->findProfesoresByDisponibilidadPrioridadMateria(6,45553);
	//error_log(print_r($result,true));
	foreach ($result as $key) {
		echo $key['cedula']."<br>";
	}
	/*
	$objProfesores = new ProfesoresModel($link);
	$result = $objProfesores->getAllHorasACumplir();
	error_log(print_r($result,true));
	print_r($result[13944531]);
	*/
/*
	$variable = array_search(13944531, array_column($result, 'cedula'));
	print_r($variable);
	foreach ($result as $key) {
		if (condition) {
			# code...
		}
		$horasACumplir=key['horas_a_cumplir'];
	}
	$result[$variable]=array("cedula"=>1,
						"horas_a_cumplir"=>3);

	error_log(print_r($result,true));
	//error_log(print_r(array_keys($result),true));
*/
 ?>
</body>
</html>