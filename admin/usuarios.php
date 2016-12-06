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
  		<span>Usuarios</span>

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



<div style="width: 99%; margin: 0 auto;">


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
			window.location.href='usuarios.php?cliente='+x;
		}

		</script>

		

		<h3 style="float:left;	"><?php echo verClienteActual($idCliente); ?> </h3>




	<button type="button" class="btn btn-success" data-target="#agregarEstamento" data-toggle="modal" style="padding-bottom: 5px; float: right">
		Agregar Usuario
	</button>

	

	<br><br><br><br>

	<script>
		$(document).ready(function() 
				{
		    		$('#tablaUsuarios').DataTable();
				} 
				);
	</script>

	
		<table class="table table-bordered table-striped table-hover dataTable" id="tablaUsuarios" cellspacing="0" width="100%">
			<thead>
				<tr style="background-color: #FCBF96">
					<th>Rut</th>
					<th>Nombre</th>
					<th>Apellido Paterno</th>
					<th>Apellido Materno</th>
					<th>Unidad</th>
					<th>Sexo</th>
					<th>Estamento</th>
					<th>Cargo</th>
					<th>Email</th>
					<th>Telefono</th>
					<th>Username</th>
					<th>Acción</th>					
				</tr>
			</thead>

			<tbody>
			
				<?php 

				//echo $idCliente;
				
				$verUsuario = verUsuario($idCliente);

				while($usuario = mysql_fetch_array($verUsuario))

					{ ?>

						<tr>
							<td><?php echo $usuario['rut']." - ".$usuario['dv']; ?></td>
							<td><?php echo $usuario['nombre']; 			 		 ?></td>
							<td><?php echo $usuario['apellido_paterno']; 		 ?></td>
							<td><?php echo $usuario['apellido_materno']; 		 ?></td>
							<td><?php echo $usuario['nombre_unidad'];		 	 ?></td>
							<td><?php echo $usuario['sexo'];  					 ?></td>
							<td><?php echo $usuario['nombre_estamento']; 		 ?></td>
							<td><?php echo $usuario['nombre_cargo'];		 	 ?></td>
							<td><?php echo $usuario['email']; 					 ?></td>
							<td><?php echo $usuario['telefono']; 				 ?></td>
							<td><?php echo $usuario['username']; 				 ?></td>
							<td>
								<button type="button" class="btn btn-primary btn-lg">
							  		<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
							    </button>
								<button type="button" class="btn btn-danger btn-lg">
							  		<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
							   </button>
							</td>
						</tr>

				<?php 
					} 
				?>

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
			window.location.href='usuarios.php?cliente='+x;
		}

		</script>
</div>
<?php } ?>






<!--------------------------  MODAL PARA AGREGAR    ---------------------------->
<div class="modal fade" id="agregarEstamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

	<form action="usuarios.php?cliente=<?php echo $idCliente;?>" method="POST">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregar Usuario</h4>
      </div>
      <div class="modal-body">


			

			<fieldset>
				<div class="form-inline">
					<div class="form-group" style="width:50%">
						
						<label>Rut</label> (Ingresa sin puntos ni guión)<br>
						<input type="text" name="rut" class="form-control" style="width: 60%; float:left;" required/><p style="float:left;">-</p>
						<input type="text" name="dv" class="form-control" style="width: 20%; " required> 
					</div>


					<div class="form-group">
						<label>Nombre</label><br>
						<input type="text" name="nombre" class="form-control" placeholder="" style="" required>
					</div>
				</div>
				<div class="form-inline">
					<div class="form-group" style="">
						<label>Apellido Paterno</label><br>
						<input type="text" name="apellidoPaterno" class="form-control" placeholder="" style=" " required>
					</div>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<div class="form-group">
						<label>Apellido Materno</label><br>
						<input type="text" name="apellidoMaterno" class="form-control" placeholder="" style="" required>
					</div>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<div class="form-group">
						<label>Sexo</label><br>
						<label class="radio-inline">
						  <input type="radio" name="sexo" value="M" style="" required> M
						</label>
						<label class="radio-inline">
						  <input type="radio" name="sexo" value="F" style="" required> F
						</label>	
					</div>
				</div>
				<div class="form-inline">
					<div class="form-group" style="width:50%" >
						<label>Email</label><br>
						<input type="email" name="email" class="form-control" placeholder="" style="width:100%" required>
					</div>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<div class="form-group">
						<label>Teléfono</label><br>
						<input type="text" name="telefono" class="form-control" placeholder="" style="" required>
					</div>
				</div>
				
				<hr>

				
				<div class="form-group" style="">
						<label>Unidad *</label>
						<select class="form-control" name="unidad" style="width: 100%;" required>
							<option selected disabled> Seleccione Unidad</option>				
						<?php 
							$verUnidades = verUnidades($idCliente);

							while($unidad = mysql_fetch_array($verUnidades)) {
								echo "<option value='".$unidad['id']."' >";
								echo $unidad['nombre'];
								echo "</option>";
							}
						?>
						</select>
				</div>
				
				
				<div class="form-group" style="">
						<label>Estamento *</label>
						<select class="form-control" name="estamento" style="width: 100%;" required>
							<option selected disabled> Seleccione Estamento</option>
										
						<?php 
							$verEstamentos = verEstamentos($idCliente);

							while($estamento = mysql_fetch_array($verEstamentos)) {
								echo "<option value='".$estamento['id_estamento']."' >";
								echo $estamento['nombre_estamento'];
								echo "</option>";
							}
						?>
						</select>
				</div>

				<div class="form-inline">
					<div class="form-group" style="" >
							<label>Cargo *</label><br>
							<select class="form-control" name="cargo" style="" required>
								<option selected disabled> Seleccione Cargo</option>
											
							<?php 
								$verCargos = verCargos($idCliente);

								while($cargo = mysql_fetch_array($verCargos)) {
									echo "<option value='".$cargo['id_cargo']."' >";
									echo $cargo['nombre_cargo'];
									echo "</option>";
								}
							?>
							</select>
					</div>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<div class="form-group" style="" >
							<label>Tipo Usuario *</label><br>
							<select class="form-control" name="tipoUsuario" style="" required>
								<option selected disabled> Seleccione tipo usuario</option>
											
							<?php 
								$verTipoUsuario = verTipoUsuario();

								while($tipoUsuario = mysql_fetch_array($verTipoUsuario)) {
									echo "<option value='".$tipoUsuario['id_tipo_usuario']."' >";
									echo $tipoUsuario['nombre_tipo_usuario'];
									echo "</option>";
								}
							?>
							</select>
					</div>
				</div>
				<br>
				<div class="form-inline">
					<div class="form-group" style="float:left">
						<label>Username</label><br>
						<input type="text" name="username" class="form-control" placeholder="" style="" required>
					</div>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<div class="form-group">
						<label>Password</label><br>
						<input type="password" name="password" class="form-control" placeholder="" style="" required>
					</div>
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
	
		//echo ultimoUsuario();

		if(isset($_POST['rut']))
		{
			$rut              = $_POST['rut'];
			$dv               = $_POST['dv'];
			$nombre           = $_POST['nombre'];
			$apellidoPaterno  = $_POST['apellidoPaterno'];
			$apellidoMaterno  = $_POST['apellidoMaterno'];
			$unidad           = $_POST['unidad'];
			$sexo             = $_POST['sexo'];
			$estamento        = $_POST['estamento'];
			$cargo            = $_POST['cargo'];
			$email            = $_POST['email'];
			$telefono         = $_POST['telefono'];
			$username         = $_POST['username'];
			$password         = $_POST['password'];

			$tipoUsuario      = $_POST['tipoUsuario'];




			insertarUsuario($rut,$dv,$nombre,$apellidoPaterno,$apellidoMaterno,$unidad,$sexo,
							$estamento,$cargo,$email,$telefono,$username,$password,$idCliente); 

			$idUltimoUsuario = ultimoUsuario();

			insertarRelTipoUsuario($idUltimoUsuario,$tipoUsuario);

			//insertarTipoUsuario($tipoUsuario);

			echo "<script type='text/javascript'>
						window.location.href='usuarios.php?cliente=".$idCliente."&agregado=true';
				  </script>";	
		
		}
	



 ?>


<?php include("footer.php"); ?>