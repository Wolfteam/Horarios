var horasCargadas = 0;
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
			var cedula = $("#selector_profesores option:selected").val();
			$.post('../controller/disponibilidad.php', {
				cedula: cedula
			}, function(data) {
				var horas = $.parseJSON(data);
				horasACumplir = horas.horas_a_cumplir;
				$("#horas_a_cumplir").val(horasACumplir);
				//Se debe cargar la disponibilidadque tenia el profesor
				//en caso de tenerla, para luego ser editada 
			});
		});
	});
	//Las tablas son transparentes, no tienen codigo de color
	//Debo arreglar ese problema para que pueda sumar y restar bien las horas
	$("td").click(function(){
		var atributo = $(this).css("background-color");
		console.log(atributo);

		if ($(this).attr("bgcolor")=="#fff" || $(this).css("background-color")=="transparent") {
			$(this).attr("bgcolor","#5cb85c");
			horasCargadas++;
		}else{
			$(this).attr("bgcolor","#fff");
			if (horasCargadas!=0) {
				horasCargadas--;
			}			
		}
		$("#horas_restantes").val(horasPorAsignar(horasACumplir,horasCargadas));
	});
	//Cambia el color cuando pasas el mouse encima de una celda
	//o sales de ella,da conflicto con lo de restar o sumar horas
	/*
	$("td").mouseover(function(){
		if ($(this).attr("bgcolor")!="#5cb85c") {
			$(this).attr("bgcolor","#057D97");
		}
	});
	$("td").mouseout(function(){
		if ($(this).attr("bgcolor")!="#5cb85c") {
			$(this).attr("bgcolor","#fff");
		}
	});
	*/
});

function horasPorAsignar(dataA,dataB){
	var resultado = dataA - dataB;

	console.log(resultado);
	return resultado;
}
