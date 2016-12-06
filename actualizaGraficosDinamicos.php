<?php

session_start();
require_once("include/dbconection.php");


$idCliente       = $_SESSION['id_cliente_seleccionado'];
/*$estamento       = $_POST['estamento'];
$sexo            = $_POST['sexo'];
$antiguedad      = $_POST['antiguedad'];
$calidadJuridica = $_POST['calidadJuridica'];*/

//echo "cliente: ".$idCliente."<br>";

$conexion = DAL::conexion();
$sql = "SELECT * FROM slorg_config_dinamicos WHERE idCliente = $idCliente";
$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
//$row = mysql_fetch_row($rs);
//echo $row['0'];
while($fila  = mysql_fetch_assoc($rs)){
    //echo $fila['sexo'];
    $vector[] = array_map('utf8_encode', $fila);
}

echo json_encode($vector);

mysql_free_result($rs);

//Passando vetor em forma de json




?>