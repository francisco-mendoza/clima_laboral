<?php

  function DB_CR_obtenerTodosCr($idCliente,$iNivel){
	$conexion = DAL::conexion();	
	$sql = "
	    SELECT 
	     id,sigla as nombre
	     FROM ".TBL_ESTRUCTURA_CR_NIVEL." nv
		JOIN	".TBL_ESTRUCTURA_CR." est
		ON nv.id_nivel = est.id_nivel		 	    
	    WHERE 1=1
	    AND est.id_cliente = ".$idCliente."
	    AND nv.nivel=".$iNivel;
	$rs1=mysql_query($sql, $conexion) or die(mysql_error());
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

  function DB_CR_obtenerIdNivel($idCliente,$iNivel){
	$conexion = DAL::conexion();	
	$sql = "
	    SELECT 
	      id_nivel
	    FROM ".TBL_ESTRUCTURA_CR_NIVEL."
	    WHERE id_cliente = ".$idCliente."
	    AND nivel=".$iNivel;
	$rs1=mysql_query($sql, $conexion) or die(mysql_error());
	$linea = mysql_fetch_assoc($rs1);
	return $linea['id_nivel'];  	
  }

  function DB_CR_cantidadNiveles($idCliente,$conexion){
	$sql = "select
	    MAX(nivel) as maximo
		from 
		".TBL_ESTRUCTURA_CR_NIVEL."
		WHERE id_cliente = ".$idCliente."  AND id_estado=1 ";
	$rs1=mysql_query($sql, $conexion) or die(mysql_error().$sql);
	$linea = mysql_fetch_assoc($rs1);
	return $linea['maximo'];			
  }  
  
  function DB_CR_nombreNivel($idCliente,$conexion,$iNivel){
  	if(empty($conexion)) $conexion = DAL::conexion();	
  	$sql = "select
	    nombre_nivel
		from 
		".TBL_ESTRUCTURA_CR_NIVEL."
		where id_cliente = ".$idCliente." AND nivel = ".$iNivel."  AND id_estado=1 ";
	//var_dump($sql);
	$rs1=mysql_query($sql, $conexion) or die(mysql_error().$sql);
	$linea = mysql_fetch_assoc($rs1);
	return $linea['nombre_nivel'];				
  }  

  function DB_CR_obtenerHijos($conexion,$idNodo){
  	if(empty($conexion)) $conexion = DAL::conexion();  	
    $sql =" SELECT id as id, nombre as nombre from ".TBL_ESTRUCTURA_CR." WHERE id_padre ";
	$sql.=($idNodo!='null')?"=$idNodo":" is null ";
	//var_dump($sql);
	$rs=mysql_query($sql, $conexion) or die(mysql_error());
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

  function DB_CR_obtenerPadre($conexion,$idNodo){
	 $sqlr="
	   select
	   b.id as id_cr,b.id_padre as id_cr_padre,b.id_nivel
	   from
	   ".TBL_ESTRUCTURA_CR." b
	   where 1=1
	   AND b.id = ".$idNodo."
	   limit 1	
	 ";	  
	  //echo $sqlr;
	  $rs1r=mysql_query($sqlr, $conexion) or die(mysql_error());
	  $rowr=mysql_fetch_array($rs1r);
	  return $rowr['id_cr_padre'];	
  }

  function DB_CR_traerCrUsuario($conexion,$usuario){
	$sqlr="
		select
		 a.id_unidad as centro_responsabilidad
		,b.id_padre as id_cr_padre
		,b.id as id_cr
		,b.id_nivel as nivel
		from
		dpp_slorg_usuario a 
		left join
		".TBL_ESTRUCTURA_CR." b
		on a.id_unidad = b.id
		WHERE 1=1
		  AND a.rut='".$usuario."'
		LIMIT 1	";
		//var_dump($sqlr);
	$rs1r=mysql_query($sqlr, $conexion) or die(mysql_error());
	$rowr=mysql_fetch_assoc($rs1r);
	return array(
	   "cr_usuario"  => $rowr['centro_responsabilidad']
	  ,"id_cr_padre" => $rowr['id_cr_padre']
	  ,"id_cr" 	     => $rowr['id_cr']	    
	  ,"nivel" 	     => $rowr['nivel']	    
	);
  }

  function DB_CR_obtenerNodosPorNivel($idCliente,$iNivel,$conexion){
  	if(empty($conexion)){ $conexion = DAL::conexion(); };
	$sql="SELECT * FROM ".TBL_ESTRUCTURA_CR." where 1=1 and id_cliente=".$idCliente." AND id_nivel=".$iNivel." order by nombre ";  	
	$rs=mysql_query($sql, $conexion) or die(mysql_error());
	if(!$rs){
	  return array();	
	} else {
	  $arrResultados = array();	
	  while($row = mysql_fetch_assoc($rs)){
	  	$arrResultados[] = $row;
	  }	
	  return $arrResultados;	
	}		
  }
  
  function DB_CR_obtenerHijosPorNodo($idNodo,$conexion){
    $sql="SELECT * FROM ".TBL_ESTRUCTURA_CR." where 1=1 AND id_padre=".$idNodo." order by nombre ";	  	
	$rs=mysql_query($sql, $conexion) or die(mysql_error());
	if(!$rs){
	  return array();	
	} else {
	  $arrResultados = array();	
	  while($row = mysql_fetch_assoc($rs)){
	  	$arrResultados[] = $row;
	  }	
	  return $arrResultados;	
	}		
  }
  
  function DB_CR_obtenerProfundidad($idNivelUnidad){
  	$conexion = DAL::conexion();
	$sql = "select
	    nivel
		from 
		".TBL_ESTRUCTURA_CR_NIVEL."
		where id_nivel = ".$idNivelUnidad."   ";
	$rs1=mysql_query($sql, $conexion) or die(mysql_error());
	$linea = mysql_fetch_assoc($rs1);
	return $linea['nivel'];
  }
  
?>