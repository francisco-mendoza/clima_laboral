<?php
	//---------------------------------------------------------------------------------------------------------------- 
	// Verificamos el uso de session, sino lo mandamos a logearse
	session_start();  
	if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.php'>Inicio</a>";die();}
	if (!in_array('3',$_SESSION['tipo_usuario'])){echo "Por Seguridad esta sección esta reservada para los administradores <br><br><a href='index.php'>Inicio</a>";die();}		
	
	//----------------------------------------------------------------------------------------------------------------
	// Cargamos el acceso a la base de datos
	include("include/dbconection.php");
	
	$idCliente= '';
	if(isset($_POST['id_cliente']) || isset($_SESSION['id_cliente_seleccionado'])){
	  if(isset($_SESSION['id_cliente_seleccionado'])){
	    $idCliente = $_SESSION['id_cliente_seleccionado'];	  		
	  }
	  if(isset($_REQUEST['id_cliente'])){
	    $idCliente = $_REQUEST['id_cliente'];	  		
	  }
	  $_SESSION['id_cliente_seleccionado'] = $idCliente;
	}/*
	echo "-----<br>";
	var_dump($_GET);	
	echo "-----<br>";
	var_dump($_REQUEST);		
	echo "-----<br>";
	var_dump($_POST);
	echo "-----<br>";
	var_dump($_SESSION);
	echo "-----<br>";
	var_dump($idCliente);*/
	$rut     		   = $_SESSION['rut']; //$_GET["rut"];
	$acceso_formulario = isset($_GET["acceso_formulario"])?1:0;
	// aca se hace el orden da las preguntas	
	$xx 			   = isset($_GET['x'])?$_GET['x']:0;
	$id				   = isset($_GET['id'])?$_GET['id']:0;
	$pos_act		   = isset($_GET['pos_act'])?$_GET['pos_act']:0;
	$pos_fut		   = isset($_GET['pos_fut'])?$_GET['pos_fut']:0;
	if(isset($_GET['x'])){
	  if ($xx==0) //para arriba
	  {
		$sql  = "UPDATE dpp_slorg_preguntas a INNER JOIN slorg_dimension b ON a.dimension = b.id set a.posicion=(a.posicion+1) where a.posicion=$pos_fut and b.id_cliente=".$idCliente;	
		//bajar la pregunta que existe		
		//echo $sql."XX1";
		mysql_query($sql,$conexion);
		//setear la pregunta que estamos haciendo subir
		$sql  = "UPDATE dpp_slorg_preguntas a INNER JOIN slorg_dimension b ON a.dimension = b.id set a.posicion=$pos_fut where a.id=$id and b.id_cliente=".$idCliente;			
		//echo $sql."XX2";
		mysql_query($sql,$conexion);
	  }
	  else // para abajo 
	  {		
		$sql  = "UPDATE dpp_slorg_preguntas a INNER JOIN slorg_dimension b ON a.dimension = b.id set a.posicion=(a.posicion-1) where a.posicion=$pos_fut and b.id_cliente=".$idCliente;			
		//hacer subir la pregunta que existe
		mysql_query($sql,$conexion);
		//echo $sql."YY1";		
		$sql  = "UPDATE dpp_slorg_preguntas a INNER JOIN slorg_dimension b ON a.dimension = b.id set a.posicion=$pos_fut where a.id=$id and b.id_cliente=".$idCliente;
		//setear la pregunta que estamos haciendo bajar
		//echo $sql."YY2";
		mysql_query($sql,$conexion);		
	  }	
	  // fin orden de preguntas
	}

include('headerHtml.php');	
		
?>
<script>
  var sufijo_error = '_error';

  function cambiarCliente(valor){
  	if(valor=='0'){
  	  return;  		
  	} else{
  	  $("#se").attr('action','dpp_admin.php');  		
  	  $("#se").submit();
		var idClienteSeleccionado = valor;
  	}
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

  function eliminarVariableSegmentacion(id){
    if (confirm('Esta seguro de querer borrar de la base de datos esta variable de segmentacion y sus opciones?')) {
	    $.post("consultas_ajax.php",{idSegmentacion:id,accion:'borrarVariableSegmentacion'},function(result){
	      //alert(result);
	      try{
			location.reload(true);
	      } catch (e){
	        alert(result);	
	      } 
	    }).error(function(xhr, textStatus, errorThrown){
  	      $("#loaderEdicionUsuario").hide();
	      alert(xhr.responseText);
	    });
  	}else{
  	  //nada
  	}  	  	
  }

function validarDatosMantenedorCliente(){
  var id_estado 	 = $("#form_id_estado").val();
  var nombre_cliente = $("#form_name_cliente").val();
  if(id_estado=='-1'){
  	$(".mensajeFormularioEdicionCliente").html("<div class='alert alert-danger'>Debe seleccionar un estado</div>");
  	$(".mensajeFormularioEdicionCliente").show();
  	return false;
  } else {
  	$(".mensajeFormularioEdicionCliente").hide();  	
  }
  if($.trim(nombre_cliente)=='' || !validarSoloTexto('form_name_cliente')){
  	$(".mensajeFormularioEdicionCliente").html("<div class='alert alert-danger'>Nombre de cliente invalido</div>");
  	$(".mensajeFormularioEdicionCliente").show();  	
  	return false;
  }else{
  	$(".mensajeFormularioEdicionCliente").hide();  	
  }     
  return true;
}

function validarSoloTextoyNumeros(idObjeto){
  var node = $("#"+idObjeto);
  node.val(node.val().replace(/[^a-zA-Z0-9ññáéíóúÑ '\u00F1\u00C1\u00E1\u00C9\u00E9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00DC\u00FC\u00D1]/g,'') );   
  var valor = $.trim($("#"+idObjeto).val());
  var regex = /[a-zA-Z0-9ññáéíóúÑ '\u00F1\u00C1\u00E1\u00C9\u00E9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00DC\u00FC\u00D1]/g;
  if(regex.test(valor)){
  	return true;  	
  }else{
   	return false;  	
  }    
}	

function enviarMantenedorCliente(opcion){
    
    switch(opcion){
      case 'eliminar':
   	    if($("#id_cliente_edicion").val()=='0'){
  	      alert('Seleccione un cliente');
  	      return;	
  	    }      
    	if (confirm('Esta seguro de querer borrar de la base de datos este cliente?')) { 
  	      var cliente = $("#id_cliente_edicion").val();
  	      $("#loaderEdicionCliente").show();
  	      
	      $.post("consultas_ajax.php",{accion:'eliminarCliente',cliente:cliente},function(result){
  	        $("#loaderEdicionCliente").hide();

			try {
			  var respuesta = JSON.parse(result);
			  if(respuesta['resultado']=='OK'){
			 	alert(respuesta['msg']);
			 	location.reload(true);
			  }else{
			 	alert(respuesta['msg']);
			  }
			} catch(err) {
			  alert(result);						    
			}	    	
	      }).error(function(xhr, textStatus, errorThrown){
  	        $("#loaderEdicionCliente").hide();
   	        alert(xhr.responseText);
	      });
    	}else{
		  //    	
	    }
      break;	
    	
  	  case 'editar':
  	    if($("#id_cliente_edicion").val()=='0'){
  	      alert('Seleccione un cliente');
  	      return;	
  	    }
  	    var cliente = $("#id_cliente_edicion").val()
	    $.post("dpp_formulario_cliente.php",{cliente:cliente},function(result){

			var box = bootbox.dialog({
			  message: result,
			  title: "Mantenedor de Cliente",
			  onEscape: function(){ return false; },
			  closeButton: false,			  
			  buttons: {
			    main: {
			      label: "<i class='fa fa-times'></i> Cerrar",
			      className: "btn-primary",
			      callback: function() {
			        //Example.show("Primary button");
			      }
			    },			  	
			    success: {
			      label: "<i class='fa fa-floppy-o'></i> Guardar",
			      className: "btn-success botonGuardarEdicionCliente",
			      callback: function() {
			        //Example.show("great success");
			        //box.modal('hide');
			        //enviar el formulario por ajax, a consultas_ajax
			        //devolver el resultado
			        if(!validarDatosMantenedorCliente())
			          return false;
			        var parametros = $("#formulario_edicion_cliente").serializeArray();
	    			$.post("consultas_ajax.php",parametros,function(result){
	    			  //$(".mensajeFormularioEdicionCliente").html(result);
						try {
						  var respuesta = JSON.parse(result);
						  if(respuesta['resultado']=='OK'){
						  	$(".mensajeFormularioEdicionCliente").html("<div class='alert alert-success'>"+respuesta['msg']+"</div>");
						  	$(".mensajeFormularioEdicionCliente").show();		
							setTimeout(function(){ box.modal('hide');location.reload(true); }, 2000);
						  	
						  					  	
						  }else{
						  	$(".mensajeFormularioEdicionCliente").html("<div class='alert alert-danger'>"+respuesta['msg']+"</div>");
						  	$(".mensajeFormularioEdicionCliente").show();						  					   
						  }
						} catch(err) {
				          alert(result);						    
						}
				    }).error(function(xhr, textStatus, errorThrown){
				      alert(xhr.responseText);
				    }); 	    							        
			        return false;
			      }
			    }
			  }
			});
	
	    }).error(function(xhr, textStatus, errorThrown){
	      alert(xhr.responseText);
	    });   
  	      	  
  	  break;
  	  case 'agregar':
	    $.post("dpp_formulario_cliente.php",null,function(result){
			var box = bootbox.dialog({
			  message: result,
			  title: "Mantenedor de Cliente",
			  onEscape: function(){ return false; },
			  closeButton: false,			  
			  buttons: {
			    main: {
			      label: "<i class='fa fa-times'></i> Cerrar",
			      className: "btn-primary",
			      callback: function() {
			        //Example.show("Primary button");
			      }
			    },			  	
			    success: {
			      label: "<i class='fa fa-floppy-o'></i> Guardar",
			      className: "btn-success botonGuardarEdicionCliente",
			      callback: function() {
			        //Example.show("great success");
			        //box.modal('hide');
			        //enviar el formulario por ajax, a consultas_ajax
			        //devolver el resultado
			        if(!validarDatosMantenedorCliente())
			          return false;			        
			        var parametros = $("#formulario_edicion_cliente").serializeArray();
	    			$.post("consultas_ajax.php",parametros,function(result){
	    			  //$(".mensajeFormularioEdicionCliente").html(result);
						try {
						  var respuesta = JSON.parse(result);
						  if(respuesta['resultado']=='OK'){
						  	$(".mensajeFormularioEdicionCliente").html("<div class='alert alert-success'>"+respuesta['msg']+"</div>");
						  	$(".mensajeFormularioEdicionCliente").show();	
							setTimeout(function(){ box.modal('hide');location.reload(true); }, 2000);						  						  	
						  }else{
						  	$(".mensajeFormularioEdicionCliente").html("<div class='alert alert-danger'>"+respuesta['msg']+"</div>");
						  	$(".mensajeFormularioEdicionCliente").show();						  					   
						  }
						} catch(err) {
				          alert(result);						    
						}
				    }).error(function(xhr, textStatus, errorThrown){
				      alert(xhr.responseText);
				    }); 	    							        
			        return false;
			      }
			    }
			  }
			});
	    }).error(function(xhr, textStatus, errorThrown){
	      alert(xhr.responseText);
	    });   
  	      	  
  	  break;  	  
  	}	
}

  function enviarMantenedorUsuarios(opcion){
    //ver que no este vacio y validar el rut 
    //alert(opcion);
  	var rut_u = $.trim($("#edit_identificador").val());
    switch(opcion){
  	  case 'editar':  	  
  	    if(rut_u==''){
  	      alert("Si va a editar, debe ingresar un rut o email de usuario");	
  	      return;
  	    }
  	    //var validaRut = $.Rut.validar(rut_u);
  	    validaRut = true;
  	    if(!validaRut && rut_u!='999-k' && rut_u!='999-K'){
  	      //alert("Rut o Email de usuario incorrecto");  	    	
		  $("#edit_rut"+sufijo_error).show();
		  return;
  	    }else{
		  $("#edit_rut"+sufijo_error).hide();  	    	
  	    }
  	    
  	    $("#loaderEdicionUsuario").show();
	    $.post("consultas_ajax.php",{rut:rut_u,accion:'existe'},function(result){
	      //alert(result);
  	      $("#loaderEdicionUsuario").hide();
	      try{
	        data = JSON.parse(result);
	        if(data['resultado']=='ERROR'){
	          $("#edit_rut_noexiste"+sufijo_error).show();	
	        }else{
	          if(data['resultado']=='OK'){
	            $("#edit_rut_no_existe"+sufijo_error).hide();	          	
				$("#accion_mantenedor_usuario").val(opcion);  	    
				//$("#edicion_rut").val(rut_u);		
				$("#edicion_rut").val(data['rut']);
		  	    $("#formUsuario").submit();	          	
	          }	else {
	          	alert(result);
	          }
	        }
	      } catch (e){
	        alert(result);	
	      } 
	    }).error(function(xhr, textStatus, errorThrown){
  	      $("#loaderEdicionUsuario").hide();
	      alert(xhr.responseText);
	    });   
  	    

  	  break;
  	  case 'agregar':
		$("#accion_mantenedor_usuario").val(opcion);  	  
  	    $("#formUsuario").submit();
  	  break;
    }
  }
	
</script>
<body bgcolor="#FFFFFF"  background="images/bg.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/b_grabar_r.gif','images/b_grabar_solo_r.gif')">


<?php

include_once('menuHtml.php');

?>

<form name="se" id='se' method="post" action="<?php if(empty($idCliente)){ echo 'dpp_admin.php';}else{ echo 'graba_admin.php';}?>">
	<input type='hidden' name="tabActual" value="3">



    <table width="780px" border="0"  align="center" cellpadding="0" cellspacing="0" bgcolor="#fafafa">
      <tr>
        <td colspan="4"><p align="center" style="line-height: 30px;"> <strong><font size="+1">ADMINISTRACI&Oacute;N DE USUARIOS Y PREGUNTAS DEL FORMULARIO</font></strong></p></td>
      </tr>

     <tr><td colspan="4">&nbsp;</td></tr>

      <tr>
        <td colspan=4 align="justify" class="cuadro_tabla">
			
			<table cellpadding="1" cellspacing="1" border="0" width="100%">
			<tr>
			<td class="encabezado-tabla-fon-gris-normal">Seleccione el Cliente al cual administrar&aacute;</td>
			<td class="item-tabla-fon-bco">
			  <select id='id_cliente' name='id_cliente' obligatorio='1' onchange='cambiarCliente(this.value);'>

				<?php 
				
				if(empty($idCliente)){
				  echo "<option value='0'>Seleccione</option>";			
				}
				
	    $sql = "select * from slorg_cliente";
	    $result = mysql_query($sql) or die(mysql_error());
	    while($row=mysql_fetch_assoc($result)){
	    	echo "<option value='".$row['id_cliente']."' "; 
			if(isset($idCliente) && $row['id_cliente']==$idCliente){ echo 'selected'; }
	    	echo ">";
			echo $row['nombre_cliente'];			
			echo "</option>";			
	    }



				$_SESSION['clienteSeleccionado'] = '<script>idClienteSeleccionado;</script>';
				
				?>			  	
			  </select>	
			</td>
			</tr>			
			</table>
		 </td>
	  </tr>

      <tr>
        <td colspan=4 align="center" valign="middle" class="Estilo5">&nbsp;</td>
      </tr>

      <tr>
        <td colspan=4 align="justify" class="cuadro_tabla">
			<table cellpadding="1" cellspacing="1" border="0" width="100%">
			<tr><td colspan=4 align="justify" class="titulo_tabla_des">Mantencion de Cliente</td></tr>
			<tr>
			  <td width="auto" class="encabezado-tabla-fon-gris-normal" colspan="1">
			  	AGREGAR CLIENTE
			  </td>
			  <td width="auto" class="item-tabla-fon-bco" colspan="3">
			  	<button type="button"  class='btn btn-custom' onclick="enviarMantenedorCliente('agregar')">Clic aqu&iacute; para agregar cliente</button>
			  </td>
			</tr>
			<tr>
			<td class="encabezado-tabla-fon-gris-normal" width="20%">EDITAR CLIENTE</td>
			<td class="item-tabla-fon-bco" width="auto">
			<table width="100%" cellpadding="1" cellspacing="1" border="0">		
			  <tr>
			    <td class="item-tabla-fon-bco" nowrap></td>
			    <td>
			       <select id="id_cliente_edicion" name='id_cliente_edicion' >
			         <option value='0'>Seleccione</option>
			         <?php
			           $sql = 'SELECT * FROM slorg_cliente ';
					   $result = mysql_query($sql) or die(mysql_error);
					   while($row = mysql_fetch_assoc($result)){
					     echo "<option value=".$row["id_cliente"].">".$row["nombre_cliente"]."</option>";
					   } 
			         ?>	
			       </select>			    		
			    </td>
 			    <td class="item-tabla-fon-bco">
 			    	<button type="button" class='btn btn-custom' onclick="enviarMantenedorCliente('editar')">Clic aqu&iacute; para Editar este Cliente</button>
 			    	<button type="button" class='btn btn-custom' onclick="enviarMantenedorCliente('eliminar')">Clic aqu&iacute; para Eliminar este Cliente</button>
 			    	<span id='loaderEdicionCliente' class='oculto' >
 			    	<img src="images/loader1.gif" width='25px' height='25px' >
 			    	</span>
 			    </td>	
			  </tr>
			  <tr>
			    <td class="item-tabla-fon-bco" nowrap></td>
			    <td colspan="2">

			    </td>	
			  </tr>			  
			</table>
		    </td>
			</tr>
			</table>
		</td>	  
     </tr>


      <tr>
        <td colspan=4 align="center" valign="middle" class="Estilo5">&nbsp;</td>
      </tr>
      
      <?php
      
      if(!isset($idCliente) || $idCliente=='0' || $idCliente=='' || is_null($idCliente)){
      	echo "</form>";	
      	exit;
      }
      
      ?>
      <!--
      <tr>
        <td colspan=4 align="justify" class="cuadro_tabla">
			<?php
			/*
			$sqlp ="select texto_inicio1, texto_inicio2 from slorg_admin where id_cliente=".$idCliente;
	 	 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());	
		 	$rowp=mysql_fetch_array($rs1)
			 */
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
			<tr><td colspan="2" class="item-tabla-fon-bco" align="center"><input class='btn btn-custom' type="submit" value="Grabar"></td></tr>			
			</table>
		 </td>
	  </tr> 	
      -->
      <tr><td colspan=4>&nbsp;</td></tr>
      
      <tr>
        <td colspan=4 align="justify" class="cuadro_tabla">
			<?php
			$sqlp ="select texto1,texto2,texto3 from slorg_admin where id_cliente=".$idCliente;
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
			<tr><td colspan="2" class="item-tabla-fon-bco" align="center"><input  class='btn btn-custom' type="submit" value="Grabar"></td></tr>			
			</table>
		 </td>
	  </tr> 	
      <tr><td colspan=4>&nbsp;</td></tr>



      <tr>
        <td colspan=4 align="justify" class="cuadro_tabla">
			<table cellpadding="1" cellspacing="1" border="0" width="100%">
			<tr><td colspan=4 align="justify" class="titulo_tabla_des">Mantencion de Usuarios</td></tr>
			<tr>
			  <td width="auto" class="encabezado-tabla-fon-gris-normal" colspan="1">
			  	AGREGAR USUARIO
			  </td>
			  <td width="auto" class="item-tabla-fon-bco" colspan="3">
			  	<button type="button" class='btn btn-custom' onclick="enviarMantenedorUsuarios('agregar')">Clic aqu&iacute; para agregar usuario</button>
			  </td>
			</tr>
			<tr>
			<td class="encabezado-tabla-fon-gris-normal" width="20%">EDITAR USUARIO</td>
			<td class="item-tabla-fon-bco" width="auto">
			<table width="100%" cellpadding="1" cellspacing="1" border="0">		
			  <tr>
			    <td class="item-tabla-fon-bco" nowrap>INGRESE RUT o EMAIL</td>
			    <td>
			        <input type="text" name="edit_identificador" id="edit_identificador" size="20" class="input-generico">
			        <input type="hidden" name="edit_rut" id="edit_rut" size="20" class="input-generico">			    		
			    </td>
 			    <td class="item-tabla-fon-bco">
 			    	<button type="button" class='btn btn-custom' onclick="enviarMantenedorUsuarios('editar')">Clic aqu&iacute; para Editar este usuario</button>
 			    	<span id='loaderEdicionUsuario' class='oculto' >
 			    	<img src="images/loader1.gif" width='25px' height='25px' >
 			    	</span>
 			    </td>	
			  </tr>
			  <tr>
			    <td class="item-tabla-fon-bco" nowrap></td>
			    <td colspan="2">
			      <div id='edit_rut_error' class='oculto alert alert-danger'>
 				    Rut o Correo no Valido
			      </div>
			      <div id='edit_rut_noexiste_error' class='oculto alert alert-danger'>
 				    El Rut o Email no existe dentro de este Cliente
			      </div>			      
			    </td>	
			  </tr>			  
			</table>
		    </td>
			</tr>
			</table>
		</td>	  
     </tr>
     <tr><td colspan="4">&nbsp;</td></tr>


     <tr><td colspan="5">&nbsp;</td></tr>

		<tr><td colspan="5" class="titulo_tabla_des">Configuración Gráficos Dinámicos</td></tr>

		<tr>
			<td colspan="5">


<br>



	<table border="0">
		<tr >
			<td>
				Estamento&nbsp;&nbsp;
			</td>
			<td style="padding-bottom: 5px">
				<input type="checkbox" id="estamento" value="on"  data-toggle="toggle" data-onstyle="warning">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				Sexo&nbsp;&nbsp;
			</td>
			<td>
				<input type="checkbox" id="sexo" value="on"  data-toggle="toggle" data-onstyle="warning">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			</td>
			<td>
				Antiguedad&nbsp;&nbsp;
			</td>
			<td>
				<input type="checkbox" id="antiguedad" data-toggle="toggle" data-onstyle="warning">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				Calidad Juridica&nbsp;&nbsp;
			</td>
			<td>
				<input type="checkbox" id="calidadJuridica" value="on"  data-toggle="toggle" data-onstyle="warning">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				Centro de Costo&nbsp;&nbsp;
			</td>
			<td>
				<input type="checkbox" id="centroCosto" value="on"  data-toggle="toggle" data-onstyle="warning">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				Unidad de Desempeño&nbsp;&nbsp;
			</td>
			<td>
				<input type="checkbox" id="unidadDesempeno" value="on"  data-toggle="toggle" data-onstyle="warning">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			</td>
		</tr>
		<tr>
			<td colspan="6" align="center" style="padding-top: 20px">
				<button type="button" class="btn btn-custom" onclick="actualizarGraficosDinamicos()"><i class="fa fa-save"></i> Guardar <i class="fa fa-refresh fa-spin" id="loadGuardar" style="display: none;"></i></button>
				<br><br>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close" id="mensajeDinamicos" style="background-color: #FB9947;display: none">
					<span aria-hidden="true">&times;</span>
					Graficos Dinamicos Actualizados
				</button>
				<style>
					button.close{
						padding: 12px;
					}
					.close{
						float: none;
						opacity: 0.7;
					}


				</style>
			</td>
		</tr>
	</table>

				<script>
					// Función para recoger los datos de PHP según el navegador, se usa siempre.
					function objetoAjax(){
						var xmlhttp=false;
						try {
							xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
						} catch (e) {

							try {
								xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
							} catch (E) {
								xmlhttp = false;
							}
						}

						if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
							xmlhttp = new XMLHttpRequest();
						}
						return xmlhttp;
					}
					//Función para recoger los datos del formulario y enviarlos por post
					function actualizarGraficosDinamicos(){

						$('#loadGuardar').show();
						$('#mensajeDinamicos').fadeIn(200);



						var vEstamento = $('#estamento').prop('checked');
						if(vEstamento == true){
							estamento = $('#estamento').val('on');
						}
						else{
							estamento = $('#estamento').val('off');
						}

						var vCalidadJuridica = $('#calidadJuridica').prop('checked');
						if(vCalidadJuridica == true){
							calidadJuridica = $('#calidadJuridica').val('on');
						}
						else{
							calidadJuridica = $('#calidadJuridica').val('off');
						}

						var vSexo = $('#sexo').prop('checked');
						if(vSexo == true){
							sexo = $('#sexo').val('on');
						}
						else{
							sexo = $('#sexo').val('off');
						}

						var vCentroCosto = $('#centroCosto').prop('checked');
						if(vCentroCosto == true){
							centroCosto = $('#centroCosto').val('on');
						}
						else{
							centroCosto = $('#centroCosto').val('off');
						}

						var vAntiguedad = $('#antiguedad').prop('checked');
						if(vAntiguedad == true){
							$('#antiguedad').val('on');
						}
						else{
							$('#antiguedad').val('off');
						}

						var vUnidadDesempeno = $('#unidadDesempeno').prop('checked');
						if(vUnidadDesempeno == true){
							unidadDesempeno = $('#unidadDesempeno').val('on');
						}
						else{
							unidadDesempeno = $('#unidadDesempeno').val('off');
						}

						//recogemos valores
						estamento 		 = $('#estamento').val();
						calidadJuridica  = $('#calidadJuridica').val();
						sexo 			 = $('#sexo').val();
						centroCosto		 = $('#centroCosto').val();
						antiguedad		 = $('#antiguedad').val();
						unidadDesempeno  = $('#unidadDesempeno').val();

						//instanciamos el objetoAjax
						ajax=objetoAjax();

						//uso del medotod POST
						//archivo que realizará la operacion
						//registro.php
						ajax.open("POST", "updateGraficosDinamicos.php",true);
						//cuando el objeto XMLHttpRequest cambia de estado, la función se inicia
						ajax.onreadystatechange=function() {

							$('#loadGuardar').hide();

							$('#mensajeDinamicos').fadeOut(500);

							//la función responseText tiene todos los datos pedidos al servidor
							if (ajax.readyState==4) {
								//mostrar resultados en esta capa
								divResultado.innerHTML = ajax.responseText
								//llamar a funcion para limpiar los inputs
								//LimpiarCampos();
								$('#loadGuardar').hide();


							}
						}
						ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
						//enviando los valores a registro.php para que inserte los datos
						ajax.send("estamento="+estamento+"&calidadJuridica="+calidadJuridica+"&sexo="+sexo+"&centroCosto="+centroCosto+"&antiguedad="+antiguedad+"&unidadDesempeno="+unidadDesempeno)
					}

				</script>





<br>
				<script>
					$(document).ready(function(){


						//$('#datosDinamicos').empty(); //Limpando a tabela
						$('#estamento').bootstrapToggle('off');
						$.ajax({
							type:'post',		//Definimos o método HTTP usado
							dataType: 'json',	//Definimos o tipo de retorno
							url: 'actualizaGraficosDinamicos.php',//Definindo o arquivo onde serão buscados os dados
							success: function(dados){
								for(var i=0;dados.length>i;i++){
									//Adicionando registros retornados na tabela
									$('#estamento').bootstrapToggle(dados[i].estamento);
									$('#sexo').bootstrapToggle(dados[i].sexo);
									$('#antiguedad').bootstrapToggle(dados[i].antiguedad);
										$('#antiguedad').val(dados[i].antiguedad);
									$('#calidadJuridica').bootstrapToggle(dados[i].calidadJuridica);
									$('#centroCosto').bootstrapToggle(dados[i].centroCosto);
									$('#unidadDesempeno').bootstrapToggle(dados[i].unidadDesempeno);
									//$('#datosDinamicos').append('<tr><td>'+dados[i].estamento+'</td><td>'+dados[i].sexo+'</td></tr>');
								}
							}
						});
					});
				</script>

			</td>
		</tr>


	  <tr>





     <td colspan="5" class="cuadro_tabla" align="center">



	  <table border="0" cellpadding="1" cellspacing="1" width="100%">
	  <tr><td class="titulo_tabla_des" colspan="5">Variables</td></tr>
	  <tr>
	  <td class="encabezado-tabla-fon-gris-normal">N&deg;</td>
	  <td class="encabezado-tabla-fon-gris-normal">Variable</td>
	  <!--
	  <td class="encabezado-tabla-fon-gris-normal">Promedio</td>
	  -->
	  <td class="encabezado-tabla-fon-gris-normal" colspan="2">Eliminar</td>
	  </tr> 
		<?php
			$sqlp ="select id,dimension, promedio from slorg_dimension where id_cliente=".$idCliente;
	 	 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
	 	 	$i=1;	
		 	while ($rowp=mysql_fetch_array($rs1))
		 	{
		 		echo "<tr>";
		 		echo "<td class='item-tabla-fon-bco'>$i</td>";
		 		echo "<td class='item-tabla-fon-bco'>";
		 		echo "<input type='text' name='dimension_$i' class='input-generico' value='$rowp[1]' size=80>";
		 		echo "<input type='hidden' name='id_dim_$i' class='input-generico' value='$rowp[0]'>";
		 		echo "</td>";
				
				//echo "<td class='item-tabla-fon-bco' >";
				//echo "<input type='text' name='promedio_$i' class='input-generico' value='$rowp[2]' size=2></td>";
		 		echo "<td class='item-tabla-fon-bco' width='30%'>";
				
		 		echo "<input type='checkbox' name='del_dim_$i' value='$rowp[0]'>";
		 		echo "</td>";
		 		//if ($i==1){ echo "<td class='item-tabla-fon-bco' colspan='2' rowspan='10' align=center valign=middle><input type='submit' value='Agregar'><br><input type='submit' value='Actualizar'><br><input type='submit' value='Eliminar'></td>";}
		 		echo "<tr>";
		 		$i++;
		 	}
		?>	   
				<tr>
		 		<td class='item-tabla-fon-bco'>NUEVO</td>
		 		<td class='item-tabla-fon-bco'>
		 		<input type='text' name='dimension_' class='input-generico' value='' size=80>
		 		<input type='hidden' name='id_dim_' class='input-generico' value=''>
		 		</td>
		 		<!--
		 		<td class='item-tabla-fon-bco'>
		 		<input type='text' name='promedio_' class='input-generico' value='' size=2>		 		
		 		</td>
		 		-->		 		
			</tr>
	  </table>
     </td>
	  </tr>
	  <tr>
	    <td colspan='4' align='center'>
		 <br>
		 <input type='submit' class='btn btn-custom' value='Agregar'>
		 <input type='submit' class='btn btn-custom' value='Actualizar'>
		 <input type='submit' class='btn btn-custom' value='Eliminar'>	    
	    </td>
	  </tr>
	  
	  
	  <tr><td colspan="4">&nbsp;</td></tr>
     <tr> 
     <td colspan="4" class="cuadro_tabla">
	  <table border="0" cellpadding="1" cellspacing="1" width="100%">
	   <tr><td class="titulo_tabla_des" colspan="2">Variables de Segmentacion</td></tr>
 <tr><td>&nbsp;</td></tr>
<?php 
 	/*********************************************/
 	/*  mantenedor segmentacion					 ***/
	/*******************************************/
	   $sqls ="select id, name, title from slorg_segmentaciones where id_cliente=".$idCliente." order by id ";
	 	$rs1=mysql_query($sqls, $conexion) or die(mysql_error());

		while ($rowp=mysql_fetch_array($rs1))
		{
			echo "<tr>";
			echo "<td class='cuadro_tabla'>";
			echo "<table border='0' cellpadding='1' cellspacing='1' width='100%'>";
	   		echo "<tr><td class='encabezado-tabla-fon-gris-normal' colspan='1'>
	   				<input type='text' name='seg_title_".$rowp['id']."' class='input_generico textoNegro' value='".$rowp['title']."'>
	   				<input type='hidden' name='seg_name_".$rowp['id']."' class='input_generico' value='".$rowp['name']."'>";
			echo "<button type='button'  class='btn btn-custom' onclick='eliminarVariableSegmentacion(".$rowp['id'].")'>Eliminar la variable completa</button>";
			echo "</td>";
			echo "<td class='encabezado-tabla-fon-gris-normal' colspan='2'>Eliminar</td>";
			echo "</tr>";
			$sqlso ="  SELECT a.id, a.value, a.texto 	
					FROM slorg_segmentacion_opciones a INNER JOIN  slorg_segmentaciones b ON a.segmentacion_id = b.id
					  WHERE a.segmentacion_id='".$rowp['id']."' AND b.id_cliente=".$idCliente;
		 	$rsso=mysql_query($sqlso, $conexion) or die(mysql_error());
			$k=0;	
			while ($rowso=mysql_fetch_array($rsso)) 
			{				
			  echo "<tr><td class='item-tabla-fon-bco'>";
			  echo "<input type='text' name='seg_opc_text_".$rowp['id']."_".$rowso['id']."' class='input-generico' size='80' value='".$rowso['texto']."'>
				  <input type='hidden' name='seg_opc_value_".$rowp['id']."_".$rowso['id']."' value='".$rowso['value']."'>
				  </td>";
			  echo "<td class='item-tabla-fon-bco' width='30%'>";
		 	  echo "<input type='checkbox' name='del_seg_opc_".$rowso['id']."' value='".$rowso['id']."'>";
		 	  echo "</td>";			  
			  echo "</tr>";			  	 
			  }
			  echo "<tr><td><input type='submit'  class='btn btn-custom' value='Actualizar'><input  class='btn btn-custom' type='submit' value='Eliminar las opciones seleccionadas'></td></tr>";
			  //nuevo elemento			
			  echo "<tr><td class='item-tabla-fon-bco'>NUEVO:&nbsp;";
			  echo "<input type='text' name='nuevo_seg_opc_text_".$rowp['id']."' class='input-generico' size='80' value=''></td>";
			  echo "<td class='item-tabla-fon-bco' rowspan='10' align='center' valign='middle'><input  class='btn btn-custom' type='submit' value='Agregar'></td>";
			  echo "</table>";
			  echo "</td>";
			  echo "</tr>";
			  $k++;
			}   
			
	   ?>	   
	   <tr>
	   <td class="cuadro_tabla">
	   <table border="0" cellpadding="1" cellspacing="1" width="100%">
	   <tr><td class="encabezado-tabla-fon-gris-normal" colspan="2">Agregar otra Variable de Segmentacion</td></tr>
	   <tr><td class='encabezado-tabla-fon-gris-normal'>Nombre</td>
	   	 <td class='item-tabla-fon-bco'><input type='text' name='nombre_vg' class='input-generico' size='50'>&nbsp;Ej. Tipo de Educacion</td>
	   </tr>	 
	   <?php
		for($z=1;$z<=10;$z++) 
		{
		  echo "<tr><td class='encabezado-tabla-fon-gris-normal'>Variable N&deg; $z</td>";
   	  echo "<td class='item-tabla-fon-bco'><input type='text' name='nueva_vg_$z' class='input-generico' size='80'></td>";
   	  echo "</tr>";	 
	   }
	   ?>

	   <tr>
	     <td class="item-tabla-fon-bco" colspan="2" align='center'>
	       <input  class='btn btn-custom' type='submit' value='Agregar'>
	     </td>
	   </tr>	   
	   
		</table>
		</td>
		
		</tr>
	
	  </table>
	  </td>
	  </tr>	
     <tr><td colspan="4">&nbsp;</td></tr>
     <tr> 
     <td colspan="4" class="cuadro_tabla">
	  <table style="border-collapse: collapse;" border="0" cellpadding="1" cellspacing="1" width="777px">
	   <tr><td class="titulo_tabla_des" colspan="5">Preguntas del Formulario</td></tr>
		<tr><td class="encabezado-tabla-fon-gris-normal">N&deg;</td><td class="encabezado-tabla-fon-gris-normal">Pregunta</td><td class="encabezado-tabla-fon-gris-normal" colspan="1">Dimension</td>
<td class="encabezado-tabla-fon-gris-normal" colspan="1">Eliminar</td>
<td class="encabezado-tabla-fon-gris-normal" align="center" colspan="1">Acci&oacute;n</td>
</tr>		
		<?php
		
		/*********************************************/
		/**  MANTENEDOR PREGUNTAS de CLASIFICACION  **/
		/*********************************************/
		
		$sqlp ="select a.n,a.pregunta,a.dimension,a.posicion,a.id from dpp_slorg_preguntas a inner join slorg_dimension b ON a.dimension = b.id  where b.id_cliente= ".$idCliente." order by posicion";
 	 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
 	 	$i=1;	
	 	while ($rowp=mysql_fetch_array($rs1)) 
	  	{
	  		echo "<tr >";
	  		echo "<td style='padding-bottom:14px;'  class='item-tabla-fon-bco' align='center'>".$rowp[3]."&nbsp;";	  		
	  		if ($rowp[3]>1){echo "<a href='dpp_admin.php?id=$rowp[4]&pos_act=$rowp[3]&pos_fut=".($rowp[3]-1)."&acceso_formulario=".$acceso_formulario."&x=0&id_cliente=".$idCliente."&tabActual=3'><img src='images/arriba.gif' height='10px' width='10px' alt='Subir Posicion' border='0'></a>-";}
	  		if ($rowp[3]<60){echo "<a href='dpp_admin.php?id=$rowp[4]&pos_act=$rowp[3]&pos_fut=".($rowp[3]+1)."&acceso_formulario=".$acceso_formulario."&x=1&id_cliente=".$idCliente."&tabActual=3'><img src='images/abajo.gif' height='10px' width='10px' alt='Bajar Posicion' border='0'></a>";}
	  		echo "</td>";
	  		echo "<td style='padding-bottom:18px;' class='item-tabla-fon-bco' align='left'><input type='text' name='p_$i' value='".$rowp[1]."' size=60 class='input-generico'>";
			echo "<input name='dim_id_".$i."'  type='hidden' value='".$rowp[4]."'></td>";
			echo "<td  class='item-tabla-fon-bco' align='left'>";
			echo "<select name='dim_$i' class='input-generico' style='width:200px'>";
					$sqld ="select id,dimension from slorg_dimension where id_cliente = ".$idCliente;
 	 				$rsd=mysql_query($sqld, $conexion) or die(mysql_error());	
	 				while ($rowd=mysql_fetch_array($rsd)) 
	  				{
	  					if ($rowp[2]==$rowd[0]){echo "<option value='$rowd[0]' selected>".$rowd[1]."</option>";}
	  					else {echo "<option value='$rowd[0]'>".$rowd[1]."</option>";}	  					
	  				}				
			echo "</select>";
			echo "&nbsp;</td>";		
			echo "<td class='item-tabla-fon-bco' width='30%'>";
		 		echo "<input type='checkbox' name='del_p_$i' value='$rowp[4]'>";
		 		echo "</td>";	
			echo "<td style='padding-bottom:15px;'  class='item-tabla-fon-bco' colspan=2 align='center'><input  class='btn btn-custom' type='submit' value='Actualizar'></td>";	  		
	  		echo "</tr>";
	  		$i++;
	  	}	

			echo "<tr>";
	  		echo "<td class='item-tabla-fon-bco' valign='middle' align='center'>NUEVO</td>";
	  		echo "<td class='item-tabla-fon-bco' valign='middle' align='left'><input type='text' name='p_' value='' size=60 class='input-generico'></td>";

			echo "<td class='item-tabla-fon-bco' valign='middle' style='padding-top:17px;' align='left'>";
			echo "<select name='dim_' class='input-generico' style='width:200px'>";
					$sqld ="select id,dimension from slorg_dimension where id_cliente = ".$idCliente;
 	 				$rsd=mysql_query($sqld, $conexion) or die(mysql_error());	
	 				while ($rowd=mysql_fetch_array($rsd)) 
	  				{
	  					if ($rowp[2]==$rowd[0]){echo "<option value='$rowd[0]' selected>".$rowd[1]."</option>";}
	  					else {echo "<option value='$rowd[0]'>".$rowd[1]."</option>";}
	  					
	  				}				
			echo "</select>";
			echo "&nbsp;</td>";

		?>
		<tr><td class='item-tabla-fon-bco' colspan=5 align="center"><input  class='btn btn-custom' type='submit' value='Actualizar'></td></tr>	  
	  </table>
	  </p>
	  </div>	
     </div>           
        </td>
      </tr>
     <tr><td colspan="4">&nbsp;</td></tr>

     <tr> 
     <td colspan="4" class="cuadro_tabla">
     <table border="0" cellpadding="1" cellspacing="1" width="777px">
	   <tr><td class="titulo_tabla_des" colspan="4">Preguntas 61 a la 63</td></tr>
	   <tr><td class='item-tabla-fon-bco' colspan="4">
		<?php
		$sqlp ="select p61,p62,p63 from slorg_admin where id_cliente=".$idCliente;
 	 	$rs1=mysql_query($sqlp, $conexion) or die(mysql_error());
	 	$rowp=mysql_fetch_array($rs1);
	 	if ($rowp[0]==1){$chk_61=' checked ';} else { $chk_61= ' ';}
	 	if ($rowp[1]==1){$chk_62=' checked ';} else { $chk_62= ' ';}
	 	if ($rowp[2]==1){$chk_63=' checked ';} else { $chk_63= ' ';}
		?>
	   <input type="checkbox" name="p61" value="1" <?php echo $chk_61;?>>Mostrar &Iacute;tem Priorizaci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   <input type="checkbox" name="p62" value="1" <?php echo $chk_62;?>>Mostrar &Iacute;tem de Comentarios de Fortaleza y Debilidades&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   <input type="checkbox" name="p63" value="1" <?php echo $chk_63;?>>Mostrar &Iacute;tem Evaluacion Impacto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   <br>
	   
	   </td></tr>
	   <tr><td class="item-tabla-fon-bco" colspan="4" align='center'><input  class='btn btn-custom' type='submit' value='Actualizar'></td></tr>	   
	   
	   </table>
	  </td>
	  </tr>	
      <tr>
        <td width="8%"></td>
        <td colspan="3"></td>
      </tr>
      <tr>
        <td colspan=4>&nbsp;</td>
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

			     <!--<form id='formUsuario' name='formUsuario' action='usuarios.php' method="POST" target="_blank">-->
			     <form id='formUsuario' name='formUsuario' action='usuarios.php' method="POST">			     	
			        <input type="hidden" name="accion_mantenedor_usuario" id="accion_mantenedor_usuario" value=''>
			        <input type="hidden" name="edicion_rut" id="edicion_rut" size="12" value=''>			    	
			      </form>

<script>
  var sufijo_error = "_error";
  $(document).ready(function(){
    $('#edit_rut').Rut({
      on_error: function(){
        if($('#edit_rut').val()=='999-k' || $('#edit_rut').val()=='999-K')
        return;
        $("#edit_rut"+sufijo_error).show();
      },
      on_success: function(){
        $("#edit_rut"+sufijo_error).hide();
      },
      format_on: 'keyup'
    });  	
  	
  	
  })	
</script>

</body>
</html>
            
            
            
