<?php
//header( 'Content-type: text/html; charset=utf-8' );

  //error_reporting(0);
  //---------------------------------------------------------------------------------------------------------------- 
  // Verificamos el uso de session, sino lo mandamos a logearse
  session_start();  
  if ($_SESSION['usuario']==""){echo "session expirada! vuelva a ingresar<br><br><a href='index.htm'>Inicio</a>";die();}   
  //----------------------------------------------------------------------------------------------------------------
  include_once("include/dbconection.php");
  include_once("include/definiciones.php");  	
  include_once("include/funciones.php");
  include_once("include/renderizacion.php");
    
  $rut = $_SESSION["usuario"];
  
  include("headerHtml.php");
?>
<script src='include/js/carga_select_unidad.js'></script>
<script src='include/js/condicion_distintos_valores_opcion_del_grupo.js'></script>
<script src='include/js/condicion_todas_opcion_del_grupo.js'></script>
<script src='include/js/condicion_una_opcion_del_grupo.js'></script>
<body>

<?php include_once('menuHtml.php'); ?>

<form name='se' id='se' method='post' action='formulario_graba.php'>

 
<?php



  if(isset($_SESSION['msg'])){
  ?>  
    <div class='alert alert-success textoCentrado'>
    	<h3><?php echo $_SESSION['msg']; ?></h3>
    </div>    
<?php
  } elseif($_SESSION['Completado']){ ?>
    <div class='alert alert-success textoCentrado'>
       <h3>Ud. Ya Ha Completado el Formulario, Gracias por su participaci&oacute;n.</h3>
    </div>	
<?php
   }

  $arrParametros	 = array('id_cliente'=>$_SESSION['id_cliente']);
  $arrDataFormulario = DB_FORM_obtenerFormulario($arrParametros,$conexion);
  //var_dump($arrDataFormulario);
  //echo date("Y-m-d",strtotime($arrDataFormulario['fecha_inicio']));
  $tAhora  = strtotime(date('Y-m-d'));
  $tInicio = strtotime($arrDataFormulario['fecha_inicio']);
  $tFin    = strtotime($arrDataFormulario['fecha_fin']);
  
  if($tAhora < $tInicio){
	?>
	<div class='alert alert-info textoCentrado '>
	  <b>El Formulario aun no se encuentra abierto, puede contestarlo desde el <?php echo date('d/m/Y',strtotime($arrDataFormulario['fecha_inicio']));?>
	  </b>
	</div>
	
	<?php
    exit;  	
  }

  if($tAhora > $tFin){
	?>
	<div class='alert alert-danger textoCentrado '>
	  <b>Se ha terminado el plazo para contestar el Formulario, se ha cerrado el <?php echo date('d/m/Y',strtotime($arrDataFormulario['fecha_fin']));?>
	  </b>
	</div>
	
	<?php
    exit;  	
  }
  
  $arrItems 		 = DB_FORM_obtenerItems($arrDataFormulario,$conexion);
  //var_dump($arrItems);
  $_SESSION['Completado'] = DB_RESULT_estadoResultado($_SESSION['id_usuario']);
  //var_dump($_SESSION);
  
  echo "<div class='sortable'>";
  foreach($arrItems as $item){		
	echo obtenerContenido($item);
  }
  echo "</div>";
  echo '<input type="hidden" name="tipo_guardado" id="tipo_guardado" value="0">';
  echo "<script>\n";  
  echo renderizarCondicionesFormulario($arrItems);
	
  ?>	  
	$(document).ready(function(){
      <?php
	    if(!in_array('3',$_SESSION['tipo_usuario'])){
	      if($_SESSION['Completado'] || $_SESSION['finCuestionario']){ 
		    echo "$('#se :input').attr('disabled', true);";
          }		    	
	    }
  		unset($_SESSION['msg']);
  		unset($_SESSION['Completado']);		
  		unset($_SESSION['finCuestionario']);		
      ?>
      

	  <?php 
	    if(isset($_SESSION['id_formulario_enlazado'])){
	    ?>
	    
		bootbox.dialog({
		  message: "Gracias por contestar la encuesta, los datos se han grabado exitosamente",
		  title: "Encuesta de satisfacci&oacute;n laboral finalizada",
		  buttons: {
		    main: {
		      label: "Salir",
		      className: "btn-primary",
		      callback: function() {
		        location.href="terminar_sesion.php";
		      }
		    }
		  }
		});

	    <?php  	 
  		  //unset($_SESSION['id_formulario_enlazado']);
  		  unset($_SESSION['msg_formulario_enlazado']);						
	    } 
	  ?>

      
	  $('.sortable').each(function(){
		/*var $this = $(this);
		$this.append($this.find('.score').get().sort(function(a, b) {
		  return $(a).data('index') - $(b).data('index');
		}));*/
	  });      
      
	})
	
	function toggleBotonGuardar(id){
      if($("#capaBoton"+id).is(":visible")){
	    $("#capaBoton"+id).hide();
	    $("#loaderBoton"+id).show();	  		
	  } else {
	    $("#capaBoton"+id).show();
	    $("#loaderBoton"+id).hide();	  		
	  }		
	}	

	function mostrarBotonesGuardar(){
	  $(".capaLoader").hide();
      $(".botonGuardar").show();		
	}
	
<?php   
  echo "</script>";  
?>

</form>


<div align=center>

<table border=0 cellspacing=0 cellpadding=0 style='mso-cellspacing:0cm;
 mso-padding-alt:0cm 0cm 0cm 0cm'>
 <tr>
  <td >

    	  <div class='footer' style='padding:10px;'>

  <span class="MsoNormal " align=center style='text-align:center'><span
  style=''>Copyright @ 2015, <a
  href="http://www.psicus.cl" target="_blank"><span style='color:white'>PSICUS
  Profesionales,</span></a> Resoluci&oacute;n Recomendada:1024x768 </span>
  </span>
  	
  </div>

  </td>
 </tr>
</table>

</div>

</body>
</html>