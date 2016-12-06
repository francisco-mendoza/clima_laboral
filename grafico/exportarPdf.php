<?php

 session_start();

require_once("../include/definiciones.php");
require_once("../include/dbconection.php");
require_once("../include/funciones.php");
require_once("balance_social.php");
require_once("tabla_inventario.php");
require_once(_MPDF_PATH . "mpdf.php");
$arrGraficos 		  		 = DB_PARAMETROS_obtenerInfoGraficos();

//error_reporting(E_ALL);

$mpdf=new mPDF('','A4','9','verdana'); 
$mpdf->useSubstitutions = false; // optional - just as an example
$mpdf->simpleTables = true;
//$mpdf->SetHeader($url.'||Page {PAGENO}');  // optional - just as an example
//$mpdf->CSSselectMedia='mpdf'; // assuming you used this in the document header
//$mpdf->setBasePath($url);
$html = '';
$mpdf->showImageErrors = true.
$idSelect = (isset($_POST['id_select']))?$_POST['id_select']:0;
$idCliente = (isset($_POST['id_cliente']))?$_POST['id_cliente']:$_GET['id_cliente'];
$idGrafico = (isset($_POST['identificador']))?$_POST['identificador']:$_GET['identificador'];
$tipoGrafico = (isset($_POST['tipo_grafico']))?$_POST['tipo_grafico']:0;

$stylesheet = file_get_contents("../include/custom.css"); // external css
$mpdf->WriteHTML($stylesheet,1);
$estilo ="<style>";
$estilo.=" body{ font-family:Verdana, Geneva, sans-serif; }";
$estilo.="<style>";
$mpdf->WriteHTML($estilo,1);
$html.= '<body>';

if(!empty($tipoGrafico) && $tipoGrafico=='ISTA'){
  $html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["ISTA"]['titulo'].'</div><br><img src="ISTA_'.$idGrafico.'.png"></div>';
  //$html.= '<div class="tab-content"><br><div class="tituloGrafico"> Porcentaje de distribución de satisfaccion general</div><br><img src="b_grabar_n.gif"></div>';
  $html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
  $html.=$arrGraficos["ISTA"]['descripcion'];
  $html.="</td></tr></table>";
  $mpdf->WriteHTML($html);
  $mpdf->Output();
  exit;
}

//--------------------------------------------DSG-------------------------------------------
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["DSG"]['titulo'].'</div><br><img src="DSG_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["DSG"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<pagebreak />";
//--------------------------------------------DSG-------------------------------------------
//--------------------------------------------DSCR-------------------------------------------
if(empty($idSelect)){
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["DSCR"]['titulo'].'</div><br><img src="DSCR_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["DSCR"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<pagebreak />";
}
//--------------------------------------------DSCR-------------------------------------------
//--------------------------------------------DSV-------------------------------------------
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["DSV"]['titulo'].'</div><br><img src="DSV_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["DSV"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<pagebreak />";
//--------------------------------------------DSV-------------------------------------------
//--------------------------------------------PCRPI-------------------------------------------
if(empty($idSelect)){
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["PCRPI"]['titulo'].'</div><br><img src="PCRPI_'.$idGrafico.'.png"></div>';

$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["PCRPI"]['descripcion'];
$html.="</td></tr></table>";

$html.= "<pagebreak />";
}
//--------------------------------------------PCRPI-------------------------------------------
//--------------------------------------------PVPI-------------------------------------------
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["PVPI"]['titulo'].'</div><br><img src="PVPI_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["PVPI"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<pagebreak />";
//--------------------------------------------PVPI-------------------------------------------


//--------------------------------------------TC-------------------------------------------
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["TC"]['titulo'].'</div><br><img src="TC_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["TC"]['descripcion'];
$html.="</td></tr></table>";
if(!empty($idSelect)){
  $html = utf8_encode($html);
  $mpdf->WriteHTML($html);
  $mpdf->Output();
  exit;
}

$html.= "<pagebreak />";
//--------------------------------------------TC-------------------------------------------




//----------------------------------SEG1------------------------------------------------------

$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["SEG1"]['titulo'].'</div><br><img src="SEG1_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["SEG1"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<pagebreak />";


//----------------------------------SEG1------------------------------------------------------



//--------------------------------RANKING--------------------------------------------------------

$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["ranking"]['titulo'].'</div><br><img src="ranking_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["ranking"]['descripcion'];
$html.="</td></tr></table>";
$html.= "<pagebreak />";


//--------------------------------RANKING--------------------------------------------------------




//--------------------------------------------INV-------------------------------------------
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["INV"]['titulo'].'</div><br><img src="INV_'.$idGrafico.'.png"></div>';

////////////////////////////////////////////////////////////////////////////////////////////////////////////
$html.= "<pagebreak />";

$html.= obtenerTablaInventario($idCliente);

$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["INV"]['descripcion'];
$html.="</td></tr></table>";
	
//tabla de inventario
$html.= "<pagebreak />";
//las dos tablas de balance social
//--------------------------------------------INV-------------------------------------------
//--------------------------------------------PR-------------------------------------------
$html.= '<div class="tab-content"><br><div class="tituloGrafico">'.$arrGraficos["PR"]['titulo'].'</div><br><img src="PR_'.$idGrafico.'.png"></div>';
$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["PR"]['descripcion'];
$html.="</td></tr></table>";

$html.= "<pagebreak />";
//--------------------------------------------PR-------------------------------------------
//--------------------------------------------BS-------------------------------------------
$html.= obtenerBalanceSocial($idCliente,array("exportacion"=>true,'pdf'=>true));
///--------------------------------------------BS-------------------------------------------

$html.="<br><br><table align='center' class=' tablaDescripcionGrafico bordeNegroSolido'><tr><td>";
$html.=$arrGraficos["BS"]['descripcion'];
$html.="</td></tr></table>";
	
$html.= '</body>';

$html = utf8_encode($html);
$mpdf->WriteHTML($html);
$mpdf->Output();

exit;

?>