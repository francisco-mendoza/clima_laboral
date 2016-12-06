<?php 

//---------------------------------------------------------------------------------------------------------------- 
// Verificamos el uso de session, sino lo mandamos a logearse
//session_start();  
//----------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// Inicializamos String de Conexión a la Base de Datos
// ----------------------------------------------------------------------------
include_once(dirname(__FILE__)."/../include/dbconection.php");
include_once(dirname(__FILE__)."/../include/funciones.php");
include_once(dirname(__FILE__)."/../grafico/exportar.php");
error_reporting(0);
//-----------------------------------------------------------------------------
// cargamos las librerias del grafico
//-----------------------------------------------------------------------------

//$idCliente 	 			 = isset($_SESSION['id_cliente'])?$_SESSION['id_cliente']:$_GET['id_cliente'];

function obtenerBalanceSocial($idCliente,$arrParametros = array()){
    $arrParameters 			 	= DB_PARAMETROS_obtenerParametro($idCliente,"BS");
	$iNumeroTotalUsuarios 	 	= DB_USUARIO_cantidadUsrActivos($idCliente);
	$arrClasificacionPreguntas 	= DB_RESULT_obtenerClasificacionUsuariosPregunta($idCliente,$arrParameters);
	$arrResultados 			 	= DB_RESULT_obtenerBalanceSocial($idCliente,$arrParameters);
	$arrGraficos 		  	 	= DB_PARAMETROS_obtenerInfoGraficos();	  
	$strRespuesta			 	= "<div class='' style='' >";
	  
	$strRespuesta.= "<br><h2>".$arrGraficos["BS"]['titulo']."</h2><br>";	  
	$strRespuesta.= "<br><h4>".$arrGraficos["BST1"]['titulo']."</h4>";	  
	$strRespuesta.= "<table id='balancesocial1' class='' width='701px'>";
	$strRespuesta.= "<tr class='estiloAmarilloGrafico'>";
	  $strRespuesta.= "<th class='textoCentrado'>";	  
	  $strRespuesta.= "Dimensi&oacute;n";
	  $strRespuesta.= "</th>";
	  $strRespuesta.= "<th class='textoCentrado'>";
	  $strRespuesta.= "Variable";
	  $strRespuesta.= "</th>";
	  $strRespuesta.= "<th class='textoCentrado'>";
	  $strRespuesta.= "Activos";
	  $strRespuesta.= "</th>";
	  $strRespuesta.= "<th class='textoCentrado'>";
	  $strRespuesta.= "Pasivos";	    
	  $strRespuesta.= "</th>";
	  $strRespuesta.= "<th class='textoCentrado'>";	  
	  $strRespuesta.= "Resultado";	    
	  $strRespuesta.= "</th>";	
	  $strRespuesta.= "<th class='textoCentrado'>";	  
	  $strRespuesta.= "Neutro";
	  $strRespuesta.= "</th>";
	  $strRespuesta.= "<th class='textoCentrado'>";	  
	  $strRespuesta.= "Total Encuestados";	    
	  $strRespuesta.= "</th>";			
	  $strRespuesta.= "<th class='textoCentrado'>";	  
	  $strRespuesta.= "Universo";	    
	  $strRespuesta.= "</th>";																			  	  
	  $strRespuesta.= "</tr>";
	  $tmpDim = "";
	  $tmpVar = "";	  
	  $tmpVar = "";
	  $arrClasificacion = array();
	  $flag=0;	  
	  $iContadorFilas = 0;
	  foreach($arrResultados as $indice => $valor){
	    if($valor['dimension']!=$tmpDim){
	    	$arrClasificacionUsr = array();
	    		  
	      	$strRespuesta.= renderizarColumnas($arrClasificacion,$iNumeroTotalUsuarios,false,$iContadorFilas);
			$arrClasificacion = array();
			$flag=1;			
			$strRespuesta.= "<tr>";			
			$iVarsPorDim = DB_NEGOCIO_obtenerVariablesxDimension($valor['id_dimension']);
			if(isset($arrParametros['exportacion'])){
			  $iVarsPorDim++;	
			}						
	        $strRespuesta.= "<td rowspan='".$iVarsPorDim."'>";
		    $strRespuesta.= $valor['dimension'];		
	        $strRespuesta.= "</td>";
		  $tmpDim = $valor['dimension'];
	    }
	    if($valor['variable']!=$tmpVar){
			if(!empty($arrClasificacion)){
			  $strRespuesta.= renderizarColumnas($arrClasificacion,$iNumeroTotalUsuarios,false,$iContadorFilas);
			  $arrClasificacion = array();			  		
			}
			if(isset($arrParametros['exportacion'])){
		      $strRespuesta.= "<tr>";
			}
			if($flag==0){
			  if(!isset($arrParametros['exportacion'])){
	    	    $strRespuesta.= "<tr>";			  	
			  }				
			}else{
				$flag=1;
			}		
	        $strRespuesta.= "<td>";
		    $strRespuesta.= $valor['variable'];
	        $strRespuesta.= "</td>";
		  $tmpVar = $valor['variable'];
		}
		$arrClasificacion[$valor["nombre_clasificacion_escala"]] = $valor["cuantos"];
	  }
	  $strRespuesta.= renderizarColumnas($arrClasificacion,$iNumeroTotalUsuarios,false,$iContadorFilas);
	  $strRespuesta.= "<tr>";
	    $strRespuesta.= "<td colspan='2' class='estiloAmarilloGrafico'>";	  	  
		$strRespuesta.= "Balance Social Laboral";
	    $strRespuesta.= "</td>";

		$arrTmp = array();
		//var_dump($arrClasificacionPreguntas);
		foreach($arrClasificacionPreguntas as $llave => $valor){
		  $arrTmp[$valor["nombre_clasificacion_escala"]] = $valor["cantidad"];			
		} 
		
	    $strRespuesta.= "<td class='estiloAmarilloGrafico'>";	
	    $strRespuesta.= ($arrTmp['Activo']);  	  
	    $strRespuesta.= "</td>";
	    
	    $strRespuesta.= "<td class='estiloAmarilloGrafico'>";	  	  
	    $strRespuesta.= ($arrTmp['Pasivo']);		
	    $strRespuesta.= "</td>";
		
	    $strRespuesta.= "<td class='estiloAmarilloGrafico'>";	  	  
	    $strRespuesta.= ($arrTmp['Activo']-$arrTmp['Pasivo']);
	    $strRespuesta.= "</td>";			    			  
	  $strRespuesta.= "</tr>";
	  
	  $strRespuesta.= "</table>";
	  
	  //$strRespuesta.= "<br>";
//////////////////////////////// SEGUNDA TABLA ///////////////////////////////////////////////////////////////////
	  if(!isset($arrParametros['exportacion'])){
	    $strRespuesta.= " <hr style='page-break-before: always;height: px !important;'>";	  	
	  }else{
	  	if($arrParametros['pdf']){
	      $strRespuesta.= "<pagebreak />";	  		
	  	}elseif($arrParametros['word']){
	      $strRespuesta.= "<br style='page-break-before: always'>";	  		
	  	}
	  }
	  
	  $strRespuesta.= "<h4>".$arrGraficos["BST2"]['titulo']."</h4>";
	  $strRespuesta.= "<table id='balancesocial2' class='' width='701px'>";
	  $strRespuesta.= "<tr class='estiloAmarilloGrafico'>";
	    $strRespuesta.= "<th class='textoCentrado'>";	  
	    $strRespuesta.= "Dimensi&oacute;n";	    
	    $strRespuesta.= "</th>";
	    $strRespuesta.= "<th class='textoCentrado'>";	  
	    $strRespuesta.= "Variable";	    
	    $strRespuesta.= "</th>";
	    $strRespuesta.= "<th class='textoCentrado'>";	  
	    $strRespuesta.= "Activos";	    
	    $strRespuesta.= "</th>";
	    $strRespuesta.= "<th class='textoCentrado'>";	  
	    $strRespuesta.= "Pasivos";	    
	    $strRespuesta.= "</th>";	
	    $strRespuesta.= "<th class='textoCentrado'>";	  
	    $strRespuesta.= "Neutro";
	    $strRespuesta.= "</th>";
	    $strRespuesta.= "<th class='textoCentrado'>";	  
	    $strRespuesta.= "Participaci&oacute;n";	    
	    $strRespuesta.= "</th>";													  	  
	  $strRespuesta.= "</tr>";
	  $tmpDim = "";
	  $flag=0;	  
	  $arrClasificacion = array();
	  $iContadorFilas = 0;
	  foreach($arrResultados as $indice => $valor){
	    if($valor['dimension']!=$tmpDim){	    		  
	      	$strRespuesta.= renderizarColumnas($arrClasificacion,$iNumeroTotalUsuarios,true,$iContadorFilas);
			$arrClasificacion = array();
			$flag=1;			
			$strRespuesta.= "<tr>";
			$iVarsPorDim = DB_NEGOCIO_obtenerVariablesxDimension($valor['id_dimension']);
			if(isset($arrParametros['exportacion'])){
			  $iVarsPorDim++;	
			}			
	        $strRespuesta.= "<td rowspan='".$iVarsPorDim."'>";
		    $strRespuesta.= $valor['dimension'];		
	        $strRespuesta.= "</td>";
		  $tmpDim = $valor['dimension'];
	    }
	    if($valor['variable']!=$tmpVar){
			if(!empty($arrClasificacion)){
			  $strRespuesta.= renderizarColumnas($arrClasificacion,$iNumeroTotalUsuarios,true,$iContadorFilas);
			  $arrClasificacion = array();			  		
			}
			if(isset($arrParametros['exportacion'])){
		      $strRespuesta.= "<tr>";
			}			
			if($flag==0){
			  if(!isset($arrParametros['exportacion'])){
	    	    $strRespuesta.= "<tr>";			  	
			  }				
			}else{
				$flag=1;
			}		
	        $strRespuesta.= "<td>";
		    $strRespuesta.= $valor['variable'];
	        $strRespuesta.= "</td>";
		  $tmpVar = $valor['variable'];
		}
		$arrClasificacion[$valor["nombre_clasificacion_escala"]] = $valor["cuantos"];
	  }
	      	$strRespuesta.= renderizarColumnas($arrClasificacion,$iNumeroTotalUsuarios,true,$iContadorFilas);

	  $strRespuesta.= "<tr>";
	    
	    $strRespuesta.= "<td colspan='2' class='estiloAmarilloGrafico'>";	  	  
		$strRespuesta.= "Balance Social Laboral";
	    $strRespuesta.= "</td>";
		
	    $strRespuesta.= "<td class='estiloAmarilloGrafico'>";	
	    $valor= ($arrTmp['Activo']/($arrTmp['Activo']+$arrTmp['Pasivo']+$arrTmp['Neutro']))*100;  	      
  	
	    $strRespuesta.= number_format($valor,2);
	    $strRespuesta.= "%";
		$strRespuesta.= "</td>";
	    
	    $strRespuesta.= "<td class='estiloAmarilloGrafico'>";	  	  
	    $valor= ($arrTmp['Pasivo']/($arrTmp['Activo']+$arrTmp['Pasivo']+$arrTmp['Neutro']))*100;		  
	    $strRespuesta.= number_format($valor,2);
	    $strRespuesta.= "%";
		$strRespuesta.= "</td>";
		
	    $strRespuesta.= "<td class='estiloAmarilloGrafico'>";	  	  
	    $valor= ($arrTmp['Neutro']/($arrTmp['Activo']+$arrTmp['Pasivo']+$arrTmp['Neutro']))*100;
	    $strRespuesta.= number_format($valor,2);
	    $strRespuesta.= "%";
		$strRespuesta.= "</td>";			    			  
	    $strRespuesta.= "</tr>";
	  
	  $strRespuesta.= "</table>";
	  $strRespuesta.= "</div>";
	  ////////////////////////////////////
	  if(isset($_GET['datos'])){
	    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	    header("Content-Disposition: attachment; filename=datos.xls");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: private",false);	  	
	  }	  
	  ////////////////////////////////////	  
	  return $strRespuesta;
		
}
	  

function renderizarColumnas($arrClasificacion,$iNumeroTotalUsuarios,$segundaTabla=false,&$iContadorFilas){
	if(empty($arrClasificacion))
	  return '';

	$iContadorFilas++;
	$strRespuesta = "";
				
	if($segundaTabla){

 	  $strRespuesta.= "<td  class='textoCentrado'>"; 
 			  $valor1=($arrClasificacion['Activo']/($arrClasificacion['Activo']+$arrClasificacion['Pasivo']+$arrClasificacion['Neutro']))*100;				          			  
	  $strRespuesta.= number_format($valor1,2);
 	  $strRespuesta.= "%";			   				
 	  $strRespuesta.= "</td>";
			  
 	  $strRespuesta.= "<td class='textoCentrado'>";           				
$valor1=($arrClasificacion['Pasivo']/($arrClasificacion['Activo']+$arrClasificacion['Pasivo']+$arrClasificacion['Neutro']))*100;
	  $strRespuesta.= number_format($valor1,2);
 	  $strRespuesta.= "%";			   			   			  
 	  $strRespuesta.= "</td>";
			  
 	  $strRespuesta.= "<td class='textoCentrado'>";           				
$valor1=($arrClasificacion['Neutro']/($arrClasificacion['Activo']+$arrClasificacion['Pasivo']+$arrClasificacion['Neutro']))*100;
	  $strRespuesta.= number_format($valor1,2);
 	  $strRespuesta.= "%";			  			   			  
 	  $strRespuesta.= "</td>";				

 	  $strRespuesta.= "<td class='textoCentrado'>";           				
$valor1 = (($arrClasificacion['Neutro'] + $arrClasificacion['Activo']+ $arrClasificacion['Pasivo'])/$iNumeroTotalUsuarios)*100;
	  $strRespuesta.= number_format($valor1,2);			   			  
 	  $strRespuesta.= "%";
 	  $strRespuesta.= "</td>";				
 		  
 	  $strRespuesta.= "</tr>";						

///////////////////////////					
	} else {
						
 	  $strRespuesta.= "<td class='textoCentrado'>"; 
 	  $strRespuesta.= (empty($arrClasificacion['Activo'])?'0':$arrClasificacion['Activo']);				          				
 	  $strRespuesta.= "</td>";
			  
 	  $strRespuesta.= "<td class='textoCentrado'>";           				
 	  $strRespuesta.= ($arrClasificacion['Pasivo']); 			  
 	  $strRespuesta.= "</td>";
		  
 	  $strRespuesta.= "<td class='";           				
	  if(($arrClasificacion['Activo']-$arrClasificacion['Pasivo'])>=0){
 	    $strRespuesta.= " positivo ";			  	
	  } else {
 	    $strRespuesta.= " negativo ";			  	
	  }
 	  $strRespuesta.= " textoCentrado'><b>";			  
 	  $strRespuesta.= ($arrClasificacion['Activo']-$arrClasificacion['Pasivo']); 			  
 	  $strRespuesta.= "</b></td>";
	  
 	  $strRespuesta.= "<td class=' textoCentrado'>";           				
 	  $strRespuesta.= ($arrClasificacion['Neutro']); 			  
 	  $strRespuesta.= "</td>";				

 	  $strRespuesta.= "<td class=' textoCentrado'>";           				
 	  $strRespuesta.= ($arrClasificacion['Neutro'] + $arrClasificacion['Activo']+ $arrClasificacion['Pasivo']); 			  
 	  $strRespuesta.= "</td>";				

	  if($iContadorFilas==1){
	  	$arrVars = DB_NEGOCIO_obtenerVariables();
 	    $strRespuesta.= "<td class=' textoCentrado' rowspan='".count($arrVars)."'>";           				
 	    $strRespuesta.= $iNumeroTotalUsuarios; 			  
 	    $strRespuesta.= "</td>";
	  }
			  
 	  $strRespuesta.= "</tr>";						

					
	}
							  
  return $strRespuesta;	
}


?>