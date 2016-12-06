<?php


$m = (isset($_GET['m']))?$_GET['m']:'';
if ($m>4){$m=0;}

?>
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 9">
<meta name=Originator content="Microsoft Word 9">
<link rel=File-List href="./default_archivos/filelist.xml">
<link rel=Edit-Time-Data href="./default_archivos/editdata.mso">
<title>PSICUS Profesionales</title>

<link rel="Stylesheet" type="text/css" media=all href="include/bootstrap/css/bootstrap.min.css">
<link rel="Stylesheet" type="text/css" media=all href="beta2.css">
<link rel="Stylesheet" type="text/css" media=all href="include/custom.css">
<link rel="stylesheet" href="include/fontawesome/css/font-awesome.min.css">

<style>
html,
body {
   margin:0;
   padding:0;
   height:100%;
}
#container {
   min-height:100%;
   position:relative;
}
#header {
   /*background:#ff0;*/
   /*padding:10px;*/
}
#body {
   padding:10px;
   padding-bottom:60px;   /* Height of the footer */
}
#footer {
   position:absolute;
   bottom:0;
   width:100%;
   height:60px;   /* Height of the footer */
   /*background:#6cf;*/
}	
	
</style>

<script language="JavaScript">
var mm ='<?php echo $m;?>';

function msgError(){
  if (mm=='1')
  {
    alert('Usuario o clave no validos');
  }
  if (mm=='2')
  {
    alert('El Formato del email es invalido');
  }
  if (mm=='3')
  {
    alert('El Rut es incorrecto');
  }	
  if (mm=='4')
  {
    alert('El Formato del Username es invalido');
  }		
}
	
		function cmdValida()
		{
			var usuario = window.document.se.usuario.value;
			if( usuario == "" ){
				window.document.se.usuario.focus();
				alert("Debe ingresar un usuario.");
				return;
			}
			var clave = window.document.se.clave.value;
			if( clave == "" ){
				alert("Debe ingresar una clave de acceso.");
				window.document.se.clave.focus();
				return;
			}
			window.document.se.action="dpp_valida_usuario.php"
			window.document.se.method="POST"
			window.document.se.submit();
		}
		function MM_openBrWindow(theURL,winName,features) { //v2.0
			window.open(theURL,winName,features);
		}

		function MM_findObj(n, d) { //v4.0
			var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
				d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
			if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
			for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
			if(!x && document.getElementById) x=document.getElementById(n); return x;
		}
		<!--
		function MM_reloadPage(init) {  //reloads the window if Nav4 resized
			if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
			document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
			else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
		}
		MM_reloadPage(true);
		// -->
		function MM_showHideLayers() { //v3.0
			var i,p,v,obj,args=MM_showHideLayers.arguments;
			for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
			if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
			obj.visibility=v; }
		}

		function MM_swapImgRestore() { //v3.0
			var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
		}

		function MM_preloadImages() { //v3.0
			var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
			var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
			if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
		}

		function MM_swapImage() { //v3.0
			var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
			if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
		}
</script>
</head>

<body bgcolor="#e6e6e6" background="images/bg.gif" lang=ES link=navy
vlink=navy style='tab-interval:35.4pt' leftmargin=0 topmargin=0 onload='msgError();'>


<div id="container">
   <div id="header">

<table id="titulo" border="0"  align="center" cellpadding="0" cellspacing="0">
<tr>
  <td align="" nowrap class='contenedorBarraFront'>
  	
  	<table class='barraFront'>
  	  <tr>
  		<td>
  		  <a href='index.php'>	
			<img src="images/logosinrefl.png" height="50px" width="50px">
  		  </a>  			
  		</td>	
  		<td class='barraDer' nowrap>
  		  <a href='index.php' class='textoSinDecoracion'>  			
  		  <span class='tituloFront'>
  		  SATISFACCION<br>
  		  LABORAL.ORG
  		  </span>&nbsp;&nbsp;
  		  </a>
  		</td>
  		<td>
		<div>
			
			
		 </div>
  		</td>
  	  </tr>
  	</table>
  	

 
	</td>
	<td>	
	</td>
	<td>		
	</td>		
</tr>
</table>
   	
   	
   </div>
   <div id="body">
   	
   	
   	<!-------------------------------------------------------------------------------------------->
   	
<div class=Section1>


<form name=se>


<p class=MsoNormal><span style='font-size:8.5pt;font-family:Verdana;color:black;
display:none;mso-hide:all'><![if !supportEmptyParas]>&nbsp;<![endif]><o:p></o:p></span></p>

<div align=center>

<table border=0 cellspacing=0 cellpadding=0 bgcolor="#fafafa" style='mso-cellspacing:
 0cm;background:#FAFAFA;mso-padding-alt:0cm 0cm 0cm 0cm' id=Contenido>
 <tr style='height:262.5pt'>
  <td width="759" style='background:#FBFBFB;padding:0cm 0cm 0cm 0cm;height:262.5pt'>
  <p class=MsoNormal><span style='font-size:8.5pt;font-family:Verdana;
  color:#222222'>&nbsp; <o:p></o:p></span></p>
  <div align=center>
  <table border=0 cellspacing=4 cellpadding=0 width="73%" style='width:50.0%;
   mso-cellspacing:3.0pt'>
   <tr>
    <td style='background:#CCCCCC;padding:.75pt .75pt .75pt .75pt'>
    <div align=center>
    <table border=0 cellspacing=0 cellpadding=0 width="600px" height="170px" bgcolor=white
     style=';mso-cellspacing:0cm;background:white;mso-padding-alt:
     0cm 0cm 0cm 0cm'>
     <tr style='height:37.5pt'>
      <td style='background:#F57E1B;padding:3.0pt 3.0pt 3.0pt 3.0pt;
      height:37.5pt'>
        <p class=MsoNormal align=center style='text-align:center'><span
      style='font-size:11.5pt;font-family:Arial;color:white'>&nbsp;<b>Usuario</b>
            <o:p></o:p></span></p>
      </td>
      <td style='padding:3.0pt 3.0pt 3.0pt 3.0pt;height:37.5pt'>
      <p class=MsoNormal align=center style='text-align:center'><span
      style='font-size:11.5pt;font-family:Arial;color:#222222'><INPUT TYPE="TEXT" MAXLENGTH="100" SIZE="20" NAME="usuario"
      onfocus="siguienteCampo='clave';"><o:p></o:p></span></p>
      </td>
     </tr>

     <tr style='height:37.5pt;border-top-color: gray;border-top-width: 2px;border-top-style: solid;'>
      <td style='background:#F57E1B;padding:3.0pt 3.0pt 3.0pt 3.0pt;
      height:37.5pt'>
      <p class=MsoNormal align=center style='text-align:center'><span
      style='font-size:11.5pt;font-family:Arial;color:white'>&nbsp;<b>Clave</b><o:p></o:p></span></p>
      </td>
      <td style='padding:3.0pt 3.0pt 3.0pt 3.0pt;height:37.5pt'>
      <p class=MsoNormal align=center style='text-align:center'><span
      style='font-size:11.5pt;font-family:Arial;color:#222222'><INPUT TYPE="password" MAXLENGTH="100" SIZE="20" NAME="clave"
      onfocus="siguienteCampo='fin';"><o:p></o:p></span></p>
      </td>
     </tr>
    </table>
    </div>
    
    <table border=0 cellspacing=0 cellpadding=0 width="100%" style='width:100.0%;
     mso-cellspacing:0cm;mso-padding-alt:0cm 0cm 0cm 0cm'>
     <tr>
      <td style='background:white;padding:3.0pt 3.0pt 3.0pt 3.0pt'>
      <p class=MsoNormal align=center style='text-align:center'><span
      style='font-size:11.5pt;font-family:Arial;color:#222222'>Estimado Usuario:
      le recordamos que su nombre de usuario y clave, corresponden a su rut sin puntos, con guion mas el digito verificador.</span><span
      style='font-size:8.5pt;font-family:Arial;color:#222222'>.</span></p>
      <p class=MsoNormal align=center style='text-align:center'><span
      style='font-size:11.5pt;font-family:Arial;color:#222222'>Por ejemplo:
         9999999-9  
          <o:p></o:p></span></p>
      </td>
     </tr>
    </table>
    
    </td>
   </tr>
   <tr>
    <td style='padding:.75pt .75pt .75pt .75pt'>
    <p class=MsoNormal><span style='font-size:8.5pt;font-family:Verdana;
    color:#222222'><img width=1 height=15 id="_x0000_i1027"
    src="images/shim_trans.gif" border=0><o:p></o:p></span></p>
    </td>
   </tr>
  </table>
  </div>
  <div align=center>
  <table border=0 cellspacing=1 cellpadding=0 width="100%" style='width:100.0%;
   mso-cellspacing:.7pt'>
   <tr>
    <td style='padding:.75pt .75pt .75pt .75pt'>
    <p class=MsoNormal align=center style='text-align:center'><span
    style='font-size:12.5pt;font-family:Verdana;color:#222222'><a
    href="Javascript:cmdValida();" class='btn btn-custom' style='font-size:20px !important;'>
    
    
    <i class="fa fa-check-square-o"></i> Ingresar
    
    </a></span></p>
    
    </td>
   </tr>
   <tr>
    <td style='padding:.75pt .75pt .75pt .75pt'>
    <p class=MsoNormal><span style='font-size:8.5pt;font-family:Verdana;
    color:#222222'><img border=0 width=1 height=5 id="_x0000_i1029"
    src="images/shim_trans.gif"><o:p></o:p></span></p>
    </td>
   </tr>
  </table>
  </div>
  <p class=MsoNormal><span style='font-size:8.5pt;font-family:Verdana;
  color:#222222'><o:p></o:p></span></p>
  </td>
 </tr>
</table>

</div>

</form>

</div>   	
   	
   	
   	<!--------------------------------------------------------------------------------------------->
   </div>
   <div id="footer">
   	

<div align=center>

<table border=0 cellspacing=0 cellpadding=0 style='mso-cellspacing:0cm;
 mso-padding-alt:0cm 0cm 0cm 0cm'>
 <tr>
  <td >

    	  <div class='footer' style='padding:10px;'>

  <span class="MsoNormal " align=center style='text-align:center'><span
  style=''>Copyright @ 2015, <a
  href="http://www.psicus.cl" target="_blank"><span style='color:white'>PSICUS
  Profesionales,</span></a> Resoluci&oacute;n Recomendada:1024x768 </span>
  </span>
  	
  </div>

  </td>
 </tr>
</table>

</div>
   	
   </div>
</div>











</body>

</html>
