<?php
  //para limpiar las imagenes temporales  
  $arrImagenes = glob("grafico/*.png"); 
  if(count($arrImagenes)){
    foreach($arrImagenes as $img){
      //echo basename($img)."<br>";
      $strNombre = basename($img);
	  $arrNombre = explode("_",$strNombre);
	  if(count($arrNombre)>1){
		$ts = $arrNombre[1];
		//var_dump($arrArchivo); 
		$str = strtotime(date("Y-m-d H:i:s"))-($ts);
		//echo floor($str/3600/24)."<br>";  
		$iCantidadDias = floor($str/3600/24);
		//if($iCantidadDias==0){
		if($iCantidadDias>=2){			
		  unlink("grafico/".$strNombre);
		}
	  }
    }
  }

?>