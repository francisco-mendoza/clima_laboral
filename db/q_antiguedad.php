<?php


  function DB_ANTIGUEDAD_obtenerTodasAntiguedades($idCliente){
  	$conexion = DAL::conexion();
	$arrFiltro = array();	
    $sql1 = " 
		select
		*
		from
		slorg_antiguedad
		where id_cliente=".$idCliente."    
		
    ";	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	$arrResultados[] = $row;  
	  }
	  return $arrResultados;	
	}  	  	
  }

  function DB_ANTIGUEDAD_obtenerFechasLimite($idAntiguedad){
  	$conexion = DAL::conexion();
    $sql1 = " 
		SELECT
		*
		FROM
		  slorg_antiguedad
		WHERE 1=1
		AND id_antiguedad=".$idAntiguedad." 		
    ";	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $row = mysql_fetch_assoc($rs1);
	  $iAnhoMinimo = $row['minimo'];
	  $iAnhoMaximo = $row['maximo'];
	  $tsHoy = strtotime('now');
	  
	  $tsMinimo = strtotime('-'.$iAnhoMinimo.' years', $tsHoy);
	  if($iAnhoMinimo!=0){
	    $tsMinimo = strtotime('-1 days', $tsMinimo);	  	
	  }
	    
	  $dMinimo = date("Y-m-d",$tsMinimo);
	  if(!empty($iAnhoMaximo)){
	    $tsMaximo = strtotime('-'.$iAnhoMaximo.' years', $tsHoy);
	    $dMaximo  = date("Y-m-d",$tsMaximo);
	  } else {
	    $tsMaximo = 0;
	    $dMaximo  = 0;
	  } 
  
	  return array(
	    'minimo' => $dMinimo 
	   ,'maximo' => $dMaximo
	  );
	}  	  	  	
  }

function add_date($givendate,$day=0,$mth=0,$yr=0) {
      $cd = strtotime($givendate);
      $newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
    date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
    date('d',$cd)+$day, date('Y',$cd)+$yr));
      return $newdate;
              }


?>