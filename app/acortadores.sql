-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-12-2019 a las 21:53:40
-- Versión del servidor: 10.1.31-MariaDB
-- Versión de PHP: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `acortador_php`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acortadores_subidas`
--

CREATE TABLE `acortadores_subidas` (
  `id_subida` bigint(20) UNSIGNED NOT NULL,
  `id_acortador` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `acortadores_subidas`
--

INSERT INTO `acortadores_subidas` (`id_subida`, `id_acortador`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `clave` varchar(255) NOT NULL,
  `valor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ajustes`
--

INSERT INTO `ajustes` (`clave`, `valor`) VALUES
('ENLACE_MEMBRESIA', 'https://parzibyte.me/contacto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enlaces_subidas`
--

CREATE TABLE `enlaces_subidas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_subida` bigint(20) UNSIGNED NOT NULL,
  `leyenda` varchar(255) NOT NULL,
  `enlace_original` varchar(1024) NOT NULL,
  `enlace_acortado` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `enlaces_subidas`
--

INSERT INTO `enlaces_subidas` (`id`, `id_subida`, `leyenda`, `enlace_original`, `enlace_acortado`) VALUES
(1, 1, 'Mi Twitter', 'https://twitter.com/parzibyte', 'http://q.gs/F3bq1'),
(2, 1, 'Mi Facebook', 'https://facebook.com/parzibyte.fanpage', 'http://q.gs/F3byY'),
(3, 1, 'Blog', 'https://parzibyte.me/blog', 'http://q.gs/F0sp7');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restablecimientos_passwords_usuarios`
--

CREATE TABLE `restablecimientos_passwords_usuarios` (
  `token` varchar(20) NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` varchar(255) NOT NULL,
  `datos` text NOT NULL,
  `ultimo_acceso` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`id`, `datos`, `ultimo_acceso`) VALUES
('ajb41lj7oi7glel3vmbt7eim4d', '', 1576468175),
('aqfpvqeeuovq4tinqu7iudvigl', 'correoUsuario|s:15:\"admin@gmail.com\";idUsuario|i:1;administrador|b:0;token_csrf|s:20:\"6846daf7d6132edab128\";', 1576468360);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones_usuarios`
--

CREATE TABLE `sesiones_usuarios` (
  `id_sesion` varchar(255) NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sesiones_usuarios`
--

INSERT INTO `sesiones_usuarios` (`id_sesion`, `id_usuario`) VALUES
('aqfpvqeeuovq4tinqu7iudvigl', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subidas`
--

CREATE TABLE `subidas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `token` char(5) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `subidas`
--

INSERT INTO `subidas` (`id`, `titulo`, `token`, `descripcion`, `fecha`) VALUES
(1, 'Enlaces de Parzibyte', 'BgidC', 'Estos son enlaces de prueba para el software ;)', '2019-12-15 21:49:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `administrador` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_vencimiento_pago` date NOT NULL DEFAULT '1970-01-01',
  `correo` varchar(255) NOT NULL,
  `palabra_secreta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `administrador`, `fecha_vencimiento_pago`, `correo`, `palabra_secreta`) VALUES
(1, 1, '1970-01-01', 'admin@gmail.com', '$2y$10$kaT7N64bf3o9SQVxk57qZupY5jhnVkvecP1sJke9.WDv9aEEgLbza');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_no_verificados`
--

CREATE TABLE `usuarios_no_verificados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `correo` varchar(255) NOT NULL,
  `palabra_secreta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificaciones_pendientes_usuarios`
--

CREATE TABLE `verificaciones_pendientes_usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(20) NOT NULL,
  `id_usuario_no_verificado` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acortadores_subidas`
--
ALTER TABLE `acortadores_subidas`
  ADD KEY `id_subida` (`id_subida`);

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `enlaces_subidas`
--
ALTER TABLE `enlaces_subidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_subida` (`id_subida`);

--
-- Indices de la tabla `restablecimientos_passwords_usuarios`
--
ALTER TABLE `restablecimientos_passwords_usuarios`
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sesiones_usuarios`
--
ALTER TABLE `sesiones_usuarios`
  ADD UNIQUE KEY `id_sesion` (`id_sesion`);

--
-- Indices de la tabla `subidas`
--
ALTER TABLE `subidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `usuarios_no_verificados`
--
ALTER TABLE `usuarios_no_verificados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `verificaciones_pendientes_usuarios`
--
ALTER TABLE `verificaciones_pendientes_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `id_usuario_no_verificado` (`id_usuario_no_verificado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `enlaces_subidas`
--
ALTER TABLE `enlaces_subidas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `subidas`
--
ALTER TABLE `subidas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios_no_verificados`
--
ALTER TABLE `usuarios_no_verificados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `verificaciones_pendientes_usuarios`
--
ALTER TABLE `verificaciones_pendientes_usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acortadores_subidas`
--
ALTER TABLE `acortadores_subidas`
  ADD CONSTRAINT `acortadores_subidas_ibfk_1` FOREIGN KEY (`id_subida`) REFERENCES `subidas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `enlaces_subidas`
--
ALTER TABLE `enlaces_subidas`
  ADD CONSTRAINT `enlaces_subidas_ibfk_1` FOREIGN KEY (`id_subida`) REFERENCES `subidas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `restablecimientos_passwords_usuarios`
--
ALTER TABLE `restablecimientos_passwords_usuarios`
  ADD CONSTRAINT `restablecimientos_passwords_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `verificaciones_pendientes_usuarios`
--
ALTER TABLE `verificaciones_pendientes_usuarios`
  ADD CONSTRAINT `verificaciones_pendientes_usuarios_ibfk_1` FOREIGN KEY (`id_usuario_no_verificado`) REFERENCES `usuarios_no_verificados` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
