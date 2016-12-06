


<?php 

  session_start();  
  if ($_SESSION['usuario']==""){echo "session expirada! vuelva a logearse<br><br><a href='index.htm'>Inicio</a>";die();}

 ?>



<!doctype html>
<html>
<head>
  <title>Administración SLORG</title>

  <link rel="Stylesheet" type="text/css" media=all href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <!--<link rel="Stylesheet" type="text/css" media=all href="../include/datatable/jquery.dataTables.min.css">-->
  <link rel="stylesheet" type="text/css" href="../include/datatable/datatables.min.css">
  
  <link rel="Stylesheet" type="text/css" media=all href="estiloIndex.css">

  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="../include/datatable/datatables.min.js"></script>
  <script type="text/javascript" src="../include/bootstrap/js/bootstrap.min.js"></script>


</head>
<body>

<header>

<nav class="navbar navbar-default" style="background-color: #F9A770;">
  <div class="container-fluid" style="width: 70%; margin: 0 auto;">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <img src="../images/logosinrefl.png" height="50px" width="50px" style="float: left">
      <a class="navbar-brand" href="#"> Satisfacción Laboral  </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        
        <li><a href="index.php">Inicio</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mantenedores <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="cargos.php">Cargo</a></li>
            <li><a href="estamentos.php">Estamento</a></li>
            <li><a href="unidades.php">Unidad</a></li>
            <li><a href="usuarios.php">Usuario</a></li>
          </ul>
        </li>
      </ul>
      
      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

</header>