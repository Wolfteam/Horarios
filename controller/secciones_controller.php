<?php 
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/MateriasModel.php');	
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/SeccionesModel.php'); 

	$link = DBConnection::connection();
	$materias = new MateriasModel($link);
	$secciones = new SeccionesModel($link);

	if (isset($_POST['operacion']) && $_POST['operacion']=="materias") {
	    $resultado = $materias->getMaterias();
	    $data = "<option value='0'>Seleccione una materia</option>";
	    foreach ($resultado as $key) {
	    	$data.= "<option value=".$key['codigo'].">Semestre:".$key['semestre']." ".$key['asignatura']."</option>";
	    }
	    echo $data;
	}

	if (isset($_POST['operacion']) && $_POST['operacion']=="create" && isset($_POST['codigo']) && isset($_POST['cantidad_alumnos']) && isset($_POST['cantidad_secciones']) && $_POST['codigo']!="" && $_POST['cantidad_alumnos']!="" && $_POST['cantidad_secciones']!="") {
	    $codigo = $_POST['codigo'];
	    $cantidadSecciones = $_POST['cantidad_secciones'];
	    $cantidadAlumnos = $_POST['cantidad_alumnos'];
	    $secciones->createSecciones($codigo,$cantidadSecciones,$cantidadAlumnos);
	}

	if (isset($_POST['operacion']) && $_POST['operacion']=="read") {
		$data = '<table class="table table-bordered table-hover table-condensed">
		                    <tr class="success">
		                        <th >Codigo</th>
		                        <th >Semestre</th>
		                        <th >Asignatura</th>    
		                        <th >Numero Seccion</th>
		                        <th >Cantidad Alumnos</th>                            
		                        <th >Borrar</th>
		                    </tr>';
		$dato = $secciones->getSecciones();
		if (count($dato) > 0) {
			foreach ($dato as $key) {
			    $data.= "<tr>
					<td>".$key['codigo']."</td>
					<td>".$key['semestre']."</td>
					<td>".$key['asignatura']."</td>
					<td>".$key['numero_seccion']."</td>
					<td>".$key['cantidad_alumnos']."</td>
					<td> <button onclick='deleteStuff(".$key['codigo'].",".$key['numero_seccion'].")' class='btn btn-danger'>Borrar</button> </td>
					</tr>";
			}
		} else {
			$data .= '<tr><td colspan="6">No se encontraron secciones!</td></tr>';
		}
		$data .= '</table>';
		echo $data;
	}

	if (isset($_POST['operacion']) && $_POST['operacion']=="delete" && isset($_POST['codigo']) && isset($_POST['numero_seccion']) && $_POST['codigo']!="" && $_POST['numero_seccion']!="") {
	    $codigo = $_POST['codigo'];
	    $numeroSeccion = $_POST['numero_seccion'];
	    $secciones->deleteSecciones($codigo,$numeroSeccion);
	}
?>