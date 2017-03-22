<?php  
/**
 * 
 */
class DiasModel{
	private $link;
	function __construct($link){
		$this->link=$link;
	}

	public function getDias(){
		$query = "SELECT * FROM dias";
		$result = $this->link->query($query);
		while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
			$horarioProfesores[]=$rows;
		}
		return $horarioProfesores;
	}
}
?>