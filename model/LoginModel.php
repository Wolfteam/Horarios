<?php
/**
 * Esta clase permite el acceso al sistema
 * mediante el login 
 */
class LoginModel{
	private $link;

	function __construct($link){
		$this->link = $link;
	}
	/*
	function __destruct() {
			$this->link=null;
		}
	*/
	public function getUser($username,$password){
		$result = $this->link->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
		$rows = $result->rowCount();
		if($rows>0){	
			return true;
		}
		return false;
	}

	//quizas agregar un metodo aca que evite el sql
	//o quizas un metodo para realizar mas validaciones
	//normalmente se suele hacer este tipo de cosas con prepare y bindvalues y consultas preparadas
	//para evitar sql injection, por supuesto esto aplica a cualquier clase q acceda a la DB
}
?>