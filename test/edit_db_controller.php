<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/AulasModel.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/MateriasModel.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresMateriasModel.php');
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');		
	
	$link = DBConnection::connection();
	
	$arrayCabeceraTablas = [
	    "aulas" => '<table class="table table-bordered  table-hover">
	    				<tr class="success">
	                        <th>ID</th>
	                        <th>Nombre</th>
	                        <th>Capacidad</th>
	                        <th>Tipo</th>                              
	                        <th>Editar</th>
	                        <th>Borrar</th>
	                    </tr>',
	    "materias" => '<table class="table table-bordered  table-hover">
			                <tr class="success">
			                    <th>Codigo</th>
			                    <th>Asignatura</th>
			                    <th>Semestre</th>
			                    <th>Horas_Academicas_Totales</th>
			                    <th>Horas_Academicas_Semanales</th> 
			                    <th>Tipo</th>   
			                    <th>Carrera</th>                            
			                    <th>Editar</th>
			                    <th>Borrar</th>
			                </tr>',
		"profesores"=> '<table class="table table-bordered  table-hover">
		                    <tr class="success">
		                        <th>No.</th>
		                        <th>Cedula</th>
		                        <th>Nombre</th>
		                        <th>Apellido</th>
		                        <th>Prioridad</th>                              
		                        <th>Editar</th>
		                        <th>Borrar</th>
		                    </tr>',
		"profesores_materias"=>'<table class="table table-bordered  table-hover">
				                    <tr class="success">
				                        <th>Codigo</th>
				                        <th>Asignatura</th>
				                        <th>Semestre</th>
				                        <th>Nombre</th>
				                        <th>Apellido</th>                               
				                        <th>Editar</th>
				                        <th>Borrar</th>
				                    </tr>',
	];

	switch ($_POST['operacion']) {
		case "create":
			switch ($_POST['selector_db']) {
				case '1'://Crear Aulas
					if (isset($_POST['nombre_aula']) && isset($_POST['capacidad']) && isset($_POST['id_tipo'])) {
						$nombreAula = $_POST['nombre_aula'];
					    $capacidad = $_POST['capacidad'];
					 	$idTipo = $_POST['id_tipo'];
					    $object = new AulasModel($link);
					    $object->createAulas($nombreAula,$capacidad,$idTipo);
					}
					break;
				case '2'://Crear Materias
					if (isset($_POST['codigo']) && isset($_POST['asignatura']) && isset($_POST['semestre']) && isset($_POST['horas_academicas_totales']) && isset($_POST['horas_academicas_semanales']) && isset($_POST['id_tipo']) && isset($_POST['id_carrera'])) {

						$codigo = $_POST['codigo'];
					    $nombreMateria = $_POST['asignatura'];
					    $semestre = $_POST['semestre'];
					    $horasAcademicasTotales = $_POST['horas_academicas_totales'];
					    $horasAcademicasSemanales = $_POST['horas_academicas_semanales'];
					 	$idTipo = $_POST['id_tipo'];
					 	$idCarrera = $_POST['id_carrera'];
					    $object = new MateriasModel($link);
					    $object->createMaterias($codigo,$nombreMateria,$semestre,$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera);
					}
					break;
				
				default:
					# code...
					break;
			}
			break;
	
		case "read":
			switch ($_POST['selector_db']) {
				case '1'://leer Aulas
					$object = new AulasModel($link);
					$data = $arrayCabeceraTablas['aulas'];
					$aulas = $object->getAulas();
					if (count($aulas) > 0) {
						foreach ($aulas as $key) {
							//Nota como usa el .= para concatener cada fila
						    $data.= "<tr>
								<td>".$key['id_aula']."</td>
								<td>".$key['nombre_aula']."</td>
								<td>".$key['capacidad']."</td>
								<td>".$key['id_tipo']."</td>
								<td> <button onclick='getDetails(".$key['id_aula'].")' class='btn btn-warning'>Editar</button> </td>
								<td> <button onclick='deleteStuff(".$key['id_aula'].")' class='btn btn-danger'>Borrar</button> </td>
								</tr>";
						}
					}else {
						$data .= '<tr><td colspan="6">No se encontraron aulas!</td></tr>';
					}
					$data .= '</table>';
					echo $data;
					break;
				case '2'://leer Materias
					$object = new MateriasModel($link);
					$data = $arrayCabeceraTablas['materias'];
					$materias = $object->getMaterias();
					if (count($materias) > 0) {
						foreach ($materias as $key) {
							//Nota como usa el .= para concatener cada fila
						    $data.= "<tr>
								<td>".$key['codigo']."</td>
								<td>".$key['asignatura']."</td>
								<td>".$key['semestre']."</td>
								<td>".$key['horas_academicas_totales']."</td>
								<td>".$key['horas_academicas_semanales']."</td>
								<td>".$key['id_tipo']."</td>
								<td>".$key['id_carrera']."</td>
								<td> <button onclick='getDetails(".$key['codigo'].")' class='btn btn-warning'>Editar</button> </td>
								<td> <button onclick='deleteStuff(".$key['codigo'].")' class='btn btn-danger'>Borrar</button> </td>
								</tr>";
						}
					}else {
						$data .= '<tr><td colspan="6">No se encontraron materias!</td></tr>';
					}
					$data .= '</table>';
					echo $data;
					break;
				case '3'://leer Profesores
					$object = new ProfesoresModel($link);
					$data = $arrayCabeceraTablas['profesores'];
					$profesores = $object->getProfesores();
					if (count($profesores) > 0) {
						$number=0;
						foreach ($profesores as $key) {
							$number++;
							//Nota como usa el .= para concatener cada fila
						    $data.= "<tr>
								<td>".$number."</td>
								<td>".$key['cedula']."</td>
								<td>".$key['nombre']."</td>
								<td>".$key['apellido']."</td>
								<td>".$key['id_prioridad']."</td>
								<td> <button onclick='getProfesoresDetails(".$key['cedula'].")' class='btn btn-warning'>Editar</button> </td>
								<td> <button onclick='deleteProfesores(".$key['cedula'].")' class='btn btn-danger'>Borrar</button> </td>
								</tr>";
						}
					}else {
						$data .= '<tr><td colspan="6">No se encontraron profesores!</td></tr>';
					}
					$data .= '</table>';
					echo $data;
					break;
				case '4':
					$object = new ProfesoresMateriasModel($link);
					$data = $arrayCabeceraTablas['profesores_materias'];
					$aulas = $object->getProfesoresMaterias3();
					if (count($aulas) > 0) {
						foreach ($aulas as $key) {
						    $data.= "<tr>
								<td>".$key['codigo']."</td>
								<td>".$key['asignatura']."</td>
								<td>".$key['semestre']."</td>
								<td>".$key['nombre']."</td>
								<td>".$key['apellido']."</td>
								<td> <button onclick='getProfesoresMateriasDetails(".$key['cedula'].",".$key['codigo'].")' class='btn btn-warning'>Editar</button> </td>
								<td> <button onclick='deleteProfesoresMaterias(".$key['cedula'].",".$key['codigo'].")' class='btn btn-danger'>Borrar</button> </td>
								</tr>";
						}
					}else {
						$data .= '<tr><td colspan="6">No se encontraron resultados</td></tr>';
					}
					$data .= '</table>';
					echo $data;
					break;
				default:
					# code...
					break;
			}
			break;

		case "update":
			switch ($_POST['selector_db']) {
				case '1':
					if (isset($_POST['nombre_aula']) && isset($_POST['capacidad']) && isset($_POST['id_aula']) && isset($_POST['id_tipo']) && isset($_POST['id_aula']) != "") {		
						error_log("entre aca"); 
					    $idAula = $_POST['id_aula'];
					    $nombreAula = $_POST['nombre_aula'];
					    $capacidad = $_POST['capacidad'];
					    $idTipo = $_POST['id_tipo'];
					    $obj = new AulasModel($link);			 
					    $obj->setAulas($idAula,$nombreAula,$capacidad,$idTipo);

					}
					break;
				case '2':
					if (isset($_POST['codigo']) && isset($_POST['asignatura']) && isset($_POST['semestre']) && isset($_POST['horas_academicas_totales']) && isset($_POST['horas_academicas_semanales']) && isset($_POST['id_tipo']) && isset($_POST['id_carrera'])) {	

						$codigo = $_POST['codigo'];
					    $asignatura = $_POST['asignatura'];
					    $semestre = $_POST['semestre'];
					    $horasAcademicasTotales = $_POST['horas_academicas_totales'];
					    $horasAcademicasSemanales = $_POST['horas_academicas_semanales'];
					 	$idTipo = $_POST['id_tipo'];
					 	$idCarrera = $_POST['id_carrera'];
					    $obj = new MateriasModel($link);			 
					    $obj->setMaterias($codigo,$asignatura,$semestre,$horasAcademicasTotales,
					    	$horasAcademicasSemanales,$idTipo,$idCarrera);
					}
					break;
				
				default:
					# code...
					break;
			}
			break;

		case "delete":
			switch ($_POST['selector_db']) {
				case '1':
					if (isset($_POST['id_aula']) && isset($_POST['id_aula']) != "") {
					    $idAula = $_POST['id_aula'];
					    $object = new AulasModel($link);
					    $object->deleteAulas($idAula);
					}
					break;
				case '2':
					if (isset($_POST['codigo']) && isset($_POST['codigo']) != "") {
					    $codigo = $_POST['codigo'];
					    $object = new MateriasModel($link);
					    $object->deleteMaterias($codigo);
					}
					break;
				default:
					# code...
					break;
			}

			break;

		case "details":
			switch ($_POST['selector_db']) {
				case '1':
					if (isset($_POST['id_aula']) && isset($_POST['id_aula']) != "") {
					    $idAula = $_POST['id_aula'];
					    $object = new AulasModel($link);
					   // $variable = $object->getAulas3($idAula);	 
					   // error_log(print_r($variable));
					    echo json_encode($object->getAulas3($idAula));
					}
					break;
				case '2':
					if (isset($_POST['codigo']) && isset($_POST['codigo']) != "") {
					    $codigo = $_POST['codigo'];
					    $object = new MateriasModel($link);
					    
					    //error_log(print_r($variable));
					    echo json_encode($object->getMaterias2($codigo));

					}
					break;
				default:
					# code...
					break;
			}
			break;		
	}
?>