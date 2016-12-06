<?php
  //---------------------------------------------------------------------------------------------------------------- 
  // Verificamos el uso de session, sino lo mandamos a logearse
  session_start();  
  if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}
  //----------------------------------------------------------------------------------------------------------------
  // Cargamos el acceso a la base de datos
  include_once("include/dbconection.php");
  include_once("include/definiciones.php");
  include_once("include/funciones.php");
  include_once("grafico/balance_social.php");  
  include_once("grafico/tabla_inventario.php");  
?>

<?php
  include_once('headerHtml.php');
?>
<style>
/*
#header-wrap {
    background: #eeeeff;
    position: fixed;
    width: 100%;
    height: 50px;
    top: 0;
    z-index: 1;
}	
#container{ 
    margin-top: 50px;
}	
	*/
</style>
<div id='header-wrap'>
<?php
  include_once('menuHtml.php');
?>
</div>
<div id="container">
	
<script>
	
	function cargarSelect(iNivel,valor){


    for(var i=iNivel;i<12;i++){
      $('#sel_cr_'+i)
         .find('option')
         .remove()
         .end();
      $('#sel_cr_'+i)
        .append($("<option></option>")
        .attr("value",'0')
        .text('Seleccione'));    	
    }
    	
  $.post("consultas_ajax.php",{accion:'obtenerUnidades',padre:valor},function(result){
  //alert(result);
    try{
      data = JSON.parse(result);
      $.each(data, function(key, value){   
       $('#sel_cr_'+iNivel)
         .append($("<option></option>")
         .attr("value",value['id'])
         .text(value['nombre']));
      });
    } catch (e){
      alert(result);	
    } 
  }).error(function(xhr, textStatus, errorThrown){
    alert(xhr.responseText);
  }); 
}

 function enviar(){
   for(var i=0;i<12;i++){
     if($('#sel_cr_'+i).length>0){
       //alert(i);
       //alert($('#sel_cr_'+i).val())
       if($.trim($('#sel_cr_'+i).val())!='' && $.trim($('#sel_cr_'+i).val())!='0'){
         $('#id_select').val($('#sel_cr_'+i).val());       	
       }
     }
   }
   /*if($.trim($('#id_select').val())=='' || $.trim($('#id_select').val())=='0'){
     alerta('Por favor seleccione al menos uno de los niveles ');
     return;	
   }*/
   $("#form_graficos").submit();
 }

</script>


    <br>
  	<table width='80%' align='center'>
  	  <tr>
  	  	<td>
  	  	  <div class='panel panel-default'>
			<div class='numeroPanel'>
				
			  <!-- -->
			  <?php 
			    //obtener porcentaje de contestados completos del formulario por defecto del cliente 1
			    //versus la cantidad de usuarios totales del cliente
			    $iCantidadEncuestados =  DB_RESULT_obtenerParticipacion($_SESSION['id_cliente']);
			    $iCantidadTotalUsrs   =  DB_USUARIO_cantidadUsrActivos($_SESSION['id_cliente']);
			    $arrCr   		  	  =  DB_CR_obtenerTodosCr($_SESSION['id_cliente'],1);

				$iCr 				  = count($arrCr);
				$iTotal 			  = ($iCantidadEncuestados/$iCantidadTotalUsrs)*100;
				//$fechaTermino 		  = DB_FORM_obtenerFechaTermino($_SESSION['id_cliente']);
				$diasRestantes 		  = DB_FORM_obtenerDiasRestantes($_SESSION['id_cliente']);
				
				$arrGraficos 		  = DB_PARAMETROS_obtenerInfoGraficos();
				$idCliente			  = $_SESSION['id_cliente'];
				echo number_format($iTotal,1)."%";
			  ?>				
				
			</div>  	  	  	
			<div class='descripcionPanel'>
			  <span class='iconoPanel'></span>
			  	
		  
			  <span class='tituloPanel'>  <i class="fa fa-check-square-o"></i> % De cumplimiento</span>			  	
			</div>			
  	  	  </div>	
  	  	</td>
  	  	
  	  	<td>
  	  	  <div class='panel panel-default'>
			<div class='numeroPanel'>
				
				<?php
				
				echo $iCantidadEncuestados;
				
				?>
				
			</div>  	  	  	
			<div class='descripcionPanel'>
			  <span class='iconoPanel'></span>
			  <span class='tituloPanel'> <i class="fa fa-users"></i> Cantidad de encuestados </span>			  	
			</div>			
  	  	  </div>	
  	  	</td>  	  	


  	  	<td>
  	  	  <div class='panel panel-default'>
			<div class='numeroPanel'>
				<?php
				
				echo $iCantidadTotalUsrs;
				
				?>				
				
			</div>  	  	  	
			<div class='descripcionPanel'>
			  <span class='iconoPanel'></span>
			  <span class='tituloPanel'> <i class="fa fa-user"></i> N&#176; de funcionarios </span>			  	
			</div>			
  	  	  </div>	
  	  	</td>  	  	

  	  	<td>
  	  	  <div class='panel panel-default'>
			<div class='numeroPanel'>
				<?php
				
				  echo $iCr;
				  
				?>
			</div>  	  	  	
			<div class='descripcionPanel'>
			  <span class='iconoPanel'></span>
			  <span class='tituloPanel'> <i class="fa fa-sitemap"></i> Centros de responsabilidad </span>			  	
			</div>			
  	  	  </div>	
  	  	</td>  	


  	  	<td>
  	  	  <div class='panel panel-default'>
			<div class='numeroPanel'>
				<!--span id="clock"></span-->
				
				<?php
				echo $diasRestantes;
				  ?>
				
			</div>  	  	  	
			<div class='descripcionPanel'>
			  <span class='iconoPanel'></span>
			  <span class='tituloPanel'> <i class="fa fa-calendar"></i> Dias Restantes </span>			  	
			</div>			
  	  	  </div>	
  	  	</td>  	
  	  	
  	  </tr>	
  	</table>
  

<?php //		die(); ?>

  <div>

      <?php

      $sql = "SELECT * from slorg_cliente inner join dpp_slorg_usuario
      on slorg_cliente.id_cliente = dpp_slorg_usuario.id_cliente where dpp_slorg_usuario.rut =".$_SESSION['usuario'];
      $rs1=mysql_query($sql, $conexion) or die(mysql_error());
      $fila = mysql_fetch_assoc($rs1);

      ?>







      <!-- Nav tabs -->
      <ul class="nav nav-tabs" id='tabGrafico' role="tablist">

          <?php
          switch ($fila['tipo_formulario']) {
              case 1:
                  //SOLO SATISFACCION LABORAL
                  ?>
                  <li role="presentation" class="active">
                      <a href="#gDsg" aria-controls="gDsg" role="tab" data-toggle="tab">
                          Gr&aacute;ficos Generales
                      </a>
                  </li>

                  <li role="presentation">
                      <a href="#gPvpi" aria-controls="gPvpi" role="tab" data-toggle="tab">
                          Gr&aacute;ficos Dinamicos
                      </a>
                  </li>
                  <?php
                  $claseTab1 = "tab-pane active textoCentrado";
                  $claseTab2 = "tab-pane textoCentrado";
                  $claseTab3 = "tab-pane textoCentrado";

                  break;

              case 2:
                  //SOLO ISTAS
                  ?>
                  <li role="presentation" class="active">
                      <a href="#gIstas" aria-controls="gIstas" role="tab" data-toggle="tab">
                          Suseso Istas 21
                      </a>
                  </li>
                  <?php
                  $claseTab1 = "tab-pane textoCentrado";
                  $claseTab2 = "tab-pane textoCentrado";
                  $claseTab3 = "tab-pane active textoCentrado";
                  break;
              case 3:
                  //AMBOS
                  ?>
                  <li role="presentation" class="active">
                      <a href="#gDsg" aria-controls="gDsg" role="tab" data-toggle="tab">
                          Gr&aacute;ficos Generales
                      </a>
                  </li>

                  <li role="presentation">
                      <a href="#gPvpi" aria-controls="gPvpi" role="tab" data-toggle="tab">
                          Gr&aacute;ficos Dinamicos
                      </a>
                  </li>

                  <li role="presentation">
                      <a href="#gIstas" aria-controls="gIstas" role="tab" data-toggle="tab">
                          Suseso Istas 21
                      </a>
                  </li>
                  <?php
                  $claseTab1 = "tab-pane active textoCentrado";
                  $claseTab2 = "tab-pane textoCentrado";
                  $claseTab3 = "tab-pane textoCentrado";
                  break;
          }
          ?>

      </ul>

	<?php	
	  $ident = identificador();
	  //var_dump($_SERVER);
	  //echo HOME_PATH."<br>";
	  //echo GET_CONTENT_PATH;	  
	  //exit;
	?>

  <!-- Tab panes -->
  
  
  <div class="tab-content">

    <div role="tabpanel" class="tab-pane active textoCentrado" id="gDsg"  align='center'>
      
      <table align='center'>
      	<tr>
      	  <td>
      <button type='button' class='btn btn-primary printBtn' data-toggle="tooltip" title="Imprimir" ><i class="fa fa-print"></i></button>      	  	
      	  </td>
      	  <td>
	  <form name='formPdf' method='POST' action="grafico/exportarPdf.php" target='_blank'>
	    <input name='id_cliente' type='hidden' value='<?php echo $_SESSION['id_cliente'];?>'>    
	    <input name='identificador' id='identificador' type='hidden' value='<?php echo $ident;?>'>	    
	    <button type='submit' class='btn btn-danger '  data-toggle="tooltip" title="PDF"  ><i class="fa fa-file-pdf-o"></i></button>	  	
	  </form>      	  	
      	  </td>
      	  <td>
	  <form name='formWord' method='POST' action="grafico/exportarWord.php" target='_blank'>   
	    <input name='id_cliente' type='hidden' value='<?php echo $_SESSION['id_cliente'];?>'>
	    <input name='identificador' id='identificador' type='hidden' value='<?php echo $ident;?>'>	    	  	   	  	
	    <button type='submit' class='btn btn-info '  data-toggle="tooltip" title="Word"  ><i class="fa fa-file-word-o"></i></button>    
	  </form>  	  	
      	  </td>      	        	  	
      	</tr>      	
      </table>

      <br>
      
<!---------------------------------- DSG -------------------------------------------->      
      <br>
        <div class='tituloGrafico'>      
        <?php echo $arrGraficos['DSG']['titulo']; ?>
        </div>
      <br>

      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'DSG','salida'=>'img','ident'=>$ident)); ?>" id="testPicture1" alt="" grafico='DSG' class="pChartPicture"/>      
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['DSG']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>      
      <script>
        addImage("testPicture1","pictureMap1","graficos.php?grafico=DSG&ImageMap=get");
      </script>
<!---------------------------------- DSG -------------------------------------------->
<hr style='page-break-before: always;height: px !important;'>
<!---------------------------------- DSCR -------------------------------------------->
      <br>
		<div class='tituloGrafico'>
        <?php echo $arrGraficos['DSCR']['titulo']; ?>
        </div>
      <br>

      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'DSCR','salida'=>'img','ident'=>$ident)); ?>" id="testPicture7" alt=""  grafico='DSCR' class="pChartPicture"/>      
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['DSCR']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>          
      <script>
        addImage("testPicture7","pictureMap7","graficos.php?grafico=DSCR&ImageMap=get");
      </script>  
<!---------------------------------- DSCR -------------------------------------------->
<hr style='page-break-before: always;height: px !important;'>
<!---------------------------------- DSV -------------------------------------------->
      <br>
		<div class='tituloGrafico'>
        <?php echo $arrGraficos['DSV']['titulo']; ?>
        </div>
      <br>

      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'DSV','salida'=>'img','ident'=>$ident)); ?>" id="testPicture3" alt=""  grafico='DSV' class="pChartPicture"/>      
      
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['DSV']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>          
      <script>
        addImage("testPicture3","pictureMap3","graficos.php?grafico=DSV&ImageMap=get");
      </script>  
<!---------------------------------- DSV -------------------------------------------->
<hr style='page-break-before: always;height: px !important;'>
<!---------------------------------- PCRPI -------------------------------------------->
      <br>
        <div class='tituloGrafico'>
        <!--Promedio por CR vs promedio instituci&oacute;n-->
		<?php echo $arrGraficos['PCRPI']['titulo']; ?>
        </div>
      <br>

      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'PCRPI','salida'=>'img','ident'=>$ident)); ?>" id="testPicture8" alt=""  grafico='PCRPI' class="pChartPicture"/>      

	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['PCRPI']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>       
      <script>
        addImage("testPicture8","pictureMap8","graficos.php?grafico=PCRPI&ImageMap=get");
      </script> 
<!---------------------------------- PCRPI -------------------------------------------->
<hr style='page-break-before: always;height: px !important;'>  
<!---------------------------------- PVPI -------------------------------------------->
      <br>
        <div class='tituloGrafico'>
        <!--Promedio por variable vs promedio instituci&oacute;n-->
        <?php echo $arrGraficos['PVPI']['titulo']; ?>        
        </div>
      <br>

      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'PVPI','salida'=>'img','ident'=>$ident)); ?>" id="testPicture2" alt=""   grafico='PVPI' class="pChartPicture"/>      
      
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['PVPI']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>       
      <script>
        addImage("testPicture2","pictureMap2","graficos.php?grafico=PVPI&ImageMap=get");
      </script>
<!---------------------------------- PVPI -------------------------------------------->

<hr style='page-break-before: always;height: px !important;'>
<!---------------------------------- TC -------------------------------------------->
      <br>
      	<div class='tituloGrafico'>
        <?php echo $arrGraficos['TC']['titulo']; ?>
        </div>
      <br>


      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'TC','salida'=>'img','ident'=>$ident)); ?>" id="testPicture4" alt="" class="pChartPicture"  grafico='TC'  />      
     
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['TC']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>      
      <script>
        addImage("testPicture4","pictureMap4","graficos.php?grafico=TC&ImageMap=get");
      </script>
<!---------------------------------- TC -------------------------------------------->





<hr style='page-break-before: always;height: px !important;'>
<!---------------------------------- SEG1 -------------------------------------------->




<br>
        <div class='tituloGrafico'>
        <!--Promedio por variable vs promedio instituci&oacute;n-->
        <?php echo $arrGraficos['SEG1']['titulo']; ?>        
        </div>
      <br>

      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'SEG1','salida'=>'img','ident'=>$ident)); ?>" id="testPicture2" alt=""   grafico='SEG1' class="pChartPicture"/>      
      
    <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
        <tr>
          <td align='justify'>
      <?php echo $arrGraficos['SEG1']['descripcion']; ?>
          </td> 
        </tr>
      </table>
      <br>       
      <script>
        addImage("testPicture2","pictureMap2","graficos.php?grafico=SEG1&ImageMap=get");
      </script>

<!---------------------------------- SEG1 -------------------------------------------->
        <hr style='page-break-before: always;height: px !important;'>

<!---------------------------------- SEG2 -------------------------------------------->


<?php 

  //if($promedioFemenino === NULL){echo "No hay ";} 
$seg2=0; // 1 si quiero que aparezca 

if($seg2==1)
{ 
?>


        <br>
        <div class='tituloGrafico'>
            <!--Promedio por variable vs promedio instituci&oacute;n-->
            <?php echo $arrGraficos['SEG2']['titulo']; ?>
        </div>
        <br>

        <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'SEG2','salida'=>'img','ident'=>$ident)); ?>" id="testPicture2" alt=""   grafico='SEG2' class="pChartPicture"/>

        <br><br>
        <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
            <tr>
                <td align='justify'>
                    <?php echo $arrGraficos['SEG2']['descripcion']; ?>
                </td>
            </tr>
        </table>
        <br>
        <script>
            addImage("testPicture2","pictureMap2","graficos.php?grafico=SEG2&ImageMap=get");
        </script>

<!---------------------------------- SEG2 -------------------------------------------->

<hr style='page-break-before: always;height: px !important;'> <?php }?>

<!---------------------------------- SEG3 -------------------------------------------->

<?php 

  //if($promedioFemenino === NULL){echo "No hay ";} 
$seg3=0; // 1 si quiero que aparezca 

if($seg3==1)
{
?>


        <br>
        <div class='tituloGrafico'>
            <!--Promedio por variable vs promedio instituci&oacute;n-->
            <?php echo $arrGraficos['SEG3']['titulo']; ?>
        </div>
        <br>

        <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'SEG3','salida'=>'img','ident'=>$ident)); ?>" id="testPicture2" alt=""   grafico='SEG3' class="pChartPicture"/>

        <br><br>
        <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
            <tr>
                <td align='justify'>
                    <?php echo $arrGraficos['SEG3']['descripcion']; ?>
                </td>
            </tr>
        </table>
        <br>
        <script>
            addImage("testPicture2","pictureMap2","graficos.php?grafico=SEG3&ImageMap=get");
        </script>
<!---------------------------------- SEG3 -------------------------------------------->
        <hr style='page-break-before: always;height: px !important;'><?php }?>

<!---------------------------------- SEG4 -------------------------------------------->

<?php 

  //if($promedioFemenino === NULL){echo "No hay ";} 
$seg4=0; // 1 si quiero que aparezca 

if($seg4==1)
{
?>


        <br>
        <div class='tituloGrafico'>
            <!--Promedio por variable vs promedio instituci&oacute;n-->
            <?php echo $arrGraficos['SEG4']['titulo']; ?>
        </div>
        <br>

        <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'SEG4','salida'=>'img','ident'=>$ident)); ?>" id="testPicture2" alt=""   grafico='SEG4' class="pChartPicture"/>

        <br><br>
        <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
            <tr>
                <td align='justify'>
                    <?php echo $arrGraficos['SEG4']['descripcion']; ?>
                </td>
            </tr>
        </table>
        <br>
        <script>
            addImage("testPicture2","pictureMap2","graficos.php?grafico=SEG4&ImageMap=get");
        </script>
<!---------------------------------- SEG4 -------------------------------------------->
        <hr style='page-break-before: always;height: px !important;'><?php }?>


<!---------------------------------- ranking -------------------------------------------->
        <br>
        <div class='tituloGrafico'>
            <?php echo $arrGraficos['ranking']['titulo']; ?>
        </div>
        <br>

        <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'ranking','salida'=>'img','ident'=>$ident)); ?>" id="testPicture5" alt=""  grafico='ranking' class="pChartPicture"/>

        <br><br>
        <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
            <tr>
                <td align='justify'>
                    <?php echo $arrGraficos['ranking']['descripcion']; ?>
                </td>
            </tr>
        </table>
        <br>
        <script>
            addImage("testPicture5","pictureMap5","graficos.php?grafico=ranking&ImageMap=get");
        </script>
<!---------------------------------- ranking -------------------------------------------->
        <hr style='page-break-before: always;height: px !important;'>

        <!---------------------------------- INV -------------------------------------------->
      <br>
		<div class='tituloGrafico'>
        <?php echo $arrGraficos['INV']['titulo']; ?>
        </div>
      <br>      

        <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'INV','salida'=>'img','ident'=>$ident)); ?>" id="testPicture6" alt=""  grafico='INV' class="pChartPicture"/>      
      <br>       
      <br>      
<hr style='page-break-before: always;height: px !important;'>        
      <?php
        echo obtenerTablaInventario($_SESSION['id_cliente']);
      ?>
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico  bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['INV']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>         
      
      <script>
        addImage("testPicture6","pictureMap6","graficos.php?grafico=INV&ImageMap=get");
      </script>
<!---------------------------------- INV -------------------------------------------->      
<hr style='page-break-before: always;height: px !important;'>
<!---------------------------------- PR -------------------------------------------->
      <br>
        <div class='tituloGrafico'>
        <?php echo $arrGraficos['PR']['titulo']; ?>
        </div>
      <br>      

      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'PR','salida'=>'img','ident'=>$ident)); ?>" id="testPicture5" alt=""  grafico='PR' class="pChartPicture"/>
      
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['PR']['descripcion']; ?>
      	  </td>
      	</tr>
      </table>
      <br>         
      <script>
        addImage("testPicture5","pictureMap5","graficos.php?grafico=PR&ImageMap=get");
      </script>
<!---------------------------------- PR -------------------------------------------->      
<hr style='page-break-before: always;height: px !important;'>      
<!---------------------------------- BS -------------------------------------------->
      <div align='center' style=''>
      <?php
		echo obtenerBalanceSocial($_SESSION['id_cliente']);
      ?>   
      </div>

	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td align='justify'>
			<?php echo $arrGraficos['BS']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>      
<!---------------------------------- BS -------------------------------------------->
    <br><br><br><br>
         
    </div>
    <div role="tabpanel" class="tab-pane textoCentrado" id="gPvpi" align='center'>   	
     <!-- dsg --> 	
     <!-- dsv -->
     <!-- pvpi -->
     <!-- tendencia -->     
     <div class='panel panel-default'>
     	<div class='tablaRadius1'>
     	  <br>
     	  <form action='graficos_cr.php' name='form_graficos' id='form_graficos' method="POST" target='graficosCr'>
		  <input type='hidden' name='id_cliente' value='<?php $_SESSION['id_cliente']; ?>'>
		  <input type='hidden' name='identificador' value='<?php echo $ident; ?>'>		  
		  
		  <input name='id_select' id='id_select' type='hidden' value=''>
              <div id="cargaFiltros"><i class="fa fa-spinner fa-spin fa-5x" ></i> <br> <h3>Cargando. Espere un momento</h3></div>

     	  <table align='center' id='tablaSeleccionCr' style="display:none;">
              <script>
                  $(document).ready(function(){

                      window.onload=cerrar;
                      function cerrar(){
                          $("#cargaFiltros").fadeOut(50);
                          $("#tablaSeleccionCr").fadeIn(200);
                          //$("#carga").animate({"opacity":"0"},200,function(){$("#carga").css("display","none");});

                      }
                      $("#carga").click(function(){cerrar();});

                  });</script>
     		<tr>
			
<?php

	  $rowr = array(); 
	  $arrSeleccionados = array();
	
	if(strlen($edit_rut)>0){
	  //si viene un rut desde el usuario
	  $usuario 				 = getRun($edit_rut);	
	  $arrResult 			 = DB_CR_traerCrUsuario($conexion,$usuario);
	  $rowr['id_cr'] 		 = $arrResult['id_cr'];		
	  $rowr['id_cr_padre']   = $arrResult['id_cr_padre'];	  
	  $rowr['nivel'] 		 = $arrResult['nivel'];	  	  			

	  if(is_null($rowr["id_cr_padre"])){
	    //solo tengo que seleccionar el primero
	    $arrSeleccionados[] = NULL;
		$arrSeleccionados[] = $rowr["id_cr"];
	  } else {
	    //obtener todos los padres hasta llegar al 1
	    $nivelNodo = intval($rowr["nivel"]);
	    $idNodo = $rowr["id_cr"];	  
	    for($i=$nivelNodo;$i>=1;$i--){
	  	  $idPadre = DB_CR_obtenerPadre($conexion,$idNodo);
	      $arrSeleccionados[$i-1] = $idPadre;
	      $idNodo = $idPadre;
	      if(is_null($idNodo))
	        break;	     	
	    }	
	    $arrSeleccionados[$nivelNodo] = $rowr["id_cr"];	  
	  }			
	}
//var_dump($arrSeleccionados);
	
	//var_dump($arrSeleccionados);
	$iNiveles = DB_CR_cantidadNiveles($idCliente,$conexion);

	   	  
	for($i=0;$i<$iNiveles;$i++){
	  $sqlp="SELECT * FROM ".TBL_ESTRUCTURA_CR." where 1=1 and id_cliente=".$idCliente." AND id_nivel=".($i+1)." order by nombre ";		
	  $rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
	  if($i==0){
	    echo "<td>".DB_CR_nombreNivel($idCliente,$conexion,($i+1)).":</td><td><select class='seleccioncr' id='sel_cr_".($i)."' name='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)' ";
		echo ">";
	    echo " <option value='0'>Seleccione</option>";
	    while($rowr=mysql_fetch_array($rs1)){
	  	  echo "<option value='".$rowr['id']."' ";
		  if(isset($arrSeleccionados[($i+1)]) && !empty($arrSeleccionados[($i+1)])){ if($arrSeleccionados[($i+1)]==$rowr['id']){ echo "selected";  }}
	  	  echo ">";
		  echo $rowr['nombre'];	
	  	  echo "</option>";
	    }
	    echo " </select></td>";
	  }else{
		if(isset($arrSeleccionados[($i)]) && !empty($arrSeleccionados[($i)])){
	      $sqlp="SELECT * FROM ".TBL_ESTRUCTURA_CR." where 1=1 and id_cliente=".$idCliente." AND id_padre=".$arrSeleccionados[($i)]." order by nombre ";			
	  	  $rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
	      echo "<td>".DB_CR_nombreNivel($idCliente,$conexion,($i+1)).": </td><td><select class='seleccioncr' id='sel_cr_".($i)."' name='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)' ";
		  echo ">";
	      echo " <option value='0'>Seleccione</option>";
	      while($rowr=mysql_fetch_array($rs1)){
	  	    echo "<option value='".$rowr['id']."' ";
		    if(isset($arrSeleccionados[($i+1)]) && !empty($arrSeleccionados[($i+1)])){ if($arrSeleccionados[($i+1)]==$rowr['id']){ echo "selected";  }}
	  	    echo ">";
		    echo $rowr['nombre'];
	  	    echo "</option>";			
	      }			
	      echo " </select></td>";		  
		}else{
	      echo " <td id='sel_cr_".($i)."Label'>".DB_CR_nombreNivel($idCliente,$conexion,($i+1)).": </td><td id='sel_cr_".($i)."Ver'><select id='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)'  class='seleccioncr' >";
	      echo " <option value='0'>Seleccione</option>";	    
	      echo " </select></td>";			
		}	
	  }
	}

  	?>	
	
     			  </tr>

     			  <tr>
     			   <div >
     			     <td id="estamentoVerLabel">
     			      Estamentoo
     			    </td>
     			    <td id="estamentoVer">
						<select name='id_estamento' id='id_estamento'>
						  <option value='0'>Seleccione</option>     			    	
     			 	    <?php
						  $arrEstamento = DB_ESTAMENTO_obtenerTodosEstamentos($_SESSION['id_cliente']);
						  for($i=0;$i<count($arrEstamento);$i++){
						    echo "<option value='".$arrEstamento[$i]['id_estamento']."'>";
						    echo $arrEstamento[$i]['nombre_estamento'];
						    echo "</option>";										  	
						  }
     			 	    ?>
						</select>     			 	   	
     			    </td>

                   </div>

     			    <td id="antiguedadVerLabel">
     			      Antiguedad	
     			    </td>
     			    <td id="antiguedadVer">
					  <select name='id_antiguedad' id='id_antiguedad' >
						<option value='0'>Seleccione</option>
 					    <?php 
						  $arrAntiguedad = DB_ANTIGUEDAD_obtenerTodasAntiguedades($_SESSION['id_cliente']);
						  for($i=0;$i<count($arrAntiguedad);$i++){
						    echo "<option value='".$arrAntiguedad[$i]['id_antiguedad']."'>";
						    echo $arrAntiguedad[$i]['nombre_antiguedad'];
						    echo "</option>";							
						  }
 					    ?>    	
					  </select> 					  		      	
     			    </td>     			    
     			    <td id="calidadJuridicaVerLabel">
     			      Calidad Juridica	   			    
     			    </td>     			    
     			    <td id="calidadJuridicaVer">
					  <select name='id_cjuridica' id='id_cjuridica' >
						<option value='0'>Seleccione</option>
 					    <?php 
						  $arrCjuridica = DB_CJURIDICA_obtenerTodasCjuridica($_SESSION['id_cliente']);
						  for($i=0;$i<count($arrCjuridica);$i++){
						    echo "<option value='".$arrCjuridica[$i]['id_calidad_juridica']."'>";
						    echo $arrCjuridica[$i]['nombre_calidad_juridica'];
						    echo "</option>";
						  }
 					    ?>
					  </select>
     			    </td>     			    
     			  </tr>	     			  
     			  <tr>     			  
     			    <td align='center'  id="sexoVerLabel">
     				  Sexo
     			    </td>
     			    <td align='center' id="sexoVer">
					  <select name='id_sexo' id='id_sexo' >
						<option value='0'>Seleccione</option>
						<option value='M'>Masculino</option>
						<option value='F'>Femenino</option>						
					  </select>
     			    </td>
     			    <td align='center'>
     				  
     			    </td>
     			    <td align='center'>
     				  
     			    </td>
     			    <td align='center'>
     				  
     			    </td>
     			    <td align='center'>
     				  
     			    </td>     			         			         			         			         			    
     			  </tr>
     			  <tr>     			  
     			    <td colspan='6' align='center'>
     				  <button type='button' class='btn btn-custom' onclick='enviar()'>Mostrar Gr&aacute;ficos</button>
     			    </td>
     			  </tr>
     			</table>

     		</form>

            <script>
                $(document).ready(function(){
                    //$('#datosDinamicos').empty(); //Limpando a tabela
                    //$('#estamento').bootstrapToggle('off');
                    $.ajax({
                        type:'post',		//Definimos o método HTTP usado
                        dataType: 'json',	//Definimos o tipo de retorno
                        url: 'actualizaGraficosDinamicos.php',//Definindo o arquivo onde serão buscados os dados
                        success: function(dados){
                            for(var i=0;dados.length>i;i++){
                                //Adicionando registros retornados na tabela
                                var estamento = dados[i].estamento;
                                var sexo = dados[i].sexo;
                                var antiguedad = dados[i].antiguedad;
                                var calidadJuridica = dados[i].calidadJuridica;
                                var centroCosto = dados[i].centroCosto;
                                var unidadDesempeno = dados[i].unidadDesempeno;

                                //$('#sexo').bootstrapToggle(dados[i].sexo);
                                //$('#antiguedad').bootstrapToggle(dados[i].antiguedad);
                                //$('#calidadJuridica').bootstrapToggle(dados[i].calidadJuridica);
                                //$('#datosDinamicos').append('<tr><td>'+dados[i].estamento+'</td><td>'+dados[i].sexo+'</td></tr>');
                            }

                            if(estamento == "off")
                            {
                                $('#estamentoVerLabel').css('display','none');
                                $('#estamentoVer').css('display','none');
                            }
                            if(sexo == "off")
                            {
                                $('#sexoVerLabel').css('display','none');
                                $('#sexoVer').css('display','none');
                            }
                            if(antiguedad == "off")
                            {
                                $('#antiguedadVerLabel').css('display','none');
                                $('#antiguedadVer').css('display','none');
                            }
                            if(calidadJuridica == "off")
                            {
                                $('#calidadJuridicaVerLabel').css('display','none');
                                $('#calidadJuridicaVer').css('display','none');
                            }
                            if(centroCosto == "off")
                            {
                                $('#sel_cr_1Label').css('display','none');
                                $('#sel_cr_1Ver').css('display','none');
                            }
                            if(unidadDesempeno == "off")
                            {
                                $('#sel_cr_2Label').css('display','none');
                                $('#sel_cr_2Ver').css('display','none');
                            }



                        }
                    });
                });
            </script>
     		
     		
     	</div>
     	
     	<div class=''>
     		<iframe src='' name='graficosCr' id='graficosCr' style='width:800px !important;height:3200px !important;border-width:0px !important;overflow:auto !important;  '>
     			
     			
     		</iframe>
     		
     	</div>     	
     	
     </div>
    </div>
    <div role="tabpanel" class="tab-pane textoCentrado" id="gIstas" align='center'>


      <table align='center'>
      	<tr>
      	  <td>
      <button type='button' class='btn btn-primary printBtn2'  data-toggle="tooltip" title="Imprimir"  ><i class="fa fa-print"></i></button>      	  	
      	  </td>
      	  <td>
	  <form name='formPdf' method='POST' action="grafico/exportarPdf.php" target='_blank'>
	    <input name='id_cliente' type='hidden' value='<?php echo $_SESSION['id_cliente'];?>'>    
	    <input name='identificador' id='identificador' type='hidden' value='<?php echo $ident;?>'>	    
	    <input name='tipo_grafico' id='tipo_grafico' type='hidden' value='ISTA'>	    
	    <button type='submit' class='btn btn-danger '  data-toggle="tooltip" title="PDF"  ><i class="fa fa-file-pdf-o"></i></button>	  	
	  </form>      	  	
      	  </td>
      	  <td>
	  <form name='formWord' method='POST' action="grafico/exportarWord.php" target='_blank'>   
	    <input name='id_cliente' type='hidden' value='<?php echo $_SESSION['id_cliente'];?>'>
	    <input name='identificador' id='identificador' type='hidden' value='<?php echo $ident;?>'>
		<input name='tipo_grafico' id='tipo_grafico' type='hidden' value='ISTA'>
	    <button type='submit' class='btn btn-info '  data-toggle="tooltip" title="Word"  ><i class="fa fa-file-word-o"></i></button>    
	  </form>  	  	
      	  </td>      	        	  	
      	</tr>      	
      </table>
      
            <br>
        <div class='tituloGrafico'>
						<?php echo $arrGraficos['ISTA']['titulo']; ?>
        </div>
      <br>      

      <img src="<?php echo generarString(array('id_cliente'=>$_SESSION['id_cliente'],'grafico'=>'ISTA','salida'=>'img','ident'=>$ident)); ?>" id="testPicture9" alt=""  grafico='ISTA' class="pChartPicture"/>
      
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td>
      	  				<?php echo $arrGraficos['ISTA']['descripcion']; ?>
      	  </td>
      	</tr>
      </table>
      <br>         
      <script>
        addImage("testPicture9","pictureMap9","graficos.php?grafico=ISTA&ImageMap=get");
      </script>
      
    </div>
  </div>

  </div>

  <div class='footer'>
    <span class="MsoNormal " align=center style='text-align:center'><span
  style=''>Copyright @ 2015, <a
  href="http://www.psicus.cl" target="_blank"><span style='color:white'>PSICUS
  Profesionales,</span></a> Resoluci&oacute;n Recomendada:1024x768 </span>
    </span>   	
  </div>  	
</div>
  <script>
	$(document).ready(function(){
		
	  $('#tabGrafico a').click(function(e){
        e.preventDefault();
        //alert('aa');
        $(this).tab('show');
      });
      
	  $('.printBtn').bind('click',function(){
	    //var thePopup = window.open( '', "Customer Listing", "menubar=0,location=0,height=700,width=700" );
	    //$('#popup-content').clone().appendTo( thePopup.document.body );
	    //$('#gDsg').clone().appendTo( thePopup.document.body );
	    //thePopup.print();
	    PrintElem('gDsg');
	  });      
		
	  $('.printBtn2').bind('click',function(){
	    //var thePopup = window.open( '', "Customer Listing", "menubar=0,location=0,height=700,width=700" );
	    //$('#popup-content').clone().appendTo( thePopup.document.body );
	    //$('#gDsg').clone().appendTo( thePopup.document.body );
	    //thePopup.print();	
	    PrintElem('gIstas');
	  });
		
      $('[data-toggle="tooltip"]').tooltip(); 


	  $('#clock').countdown('<?php echo $fechaTermino; ?>', function(event) {
		//$(this).html(event.strftime('%D dias %H:%M:%S'));
		//$(this).html(event.strftime('%D dias'));
	  });
		
	});

		
 

function PrintElem(elem)
{
	$(".pChartPicture").each(function(index){
	  var grafico = $(this).attr("grafico");
	  var id = $("#identificador").val();
	  $(this).attr("src","<?php echo HOME_PATH; ?>grafico/"+grafico+"_"+id+".png");	
	})
    Popup($("#"+elem).html());
}

function Popup(data) 
{
    var mywindow = window.open('', 'print_div', 'height=400,width=600');
    mywindow.document.write('<html><head><title>Impresion</title>');
    mywindow.document.write('<link href="<?php echo HOME_PATH; ?>include/bootstrap/css/bootstrap.min.css" rel="stylesheet">');
    mywindow.document.write('<link rel="stylesheet" type="text/css" href="<?php echo HOME_PATH; ?>include/custom.css" />');
    mywindow.document.write('<style>.btn{ display:none; }</style>');    
    mywindow.document.write('</head><body >');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');
    mywindow.document.close();
    
	setTimeout(function(){
	  mywindow.print();
	}, 2000);
    
    return true;
}


</script>

</body>

</html>
