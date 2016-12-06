
function alerta(mensaje,strOpcion){
  strOpcion = (typeof strOpcion === 'undefined') ? 'danger' : strOpcion;
  $.notify({
  	//opciones
  	 message: mensaje 
  },{
  	//settings
     type:strOpcion
	,placement : { from:'bottom',align:'center'}  	
	,animate: {
	  enter: 'animated fadeInDown',
	  exit: 'animated fadeOutUp'
	}
	,mouse_over:'pause'
	,offset:50
	,delay:1500 	
  });
}