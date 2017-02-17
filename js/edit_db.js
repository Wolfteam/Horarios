$(document).ready(function () {
	$("#add_aulas_button").hide();
	$("#add_materias_button").hide();
	$("#add_profesores_button").hide();
	$("#add_profesores_materias_button").hide();
	$("#selector_db").change(function(){
		$("#selector_db").each(function () {
			opcionSeleccionada = $(this).val();
			switch(opcionSeleccionada) {
			    case '1'://Aulas
					$("#add_aulas_button").show();
					$("#add_materias_button").hide();
					$("#add_profesores_button").hide();
					$("#add_profesores_materias_button").hide();
			        readAulas();
			        break;
			    case '2'://Materias
					$("#add_aulas_button").hide();
					$("#add_materias_button").show();
					$("#add_profesores_button").hide();
					$("#add_profesores_materias_button").hide();
			        readMaterias();
			        break;
			    case '3'://Profesores
					$("#add_aulas_button").hide();
					$("#add_materias_button").hide();
					$("#add_profesores_button").show();
					$("#add_profesores_materias_button").hide();
			        readProfesores();
			        break;
			    case '4'://Profesores_Materias
					$("#add_aulas_button").hide();
					$("#add_materias_button").hide();
					$("#add_profesores_button").hide();
					$("#add_profesores_materias_button").show();
					readProfesoresMaterias();		    	
			        break;
			    case '5':
			        break;           
			    default:
			    	console.log("Estoy en el default, opcionSeleccionada="+opcionSeleccionada);
			        break;
			}
		});
	});
});