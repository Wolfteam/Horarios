<?php
//--crear un array para meter cada una de las horasACumplir de cada prof.
for ($prioridad=1; $prioridad<=6 ; $prioridad++) { 
	for ($semestre=3; $semestre<14 ; $semestre++) { 
		//--pido todas las materias del semestre en el q toy parado, sino hay break;
		foreach ($materias as $key ) {
			//--cuantas secciones tiene la materia en la que estoy parado? 
			//sino tiene break
			//--saca un array que contenga los numeros de las secciones de la materia en la q toy parado
			//quizas se pueda ir eliminando del array las secciones asignadas con unset()
			//*--saco el tipoMateria en el que estoy parado
			//*--saco las horasPorSemana de la materia en la q toy parado
			//--cuantos y quienes son los prof. q tienen disponibilidad, tienen la misma prioridad 
			//q la q toy parado y dan la materia en la q estoy parado, 
			//si no hay break;
			for ($i=1; $i <=$numeroSecciones; $i++) { 
				foreach ($profesores as $key2) {
					//--saco las horas que tenga previamente asignadas 
					//debe ser una funcion que permita hacer ese calculo y devuelva horasAsignadas
					//*--horasRestantes =(horasACumplir-horasAsignadas)
					//--horasRestantes >= horasPorSemana de la materia actual?
					//sino continue; para saltar de prof					
					//*--saco un array con los dias que tiene el prof. disponible con alguna funcion 
					//*--saco un array con las aulas que son iguales al tipo de materia que tengo
					//y cuya capacidad sea igual o mayor a la cantidad de alumnos de una seccion
					//*--seccion a asignar sera igual a $i, esto debe ser probado para ver si no da bugs
					//*--saca un array con la disponibilidad del prof. en el q estoy parado
					//--createHorarioProfesor(pasar mierdero de parametros aca);
					//--sino devuelve true la funcion anterior, debo guardar el nombre del profesor
					//cedula, asignatura,seccion que se intento asignar en un arrayErrores
					//lo puedes hacer algo con un metodo push $data= $nombre.""$cedula...
				}
			}

		}
	}
}

function validateChoqueAula($horaInicialSeccion,$horaFinalSeccion,$dia){
	//debe retornar true en caso de validar que no exista choque de aulas en una hora de inicio-fin y dia
	//especifico.
	//Ten en cuenta que no vale solo con ver si existen esos 5 parametros en la DB, se debe
	//hacer unas restas como las que tienes anotadas para saber si las horas
	// llegasen a coincidir con alguno de los registros en la DB.si no existen registros
	// devuelvo true automaticamente. los registros los puedes pedir por cedula y dia
}

function validateChoqueSemestre($codigo,$horaInicialSeccion,$horaFinalSeccion,$dia){
	//debe retornar true en caso de validar que no exista choque de materias en el mismo semestre
	////para la materia que se recibe como parametro en su hora y dia indicados.
	//Ten en cuenta que no vale solo con ver si existen esos 5 parametros en la DB, se debe
	//hacer unas restas como las que tienes anotadas para saber si las horas
	// llegasen a coincidir con alguno de los registros en la DB.si no existen registros
	// devuelvo true automaticamente. los registros los puedes pedir por codigo y dia
}

function validateChoqueHorario($cedula,$horaInicialSeccion,$horaFinalSeccion,$dia,$aula){
	//debe retornar true en caso de validar que no exista el horario que se recibe como parametro
	//para la persona que se recibe como parametro.
	//Ten en cuenta que no vale solo con ver si existen esos 5 parametros en la DB, se debe
	//hacer unas restas como las que tienes anotadas para saber si las horas
	// llegasen a coincidir con alguno de los registros en la DB.si no existen registros
	// devuelvo true automaticamente. los registros los puedes pedir por cedula y dia
}

function getDiasDeDisponibiliadProfesores($arrayData){
	//te debe devolver el array de dias q tiene disponible
}

//quizas no nite el arraydiasdisponibles
function createHorarioProfesor($cedula,$codigo,$arrayDisponibilidad,$arrayDiasDisponibles,$arrayAulas,$horasRestantes,$horasPorSemana,$prioridadProfesor,$numeroSeccion,$tipoMateria){
	$horaInicialSeccion=0;
	$horaFinalSeccion=0;
	//--si dias disponibles >=2 y la materia no es laboratorio 
	//entonces $horas=round($horasPorSemana/2), sino $horasPorSeccion=horasPorSemana 
	//quizas esta parte se deba poner en otra funcion
	//--guarda en una variable el numero de elementos de arrayDisponibilidad
//*--hacer todo esto que viene abajo mientras horasPorSemana!=0
	foreach ($arrayDisponibilidad as $key) {
		//--$horaInicialSeccion= a la de la DB ,
		while ( $horaFinalSeccion<= $horaFinalDB) {
			//no se si a quien debo validar primero
			//--$horaFinalSeccion = horaInicial+$horasPorSeccion
			//--si $horaFinalSeccion >horaFinal de la DB, break xq no la puedes meter en esa zona
			//--validateChoqueHorario(cedula,horaInicialSeccion,horaFinalSeccion,dia,aula);
			//--validateChoqueSemestre(codigo,horaInicialSeccion,horaFinalSeccion,dia)
			//si lo anterior te devuelve false
			//$horaInicialSeccion++;
			//$horaFinalSeccion = horaInicial+$horasPorSeccion
		}

	}
//fin hacer
	//return true o false
}

?>