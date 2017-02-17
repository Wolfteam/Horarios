<?php  
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

?>