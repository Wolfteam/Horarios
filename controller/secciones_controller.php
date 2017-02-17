<?php 
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/MateriasModel.php');	
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/SeccionesModel.php'); 
	$link = DBConnection::connection();

	if ($_POST['operacion']=="materias") {
	    $obj = new MateriasModel($link);
	    $result = $obj->getMaterias();
	    $data = "<option>Seleccione una materia</option>";
	    foreach ($result as $key) {
	    	$data.= "<option value=".$key['Codigo'].">Semestre:".$key['Semestre']." ".$key['Asignatura']."</option>";
	    }
	    echo $data;
	}	

	if ($_POST['operacion']=="create") {
		if (isset($_POST['numero_seccion']) && isset($_POST['cantidad_alumnos']) && isset($_POST['codigomateria'])) {
	 
	    $numeroSeccion = $_POST['numero_seccion'];
	    $cantidadAlumnos = $_POST['cantidad_alumnos'];
	    //$codigo = $_POST['codigo'];
	 	$codigomateria = $_POST['codigomateria'];
	    $object = new SeccionesModel($link);
	 
	    $object->createSecciones($numeroSeccion,$cantidadAlumnos,$codigomateria);
		}
	}

	if ($_POST['operacion']=="read") {
		$object = new SeccionesModel($link);
		// Design initial table header
		$data = '<table class="table table-bordered  table-hover text-center">
		                    <tr class="success">
		                        <th class="text-center">No.</th>
		                        <th class="text-center">Codigo</th>
		                        <th class="text-center">Semestre</th>
		                        <th class="text-center">Asignatura</th>    
		                        <th class="text-center">Numero Seccion</th>
		                        <th class="text-center">Cantidad Alumnos</th>                            
		                        <th class="text-center">Update</th>
		                        <th class="text-center">Delete</th>
		                    </tr>';
		$users = $object->getSecciones();
		if (count($users) > 0) {
			$number = 1;
			foreach ($users as $user) {
				//Nota como usa el .= para concatener cada fila
			    $data.= "<tr>
					<td>".$number."</td> 
					<td>".$user['Codigo']."</td>
					<td>".$user['Semestre']."</td>
					<td>".$user['Asignatura']."</td>
					<td>".$user['Numero_Seccion']."</td>
					<td>".$user['Cantidad_Alumnos']."</td>
					<td> <button onclick='GetUserDetails(".$user['ID'].")' class='btn btn-warning'>Update</button> </td>
					<td> <button onclick='DeleteUser(".$user['ID'].")' class='btn btn-danger'>Delete</button> </td>
					</tr>";
			    $number++;
			}

		} else {
		// records not found
			$data .= '<tr><td colspan="6">Records not found!</td></tr>';
		}
		$data .= '</table>';
		echo $data;
	}

	if ($_POST['operacion']=="details") {
		if (isset($_POST['id']) && isset($_POST['id']) != "") {
		    $id = $_POST['id'];
		    $object = new SeccionesModel($link);	 
		    echo $object->getSeccion2($id);
		}

	}

	if ($_POST['operacion']=="update") {
		if (isset($_POST)) {		 
		    $id = $_POST['id'];
		    $numeroSeccion = $_POST['numero_seccion'];
		    $cantidadAlumnos = $_POST['cantidad_alumnos'];
		 
		    $object = new SeccionesModel($link);
		 
		    $object->setSecciones($id,$numeroSeccion,$cantidadAlumnos);
		}
	}


	if ($_POST['operacion']=="delete") {
		if (isset($_POST['id']) && isset($_POST['id']) != "") {
		    $id = $_POST['id'];
		    $object = new SeccionesModel($link);
		    $object->deleteSecciones($id);
		}
	}
?>