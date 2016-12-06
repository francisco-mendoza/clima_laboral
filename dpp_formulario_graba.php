<?php
	//---------------------------------------------------------------------------------------------------------------- 
	// Verificamos el uso de session, sino lo mandamos a logearse
	session_start();  
	if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}
	//----------------------------------------------------------------------------------------------------------------
	$_SESSION['tabActual']='1';
	// Cargamos el acceso a la base de datos
	include("include/dbconection.php");
	$rut     			 	= $_POST["rut"];
	$acceso_formulario 		= $_POST["acceso_formulario"];
	$v_password			 	= $_POST["v_password"];
	$v_cr					= $_POST["sel_cr"];
	$v_iSelect				= $_POST["id_select"];
	$popup					= $_POST["popup"];
	$v_rut 			      	= $_POST["rut"];
	$v_acceso_formulario 	= $_POST["acceso_formulario"];
	
    $v_unidad		      	= $_POST["c_unidad"];
	$v_cjuridica		 	= $_POST["c_cjuridica"];
	$v_funcion		 		= $_POST["c_funcion"];
	$v_antiguedad	 		= $_POST["c_antiguedad"];

	if ($v_unidad==""){$v_unidad=0;}
	if ($v_cjuridica==""){$v_cjuridica=0;}
	if ($v_funcion==""){$v_funcion=0;}
	if ($v_antiguedad==""){$v_antiguedad=0;}

	$nivel1					= (isset($_POST["c_nivel_1"]) && !empty($_POST["c_nivel_1"]))?$_POST["c_nivel_1"]:0;
	$nivel2					= (isset($_POST["c_nivel_2"]) && !empty($_POST["c_nivel_2"]))?$_POST["c_nivel_2"]:0;
	$nivel3					= (isset($_POST["c_nivel_3"]) && !empty($_POST["c_nivel_3"]))?$_POST["c_nivel_3"]:0;
	$nivel4					= (isset($_POST["c_nivel_4"]) && !empty($_POST["c_nivel_4"]))?$_POST["c_nivel_4"]:0;
	$nivel5					= (isset($_POST["c_nivel_5"]) && !empty($_POST["c_nivel_5"]))?$_POST["c_nivel_5"]:0;
	$nivel6					= (isset($_POST["c_nivel_6"]) && !empty($_POST["c_nivel_6"]))?$_POST["c_nivel_6"]:0;
	$nivel7					= (isset($_POST["c_nivel_7"]) && !empty($_POST["c_nivel_7"]))?$_POST["c_nivel_7"]:0;
	$nivel8					= (isset($_POST["c_nivel_8"]) && !empty($_POST["c_nivel_8"]))?$_POST["c_nivel_8"]:0;
	$nivel9					= (isset($_POST["c_nivel_9"]) && !empty($_POST["c_nivel_9"]))?$_POST["c_nivel_9"]:0;
	$nivel10				= (isset($_POST["c_nivel_10"]) && !empty($_POST["c_nivel_10"]))?$_POST["c_nivel_10"]:0;
	$nivel11				= (isset($_POST["c_nivel_11"]) && !empty($_POST["c_nivel_11"]))?$_POST["c_nivel_11"]:0;
	$nivel12				= (isset($_POST["c_nivel_12"]) && !empty($_POST["c_nivel_12"]))?$_POST["c_nivel_12"]:0;
	$nivel13				= (isset($_POST["c_nivel_13"]) && !empty($_POST["c_nivel_13"]))?$_POST["c_nivel_13"]:0;
	$nivel14				= (isset($_POST["c_nivel_14"]) && !empty($_POST["c_nivel_14"]))?$_POST["c_nivel_14"]:0;
	
	$fortalezas		 		= strtoupper((str_replace("'","",$_POST["c_fortalezas"])));
	$debilidades		 	= strtoupper((str_replace("'","",$_POST["c_debilidades"])));
	
	$sql ="delete from slorg_segmentacion_resultados where rut='$v_rut'";
	mysql_query($sql,$conexion) or die(mysql_error());
	
	$sql_seg = "SELECT id FROM slorg_segmentaciones where id_cliente=".$_SESSION['id_cliente'];
	$rs_seg=mysql_query($sql_seg, $conexion) or die(mysql_error());	  
	while ($row_seg=mysql_fetch_array($rs_seg)) { 
		$valor='opc_seg_' . $row_seg[0];
		$vg	= $_POST[$valor];
		
		$sqlx = "insert into slorg_segmentacion_resultados (rut,segmentacion_id,segmentacion_opcion_id) values (";
		$sqlx.= "'".$v_rut . "','".$row_seg[0]."','" . $vg . "')";
		mysql_query($sqlx,$conexion); 	
	};
	//die();
	$propo1					= $_POST["c_r1"];
	$propo2					= $_POST["c_r2"];
	if ($propo1=="") {$propo1=0;}
	if ($propo2=="") {$propo2=0;}
			
	$sql = "delete from slorg_resultados where rut = '" . $v_rut . "'"; 
	mysql_query($sql,$conexion) or die(mysql_error());
	
	for($i=1;$i<=63;$i++) 
	{	
		$valor 		= "c_p" . $i;	
		$pregunta1	= (isset($_POST[$valor]))?$_POST[$valor]:-3;
		$valor 		= "c_pa" . $i;	
		$pregunta2	= (isset($_POST[$valor]))?$_POST[$valor]:-3;
		//if ($pregunta1=="") {$pregunta1=-3;}
		//if ($pregunta2=="") {$pregunta2=-3;}
		
		if($popup==0){
		  $completado = '1';		
		}else{
		  $completado = '0';			
		}
		
		$sql  = "INSERT INTO slorg_resultados(rut,unidad,cjuridica,funcion,antiguedad,numero_pregunta,pregunta_unidad,pregunta_instit,nivel_1,nivel_2,nivel_3,nivel_4,nivel_5,nivel_6,nivel_7,nivel_8,nivel_9,nivel_10,nivel_11,nivel_12,nivel_13, nivel_14, fortalezas,debilidades,proposicion1,proposicion2,completado) values (";
		$sql .= "'".$v_rut . "', ";
		$sql .= $v_unidad . ",";
		$sql .= $v_cjuridica . ",";
		$sql .= $v_funcion . ",";
		$sql .= $v_antiguedad . ",";
		$sql .= $i . ", ";	
		$sql .= $pregunta1 . ",";
		$sql .= $pregunta2 . ",";
		$sql .= $nivel1 . "," . $nivel2 . "," . $nivel3 . ",";
		$sql .= $nivel4 . "," . $nivel5 . "," . $nivel6 . "," . $nivel7 . "," . $nivel8 . ","; 
		$sql .= $nivel9 . "," . $nivel10 . "," . $nivel11 . "," . $nivel12 . "," . $nivel13 . ",";
		$sql .= $nivel14 . ",'" . $fortalezas . "','" . $debilidades . "',";
		$sql .= $propo1 . "," . $propo2 . ",".$completado.")";	
		//var_dump($sql); 	
		mysql_query($sql,$conexion) or die(mysql_error());
	}	
	
				
	$sql = "delete from slorg_select_options_res where rut = '" . $v_rut . "'"; 
	mysql_query($sql,$conexion) or die(mysql_error());
	$sql = "INSERT INTO slorg_select_options_res (rut, id_select, value) VALUES ('$v_rut', '$v_iSelect', '$v_cr')";
	mysql_query($sql,$conexion) or die(mysql_error());
	
	mysql_close($conexion);    
	if ($popup==0)
	{
	  $msg  = " Estimado Usuario, los datos fueron grabados con exito en nuestra base de datos.\n";
      //$msg .= " Si quiere agregar m&aacute; informaci&oacute;n presione el boton ACEPTAR de lo contrario solo cierre la p&aacute;gina.\n";
	  $msg .= " Gracias, por su cooperacion";
	  $_SESSION['finCuestionario'] = true;	  
	  
	}
	else
	{
	  $msg = "Estimado/a los datos fueron grabados con exito, por favor complete el resto del formulario.\n";
	  $_SESSION['finCuestionario'] = false;	  
	}
	$_SESSION['msg'] = $msg;
	$destino = "dpp_formulario.php";
 	
// 	echo "<html><head><script>alert( " . $msg . " );window.location.href = " . $destino . ";</script></head></html>";
 	header('Location: ' .$destino); // hacemos el redireccionamiento		
?>
