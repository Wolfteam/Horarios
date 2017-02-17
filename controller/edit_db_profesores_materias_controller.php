<?php  
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/MateriasModel.php');	
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresMateriasModel.php');		
	$link = DBConnection::connection();

	switch ($_POST['operacion']) {
		case "mostrar_materias":
			$obj = new MateriasModel($link);
		    $result = $obj->getMaterias();
		    $data = "<option value='0'>Seleccione una materia</option>";
		    foreach ($result as $key) {
		    	$data.= "<option value=".$key['Codigo'].">Semestre:".$key['Semestre']." ".$key['Asignatura']."</option>";
		    }
		    echo $data;
			break;
		case "mostrar_profesores":
			$obj = new ProfesoresModel($link);
		    $result = $obj->getProfesores();
		    $data = "<option value='0'>Seleccione un profesor</option>";
		    foreach ($result as $key) {
		    	$data.= "<option value=".$key['ID'].">".$key['Nombre']." ".$key['Apellido']." Prioridad:".$key['Prioridad']."</option>";
		    }
		    echo $data;
			break;

		case "create"://Crear profesores_materias
			if (isset($_POST['id_profesor']) && isset($_POST['codigo_materia'])) {
				$idProfesor = $_POST['id_profesor'];
			    $codigoMateria = $_POST['codigo_materia'];
			    $object = new ProfesoresMateriasModel($link);
			    $object->createProfesorMaterias($idProfesor,$codigoMateria);
			}
			break;		

		case "read"://Leer profesores_materias
			$object = new ProfesoresMateriasModel($link);
			$data = '<table class="table table-bordered  table-hover">
				                    <tr class="success">
				                        <th>Codigo</th>
				                        <th>Asignatura</th>
				                        <th>Semestre</th>
				                        <th>Nombre</th>
				                        <th>Apellido</th>                               
				                        <th>Editar</th>
				                        <th>Borrar</th>
				                    </tr>';
			$aulas = $object->getProfesoresMaterias3();
				if (count($aulas) > 0) {
					foreach ($aulas as $key) {
					    $data.= "<tr>
							<td>".$key['Codigo']."</td>
							<td>".$key['Asignatura']."</td>
							<td>".$key['Semestre']."</td>
							<td>".$key['Nombre']."</td>
							<td>".$key['Apellido']."</td>
							<td> <button onclick='getProfesoresMateriasDetails(".$key['ID'].",".$key['Codigo'].")' class='btn btn-warning'>Editar</button> </td>
							<td> <button onclick='deleteProfesoresMaterias(".$key['ID'].",".$key['Codigo'].")' class='btn btn-danger'>Borrar</button> </td>
							</tr>";
					}
				}else {
					$data .= '<tr><td colspan="6">No se encontraron resultados</td></tr>';
				}
				$data .= '</table>';
				echo $data;
			break;	

		case "update"://Actualizar profesores_materias
			if (isset($_POST['id_profesor_nuevo']) !="" && isset($_POST['codigo_materia_nuevo']) !="" && isset($_POST['codigo_materia_viejo']) !="" && isset($_POST['id_profesor_viejo']) !="") {		 

				$idProfesorNuevo = $_POST['id_profesor_nuevo'];
			    $codigoMateriaNuevo = $_POST['codigo_materia_nuevo'];
			    $idProfesorViejo = $_POST['id_profesor_viejo'];
			    $codigoMateriaViejo = $_POST['codigo_materia_viejo'];
			    $obj = new ProfesoresMateriasModel($link);			 
			    $obj->setProfesoresMaterias($idProfesorViejo,$codigoMateriaViejo,$idProfesorNuevo,$codigoMateriaNuevo);
			}
			break;

		case "delete":
			if (isset($_POST['id_profesor'])!="" && isset($_POST['codigo_materia']) != "") {
			    $idProfesor = $_POST['id_profesor'];
			    $codigoMateria = $_POST['codigo_materia'];
			    $object = new ProfesoresMateriasModel($link);
			    $object->deleteProfesorMaterias($idProfesor,$codigoMateria);
			}
			break;

		case "details":
			break;	
	}

?>