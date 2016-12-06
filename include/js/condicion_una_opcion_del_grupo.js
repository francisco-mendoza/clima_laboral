
function validaUnaPorGrupo(id_grupo,nombre_grupo,tipo_opcion,id_linea){
  var contador = 0;
  $("."+id_linea).removeClass('alert-danger');
  $("."+id_grupo).each(function(index){   
   	if(tipo_opcion=='checkbox' || tipo_opcion=='radio'){
   	  if($(this).is(':checked')){
   	    contador++;		
   	  }
   	}else if(tipo_opcion=='select'){
   	  if($(this).val()!='0'){
   	    contador++;
   	  }      		   		
   	} else if (tipo_opcion=='text'){
   	  if($.trim($(this).val())!=''){
   	    contador++;		
   	  }
   	}
  });	
  if(contador==0){
  	alerta('Por favor seleccione <br> una de las opciones de '+nombre_grupo);
	arrIdent1 = id_grupo.split("_");
   	$("."+id_linea+"_"+arrIdent1[2]).addClass('alert-danger');
  	return false;
  }	
  return true;
}
