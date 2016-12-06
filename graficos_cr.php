<?php
  session_start();
  require_once("include/dbconection.php");
  require_once("include/funciones.php");  
  require_once("include/definiciones.php");
  
  $idSelect = $_POST['id_select'];
  //var_dump($idSelect);
  //$idSelect = 457;
  
  $idCliente 	= isset($_SESSION['id_cliente'])?$_SESSION['id_cliente']:$_POST['id_cliente'];
  $ident 		= isset($_POST['identificador'])?$_POST['identificador']:$_GET['identificador'];  
  
  $idEstamento  = isset($_POST['id_estamento'])?$_POST['id_estamento']:$_GET['id_estamento'];  
  $idAntiguedad = isset($_POST['id_antiguedad'])?$_POST['id_antiguedad']:$_GET['id_antiguedad'];  

  $idSexo 		= isset($_POST['id_sexo'])?$_POST['id_sexo']:$_GET['id_sexo'];
  $idCjuridica 	= isset($_POST['id_cjuridica'])?$_POST['id_cjuridica']:$_GET['id_cjuridica'];  
  
?>
<link href="include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="include/fontawesome/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="include/custom.css" />
<script language="JavaScript"  src="include/extras/imagemap.js"></script>
<script language="JavaScript"  src="include/jquery.min.js"></script>
<script language="JavaScript"  src="include/bootstrap/js/bootstrap.min.js"></script>

      <table align='center'>
      	<tr>
      	  <td style='vertical-align: top;'>
      	  	<br>
      <button type='button' class='btn btn-primary printBtn'  data-toggle="tooltip" title="Imprimir"  ><i class="fa fa-print"></i></button>      	  	
      	  </td>
      	  <td>
      	  	<br>
	  <form name='formPdf' method='POST' action="grafico/exportarPdf.php" target='_blank'>
	    <input name='id_cliente' type='hidden' value='<?php echo $idCliente;?>'>
	    <input name='id_select' type='hidden' value='<?php echo $idSelect;?>'>
	    <input name='identificador' id='identificador' type='hidden' value='<?php echo $ident;?>'>	    	    
	    <button type='submit' class='btn btn-danger '  data-toggle="tooltip" title="PDF"  ><i class="fa fa-file-pdf-o"></i>
</button>	  	
	  </form>      	  	
      	  </td>
      	  <td>
      	  	<br>
	  <form name='formWord' method='POST' action="grafico/exportarWord.php" target='_blank'>   
	    <input name='id_cliente' type='hidden' value='<?php echo $idCliente;?>'>
	    <input name='id_select' type='hidden' value='<?php echo $idSelect;?>'>
	    <input name='identificador' id='identificador' type='hidden' value='<?php echo $ident;?>'>	    
	  <button type='submit' class='btn btn-info '  data-toggle="tooltip" title="Word"  ><i class="fa fa-file-word-o"></i>
</button>    
	  </form>  	  	
      	  </td>        	  
      	</tr>
     	      	
      </table>

	<br>
	<br>
	<br>		

  <?php
     				$arrGraficos 		  = DB_PARAMETROS_obtenerInfoGraficos();
  ?>

	  <div id='capaImpresion'>
<br><div class='tituloGrafico'><?php echo $arrGraficos['DSG']['titulo']; ?></div><br>
      <img src="<?php echo generarString(array('id_cliente'=>$idCliente,'grafico'=>'DSG','salida'=>'img','ident'=>$ident,"select"=>$idSelect,'id_estamento'=>$idEstamento,'id_antiguedad'=>$idAntiguedad,'id_cjuridica'=>$idCjuridica,'id_sexo'=>$idSexo)); ?>" id="testPicture1" alt="" grafico="DSG" class="pChartPicture" onerror="imgError(this);" align='center'/>
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td>
        <?php echo $arrGraficos['DSG']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>  
<hr style='page-break-before: always;height: px !important;'>          
      <script>
        addImage("testPicture1","pictureMap1","<?php echo generarString(array('id_cliente'=>$idCliente,'grafico'=>'DSG','salida'=>'img','ident'=>$ident,"select"=>$idSelect,'id_estamento'=>$idEstamento,'id_antiguedad'=>$idAntiguedad,'id_cjuridica'=>$idCjuridica,'id_sexo'=>$idSexo,'ImageMap'=>'get')); ?>");
      </script>

<br><div class='tituloGrafico'><?php echo $arrGraficos['PVPI']['titulo']; ?></div><br>
      <img src="<?php echo generarString(array('id_cliente'=>$idCliente,'grafico'=>'PVPI','salida'=>'img','ident'=>$ident,"select"=>$idSelect,'id_estamento'=>$idEstamento,'id_antiguedad'=>$idAntiguedad,'id_cjuridica'=>$idCjuridica,'id_sexo'=>$idSexo)); ?>" id="testPicture2" alt="" grafico="PVPI" class="pChartPicture" onerror="imgError(this);"/>
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td>
        <?php echo $arrGraficos['PVPI']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>    
<hr style='page-break-before: always;height: px !important;'>             
      <script>
        addImage("testPicture2","pictureMap2","<?php echo generarString(array('id_cliente'=>$idCliente,'grafico'=>'PVPI','salida'=>'img','ident'=>$ident,"select"=>$idSelect,'id_estamento'=>$idEstamento,'id_antiguedad'=>$idAntiguedad,'id_cjuridica'=>$idCjuridica,'id_sexo'=>$idSexo,'ImageMap'=>'get')); ?>");
      </script>   
<br><div class='tituloGrafico'><?php echo $arrGraficos['DSV']['titulo']; ?></div><br>
      <img src="<?php echo generarString(array('id_cliente'=>$idCliente,'grafico'=>'DSV','salida'=>'img','ident'=>$ident,"select"=>$idSelect,'id_estamento'=>$idEstamento,'id_antiguedad'=>$idAntiguedad,'id_cjuridica'=>$idCjuridica,'id_sexo'=>$idSexo)); ?>" id="testPicture3" alt=""  grafico="DSV" class="pChartPicture" onerror="imgError(this);"/>
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td>
          <?php echo $arrGraficos['DSV']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>         
<hr style='page-break-before: always;height: px !important;'>      
      <script>
        addImage("testPicture3","pictureMap3","<?php echo generarString(array('id_cliente'=>$idCliente,'grafico'=>'DSV','salida'=>'img','ident'=>$ident,"select"=>$idSelect,'id_estamento'=>$idEstamento,'id_antiguedad'=>$idAntiguedad,'id_cjuridica'=>$idCjuridica,'id_sexo'=>$idSexo,'ImageMap'=>'get')); ?>");
      </script>  
<br><div class='tituloGrafico'><?php echo $arrGraficos['TC']['titulo']; ?></div><br>	
      <img src="<?php echo generarString(array('id_cliente'=>$idCliente,'grafico'=>'TC','salida'=>'img','ident'=>$ident,"select"=>$idSelect,'id_estamento'=>$idEstamento,'id_antiguedad'=>$idAntiguedad,'id_cjuridica'=>$idCjuridica,'id_sexo'=>$idSexo)); ?>" id="testPicture4" alt=""  grafico="TC" class="pChartPicture" onerror="imgError(this);"/>
	  <br><br>
      <table align='center' class=' tablaDescripcionGrafico bordeNegroSolido' cellpadding="10">
      	<tr>
      	  <td>
            <?php echo $arrGraficos['TC']['descripcion']; ?>
      	  </td>	
      	</tr>
      </table>
      <br>
      
	  </div>
<hr style='page-break-before: always;height: px !important;'>	                   
      <script>
        addImage("testPicture4","pictureMap4","<?php echo generarString(array('id_cliente'=>$idCliente,'grafico'=>'TC','salida'=>'img','ident'=>$ident,"select"=>$idSelect,'id_estamento'=>$idEstamento,'id_antiguedad'=>$idAntiguedad,'id_cjuridica'=>$idCjuridica,'id_sexo'=>$idSexo,'ImageMap'=>'get')); ?>");
      </script>


  <script>
	$(document).ready(function(){
	  $('#tabGrafico a').click(function(e){
        e.preventDefault();
        //alert('aa');
        $(this).tab('show');
      })
      
		$('.printBtn').bind('click',function() {
		    //var thePopup = window.open( '', "Customer Listing", "menubar=0,location=0,height=700,width=700" );
		    //$('#popup-content').clone().appendTo( thePopup.document.body );
		    //$('#gDsg').clone().appendTo( thePopup.document.body );
		    //thePopup.print();
		    PrintElem('capaImpresion');
		});      

      $('[data-toggle="tooltip"]').tooltip({placement:'bottom'});
      		
  	})




  

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

function imgError(image) {
    image.onerror = "";
    image.src = "<?php echo HOME_PATH; ?>images/error2.png";
    return true;
}


</script>

