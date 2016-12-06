<?php
  //nombres de tablas
  define('TBL_ESTRUCTURA_CR','slorg_unidad');
  define('TBL_ESTRUCTURA_CR_NIVEL','slorg_unidad_nivel');
  define('TBL_ESTRUCTURA_LOCALIZACION','slorg_region_pais');
  define('TBL_ESTRUCTURA_LOCALIZACION_NIVEL','slorg_region_pais_nivel');
  
  //tipo elementos
  define('TIPO_ELEMENTO_BOTON','6');
  define('TEXTO_PRESENTACION','7');  
  define('TIPO_ELEMENTO_BOTON_PARCIAL','8');
    
  //condiciones para las preguntas
  define('UNA_PREGUNTA_GRUPO','1');
  define('TODAS_PREGUNTA_GRUPO','2');
  define('VALORES_DISTINTOS_PREGUNTA_GRUPO','3');
  
  define('PREFIJO_GRUPO','grupo_');
  define('PREFIJO_LINEA','lineaItem_');
  
  define('PREFIJO_ELEMENTO_NEGOCIO','n');  
  define('PREFIJO_IGNORAR_ELEMENTO','x');  
  define('PREFIJO_ELEMENTO_UNIDAD' ,'u');  
  define('PREFIJO_ELEMENTO_REGION' ,'r');
  
  define('COLUMNA_ELEMENTO_NEGOCIO','id_elemento_negocio');  
  define('COLUMNA_ELEMENTO_UNIDAD','id_unidad');  
  define('COLUMNA_ELEMENTO_NIVEL_UNIDAD','id_unidad_nivel');
  define('COLUMNA_ELEMENTO_REGION','id_region_pais');
  define('COLUMNA_ELEMENTO_NIVEL_REGION','id_region_pais_nivel');
  
  define('_MPDF_PATH',DIRNAME(__FILE__).'/mpdf60/');  
  include_once(DIRNAME(__FILE__)."/dbconection.php");
  include_once(DIRNAME(__FILE__)."/funciones.php");

  define('HOME_PATH',obtenerWebPath());	
  define('GET_CONTENT_PATH',obtenerUrl());  
  
  //opciones de renderizacion
  define('RENDER_TABLA',1);	
  define('RENDER_ITEM',2);  
	
?>