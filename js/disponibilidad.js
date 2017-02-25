var horasRestantes = 0;
var horasACumplir;

$(document).ready(function() {
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
					var horas = $.parseJSON(data);
					horasACumplir = horas.horas_a_cumplir;
					//debo hacer una forma de saber si ya tiene las horas cargadas (en cuyo caso
					//horasRestantes =0 o si no las tiene, en cuyo caso horasRestantes=horasACumplir)
					horasRestantes = horasACumplir;
					$("#horas_a_cumplir").val(horasACumplir);
					$("#horas_restantes").val(horasRestantes);
					//Se debe cargar la disponibilidadque tenia el profesor
					//en caso de tenerla, para luego ser editada 
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
}

//Esta funcion es llamada al presionar el boton de guardar
function cargarDisponibilidad(){
	if (horasRestantes==0 && $("#selector_profesores option:selected").val()!=0) {
		var data = guardarData();
		if (!validarData(data)) {
			alert("Un dia contiene menos de 2 horas academicas consecutivas");
		}else{
			//aca debo implantar la parte que se pasara al servidor
			alert("Guardado con exito");
		}
	}else if ($("#selector_profesores option:selected").val()==0){
		alert("No has seleccionado a un profesor");
	}else{
		alert("No has asignado todas tus horas requeridas");
	}
	return;
}

//Esta funcion recoge la data seleccionada por el usuario y devuelve un array
function guardarData(){
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

//Notese que no puedo llamar directamente a mi funcion cargarDisponibilidad,
//en cambio debe primero llamar a una jQuery
$("#cargar_disponibilidad").click(function(){
	cargarDisponibilidad();
	//falta algo como recargar pagina?
});