<?php


  function DB_RANGO_obtenerRango($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
	$arrFiltro = array();	
    $sql1 = " 
		select
		distinct
		nombre_rango,
		valor,color
		from
		rango a 
		where valor is not null
		and a.valor in ('A','M','B')    
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