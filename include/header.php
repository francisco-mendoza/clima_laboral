<?php
//----------------------------------------------------------------------------------------------------------------
// Verificamos el uso de session, sino lo mandamos a logearse
session_start();
if ($_SESSION['usuario'] == "") {echo "session expirada! vuelva a logearse<br><br><a href='../index.htm'>Inicio</a>";
	die();
}
define("ROOT_PATH", "/var/www/psicus.cl/public_html/slorg");

$acceso_formulario = $_GET["acceso_formulario"];
$rut = $_GET["rut"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="author" content="ivan cabrera" />
		<meta name="description" content="Generar graficos con PHP y jpgraph" />
		<meta name="keywords" content="jpgraph, php, grafico de barras, diagrama de barras, grafico de tarta, diagrama de tarta, estadistica, alvaro pita" />	

		
		<title>PSICUS Profesionales</title>
		<link rel="stylesheet" type="text/css" media=all href="../beta2.css">
		<link rel="stylesheet" type="text/css" media=all href="../sig_styles.css">
		
		<link rel="stylesheet" type="text/css" href="../include/menu.css" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
		<script type="text/javascript" src="../include/menu.js"></script>
		<script type="text/javascript" >
		
		</script>
	</head>
	
	<body bgcolor="#fff">
		<div class=Section1>
			<div align=center>
				<table id="titulo" border="0"  align="center" cellpadding="0" cellspacing="0">
					<tr><td align="center"><img src="../images/banner_titulo.png" width="910" height="100"></td></tr>
					<tr>
						<td background="../images/banner_menu.gif" height="35" alt="" align="center">
							<div id="menu-container">
								<ul id="navigationMenu"> 
		 						    <li><a  class='normalMenu' href="../dpp_presentacion.php?rut=<?php echo $rut; ?>&acceso_formulario=<?php echo $acceso_formulario; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li> 
		 						    <li><a  class='normalMenu' href="../dpp_formulario.php?rut=<?php echo $rut; ?>&acceso_formulario=<?php echo $acceso_formulario; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORMULARIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
								    <li><a  class='normalMenu' href="mostrar-graficos.php?rut=<?php echo $rut; ?>&acceso_formulario=<?php echo $acceso_formulario; ?>">&nbsp;VER RESULTADOS&nbsp;</a></li> 
								    <!-- <li><a  class='normalMenu' href="../dpp_planes_mejora.php">PLANES DE MEJORA</a></li> -->  
								    <li><a  class='normalMenu' href="../dpp_admin.php?rut=<?php echo $rut; ?>&acceso_formulario=<?php echo $acceso_formulario; ?>">&nbsp;&nbsp;&nbsp;ADMINISTRADOR&nbsp;&nbsp;&nbsp;</a></li>        
								    <li><a  class='normalMenu' href="../terminar_sesion.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SALIR [x]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
							    </ul>
						    </div>
						</td>
					</tr>
				</table>				
			</div>