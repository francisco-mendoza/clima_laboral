<?php
  function DB_NEGOCIO_obtenerTodasDimensionesIstas($idCliente){
	$conexion = DAL::conexion();	
	$sql1 ="
		SELECT
		  a.id_elemento_negocio as id_dimension
		 ,a.sigla as dimension
		 ,a.orden as numeracion 		 
		FROM
		  slorg_elemento_negocio a 
		WHERE 1=1
		  AND a.id_nivel = 6
	";
	$rs1=mysql_query($sql1, $conexion) or die(mysql_error());
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

  function DB_NEGOCIO_obtenerVariablesxDimension($idDimension){
	$conexion = DAL::conexion();	
	$sql1 ="
		SELECT
		  a.id_elemento_negocio as id_dimension
		 ,a.nombre_elemento as dimension
		 ,b.id_elemento_negocio as id_variable
		 ,b.nombre_elemento as variable 		 
		FROM
		  slorg_elemento_negocio a 
		INNER JOIN 
		  slorg_elemento_negocio b
		ON a.id_elemento_negocio = b.id_elemento_padre
		WHERE 1=1
		  AND b.id_nivel = 2
		  AND a.id_elemento_negocio = ".$idDimension."
	";
	$rs1=mysql_query($sql1, $conexion) or die(mysql_error());
	if(!$rs1){
	  return 0;	
	} else {
	  return mysql_num_rows($rs1);	
	}   	
  }
  
  function DB_NEGOCIO_obtenerDimensionesVariables(){
	$conexion = DAL::conexion();	
	$sql1 ="
		SELECT
		  a.id_elemento_negocio as id_dimension
		 ,a.nombre_elemento as dimension
		 ,b.id_elemento_negocio as id_variable
		 ,b.nombre_elemento as variable 		 
		FROM
		  slorg_elemento_negocio a 
		INNER JOIN 
		  slorg_elemento_negocio b
		ON a.id_elemento_negocio = b.id_elemento_padre
		WHERE 1=1
		  AND b.id_nivel = 2
	";
	$rs1=mysql_query($sql1, $conexion) or die(mysql_error());
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

  function DB_NEGOCIO_obtenerPreguntasCuestionario(){
	$conexion = DAL::conexion();	
	$sql1 ="
		SELECT
		  a.id_elemento_negocio as id
		 ,a.nombre_elemento as nombre
		 ,b.nombre_nivel as categoria
		 ,a.orden
		FROM
		slorg_elemento_negocio a 
		INNER JOIN
		slorg_elemento_negocio_nivel b
		ON a.id_nivel = b.id_nivel
		WHERE 1=1
		AND a.id_nivel = 3
		order by a.orden	
	";
	$rs1=mysql_query($sql1, $conexion) or die(mysql_error());
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

  function DB_NEGOCIO_obtenerVariables(){
	$conexion = DAL::conexion();	
	$sql1 ="
		select
		  a.id_elemento_negocio as id
		, a.nombre_elemento as nombre
		, b.nombre_nivel as categoria
		from
		slorg_elemento_negocio a 
		inner join
		slorg_elemento_negocio_nivel b
		ON a.id_nivel = b.id_nivel
		where 1=1
		and a.id_nivel = 2	
	";
	$rs1=mysql_query($sql1, $conexion) or die(mysql_error());
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

 

  function DB_NEGOCIO_cantidadDimensiones($idCliente,$conexion) {
	$sql1 ="select * from slorg_dimension where 1=1 and id_cliente=".$idCliente." order by id";
	$rs1=mysql_query($sql1, $conexion) or die(mysql_error());
    return intval(mysql_num_rows($rs1));
  }	
  
  function DB_NEGOCIO_textosPresentacion($idCliente,$conexion) {  
	$sqlp ="select texto1,texto2,texto3,p61, p62, p63 from slorg_admin where id_cliente=".$idCliente;
 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());	
 	if(!$rs1){
	  return array(); 		
 	} else {
 	  return mysql_fetch_array($rs1); 		
 	}
  }
  
  function DB_NEGOCIO_segmentacionCabecera($idCliente,$conexion) {
	$sqlp ="select id,name,title from slorg_segmentaciones where id_cliente=".$idCliente;
 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
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
  
  function DB_NEGOCIO_segmentacionDetalle($idSegmentacion,$idCliente,$conexion) {
	$sqlso = "SELECT a.* FROM slorg_segmentacion_opciones a INNER JOIN slorg_segmentaciones b ON a.segmentacion_id = b.id WHERE a.segmentacion_id='".$idSegmentacion."' AND b.id_cliente=".$idCliente;
    $rsso=mysql_query($sqlso, $conexion) or die(mysql_error());
	if(!$rsso){
	  return array();	
	} else {
	  $arrResultados = array();	
	  while($row = mysql_fetch_assoc($rsso)){
	  	$arrResultados[] = $row;
	  }	
	  return $arrResultados;	
	}           	
  }
  
  function DB_NEGOCIO_obtenerPreguntas($idCliente,$conexion) {
    $sqlp ="select a.n,a.pregunta from dpp_slorg_preguntas a inner join slorg_dimension b ON a.dimension = b.id where b.id_cliente=".$idCliente." order by posicion ASC";
    $rs1=mysql_query($sqlp, $conexion) or die(mysql_error());	
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
  
  function DB_NEGOCIO_obtenerDimensiones($idCliente,$conexion){
  	$sql1 ="select * from slorg_dimension where id_cliente=".$idCliente." order by id";
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error());  	
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
  
  /**
   * Estructura Nueva
   */
   
  function DB_NEGOCIO_obtenerNodosNivel($iIdNivel){
	$conexion = DAL::conexion();    	
    $sql1 = " SELECT * FROM slorg_elemento_negocio WHERE 1=1 AND id_nivel = ".$iIdNivel." order by orden ";
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
  
  function DB_NEGOCIO_obtenerNodosHijos($iIdPadre){
	$conexion = DAL::conexion();
    $sql1 = " SELECT * FROM slorg_elemento_negocio WHERE 1=1 AND id_elemento_padre = ".$iIdPadre." order by orden ";	
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