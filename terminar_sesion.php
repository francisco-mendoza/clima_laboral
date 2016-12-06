<?php

session_start();

// Destruir todas las variables de sesión.
$_SESSION = array();

// Finalmente, destruir la sesión.
session_destroy();

$destino = "http://www.psicus.cl"; 	
header('Location: ' .$destino); // hacemos el redireccionamiento

?>