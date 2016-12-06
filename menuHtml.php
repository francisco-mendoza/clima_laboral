<?php
  error_reporting(0);
  session_start();
  if ($_SESSION['usuario']==""){echo "session expirada! vuelva a ingresar<br><br><a href='index.htm'>Inicio</a>";die();}

  function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
      if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
        return true;
      }
    }
    return false;
  }

  $idTabActivo = 0;
  if(isset($_SESSION['tabActual'])){
	$idTabActivo = $_SESSION['tabActual'];
	unset($_SESSION['tabActual']);
  }
  if(isset($_REQUEST['tabActual'])){
	$idTabActivo = $_REQUEST['tabActual'];
  }
  
?>

<?php
	include_once("include/dbconection.php");
	//$conexion = DAL::conexion();
	 $sql = "SELECT * from slorg_cliente inner join dpp_slorg_usuario 
			on slorg_cliente.id_cliente = dpp_slorg_usuario.id_cliente where dpp_slorg_usuario.rut =".$_SESSION['usuario'];
	 $rs1=mysql_query($sql, $conexion) or die(mysql_error());
	 $fila = mysql_fetch_assoc($rs1);
	 /*
	$mysql = "SELECT tipo_formulario from slorg_cliente where id_cliente = 1";
	$resultado = mysql_query($sql,$conexion) ;
	$row = mysql_fetch_assoc($resultado);*/
	//$valor = $row[0];
?>

<table class='table row-fluid' id="titulo" border="0"  align="center" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <a  href='index.php' >	
      <img src="images/logosinrefl.png" height="50px" width="50px">
    </a>  			
  </td>	
  <td class='barraDer ' nowrap>
    <a href='index.php' class='textoSinDecoracion'>  			
    <span class='tituloFront'>
      SATISFACCION<br>  
      LABORAL.ORG <?php 

      /*if($fila['tipo_formulario']==2)
      	echo "Es Istas";
      else
      	echo "No es istas";*/

      switch ($fila['tipo_formulario']) {
      	case 1:
      		$soloSatisfaccion = "Solo SATISFACCION";
      		break;
      	
      	case 2:
      		echo "Solo Istas";
      		break;
      	case 3:
      		echo "Ambos";
      		break;	
      }

      /*if($valor == '2')
      	{echo "es istas";}
      else
      	{echo "NO es istas";}
*/


       ?>
    </span>&nbsp;&nbsp;  			
    
    </a>
  </td>	
	
  <td width='99%' align="center" nowrap class='contenedorBarraFront'>
  	
  	<table class='barraFront'>
  	  <tr>
		<!--  		
  		<td>
  		  <a href='index.php'>	
  		    <img src="images/psicuscaja2.png">
  		  </a>  			
  		</td>	
  		<td>
  		  <a href='index.php' class='textoSinDecoracion'>  			
  		  <span class='tituloFront'>
  		  SATISFACCI&Oacute;N<br>
  		  LABORAL.ORG
  		  </span>  			
  		  </a>
  		</td>
  		-->
  		<td>
		<div>
		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs nav-custom" role="tablist" id="myTabs">	
		  <!-- Tab panes -->

  			
  	<?php
		 //var_dump($_SESSION);		
		 if(isset($_SESSION['menus'])){

		  if(in_array_r("INICIO",$_SESSION['menus'],false)){
		?>		  	
   			<!--<a  class='btn btn-custom' href="dpp_presentacion.php">INICIO</a>-->
		   <!--
		   <li role="presentation" class='<?php /*if($idTabActivo=='0')echo 'active';*/ ?>'>
		   	 <a href="dpp_presentacion.php" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="0">
		       <i class="circulo fa fa-home">
</i>
		       INICIO		    	
		     </a>
		   </li>   			
		   -->
		<?php			
		  }	
		?>

		<?php
		  if(in_array_r("FORMULARIO",$_SESSION['menus'],false)){
		?>	
			<!--	  	
		   <a class='btn btn-custom' href="dpp_formulario.php">
		     <i class="circulo fa fa-file-text-o"></i>
		     FORMULARIO
		   </a>
		  	-->
		  	<?php 
		  		 switch ($fila['tipo_formulario']) {
      				case 1:
      				//SOLO SATISFACCION
      		?>

				<li role="presentation" class='<?php if($idTabActivo=='1')echo 'active'; ?>'>
		   		 <a href="formulario.php" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="1">
		       <!--i class="fa fa-file-text-o"></i-->
			  	 <img src="images/nuevo/form2.png" width="30px" height="28px">
		     	  FORMULARIO		    	
		   		  </a>
		   		</li>

      		<?php
      		break;
      		?>
      		<?php
      	
      			   case 2:
      			   //SOLO ISTAS
      		
      		?>
				<li role="presentation" class='<?php if($idTabActivo=='1')echo 'active'; ?>'>
		   		 <a href="formulario_istas.php" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="1">
		       <!--i class="fa fa-file-text-o"></i-->
			  	 <img src="images/nuevo/form2.png" width="30px" height="28px">
		     	  FORMULARIO ISTAS		    	
		   		  </a>
		   		</li>
			<?php
      		break;
      		?>
      	    <?php
      			 case 3:
      				//AMBOS

      				?>
					<li role="presentation" class='<?php if($idTabActivo=='1')echo 'active'; ?>'>
		   		 	 <a href="formulario.php" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="1">
		       				<!--i class="fa fa-file-text-o"></i-->
			 		  <img src="images/nuevo/form2.png" width="30px" height="28px">
		      			 FORMULARIO	    	
		   			  </a>
		 		  </li>

      				<?php
      			 break;	
      }
		  	 ?>

		   

			<?php 

			 ?>


		<?php			
		  }	
		?>

		<?php
		  if(in_array_r("VER RESULTADOS",$_SESSION['menus'],false)){
		?>		
		  <!--  	
		  <a  class='btn btn-custom' href="dpp_ver_resultados.php">
		  	<i class="circulo fa fa-bar-chart"></i> VER RESULTADOS
		  </a>
		  -->
		   <li role="presentation" class='<?php if($idTabActivo=='2')echo 'active'; ?>'>
			<a href="dpp_ver_resultados.php" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="2">
		  	  <!--i class="circulo fa fa-bar-chart"></i-->
		  	  <img src="images/nuevo/res2.png" width="35px" height="28px">
		  	  
		  	   VER RESULTADOS
		  	</a>
		   </li>		  
		  
		  
		<?php			
		  }	
		?>	


		<?php
		  if(in_array_r("ADMINISTRADOR",$_SESSION['menus'],false)){
		?>		  	

 		  
		  <!--  	
   		  <a  class='btn btn-custom' href="dpp_admin.php">
   		  	<i class="circulo fa fa-cogs"></i> ADMINISTRADOR
 		  </a>
		  -->
		   <li role="presentation" class='<?php if($idTabActivo=='3')echo 'active'; ?>'>
			<a href="dpp_admin.php" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="3">
		  	  <!--i class="fa fa-cogs"></i--><img src="images/nuevo/cogs2.png" width="30px" height="28px"> ADMINISTRADOR
		  	</a>
		   </li>	

		   <li role="presentation" class='<?php if($idTabActivo=='3')echo 'active'; ?>'>
			<a href="admin/" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="3">
		  	 <!-- <i class="fa fa-user"></i>  --><img src="images/nuevo/user2.png" width="30px" height="28px">  AGREGAR CLIENTE
		  	</a>
		   </li>		   		  
 		  
		<?php			
		  }	
		?>	

		<?php
		  if(in_array_r("ADMINISTRACIONCLIENTE",$_SESSION['menus'],false)){
		?>		  	
		
 		  
		  <!--  	
   		  <a  class='btn btn-custom' href="dpp_admin_cliente.php">
   		  	<i class="circulo fa fa-cogs"></i> ADMINISTRADOR
   		  </a>
		  -->
		   <li role="presentation" class='<?php if($idTabActivo=='4')echo 'active'; ?>'>
			<a href="dpp_admin_cliente.php" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="4">
		  	  <!--i class="fa fa-cogs"></i--><img src="images/nuevo/cogs2.png" width="30px" height="28px"> ADMINISTRADOR
		  	</a>
		   </li>		   		  
 		  		
		

		<?php			
		  }	
		?>			
	

		<?php
		  if(in_array_r("SALIR",$_SESSION['menus'],false)){
		?>		  	
		  <!--  	
	      <a  class='btn btn-custom' href="terminar_sesion.php">
	      	<i class="circulo fa fa-times"></i>SALIR
	      </a>
		  -->
		   <li role="presentation" class='<?php if($idTabActivo=='5')echo 'active'; ?>'>
			<a href="terminar_sesion.php" aria-controls="profile" role="tab" data-toggle="tab" data-num-tab="5">
	      	<img src="images/nuevo/salir2.png" width="30px" height="28px"> SALIR
		  	</a>
		   </li>				
		

		<?php			
		  }	
		?>
			
			
		<?php	
		 } 
		?>  			
		
		  </ul>				
		 </div>
  		</td>
  	  </tr>
  	</table>
  	

   <!--
   <li><a  class='normalMenu' href="dpp_planes_mejora.php">PLANES DE MEJORA</a></li>
    -->

</td>
</tr>
</table>

<form id='menuTab' name='menuTab' action="" method='POST'>
  <input type='hidden' name='tabActual' id='tabActual' value=''>
</form>

<script>
  $('#myTabs a').click(function (e) {
    //alert($(this).text())
      e.preventDefault();
      //$(this).tab('show');
      $("#menuTab").attr("action",$(this).attr("href"));
      $("#tabActual").val($(this).attr("data-num-tab"));
	  //console.log($("#menuTab"));
	  //console.log($(this).attr("href"));	  
	  $("#menuTab").submit();      
    })	
</script>