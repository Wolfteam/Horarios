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
			//--saco el tipoMateria en el que estoy parado
			//--saco las horasPorSemana de la materia en la q toy parado
			for ($i=1; $i <=$numeroSecciones; $i++) { 
				//--cuantos y quienes son los prof. q tienen disponibilidad, tienen la misma prioridad 
				//q la q toy parado y dan la materia en la q estoy parado, 
				//si no hay break;
				foreach ($profesores as $key2) {
					//--el prof. en el q toy parado tiene horasACumplir>=horasPorSemana de la materia?
					//sino continue; para saltar de prof
					//--seccion a asignar sera la primera que se encuentre en el arraySecciones,
					//sino hay secciones break; quizas se pueda sustituir por el valor de i?
					//--saco los dias que tiene el prof. disponible con alguna funcion
					
//Todo antes de este punto debe estar bien

					//
					//--saco un array con las aulas que son iguales al tipo de materia que tengo
					//
					//--saco las horas que tenga previamente asignadas 
					//debe ser una funcion que permita hacer ese calculo
					
					//--saca la disponibilidad del prof. en el q estoy parado
					


					//--elimina la seccion asignada del arraySecciones con unset() el valor de i
				}
			}

		}
	}
}

function validateChoqueAula(){
	//debe retornar true en caso de validar que no exista choque de aulas en una hora de inicio-fin y dia
	//especifico.
}

function validateChoqueSemestre(){
	//debe retornar true en caso de validar que no exista choque de materias en el mismo semestre
}

function getDiasDeDisponibiliadProfesores($arrayData){
	//te debe devolver el numero de dias q tiene disponible
}


function createHorarioProfesor($arrayDisponibilidad,$diasDisponibles,$horasPorSemana){

}

?>