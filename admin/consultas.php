<?php
include("../include/dbconection.php"); 






//***********************consultas varias************************************************************////
function SeleccionarTipoFormulario()
{
	$conexion = DAL::conexion();
	$sql = "select * FROM slorg_tipo_formulario";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	return $rs;
	
}

// veo el uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuultimo cliente :) 
function ultimoCliente()
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT id_cliente, nombre_cliente FROM slorg_cliente where id_cliente=(SELECT MAX(id_cliente) AS id FROM slorg_cliente);
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	$row = mysql_fetch_array($rs);
	return $row;
	

}

function validaSiHayValores($idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			select id from slorg_admin where id_cliente = $idCliente;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	$row = mysql_fetch_array($rs);
	return $row['0'];
}


function verCargos($idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT * FROM slorg_cargo where id_cliente = $idCliente;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	return $rs;
}

function agregarCargos($cargo,$descripcion,$idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			INSERT INTO slorg_cargo (nombre_cargo,descripcion_cargo,id_cliente) 
			VALUES ('$cargo', '$descripcion', $idCliente);

			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	//return $rs;
}

function verEstamentos($idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT * FROM slorg_estamento where id_cliente = $idCliente;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	return $rs;
}

function agregarEstamentos($estamento,$descripcion,$idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			INSERT INTO slorg_estamento (nombre_estamento,descripcion_estamento,id_cliente) 
			VALUES ('$estamento', '$descripcion', $idCliente);

			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	//return $rs;
}

function verUnidades($idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT u.id,u.nombre,u.sigla, n.nombre_nivel 
			FROM slorg_unidad u
			INNER JOIN slorg_unidad_nivel n ON u.id_nivel = n.id_nivel  
			WHERE u.id_cliente = $idCliente;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	return $rs;
}


function agregarUnidades($nombreUnidad,$idEstado,$idCliente,$idNivel,$sigla)
{
	$conexion = DAL::conexion();
	$sql = "
			INSERT INTO slorg_unidad (nombre,id_estado,id_cliente,id_nivel,sigla) 
			VALUES ('$nombreUnidad', $idEstado, $idCliente,$idNivel,'$sigla');

			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	//return $rs;
}

function verNivelesUnidades($idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT * FROM slorg_unidad_nivel where id_cliente = $idCliente;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	return $rs;
}

function verClientes()
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT * FROM slorg_cliente;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	return $rs;
}

function verClienteActual($idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT * FROM slorg_cliente where id_cliente = $idCliente;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	$row = mysql_fetch_array($rs);
	return $row['nombre_cliente'];
}


function verUsuario($idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT u.rut,u.dv,u.nombre,u.apellido_paterno,u.apellido_materno, ud.nombre as nombre_unidad,u.sexo,e.nombre_estamento,c.nombre_cargo,u.email,u.telefono,u.username
		    FROM dpp_slorg_usuario u 
		    INNER JOIN slorg_unidad ud ON ud.id = u.id_unidad
		    INNER JOIN slorg_estamento e ON u.id_estamento = e.id_estamento
		    INNER JOIN slorg_cargo c ON u.id_cargo = c.id_cargo
		    WHERE u.id_cliente = $idCliente;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	return $rs;
}

function insertarUsuario($rut,$dv,$nombre,$apellidoPaterno,$apellidoMaterno,$unidad,$sexo,
						$estamento,$cargo,$email,$telefono,$username,$password,$idCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			INSERT INTO `dpp_slorg_usuario` (`rut`, `dv`, `nombre`, `apellido_paterno`, `apellido_materno`, `id_unidad`, `sexo`, `id_estamento`, 
								`id_cargo`, `email`, `telefono`, `username`, `password`, `id_cliente`, `id_estado`) 
                                VALUES
			($rut, '$dv', '$nombre', '$apellidoPaterno', '$apellidoMaterno', $unidad, '$sexo', $estamento, $cargo, '$email', '$telefono', '$username', '$password', $idCliente, 1);
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	//return $rs;
}

function verTipoUsuario()
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT * FROM slorg_tipo_usuario;
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	//$row = mysql_fetch_array($rs);
	return $rs;
}

function ultimoUsuario()
{
	$conexion = DAL::conexion();
	$sql = "
			SELECT id_usuario FROM dpp_slorg_usuario where id_usuario=(SELECT MAX(id_usuario) AS id FROM dpp_slorg_usuario);
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	$row = mysql_fetch_array($rs);
	return $row['0'];
	

}


function insertarRelTipoUsuario($idUltimoUsuario,$tipoUsuario)
{
	$conexion = DAL::conexion();
	$sql = "
			INSERT INTO `rel_usuario_tipo_usuario` (`id_usuario`, `id_tipo_usuario`) VALUES
			($idUltimoUsuario, $tipoUsuario);
			";
	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	$row = mysql_fetch_array($rs);
	return $row['0'];
	

}






//**********************************************************************************************************************************//

// Aca sacamos el el id que correspondera al formulario 1 para luego hacer el update
function form1($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			select id from slorg_elemento_formulario where nombre_elemento ='Formulario 1' and id_cliente =$idCliente;
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);

	$row = mysql_fetch_array($rs);
	return $row['0'];
}


//sacamos el de istas
function formIstas($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			select id from slorg_elemento_formulario where nombre_elemento ='Formulario Istas' and id_cliente =$idCliente;
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	$row = mysql_fetch_array($rs);
	return $row['0'];
}



// hacemos el update
function autoUpdateElementoForm($form1,$idCliente)
{
	$conexion = DAL::conexion();
	$sql = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$form1' WHERE nombre_elemento ='Texto Bienvenida' and id_cliente=$idCliente and form_name ='form';";
	$sql2 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$form1' WHERE nombre_elemento ='Centros de Responsabilidad' and id_cliente=$idCliente and form_name ='form';";
	$sql3 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$form1' WHERE nombre_elemento ='Segmentación' and id_cliente=$idCliente and form_name ='form';";
	$sql4 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$form1' WHERE nombre_elemento ='Pregunta Cuestionario' and id_cliente=$idCliente and form_name ='form';";
	$sql5 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$form1' WHERE nombre_elemento ='Selección de Nivel de Importancia' and id_cliente=$idCliente and form_name ='form';";
	$sql6 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$form1' WHERE nombre_elemento ='Boton Guardar' and id_cliente=$idCliente and form_name ='form';";
	$sql7 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$form1' WHERE nombre_elemento ='Boton Guardado Parcial' and id_cliente=$idCliente and form_name ='form';";
	$sql8 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$form1' WHERE nombre_elemento ='Texto Instrucciones' and id_cliente=$idCliente and form_name ='form';";

	mysql_query($sql,$conexion) or die(mysql_error().$sql);
	mysql_query($sql2,$conexion) or die(mysql_error().$sql2);
	mysql_query($sql3,$conexion) or die(mysql_error().$sql3);
	mysql_query($sql4,$conexion) or die(mysql_error().$sql4);
	mysql_query($sql5,$conexion) or die(mysql_error().$sql5);
	mysql_query($sql6,$conexion) or die(mysql_error().$sql6);
	mysql_query($sql7,$conexion) or die(mysql_error().$sql7);
	mysql_query($sql8,$conexion) or die(mysql_error().$sql8);
}


function autoUpdateElementoFormIstas($formIstas,$idCliente)
{
	$conexion = DAL::conexion();
	$sql = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$formIstas' WHERE nombre_elemento ='Texto Bienvenida' and id_cliente=$idCliente and form_name ='istas';";
	$sql2 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$formIstas' WHERE nombre_elemento ='Pregunta Cuestionario' and id_cliente=$idCliente and form_name ='istas';";
	$sql3 = "UPDATE slorg_elemento_formulario SET id_elemento_formulario_padre ='$formIstas' WHERE nombre_elemento ='Boton Guardar' and id_cliente=$idCliente and form_name ='istas';";

	mysql_query($sql,$conexion) or die(mysql_error().$sql);
	mysql_query($sql2,$conexion) or die(mysql_error().$sql2);
	mysql_query($sql3,$conexion) or die(mysql_error().$sql3);
}



//******************************************************************************************************************************************//

/*
			
			
		
			
			
			
			
			
*/












//********************************************************************************************************/////
//***************************************** INSERTAR ****************************************************///////
//******************************************************************************************************////////
function agregarCliente($datosCliente)
{
	$conexion = DAL::conexion();
	$sql = "
			
			INSERT INTO `slorg_cliente` (`nombre_cliente`, `rut_cliente`, `dv_cliente`, `nombre_contraparte`, `correo_contraparte`, 
			`telefono_contraparte`, `id_estado`, `tipo_formulario`) 
			VALUES
			('$datosCliente[0]', $datosCliente[1], $datosCliente[2], '$datosCliente[3]', '$datosCliente[4]', $datosCliente[5], 
				$datosCliente[6], $datosCliente[7])
	";

	 
	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
	
}
///////******************** valores por defecto ****************************//////

function agregarParametroGraficoCliente($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `parametros_graficos_cliente` (`id_cliente`, `nombre_grafico`, `parametro`, `valor`) VALUES
			($idCliente, 'TC', 'id_nivel_variable', 3),
			($idCliente, 'PVPI', 'id_nivel_variable', 3),
			($idCliente, 'PCRPI', 'id_nivel_preguntas', 3),
			($idCliente, 'PCRPI', 'id_nivel_variable', 3),
			($idCliente, 'ISTA', 'id_nivel', 6),
			($idCliente, 'DSG', 'id_escala', 1),
			($idCliente, 'DSG', 'id_nivel_preguntas', 3),
			($idCliente, 'DSG', 'id_nivel_variables', 2),
			($idCliente, 'DSV', 'id_nivel', 3),
			($idCliente, 'DSV', 'id_escala', 1),
			($idCliente, 'BS', 'id_escala', 1),
			($idCliente, 'BS', 'id_nivel', 3),
			($idCliente, 'BS', 'id_nivel_preguntas', 3),
			($idCliente, 'BS', 'id_nivel_variables', 2),
			($idCliente, 'PCRPI', 'id_nivel_variables', 2),
			($idCliente, 'DSCR', 'id_escala', 1),
			($idCliente, 'DSCR', 'id_nivel_preguntas', 3),
			($idCliente, 'DSCR', 'id_nivel_variables', 2),
			($idCliente, 'PR', 'tipo_elemento_negocio', 1),
			($idCliente, 'PR', 'id_nivel', 2),
			($idCliente, 'INV', 'id_nivel', 3),
			($idCliente, 'INV', 'tipo_elemento_negocio', 1),
			($idCliente, 'INV', 'puntaje_maximo_escala', 4),
			($idCliente, 'PR', 'id_escala', 1),
			($idCliente, 'SEG1', 'id_nivel_variable', 3),
			($idCliente, 'SEG2', 'id_nivel_variable', 3),
			($idCliente, 'SEG3', 'id_nivel_variable', 3),
			($idCliente, 'SEG4', 'id_nivel_variable', 3),
			($idCliente, 'SEG11', 'id_nivel_variable', 3),
			($idCliente, 'ranking', 'id_escala', 1);
        	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}


function agregarAdmin($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_admin` (`id_cliente`, `texto1`, `texto2`, `texto3`, `texto_inicio1`, `texto_inicio2`, `p61`, `p62`, `p63`) 
			VALUES
			($idCliente, '', 'CUESTIONARIO DE SATISFACCION LABORAL', '<p>Estimados funcionarios (as),</p><p>El Estudio de SatisfacciÃ³n Laboral (ESL) busca determinar las variables que pueden incidir positiva y/o negativamente sobre la percepciÃ³n que tienen los funcionarios sobre la organizaciÃ³n que conforman, mediante la generaciÃ³n de un Balance Social Laboral.</p><p>Antes de Responder el Cuestionario debe considerar que:<br/>El Ã©xito de este Estudio depende de su colaboraciÃ³n y sinceridad al responder. AdemÃ¡s, le aseguramos que la informaciÃ³n entregada por usted serÃ¡ CONFIDENCIAL.</p><p>INSTRUCCIONES:</p><p>A continuaciÃ³n encontrarÃ¡ una serie de PROPOSICIONES, frente a cada una de ellas usted debe seleccionar la alternativa que mejor lo represente.</p><p>Es importante, que usted responda TODAS las PROPOSICIONES, de lo contrario su Cuestionario no podrÃ¡ ser considerado para el anÃ¡lisis. Cabe seÃ±alar que no existen respuestas buenas o malas, correctas o incorrectas, solo nos interesa su OPINIÃ“N.</p><p>Para focalizar los resultados y elaborar Planes de AcciÃ³n mÃ¡s efectivos, es necesario diferenciar la informaciÃ³n recogida en el Cuestionario, para tales efectos, le agradecemos seleccionar la informaciÃ³n que corresponda a sus datos:</p>', 
			'', '', 0, 0, 1);

	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}



function agregarAntiguedad($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_antiguedad` (`nombre_antiguedad`, `descripcion_antiguedad`, `id_cliente`, `minimo`, `maximo`) VALUES
			('MENOR A 1 AÃ‘O0d	', NULL, $idCliente, 0, 1),
			('ENTRE 1 Y 4 AÃ‘OS Â Â Â  Â Â Â ', NULL, $idCliente, 1, 4),
			('ENTRE 5 Y 7 AÃ‘OS	', NULL, $idCliente, 4, 7),
			('ENTRE 8 Y 10 AÃ‘OS	', NULL, $idCliente, 7, 10),
			('10 O MAS AÃ‘OS	', NULL, $idCliente, 10, NULL);
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}


function agregarCalidadJuridica($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_calidad_juridica` (`nombre_calidad_juridica`, `descripcion_calidad_juridica`, `id_cliente`) VALUES
			('HSA', NULL, $idCliente),
			('PLANTA', NULL, $idCliente),
			('CONTRATA', NULL, $idCliente);
			";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}


function agregarDimension($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_dimension` (`dimension`, `promedio`, `id_cliente`) VALUES
			('IDENTIDAD INSTITUCIONAL', 1.2, $idCliente),
			('MOTIVACIÃ“N', 0, $idCliente),
			('RESPONSABILIDAD', 0, $idCliente),
			('INCENTIVOS Y BENEFICIOS', 0, $idCliente),
			('ESTABILIDAD LABORAL', 0, $idCliente),
			('TIEMPO PERSONAL', 0, $idCliente),
			('COMUNICACIONES', 0, $idCliente),
			('ESTRATEGIA INSTITUCIONAL', 0, $idCliente),
			('ESPACIO DE TRABAJO', 0, $idCliente),
			('OPORTUNIDAD DE DESARROLLO', 0, $idCliente),
			('APOYO INTERPERSONAL', 0, $idCliente),
			('ESTILO DE DIRECCIÃ“N', 0, $idCliente),
			('TRABAJO EN EQUIPO', 0, $idCliente),
			('INNOVACION Y CAMBIO', 0, $idCliente);
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}


function agregarGrado($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_grado` (`nombre_grado`, `descripcion_grado`, `id_cliente`) VALUES
			('SINGR', NULL, $idCliente),
			('5', NULL, $idCliente),
			('13', NULL, $idCliente),
			('15', NULL, $idCliente),
			('8', NULL, $idCliente),
			('4', NULL, $idCliente),
			('6', NULL, $idCliente),
			('11', NULL, $idCliente),
			('19', NULL, $idCliente),
			('10', NULL, $idCliente),
			('9', NULL, $idCliente),
			('14', NULL, $idCliente),
			('12', NULL, $idCliente),
			('7', NULL, $idCliente),
			('18', NULL, $idCliente),
			('16', NULL, $idCliente),
			('2', NULL, $idCliente),
			('21', NULL, $idCliente),
			('17', NULL, $idCliente),
			('20', NULL, $idCliente),
			('3', NULL, $idCliente);
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}



function agregarSegmentaciones($idCliente)
{	
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_segmentaciones` (`name`, `title`, `id_cliente`) VALUES
			('iii_estamento', 'III. ESTAMENTO', $idCliente);
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}

function agregarUnidadNivel($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_unidad_nivel` (`id_cliente`, `nivel`, `nombre_nivel`, `id_estado`) VALUES
			($idCliente, 1, 'Centro Responsabilidad', 1),
			($idCliente, 2, 'Centro Costo', 1),
			($idCliente, 3, 'Unidad de DesempeÃ±o', 1);
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}

function agregarElementoFormulario($idCliente)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_elemento_formulario` (`nombre_elemento`, `contenido_elemento`, `titulo_elemento`, `id_elemento_formulario_padre`, `id_nivel_elemento_negocio`, `id_nivel_region_pais`, `id_nivel_unidad`, `orden`, `flag_nivel`, `por_defecto`, `id_cliente`, `id_nivel_elemento_formulario`, `id_estado`, `id_tipo_elemento`, `id_opcion_renderizacion`, `id_escala`, `id_tipo_respuesta`, `mostrar_numeracion`, `titulo_numeracion`, `titulo_pregunta`, `id_formulario_enlazado`, `fecha_inicio`, `fecha_fin`,`form_name`) 
			VALUES('Formulario 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, $idCliente, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, '2015-08-03 09:00:00', '2015-12-06 12:00:00','form'),

			('Texto Bienvenida', '<h3 align=''center''>CUESTIONARIO DE SATISFACCION/CLIMA LABORAL\n</h3>\n<br>\n<p>Estimados/as colaboradores (as),</p><p>El Estudio de Satisfacción Laboral (ESL) busca determinar las variables que pueden incidir positiva y/o negativamente sobre la percepción que tienen los funcionarios sobre la organización que conforman, mediante la generación de un Balance Social Laboral.El Estudio de Satisfacción/Clima Laboral busca determinar las variables que pueden incidir positiva y/o negativamente sobre la percepción que tienen los colaboradores sobre la organización que conforman, mediante la generación de un Balance de Satisfacción Laboral.\n</p><p>Antes de Responder el Cuestionario debe considerar que:<br/>El éxito de este Estudio depende de su colaboración y sinceridad al responder. Además, le aseguramos que la información entregada por usted será CONFIDENCIAL.</p>', NULL, 1, NULL, NULL, NULL, 1, NULL, NULL, $idCliente, 2, 1, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'form'),

			('Centros de Responsabilidad', NULL, 'I. CENTRO DE RESPONSABILIDAD', 1, NULL, NULL, 3, 3, 1, NULL,$idCliente , 2, 1, 4, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL,'form'),

			('Segmentación', NULL, NULL, 1, 4, NULL, NULL, 4, 0, NULL, $idCliente, 2, 1, 1, 2, NULL, 1, 1, NULL, 'Seleccione', NULL, NULL, NULL,'form'),

			('Pregunta Cuestionario', NULL, 'VI. A CONTINUACION SEÑALE SU NIVEL DE SATISFACCIÓN DE LAS SIGUIENTES PROPOSICIONES:', 1, 3, NULL, NULL, 5, 1, NULL, $idCliente, 2, 1, 1, 1, 1, 1, 1, 'Nº', 'PROPOSICION. <br><br>CONSIDERE COMO SU JEFATURA A LA DIRECCIÓN EJECUTIVA, SUB DIRECCIÓN EJECUTIVA Y GERENCIAS SEGÚN CORRESPONDA', NULL, NULL, NULL,'form'),

			('Selección de Nivel de Importancia', NULL, 'VII. A CONTINUACION, UD. ENCONTRARA UNA LISTA DE VARIABLES QUE INFLUYEN EN LA SATISFACCIÓN LABORAL. \n\n<UL>\n<LI>ORDENE DEL 1 AL @@COUNT_NIVEL_NEGOCIO@@, SEGUN EL NIVEL DE IMPORTANCIA QUE TIENEN PARA USTED CADA UNA DE ELLAS.\n</LI>\n<LI>\nSEÑALE CON EL NUMERO 1 LA MAS IMPORTANTE Y CON EL NUMERO @@COUNT_NIVEL_NEGOCIO@@ LA MENOS IMPORTANTE. \n</LI>\n</UL>', 1, 2, NULL, NULL, 7, 1, NULL, $idCliente, 2, 1, 1, 1, 7, 1, 1, 'Nº', 'VARIABLES ', NULL, NULL, NULL,'form'),

			('Boton Guardar', 'Guardar', NULL, 1, NULL, NULL, NULL, 8, NULL, NULL, $idCliente, 2, 1, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'form'),

			('Boton Guardado Parcial', 'Guardar y Continuar', NULL, 1, NULL, NULL, NULL, 6, NULL, NULL, $idCliente, 2, 0, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'form'),

			('Texto Instrucciones', '\r\n<p align=''center''><h4><b>INSTRUCCIONES:</b></h4></p>\r\n<p>A continuación encontrará una serie de PROPOSICIONES, frente a cada una de ellas usted debe seleccionar la alternativa que mejor lo represente.</p><p>Es importante, que usted responda TODAS las PROPOSICIONES, de lo contrario su Cuestionario no podrá ser considerado para el análisis. Cabe señalar que no existen respuestas buenas o malas, correctas o incorrectas, solo nos interesa su OPINIÓN.</p><p>Para focalizar los resultados y elaborar Planes de Acción más efectivos, es necesario diferenciar la información recogida en el Cuestionario, para tales efectos, le agradecemos seleccionar la información que corresponda a sus datos:</p>', NULL, 1, NULL, NULL, NULL, 2, NULL, NULL, $idCliente, 2, 1, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'form'),

			('Formulario Istas', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $idCliente, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'istas'),

			('Texto Bienvenida', '<table  cellpadding=''10''>\r\n<tr>\r\n <td width=''10%''>\r\n<br>\r\n<img src=''images/suseso.png''>\r\n\r\n</td>\r\n<td align=''center''>\r\n<b>\r\n<h3>Cuestionario SUSESO ISTAS 21 Versión Breve</h3>\r\n</b>\r\n</td>\r\n <td width=''10%''>\r\n<br>\r\n<img src=''images/logosinrefl.png'' width=''110px'' height=''94px''>\r\n\r\n</td>\r\n</tr>\r\n\r\n<tr>\r\n <td colspan=''3''>\r\n<br>\r\n  </td>\r\n</tr>\r\n\r\n<tr>\r\n <td colspan=''3''>\r\n\r\nEste Cuestionario incluye 20 preguntas. Para responder seleccione una sola respuesta para cada pregunta. Debe responder todas las preguntas Recuerde que no existen respuestas buenas o malas. Lo que interesa es su opinión sobre los contenidos y exigencias de su trabajo. Muchas gracias.\r\n</td>\r\n</tr>\r\n\r\n</table>\r\n', NULL, 11, NULL, NULL, NULL, 1, NULL, NULL, $idCliente, 2, 1, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'istas'),
			
			('Pregunta Cuestionario', NULL, NULL, 11, 6, NULL, NULL, 2, 0, NULL, $idCliente, 2, 1, 1, 1, 8, 1, 1, NULL, NULL, NULL, NULL, NULL,'istas'),
			
			('Boton Guardar', 'Guardar', NULL, 11, NULL, NULL, NULL, 3, NULL, NULL, $idCliente, 2, 1, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'istas');
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}

///////*********************************************************************************************************************************///////
///////*********************************************************************************************************************************///////
///////*********************************************************************************************************************************///////
///////*********************************************************************************************************************************///////

//*******************VALORES MANTENEDOR **************//////


function agregarCargo($idCliente,$nombreCargo)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_cargo` (`nombre_cargo`, `descripcion_cargo`, `id_cliente`) VALUES
			('$nombreCargo', NULL, $idCliente);
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}


function agregarEstamento($idCliente,$nombreEstamento)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_estamento` (`nombre_estamento`, `descripcion_estamento`, `id_cliente`) VALUES
			('$nombreEstamento', NULL, $idCliente);
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}


//********************UNIDADES************************

function agregarUnidad($idCliente,$nombreUnidad,$siglaUnidad)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `slorg_unidad` (`nombre`, `id_padre`, `id_estado`, `id_cliente`, `id_nivel`, `id_usuario_jefe`, `descripcion`, `sigla`) VALUES
			('$nombreUnidad', NULL, 1, $idCliente, 4, NULL, NULL, '$siglaUnidad');
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}


//**************************USUARIO***********************************////

function agregarUsuario(
	$rut,$dv,$nombre,$apellidoPaterno,$apellidoMaterno,$unidad,$sexo,$idGrado,$estamento,$calidadJuridica,$cargo,$email,$telefono,$fechaIngreso,$jefeDirecto,$username,$password,$idCliente,$idEstado,$idRegion
	)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `dpp_slorg_usuario` (`rut`, `dv`, `nombre`, `apellido_paterno`, `apellido_materno`, `id_unidad`, `sexo`, `id_grado`, `id_estamento`, 
								`id_calidad_juridica`, `id_cargo`, `email`, `telefono`, `fecha_ingreso_cargo`, `id_jefe_directo`, `username`,
                                `password`, `id_cliente`, `id_estado`, `id_region_pais`) 
                                VALUES
            (17080827, '4', 'FRANCISCO', 'MENDOZA', 'ROUMAT', 600, 'M', NULL, 20, NULL, 48, 'francisco@francisco.cl', '2 3667100', NULL, NULL, '17080827-4', '17080827-4', 7, 1, NULL);
	";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}

function agregarRelTipoUsuario($idUsuario,$tipoUsuario)
{
	$conexion = DAL::conexion();
	$sql = " 
			INSERT INTO `rel_usuario_tipo_usuario` (`id_usuario`, `id_tipo_usuario`) VALUES
			($idUsuario, $tipoUsuario);
			";

	$rs = mysql_query($sql,$conexion) or die(mysql_error().$sql);
}



?>