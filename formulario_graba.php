<?php
	//---------------------------------------------------------------------------------------------------------------- 
	// Verificamos el uso de session, sino lo mandamos a logearse
	session_start();  
	if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}
	//----------------------------------------------------------------------------------------------------------------
	$_SESSION['tabActual']='1';
	// Cargamos el acceso a la base de datos
	include_once("include/dbconection.php");
	include_once("include/definiciones.php");
	include_once("include/funciones.php");	

	$idUsuario 		= $_SESSION['id_usuario'];
	$idCliente 		= $_SESSION['id_cliente'];	
	$arrResultados 	= $_POST;
	//var_dump($_SERVER);
 	//header('Location: ' .$_SERVER["HTTP_REFERER"]); 
	//exit;
	$texto1  = $_POST['area1'];
	$texto2  = $_POST['area2'];

	//$inv1  = $_POST['inv1'];
	$inv2  = $_POST['inv2'];
	$inv3  = $_POST['inv3'];

	mysql_query("insert into pAbiertas (nombreArea, texto, id_usuario, id_cliente) values ('Area1','$texto1',$idUsuario,$idCliente)");
	mysql_query("insert into pAbiertas (nombreArea, texto, id_usuario, id_cliente) values ('Area2','$texto2',$idUsuario,$idCliente)");

	//mysql_query("insert into soloInvestigadores (valor,nombre, id_usuario, id_cliente) values ($inv1,'inv1',$idUsuario,$idCliente)");
	mysql_query("insert into soloInvestigadores (valor,nombre, id_usuario, id_cliente) values ($inv2,'inv2',$idUsuario,$idCliente)");
	mysql_query("insert into soloInvestigadores (valor,nombre, id_usuario, id_cliente) values ($inv3,'inv3',$idUsuario,$idCliente)");


	mysql_query("update dpp_slorg_usuario SET PASSWORD = 'PSICUS128' WHERE ID_USUARIO = $idUsuario");

	//mysql_query=($inserta1);

	//mysql_query=($inserta2);

	if(empty($arrResultados['id_formulario_enlazado'])){
	  DB_RESULT_borrarResultadosAnteriores($idUsuario);		
	}
	
	//var_dump($arrResultados);
    $flag_completado=$arrResultados['tipo_guardado'];
	foreach($arrResultados as $key=>$value){
	  if($key=="tipo_guardado"){
		continue;
	  }
	  if($key=="id_formulario_enlazado"){
		continue;
	  }	  
	  if(is_array($value)){
	  	//insertar una linea en resultados para cada uno de los valores
        $arrId 		   = explode("_",$key);
		if(count($arrId)==1)
		  continue;
		$arrParametros = array(
		   "id_usuario"				=> $idUsuario
		  ,"id_elemento_formulario"	=> $arrId[0]
		  ,"valor"					=> null
		  ,"completado"				=> $flag_completado
		);
	
	  	foreach($value as $key2 => $value2){
	  		
		  $arrColumnas = obtenerColumnaReferencia($key,$value2);
		  foreach($arrColumnas as $nombreColumna=>$valor){
		    $arrParametros[$nombreColumna] = $valor;
		  }
		  $arrParametros['valor'] = $value2;
		  $arrInsercion  = DB_RESULT_insertarResultados($arrParametros);
		  if($arrInsercion['resultado']==false){
			//DB_RESULT_borrarResultadosAnteriores($idUsuario);		  	
		  	manejoError($arrInsercion['mensaje'],$_SERVER["HTTP_REFERER"]);
		  }
	  	}
	  }
	  
	  if(is_string($value)){
        $arrId 		   = explode("_",$key);
		if(count($arrId)==1)
		  continue;		   	
		$arrParametros = array(
		   "id_usuario" 			=> $idUsuario
		  ,"id_elemento_formulario" => $arrId[0]		  
		  ,"valor" 					=> $value
		  ,"completado"				=> $flag_completado		  
		);
		$arrColumnas = obtenerColumnaReferencia($key,$value);
		foreach($arrColumnas as $nombreColumna=>$valor){
		  $arrParametros[$nombreColumna] = $valor;	
		}
			
		$arrInsercion = DB_RESULT_insertarResultados($arrParametros);
		if($arrInsercion['resultado']==false){
		  //DB_RESULT_borrarResultadosAnteriores($idUsuario);
		  manejoError($arrInsercion['mensaje'],$_SERVER["HTTP_REFERER"]);			
		}			
	  }
	}

	if ($flag_completado=='1')
	{
	  $msg  = " Estimado Usuario, los datos fueron grabados con exito en nuestra base de datos.";
	  $msg .= " Gracias, por su cooperacion";
	  $_SESSION['finCuestionario'] = true;	  	  
	} else {
	  $msg = "Estimado/a los datos fueron grabados con exito, por favor complete el resto del formulario.";
	  $_SESSION['finCuestionario'] = false;	  
	}
	$_SESSION['msg'] = $msg;
	

    function obtenerColumnaReferencia($strIdentificador,$valor){
      $arrId 		 = explode("_",$strIdentificador);
	  $arrRespuesta = array();		  
	  if(preg_match("/^".PREFIJO_ELEMENTO_NEGOCIO."/",$arrId[1])!=false){  	
	  	$arrRespuesta = array(
	  	  COLUMNA_ELEMENTO_NEGOCIO=>substr($arrId[1],1,strlen($arrId[1])-1)
		);
	  }elseif(preg_match("/^".PREFIJO_ELEMENTO_UNIDAD."/",$arrId[1])!=false){
		$arrRespuesta =  array(	  	
	  	  COLUMNA_ELEMENTO_NIVEL_UNIDAD => substr($arrId[1],1,strlen($arrId[1])-1)
		);			
		if(!empty($valor)){
		  $arrRespuesta[COLUMNA_ELEMENTO_UNIDAD] = $valor;	
		}
	  }elseif(preg_match("/^".PREFIJO_ELEMENTO_REGION."/",$arrId[1])!=false){ 	
		$arrRespuesta =  array(
	  	  COLUMNA_ELEMENTO_NIVEL_REGION=>substr($arrId[1],1,strlen($arrId[1])-1)
		);
		if(!empty($valor)){
		  $arrRespuesta[COLUMNA_ELEMENTO_REGION] = $valor;	
		}
	  }elseif(preg_match("/^".PREFIJO_IGNORAR_ELEMENTO."/",$arrId[1])!=false){ 	
		$arrRespuesta =  array(
	  	  COLUMNA_ELEMENTO_NEGOCIO=>$valor
		);
	  }
	  return $arrRespuesta;
    }
	
	//ver si el formulario tiene uno enlazado
	$arrFormulario = DB_FORM_obtenerFormulario(array("id_cliente"=>$idCliente));
	if(!empty($arrFormulario['id_formulario_enlazado'])){
	  $_SESSION['id_formulario_enlazado']  = $arrFormulario['id_formulario_enlazado'];
	  $_SESSION['msg_formulario_enlazado'] = "Gracias por contestar la Encuesta de Satisfacción Laboral. ";		
	  //$destino = "formulario_enlazado.php";	A continuación considere contestar el formulario SUSESO ISTAS 21 que mide los riesgos psicosociales en su Institución	
	  //$destino = "formulario.php";
	  $destino =  $_SERVER["HTTP_REFERER"];
	} else {
	  //$destino = "formulario.php";
	  $destino =  $_SERVER["HTTP_REFERER"];
	}
	
 	header('Location: ' .$destino); 		
?>