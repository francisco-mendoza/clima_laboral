<?php
  $m = $_GET['m'];
  if ($m<>1){$m=0;}
?>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
  <head>
    <meta http-equiv=Content-Type content="text/html; charset=iso-8859-1">
    <meta name=ProgId content=Word.Document>
    <meta name=Generator content="Microsoft Word 9">
    <meta name=Originator content="Microsoft Word 9">
    <link rel=File-List href="./default_archivos/filelist.xml">
    <link rel=Edit-Time-Data href="./default_archivos/editdata.mso">
    <title>PSICUS Profesionales</title>
    <link rel="Stylesheet" type="text/css" media=all href="beta2.css">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script language="JavaScript">
      var mm ='<?php echo $m;?>';
      if (mm==1)
      {
      	alert('Usuario o clave no validos');
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
      	
        $.ajax({
          data: $("#se").serialize(),
          type: $("#se").attr('method'),
          url: $("#se").attr('action'),
          dataType: "json",
          success: function(response){
            if(response=='0'){
              alert("Estimado Usuario revise los datos ingresados: Usuario y Clave e intente denuevo");
            }else if(response=='1'){
              top.document.getElementById('usuario').value=usuario; 
              top.document.getElementById('clave').value=clave;              
              top.document.getElementById('formularioSlorg').submit();              
            }
          }
        });
  			//window.document.se.action="dpp_valida_usuario.php"
  			//window.document.se.method="POST"
  			//window.document.se.submit();
  		}
      function MM_openBrWindow(theURL,winName,features) { //v2.0
        window.open(theURL,winName,features);
      }
   		function MM_findObj(n, d) { //v4.0
   			var p,i,x;  
        if(!d) d=document; 
        if((p=n.indexOf("?"))>0&&parent.frames.length) 
        { 
          d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
        }
      	if (!(x=d[n])&&d.all) x=d.all[n];
        for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
      	for (i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
      	if (!x && document.getElementById) x=document.getElementById(n); return x;
      }
      <!--
      function MM_reloadPage(init) {  //reloads the window if Nav4 resized
        if (init==true) with (navigator) {
          if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
      			document.MM_pgW=innerWidth; 
            document.MM_pgH=innerHeight; 
            onresize=MM_reloadPage; 
          }
        }
      	else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
      }
      MM_reloadPage(true);
      // -->
      function MM_showHideLayers() { //v3.0
        var i,p,v,obj,args=MM_showHideLayers.arguments;
      	for (i=0; i<(args.length-2); i+=3){
          if ((obj=MM_findObj(args[i]))!=null) { 
            v=args[i+2];
            if (obj.style) { 
              obj=obj.style; 
              v=(v=='show')?'visible':(v='hide')?'hidden':v; 
            }
            obj.visibility=v; 
          }
        } 
      }
   		function MM_swapImgRestore() { //v3.0
   	    var i,x,a=document.MM_sr; 
        for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
      }
   		function MM_preloadImages() { //v3.0
   	    var d=document; 
        if(d.images){
          if(!d.MM_p) d.MM_p=new Array();
      		var i,j=d.MM_p.length,a=MM_preloadImages.arguments; 
          for(i=0; i<a.length; i++){
            if (a[i].indexOf("#")!=0){ 
              d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];
            }
          } 
        }	
      }
   		function MM_swapImage() { //v3.0
   			var i,j=0,x,a=MM_swapImage.arguments; 
        document.MM_sr=new Array; 
        for(i=0;i<(a.length-2);i+=3){
          if ((x=MM_findObj(a[i]))!=null){
            document.MM_sr[j++]=x; 
            if(!x.oSrc) x.oSrc=x.src; 
            x.src=a[i+2];
          }
        }	
      }
    </script>
  </head>
  <body bgcolor="#e6e6e6" background="images/bg.gif" lang=ES link=navy vlink=navy style='tab-interval:35.4pt' leftmargin=0 topmargin=0>
    <div class=Section1>
      <form name=se id="se" method="post" action="dpp_valida_usuario.php?login2">
        <div align=center>
          <table border=0 cellspacing=0 cellpadding=0 bgcolor="#fafafa" style='mso-cellspacing: 0cm;background:#FAFAFA;mso-padding-alt:0cm 0cm 0cm 0cm;width:100%' id='Contenido'>
            <tr>
              <td style='background:#FBFBFB;padding:0cm 0cm 0cm 0cm;'>
                <div align=center>
                  <table border=0 cellspacing=4 cellpadding=0 style='mso-cellspacing:3.0pt;width:100%'>
                    <tr>
                      <td style='background:#CCCCCC;padding:.75pt .75pt .75pt .75pt'>
                        <div align=center>
                          <table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor=white style='width:100.0%;mso-cellspacing:0cm;background:white;mso-padding-alt:0cm 0cm 0cm 0cm'>
                            <tr >
                              <td style='background:#FFDB70;'>
                                <p class=MsoNormal align=center style='text-align:center;margin-right:6px'>
                                  <span style='font-size:8.5pt;font-family:Arial;color:#0055A9;font-weight:bold'>&nbsp;Usuario
                                    <o:p></o:p>
                                  </span>
                                </p>
                              </td>
                              <td style='padding:3px;'>
                                <p class=MsoNormal align=center style='text-align:center'>
                                  <span style='font-size:8.5pt;font-family:Arial;color:#222222'>
                                    <INPUT TYPE="TEXT" MAXLENGTH="100" style="width:100%" NAME="usuario" onfocus="siguienteCampo='clave';">
                                      <o:p></o:p>
                                  </span>
                                </p>
                              </td>
                            </tr>
                            <tr>
                              <td style='background:#FFDB70;'>
                                <p class=MsoNormal align=center style='text-align:center;margin;margin-right:6px;'>
                                  <span style='font-size:8.5pt;font-family:Arial;color:#0055A9;font-weight:bold;'>&nbsp;Clave
                                    <o:p></o:p>
                                  </span>
                                </p>
                              </td>
                              <td style='padding:3px;'>
                                <p class=MsoNormal align=center style='text-align:center'>
                                  <span style='font-size:8.5pt;font-family:Arial;color:#222222'>
                                    <INPUT TYPE="password" MAXLENGTH="100" style="width:100%" NAME="clave" onfocus="siguienteCampo='fin';">
                                    <o:p></o:p>
                                  </span>
                                </p>
                              </td>
                            </tr>
                            <tr style='height:.75pt'>
                              <td colspan=2 valign=top style='background:#FFE3BB;padding:0cm 0cm 0cm 0cm;height:.75pt'>
                                <p class=MsoNormal style='mso-line-height-alt:.75pt'>
                                  <span style='font-size:8.5pt;font-family:Verdana;color:#222222'>
                                    <img width=1 height=1 id="_x0000_i1026" src="images\block.gif">
                                    <o:p>
                                    </o:p>
                                  </span>
                                </p>
                              </td>
                            </tr>
                          </table>
                        </div>
                        <table border=0 cellspacing=0 cellpadding=0 width="100%" style='width:100.0%; mso-cellspacing:0cm;mso-padding-alt:0cm 0cm 0cm 0cm'>
                          <tr>
                            <td style='background:white;padding:3.0pt 3.0pt 3.0pt 3.0pt'>
                              <p class=MsoNormal align=center style='text-align:center'>
                                <span style='font-size:8.5pt;font-family:Arial;color:#222222'>
                                  Estimado Usuario: le recordamos que su nombre de usuario y clave, corresponden a su login para ingresar al portal mercado publico.
                                </span>
                                <span style='font-size:8.5pt;font-family:Arial;color:#222222'>.</span>
                              </p>
                              <p class=MsoNormal align=center style='text-align:center'>
                                <span style='font-size:8.5pt;font-family:Arial;color:#222222'>
                                  Por ejemplo: jperez-1
                                  <o:p></o:p>
                                </span>
                              </p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
                <div align=center>
                  <table border=0 cellspacing=1 cellpadding=0 width="100%" style='width:100.0%; mso-cellspacing:.7pt'>
                    <tr>
                      <td style='padding:.75pt .75pt .75pt .75pt'>
                        <p class=MsoNormal align=center style='text-align:center'>
                          <span style='font-size:8.5pt;font-family:Verdana;color:#222222'>
                            <a href="Javascript:cmdValida();" onmouseover="MM_swapImage('Image1','','images/b_ingresar_r.gif',1)" onmouseout="MM_swapImgRestore();">
                              <img border=0 width=84 height=19 id="_x0000_i1028" src="images/b_ingresar_n.gif" align=bottom name=image1>
                            </a>
                            <o:p></o:p>
                          </span>
                        </p>
                      </td>
                    </tr>
                    <tr>
                      <td style='padding:.75pt .75pt .75pt .75pt'>
                        <p class=MsoNormal>
                          <span style='font-size:8.5pt;font-family:Verdana;color:#222222'>
                            <img border=0 width=1 height=5 id="_x0000_i1029" src="images/shim_trans.gif">
                            <o:p></o:p>
                          </span>
                        </p>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </body>
</html>