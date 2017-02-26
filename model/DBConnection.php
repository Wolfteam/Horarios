<?php
/**
 * Esta clase se encarga de realizar la conexion con la DB y retorna
 *un link a ella
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConfig.php');

class DBConnection {
	public static function connection() {
		try {
			$link = new PDO(DBHost,DBUsername,DBPassword);
			$link->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$link->exec(DBCharset);
		} catch (Exception $e) {
			echo "Error en la linea: ".$e->getLine();
			error_log("Error al crear la base de datos");
			die("El error es: ".$e->getMessage()) ;
		}
		return $link;
	}
}
?>