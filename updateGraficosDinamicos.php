<?php
session_start();
require_once("include/dbconection.php");

$idCliente       = $_SESSION['id_cliente_seleccionado'];

$conexion = DAL::conexion();

if(isset($_POST['antiguedad']))
{
    $antiguedad      = $_POST['antiguedad'];
    $sql = "UPDATE slorg_config_dinamicos SET antiguedad='$antiguedad' WHERE idCliente=$idCliente;";
    $rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}
if(isset($_POST['estamento']))
{
    $estamento      = $_POST['estamento'];
    $sql2 = "UPDATE slorg_config_dinamicos SET estamento='$estamento' WHERE idCliente=$idCliente;";
    $rs2 = mysql_query($sql2,$conexion) or die(mysql_error().$sql2);
}
if(isset($_POST['antiguedad']))
{
    $calidadJuridica      = $_POST['calidadJuridica'];
    $sql3 = "UPDATE slorg_config_dinamicos SET calidadJuridica='$calidadJuridica' WHERE idCliente=$idCliente;";
    $rs3 = mysql_query($sql3,$conexion) or die(mysql_error().$sql3);
}
if(isset($_POST['antiguedad']))
{
    $sexo      = $_POST['sexo'];
    $sql4 = "UPDATE slorg_config_dinamicos SET sexo='$sexo' WHERE idCliente=$idCliente;";
    $rs4 = mysql_query($sql4,$conexion) or die(mysql_error().$sql4);
}
if(isset($_POST['antiguedad']))
{
    $centroCosto      = $_POST['centroCosto'];
    $sql5 = "UPDATE slorg_config_dinamicos SET centroCosto='$centroCosto' WHERE idCliente=$idCliente;";
    $rs5 = mysql_query($sql5,$conexion) or die(mysql_error().$sql5);
}
if(isset($_POST['antiguedad']))
{
    $unidadDesempeno      = $_POST['unidadDesempeno'];
    $sql6 = "UPDATE slorg_config_dinamicos SET unidadDesempeno='$unidadDesempeno' WHERE idCliente=$idCliente;";
    $rs6 = mysql_query($sql6,$conexion) or die(mysql_error().$sql6);
}

?>