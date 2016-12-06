<?php include("header.php"); ?>
<?php include("consultas.php"); ?>


<?php 

    // recuperamos el cliente de la url
	if(isset($_GET['cliente'])) {
	$idCliente = $_GET['cliente'];
}

?>



<style type="text/css">
	
	#table tbody tr:hover{
		background-color:#FEC88F;
	} 

	#table thead tr{
		background-color:#FCE6D9;
	} 

</style>



<div class="sub-bar" style="">
  <div class="contenido-sub-bar">
  	<div>
  		
  		<span class="fa fa-home" style="color:#914C09"> SLORG</span>
  		<span>|</span>
  		<span>Administración</span>
  		<span>|</span>  		
  		<span>Estamentos</span>

  		<a rel="tooltip" class="fa fa-question-circle no-load" style="float:right" data-toggle="tooltip" data-placement="left" title="Necesitas ayuda ? " href="#"></a>
  		
  	</div>
  </div>
  
</div>

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



<?php if(isset($_GET['cliente'])) {?>



<div style="width: 60%; margin: 0 auto;">

<br>


		<div class="form-group" style="padding-left: 30%" >
				<label>Seleccione Cliente </label>
				<select class="form-control" id="seleccionaC" name="tipoUsuario" style="width: 40%;" onchange="SeleccionaCliente()">
					<option selected disabled> Seleccione cliente</option>
								
				<?php 
					$verClientes = verClientes();

					while($cliente = mysql_fetch_array($verClientes)) {
						echo "<option value='".$cliente['id_cliente']."' >";
						echo $cliente['nombre_cliente'];
						echo "</option>";
					}
				?>
				</select>
		</div>



		<script type="text/javascript">

		function SeleccionaCliente()
		{
			var x = document.getElementById("seleccionaC").value;
   		    //document.getElementById("demo").innerHTML = "You selected: " + x;
			window.location.href='estamentos.php?cliente='+x;
		}

		</script>






	<h3 style="float:left;	"><?php echo verClienteActual($idCliente); ?> </h3>




	<button type="button" class="btn btn-success" data-target="#agregarEstamento" data-toggle="modal" style="padding-bottom: 5px; float: right">
		Agregar Estamento
	</button>

	

	<br><br><br><br>

	<script>
		$(document).ready(function() 
				{
		    		$('#tablaEstamentos').DataTable();
				} 
				);
	</script>

	
		<table class="table table-bordered table-striped table-hover dataTable" id="tablaEstamentos" cellspacing="0" width="100%">
			<thead>
				<tr style="background-color: #FCBF96">
					<th>Nombre Estamento</th>
					<th>Descripción</th>
					<th>Acción</th>
				</tr>
			</thead>

			<tbody>
			
	<?php 

	//echo $idCliente;
	
	$verEstamentos = verEstamentos($idCliente);

	while($estamento = mysql_fetch_array($verEstamentos))

	{ ?>

			<tr>
				<td><?php echo $estamento['nombre_estamento']; ?></td>
				<td><?php echo $estamento['descripcion_estamento']; ?></td>
				<td>
					<button type="button" class="btn btn-primary btn-lg">
				  		<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				   </button>
					<button type="button" class="btn btn-danger btn-lg">
				  		<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
				   </button>
				</td>
			</tr>

	<?php } ?>
		</tbody>
	
		</table>


</div>




<?php } else{?>
<div style="margin: 0 auto;padding-left: 40% ">
	<div class="form-group" style="" >
				<label>Seleccione Cliente </label>
				<select class="form-control" id="seleccionaC" name="tipoUsuario" style="width: 40%;" onchange="SeleccionaCliente()">
					<option selected disabled> Seleccione Cliente</option>
								
				<?php 
					$verClientes = verClientes();

					while($cliente = mysql_fetch_array($verClientes)) {
						echo "<option value='".$cliente['id_cliente']."' >";
						echo $cliente['nombre_cliente'];
						echo "</option>";
					}
				?>
				</select>
		</div>



		<script type="text/javascript">

		function SeleccionaCliente()
		{
			var x = document.getElementById("seleccionaC").value;
   		    //document.getElementById("demo").innerHTML = "You selected: " + x;
			window.location.href='estamentos.php?cliente='+x;
		}

		</script>
</div>
<?php } ?>





<!--------------------------  MODAL PARA AGREGAR    ---------------------------->
<div class="modal fade" id="agregarEstamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregar Estamento</h4>
      </div>
      <div class="modal-body">


	<form action="estamentos.php?cliente=<?php echo $idCliente;?>" method="POST">

	<fieldset>
		<div class="form-group">
			<?php echo $idCliente; ?>
			<label>Nombre Estamento</label>
			<input type="text" name="nombreEstamento" class="form-control" placeholder="Nombre">
		</div>
		<div class="form-group">
			<label>Descripción</label>
			<input type="text" name="descripcionEstamento" class="form-control" placeholder="Descripción">
		</div>
	</fieldset>
	



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" style="float:left">Cancelar</button>
		<input type="submit" value="Agregar" class="btn btn-success">
		
		
      </div>
      </form>
    </div>
  </div>
</div>


<?php 
	
	

		if(isset($_POST['nombreEstamento']))
		{
			$nombreEstamento       = $_POST['nombreEstamento'];
			$descripcionEstamento  = $_POST['descripcionEstamento'];

			agregarEstamentos($nombreEstamento,$descripcionEstamento,$idCliente); 

			echo "<script type='text/javascript'>
						window.location.href='estamentos.php?cliente=".$idCliente."';
				  </script>";	
		
		}
	



 ?>

<?php include("footer.php"); ?>