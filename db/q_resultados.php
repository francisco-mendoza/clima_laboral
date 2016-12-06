<?php

  function obtenerFiltroAntiguedad($arrAntiguedad,$aliasTbUsr){
	$str = " AND ".$aliasTbUsr.".fecha_ingreso_cargo <= '".$arrAntiguedad['minimo']."' ";
	if(isset($arrAntiguedad['maximo'])){
	  $str.= " AND ".$aliasTbUsr.".fecha_ingreso_cargo >= '".$arrAntiguedad['maximo']."' ";		
	}
	return $str;
  }

  function DB_RESULT_obtenerParticipacion($idCliente){
  	$conexion = DAL::conexion();
  	$sql1 = "
		SELECT
		  count(distinct a.id_usuario) as cantidad
		FROM
		  resultados a
		  INNER JOIN dpp_slorg_usuario b on a.id_usuario = b.id_usuario
		  INNER JOIN slorg_elemento_formulario c on a.id_elemento_formulario = c.id
		  INNER JOIN slorg_elemento_formulario d on c.id_elemento_formulario_padre = d.id
		WHERE d.por_defecto = 1
		  AND a.completado = 1
		  AND b.id_cliente = ".$idCliente." ";
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);
	if(!$rs1){
	  return 0;
	} else {	
	  $fila = mysql_fetch_assoc($rs1);
	  return $fila['cantidad'];
	}	
  }

  function DB_RESULT_obtenerClasificacionRiesgo($idCliente,$arrParametros=array()){
  	$conexion = DAL::conexion();  	
	$sql1 = "  
		SELECT  
		  *  
		FROM  
		  v_usuario_vs_dim_istas_clasificacion	
		WHERE 1=1
		AND id_nivel   = ".$arrParametros['id_nivel']."  
		AND id_cliente = ".$idCliente."  
	  ";
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	if(!isset($arrResultados[$row["id_dimension"]])){
	  	  $arrResultados[$row["id_dimension"]] = array();	
	  	}
	  	if(!isset($arrResultados[$row["id_dimension"]]["".$row["valor"]])){
	  	  $arrResultados[$row["id_dimension"]]["".$row["valor"]] = $row['cantidad'];
	  	}	  	
	  	//$arrResultados[$row["id_variable"]][$row["valor"]] = $row['cantidad'];  
	  }
	  return $arrResultados;	
	} 		
  }

  function DB_RESULT_obtenerPromedioPorCr($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
	$arrFiltro = array();	
    $sql1 = "
	  SELECT
	     COALESCE(uni5.id,uni4.id,uni3.id,uni2.id,uni1.id) as id_unidad_raiz
 	    ,COALESCE(uni5.sigla,uni4.sigla,uni3.sigla,uni2.sigla,uni1.sigla) as sigla_unidad_raiz	
	    ,ROUND(AVG(clas.promedio),2) as promedio
      FROM
        	v_usuario_vs_preguntas clas 
	   JOIN dpp_slorg_usuario a ON clas.id_usuario = a.id_usuario
	   JOIN slorg_unidad uni1 ON a.id_unidad = uni1.id
	   LEFT JOIN slorg_unidad uni2 ON uni1.id_padre = uni2.id
	   LEFT JOIN slorg_unidad uni3 ON uni2.id_padre = uni3.id
	   LEFT JOIN slorg_unidad uni4 ON uni3.id_padre = uni4.id
	   LEFT JOIN slorg_unidad uni5 ON uni4.id_padre = uni5.id
	WHERE 1=1
	  AND a.id_cliente = ".$idCliente."
	  AND clas.id_nivel_preguntas = ".$arrParametros['id_nivel_preguntas']."
	  AND clas.id_nivel_variables = ".$arrParametros['id_nivel_variables']."	
	GROUP BY
	  id_unidad_raiz
	 ,sigla_unidad_raiz
	ORDER BY 
	  id_unidad_raiz
	 ,sigla_unidad_raiz";	
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

  function obtenerFiltroCR($idCr,$aliasTablaUsuario){
	$sqljoin = "
	  INNER JOIN slorg_unidad uni1 ON ".$aliasTablaUsuario.".id_unidad = uni1.id
	  LEFT JOIN slorg_unidad uni2  ON uni1.id = uni2.id_padre
	  LEFT JOIN slorg_unidad uni3  ON uni2.id = uni3.id_padre
	  
		INNER JOIN slorg_unidad uni4 ON ".$aliasTablaUsuario.".id_unidad = uni4.id
		LEFT JOIN slorg_unidad uni5 ON uni5.id = uni4.id_padre
		LEFT JOIN slorg_unidad uni6 ON uni6.id = uni5.id_padre	  
	  
	";	
	$sqlwhere = "
		AND (
		       uni1.id = ".$idCr." 
		    OR uni1.id_padre = ".$idCr."
		    OR uni2.id_padre = ".$idCr."
		    OR uni2.id = ".$idCr."
		    OR uni3.id_padre = ".$idCr."
		    OR uni3.id = ".$idCr."
		    
		    OR uni4.id = ".$idCr." 
		    OR uni4.id_padre = ".$idCr."
		    OR uni5.id_padre = ".$idCr."
		    OR uni5.id = ".$idCr."
		    OR uni6.id_padre = ".$idCr."
		    OR uni6.id = ".$idCr."		    
		    
		 )
	";	
	return array(
	  "join"  => $sqljoin
	  ,"where" => $sqlwhere
	);
  }

  function DB_RESULT_obtenerClasificacionCrEscala($idCliente,$arrParametros=array()){
  	$conexion = DAL::conexion();
  	$sql1="
	 SELECT
	   COALESCE(uni5.id,uni4.id,uni3.id,uni2.id,uni1.id) as id_unidad_raiz
	  ,COALESCE(uni5.sigla,uni4.sigla,uni3.sigla,uni2.sigla,uni1.sigla) as sigla_unidad_raiz
	  ,clas.id_escala
	  ,clas.nombre_escala
	  ,clas.valor
	  ,COUNT(clas.id_usuario) as cantidad 
	 FROM
			v_usuario_preguntas_clasificacion clas
	     JOIN dpp_slorg_usuario a ON clas.id_usuario = a.id_usuario
	     JOIN slorg_unidad uni1 ON a.id_unidad = uni1.id
	 LEFT JOIN slorg_unidad uni2 ON uni1.id_padre = uni2.id
	 LEFT JOIN slorg_unidad uni3 ON uni2.id_padre = uni3.id
	 LEFT JOIN slorg_unidad uni4 ON uni3.id_padre = uni4.id
	 LEFT JOIN slorg_unidad uni5 ON uni4.id_padre = uni5.id
	WHERE 1=1
	  AND clas.id_padre_escala = ".$arrParametros['id_escala']."
	  AND clas.id_nivel_preguntas = ".$arrParametros['id_nivel_preguntas']."
	  AND clas.id_nivel_variables = ".$arrParametros['id_nivel_variables']."
	  AND clas.id_cliente = ".$idCliente."
	GROUP BY
	  id_unidad_raiz
	 ,sigla_unidad_raiz
	 ,clas.id_escala
	 ,clas.nombre_escala
	 ,clas.valor
	ORDER BY 
	 id_unidad_raiz
	,sigla_unidad_raiz
	,id_escala
	,nombre_escala	
	,valor  	
  	";
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	if(!isset($arrResultados[$row["id_unidad_raiz"]])){
	  	  $arrResultados[$row["id_unidad_raiz"]] = array();	
	  	}
	  	if(!isset($arrResultados[$row["id_unidad_raiz"]]["".$row["valor"]])){
	  	  $arrResultados[$row["id_unidad_raiz"]]["".$row["valor"]] = $row['cantidad'];
	  	}	  	
	  	//$arrResultados[$row["id_variable"]][$row["valor"]] = $row['cantidad'];  
	  }
	  return $arrResultados;	
	} 	
  }
   
  function DB_RESULT_obtenerClasificacionVariableEscala($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
	$arrFiltro = array();  	
	$sql1="
		select
		id_variable, variable, valor, count(*) as cantidad
		from
		v_usuario_variable_clasificacion a
		join
		dpp_slorg_usuario b on a.id_usuario = b.id_usuario";
    if(isset($arrParametros['id_cr'])){
      $arrFiltro = obtenerFiltroCR($arrParametros['id_cr'],'b');
	  $sql1.= $arrFiltro['join'];		
    }		
	$sql1.=" WHERE a.id_cliente =  ".$idCliente." ";
	$sql1.=" AND a.id_nivel =  ".$arrParametros["id_nivel"]." ";	
	$sql1.=" AND a.id_padre_escala = ".$arrParametros["id_escala"]." ";	

    if(isset($arrParametros['id_estamento'])){
      $sql1.= " AND b.id_estamento = ".$arrParametros['id_estamento'];  	
    }
    if(isset($arrParametros['id_sexo'])){
      $sql1.= " AND b.sexo = '".$arrParametros['id_sexo']."'";  	
    }
    if(isset($arrParametros['id_cjuridica'])){
      $sql1.= " AND b.id_calidad_juridica = ".$arrParametros['id_cjuridica']." ";  	
    }          
    if(isset($arrParametros['antiguedad'])){
	  $sql1.= obtenerFiltroAntiguedad($arrParametros['antiguedad'],'b');    	
    }    
    if(isset($arrParametros['id_cr'])){
	  $sql1.= $arrFiltro['where'];		
    }		
	$sql1.=" GROUP BY id_variable, variable, valor
	   ORDER BY id_variable, variable, valor	
	";	

    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	if(!isset($arrResultados[$row["id_variable"]])){
	  	  $arrResultados[$row["id_variable"]] = array();	
	  	}
	  	if(!isset($arrResultados[$row["id_variable"]]["".$row["valor"]])){
	  	  $arrResultados[$row["id_variable"]]["".$row["valor"]] = $row['cantidad'];
	  	}	  	
	  	//$arrResultados[$row["id_variable"]][$row["valor"]] = $row['cantidad'];  
	  }
	  return $arrResultados;	
	} 	
  } 

  function DB_RESULT_obtenerTodosPromediosVariable($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
	$arrFiltro = array();
    $sql1 = " SELECT 
  	  a.id_variable
 	 ,a.variable
 	 ,a.promedio
	FROM
	  v_usuario_vs_variable a 
	JOIN dpp_slorg_usuario b on a.id_usuario = b.id_usuario ";
      if(isset($arrParametros['id_cr'])){
        $arrFiltro = obtenerFiltroCR($arrParametros['id_cr'],'b');
        $sql1.= $arrFiltro['join'];		
      }			  
	  $sql1.=" WHERE a.id_cliente = ".$idCliente." ";
	  $sql1.=" AND a.id_nivel = ".$arrParametros['id_nivel_variable']." ";	  	  
      if(isset($arrParametros['id_estamento'])){
        $sql1.= " AND b.id_estamento = ".$arrParametros['id_estamento'];  	
      }
      if(isset($arrParametros['id_sexo'])){
        $sql1.= " AND b.sexo = '".$arrParametros['id_sexo']."'";  	
      }
      if(isset($arrParametros['id_cjuridica'])){
        $sql1.= " AND b.id_calidad_juridica = ".$arrParametros['id_cjuridica']." ";  	
      }	  
      if(isset($arrParametros['antiguedad'])){
	    $sql1.= obtenerFiltroAntiguedad($arrParametros['antiguedad'],'b');    	
      }
      if(isset($arrParametros['id_cr'])){
        $sql1.= $arrFiltro['where'];		
      }		  
	  $sql1.=" ORDER BY
	   a.id_variable
	  ,a.variable
	  ,a.promedio ";	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	if(!isset($arrResultados[$row["id_variable"]])){
	  	  $arrResultados[$row["id_variable"]] = array();	
	  	}
	  	$arrResultados[$row["id_variable"]][] = $row['promedio'];  
	  }
	  return $arrResultados;	
	}  	  	
  }
  function DB_RESULT_obtenerPromedioGeneral($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
	$arrFiltro = array();  	
    $sql1 = "
	  select
		ROUND(AVG(a.promedio),2) as promedio 
	  from
		v_usuario_vs_variable a 
	  inner join  dpp_slorg_usuario b on a.id_usuario = b.id_usuario ";
  if(isset($arrParametros['id_cr'])){
    $arrFiltro = obtenerFiltroCR($arrParametros['id_cr'],'b');
    $sql1.= $arrFiltro['join'];		
  }
  $sql1.=" WHERE a.id_cliente = ".$idCliente." ";
  $sql1.=" AND a.id_nivel = ".$arrParametros['id_nivel_variable']." ";
  if(isset($arrParametros['id_estamento'])){
    $sql1.= " AND b.id_estamento = ".$arrParametros['id_estamento'];  	
  }
  if(isset($arrParametros['id_sexo'])){
    $sql1.= " AND b.sexo = '".$arrParametros['id_sexo']."'";  	
  }
  if(isset($arrParametros['id_cjuridica'])){
    $sql1.= " AND b.id_calidad_juridica = ".$arrParametros['id_cjuridica']." ";  	
  }      
  if(isset($arrParametros['antiguedad'])){
	$sql1.= obtenerFiltroAntiguedad($arrParametros['antiguedad'],'b');    	
  }  
  if(isset($arrParametros['id_cr'])){
    $sql1.= $arrFiltro['where'];		
  }	
	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return 0;
	} else {
	  $row = mysql_fetch_assoc($rs1);
	  return $row['promedio'];	
	}  	  	
  }

function ObtenerPromedioPorEstamento($idCliente,$idEstamento)
{
	$conexion = DAL::conexion();
	$sql = "
	SELECT truncate(
	sum(valor)/(260*(select count(distinct r.id_usuario) FROM resultados r
	INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
	where r.id_elemento_formulario = 6 and u.id_estamento =".$idEstamento.")),2 ) AS PROMEDIO
	FROM psicus_satisfaccion_laboral_cliente2.resultados r
	INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
	WHERE 1=1 AND id_elemento_formulario = 6 AND u.id_cliente = ".$idCliente."
	and u.id_estamento =".$idEstamento.";

	  ";
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	$row = mysql_fetch_row($rs);
	return $row['0'];
}


function ObtenerPromedioInstitucion($idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
	SELECT truncate (
	sum(valor)/(260*(select count(distinct r.id_usuario) FROM resultados r
	INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
	where r.id_elemento_formulario = 6)),2 )  AS PROMEDIO
	FROM psicus_satisfaccion_laboral_cliente2.resultados r
	INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
	WHERE 1=1 AND id_elemento_formulario = 6 AND u.id_cliente = ".$idCliente.";

	  ";
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	$row = mysql_fetch_row($rs);
	return $row['0'];
}


function ObtenerPromedioPorSexo($idCliente, $idSexo)
{
	$conexion = DAL::conexion();
	$sql ="
	SELECT
	truncate(sum(valor)/(260*(select count(distinct r.id_usuario) FROM resultados r
				 INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
				 WHERE r.id_elemento_formulario = 6 AND u.id_cliente = ".$idCliente."
				 and u.sexo = '".$idSexo."' )),2) AS PROMEDIO
	FROM psicus_satisfaccion_laboral.resultados r
	INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario WHERE 1=1
	AND id_elemento_formulario = 6 AND u.id_cliente = ".$idCliente." and u.sexo = '".$idSexo."' ;
	";
	$rs = mysql_query($sql,$conexion) or die (mysql_error().$sql);
	$row = mysql_fetch_row($rs);
	return $row ['0'];
	//return $sql;
}


function ObtenerPromedioPorCalidadJuridica($idCliente, $idCalidadJuridica)
{
	$conexion = DAL::conexion();
	$sql ="
	SELECT
	truncate(sum(valor)/(260*(select count(distinct r.id_usuario) FROM resultados r
				 INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
				 WHERE r.id_elemento_formulario = 6  AND u.id_cliente = ".$idCliente."
				 AND id_calidad_juridica = ".$idCalidadJuridica." )),2) AS PROMEDIO
	FROM psicus_satisfaccion_laboral.resultados r
	INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
	WHERE 1=1 AND id_elemento_formulario = 6 AND u.id_cliente = ".$idCliente."
	AND id_calidad_juridica = ".$idCalidadJuridica."  ;
	";
	$rs = mysql_query($sql,$conexion) or die (mysql_error().$sql);
	$row = mysql_fetch_row($rs);
	return $row ['0'];
}

function ObtenerPromedioPorAntiguedad($idCliente, $idAntiguedad)
{
	$conexion = DAL::conexion();
	$sql ="
	SELECT
	truncate(sum(valor)/(260*(select count(distinct r.id_usuario) FROM resultados r
				 INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
				 where r.id_elemento_formulario = 6  AND u.id_cliente = ".$idCliente."
                 and r.id_usuario in (SELECT id_usuario FROM psicus_satisfaccion_laboral.resultados
                 where id_elemento_negocio =".$idAntiguedad."))),2) AS PROMEDIO
	FROM psicus_satisfaccion_laboral.resultados r
	INNER JOIN dpp_slorg_usuario u on r.id_usuario = u.id_usuario
	WHERE 1=1 AND id_elemento_formulario = 6 AND u.id_cliente = ".$idCliente."
	and r.id_usuario in (SELECT id_usuario FROM psicus_satisfaccion_laboral.resultados
	where id_elemento_negocio =".$idAntiguedad.")  ;
		";
	$rs = mysql_query($sql,$conexion)or die (mysql_error().$sql);
	$row = mysql_fetch_row($rs);
	return $row ['0'];
}

function DB_ObtenerPromedioInstituciones($id)
{
	$conexion = DAL::conexion();
	$sql ="SELECT indicePromedio FROM promedio_instituciones where id = ".$id." ";
	$rs = mysql_query($sql,$conexion)or die (mysql_error().$sql);
	$row = mysql_fetch_row($rs);
	return $row['0'];
}

function ObtenerPromedioInstitucionPorVariables($idCliente)
{
	$conexion = DAL::conexion();
	$arrFiltro = array();	
    $sql = "
   SELECT
     ROUND(AVG(a.promedio),2) as promedio 
   FROM
    v_usuario_vs_variable a 
   JOIN dpp_slorg_usuario b on a.id_usuario = b.id_usuario ";

   $rs = mysql_query($sql,$conexion)or die (mysql_error().$sql);
   $row = mysql_fetch_row($rs);
	return $row['0'];


}


function DB_RESULT_obtenerPromedioPorVariable($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
	$arrFiltro = array();	
    $sql1 = "
   SELECT
     a.id_variable
    ,a.variable
    ,ROUND(AVG(a.promedio),2) as promedio 
   FROM
    v_usuario_vs_variable a 
   JOIN dpp_slorg_usuario b on a.id_usuario = b.id_usuario ";
  
  if(isset($arrParametros['id_cr'])){
    $arrFiltro = obtenerFiltroCR($arrParametros['id_cr'],'b');
    $sql1.= $arrFiltro['join'];		
  }
  		
$sql1.=" WHERE a.id_cliente = ".$idCliente." ";
$sql1.=" AND a.id_nivel = ".$arrParametros['id_nivel_variable']." ";

  if(isset($arrParametros['id_estamento'])){
    $sql1.= " AND b.id_estamento = ".$arrParametros['id_estamento'];  	
  }
  if(isset($arrParametros['antiguedad'])){
	$sql1.= obtenerFiltroAntiguedad($arrParametros['antiguedad'],'b');    	
  }    
  if(isset($arrParametros['id_sexo'])){
    $sql1.= " AND b.sexo = '".$arrParametros['id_sexo']."'";  	
  }
  if(isset($arrParametros['id_cjuridica'])){
    $sql1.= " AND b.id_calidad_juridica = ".$arrParametros['id_cjuridica']." ";  	
  }  
  if(isset($arrParametros['id_cr'])){
    $sql1.= $arrFiltro['where'];		
  }
$sql1.=" GROUP BY id_variable,a.variable ";	
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

  function DB_RESULT_obtenerClasificacionUsuariosEscala($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
	$arrFiltro = array();  	
    $sql1 = "
			SELECT 
			   b.id_escala
			  ,b.nombre_escala
			  ,b.valor
			  ,count(a.id_escala) as cantidad
			FROM 
			  slorg_escala b
			LEFT JOIN v_usuario_preguntas_clasificacion a on b.id_escala = a.id_escala
			LEFT JOIN dpp_slorg_usuario c ON a.id_usuario = c.id_usuario 
			";		
    if(isset($arrParametros['id_cr'])){
      $arrFiltro = obtenerFiltroCR($arrParametros['id_cr'],'c');
      $sql1.= $arrFiltro['join'];
    }
    $sql1.= " WHERE 1=1 ";
    $sql1.= "   AND a.id_padre_escala = ".$arrParametros['id_escala'];
    $sql1.= "   AND a.id_nivel_preguntas = ".$arrParametros['id_nivel_preguntas'];
    $sql1.= "   AND a.id_nivel_variables = ".$arrParametros['id_nivel_variables'];
    $sql1.= "   AND a.id_cliente = ".$idCliente."	";
	
    if(isset($arrParametros['id_estamento'])){
      $sql1.= " AND c.id_estamento = ".$arrParametros['id_estamento'];  	
    }	
    if(isset($arrParametros['id_sexo'])){
      $sql1.= " AND c.sexo = '".$arrParametros['id_sexo']."'";  	
    }
    if(isset($arrParametros['id_cjuridica'])){
      $sql1.= " AND c.id_calidad_juridica = ".$arrParametros['id_cjuridica']." ";  	
    }	
    if(isset($arrParametros['antiguedad'])){
	  $sql1.= obtenerFiltroAntiguedad($arrParametros['antiguedad'],'c');    	
    }  
    if(isset($arrParametros['id_cr'])){
      $sql1.= $arrFiltro['where'];		
    }	
    $sql1.= "     	
			GROUP BY 
			  b.id_escala
			 ,b.nombre_escala			
			 ,b.valor
			 ORDER BY id_escala,valor
	    	";	
			//echo "$sql1";die();
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

  function DB_RESULT_obtenerClasificacionUsuariosPregunta($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
    $sql1 = "
			select 
			  id_clasificacion_escala,
			  nombre_clasificacion_escala,
			  count(*) as cantidad
			from v_usuario_preguntas_clasificacion
			WHERE 1=1
	AND id_padre_escala = ".$arrParametros['id_escala']."
	AND id_nivel_preguntas = ".$arrParametros['id_nivel_preguntas']."
	AND id_nivel_variables = ".$arrParametros['id_nivel_variables']."
	AND id_cliente = ".$idCliente."			
			group by 
			  id_clasificacion_escala,
			  nombre_clasificacion_escala
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

  function DB_RESULT_obtenerBalanceSocial($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
    $sql1 = "
		SELECT
		   id_dimension
		  ,dimension
		  ,id_variable
		  ,variable
		  ,id_clasificacion_escala
		  ,nombre_clasificacion_escala
		  ,count(*) AS cuantos
		FROM
		  v_usuario_variable_clasificacion a
		WHERE 1=1
		  AND a.id_padre_escala = ".$arrParametros['id_escala']."
		  AND a.id_nivel = ".$arrParametros['id_nivel']."		   
		  AND a.id_cliente = ".$idCliente."		   		   
		GROUP BY
		   id_dimension
		  ,dimension
		  ,id_variable
		  ,variable
		  ,id_clasificacion_escala
		  ,nombre_clasificacion_escala
		ORDER BY
		   id_dimension
		  ,dimension
		  ,id_variable
		  ,variable
		  ,id_clasificacion_escala
		  ,nombre_clasificacion_escala  
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
  
  //////////////////////////////////////////////////////////////////////

  function DB_RESULT_obtenerResultadoPregunta($idCliente,$arrParametros=array()){
  	$conexion = DAL::conexion();
    $sql1 = "
			SELECT
			 b.id_elemento_negocio as id
			 ,b.orden
			 ,b.nombre_elemento as nombre
			,AVG(a.valor)/".$arrParametros['puntaje_maximo_escala']." as promedio
			FROM
			resultados a 
			INNER JOIN slorg_elemento_negocio b ON a.id_elemento_negocio = b.id_elemento_negocio
			INNER JOIN dpp_slorg_usuario e ON a.id_usuario = e.id_usuario
			AND b.id_tipo_elemento_negocio = ".$arrParametros['tipo_elemento_negocio']."
			AND b.id_nivel   = ".$arrParametros['id_nivel']."
			AND e.id_cliente = ".$idCliente."
			AND a.completado = 1
			GROUP BY
			  b.id_elemento_negocio,b.orden,b.nombre_elemento
			ORDER BY b.orden          
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

  function DB_RESULT_obtenerUsuarioxPriorizacionxVariable($idCliente,$arrParametros = array()){
  	$conexion = DAL::conexion();
    $sql1 = "
	  SELECT
		  a.id_usuario
		 ,b.id_elemento_negocio as id_variable
	    ,a.valor
	   FROM
		 resultados a
	   INNER JOIN slorg_elemento_negocio b ON a.id_elemento_negocio = b.id_elemento_negocio
	   INNER JOIN dpp_slorg_usuario e ON a.id_usuario = e.id_usuario	  
	   WHERE 1=1
		 AND b.id_tipo_elemento_negocio = ".$arrParametros['tipo_elemento_negocio']."
		 AND b.id_nivel = ".$arrParametros['id_nivel']."
		 AND e.id_cliente = ".$idCliente."
		 AND a.completado =1
        ORDER BY id_usuario,b.id_elemento_negocio	    
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

  function DB_RESULT_obtenerUsuarioxSuma($idCliente){
  	$conexion = DAL::conexion();
    $sql1 = "
	  SELECT
		  a.id_usuario
		 ,SUM(a.valor) as suma
	   FROM
		 resultados a
	   INNER JOIN slorg_elemento_negocio b ON a.id_elemento_negocio = b.id_elemento_negocio
	   INNER JOIN dpp_slorg_usuario e ON a.id_usuario = e.id_usuario	  
	  WHERE 1=1
		AND b.id_tipo_elemento_negocio = 1
		AND b.id_nivel = 3
		AND e.id_cliente = ".$idCliente."
		AND a.completado =1
	  GROUP BY
	    a.id_usuario
    ";	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	$row['porcentaje'] = $row['suma']/260; 
	  	$arrResultados[$row['id_usuario']] = $row;
	  }
	  return $arrResultados;	
	}    	
  }
  
  function DB_RESULT_obtenerUsuarioxSumaxVariable($idCliente){
  	$conexion = DAL::conexion();
    $sql1 = "
	  SELECT
		  a.id_usuario
		 ,b.id_elemento_padre as id_variable
		 ,c.nombre_elemento as variable
		 ,d.id_elemento_negocio as id_dimension
		 ,d.nombre_elemento as dimension
		 ,c.puntaje
		 ,SUM(a.valor) as suma
	   FROM
		 resultados a
	   INNER JOIN slorg_elemento_negocio b ON a.id_elemento_negocio = b.id_elemento_negocio
	   INNER JOIN slorg_elemento_negocio c ON b.id_elemento_padre = c.id_elemento_negocio
	   INNER JOIN slorg_elemento_negocio d ON c.id_elemento_padre = d.id_elemento_negocio
	   INNER JOIN dpp_slorg_usuario e ON a.id_usuario = e.id_usuario	  
	  WHERE 1=1
		AND b.id_tipo_elemento_negocio = 1
		AND b.id_nivel = 3
		AND e.id_cliente = ".$idCliente."
		AND a.completado =1		
	  GROUP BY
	    a.id_usuario
	   ,b.id_elemento_padre
	   ,c.nombre_elemento    
	   ,d.id_elemento_negocio	   
	   ,d.nombre_elemento
	   ,c.puntaje	   	   
    ";	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	$row['porcentaje'] = $row['suma']/$row['puntaje']; 
	  	$arrResultados[$row['id_usuario']][] = $row;
	  }
	  return $arrResultados;	
	}    	
  }

  function DB_RESULT_estadoResultado($idUsuario,$idElementoFormulario=6){
	$conexion = DAL::conexion();
    $sql1 = " SELECT completado FROM resultados WHERE 1=1 AND id_usuario = ".$idUsuario." AND id_elemento_formulario = ".$idElementoFormulario." LIMIT 1 ";	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);
	if(!$rs1){
	  return false;
	} else {
	  $fila = mysql_fetch_assoc($rs1);
	  if(!empty($fila['completado'])){
	    return true;
	  } else {
	    return false;	  	
	  }
	}
  }

  function DB_RESULT_obtenerResultadoNegocio($item){
	$conexion = DAL::conexion();
    $sql1 = " SELECT * FROM resultados WHERE 1=1 AND id_elemento_formulario = ".$item['id']." AND id_usuario = ".$_SESSION['id_usuario'];	
    $rs1=mysql_query($sql1,$conexion) or die(mysql_error().$sql1);  	
	if(!$rs1){
	  return array();
	} else {
	  $arrResultados = array();
	  while($row = mysql_fetch_assoc($rs1)){
	  	$arrResultados[$row['id_elemento_negocio']] = $row['valor'];
	  }
	  return $arrResultados;	
	}  	
  }

  function DB_RESULT_obtenerUnidadResultado($arrParametros){
  	$conexion = DAL::conexion();
	
	$sql = " SELECT * FROM resultados WHERE 1=1 ";
	$sql.= " AND id_unidad_nivel 		= ".$arrParametros['id_unidad_nivel'] ;
	$sql.= " AND id_usuario 			= ".$arrParametros['id_usuario'];
	$sql.= " AND id_elemento_formulario = ".$arrParametros['id'];
		
	$rs=mysql_query($sql,$conexion) or die(mysql_error());
	if(!$rs){
	  return array();	
	} else {
	  $fila = mysql_fetch_assoc($rs);
	  return $fila['id_unidad']; 
	}
  }

  function DB_RESULT_borrarResultadosAnteriores($idUsuario){
  	$conexion = DAL::conexion();  	
	$sql = " DELETE FROM resultados WHERE 1=1 AND id_usuario = ".$idUsuario;
	$rs=mysql_query($sql,$conexion);
	if(!$rs){
	  return array(
	     "resultado" => false
	    ,"mensaje" => "ERROR: ".mysql_error($conexion).$sql
	  );	
	} else {
	  return array(		
	    'resultado' => true	
	  ); 
	}	
  }

  function DB_RESULT_insertarResultados($arrParametros){
  	$conexion = DAL::conexion();
	$sql = "INSERT INTO ";
	$sql.= " resultados ";
	$sql.= " ( ";
	$iContador = 0;
	foreach($arrParametros as $key => $value){
	  $sql.= ($iContador>0)?',':'';
  	  $sql.= "".$key."";
	  $iContador++;
	}

	$sql.= " ,fecha_ingreso ";
	$sql.= " ) ";
	$sql.= " VALUES ";
	$sql.= " ( ";
	$iContador = 0;
	foreach($arrParametros as $key => $value){
	  $sql.= ($iContador>0)?',':'';
  	  $sql.= "'".$value."'";
	  $iContador++;
	}
	$sql.= " ,NOW() ";	
	$sql.= " ) ";
	$rs=mysql_query($sql,$conexion);
	if(!$rs){
	  return array(
	     "resultado" => false
	    ,"mensaje" => "ERROR: ".mysql_error($conexion).$sql
	  );	
	} else {
	  return array(		
	    'resultado' => true	
	  ); 
	}
  }

  function DB_RESULT_traerCr($arrParametros,$conexion){
	$sqlr="
      SELECT
        a.id_select,a.rut,b.id as id_cr,b.id_padre as id_cr_padre,b.id_nivel as nivel
      FROM
        slorg_select_options_res a
        inner join
      ".TBL_ESTRUCTURA_CR." b
      ON a.id_select = b.id
      WHERE a.rut='".$arrParametros['rut']."'
      LIMIT 1	
	";  	
	$rs=mysql_query($sqlr,$conexion) or die(mysql_error());
	if(!$rs){
	  return array();	
	} else {
	  return mysql_fetch_assoc($rs);	
	}  	
  }

  function DB_RESULT_obtenerResultados($arrParametros,$conexion){
	$sql = " select * from slorg_resultados where rut  = '" . $arrParametros['rut'] . "' order by numero_pregunta";
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
  
  function DB_RESULT_obtenerResultadoSegmentacion($idSegmentacion,$iRut,$conexion) {  
	$sqlp ="select segmentacion_opcion_id from slorg_segmentacion_resultados where segmentacion_id=".$idSegmentacion." and rut='".$iRut."'";
 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
 	if(!$rs1){
	  return array(); 		
 	} else {
 	  return mysql_fetch_array($rs1); 		
 	}
  }
  
?>