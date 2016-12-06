<?php
	//---------------------------------------------------------------------------------------------------------------- 
	// Verificamos el uso de session, sino lo mandamos a logearse
	session_start();  
	if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.php'>Inicio</a>";die();}
	if (!in_array(array('2','3'),$_SESSION['tipo_usuario'])){echo "Por Seguridad esta sección esta reservada para los administradores <br><br><a href='index.php'>Inicio</a>";die();}	
	//----------------------------------------------------------------------------------------------------------------
	// Cargamos el acceso a la base de datos
	include("include/dbconection.php");
	$rut     		   = $_SESSION['usuario']; //$_GET["rut"];
	$acceso_formulario = $_GET["acceso_formulario"];
	// aca se hace el orden da las preguntas	
	$xx = $_GET['x'];
	$id					 = $_GET['id'];
	$pos_act				 = $_GET['pos_act'];
	$pos_fut				 = $_GET['pos_fut'];
	
	// fin orden de preguntas
	

include('headerHtml.php');	
		
?>
<script>
  var sufijo_error = '_error';


	
</script>
<body bgcolor="#FFFFFF"  background="images/bg.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/b_grabar_r.gif','images/b_grabar_solo_r.gif')">

<?php

include_once('menuHtml.php');

?>

<form name="se" method="post" action="graba_admin_cliente.php">
	<input type='hidden' name="tabActual" value="4">
	<input type='hidden' name='id_cliente' id='id_cliente' value='<?php echo $_SESSION['id_cliente']?>'>

<?php


  if(isset($_SESSION['mensajeGrabacion'])){
	echo $_SESSION['mensajeGrabacion']; 	
  }
  unset($_SESSION['mensajeGrabacion']);
?>



    <table width="780px" border="0"  align="center" cellpadding="0" cellspacing="0" bgcolor="#fafafa">
      <tr>
        <td colspan="4"><p align="center" style="line-height: 30px;"> <strong><font size="+1">ADMINISTRACI&Oacute;N DE USUARIOS Y PREGUNTAS DEL FORMULARIO</font></strong></p></td>
      </tr>
      <tr>
        <td colspan=4 align="center" valign="middle" class="Estilo5">&nbsp;</td>
      </tr>
      <tr>
        <td colspan=4 align="justify" class="cuadro_tabla">
			<?php
			$sqlp ="select texto_inicio1,texto_inicio2 from slorg_admin where id_cliente = '".$_SESSION['id_cliente']."'";
	 	 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());	
		 	$rowp=mysql_fetch_array($rs1)
		 	?> 		
			<table cellpadding="1" cellspacing="1" border="0" width="776px">
			<tr><td colspan="2" class="titulo_tabla_des">Textos introductorios de Pagina de Inicio</td></tr>
			<tr>
			<td class="encabezado-tabla-fon-gris-normal">Titulo</td>
			<td class="item-tabla-fon-bco"><input type="text" name="texto_inicio1" size="80" class="input-generico" value="<?php echo $rowp[0];?>"></td>
			</tr>
			<tr>
			<td class="encabezado-tabla-fon-gris-normal">Descripcion</td>
			<td class="item-tabla-fon-bco"><input type="text" name="texto_inicio2" size="80" class="input-generico" value="<?php echo $rowp[1];?>"></td>
			</tr>
			<tr><td colspan="2" class="item-tabla-fon-bco" align="center"><input type="submit" value="Grabar"></td></tr>			
			</table>
		 </td>
		</tr> 	
      <tr><td colspan=4>&nbsp;</td></tr>
      <tr>
        <td colspan=4 align="justify" class="cuadro_tabla">
			<?php
			$sqlp ="select texto1,texto2,texto3 from slorg_admin where id_cliente = '".$_SESSION['id_cliente']."'";
	 	 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());	
		 	$rowp=mysql_fetch_array($rs1)
		 	?> 		
			<table cellpadding="1" cellspacing="1" border="0" width="100%">
			<tr><td colspan="2" class="titulo_tabla_des">Textos introductorios del Formulario</td></tr>
			<tr>
			<td class="encabezado-tabla-fon-gris-normal">Texto de Alerta en Rojo</td>
			<td class="item-tabla-fon-bco"><input type="text" name="texto1" size="80" class="input-generico" value="<?php echo $rowp[0];?>"></td>
			</tr>
			<tr>
			<td class="encabezado-tabla-fon-gris-normal">Titulo</td>
			<td class="item-tabla-fon-bco"><input type="text" name="texto2" size="80" class="input-generico" value="<?php echo $rowp[1];?>"></td>
			</tr>
			<tr>
			<td class="encabezado-tabla-fon-gris-normal">Texto introductorio</td>
			<td class="item-tabla-fon-bco"><textarea name="texto3" cols="80" rows="4" class="input-generico"><?php echo $rowp[2];?></textarea></td>
			</tr>
			<tr><td colspan="2" class="item-tabla-fon-bco" align="center"><input type="submit" value="Grabar"></td></tr>			
			</table>
		 </td>
		</tr> 	
      <tr><td colspan=4>&nbsp;</td></tr>
	</table>
<script>

  $(document).ready(function(){
    
  	
  })	
</script>

</body>
</html>
            
            
            
