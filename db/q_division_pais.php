<?php

  function DB_DIVISION_obtenerAllRegionComuna($arrParametros,$conexion){
	$sql = "select a1.nombre_region_pais,a3.nombre_region_paos from slorg_region_pais a1
	  left join slorg_region_pais a2 ON a2.id_region_pais_padre = a1.id_region_pais
	  left join slorg_region_pais a3 ON a3.id_region_pais_padre = a2.id_region_pais
	  WHERE 1=1 AND (a1.id_nivel = 2 OR a3.id_nivel = 4)";
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
	if(!$rs){
	  return array();	
	}else{
	  return mysql_fetch_assoc($rs);	
	}
  }
  
  function DB_DIVISION_obtenerRegiones($arrParametros,$conexion){
	$sql = "select id_region_pais as id_region,nombre_region_pais as nombre_region from slorg_region_pais 
	  WHERE 1=1 AND id_nivel = 2 ";
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
	if(!$rs){
	  return array();	
	}else{
	  $arrResultados = array();	
	  while($row = mysql_fetch_assoc($rs)){
	  	$arrResultados[] = $row;
	  }	
	  return $arrResultados;
	}
  }
  
  
  function DB_DIVISION_obtenerComunas($arrParametros,$conexion){
		
	$sql = "select id_region_pais as id_comuna,nombre_region_pais as nombre_comuna from slorg_region_pais 
	  WHERE 1=1 AND id_nivel = 4 "; 
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
	if(!$rs){
	  return array();	
	}else{
	  $arrResultados = array();	
	  while($row = mysql_fetch_assoc($rs)){
	  	$arrResultados[] = $row;
	  }	
	  return $arrResultados;
	}
  }

  function DB_DIVISION_obtenerRegionDeComuna($arrParametros,$conexion){
  	if(empty($arrParametros['id']))
	  return array();
	$iNivel = DB_DIVISION_obtenerNivel($arrParametros,$conexion);
	if($iNivel=='4'){//comuna
	  $sql = "SELECT a3.id_region_pais as id_region, a3.nombre_region_pais as nombre_region from slorg_region_pais a1
			inner join slorg_region_pais a2 On a2.id_region_pais = a1.id_region_pais_padre
			inner join slorg_region_pais a3 On a3.id_region_pais = a2.id_region_pais_padre
			where 1=1 AND a1.id_region_pais=".$arrParametros['id'];			  	
	} else {//region
	  $sql = "SELECT id_region_pais as id_region, nombre_region_pais as nombre_region from slorg_region_pais 
			where 1=1 AND id_region_pais=".$arrParametros['id'];	
	}

	$rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
	if(!$rs){
	  return array();	
	} else {  
	  return mysql_fetch_assoc($rs);
	}		
	
  }

  function DB_DIVISION_esRegion($arrParametros,$conexion){
	$sql = " SELECT a2.nivel from slorg_region_pais a1 INNER JOIN slorg_region_pais_nivel a2 ON a1.id_nivel = a2.id_nivel WHERE 1=1 AND a1.id_region_pais = ".$arrParametros['id'];
	$rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
	if(!$rs){
	  return array();	
	} else {  
	  $linea =mysql_fetch_assoc($rs);  
	  if($linea['nivel']=='2'){
	    return true;	
	  } else {
	    return false;	  	
	  }
	}  	
  }

  function DB_DIVISION_obtenerNivel($arrParametros,$conexion){
	$sql = "  SELECT a2.nivel from slorg_region_pais a1 INNER JOIN slorg_region_pais_nivel a2 ON a1.id_nivel = a2.id_nivel WHERE 1=1 AND a1.id_region_pais=".$arrParametros['id'];
	$rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
	if(!$rs){
	  return array();	
	} else {  
	  $linea =mysql_fetch_assoc($rs);  
	  return $linea['nivel'];
	}  	
  }
  
  function DB_DIVISION_obtenerComunasDeRegion($arrParametros,$conexion){
  	if(empty($arrParametros['id']))
	  return array();
	$sql = "select a3.id_region_pais as id, a3.nombre_region_pais as nombre from slorg_region_pais a1
			inner join slorg_region_pais a2 On a2.id_region_pais_padre = a1.id_region_pais
			inner join slorg_region_pais a3 On a3.id_region_pais_padre = a2.id_region_pais
			where 1=1 AND a1.id_region_pais=".$arrParametros['id'];
	$rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
	if(!$rs){
	  return array();	
	} else {
	  $arrResultados = array();	
	  while($row = mysql_fetch_assoc($rs)){
	  	$row['nombre'] = utf8_encode($row['nombre']);	
	  	$arrResultados[] = $row;
	  }	
	  return $arrResultados;			
	}
  }

  function DB_DIVISION_obtenerComunasDeMismoNivel($arrParametros,$conexion){
  	if(empty($arrParametros['id']))
	  return array();
	$sql = "select a3.id_region_pais as id, a3.nombre_region_pais as nombre FROM slorg_region_pais a1
			INNER JOIN slorg_region_pais a2 ON a2.id_region_pais = a1.id_region_pais_padre
			INNER JOIN slorg_region_pais a3 ON a3.id_region_pais = a2.id_region_pais_padre			
			WHERE 1=1 AND a1.id_region_pais=".$arrParametros['id'];
	$rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
	if(!$rs){
	  return array();	
	} else {
	  $row = mysql_fetch_assoc($rs);//obtener la region
	  return DB_DIVISION_obtenerComunasDeRegion($row,$conexion);	  			
	}
  }  
    
?>