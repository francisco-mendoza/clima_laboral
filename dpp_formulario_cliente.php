<?php
   include("include/dbconection.php");
   if(isset($_POST["cliente"]) && !empty($_POST["cliente"])){
     $sql = "select * from slorg_cliente where id_cliente=".$_POST["cliente"];
	 $rs1=mysql_query($sql, $conexion) or die(mysql_error());
	 $fila = mysql_fetch_assoc($rs1);
   } else {
     $fila = array();
   }

?>

<script>



	
</script>
<form name='formulario_edicion_cliente' id='formulario_edicion_cliente' action='consultas_ajax.php' target='frameEdicionCliente'  enctype="multipart/form-data">
<table class='table'>
  <tr>
	<td>Nombre Cliente</td>	
	<td>
  	  <input type='hidden' name='accion' id='accion' value='grabarCliente' >
  	  <input type='hidden' name='form_id_cliente' id='form_id_cliente' value='<?php if(isset($fila['id_cliente'])){ echo $fila['id_cliente']; } ?>' >		
	  <input type='text' name='form_name_cliente' id='form_name_cliente' value='<?php if(isset($fila['nombre_cliente'])){
	  	echo $fila['nombre_cliente'];
	  }?>' onkeyup="validarSoloTexto(this.id)"  obligatorio='1'>	
	</td>
  </tr>


  <tr>
	<td>Rut Cliente</td>	
	<td>
	  <input type='text' name='form_rut_cliente' id='form_rut_cliente' value='<?php if(isset($fila['rut_cliente'])){
	  	echo $fila['rut_cliente'];
	  }?>'  obligatorio='1'>	
	</td>
  </tr>

  <tr>
	<td>Logo Cliente</td>	
	<td>
      <input type="file" name="form_imagen_cliente" id="form_imagen_cliente"  obligatorio='0'>
	</td>
  </tr>

  <tr>
	<td>Nombre Contraparte</td>	
	<td>
	  <input type='text' name='form_nombre_contraparte' id='form_nombre_contraparte' value='<?php if(isset($fila['nombre_contraparte'])){
	  	echo $fila['nombre_contraparte'];
	  }?>'  obligatorio='1'>
	</td>
  </tr>


  <tr>
	<td>Correo Contraparte</td>	
	<td>
	  <input type='text' name='form_correo_contraparte' id='form_correo_contraparte' value='<?php if(isset($fila['correo_contraparte'])){
	  	echo $fila['correo_contraparte'];
	  }?>'  obligatorio='1'>
	</td>
  </tr>

  <tr>
	<td>Telefono Contraparte</td>	
	<td>
	  <input type='text' name='form_telefono_contraparte' id='form_telefono_contraparte' value='<?php if(isset($fila['telefono_contraparte'])){
	  	echo $fila['telefono_contraparte'];
	  }?>' obligatorio='0'>
	</td>
  </tr>

  <tr>
	<td>Estado</td>	
	<td>
	  <select id='form_id_estado' name='form_id_estado'  obligatorio='1'>
	  	<option value='-1'>Seleccione</option>
	  	<?php 
     	  $sql = "select * from slorg_estado ";
	 	  $rs1=mysql_query($sql, $conexion) or die(mysql_error());
	 	  while($fila1 = mysql_fetch_assoc($rs1)){
	  		echo "<option value='".$fila1['id_estado']."'";
			if(isset($fila['id_estado']) && $fila1['id_estado']==$fila['id_estado']){ echo "selected"; }
	  		echo ">";
	  		echo $fila1['nombre_estado']."</option>";	 	  	
	 	  }	  	
	  	?>	  		  	
	  </select>	
	</td>
  </tr>  
  <tr>
	<td colspan="2"	>
	  <div class='oculto mensajeFormularioEdicionCliente alert'>
	    	
	  </div>
	</td>
  </tr>  
</table>
</form>