<?php

  function DB_ESCALA_obtenerDatosEscala($iIdPadre){
  	$iIdPadre = 1;
	$conexion = DAL::conexion();
    $sql1 = " 
		SELECT
		  id_escala,
		  nombre_escala,
		  valor,
		  rango_minimo as minimo,
		  rango_maximo as maximo,
		  color,
		  color_seccion,
		  color_punto 
		FROM
		  slorg_escala
		WHERE 1=1
		  AND id_padre_escala = ".$iIdPadre;
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

  function DB_ESCALA_obtenerDatosEscalaInventario($iIdPadre){
  	$iIdPadre = 1;
	$conexion = DAL::conexion();
    $sql1 = " 
		SELECT
		  rango_minimo_inventario as minimo,
		  rango_maximo_inventario as maximo,
		  color,
		  color_seccion,
		  color_punto 
		FROM
		  slorg_escala
		WHERE
		  id_padre_escala = ".$iIdPadre;	
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

  function DB_ESCALA_obtenerNodo($iIdNodo){
	$conexion = DAL::conexion();    	
    $sql1 = " SELECT * FROM slorg_escala WHERE 1=1 AND id_escala = ".$iIdNodo;	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);	
	if(!$rs1){
	  return array();
	} else {
	  return mysql_fetch_assoc($rs1);
	}
  }

  function DB_ESCALA_obtenerNodosHijos($iIdPadre){
	$conexion = DAL::conexion();
    $sql1 = " SELECT * FROM slorg_escala WHERE 1=1 AND id_padre_escala = ".$iIdPadre;	
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

  
    
?>