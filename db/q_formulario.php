<?php

  function DB_FORM_obtenerFechaTermino($idCliente){
  	if(empty($conexion)) $conexion = DAL::conexion();  	
	$sql1="
		SELECT DATE_FORMAT(a.fecha_fin,'%Y/%m/%d') as dias
		  FROM slorg_elemento_formulario a 
		WHERE a.por_defecto = 1
		  AND a.id_cliente = ".$idCliente ;
    $rs=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);
    if(!$rs){
      return array();
    }else{
      $fila = mysql_fetch_assoc($rs);
      return $fila['dias'];
    }	
  }  


  function DB_FORM_obtenerDiasRestantes($idCliente){
  	if(empty($conexion)) $conexion = DAL::conexion();  	
	$sql1="
		SELECT datediff(a.fecha_fin,NOW()) as dias
		  FROM slorg_elemento_formulario a 
		WHERE a.por_defecto = 1
		  AND a.id_cliente = ".$idCliente ;
    $rs=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);
    if(!$rs){
      return array();
    }else{
      $fila = mysql_fetch_assoc($rs);
      return $fila['dias'];
    }	
  }  

  function DB_FORM_obtenerFormulario($arrParametros,$conexion=null){
  	if(empty($conexion)) $conexion = DAL::conexion();
    
    $sql  = " SELECT * FROM slorg_elemento_formulario a INNER JOIN slorg_elemento_formulario_nivel b ON a.id_nivel_elemento_formulario = b.id_nivel WHERE 1=1 AND a.por_defecto=1 AND a.id_cliente=".$arrParametros['id_cliente']." AND b.nivel = 1  AND a.id_estado = 1 ";
	//$sql.= " AND fecha_fin    > NOW() ";
	//$sql.= " AND fecha_inicio < NOW() ";//validacion de fecha
	
    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
    if(!$rs){
      return array();
    }else{
      return mysql_fetch_assoc($rs);
    }
  }

  function DB_FORM_obtenerItems($arrParametros,$conexion){
    $sql  = " SELECT * FROM slorg_elemento_formulario a INNER JOIN slorg_elemento_formulario_nivel b ON a.id_nivel_elemento_formulario=b.id_nivel WHERE 1=1 AND b.nivel = 2 AND a.id_elemento_formulario_padre=".$arrParametros['id']." AND a.id_estado = 1 order by a.orden ASC ";
    //echo $sql;
    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
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

  function DB_FORM_obtenerTipoElemento($arrParametros){
	$conexion = DAL::conexion();  	
    $sql = " SELECT * FROM slorg_elemento_formulario a INNER JOIN slorg_tipo_elemento_formulario b ON a.id_tipo_elemento = b.id_tipo_elemento WHERE 1=1 AND a.id=".$arrParametros['id']." ";
    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
    if(!$rs){
      return array();
    } else {
    	$fila = mysql_fetch_assoc($rs);
	  return $fila['nombre'];
    }
  }

  function DB_FORM_obtenerElemento($arrParametros){
	$conexion = DAL::conexion();  	
    $sql = " SELECT * FROM slorg_elemento_formulario a LEFT JOIN slorg_tipo_elemento_formulario b ON a.id_tipo_elemento = b.id_tipo_elemento WHERE 1=1 AND a.id=".$arrParametros['id']." ";
    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);
    if(!$rs){
      return array();
    } else {
   	  return mysql_fetch_assoc($rs);
	}
  }


?>