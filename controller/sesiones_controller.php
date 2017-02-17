<?php 
	session_start(); 
	if (isset($_POST['operacion'])) {
		if ($_POST['operacion']=="validar_sesion") {
			if (isset($_SESSION['sesion'])) {
				if ($_SESSION['sesion']==true) {
					echo "true";
				}else {
					echo "false";
				}
			}else {
				echo "false";
			}
		}else{
			//Cuando se usa session_destroy solo se destruye la sesion actual si hubieran mas de 1 no las destruiria
			session_destroy();
			echo "false";
		}
	}else{
		//Aca entra solo cuando se pulsa en Cerrar Sesion
		session_destroy();
		header("location:../index.html");
	}
?>