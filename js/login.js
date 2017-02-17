$("#login_button").click(function(){
	var username = $("#input_username").val().trim();
	var password = $("#input_password").val().trim();
	if (validate(username,password)) {
		$.post("controller/login_controller.php",{username:username,password:password},function(data){
			if (data =="true") {
				window.location.href = 'views/home_view.html';
				//window.location.replace('views/home_view.html');
			}else{
				$("#error_login").show();
			}
		});
	}	
});

//Este trozo lo que evita es que cuando la sesion este abierta
//no puedas acceder a la pagina index a menos que cierres la sesion
//Esteticamente, esta funcion no deberia estar aqui
window.onload = function (){
	var operacion = "validar_sesion";
	$.post("controller/sesiones_controller.php",{operacion:operacion},function(data){
		if (data=="true") {
			window.location.href = 'views/home_view.html';
		} else {
			$("#cuerpo").show();
		}
	});
};


function validate(username,password){
	if(username=="" || username.length<6){
		$("#input_username").parent().attr("class","form-group has-error");
		$("#input_username").parent().children("span").text("Usuario invalido,debe contener 6 caracteres").show();
	}else if (password=="" || password.length<6){
		$("#input_username").parent().attr("class","form-group has-success");
		$("#input_username").parent().children("span").text("").hide();
		$("#input_password").parent().attr("class","form-group has-error");
		$("#input_password").parent().children("span").text("Clave invalida,debe contener 6 caracteres").show();
	}else{
		$("#input_password").parent().attr("class","form-group has-success");
		$("#input_password").parent().children("span").text("Usuario invalido,debe contener 6 caracteres").hide();
		$("#input_username").parent().attr("class","form-group has-success");
		$("#input_username").parent().children("span").text("Clave invalida,debe contener 6 caracteres").hide();
		return true;
	}
	return false;
}