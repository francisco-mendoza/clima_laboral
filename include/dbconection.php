<?php
// ----------------------------------------------------------------------------
// Definici�n de variables
// ----------------------------------------------------------------------------
	$server = 'localhost';
	
	$conexion = @mysql_connect("localhost", "root", "Propro123");
	if(esProduccion()){
	  $strDB = 'psicus_satisfaccion_laboral_cliente2';	
	}else{
	  $strDB = 'psicus_satisfaccion_laboral_qa';		
	}
	mysql_select_db($strDB, $conexion);		
	//La siguiente linea está en include/header.php
	//define("ROOT_PATH", "/var/www/psicus.cl/public_html/slorg");
	
	define( 'DB_HOST', 'localhost' ); // set database host
	define( 'DB_USER', 'root' ); // set database user
	define( 'DB_PASS', 'Propro123' ); // set database password

	//	var_dump($strDB);
	define( 'DB_NAME',$strDB); // set database name
	
	define( 'SEND_ERRORS_TO', 'fmendoza@psicus.cl' ); //set email notification email address
	define( 'DISPLAY_DEBUG', true ); //display db errors?	

	define('TABLA_USUARIO',"dpp_slorg_usuario"); // set database name	
	
	function esProduccion(){
	  $url = $_SERVER["REQUEST_URI"];
	  $arrUrl = explode("?",$url);  
	  if (preg_match("/qa/i",$arrUrl[0])==false){
        return true;
      } else {
        return false;
      }	
	}
		
    require_once(dirname(__FILE__).'/DAL.php');
	include_once(dirname(__FILE__).'/../db/querys.php');
 
?>