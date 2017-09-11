<?php
	require($_SERVER['DOCUMENT_ROOT'].'/Model/DBConnection.php'); 
	require_once($_SERVER['DOCUMENT_ROOT'].'/Model/LoginModel.php');
	
	if ( (isset($_POST['username'])) && (isset($_POST['password'])) ) {
		$username=trim($_POST['username']);
		$password=trim($_POST['password']);
		if ($username !="" && $password !="") {
			$link = DBConnection::connection();
			$obj = new LoginModel($link);
			//$link=null;
			$var = $obj->getUser($username,$password);			
			if ($obj->getUser($username,$password)) {
				session_start();
				$_SESSION['sesion'] =true;
				//Si la clave y usuario coincide con el de la db devuelvo true
				echo "true";
			}else{
				echo "false";
			}
			
		}else{
			echo "false";
		}
	}else{
		echo "false";
	}
?>