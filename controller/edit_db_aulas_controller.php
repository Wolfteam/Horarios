<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/AulasModel.php');
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');		
	
	$link = DBConnection::connection();
	
	switch ($_POST['operacion']) {
		case "create"://Crear aulas
			if (isset($_POST['nombre_aula']) && isset($_POST['capacidad']) && isset($_POST['id_tipo'])) {
				$nombreAula = $_POST['nombre_aula'];
			    $capacidad = $_POST['capacidad'];
			 	$idTipo = $_POST['id_tipo'];
			    $object = new AulasModel($link);
			    $object->createAulas($nombreAula,$capacidad,$idTipo);
			}
			break;

		case "read"://Leer aulas
			$object = new AulasModel($link);
			$data = '<table class="table table-bordered  table-hover">
				                    <tr class="success">
				                        <th>ID</th>
				                        <th>Nombre</th>
				                        <th>Capacidad</th>
				                        <th>Tipo</th>                              
				                        <th>Editar</th>
				                        <th>Borrar</th>
				                    </tr>';
			$aulas = $object->getAulas();
				if (count($aulas) > 0) {
					foreach ($aulas as $key) {
						//Nota como usa el .= para concatener cada fila
					    $data.= "<tr>
							<td>".$key['id_aula']."</td>
							<td>".$key['nombre_aula']."</td>
							<td>".$key['capacidad']."</td>
							<td>".$key['id_tipo']."</td>
							<td> <button onclick='getAulasDetails(".$key['id_aula'].")' class='btn btn-warning'>Editar</button> </td>
							<td> <button onclick='deleteAulas(".$key['id_aula'].")' class='btn btn-danger'>Borrar</button> </td>
							</tr>";
					}
				}else {
					$data .= '<tr><td colspan="6">No se encontraron aulas!</td></tr>';
				}
				$data .= '</table>';
				echo $data;
			break;

		case "update"://Actualizar aulas
			if (isset($_POST['nombre_aula']) && isset($_POST['capacidad']) && isset($_POST['id_aula']) && isset($_POST['id']) && isset($_POST['id']) != "") {		 
			    $id = $_POST['id'];
			    $nombreAula = $_POST['nombre_aula'];
			    $capacidad = $_POST['capacidad'];
			    $tipo = $_POST['tipo'];
			    $obj = new AulasModel($link);			 
			    $obj->setAulas($id,$nombreAula,$capacidad,$tipo);
			}
			break;

		case "delete":
			if (isset($_POST['id']) && isset($_POST['id']) != "") {
			    $id = $_POST['id'];
			    $object = new AulasModel($link);
			    $object->deleteAulas($id);
			}
			break;

		case "details":
			if (isset($_POST['id']) && isset($_POST['id']) != "") {
			    $id = $_POST['id'];
			    $object = new AulasModel($link);	 
			    echo json_encode($object->getAulas3($id));
			}
			break;	
	}
?>