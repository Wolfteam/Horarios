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

for ($idPrioridad=1; $idPrioridad<=6; $idPrioridad++) {
	for ($idSemestre=3; $idSemestre<14; $idSemestre++) { 
		$arrayMaterias = $objMaterias->getMateriasBySemestre($idSemestre);
		if (count($arrayMaterias)==0) {
			break;
		}
		foreach ($arrayMaterias as $keyMateria) {
			$codigo = $keyMateria['codigo'];
			$numeroSecciones = $objSecciones->getNumeroSeccionesCreadas($codigo);
			$capacidad = $objSecciones->getCantidadAlumnos($codigo);
			if ($numeroSecciones == 0) {
				break;
			}
			$idTipoMateria = $objMaterias->getTipoMateria($codigo);
			$horasPorSemana = $objMaterias->getHorasSemanales($codigo);
			$arrayProfesores = $objDisponibilidadProfesores->findProfesoresByDisponibilidadPrioridadMateria($idPrioridad,$codigo);
			if (count($arrayProfesores)==0) {
				break;
			}
			for ($i=1; $i<$numeroSecciones; $i++) { 
				foreach ($arrayProfesores as $keysCedula) {
					$cedula = $keyCedula['cedula'];
					$horasACumplir = $arrayHorasACumplir[$cedula];
					$horasAsignadas = getHorasAsignadas($cedula);
					$horasRestantes = $horasACumplir - $horasAsignadas;
					if ($horasRestantes < $horasPorSemana) {
						continue;
					}
					$arrayDisponibilidad = $objDisponibilidadProfesores->getDisponiblidadProfesores($cedula);
					$arrayIdDiasDisponibles = getDiasDisponibiliadProfesor($arrayDisponibilidad);
					$arrayIdAulas = $objAulas->getAulasByTipoCapacidad($idTipoMateria,$capacidad);
					if (count($arrayAulas)==0) {
						break;
					}
					$numeroSeccion = $i;
					$resultA = createHorarioProfesor($cedula,$codigo,$idSemestre,$idTipoMateria,$horasPorSemana,
						$numeroSeccion,$arrayDisponibilidad,$arrayIdDiasDisponibles,$arrayIdAulas);
					if (!$resultA) {
						//debo guardar los datos del prof q no pude asignar
						$resultB = createRandomHorarios($cedula,$codigo,$idSemestre,$idTipoMateria,
							$horasPorSemana,$numeroSeccion,$arrayIdAulas);
						if (!$resultB) {
							//debo guardar los datos del prof q no pude asignar
							break;
						}else{
							$horasRestantes = $horasRestantes - $horasPorSemana;
							$arrayHorasACumplir[cedula] = $horasRestantes;
						}
					}else{
						$horasRestantes = $horasRestantes - $horasPorSemana;
						$arrayHorasACumplir[cedula] = $horasRestantes;
					}
				}
			}
		}
	}	
}

function getDiasDisponibiliadProfesor($arrayDisponibilidad){
	$arrayIdDias=[];
	for ($idDia=1; $idDia < 7; $idDia++) { 
		foreach ($arrayDisponibilidad as $key2) {
			if ($arrayDisponibilidad['id_dia'] == $idDia) {
				$arrayIdDias[]=$idDia;
				break;
			}
		}
	}
	return $arrayIdDias;
}

function getHorasAsignadas($cedula){
	$horasAsignadas=0;
	$arrayHorasAsignadas = $objHorarioProfesores->getHorarioProfesores($cedula);
	foreach ($arrayHorasAsignadas as $key) {
		$aux = $key['id_hora_fin'] - $key['id_hora_inicio'];
		$horasAsignadas += $aux;
	}
	return $horasAsignadas;
}

function setHorasPorSeccion($horasPorSemana,$diasDisponibles,$idTipoMateria){
	if ($diasDisponibles>=2 && $idTipoMateria==1) {
		$horasPorSeccion=round($horasPorSemana/2);
	}else{
		$horasPorSeccion=$horasPorSemana;
	}
	return $horasPorSeccion;
}

function validateHoras($idHoraInicioDB,$idHoraFinDB,$idHoraInicio,$idHoraFin){
	$dato1 = $idHoraFinDB - $idHoraInicio;
	$dato2 = $idHoraInicioDB - $idHoraFin;
	if (($dato1>=0 && $dato2<0) || ($dato1<0 && $dato2>=0)) {
		return false;
	}
	return true;
}

function validateChoqueAula($idHoraInicio,$idHoraFin,$idDia,$idAula){
	$arrayRegistros = $objHorarioProfesores->getHorarioProfesores($idDia,$idAula);
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

function validateChoqueSemestre($codigo,$idSemestre,$idHoraInicio,$idHoraFin,$idDia){
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
}

function validateChoqueHorario($cedula,$idHoraInicio,$idHoraFin,$idDia){
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
	$arrayDisponibilidad,$arrayIdDiasDisponibles,$arrayIdAulas){
	$horarioGenerado =[];
	$idHoraInicio=0;
	$idHoraFin=0;
	$aux=$horasPorSemana;
	$diasDisponibles = count($arrayIdDiasDisponibles);
	$horasPorSeccion = setHorasPorSeccion($horasPorSemana,$diasDisponibles,$idTipoMateria);
	$numeroRegistros = count($arrayDisponibilidad);
	foreach ($arrayDiasDisponibles as $keyDiasDisponibles => $valueDia) {
		//debo pensar una logica para relacionar el arraydiasDisponibles 
		//con el arrayDisponibilidad x.x
		foreach ($arrayDisponibilidad as $keyDisponibilidad) {
			if ($keyDisponibilidad['id_dia'] != $valueDia) {
				continue;
			}
			$idHoraInicio = $keyDisponibilidad['id_hora_inicio'];
			$idHoraFin = $idHoraInicio + $horasPorSeccion;
			while ($idHoraFin <= $keyDisponibilidad['id_hora_fin']) {
				foreach ($arrayIdAulas as $keyAula) {
					$result = validateChoqueAula($idHoraInicio,$idHoraFin,$valueDia,$keyAula['id_aula']);
					if (!$result) {
						continue;
					}
					$result= validateChoqueSemestre($codigo,$idSemestre,$idHoraInicio,$idHoraFin,$valueDia);
					if (!$result) {
						break;
					}
					$result = validateChoqueHorario($cedula,$idHoraInicio,$idHoraFin,$valueDia);
					if (!$result) {
						break;
					}
					$horarioGenerado[] = array(
											"cedula"=>$cedula,
											"codigo"=>$codigo,
											"id_aula"=>$keyAula['id_aula'],
											"id_dia"=>$valueDia,
											"id_hora_inicio"=>$idHoraInicio,
											"id_hora_fin"=>$idHoraFin,
											"numero_seccion"=>$numeroSeccion						
										);
					$aux = $aux - $horasPorSeccion;
					$horasPorSeccion = $aux;
					break;
				}
				if ( ($aux != $horasPorSemana && $aux>0) || ($aux==0) ) {
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
		$objHorarioProfesores->createHorarioProfesores($key['cedula'],$key['codigo'],$key['id_aula'],
				$key['id_dia'],$key['id_hora_inicio'],$key['id_hora_fin'],$key['numero_seccion']);
	}
	return true;	
}

function createRandomHorarioProfesor($cedula,$codigo,$idSemestre,$idTipoMateria,$horasPorSemana,
	$numeroSeccion,$arrayIdAulas){
	$horarioGenerado =[];
	$idHoraInicio=0;
	$idHoraFin=0;
	$aux=$horasPorSemana;
	$horasPorSeccion = setHorasPorSeccion($horasPorSemana,2,$idTipoMateria);
	for ($idDia=1; $idDia<7; $idDia++) {
		$idHoraInicio=1;
		$idHoraFin= $idHoraInicio + $horasPorSeccion;
		while (idHoraFin <= 14) {
			foreach ($arrayIdAulas as $keyAula) {
				$result = validateChoqueAula($idHoraInicio,$idHoraFin,$idDia,$keyAula['id_aula']);
				if (!$result) {
					continue;
				}
				$result= validateChoqueSemestre($codigo,$idSemestre,$idHoraInicio,$idHoraFin,$idDia);
				if (!$result) {
					break;
				}
				$result = validateChoqueHorario($cedula,$idHoraInicio,$idHoraFin,$idDia);
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
				break;
			}
			if ( ($aux != $horasPorSemana && $aux>0) || ($aux==0) ) {
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
		$objHorarioProfesores->createHorarioProfesores($key['cedula'],$key['codigo'],$key['id_aula'],
				$key['id_dia'],$key['id_hora_inicio'],$key['id_hora_fin'],$key['numero_seccion']);
	}
	return true;
}

?>