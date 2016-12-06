<?php

//include(_MPDF_PATH."mpdf.php");

function renderizarExcel($arrColumnas,$arrTitulos){
  
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=datos.xls");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);

  $strRespuesta = "<table>";
  $strRespuesta.= "<tr>";
  foreach($arrTitulos as $columna){
    $strRespuesta.= "<th>";
    $strRespuesta.= $columna;
    $strRespuesta.= "</th>";
  }
  $strRespuesta.= "</tr>";
  
  for($i=0;$i<count($arrColumnas[0]);$i++){
	$strRespuesta.= "<tr>";    	
    for($j=0;$j<count($arrColumnas);$j++){
      $strRespuesta.= "<th>";
      $strRespuesta.= utf8_encode($arrColumnas[$j][$i]);
      $strRespuesta.= "</th>";      	
    }
	$strRespuesta.= "</tr>";  	
  }

  $strRespuesta.= "</table>";
  echo utf8_decode($strRespuesta);
  exit;  
}

function renderizarPdf($strHtml){
  	
  	
}

?>