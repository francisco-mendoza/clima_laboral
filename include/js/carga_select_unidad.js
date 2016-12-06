/*
 * INivel es el proximo nivel a llenar
 * valor es el valor actual del select
 * idSelect es el nombre generico de los selects
 * */
function cargarSelect(iNivel,valor,idSelect){

  for(var i=iNivel;i<10;i++){
    if($('#'+idSelect+i).length==0)
      break;
    $('#'+idSelect+i)
      .find('option')
      .remove()
      .end();
    $('#'+idSelect+i)
      .append($("<option></option>")
      .attr("value",'0')
      .text('Seleccione'));    	
  }
   	
  $.post("consultas_ajax.php",{accion:'obtenerUnidades',padre:valor},function(result){
    try{
      data = JSON.parse(result);
      $.each(data, function(key, value){   
       $('#'+idSelect+iNivel)
         .append($("<option></option>")
         .attr("value",value['id'])
         .text(value['nombre']));
      });     
    }catch (e){
      alert('excepcion:'+result);	
    } 
  }).error(function(xhr, textStatus, errorThrown){
    alert("error"+xhr.responseText);
  }); 
}