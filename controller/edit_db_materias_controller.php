<?php  
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/MateriasModel.php');		
	$link = DBConnection::connection();

	switch ($_POST['operacion']) {
		case "create"://Crear materias
			if (isset($_POST['codigo']) && isset($_POST['nombre_materia']) && isset($_POST['semestre']) && isset($_POST['horas_totales']) && isset($_POST['horas_semanales']) && isset($_POST['tipo_materia']) && isset($_POST['carrera'])) {

				$codigo = $_POST['codigo'];
			    $nombreMateria = $_POST['nombre_materia'];
			    $semestre = $_POST['semestre'];
			    $horasTotales = $_POST['horas_totales'];
			    $horasSemanales = $_POST['horas_semanales'];
			 	$tipo = $_POST['tipo_materia'];
			 	$carrera = $_POST['carrera'];

			    $object = new MateriasModel($link);
			    $object->createMaterias($codigo,$nombreMateria,$semestre,$horasTotales,$horasSemanales,$tipo,$carrera);
			}
			break;

		case "read"://Leer materias
			$object = new MateriasModel($link);
			$data = '<table class="table table-bordered  table-hover">
				                    <tr class="success">
				                        <th>Codigo</th>
				                        <th>Asignatura</th>
				                        <th>Semestre</th>
				                        <th>Horas_Academicas</th>
				                        <th>Horas_Academicas_Semanales</th> 
				                        <th>Tipo</th>   
				                        <th>Carrera</th>                            
				                        <th>Editar</th>
				                        <th>Borrar</th>
				                    </tr>';
			$materias = $object->getMaterias();
				if (count($materias) > 0) {
					foreach ($materias as $key) {
						//Nota como usa el .= para concatener cada fila
					    $data.= "<tr>
							<td>".$key['Codigo']."</td>
							<td>".$key['Asignatura']."</td>
							<td>".$key['Semestre']."</td>
							<td>".$key['Horas_Academicas']."</td>
							<td>".$key['Horas_Academicas_Semanales']."</td>
							<td>".$key['Tipo']."</td>
							<td>".$key['Carrera']."</td>
							<td> <button onclick='getMateriasDetails(".$key['Codigo'].")' class='btn btn-warning'>Editar</button> </td>
							<td> <button onclick='deleteMaterias(".$key['Codigo'].")' class='btn btn-danger'>Borrar</button> </td>
							</tr>";
					}
				}else {
					$data .= '<tr><td colspan="6">No se encontraron materias!</td></tr>';
				}
				$data .= '</table>';
				echo $data;
			break;

		case "update"://Actualizar materias
			if (isset($_POST['codigo']) && isset($_POST['nombre_materia']) && isset($_POST['semestre']) && isset($_POST['horas_totales']) && isset($_POST['horas_semanales']) && isset($_POST['tipo_materia']) && isset($_POST['carrera'])) {	

				$codigo = $_POST['codigo'];
			    $nombreMateria = $_POST['nombre_materia'];
			    $semestre = $_POST['semestre'];
			    $horasTotales = $_POST['horas_totales'];
			    $horasSemanales = $_POST['horas_semanales'];
			 	$tipo = $_POST['tipo_materia'];
			 	$carrera = $_POST['carrera'];
			 	error_log("Codigo:".$codigo." nombre:".$nombreMateria." semestre:".$semestre." horas totales:".$horasTotales." horasSemanales:".$horasSemanales." tipo:".$tipo." carrera:".$carrera."");
			    $obj = new MateriasModel($link);			 
			    $obj->setMaterias($codigo,$nombreMateria,$semestre,$horasTotales,$horasSemanales,$tipo,$carrera);
			}
			break;

		case "delete":
			if (isset($_POST['codigo']) && isset($_POST['codigo']) != "") {
			    $codigo = $_POST['codigo'];
			    $object = new MateriasModel($link);
			    $object->deleteMaterias($codigo);
			}
			break;

		case "details":
			if (isset($_POST['codigo']) && isset($_POST['codigo']) != "") {
			    $codigo = $_POST['codigo'];
			    $object = new MateriasModel($link);	 
			    echo json_encode($object->getMaterias2($codigo));
			}
			break;	
	}
?>