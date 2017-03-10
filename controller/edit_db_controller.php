<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/AulasModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/MateriasModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresModel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/ProfesoresMateriasModel.php');
require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');		

$link = DBConnection::connection();

$arrayCabeceraTablas = [
    "aulas" => '<table class="table table-bordered  table-hover table-condensed">
    				<tr class="success">
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>Capacidad</th>
                        <th>Tipo</th>                              
                        <th>Editar</th>
                        <th>Borrar</th>
                    </tr>',
    "materias" => '<table class="table table-bordered  table-hover table-condensed">
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
	"profesores"=> '<table class="table table-bordered  table-hover table-condensed">
	                    <tr class="success">
	                        <th>No.</th>
	                        <th>Cedula</th>
	                        <th>Nombre</th>
	                        <th>Apellido</th>
	                        <th>Prioridad</th>                              
	                        <th>Editar</th>
	                        <th>Borrar</th>
	                    </tr>',
	"profesores_materias"=>'<table class="table table-bordered  table-hover table-condensed">
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

//Se debe cambiar las validacion isset y poner unas mas bonitas :D?
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
				if (isset($_POST['codigo']) && isset($_POST['asignatura']) && isset($_POST['id_semestre']) && isset($_POST['horas_academicas_totales']) && isset($_POST['horas_academicas_semanales']) && isset($_POST['id_tipo']) && isset($_POST['id_carrera'])) {

					$codigo = $_POST['codigo'];
				    $nombreMateria = $_POST['asignatura'];
				    $idSemestre = $_POST['id_semestre'];
				    $horasAcademicasTotales = $_POST['horas_academicas_totales'];
				    $horasAcademicasSemanales = $_POST['horas_academicas_semanales'];
				 	$idTipo = $_POST['id_tipo'];
				 	$idCarrera = $_POST['id_carrera'];
				    $object = new MateriasModel($link);
				    $object->createMaterias($codigo,$nombreMateria,$idSemestre,$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera);
				}
				break;
			case '3'://Crear profesores
				if (isset($_POST['cedula']) && isset($_POST['nombre_profesor']) && isset($_POST['apellido_profesor']) && isset($_POST['id_prioridad'])) {
					$cedula = $_POST['cedula'];
					$nombreProfesor = $_POST['nombre_profesor'];
				    $apellidoProfesor = $_POST['apellido_profesor'];
				 	$idPrioridad = $_POST['id_prioridad'];
				    $object = new ProfesoresModel($link);
				    $object->createProfesores($cedula,$nombreProfesor,$apellidoProfesor,$idPrioridad);
				}
			case '4'://Crear profesores_materias
				if(isset($_POST['cedula']) && isset($_POST['codigo'])){
					$cedula = $_POST['cedula'];
				    $codigo = $_POST['codigo'];
				    $object = new ProfesoresMateriasModel($link);
				    $object->createProfesorMaterias($cedula,$codigo);
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
					$number=0;
					foreach ($aulas as $key) {
						$number++;
						//Nota como usa el .= para concatener cada fila
					    $data.= "<tr>
							<td>".$number."</td>
							<td>".$key['nombre_aula']."</td>
							<td>".$key['capacidad']."</td>
							<td>".$key['nombre_tipo']."</td>
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
							<td>".$key['nombre_semestre']."</td>
							<td>".$key['horas_academicas_totales']."</td>
							<td>".$key['horas_academicas_semanales']."</td>
							<td>".$key['nombre_tipo']."</td>
							<td>".$key['nombre_carrera']."</td>
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
							<td>".$key['codigo_prioridad']."</td>
							<td> <button onclick='getDetails(".$key['cedula'].")' class='btn btn-warning'>Editar</button> </td>
							<td> <button onclick='deleteStuff(".$key['cedula'].")' class='btn btn-danger'>Borrar</button> </td>
							</tr>";
					}
				}else {
					$data .= '<tr><td colspan="6">No se encontraron profesores!</td></tr>';
				}
				$data .= '</table>';
				echo $data;
				break;
			case '4'://leer profesores_materias
				if (isset($_POST['mostrar']) && $_POST['mostrar']=="mostrar_materias") {
					$obj = new MateriasModel($link);
				    $result = $obj->getMaterias();
				    $data = "<option value='0'>Seleccione una materia</option>";
				    foreach ($result as $key) {
				    	$data.= "<option value=".$key['codigo'].">Semestre:".$key['nombre_semestre']." ".$key['asignatura']."</option>";
					}
					echo $data;   
				}else if (isset($_POST['mostrar']) && $_POST['mostrar']=="mostrar_profesores"){
					$obj = new ProfesoresModel($link);
				    $result = $obj->getProfesores();
				    $data = "<option value='0'>Seleccione un profesor</option>";
				    foreach ($result as $key) {
				    	$data.= "<option value=".$key['cedula'].">".$key['nombre']." ".$key['apellido']." Prioridad:".$key['id_prioridad']."</option>";
				    }
				    echo $data;
				}else{
					$object = new ProfesoresMateriasModel($link);
					$data = $arrayCabeceraTablas['profesores_materias'];
					$profesoresMaterias = $object->getProfesoresMaterias3();
					if (count($profesoresMaterias) > 0) {
						foreach ($profesoresMaterias as $key) {
						    $data.= "<tr>
								<td>".$key['codigo']."</td>
								<td>".$key['asignatura']."</td>
								<td>".$key['nombre_semestre']."</td>
								<td>".$key['nombre']."</td>
								<td>".$key['apellido']."</td>
								<td> <button onclick='getDetails(".$key['cedula'].",".$key['codigo'].")' class='btn btn-warning'>Editar</button> </td>
								<td> <button onclick='deleteStuff(".$key['cedula'].",".$key['codigo'].")' class='btn btn-danger'>Borrar</button> </td>
								</tr>";
						}
					}else {
						$data .= '<tr><td colspan="6">No se encontraron resultados</td></tr>';
					}
					$data .= '</table>';
					echo $data;
				}
				break;
			default:
				# code...
				break;
		}
		break;

	case "update":
		switch ($_POST['selector_db']) {
			case '1'://Actualizar aulas
				if (isset($_POST['nombre_aula']) && isset($_POST['capacidad']) && isset($_POST['id_aula']) && isset($_POST['id_tipo']) && isset($_POST['id_aula']) != "") {		

				    $idAula = $_POST['id_aula'];
				    $nombreAula = $_POST['nombre_aula'];
				    $capacidad = $_POST['capacidad'];
				    $idTipo = $_POST['id_tipo'];
				    $obj = new AulasModel($link);			 
				    $obj->setAulas($idAula,$nombreAula,$capacidad,$idTipo);

				}
				break;
			case '2'://Actualizar materias
				if (isset($_POST['codigo']) && isset($_POST['codigo_nuevo']) && isset($_POST['asignatura']) && isset($_POST['id_semestre']) && isset($_POST['horas_academicas_totales']) && isset($_POST['horas_academicas_semanales']) && isset($_POST['id_tipo']) && isset($_POST['id_carrera'])) {	

					$codigo = $_POST['codigo'];
					$codigoNuevo = $_POST['codigo_nuevo'];
				    $asignatura = $_POST['asignatura'];
				    $idSemestre = $_POST['id_semestre'];
				    $horasAcademicasTotales = $_POST['horas_academicas_totales'];
				    $horasAcademicasSemanales = $_POST['horas_academicas_semanales'];
				 	$idTipo = $_POST['id_tipo'];
				 	$idCarrera = $_POST['id_carrera'];
				    $obj = new MateriasModel($link);			 
				    $obj->setMaterias($codigo,$codigoNuevo,$asignatura,$idSemestre,
				    	$horasAcademicasTotales,$horasAcademicasSemanales,$idTipo,$idCarrera);
				}
				break;
			case '3'://Actualizar profesor
				if (isset($_POST['nombre_profesor']) && isset($_POST['apellido_profesor']) && isset($_POST['id_prioridad']) && isset($_POST['cedula']) && isset($_POST['cedula_nueva']) && isset($_POST['cedula_nueva']) != ""){	 

				    $cedula = $_POST['cedula'];
				    $cedulaNueva = $_POST['cedula_nueva'];
					$nombreProfesor = $_POST['nombre_profesor'];
				    $apellidoProfesor = $_POST['apellido_profesor'];
				 	$idPrioridad = $_POST['id_prioridad'];
				    $object = new ProfesoresModel($link);			 
				    $var = $object->setProfesores($cedula,$cedulaNueva,$nombreProfesor,$apellidoProfesor,$idPrioridad);
				}
				break;
			case '4'://Actualizar Profesores_Materias
				if (isset($_POST['cedula_nueva']) !="" && isset($_POST['codigo_nuevo']) !="" && isset($_POST['codigo']) !="" && isset($_POST['cedula']) !="") {		 
					$cedulaNueva = $_POST['cedula_nueva'];
				    $codigoNuevo = $_POST['codigo_nuevo'];
				    $codigo = $_POST['codigo'];
				    $cedula = $_POST['cedula'];
				    $obj = new ProfesoresMateriasModel($link);			 
				    $obj->setProfesoresMaterias($cedula,$codigo,$cedulaNueva,$codigoNuevo);
				}
				break;
			default:
				# code...
				break;
		}
		break;

	case "delete":
		switch ($_POST['selector_db']) {
			case '1'://Borrar aulas
				if (isset($_POST['id_aula']) && isset($_POST['id_aula']) != "") {
				    $idAula = $_POST['id_aula'];
				    $object = new AulasModel($link);
				    $object->deleteAulas($idAula);
				}
				break;
			case '2'://Borrar materias
				if (isset($_POST['codigo']) && isset($_POST['codigo']) != "") {
				    $codigo = $_POST['codigo'];
				    $object = new MateriasModel($link);
				    $object->deleteMaterias($codigo);
				}
				break;

			case '3'://Borrar profesores
				if (isset($_POST['cedula']) && isset($_POST['cedula']) != "") {
				    $cedula = $_POST['cedula'];
				    $object = new ProfesoresModel($link);
				    $object->deleteProfesores($cedula);
				}				
				break;
			case '4'://Borrar profesores_materias
				if (isset($_POST['cedula'])!="" && isset($_POST['codigo'])!="") {
				    $cedula = $_POST['cedula'];
				    $codigo = $_POST['codigo'];
				    $object = new ProfesoresMateriasModel($link);
				    $object->deleteProfesorMaterias($cedula,$codigo);
				}
				break;
			default:
				# code...
				break;
		}
		break;

	case "details":
		switch ($_POST['selector_db']) {
			case '1'://Detalles del aula
				if (isset($_POST['id_aula']) && isset($_POST['id_aula']) != "") {
				    $idAula = $_POST['id_aula'];
				    $object = new AulasModel($link);
				    echo json_encode($object->getAulas3($idAula));
				}
				break;
			case '2'://Detalles de la materia
				if (isset($_POST['codigo']) && isset($_POST['codigo']) != "") {
				    $codigo = $_POST['codigo'];
				    $object = new MateriasModel($link);
				    echo json_encode($object->getMaterias2($codigo));

				}
				break;
			case '3'://Detalles del profesor
				if (isset($_POST['cedula']) && isset($_POST['cedula']) != "") {
				    $cedula = $_POST['cedula'];
				    $object = new ProfesoresModel($link);
				    echo json_encode($object->getProfesores3($cedula));
				}
				break;
			case '4'://Detalles de profesor_materias
				//Detalles no es necesario puesto que en el read de este caso
				//se llenan los selector_update
				break;
			default:
				# code...
				break;
		}
		break;		
}
?>