<?php    
 error_reporting(0);
 session_start();
 //if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}
 
 define("CLASS_PATH", "../../../class");
 define("FONT_PATH", "../../../fonts");

 include("grafico/lib/class/pData.class.php"); 
 include("grafico/lib/class/pDraw.class.php"); 
 include("grafico/lib/class/pImage.class.php"); 
 include("grafico/lib/class/pPie.class.php");
 /*
 /*
 header('Pragma: public');
 header('Cache-Control: max-age=86400');
 header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
 header('Content-Type: image/png');
 */
 //error_reporting(E_ALL);
 //ini_set('display_errors', 1);
 //error_reporting(0);
 require_once("include/dbconection.php");
 require_once("include/definiciones.php");
 require_once("include/funciones.php");  
 require_once("grafico/exportar.php");
 $path = 'grafico/lib/';  
 $pathExportacion = 'grafico/'; 
 
 //colores de escala
 $arrColoresEscala = DB_ESCALA_obtenerDatosEscala(1);
 $arrColores = array();
 for($i=0;$i<count($arrColoresEscala);$i++){
   $arrColor = explode(",",$arrColoresEscala[$i]['color']);	
   $arrColores[$arrColoresEscala[$i]['valor']] = array (
      'R'=>$arrColor[0]
     ,'G'=>$arrColor[1]
     ,'B'=>$arrColor[2]
   );
 }

 $colorFondoSatisfecho 	 		= array('R'=>207,'G'=>207,'B'=>245, "DashR"=>193, "DashG"=>172, "DashB"=>237);//azul (para area achurada) 
 $colorFondoInsatisfecho 		= array('R'=>255,'G'=>184,'B'=>184, "DashR"=>193, "DashG"=>172, "DashB"=>237);//rojo (para area achurada) 
  
 //valores de mantenedor 
 $colorMedia			 		= array('R'=>146,'G'=>208,'B'=>80);//verde
 $colorMediana			 		= array('R'=>255,'G'=>0,'B'=>0);//rojo   
 $colorModa				 		= array('R'=>0,'G'=>112,'B'=>192);//azul 
 
 $colorPriorizacion		 		= array('R'=>91,'G'=>155,'B'=>213);//celeste 

 $colorPVariable   		 		= array('R'=>255,'G'=>0,'B'=>0);//rojo 
 $colorPInstitucion		 		= array('R'=>0,'G'=>0,'B'=>204);//azul
   
 $colorLineaDispersion   		= array('R'=>112,'G'=>173,'B'=>71);//verde
 $colorDefectoPromedioPregunta  = array('R'=>255,'G'=>204,'B'=>50);//amarillo
 //valores de mantenedor
 $idCliente     				= (isset($_SESSION["id_cliente"]))?$_SESSION["id_cliente"]:$_GET["id_cliente"]; 

 $opcionGrafico 				= $_GET['grafico'];
 $idCr 				            = (isset($_GET['select']))?$_GET['select']:0;
 
 $opcionSalida 				    = (isset($_GET['salida']))?$_GET['salida']:0; 
 $identificador 				= (isset($_GET['ident']))?$_GET['ident']:''; 
  
 $idEstamento 					= (isset($_GET['id_estamento']))?$_GET['id_estamento']:''; 
 $idAntiguedad 					= (isset($_GET['id_antiguedad']))?$_GET['id_antiguedad']:'';
 
 $idSexo 						= (isset($_GET['id_sexo']))?$_GET['id_sexo']:''; 
 $idCjuridica 					= (isset($_GET['id_cjuridica']))?$_GET['id_cjuridica']:''; 
 
 $arrFechasAntiguedad			= array();
  
 if(!empty($idAntiguedad)){
   $arrFechasAntiguedad = DB_ANTIGUEDAD_obtenerFechasLimite($idAntiguedad);
 }
 
 $iAltoGraficoInv   = 390;
 $iAnchoGraficoInv  = 750; 

 $iAltoGraficoInv2   = 800;
 $iAnchoGraficoInv2  = 700; 

 $iAltoGraficoPvpi  = 450;
 $iAnchoGraficoPvpi = 700;

 $iAltoGraficoTc    = 460;
 $iAnchoGraficoTc   = 700;

 $iAltoGraficoPr    = 400;
 $iAnchoGraficoPr   = 700;

 $iAltoGraficoDsg   = 350;
 $iAnchoGraficoDsg  = 700;

 $iAltoGraficoDsv   = 630;
 $iAnchoGraficoDsv  = 700; 

 $iAltoGraficoDscr  = 530;
 $iAnchoGraficoDscr = 750; 

 $iAltoGraficoPcrpi  = 350;
 $iAnchoGraficoPcrpi = 700;

 $iAltoGraficoIsta  = 350;
 $iAnchoGraficoIsta = 700; 

 switch($opcionGrafico){
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	case 'ISTA':
		//Stacked
	  	//$arrResultados 		 = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
		$arrParameters = array();
		$arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);
		if(!empty($idCr)){
		  $arrParameters['id_cr'] = $idCr; 	
		}
	  	$arrClasificacion    = DB_RESULT_obtenerClasificacionRiesgo($idCliente,$arrParameters); 
	  	$arrResultadosV 	 = DB_NEGOCIO_obtenerTodasDimensionesIstas($idCliente);
	  	//$arrClasificacion  = array();
	  	$arrPromUsuario 	 = array();
	  	$arrClasificacionUsr = array();
	    $arrRango 			 = DB_RANGO_obtenerRango($idCliente);	  
	    $arrLeyenda 		 = array();
   
		$arrColoresRango = array();		  
		/* para que la escala se muestre en orden ascendente */
	    foreach($arrRango as $llave=>$valorRango){
		  $arrColor = explode(",",$valorRango['color']);
		  $arrLeyenda[$valorRango['nombre_rango']] = array(
		    'color'=> array(
		        'R' => $arrColor[0]
		       ,'G' => $arrColor[1]
		       ,'B' => $arrColor[2]
			)
			,'Ticks'=>0
			,'Weight'=>0
			,'mostrar'=>true
			,'isDrawable'=>true
			,"Description"=>$valorRango['nombre_rango']
		  );
		  $arrColoresRango[$valorRango['valor']] = array(
		     'R' => $arrColor[0]
		    ,'G' => $arrColor[1]
		    ,'B' => $arrColor[2]
		  ); 		  
		}
	    /* para que la escala se muestre en orden ascendente */

	      $arrValores = array(
			 "A"=>array()
			,"M" =>array()			  
			,"B"=>array()		  			  
		  );
	      $arrValoresP = array(
			 "A"=>array()
			,"M" =>array()			  
			,"B"=>array()
		  );		  
		 //$arrResultadosV = DB_NEGOCIO_btenerVariables();
		 $total = 0;
		 $a = 0;
		 $m = 0;
		 $b = 0;
		 //var_dump($arrClasificacion);
		 /*
		 echo "<hr><pre>";
		 var_dump($arrResultadosV);
		 echo "</pre><hr>";
		 */		 
		 //exit;
		 foreach($arrResultadosV as $row){
		   //$arrVariablesEje[] = $row['dimension'];
		   $arrVariablesEje[] = $row['numeracion'];
		   //por cada una de los cr hacer un array
		   //en los valores 2 hacer un doble array
		   $total = 0;
		   $a = isset($arrClasificacion[$row['id_dimension']]['A'])?$arrClasificacion[$row['id_dimension']]['A']:0;
		   $total+=isset($arrClasificacion[$row['id_dimension']]['A'])?$arrClasificacion[$row['id_dimension']]['A']:0;
		   $m  = isset($arrClasificacion[$row['id_dimension']]['M'])?$arrClasificacion[$row['id_dimension']]['M']:0;		   		   
		   $total+=isset($arrClasificacion[$row['id_dimension']]['M'])?$arrClasificacion[$row['id_dimension']]['M']:0;
		   $b = isset($arrClasificacion[$row['id_dimension']]['B'])?$arrClasificacion[$row['id_dimension']]['B']:0;		   
		   $total+=isset($arrClasificacion[$row['id_dimension']]['B'])?$arrClasificacion[$row['id_dimension']]['B']:0;

		   
		   $a = ($total!=0)?$a/$total:0;
		   $m  = ($total!=0)?$m/$total:0;
		   $b = ($total!=0)?$b/$total:0;
		   
		   $arrValores['A'][]   = isset($arrClasificacion[$row['id_dimension']]['A'])?$arrClasificacion[$row['id_dimension']]['A']:VOID;
		   $arrValores['M'][]   = isset($arrClasificacion[$row['id_dimension']]['M'])?$arrClasificacion[$row['id_dimension']]['M']:VOID;
		   $arrValores['B'][]   = isset($arrClasificacion[$row['id_dimension']]['B'])?$arrClasificacion[$row['id_dimension']]['B']:VOID;

		   $arrValoresP['A'][]  = empty($a)? VOID : round($a*100,2);
		   $arrValoresP['M'][]  = empty($m)? VOID : round($m*100,2);
		   $arrValoresP['B'][]  = empty($b)? VOID : round($b*100,2);
		 }
		 $MyData = new pData();		
		 $MyData->addPoints($arrValoresP["A"],"Riesgo Alto",$arrColoresRango['A'],true,false,$arrValoresP["A"],$arrLeyenda); 
		 $MyData->addPoints($arrValoresP["M"],"Riesgo Medio",$arrColoresRango['M'],true,false,$arrValoresP["M"],$arrLeyenda);
		 $MyData->addPoints($arrValoresP["B"],"Riesgo Bajo",$arrColoresRango['B'],true,false,$arrValoresP["B"],$arrLeyenda); 

		 /*		  
	  	 echo "<pre>";
	  	 var_dump($arrValoresP);
	  	 echo "</pre>";		
		 exit;				  
		 */	  

		 $MyData->setAxisName(0,"Porcentaje de Personas"); 
		 //$MyData->addPoints(array("Variable1 bla","Variable2  bla","Variable3  bla","Variable4 bla","Variable5 bla","Variable6 bla"),"Labels"); 
		 $MyData->addPoints($arrVariablesEje,"Labels"); 
		 $MyData->setSerieDescription("Labels","Dimensiones"); 
		 $MyData->setAbscissa("Labels");
		 
  	    ////////////////// EXPORTAR DATOS /////////////////////////////
	    if(isset($_GET['datos'])){
	      $arrTitulos  = array("Dimensiones",'Riesgo Alto','Riesgo Medio','Riesgo Bajo');
	      $arrColumnas = array(
	        $arrVariablesEje,
	        $arrValoresP['A'],		
	        $arrValoresP['M'],	        
	        $arrValoresP['B'],
	        
	      );
	      renderizarExcel($arrColumnas,$arrTitulos);	   
	    }
	    ////////////////// EXPORTAR DATOS /////////////////////////////			 
		 //#FF0000
		 //rojo
		 //#0000CC
		 //blue
		 //#FFFFFF
		 //blanco
		 $myPicture = new pImage($iAnchoGraficoIsta,$iAltoGraficoIsta,$MyData); 		 
		 //if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"]))
		  //$myPicture->dumpImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");
		 $myPicture->initialiseImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");
	     $myPicture->drawRectangle(0,0,$iAnchoGraficoIsta-1,$iAltoGraficoIsta-1,array("R"=>0,"G"=>0,"B"=>0));		
		 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
		 //$myPicture->drawFilledRectangle(0,0,700,630,$Settings); 
		 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50); 
		 
		 //$myPicture->drawGradientArea(0,0,700,630,DIRECTION_VERTICAL,$Settings); 
		 
		 //$myPicture->drawGradientArea(1,65,$iAnchoGraficoDsv-2,$iAltoGraficoDsv-3,DIRECTION_VERTICAL,array("StartR"=>255,"StartG"=>255,"StartB"=>255,"EndR"=>248,"EndG"=>165,"EndB"=>95,"Alpha"=>100));
		 //$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80)); 
		 //$myPicture->drawRectangle(0,0,699,629,array("R"=>0,"G"=>0,"B"=>0)); 
		 //$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>6)); 
		 //$myPicture->drawText(10,13,"Psicus",array("R"=>255,"G"=>255,"B"=>255)); 
		 $myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>8)); 
		 //$myPicture->drawText(340,25,"Porcentaje de Distribución de satisfacción por variable",array("FontSize"=>13,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE)); 
		
		 $myPicture->setGraphArea(70,30,$iAnchoGraficoIsta-50,$iAltoGraficoIsta-60); 
		 //$myPicture->drawFilledRectangle(60,30,650,400,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
		 
		 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
		 $myPicture->drawScale(array(
		    "DrawSubTicks"=>FALSE
		   ,"Mode"=>SCALE_MODE_MANUAL 
		   //,'LabelRotation'=>70
		   ,'RemoveYAxis'=>false
		   ,'EspacioLabelLineaX'=>6			
		   ,'EspacioLabelLineaY'=>5		 
		   ,'OuterTickWidth' =>2
	   	   ,"ManualScale"=>$AxisBoundaries		   
		   ,"GridR"=>180
		   ,"GridG"=>180
		   ,"GridB"=>180
		   //,'XMargin'=>0
		   //,'YMargin'=>0		   
		 )); 
		 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10)); 
		 $myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>5));
		 $myPicture->drawStackedBarChart(array(
		    "RecordImageMap"=>TRUE
		   ,"DisplayValues"=>FALSE
		   ,"DisplayColor"=>DISPLAY_AUTO	   		   
		   ,"Rounded"=>FALSE
		   ,"Surrounding"=>60
		   ,"DisplayOrientation"=>ORIENTATION_HORIZONTAL 
		   ,"ForzarValores"=>true
		  ));
		 $myPicture->setShadow(FALSE); 
		 $myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>7));		  
		 $myPicture->drawLegend(250,330,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));  
		 //$myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawStackedBarChart.png");
	 	 //if(isset($_GET['archivo'])){
	     //$myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   	 //  exit;
	 	 //}		 
		 if(isset($_GET["ImageMap"])){
		   $myPicture->dumpImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");    	
		 } else {
		   	
		   if($opcionSalida=='img'){
	   	     $myPicture->render($pathExportacion."ISTA_".$identificador.".png");		   		
		     $myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawStackedBarChart.png");
		     exit;
		   }elseif($opcionSalida=='nombre'){
	   	     $myPicture->render($pathExportacion."ISTA_".$identificador.".png");
	   	     echo "ISTA_".$identificador.'.png';
	   	     exit;		   	
		   }
	
		 }
		break;		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
 	case 'DSCR':
		//Stacked
	  	//$arrResultados 		 = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
		$arrParameters = array();
		$arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);
		if(!empty($idCr)){
		  $arrParameters['id_cr'] = $idCr; 	
		}	  			
	  	$arrClasificacion    = DB_RESULT_obtenerClasificacionCrEscala($idCliente,$arrParameters); 
		/* echo "<pre>";
		echo VOID."void";		
	  	var_dump($arrClasificacion);
	  	echo "</pre>";		
		//exit; */
	  	$arrResultadosV 	 = DB_CR_obtenerTodosCr($idCliente,1);
	  	//$arrClasificacion  = array();
	  	$arrPromUsuario 	 = array();
	  	$arrClasificacionUsr = array();
	    $arrEscala = DB_ESCALA_obtenerDatosEscala(1);	  
	    $arrLeyenda = array();
		  
		 /* para que la escala se muestre en orden ascendente */
	    foreach($arrEscala as $llave=>$valorEscala){
		  $arrColor = explode(",",$valorEscala['color']);
		  $arrLeyenda[$valorEscala['nombre_escala']] = array(
		    'color'=> array(
		        'R' => $arrColor[0]
		       ,'G' => $arrColor[1]
		       ,'B' => $arrColor[2]
			)
			,'Ticks'=>0
			,'Weight'=>0
			,'mostrar'=>true
			,'isDrawable'=>true
			,"Description"=>$valorEscala['nombre_escala']
		  );
		}
	    /* para que la escala se muestre en orden ascendente */

	      $arrValores = array(
			 "MI"=>array()
			,"I" =>array()			  
			,"NI"=>array()
			,"NI1"=>array()
			,"NI2"=>array()			
			,"S" =>array()
			,"MS"=>array()			  			  			  
		  );
	      $arrValoresP = array(
			 "MI"=>array()
			,"I" =>array()			  
			,"NI"=>array()
			,"NI1"=>array()
			,"NI2"=>array()			
			,"S" =>array()
			,"MS"=>array()			  			  			  
		  );		  
		 //$arrResultadosV = DB_NEGOCIO_btenerVariables();
		 $total = 0;
		 $mi = 0;
		 $i = 0;
		 $ni = 0;
		 $s = 0;
		 $ms = 0;
		 //var_dump($arrClasificacion);
		 /*
		 echo "<hr><pre>";
		 var_dump($arrResultadosV);
		 echo "</pre><hr>";
		 */		 
		 //exit;
		 foreach($arrResultadosV as $row){
		   $arrVariablesEje[] = $row['nombre'];
		   //por cada una de los cr hacer un array
		   //en los valores 2 hacer un doble array
		   $total = 0;
		   $mi = isset($arrClasificacion[$row['id']]['0'])?$arrClasificacion[$row['id']]['0']:0;
		   $total+=isset($arrClasificacion[$row['id']]['0'])?$arrClasificacion[$row['id']]['0']:0;
		   $i  = isset($arrClasificacion[$row['id']]['1'])?$arrClasificacion[$row['id']]['1']:0;		   		   
		   $total+=isset($arrClasificacion[$row['id']]['1'])?$arrClasificacion[$row['id']]['1']:0;
		   $ni = isset($arrClasificacion[$row['id']]['2'])?$arrClasificacion[$row['id']]['2']:0;		   
		   $total+=isset($arrClasificacion[$row['id']]['2'])?$arrClasificacion[$row['id']]['2']:0;
		   $s  = isset($arrClasificacion[$row['id']]['3'])?$arrClasificacion[$row['id']]['3']:0;		   
		   $total+=isset($arrClasificacion[$row['id']]['3'])?$arrClasificacion[$row['id']]['3']:0;
		   $ms = isset($arrClasificacion[$row['id']]['4'])?$arrClasificacion[$row['id']]['4']:0;		   
		   $total+=isset($arrClasificacion[$row['id']]['4'])?$arrClasificacion[$row['id']]['4']:0;		   		   		   		   		   
		   
		   $mi = ($total!=0)?$mi/$total:0;
		   $i  = ($total!=0)?$i/$total:0;
		   $ni = ($total!=0)?$ni/$total:0;
		   $s  = ($total!=0)?$s/$total:0;
		   $ms = ($total!=0)?$ms/$total:0;		   		   		   
		   
		   $arrValores['MI'][]  = isset($arrClasificacion[$row['id']]['0'])?$arrClasificacion[$row['id']]['0']*-1:VOID;
		   $arrValores['I'][]   = isset($arrClasificacion[$row['id']]['1'])?$arrClasificacion[$row['id']]['1']*-1:VOID;
		   $arrValores['NI1'][] = isset($arrClasificacion[$row['id']]['2'])?($arrClasificacion[$row['id']]['2']/2)*-1:VOID;
		   $arrValores['NI'][]  = isset($arrClasificacion[$row['id']]['2'])?$arrClasificacion[$row['id']]['2']:VOID;
		   $arrValores['NI2'][] = isset($arrClasificacion[$row['id']]['2'])?$arrClasificacion[$row['id']]['2']/2:VOID;
		   $arrValores['S'][]   = isset($arrClasificacion[$row['id']]['3'])?$arrClasificacion[$row['id']]['3']:VOID;
		   $arrValores['MS'][]  = isset($arrClasificacion[$row['id']]['4'])?$arrClasificacion[$row['id']]['4']:VOID;

		   $arrValoresP['MI'][] = empty($mi)? VOID : round($mi*100,2);
		   $arrValoresP['I'][]  = empty($i)? VOID : round($i*100,2);
		   $arrValoresP['NI'][] = empty($ni)? VOID : round($ni*100,2);
		   $arrValoresP['S'][]  = empty($s)? VOID : round($s*100,2);
		   $arrValoresP['MS'][] = empty($ms)? VOID : round($ms*100,2);
		 }
		 $MyData = new pData();			
	     /*
		 $MyData->addPoints(array(3,12,15,8,5,5,1,2,3,4,5,6,7),"Ni Satisfecho Ni Insatisfecho",$colorNI,true,true,array(6,24,30,16,10,10,2,4,6,8,10,12,14)); 
		 $MyData->addPoints(array(4,17,6,12,8,3,1,2,3,4,5,6,7),"Satisfecho",$colorS,true,false);
		 $MyData->addPoints(array(4,17,6,12,8,3,1,2,3,4,5,6,7),"Muy Satisfecho",$colorMS,true,false); 
		 $MyData->addPoints(array(-3,-12,-15,-8,-5,-5,-1,-2,-3,-4,-5,-6,-7),"NOMOSTRAR",$colorNI,false,false);
		 $MyData->addPoints(array(-15,-7,-5,-18,-19,-22,-1,-2,-3,-4,-5,-6,-7),"Insatisfecho",$colorI,true,false);
		 $MyData->addPoints(array(-15,-7,-5,-18,-19,-22,-1,-2,-3,-4,-5,-6,-7),"Muy Insatisfecho",$colorMI,true,false);		  
		 */
		 /*
		 $MyData->addPoints($arrValores["NI2"],"Ni Satisfecho Ni Insatisfecho",$colorNI,true,true,$arrValoresP["NI"],$arrLeyenda); 
		 $MyData->addPoints($arrValores["S"],"Satisfecho",$colorS,true,false,$arrValoresP["S"],$arrLeyenda);
		 $MyData->addPoints($arrValores["MS"],"Muy Satisfecho",$colorMS,true,false,$arrValoresP["MS"],$arrLeyenda); 
		 $MyData->addPoints($arrValores["NI1"],"NOMOSTRAR",$colorNI,false,false,array(),$arrLeyenda);
		 $MyData->addPoints($arrValores["I"],"Insatisfecho",$colorI,true,false,$arrValoresP["I"],$arrLeyenda);
		 $MyData->addPoints($arrValores["MI"],"Muy Insatisfecho",$colorMI,true,false,$arrValoresP["MI"],$arrLeyenda);
		 */
		 $MyData->addPoints($arrValores["NI2"],"Ni Satisfecho Ni Insatisfecho",$arrColores['2'],true,true,$arrValoresP["NI"],$arrLeyenda); 
		 $MyData->addPoints($arrValores["S"],"Satisfecho",$arrColores['3'],true,false,$arrValoresP["S"],$arrLeyenda);
		 $MyData->addPoints($arrValores["MS"],"Muy Satisfecho",$arrColores['4'],true,false,$arrValoresP["MS"],$arrLeyenda); 
		 $MyData->addPoints($arrValores["NI1"],"NOMOSTRAR",$arrColores['2'],false,false,array(),$arrLeyenda);
		 $MyData->addPoints($arrValores["I"],"Insatisfecho",$arrColores['1'],true,false,$arrValoresP["I"],$arrLeyenda);
		 $MyData->addPoints($arrValores["MI"],"Muy Insatisfecho",$arrColores['0'],true,false,$arrValoresP["MI"],$arrLeyenda);
		 		  
		/*		  
	  	echo "<pre>";
	  	var_dump($arrValoresP);
	  	echo "</pre>";		
		exit;				  
			*/	  

		 $MyData->setAxisName(0,"Porcentaje de Personas");
		 //$MyData->addPoints(array("Variable1 bla","Variable2  bla","Variable3  bla","Variable4 bla","Variable5 bla","Variable6 bla"),"Labels"); 
		 $MyData->addPoints($arrVariablesEje,"Labels"); 
		 $MyData->setSerieDescription("Labels","Centros de Responsabilidad"); 
		 $MyData->setAbscissa("Labels");
		 
  	    ////////////////// EXPORTAR DATOS /////////////////////////////
	    if(isset($_GET['datos'])){
	      $arrTitulos  = array("Centros de Responsabilidad",'Muy Insatisfecho','Insatisfecho','Ni Insatisfecho Ni Satisfecho','Satisfecho','Muy Satisfecho');
	      $arrColumnas = array(
	        $arrVariablesEje,
	        $arrValoresP['MI'],		
	        $arrValoresP['I'],	        
	        $arrValoresP['NI'],
	        $arrValoresP['S'],	        
	        $arrValoresP['MS']	        
	      );
	      renderizarExcel($arrColumnas,$arrTitulos);	   
	    }
	    ////////////////// EXPORTAR DATOS /////////////////////////////			 
		 //#FF0000
		 //rojo
		 //#0000CC
		 //blue
		 //#FFFFFF
		 //blanco
		 $myPicture = new pImage($iAnchoGraficoDscr,$iAltoGraficoDscr,$MyData); 		 
		 //if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"]))
		  //$myPicture->dumpImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");
		 $myPicture->initialiseImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");
	     $myPicture->drawRectangle(0,0,$iAnchoGraficoDscr-1,$iAltoGraficoDscr-1,array("R"=>0,"G"=>0,"B"=>0));		
		 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
		 //$myPicture->drawFilledRectangle(0,0,700,630,$Settings); 
		 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50); 
		 
		 //$myPicture->drawGradientArea(0,0,700,630,DIRECTION_VERTICAL,$Settings); 
		 
		 //$myPicture->drawGradientArea(1,65,$iAnchoGraficoDsv-2,$iAltoGraficoDsv-3,DIRECTION_VERTICAL,array("StartR"=>255,"StartG"=>255,"StartB"=>255,"EndR"=>248,"EndG"=>165,"EndB"=>95,"Alpha"=>100));
		 //$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80)); 
		 //$myPicture->drawRectangle(0,0,699,629,array("R"=>0,"G"=>0,"B"=>0)); 
		 //$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>6)); 
		 //$myPicture->drawText(10,13,"Psicus",array("R"=>255,"G"=>255,"B"=>255)); 
		 $myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>8)); 
		 //$myPicture->drawText(340,25,"Porcentaje de Distribución de satisfacción por variable",array("FontSize"=>13,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE)); 
		
		 $myPicture->setGraphArea(30,30,$iAnchoGraficoDscr-20,$iAltoGraficoDscr-150); 
		 $myPicture->drawFilledRectangle(60,30,650,400,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10)); 
		 $myPicture->drawScale(array(
		    "DrawSubTicks"=>TRUE
		   ,"Mode"=>SCALE_MODE_ADDALL
		   ,'LabelRotation'=>70
		   ,'RemoveYAxis'=>true
		   ,'EspacioLabelLineaX'=>6			
		   ,'EspacioLabelLineaY'=>5		 
		   ,'OuterTickWidth' =>2
		   ,"GridR"=>180
		   ,"GridG"=>180
		   ,"GridB"=>180
		 )); 
		 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10)); 
		 $myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>5));
		 $myPicture->drawStackedBarChart(array(
		    "RecordImageMap"=>TRUE
		   ,"DisplayValues"=>TRUE
		   ,"DisplayColor"=>DISPLAY_AUTO	   		   
		   ,"Rounded"=>FALSE
		   ,"Surrounding"=>60
		   ,"DisplayOrientation"=>ORIENTATION_HORIZONTAL 
		   ,"ForzarValores"=>true
		  ));
		 $myPicture->setShadow(FALSE); 
		 $myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>7));		  
		 $myPicture->drawLegend(100,500,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));  
		 //$myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawStackedBarChart.png");
	 	 //if(isset($_GET['archivo'])){
	     //$myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   	 //  exit;
	 	 //}		 
		 if(isset($_GET["ImageMap"])){
		   $myPicture->dumpImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");    	
		 } else {
		   	
		   if($opcionSalida=='img'){
	   	     $myPicture->render($pathExportacion."DSCR_".$identificador.".png");		   		
		     $myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawStackedBarChart.png");
		     exit;
		   }elseif($opcionSalida=='nombre'){
	   	     $myPicture->render($pathExportacion."DSCR_".$identificador.".png");
	   	     echo "DSCR_".$identificador.'.png';
	   	     exit;		   	
		   }
	
		 }
		break;		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
 	case 'INV2':
	 //Dispersion
	 //Plotchart y Linechart 		
	 //Linea
	 //obtener las respuestas a las preguntas de todos los usuarios 
	 //Aca van las variables
	 $arrParameters = array();
	 $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);	 

	 $arrVariables = array();
	 $arrResultados = DB_NEGOCIO_obtenerPreguntasCuestionario();
	 //foreach($arrResultados as $row){
	 //  $arrVariables[] = $row['orden'];//nro de las preguntas
	 //}
	 $arrResultadosPreguntas = DB_RESULT_obtenerResultadoPregunta($idCliente,$arrParameters);
	 $fSumatoria = 0;
	 $iContador = 0;
	 $iPromedioFinal = 0;
	 $arrValoresPreguntas = array();
	 foreach($arrResultadosPreguntas as $row){
	   $fSumatoria+= $row['promedio'];
	   $iContador++;  	   
	   $arrValoresPreguntas[] = redondeo($row['promedio'],2);
	   $arrVariables[]		  = $row["orden"]; 	
	 }
	 $iPromedioFinal = $fSumatoria/$iContador;
	 $MyData = new pData();
	 /*   
	 $arrValoresPreguntas = array(
	   0.1,	   0.3,	   0.5,	   0.7,	   0.3,	   0.4,	   0.2,	   0.4,	   0.6,	   0.5,	   0.3,
	   0.2,	   0.3,	   0.3,	   0.1,	   0.3,	   0.5,	   0.7,	   0.3,	   0.4,	   0.2,	   0.4,
	   0.6,	   0.5,	   0.3,	   0.2,	   0.3,	   0.3,    0.1,	   0.3,	   0.5,	   0.7,	   0.3,
	   0.4,	   0.2,	   0.4,	   0.6,	   0.5,	   0.3,	   0.2,	   0.3,	   0.3,    0.1,	   0.3,
	   0.5,	   0.7,	   0.3,	   0.4,	   0.2,	   0.4,	   0.6,	   0.5,	   0.3,	   0.2,	   0.3,
	   0.3,	   0.2,	   0.3,	   0.3,	   0.2,	   0.3,	   0.3,    0.2,	   0.3,    0.2
	 );
	  */
	 $MyData->addPoints($arrValoresPreguntas,"Puntaje Pregunta",$colorDefectoPromedioPregunta);
	 $fTotal 	= 0;
	 $fPromedio = 0;
	 foreach($arrValoresPreguntas as $llave => $valor){
	   $fTotal+=$valor;	
	 }
	 $arrGeneral = array();
	 $fPromedio = $fTotal/count($arrValoresPreguntas);  
	 for($i=0;$i<count($arrValoresPreguntas);$i++){
	   $arrGeneral[$i] = redondeo($fPromedio,2); 	 	
	 }
	 $MyData->addPoints($arrGeneral,"Puntaje General",$colorLineaDispersion);
	 $MyData->setAxisName(0,"");

	 $MyData->addPoints($arrVariables,"Labels");
	 
	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 if(isset($_GET['datos'])){
	   $arrTitulos = array("Numero Pregunta","Puntaje Pregunta","Puntaje General");
	   $arrColumnas = array(
	     $arrVariables,
	     $arrValoresPreguntas,	      
	     $arrGeneral		
	   );	   
	   renderizarExcel($arrColumnas,$arrTitulos);	   
	 }
	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 
	 $MyData->setSerieDescription("Labels",""); 
	 $MyData->setAbscissa("Labels"); 
	 // Create the pChart object  
	 $myPicture = new pImage($iAnchoGraficoInv,$iAltoGraficoInv,$MyData); 
	 // Turn of Antialiasing  
	 $myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");	 
	 $myPicture->Antialias = FALSE; 	
	 // Add a border to the picture  
	 $myPicture->drawRectangle(0,0,$iAnchoGraficoInv-1,$iAltoGraficoInv-1,array("R"=>0,"G"=>0,"B"=>0)); 
	 // Write the chart title   
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>10)); 
	 //$myPicture->drawText(380,35,"INVENTARIO DE SATISFACTORES E INSATISFACTORES",array("FontSize"=>16,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE)); 
	 // Set the default font

	  //Fondo Satisfecho  
	 $myPicture->drawFilledRectangle(30,40,$iAnchoGraficoInv-20,140,$colorFondoSatisfecho);
	 //Fondo Insatisfecho
	 $myPicture->drawFilledRectangle(30,235,$iAnchoGraficoInv-20,$iAltoGraficoInv-60,$colorFondoInsatisfecho);
	 //Titulos
	 /*
	   $myPicture->drawText(40,75,"Muy Satisfecho",array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	   $myPicture->drawText(40,110,"Satisfecho",array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
	   $myPicture->drawText(40,160,"Ni Satisfecho \nNi Insatisfecho",array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
	   $myPicture->drawText(40,187,"Insatisfecho",array("FontSize"=>11x,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	   $myPicture->drawText(40,225,"Muy Insatisfecho",array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
       //lineas separadoras
	   $arrFormato = array(
	    "Ticks"=>1 
	   );
	   $myPicture->drawLine(105,50,740,50,$arrFormato);
	   $myPicture->drawLine(105,88,740,88,$arrFormato);
	   $myPicture->drawLine(105,125,740,125,$arrFormato);
	   $myPicture->drawLine(105,164,740,164,$arrFormato);		 
	   $myPicture->drawLine(105,202,740,202,$arrFormato);
	   $myPicture->drawLine(105,240,740,240,$arrFormato);	 
	 */
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>8));
	 // Define the chart area  
	 $myPicture->setGraphArea(30,40,$iAnchoGraficoInv-20,$iAltoGraficoInv-60); 
	 // Draw the scale
	 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));  
	 $scaleSettings = array(
	    "XMargin"=>10
	   ,"YMargin"=>0
	   ,"Floating"=>TRUE
	   ,"GridR"=>200
	   ,"GridG"=>200
	   ,"GridB"=>200
	   ,"DrawSubTicks"=>TRUE
	   ,"CycleBackground"=>TRUE
	   ,'LabelRotation'=>0
	   ,"Mode"=>SCALE_MODE_MANUAL
	   ,"ManualScale"=>$AxisBoundaries
	   ,"MinDivHeight"=>20
	   ,"LabelSkip" => 3
	   ,'EspacioLabelLineaY'=>5
	 ); 
	 $myPicture->drawScale($scaleSettings); 
	 // Turn on Antialiasing  
	 $myPicture->Antialias = TRUE; 
	 // Draw the line chart 
	 $MyData->setSerieDrawable("Puntaje Pregunta",FALSE);
	 $MyData->setSerieDrawable("Puntaje General",TRUE);
	 $MyData->setSerieWeight("Puntaje General",1);
	 $myPicture->drawLineChart(array("RecordImageMap"=>TRUE)); 
     
	 $MyData->setSerieDrawable("Puntaje General",FALSE);	 
	 $MyData->setSerieDrawable("Puntaje Pregunta",TRUE);	 
	 
	 $arrEscala = DB_ESCALA_obtenerDatosEscalaInventario(1);
 
	 $myPicture->drawPlotChart(array(
	       "RecordImageMap"=>TRUE
	      ,"DisplayValues"=>FALSE
	      ,"PlotBorder"=>TRUE
	      ,"BorderSize"=>2
	      ,"Surrounding"=>-60
	      ,"BorderAlpha"=>80
	      ,"escala" => $arrEscala
		)
	 );

	 // Write the chart legend
	 $MyData->setSerieDrawable("Puntaje Pregunta",TRUE);
	 $MyData->setSerieDrawable("Puntaje General",TRUE);	 
	   
	 $myPicture->drawLegend(290,$iAltoGraficoInv-20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL)); 
	 // Render the picture (choose the best way)  
	 //$myPicture->autoOutput("grafico/lib/pictures/example.drawLineChart.simple.png");
	 if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   exit;
	 }
	 if(isset($_GET["ImageMap"])){
	   $myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");    	
	 } else {
	   //$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	   if($opcionSalida=='img'){
		 $myPicture->render($pathExportacion."INV_".$identificador.".png");
	     $myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	     exit;
	   }elseif($opcionSalida=='nombre'){
	     $myPicture->render($pathExportacion."INV_".$identificador.".png");
	      echo "INV_".$identificador.'.png';
	      exit;		   	
	   }	   
	   
	 }			
	 		
		break;		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
 	case 'PVPI':
	  $arrParameters = array();
	  $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);	  
	  if(!empty($idCr)){
		$arrParameters['id_cr'] = $idCr; 	
	  }
	  if(!empty($idEstamento)){
		$arrParameters['id_estamento'] = $idEstamento; 	
	  }
	  if(!empty($arrFechasAntiguedad)){
		$arrParameters['antiguedad']   = $arrFechasAntiguedad;			
	  }
	  if(!empty($idCjuridica)){
		$arrParameters['id_juridica']  = $idCjuridica;			
	  } 		
	  if(!empty($idSexo)){
		$arrParameters['id_sexo'] = $idSexo;			
	  } 			  	   		   	 	
	  //Linea
	  //$arrResultados  = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
	  $arrResultados = DB_RESULT_obtenerPromedioPorVariable($idCliente,$arrParameters);


	  //OBTENER PROMEDIO DE INSTITUCIÓN (FIja)
	  $promedioInstitucion = ObtenerPromedioInstitucionPorVariables($idCliente);

	
	  //Aca van las Variables
	  $arrVariablesV  = array();

	  $arrClasificacion 	= array();
	  $arrPromUsuarios 		= array();//promedio general todas las respuestas
	  $arrPromVariables 	= array();//promedio general variables	  

	  $arrValoresPV = array();
	  $arrValoresPI = array();
	  $promedioIN   = array();

	  $fPromedioTotalUsuarios = DB_RESULT_obtenerPromedioGeneral($idCliente,$arrParameters);//traerlo de funcion modificada
 	  foreach($arrResultados as $key => $value)
 	  {//por cada una de las variables
	    $arrValoresPI[]  = redondeo($fPromedioTotalUsuarios,2);
	    $arrValoresPV[]  = redondeo($value['promedio'],2);
	    $arrVariablesV[] = $value['variable'];	    

	    $promedioIN[]    = $promedioInstitucion;
      }
	 // Create and populate the pData object  
	 $MyData = new pData();  

	 //VALORES DE EL GRAFICO X2
	 
	 


	 //$MyData->addPoints(array(0.1,0.3,0.5,0.7,0.3,0.4,0.2,0.4,0.6,0.5,0.3,0.2,0.3),"PROMEDIO VARIABLE",$colorPVariable); 
	 $MyData->addPoints($arrValoresPV,"INDICE PROMEDIO VARIABLE",$colorPVariable);


	 //$MyData->addPoints(array(0.4,0.2,0.3,0.4,0.1,0.2,0.3,0.5,0.1,0.7,0.7,0.9,1),"PROMEDIO INSTITUCIÓN",$colorPInstitucion); 

	//*****$MyData->addPoints($arrValoresPI,"INDICE PROMEDIO INSTITUCION",$colorPInstitucion);


	 $MyData->addPoints($promedioIN,"INDICE PROMEDIO INSTITUCION",$colorPInstitucion);
	  

	 //$MyData->addPoints(array(0.4,0.2,0.3,0.4,0.1,0.2,0.3,0.5,0.1,0.7,0.7,0.9,1),"test");



	 $MyData->setAxisName(0,"");

	 $MyData->addPoints($arrVariablesV,"Labels");



	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 if(isset($_GET['datos'])){
	   $arrTitulos = array("Variable","Promedio Variable","Promedio Institución");
	   $arrColumnas = array(
	     $arrVariablesV,
	     $arrValoresPV,	      
	     $arrValoresPI		
	   );
	   renderizarExcel($arrColumnas,$arrTitulos);	   
	 }
	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 
	 $MyData->setSerieDescription("Labels",""); 
	 $MyData->setAbscissa("Labels"); 	
	 // Create the pChart object  
	 $myPicture = new pImage($iAnchoGraficoPvpi,$iAltoGraficoPvpi,$MyData); 

	 // Turn of Antialiasing  

	 if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"])) 
  	$myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/"); 

	 $myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");	 
	 $myPicture->Antialias = FALSE; 	
	 // Add a border to the picture  
	 $myPicture->drawRectangle(0,0,$iAnchoGraficoPvpi-1,$iAltoGraficoPvpi-1,array("R"=>0,"G"=>0,"B"=>0)); 
	 // Write the chart title   
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>9)); 
	 //$myPicture->drawText(360,35,"PROMEDIO POR VARIABLE VS PROMEDIO INSTITUCIÓN",array("FontSize"=>14,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE)); 
	 // Set the default font

	 //Fondo Satisfecho  
	 $myPicture->drawFilledRectangle(150,20,680,92,$colorFondoSatisfecho);
	 
	 //Fondo Insatisfecho
	 $myPicture->drawFilledRectangle(150,128,680,200,$colorFondoInsatisfecho);

	 //Titulos
	 $myPicture->drawText(59,45,"Muy Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(59,76,"Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
	 $myPicture->drawText(59,126,"Ni Satisfecho \nNi Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
	 $myPicture->drawText(59,153,"Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(59,191,"Muy Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
     //lineas separadoras
	 $arrFormato = array(
	   "Ticks"=>0
	  ,"Weight"=>2 
	 );
	 
	 $myPicture->drawLine(150,20,680,20,$arrFormato);
	 $myPicture->drawLine(150,56,680,56,$arrFormato);
	 $myPicture->drawLine(150,92,680,92,$arrFormato);
	 $myPicture->drawLine(150,128,680,128,$arrFormato);		 
	 $myPicture->drawLine(150,164,680,164,$arrFormato);
	 //$myPicture->drawLine(150,240,670,240,$arrFormato);	 
	 		 
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdanab.ttf","FontSize"=>6)); //********
	 // Define the chart area  
	 $myPicture->setGraphArea(150,20,$iAnchoGraficoPvpi-20,$iAltoGraficoPvpi-250); 
	 // Draw the scale
	 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));  
	 $scaleSettings = array(
	    "XMargin"=>10
	   ,"YMargin"=>0
	   ,"Floating"=>TRUE
	   ,"GridR"=>200
	   ,"GridG"=>200
	   ,"GridB"=>200
	   ,"DrawSubTicks"=>TRUE
	   ,"CycleBackground"=>TRUE
	   ,'LabelRotation'=>60
	   ,"Mode"=>SCALE_MODE_MANUAL
	   ,"ManualScale"=>$AxisBoundaries
	   ,"MinDivHeight"=>15
		 ,'EspacioLabelLineaX'=>6			
		 ,'EspacioLabelLineaY'=>5		 
		 ,'OuterTickWidth' =>4	   
	   ); 
	 $myPicture->drawScale($scaleSettings); 
	 // Turn on Antialiasing  
	 $myPicture->Antialias = true; 
	 // Draw the line chart 
	 $myPicture->drawLineChart(array("RecordImageMap"=>TRUE)); 
     $myPicture->drawPlotChart(array(
       "RecordImageMap"=>true,"DisplayValues"=>false,"PlotBorder"=>true,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)
	 );
	 // Write the chart legend  
	 $myPicture->drawLegend(150,412,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL)); 
	 // Render the picture (choose the best way)  
	 //$myPicture->autoOutput("grafico/lib/pictures/example.drawLineChart.simple.png");

	 //ETIQUETAS *****************************************************************************************************///
	 //**************************************************************************************************************///
	 //**************************************************************************************************************///
	/* $LabelSettings = array("NoTitle"=>TRUE);
	 $myPicture->writeLabel("INDICE PROMEDIO VARIABLE","INDICE PROMEDIO INSTITUCION",array(0,1,2,3,4,5,6,7,8,9,10,11),$LabelSettings);
*/
/*
	 $LabelSettings2 = array("NoTitle"=>TRUE,$colorPInstitucion);
	 $myPicture->writeLabel("INDICE PROMEDIO INSTITUCION",array(13),$LabelSettings2);*/

	 /*$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>TRUE,"SerieAlpha"=>TRUE); //"DrawSerieColor"=>FALSE SACA el cuadradito
 	 $myPicture->writeLabel(array("INDICE PROMEDIO VARIABLE","INDICE PROMEDIO INSTITUCION"),array(12),$LabelSettings); 
*/
 	 $LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE,"FontName"=>"grafico/lib/fonts/verdanab.ttf"); 
 	 $myPicture->writeLabel(array("INDICE PROMEDIO VARIABLE"),array(0,1,2,3,4,5,6,7,8,9,10,11,12,13),$LabelSettings);

 	 /*$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE); 
 	 $myPicture->writeLabel(array("test"),array(0,1,2,3,4,5,6,7,8,9,10,11,12),$LabelSettings);
*/
	 //***************************************************************************************************************//
	 //***************************************************************************************************************//
	 //***************************************************************************************************************//

	 if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   exit;
	 }	 
	 if(isset($_GET["ImageMap"])){
	   $myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");    	
	 } else {
	   //$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	   if($opcionSalida=='img'){
	     $myPicture->render($pathExportacion."PVPI_".$identificador.".png");	   		
	     $myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	     exit;
	   }elseif($opcionSalida=='nombre'){
	     $myPicture->render($pathExportacion."PVPI_".$identificador.".png");
	      echo "PVPI_".$identificador.'.png';
	      exit;		   	
	   }	
	 }					
		break;		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////		


  case 'SEG1':
	  $arrParameters = array();
	  $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);

	  if(!empty($idCr)){
		$arrParameters['id_cr'] = $idCr;
	  }
	  if(!empty($idEstamento)){
		$arrParameters['id_estamento'] = $idEstamento;
	  }
	  if(!empty($arrFechasAntiguedad)){
		$arrParameters['antiguedad']   = $arrFechasAntiguedad;
	  }
	  if(!empty($idCjuridica)){
		$arrParameters['id_juridica']  = $idCjuridica;
	  }
	  if(!empty($idSexo)){
		$arrParameters['id_sexo'] = $idSexo;

	  }
	  //Linea
	  //$arrResultados  = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
	  $arrResultados = DB_RESULT_obtenerPromedioPorVariable($idCliente,$arrParameters, $idEstamento);

	  $profesionales     = 10;
	  $coordinadores     = 11;
	  $directivos  	     = 12;
	  $administrativos   = 13; 
	  $investigadores    = 14;

	  $promedioInstitucion    = ObtenerPromedioInstitucion($idCliente);

	  $promedioprofesionales    = ObtenerPromedioPorEstamento($idCliente,$profesionales);
	  $promediocoordinadores = ObtenerPromedioPorEstamento($idCliente,$coordinadores);
	  $promediodirectivos      = ObtenerPromedioPorEstamento($idCliente,$directivos);
	  $promedioadministrativos        = ObtenerPromedioPorEstamento($idCliente,$administrativos);
	  $promedioinvestigadores        = ObtenerPromedioPorEstamento($idCliente,$investigadores);

	  /*$promedioProfesional = 0.65;
	  $promedioAdministrativo = 0.65;
	  $promedioDirectivo = 0.65;
	  $promedioTecnico  = 0.65;*/
	  //Aca van las Variables asdasdasasdasdasdasdasdsad


	  $arrVariablesV  = array();

	  $arrClasificacion 	= array();
	  $arrPromUsuarios 		= array();//promedio general todas las respuestas
	  $arrPromVariables 	= array();//promedio general variables

	  $arrValoresPV = array();
	  $arrValoresPI = array();

	  $fPromedioTotalUsuarios = DB_RESULT_obtenerPromedioGeneral($idCliente,$arrParameters);//traerlo de funcion modificada
 	  foreach($arrResultados as $key => $value){//por cada una de las variables
	    $arrValoresPI[]  = redondeo($fPromedioTotalUsuarios,2);
	    $arrValoresPV[]  = redondeo($value['promedio'],2);
	    $arrVariablesV[] = $value['variable'];




      }
	 // Crear pData objeto
	 $MyData = new pData();

	 //aqui ponemos los valores para el grafico

	  $MyData->addPoints(array($promedioInstitucion,$promedioInstitucion
	  ,$promedioInstitucion,$promedioInstitucion,$promedioInstitucion),"PROMEDIO INSTITUCIÓN",$colorPInstitucion);
	  // $MyData->addPoints($arrValoresPI,"INDICE PROMEDIO INSTITUCION",$colorPInstitucion);
	  $MyData->addPoints(array($promedioprofesionales,$promediocoordinadores
	  ,$promediodirectivos,$promedioadministrativos,$promedioinvestigadores),"PROMEDIO ESTAMENTO",$colorPVariable);
	  // $MyData->addPoints($arrValoresPV,"INDICE PROMEDIO VARIABLE",$colorPVariable);
	  //$MyData->addPoints(array(0.4,0.2,0.3,0.4,0.1,0.2,0.3,0.5,0.1,0.7,0.7,0.9,1),"test");



	  $MyData->setAxisName(0,"");

	 //$MyData->addPoints($arrVariablesV,"Labels");
	  $MyData->addPoints(array("PROFESIONAL","COORDINADORES","DIRECTIVO","ADMINISTRATIVOS","INVESTIGADORES"),"Labels");


	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 if(isset($_GET['datos'])){
	   $arrTitulos = array("Variable","Promedio Variable","Promedio Institución");
	   $arrColumnas = array(
	     $arrVariablesV,
	     $arrValoresPV,
	     $arrValoresPI
	   );
	   renderizarExcel($arrColumnas,$arrTitulos);
	 }
	 ////////////////// EXPORTAR DATOS /////////////////////////////

	 $MyData->setSerieDescription("Labels","");
	 $MyData->setAbscissa("Labels");
	 // Create the pChart object
	 $myPicture = new pImage($iAnchoGraficoPvpi,$iAltoGraficoPvpi,$MyData);

	 // Turn of Antialiasing

	 if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"]))
  	$myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");

	 $myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");
	 $myPicture->Antialias = FALSE;
	 // Add a border to the picture
	 $myPicture->drawRectangle(0,0,$iAnchoGraficoPvpi-1,$iAltoGraficoPvpi-1,array("R"=>0,"G"=>0,"B"=>0));
	 // Write the chart title
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>9));
	 //$myPicture->drawText(360,35,"PROMEDIO POR VARIABLE VS PROMEDIO INSTITUCIÓN",array("FontSize"=>14,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 // Set the default font

	 //Fondo Satisfecho
	 $myPicture->drawFilledRectangle(150,20,680,92,$colorFondoSatisfecho);

	 //Fondo Insatisfecho
	 $myPicture->drawFilledRectangle(150,128,680,200,$colorFondoInsatisfecho);

	 //Titulos
	 $myPicture->drawText(59,45,"Muy Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(59,76,"Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(59,126,"Ni Satisfecho \nNi Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(59,153,"Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(59,191,"Muy Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
     //lineas separadoras
	 $arrFormato = array(
	   "Ticks"=>0
	  ,"Weight"=>2
	 );

	 $myPicture->drawLine(150,20,680,20,$arrFormato);
	 $myPicture->drawLine(150,56,680,56,$arrFormato);
	 $myPicture->drawLine(150,92,680,92,$arrFormato);
	 $myPicture->drawLine(150,128,680,128,$arrFormato);
	 $myPicture->drawLine(150,164,680,164,$arrFormato);
	 //$myPicture->drawLine(150,240,670,240,$arrFormato);

	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdanab.ttf","FontSize"=>6)); //********
	 // Define the chart area
	 $myPicture->setGraphArea(150,20,$iAnchoGraficoPvpi-20,$iAltoGraficoPvpi-250);
	 // Draw the scale
	 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));
	 $scaleSettings = array(
	    "XMargin"=>10
	   ,"YMargin"=>0
	   ,"Floating"=>TRUE
	   ,"GridR"=>200
	   ,"GridG"=>200
	   ,"GridB"=>200
	   ,"DrawSubTicks"=>TRUE
	   ,"CycleBackground"=>TRUE
	   ,'LabelRotation'=>60
	   ,"Mode"=>SCALE_MODE_MANUAL
	   ,"ManualScale"=>$AxisBoundaries
	   ,"MinDivHeight"=>15
		 ,'EspacioLabelLineaX'=>6
		 ,'EspacioLabelLineaY'=>5
		 ,'OuterTickWidth' =>4
	   );
	 $myPicture->drawScale($scaleSettings);
	 // Turn on Antialiasing
	 $myPicture->Antialias = true;
	 // Draw the line chart
	 $myPicture->drawLineChart(array("RecordImageMap"=>TRUE));
     $myPicture->drawPlotChart(array(
       "RecordImageMap"=>true,"DisplayValues"=>false,"PlotBorder"=>true,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)
	 );
	 // Write the chart legend
	 $myPicture->drawLegend(150,412,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));


 	 $LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE,"FontName"=>"grafico/lib/fonts/verdanab.ttf");
 	 $myPicture->writeLabel(array("PROMEDIO ESTAMENTO"),array(0,1,2,3,4),$LabelSettings);

 	 /*$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE);
 	 $myPicture->writeLabel(array("test"),array(0,1,2,3,4,5,6,7,8,9,10,11,12),$LabelSettings);
*/
	 //***************************************************************************************************************//
	 //***************************************************************************************************************//
	 //***************************************************************************************************************//

	 if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   exit;
	 }
	 if(isset($_GET["ImageMap"])){
	   $myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");
	 } else {
	   //$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	   if($opcionSalida=='img'){
	     $myPicture->render($pathExportacion."SEG1_".$identificador.".png");
	     $myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	     exit;
	   }elseif($opcionSalida=='nombre'){
	     $myPicture->render($pathExportacion."SEG1_".$identificador.".png");
	      echo "SEG1_".$identificador.'.png';
	      exit;
	   }
	 }
		break;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	case 'SEG2':
		$arrParameters = array();
		$arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);

		if(!empty($idCr)){
			$arrParameters['id_cr'] = $idCr;
		}
		if(!empty($idEstamento)){
			$arrParameters['id_estamento'] = $idEstamento;
		}
		if(!empty($arrFechasAntiguedad)){
			$arrParameters['antiguedad']   = $arrFechasAntiguedad;
		}
		if(!empty($idCjuridica)){
			$arrParameters['id_juridica']  = $idCjuridica;
		}
		if(!empty($idSexo)){
			$arrParameters['id_sexo'] = $idSexo;

		}
		//Linea
		//$arrResultados  = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
		$arrResultados = DB_RESULT_obtenerPromedioPorVariable($idCliente,$arrParameters, $idEstamento);

		$idSexoFemenino  = "F";
		$idSexoMasculino = "M";

		$promedioInstitucion = ObtenerPromedioInstitucion($idCliente);
		$promedioFemenino 	 = ObtenerPromedioPorSexo($idCliente,$idSexoFemenino);
		$promedioMasculino 	 = ObtenerPromedioPorSexo($idCliente,$idSexoMasculino);

		//Aca van las Variables


		$arrVariablesV  = array();

		$arrClasificacion 	= array();
		$arrPromUsuarios 	= array();//promedio general todas las respuestas
		$arrPromVariables 	= array();//promedio general variables

		$arrValoresPV = array();
		$arrValoresPI = array();

		$fPromedioTotalUsuarios = DB_RESULT_obtenerPromedioGeneral($idCliente,$arrParameters);//traerlo de funcion modificada
		foreach($arrResultados as $key => $value){//por cada una de las variables
			$arrValoresPI[]  = redondeo($fPromedioTotalUsuarios,2);
			$arrValoresPV[]  = redondeo($value['promedio'],2);
			$arrVariablesV[] = $value['variable'];




		}
		// Crear pData objeto, DONDE SE AGREGAN LOS DATOS PARA MOSTRAR EN EL GRAFICO
		$MyData = new pData();


		$MyData->addPoints(array($promedioInstitucion
		,$promedioInstitucion),"PROMEDIO INSTITUCIÓN",$colorPInstitucion);
		// $MyData->addPoints($arrValoresPI,"INDICE PROMEDIO INSTITUCION",$colorPInstitucion);
		$MyData->addPoints(array($promedioFemenino,$promedioMasculino),"PROMEDIO SEXO",$colorPVariable);
		// $MyData->addPoints($arrValoresPV,"INDICE PROMEDIO VARIABLE",$colorPVariable);
		//$MyData->addPoints(array(0.4,0.2,0.3,0.4,0.1,0.2,0.3,0.5,0.1,0.7,0.7,0.9,1),"test");



		$MyData->setAxisName(0,"");

		///////// ETIQUETAS ABAJO DEL GRAFICO ///////////////
		//$MyData->addPoints($arrVariablesV,"Labels");
		$MyData->addPoints(array("FEMENINO","MASCULINO"),"Labels");


		////////////////// EXPORTAR DATOS /////////////////////////////
		if(isset($_GET['datos'])){
			$arrTitulos = array("Variable","Promedio Variable","Promedio Institución");
			$arrColumnas = array(
				$arrVariablesV,
				$arrValoresPV,
				$arrValoresPI
			);
			renderizarExcel($arrColumnas,$arrTitulos);
		}
		////////////////// EXPORTAR DATOS /////////////////////////////

		$MyData->setSerieDescription("Labels","");
		$MyData->setAbscissa("Labels");
		// Create the pChart object
		$myPicture = new pImage($iAnchoGraficoPvpi,$iAltoGraficoPvpi,$MyData);

		// Turn of Antialiasing

		if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"]))
			$myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");

		$myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");
		$myPicture->Antialias = FALSE;
		// Add a border to the picture
		$myPicture->drawRectangle(0,0,$iAnchoGraficoPvpi-1,$iAltoGraficoPvpi-1,array("R"=>0,"G"=>0,"B"=>0));
		// Write the chart title
		$myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>9));
		//$myPicture->drawText(360,35,"PROMEDIO POR VARIABLE VS PROMEDIO INSTITUCIÓN",array("FontSize"=>14,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		// Set the default font

		//Fondo Satisfecho
		$myPicture->drawFilledRectangle(150,20,680,92,$colorFondoSatisfecho);

		//Fondo Insatisfecho
		$myPicture->drawFilledRectangle(150,128,680,200,$colorFondoInsatisfecho);

		//Titulos
		$myPicture->drawText(59,45,"Muy Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,76,"Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,126,"Ni Satisfecho \nNi Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,153,"Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,191,"Muy Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		//lineas separadoras
		$arrFormato = array(
			"Ticks"=>0
		,"Weight"=>2
		);

		$myPicture->drawLine(150,20,680,20,$arrFormato);
		$myPicture->drawLine(150,56,680,56,$arrFormato);
		$myPicture->drawLine(150,92,680,92,$arrFormato);
		$myPicture->drawLine(150,128,680,128,$arrFormato);
		$myPicture->drawLine(150,164,680,164,$arrFormato);
		//$myPicture->drawLine(150,240,670,240,$arrFormato);

		$myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdanab.ttf","FontSize"=>6)); //********
		// Define the chart area
		$myPicture->setGraphArea(150,20,$iAnchoGraficoPvpi-20,$iAltoGraficoPvpi-250);
		// Draw the scale
		$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));
		$scaleSettings = array(
			"XMargin"=>10
		,"YMargin"=>0
		,"Floating"=>TRUE
		,"GridR"=>200
		,"GridG"=>200
		,"GridB"=>200
		,"DrawSubTicks"=>TRUE
		,"CycleBackground"=>TRUE
		,'LabelRotation'=>60
		,"Mode"=>SCALE_MODE_MANUAL
		,"ManualScale"=>$AxisBoundaries
		,"MinDivHeight"=>15
		,'EspacioLabelLineaX'=>6
		,'EspacioLabelLineaY'=>5
		,'OuterTickWidth' =>4
		);
		$myPicture->drawScale($scaleSettings);
		// Turn on Antialiasing
		$myPicture->Antialias = true;
		// Draw the line chart
		$myPicture->drawLineChart(array("RecordImageMap"=>TRUE));
		$myPicture->drawPlotChart(array(
				"RecordImageMap"=>true,"DisplayValues"=>false,"PlotBorder"=>true,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)
		);
		// Write the chart legend
		$myPicture->drawLegend(150,412,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));


		$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE,"FontName"=>"grafico/lib/fonts/verdanab.ttf");
		$myPicture->writeLabel(array("PROMEDIO SEXO"),array(0,1),$LabelSettings);

		/*$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE);
        $myPicture->writeLabel(array("test"),array(0,1,2,3,4,5,6,7,8,9,10,11,12),$LabelSettings);
  */
		//***************************************************************************************************************//
		//***************************************************************************************************************//
		//***************************************************************************************************************//

		if(isset($_GET['archivo'])){
			$myPicture->render($path."pictures/".$_GET['archivo'].".png");
			exit;
		}
		if(isset($_GET["ImageMap"])){
			$myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");
		} else {
			//$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
			if($opcionSalida=='img'){
				$myPicture->render($pathExportacion."SEG2_".$identificador.".png");
				$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
				exit;
			}elseif($opcionSalida=='nombre'){
				$myPicture->render($pathExportacion."SEG2_".$identificador.".png");
				echo "SEG2_".$identificador.'.png';
				exit;
			}
		}
		break;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	case 'SEG3':
		$arrParameters = array();
		$arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);

		if(!empty($idCr)){
			$arrParameters['id_cr'] = $idCr;
		}
		if(!empty($idEstamento)){
			$arrParameters['id_estamento'] = $idEstamento;
		}
		if(!empty($arrFechasAntiguedad)){
			$arrParameters['antiguedad']   = $arrFechasAntiguedad;
		}
		if(!empty($idCjuridica)){
			$arrParameters['id_juridica']  = $idCjuridica;
		}
		if(!empty($idSexo)){
			$arrParameters['id_sexo'] = $idSexo;

		}
		//Linea
		//$arrResultados  = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
		$arrResultados = DB_RESULT_obtenerPromedioPorVariable($idCliente,$arrParameters, $idEstamento);


		$idHsa      = 4;
		$idPlanta   = 5;
		$idContrata = 6;

		$promedioInstitucion = ObtenerPromedioInstitucion($idCliente);

		$promedioHsa	  = ObtenerPromedioPorCalidadJuridica($idCliente, $idHsa);
		$promedioPlanta   = ObtenerPromedioPorCalidadJuridica($idCliente, $idPlanta);
		$promedioContrata = ObtenerPromedioPorCalidadJuridica($idCliente, $idContrata);

		//Aca van las Variables


		$arrVariablesV  = array();

		$arrClasificacion 	= array();
		$arrPromUsuarios 	= array();//promedio general todas las respuestas
		$arrPromVariables 	= array();//promedio general variables

		$arrValoresPV = array();
		$arrValoresPI = array();

		$fPromedioTotalUsuarios = DB_RESULT_obtenerPromedioGeneral($idCliente,$arrParameters);//traerlo de funcion modificada
		foreach($arrResultados as $key => $value){//por cada una de las variables
			$arrValoresPI[]  = redondeo($fPromedioTotalUsuarios,2);
			$arrValoresPV[]  = redondeo($value['promedio'],2);
			$arrVariablesV[] = $value['variable'];




		}
		// Crear pData objeto, DONDE SE AGREGAN LOS DATOS PARA MOSTRAR EN EL GRAFICO
		$MyData = new pData();


		$MyData->addPoints(array($promedioInstitucion
		,$promedioInstitucion,$promedioInstitucion),"PROMEDIO INSTITUCIÓN",$colorPInstitucion);
		// $MyData->addPoints($arrValoresPI,"INDICE PROMEDIO INSTITUCION",$colorPInstitucion);
		$MyData->addPoints(array($promedioHsa,$promedioPlanta,$promedioContrata)
			,"PROMEDIO CALIDAD JURIDICA",$colorPVariable);
		// $MyData->addPoints($arrValoresPV,"INDICE PROMEDIO VARIABLE",$colorPVariable);
		//$MyData->addPoints(array(0.4,0.2,0.3,0.4,0.1,0.2,0.3,0.5,0.1,0.7,0.7,0.9,1),"test");



		$MyData->setAxisName(0,"");

		///////// ETIQUETAS ABAJO DEL GRAFICO ///////////////
		//$MyData->addPoints($arrVariablesV,"Labels");
		$MyData->addPoints(array("HSA","PLANTA","CONTRATA"),"Labels");


		////////////////// EXPORTAR DATOS /////////////////////////////
		if(isset($_GET['datos'])){
			$arrTitulos = array("Variable","Promedio Variable","Promedio Institución");
			$arrColumnas = array(
				$arrVariablesV,
				$arrValoresPV,
				$arrValoresPI
			);
			renderizarExcel($arrColumnas,$arrTitulos);
		}
		////////////////// EXPORTAR DATOS /////////////////////////////

		$MyData->setSerieDescription("Labels","");
		$MyData->setAbscissa("Labels");
		// Create the pChart object
		$myPicture = new pImage($iAnchoGraficoPvpi,$iAltoGraficoPvpi,$MyData);

		// Turn of Antialiasing

		if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"]))
			$myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");

		$myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");
		$myPicture->Antialias = FALSE;
		// Add a border to the picture
		$myPicture->drawRectangle(0,0,$iAnchoGraficoPvpi-1,$iAltoGraficoPvpi-1,array("R"=>0,"G"=>0,"B"=>0));
		// Write the chart title
		$myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>9));
		//$myPicture->drawText(360,35,"PROMEDIO POR VARIABLE VS PROMEDIO INSTITUCIÓN",array("FontSize"=>14,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		// Set the default font

		//Fondo Satisfecho
		$myPicture->drawFilledRectangle(150,20,680,92,$colorFondoSatisfecho);

		//Fondo Insatisfecho
		$myPicture->drawFilledRectangle(150,128,680,200,$colorFondoInsatisfecho);

		//Titulos
		$myPicture->drawText(59,45,"Muy Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,76,"Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,126,"Ni Satisfecho \nNi Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,153,"Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,191,"Muy Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		//lineas separadoras
		$arrFormato = array(
			"Ticks"=>0
		,"Weight"=>2
		);

		$myPicture->drawLine(150,20,680,20,$arrFormato);
		$myPicture->drawLine(150,56,680,56,$arrFormato);
		$myPicture->drawLine(150,92,680,92,$arrFormato);
		$myPicture->drawLine(150,128,680,128,$arrFormato);
		$myPicture->drawLine(150,164,680,164,$arrFormato);
		//$myPicture->drawLine(150,240,670,240,$arrFormato);

		$myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdanab.ttf","FontSize"=>6)); //********
		// Define the chart area
		$myPicture->setGraphArea(150,20,$iAnchoGraficoPvpi-20,$iAltoGraficoPvpi-250);
		// Draw the scale
		$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));
		$scaleSettings = array(
			"XMargin"=>10
		,"YMargin"=>0
		,"Floating"=>TRUE
		,"GridR"=>200
		,"GridG"=>200
		,"GridB"=>200
		,"DrawSubTicks"=>TRUE
		,"CycleBackground"=>TRUE
		,'LabelRotation'=>60
		,"Mode"=>SCALE_MODE_MANUAL
		,"ManualScale"=>$AxisBoundaries
		,"MinDivHeight"=>15
		,'EspacioLabelLineaX'=>6
		,'EspacioLabelLineaY'=>5
		,'OuterTickWidth' =>4
		);
		$myPicture->drawScale($scaleSettings);
		// Turn on Antialiasing
		$myPicture->Antialias = true;
		// Draw the line chart
		$myPicture->drawLineChart(array("RecordImageMap"=>TRUE));
		$myPicture->drawPlotChart(array(
				"RecordImageMap"=>true,"DisplayValues"=>false,"PlotBorder"=>true,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)
		);
		// Write the chart legend
		$myPicture->drawLegend(150,412,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));


		$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE,"FontName"=>"grafico/lib/fonts/verdanab.ttf");
		$myPicture->writeLabel(array("PROMEDIO CALIDAD JURIDICA"),array(0,1,2),$LabelSettings);

		/*$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE);
        $myPicture->writeLabel(array("test"),array(0,1,2,3,4,5,6,7,8,9,10,11,12),$LabelSettings);
  */
		//***************************************************************************************************************//
		//***************************************************************************************************************//
		//***************************************************************************************************************//

		if(isset($_GET['archivo'])){
			$myPicture->render($path."pictures/".$_GET['archivo'].".png");
			exit;
		}
		if(isset($_GET["ImageMap"])){
			$myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");
		} else {
			//$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
			if($opcionSalida=='img'){
				$myPicture->render($pathExportacion."SEG3_".$identificador.".png");
				$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
				exit;
			}elseif($opcionSalida=='nombre'){
				$myPicture->render($pathExportacion."SEG3_".$identificador.".png");
				echo "SEG3_".$identificador.'.png';
				exit;
			}
		}
		break;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	case 'SEG4':
		$arrParameters = array();
		$arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);

		if(!empty($idCr)){
			$arrParameters['id_cr'] = $idCr;
		}
		if(!empty($idEstamento)){
			$arrParameters['id_estamento'] = $idEstamento;
		}
		if(!empty($arrFechasAntiguedad)){
			$arrParameters['antiguedad']   = $arrFechasAntiguedad;
		}
		if(!empty($idCjuridica)){
			$arrParameters['id_juridica']  = $idCjuridica;
		}
		if(!empty($idSexo)){
			$arrParameters['id_sexo'] = $idSexo;

		}
		//Linea
		//$arrResultados  = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
		$arrResultados = DB_RESULT_obtenerPromedioPorVariable($idCliente,$arrParameters, $idEstamento);

		//Valores de la BD antiguedad
		$menorDeUno 	  = "91";
		$entreDosYCuatro  = "92";
		$entreCincoYSiete = "93";
		$entreOchoYDiez   = "94";
		$diezOMas 		  = "100";


		$promedioInstitucion = ObtenerPromedioInstitucion($idCliente);

		$PromedioMenorDeUno	      = ObtenerPromedioPorAntiguedad($idCliente, $menorDeUno);
		$PromedioEntreDosYCuatro  = ObtenerPromedioPorAntiguedad($idCliente, $entreDosYCuatro);
		$PromedioEntreCincoYSiete = ObtenerPromedioPorAntiguedad($idCliente, $entreCincoYSiete);
		$PromedioEntreOchoYDiez   = ObtenerPromedioPorAntiguedad($idCliente,$entreOchoYDiez);
		$PromedioDiezOMas         = ObtenerPromedioPorAntiguedad($idCliente,$diezOMas);

		//Aca van las Variables


		$arrVariablesV  = array();

		$arrClasificacion 	= array();
		$arrPromUsuarios 	= array();//promedio general todas las respuestas
		$arrPromVariables 	= array();//promedio general variables

		$arrValoresPV = array();
		$arrValoresPI = array();

		$fPromedioTotalUsuarios = DB_RESULT_obtenerPromedioGeneral($idCliente,$arrParameters);//traerlo de funcion modificada
		foreach($arrResultados as $key => $value){//por cada una de las variables
			$arrValoresPI[]  = redondeo($fPromedioTotalUsuarios,2);
			$arrValoresPV[]  = redondeo($value['promedio'],2);
			$arrVariablesV[] = $value['variable'];




		}
		// Crear pData objeto, DONDE SE AGREGAN LOS DATOS PARA MOSTRAR EN EL GRAFICO
		$MyData = new pData();


		$MyData->addPoints(array($promedioInstitucion,$promedioInstitucion,$promedioInstitucion
		,$promedioInstitucion,$promedioInstitucion),"PROMEDIO INSTITUCIÓN",$colorPInstitucion);
		// $MyData->addPoints($arrValoresPI,"INDICE PROMEDIO INSTITUCION",$colorPInstitucion);
		$MyData->addPoints(array($PromedioMenorDeUno,$PromedioEntreDosYCuatro
			,$PromedioEntreCincoYSiete,$PromedioEntreOchoYDiez,$PromedioDiezOMas)
			,"PROMEDIO ANTIGUEDAD",$colorPVariable);
		// $MyData->addPoints($arrValoresPV,"INDICE PROMEDIO VARIABLE",$colorPVariable);
		//$MyData->addPoints(array(0.4,0.2,0.3,0.4,0.1,0.2,0.3,0.5,0.1,0.7,0.7,0.9,1),"test");



		$MyData->setAxisName(0,"");

		///////// ETIQUETAS ABAJO DEL GRAFICO ///////////////
		//$MyData->addPoints($arrVariablesV,"Labels");
		$MyData->addPoints(array("MENOS DE 1 AÑO","ENTRE 2 Y 4 AÑOS"
								,"ENTRE 5 Y 7 AÑOS", "ENTRE 8 Y 10 AÑOS","10 o MÁS"),"Labels");


		////////////////// EXPORTAR DATOS /////////////////////////////
		if(isset($_GET['datos'])){
			$arrTitulos = array("Variable","Promedio Variable","Promedio Institución");
			$arrColumnas = array(
				$arrVariablesV,
				$arrValoresPV,
				$arrValoresPI
			);
			renderizarExcel($arrColumnas,$arrTitulos);
		}
		////////////////// EXPORTAR DATOS /////////////////////////////

		$MyData->setSerieDescription("Labels","");
		$MyData->setAbscissa("Labels");
		// Create the pChart object
		$myPicture = new pImage($iAnchoGraficoPvpi,$iAltoGraficoPvpi,$MyData);

		// Turn of Antialiasing

		if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"]))
			$myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");

		$myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");
		$myPicture->Antialias = FALSE;
		// Add a border to the picture
		$myPicture->drawRectangle(0,0,$iAnchoGraficoPvpi-1,$iAltoGraficoPvpi-1,array("R"=>0,"G"=>0,"B"=>0));
		// Write the chart title
		$myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>9));
		//$myPicture->drawText(360,35,"PROMEDIO POR VARIABLE VS PROMEDIO INSTITUCIÓN",array("FontSize"=>14,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		// Set the default font

		//Fondo Satisfecho
		$myPicture->drawFilledRectangle(150,20,680,92,$colorFondoSatisfecho);

		//Fondo Insatisfecho
		$myPicture->drawFilledRectangle(150,128,680,200,$colorFondoInsatisfecho);

		//Titulos
		$myPicture->drawText(59,45,"Muy Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,76,"Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,126,"Ni Satisfecho \nNi Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,153,"Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		$myPicture->drawText(59,191,"Muy Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
		//lineas separadoras
		$arrFormato = array(
			"Ticks"=>0
		,"Weight"=>2
		);

		$myPicture->drawLine(150,20,680,20,$arrFormato);
		$myPicture->drawLine(150,56,680,56,$arrFormato);
		$myPicture->drawLine(150,92,680,92,$arrFormato);
		$myPicture->drawLine(150,128,680,128,$arrFormato);
		$myPicture->drawLine(150,164,680,164,$arrFormato);
		//$myPicture->drawLine(150,240,670,240,$arrFormato);

		$myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdanab.ttf","FontSize"=>6)); //********
		// Define the chart area
		$myPicture->setGraphArea(150,20,$iAnchoGraficoPvpi-20,$iAltoGraficoPvpi-250);
		// Draw the scale
		$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));
		$scaleSettings = array(
			"XMargin"=>10
		,"YMargin"=>0
		,"Floating"=>TRUE
		,"GridR"=>200
		,"GridG"=>200
		,"GridB"=>200
		,"DrawSubTicks"=>TRUE
		,"CycleBackground"=>TRUE
		,'LabelRotation'=>60
		,"Mode"=>SCALE_MODE_MANUAL
		,"ManualScale"=>$AxisBoundaries
		,"MinDivHeight"=>15
		,'EspacioLabelLineaX'=>6
		,'EspacioLabelLineaY'=>5
		,'OuterTickWidth' =>4
		);
		$myPicture->drawScale($scaleSettings);
		// Turn on Antialiasing
		$myPicture->Antialias = true;
		// Draw the line chart
		$myPicture->drawLineChart(array("RecordImageMap"=>TRUE));
		$myPicture->drawPlotChart(array(
				"RecordImageMap"=>true,"DisplayValues"=>false,"PlotBorder"=>true,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)
		);
		// Write the chart legend
		$myPicture->drawLegend(150,412,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));


		$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE,"FontName"=>"grafico/lib/fonts/verdanab.ttf");
		$myPicture->writeLabel(array("PROMEDIO ANTIGUEDAD"),array(0,1,2,3,4),$LabelSettings);

		/*$LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE);
        $myPicture->writeLabel(array("test"),array(0,1,2,3,4,5,6,7,8,9,10,11,12),$LabelSettings);
  */
		//***************************************************************************************************************//
		//***************************************************************************************************************//
		//***************************************************************************************************************//

		if(isset($_GET['archivo'])){
			$myPicture->render($path."pictures/".$_GET['archivo'].".png");
			exit;
		}
		if(isset($_GET["ImageMap"])){
			$myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");
		} else {
			//$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
			if($opcionSalida=='img'){
				$myPicture->render($pathExportacion."SEG4_".$identificador.".png");
				$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
				exit;
			}elseif($opcionSalida=='nombre'){
				$myPicture->render($pathExportacion."SEG4_".$identificador.".png");
				echo "SEG4_".$identificador.'.png';
				exit;
			}
		}
		break;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	case 'PCRPI':
	  $arrParameters = array();
	  $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);	  
	  if(!empty($idCr)){
		$arrParameters['id_cr'] = $idCr; 	
	  }
	  //lineas, 2 series con areas
	  //Linea
	  //$arrResultados  = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
	  $arrResultados = DB_RESULT_obtenerPromedioPorCr($idCliente,$arrParameters);	
	  //Aca van las Variables
	  $arrVariablesV  = array();

	  $arrClasificacion 	= array();
	  $arrPromUsuarios 		= array();//promedio general todas las respuestas
	  $arrPromVariables 	= array();//promedio general variables	  

	  $arrValoresPV = array();
	  $arrValoresPI = array();

	  $fPromedioTotalUsuarios = DB_RESULT_obtenerPromedioGeneral($idCliente,$arrParameters);//traerlo de funcion modificada
	  foreach($arrResultados as $key => $value){//por cada una de las variables
	    $arrValoresPI[]  = redondeo($fPromedioTotalUsuarios,2);
	    $arrValoresPV[]  = redondeo($value['promedio'],2);
	    $arrVariablesV[] = $value['sigla_unidad_raiz'];	    
      }
	 // Create and populate the pData object  
	 $MyData = new pData();   
	 //$MyData->addPoints(array(0.1,0.3,0.5,0.7,0.3,0.4,0.2,0.4,0.6,0.5,0.3,0.2,0.3),"PROMEDIO VARIABLE",$colorPVariable); 
	 $MyData->addPoints($arrValoresPV,"INDICE PROMEDIO CR",$colorPVariable);
	 //$MyData->addPoints(array(0.4,0.2,0.3,0.4,0.1,0.2,0.3,0.5,0.1,0.7,0.7,0.9,1),"PROMEDIO INSTITUCIÓN",$colorPInstitucion); 
	 $MyData->addPoints($arrValoresPI,"INDICE PROMEDIO INSTITUCION",$colorPInstitucion);
	 $MyData->setAxisName(0,"");

	 $MyData->addPoints($arrVariablesV,"Labels");

	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 if(isset($_GET['datos'])){
	   $arrTitulos = array("Variable","Promedio Variable","Promedio Institución");
	   $arrColumnas = array(
	     $arrVariablesV,
	     $arrValoresPV,	      
	     $arrValoresPI		
	   );
	   renderizarExcel($arrColumnas,$arrTitulos);	   
	 }
	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 
	 $MyData->setSerieDescription("Labels",""); 
	 $MyData->setAbscissa("Labels"); 	
	 // Create the pChart object  
	 $myPicture = new pImage($iAnchoGraficoPcrpi,$iAltoGraficoPcrpi,$MyData); 
	 // Turn of Antialiasing  
	 $myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");	 
	 $myPicture->Antialias = FALSE; 	
	 // Add a border to the picture  
	 $myPicture->drawRectangle(0,0,$iAnchoGraficoPcrpi-1,$iAltoGraficoPcrpi-1,array("R"=>0,"G"=>0,"B"=>0)); 
	 // Write the chart title   
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>9)); 
	 //$myPicture->drawText(360,35,"PROMEDIO POR VARIABLE VS PROMEDIO INSTITUCIÓN",array("FontSize"=>14,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE)); 
	 // Set the default font

	 //Fondo Satisfecho  
	 $myPicture->drawFilledRectangle(150,20,680,108,$colorFondoSatisfecho);
	 
	 //Fondo Insatisfecho
	 $myPicture->drawFilledRectangle(150,152,680,240,$colorFondoInsatisfecho);

	 //Titulos
	 $myPicture->drawText(59,48,"Muy Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(59,86,"Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
	 $myPicture->drawText(59,140,"Ni Satisfecho \nNi Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
	 $myPicture->drawText(59,180,"Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(59,225,"Muy Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
     //lineas separadoras
	 $arrFormato = array(
	   "Ticks"=>0
	  ,"Weight"=>2 
	 );
	 
	 $myPicture->drawLine(150,20,680,20,$arrFormato);
	 $myPicture->drawLine(150,64,680,64,$arrFormato);
	 $myPicture->drawLine(150,108,680,108,$arrFormato);
	 $myPicture->drawLine(150,152,680,152,$arrFormato);		 
	 $myPicture->drawLine(150,196,680,196,$arrFormato);
	 //$myPicture->drawLine(150,240,670,240,$arrFormato);	 
	 		 
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdanab.ttf","FontSize"=>6));
	 // Define the chart area  
	 $myPicture->setGraphArea(150,20,$iAnchoGraficoPcrpi-20,$iAltoGraficoPcrpi-110); 
	 // Draw the scale
	 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));  
	 $scaleSettings = array(
	    "XMargin"=>10
	   ,"YMargin"=>0
	   ,"Floating"=>TRUE
	   ,"GridR"=>200
	   ,"GridG"=>200
	   ,"GridB"=>200
	   ,"DrawSubTicks"=>TRUE
	   ,"CycleBackground"=>TRUE
	   ,'LabelRotation'=>60
	   ,"Mode"=>SCALE_MODE_MANUAL
	   ,"ManualScale"=>$AxisBoundaries
	   ,"MinDivHeight"=>15
		 ,'EspacioLabelLineaX'=>6			
		 ,'EspacioLabelLineaY'=>5		 
		 ,'OuterTickWidth' =>4	   
	   ); 
	 $myPicture->drawScale($scaleSettings); 
	 // Turn on Antialiasing  
	 $myPicture->Antialias = TRUE; 
	 // Draw the line chart 
	 $myPicture->drawLineChart(array("RecordImageMap"=>TRUE)); 
     $myPicture->drawPlotChart(array(
       "RecordImageMap"=>TRUE,"DisplayValues"=>FALSE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)
	 );
	 // Write the chart legend  
	 $myPicture->drawLegend(230,320,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL)); 
	 // Render the picture (choose the best way)  
	 //$myPicture->autoOutput("grafico/lib/pictures/example.drawLineChart.simple.png");

     /**ETIQUETAS !!!!!!!!!!! **************************************************************************////
     /**************************************************************************************************/
     /**************************************************************************************************/
	 $LabelSettings = array("NoTitle"=>TRUE,"DrawSerieColor"=>FALSE,"FontName"=>"grafico/lib/fonts/verdanab.ttf"); 
 	 $myPicture->writeLabel(array("INDICE PROMEDIO CR"),array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17),$LabelSettings); 









	 if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   exit;
	 }	 
	 if(isset($_GET["ImageMap"])){
	   $myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");    	
	 } else {
	   //$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	   if($opcionSalida=='img'){
	     $myPicture->render($pathExportacion."PCRPI_".$identificador.".png");	   		
	     $myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	     exit;
	   }elseif($opcionSalida=='nombre'){
	     $myPicture->render($pathExportacion."PCRPI_".$identificador.".png");
	      echo "PCRPI_".$identificador.'.png';
	      exit;		   	
	   }
	 }					
		break;		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
	case 'PR':
		function sortByOrder($a, $b) {
		  return $b['valor'] - $a['valor'];
		}	
		//barras , normal
		$arrParameters = array();
	    $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);		
		$arrVariables = array();
		$arrResultados = DB_NEGOCIO_obtenerVariables();
		$arrValoresPriorizacion = array();
		$arrValoresCalculo = array();		 
		$iContadorReversa = count($arrResultados);		 
		foreach($arrResultados as $row){
		  //$arrVariables[] 			= $row['nombre'];
		  $arrValoresCalculo[] = $iContadorReversa;
		  $iContadorReversa--;		   
		}		
		$arrResP = DB_RESULT_obtenerUsuarioxPriorizacionxVariable($idCliente,$arrParameters);
		$arrResultadosPriorizacion = array();  
		foreach($arrResP as $key=>$value){
		  //Convertir los valores e irlos acumulando
		  if(!isset($arrResultadosPriorizacion[$value["id_variable"]])){
		    $arrResultadosPriorizacion[$value["id_variable"]] = 0;	
		  }
		  $arrResultadosPriorizacion[$value["id_variable"]]+= $arrValoresCalculo[$value["valor"]-1]; 
		}
		$arrDatosGrafico = array();
		foreach($arrResultados as $key => $value){
		  //$arrDatosGrafico[] = $arrResultadosPriorizacion[$row["id"]];
		  $arrResultados[$key]["valor"] = $arrResultadosPriorizacion[$arrResultados[$key]["id"]];  			
 		}
		usort($arrResultados,'sortByOrder');
		$iContador = 0;
	    $arrParametersPvPi = DB_PARAMETROS_obtenerParametro($idCliente,'PVPI');
		$arrPromediosVariable = DB_RESULT_obtenerPromedioPorVariable($idCliente,$arrParametersPvPi);
		$arrPromedios = array();
		foreach ($arrPromediosVariable as $key => $value){
		  $arrPromedios[$value['id_variable']] = $value['promedio']; 			
		}
		$arrColoresBarras =array();
		$arrEscala = DB_ESCALA_obtenerDatosEscala($arrParameters['id_escala']);
		//var_dump($arrEscala);
		//exit;
		foreach($arrResultados as $row){
		  $arrVariables[] 	 = ($iContador+1)."º  ".$row['nombre'];
		  $arrDatosGrafico[] = $row['valor'];
		  foreach($arrEscala as $key => $value){
		    if($arrPromedios[$row['id']]>=$value['minimo'] && $arrPromedios[$row['id']]<=$value['maximo']){
		      $arrColor = explode(',',$value['color']);	
		      $arrColoresBarras[] = array(
		        'R'     => $arrColor[0]
		       ,'G'     => $arrColor[1]
		       ,'B' 	=> $arrColor[2]		   
		       ,"Alpha" => 100 		    
		      );			    
			  break;	
		    }
		  } 



		  $iContador++;
		}
		//var_dump($arrResultados);
		//var_dump($arrColoresBarras);
		//exit;
		
		$MyData = new pData();  
		//$MyData->addPoints(array(150,220,300,250,420,200,300,200,100,25,56,86,32),"Server A",$colorPriorizacion);
		$MyData->addPoints($arrDatosGrafico,"Priorizacion",$colorPriorizacion);
		//$MyData->setAxisName(0,"Hits");
		$MyData->addPoints($arrVariables,"Months");
		$MyData->setSerieDescription("Months","Month");
		$MyData->setAbscissa("Months");
		
  	   ////////////////// EXPORTAR DATOS /////////////////////////////
	   if(isset($_GET['datos'])){
	     $arrTitulos = array("Variable","Priorización");
	     $arrColumnas = array(
	       $arrVariables,
	       $arrDatosGrafico		
	     );
	     renderizarExcel($arrColumnas,$arrTitulos);	   
	   }
	   ////////////////// EXPORTAR DATOS /////////////////////////////		
		
		// Create the pChart object 
		$myPicture = new pImage($iAnchoGraficoPr ,$iAltoGraficoPr ,$MyData);
		$myPicture->initialiseImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");		
		// Turn of Antialiasing 
		$myPicture->Antialias = FALSE;
		// Add a border to the picture 
		
		//$myPicture->drawGradientArea(0,0,700,350,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
		//$myPicture->drawGradientArea(0,0,700,350,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));
		
		$myPicture->drawRectangle(0,0,$iAnchoGraficoPr-1,$iAltoGraficoPr-1,array("R"=>0,"G"=>0,"B"=>0));
		// Set the default font
		$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>7.5));//tamaño en escala
		// Define the chart area
		$myPicture->setGraphArea(60,40,650,200);
		// Draw the scale 
		$scaleSettings = array(
		  "GridR"=>200
		 ,"GridG"=>200
		 ,"GridB"=>200
		 ,"DrawSubTicks"=>TRUE
		 ,"CycleBackground"=>TRUE
		 ,'LabelRotation'=>60
		 //,'XMargin'=>0
		 ,'EspacioLabelLineaX'=>7			
		 ,'EspacioLabelLineaY'=>7		 
		 ,'OuterTickWidth' =>4
		);
		$myPicture->drawScale($scaleSettings);
		// Write the chart legend 
		//$myPicture->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
		//$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>16));
		//$myPicture->drawText(280,33,"PRIORIZACIÓN",array("R"=>0,"G"=>0,"B"=>0));		
		
		// Turn on shadow computing  
		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
		// Draw the chart 
		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
		
		$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>7));//tamaño fuente valores barra
				
		$settings = array(
		  "Surrounding"		 => 200
		 ,"InnerSurrounding" => 0
		 ,"RecordImageMap"	 => TRUE
		 ,"DisplayValues"	 => TRUE
		 ,"DisplayOffset"	 => 5
		 ,"OverrideColors"	 => $arrColoresBarras	 		 
		);
		$myPicture->drawBarChart($settings);
		// Render the picture (choose the best way)
	    if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	      exit;
	    }		 
		if(isset($_GET["ImageMap"])){
		  $myPicture->dumpImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");
		} else {
		  //$myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawBarChart.png");
	      if($opcionSalida=='img'){
			$myPicture->render($pathExportacion."PR_".$identificador.".png");
	        $myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawBarChart.png");
	        exit;
	      }elseif($opcionSalida=='nombre'){
	        $myPicture->render($pathExportacion."PR_".$identificador.".png");
	        echo "PR_".$identificador.'.png';
	        exit;		   	
	   	  }
		}
		break;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
 	case 'DSG':
	  // Create and populate the pData object 
	  $arrParameters = array();
	  $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);	  
	  if(!empty($idCr)){
		$arrParameters['id_cr'] = $idCr; 	
	  }
	  if(!empty($idEstamento)){
		$arrParameters['id_estamento'] = $idEstamento; 	
	  }
	  if(!empty($arrFechasAntiguedad)){
		$arrParameters['antiguedad'] = $arrFechasAntiguedad;			
	  } 		   	 			
	  if(!empty($idCjuridica)){
		$arrParameters['id_juridica']  = $idCjuridica;			
	  } 		
	  if(!empty($idSexo)){
		$arrParameters['id_sexo'] = $idSexo;			
	  } 			  	   		  	  
	  $arrResult = DB_RESULT_obtenerClasificacionUsuariosEscala($idCliente,$arrParameters);
	  //var_dump($arrResult);
	  //exit;	  
	  $arrValores = array();
	  $arrValoresParaColores = array();
	  foreach($arrResult as $key => $value){
		$arrLabels[]  = $value['nombre_escala'];		  	
		$arrValores[] = $value['cantidad'];
		$arrValoresParaColores[] = $value['valor'];	
	  }
	  /* para que la escala se muestre en orden ascendente */
	  	$arrEscala = DB_ESCALA_obtenerDatosEscala($arrParameters['id_escala']);
	    $arrLeyenda = array();
	    $arrValueLeyenda = array();
	    $arrPaletteLeyenda = array();				
	    foreach($arrEscala as $llave=>$valorEscala){
		  $arrColor = explode(",",$valorEscala['color']);
		  $arrValueLeyenda[] =  $valorEscala['nombre_escala'];
		  $arrPaletteLeyenda[] =  array(
		    'R' => $arrColor[0]
		   ,'G' => $arrColor[1]
		   ,'B' => $arrColor[2]
		  );
		}
		$arrLeyenda['leyenda'] = $arrValueLeyenda;
		$arrLeyenda['palette'] = $arrPaletteLeyenda;		
		
	    /* para que la escala se muestre en orden ascendente */
		$MyData = new pData();   
		$MyData->addPoints($arrValores,"ScoreA",null,null,null,array(),$arrLeyenda);  
		//exit;
		$MyData->setSerieDescription("ScoreA","Application A");
		// Define the absissa serie 
		$MyData->addPoints($arrLabels,"Labels");
		$MyData->setAbscissa("Labels");
		
		
  	    ////////////////// EXPORTAR DATOS /////////////////////////////
	    if(isset($_GET['datos'])){
	      $arrTitulos  = array("Clasificación","Cantidad");
	      $arrColumnas = array(
	        $arrLabels,
	        $arrValores		
	      );
	      renderizarExcel($arrColumnas,$arrTitulos);	   
	    }
	    ////////////////// EXPORTAR DATOS /////////////////////////////			
		// Create the pchart object 
		$myPicture = new pImage($iAnchoGraficoDsg,$iAltoGraficoDsg,$MyData,TRUE);
		$myPicture->initialiseImageMap("ImageMapPieChart",IMAGE_MAP_STORAGE_FILE,"PieChart",$path."pictures/");		
	    $myPicture->Antialias = true;
		// Draw a solid background 
		$Settings = array("R"=>173, "G"=>152, "B"=>217, "Dash"=>1, "DashR"=>193, "DashG"=>172, "DashB"=>237);
		//$myPicture->drawFilledRectangle(0,0,$iAnchoGraficoDsg,$iAltoGraficoDsg,$Settings);
		// Draw a gradient overlay 
		$Settings = array("StartR"=>209, "StartG"=>150, "StartB"=>231, "EndR"=>111, "EndG"=>3, "EndB"=>138, "Alpha"=>50);		
		//$myPicture->drawGradientArea(0,0,$iAnchoGraficoDsg,$iAltoGraficoDsg,DIRECTION_VERTICAL,$Settings);

		//$myPicture->drawGradientArea(0,65,$iAnchoGraficoDsg,$iAltoGraficoDsg,DIRECTION_VERTICAL,array("StartR"=>255,"StartG"=>255,"StartB"=>255,"EndR"=>248,"EndG"=>165,"EndB"=>95,"Alpha"=>100));
				
		// Add a border to the picture 
		$myPicture->drawRectangle(0,0,$iAnchoGraficoDsg-1,$iAltoGraficoDsg-1,array("R"=>0,"G"=>0,"B"=>0));
		// Write the picture title  
		$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>6));

		// Set the default font properties  
		$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>8,"R"=>80,"G"=>80,"B"=>80));
		// Create the pPie object  
		$PieChart = new pPie($myPicture,$MyData);
		// Define the slice color
		$arrEscala = DB_ESCALA_obtenerDatosEscala(1);
		//$arrValoresParaColores[]
		for($i=0;$i<count($arrValoresParaColores);$i++){
		  $PieChart->setSliceColor($i,$arrColores[$arrValoresParaColores[$i]]);			
		}

		/*
		$PieChart->setSliceColor(0,$arrColores["0"]);
		$PieChart->setSliceColor(1,$arrColores["1"]);
		$PieChart->setSliceColor(2,$arrColores["2"]);
		$PieChart->setSliceColor(3,$arrColores["3"]);
		$PieChart->setSliceColor(4,$arrColores["4"]);
		*/
		// Draw a simple pie chart
		//PIE_VALUE_PERCENTAGE
		//$PieChart->draw3DPie(320,155,array("RecordImageMap"=>TRUE,"SecondPass"=>TRUE,"DrawLabels"=>FALSE,"Border"=>TRUE,"WriteValues"=>PIE_VALUE_NATURAL,"Radius"=>130,"ValuePosition"=>PIE_VALUE_OUTSIDE ));
		//$PieChart->draw3DPie(320,185,array(
		$PieChart->draw2DPie(320,155,array(				  
		  "WriteValues"   =>  PIE_VALUE_PERCENTAGE
		  ,"Radius"		   => 110//110
		  ,"ValuePosition" => PIE_VALUE_OUTSIDE
		  ,"ValuePadding"  => 40//30
		  ,"DataGapAngle"  => 14//10
		  ,"DataGapRadius" => 6
		  ,"ValueR"		   => 0
		  ,"ValueG"		   => 0
		  ,"ValueB"		   => 0
		  ,"ValueAlpha"    =>100	
		  ,"Precision"	   =>1//1
		));


		$myPicture->setShadow(TRUE,array("X"=>2,"Y"=>4,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>80));		
		$PieChart->draw2DPie(320,155,array(		
		   "RecordImageMap"=> TRUE
		  ,"SecondPass"	   => TRUE	
		  ,"DrawLabels"	   => FALSE
		  ,"Border"		   => TRUE
		  ,"BorderR"	   => 0
		  ,"BorderG"	   => 0
		  ,"BorderB"	   => 0
		  ,"WriteValues"   => PIE_VALUE_NATURAL//PIE_VALUE_PERCENTAGE
		  ,"Radius"		   => 110
		  ,"ValuePosition" => PIE_VALUE_OUTSIDE //PIE_VALUE_INSIDE
		  ,"ValuePadding"  => 20//30
		  ,"DataGapAngle"  => 10
		  ,"DataGapRadius" => 6
		  ,"ValueR"		   => 133
		  ,"ValueG"		   => 133
		  ,"ValueB"		   => 133
		  ,"ValueAlpha"    => 100	
		  ,"Precision"	   => 1
		));
		
		// Enable shadow computing  
		$myPicture->setShadow(TRUE,array("X"=>30,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>100));
		// Write the legend 
		$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>13));
		$myPicture->setShadow(TRUE,array("X"=>10,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>80));
		//$myPicture->drawText(330,30,"PORCENTAJE DE DISTRIBUCIÓN DE SATISFACCIÓN GENERAL",array("DrawBox"=>FALSE,"BoxRounded"=>FALSE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
		//Write the legend box
	  
		$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>9,"R"=>255,"G"=>255,"B"=>255));
		$PieChart->drawPieLegend(40,320,array(
		  "Style"=>LEGEND_NOBORDER,
		  "Mode"=>LEGEND_HORIZONTAL,
		  "FontR"=>0,
		  "FontG"=>0,
		  "FontB"=>0		  		  
		));
		//exit;
		//Render the picture (choose the best way)
	 	if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   	  exit;
	 	}		 
		if(isset($_GET["ImageMap"])){
		  $myPicture->dumpImageMap("ImageMapPieChart",IMAGE_MAP_STORAGE_FILE,"PieChart",$path."pictures/");    	
		} else {
		  //$myPicture->autoOutput("ImageMapPieChart",$path."pictures/exampledrawPieChart.png");
		  if($opcionSalida=='img'){
		    $myPicture->render($pathExportacion."DSG_".$identificador.".png");		  		
		    $myPicture->autoOutput("ImageMapPieChart",$path."pictures/exampledrawPieChart.png");
		    exit;
		  }elseif($opcionSalida=='nombre'){
		    $myPicture->render($pathExportacion."DSG_".$identificador.".png");
		    echo "DSG_".$identificador.'.png';
		    exit;		   	
		  }
		}				
		break;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
 	case 'DSV':
		//Stacked
	  	//$arrResultados 		 = DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente);
		$arrParameters = array();
	  $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);		
	  if(!empty($idCr)){
		$arrParameters['id_cr'] = $idCr; 	
	  }	  			
	  if(!empty($idEstamento)){
		$arrParameters['id_estamento'] = $idEstamento; 	
	  }
	  if(!empty($arrFechasAntiguedad)){
		$arrParameters['antiguedad'] = $arrFechasAntiguedad;			
	  }   	 			
	  	$arrClasificacion    = DB_RESULT_obtenerClasificacionVariableEscala($idCliente,$arrParameters); 
	  	//var_dump($arrClasificacion);
	  	$arrVariables  		 = DB_NEGOCIO_obtenerDimensionesVariables();
	  	//$arrClasificacion 	 = array();
	  	$arrPromUsuario 	 = array();
	  	$arrClasificacionUsr = array();
	    $arrEscala = DB_ESCALA_obtenerDatosEscala(1);	  
	    $arrLeyenda = array();
		  
		 /* para que la escala se muestre en orden ascendente */
	    foreach($arrEscala as $llave=>$valorEscala){
		  $arrColor = explode(",",$valorEscala['color']);
		  $arrLeyenda[$valorEscala['nombre_escala']] = array(
		    'color'=> array(
		        'R' => $arrColor[0]
		       ,'G' => $arrColor[1]
		       ,'B' => $arrColor[2]
			)
			,'Ticks'=>0
			,'Weight'=>0
			,'mostrar'=>true
			,'isDrawable'=>true
			,"Description"=>$valorEscala['nombre_escala']
		  );
		}
	    /* para que la escala se muestre en orden ascendente */

	      $arrValores = array(
			 "MI"=>array()
			,"I" =>array()			  
			,"NI"=>array()
			,"NI1"=>array()
			,"NI2"=>array()			
			,"S" =>array()
			,"MS"=>array()			  			  			  
		  );
	      $arrValoresP = array(
			 "MI"=>array()
			,"I" =>array()			  
			,"NI"=>array()
			,"NI1"=>array()
			,"NI2"=>array()			
			,"S" =>array()
			,"MS"=>array()			  			  			  
		  );		  
		 $arrResultadosV = DB_NEGOCIO_obtenerVariables();
		 $total = 0;
		 $mi = 0;
		 $i = 0;
		 $ni = 0;
		 $s = 0;
		 $ms = 0;
		 //var_dump($arrClasificacion);
		 foreach($arrResultadosV as $row){
		   $arrVariablesEje[] = $row['nombre'];
		   //por cada una de las divisiones (escala) hacer un array
		   //en los valores 2 hacer un doble array
		   $total = 0;
		   $mi = isset($arrClasificacion[$row['id']]['0'])?$arrClasificacion[$row['id']]['0']:0;
		   $total+=isset($arrClasificacion[$row['id']]['0'])?$arrClasificacion[$row['id']]['0']:0;
		   $i  = isset($arrClasificacion[$row['id']]['1'])?$arrClasificacion[$row['id']]['1']:0;		   		   
		   $total+=isset($arrClasificacion[$row['id']]['1'])?$arrClasificacion[$row['id']]['1']:0;
		   $ni = isset($arrClasificacion[$row['id']]['2'])?$arrClasificacion[$row['id']]['2']:0;		   
		   $total+=isset($arrClasificacion[$row['id']]['2'])?$arrClasificacion[$row['id']]['2']:0;
		   $s  = isset($arrClasificacion[$row['id']]['3'])?$arrClasificacion[$row['id']]['3']:0;		   
		   $total+=isset($arrClasificacion[$row['id']]['3'])?$arrClasificacion[$row['id']]['3']:0;
		   $ms = isset($arrClasificacion[$row['id']]['4'])?$arrClasificacion[$row['id']]['4']:0;		   
		   $total+=isset($arrClasificacion[$row['id']]['4'])?$arrClasificacion[$row['id']]['4']:0;		   		   		   		   		   
		   
		   $mi = $mi/$total;
		   $i  = $i/$total;
		   $ni = $ni/$total;
		   $s  = $s/$total;
		   $ms = $ms/$total;		   		   		   
		   
		   $arrValores['MI'][]  = isset($arrClasificacion[$row['id']]['0'])?$arrClasificacion[$row['id']]['0']*-1:VOID;
		   $arrValores['I'][]   = isset($arrClasificacion[$row['id']]['1'])?$arrClasificacion[$row['id']]['1']*-1:VOID;
		   $arrValores['NI1'][] = isset($arrClasificacion[$row['id']]['2'])?($arrClasificacion[$row['id']]['2']/2)*-1:VOID;
		   $arrValores['NI'][]  = isset($arrClasificacion[$row['id']]['2'])?$arrClasificacion[$row['id']]['2']:VOID;
		   $arrValores['NI2'][] = isset($arrClasificacion[$row['id']]['2'])?$arrClasificacion[$row['id']]['2']/2:VOID;
		   $arrValores['S'][]   = isset($arrClasificacion[$row['id']]['3'])?$arrClasificacion[$row['id']]['3']:VOID;
		   $arrValores['MS'][]  = isset($arrClasificacion[$row['id']]['4'])?$arrClasificacion[$row['id']]['4']:VOID;

		   $arrValoresP['MI'][] = empty($mi)? VOID : round($mi*100,2);
		   $arrValoresP['I'][]  = empty($i)? VOID : round($i*100,2);
		   $arrValoresP['NI'][] = empty($ni)? VOID : round($ni*100,2);
		   $arrValoresP['S'][]  = empty($s)? VOID : round($s*100,2);
		   $arrValoresP['MS'][] = empty($ms)? VOID : round($ms*100,2);
		 }
		 $MyData = new pData();		
	     /*
		 $MyData->addPoints(array(3,12,15,8,5,5,1,2,3,4,5,6,7),"Ni Satisfecho Ni Insatisfecho",$colorNI,true,true,array(6,24,30,16,10,10,2,4,6,8,10,12,14)); 
		 $MyData->addPoints(array(4,17,6,12,8,3,1,2,3,4,5,6,7),"Satisfecho",$colorS,true,false);
		 $MyData->addPoints(array(4,17,6,12,8,3,1,2,3,4,5,6,7),"Muy Satisfecho",$colorMS,true,false); 
		 $MyData->addPoints(array(-3,-12,-15,-8,-5,-5,-1,-2,-3,-4,-5,-6,-7),"NOMOSTRAR",$colorNI,false,false);
		 $MyData->addPoints(array(-15,-7,-5,-18,-19,-22,-1,-2,-3,-4,-5,-6,-7),"Insatisfecho",$colorI,true,false);
		 $MyData->addPoints(array(-15,-7,-5,-18,-19,-22,-1,-2,-3,-4,-5,-6,-7),"Muy Insatisfecho",$colorMI,true,false);		  
		 */
		 /*
		 $MyData->addPoints($arrValores["NI2"],"Ni Satisfecho Ni Insatisfecho",$colorNI,true,true,$arrValoresP["NI"],$arrLeyenda); 
		 $MyData->addPoints($arrValores["S"],"Satisfecho",$colorS,true,false,$arrValoresP["S"],$arrLeyenda);
		 $MyData->addPoints($arrValores["MS"],"Muy Satisfecho",$colorMS,true,false,$arrValoresP["MS"],$arrLeyenda); 
		 $MyData->addPoints($arrValores["NI1"],"NOMOSTRAR",$colorNI,false,false,array(),$arrLeyenda);
		 $MyData->addPoints($arrValores["I"],"Insatisfecho",$colorI,true,false,$arrValoresP["I"],$arrLeyenda);
		 $MyData->addPoints($arrValores["MI"],"Muy Insatisfecho",$colorMI,true,false,$arrValoresP["MI"],$arrLeyenda);
		 */
		 $MyData->addPoints($arrValores["NI2"],"Ni Satisfecho Ni Insatisfecho",$arrColores['2'],true,true,$arrValoresP["NI"],$arrLeyenda); 
		 $MyData->addPoints($arrValores["S"],"Satisfecho",$arrColores['3'],true,false,$arrValoresP["S"],$arrLeyenda);
		 $MyData->addPoints($arrValores["MS"],"Muy Satisfecho",$arrColores['4'],true,false,$arrValoresP["MS"],$arrLeyenda); 
		 $MyData->addPoints($arrValores["NI1"],"NOMOSTRAR",$arrColores['2'],false,false,array(),$arrLeyenda);
		 $MyData->addPoints($arrValores["I"],"Insatisfecho",$arrColores['1'],true,false,$arrValoresP["I"],$arrLeyenda);
		 $MyData->addPoints($arrValores["MI"],"Muy Insatisfecho",$arrColores['0'],true,false,$arrValoresP["MI"],$arrLeyenda);
		 		  

		 $MyData->setAxisName(0,"Porcentaje de Personas"); 
		 //$MyData->addPoints(array("Variable1 bla","Variable2  bla","Variable3  bla","Variable4 bla","Variable5 bla","Variable6 bla"),"Labels"); 
		 $MyData->addPoints($arrVariablesEje,"Labels"); 
		 $MyData->setSerieDescription("Labels","Variables"); 
		 $MyData->setAbscissa("Labels");
		 
  	    ////////////////// EXPORTAR DATOS /////////////////////////////
	    if(isset($_GET['datos'])){
	      $arrTitulos  = array("Variables",'Muy Insatisfecho','Insatisfecho','Ni Insatisfecho Ni Satisfecho','Satisfecho','Muy Satisfecho');
	      $arrColumnas = array(
	        $arrVariablesEje,
	        $arrValoresP['MI'],		
	        $arrValoresP['I'],	        
	        $arrValoresP['NI'],
	        $arrValoresP['S'],	        
	        $arrValoresP['MS']	        
	      );
	      renderizarExcel($arrColumnas,$arrTitulos);	   
	    }
	    ////////////////// EXPORTAR DATOS /////////////////////////////			 
		 //#FF0000
		 //rojo
		 //#0000CC
		 //blue
		 //#FFFFFF
		 //blanco
		 $myPicture = new pImage(700,630,$MyData); 		 
		 //if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"]))
		  //$myPicture->dumpImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");
		 $myPicture->initialiseImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");
	     $myPicture->drawRectangle(0,0,699,629,array("R"=>0,"G"=>0,"B"=>0));		
		 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
		 //$myPicture->drawFilledRectangle(0,0,700,630,$Settings); 
		 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50); 
		 
		 //$myPicture->drawGradientArea(0,0,700,630,DIRECTION_VERTICAL,$Settings); 
		 
		 //$myPicture->drawGradientArea(1,65,$iAnchoGraficoDsv-2,$iAltoGraficoDsv-3,DIRECTION_VERTICAL,array("StartR"=>255,"StartG"=>255,"StartB"=>255,"EndR"=>248,"EndG"=>165,"EndB"=>95,"Alpha"=>100));
		 //$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80)); 
		 //$myPicture->drawRectangle(0,0,699,629,array("R"=>0,"G"=>0,"B"=>0)); 
		 //$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>6)); 
		 //$myPicture->drawText(10,13,"Psicus",array("R"=>255,"G"=>255,"B"=>255)); 
		 $myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>8)); 
		 //$myPicture->drawText(340,25,"Porcentaje de Distribución de satisfacción por variable",array("FontSize"=>13,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE)); 
		
		 $myPicture->setGraphArea(60,30,650,400); 
		 $myPicture->drawFilledRectangle(60,30,650,400,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10)); 
		 $myPicture->drawScale(array(
		    "DrawSubTicks"=>TRUE
		   ,"Mode"=>SCALE_MODE_ADDALL
		   ,'LabelRotation'=>70
		   ,'RemoveYAxis'=>true
		   ,'EspacioLabelLineaX'=>6			
		   ,'EspacioLabelLineaY'=>5		 
		   ,'OuterTickWidth' =>2
		   ,"GridR"=>180
		   ,"GridG"=>180
		   ,"GridB"=>180
		 )); 
		 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10)); 
		 $myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>7));
		 $myPicture->drawStackedBarChart(array(
		    "RecordImageMap"=>TRUE
		   ,"DisplayValues"=>TRUE
		   ,"DisplayColor"=>DISPLAY_AUTO
		   ,"Rounded"=>FALSE
		   ,"Surrounding"=>60
		   ,"DisplayOrientation"=>ORIENTATION_HORIZONTAL 
		  ));
		 $myPicture->setShadow(FALSE); 
		  
		 $myPicture->drawLegend(75,610,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));  
		 //$myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawStackedBarChart.png");
	 	 if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   	   exit;
	 	 }		 
		 if(isset($_GET["ImageMap"])){
		   $myPicture->dumpImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");    	
		 }else{
		   //$myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawStackedBarChart.png");
		   if($opcionSalida=='img'){
		     $myPicture->render($pathExportacion."DSV_".$identificador.".png");		   		
		     $myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawStackedBarChart.png");
		     exit;
		   }elseif($opcionSalida=='nombre'){
		     $myPicture->render($pathExportacion."DSV_".$identificador.".png");
		      echo "DSV_".$identificador.'.png';
		      exit;		   	
		   }	
		 }			 	
		break;		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
 	case 'TC':
	 $arrParameters = array();
	 $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,$opcionGrafico);	 
	 if(!empty($idCr)){
	   $arrParameters['id_cr'] = $idCr; 	
	 }
	 if(!empty($idEstamento)){
	   $arrParameters['id_estamento'] = $idEstamento; 	
	 }
	 if(!empty($arrFechasAntiguedad)){
	   $arrParameters['antiguedad'] = $arrFechasAntiguedad;			
	 }		 
	 if(!empty($idCjuridica)){
	   $arrParameters['id_juridica'] = $idCjuridica;			
	 } 		
	 if(!empty($idSexo)){
	   $arrParameters['id_sexo'] = $idSexo;			
	 } 			  	   		  	 
	 // Create and populate the pData object  
	 //aca van las variables
	 $arrVariables = array();
	 $arrResultados = DB_NEGOCIO_obtenerVariables();
	 foreach($arrResultados as $row){
	   $arrVariables[] = $row['nombre'];	  	
	 }	
	 //calcular mediana, promedio y moda de cada una de las variables
	 $arrResultadosVariable  = DB_RESULT_obtenerTodosPromediosVariable($idCliente,$arrParameters);		
	 $arrValores = array();	  
	 $arrValoresPromedio = array();
	 $arrValoresMediana  = array();
	 $arrValoresModa 	 = array();
	  
	  foreach($arrResultados as $key => $value){//por cada una de las variables
	    $arrValoresPromedio[] = redondeo(calcular_promedio($arrResultadosVariable[$value['id']]),2);
	    $arrValoresMediana[]  = redondeo(calcular_mediana($arrResultadosVariable[$value['id']]),2);
	    $arrValoresModa[] 	  = redondeo(calcular_moda($arrResultadosVariable[$value['id']]),2);		
      }
	   //var_dump($arrValoresMediana);
	   //var_dump($arrValoresModa);
	   //exit;
	 $MyData = new pData();
	 $MyData->addPoints($arrValoresPromedio,"MEDIA",$colorMedia); 
	 $MyData->addPoints($arrValoresMediana,"MEDIANA",$colorMediana); 
	 $MyData->addPoints($arrValoresModa,"MODA",$colorModa);	    
	 //$MyData->addPoints(array(0.4,0.1,0.2,0.12,0.8,0.3,0.12,0.8,0.3,0.12,0.8,0.3,0.5),"MEDIA",$colorMedia); 
	 //$MyData->addPoints(array(0.3,0.12,0.15,0.8,0.5,0.5,0.12,0.15,0.8,0.12,0.15,0.8,0.14),"MEDIANA",$colorMediana); 
	 //$MyData->addPoints(array(0.2,0.7,0.5,0.18,0.19,0.22,0.7,0.5,0.18,0.10,0.21,0.11,0.9),"MODA",$colorModa);
	  
	 //$MyData->setSerieTicks("Probe 2",4);
	 //$MyData->setSerieWeight("Probe 3",2);
	 //$MyData->setAxisName(0,"Temperatures");
	 $MyData->setAxisName(0,"");
	 
 
	 $MyData->addPoints($arrVariables,"Labels");
	 
  	    ////////////////// EXPORTAR DATOS /////////////////////////////
	    if(isset($_GET['datos'])){
	      $arrTitulos  = array("Variables",'Media','Mediana','Moda');
	      $arrColumnas = array(
	        $arrVariables,
	        $arrValoresPromedio,		
	        $arrValoresMediana,	        
	        $arrValoresModa        
	      );
	      renderizarExcel($arrColumnas,$arrTitulos);	   
	    }
	    ////////////////// EXPORTAR DATOS /////////////////////////////		 
	 
	 $MyData->setSerieDescription("Labels",""); 
	 $MyData->setAbscissa("Labels"); 
	 // Create the pChart object  
	 $myPicture = new pImage($iAnchoGraficoTc,$iAltoGraficoTc,$MyData); 
	 // Turn of Antialiasing  
	 $myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");	 
	 $myPicture->Antialias = FALSE; 	
	 
	 
	 
	 // Add a border to the picture  
	 $myPicture->drawRectangle(0,0,$iAnchoGraficoTc-1,$iAltoGraficoTc-1,array("R"=>0,"G"=>0,"B"=>0)); 
	 // Write the chart title   
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>9)); 
	 //$myPicture->drawText(330,35,"MEDIDAS DE TENDENCIA CENTRAL POR VARIABLE",array("FontSize"=>16,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE)); 
	 // Set the default font  
	 //$myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/pf_arma_five.ttf","FontSize"=>6)); 
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>8));
	 
	 
	 
	 //Fondo Satisfecho  
	 $myPicture->drawFilledRectangle(150,20,690,104,$colorFondoSatisfecho);
	 //Fondo Insatisfecho
	 $myPicture->drawFilledRectangle(150,146,690,230,$colorFondoInsatisfecho);

	 //Titulos
	 $myPicture->drawText(60,50,"Muy Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(60,85,"Satisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
	 $myPicture->drawText(60,135,"Ni Satisfecho \nNi Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
	 $myPicture->drawText(60,162,"Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	 $myPicture->drawText(60,200,"Muy Insatisfecho",array("FontSize"=>9,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));	 
     //lineas separadoras
	 $arrFormato = array(
	   "Ticks"=>0
	  ,"Weight"=>2 
	 );
	 
	 $myPicture->drawLine(150,20,690,20,$arrFormato);
	 $myPicture->drawLine(150,62,690,62,$arrFormato);
	 $myPicture->drawLine(150,104,690,104,$arrFormato);
	 $myPicture->drawLine(150,146,690,146,$arrFormato);		 
	 $myPicture->drawLine(150,188,690,188,$arrFormato);
	 
	 // Define the chart area  
	 $myPicture->setGraphArea(150,20,690,230); 
	 // Draw the scale  
	 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));
	 $scaleSettings = array(
	    "XMargin"=>10
	   ,"YMargin"=>0
	   ,"Floating"=>TRUE
	   ,"GridR"=>200
	   ,"GridG"=>200
	   ,"GridB"=>200
	   ,"DrawSubTicks"=>TRUE
	   ,"CycleBackground"=>TRUE
	   ,'LabelRotation'=>60
	   ,"Mode"=>SCALE_MODE_MANUAL
	   ,"ManualScale"=>$AxisBoundaries
	   ,"MinDivHeight"=>15
		 ,'EspacioLabelLineaX'=>6			
		 ,'EspacioLabelLineaY'=>5		 
		 ,'OuterTickWidth' =>4	   
	   	   
	 ); 
	 
	 $myPicture->drawScale($scaleSettings); 
	 // Turn on Antialiasing  
	 $myPicture->Antialias = TRUE; 
	 // Draw the line chart 
	 $myPicture->drawLineChart(array("RecordImageMap"=>TRUE)); 
     	 $myPicture->drawPlotChart(array("RecordImageMap"=>TRUE,"DisplayValues"=>FALSE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80));

	 // Write the chart legend  
	 $myPicture->drawLegend(270,440,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL)); 
	 // Render the picture (choose the best way)  
	 //$myPicture->autoOutput("grafico/lib/pictures/example.drawLineChart.simple.png");
	 if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   exit;
	 }	 
	 if(isset($_GET["ImageMap"])){
	   $myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");    	
	 } else {
	   //$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	   if($opcionSalida=='img'){
	     $myPicture->render($pathExportacion."TC_".$identificador.".png");	   		
	     $myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	     exit;
	   }elseif($opcionSalida=='nombre'){
	     $myPicture->render($pathExportacion."TC_".$identificador.".png");
	      echo "TC_".$identificador.'.png';
	      exit;		   	
	   }
	 }			 		 		 
	break;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////		



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	case 'ranking':

		$i1=1;
		$i2=2;
		$i3=3;
		$i4=4;
		$i5=5;
		$i6=6;
		$i7=7;
		$i8=8;
		$i9=9;
		$i10=10;
		$i11=11;
		$XX			   = DB_ObtenerPromedioInstituciones($i11);
		$INSTITUCION1  = DB_ObtenerPromedioInstituciones($i1);
		$INSTITUCION2  = DB_ObtenerPromedioInstituciones($i2);
		$INSTITUCION3  = DB_ObtenerPromedioInstituciones($i3);
		$INSTITUCION4  = DB_ObtenerPromedioInstituciones($i4);
		$INSTITUCION5  = DB_ObtenerPromedioInstituciones($i5);
		$INSTITUCION6  = DB_ObtenerPromedioInstituciones($i6);
		$INSTITUCION7  = DB_ObtenerPromedioInstituciones($i7);
		$INSTITUCION8  = DB_ObtenerPromedioInstituciones($i8);
		$INSTITUCION9  = DB_ObtenerPromedioInstituciones($i9);
		$INSTITUCION10 = DB_ObtenerPromedioInstituciones($i10);

		/* Create and populate the pData object */
		$MyData = new pData();

		$MyData->addPoints(array($XX,$INSTITUCION1,$INSTITUCION2,$INSTITUCION3
		,$INSTITUCION4,$INSTITUCION5,$INSTITUCION6
		,$INSTITUCION7,$INSTITUCION8,$INSTITUCION9
		,$INSTITUCION10),"Institucion",$colorPriorizacion);
		//$MyData->addPoints(array(2,6,5,18,19,22),"Probe 3");

		$MyData->addPoints(array('XXXXXXXXX','INSTITUCIÓN 1','INSTITUCIÓN 2','INSTITUCIÓN 3'
									,'INSTITUCIÓN 4','INSTITUCIÓN 5','INSTITUCIÓN 6'
								    ,'INSTITUCIÓN 7','INSTITUCIÓN 8','INSTITUCIÓN 9'
									,'INSTITUCIÓN 10'),"Months");
		$MyData->setSerieDescription("Months","Month");
		$MyData->setAbscissa("Months");

		/* Create the pChart object */
		$myPicture = new pImage($iAnchoGraficoPr ,$iAltoGraficoPr ,$MyData);
		$myPicture->initialiseImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");

		$myPicture->Antialias = FALSE;

		$myPicture->drawRectangle(0,0,$iAnchoGraficoPr-1,$iAltoGraficoPr-1,array("R"=>0,"G"=>0,"B"=>0));
		// Set the default font
		$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>7.5));//tamaño en escala
		// Define the chart area
		$myPicture->setGraphArea(60,40,650,200);
		// Draw the scale
		$scaleSettings = array(
			"GridR"=>200
		,"GridG"=>200
		,"GridB"=>200
		,"DrawSubTicks"=>TRUE
		,"CycleBackground"=>TRUE
		,'LabelRotation'=>60
			//,'XMargin'=>0
		,'EspacioLabelLineaX'=>7
		,'EspacioLabelLineaY'=>7
		,'OuterTickWidth' =>4
		);
		$myPicture->drawScale($scaleSettings);
		// Write the chart legend
		//$myPicture->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
		//$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>16));
		//$myPicture->drawText(280,33,"PRIORIZACIÓN",array("R"=>0,"G"=>0,"B"=>0));

		// Turn on shadow computing
		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
		// Draw the chart
		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

		$myPicture->setFontProperties(array("FontName"=>$path."fonts/verdana.ttf","FontSize"=>7));//tamaño fuente valores barra

		$settings = array(
			"Surrounding"		 => 200
		,"InnerSurrounding" => 0
		,"RecordImageMap"	 => TRUE
		,"DisplayValues"	 => TRUE
		,"DisplayOffset"	 => 5
		,"OverrideColors"	 => $arrColoresBarras
		);
		$myPicture->drawBarChart($settings);

		if(isset($_GET['archivo'])){
			$myPicture->render($path."pictures/".$_GET['archivo'].".png");
			exit;
		}
		if(isset($_GET["ImageMap"])){
			$myPicture->dumpImageMap("ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart",$path."pictures/");
		} else {
			//$myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawBarChart.png");
			if($opcionSalida=='img'){
				$myPicture->render($pathExportacion."ranking_".$identificador.".png");
				$myPicture->autoOutput("ImageMapBarChart",$path."pictures/exampledrawBarChart.png");
				exit;
			}elseif($opcionSalida=='nombre'){
				$myPicture->render($pathExportacion."ranking_".$identificador.".png");
				echo "ranking_".$identificador.'.png';
				exit;
			}
		}





		/* Render the picture (choose the best way) */
		//$myPicture->autoOutput("pictures/example.drawBarChart.png");
		break;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////////////////////////////
 	case 'INV':
	 //Dispersion
	 //Plotchart y Linechart 		
	 //Linea
	 //obtener las respuestas a las preguntas de todos los usuarios 
	 //Aca van las variables
	 $iLimiteCaracteresLabel = 53;
	 $arrParameters = array();
	 $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,'INV');	 

	 $arrVariables = array();
	 $arrResultados = DB_NEGOCIO_obtenerPreguntasCuestionario();
	 $arrResultadosPreguntas = DB_RESULT_obtenerResultadoPregunta($idCliente,$arrParameters);
	 $fSumatoria = 0;
	 $iContador = 0;
	 $iPromedioFinal = 0;
	 $arrValoresPreguntas = array();

	 foreach($arrResultadosPreguntas as $row){
	   $fSumatoria+= $row['promedio'];
	   $iContador++;  	   
	   $arrValoresPreguntas[] = redondeo($row['promedio'],2);
	   //$arrVariables[]	  = $row["orden"]; 	
	   $arrVariables[]		  = (strlen($iContador." - ".$row["nombre"])>$iLimiteCaracteresLabel)?substr($iContador." - ".$row['nombre'],0,$iLimiteCaracteresLabel)."...":$row["nombre"];
	 }

	 $iPromedioFinal = $fSumatoria/$iContador;
	 $MyData = new pData();
	 $MyData->addPoints($arrValoresPreguntas,"Promedio Pregunta",array("R"=>255,"G"=>102,"B"=>0),true,false,array(),array(),'circulo');
	 //$MyData->setSerieShape("Promedio Pregunta",SERIE_SHAPE_CIRCLE);

	 $fTotal 	= 0;	
	 $fPromedio = 0;
	 	 
	 foreach($arrValoresPreguntas as $llave => $valor){
	   $fTotal+=$valor;
	 }
	 
	 $arrGeneral = array();
	 $fPromedio   = $fTotal/count($arrValoresPreguntas);
	 $fDesviacion = desviacionEstandar($arrValoresPreguntas);
	 $fDesviacion1 = $fPromedio+$fDesviacion;
	 $fDesviacion2 = $fPromedio-$fDesviacion;	 
	 $arrDesviacion1 = array();//1ra desv +   
	 $arrDesviacion2 = array();//1ra desv -
	 $arrDesviacion3 = array();//2da +
	 $arrDesviacion4 = array();//2da -
	 $fAcumuladorD3 = 0;
	 $fAcumuladorD4 = 0;	
	 $arrOcultaEscalaColor = array(); 
	 for($i=0;$i<count($arrValoresPreguntas);$i++){
	   $arrGeneral[$i]      = redondeo($fPromedio,2);
	   $arrDesviacion1[$i]  = redondeo($fDesviacion1,2);  	  	 	
	   $arrDesviacion2[$i]  = redondeo($fDesviacion2,2);
	    
	   if($arrValoresPreguntas[$i] >= $fDesviacion1){
		 $arrDesviacion3[] = $arrValoresPreguntas[$i];
		 //$fAcumuladorD3+=$arrValoresPreguntas[$i];
		 $arrOcultaEscalaColor[] = array("R"=>0,"G"=>0,"B"=>202);  	   	
	   }elseif($arrValoresPreguntas[$i] <= $fDesviacion2){
		 $arrDesviacion3[] = $arrValoresPreguntas[$i];
		 //$arrDesviacion4[] = $arrValoresPreguntas[$i];
		 //$fAcumuladorD4+=$arrValoresPreguntas[$i];		    
		 $arrOcultaEscalaColor[] = array("R"=>225,"G"=>0,"B"=>0);		 	   	
	   }else{
		 $arrOcultaEscalaColor[] = array("R"=>0,"G"=>0,"B"=>0);	   	
	     $arrVariables[$i] = "";	   	
	   }	   
	 }
	 //var_dump($arrDesviacion3);
	 //echo "<hr>";
	 //var_dump($arrDesviacion4);	 
	 //exit;
	 $fDesviacion3 = desviacionEstandar($arrDesviacion3);	 
	 //$fDesviacion4 = desviacionEstandar($arrDesviacion4);	 
	 /*
	 if(count($arrDesviacion3)>0){
	   $fDesviacion3 = $fAcumuladorD3/count($arrDesviacion3);
	 }else{
	   $fDesviacion3 = 0;
	 }
	 if(count($arrDesviacion3)>0){
	   $fDesviacion4 = $fAcumuladorD4/count($arrDesviacion4);
	 }else{
	   $fDesviacion4 = 0;	 	
	 }
	  */
	 //var_dump($fDesviacion3);
	 //echo "<hr>";	 
	 //var_dump($fDesviacion4);
	 //exit;

	 $arr2daP1 = array();
	 $arr2daP2 = array();	 
	 for($i=0;$i<count($arrValoresPreguntas);$i++){

	   $arr2daP1[] = redondeo($fPromedio+$fDesviacion3,2); 	
	   $arr2daP2[] = redondeo($fPromedio-$fDesviacion3,2); 	
	 }
	 
	 $MyData->addPoints($arrGeneral,"Promedio General",array("R"=>255,"G"=>102,"B"=>0));
	 $MyData->addPoints($arrDesviacion1,"Primera Desviacion Estandar (+)",array("R"=>0,"G"=>0,"B"=>202));
	 $MyData->addPoints($arrDesviacion2,"Primera Desviacion Estandar (-)",array("R"=>225,"G"=>0,"B"=>0));
	 
	 $MyData->addPoints($arr2daP1,"Segunda Desviacion Estandar (+)",$colorLineaDispersion);
	 $MyData->addPoints($arr2daP2,"Segunda Desviacion Estandar (-)",$colorLineaDispersion);

	 $MyData->setAxisName(0,"");

	 $MyData->addPoints($arrVariables,"Labels");


	 

	 //$MyData->SetBox('yellow','black');
	 //exit;
	 
	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 if(isset($_GET['datos'])){
	   $arrTitulos = array("Numero Pregunta","Puntaje Pregunta","Puntaje General");
	   $arrColumnas = array(
	     $arrVariables,
	     $arrValoresPreguntas,	      	
	     $arrGeneral		
	   );	   
	   renderizarExcel($arrColumnas,$arrTitulos);	   
	 }
	 ////////////////// EXPORTAR DATOS /////////////////////////////
	 
	 $MyData->setSerieDescription("Labels",""); 
	 $MyData->setAbscissa("Labels"); 
	 // Create the pChart object  
	 $myPicture = new pImage($iAnchoGraficoInv2,$iAltoGraficoInv2,$MyData); 
	 // Turn of Antialiasing  
	 $myPicture->initialiseImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");	 
	 $myPicture->Antialias = FALSE; 	
	 // Add a border to the picture  
	 $myPicture->drawRectangle(0,0,$iAnchoGraficoInv2-1,$iAltoGraficoInv2-1,array("R"=>0,"G"=>0,"B"=>0)); 
	 // Write the chart title   
	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>10)); 

	 // Set the default font

	 //Fondo Satisfecho  
	 //$myPicture->drawFilledRectangle(30,40,$iAnchoGraficoInv2-20,140,$colorFondoSatisfecho);
	 //Fondo Insatisfecho
	 //$myPicture->drawFilledRectangle(30,235,$iAnchoGraficoInv2-20,$iAltoGraficoInv2-60,$colorFondoInsatisfecho);
	 //Titulos

	 $myPicture->setFontProperties(array("FontName"=>"grafico/lib/fonts/verdana.ttf","FontSize"=>8)); // 7
	 // Define the chart area  
	 $myPicture->setGraphArea(350,20,$iAnchoGraficoInv2-20,$iAltoGraficoInv2-95); 
	 // Draw the scale
	 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>1));  
	 $scaleSettings = array(
	    "XMargin"=>10
	   ,"YMargin"=>0
	   ,"Floating"=>TRUE
	   ,"GridR"=>200
	   ,"GridG"=>200
	   ,"GridB"=>200
	   ,"DrawSubTicks"=>TRUE
	   ,"CycleBackground"=>TRUE
	   ,'LabelRotation'=>0
	   ,"Mode"=>SCALE_MODE_MANUAL
	   ,"ManualScale"=>$AxisBoundaries
	   ,"MinDivHeight"=>100
	   //,"LabelSkip" => 3
	   ,'EspacioLabelLineaY'=>5
	   ,"Pos"=>SCALE_POS_TOPBOTTOM
	   ,"ScaleSpacing"=>5
	   ,"coloresEscala"=>$arrOcultaEscalaColor
	 ); 
	 $myPicture->drawScale($scaleSettings); 
	 //exit;
	 // Turn on Antialiasing  
	 $myPicture->Antialias = TRUE; 
	 // Draw the line chart 
	 $MyData->setSerieDrawable("Promedio Pregunta",FALSE);
	 $MyData->setSerieDrawable("Promedio General",TRUE);	 
	 //$MyData->setSerieWeight("Puntaje General",1);
	 $MyData->setSerieDrawable("Primera Desviacion Estandar (+)",TRUE);	 
	 $MyData->setSerieDrawable("Primera Desviacion Estandar (-)",TRUE);
	 $MyData->setSerieDrawable("Segunda Desviacion Estandar (+)",TRUE);	 
	 $MyData->setSerieDrawable("Segunda Desviacion Estandar (-)",TRUE);
	 	 
	 $myPicture->drawLineChart(array("RecordImageMap"=>TRUE)); 
     
	 $MyData->setSerieDrawable("Promedio General",FALSE);	 
	 $MyData->setSerieDrawable("Primera Desviacion Estandar (+)",FALSE);	 
	 $MyData->setSerieDrawable("Primera Desviacion Estandar (-)",FALSE);
	 $MyData->setSerieDrawable("Segunda Desviacion Estandar (+)",FALSE);	 
	 $MyData->setSerieDrawable("Segunda Desviacion Estandar (-)",FALSE);
	 $MyData->setSerieDrawable("Promedio Pregunta",TRUE);	 
	 
	 //$arrEscala = DB_ESCALA_obtenerDatosEscalaInventario(1);
 	 	$arrEscala = array(
	   0=>array(
	     "minimo"=>0,
	     "maximo"=>$fDesviacion2,
	     "color_punto"=>"225,0,0"	     
	   ),
	   1=>array(
	     "minimo"=>$fDesviacion2,
	     "maximo"=>$fDesviacion1,
	     "color_punto"=>"255,102,0"	     
	   ),
	   2=>array(
	     "minimo"=>$fDesviacion1,
	     "maximo"=>1,
	     "color_punto"=>"0,0,202"	     
	   )	   
	 );
 	
	 $myPicture->drawPlotChart(array(
	       "RecordImageMap"=>TRUE
	      ,"DisplayValues"=>FALSE
	      ,"PlotBorder"=>TRUE
	      ,"BorderSize"=>2
	      ,"Surrounding"=>-60
	      ,"BorderAlpha"=>80
	      ,"escala" => $arrEscala
		)
	 );

	 // Write the chart legend
	 $MyData->setSerieDrawable("Promedio Pregunta",TRUE);
	 $MyData->setSerieDrawable("Promedio General",TRUE);	 
	 $MyData->setSerieDrawable("Primera Desviacion Estandar (+)",TRUE);	 
	 $MyData->setSerieDrawable("Primera Desviacion Estandar (-)",TRUE);	 
	 $MyData->setSerieDrawable("Segunda Desviacion Estandar (+)",TRUE);	 
	 $MyData->setSerieDrawable("Segunda Desviacion Estandar (-)",TRUE);	 



	 $myPicture->drawLegend(220,$iAltoGraficoInv2-80,array("Style"=>LEGEND_BOX,"BoxSize"=>4,
	 										               "Alpha"=>40,"BorderR"=>0, "BorderG"=>0, "BorderB"=>0)); 

	 // Render the picture (choose the best way)  
	 //$myPicture->autoOutput("grafico/lib/pictures/example.drawLineChart.simple.png");
	 if(isset($_GET['archivo'])){
	   $myPicture->render($path."pictures/".$_GET['archivo'].".png");
	   exit;
	 }
	 if(isset($_GET["ImageMap"])){
	   $myPicture->dumpImageMap("ImageMapLineChart",IMAGE_MAP_STORAGE_FILE,"LineChart",$path."pictures/");    	
	 } else {
	   //$myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	   if($opcionSalida=='img'){
		 $myPicture->render($pathExportacion."INV_".$identificador.".png");
	     $myPicture->autoOutput("ImageMapLineChart",$path."pictures/exampledrawLineChart.png");
	     exit;
	   }elseif($opcionSalida=='nombre'){
	     $myPicture->render($pathExportacion."INV_".$identificador.".png");
	      echo "INV_".$identificador.'.png';
	      exit;		   	
	   }	   
	   
	 }			
	 		
		break;		

	











    default:
	  echo "sin opcion";
	break;	
 }

?>