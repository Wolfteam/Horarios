<!DOCTYPE html>
<html>
<head>
	<title>+</title>
</head>
<body>
<?php 
	$array=[
		"cedula" => "21255727",
		"Nombre" => "efrain",
	];


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
	while ($k <= 3) {
		echo "entre al while";
		$k++;
	}
 ?>
</body>
</html>