var selectorDB; 
var operacion;
var validate;

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
var nombre;
var apellido;
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
		case "1":
			nombreAula = $("#nombre_aula").val().trim();
			capacidad = $("#capacidad").val().trim();
			idTipo = $("#selector_id_tipo_aula option:selected").val();
			validate = validarCampos(selectorDB,nombreAula,capacidad,idTipo);
			if (!validate){
				alert("Todos los campos son obligatorios");
			}else{
				$.post("edit_db_controller.php", {
		            nombre_aula: nombreAula,
		            capacidad: capacidad,
		           	id_tipo: idTipo,
		           	selector_db : selectorDB,
		            operacion: operacion
		        }, function (data, status) {
		            $("#add_new_aulas_modal").modal("hide");
		            read();
		            $("#nombre_aula").val("");
		            $("#capacidad").val("");
					$("#id_tipo").val("");
		        });
			}
			break;
		case "2":
			codigo = $("#codigo").val().trim();
		    asignatura = $("#nombre_materia").val().trim();
		    semestre = $("#semestre").val();
		    horasAcademicasTotales = $("#horas_totales").val().trim();
		    horasSemanales = $("#horas_semanales").val().trim();
		    idTipo = $("#tipo_materia").val();
		    idCarrera = $("#carrera").val();
			break;
		case "3":
		    nombre = $("#nombre_profesor").val().trim();
		    apellido = $("#apellido_profesor").val().trim();
			idPrioridad = $("#prioridad").val();
			break;
		case "4":
			cedula = document.getElementById("selector_profesores").value;
			codigo = document.getElementById("selector_materias").value;
			break;
	}
}

//Esta funcion lee de la DB de acuerdo a la opcion seleccionada en el selector
function read() {
    operacion="read";
    $.post("edit_db_controller.php", {
    	selector_db:selectorDB,
    	operacion:operacion
    },function (data, status) {
        $(".records_content").html(data);
    });
}


function update(){
	operacion = "update";
	switch(selectorDB){
		case '1':
			nombreAula = $("#update_nombre_aula").val().trim();
		    capacidad = $("#update_capacidad").val().trim();
		    idTipo = $("#selector_update_id_tipo_aula option:selected").val();
		    validate = validarCampos(selectorDB,nombreAula,capacidad,idTipo);
		    if (!validate) {
		    	alert("Todos los campos son obligatorios");
		    }else {
		        idAula = $("#hidden_aula_id").val();
		        $.post("edit_db_controller.php", {
		                id_aula: idAula,
		                nombre_aula: nombreAula,
		                capacidad: capacidad,
		                id_tipo: idTipo,
		                selector_db:selectorDB,
		                operacion: operacion
		        	},
		            function (data, status) {
		                $("#update_aulas_modal").modal("hide");
		                read();
		            }
		        );
		    }
			break;
		case '2':
			break;
		case '3':
			break;
		case '4':
			break;			
	}
}


function deleteStuff (){
	operacion="delete";
    var conf = confirm("¿Estas seguro que deseas borrar el aula?");
    if (conf == true) {
        $.post("edit_db_controller.php", {
                id_aula: idAula,
                selector_db:selectorDB,
                operacion: operacion
            },
            function (data, status) {
                read();
            }
        );
    }
}


function getDetails(data1 = false,data2 = false){
	operacion="details";
	switch(selectorDB){
		case '1': //Leer los detalles guardados de la aula
			$("#hidden_aula_id").val(data1);	//Guardo el id del aula
			$.post("edit_db_controller.php", {
				id_aula: data1,
				selector_db:selectorDB,
				operacion: operacion
			},function (data, status) {
		    	var aulas = JSON.parse(data);
		        $("#update_nombre_aula").val(aulas.nombre_aula);
		        $("#update_capacidad").val(aulas.capacidad);
		        $('#update_id_tipo_aula > option[value='+aulas.id_tipo+']').attr('selected', 'selected');
		        //$("#update_id_tipo").val(aulas.Tipo);
			});
			$("#update_aulas_modal").modal("show");
			break;
	}
}


function validarCampos(selectorDB,data1 = false,data2 = false ,data3 = false,data4 = false,data5 = false, data6 = false, data7 = false){
	var validacion;
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
	return validacion;
}


document.getElementById("selector_db").addEventListener("change", function(){
	selectorDB = document.getElementById("selector_db").value;
	console.log(selectorDB);
});
