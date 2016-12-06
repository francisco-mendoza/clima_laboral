<?php


include("include/dbconection.php");



$opcion  = $_GET['opcion'];


if($opcion=2)
{



mysql_query("update slorg_elemento_formulario SET fecha_fin = '2015-12-10 12:00:00' 
				WHERE slorg_elemento_formulario.id = 1");


}

else
{
	mysql_query("update slorg_elemento_formulario SET fecha_fin = '2015-12-30 12:00:00' 
				WHERE slorg_elemento_formulario.id = 1");
}