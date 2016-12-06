<?php
//echo phpinfo();
//exit;
//echo ini_get('session.auto_start');
//echo phpinfo();
//error_reporting(E_ALL);
//$url = "www.google.cl";
//echo curl_get_contents($url);
/*
function curl_get_contents($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
*/

//exit;


include_once("include/dbconection.php");

$idIsta = array(
102,103,104,105,106,108,109,110,111,112,114,115,116,117,118,120,121,122,124,125
);
$conexion = DAL::conexion();

$sql = "DELETE FROM resultados where id_elemento_formulario = 13";
mysql_query($sql,$conexion);

$sql = "SELECT distinct id_usuario FROM resultados";
$res = mysql_query($sql,$conexion);
while($row = mysql_fetch_assoc($res)){
  $idu = $row['id_usuario'];
	
	   for($i=0;$i<count($idIsta);$i++){
	$sql = "INSERT INTO resultados ";  	 	
	$sql.= " ( ";  
	$sql.= "   id_usuario";
	$sql.= "  ,id_elemento_formulario";
	$sql.= "  ,id_elemento_negocio";
	$sql.= "  ,valor";	  	  	  
	$sql.= "  ,completado";	  
	$sql.= " )";  
	$sql.= " VALUES ";	  
	$sql.= " ( ";  
	$sql.= "   ".$idu."";
	$sql.= "  ,13";
	$sql.= "  ,".$idIsta[$i];
	$sql.= "  ,".mt_rand(0,4);
	$sql.= "  ,1";	  
	$sql.= " )";
    $res1 = mysql_query($sql);	  
  }
}

//exit;
define('CANTIDAD',50);
$idPreg = array(
  3,10,15,23,28,63,66,53,46,56
 ,68,80,49,43,39,27,12,5,18,25
 ,33,38,44,50,55,21,7,13,4,11
 ,42,31,17,45,52,58,70,62,77,71
 ,79,47,40,51,59,65,72,75,57,76
 ,16,6,22,8,32,29,69,78,64,36
 ,73,24,37
);

$idPrio = array(2,9,14,20,26,30,35,41,48,54,61,67,74);

$conexion = DAL::conexion();
$sql = "DELETE FROM resultados";
mysql_query($sql,$conexion);

$sql = "SELECT id_usuario FROM dpp_slorg_usuario limit ".CANTIDAD;
$res = mysql_query($sql,$conexion);
while($row = mysql_fetch_assoc($res)){
  $idu = $row['id_usuario'];

  for($i=0;$i<count($idPreg);$i++){
	$sql = "INSERT INTO resultados ";  	 	
	$sql.= " ( ";  
	$sql.= "   id_usuario";
	$sql.= "  ,id_elemento_formulario";
	$sql.= "  ,id_elemento_negocio";
	$sql.= "  ,valor";	  	  	  
	$sql.= "  ,completado";	  
	$sql.= " )";  
	$sql.= " VALUES ";	  
	$sql.= " ( ";  
	$sql.= "   ".$idu."";
	$sql.= "  ,6";
	$sql.= "  ,".$idPreg[$i];
	$sql.= "  ,".mt_rand(0,4);
	$sql.= "  ,1";	  
	$sql.= " )";
    $res1 = mysql_query($sql);	  
  }

  $max = 13;
  $done = false;
  while(!$done){
    $numbers = range(1, $max);
    shuffle($numbers);
    $done = true;
    foreach($numbers as $key => $val){
        if($key == $val){
            $done = false;
            break;
        }
    }
  }

  for($i=0;$i<count($idPrio);$i++){
	$sql = "INSERT INTO resultados ";  	 	
	$sql.= " ( ";  
	$sql.= "   id_usuario";
	$sql.= "  ,id_elemento_formulario";
	$sql.= "  ,id_elemento_negocio";
	$sql.= "  ,valor";	  	  	  
	$sql.= "  ,completado";	  
	$sql.= " )";  
	$sql.= " VALUES ";	  
	$sql.= " ( ";  
	$sql.= "   ".$idu."";
	$sql.= "  ,7";
	$sql.= "  ,".$idPrio[$i];
	$sql.= "  ,".$numbers[$i];
	$sql.= "  ,1";	  
	$sql.= " )";
	$res2 = mysql_query($sql);	  
  }
}

?>