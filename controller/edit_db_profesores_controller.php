<?php  
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php');			
	$link = DBConnection::connection();

	switch ($_POST['operacion']) {
		case "create"://Crear profesores
			if (isset($_POST['nombre_profesor']) && isset($_POST['apellido_profesor']) && isset($_POST['prioridad'])) {
				$nombreProfesor = $_POST['nombre_profesor'];
			    $apellidoProfesor = $_POST['apellido_profesor'];
			 	$prioridad = $_POST['prioridad'];
			    $object = new ProfesoresModel($link);
			    $object->createProfesores($nombreProfesor,$apellidoProfesor,$prioridad);
			}
			break;

		case "read"://Leer profesores
			$object = new ProfesoresModel($link);
			$data = '<table class="table table-bordered  table-hover">
				                    <tr class="success">
				                        <th>No.</th>
				                        <th>Nombre</th>
				                        <th>Apellido</th>
				                        <th>Prioridad</th>                              
				                        <th>Editar</th>
				                        <th>Borrar</th>
				                    </tr>';
			$profesores = $object->getProfesores();
				if (count($profesores) > 0) {
					$number=0;
					foreach ($profesores as $key) {
						$number++;
						//Nota como usa el .= para concatener cada fila
					    $data.= "<tr>
							<td>".$number."</td>
							<td>".$key['Nombre']."</td>
							<td>".$key['Apellido']."</td>
							<td>".$key['Prioridad']."</td>
							<td> <button onclick='getProfesoresDetails(".$key['ID'].")' class='btn btn-warning'>Editar</button> </td>
							<td> <button onclick='deleteProfesores(".$key['ID'].")' class='btn btn-danger'>Borrar</button> </td>
							</tr>";
					}
				}else {
					$data .= '<tr><td colspan="6">No se encontraron profesores!</td></tr>';
				}
				$data .= '</table>';
				echo $data;
			break;

		case "update"://Actualizar profesores
			if (isset($_POST['nombre_profesor']) && isset($_POST['apellido_profesor']) && isset($_POST['prioridad']) && isset($_POST['id']) && isset($_POST['id']) != "") {		 
			    $id = $_POST['id'];
				$nombreProfesor = $_POST['nombre_profesor'];
			    $apellidoProfesor = $_POST['apellido_profesor'];
			 	$prioridad = $_POST['prioridad'];
			    $object = new ProfesoresModel($link);			 
			    $object->setProfesores($id,$nombreProfesor,$apellidoProfesor,$prioridad);
			}
			break;

		case "delete"://Borrar profesores
			if (isset($_POST['id']) && isset($_POST['id']) != "") {
			    $id = $_POST['id'];
			    $object = new ProfesoresModel($link);
			    $object->deleteProfesores($id);
			}
			break;

		case "details"://Obtener detalles de un profesor en especifico
			if (isset($_POST['id']) && isset($_POST['id']) != "") {
			    $id = $_POST['id'];
			    $object = new ProfesoresModel($link);
			    echo json_encode($object->getProfesores3($id));
			}
			break;		
	}
?>