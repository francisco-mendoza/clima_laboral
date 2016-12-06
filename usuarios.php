<?php
  //---------------------------------------------------------------------------------------------------------------- 
  // Verificamos el uso de session, sino lo mandamos a logearse
   session_start();  
  if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}
  if (!in_array('3',$_SESSION['tipo_usuario'])){echo "Por Seguridad esta sección esta reservada para los administradores <br><br><a href='index.php'>Inicio</a>";die();}	
  //----------------------------------------------------------------------------------------------------------------
  // Cargamos el acceso a la base de datos
  include("include/dbconection.php");
  include_once("include/definiciones.php");  
  
  if($_POST["accion_mantenedor_usuario"]=='editar'){
    $edit_rut = trim($_POST["edicion_rut"]);  	
  }	else {
    $edit_rut = '';  	
  }

  $idCliente = '0';
  if(isset($_SESSION['id_cliente_seleccionado'])){
    $idCliente = $_SESSION['id_cliente_seleccionado'];
  }
  if(isset($_POST['id_cliente'])){
    $idCliente = $_POST['id_cliente'];
  }

  include('headerHtml.php');
?>	

<script type="text/javascript" >
var sufijo_error = "_error";
var clienteInicial = '<?php echo $idCliente; ?>';
var maxNivel = parseInt('<?php echo DB_CR_cantidadNiveles($idCliente,$conexion); ?>',10);

function seleccionPerfil(idChk){
  var valor_actual = $("#"+idChk).is(':checked');
  if (confirm('Esta seguro de querer modificar el perfil de este usuario?')) {
    //
  } else {
    $("#"+idChk).prop('checked',!valor_actual);
  }
}

function cmdCambiaCliente(){
  var cliente_seleccionado = $("#id_cliente").val();
  if(cliente_seleccionado=='0')
	return;
	
  if (confirm('Esta seguro de querer seleccionar un nuevo cliente para este usuario?')) {
    $("#se").attr('action',"usuarios.php");
    $("#se").submit();  
  } else {
    //
    $("#id_cliente").val(clienteInicial);
  }  	
}

function esValidoChk(idObjeto){
  var n = $("."+idObjeto+":checked").length;
  if(n>0){
  	$("#"+idObjeto+sufijo_error).hide();
  	return true;  	
  } else {
  	$("#"+idObjeto+sufijo_error).show();
  	return false;  	
  }
}

function esValidoOpt(idObjeto){
  var n = $("."+idObjeto+":checked").length;
  if(n>0){
  	$("#"+idObjeto+sufijo_error).hide();
  	return true;  	
  } else {
  	$("#"+idObjeto+sufijo_error).show();
  	return false;  	
  }
}

function validacionCampos(){

    if(!esValido('id_cliente')) return false;    
    if(!validarRut('rut_u')) return false;
    if(!validarSoloTexto('nombre')) return false;
    if(!validarSoloTexto('apellido_paterno')) return false;
    if(!validarSoloTexto('apellido_materno')) return false;
	if(!validarSeleccionCr()) return false;
	//if(!esValido('sel_cc')) return false;
    if(!esValido('cargo')) return false;
    if(!esValido('grado')) return false;
    if(!esValidoChk('id_tipo_usuario')) return false;
    if(!esValido('estamento')) return false;
    if(!esValido('cjuridica')) return false;    
    if(!validarEmail('email')) return false;
	if(!esValidoVacio('fono')) return false;
   // if(!esValidoVacio('fecha_ingreso_cargo')) return false;        
    if(!esValidoOpt('sexo')) return false;    
	//if(!validarSoloTexto('profesion')) return false;
    if(!esValidoVacio('id_jefe_directo')) return false;		
    if(!esValido('id_region')) return false;
    if(!esValido('id_comuna')) return false;
    //if(!validarSoloTexto('cargo_jefe_directo')) return false;
    //if(!validarSoloTexto('nombre_jefe_directo')) return false;
    //if(!validarEmail('email_jefe_directo')) return false;
    //if(!validarUsername('username')) return false;
    if(!validarPassword('password')) return false;            
    if(!validarPassword('password2')) return false;
    if(!validarPasswordIguales()) return false;
	
   return true;
}

function validarPasswordIguales(){
  var pw1 = $.trim($("#password").val());	
  var pw2 = $.trim($("#password2").val());
  if(pw1!=pw2){
  	alert('Los passwords ingresados no son iguales');
  	return false;
  } else {  
  	return true;  	
  }
}

function toggleLoader(nombreObjeto){
  if($("#loader"+nombreObjeto).is(":visible")){
  	$("#loader"+nombreObjeto).hide();
  } else{
  	$("#loader"+nombreObjeto).show();
  }
}

function validarUsername(idObjeto){
  var node = $("#"+idObjeto);
  node.val(node.val().replace(/[^a-zA-Z0-9@\._-]/g,'') );   
  var valor = $.trim($("#"+idObjeto).val()); 
  var regex = /[a-zA-Z0-9@\._-]/g;
  if(regex.test(valor)){
  	$("#"+idObjeto+sufijo_error).hide();
  	return true;  	
  }else{
  	$("#"+idObjeto+sufijo_error).show();
  	return false;  	
  }  
}

function validarPassword(idObjeto){
  var node = $("#"+idObjeto);
  node.val(node.val().replace(/[^a-zA-Z0-9-\.]/g,'') );   
  var valor = $.trim($("#"+idObjeto).val());
  var regex = /[a-zA-Z0-9-\.]/g;
	
  if(valor.length<5){
  	$("#"+idObjeto+sufijo_error).show();  	
  	return false;
  }	

  if(regex.test(valor)){
  	$("#"+idObjeto+sufijo_error).hide();
  	return true;  	
  } else {
  	$("#"+idObjeto+sufijo_error).show();
  	return false;  	
  }
}

function validarSoloTexto(idObjeto){
  var node = $("#"+idObjeto);
  node.val(node.val().replace(/[^a-zA-ZññáéíóúÑ '\u00F1\u00C1\u00E1\u00C9\u00E9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00DC\u00FC\u00D1]/g,'') );   
  var valor = $.trim($("#"+idObjeto).val());
  var strObligatorio = $('#'+idObjeto).attr('obligatorio');
  if(strObligatorio=='0' && valor==''){
  	$("#"+idObjeto+sufijo_error).hide();  	
    return true;
  }
  var regex = /[a-zA-ZññáéíóúÑ '\u00F1\u00C1\u00E1\u00C9\u00E9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00DC\u00FC\u00D1]/g;
  if(regex.test(valor)){
  	$("#"+idObjeto+sufijo_error).hide();
  	return true;  	
  }else{
  	$("#"+idObjeto+sufijo_error).show();
   	return false;  	
  }    
}

function validarEmail(idObjeto)
{
  var email 		 = $.trim($('#'+idObjeto).val());
  var strObligatorio = $('#'+idObjeto).attr('obligatorio');
  if(strObligatorio=='0' && email==''){
  	$("#"+idObjeto+sufijo_error).hide();
    return true;  	
  }
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  var bResultado = regex.test(email);
  if(bResultado){
  	$("#"+idObjeto+sufijo_error).hide();
  	return true;
  } else {
  	$("#"+idObjeto+sufijo_error).show();
  	return false;  	
  }
}

function validarRut(idObjeto){
  var valor = $("#"+idObjeto).val();
  var strObligatorio = $('#'+idObjeto).attr('obligatorio');
  
  if(strObligatorio=='0' && valor==''){
  	
  }
  if(valor=='999-k' || valor=='999-K'){
  	return true;
  }
  var validacion = $.Rut.validar(valor);
  if(validacion){
  	$("#"+idObjeto+sufijo_error).hide(); 	
  }	else {
  	$("#"+idObjeto+sufijo_error).show();  	
  } 
  return validacion;
}

function esValido(idObjeto){//para los selects
  var valor = $.trim($("#"+idObjeto).val());
  var strObligatorio = $('#'+idObjeto).attr('obligatorio');
  if(strObligatorio=='0' && valor=='0'){
  	$("#"+idObjeto+sufijo_error).hide();
    return true;
  }
  if(valor=='' || valor=='0' || typeof valor == 'undefined'){
  	$("#"+idObjeto+sufijo_error).show();  	
  	return false;
  } else {
  	$("#"+idObjeto+sufijo_error).hide();  	
  	return true;
  }
}

function validarSeleccionCr(){
  for(var i=0;i<maxNivel;i++){
	var valor = $("#sel_cr_"+i).val();
	//if(valor=='0'){
	//  alert("Por Favor seleccione Centro de Responsabilidad en el nivel "+(i+1));
	//  $("#sel_cr"+sufijo_error).show();
	//  return false;	
	//}  	
	if(valor!='0' && valor!='undefined' && typeof valor != 'undefined'){
	  $("#id_select").val(valor);		
	}
  }
  $("#sel_cr"+sufijo_error).hide();
  //$("#id_select").val($("#sel_cr_"+(maxNivel-1)).val());    
  return true;
}

function esValidoVacio(idObjeto){//para los campos
  var valor = $.trim($("#"+idObjeto).val());
  var strObligatorio = $('#'+idObjeto).attr('obligatorio');
  if(strObligatorio=='0' && valor==''){
  	$("#"+idObjeto+sufijo_error).hide();
    return true;
  }
  if(valor=='' || typeof valor == 'undefined'){
  	$("#"+idObjeto+sufijo_error).show();  	
  	return false;
  } else {
  	$("#"+idObjeto+sufijo_error).hide();  	
  	return true;
  }
}



function cmdValida(){
  toggleLoader('Resultado');
  $("#accion_formulario").val('grabar'); 
  if(validacionCampos()){
    //document.se.submit();
    var parametros = $("#se").serializeArray();
    $.post("graba_usuarios.php",parametros,function(result){
    //alert(result);
      toggleLoader('Resultado');
      $("#resultadosSubmit").html(result);
      /*
      try{
        data = JSON.parse(result);
      } catch (e){
        alert(result);	
      } */
    }).error(function(xhr, textStatus, errorThrown){
  	  toggleLoader('Resultado');
      alert(xhr.responseText);
    });     
  } else {
    toggleLoader('Resultado');  	
  }
}

function cmdElimina(){
  var rutUsuario =  $.trim($("#rut_u").val());
  if(rutUsuario==''){
  	alert('por favor ingrese un rut de usuario');
  	return;
  }
  toggleLoader('Resultado');
  if (confirm('Esta seguro de querer borrar de la base de datos al usuario de rut '+rutUsuario+' ?')) {
    $("#accion_formulario").val('eliminar');
  	  var parametros = $("#se").serializeArray();
      $.post("graba_usuarios.php",parametros,function(result){
      //alert(result);
      toggleLoader('Resultado');
      $("#resultadosSubmit").html(result);
      /*
      try{
        data = JSON.parse(result);
      } catch (e){
        alert(result);	
      } */
    }).error(function(xhr, textStatus, errorThrown){
      toggleLoader('Resultado');
      alert(xhr.responseText);
    });		
  } else {
    toggleLoader('Resultado');    
  }  
}

function cargarComuna(valor){
  if(valor=='0')
    return;
      $('#id_comuna')
         .find('option')
         .remove()
         .end();
      $('#id_comuna')
        .append($("<option></option>")
        .attr("value",'0')
        .text('Seleccione Comuna'));    	

  $.post("consultas_ajax.php",{accion:'obtenerComunas',region:valor},function(result){
  //alert(result);
    try{
      data = JSON.parse(result);
      $.each(data, function(key, value){   
       $('#id_comuna')
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

  var rutUsuario = $.trim($("#rut_u").val());    	
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

</script>

<body bgcolor="#E6E6E6" background="images/bg.gif" lang=ES-CL link=navy
vlink=navy style='tab-interval:35.4pt' leftmargin=0 topmargin=0>

<div class=Section1>
<div align=center>

<?php


  include_once('menuHtml.php');

?>

<form action="dpp_admin.php">
  <input type='hidden' name="tabActual" value="3">
  <input class='btn btn-custom' type="submit" value="Volver al menu de administrador">
</form>

<form name="se" id='se' action="graba_usuarios.php" method="post">

<input type='hidden' name='accion_mantenedor_usuario' id='accion_mantenedor_usuario' value="<?php if(isset($_POST["accion_mantenedor_usuario"])){ echo $_POST["accion_mantenedor_usuario"];} ?>">
<input type='hidden' name='edicion_rut' id='edicion_rut' value="<?php if(isset($_POST["edicion_rut"])){ echo $_POST["edicion_rut"];} ?>">

<?php

function getRun($strRut){
  $strRut = str_replace(".","",$strRut);	
  $arrRut = explode("-",$strRut);
  return $arrRut[0];	
}

function getDv($strRut){
  $strRut = str_replace(".","",$strRut);	
  $arrRut = explode("-",$strRut);
  return $arrRut[1];	
}

if (strlen($edit_rut)>0)
{
  $sql ="select * from dpp_slorg_usuario where rut='".getRun($edit_rut)."';";
  $rs_m=mysql_query($sql,$conexion) or die(mysql_error());
  $iCuantos = mysql_num_rows($rs_m);
  $fila=mysql_fetch_assoc($rs_m);
  if($iCuantos==0){
  	echo "<div class='alert alert-danger'>";
  	echo " No existe el rut ".getRun($edit_rut)."-".getDv($edit_rut)." en la base de datos";
  	echo "</div>";
  }
} else {
  $fila = array();	
}		

?>
<input type='hidden' name='id_usuario' id='id_usuario' value='<?php if(!empty($fila)){ echo $fila['id_usuario'] ;}?>'>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0>
<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
<td style='background:#FBFBFB;padding:0cm 0cm 0cm 0cm'>

<table cellSpacing=0 cellPadding=0 width="777px" align=center border=0>
  <tr> 
    <td width="25%" class="encabezado-tabla-fon-gris-normal">CLIENTE</td>
    <td colspan='3' class="item-tabla-fon-bco">
      <select id='id_cliente' name='id_cliente' obligatorio='1' onchange='cmdCambiaCliente();'>
      	<option value='0'>Seleccione</option>
	    <?php 
	    $sql = "select * from slorg_cliente";
	    $result = mysql_query($sql) or die(mysql_error());
	    while($row=mysql_fetch_assoc($result)){
	    	echo "<option value='".$row['id_cliente']."' "; 
	    	if(isset($fila['id_cliente']) && $fila['id_cliente']==$row['id_cliente']){ echo 'selected';}else{if($row['id_cliente']==$idCliente){ echo "selected";}}	    	
	    	echo ">";
			echo $row['nombre_cliente'];			
			echo "</option>";			
	    }
		
	    ?>
      </select>
	  <div id='id_cliente_error' class='alert-danger oculto'>Debe seleccionar un cliente</div>
    </td>

  </tr>  
  <tr> 
    <td width="25%" class="encabezado-tabla-fon-gris-normal">RUT</td>
    <td width="auto" class="item-tabla-fon-bco">
      <input type="text" name="rut_u" id="rut_u" value="<?php echo (isset($fila['rut'])?number_format($fila['rut'],0,",",".")."-".$fila['dv']:''); ?>" size="12" maxlength="12" class="input-generico" obligatorio='1'>
	  <div id='rut_u_error' class='alert-danger oculto'>El Rut es Incorrecto</div>
    </td>
    <td width="25%" class="encabezado-tabla-fon-gris-normal">Nombre</td>
    <td width="auto" class="item-tabla-fon-bco">
      <input type="text" name="nombre" id="nombre" onkeyup="validarSoloTexto(this.id);" value="<?php echo (isset($fila['nombre'])?$fila['nombre']:''); ?>" size="40" maxlength="100" class="input-generico" obligatorio='1' >
	  <div id='nombre_error' class='alert-danger oculto'>El Valor de Nombre es Incorrecto</div>
    </td>
  </tr>
  
  <tr> 
    <td width="25%" class="encabezado-tabla-fon-gris-normal">Apellido Paterno</td>
    <td width="auto" class="item-tabla-fon-bco">
      <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?php echo (isset($fila['apellido_paterno'])?$fila['apellido_paterno']:''); ?>" size="20" maxlength="100" class="input-generico" obligatorio='1' onkeyup="validarSoloTexto(this.id);">
	  <div id='apellido_paterno_error' class='alert-danger oculto'>El valor de Apellido Paterno es incorrecto</div>
    </td>
    <td width="25%" class="encabezado-tabla-fon-gris-normal">Apellido Materno</td>
    <td width="auto" class="item-tabla-fon-bco">
      <input type="text" name="apellido_materno" id="apellido_materno" value="<?php echo (isset($fila['apellido_materno'])?$fila['apellido_materno']:''); ?>" size="20" maxlength="100" class="input-generico" obligatorio='1' onkeyup="validarSoloTexto(this.id);">
	  <div id='apellido_materno_error' class='alert-danger oculto'>El valor de Apellido Materno es incorrecto</div>
    </td>
  </tr>  
  
  <tr>
  <td width="25%" class="encabezado-tabla-fon-gris-normal">Centro de Responsabilidad</td>
  <td width="" class="item-tabla-fon-bco" colspan="1">
  	
 	  
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

	  echo "<input name='id_select' id='id_select' type='hidden' value=''>"; //Si se cambia a SELECT Dnámicos intervenir este hidden 	  
	for($i=0;$i<$iNiveles;$i++){
	  $sqlp="SELECT * FROM ".TBL_ESTRUCTURA_CR." where 1=1 and id_cliente=".$idCliente." AND id_nivel=".($i+1)." order by nombre ";		
	  $rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
	  if($i==0){
	    echo "".DB_CR_nombreNivel($idCliente,$conexion,($i+1)).":<br><select class='seleccioncr' id='sel_cr_".($i)."' name='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)' ";
		echo ">";
	    echo " <option value='0'>Seleccione</option>";
	    while($rowr=mysql_fetch_array($rs1)){
	  	  echo "<option value='".$rowr['id']."' ";
		  if(isset($arrSeleccionados[($i+1)]) && !empty($arrSeleccionados[($i+1)])){ if($arrSeleccionados[($i+1)]==$rowr['id']){ echo "selected";  }}
	  	  echo ">";
		  echo $rowr['nombre'];
	  	  echo "</option>";
	    }
	    echo " </select>";
	  }else{
		if(isset($arrSeleccionados[($i)]) && !empty($arrSeleccionados[($i)])){
	      $sqlp="SELECT * FROM ".TBL_ESTRUCTURA_CR." where 1=1 and id_cliente=".$idCliente." AND id_padre=".$arrSeleccionados[($i)]." order by nombre ";			
	  	  $rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
	      echo "<br>".DB_CR_nombreNivel($idCliente,$conexion,($i+1)).":<br><select class='seleccioncr' id='sel_cr_".($i)."' name='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)' ";
		  echo ">";
	      echo " <option value='0'>Seleccione</option>";
	      while($rowr=mysql_fetch_array($rs1)){
	  	    echo "<option value='".$rowr['id']."' ";
		    if(isset($arrSeleccionados[($i+1)]) && !empty($arrSeleccionados[($i+1)])){ if($arrSeleccionados[($i+1)]==$rowr['id']){ echo "selected";  }}
	  	    echo ">";
		    echo $rowr['nombre'];
	  	    echo "</option>";			
	      }			
	      echo " </select>";		  
		}else{
	      echo "<br>".DB_CR_nombreNivel($idCliente,$conexion,($i+1)).":<br><select id='sel_cr_".($i)."' onchange='cargarSelect(".($i+1).",this.value)'  class='seleccioncr' >";
	      echo " <option value='0'>Seleccione</option>";	    
	      echo " </select>";			
		}	
	  }
	}

  	?>	
	<!--select name='sel_cr' id='sel_cr' size='1' class="input-generico" style="width:310px;"-->
    <!--option value=0>Seleccione</option-->
    <?php
	  /*
       	$sql  = " select * from ".TBL_ESTRUCTURA_CR." where id_cliente = 0 ";	
		$rs=mysql_query($sql,$conexion) or die(mysql_error());
        while($row = mysql_fetch_assoc($rs)){
          echo "<option value='".$row['id_cr']."'";	
          if(!empty($edit_rut) && $fila['centro_responsabilidad']==$row['id_cr']){ echo ' selected '; }
          echo ">";			
          echo $row['nombre_cr'];			
          echo "</option>";			
      }
	  */
	?>	  	
	<!--/select-->
    <div id='sel_cr_error' class='alert-danger oculto'>Debe seleccionar una opci&oacute;n en cada uno los niveles</div>
</td>

<td width="25%" class="encabezado-tabla-fon-gris-normal">Cargo</td>
<td width="auto" class="item-tabla-fon-bco">

	<select name='cargo' id='cargo' obligatorio='1' size='1' class="input-generico" style="width:310px;">
      <option value='0'>Seleccione</option>
       <?php
        // --------------------------------------------------------------------------------
        // Desplegamos grado
        // --------------------------------------------------------------------------------			 
        $sql = " SELECT id_cargo,nombre_cargo FROM slorg_cargo WHERE 1=1 and id_cliente=".$idCliente;
	    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);	
	    //echo "total registros = ". $totEmp;				 
		 while ($row=mysql_fetch_array($rs)) 
		 {					
 		   echo '<option value= "'.$row["id_cargo"].'"';
 		   if(isset($fila['id_cargo']) && $row['id_cargo']==$fila['id_cargo']){echo ' selected ';}
 		   echo '>';
 		   echo $row["nombre_cargo"].'</option>';
		 }
		?>	  	
	</select>


    <div id='cargo_error' class='alert-danger oculto'>Debe seleccionar un valor de cargo</div>
</td>
<!--
<td width="auto" class="item-tabla-fon-bco">
	<select name='sel_cc' id='sel_cc' size='1' class="input-generico" style="width:310px;" obligatorio='0' >
      <option value='0'>Seleccione</option>
 	
	</select>
    <div id='sel_cc_error' class='alert-danger oculto'>Debe seleccionar una opci&oacute;n </div>	
</td>
-->
</tr>
<tr>

<td width="25%" class="encabezado-tabla-fon-gris-normal">Grado</td>
<td width="auto" class="item-tabla-fon-bco">

	<select name='grado' id='grado' obligatorio='1' size='1' class="input-generico" style="width:310px;">
      <option value='0'>Seleccione</option>
       <?php
        // --------------------------------------------------------------------------------
        // Desplegamos grado
        // --------------------------------------------------------------------------------			 
        $sql = " SELECT id_grado,nombre_grado FROM slorg_grado WHERE 1=1 and id_cliente=".$idCliente;
	    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);	
		   				    
	    //echo "total registros = ". $totEmp;				 
		 while ($row=mysql_fetch_array($rs)) 
		 {					
 		   echo '<option value= "'.$row["id_grado"].'"';
 		   if(isset($fila['id_grado']) && $row['id_grado']==$fila['id_grado']){echo ' selected ';}
 		   echo '>';
 		   echo $row["nombre_grado"].'</option>';
		 }
		?>	  	
	</select>


    <div id='grado_error' class='alert-danger oculto'>Seleccione un valor de Grado</div>
</td>

<td width="25%" class="encabezado-tabla-fon-gris-normal">Perfil del Usuario</td>
<td width="auto" class="item-tabla-fon-bco">
	<!--
	<select name='id_tipo_usuario' id='id_tipo_usuario' obligatorio='1' size='1' class="input-generico" style="width:310px;">
      <option value='0'>Seleccione</option>
     -->
       <?php
        // --------------------------------------------------------------------------------
        // Desplegamos los perfiles de usuario
        // --------------------------------------------------------------------------------			 
        $sql = " SELECT id_tipo_usuario,nombre_tipo_usuario FROM slorg_tipo_usuario WHERE 1=1 ";
	    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);	


		if(!empty($fila)){
		  $arrParametros = array("id_usuario"=>$fila['id_usuario']);
		  $arrPerfiles = DB_USUARIO_obtenerTiposDeUsuario($arrParametros,$conexion);
		}else{
		  $arrPerfiles = array();	
		}			
	    //echo "total registros = ". $totEmp;				 
		while ($row=mysql_fetch_array($rs)) 
		{					
 		  echo '<input obligatorio="1" class="id_tipo_usuario" type="checkbox" name="id_tipo_usuario[]" value= "'.$row["id_tipo_usuario"].'"';
 		   if(!empty($arrPerfiles) && in_array($row["id_tipo_usuario"],$arrPerfiles)){echo ' checked ';}
 		  echo '  onclick="seleccionPerfil(this.id);" id="id_tipo_usuario_'.$row["id_tipo_usuario"].'"  >';
 		  echo $row["nombre_tipo_usuario"].'</option>';
		}
		?>	  	
	<!--
	</select>	
	-->
    <div id='id_tipo_usuario_error' class='alert-danger oculto'>Debe seleccionar un perfil para el usuario</div>	
</td>
	
</tr>
<tr>
<td width="25%" class="encabezado-tabla-fon-gris-normal">Estamento</td>
<td width="auto" class="item-tabla-fon-bco">

	<select name='estamento' id='estamento' obligatorio='1' size='1' class="input-generico" style="width:310px;">
      <option value='0'>Seleccione</option>
       <?php
        // --------------------------------------------------------------------------------
        // Desplegamos los estamentos
        // --------------------------------------------------------------------------------			 
        $sql = " SELECT id_estamento,nombre_estamento FROM slorg_estamento WHERE 1=1 and id_cliente=".$idCliente;
	    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);	
		   				    
	    //echo "total registros = ". $totEmp;				 
		 while ($row=mysql_fetch_array($rs)) 
		 {					
 		   echo '<option value= "'.$row["id_estamento"].'"';
 		   if(isset($fila['id_estamento']) && $row['id_estamento']==$fila['id_estamento']){echo ' selected ';}
 		   echo '>';
 		   echo $row["nombre_estamento"].'</option>';
		 }
		?>	  	
	</select>

  
  <div id='estamento_error' class='alert-danger oculto'>Seleccione un valor de estamento</div>
</td>
  <td width="25%" class="encabezado-tabla-fon-gris-normal">Calidad Juridica</td>
  <td width="auto" class="item-tabla-fon-bco">
  	
	<select name='cjuridica' id='cjuridica' obligatorio='1' size='1' class="input-generico" style="width:310px;">
      <option value='0'>Seleccione</option>
       <?php
        // --------------------------------------------------------------------------------
        // Desplegamos calidad juridica
        // --------------------------------------------------------------------------------			 
        $sql = " SELECT id_calidad_juridica,nombre_calidad_juridica FROM slorg_calidad_juridica WHERE 1=1 and id_cliente=".$idCliente;
	    $rs=mysql_query($sql,$conexion) or die(mysql_error().$sql);	
		   				    
	    //echo "total registros = ". $totEmp;				 
		 while ($row=mysql_fetch_array($rs)) 
		 {					
 		   echo '<option value= "'.$row["id_calidad_juridica"].'"';
 		   if(isset($fila['id_calidad_juridica']) && $row['id_calidad_juridica']==$fila['id_calidad_juridica']){echo ' selected ';}
 		   echo '>';
 		   echo $row["nombre_calidad_juridica"].'</option>';
		 }
		?>	  	
	</select>

  	
    <div id='cjuridica_error' class='alert-danger oculto'>Seleccione un valor para calidad juridica</div>
  </td>
</tr>
<tr>
<td width="25%" class="encabezado-tabla-fon-gris-normal">Email</td>
<td width="auto" class="item-tabla-fon-bco">
  <input type="text" name="email" id="email" size="42" obligatorio='1' maxlength="100" class="input-generico" value="<?php echo (isset($fila['email'])?$fila['email']:'');?>" onblur='validarEmail(this.id)' >
  <div id='email_error' class='alert-danger oculto'>El valor de email es incorrecto</div>  
</td>

<td width="25%" class="encabezado-tabla-fon-gris-normal">Usuario Activo</td>

<td width="auto" class="item-tabla-fon-bco">SI <input type="radio" name="activo" id="activo" value="1" <?php if(isset($fila['id_estado']) && $fila['id_estado']=='1'){ echo "checked";}elseif(!isset($fila['id_estado'])){ echo "checked"; } ?>>&nbsp;&nbsp;
NO <input type="radio" name="activo" value="0" <?php if(isset($fila['id_estado']) && $fila['id_estado']=='0'){ echo "checked";} ?>></td>
</tr>
<tr>
<td width="25%" class="encabezado-tabla-fon-gris-normal">Telefono</td>
<td width="auto" class="item-tabla-fon-bco">
  <input type="text" name="fono" id="fono" size="40" maxlength="100"  obligatorio='1' class="input-generico" value="<?php echo (isset($fila['telefono'])?$fila['telefono']:'');?>">
  <div id='fono_error' class='alert-danger oculto'>El valor de fono es incorrecto</div>
</td>
<td width="25%" class="encabezado-tabla-fon-gris-normal">Fecha Ingreso Cargo</td>
<td width="auto" class="item-tabla-fon-bco">
  <input readonly name="fecha_ingreso_cargo" id="fecha_ingreso_cargo" obligatorio='1' type="text" size="10"  value="<?php echo (isset($fila['fecha_ingreso_cargo'])?$fila['fecha_ingreso_cargo']:'');?>">
  <div id='fecha_ingreso_cargo_error' class='alert-danger oculto'>Debe seleccionar una fecha de ingreso</div>	
</td>
</tr>

<tr>
<td width="25%" class="encabezado-tabla-fon-gris-normal"> Sexo </td>
<td width="auto" class="item-tabla-fon-bco">

  Masculino <input type="radio" class='sexo' obligatorio="1" name="sexo" id="activo" value="M" <?php if(isset($fila['sexo']) && $fila['sexo']=='M'){ echo "checked";}  ?>>&nbsp;&nbsp;
  Femenino <input type="radio" class='sexo' obligatorio="1" name="sexo" value="F" <?php if(isset($fila['sexo']) && $fila['sexo']=='F'){ echo "checked"; } ?>>
  <div id='sexo_error' class='alert-danger oculto'>Debe seleccionar un sexo</div>
</td>

<td width="25%" class="encabezado-tabla-fon-gris-normal"> Jefe Directo</td>
<td width="auto" class="item-tabla-fon-bco">

	<select name='id_jefe_directo' id='id_jefe_directo' obligatorio='0' size='1' class="input-generico" style="width:310px;">
      <option value='0'>Seleccione</option>
       <?php
        // --------------------------------------------------------------------------------
        // Desplegamos listado de usuarios existentes
        // --------------------------------------------------------------------------------		
        $arrParametros = array("id_cliente"=>$idCliente);	 
		$arrResultados = DB_USUARIO_listadoUsuarios($arrParametros,$conexion);
	    //echo "total registros = ". $totEmp;				 
		 foreach($arrResultados as $row) 
		 {					
 		   echo '<option value= "'.$row["id"].'"';
 		   if(isset($fila['id_jefe_directo']) && $row['id']==$fila['id_jefe_directo']){echo ' selected ';}
 		   echo '>';
 		   echo $row["nombre"].'</option>';
		 }
		?>	  	
	</select>

  	
    <div id='id_jefe_directo_error' class='alert-danger oculto'>Seleccione un jefe directo</div>

</td>
</tr>

<tr>
<td width="25%" class="encabezado-tabla-fon-gris-normal">Localizaci&oacute;n</td>
<td width="auto" class="item-tabla-fon-bco">

	<select name='id_region' id='id_region' obligatorio='1' size='1' class="input-generico" style="width:310px;" onchange="cargarComuna(this.value);">
      <option value='0'>Seleccione Regi&oacute;n</option>      
       <?php
        // --------------------------------------------------------------------------------
        // Desplegamos region
        // --------------------------------------------------------------------------------			 
		$arrResultados = DB_DIVISION_obtenerRegiones(array(),$conexion);	
		
		$idComuna = (isset($fila['id_region_pais']) && !empty($fila['id_region_pais']))?$fila['id_region_pais']:0; 
		$arrParametros=array('id'=>$idComuna);
		$arrRegion = DB_DIVISION_obtenerRegionDeComuna($arrParametros,$conexion);
		//var_dump($arrRegion);		   				    
		//var_dump($arrResultados);
	    //echo "total registros = ". $totEmp;				 
		foreach($arrResultados as $row) 
		{					
 		  echo '<option value= "'.$row["id_region"].'"';
 		  if(!empty($arrRegion) && $row['id_region']==$arrRegion['id_region']){echo ' selected ';}
 		  echo '>';
 		  echo $row["nombre_region"].'</option>';
		}
		
		
		?>	  	
	</select>
  	
	<select name='id_comuna' id='id_comuna' obligatorio='0' size='1' class="input-generico" style="width:310px;">
      <option value='0'>Seleccione Comuna</option>      
       <?php
        // --------------------------------------------------------------------------------
        // Desplegamos comuna
        // --------------------------------------------------------------------------------			 

        if(isset($fila['id_region_pais']) && !empty($fila['id_region_pais'])){			 
		  //falta si estuviese seteada una region en vez de una comuna		  
		  $arrParametros=array('id'=>$fila['id_region_pais']);
		  if(DB_DIVISION_esRegion($arrParametros,$conexion)){
	        $arrResultados = DB_DIVISION_obtenerComunasDeRegion($arrParametros,$conexion);				  	
		  } else {
	        $arrResultados = DB_DIVISION_obtenerComunasDeMismoNivel($arrParametros,$conexion);
		  }
		  foreach($arrResultados as $row) 
		  {					
	 		echo '<option value= "'.$row["id"].'"';
	 		if(isset($fila['id_region_pais']) && $row['id']==$fila['id_region_pais']){echo ' selected ';}
	 		echo '>';
	 		echo $row["nombre"].'</option>';
		  }		  
		} 
		
		?>	  	
	</select>  	
  	
    <div id='id_region_error' class='alert-danger oculto'>Seleccione un valor para Region</div>
    <div id='id_comuna_error' class='alert-danger oculto'>Seleccione un valor para Comuna</div>    

</td>
<td width="25%" class="encabezado-tabla-fon-gris-normal"></td>
<td width="auto" class="item-tabla-fon-bco">
	
</td>
</tr>

<tr>
<td width="25%" class="encabezado-tabla-fon-gris-normal"></td>
<td width="auto" class="item-tabla-fon-bco">

</td>
<td width="25%" class="encabezado-tabla-fon-gris-normal"></td>
<td width="auto" class="item-tabla-fon-bco">

</td>
</tr>

<tr>
<td width="25%" class="encabezado-tabla-fon-gris-normal">Password</td>
<td width="auto" class="item-tabla-fon-bco">
  <input type="text" name="password" id="password" onkeyup='validarPassword(this.id);'  obligatorio='1' size="10" maxlength="10" class="input-generico" value="<?php echo (isset($fila['password'])?$fila['password']:'');?>">
  <div id='password_error' class='alert-danger oculto'>El valor de password es invalido, debe ser mayor o igual<br>a 5 caracteres y debe contener solo letras ,n&oacute;meros, guion o puntos</div>
</td>
<td width="25%" class="encabezado-tabla-fon-gris-normal">Repita Password</td>
<td width="auto" class="item-tabla-fon-bco">
  <input type="text" name="password2" id="password2" onkeyup='validarPassword(this.id);'  obligatorio='1' onblur='validarPasswordIguales();' size="10" maxlength="10" class="input-generico" value="<?php echo (isset($fila['password'])?$fila['password']:'');?>">
  <div id='password2_error' class='alert-danger oculto'>El valor de password 2 es invalido, debe ser mayor o igual<br>a 5 caracteres ,debe contener solo letras ,n&uacute;meros, guion o puntos y debe coincidir con el campo password 1</div>	
</td>
</tr>

<tr>
  <td align=center colspan=4 height="50px" valign=middle align="center">
	<input type='hidden' name='accion_formulario' id='accion_formulario' value='n'>
	<input type="button" value="Grabar"   class='btn btn-custom' onclick='cmdValida()'>
	<input type="button" value="Eliminar" class='btn btn-custom' onclick='cmdElimina()'>
	<br>
	<div id='loaderResultado' class='oculto'>
	  <img src='images/loader1.gif'>
	</div>
  </td>
</tr>
<tr>
  <td align=center colspan=4 height="50px" valign=middle align="center">
	<div id="resultadosSubmit"></div>	
  </td>
</tr>
</table>		    
</td></tr></table>
</div>
</div>

</form>
<script>
  $(document).ready(function(){
	var picker = new Pikaday({
  	   field:$('#fecha_ingreso_cargo')[0]
	  ,
	});
	$('#fono').numeric({decimal: false,negative: false});
    $('#rut_u').Rut({
      on_error: function(){
        if($('#rut_u').val()=='999-k' || $('#rut_u').val()=='999-K')
          return;      	       	
        $("#rut_u"+sufijo_error).show();
      },
      on_success: function(){
        $("#rut_u"+sufijo_error).hide();
      },
      format_on: 'keyup'
    });
	$("#id_jefe_directo").chosen();    
  });
</script>
</body>
</html>