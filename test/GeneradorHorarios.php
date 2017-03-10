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
					//*--saca un array con la disponibilidad del prof. en el q estoy parado
					//*--saca un array que contenga lo que esta en la tabla dias
					//debe contener algo como 1=>"lunes"
					//*--saco un array con los dias que tiene el prof. disponible con alguna funcion la cual recibe arrayDisponibilidad
					//*--saco un array con las aulas que son iguales al tipo de materia que tengo
					//y cuya capacidad sea igual o mayor a la cantidad de alumnos de una seccion si no encuentro ninguna generara problemas?
					//*--seccion a asignar sera igual a $i, esto debe ser probado para ver si no da bugs
					//--createHorarioProfesor(pasar mierdero de parametros aca);
					//--sino devuelve true la funcion anterior, debo guardar el nombre del profesor
					//cedula, asignatura,seccion que se intento asignar en un arrayErrores
					//lo puedes hacer algo con un metodo push $data= $nombre.""$cedula...
					//--si devuelve true nito actualizar las horasRestantes, 
					//quitandole lashorasPorSemana que cree
				}
			}

		}
	}
}

function validateChoqueAula($horaInicialSeccion,$horaFinalSeccion,$dia,$aula){
	//--pide los registros que coincidan con la aula que quiero asignar y el dia
	//que quiero asignar
	foreach ($arrayRegistros as $key) {
		$horaInicioDB= $key['hora_inicio'];
		$horaFinDB= $key['hora_fin'];
		$result = validateHoras($horaInicioDB,$horaFinDB,$horaInicio,$horaFin);
		//si result = false entonces return false
	}
}

function validateChoqueSemestre($codigo,$semestre,$horaInicio,$horaFin,$dia){
	//--pido el array de materias que pertenecen al semestre y dia dados en la tabla horarios_p
	//--pido el array de materias que tienen codigo y dia igual al parametro pasado de la 
	//tabla horarios_p
	//--si ambbos arrays tan vacios return true;
	//--si el primero esta vacio entonces
	foreach ($arrayMateriasSemestre as $key) {
		$horaInicioDB= $key['hora_inicio'];
		$horaFinDB= $key['hora_fin'];
		$result = validateHoras($horaInicioDB,$horaFinDB,$horaInicio,$horaFin);
		//si result = false entonces return false
	}
	//sino haces esto
	foreach ($arrayMaterias as $key) {
		$horaInicioDB= $key['hora_inicio'];
		$horaFinDB= $key['hora_fin'];
		$result = validateHoras($horaInicioDB,$horaFinDB,$horaInicio,$horaFin);
		//si result = false entonces return false
	}	
	//si llego a este punto return true
}

function validateHoras($horaInicioDB,$horaFinDB,$horaInicio,$horaFin){
	$dato1 = $horaFinDB;
	$dato2 = $horaInicioDB;
	//si dato1 es positivo y dato2 negativo o dato1 es negativo y dato2 positivo
	//return false, sino return true
 }

function validateChoqueHorario($cedula,$horaInicio,$horaFin,$dia){
	//--pregunta si existe algun registro con esos parametros que te dan
	//--pide todos los horarios asociados con la cedula y dia que recibes
	//en los parametros,si no hay return true 
	foreach ($arrayData as $key) {
		$horaInicioDB= $key['hora_inicio'];
		$horaFinDB= $key['hora_fin'];
		$result = validateHoras($horaInicioDB,$horaFinDB,$horaInicio,$horaFin);
		//si result = false entonces return false
	}
	//si llego a este punto return true
}

function getDiasDeDisponibiliadProfesores($arrayDisponibilidad,$arrayDias){	
	$array=[];
	foreach ($arrayDias as $key) {
		foreach ($arrayDisponibilidad as $key2) {
			//el dia en el que estoy parado existe en el array disponibilidad?
			//si existe entonces guarda en la variable array el dia en el que
			//toy parado y luego break
		}
	}
	//return el array;
}

//quizas no nite el arraydiasdisponibles y solo el numero de dias disponibles
function createHorarioProfesor($cedula,$codigo,$semestre,$arrayDisponibilidad,$arrayDiasDisponibles,$arrayAulas,$horasRestantes,$horasPorSemana,$prioridadProfesor,$numeroSeccion,$tipoMateria){
	$horaInicialSeccion=0;
	$horaFinalSeccion=0;
	$aux=$horasPorSemana;
	//guarda el numero de dias disponibles contando los elementos de arrayDiasDispo..
	//--si dias disponibles >=2 y la materia no es laboratorio 
	//entonces $horasPorSeccion=round($horasPorSemana/2), 
	//sino $horasPorSeccion=horasPorSemana 
	//quizas esta parte de arriba se deba poner en otra funcion
	//--guarda en una variable el numero de elementos de arrayDisponibilidad
	foreach ($arrayDiasDisponibles as $key3) {
		//debo pensar una logica para relacionar el arraydiasDisponibles 
		//con el arrayDisponibilidad x.x
		foreach ($arrayDisponibilidad as $key) {
			//--si el dia del arrayDisponilidad != al dia del arrayDiasDisponibles
			//entonces continue,esto debe ser probado, creo que se usara para el caso
			//cuando se tenga varios dias para asignar una seccion
			//--$horaInicialSeccion= a la de la DB 
			//--$horaFinalSeccion = horaInicial+$horasPorSeccion
			while ( $horaFinalSeccion<= $horaFinalDB) {
				foreach ($arraryAulas as $key2) {
					//--validateChoqueAula(horaInicialSeccion,horaFinalSeccion,dia,aula)
					//si lo anterior te devuelve false, continue para checar la sig aula
					//--validateChoqueSemestre(codigo,horaInicialSeccion,horaFinalSeccion,dia)
					//si lo anterior te devuelve false break;
					//--validateChoqueHorario(cedula,horaInicialSeccion,horaFinalSeccion,dia);
					//si lo anterior te devuelve false break;
					//--llegado a este punto guarda en un array  
					//cedula,codigo,aula,horaInicioSeccion,HoraFinSeccion,
					//numeroSeccion,dia en el que toy parado
					//para luego generar la consulta a la DB
					//--aux = aux - horaPorSeccion
					//--horaPorSeccion=aux
					//y break;
				}
				//si aux!=horasPorSemana y aux>0 break porque ya asigne una hora y falta
				//la otra hora de la seccion;
				//si aux=0 es xq ya asigne la seccion completa y break?
				//--llegado a este punto es xq no pude asignar la horas y aulas
				//y debo cambiar el valor de las horas a ver si consigo asignarla
				//$horaInicialSeccion++; 
				//--$horaFinalSeccion = horaInicial+$horasPorSeccion
			}
			//si aux=0 entonces break;xq asigne la materia completa
			//si aux!=horasPorSemana y aux>0 has un break y cambia de dia 
			//nita pensarse bien esta hdp parte
		}
		//si aux=0 entonces break;xq asigne la materia completa
		//si aux!=horasPorSemana y aux>0 continue para cambiar de dia
	}
	//si aux!=0 retorna false
	//sino utiliza alguna funcion que reciba el array de datos a guardar
	//y retorna true;
}

?>