<?php

  function DB_PARAMETROS_obtenerParametro($idCliente,$strNombreGrafico){
  	$conexion = DAL::conexion();
	$arrFiltro = array();	
    $sql1 = " 
		SELECT
		 *
		FROM
		  parametros_graficos_cliente
		WHERE 1=1
		  AND id_cliente     = ".$idCliente."
		  AND nombre_grafico = '".$strNombreGrafico."'	   
        ";	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	$arrResultados[$row['parametro']] = $row['valor'];  
	  }
	  return $arrResultados;	
	}  	  	
  }

  function DB_PARAMETROS_obtenerInfoGraficos(){
  	$conexion = DAL::conexion();
	$arrFiltro = array();	
    $sql1 = " 
		SELECT
		 *
		FROM
		  grafico
		WHERE 1=1 
   
        ";	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	$arrResultados[$row['codigo']] = array(
	  	   "titulo" => $row['titulo']
	  	  ,"descripcion" => $row['descripcion']		
		);  
	  }
	  return $arrResultados;	
	}  	  	
  }













?>