<?php


// Function to calculate standard deviation (uses sd_square)    
function desviacionEstandar($array) {
  // square root of sum of squares devided by N-1
  return sqrt(array_sum(array_map("sd_square",$array,array_fill(0,count($array),(array_sum($array)/count($array)))))/(count($array)-1));
}
// Function to calculate square of value - mean
function sd_square($x, $mean) { return pow($x - $mean,2); }


function obtenerUrl(){
  $str = "";
  $ip = gethostbyname($_SERVER['HTTP_HOST']);
  $arrSitio = explode("/",$_SERVER['SCRIPT_NAME']);
  $str.= "http://".$ip."/".$arrSitio[1]."/";
  return $str;
}

function obtenerWebPath(){
  if(esProduccion()){
  	$strPath = "http://".$_SERVER['HTTP_HOST']."/slorg/";
  }  else {
  	$strPath = "http://".$_SERVER['HTTP_HOST']."/slorg-qa/";  	
  }  	
  return $strPath;
}


function identificador(){
  return time()."_".rand(10,1000);
}

function generarString($arrParametros=array()){
  $str =  "graficos.php";
  $iContador = 0;
  foreach($arrParametros as $key => $value){
  	if($iContador==0){
  	  $str.="?";	
  	} else {
  	  $str.="&";  		
  	}
  	$str.=$key."=".$value;
	$iContador++;
  }
  return $str;
}

function curl_get_contents($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}


  function calcular_promedio($array){
	$total = 0;	
	foreach($array as $key => $valor){
	  $total+=$valor;
	}    	  	
	return $total/count($array);
  }  
  function calcular_moda($array){
	foreach($array as $key => $valor){
	  $valor = redondeo($valor,2);
	  $valor = "".$valor;
	  $array[$key] = $valor;
	}    	
    $cuenta = array_count_values($array);
    arsort($cuenta);
    return key($cuenta);
  }

  function calcular_mediana($array){
    // perhaps all non numeric values should filtered out of $array here?
    $iCount = count($array);
    if ($iCount == 0){
      throw new DomainException('Median of an empty array is undefined');
    }
    // if we're down here it must mean $array
    // has at least 1 item in the array.
    $middle_index = floor($iCount / 2);
    sort($array, SORT_NUMERIC);
    $median = $array[$middle_index]; // assume an odd # of items
    // Handle the even case by averaging the middle 2 items
    if ($iCount % 2 == 0){
      $median = ($median + $array[$middle_index - 1]) / 2;
    }
    return $median;
  }

  function redondeo($valor,$precision){
  	return round($valor,$precision,PHP_ROUND_HALF_UP);
  }

  function obtenerCampo($strUsername){
  	$strUsername = trim($strUsername);
    $arrMail = explode("@",$strUsername);
	if(count($arrMail)>1){//el username se esta logueando por un mail
	  if(!validarMail($strUsername)){
        return array('resultado'=>false,'codigo'=>2,'msg'=>'El Email tiene un formato incorrecto');
	  }
	  return array('resultado'=>true,'campos'=>array('email'),'datos'=>array($strUsername));	
	}
    $arrRut = explode("-",$strUsername);	
	if(count($arrRut)>1){//el username se esta logueando por un rut
	  $iRut = str_replace(".","",$arrRut[0]);
	  $cDv  = $arrRut[1];	
	  //var_dump($cDv."--".$iRut);
	  //aca falta la validacion de digito verificador
	  $cDv = strtolower($cDv); 
	  $cDvCalculado = obtenerDv($iRut);
	  $strTmp = $iRut."-".$cDv;	  
	  if($strTmp!='999-k'){
	    if($cDv!=$cDvCalculado){
          return array('resultado'=>false,'codigo'=>3,'msg'=>'El digito verificador no corresponde');	  	
	    }	  	
	  }
	  return array('resultado'=>true,'campos'=>array('rut','dv'),'datos'=>array($iRut,$cDv));	
	} 

	
    return array('resultado'=>false,'codigo'=>4,'msg'=>'El username no tiene un formato valido');
  }
  
  function obtenerDv($_rol) {
    while($_rol[0] == "0") {
        $_rol = substr($_rol, 1);
    }
    $factor = 2;
    $suma = 0;
    for($i = strlen($_rol) - 1; $i >= 0; $i--) {
        $suma += $factor * $_rol[$i];
        $factor = $factor % 7 == 0 ? 2 : $factor + 1;
    }
    $dv = 11 - $suma % 11;
    /* Por alguna razón me daba que 11 % 11 = 11. Esto lo resuelve. */
    $dv = $dv == 11 ? '0' : ($dv == 10 ? "k" : ''.$dv);
    return $dv;
  }  
  
  
  function validarMail($strEmail){
	$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
	
    if (preg_match($pattern,$strEmail) === 1){
      return true;
    } else {
      return false;    	
    }  		
  }

 function manejoError($strError,$strPaginaRedireccion){
   include_once(DIRNAME(__FILE__)."/../headerHtml.php");	
   echo "<div class='alert alert-danger'> <b>Se produjo un error , por favor avisar al administrador del sistema.</b> <br>";
   echo $strError ."</div>";
   echo "<a class='btn btn-danger' href='".$strPaginaRedireccion."'>Volver</a><br>";
   die();
 }

  function romanic_number($integer, $upcase = true) 
  { 
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
    $return = ''; 
    while($integer > 0) 
    { 
        foreach($table as $rom=>$arb) 
        { 
            if($integer >= $arb) 
            { 
                $integer -= $arb; 
                $return .= $rom; 
                break; 
            } 
        } 
    } 

    return $return; 
  } 

?>