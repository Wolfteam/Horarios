window.onload = sesion();

function sesion(){
	var operacion = "validar_sesion";
	$.post("../controller/sesiones_controller.php",{operacion:operacion},function(data){
		if (data!="true") {
			window.location.href = '../index.html';
		} else {
			$("#cuerpo").show();
		}
	});
}

//El problema aca esq este metodo se ejecuta cuando presionas f5,cierras la ventana o recargas
//la pagina, de la pagina donde te encuentres
//Nito ver si hay una forma dq solo se ejecute en la que yo quiero y no se ejcute el 1 y 3 evento
/* 
window.onBeforeUnload = cierraSesion();

function cierraSesion(){
	window.location.href = '../controller/sesiones_controller.php';
}
*/