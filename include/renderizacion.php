<?php

  function obtenerAspecto($item,$arrParametros=array()){
  	if(empty($arrParametros)){
	  switch($item['id_tipo_elemento']){
	  	case TIPO_ELEMENTO_BOTON:
	  	case TIPO_ELEMENTO_BOTON_PARCIAL:
		  return 'btn btn-custom';
		  break;
	  	case TEXTO_PRESENTACION:
		  return 'tablaRadius1 padding1';
		  break;			  	
	  }
  	} else {
  	  switch($arrParametros['tipo']){
	  	case 'contenedorItem':
		  return ''; //aca tendrian que ir los panel default
		  break;
	  	case 'tituloItem':
		  return 'titulo_tabla_des'; //aca tendrian que ir los panel default
		  break;
	  	case 'contenidoItem':
		  return ''; //aca tendrian que ir los panel default
		  break;
	  	case 'elementoItem':
		  return ''; //aca tendrian que ir los panel default
		  break;
	  	case 'cabeceraEscala':
		  return ''; //aca tendrian que ir los panel default
		  break;
	  	case 'columnaEscala':
		  return ''; //aca tendrian que ir los panel default
		  break;		  			  		  		  		  		  					  			  	  	  	
  	  }
  	}
	return '';
  }

  function obtenerTitulo($item){
  	$strTitulo='';
  	if(!empty($item['titulo_elemento'])){
  	  $strTitulo.= "<div class='".obtenerAspecto($item,array("tipo"=>'tituloItem'))."'>";
  	  $strTitulo.= buscarVariables($item['titulo_elemento'],$item);
  	  $strTitulo.= "</div>";	  
  	}
	return $strTitulo;
  }
  
  function buscarVariables($strTexto,$item){
  	$arrVariables = array("@@COUNT_NIVEL_NEGOCIO@@");
	foreach($arrVariables as $key => $value){
	  $pattern = "/".$value."/";
		//echo"---";
		//echo"$pattern";
		//echo"$strTexto";		
		//var_dump(preg_match($pattern, $strTexto));
		//echo"---<br>";		
	  if(preg_match($pattern, $strTexto)!=false){
	  	$reemplazo = buscarReemplazoVariable($item,$value);
	  	$strTexto = str_replace($value,$reemplazo,$strTexto);
	  }    	
	}
	return $strTexto;
  }
  
  function buscarReemplazoVariable($item,$variable){
  	switch($variable){
	  case "@@COUNT_NIVEL_NEGOCIO@@":
		if(!empty($item['id_nivel_elemento_negocio'])){
		  $arrVariables = DB_NEGOCIO_obtenerVariables();
		  return count($arrVariables);	
		}  
		break;
  	}
	return '';
  }
  
  
  //centros de costo, de responsabilidad
  function renderizarUnidad($item){
  	$strRespuesta = "";
	$strRespuesta.= "<div class='panel panel-default score ".obtenerAspecto($item,array("tipo"=>'contenedorItem'))."'  data-index='".$item['orden']."'>";
	$strRespuesta.= obtenerTitulo($item);
	$strRespuesta.= "<div class='contenidoUnidad ".obtenerAspecto($item,array("tipo"=>'contenidoItem'))."' align='center'>";
		
	$idNivelUnidad 	  	    = $item['id_nivel_unidad'];	  	
	$bMostrarHastaEsteNivel = $item['flag_nivel'];
	$iProfundidad 			= DB_CR_obtenerProfundidad($idNivelUnidad);
	$strRespuesta.= "";
	$strNombreIdentificador = 'nivel_unidad_';
	$idResultadoAnterior = null;
	for($i=1;$i<=$iProfundidad;$i++){
	  $arrIdentificacion = array(
	     "id"			   => $item['id']
	    ,"id_unidad_nivel" => DB_CR_obtenerIdNivel($item['id_cliente'],$i) 
		,'id_usuario'	   => $_SESSION['id_usuario']
	  );
	  $idResultado = obtenerResultado($arrIdentificacion);	
	  if($i==1){
		$strRespuesta.= "<span class='' >&nbsp;";			
	  	$strRespuesta.= DB_CR_nombreNivel($item['id_cliente'],'',$i);	
		$strRespuesta.= "&nbsp;";
		$strRespuesta.= "<select class='".PREFIJO_GRUPO.$item['id']." selectUnidad' id='".$strNombreIdentificador.$i."' onchange='cargarSelect(".($i+1).",this.value,\"".$strNombreIdentificador."\")' name='".$item['id']."_".PREFIJO_ELEMENTO_UNIDAD.DB_CR_obtenerIdNivel($item['id_cliente'],$i)."'>";
		//cargar primer nivel de profundidad con todas las opciones
  	    $arrHijos = DB_CR_obtenerHijos(null,'null');
		$strRespuesta.= "<option value='0'> Seleccione </option>";		
		foreach($arrHijos as $key => $value){
		  $strRespuesta.= "<option value='".$value['id']."'"; 
		  if(!empty($idResultado) && $value['id'] == $idResultado){
		    $strRespuesta.= " selected ";	
		  }
		  $strRespuesta.= ">";			
		  $strRespuesta.= $value['nombre'];

		  $strRespuesta.= "</option>";			
		}
		$strRespuesta.=  "</select>";
		$strRespuesta.= "</span>";
	  }	else {
		$strRespuesta.= "<span class='elementoUnidad' >&nbsp;";
	  	$strRespuesta.= DB_CR_nombreNivel($item['id_cliente'],'',$i);	
		$strRespuesta.= "&nbsp;";	  	
		$strRespuesta.= "<select class='".PREFIJO_GRUPO.$item['id']." selectUnidad' id='".$strNombreIdentificador.$i."' onchange='cargarSelect(".($i+1).",this.value,\"".$strNombreIdentificador."\")'  name='".$item['id']."_".PREFIJO_ELEMENTO_UNIDAD.DB_CR_obtenerIdNivel($item['id_cliente'],$i)."' >";
		$strRespuesta.= "<option value='0'> Seleccione </option>";

		if(!empty($idResultadoAnterior)){
		  $arrHijos = DB_CR_obtenerHijos(null,$idResultadoAnterior);
		  foreach($arrHijos as $key => $value){
		    $strRespuesta.= "<option value='".$value['id']."'"; 
		    if(!empty($idResultado) && $value['id'] == $idResultado){
		      $strRespuesta.= " selected ";	
		    }
		    $strRespuesta.= ">";			
		    $strRespuesta.= $value['nombre'];

		    $strRespuesta.= "</option>";			  	
		  }						
		}
		$strRespuesta.= "</select></span>"; 
	  }
	  $idResultadoAnterior = $idResultado;		
  
	}
	$strRespuesta.= "</div>";//cierre contenido item
	$strRespuesta.= "</div>";//cierre contenedor item
	return $strRespuesta;
  }


  function obtenerAnchoColumnaEscala($arrValores,$item){
  	 $iAncho = 1;
	 /*
	 if(count($arrValores)<=6){
  	   $iAncho = 2;	 	
	 }
	 if(count($arrValores)>6 && count($arrValores)<=12){
	   $iAncho = 1;
	 }
	 if(count($arrValores)>12){
	   $iAncho = 0;
	 }*/
	 return $iAncho;
  }

    
  function renderizarEscala($item,$opcion,$arrParametros=array()){
  	$strRespuesta = "";
		
	switch($opcion){
	  case 'cabecera':
  	  $arrNodo 	  = DB_ESCALA_obtenerNodo($item["id_escala"]);
  	  $arrValores = $arrParametros['escala'];
	    
	      if(empty($arrNodo['mostrar_nombre_columna'])) /* ya que se muestra en la otra fila */
			return '';

		  if(empty($arrNodo['mostrar_titulo_columna']))
		    return ''; 

	      $strRespuesta.="<td colspan=' ".obtenerCantidadColumnasPreguntas($item)."' class='titulo_tabla_des'>";
	      $strRespuesta.="</td>";	    	


	    $strRespuesta.= "<td class='titulo_tabla_des ".obtenerAspecto($item,array("tipo"=>'cabeceraEscala'))."' colspan='".count($arrValores)."' ";

	    $strRespuesta.=" align='center'>";
		if(!empty($arrNodo['mostrar_titulo_columna']))
	      $strRespuesta.= $arrNodo['titulo_escala'];	    
	    
	    //$strRespuesta.= "</span>";	  
	    $strRespuesta.= "</td>";
			

		    
	  break;


	  case 'lineaCabecera':
  	  $arrNodo 	  = DB_ESCALA_obtenerNodo($item["id_escala"]);
  	  $arrValores = $arrParametros['escala'];	  
	    //$strRespuesta.= "<span class='col-md-6'>";
		$iAncho = obtenerAnchoColumnaEscala($arrValores,$item);
		
	    if(empty($arrNodo['mostrar_nombre_columna'])){
		  if(!empty($arrNodo['mostrar_titulo_columna']))
	        $strRespuesta.= "<td align='center' class='titulo_tabla_des' colspan='".count($arrNodo)."'>".$arrNodo['titulo_escala']."</td>";
		    return $strRespuesta;		    	
	    }		

		
	    foreach($arrValores as $key => $value){
	      //if(empty($arrNodo['mostrar_nombre_columna'])){
		  //	continue;
	      //}
		    	    	
	      $strRespuesta.= "<td class='titulo_tabla_des ".$iAncho." ".obtenerAspecto($item,array("tipo"=>'columnaEscala'))."' align='center'>";
	      
	      if(!empty($arrNodo['mostrar_nombre_columna'])){

			if(!empty($arrNodo['id_nivel_elemento_negocio'])){
              $strRespuesta.= $value['valor'];				
			} else {
              $strRespuesta.= $value['nombre_escala'];
			}
	      }
	        
	      $strRespuesta.= "</td>";
	    }		
	  break;
	  
	  case 'columnas':
  	  $arrNodo 	  = DB_ESCALA_obtenerNodo($item["id_escala"]);
  	  $arrValores = $arrParametros['escala'];	  
	    //$strRespuesta.= "<span class='col-md-6'>";
		$iAncho = obtenerAnchoColumnaEscala($arrValores,$item);
		
	    if(empty($arrNodo['mostrar_nombre_columna'])){
		  if(!empty($arrNodo['mostrar_titulo_columna']))
	        $strRespuesta.= "<td align='center' class='titulo_tabla_des' colspan='".count($arrNodo)."'>".$arrNodo['titulo_escala']."</td>";
		    return $strRespuesta;		    	
	    }		

		
	    foreach($arrValores as $key => $value){
	      //if(empty($arrNodo['mostrar_nombre_columna'])){
		  //	continue;
	      //}
		    	    	
	      $strRespuesta.= "<td class='titulo_tabla_des ".$iAncho." ".obtenerAspecto($item,array("tipo"=>'columnaEscala'))."' align='center'>";
	      
	      if(!empty($arrNodo['mostrar_nombre_columna'])){

			if(!empty($arrNodo['id_nivel_elemento_negocio'])){
              $strRespuesta.= $value['valor'];				
			} else {
              $strRespuesta.= $value['nombre_escala'];
			}
	      }
	        
	      $strRespuesta.= "</td>";
	    }
	    
	    
		
		
	    //$strRespuesta.= "</span>";		
	  break;
	  case 'valores':
		$arrEscala = $arrParametros['escala'];
		$strIdentificador =  PREFIJO_GRUPO.$arrParametros['identificador']['id_elemento_formulario']."_".$arrParametros['identificador']['id_elemento_negocio'];
	    $arrCondiciones = DB_CONDICION_obtenerCondicionesItem($item);
		$arrAccion  = array("accion"=>"onclick");
	    
	    //$strRespuesta.= "<span class='col-md-6'>";				
		$iAncho = obtenerAnchoColumnaEscala($arrEscala,$item);				
	    foreach($arrEscala as $key => $value){
	      //$strRespuesta.= "<span class='col-md-".$iAncho." textoCentrado'>";
	      $strRespuesta.= "<td class=' textoCentrado'>";
		    $strRespuesta.= "<label >";
		    $strRespuesta.="<input class='".$strIdentificador."' type='".$arrParametros['tipo_elemento']."' name='".$arrParametros['identificador']['id_elemento_formulario']."_".PREFIJO_ELEMENTO_NEGOCIO.$arrParametros['identificador']['id_elemento_negocio']."[]'  value='"; 
	        $strRespuesta.= $value['valor'];
	        $strRespuesta.= "'";

	        foreach($arrCondiciones as $condicion){
	  		  $strRespuesta.= renderizarValidacion($item,$condicion,$arrAccion);
	  		}				
	        if(!empty($arrParametros['resultados'])){

	          if(isset($arrParametros['resultados'][$arrParametros['identificador']['id_elemento_negocio']]) && $value['valor'] == $arrParametros['resultados'][$arrParametros['identificador']['id_elemento_negocio']]){
	            $strRespuesta.= " checked ";	          	
	          }
			}
			
	        $strRespuesta.= ">";
		    if(!empty($value['mostrar_valor'])){
	          $strRespuesta.= "<br>";
	          $strRespuesta.=$value['valor'];			  		    	
		    }	
		    $strRespuesta.= "</label>";			
	      //$strRespuesta.= "</span>";
	      $strRespuesta.= "</td>";
	    }
	    //$strRespuesta.= "</span>";		
	}
	return $strRespuesta;
  }

  /**
   * minimo hay una columna (el titulo de las preguntas)
   */
  function obtenerCantidadColumnasPreguntas($item){
	$iColumnas = 1;
  	if(!empty($item['mostrar_numeracion'])){
  	  $iColumnas++;	
  	}
  	return $iColumnas;
  }

  function renderizarElementoNegocio($item){
	//ob_flush();
	//flush();	
	$strRespuesta = "";
	$iIdNivel = $item['id_nivel_elemento_negocio'];
	//obtener todos los elementos del nivel indicado
	$arrNodos = DB_NEGOCIO_obtenerNodosNivel($iIdNivel);
	$iContador = 0;
	$strTipoElemento = DB_FORM_obtenerTipoElemento($item);	
	$arrEscala = obtenerEscala($item);
	
	$arrResultado = obtenerResultado($item);//obtener resultado existente en la base de datos
	
	$arrParametros = array(
	   "escala" 	   	   => $arrEscala
	  ,"tipo_elemento"	   => $strTipoElemento
	  ,'cantidad_columnas' => obtenerCantidadColumnasPreguntas($item)
	  ,'identificador'	   => array(
	     "id_elemento_formulario" => $item['id']
	  )
	  ,'resultados'        => $arrResultado
	);
	
	foreach($arrNodos as $key => $value){
	  if(!empty($item['flag_nivel'])){ //significa que solo hay que mostrar el nivel indicado
//////////////////////////// MOSTRAR SOLO UN NIVEL ////////////////////////////
	  	//cae aca el caso de cuestionario y de nivel de importancia
		//////CABECERA//////
		if($iContador==0){
		  //iniciar tabla
		  $strRespuesta.= "<div class='score panel panel-default ' style='clear:both;' data-index='".$item['orden']."'><table class='table table-condensed table-bordered'>";
			$strRespuesta.= "<tr>";
			$strRespuesta.= "<td class='titulo_tabla_des' colspan='".(obtenerCantidadColumnasPreguntas($item) + count($arrEscala))."' >";
			//$strRespuesta.= $item['titulo_elemento']."";
			$strRespuesta.= obtenerTitulo($item);   			   
			$strRespuesta.= "</td>";			
		    $strRespuesta.= "</tr>";	

			$strRespuesta.= "<tr>";
		    $strRespuesta.= renderizarEscala($item,'cabecera',$arrParametros);//tiene que completar los otros 6		   			   
		  $strRespuesta.= "</tr>";		    
		  $strRespuesta.= "<tr>";   
  			if(!empty($item['mostrar_numeracion'])){
		      $strRespuesta.= "<td  class='titulo_tabla_des'>";
		      $strRespuesta.= $item['titulo_numeracion'];
		      $strRespuesta.= "</td>";	
  			}
		    $strRespuesta.= "<td  class='titulo_tabla_des'>";			
		    $strRespuesta.= $item['titulo_pregunta'];			
		    $strRespuesta.= "</td>";
			$strRespuesta.= renderizarEscala($item,'columnas',$arrParametros);//tiene que completar los otros 6
		  $strRespuesta.= "</tr>";
	   		    /*
  		  $strRespuesta.= "<div class='row'>";
		    $strRespuesta.= "<span class='col-md-6'>";
		    $strRespuesta.= "</span>";		
		    $strRespuesta.= renderizarEscala($item,'cabecera',$arrParametros);//tiene que completar los otros 6		   			   
		  $strRespuesta.= "</div>";		    
		  $strRespuesta.= "<div class='row'>";   
  			if(!empty($item['mostrar_numeracion'])){
		      $strRespuesta.= "<span class='col-md-1'>";
		      $strRespuesta.= $item['titulo_numeracion'];
		      $strRespuesta.= "</span>";	
		    $strRespuesta.= "<span class='col-md-5'>";
  			}else{
		    $strRespuesta.= "<span class='col-md-6'>";  				
  			}
		    $strRespuesta.= $item['titulo_pregunta'];			
		    $strRespuesta.= "</span>";
			$strRespuesta.= renderizarEscala($item,'columnas',$arrParametros);//tiene que completar los otros 6
		  $strRespuesta.= "</div>";		  		
			*/
		}
		//////CABECERA//////

		//renderizar el contenido de la pregunta
		//también la escala 
		$strRespuesta.= "<tr class='".PREFIJO_LINEA.$arrParametros['identificador']['id_elemento_formulario']." ".PREFIJO_LINEA.$arrParametros['identificador']['id_elemento_formulario']."_".$value['id_elemento_negocio']."'  >";
  		  if(!empty($item['mostrar_numeracion'])){
		    $strRespuesta.= "<td class=''>";
		    //$strRespuesta.= ($iContador+1);
		    $strRespuesta.= $value['orden'];
		    $strRespuesta.= "</td>";
		    
  		  }
		  $strRespuesta.= "<td>";
		  $strRespuesta.= $value['nombre_elemento'];
		  $strRespuesta.= "</td>";
		  
		  $arrParametros['identificador']['id_elemento_negocio'] = $value['id_elemento_negocio'];
		  $strRespuesta.= renderizarEscala($item,'valores',$arrParametros);
		$strRespuesta.= "</tr>";
		
		if($iContador==(count($arrNodos)-1)){ //finalizar tabla	
		  $strRespuesta.= "</table></div>";		
		}
				/*
		$strRespuesta.= "<div class='row ".PREFIJO_LINEA.$arrParametros['identificador']['id_elemento_formulario']." ".PREFIJO_LINEA.$arrParametros['identificador']['id_elemento_formulario']."_".$value['id_elemento_negocio']."'  >";
  		  if(!empty($item['mostrar_numeracion'])){
		    $strRespuesta.= "<span class='col-md-1'>";
		    //$strRespuesta.= ($iContador+1);
		    $strRespuesta.= $value['orden'];
		    $strRespuesta.= "</span>";
		  $strRespuesta.= "<span class='col-md-5'>";		    
  		  }else{
		  $strRespuesta.= "<span class='col-md-6'>";  		  	
  		  }
		  $strRespuesta.= $value['nombre_elemento'];
		  $strRespuesta.= "</span>";
		  
		  $arrParametros['identificador']['id_elemento_negocio'] = $value['id_elemento_negocio'];
		  $strRespuesta.= renderizarEscala($item,'valores',$arrParametros);
		$strRespuesta.= "</div>";
		
		if($iContador==(count($arrNodos)-1)){ //finalizar tabla	
		  $strRespuesta.= "</div>";		
		}*/
		
//////////////////////////// MOSTRAR SOLO UN NIVEL ////////////////////////////		
	  }	else {
//////////////////////////// MOSTRAR MAS DE UN NIVEL ////////////////////////////

	    if($item["id_opcion_renderizacion"]==RENDER_TABLA){
////////////////////////// RENDER TABLA //////////////////////////////////////
	  	//cae aca el caso de cuestionario y de nivel de importancia
		//////CABECERA//////
		//esta cabecera debe existir cada vez que pase por el arrnodo2
		if($iContador==0){
		  //iniciar tabla
		  
		  $strRespuesta.= "<div class='score panel panel-default ' style='clear:both;' data-index='".$item['orden']."'><table class='table table-condensed table-bordered'>";
			/*
			$strRespuesta.= "<tr>";
			$strRespuesta.= "<td class='titulo_tabla_des' colspan='".(obtenerCantidadColumnasPreguntas($item) + count($arrEscala))."' >";
			//$strRespuesta.= $item['titulo_elemento']."";
			$strRespuesta.= obtenerTitulo($item);   			   
			$strRespuesta.= "</td>";			
		    $strRespuesta.= "</tr>";	

			$strRespuesta.= "<tr>";
		    $strRespuesta.= renderizarEscala($item,'cabecera',$arrParametros);//tiene que completar los otros 6		   			   
		  $strRespuesta.= "</tr>";		    
		  
		  $strRespuesta.= "<tr>";   
  			if(!empty($item['mostrar_numeracion'])){
		      $strRespuesta.= "<td  class='titulo_tabla_des'>";
		      $strRespuesta.= $item['titulo_numeracion'];
		      $strRespuesta.= "</td>";	
  			}
		    $strRespuesta.= "<td  class='titulo_tabla_des'>";			
		    $strRespuesta.= $item['titulo_pregunta'];			
		    $strRespuesta.= "</td>";
			$strRespuesta.= renderizarEscala($item,'columnas',$arrParametros);//tiene que completar los otros 6
		  $strRespuesta.= "</tr>";
			*/
		}
		//////CABECERA//////

		//renderizar el contenido de la pregunta
		//también la escala 
		$strRespuesta.= "<tr class=''  >";
  		  if(!empty($item['mostrar_numeracion'])){
		    //$strRespuesta.= "<td class=''>";
		    //$strRespuesta.= ($iContador+1);
		    //$strRespuesta.= $value['orden'];
		    //$strRespuesta.= "</td>";
  		  }
		  $strRespuesta.= "<td colspan='2' class='titulo_tabla_des' >";
		  $strRespuesta.= $value['nombre_elemento'];
		  $strRespuesta.= "</td>";
		  
		  $arrParametros['identificador']['id_elemento_negocio'] = $value['id_elemento_negocio'];
		  $strRespuesta.= renderizarEscala($item,'lineaCabecera',$arrParametros);
		$strRespuesta.= "</tr>";


		$arrNodos2 = DB_NEGOCIO_obtenerNodosHijos($value['id_elemento_negocio']);
		foreach($arrNodos2 as $llave2 => $value2){


		  $strRespuesta.= "<tr class='".PREFIJO_LINEA.$arrParametros['identificador']['id_elemento_formulario']." ".PREFIJO_LINEA.$arrParametros['identificador']['id_elemento_formulario']."_".$value2['id_elemento_negocio']."'  >";
  		    if(!empty($item['mostrar_numeracion'])){
		      $strRespuesta.= "<td class=''>";
		      //$strRespuesta.= ($iContador+1);
		      $strRespuesta.= $value2['orden'];
		      $strRespuesta.= "</td>";
		    
  		    }
		    $strRespuesta.= "<td>";
		    $strRespuesta.= $value2['nombre_elemento'];
		    $strRespuesta.= "</td>";
		  
		    $arrParametros['identificador']['id_elemento_negocio'] = $value2['id_elemento_negocio'];
		    $strRespuesta.= renderizarEscala($item,'valores',$arrParametros);
		  $strRespuesta.= "</tr>";
			
			
		}

		
		if($iContador==(count($arrNodos)-1)){ //finalizar tabla	
		  $strRespuesta.= "</table></div>";		
		}	    		
////////////////////////// RENDER TABLA //////////////////////////////////////	    	
	    }elseif($item["id_opcion_renderizacion"]==RENDER_ITEM){
////////////////////////// RENDER ITEM //////////////////////////////////////
		if($iContador==0){ //iniciar contenedor	
		  $strRespuesta.= "<div class='contenedorApilable'>";		
		}

	  	//caso segmentacion seg
	    //una tabla para cada una de las preguntas y sus opciones 
		$strRespuesta.= "<div class='panel panel-default score apilable ".obtenerAspecto($item,array("tipo"=>'contenedorItem'))."' data-index='".$item['orden']."' >";
		//$strRespuesta.= "<div class='".obtenerAspecto($item,array("tipo"=>'tituloItem'))."'>";
		$strRespuesta.= "<table class='vertical table table-bordered'>";
		$strRespuesta.= "<tr class='".obtenerAspecto($item,array("tipo"=>'tituloItem'))."'>";		
		$arrNodos2 = DB_NEGOCIO_obtenerNodosHijos($value['id_elemento_negocio']);
		    //Titulo
		    //$strRespuesta.= "<span class=''>";
		    $strRespuesta.= "<th class=''>";	
			if(!empty($item['mostrar_numeracion'])){
		      $strRespuesta.= romanic_number($value['orden']).". ";			
			}							
		    $strRespuesta.= $value['nombre_elemento'];
		    //$strRespuesta.= "</span>";
		    $strRespuesta.= "</th>";		    
		    //Opcion
		    //$strRespuesta.= "<span class=''>";
		    $strRespuesta.= "<th class='titulo_pregunta'>";
		    $strRespuesta.= $item['titulo_pregunta'];
		    //$strRespuesta.= "</span>";
		    $strRespuesta.= "</th>";
			
		  //$strRespuesta.= "</div>";//cierre titulo item
		  $strRespuesta.= "</tr>";//cierre titulo item
		  //$strRespuesta.= "</table>";
		  		  		  
		  //$strRespuesta.= "<div>";//contenido item		  
	  	  foreach($arrNodos2 as $key2 => $value2){
		    //$strRespuesta.= "<div class='".obtenerAspecto($item,array("tipo"=>'elementoItem'))."'>";
		    $strRespuesta.= "<tr class='".obtenerAspecto($item,array("tipo"=>'elementoItem'))."'>";
		    //titulo
		    //$strRespuesta.= "<span class=''>";
		    $strRespuesta.= "<td class=''>";
		    $strRespuesta.= $value2['nombre_elemento'];						
		    //$strRespuesta.= "</span>";
		    $strRespuesta.= "</td>";

		    //$strRespuesta.= "<span class=''>";
		    $strRespuesta.= "<td class='titulo_pregunta'>";
		    $strRespuesta.= "<input class='".PREFIJO_GRUPO.$item['id']."' name='".$item['id']."_".PREFIJO_IGNORAR_ELEMENTO.$value['id_elemento_negocio']."[]' value='".$value2['id_elemento_negocio']."' type='".$strTipoElemento."' ";
			
			if(!empty($arrResultado)){
			  if(isset($arrResultado[$value2['id_elemento_negocio']]) && $arrResultado[$value2['id_elemento_negocio']]==$value2['id_elemento_negocio']){
			  	$strRespuesta.= 'checked';
			  }
			}
			$strRespuesta.= ">";
		    //$strRespuesta.= "</span>";
		    $strRespuesta.= "</td>";
		  //$strRespuesta.= "</div>";
		  $strRespuesta.= "</tr>";
		}
		//sacar el titulo de la definicion en el arbol
		//$strRespuesta.= "</div>";//contenido item		
	    $strRespuesta.= "</table>";//contenedor item
		$strRespuesta.= "</div>";//contenedor item
		
		if($iContador==(count($arrNodos)-1)){ //finalizar tabla	
		  $strRespuesta.= "</div>";		
		}	
////////////////////////// RENDER ITEM /////////////////////////////////////
	    	
	    }


		
		
//////////////////////////// MOSTRAR MAS DE UN NIVEL ////////////////////////////				
	  }

	  $iContador++;
	}
	return $strRespuesta;
  }

  //region, comuna
  function renderizarLocalizacion($item){
	return '';
  }
  
  function obtenerContenido($item){
	
	if($item['id_tipo_elemento']==TIPO_ELEMENTO_BOTON || $item['id_tipo_elemento']==TIPO_ELEMENTO_BOTON_PARCIAL){
	  return renderizarBoton($item);	
	}elseif(!empty($item['contenido_elemento'])){
	  return  renderizarContenido($item);
	}elseif(!empty($item['id_nivel_elemento_negocio'])){
  	  //mostrar el nivel de item del arbol de elemento de negocio
  	  return renderizarElementoNegocio($item);
  	}elseif(!empty($item['id_nivel_unidad'])){
  	  //mostrar la seleccion de los
  	  return renderizarUnidad($item);
  	}elseif(!empty($item['id_nivel_region_pais'])){
  	  //mostrar la seleccion de division pais
  	  return renderizarLocalizacion($item);
  	}
	return '';
  }

  function renderizarContenido($item){
  	$strTitulo='';
	
  	if(!empty($item['contenido_elemento'])){
  	  //ponerlo bajo una tabla o un div
  	  $strTitulo.= "<div class='score ".obtenerAspecto($item)."' data-index='".$item['orden']."'  >";
  	  $strTitulo.= $item['contenido_elemento'];
  	  $strTitulo.= "</div>";	  
  	}
	return $strTitulo;  	
  }
  
  function renderizarBoton($item){
  	$str = "<div class='score textoCentrado' data-index='".$item['orden']."' >";	

    if(!isset($_SESSION['finCuestionario']) || $_SESSION['finCuestionario']==false  || in_array('3',$_SESSION['tipo_usuario'])){
      if($_SESSION['Completado']==false  || in_array('3',$_SESSION['tipo_usuario'])){
		   
		
      	$str.="<table class='table table-condensed table-bordered'>
		<tr >
			<td class='titulo_tabla_des' colspan='7'>
				RESPONDER SOLO INVESTIGADORES
			</td>
	    </tr>
	    <tr>
	    <td class='titulo_tabla_des'>
	    	Cuan satisfecho se encuentra con el estilo de direcci&#243;n y supervisi&#243;n de:
	    </td>
	    </tr>


		"; 

		$str.="


		
	<tr>
		<td class='titulo_tabla_des'>
			N&#176;
		</td>
		<td class='titulo_tabla_des'>
			PROPOSICION
		</td>
		<td class='titulo_tabla_des'>
			Muy Insatisfecho
		</td>
		<td class='titulo_tabla_des'>
			Insatisfecho
		</td>
		<td class='titulo_tabla_des'>
			Ni Satisfecho Ni Insatisfecho
		</td>
		<td class='titulo_tabla_des'>
			Satisfecho
		</td>
		<td class='titulo_tabla_des'>
			Muy Satisfecho
		</td>
	</tr>
	
	<tr>
		<td>
			1
		</td>
		<td>
			COORDINADOR/A DE AREA DE INVESTIGACI&Oacute;N
		</td>
		<td>
			<input type='radio' name='inv2' required value='0'>
		</td>
		<td>
			<input type='radio' name='inv2' value='1'>
		</td>
		<td>
			<input type='radio' name='inv2' value='2'>
		</td>
		<td>
			<input type='radio' name='inv2' value='3'>
		</td>
		<td>
			<input type='radio' name='inv2' value='4'>
		</td>
	</tr>
	<tr>
		<td>
			2
		</td>
		<td>
			ENCARGADO/A DE LINEA DE INVESTIGACI&Oacute;N
		</td>
		<td>
			<input type='radio' name='inv3' required  value='0'>
		</td>
		<td>
			<input type='radio' name='inv3' value='1'>
		</td>
		<td>
			<input type='radio' name='inv3' value='2'>
		</td>
		<td>
			<input type='radio' name='inv3' value='3'>
		</td>
		<td>
			<input type='radio' name='inv3' value='4'>
		</td>
	</tr>


</table>
<br><br>

		";	






		$str.="<table class='table table-condensed table-bordered'>
		<tr>
			<td class='titulo_tabla_des'>
				PREGUNTAS ABIERTAS
			</td>
	    </tr>


		";    
		$str.="


		
	<tr>
		<td>
			a)  Se&#241;ale alguna situaci&#243;n que le genere a Ud u otro, satisfacci&#243;n laboral
		</td>
	</tr>
	<tr>
		<td>
			<textarea name='area1' style='width: 98%; height: 80px' required></textarea>
		</td>

	</tr>
	<tr>
		<td>
			b)  Se&#241;ale alguna situaci&#243;n que le genere a Ud u otro, insatisfacci&#243;n laboral. Agregue alguna mejora para revertir esta situaci&#243;n.
		</td>
	</tr>
	<tr>
		<td>
			<textarea name='area2' style='width: 98%; height: 80px' required></textarea>
		</td>

	</tr>


</table>

		";	
		$str.= "<div  class=''>";  
		$str.= "<button id='capaBoton".$item['id']."' type='button' class='botonGuardar ".obtenerAspecto($item)."' ";
		$str.= " onclick='toggleBotonGuardar(".$item['id'].");validarFormulario(";
		if($item['id_tipo_elemento'] == TIPO_ELEMENTO_BOTON){
		  $str.= '1';
		}elseif($item['id_tipo_elemento'] == TIPO_ELEMENTO_BOTON_PARCIAL){
		  $str.= '0';
		}
		$str.= ");' >";
		$str.= $item['contenido_elemento']."</button>";		
		$str.= "</div>";
		
		$str.= "<div id='loaderBoton".$item['id']."' class='oculto capaLoader '>";
		$str.= "<img src='images/loader1.gif' height='65px' width='65px'>";  		
		$str.= "</div>";		
      }	
	}	


	$str.= "</div>";
	return $str;

  }

  function renderizarCondicionesFormulario($arrItems){
	$str = '';	
	$str.= " function validarFormulario(valor){ ";
    
    $str.= "   if(valor==1){ ";
	
    foreach($arrItems as $item){
      $arrCondiciones = DB_CONDICION_obtenerCondicionesFormulario($item);
	  foreach($arrCondiciones as $condicion){
	  	$str.= renderizarValidacion($item,$condicion);
	  }
    }
	$str.= "     document.getElementById('tipo_guardado').value=valor; ";
	$str.= "     document.se.submit(); ";
    $str.= "   } ";	
	
    $str.= "   if(valor==0){ ";
    foreach($arrItems as $item){
      $arrCondiciones = DB_CONDICION_obtenerCondicionesFormularioParcial($item);
	  foreach($arrCondiciones as $condicion){
	  	$str.= renderizarValidacion($item,$condicion);
	  }
    }
	$str.= "     document.getElementById('tipo_guardado').value=valor; ";
	$str.= "     document.se.submit(); ";
    $str.= "   } ";
	
	$str.= " } ";	
	return $str;  		
  }
  
  /**
   * Generar los llamados a javascript 
   */
  function renderizarValidacion($item,$condicion,$arrParametros=array()){
   $strRespuesta = "";
	 
   if(empty($arrParametros)){

  	 if($condicion['id_condicion_pregunta']==UNA_PREGUNTA_GRUPO){
	   if(!empty($item['id_nivel_elemento_negocio'])){
  	     if(empty($item['flag_nivel'])){
  	     	//significa que muestra todos los niveles hacia abajo
  	        $strRespuesta.= "if(!validaUnaPorGrupo('".PREFIJO_GRUPO.$condicion['id_elemento_formulario']."','".$item['nombre_elemento']."','".DB_FORM_obtenerTipoElemento($item)."','".PREFIJO_LINEA.$condicion['id_elemento_formulario']."')){mostrarBotonesGuardar();\n return false;}\n";				
  	     }
	   }
	   if(!empty($item['id_nivel_unidad'])){
  	     $strRespuesta.= "if(!validaUnaPorGrupo('".PREFIJO_GRUPO.$condicion['id_elemento_formulario']."','".$item['nombre_elemento']."','".DB_FORM_obtenerTipoElemento($item)."','".PREFIJO_LINEA.$condicion['id_elemento_formulario']."')){mostrarBotonesGuardar(); \n return false;}\n";
	   }
  	 } elseif($condicion['id_condicion_pregunta']==TODAS_PREGUNTA_GRUPO) {
  	   //return "if(!validaTodasPorGrupo('".PREFIJO_GRUPO.$condicion['id_elemento_formulario']."','".$item['nombre_elemento']."','".DB_FORM_obtenerTipoElemento($item)."')) return false;";
	   if(!empty($item['id_nivel_elemento_negocio'])){
  	     if(!empty($item['flag_nivel'])){
	   	   //significa que solo hay que mostrar el nivel indicado
		   $iIdNivel = $item['id_nivel_elemento_negocio'];
		   //obtener todos los elementos del nivel indicado
		   $arrNodos = DB_NEGOCIO_obtenerNodosNivel($iIdNivel);
		   foreach($arrNodos as $key => $value){
		     $strRespuesta.= "if(!validaUnaPorGrupo('".PREFIJO_GRUPO.$condicion['id_elemento_formulario']."_".$value["id_elemento_negocio"]."','".$item['nombre_elemento']." Nro.".$value['orden']."','".DB_FORM_obtenerTipoElemento($item)."','".PREFIJO_LINEA.$condicion['id_elemento_formulario']."')){mostrarBotonesGuardar();\n return false;}\n";		   	 
		   }
	     } else {//logica aun no definida
	       $strRespuesta = "";

		   $iIdNivel = $item['id_nivel_elemento_negocio'];
		   //obtener todos los elementos del nivel indicado
		   $arrNodos = DB_NEGOCIO_obtenerNodosNivel($iIdNivel);
		   
		   foreach($arrNodos as $key => $value){
		     $iIdPadre = $value['id_elemento_negocio'];
		     //obtener todos los elementos del nivel indicado
		     $arrNodos2 = DB_NEGOCIO_obtenerNodosHijos($iIdPadre);
		     foreach($arrNodos2 as $key2 => $value2){		   	
		     $strRespuesta.= "if(!validaUnaPorGrupo('".PREFIJO_GRUPO.$condicion['id_elemento_formulario']."_".$value2["id_elemento_negocio"]."','".$item['nombre_elemento']." Nro.".$value2['orden']."','".DB_FORM_obtenerTipoElemento($item)."','".PREFIJO_LINEA.$condicion['id_elemento_formulario']."')){mostrarBotonesGuardar();\n return false;}\n";		   	 
		     }
		   }
	     }	  	
	   }
	   //aca falta definir mas logicas
  	 } elseif($condicion['id_condicion_pregunta']==VALORES_DISTINTOS_PREGUNTA_GRUPO){
	   if(!empty($item['id_nivel_elemento_negocio'])){
  	     if(!empty($item['flag_nivel'])){
		   $strRespuesta.= "if(!validaDistintaPorGrupo('".PREFIJO_GRUPO.$condicion['id_elemento_formulario']."','".$item['nombre_elemento']."','".DB_FORM_obtenerTipoElemento($item)."','".PREFIJO_LINEA.$condicion['id_elemento_formulario']."')){mostrarBotonesGuardar(); \n return false;}\n";		   	 
  	     }
	   }	     	 	
  	 }
   } else { // es de accion
  	 if($condicion['id_condicion_pregunta']==UNA_PREGUNTA_GRUPO){
	 	//falta completar logica	 	
	 }elseif($condicion['id_condicion_pregunta']==TODAS_PREGUNTA_GRUPO){
	 	//falta completar logica
  	 }elseif($condicion['id_condicion_pregunta']==VALORES_DISTINTOS_PREGUNTA_GRUPO){
	   if(!empty($item['id_nivel_elemento_negocio'])){
  	     if(!empty($item['flag_nivel'])){
	       $strRespuesta.= "  ".$arrParametros['accion']."='validaDistintaPorGrupo(\"".PREFIJO_GRUPO.$condicion['id_elemento_formulario']."\",\"".$item['nombre_elemento']."\",\"".DB_FORM_obtenerTipoElemento($item)."\",\"".PREFIJO_LINEA.$condicion['id_elemento_formulario']."\");' \n";  	     	
  	     }
	   }	 	
  	 }
   }
  	 
  return $strRespuesta;
  }

  /**
   * obtener los datos de la escala, ya que esta puede estar definida en la tabla de escala directamente 
   * o apuntar dinamicamente a una variable del modelo de negocios
   * (p.e.: un ranking del segundo nivel del arbol de dimension)
   */
  function obtenerEscala($item){
	if(!empty($item['id_escala'])){
  	  $arrNodo 	 = DB_ESCALA_obtenerNodo($item["id_escala"]);
	  if(!empty($arrNodo['id_nivel_elemento_negocio'])){
	    $arrEscala = DB_NEGOCIO_obtenerNodosNivel($arrNodo['id_nivel_elemento_negocio']);	  	
		//armar tal como vendria el array escala 
		for($i=0;$i<count($arrEscala);$i++){
	      $arrEscala[$i]['valor'] = ($i+1); 
	      $arrEscala[$i]['mostrar_valor'] = $arrNodo['mostrar_valor'];			
	      $arrEscala[$i]['nombre_escala'] = $arrNodo['nombre_escala'];		  
		}
	  }	else {
	    $arrEscala = DB_ESCALA_obtenerNodosHijos($item['id_escala']);	  	
	  }
	  return $arrEscala;
	} else {
	  return array();
	}
  }

  /**
   * Obtener resultados existentes desde la base de datos
   */
  function obtenerResultado($arrParametros){
	$arrElemento = DB_FORM_obtenerElemento($arrParametros);
	if(!empty($arrElemento['id_nivel_elemento_negocio'])){
	  return DB_RESULT_obtenerResultadoNegocio($arrParametros);	    
	}elseif(!empty($arrElemento['id_nivel_region_pais'])){
	  	  	
	}elseif(!empty($arrElemento['id_nivel_unidad'])){
	  //Obtener desde la tabla resultado el nivel de la unidad 
	  return DB_RESULT_obtenerUnidadResultado($arrParametros);
	}	
	return array();
  }


?>