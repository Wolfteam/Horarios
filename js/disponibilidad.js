var horasRestantes = 0;
var horasACumplir;

$(document).ready(function() {
	$("#borrar_disponibilidad").hide();
	//Obten la data de los profesores al cargar la pagina 
	$.post("../controller/disponibilidad.php",{
		operacion:"read"
	},function(data){
		$("#selector_profesores").html(data);
	});

	$("#selector_profesores").change(function(){
		$("#selector_profesores").each(function(){
			if ($("#selector_profesores option:selected").val()!=0) {
				var cedula = $("#selector_profesores option:selected").val();
				$.post('../controller/disponibilidad.php', {
					cedula: cedula
				}, function(data) {
					limpiarCeldas();
					var datos = $.parseJSON(data);
					horasACumplir = datos.horas_a_cumplir;
					if (datos.numero_registros==0) {
						horasRestantes = horasACumplir;
					}else{
						horasRestantes=0;
						$("#borrar_disponibilidad").show();
						llenarCeldas(datos.registros);
					}			
					$("#horas_a_cumplir").val(horasACumplir);
					$("#horas_restantes").val(horasRestantes);
				});
			}else{
				limpiarCeldas();
			}
		});
	});

	//Si el selector esta en una option diferente a la default, se permite el cambio
	//de color de las celdas al hacer click y se actualizan las horas_restantes
	$("td").click(function(){
		if ($("#selector_profesores option:selected").val()!=0) {
			var atributo = $(this).css("background-color");
			if ($(this).attr("bgcolor")=="#fff" || $(this).attr("bgcolor")=="#057D97"){
				if (horasRestantes>0) {
					$(this).attr("bgcolor","#5cb85c");
					horasRestantes--;
				}	
			}else{
				$(this).attr("bgcolor","#fff");
				horasRestantes++;
			}
			$("#horas_restantes").val(horasRestantes);
		}
	});

	//Cambia el color cuando pasas el mouse encima de una celda o sales de ella,
	//si y solo si la option del select es diferente a la default	
	$("td").mouseover(function(){
		if ($("#selector_profesores option:selected").val()!=0) {
			if ($(this).attr("bgcolor")!="#5cb85c") {
				$(this).attr("bgcolor","#057D97");
			}
		}
	});
	$("td").mouseout(function(){
		if ($("#selector_profesores option:selected").val()!=0) {
			if ($(this).attr("bgcolor")!="#5cb85c") {
				$(this).attr("bgcolor","#fff");
			}
		}
	});
});

//Coloca las celdas de color blanco, puesto que inicialmente son transparentes 
//y limpia los campos deshabilitados
function limpiarCeldas() {
	$("#tabla_horario tbody tr td").attr("bgcolor","#fff");
	$("#horas_a_cumplir").val("");
	$("#horas_restantes").val("");
	$("#borrar_disponibilidad").hide();
}

//Esta funcion es llamada al presionar el boton de guardar
function cargarDisponibilidad(){
	if (horasRestantes==0 && $("#selector_profesores option:selected").val()!=0) {
		var data = codificarData();
		if (!validarData(data)) {
			alert("Un dia contiene menos de 2 horas academicas consecutivas");
		}else{
			var cedula = $("#selector_profesores option:selected").val();
			$.post('../controller/disponibilidad.php', {
				operacion: 'create_update',
				cedula:cedula,
				data: data
			}, function(data) {
				alert("Guardado con exito");
				location.reload();
			});	
		}
	}else if ($("#selector_profesores option:selected").val()==0){
		alert("No has seleccionado a un profesor");
	}else{
		alert("No has asignado todas tus horas requeridas");
	}
	return;
}

//Esta funcion recoge la data seleccionada por el usuario y devuelve un array
function codificarData(){
	var arrayData=[];
	var tabla=document.getElementById("tabla_horario");
	for (var j = 1; j < 7; j++) {		
		for (var i = 1; i < 13; i++) {
			if (tabla.rows[i].cells[j].getAttribute("bgcolor")=="#5cb85c") {
				arrayData.push(i+","+j);
			}	
		}
	}
	return arrayData;
}

//Esta funcion valida que hayan minimo 2 horas academicas seguidas en un dia
//Recibe como parametro un array que contiene los datos de los dias-horas seleccionados
function validarData(data){
	var indexA,indexB,indexC,i,j,k,coincidenciasMinimas;
	var arrayCoincidencias=[];
	for (j = 1; j < 7; j++) {
		var coincidencias=0;
		var coincidenciasObtenidas = 0;		
		for (i = 1; i < 13; i++) {
			indexA = i+","+j;
			for (k = 0; k <= data.length; k++) {
				if (data[k]==indexA) {
					coincidencias++;
					break;
				}	
			}
		}
		//El chiste aca es que coincidencias llegue a 0, si no lo hace es porque
		//no se cumple que hayan 2 hrs academicas consecutivas
		//Para validar pregunto por la fila en la q me encuentro y la siguiente,
		//si ambas estan seleccionadas resto 2 e incremento la fila para poder
		//preguntar por las 2 siguientes. Sino se cumple esa condicion, preg. si la celda
		//anterior esta seleccionada y si k>0 entonces resto 1. Este caso ocurre solo cuando
		//tienes mas de 3 celdas seguidas seleccionadas y la 4ta no lo esta
		for (i = 1; i <= 12; i++) {
			indexA = i+","+j;
			indexB = (i+1)+","+j;
			indexC = (i-1)+","+j;
			for (k = 0; k < data.length; k++) {
				if (data[k]==indexA && data[k+1]==indexB) {
					coincidencias-=2;
					i++;
					break;
				}else if (data[k-1]==indexC && data[k]==indexA && k>0) {
					coincidencias-=1;
				}
			}
		}
		if (coincidencias!=0) {
			return false;
		}
	}
	return true;
}

//Esta funcion llena las celdas cuando existe algun registro guardado en la tabla
//disponibilidad_profesores. Recibe un conjunto de registros equivalentes a lo 
//encontrado en la DB
function llenarCeldas(datos){
	for (var k = 0; k < Object.keys(datos).length; k++) {
		var tabla=document.getElementById("tabla_horario");
		for (var j = 1; j < 7; j++) {		
			for (var i = 1; i < 13; i++) {
				if (datos[k].id_dia==j && datos[k].id_hora_inicio==i) {
					for (var t = i; t < datos[k].id_hora_fin; t++) {
						tabla.rows[t].cells[j].setAttribute("bgcolor","#5cb85c");
					}
				}	
			}
		}	
		//console.log("Dia:"+datos[i].id_dia+",hora_inicio:"+datos[i].id_hora_inicio+",hora_fin:"+datos[i].id_hora_fin);
	}
	return;
}

//Notese que no puedo llamar directamente a mi funcion cargarDisponibilidad,
//en cambio debe primero llamar a una jQuery
$("#cargar_disponibilidad").click(function(){
	cargarDisponibilidad();
});

//Esto se ejecuta solo si la persona seleccionada cuenta con disponibilidad 
//previamente almacenada y borra dicha disponibilidad
$("#borrar_disponibilidad").click(function(){
	var cedula = $("#selector_profesores option:selected").val();
	$.post('../controller/disponibilidad.php', {
		operacion: 'delete',
		cedula: cedula
	}, function(data) {
		alert("Borrado exitoso");
		location.reload();
	});	
});