<?php 
//include("../include/dbconection.php"); 

include("consultas.php"); 
//include("../include/dbconection.php"); 

$ultimoCliente = ultimoCliente();

$idCliente = $ultimoCliente['id_cliente'];

//Rescatamos los valores de las dos funciones de formularios
$form1     =form1($idCliente);
$formIstas = formIstas($idCliente);

//echo "Formulario1: ".form1($idCliente)."<br>";

//echo "id cliente: ".$idCliente."<br>";
//echo $formIstas;
autoUpdateElementoForm($form1,$idCliente);
autoUpdateElementoFormIstas($formIstas,$idCliente);
//echo "actualizado";

echo "<script type='text/javascript'>
			alert('Cliente Actualizado, recuerde agregar Cargos, Estamentos, Unidad y Usuarios');
			window.location.href='index.php';
	  </script>";




//ID CLiente Prueba
//$idCliente = 7;










 ?>