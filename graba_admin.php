<?php

	//---------------------------------------------------------------------------------------------------------------- 
	// Verificamos el uso de session, sino lo mandamos a logearse
	session_start();  
	if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}
	if (!in_array('3',$_SESSION['tipo_usuario'])){echo "Por Seguridad esta sección esta reservada para los administradores <br><br><a href='index.php'>Inicio</a>";die();}	
	//----------------------------------------------------------------------------------------------------------------
	$_SESSION['tabActual']='3';
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
		
	//Datos del drop (TODO: Hacerlo para construir más de un droplist)
	$i = 1;


	$sql = "SELECT * FROM slorg_admin WHERE id_cliente=".$_POST['id_cliente'];
	$result = mysql_query($sql) or die(mysql_error());
	$iCuantos = mysql_num_rows($result);
	if($iCuantos==0){
	    //insert
		// actualizamos los textos del inicio del formulario
		$sql  = " INSERT INTO slorg_admin (";
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
		$sql .= "'" . $p63 . "',";		
		$sql .= "'" . $_POST['id_cliente'] . "' ";		
		$sql .= " ) ";		
		mysql_query($sql,$conexion);		
	}else{
	  //update
		// actualizamos los textos del inicio del formulario
		$sql  = "UPDATE slorg_admin set ";
		$sql .= "texto1='" . $txt1 . "',";
		$sql .= "texto2='" . $txt2 . "',";
		$sql .= "texto3='" . $txt3 . "',";
		$sql .= "texto_inicio1='" . $txt_ini1 . "',";
		$sql .= "texto_inicio2='" . $txt_ini2 . "',";
		$sql .= "p61='" . $p61 . "', ";
		$sql .= "p62='" . $p62 . "', ";
		$sql .= "p63='" . $p63 . "' ";		
		$sql .= "WHERE id_cliente='" . $_POST['id_cliente'] . "'; ";	
		mysql_query($sql,$conexion);		
	}

	//actualizamos las dimensiones
	$i=1;
	while (isset($_POST['dimension_'.$i])) 
	{
	  $tt = $_POST['dimension_'.$i];
	  //$uu = $_POST['promedio_'.$i];
	  $tid= $_POST['id_dim_'.$i];
	  //$sqlt ="update slorg_dimension set dimension='$tt', promedio='$uu' where id='$tid' ";
	  $sqlt ="update slorg_dimension set dimension='$tt' where id='$tid' ";
	  mysql_query($sqlt,$conexion);		
	  if ($_POST['del_dim_'.$i] != "") {
		$sqld =	"DELETE FROM slorg_dimension WHERE id='$tid';";
		mysql_query($sqld,$conexion);
	  }
	  $i++;
	}
		
	if($_POST['dimension_'] != ""){
	  $tt = $_POST['dimension_'];
	  //$uu = $_POST['promedio_'];
	  $sqlt ="insert into slorg_dimension (dimension, id_cliente) VALUES('$tt',".$_POST['id_cliente'].");";
	  mysql_query($sqlt,$conexion);		
	}

	// actualizamos las variables de segmentacion	
	//Obtnemos los segmentos a actualizar
	$segmento = array();
	$new_segmento = array();
	
	$sql_seg = " SELECT id,name,title FROM slorg_segmentaciones where id_cliente = ".$_POST['id_cliente'];
	$rs_seg=mysql_query($sql_seg, $conexion) or die(mysql_error());
	
	while ($row_seg=mysql_fetch_array($rs_seg))
	{

			
		// Verifica si se actualizaron las variables, si es así guarda ese dato	
		if ((trim($_POST['seg_title_'.$row_seg['id']]) != $row_seg['title']) AND sanear_string($_POST['seg_title_'.$row_seg['id']])!= '')	{		
			$segmento[$row_seg['id']]['title'] = sanear_string($_POST['seg_title_'.$row_seg['id']]);					  
			$segmento[$row_seg['id']]['name'] = sanear_string($_POST['seg_title_'.$row_seg['id']],1);		
		} else {
			$segmento[$row_seg['id']]['title'] = sanear_string($_POST['seg_title_'.$row_seg['id']]);					  
			$segmento[$row_seg['id']]['name'] = sanear_string($_POST['seg_title_'.$row_seg['id']],1);			
		}		
		$sqlso ="SELECT DISTINCT a.id, a.value, a.texto 
					FROM slorg_segmentacion_opciones a
					inner join slorg_segmentaciones b
					ON a.segmentacion_id= b.id
					WHERE a.segmentacion_id='".$row_seg['id']."' and b.id_cliente=".$_POST['id_cliente'];
		
		$rsso=mysql_query($sqlso, $conexion) or die(mysql_error());
			
		while ($rowso=mysql_fetch_array($rsso)) 
		{
		    if (isset($_POST['del_seg_opc_'.$rowso['id']]) AND $_POST['del_seg_opc_'.$rowso['id']] != '')	{
			  $sqld = "DELETE FROM slorg_segmentacion_opciones WHERE id='".$_POST['del_seg_opc_'.$rowso['id']]."'";
			  mysql_query($sqld, $conexion);
		    }			
						
			if (($_POST['seg_opc_text_'.$row_seg['id'].'_'.$rowso['id']] != $rowso['texto'])AND (sanear_string($_POST['seg_opc_text_'.$row_seg['id'].'_'.$rowso['id']]) != '')) {
				$segmento[$row_seg['id']]['opcion'][$rowso['id']]['text'] =  sanear_string($_POST['seg_opc_text_'.$row_seg['id'].'_'.$rowso['id']]);
				$segmento[$row_seg['id']]['opcion'][$rowso['id']]['value'] =  sanear_string($_POST['seg_opc_text_'.$row_seg['id'].'_'.$rowso['id']],1);
			} else {
				$segmento[$row_seg['id']]['opcion'][$rowso['id']]['text'] =  sanear_string($_POST['seg_opc_text_'.$row_seg['id'].'_'.$rowso['id']]);
				$segmento[$row_seg['id']]['opcion'][$rowso['id']]['value'] =  sanear_string($_POST['seg_opc_text_'.$row_seg['id'].'_'.$rowso['id']],1);				
			}			
		};					
		//Inserta la nueva variable, si la hay
		
		if (trim($_POST['nuevo_seg_opc_text_'.$row_seg['id']])) {			
			$new_segmento[$row_seg['id']]['texto'] = sanear_string($_POST['nuevo_seg_opc_text_'.$row_seg['id']]);
			$new_segmento[$row_seg['id']]['value'] = sanear_string($_POST['nuevo_seg_opc_text_'.$row_seg['id']],1);
		}
	};
	

	//Actualizar los datos
	foreach ($segmento as $seg_key => $seg) {
		$sql = "UPDATE slorg_segmentaciones SET title='".$seg['title']."', 	
				name='".$seg['name']."' WHERE id='".$seg_key."'";
		mysql_query($sql, $conexion);
		foreach ($seg['opcion'] as $opc_key => $opc) {
			$sqlo = "UPDATE slorg_segmentacion_opciones SET value='".$opc['value']."',
					texto='".$opc['text']."' WHERE id='".$opc_key."' AND segmentacion_id='".$seg_key."'";
					//echo $sqlo."<br>";
			mysql_query($sqlo, $conexion);
		}
	}		
	//exit;
	
	foreach ($new_segmento as $ns_key => $n_seg){
		$sql = "INSERT INTO slorg_segmentacion_opciones (segmentacion_id, value, texto) VALUES
				('".$ns_key."', '".$n_seg['value']."','".$n_seg['texto']."')";
		mysql_query($sql, $conexion);
	}	

	
	// fin de actualizacion de variables de segmentacion
	$destino = "dpp_admin.php";
	//header('Location: ' .$destino); // hacemos el redireccionamiento

	// grabamos las variables de segmentacion
	$nombre_vg	 = sanear_string($_POST['nombre_vg'],1);
	$title_vg	 = sanear_string($_POST['nombre_vg']);
	for ($i=0; $i < 10; $i++) {
		if (isset($_POST['nueva_vg_'.($i+1)]) AND $_POST['nueva_vg_'.($i+1)] != '') 
			$vg[$i] = sanear_string($_POST['nueva_vg_'.($i+1)]);
	}
	
	
	if ($nombre_vg<>'')
	{
		$sqls ="INSERT INTO slorg_segmentaciones (name, title,id_cliente) VALUES (";
		$sqls.="'$nombre_vg','$title_vg',".$_POST['id_cliente'].");";			
		mysql_query($sqls, $conexion);
		
		$sqls = "SELECT id FROM slorg_segmentaciones ORDER BY id DESC LIMIT 1";
		$rs_id=mysql_query($sqls, $conexion) or die(mysql_error());	
		$seg_id=mysql_fetch_array($rs_id);	
		
		foreach ($vg as $opc) {
		  $sqlo = "INSERT INTO slorg_segmentacion_opciones (segmentacion_id, value, texto) VALUES ('".$seg_id[0]."', '".sanear_string($opc,1)."','".$opc."')";	
		  mysql_query($sqlo, $conexion);			
		}
	}
	
	// actualizamos las preguntas
	$x=1;
	
	while (isset($_POST['p_'.$x]))
	{
		$txt  = $_POST['p_'.$x];
		$txtd = $_POST['dim_'.$x];
		$idx = $_POST['dim_id_'.$x];
		$sql  = "UPDATE dpp_slorg_preguntas set pregunta='" . $txt . "' , dimension=$txtd where id=$idx";			
		mysql_query($sql,$conexion);
	 
		if (isset($_POST['del_p_'.$x])) {
		  $sql = 	"DELETE e FROM dpp_slorg_preguntas e inner join slorg_dimension d ON e.dimension=d.id  WHERE e.n='$x' and d.id_cliente = ".$_POST['id_cliente']." ";
		  mysql_query($sql,$conexion);
		}
	
		$x++;
	}
	
	$sqlp ="select count(*) from dpp_slorg_preguntas e inner join slorg_dimension d ON e.dimension=d.id WHERE d.id_cliente=".$_POST['id_cliente'];
 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
	$rowp=mysql_fetch_array($rs1);
	$cantidad_preguntas = intval($rowp[0]);

	if ($_POST['p_'] != "" )
	{		
	  $txt  = $_POST['p_'];
	  $txtd = $_POST['dim_'];
	  $sql  = "INSERT INTO dpp_slorg_preguntas (n, pregunta, posicion, dimension) VALUES ('";
	  $sql  .= $cantidad_preguntas+1;
	  $sql  .= "','$txt','";
	  $sql  .= $cantidad_preguntas+1;
	  $sql  .= "','$txtd'";
	  $sql  .= ")";				
	  mysql_query($sql,$conexion);
	  $x++;
	}

	
	// datos de los tab
	// editar usuario
	/*
	$edit_rut = $_POST['edit_rut'];
	if (strlen($edit_rut)>0)
	{
		$destino = "usuarios.php?rut=" . $rut . "&acceso_formulario=" . $acceso_formulario . "&edit_rut=" . $edit_rut;
		header('Location: ' .$destino); // hacemos el redireccionamiento
		die();					
	}
	*/
	$destino = "dpp_admin.php";
	header('Location: ' .$destino); // hacemos el redireccionamiento

?>
