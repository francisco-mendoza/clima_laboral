<?php
	error_reporting(0);
	//---------------------------------------------------------------------------------------------------------------- 
	// Verificamos el uso de session, sino lo mandamos a logearse
	session_start();  
	if ($_SESSION['usuario']==""){echo "session expirada! vuelva a ingresar<br><br><a href='index.htm'>Inicio</a>";die();}   
	//----------------------------------------------------------------------------------------------------------------
	// Cargamos el acceso a la base de datos
	include_once("include/dbconection.php");
    include_once("include/definiciones.php");  	
	
	$rut     			 = $_SESSION["usuario"];
	//if ($rut==''){$rut=$_SESSION['usuario'];}

	$acceso_formulario 	 = $_GET["acceso_formulario"];
	$v_password			 = $_GET["v_password"];
	$pregunta1=array();
	$pregunta2=array();	
	// ----------------------------------------------------------------------------
	// Antecedentes del funcionarios
	// ----------------------------------------------------------------------------
	$arrParametros = array("rut"=>$rut);
	$arrResultados = DB_RESULT_obtenerResultados($arrParametros,$conexion);
	
	$p=0;
	$flagCompletado = 0;
	foreach($arrResultados as $row)
 	{
 		$p++;
		$num_pregunta	= $row["numero_pregunta"];
		$_SESSION['Completado'] = intval($row["completado"]);		
		$v_rut         =  $row["rut"];
		$v_unidad		=  $row["unidad"];
		$v_cjuridica	=  $row["cjuridica"];
		$v_funcion 		=  $row["funcion"];
		$v_antiguedad	=  $row["antiguedad"];
		
   		$pregunta1[$num_pregunta] = $row["pregunta_unidad"];
   		$pregunta2[$num_pregunta] = $row["pregunta_instit"];
   		
		$nivel1			=  $row["nivel_1"];
		$nivel2			=  $row["nivel_2"];
		$nivel3			=  $row["nivel_3"];
		$nivel4			=  $row["nivel_4"];
		$nivel5			=  $row["nivel_5"];
		$nivel6			=  $row["nivel_6"];
		$nivel7			=  $row["nivel_7"];
		$nivel8			=  $row["nivel_8"];
		$nivel9			=  $row["nivel_9"];
		$nivel10			=  $row["nivel_10"];
		$nivel11			=  $row["nivel_11"];
		$nivel12			=  $row["nivel_12"];
		$nivel13			=  $row["nivel_13"];
		$nivel13			=  $row["nivel_14"];
		if ($p==1)
		{
			$datos = $row["nivel_1"].":".$row["nivel_2"] .":".$row["nivel_3"] .":".$row["nivel_4"] .":".$row["nivel_5"] .":".$row["nivel_6"] .":".$row["nivel_7"] .":".$row["nivel_8"] .":".$row["nivel_9"] .":".$row["nivel_10"] .":".$row["nivel_11"] .":".$row["nivel_12"] .":".$row["nivel_13"];	
		}	
		$fortalezas		=  $row["fortalezas"];
		$debilidades	=  $row["debilidades"];
		$propo1			=  $row["proposicion1"];
		$propo2			=  $row["proposicion2"];	
	}
	$nivel = explode(":", $datos);
	
   

  include("headerHtml.php");
?>

<script type="text/javascript">

var maxNivel = parseInt('<?php echo DB_CR_cantidadNiveles($_SESSION['id_cliente'],$conexion); ?>',10);

function validarSegmentacion(){
  var contador = 0;
  $(".opSegmentacion").each(function(index){   
   	if($(this).is(':checked')){
   	  contador++;		
   	}         
  });	
  if(contador==0){
  	alert('Por favor seleccione al menos \n una de las opciones de segmentaci\u00F3n');
  	return false;
  }	
  return true;
}

function validarSeleccionCr(){
  for(var i=0;i<maxNivel;i++){
	var valor = $("#sel_cr_"+i).val();
	if(valor=='0'){
	  alert("Seleccionar Centro de Responsabilidad en el nivel "+(i+1));
	  return false;	
	}  	
  }	
}

function validaImportancia(){
  //alert("AAA"); 
  var ob1;
	var ob2;
	var suma;
	var suma=0;
	// validamos la pregunta 61
	$(".lineaImportancia").removeClass('alert-danger');



	for (var i=1;i<=<?php echo DB_NEGOCIO_cantidadDimensiones($_SESSION['id_cliente'],$conexion); ?>;i++)
	{
	  ob1 =eval("document.se.c_nivel_" + i);
	  for (var j=2;j<=<?php echo DB_NEGOCIO_cantidadDimensiones($_SESSION['id_cliente'],$conexion); ?>;j++)
	  {
		if (i!=j)
		{
	      ob2 =eval("document.se.c_nivel_" + j);
	      if  (ob1.value>0){
	      	if (ob2.value == ob1.value){
			  alert("Estimado Usuario\nno pueden haber 2 o mas opciones con el mismo valor en el Item vii, hay conflicto entre las respuestas Nº "+i+" y "+j+"");
	      	  $(".importancia"+i).addClass('alert-danger');
	      	  $(".importancia"+j).addClass('alert-danger');	      	  
	      	  return false;
	      	  suma++;
	      	}
	      }
		}
	  } //for j
	    		  
        if (suma>0)
		{
		 //alert("Estimado Usuario\nno pueden haber 2 o mas opciones con el mismo valor en el Item vii");
		  //$(".oculto.errorItemImportancia").show();
		 suma=0;
		 return false;
		}else{
		  //$(".oculto.errorItemImportancia").hide();		  			
		}
	}
	// fin validacion pregunta 61	
}


function valida61(){
  //alert("AAA"); 
  var ob1;
	var ob2;
	var suma;
	var suma=0;
	// validamos la pregunta 61
	for (var i=1;i<=<?php echo DB_NEGOCIO_cantidadDimensiones($_SESSION['id_cliente'],$conexion); ?>;i++)
	{
	  ob1 =eval("document.se.c_nivel_" + i);
	  for (var j=2;j<=<?php echo DB_NEGOCIO_cantidadDimensiones($_SESSION['id_cliente'],$conexion); ?>;j++)
	  {
		if (i!=j)
		{
	    ob2 =eval("document.se.c_nivel_" + j);
	    if  (ob1.value>0){ if (ob2.value == ob1.value){suma++;}}
		}
	  } //for j
        if (suma>0)
		{
		 alert("Estimado Usuario\nno pueden haber 2 o mas opciones con el mismo valor en la pregunta 61!");
		 suma=0;
		 return;
		}
	}
	// fin validacion pregunta 61	
}	

function toggleBotonGuardar(op){
  if(op==0){
	if($("#BotonGrabarFinal").is(":visible")){
	  $("#BotonGrabarFinal").hide();
	  $("#loaderBotonGrabarFinal").show();	  		
	} else {
	  $("#BotonGrabarFinal").show();
	  $("#loaderBotonGrabarFinal").hide();	  		
	}		
  } else{
	if(op==1){
	  if($("#BotonGrabarParcial").is(":visible")){
	    $("#BotonGrabarParcial").hide();
	    $("#loaderBotonGrabarParcial").show();	  		
	  } else {
	    $("#BotonGrabarParcial").show();
	    $("#loaderBotonGrabarParcial").hide();	  		
	  }
	}	
  }
}

function cmdValida(op){
   toggleBotonGuardar(op);
	
	
	
	var selecciono=0;
	var mcompetencia ;
	var elem;
	var obj1;
	var obj2;
	var esigual;
	var esigual=0;
	document.se.popup.value=op;
	if(validarSegmentacion()==false){
      toggleBotonGuardar(op);		
	  return;		
	}
	if(validarSeleccionCr()==false){
      toggleBotonGuardar(op);		
	  return;		
	} else {
	  //obtener el id de ultimo nivel y guardarlo
	  var nivel = maxNivel-1;
	  $("#id_select").val($("#sel_cr_"+nivel).val());  
	}
	if (op==0)
	{
	  $(".lineaCuestionario").removeClass('alert-danger');		
	// validamdos que conteste las 60 preguntas del punto III
	  for (var i=1; i<= 63; i++ ){
		obj1 = "document.se.c_p" + i;
		//alert(obj1);
		obj2 = eval(obj1);
   		for(var j=1;j<=5;j++){
   		  if($('.c_p'+i+'_'+j).is(':checked')){ 
			selecciono++;
   		  }  			
   		}		
		if ( selecciono == 0 ){

		  $(".cuestionario"+i).addClass('alert-danger');			
		  alert( "Estimado Usuario, La pregunta Nro " + i +  " no fue contestada" );
		  toggleBotonGuardar(op);
		  return;
		}
		selecciono = 0;
	  }
	}

	// validamos la pregunta 61
	$(".lineaImportancia").removeClass('alert-danger');	
	for (var i=1;i<=<?php echo DB_NEGOCIO_cantidadDimensiones($_SESSION['id_cliente'],$conexion); ?>;i++)
	{
	  obj1 =eval("document.se.c_nivel_" + i);  
	  
		  for (var j=2;j<=<?php echo DB_NEGOCIO_cantidadDimensiones($_SESSION['id_cliente'],$conexion); ?>;j++)
		  {
			if (i!=j)
			{
		    obj2 =eval("document.se.c_nivel_" + j); 
	          if  (obj1.value>0){
	            if (obj2.value == obj1.value){
	          	  esigual++;
				 alert("Estimado Usuario\nno pueden haber 2 o mas opciones con el mismo valor en el item VII");
	      	     $(".importancia"+i).addClass('alert-danger');
	      	     $(".importancia"+j).addClass('alert-danger');
	      	     toggleBotonGuardar(op);		          	  
	          	 return; 
	          	}
	          }
			}
		  } //for j
	      if (esigual>0)
		  {	
			alert("Estimado Usuario\nno pueden haber 2 o mas opciones con el mismo valor en el item VII");
		 	//$(".oculto.errorItemImportancia").show();			 
		    esigual=0;
		    toggleBotonGuardar(op);
		    return;
		  }else{
		    //$(".oculto.errorItemImportancia").hide();				
		  }
	}
	if (op==0){
	  for (var i=1;i<=<?php echo DB_NEGOCIO_cantidadDimensiones($_SESSION['id_cliente'],$conexion); ?>;i++)
	  {
	  	obj1 =eval("document.se.c_nivel_" + i);
	    if(obj1.value==""){
		  alert("Estimado Usuario,\n debe seleccionar una opcion en la pregunta "+i+" del Item VII ");
	      $(".importancia"+i).addClass('alert-danger');
   		  toggleBotonGuardar(op);	      		  	
		  return;      	
	    }
	  }			
	}
	// fin validacion pregunta 61

	if ( document.se.c_fortalezas.value.length > 4000 ){
		alert(" Estimado Usuario, la cantidad de caracteres escritos en las fortalezas sobrepasa el limite maximo de 4000.")
		document.se.c_form_objetivo.select();
		toggleBotonGuardar(op);
		return;
	}
	if ( document.se.c_debilidades.value.length > 4000 ){
		alert(" Estimado Usuario, la cantidad de caracteres escritos en las debilidades sobrepasa el limite maximo de 4000.")
		document.se.c_form_objetivo.select();
		toggleBotonGuardar(op);
		return;
	}
	//alert('llegamos al submit');
	document.se.submit();
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}			

function cargarSelect(iNivel,valor){
  if(iNivel>=maxNivel)
    return;

    for(var i=iNivel;i<maxNivel;i++){
      $('#sel_cr_'+i)
         .find('option')
         .remove()
         .end();
      $('#sel_cr_'+i)
        .append($("<option></option>")
        .attr("value",'0')
        .text('Seleccione'));    	
    }
   	
  $.post("cargarSelect.php",{iNivel:iNivel,usuario:'<?php echo $_SESSION['rut']; ?>',padre:valor},function(result){
    //alert(result+iNivel);
    //alert(result);
    try{
      data = JSON.parse(result);
      $.each(data, function(key, value){   
       $('#sel_cr_'+iNivel)
         .append($("<option></option>")
         .attr("value",value['id'])
         .text(value['nombre']));
      });
      //$('#sel_cr_'+iNivel).css("width",$('#sel_cr_'+(iNivel-1)).outerWidth()+'px');      
    }catch (e){
      alert('excepcion:'+result);	
    } 
  }).error(function(xhr, textStatus, errorThrown){
    alert("error"+xhr.responseText);
  }); 
}
			
</script>

<body bgcolor="#FFFFFF"  background="images/bg.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/b_grabar_r.gif','images/b_grabar_solo_r.gif')">

<?php
  include_once('menuHtml.php');
?>

<form name="se" id='se' method="post" action="dpp_formulario_graba.php">
	
<!-- ENCABEZADO DE LA P�GINA -->

	
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
    //exit;
  } 
  //unset($_SESSION['msg']);
?>	
	
	<?php

 	$rowp=DB_NEGOCIO_textosPresentacion($_SESSION['id_cliente'],$conexion); 

 	if ($rowp['p61']==0){$p61=" class='oculto' ";$mg=" class='oculto' ";} else {$p61=' ';}
  	if ($rowp['p62']==0){$p62=" class='oculto' ";$mg=" ";} else {$p62=' ';}
   if ($rowp['p63']==0){$p63=" class='oculto' ";$mg=" ";} else {$p63=' ';}
	?> 


    <table width="777px" border="0"  align="center" cellpadding="0" cellspacing="0" bgcolor="#fafafa">
	  <tr>
	  	<td colspan='4'>
	  	  <div class='tablaRadius1 padding1'>

	  	    <table>
		      <tr>
		        <td colspan="4" align="center"><br>
		            <font color="#FF0000"><b><?php echo $rowp[0];?></b></font>
		        </td>
		      </tr>
		      <tr>
		      	<td colspan="4" align="center">&nbsp;</td>
		      </tr>
		      <tr>
		        <td colspan="4"><p align="center" style="line-height: 30px;"> <strong><font size="+1"><?php echo $rowp[1];?></font></strong></p>
		        </td>
		      </tr>
		      <tr>
		        <td colspan=4 align="center" valign="middle" class="Estilo5">&nbsp;</td>
		      </tr>
		      <tr>
		        <td colspan=4 class="Estilo5" align="justify"><?php echo $rowp[2];?></b></td>
		      </tr>	  	  	
	  	    </table>
	  	  	
	  	  </div>	
		
	  	</td>
	  </tr>	

      <tr><td colspan=4>&nbsp;</td></tr>
<!-- SECCION I EXTRAE EL DROP LIST DE LA BBDD --> 
<?php

	$arrParametros = array("rut"=>$_SESSION['rut']);
	$rowr=DB_RESULT_traerCr($arrParametros,$conexion);
	
	/* por reunion 24-06 se modifica para que no traiga desde el usuario */
	/*
	if($iCuantosResultados==0){
	  $usuario = 'rutse'];
	  $arrResult = DB_CR_traerCrUsuario($conexion,$usuario);
	  if(!is_null($arrResult['cr_usuario']) && !is_null($arrResult['id_cr'])){
	    $rowr['id_cr'] = $arrResult['id_cr'];		
	    $rowr['id_cr_padre'] = $arrResult['id_cr_padre'];	  
	    $rowr['nivel'] = $arrResult['nivel'];	  	  	
	  }
	}*/
	
	$arrSeleccionados = array();
	if(is_null($rowr["id_cr_padre"])){
	  //solo tengo que seleccionar el primero
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
	
	//var_dump($arrSeleccionados);
	$iNiveles = DB_CR_cantidadNiveles($_SESSION['id_cliente'],$conexion);
	echo $iNiveles;
	
	echo "<TR><td colspan='4'>&nbsp;</td></TR><TR><td colspan='4'>&nbsp;</td></TR>";	
	echo "<tr>";
	echo "<td colspan=4 align='center' valign='middle' class='Estilo5'>";

	echo "<div class='panel panel-default'>";
	
	echo "<table class='table table-bordered' border='1' cellpadding='0' cellspacing='0' width='776px' align='center'>";
	echo "<tr><td align='center' class='titulo_tabla_des' width='90%'>I. CENTRO DE RESPONSABILIDAD</td></tr>";
	echo "<tr><td align='center'>";
	echo "<input name='id_select' id='id_select' type='hidden' value=''>"; //Si se cambia a SELECT Dnámicos intervenir este hidden
	echo "<table>"; 	  
	echo "<tr>";	  
	for($i=0;$i<$iNiveles;$i++){

	  $arrNodos = DB_CR_obtenerNodosPorNivel($_SESSION['id_cliente'],($i+1),$conexion);
	  
	  if($i==0){
	    echo "<td>".DB_CR_nombreNivel($_SESSION['id_cliente'],$conexion,($i+1)).": <select class='seleccioncr' id='sel_cr_".($i)."' name='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)' ";
		echo ">";
	    echo " <option value='0'>Seleccione</option>";
	    foreach($arrNodos as $rowr){
	  	  echo "<option value='".$rowr['id']."' ";
		  if(isset($arrSeleccionados[($i+1)]) && !empty($arrSeleccionados[($i+1)])){ if($arrSeleccionados[($i+1)]==$rowr['id']){ echo "selected";  }}
	  	  echo ">";
		  echo $rowr['nombre'];
	  	  echo "</option>";
	    }
	    echo " </select></td>";
	  } else {
		if(isset($arrSeleccionados[($i)]) && !empty($arrSeleccionados[($i)])){
		  
		  $arrResultados = DB_CR_obtenerHijosPorNodo($arrSeleccionados[($i)],$conexion)	;	  
		  
	      echo "<td>".DB_CR_nombreNivel($_SESSION['id_cliente'],$conexion,($i+1)).": <select class='seleccioncr' id='sel_cr_".($i)."' name='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)' ";
		  echo ">";
	      echo " <option value='0'>Seleccione</option>";
	      foreach($arrResultados as $rowr){
	  	    echo "<option value='".$rowr['id']."' ";
		    if(isset($arrSeleccionados[($i+1)]) && !empty($arrSeleccionados[($i+1)])){ if($arrSeleccionados[($i+1)]==$rowr['id']){ echo "selected";  }}
	  	    echo ">";
		    echo $rowr['nombre'];
	  	    echo "</option>";			
	      }			
	      echo " </select></td>";
		}else{
	      echo " <td>".DB_CR_nombreNivel($_SESSION['id_cliente'],$conexion,($i+1)).": <select  class='seleccioncr' id='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)'>";
	      echo " <option value='0'>Seleccione</option>";	    
	      echo " </select></td>";			
		}	
	  }
	}
	  echo "</tr>";
	  echo "</table>"; 	  
	  
	
		  echo "</td></tr>";
	  echo "</table>";
	  
	  echo "</div>";
	  
	  echo "</td>";
	  echo "</tr>";	
	
	
 	/*******************************/
 	/*  EXTRAE  LOS OPTION RADIO ***/
	/*******************************/

 	$iContador=1;

 	$arrResultados = DB_NEGOCIO_segmentacionCabecera($_SESSION['id_cliente'],$conexion);
	  echo "<TR><td colspan='4'>&nbsp;</td></TR><TR><td colspan='4'>&nbsp;</td></TR>";	
	  echo "<tr>";
	  echo "<td colspan=4 align='center' valign='middle' class='Estilo5 '>"; 	 	
 	foreach ($arrResultados as $rowp)
 	{
 			
	
	  
	  echo "<div class='panel panel-default' style='float:left !important;width:45% !important;'>";	  
	  
	  echo "<table class='table table-bordered' border='1' cellpadding='0' cellspacing='0' width='200px' align='center'>";
	  echo "<tr><td class='titulo_tabla_des' width='90%'>".$rowp['title']."</td><TD class='titulo_tabla_des' align='center' width='10%'>SELECCIONE</TD>";
	  
	  $arrDetalle = DB_NEGOCIO_segmentacionDetalle($rowp['id'],$_SESSION['id_cliente'],$conexion);
 
	  foreach($arrDetalle as $rowso)
	  { 
	  	echo "<tr>";
	  	echo "<td class='item-tabla-fon-bco'>".$rowso['texto']."</td>";
	  	echo "<td class='item-tabla-fon-bco' align='center'>";
	  	$rowx=DB_RESULT_obtenerResultadoSegmentacion($rowp['id'],$rut,$conexion);
	  	$chk_vg='';
	  	if ($rowx[0]==$rowso['id']){$chk_vg=' checked ';}
		 
  		echo "<input type='radio' class='opSegmentacion' name='opc_seg_".$rowp['id']."' value='".$rowso['id']."' $chk_vg ></td></tr>";	
  		
	  	$iContador++;
	  }
	  echo "</table>";
	  
	  echo "</div>";	  

	}//fin del while

	  
	  echo "</td>";
	  echo "</tr>";

 	/*********************************************/
 	/*  EXTRAE LAS PREGUNTAS de CLASIFICACION ***/
	/*******************************************/
?>    
  <TR><td colspan="4">&nbsp;</td></TR>
  
  <TR>
  	<td colspan="4">
  	  <div class='panel panel-default'> 	
  	  <table class='table table-bordered'>
		  <tr><td colspan="4" class="titulo_tabla_des">VI. A CONTINUACION CONTESTE CADA UNA DE LAS SIGUIENTES PROPOSICIONES:</td></tr>
		  <tr><td colspan=4 align="center" valign="middle" class="Estilo5">
		    <table class='table table-bordered' border="1" cellpadding="0" cellspacing="0" width="777px" align="center">
		    <tr>
		    <td rowspan="2" class="encabezado-tabla-fon-gris-normal" width="1%" align="center">N&deg;</td>
		    <td rowspan="2" class="encabezado-tabla-fon-gris-normal" width="49%">PROPOSICION</td>
		    <td colspan="5" class="encabezado-tabla-fon-gris-normal" width="40%" align="center">REFIERASE A LA INSTITUCION EN LA QUE UD. TRABAJA</td>
		    <!--
		    <td rowspan="100" class="item-tabla-fon-bco" width="40%">&nbsp;</td>
		    -->
		    <!--
		    <td colspan="5" class="encabezado-tabla-fon-gris-normal" width="40%" align="center">REFIERASE A LA INSTITUCION</td>
		    -->
		    </tr>
		    <tr>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Muy Insatisfecho</td>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Insatisfecho</td>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Ni Satisfecho ni Insatisfecho</td>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Satisfecho</td>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Muy Satisfecho</td>
		    <!--
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Muy en Desacuerdo</td>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">En Desacuerdo</td>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Indiferente</td>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Satisfecho</td>
		    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">Muy Satisfecho</td>
		    -->
		    </tr>
		    <?php 
			 
			 $arrPreguntas = DB_NEGOCIO_obtenerPreguntas($_SESSION['id_cliente'],$conexion);
		 	 $i=1;	
			 foreach($arrPreguntas as $rowp) 
			  {
			  //print_r($rowp);
		    ?>
		    <tr>
		      <td class="item-tabla-fon-bco lineaCuestionario cuestionario<?php echo $i;?>" align="center"><?php echo $i;?></td>
		      <td class="item-tabla-fon-bco lineaCuestionario cuestionario<?php echo $i;?>"><?php echo $rowp['pregunta'];  //echo print_r($rowp,1);  ?></td>           
		
		      <td class="item-tabla-fon-bco lineaCuestionario cuestionario<?php echo $i;?>" align="center"><input type="radio" class='c_p<?php echo $i;?>_1' name="c_p<?php echo $rowp['n']; ?>" value="-2" <?php if(isset($pregunta1[$rowp['n']])){ if($pregunta1[$rowp['n']]==-2){ echo " checked ";}} ?>></td>
		      <td class="item-tabla-fon-bco lineaCuestionario cuestionario<?php echo $i;?>" align="center"><input type="radio" class='c_p<?php echo $i;?>_2' name="c_p<?php echo $rowp['n']; ?>" value="-1" <?php if(isset($pregunta1[$rowp['n']])){ if($pregunta1[$rowp['n']]==-1){ echo " checked ";}} ?>></td>
		      <td class="item-tabla-fon-bco lineaCuestionario cuestionario<?php echo $i;?>" align="center"><input type="radio" class='c_p<?php echo $i;?>_3' name="c_p<?php echo $rowp['n']; ?>" value="0"  <?php if(isset($pregunta1[$rowp['n']])){ if($pregunta1[$rowp['n']]==0){ echo " checked ";}} ?>></td>
		      <td class="item-tabla-fon-bco lineaCuestionario cuestionario<?php echo $i;?>" align="center"><input type="radio" class='c_p<?php echo $i;?>_4' name="c_p<?php echo $rowp['n']; ?>" value="1"  <?php if(isset($pregunta1[$rowp['n']])){ if($pregunta1[$rowp['n']]==1){ echo " checked ";}} ?>></td>
		      <td class="item-tabla-fon-bco lineaCuestionario cuestionario<?php echo $i;?>" align="center"><input type="radio" class='c_p<?php echo $i;?>_5' name="c_p<?php echo $rowp['n']; ?>" value="2"  <?php if(isset($pregunta1[$rowp['n']])){ if($pregunta1[$rowp['n']]==2){ echo " checked ";}} ?>></td>
		
			  <!--
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_p<?php echo $rowp[0]; ?>" value="-2" <?php if(isset($pregunta1[$rowp[0]])){ if($pregunta1[$rowp[0]]==-2){ echo " checked ";}} ?>></td>
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_p<?php echo $rowp[0]; ?>" value="-1" <?php if(isset($pregunta1[$rowp[0]])){ if($pregunta1[$rowp[0]]==-1){ echo " checked ";}} ?>></td>
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_p<?php echo $rowp[0]; ?>" value="0"  <?php if(isset($pregunta1[$rowp[0]])){ if($pregunta1[$rowp[0]]==0){ echo " checked ";}} ?>></td>
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_p<?php echo $rowp[0]; ?>" value="1"  <?php if(isset($pregunta1[$rowp[0]])){ if($pregunta1[$rowp[0]]==1){ echo " checked ";}} ?>></td>
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_p<?php echo $rowp[0]; ?>" value="2"  <?php if(isset($pregunta1[$rowp[0]])){ if($pregunta1[$rowp[0]]==2){ echo " checked ";}} ?>></td>
			  -->
		
			  <!--
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_pa<?php echo $rowp[0];?>" value="-2" <?php if ($pregunta2[$rowp[0]]==-2){ echo " checked ";} ?>></td>
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_pa<?php echo $rowp[0];?>" value="-1" <?php if ($pregunta2[$rowp[0]]==-1){ echo " checked ";} ?>></td>
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_pa<?php echo $rowp[0];?>" value="0"  <?php if ($pregunta2[$rowp[0]]==0) { echo " checked ";} ?>></td>
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_pa<?php echo $rowp[0];?>" value="1"  <?php if ($pregunta2[$rowp[0]]==1) { echo " checked ";} ?>></td>
		      <td class="item-tabla-fon-bco" align="center"><input type="radio" name="c_pa<?php echo $rowp[0];?>" value="2"  <?php if ($pregunta2[$rowp[0]]==2) { echo " checked ";} ?>></td>
			  -->
		    </tr>
		    <?php
		    $i++;
		    }
		    ?>
		    </table>
		  </td>
		  </tr>
		    	  	
  	  		
  	  </table>	
	  </div>  	  
  	  
	</td>
  </TR>  
  

<?php
  //var_dump($_SESSION['finCuestionario']);
   
  if(!isset($_SESSION['finCuestionario']) || $_SESSION['finCuestionario']==false  || in_array('3',$_SESSION['tipo_usuario'])){
    if($_SESSION['Completado']==false  || in_array('3',$_SESSION['tipo_usuario'])){	
    ?>
   <TR><td colspan="4">&nbsp;</td></TR><TR><td colspan="4">&nbsp;</td></TR>
   <TR <?php echo $mg;?>>
   	<td colspan="4" align="center">
  	<div id='loaderBotonGrabarParcial' class='oculto'>
  	  <img src='images/loader1.gif' height="65px" width="65px">
  	</div>
  	<div id='BotonGrabarParcial'>
  		<!--
   	  <a href="Javascript:cmdValida(1);" > <img name="Image1" border="0" src="images/b_grabar_n.gif" align="bottom" alt='Permite grabar las modificaciones.'>
      </a>&nbsp;
        -->  	
      
 		<button type='button' class='btn btn-custom' onclick='cmdValida(1);' title='Permite grabar las modificaciones.'>
 		  Grabar y Continuar <i class="fa fa-check-square-o"></i>	  	    
  	    </button>      
  	</div>

    </td></TR>
   <TR><td colspan="4">&nbsp;</td></TR><TR><td colspan="4">&nbsp;</td></TR>  
<?php
	}
  } elseif(isset($_SESSION['finCuestionario']) && $_SESSION['finCuestionario']==true) {
?>	  

<?php
  } 
  //unset($_SESSION['msg']);
  //unset($_SESSION['finCuestionario']);  
?>	  
 
	    
	    
	    <tr>
	      <td colspan='4' >
	      <div class='oculto alert alert-danger errorItemImportancia'>
	      	No pueden haber 2 items con el mismo valor en el item VII
	      </div>
	      </td>		    
	    </tr>
  		<TR <?php echo $p61;?>>
  			<td colspan="4" class="">
			  

  			</td>
  		</TR>  
  		<TR <?php echo $p61;?>>
  		  <td colspan="4">
  		  	<div class='panel panel-default'>
  			<table class='table table-bordered' border="1" cellpadding="0" cellspacing="0" width="777px" align="center">
    		  <TR>
    		  	<TD colspan=3 class='titulo_tabla_des'>

  				VII. A CONTINUACION, UD. ENCONTRARA UNA LISTA DE TEMAS QUE PUEDEN AFECTAR EL CLIMA LABORAL. ORDENE DEL 1 AL 14, SEGUN EL NIVEL DE IMPORTANCIA QUE TIENEN PARA USTED. SE&Ntilde;ALE CON EL NUMERO 1 EL MAS IMPORTANTE Y CON EL NUMERO 14 EL MENOS IMPORTANTE. (EL PUNTAJE NO DEBE REPETIRSE, CADA UNO DE LOS TEMAS DEBE TENER UN PUNTAJE UNICO Y ESPECIFICO).				    	
 					
    		  		
    		  	</TD>
    		  </TR>	
    		  <TR>
    		  	<TD class="encabezado-tabla-fon-gris-normal" width="1%">N&deg;</TD>
    		  	<TD class="encabezado-tabla-fon-gris-normal" width="89%">TEMAS</TD>
    		  	<TD class="encabezado-tabla-fon-gris-normal" width="10%" align='center'>NIVEL DE IMPORTANCIA</TD></TR>
	<?php

	$arrDimensiones = DB_NEGOCIO_obtenerDimensiones($_SESSION['id_cliente'],$conexion);
	$numero_filas = count($arrDimensiones);
 	$i=1;$x=0;	
 	foreach($arrDimensiones as $row1)
 	{ ?>    
    <TR class='' ><TD class='item-tabla-fon-bco  lineaImportancia importancia<?php echo $i;?>'><?php echo $i; ?></td>
    <TD class='item-tabla-fon-bco lineaImportancia importancia<?php echo $i;?>'><?php echo $row1['dimension'];?></TD>

    <TD class='item-tabla-fon-bco  lineaImportancia importancia<?php echo $i;?>'>
      <table>
      	<tr>

    <?php
    for($j=1;$j<=$numero_filas;$j++){
    ?>	
      <td align='center'>
        <input type='radio' onclick='validaImportancia();' name='c_nivel_<?php echo $i;?>' value='<?php echo ($j); ?>' <?php if (isset($nivel[$x]) && $nivel[$x]==$j){echo "checked";} ?> >		
        <span><?php echo ($j); ?></span> 
      </td>
	<?php	
    }    
    ?>
	    </tr>    	    
      </table>    	
	</TD>
	</TR>
   <?php 
		$i++;$x++;   
   } ?>

	    <tr>
	      <td colspan='3' >
	      <div class='oculto alert alert-danger'>
	      	No pueden haber 2 items con el mismo valor en el item VII
	      </div>
	      </td>		    
	    </tr>  

    </table>
    </div>
  </td></TR>

  <TR><td colspan="4">&nbsp;</td></TR><TR><td colspan="4">&nbsp;</td></TR>
  <TR <?php echo $p62;?>><td colspan="4" class="titulo_tabla_des">62. Ahora, le solicitamos que realice comentarios que puedan ayudar a profundizar informaci�n sobre las respuestas que usted acaba de dar, se�alando las fortalezas y las debilidades que usted considera que tiene la Instituci�n y que afectan positiva o negativamente el clima laboral.</td></TR>
  <TR <?php echo $p62;?>><td colspan="4"class="encabezado-tabla-fon-gris-normal">FORTALEZAS<BR><textarea name="c_fortalezas" cols="100" rows="4" class="input-generico"><?php if(isset($fortalezas)) echo $fortalezas; ?></textarea></td></TR>
  <TR <?php echo $p62;?>><td colspan="4" class="encabezado-tabla-fon-gris-normal">DEBILIDADES<BR><textarea name="c_debilidades" cols="100" rows="4" class="input-generico"><?php if(isset($debilidades))echo $debilidades; ?></textarea></td></TR>
  <TR><td colspan="4">&nbsp;</td></TR><TR><td colspan="4">&nbsp;</td></TR>
  <TR <?php echo $p63;?>><td colspan="4" class="titulo_tabla_des">63. Finalmente, SOLO SI USTED PERTENECE A UNA DEFENSORIA REGIONAL O LOCAL le solicitamos pueda evaluar el impacto que hayan o est�n teniendo los Planes de Acci�n para la mejora del Clima Laboral en su Unidad de Trabajo.</td></TR>
  <TR <?php echo $p63;?>><td colspan="4">
  	<table border="1" cellpadding="0" cellspacing="0" width="100%" align="center">
    <TR>
    <TD class="encabezado-tabla-fon-gris-normal" width="10%" align="center">N&deg;</TD>
    <td class="encabezado-tabla-fon-gris-normal" width="40%" align="center">PROPOSICION</td>
    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">MUY EN DESACUERDO</td>
    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">EN DESACUERDO</td>
    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">INDIFERENTE</td>
    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">SATISFECHO</td>
    <td class="encabezado-tabla-fon-gris-normal" width="10%" align="center">MUY SATISFECHO</td>    
    </tr>
    <TR><TD class="item-tabla-fon-bco" align="center">1</TD><TD class="item-tabla-fon-bco">El Plan de Acci&oacute;n de Clima Laboral es conocido por todos los miembros del equipo y ha tenido un impacto positivo en mi unidad de trabajo</TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r1" value="-2" <?php if ($propo1==-2){ echo " checked ";} ?>></TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r1" value="-1" <?php if ($propo1==-1){ echo " checked ";} ?>></TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r1" value="0"  <?php if ($propo1==0){ echo " checked ";} ?>></TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r1" value="1"  <?php if ($propo1==1){ echo " checked ";} ?>></TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r1" value="2"  <?php if ($propo1==2){ echo " checked ";} ?>></TD></TR>
    <TR><TD class="item-tabla-fon-bco" align="center">2</TD><TD class="item-tabla-fon-bco">El equipo directivo de mi regi&oacute;n se encuentra comprometido con la ejecuci&oacute;n de las acciones del Plan de Acci&oacute;n</TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r2" value="-2" <?php if ($propo2==-2){ echo " checked ";} ?>></TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r2" value="-1" <?php if ($propo2==-1){ echo " checked ";} ?>></TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r2" value="0"  <?php if ($propo2==0){ echo " checked ";} ?>></TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r2" value="1"  <?php if ($propo2==1){ echo " checked ";} ?>></TD>
    <TD class="item-tabla-fon-bco" align="center"><input type="radio" name="c_r2" value="2"  <?php if ($propo2==2){ echo " checked ";} ?>></TD></TR>
    </table>	  
  </td></TR>
  <TR><td colspan="4">&nbsp;</td></TR><TR><td colspan="4">&nbsp;</td></TR>
  


<?php
  //var_dump($_SESSION['finCuestionario']);
 	
     
    if(!isset($_SESSION['finCuestionario']) || $_SESSION['finCuestionario']==false || in_array('3',$_SESSION['tipo_usuario'])){
      if($_SESSION['Completado']==false  || in_array('3',$_SESSION['tipo_usuario'])){
  		      		
    ?>

  <TR><td colspan="4" align="center">
  	<div id='loaderBotonGrabarFinal' class=' oculto'>
  	  <img src='images/loader1.gif' height="65px" width="65px">  		
  	</div>
  	<div id='BotonGrabarFinal'>
  	  <!--<a href="Javascript:cmdValida(0);">-->
  	    <!--
  	    <img name="Image1" border="0" src="images/b_grabar_solo_n.gif" align="bottom" alt='Permite grabar las modificaciones realizadas.'>
  	    -->
 		<button type='button' class='btn btn-custom' onclick='cmdValida(0);' title='Permite grabar las modificaciones realizadas.'>
 		   Grabar <i class="fa fa-check-square-o"></i>	  	    
  	    </button>
  	  <!--</a>-->
  	</div>
  	
  	</td></TR>
  <TR><td colspan="4">&nbsp;</td></TR><TR><td colspan="4">&nbsp;</td></TR>

<?php

  		
      }
    } elseif(isset($_SESSION['finCuestionario']) && $_SESSION['finCuestionario']==true) {
  	
    } 
	
 
?>	  
  
  <tr><td colspan="4" align="center"></td></tr>          
  <tr>
    <td align="right" colspan=3 height="50px" valign=middle></td>
    <td align="right" colspan="1" height="50px" valign="middle"><a href="dpp_presentacion.php">Salir del formulario</a></td>
  </tr>
  <tr>
    <td bgcolor="#FFC300"  height="20px" align="center" colspan="4"> 
    	
  <div class='footer'>

  <span class="MsoNormal " align=center style='text-align:center'><span
  style=''>Copyright @ 2012, <a
  href="http://www.psicus.cl" target="_blank"><span style='color:white'>PSICUS
  Profesionales,</span></a> Resoluci&oacute;n Recomendada:1024x768 </span>
  </span>
  	
  </div>    	
    	
    	
    	</td>
  </tr>
  </tr></table></td></tr>
  </table>
	<input name="popup"	            type="hidden">
	
	<input name="rut"	            type="hidden" value="<?php echo $rut;?>">
	<input name="acceso_formulario"	type="hidden" value="<?php echo $acceso_formulario;?>">
	</form>

<script>
	
	$(document).ready(function(){
      //for(var i=1;i<maxNivel;i++){
      //  $('#sel_cr_'+i).css("width",$('#sel_cr_0').outerWidth()+'px');	
      //}
      <?php
	    if(!in_array('3',$_SESSION['tipo_usuario'])){
	      if($_SESSION['Completado'] || $_SESSION['finCuestionario']){ 
		    echo "$('#se :input').attr('disabled', true);";
          }		    	
	    }
		
  		unset($_SESSION['msg']);
  		unset($_SESSION['finCuestionario']);		
      ?>
	})
	
</script>
</body>
</html>
