<?php
	//----------------------------------------------------------------------------------------------------------------
	// Cargamos el acceso a la base de datos
	include_once("include/dbconection.php");
	include_once("include/funciones.php");	
	//--------------------------------------------------------------------
	// Capturamos parametros formulario origen
	//--------------------------------------------------------------------
	$username	= $_POST["usuario"];
	
	$username   = obtenerCampo($username);	
	$password	= $_POST["clave"];
	//--------------------------------------------------------------------
	// Antecedentes del funcionarios para ingreso al sistema
	//--------------------------------------------------------------------
	//$sql  = " select rut, acceso_formulario,password from dpp_slorg_usuario";
	//var_dump($username);
	//exit;
	if($username["resultado"]==false){
   	  $destino='index.php?m='.$username['codigo'];		
      header('Location:'.$destino);
	}
	
	//var_dump($username);	
	
	//	var_dump($username);
	$arrParametros = array(
	  "username" => $username
	 ,"password" => $password
	);	
	$arrResultado = DB_USUARIO_validarUsuario($arrParametros,$conexion);
	//var_dump($arrResultado);
	if(!empty($arrResultado)) 
	{
	  $row 					= $arrResultado; 
	  $v_rut		 	    = $row['rut'];
	  //$v_run		 	    = $row['run'];	  
	  //$v_acceso_formulario	= $row['acceso_formulario'];
	  $v_password			= $row['password'];
	  //$v_tipoUsuario		= $row['id_tipo_usuario'];
		
	  //$v_username			= $row['username'];
	  $v_idUsuario			= $row['id_usuario'];	  
	  $v_cr					= $row['id_unidad'];		
	  $v_idCliente			= $row['id_cliente'];

	  $arrParametros   = array("id_usuario"=>$v_idUsuario);
	  $arrTiposUsuario = DB_USUARIO_obtenerTiposDeUsuario($arrParametros,$conexion);
	  $arrResultados   = DB_USUARIO_obtenerMenus($arrTiposUsuario,$conexion);
	  //buscar los menus a los que tiene acceso - end
	  //$destino = "dpp_presentacion.php";
	  
	  //$destino = "dpp_formulario.php";
	  $destino = "formulario.php";
	  //session_name($v_rut);
	  session_start();   
	  //var_dump($arrResultados);
	  session_regenerate_id(true);

	  $_SESSION['tabActual']			  = 1; //para que tome el tab formulario como por defecto 
	  $_SESSION['usuario'] 			 	  = $v_rut;
	  //$_SESSION['acceso_formulario'] 	  = $v_acceso_formulario;
	  //$_SESSION['username'] 		 	  = $v_username;	  
	  $_SESSION['rut'] 		 	  		  = $v_rut;	  
	  //$_SESSION['run'] 		 	  	  = $v_run;	  
	  $_SESSION['centro_responsabilidad'] = $v_cr;	  
	  $_SESSION['tipo_usuario'] 	 	  = $arrTiposUsuario;	  
	  $_SESSION['menus'] 	 		 	  = $arrResultados;	  
	  $_SESSION['id_usuario'] 			  = $v_idUsuario;  
	  $_SESSION['id_cliente'] 			  = $v_idCliente;	
	 $respuesta='1';	    
   } else {
   	 $destino='index.php?m=1';
	 $respuesta='0';
   } // Si en menor o igual a cero se devuelve a la pagina de inicio
   //exit;
   
   if(isset($_GET['login2'])){
   	 echo $respuesta;
	 exit;
   }
   
   header('Location:'.$destino); // hacemos el redireccionamiento segun corresponda
?>
