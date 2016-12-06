<?php

//----------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// Inicializamos String de Conexión a la Base de Datos
// ----------------------------------------------------------------------------
include_once("../include/dbconection.php");
//-----------------------------------------------------------------------------
// cargamos las librerias del grafico
//-----------------------------------------------------------------------------
  //$idCliente = $_SESSION['id_cliente'];

function obtenerTablaInventario($idCliente){

  $arrParameters = array();
  $arrParameters = DB_PARAMETROS_obtenerParametro($idCliente,"INV");
  $arrResultadosPreguntas = DB_RESULT_obtenerResultadoPregunta($idCliente,$arrParameters);
  usort($arrResultadosPreguntas,'sortBy');
  $iContador = 0;
  $fPromedio = 0;
  $fTotal    = 0;

  $arrValoresPreguntas = array();  
  foreach($arrResultadosPreguntas as $row){
	$arrValoresPreguntas[] = redondeo($row['promedio'],2);
	$fTotal+=   	$row['promedio'];
  }
  if(count($arrValoresPreguntas)>0){
    $fPromedio = redondeo($fTotal/count($arrValoresPreguntas),2);  	
  }
  
  $fDesviacion = desviacionEstandar($arrValoresPreguntas);
  $fDesviacion1 = $fPromedio+$fDesviacion;
  $fDesviacion2 = $fPromedio-$fDesviacion;
  $arrValoresPositivos = array();
  $arrValoresNegativos = array();  
  for($i=0;$i<count($arrResultadosPreguntas);$i++){
  	if($arrValoresPreguntas[$i]>=$fDesviacion1){
      $arrValoresPositivos[] = array(
         "promedio" => $arrValoresPreguntas[$i]
        ,"orden"    => $arrResultadosPreguntas[$i]['orden']
        ,"nombre"   => $arrResultadosPreguntas[$i]['nombre']        
	  );
  	}
  	if($arrValoresPreguntas[$i]<=$fDesviacion2){
      $arrValoresNegativos[] = array(
         "promedio" => $arrValoresPreguntas[$i]
        ,"orden"    => $arrResultadosPreguntas[$i]['orden']
        ,"nombre"   => $arrResultadosPreguntas[$i]['nombre']        
	  ); 	  		
  	}	
  }   
      
  $strRespuesta = "<div align='center'>";
  $strRespuesta.= "<table id='tablainventario' class='' style='' width='701px'>";
  	  $strRespuesta.= "<tr  class='estiloAmarilloGrafico'>"; 
		$strRespuesta.= "<td colspan='4'>";
		$strRespuesta.= "Priorizaci&oacute;n";		
		$strRespuesta.= "</td>";
  	  $strRespuesta.= "</tr>";
  	  $strRespuesta.= "<tr  class='estiloAmarilloGrafico'>"; 
		$strRespuesta.= "<td colspan='1'>";
		$strRespuesta.= "";		
		$strRespuesta.= "</td>";
		$strRespuesta.= "<td colspan='1' nowrap style='padding:4px !important;'>";
		$strRespuesta.= "N&#176;";		
		$strRespuesta.= "</td>";
		$strRespuesta.= "<td colspan='1'>";
		$strRespuesta.= "Preguntas";		
		$strRespuesta.= "</td>";
		$strRespuesta.= "<td colspan='1'>";
		$strRespuesta.= "Puntaje";		
		$strRespuesta.= "</td>";		
  	  $strRespuesta.= "</tr>";
	  			  		  
  foreach($arrValoresPositivos as $key => $value){
  	  $strRespuesta.= "<tr >";
		if($iContador==0){
		  $strRespuesta.= "<td class='positivo textoCentrado' rowspan='".count($arrValoresPositivos)."' >";
		  $strRespuesta.= "<div class=''>Reactivos (preguntas) con resultados de satisfacci&oacute;n sobre la desviaci&oacute;n estandar</div>";		
		  $strRespuesta.= "</td>";			
		} 		
		
		$strRespuesta.= "<td class='positivo textoCentrado'><b>";
		$strRespuesta.= $value['orden'];		
		$strRespuesta.= "</b></td>";
		$strRespuesta.= "<td style='align:left;'>";
		$strRespuesta.= $value['nombre'];
		$strRespuesta.= "</td>";
		$strRespuesta.= "<td class='textoCentrado'>";
		$strRespuesta.= number_format($value['promedio'],2);
		$strRespuesta.= "</td>";		
  	  $strRespuesta.= "</tr>";
	  $iContador++;
  }
  $iContador = 0;  
  foreach($arrValoresNegativos as $key => $value){
  				
  	  $strRespuesta.= "<tr >"; 
		if($iContador==0){
		  $strRespuesta.= "<td class='negativo textoCentrado' rowspan='".count($arrValoresNegativos)."' >";
		  $strRespuesta.= "<div class='' >Reactivos (respuestas) con resultados de satisfacci&oacute;n bajo la desviaci&oacute;n estandar</div>";		
		  $strRespuesta.= "</td>";			
		}
		$strRespuesta.= "<td class='negativo textoCentrado'><b>";
		$strRespuesta.= $value['orden'];		
		$strRespuesta.= "</b></td>";
		$strRespuesta.= "<td style='align:left;'>";
		$strRespuesta.= $value['nombre'];
		$strRespuesta.= "</td>";
		$strRespuesta.= "<td class='textoCentrado'>";
		$strRespuesta.= number_format($value['promedio'],2);
		$strRespuesta.= "</td>";		
  	  $strRespuesta.= "</tr>";  	    	  		
  			
  		
  	
	
	$iContador++;
  }  
  
		
  	
  


  $strRespuesta.= "</table>";
  $strRespuesta.= "</div>";  
  return $strRespuesta;
}
 
function sortBy($a, $b){
  return intval($b['promedio']*1000) - intval($a['promedio']*1000);
} 
  
?>