-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 27-08-2014 a las 18:13:00
-- Versión del servidor: 5.5.38-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `psicus_satisfaccion_laboral`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpp_slorg_competencias`
--

CREATE TABLE IF NOT EXISTS `dpp_slorg_competencias` (
  `id_competencia` int(11) NOT NULL AUTO_INCREMENT,
  `competencia` varchar(1000) DEFAULT NULL,
  `nivel_dominio` int(11) DEFAULT NULL,
  `rut` varchar(10) DEFAULT NULL,
  `competencia_add` varchar(1000) DEFAULT NULL,
  `nivel_dominio_add` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_competencia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpp_slorg_funciones`
--

CREATE TABLE IF NOT EXISTS `dpp_slorg_funciones` (
  `id_funcion` int(11) NOT NULL AUTO_INCREMENT,
  `funcion` text,
  `rut` varchar(10) DEFAULT NULL,
  `funcion_turno` text,
  PRIMARY KEY (`id_funcion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpp_slorg_funciones_extras`
--

CREATE TABLE IF NOT EXISTS `dpp_slorg_funciones_extras` (
  `rut` varchar(10) DEFAULT NULL,
  `funcion1` varchar(4000) DEFAULT NULL,
  `funcion2` varchar(4000) DEFAULT NULL,
  `funcion3` varchar(4000) DEFAULT NULL,
  `funcion4` varchar(4000) DEFAULT NULL,
  `funcion5` varchar(4000) DEFAULT NULL,
  `funcion6` varchar(4000) DEFAULT NULL,
  `funcion7` varchar(4000) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpp_slorg_licitaciones`
--

CREATE TABLE IF NOT EXISTS `dpp_slorg_licitaciones` (
  `rut` varchar(50) DEFAULT NULL,
  `l1_p` varchar(120) DEFAULT NULL,
  `le_p` varchar(120) DEFAULT NULL,
  `lp_p` varchar(120) DEFAULT NULL,
  `co_p` varchar(120) DEFAULT NULL,
  `b2_p` varchar(120) DEFAULT NULL,
  `e2_p` varchar(120) DEFAULT NULL,
  `ls_p` varchar(120) DEFAULT NULL,
  `l1_e` varchar(120) DEFAULT NULL,
  `le_e` varchar(120) DEFAULT NULL,
  `lp_e` varchar(120) DEFAULT NULL,
  `co_e` varchar(120) DEFAULT NULL,
  `b2_e` varchar(120) DEFAULT NULL,
  `e2_e` varchar(120) DEFAULT NULL,
  `ls_e` varchar(120) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpp_slorg_preguntas`
--

CREATE TABLE IF NOT EXISTS `dpp_slorg_preguntas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `n` int(11) DEFAULT NULL,
  `pregunta` varchar(1000) DEFAULT NULL,
  `posicion` int(11) DEFAULT NULL,
  `dimension` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpp_slorg_resultados`
--

CREATE TABLE IF NOT EXISTS `dpp_slorg_resultados` (
  `rut` varchar(100) DEFAULT NULL,
  `unidad` int(11) DEFAULT NULL,
  `cjuridica` int(11) DEFAULT NULL,
  `funcion` int(11) DEFAULT NULL,
  `antiguedad` int(11) DEFAULT NULL,
  `cr` int(11) DEFAULT NULL,
  `p1` int(11) DEFAULT NULL,
  `p2` int(11) DEFAULT NULL,
  `p3` int(11) DEFAULT NULL,
  `p4` int(11) DEFAULT NULL,
  `p5` int(11) DEFAULT NULL,
  `p6` int(11) DEFAULT NULL,
  `p7` int(11) DEFAULT NULL,
  `p8` int(11) DEFAULT NULL,
  `p9` int(11) DEFAULT NULL,
  `p10` int(11) DEFAULT NULL,
  `p11` int(11) DEFAULT NULL,
  `p12` int(11) DEFAULT NULL,
  `p13` int(11) DEFAULT NULL,
  `p14` int(11) DEFAULT NULL,
  `p15` int(11) DEFAULT NULL,
  `p16` int(11) DEFAULT NULL,
  `p17` int(11) DEFAULT NULL,
  `p18` int(11) DEFAULT NULL,
  `p19` int(11) DEFAULT NULL,
  `p20` int(11) DEFAULT NULL,
  `p21` int(11) DEFAULT NULL,
  `p22` int(11) DEFAULT NULL,
  `p23` int(11) DEFAULT NULL,
  `p24` int(11) DEFAULT NULL,
  `p25` int(11) DEFAULT NULL,
  `p26` int(11) DEFAULT NULL,
  `p27` int(11) DEFAULT NULL,
  `p28` int(11) DEFAULT NULL,
  `p29` int(11) DEFAULT NULL,
  `p30` int(11) DEFAULT NULL,
  `p31` int(11) DEFAULT NULL,
  `p32` int(11) DEFAULT NULL,
  `p33` int(11) DEFAULT NULL,
  `p34` int(11) DEFAULT NULL,
  `p35` int(11) DEFAULT NULL,
  `p36` int(11) DEFAULT NULL,
  `p37` int(11) DEFAULT NULL,
  `p38` int(11) DEFAULT NULL,
  `p39` int(11) DEFAULT NULL,
  `p40` int(11) DEFAULT NULL,
  `p41` int(11) DEFAULT NULL,
  `p42` int(11) DEFAULT NULL,
  `p43` int(11) DEFAULT NULL,
  `p44` int(11) DEFAULT NULL,
  `p45` int(11) DEFAULT NULL,
  `p46` int(11) DEFAULT NULL,
  `p47` int(11) DEFAULT NULL,
  `p48` int(11) DEFAULT NULL,
  `p49` int(11) DEFAULT NULL,
  `p50` int(11) DEFAULT NULL,
  `p51` int(11) DEFAULT NULL,
  `p52` int(11) DEFAULT NULL,
  `p53` int(11) DEFAULT NULL,
  `p54` int(11) DEFAULT NULL,
  `p55` int(11) DEFAULT NULL,
  `p56` int(11) DEFAULT NULL,
  `p57` int(11) DEFAULT NULL,
  `p58` int(11) DEFAULT NULL,
  `p59` int(11) DEFAULT NULL,
  `p60` int(11) DEFAULT NULL,
  `p61` int(11) DEFAULT NULL,
  `p62` int(11) DEFAULT NULL,
  `p63` int(11) DEFAULT NULL,
  `nivel1` int(11) DEFAULT NULL,
  `nivel2` int(11) DEFAULT NULL,
  `nivel3` int(11) DEFAULT NULL,
  `nivel4` int(11) DEFAULT NULL,
  `nivel5` int(11) DEFAULT NULL,
  `nivel6` int(11) DEFAULT NULL,
  `nivel7` int(11) DEFAULT NULL,
  `nivel8` int(11) DEFAULT NULL,
  `nivel9` int(11) DEFAULT NULL,
  `nivel10` int(11) DEFAULT NULL,
  `nivel11` int(11) DEFAULT NULL,
  `nivel12` int(11) DEFAULT NULL,
  `nivel13` int(11) DEFAULT NULL,
  `nivel14` int(11) DEFAULT NULL,
  `fortalezas` varchar(4000) DEFAULT NULL,
  `debilidades` varchar(4000) DEFAULT NULL,
  `proposicion1` int(11) DEFAULT NULL,
  `proposicion2` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpp_slorg_usuario`
--

CREATE TABLE IF NOT EXISTS `dpp_slorg_usuario` (
  `rut` varchar(100) DEFAULT NULL,
  `nombres` varchar(60) DEFAULT NULL,
  `centro_responsabilidad` varchar(120) DEFAULT NULL,
  `unidad_desempeno` varchar(120) DEFAULT NULL,
  `grado` varchar(10) DEFAULT NULL,
  `estamento` varchar(120) DEFAULT NULL,
  `calidad_juridica` varchar(120) DEFAULT NULL,
  `cargo` varchar(120) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefono` varchar(60) DEFAULT NULL,
  `fecha_ingreso_cargo` varchar(60) DEFAULT NULL,
  `profesion` varchar(120) DEFAULT NULL,
  `rut_jefe_directo` varchar(120) DEFAULT NULL,
  `cargo_jefe_directo` varchar(120) DEFAULT NULL,
  `nombre_jefe_directo` varchar(120) DEFAULT NULL,
  `email_jefe_directo` varchar(120) DEFAULT NULL,
  `form_observacion` varchar(4000) DEFAULT NULL,
  `form_objetivo` varchar(4000) DEFAULT NULL,
  `form_fecha_grabacion` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `acceso_formulario` int(11) DEFAULT NULL,
  `region` varchar(120) DEFAULT NULL,
  `funcion` varchar(120) DEFAULT NULL,
  `cargo_sd` varchar(120) DEFAULT NULL,
  `cargo_pc` varchar(120) DEFAULT NULL,
  `cargo_sup` varchar(120) DEFAULT NULL,
  `cargo_ps` varchar(120) DEFAULT NULL,
  `clientes_internos` varchar(255) DEFAULT NULL,
  `clientes_externos` varchar(255) DEFAULT NULL,
  `titulo` varchar(120) DEFAULT NULL,
  `especializacion` varchar(120) DEFAULT NULL,
  `experiencia` varchar(120) DEFAULT NULL,
  `cargo_ingresado` varchar(120) DEFAULT NULL,
  `nombre_jefe_ingresado` varchar(120) DEFAULT NULL,
  `unidad_desempeno_ingresado` varchar(120) DEFAULT NULL,
  `mercado_publico` varchar(120) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_admin`
--

CREATE TABLE IF NOT EXISTS `slorg_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texto1` varchar(1000) DEFAULT NULL,
  `texto2` varchar(4000) DEFAULT NULL,
  `texto3` varchar(4000) DEFAULT NULL,
  `texto_inicio1` varchar(1000) DEFAULT NULL,
  `texto_inicio2` varchar(4000) DEFAULT NULL,
  `p61` int(11) DEFAULT NULL,
  `p62` int(11) DEFAULT NULL,
  `p63` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_antiguedad`
--

CREATE TABLE IF NOT EXISTS `slorg_antiguedad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `antiguedad` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_cj`
--

CREATE TABLE IF NOT EXISTS `slorg_cj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cj` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_dimension`
--

CREATE TABLE IF NOT EXISTS `slorg_dimension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dimension` varchar(1000) DEFAULT NULL,
  `promedio` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_fd`
--

CREATE TABLE IF NOT EXISTS `slorg_fd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fd` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_resultados`
--

CREATE TABLE IF NOT EXISTS `slorg_resultados` (
  `rut` varchar(100) DEFAULT NULL,
  `unidad` int(11) DEFAULT NULL,
  `cjuridica` int(11) DEFAULT NULL,
  `funcion` int(11) DEFAULT NULL,
  `antiguedad` int(11) DEFAULT NULL,
  `numero_pregunta` int(11) DEFAULT NULL,
  `pregunta_unidad` int(11) DEFAULT NULL,
  `pregunta_instit` int(11) DEFAULT NULL,
  `nivel_1` int(11) DEFAULT NULL,
  `nivel_2` int(11) DEFAULT NULL,
  `nivel_3` int(11) DEFAULT NULL,
  `nivel_4` int(11) DEFAULT NULL,
  `nivel_5` int(11) DEFAULT NULL,
  `nivel_6` int(11) DEFAULT NULL,
  `nivel_7` int(11) DEFAULT NULL,
  `nivel_8` int(11) DEFAULT NULL,
  `nivel_9` int(11) DEFAULT NULL,
  `nivel_10` int(11) DEFAULT NULL,
  `nivel_11` int(11) DEFAULT NULL,
  `nivel_12` int(11) DEFAULT NULL,
  `nivel_13` int(11) DEFAULT NULL,
  `nivel_14` int(11) DEFAULT NULL,
  `fortalezas` varchar(4000) DEFAULT NULL,
  `debilidades` varchar(4000) DEFAULT NULL,
  `proposicion1` int(11) DEFAULT NULL,
  `proposicion2` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_res_seg`
--

CREATE TABLE IF NOT EXISTS `slorg_res_seg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rut` varchar(100) DEFAULT NULL,
  `id_segmentacion` int(11) DEFAULT NULL,
  `nombre_seg` varchar(1000) DEFAULT NULL,
  `var_seg` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_segmentacion`
--

CREATE TABLE IF NOT EXISTS `slorg_segmentacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(1000) DEFAULT NULL,
  `var1` varchar(1000) DEFAULT NULL,
  `var2` varchar(1000) DEFAULT NULL,
  `var3` varchar(1000) DEFAULT NULL,
  `var4` varchar(1000) DEFAULT NULL,
  `var5` varchar(1000) DEFAULT NULL,
  `var6` varchar(1000) DEFAULT NULL,
  `var7` varchar(1000) DEFAULT NULL,
  `var8` varchar(1000) DEFAULT NULL,
  `var9` varchar(1000) DEFAULT NULL,
  `var10` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_segmentaciones`
--

CREATE TABLE IF NOT EXISTS `slorg_segmentaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_segmentacion_opciones`
--

CREATE TABLE IF NOT EXISTS `slorg_segmentacion_opciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `segmentacion_id` int(11) NOT NULL,
  `value` varchar(1000) DEFAULT NULL,
  `texto` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_segmentacion_resultados`
--

CREATE TABLE IF NOT EXISTS `slorg_segmentacion_resultados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rut` varchar(100) DEFAULT NULL,
  `segmentacion_id` int(11) NOT NULL,
  `segmentacion_opcion_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_select_options`
--

CREATE TABLE IF NOT EXISTS `slorg_select_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `select_id` int(11) NOT NULL,
  `selected` tinyint(1) NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `select_id` (`select_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slorg_select_options_res`
--

CREATE TABLE IF NOT EXISTS `slorg_select_options_res` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `rut` varchar(100) DEFAULT NULL,
  `id_select` int(11) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `id_select` (`id_select`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
