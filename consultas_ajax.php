<?php

error_reporting(0);
session_start();  
if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}

include("include/dbconection.php");
include_once("include/definiciones.php");

switch($_REQUEST['accion']){
  case "obtenerUnidades":
      $padre   = $_POST['padre'];
      $arrTmp =DB_CR_obtenerHijos($conexion,$padre);  
      echo json_encode($arrTmp);	  
	  break; 
  case "existe":
	$strRut = $_POST['rut'];
  	$sql = "select id_usuario,rut from dpp_slorg_usuario where 1=1 AND (rut = '".getRun($strRut)."' OR  email='".$strRut."' ) AND id_cliente=".$_SESSION['id_cliente_seleccionado'];
	$res 	  = mysql_query($sql,$conexion) or die(mysql_error());
	$iCuantos = mysql_num_rows($res);
	$fila 	  = mysql_fetch_assoc($res);
	if($iCuantos==0){
	  echo json_encode(array("resultado"=>'ERROR','msg'=>'No existe esta persona en esta empresa'));	
	}else{
	  echo json_encode(array("resultado"=>'OK','msg'=>'Existe esta persona en esta empresa','rut'=>$fila['rut']));		
	}
	break;
  case 'obtenerComunas':
	  $idRegion = $_POST['region'];
	  $arrParametros = array('id'=>$idRegion);
	  $arrTmp =	DB_DIVISION_obtenerComunasDeRegion($arrParametros,$conexion);
	  echo json_encode($arrTmp);
	  break;	
  case "borrarVariableSegmentacion":
	$id = $_POST['idSegmentacion'];
  	$sql = "delete from slorg_segmentacion_opciones where segmentacion_id= ".$id." AND 1=1 ";
	$res = mysql_query($sql,$conexion) or die(mysql_error());
  	$sql = "delete from slorg_segmentaciones where id= ".$id." AND 1=1";
	$res = mysql_query($sql,$conexion) or die(mysql_error());	
	echo json_encode(array("resultado"=>'OK','msg'=>'Variable de segmentacion eliminada'));		
	break;
  case "grabarCliente":
	$id 	= $_POST['form_id_cliente'];
	$nombre = $_POST['form_name_cliente'];	
	$rut    = $_POST['form_rut_cliente'];
	$estado = $_POST['form_id_estado'];	

    $target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["form_imagen_cliente"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if (file_exists($target_file)) {
	  echo "El archivo ya existe";
	  $uploadOk = 0;
	} 
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ){
	  echo "Solo Imagenes JPG, JPEG, PNG & GIF.";
	  $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	  echo "No se pudo subir el archivo.";
	} else {
	  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	    echo "El Archivo ". basename( $_FILES["fileToUpload"]["name"]). " ha sido subido ";
	  } else {
	    echo "Hubo un error al subir el archivo.";	
	  }
	}	
	
	
	if(empty($id)){
  	  $sql = "INSERT INTO slorg_cliente (id_cliente,id_estado,nombre_cliente) values (null,".$estado.",'".$nombre."') ";
	  $res = mysql_query($sql,$conexion) or die(mysql_error());			
	}else{
  	  $sql = "UPDATE slorg_cliente SET id_estado=".$estado.",nombre_cliente='".$nombre."' where id_cliente= ".$id." AND 1=1 ";
	  //var_dump($sql);
	  $res = mysql_query($sql,$conexion) or die(mysql_error());
	}
	
	if($res==false){
	  echo json_encode(array("resultado"=>'ERROR','msg'=>'No se pudo grabar, contactese con el administrador del sistema'));	  	
	}	else {
	  echo json_encode(array("resultado"=>'OK','msg'=>'Grabacion Exitosa'));	  	
	}		
	
	break;	
  case "eliminarCliente":
	$id 	= $_POST['cliente'];
	if(!empty($id)){
  	  $sql = "DELETE from slorg_cliente where id_cliente= ".$id;
	  $res = mysql_query($sql,$conexion) or die(mysql_error());
	  if($res==false){
	    echo json_encode(array("resultado"=>'ERROR','msg'=>'No se pudo eliminar, contactese con el administrador del sistema'));		  	
	  }	else {
	    echo json_encode(array("resultado"=>'OK','msg'=>'Borrado Exitoso'));		  	
	  }	  	  			
	} else {
	  echo json_encode(array("resultado"=>'ERROR','msg'=>'No se encontro el id de cliente, contactese con el administrador del sistema'));
	}		
	
	break;		
	exit;
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

?>