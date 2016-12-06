<?php include("header.php"); ?>
<?php include("consultas.php"); ?>




<div class="sub-bar" style="">
  <div class="contenido-sub-bar">
  	<div>
  		
  		<span class="fa fa-home" style="color:#914C09"> SLORG</span>
  		<span>|</span>
  		<span>Administración</span>
  		<span>|</span>
  		<span>Agregar Cliente</span>
  		<span>|</span>
  		<span>PASO 1</span>

  		<a rel="tooltip" class="fa fa-question-circle no-load" style="float:right" data-toggle="tooltip" data-placement="left" title="Necesitas ayuda ? " href="#"></a>
  		
  	</div>
  </div>
  
</div>

<br><br>

<style type="text/css">
	
	.sub-bar {
    background: #E2E2E2;
    margin:0 auto;
    /*margin-bottom: 30px;*/
    /*padding: 10px 30px 10px 30px;*/
    /*float: left;*/
    width: 70%;
}
    .contenido-sub-bar{
    	padding:15px; 
    }

</style>





<div class="panel panel-default" style="width:50%; margin: 0 auto;">

	
	<div class="panel-heading" ><h4 style="text-align: center">Insertar Cliente</h4></div>

	<div class="panel-body">

		<form action="agregar_cliente.php" method="POST">
			
			<div class="form-group">
				<label>Nombre Cliente *</label>
				<input type="text" name="nombreCliente" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Rut Cliente </label> <br>
				<input type="text" name="rutCliente" class="form-control" style="width:40%; float: left">
				<div style="float:left"> &nbsp - &nbsp</div>
				<input type="text" name="dvCliente" class="form-control" style="width:10%">
			</div>
			<div class="form-group">
				<label>Nombre Contraparte</label>
				<input type="text" name="nombreContraparte" class="form-control">
			</div>
			<div class="form-group">
				<label>Correo Contraparte</label>
				<input type="email" name="emailContraparte" class="form-control">
			</div>
			<div class="form-group">
				<label>Teléfono Contraparte</label>
				<input type="text" name="telefonoContraparte" class="form-control">
			</div>
			<div class="form-group">
				<label>Estado</label>
				<select class="form-control" name="estado">
					<option value="1" select> Activo </option>
					<option value="0"> Inactivo </option>
				</select>
			</div>
			<div class="form-group">
				<label>Tipo Formulario *</label>
				<select class="form-control" name="tipoForm">
					<option selected disabled> Seleccione </option>
								
				<?php 
					$tipoFormularios = SeleccionarTipoFormulario();

					while($formTipo = mysql_fetch_array($tipoFormularios)) {
						echo "<option value='".$formTipo['id_tipo_formulario']."' >";
						echo $formTipo['nombre_tipo_formulario'];
						echo "</option>";
					}
				?>
				</select>
			</div>

			

			<input type="submit" value="Agregar Cliente / Siguiente >>" class="btn btn-success" style="float:right">



		</form>
		<button class="btn btn-danger" onclick="<script>window.close(); </script>">Salir</button>
    </div>

</div>


<?php 

	
	if(isset($_POST['nombreCliente']))
	{
		$nombre               = $_POST['nombreCliente'];
		$rut                  = $_POST['rutCliente'];
		$dv                   = $_POST['dvCliente'];
		$nombreContraparte    = $_POST['nombreContraparte'];
		$correoContraparte    = $_POST['emailContraparte'];
		$telefonoContraparte  = $_POST['telefonoContraparte'];
		$estado               = $_POST['estado'];
		$tipoForm             = $_POST['tipoForm'];

		$datosNuevoCliente = array($nombre,$rut,$dv,$nombreContraparte,$correoContraparte,$telefonoContraparte,$estado,$tipoForm);


		if($nombre!='')
		{
			agregarCliente($datosNuevoCliente);
			echo "<div class='alert alert-success'>
				  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				  Cliente Agregado  .
				  </div>  ";

			echo "<script type='text/javascript'>
						window.location.href='valores_por_defecto.php?paso2=1';
				  </script>";	  
		}
		else
		{
			//header('Location: /agregar_cliente.php');
		}
	}


?>


<?php include("footer.php"); ?>

