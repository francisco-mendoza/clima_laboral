<?php

	//---------------------------------------------------------------------------------------------------------------- 
	// Verificamos el uso de session, sino lo mandamos a logearse
	session_start();  
	if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}
	if (!in_array(array('2','3'),$_SESSION['tipo_usuario'])){echo "Por Seguridad esta sección esta reservada para los administradores <br><br><a href='index.php'>Inicio</a>";die();}	
	//----------------------------------------------------------------------------------------------------------------
	$_SESSION['tabActual']='4';
	/**
 * Reemplaza todos los acentos por sus equivalentes sin ellos
 *
 * @param $string
 *  string la cadena a sanear
 *
 * @return $string
 *  string saneada
 */
function sanear_string($string, $normaliza = null)
{
	$string = utf8_encode($string);
    $string = trim($string);
	if ($normaliza) {
		
	    $string = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $string
	    );
	
	    $string = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $string
	    );
	
	    $string = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $string
	    );
	
	    $string = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $string
	    );
	
	    $string = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $string
	    );
	
	    $string = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç'),
	        array('n', 'N', 'c', 'C',),
	        $string
	    );
	
	    $string = str_replace(
	        array(' ', '&nbsp;'),
	        array('_', '_',),
	        $string
	    );
		
		$string = str_replace(
	        array("\\", "¨", "º", "-", "~",
	             "#", "@", "|", "!", "\"",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "`", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "< ", ";", ",", ":", "."),
	        '',
	        $string
	    	);
			
	    $string = mb_strtolower($string);
	}
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "|", "!", "\"",
             "·", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":"),
        '',
        $string
    );

	
	
    return utf8_decode($string);
}
	
	//----------------------------------------------------------------------------
	// Cargamos el acceso a la base de datos
	include("include/dbconection.php");

	$rut     			= isset($_SESSION['rut']) ? $_SESSION['rut'] : "" ; 
	$acceso_formulario 	= $_POST["acceso_formulario"];
	$txt1			 	= $_POST['texto1'];
	$txt2			 	= $_POST['texto2'];
	$txt3			 	= $_POST['texto3'];
	$txt_ini1	 		= $_POST['texto_inicio1'];
	$txt_ini2	 		= $_POST['texto_inicio2'];			
	$p61			 	= $_POST['p61'];
	$p62			 	= $_POST['p62'];
	$p63			 	= $_POST['p63'];
	

	if ($p61==''){$p61=0;}
	if ($p62==''){$p62=0;}
	if ($p63==''){$p63=0;}		
		

	$sql = "SELECT * FROM slorg_admin WHERE id_cliente=".$_POST['id_cliente'];
	$result = mysql_query($result) or die(mysql_error());
	$iCuantos = mysql_num_rows($result);
	if($iCuantos==0){
		$sql  = " INSERT INTO slorg_admin set (";
		$sql .= " texto1,";
		$sql .= " texto2,";
		$sql .= " texto3,";
		$sql .= " texto_inicio1,";
		$sql .= " texto_inicio2,";
		$sql .= " p61,";
		$sql .= " p62,";
		$sql .= " p63, ";		
		$sql .= " id_cliente) ";	
		$sql .= " values (";
		$sql .= "'" . $txt1 . "',";
		$sql .= "'" . $txt2 . "',";
		$sql .= "'" . $txt3 . "',";
		$sql .= "'" . $txt_ini1 . "',";
		$sql .= "'" . $txt_ini2 . "',";
		$sql .= "'" . $p61 . "', ";
		$sql .= "'" . $p62 . "', ";
		$sql .= "'" . $p63 . "', ";		
		$sql .= "'" . $_POST['id_cliente'] . "' ";		
		$sql .= " ) ";		
		$result = mysql_query($sql) or die(mysql_error());		
		if($result==false){
		  $_SESSION["mensajeGrabacion"] = "<div class='alert alert-danger'>No se puedo grabar, por favor contactese con el administrador del sistema</div>";	
		}else{
		  $_SESSION["mensajeGrabacion"] = "<div class='alert alert-success'>Grabaci&oacute;n realizada correctamente.</div>";		
		}		
	} else {	// actualizamos los textos del inicio del formulario
		$sql  = "UPDATE slorg_admin set ";
		$sql .= "texto1='" . $txt1 . "',";
		$sql .= "texto2='" . $txt2 . "',";
		$sql .= "texto3='" . $txt3 . "',";
		$sql .= "texto_inicio1='" . $txt_ini1 . "',";
		$sql .= "texto_inicio2='" . $txt_ini2 . "',";
		$sql .= "p61='" . $p61 . "', ";
		$sql .= "p62='" . $p62 . "', ";
		$sql .= "p63='" . $p63 . "' ";		
		$sql .= " WHERE id_cliente='".$_POST['id_cliente']."'; ";			
		$result = mysql_query($sql) or die(mysql_error());
		if($result==false){
		  $_SESSION["mensajeGrabacion"] = "<div class='alert alert-danger'>No se puedo grabar, por favor contactese con el administrador del sistema</div>";	
		}else{
		  $_SESSION["mensajeGrabacion"] = "<div class='alert alert-success'>Grabaci&oacute;n realizada correctamente.</div>";		
		}
	}


	$destino = "dpp_admin_cliente.php";
	header('Location: ' .$destino); // hacemos el redireccionamiento

?>
