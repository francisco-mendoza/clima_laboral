<?php

  function DB_CJURIDICA_obtenerTodasCjuridica($idCliente){
  	$conexion = DAL::conexion();
	$arrFiltro = array();	
    $sql1 = " 
	  SELECT
		*
	  FROM
		slorg_calidad_juridica
	  WHERE id_cliente = ".$idCliente."    
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


?>