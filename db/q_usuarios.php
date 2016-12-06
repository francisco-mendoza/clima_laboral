<?php

  function DB_USUARIO_cantidadUsrActivos($idCliente){
	$conexion = DAL::conexion();	
	$sql = "select count(*) as cantidad from dpp_slorg_usuario where id_estado=1 ";	
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
	if(!$rs){
	  return 0;	
	} else {
	  $fila = mysql_fetch_assoc($rs);
	  return $fila['cantidad'];	
	}  	
  }

  function DB_USUARIO_validarUsuario($arrParametros,$conexion){
	$sql = "select a.id_usuario,a.rut,a.id_cliente,a.password,a.id_unidad from dpp_slorg_usuario a ";	
	$sql.= " where ";
	$sql.=" a.password  ='".$arrParametros['password']."' ";
	foreach($arrParametros['username']['campos'] as $key => $value){
 	  $sql.= " AND a.".$value."='".$arrParametros['username']['datos'][$key]."'" ;	   	
	}
	$sql.=" AND a.id_estado = 1";
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
	if(!$rs){
	  return array();	
	}else{
	  return mysql_fetch_assoc($rs);	
	}
  }

  function DB_USUARIO_obtenerTiposDeUsuario($arrParametros,$conexion){
	$sql  = " SELECT id_tipo_usuario FROM rel_usuario_tipo_usuario a where id_usuario = ".$arrParametros['id_usuario'];		
	//$sql  = " SELECT group_concat(id_tipo_usuario) as id_tipo_usuario FROM rel_usuario_tipo_usuario a where id_usuario = ".$arrParametros['id_usuario'];
		
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
	if(!$rs){
	  return array();
	}else{
	  $arrResultados = array();	
	  while($row = mysql_fetch_assoc($rs)){
	  	$arrResultados[] = $row["id_tipo_usuario"];
	  }	
	  return $arrResultados;
	}
  }
  
  function DB_USUARIO_obtenerMenus($arrParametros,$conexion){
  	
	$sql = " SELECT ";
	$sql.= "   DISTINCT a.id_menu,a.nombre_menu,a.path ";
	$sql.= " FROM ";
	$sql.= "   slorg_menu a ";
	$sql.= " INNER JOIN ";
	$sql.= "   rel_tipo_usuario_menu b ";
	$sql.= " ON a.id_menu = b.id_menu ";
	$sql.= "   WHERE b.id_tipo_usuario IN (".implode(",",$arrParametros).") ";
	$sql.= "   AND a.estado_menu = 1 ";
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
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
  
  function DB_USUARIO_listadoUsuarios($arrParametros,$conexion){
	$sql = " SELECT
 			   a.id_usuario as id
			  ,CONCAT_WS('',a.nombre
			  ,a.apellido_paterno
			  ,a.apellido_materno) as nombre
			 FROM
  			   dpp_slorg_usuario a 
			 WHERE 1=1
			 AND id_cliente=".$arrParametros['id_cliente'];
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
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
  
  function DB_USUARIO_obtenerIdUsuarioDeRut($arrParametros,$conexion){
	$sql = "select a.id_usuario from dpp_slorg_usuario a ";	
	$sql.= " where ";
	$sql.=" a.rut  ='".$arrParametros['rut']."' ";
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
	if(!$rs){
	  return '';	
	}else{
	  $linea = mysql_fetch_assoc($rs);
	  return $linea['id_usuario'];	
	}
  }  
  
?>