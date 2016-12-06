<?php

 session_start();

require_once("../include/definiciones.php");
require_once("../include/dbconection.php");
require_once("../include/funciones.php");
require_once("balance_social.php");
require_once("tabla_inventario.php");

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=GraficosWord.doc");

$arrGraficos 		  		 = DB_PARAMETROS_obtenerInfoGraficos();

$html = '';

$html.="<head>";
$html.="<style>";
$html.=file_get_contents('../include/custom.css');
$html.=" body{ font-family:Verdana, Geneva, sans-serif; }";
$html.="</style>";
$html.="</head>";
$html.="<body>";

$idSelect = (isset($_POST['id_select']))?$_POST['id_select']:0;
$idCliente = (isset($_POST['id_cliente']))?$_POST['id_cliente']:$_GET['id_cliente'];
$idGrafico = (isset($_POST['identificador']))?$_POST['identificador']:$_GET['identificador'];
$tipoGrafico = (isset($_POST['tipo_grafico']))?$_POST['tipo_grafico']:0;

$arrGraficos = DB_PARAMETROS_obtenerInfoGraficos();

if(!empty($tipoGrafico) && $tipoGrafico=='ISTA'){
  $html.= '<table align="center"><tr><td><br><div class="tituloGrafico">'.$arrGraficos["ISTA"]['titulo'].'</div><br><img src="'.HOME_PATH.'grafico/ISTA_'.$idGrafico.'.png"></td></tr></table>';
  //$html.= '<div class="tab-content"><br><div class="tituloGrafico"> Porcentaje de distribución de satisfaccion general</div><br><img src="b_grabar_n.gif"></div>';
  $html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
  $html.=$arrGraficos["ISTA"]['descripcion'];
  $html.="</td></tr></table>";
  echo $html;
  exit;
}
//-----------------------------------------------DSG
$html.= '<table align="center"><tr><td><br><div class="tituloGrafico">'.$arrGraficos["DSG"]['titulo'].'</div><br><img src="'.HOME_PATH.'grafico/DSG_'.$idGrafico.'.png"></td></tr></table>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["DSG"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<br style='page-break-before: always'>";
//-----------------------------------------------DSG
//-----------------------------------------------DSCR
if(empty($idSelect)){
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["DSCR"]['titulo'].'</div><br><img src="'.HOME_PATH.'grafico/DSCR_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["DSCR"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<br style='page-break-before: always'>";	
}
//-----------------------------------------------DSCR
//-----------------------------------------------DSV
$html.= '<table align="center"><tr><td><br><div class="tituloGrafico">'.$arrGraficos["DSV"]['titulo'].'</div><img src="'.HOME_PATH.'grafico/DSV_'.$idGrafico.'.png"  height="500px"></td></tr></table>';
$html.="<table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["DSV"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<br style='page-break-before: always'>";
//-----------------------------------------------DSV
//-----------------------------------------------PCRPI
if(empty($idSelect)){
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["PCRPI"]['titulo'].'
</div><br><img src="'.HOME_PATH.'grafico/PCRPI_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["PCRPI"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<br style='page-break-before: always'>";
}
//-----------------------------------------------PCRPI
//-----------------------------------------------PVPI
$html.= '<table align="center"><tr><td><br><div class="tituloGrafico">'.$arrGraficos["PVPI"]['titulo'].'</div><br><img src="'.HOME_PATH.'grafico/PVPI_'.$idGrafico.'.png"></td></tr></table>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["PVPI"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<br style='page-break-before: always'>";
//-----------------------------------------------PVPI

//-----------------------------------------------TC
$html.= '<table align="center"><tr><td><br><div class="tituloGrafico">'.$arrGraficos["TC"]['titulo'].'</div><br><img src="'.HOME_PATH.'grafico/TC_'.$idGrafico.'.png"></td></tr></table>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["TC"]['descripcion'];
$html.="</td></tr></table>";
if(!empty($idSelect)){
  echo $html;
  exit;	
}
$html.= "<br style='page-break-before: always'>";
//-----------------------------------------------TC
//-----------------------------------------------INV
$html.= '<table align="center" width="600px"><tr><td><div class="tituloGrafico" style="width:600px;"> &nbsp;&nbsp;&nbsp; '.$arrGraficos["INV"]['titulo'].'</div><img src="'.HOME_PATH.'grafico/INV_'.$idGrafico.'.png" width="600px"></td></tr></table>';

////////////////////////////////////////////////////////////////////////////////////////////////////////////
$html.= "<br style='page-break-before: always'>";

$html.= obtenerTablaInventario($idCliente);

$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["INV"]['descripcion'];
$html.="</td></tr></table>";
	
//tabla de inventario
$html.= "<br style='page-break-before: always'>";
//-----------------------------------------------INV
//-----------------------------------------------PR
$html.= '<table align="center"><tr><td><br><div class="tituloGrafico">'.$arrGraficos["PR"]['titulo'].'</div><br><img src="'.HOME_PATH.'grafico/PR_'.$idGrafico.'.png"></td></tr></table>';

$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["PR"]['descripcion'];
$html.="</td></tr></table>";

$html.= "<br style='page-break-before: always'>";
//-----------------------------------------------PR
//-----------------------------------------------BS
$html.= obtenerBalanceSocial($idCliente,array("exportacion"=>true,'word'=>true));
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["BS"]['descripcion'];
$html.="</td></tr></table>";
//-----------------------------------------------BS

$html.="<body>";
echo $html;
exit;

?>