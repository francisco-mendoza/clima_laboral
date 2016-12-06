<?php include("header.php"); ?>
<?php include("consultas.php"); ?>
<link rel="Stylesheet" type="text/css" media=all href="estiloIndex.css">
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">





<div class="sub-bar" style="">
  <div class="contenido-sub-bar">
  	<div>
  		
  		<span class="fa fa-home" style="color:#914C09"> SLORG</span>
  		<span>|</span>
  		<span>Administración</span>
  		<span>|</span>
  		<span>Módulo Para Instalación De Nuevos Clientes</span>

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






<!------------------------ MENU CENTRAL ------------------------------------------>
<div style="width:40%; margin: 0 auto;">
    <div class="navbox-tiles" style="">

      <a href="agregar_cliente.php" class="tile">
      <div class="icon"><i class="fa fa-home"></i></div>
      <span class="title">Agregar Nuevo Cliente</span>
      </a>

      <a href="cargos.php" class="tile">
      <div class="icon"><i class="fa fa-building-o"></i></div>
      <span class="title">Cargo</span>
      </a>

      <a href="estamentos.php" class="tile">
      <div class="icon"><i class="fa fa-book"></i></div>
      <span class="title">Estamento</span>
      </a>

      <a href="unidades.php" class="tile">
      <div class="icon"><i class="fa fa-group"></i></div>
      <span class="title">Unidad</span>
      </a>

      <a href="usuarios.php" class="tile">
      <div class="icon"><i class="fa fa-user"></i></div>
      <span class="title">Usuario</span>
      </a>

      <a href="#" class="tile">
      <div class="icon"><i class="fa fa-cog"></i></div>
      <span class="title">Configuraciones</span>
      </a>

    </div>

</div>




<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script> 
<script>
(function () {
    $(document).ready(function () {
        $('#navbox-trigger').click(function () {
            return $('#navigation-bar').toggleClass('navbox-open');
        });
        return $(document).on('click', function (e) {
            var $target;
            $target = $(e.target);
            if (!$target.closest('.navbox').length && !$target.closest('#navbox-trigger').length) {
                return $('#navigation-bar').removeClass('navbox-open');
            }
        });
    });
}.call(this));
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>






<br><br><br><br><br><br><br><br><br>



<?php include("footer.php"); ?>