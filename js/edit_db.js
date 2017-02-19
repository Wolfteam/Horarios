//La funcion validarCampos es una mierda, debe ser arreglada

var selectorDB; 
var operacion;
var validate;
var ruta = "../controller/edit_db_controller.php";

var idAula;
var nombreAula;
var capacidad;
var idTipo;

var codigo;
var asignatura;
var semestre;
var horasAcademicasTotales;
var horasSemanales;
var idCarrera;

var cedula;
var nombreProfesor;
var apellidoProfesor;
var idPrioridad;

$(document).ready(function () {
	$("#add_aulas_button").hide();
	$("#add_materias_button").hide();
	$("#add_profesores_button").hide();
	$("#add_profesores_materias_button").hide();
	$("#selector_db").change(function(){
		$("#selector_db").each(function () {
			//opcionSeleccionada = $(this).val();
			switch(selectorDB) {
			    case '1'://Aulas
					$("#add_aulas_button").show();
					$("#add_materias_button").hide();
					$("#add_profesores_button").hide();
					$("#add_profesores_materias_button").hide();
			        read(selectorDB);
			        break;
			    case '2'://Materias
					$("#add_aulas_button").hide();
					$("#add_materias_button").show();
					$("#add_profesores_button").hide();
					$("#add_profesores_materias_button").hide();
			        read(selectorDB);
			        break;
			    case '3'://Profesores
					$("#add_aulas_button").hide();
					$("#add_materias_button").hide();
					$("#add_profesores_button").show();
					$("#add_profesores_materias_button").hide();
			        read(selectorDB);
			        break;
			    case '4'://Profesores_Materias
					$("#add_aulas_button").hide();
					$("#add_materias_button").hide();
					$("#add_profesores_button").hide();
					$("#add_profesores_materias_button").show();
					read(selectorDB);		    	
			        break;
			    case '5':
			        break;           
			    default:
			    	console.log("Estoy en el default, opcionSeleccionada="+selectorDB);
			        break;
			}
		});
	});
});


function create() {
	operacion = "create";
	switch(selectorDB){
		case "1"://Crear aulas
			nombreAula = $("#nombre_aula").val().trim();
			capacidad = $("#capacidad").val().trim();
			idTipo = $("#selector_id_tipo_aula option:selected").val();
			validate = validarCampos(selectorDB,nombreAula,capacidad,idTipo);
			if (!validate){
				alert("Todos los campos son obligatorios");
			}else{
				$.post(ruta, {
		            nombre_aula: nombreAula,
		            capacidad: capacidad,
		           	id_tipo: idTipo,
		           	selector_db : selectorDB,
		            operacion: operacion
		        },function (data, status) {
		            $("#add_new_aulas_modal").modal("hide");
		            read();
		            $("#nombre_aula").val("");
		            $("#capacidad").val("");
		            $('#selector_id_tipo_aula > option[value="1"]').attr('selected', 'selected');
		        });
			}
			break;
		case "2"://Crear materias
			codigo = $("#codigo").val().trim();
		    asignatura = $("#nombre_asignatura").val().trim();
		    semestre = $("#selector_semestre_materia option:selected").val();
		    horasAcademicasTotales = $("#horas_academicas_totales").val().trim();
		    horasAcademicasSemanales = $("#horas_academicas_semanales").val().trim();
		    idTipo = $("#selector_id_tipo_materia option:selected").val();
		    idCarrera = $("#selector_carrera_materia option:selected").val();
		    validate = validarCampos(selectorDB,codigo,asignatura,semestre,horasAcademicasTotales,
		    			horasAcademicasSemanales,idTipo,idCarrera);
		    if (!validate) {
		    	alert("Todos los campos son obligatorios");
		    }else{
				$.post(ruta, {
		            codigo: codigo,
		            asignatura: asignatura,
		           	semestre: semestre,
		           	horas_academicas_totales:horasAcademicasTotales,
		           	horas_academicas_semanales:horasAcademicasSemanales,
		           	id_tipo:idTipo,
		           	id_carrera:idCarrera,
		           	selector_db : selectorDB,
		            operacion: operacion
		        },function (data, status) {
		            $("#add_new_materias_modal").modal("hide");
		            read();
		            $("#codigo").val("");
		            $("#nombre_asignatura").val("");
		            $('#selector_semestre_materia > option[value="3"]').attr('selected', 'selected');
		            $("#horas_academicas_totales").val("");
		            $("#horas_academicas_semanales").val("");
		            $('#selector_id_tipo_materia > option[value="1"]').attr('selected', 'selected');
		            $('#selector_carrera_materia > option[value="1"]').attr('selected', 'selected');
		        });
		    }
			break;
		case "3"://Crear profesor
			cedula = $("#cedula").val().trim();
		    nombreProfesor = $("#nombre_profesor").val().trim();
		    apellidoProfesor = $("#apellido_profesor").val().trim();
			idPrioridad = $("#selector_prioridad_profesor option:selected").val();
			validate = validarCampos(selectorDB,cedula,nombreProfesor,apellidoProfesor,idPrioridad);
			if (!validate) {
				alert("Todos los campos son obligatorios");
			}else{
				$.post(ruta, {
					cedula:cedula,
		            nombre_profesor: nombreProfesor,
		            apellido_profesor:apellidoProfesor,
		           	id_prioridad: idPrioridad,
		           	selector_db : selectorDB,
		            operacion: operacion
		        },function (data, status) {
		            $("#add_new_profesores_modal").modal("hide");
		            read();
		            $("#cedula").val("");
		            $("#nombre_profesor").val("");
		            $("#apellido_profesor").val("");
					$('#selector_prioridad_profesor > option[value="1"]').attr('selected','selected');
		        });
			}
			break;
		case "4"://Crear profesores_materias
			cedula = document.getElementById("selector_profesores").value;
			codigo = document.getElementById("selector_materias").value;
			if (cedula!=0 && codigo!=0) {
				$.post(ruta, {
		            cedula: cedula,
		            codigo:codigo,
		            selector_db : selectorDB,
		            operacion: operacion
		        },function (data, status) {
		            $("#add_new_profesores_materias_modal").modal("hide");
		            read();
		            $('#selector_materias > option[value="0"]').attr('selected','selected');
		            $('#selector_profesores > option[value="0"]').attr('selected','selected');
		        });				
			}
			break;
	}
}

//Esta funcion lee de la DB de acuerdo a la opcion seleccionada en el selector
function read() {
    operacion="read";
    //Estaria genial si pudiera hacer que el selectorDB 4 funcione sin esto
    if (selectorDB==4){
    	var mostrar = "mostrar_materias";
	 	$.post(ruta, {
	    	selector_db:selectorDB,
	    	operacion:operacion,
	    	mostrar:mostrar
	    },function (data, status) {
	        $("#selector_materias").html(data);
	        $("#selector_update_materias").html(data);
	    });
	    console.log(mostrar);
	 	mostrar = "mostrar_profesores";
	 	$.post(ruta, {
	    	selector_db:selectorDB,
	    	operacion:operacion,
	    	mostrar:mostrar
	    },function (data, status) {
	        $("#selector_profesores").html(data);
	        $("#selector_update_profesores").html(data);
	    });
	    console.log(mostrar);
    }
    //Con este trozo se leen todas las demas
    $.post(ruta, {
    	selector_db:selectorDB,
    	operacion:operacion
    },function (data, status) {
        $(".records_content").html(data);
    });
}


function update(){
	operacion = "update";
	switch(selectorDB){
		case '1'://Update aulas
			nombreAula = $("#update_nombre_aula").val().trim();
		    capacidad = $("#update_capacidad").val().trim();
		    idTipo = $("#selector_update_id_tipo_aula option:selected").val();
		    validate = validarCampos(selectorDB,nombreAula,capacidad,idTipo);
		    if (!validate) {
		    	alert("Todos los campos son obligatorios");
		    }else {
		        idAula = $("#hidden_aula_id").val();
		        $.post(ruta, {
	                id_aula: idAula,
	                nombre_aula: nombreAula,
	                capacidad: capacidad,
	                id_tipo: idTipo,
	                selector_db:selectorDB,
	                operacion: operacion
	        	},function (data, status) {
	                $("#update_aulas_modal").modal("hide");
	                read();
	            });
		    }
			break;
		case '2'://Update materias
			var codigoNuevo = $("#update_codigo").val().trim();
			asignatura = $("#update_nombre_asignatura").val().trim();
		    semestre = $("#selector_update_semestre_materia option:selected").val();
		    horasAcademicasTotales = $("#update_horas_academicas_totales").val().trim(); 
		    horasAcademicasSemanales = $("#update_horas_academicas_semanales").val().trim();
		    idTipo = $("#selector_update_id_tipo_materia option:selected").val();
		    idCarrera = $("#selector_update_carrera_materia option:selected").val();
		    validate = validarCampos(selectorDB,asignatura,semestre,horasAcademicasTotales,
		    			horasAcademicasSemanales,idTipo,idCarrera);
		    if (!validate) {
		    	alert("Todos los campos son obligatorios");
		    }else{
		    	codigo = $("#hidden_materia_codigo").val();
			    $.post(ruta, {
	                codigo: codigo,
	                codigo_nuevo:codigoNuevo,
	                asignatura: asignatura,
	                semestre: semestre,
	                horas_academicas_totales: horasAcademicasTotales,
	                horas_academicas_semanales: horasAcademicasSemanales,
	                id_tipo: idTipo,
	                id_carrera:idCarrera,
	                selector_db:selectorDB,
	                operacion: operacion
	            },function (data, status) {
	                $("#update_materias_modal").modal("hide");
	                read();
	           	});
		    }
			break;
		case '3'://Update profesores
			var cedulaNueva = $("#update_cedula").val().trim();
			nombreProfesor = $("#update_nombre_profesor").val().trim();
			apellidoProfesor = $("#update_apellido_profesor").val().trim();
			idPrioridad = $("#selector_update_prioridad_profesor option:selected").val();
			cedula = $("#hidden_profesor_cedula").val();
			validate = validarCampos(cedula,nombreProfesor,apellidoProfesor,idPrioridad);
			if (!validate) {
				alert("Todos los campos son obligatorios");
			}else{
				$.post(ruta, {
	                cedula: cedula,
	                cedula_nueva:cedulaNueva,
	                nombre_profesor: nombreProfesor,
	                apellido_profesor:apellidoProfesor,
	                id_prioridad: idPrioridad,
	                selector_db:selectorDB,
	                operacion: operacion
	            },function (data, status) {
	                $("#update_profesores_modal").modal("hide");
	                read();
	            });
			}
			break;
		case '4'://Update profesor_materias
		    cedulaNueva = document.getElementById("selector_update_profesores").value;
    		codigoNuevo = document.getElementById("selector_update_materias").value;
	    	cedula = $("#hidden_profesor_materia_cedula").val();
	        codigo = $("#hidden_profesor_materia_codigo").val();
	        $.post(ruta, {
                cedula_nueva: cedulaNueva,
                codigo_nuevo: codigoNuevo,
                cedula: cedula,
                codigo: codigo,
                selector_db:selectorDB, 
                operacion: operacion
	        },function (data, status) {
                $("#update_profesores_materias_modal").modal("hide");
                read();
	        });
			break;			
	}
}

//Este metodo debe volverse a pensar, para hacerlo mas simple
function deleteStuff (dataA = false, dataB = false){
	operacion="delete";
	var conf;
	switch(selectorDB){
		case '1':
		    conf = confirm("¿Estas seguro que deseas borrar el aula?");
		    if (conf == true) {
		        $.post(ruta, {
	                id_aula: dataA,
	                selector_db:selectorDB,
	                operacion: operacion
		        },function (data, status) {
		            read();
		        });
		    }
	    	break;
	    case '2':
		    conf = confirm("¿Estas seguro que deseas borrar la materia?");
		    if (conf == true) {
		        $.post(ruta, {
		            codigo: dataA,
	                selector_db:selectorDB,
	                operacion: operacion
		        },function (data, status) {
		            read();
		        });
		    }
	    	break;
	    case '3':
			conf = confirm("¿Estas seguro que deseas borrar al profesor?");
		    if (conf == true) {
		        $.post(ruta, {
	                cedula: dataA,
	                selector_db:selectorDB,
	                operacion: operacion
	            },function (data, status) {
	                read();
	            });
		    }
		    break;
		case '4':
			conf = confirm("¿Estas seguro que deseas borrar la asignacion de este profesor a esta materia?");
		    if (conf == true) {
		        $.post(ruta, {
	                cedula: dataA,
	                codigo:dataB,
	                selector_db:selectorDB,
	                operacion: operacion
	            },function (data, status) {
	                read();
	            });
		    }
	    	break;
	}
}


function getDetails(data1 = false,data2 = false){
	operacion="details";
	switch(selectorDB){
		case '1': //Leer los detalles guardados de la aula
			$("#hidden_aula_id").val(data1);	//Guardo el id del aula
			$.post(ruta, {
				id_aula: data1,
				selector_db:selectorDB,
				operacion: operacion
			},function (data, status) {
		    	var aulas = JSON.parse(data);
		        $("#update_nombre_aula").val(aulas.nombre_aula);
		        $("#update_capacidad").val(aulas.capacidad);
		        $('#selector_update_id_tipo_aula > option[value='+aulas.id_tipo+']').attr('selected', 'selected');
			});
			$("#update_aulas_modal").modal("show");
			break;

		case '2'://Leer los detalles guardados de la materia
			$("#hidden_materia_codigo").val(data1);//Guardo el codigo
		    $.post(ruta, {
		    	codigo: data1,
		    	selector_db:selectorDB,
		    	operacion: operacion
		    },function (data, status) {
		        var materias = JSON.parse(data);
	            $("#update_codigo").val(materias.codigo);
	            $("#update_nombre_asignatura").val(materias.asignatura);
	            $('#selector_update_semestre_materia > option[value='+materias.semestre+']').attr('selected', 'selected');
	            $("#update_horas_academicas_totales").val(materias.horas_academicas_totales);
	            $("#update_horas_academicas_semanales").val(materias.horas_academicas_semanales);
	            $('#selector_update_id_tipo_materia > option[value='+materias.id_tipo+']').attr('selected', 'selected');
	            $('#selector_update_carrera_materia > option[value='+materias.id_carrera+']').attr('selected', 'selected');
		    });
		    $("#update_materias_modal").modal("show");
			break;

		case '3'://Leer los detalles guardados del profesor
			$("#hidden_profesor_cedula").val(data1);//Guardo la cedula
		    $.post(ruta, {
		    	cedula: data1,
		    	selector_db:selectorDB,
		    	operacion: operacion
		    },function (data, status) {
	            var profesores = JSON.parse(data);
	            $("#update_cedula").val(profesores.cedula);
	            $("#update_nombre_profesor").val(profesores.nombre);
	            $("#update_apellido_profesor").val(profesores.apellido);
	            $('#selector_update_prioridad_profesor > option[value='+profesores.id_prioridad+']').attr('selected', 'selected');
		    });
		    $("#update_profesores_modal").modal("show");
			break;

		case '4'://En este caso solo guardo valores y selecciono los valores default
		    $("#hidden_profesor_materia_cedula").val(data1);
    		$("#hidden_profesor_materia_codigo").val(data2);
    		$('#selector_update_profesores > option[value='+data1+']').attr('selected', 'selected');
    		$('#selector_update_materias > option[value='+data2+']').attr('selected', 'selected');	
    		$("#update_profesores_materias_modal").modal("show");
			break;
	}
}

//Este metodo se debe pensar para que valide cualquier cosa que se le mande
//independientemente de los parametros que le pase
function validarCampos(selectorDB,data1 = false,data2 = false ,data3 = false,data4 = false,
					data5 = false, data6 = false, data7 = false){
	var validacion = true;
	/*
	switch(selectorDB){
		case "1":
			if (data1 ==="" || data2 ==="" || data3 ===""){
				validacion = false;
			}else{
				validacion = true;
			}
			break;
		case "2":
			if (data1 ==="" || data2 ==="" || data3 ==="" || data4 ==="" || data5 ==="" || data6 ==="" || data7 ===""){
				validacion = false;
			}else{
				validacion = true;
			}
			break;
		case "3":
			if (data1 ==="" || data2 ==="" || data3 ===""){
				validacion = false;
			}else{
				validacion = true;
			}
			break;
		case "4":
			if (data1 ==="" || data2 ===""){
				validacion = false;
			}else{
				validacion = true;
			}
			break;

	}
	*/
	return validacion;
}


document.getElementById("selector_db").addEventListener("change", function(){
	selectorDB = document.getElementById("selector_db").value;
	console.log(selectorDB);
});
