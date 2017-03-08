$(document).ready(function() {
	$("#archivos").hide();
});

$("#generar_horarios_button").click(function(){
	alert("Horarios generados con exito");
	//al hacer click aca debo generar los datos que se guardan en horarios_profesores
	//de la db
	$("#archivos").show();
});