
function validaDistintaPorGrupo(id_grupo,nombre_grupo,tipo_opcion,id_linea){
    
  $("."+id_linea).removeClass('alert-danger');
  var clase = '';
  var n = -1;
  var arrValores = {};
  var suma = 0;
 
  $("input:"+tipo_opcion+"[class*="+id_grupo+"]").each(function(index){
   	clase = $(this).attr('class');
   	//tomar el identificador y obtener el valor 
	var arrClase = clase.split(" ");
	var ident = '';
	for(var i=0;i<arrClase.length;i++){
	  n = arrClase[i].indexOf(id_grupo);
	  if(n!=-1){//encontrado
	  	ident=arrClase[i];
	  	break;
	  }	
	}
	if (!arrValores.hasOwnProperty(ident)){
	  var valor = null;
	  if(tipo_opcion=='radio' || tipo_opcion=='checkbox'){
	    valor = $('.'+ident+':checked').val();	  	
	  }else if(tipo_opcion=='select'){
	    valor = $('.'+ident+'').val();	  	
	  }else if(tipo_opcion=='text'){
	    valor = $('.'+ident+'').val();	  	
	  }
	  arrValores[ident] = valor;
	}   	           	
  });
  arrValores2 = arrValores; 	  
  var flag_break = true;
  $.each(arrValores,function(key,value){
    
    $.each(arrValores2,function(key2,value2){
	  if (key!=key2){
	  	if(arrValores[key]>0){
	      if (arrValores2[key2] == arrValores[key]){
			alerta("Estimado Usuario <br>no pueden haber 2 o mas opciones con <br> el mismo valor en "+nombre_grupo+", resuelva <br>el conflicto entre las respuestas resaltadas");
			
			arrIdent1 = key.split("_");
			arrIdent2 = key2.split("_");			
	      	$("."+id_linea+"_"+arrIdent1[2]).addClass('alert-danger');/*esto puede cambiar, por el momento es la forma en que se identifica*/
	      	$("."+id_linea+"_"+arrIdent2[2]).addClass('alert-danger');/*esto puede cambiar, por el momento es la forma en que se identifica*/
	      	flag_break=false;	      	  
	      	return false;
	      }
	  	}
	  }else{// si las llaves son iguales aun debo saber si hay uno seleccionado
	  	//aca deberia preguntar por otro valor si es que no son radios ni check
	  	//ademas eso depende de la escala
	  	//if(arrValores[key]>0){
	  	//  suma++;
	  	//}
	  }    
    });
    if(flag_break==false)
      return false;	    
  });
  if(flag_break==false)
    return false;  

  return true;
}