<!DOCTYPE html>
<html>
<head>
	<title>algo</title>
</head>
<body>
<?php 
	require($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/DBConnection.php');
	require($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/MateriasModel.php');

	$link = DBConnection::connection();
	$obj1 = new MateriasModel($link);
	$materias = $obj1->getMaterias();

	//$result = $link->query("SELECT * FROM `secciones2016` WHERE `Semestre`= 3 ORDER BY Semestre ASC,Codigo ASC,`Numero_Seccion` ASC");

	$i=1;
	//estos valores son iniciales y deben ir variando
	$limiteInferior = 3;
	$semestre =3;
	$datos = "Los dias de esta materia encontrados son:";
	$hora = "El horario va desde las :";
	//Tomamos todas las materias
	foreach ($materias as $key) {		
		$secciones=array();	
		$result = $link->query("SELECT * FROM secciones WHERE Codigo=".$key['Codigo']." ORDER BY Numero_Seccion");
		$cantidadSecciones = $result->rowCount();
		$limiteSuperior = ($cantidadSecciones + $limiteInferior) - 1;
		//Preguntamos si hay secciones de esa materia abierta y si pertenece al semestre actual
		if ($key['Asignatura']=="Sistemas Eléctronicos I") {
			echo "Cantidad de secciones encontradas:".$cantidadSecciones."<br>";
			$result = $link->query("SELECT * FROM `secciones_view` WHERE `ID`=1");
			$dias=$result->rowCount();
			$rows= $result->fetchAll();
			//aca un condicional por si solo hay un registro xq creo q si hay uno me generara un espacio no deseado
			for ($i=0; $i<$dias ; $i++) { 
				$datos .=$rows[$i]['Dia']."\n";
				$hora .= $rows[$i]['Hora_inicio']." hasta las ".$rows[$i]['Hora_fin']."\n";
			}
			echo "$datos";
			echo "$hora";
		}
	}
?>
</body>
</html>