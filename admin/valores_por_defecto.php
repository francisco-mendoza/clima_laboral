<?php include("header.php"); ?>
<?php include("consultas.php"); ?>





<div class="sub-bar" style="">
  <div class="contenido-sub-bar">
  	<div>
  		
  		<span class="fa fa-home" style="color:#914C09"> SLORG</span>
  		<span>|</span>
  		<span>Administración</span>
  		<span>|</span>
  		<span>Agregar Parametros por Defecto</span>
  		<span>|</span>
  		<span>PASO 2</span>

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

	<div class="panel-heading" style="/*background-color: #F57E1B*/"  ><h4 style="text-align: center">Agregar Parametros</h4></div>
	
	

	<div class='alert alert-success'>
		 <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			Cliente Agregado.
	</div>
	
	
	<div style="padding: 10px; margin: 0 auto;text-align: center">
		<p style="float:left; text-align: center">Cliente: &nbsp; </p>
		<input type="text" value="
			<?php 
				$ultimoCliente = ultimoCliente();
				echo $ultimoCliente['nombre_cliente'];
			?>
		" disabled class="form-control" style="width:50%">
	</div>

	
	<p style="padding:10px">Haga click en siguiente para agregar todos los parametros por defecto al nuevo Cliente </p>



<?php 
	$idCliente = $ultimoCliente['id_cliente'];
	
	$validaSiHayValores = validaSiHayValores($ultimoCliente['id_cliente']);

	if(isset($_POST['valorHidden']))
	{
		

		if(empty($validaSiHayValores))
		{
			agregarParametroGraficoCliente($idCliente);
			agregarAdmin($idCliente);
			agregarAntiguedad($idCliente);
			agregarCalidadJuridica($idCliente);
			agregarDimension($idCliente);
			agregarGrado($idCliente);
			agregarSegmentaciones($idCliente);
			agregarUnidadNivel($idCliente);
			agregarElementoFormulario($idCliente);


			//echo "parametros agregados correctamente";

			echo "<script type='text/javascript'>
						
						window.location.href='update_automatico_elementoFormulario.php';
				  </script>";


		}
		else
		{

			echo "<div class='alert alert-danger alert-dismissible' role='alert'>
					  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					  <strong>Error!</strong> Este cliente ya tiene los valores por defecto.
					  <a href=''>Salir</a>
					</div>";
			//redirigir a pagina principal
		}
	}
	else
	{
?>

			<form action="valores_por_defecto.php" method="POST">
				<input type="hidden" value="1" name="valorHidden">
				<input type="submit" class="btn btn-success" value="Siguiente >>">
			</form>
<?php
		//echo "Error, sin post definido";
		//redirigir
	}



?>




</div>



<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>


<?php include("footer.php"); ?>


			
