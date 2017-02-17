<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/AulasModel.php'); 
	require($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/DBConnection.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/HorariosProfesoresModel.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/MateriasModel.php');		
	require_once($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/ProfesoresMateriasModel.php'); 
	require_once($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/ProfesoresModel.php'); 
	require_once($_SERVER['DOCUMENT_ROOT'].'/horario2/Model/SeccionesModel.php'); 

	$link = DBConnection::connection();

if ($_POST['operacion']=="obtener_materias") {
	$materias = new MateriasModel($link);
	$rows = $materias->getMaterias();
	foreach ($rows as $key) {
		echo "<option value=".$key['Codigo'].">".$key['Codigo']." ".$key['Semestre']." ".$key['Asignatura']."</option>";
	}	
}elseif ($_POST['operacion']=="obtener_profesorID"){
	if (isset($_POST['codigoMateria'])){
		$codigoMateria = json_decode($_POST['codigoMateria']);
		$result = new ProfesoresMateriasModel($link);
		$query = $result->getProfesoresMaterias2($codigoMateria);
		if (count($query)>0){
			$profesoresID = json_encode($query);
			echo $profesoresID;
		}else{
			echo "No hay resultados";
		}


	}
}elseif ($_POST['operacion']=="obtener_profesorData") {
	if (isset($_POST['profesoresID'])){
		$profesoresID = json_decode($_POST['profesoresID']);
		$result = new ProfesoresModel($link);
		$query = $result->getProfesor2($profesoresID);
		//error_log(print_r($query,true)); con esto puedo imprimir un array en el log
		usort($query, 'sortByPrioridad');
		if (count($query)>0){
			foreach ($query as $key) {
				echo"<option value=".$key['ID'].">".$key['Nombre']." ".$key['Apellido']." Prioridad:".$key['Prioridad']."</option>";
			}
		}else{
			echo "<option>No se enconraron profesores</option>";
		}

	}
}elseif ($_POST['operacion']=="obtener_seccionID") {
	if (isset($_POST['codigoMateria'])){
		$codigoMateria = json_decode($_POST['codigoMateria']);
		$result = new SeccionesModel($link);
		$query = $result->getSeccion($codigoMateria);
		$secciones = json_encode($query);
		echo $secciones;
	}
}elseif ($_POST['operacion']=="mostrar_seccionID") {
	$array = json_decode($_POST['secciones'],true);
	if (!$array==false){
		foreach ($array as $key) {
			echo"<option value=".$key['ID'].">"."Numero_Seccion:".$key['Numero_Seccion']." Cantidad_Alumnos:".$key['Cantidad_Alumnos']."</option>";
		}
	}else{
		echo"<option>No se encontraron secciones</option>";
	}
}elseif ($_POST['operacion']=="horas_por_asignar") {
	$codigoMateria = json_decode($_POST['codigomateria']);
	$obj= new MateriasModel($link);
	$horasPorSemana = $obj->getHorasSemanales($codigoMateria);

	echo $horasPorSemana;

}elseif ($_POST['operacion']=="obtener_aula") {
	$codigoMateria = json_decode($_POST['codigomateria']);
	$horasSemanaPorAsignar = json_decode($_POST['horassemana']);

	$idSeccion= json_decode($_POST['idseccion']);
	$horaInicio = json_decode($_POST['horainicio']);
	$horaFin = json_decode($_POST['horafin']);
	$diaSeleccionado=json_decode($_POST['diaseleccionado']);

	$obj= new MateriasModel($link);
	//Dime a que tipo(lab o teoria) pertenece la materia que quiero asignar,devuelve 1 o 0
	$tipoMateria = $obj->getTipoMateria($codigoMateria);

	$obj2 = new AulasModel($link);
	//Devuelve un array asociativo con todas las aulas que sean del tipo que especifque
	$aulas =$obj2->getAulas2($tipoMateria);

	$obj3 = new SeccionesModel($link);
	//Dime la capacidad que debe tener el aula a asignar de acuerdo a las secciones, es un entero
	$cantidadAlumnosPorSeccion = $obj3->getCantidadAlumno($idSeccion);

	$obj4 = new HorariosProfesoresModel($link);
	//Obtengo un array asociativo de todos los horarios_prof disponibles
	$horasProfesores = $obj4->getHorariosProfesores();
	//agregar logica que compruebe si esq no hay ninguna horaprofesor
	//agregar logica aparte por si el aula es un lab.
	$aulaAsignada = false;
	error_log("Codigo materia:$codigoMateria,Horas por asignar:$horasSemanaPorAsignar,Idseccion:$idSeccion,Hora inicio:$horaInicio,horaFin:$horaFin,Dia:$diaSeleccionado,Tipomateria:$tipoMateria,Alumnosporseccion:$cantidadAlumnosPorSeccion.", 0);
	//while ($aulaAsignada!=true) {
		foreach ($aulas as $key ) {
			$rows=0;
			if ($key['Capacidad']>=$cantidadAlumnosPorSeccion) {
				if (is_array($horasProfesores)){					
					foreach ($horasProfesores as $key2 ) {
						if ($diaSeleccionado==$key2['Dia'] && $key['ID']==$key2['ID_aula']){
							//error_log("Comparando dia que elegi:$diaSeleccionado con DB:$key2['Dia']",0);
							$rows++;
							$result = aulaOcupada($horaInicio,$horaFin,$key2['Hora_inicio'],$key2['Hora_fin']);
							if ($result) {
								$rows--;			
							}
						}
					}
					if ($rows==0) {//Si ninguno horario coincide con el mio, entonces el aula a esa hora ta libre
						$aulaEscogida = $key['ID'];
						$aulaAsignada=true;
						break;
					}
				}else{//Esto es solo valido si no hubiera registro alguno en horariosprof
					$aulaEscogida = $key['ID'];
					$aulaAsignada=true;
					break;
				}
			}
		}
		if ($aulaAsignada){
			echo $aulaEscogida;
		}else{
			echo "No se encontraron aulas disponibles";
		}
	//}
	//error_log("¡Lo echaste a perder!", 0);


	
}


function aulaOcupada($horaInicio,$horaFin,$horaInicioDB,$horaFinDB){
	$dato1 = $horaFinDB - $horaInicio;
	$dato2 = $horaInicioDB - $horaFin;

	if ( (($dato1>0) && ($dato2<0)) || (($dato1<0) && ($dato2>0)) ) {
		return false; //El aula esta ocupada 
	}
	return true;//El aula no esta ocupada en la iteracion que llamas a esta funcion
}

function sortByPrioridad ($a, $b) {
	$c = $b['Prioridad'] - $a['Prioridad'];
    return $c;

}

?>