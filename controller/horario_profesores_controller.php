<?php
require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/AulasModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DisponibilidadProfesoresModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/HorarioProfesoresModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/MateriasModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/SeccionesModel.php');


//agregar una condicion para acceder a todo esto, la cual se pasa x post
$link = DBConnection::connection();
$objAulas = new AulasModel($link);
$objDisponibilidadProfesores = new DisponibilidadProfesoresModel($link);
$objHorarioProfesores = new HorarioProfesoresModel($link);
$objMaterias = new MateriasModel($link);
$objProfesores = new ProfesoresModel($link);
$objSecciones = new SeccionesModel($link);

//debo ver como modifico lo que contiene el array
$arrayHorasACumplir = $objProfesores->getAllHorasACumplir();
$arraySecciones = $objSecciones->getAllNumeroSeccionesCreadas();
//quizas esto de aca no sea necesario
$objHorarioProfesores->deleteAllHorarioProfesores();

//todo:
//		Si nando q es MT se asigna primero a transmision cuando se asigne a margarita q es DE
//		repetira las mismas secciones
//		Revisar detenidamente la creacion de horarios tanto nomral como random
//		closeCursor a todo
//		Documentar todas las funciones
for ($idPrioridad=1; $idPrioridad<=6; $idPrioridad++) {
	for ($idSemestre=3; $idSemestre<=14; $idSemestre++) { 
		$arrayMaterias = $objMaterias->getMateriasBySemestre($idSemestre);
		if (count($arrayMaterias)==0) {
			break;
		}
		foreach ($arrayMaterias as $keyMateria) {
			$codigo = $keyMateria['codigo'];
			$numeroSecciones = $objSecciones->getNumeroSeccionesCreadas($codigo);
			$capacidad = $objSecciones->getCantidadAlumnos($codigo);
			if ($numeroSecciones == 0) {
				continue;
			}
			$idTipoMateria = $objMaterias->getTipoMateria($codigo);
			$horasPorSemana = $objMaterias->getHorasSemanales($codigo);
			$arrayProfesores = $objDisponibilidadProfesores->findProfesoresByDisponibilidadPrioridadMateria($idPrioridad,$codigo);
			if (count($arrayProfesores)==0) {
				break;
			}
			$numeroSeccion=1;
			for ($i=$numeroSecciones; $i>0; $i--) {
				foreach ($arrayProfesores as $keyCedula) {	
					if ($numeroSecciones==0) {
						$i=0;
						break;
					}
					$cedula = $keyCedula['cedula'];
					$horasACumplir = $arrayHorasACumplir[$cedula];
					$horasAsignadas = getHorasAsignadas($cedula,$objHorarioProfesores);
					$horasRestantes = $horasACumplir - $horasAsignadas;
					error_log("cedula:".$cedula.",horasACumplir:".$horasACumplir.",horasAsignadas:".$horasAsignadas.",horasRestantes:".$horasRestantes);
					if ($horasRestantes < $horasPorSemana) {
						continue;
					}
					$arrayDisponibilidad = $objDisponibilidadProfesores->getDisponiblidadProfesores($cedula);
					$arrayIdDiasDisponibles = getDiasDisponibiliadProfesor($arrayDisponibilidad);
					$arrayIdAulas = $objAulas->getAulasByTipoCapacidad($idTipoMateria,$capacidad);
					if (count($arrayIdAulas)==0) {
						break;
					}
					$resultA = createHorarioProfesor($cedula,$codigo,$idSemestre,$idTipoMateria,$horasPorSemana,$numeroSeccion,$arrayDisponibilidad,$arrayIdDiasDisponibles,
						$arrayIdAulas,$objHorarioProfesores);
					if (!$resultA) {
						//debo guardar los datos del prof q no pude asignar
						$resultB = createRandomHorarioProfesor($cedula,$codigo,$idSemestre,
							$idTipoMateria,$horasPorSemana,$numeroSeccion,$arrayIdAulas,
							$objHorarioProfesores);
						if (!$resultB) {
							//debo guardar los datos del prof q no pude asignar
							break;
						}else{
							$numeroSecciones--;
							$numeroSeccion++;
							error_log("Asignado de forma random.cedula:".$cedula.",horasACumplir:".$arrayHorasACumplir[$cedula].",horasAsignadas:".$horasAsignadas.",horasRestantes:".$horasRestantes);
						}
					}else{
						$numeroSecciones--;
						$numeroSeccion++;
						error_log("Asignado de forma normal.cedula:".$cedula.",horasACumplir:".$arrayHorasACumplir[$cedula].",horasAsignadas:".$horasAsignadas.",horasRestantes:".$horasRestantes);
					}
				}
			}
		}
	}	
}

/**
 * Esta funcion genera un array con los ids de los dias que tiene disponible.
 * @param  [Array] $arrayDisponibilidad [Array de disponibilidad del profesor]
 * @return [Array] $arrayIdDias      	[Array con los ids de los dias disponibles]
 */
function getDiasDisponibiliadProfesor($arrayDisponibilidad){
	$arrayIdDias=[];
	for ($idDia=1; $idDia < 7; $idDia++) { 
		foreach ($arrayDisponibilidad as $key2) {
			if ($key2['id_dia'] == $idDia) {
				$arrayIdDias[]=$idDia;
				break;
			}
		}
	}
	return $arrayIdDias;
}

/**
 * Esta funcion calcula las horas ya asignadas que tiene un profesor.
 * @param  [Integer] $cedula               	[Cedula del profesor]
 * @param  [Object] $objHorarioProfesores 	[Objeto para acceder a la clase]
 * @return [Integer] $horasAsignadas      	[Horas asignadas del profesor]
 */
function getHorasAsignadas($cedula,$objHorarioProfesores){
	$horasAsignadas=0;
	$arrayHorasAsignadas = $objHorarioProfesores->getHorarioProfesores($cedula);
	foreach ($arrayHorasAsignadas as $key) {
		$aux = $key['id_hora_fin'] - $key['id_hora_inicio'];
		$horasAsignadas += $aux;
	}
	return $horasAsignadas;
}

/**
 * Esta funcion calcula cuantas horas debe tener la seccion de una materia.
 * @param  [Integer] $horasPorSemana  [Horas por semana de una materia]
 * @param  [Integer] $diasDisponibles [Numero de dias disponibles del profesor]
 * @param  [Integer] $idTipoMateria   [Id del tipo de materia]
 * @return [Integer] $horasPorSeccion [Numero de horas por seccion de una materia]
 */
function setHorasPorSeccion($horasPorSemana,$diasDisponibles,$idTipoMateria){
	if ($diasDisponibles>=2 && $idTipoMateria==1) {
		$horasPorSeccion=round($horasPorSemana/2);
	}else{
		$horasPorSeccion=$horasPorSemana;
	}
	return $horasPorSeccion;
}

/**
 * Esta funcion valida las horas que recibe como parametros.
 * Una hora no es valida cuando alguno de los datos da negativo y la otra da positiva.
 * Si alguna da cero, es valida.
 * Si ambas dan negativa es valida.
 * @param  [Integer] $idHoraInicioDB [Id de la hora de inicio almacenada en la DB]
 * @param  [Integer] $idHoraFinDB    [Id de la hora de fin almacenada en la DB]
 * @param  [Integer] $idHoraInicio   [Id de la hora de inicio]
 * @param  [Integer] $idHoraFin      [Id de la hora de fin]
 * @return [Boolean]                 [True si las horas dadas validas.]
 */
function validateHoras($idHoraInicioDB,$idHoraFinDB,$idHoraInicio,$idHoraFin){
	$dato1 = $idHoraFinDB - $idHoraInicio;
	$dato2 = $idHoraInicioDB - $idHoraFin;
	if (($dato1>=0 && $dato2<0) || ($dato1<0 && $dato2>=0)) {
		return false;
	}
	return true;
}

/**
 * Esta funcion valida de que no este ocupada una cierta aula 
 * a una cierta hora, en un cierto dia.
 * @param  [Integer] $idHoraInicio         	[Id de la hora de inicio]
 * @param  [Integer] $idHoraFin            	[Id de la hora de fin]
 * @param  [Integer] $idDia                	[Id del dia]
 * @param  [Integer] $idAula               	[Id del aula]
 * @param  [Object] $objHorarioProfesores 	[Objeto para acceder a la clase]
 * @return [Boolean]                       	[True en caso de no estar el aula ocupada]
 */
function validateChoqueAula($idHoraInicio,$idHoraFin,$idDia,$idAula,$objHorarioProfesores){
	$arrayRegistros = $objHorarioProfesores->getHorarioProfesoresByDiaAula($idDia,$idAula);
	foreach ($arrayRegistros as $key) {
		$idHoraInicioDB = $key['id_hora_inicio'];
		$idHoraFinDB = $key['id_hora_fin'];
		$result = validateHoras($idHoraInicioDB,$idHoraFinDB,$idHoraInicio,$idHoraFin);
		if (!$result) {
			return false;
		}
	}
	return true;
}

/**
 * Esta funcion valida que no existan materias del mismo semestre en el mismo dia 
 * y a la misma hora que coincidan con las que se pasan en los parametros.
 * @param  [Integer] $codigo               	[Codigo de la materia]
 * @param  [Integer] $idSemestre           	[Id del semestre]
 * @param  [Integer] $idHoraInicio         	[Id de la hora de inicio]
 * @param  [Integer] $idHoraFin            	[Id de la hora de fin]
 * @param  [Integer] $idDia                	[Id del dia]
 * @param  [Object] $objHorarioProfesores 	[Objeto para acceder a la clase]
 * @return [Boolean]                       	[True en caso de no existir choque de semestre]
 */
function validateChoqueSemestre($codigo,$idSemestre,$idHoraInicio,$idHoraFin,$idDia,$objHorarioProfesores){
	$arrayRegistros = $objHorarioProfesores->getHorarioProfesoresBySemestreDia($idSemestre,$idDia);
	if (count($arrayRegistros)==0) {
		return true;
	}else{
		foreach ($arrayRegistros as $key) {
			$idHoraInicioDB= $key['id_hora_inicio'];
			$idHoraFinDB= $key['id_hora_fin'];
			$result = validateHoras($idHoraInicioDB,$idHoraFinDB,$idHoraInicio,$idHoraFin);
			if (!$result) {
				return false;
			}
		}
	}
	return true;
	//Tambien ve si ya existe la materia en el mismo dia y a la misma hora, esto no se si deba ser un choque
	//puesto que normalmente suelen haber 2 secciones a la misma hora pero en diferentes aulas
	/*
	$arrayRegistrosA = $objHorarioProfesores->getHorarioProfesoresBySemestreDia($idSemestre,$idDia);
	$arrayRegistrosB = $objHorarioProfesores->getHorarioProfesoresByCodigoDia($codigo,$idDia);
	if ( (count($arrayRegistrosA)==0) && (count($arrayRegistrosB)==0) ) {
		return true;
	}else if (count($arrayRegistrosA)==0) {
		foreach ($arrayRegistrosA as $key) {
			$idHoraInicioDB= $key['id_hora_inicio'];
			$idHoraFinDB= $key['id_hora_fin'];
			$result = validateHoras($idHoraInicioDB,$idHoraFinDB,$idHoraInicio,$idHoraFin);
			if (!$result) {
				return false;
			}
		}
	}else{
		foreach ($arrayRegistrosB as $key) {
			$idHoraInicioDB= $key['id_hora_inicio'];
			$idHoraFinDB= $key['id_hora_fin'];
			$result = validateHoras($idHoraInicioDB,$idHoraFinDB,$idHoraInicio,$idHoraFin);
			if (!$result) {
				return false;
			}
		}
	}
	return true;
	*/
}

/**
 * Esta funcion valida que el mismo profesor no puede dar varias clases al mismo tiempo
 * @param  [Integer] $cedula               	[Cedula del profesor]
 * @param  [Integer] $idHoraInicio         	[Id de la hora de inicio]
 * @param  [Integer] $idHoraFin            	[Id de la hora de fin]
 * @param  [Integer] $idDia                	[Id del dia]
 * @param  [Object] $objHorarioProfesores 	[Objeto para acceder a la clase]
 * @return [Boolean]                       	[True en caso de no existir choques]
 */
function validateChoqueHorario($cedula,$idHoraInicio,$idHoraFin,$idDia,$objHorarioProfesores){
	$result = $objHorarioProfesores->findHorarioProfesor($cedula,$idHoraInicio,$idHoraFin,$idDia);
	if ($result) {
		return false;
	}
	$arrayData = $objHorarioProfesores->getHorarioProfesoresByCedulaDia($cedula,$idDia);
	if (count($arrayData) == 0) {
		return true;
	}
	foreach ($arrayData as $key) {
		$idHoraInicioDB = $key['id_hora_inicio'];
		$idHoraFinDB = $key['id_hora_fin'];
		$result = validateHoras($idHoraInicioDB,$idHoraFinDB,$idHoraInicio,$idHoraFin);
		if (!$result) {
			return false;
		}
	}
	return true;
}

function createHorarioProfesor($cedula,$codigo,$idSemestre,$idTipoMateria,$horasPorSemana,$numeroSeccion,
	$arrayDisponibilidad,$arrayIdDiasDisponibles,$arrayIdAulas,$objHorarioProfesores){
	$horarioGenerado =[];
	$idHoraInicio=0;
	$idHoraFin=0;
	$aux=$horasPorSemana;
	$diasDisponibles = count($arrayIdDiasDisponibles);
	$horasPorSeccion = setHorasPorSeccion($horasPorSemana,$diasDisponibles,$idTipoMateria);
	$numeroRegistros = count($arrayDisponibilidad);
	$horaAsignadaPorDia = false;
	foreach ($arrayIdDiasDisponibles as $keyDiasDisponibles => $valueDia) {
		//debo pensar una logica para relacionar el arraydiasDisponibles 
		//con el arrayDisponibilidad x.x
		foreach ($arrayDisponibilidad as $keyDisponibilidad) {
			if ($keyDisponibilidad['id_dia'] != $valueDia) {
				continue;
			}
			$idHoraInicio = $keyDisponibilidad['id_hora_inicio'];
			$idHoraFin = $idHoraInicio + $horasPorSeccion;
			while ($idHoraFin <= $keyDisponibilidad['id_hora_fin']) {
				//error_log("horainicio:".$idHoraInicio.",horafin:".$idHoraFin."dia:".$valueDia);
				foreach ($arrayIdAulas as $keyAula) {
					$result = validateChoqueAula($idHoraInicio,$idHoraFin,$valueDia,$keyAula['id_aula'],$objHorarioProfesores);				
					if (!$result) {
						continue;
					}
					error_log("Cedula:".$cedula.",paso validateChoqueAula en aula:".$keyAula['id_aula'].",horafin:".$idHoraFin.",en el dia:".$valueDia);
					$result= validateChoqueSemestre($codigo,$idSemestre,$idHoraInicio,$idHoraFin,$valueDia,$objHorarioProfesores);
					if (!$result) {
						break;
					}
					error_log("Cedula:".$cedula.",paso validateChoqueSemestre en aula:".$keyAula['id_aula'].",horafin:".$idHoraFin.",en el dia:".$valueDia);
					$result = validateChoqueHorario($cedula,$idHoraInicio,$idHoraFin,$valueDia,$objHorarioProfesores);
					if (!$result) {
						break;
					}
					error_log("Cedula:".$cedula.",paso validateChoqueHorario en aula:".$keyAula['id_aula'].",horafin:".$idHoraFin.",en el dia:".$valueDia);
					$horarioGenerado[] = array(
											"cedula"=>$cedula,
											"codigo"=>$codigo,
											"id_aula"=>$keyAula['id_aula'],
											"id_dia"=>$valueDia,
											"id_hora_inicio"=>$idHoraInicio,
											"id_hora_fin"=>$idHoraFin,
											"numero_seccion"=>$numeroSeccion						
										);
					error_log("horasPorSeccion:".$horasPorSeccion.",El valor de aux es:".$aux);
					$aux = $aux - $horasPorSeccion;
					$horasPorSeccion = $aux;
					$horaAsignadaPorDia = true;
					error_log("horasPorSeccion:".$horasPorSeccion.",El valor de aux es:".$aux);
					break;
				}
				if ( ($horaAsignadaPorDia) || ($aux==0) ) {
					$horaAsignadaPorDia = false;
					break;
				}
				$idHoraInicio++;
				$idHoraFin = $idHoraInicio + $horasPorSeccion;
			}
			if ( ($aux != $horasPorSemana && $aux>0) || ($aux==0) ) {
				break;
			}
		}
		//if ($aux != $horasPorSemana && $aux>0) {
		//	continue;
		//}
		if ($aux==0) {
			break;
		}
	}
	if ($aux!=0) {
		return false;
	}
	foreach ($horarioGenerado as $key) {
		$objHorarioProfesores->createHorarioProfesores($key['cedula'],$key['codigo'],$key['id_dia'],
			$key['id_hora_inicio'],$key['id_hora_fin'],$key['id_aula'],$key['numero_seccion']);
	}
	return true;	
}

function createRandomHorarioProfesor($cedula,$codigo,$idSemestre,$idTipoMateria,$horasPorSemana,
	$numeroSeccion,$arrayIdAulas,$objHorarioProfesores){
	$horarioGenerado =[];
	$idHoraInicio=0;
	$idHoraFin=0;
	$aux=$horasPorSemana;
	$horasPorSeccion = setHorasPorSeccion($horasPorSemana,2,$idTipoMateria);
	$horaAsignadaPorDia = false;
	for ($idDia=1; $idDia<7; $idDia++) {
		$idHoraInicio=1;
		$idHoraFin= $idHoraInicio + $horasPorSeccion;
		while ($idHoraFin <= 14) {
			foreach ($arrayIdAulas as $keyAula) {
				$result = validateChoqueAula($idHoraInicio,$idHoraFin,$idDia,$keyAula['id_aula'],$objHorarioProfesores);
				if (!$result) {
					continue;
				}
				$result= validateChoqueSemestre($codigo,$idSemestre,$idHoraInicio,$idHoraFin,$idDia,$objHorarioProfesores);
				if (!$result) {
					break;
				}
				$result = validateChoqueHorario($cedula,$idHoraInicio,$idHoraFin,$idDia,$objHorarioProfesores);
				if (!$result) {
					break;
				}
				$horarioGenerado[] = array(
										"cedula"=>$cedula,
										"codigo"=>$codigo,
										"id_aula"=>$keyAula['id_aula'],
										"id_dia"=>$idDia,
										"id_hora_inicio"=>$idHoraInicio,
										"id_hora_fin"=>$idHoraFin,
										"numero_seccion"=>$numeroSeccion						
									);
				$aux = $aux - $horasPorSeccion;
				$horasPorSeccion = $aux;
				$horaAsignadaPorDia = true;
				break;
			}
			if ( ($horaAsignadaPorDia) || ($aux==0) ) {
				$horaAsignadaPorDia = false;
				break;
			}
			$idHoraInicio++;
			$idHoraFin = $idHoraInicio + $horasPorSeccion;
		}
		if ($aux==0) {
			break;
		}
	}
	if ($aux!=0) {
		return false;
	}
	foreach ($horarioGenerado as $key) {
		$objHorarioProfesores->createHorarioProfesores($key['cedula'],$key['codigo'],$key['id_dia'],
			$key['id_hora_inicio'],$key['id_hora_fin'],$key['id_aula'],$key['numero_seccion']);
	}
	return true;
}

?>