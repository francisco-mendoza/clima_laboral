<?php

  function DB_CONDICION_obtenerCondicionesItem($arrParametros){
	$conexion = DAL::conexion();		
	$sql  = " SELECT * FROM rel_item_condicion_pregunta a WHERE id_elemento_formulario = ".$arrParametros['id']." AND validar_accion = 1 ";	
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

  function DB_CONDICION_obtenerCondicionesFormulario($arrParametros){
	$conexion = DAL::conexion();		
	$sql  = " SELECT * FROM rel_item_condicion_pregunta a WHERE id_elemento_formulario = ".$arrParametros['id']." AND validar_guardar = 1 ";	
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

  function DB_CONDICION_obtenerCondicionesFormularioParcial($arrParametros){
	$conexion = DAL::conexion();		
	$sql  = " SELECT * FROM rel_item_condicion_pregunta a WHERE id_elemento_formulario = ".$arrParametros['id']." AND validar_parcial = 1 ";	
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



?>