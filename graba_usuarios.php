<?php
	//---------------------------------------------------------------------------------------------------------------- 
	// Verificamos el uso de session, sino lo mandamos a logearse
	session_start();  
	if ($_SESSION['rut']==""){echo "session expirada! vuelva a logearse<br><br><a href='..index.htm'>Inicio</a>";die();}
	if (!in_array('3',$_SESSION['tipo_usuario'])){echo "Por Seguridad esta sección esta reservada para los administradores <br><br><a href='index.php'>Inicio</a>";die();}	
	//----------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------
	// Cargamos el acceso a la base de datos
	include("include/dbconection.php");
	$rut     			 	 = $_SESSION['rut']; //$_POST["rut"];
	$accion_formulario 		 = $_POST['accion_formulario'];	
	//$acceso_formulario 		 = 1;
	//$v_password			 = $_POST["v_password"];
	$rut_u		 			 = $_POST['rut_u'];
	$nombre					 = $_POST['nombre'];
	$ap_pat					 = $_POST['apellido_paterno'];	
	$ap_mat					 = $_POST['apellido_materno'];	

	$cr						 = (isset($_POST['id_select']) && !empty($_POST['id_select']))?$_POST['id_select']:'null';
	//$cc					 	 = $_POST['sel_cc'];
	$cc					 	 = '';
	//$ud						 = $_POST['sel_ud'];
	$ud						 = '';	
	$cargo			 		 = $_POST['cargo'];
	$grado			 		 = $_POST['grado'];
	$estamento		 		 = $_POST['estamento'];
	$cjuridica		 		 = $_POST['cjuridica'];
	//$sel_perfil			 = $_POST['sel_perfil'];
	$email					 = $_POST['email'];
	$fono					 = $_POST['fono'];
	$fecha_ingreso_cargo	 = $_POST['fecha_ingreso_cargo'];
	//$profesion		 		 = $_POST['profesion'];
	
	$id_jefe_directo	 	 = (isset($_POST['id_jefe_directo']) && !empty($_POST['id_jefe_directo']))?$_POST['id_jefe_directo']:'null';
	/*
	  $rut_jefe_directo	 	 = $_POST['rut_jefe_directo'];
	  $cargo_jefe_directo	 	 = $_POST['cargo_jefe_directo'];
	  $nombre_jefe_directo	 = $_POST['nombre_jefe_directo'];
	  $email_jefe_directo	 	 = $_POST['email_jefe_directo'];
	*/
	$sexo					 = (isset($_POST['sexo']) && !empty($_POST['sexo']))?$_POST['sexo']:'null';	
	
	//$username			 	 = $_POST['username'];
	$password				 = $_POST['password'];
	$activo					 = $_POST['activo'];
	
	$id_tipo_usuario		 = (isset($_POST['id_tipo_usuario']) && !empty($_POST['id_tipo_usuario']))?$_POST['id_tipo_usuario']:array();
	//$edicion				 = $_POST['edicion'];
	$cargo					 = (isset($_POST['cargo']))?$_POST['cargo']:'';
	$id_usuario				 = $_POST['id_usuario'];	
	$id_cliente				 = $_POST['id_cliente'];	

	$id_region				 = (isset($_POST['id_region']) && !empty($_POST['id_region']))?$_POST['id_region']:null;
	$id_comuna				 = (isset($_POST['id_comuna']) && !empty($_POST['id_comuna']))?$_POST['id_comuna']:null;

	if(is_null($id_comuna)){
	  $id_comuna = $id_region;		
	} 
	if(is_null($id_comuna) && is_null($id_region)){
	  $id_comuna = "null";		
	} 

	
function getRun($strRut){
  $strRut = str_replace(".","",$strRut);	
  $arrRut = explode("-",$strRut);
  return $arrRut[0];	
}

function getDv($strRut){
  $strRut = str_replace(".","",$strRut);	
  $arrRut = explode("-",$strRut);
  return $arrRut[1];	
}

	
	if($accion_formulario=='grabar'){
		
		if (!empty($id_usuario))
		{
		  $sql  =" UPDATE dpp_slorg_usuario set ";
		  //$sql .=" rut='".getRun($rut_u)."-".getDv($rut_u)."' , ";		  	
		  $sql .=" rut='".getRun($rut_u)."' , ";
		  $sql .=" dv='".getDv($rut_u)."' , ";		  
		  $sql .=" nombre='$nombre' , ";
		  if(!empty($cr)){
		    $sql .=" id_unidad=$cr ,";		  	
		  }else{
		    $sql .=" id_unidad=null ,";		  	
		  }
		  $sql .=" apellido_paterno='$ap_pat' , ";		  
		  $sql .=" apellido_materno='$ap_mat' , ";
		  $sql .=" sexo='$sexo' , ";		  
		  		  
		  //$sql .=" unidad_desempeno='$ud' , ";
		  $sql .=" id_grado='$grado', ";
		  $sql .=" id_estamento='$estamento', ";
		  $sql .=" id_calidad_juridica='$cjuridica' , ";
		  //$sql .=" id_perfil='$sel_perfil', ";
		  //$sql .=" perfil_cargo='$perfil_cargo' , ";
		  $sql .=" id_cargo='$cargo', ";
		  $sql .=" email='$email' , ";
		  $sql .=" telefono='$fono' , ";
		  $sql .=" fecha_ingreso_cargo='$fecha_ingreso_cargo' , ";
		  //$sql .=" profesion='$profesion' , ";
		  $sql .=" id_jefe_directo=$id_jefe_directo , ";
		  /*
		  $sql .=" cargo_jefe_directo='$cargo_jefe_directo' , ";
		  $sql .=" nombre_jefe_directo='$nombre_jefe_directo' , ";
		  $sql .=" email_jefe_directo='$email_jefe_directo' , ";
		   */
		  //$sql .=" username='$username' , ";
		  //$sql .=" username=null , ";
		  $sql .=" password='$password' , ";
		  //$sql .=" acceso_formulario='$activo',";
		  $sql .=" id_estado='$activo',";	  
		  
		  $sql .=" id_region_pais=$id_comuna,";		  
		  //$sql .=" id_tipo_usuario='$id_tipo_usuario',";	  
		  $sql .=" id_cliente='$id_cliente'";		  
		  $sql .=" where id_usuario='$id_usuario';";
		  
		} else {
		  $sql  ="insert into dpp_slorg_usuario (rut,dv, nombre,apellido_paterno,apellido_materno, id_unidad, sexo, id_grado, id_estamento, ";
		  $sql .=" id_calidad_juridica, id_cargo, email, telefono, fecha_ingreso_cargo, id_jefe_directo, ";
		  $sql .=" password, id_estado,id_cliente,id_region_pais ) values (";
	      $sql .="'".getRun($rut_u)."','".getDv($rut_u)."','$nombre','$ap_pat','$ap_mat',$cr,'$sexo','$grado','$estamento','$cjuridica','$cargo','$email','$fono',";
	      $sql .="'$fecha_ingreso_cargo',$id_jefe_directo,";
	      $sql .="'$password','$activo','$id_cliente',$id_comuna);";	
		}	
		//echo $sql;
		//die();
		$respuesta = mysql_query($sql,$conexion) or die(mysql_error());
		//$respuesta = mysql_query($sql,$conexion);
	
		if($respuesta==false){
		  $msg="";
		  //$msg.=mysql_error(); 
		  
		  $sql = "select * from dpp_slorg_usuario where rut='".getRun($rut_u)."'";
		  $resRut = mysql_query($sql,$conexion);
		  $iCuantos = mysql_num_rows($resRut);
		  if($iCuantos>0){
		  	//$msg.=" <br>(ya existe una persona con ese rut) ";
		  	//entonces tendria que grabar sobre esa persona?
		  	  $linea = mysql_fetch_assoc($resRut);
			  $sql  =" UPDATE dpp_slorg_usuario set ";	  	
			  $sql .=" rut='".getRun($rut_u)."' , ";
			  $sql .=" dv='".getDv($rut_u)."' , ";		  
			  $sql .=" nombre='$nombre' , ";
			  if(!empty($cr)){
			    $sql .=" id_unidad=$cr ,";		  	
			  }else{
			    $sql .=" id_unidad=null ,";		  	
			  }
			  $sql .=" apellido_paterno='$ap_pat' , ";		  
			  $sql .=" apellido_materno='$ap_mat' , ";
			  $sql .=" sexo='$sexo' , ";		  
			  $sql .=" id_grado='$grado', ";
			  $sql .=" id_estamento='$estamento', ";
			  $sql .=" id_calidad_juridica='$cjuridica' , ";
			  $sql .=" id_cargo='$cargo', ";
			  $sql .=" email='$email' , ";
			  $sql .=" telefono='$fono' , ";
			  $sql .=" fecha_ingreso_cargo='$fecha_ingreso_cargo' , ";
			  $sql .=" id_jefe_directo=$id_jefe_directo , ";
			  $sql .=" password='$password' , ";
			  $sql .=" id_estado='$activo',";	    
			  $sql .=" id_cliente='$id_cliente'";		
		  	  $sql .=" id_region_pais=$id_comuna,";			    
			  $sql .=" where id_usuario='".$linea['id_usuario']."';";
			  $respuesta = mysql_query($sql,$conexion);
			  if($respuesta==false){
		    	$msg.="<div class='alert alert-danger'>No se ha podido hacer la grabación, por favor contacte al administrador del sistema";			
			  }	else {
			    $sql = "DELETE from rel_usuario_tipo_usuario where id_usuario='".$linea['id_usuario']."'";
				mysql_query($sql,$conexion);			    		  
				$arrIdsPerfiles = $_POST["id_tipo_usuario"];
				foreach($arrIdsPerfiles as $key => $value){
				  $sql = "INSERT INTO rel_usuario_tipo_usuario (id_rel,id_usuario,id_tipo_usuario) VALUES (null,".$linea['id_usuario'].",".$value.")";			 
				  mysql_query($sql,$conexion);
				}
				$msg.="<div class='alert alert-success'>Se ha grabado correctamente en la base de datos</div>";
			  }		  	
		  }else{
		    $msg.="<div class='alert alert-danger'>No se ha podido hacer la grabación, por favor contacte al administrador del sistema";		  	
		  }
		  /*
		  $sql = "select * from dpp_slorg_usuario where username='".$username."'";
		  $resUser = mysql_query($sql,$conexion);
		  $iCuantos = mysql_num_rows($resUser);
		  if($iCuantos>0){
		  	//entonces tendria que grabar sobre esa persona?		  	
		  	$msg.=" <br>(ya existe una persona con ese nombre de usuario) ";
		  }
		  */  
		  $msg.="</div>";	  	
		  echo $msg;
		
		} else {
		  //aca la grabación de los permisos
		  if(empty($id_usuario)){
		    $id_usuario = mysql_insert_id($conexion);		  
		  }		
		  $sql = "DELETE from rel_usuario_tipo_usuario where id_usuario='".$id_usuario."'";
		  mysql_query($sql,$conexion);		  		  
		  $arrIdsPerfiles = $_POST["id_tipo_usuario"];
		  //var_dump($_POST["id_tipo_usuario"]);
		  foreach($arrIdsPerfiles as $key => $value){
			$sql = "INSERT INTO rel_usuario_tipo_usuario (id_rel,id_usuario,id_tipo_usuario) VALUES (null,".$id_usuario.",".$value.")";			  	
		    mysql_query($sql,$conexion);
		  }
		  echo "<div class='alert alert-success'>Se ha grabado correctamente en la base de datos</div>";		
		}	
	
		
	}elseif($accion_formulario=='eliminar'){
		
		$rut_u  = str_replace (".","",$rut_u);
		$arrRut = explode('-',$rut_u);
		$iRut = $arrRut[0];				
		$cDv  = $arrRut[1];		
		
		/*TEMPORAL*/
		
		$sql = "SELECT * FROM dpp_slorg_usuario where rut='".$iRut."'";	
		$respuestaBusqueda = mysql_query($sql,$conexion);		
		$iCuantos = mysql_num_rows($respuestaBusqueda);	
		if($iCuantos==0){
		  $msg="<div class='alert alert-danger'>No se ha podido hacer la eliminacion, el rut ".$iRut."-".$cDv." no existe ";
		  $msg.="</div>";	  	
		  echo $msg;			
		  exit;			
		}

		$sql = "DELETE e FROM rel_usuario_tipo_usuario e inner join dpp_slorg_usuario a ON e.id_usuario = a.id_usuario where a.rut='".$iRut."'";	
		mysql_query($sql,$conexion);		
		
		$sql = "DELETE FROM dpp_slorg_usuario where rut='".$iRut."'";	
		$respuesta = mysql_query($sql,$conexion);
	
		if($respuesta==false){
		  $msg="<div class='alert alert-danger'>No se ha podido hacer la eliminacion, por favor contacte al administrador del sistema";
		  $msg.=mysql_error(); 
		  
		  $msg.="</div>";	  	
		  echo $msg;		  	  	
		} else {
		  $sql = "SELECT * FROM dpp_slorg_usuario where rut='".$iRut."'";	
		  $respuestaBusqueda = mysql_query($sql,$conexion);		
		  $iCuantos = mysql_num_rows($respuestaBusqueda);	
		  if($iCuantos==0){
		    echo "<div class='alert alert-success'>Se ha eliminado el usuario de rut ".$rut_u." correctamente en la base de datos</div>";
		  } else {
			$msg="<div class='alert alert-danger'>No se ha podido hacer la eliminacion, el rut ".$iRut."-".$cDv." no se ha encontrado ";
			$msg.="</div>";	  	
			echo $msg;			
			exit;							
		  }			
		}	  	
	}else{
	  echo "ERROR";
	  exit;
	}
	

	//$destino = "admin.php";
	//header('Location: ' .$destino); // hacemos el redireccionamiento
	mysql_close($conexion);					
?>