<?php  
	require($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/DBConnection.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/PHPExcel.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/Horarios/Model/HomeModel.php');
	$link = DBConnection::connection();

	$objPHPExcel = new PHPExcel();

//Propiedades a ser asignadas al documento
	$objPHPExcel->getProperties()->setCreator("Efrain Bastidas") // Nombre del autor
    ->setLastModifiedBy("Efrain Bastidas") //Ultimo usuario que lo modificó
    ->setTitle("Planificación Académica Departamento de Ingeniería de Sistemas") // Titulo
    ->setSubject("Planificación Académica Departamento de Ingeniería de Sistemas") //Asunto
    ->setDescription("Horarios por semestre, con su profesor asignado") //Descripción
    ->setKeywords("horarios materias profesores") //Etiquetas
    ->setCategory("Planificacion académica"); //Categoria
//Estilos
	$estiloTitulo = array(
	    'font' => array(
	        'name'      => 'Verdana',
	        'bold'      => true,
	        'italic'    => false,
	        'strike'    => false,
	        'size' =>14,
	        'color'     => array(
	            'rgb' => 'FFFFFF'
	        	)
	    ),
	    'fill' => array(
	      'type'  => PHPExcel_Style_Fill::FILL_SOLID,
	      'color' => array(
	            'argb' => '#000000'
	            )
	  	),
	    'borders' => array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_NONE
	        	)
	   	),
	    'alignment' => array(
	        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'rotation' => 0,
	        'wrap' => TRUE
	    )
	);
 
	$estiloTituloColumnas = array(
	    'font' => array(
	        'name'  => 'Arial',
	        'bold'  => true,
	        'color' => array(
	            'rgb' => 'FFFFFF'
	        )
	    ),
	    'fill' => array(
	      'type'  => PHPExcel_Style_Fill::FILL_SOLID,
	      'color' => array(
	            'argb' => '#000000')
	  	),
	    'borders' => array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
	            'color' => array(
	                'rgb' => '#000000'
	            )
	        )
	    ),
	    'alignment' =>  array(
	        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'wrap'      => TRUE
	    )
	);
	 
	$estiloInformacion = new PHPExcel_Style();
	$estiloInformacion->applyFromArray( array(
	    'font' => array(
	        'name'  => 'Arial',
	        'color' => array(
	            'rgb' => '000000'
	        )
	    ),
	    'fill' => array(
	  		'type'  => PHPExcel_Style_Fill::FILL_SOLID,
	 	 	'color' => array(
	            'argb' => 'FFFFFF')
	  	),
	    'borders' => array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_THIN ,
	      		'color' => array(
	              'rgb' => '000000'
	            )
	        )
	    ),
	    'alignment' =>  array(
	        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'wrap'      => TRUE
	    )
	));





	
	$filaTitulo = 1;
	$filaTituloColumna = 2;
	$limiteInferior = 3;
	$limiteInferiorFijo =3;
	$periodoAcademico = "2016-2";
	$tituloReporte = "Planificación Académica Departamento de Ingeniería de Sistemas ".$periodoAcademico." SEMESTRE III";

    for ($semestre=3; $semestre <=14 ; $semestre++) { 
    	switch ($semestre) {
    		case 10:
    			$semestre = "Electiva Cod-41";
    			break;
    		case 11:
    			$semestre = "Electiva Cod-43";
    			break;
    		case 12:
    			$semestre = "Electiva Cod-44";
    			break;	
    		case 13:
    			$semestre = "Electiva Cod-46";
    			break;    			
    		case 14:
    			$semestre = "Otros";
    			break;      		
    		default:
    			# code...
    			break;
    	}
    	error_log("Estoy en el semestre:".$semestre.", filaTitulo esta en:".$filaTitulo.",filaTituloColumna esta en:".$filaTituloColumna." y limiteInferior esta en:".$limiteInferior);
	    

		$titulosColumnas = array( ' Codigo ', ' Asignatura ', ' Profesor ', ' Seccion ',' Dia ',' Horario ',' Aula ', ' Cantidad Alumnos ');

			// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
		$objPHPExcel->setActiveSheetIndex(0)
		->mergeCells("A".$filaTitulo.":"."H".$filaTitulo);
    	    // Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(0)
	    ->setCellValue("A".$filaTitulo,$tituloReporte) // Titulo del reporte
	    ->setCellValue("A".$filaTituloColumna,  $titulosColumnas[0])  //Titulo de las columnas
		->setCellValue("B".$filaTituloColumna,  $titulosColumnas[1])
	    ->setCellValue("C".$filaTituloColumna,  $titulosColumnas[2])
	    ->setCellValue("D".$filaTituloColumna,  $titulosColumnas[3])
	    ->setCellValue("E".$filaTituloColumna,  $titulosColumnas[4])
	    ->setCellValue("F".$filaTituloColumna,  $titulosColumnas[5])
	    ->setCellValue("G".$filaTituloColumna,  $titulosColumnas[6])
	    ->setCellValue("H".$filaTituloColumna,  $titulosColumnas[7]);

		$obj = new HomeModel($link);
		$materias = $obj->getMaterias();
		foreach ($materias as $key) {
			$cantidadSecciones = $obj->getCantidadSecciones($key['Codigo']);

			if (($cantidadSecciones>0) && ($key['Semestre'])==$semestre) {
				$limiteSuperior = ($limiteInferior + $cantidadSecciones)-1;

			    //Se unen Ax-Ay y Bx-By que corresponden al codigo y nombre asignatura
			    //de acuerdo al numero de secciones disponibles
				$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells("A".$limiteInferior.":A".$limiteSuperior)
				->mergeCells("B".$limiteInferior.":B".$limiteSuperior);
				//Se colocan los valores correspondientes en sus respectivas casillas
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$limiteInferior, $key['Codigo'])
				->setCellValue('B'.$limiteInferior, $key['Asignatura']);

				$secciones = $obj->getSecciones($key['Codigo']);

				foreach ($secciones as $key2) {
					$horariosDisponibles = $obj->getCantidadHorariosVista($key2['ID']);

					$horarios = $obj->getHorariosVista($key2['ID']);
					error_log(print_r($horarios,true));
					if ($horariosDisponibles>0) {				
					    $objPHPExcel->setActiveSheetIndex(0)
					    ->setCellValue('C'.$limiteInferior, $horarios[0]['Nombre']." ".$horarios[0]['Apellido'])
					    ->setCellValue('D'.$limiteInferior, $horarios[0]['Numero_Seccion']);

					    $datoDia = "";
					    $datoHora = "";
					    $datoAula = "";
					    for ($i=0; $i<$horariosDisponibles; $i++) { 
					    	$datoDia .= $horarios[$i]['Dia']."\n";
					    	$datoHora .= $horarios[$i]['Hora_inicio']." a ".$horarios[$i]['Hora_fin']."\n";
					    	$datoAula .= $horarios[$i]['Nombre_Aula']."\n";
					    }
					    $objPHPExcel->setActiveSheetIndex(0)
					    ->setCellValue('E'.$limiteInferior,$datoDia)
					    ->setCellValue('F'.$limiteInferior,$datoHora)
					    ->setCellValue('G'.$limiteInferior,$datoAula)
					    ->setCellValue('H'.$limiteInferior,$key2['Cantidad_Alumnos']);
					    $limiteInferior++;
					   // if ($limiteInferior==$limiteSuperior) {
						//	$limiteInferior++;
						//}
					}else{
						$limiteInferior++;
					}
				}
			}
			//$limiteInferior++;
		}

		$objPHPExcel->getActiveSheet()->getStyle("A".$filaTitulo.":H".$filaTitulo)->applyFromArray($estiloTitulo);
		$objPHPExcel->getActiveSheet()->getStyle("A".$filaTituloColumna.":H".$filaTituloColumna)->applyFromArray($estiloTituloColumnas);
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A".$limiteInferiorFijo.":H".($limiteInferior-1));


		$filaTitulo = $limiteInferior + 1;
		$filaTituloColumna = $limiteInferior +2;
		$limiteInferior += 3;
		$limiteInferiorFijo = $limiteInferior;
		error_log("filaTitulo quedo en:".$filaTitulo.",filaTituloColumna quedo en:".$filaTituloColumna." y limiteInferior quedo en:".$limiteInferior);
		switch ($semestre) {
			case 3:
				$tituloReporte = "Planificación Académica Departamento de Ingeniería de Sistemas ".$periodoAcademico." SEMESTRE IV";
				break;
			case 4:
				$tituloReporte = "Planificación Académica Departamento de Ingeniería de Sistemas ".$periodoAcademico." SEMESTRE V";
				break;
			case 5:
				$tituloReporte = "Planificación Académica Departamento de Ingeniería de Sistemas ".$periodoAcademico." SEMESTRE VI";
				break;
			case 6:
				$tituloReporte ="Planificación Académica Departamento de Ingeniería de Sistemas ".$periodoAcademico." SEMESTRE VII";
				break;
			case 7:
				$tituloReporte="Planificación Académica Departamento de Ingeniería de Sistemas ".$periodoAcademico." SEMESTRE VIII";
				break;
			case 8:
				$tituloReporte = "Planificación Académica Departamento de Ingeniería de Sistemas ".$periodoAcademico." SEMESTRE IX";
				break;	
			case 9:
				$tituloReporte = "Planificación Académica DIS ".$periodoAcademico." Electivas Maquinas Electricas Cod-41";
				break;
			case 'Electiva Cod-41':
				$semestre=10;
				$tituloReporte = "Planificación Académica DIS ".$periodoAcademico." Electivas Controles Industriales Cod-43";
				break;
			case 'Electiva Cod-43':
				$semestre=11;
				$tituloReporte = "Planificación Académica DIS ".$periodoAcademico." Electivas Sistemas de Comunicacion Cod-44";
				break;
			case 'Electiva Cod-44':
				$semestre=12;
				$tituloReporte = "Planificación Académica DIS ".$periodoAcademico." Electivas de Computacion Cod-46";
				break;
			case 'Electiva Cod-46':
				$semestre=13;
				$tituloReporte = "Planificación Académica DIS ".$periodoAcademico." Asignaturas del DIS a otras carreras";
				break;																				
			default:
				$semestre =14;
				break;
		}
    }


 	for($i = 'A'; $i <= 'H'; $i++){
    	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
	}

		// Se asigna el nombre a la hoja
	$objPHPExcel->getActiveSheet()->setTitle('Horario_1');
	 
	// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
	$objPHPExcel->setActiveSheetIndex(0);
	 
	// Inmovilizar paneles
	//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
	//Esto sirve para que una fila se vea siempre, si quieres que se vea desde la fila 0 hasta la 4
	//el rango debe ir (0,5)
	//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

	// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Reportedealumnos.xlsx"');
	header('Cache-Control: max-age=0');
	 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
	/*
	}
	else{
	    print_r('No hay resultados para mostrar');
	}
	*/
?>
