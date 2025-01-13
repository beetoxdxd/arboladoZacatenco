-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-12-2024 a las 06:10:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `arbolado`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brigadas`
--

CREATE TABLE `brigadas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `seccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brigadistas`
--

CREATE TABLE `brigadistas` (
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contrasena` varchar(20) NOT NULL,
  `brigada` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censos`
--

CREATE TABLE `censos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `brigada` int(11) DEFAULT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `censo_arboles`
--

CREATE TABLE `censo_arboles` (
  `id` int(11) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `altura` double NOT NULL,
  `diametro` double NOT NULL,
  `latitud` double NOT NULL,
  `longitud` double NOT NULL,
  `condicion` text NOT NULL,
  `censo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinadores`
--

CREATE TABLE `coordinadores` (
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `telefono` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_reporte`
--

CREATE TABLE `estado_reporte` (
  `id` int(11) NOT NULL,
  `reporte_id` int(11) NOT NULL,
  `fecha_cambio` date NOT NULL DEFAULT current_timestamp(),
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE `reporte` (
  `id` int(11) NOT NULL,
  `tipoReporte` varchar(50) NOT NULL,
  `latitud` double NOT NULL,
  `longitud` double NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` mediumblob NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `estado_reporte` text NOT NULL DEFAULT 'Pendiente',
  `brigada_asignada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `reporte`
--
DELIMITER $$
CREATE TRIGGER `insertar_estado_inicial` AFTER INSERT ON `reporte` FOR EACH ROW INSERT INTO `estado_reporte` (`reporte_id`, `notas`)
  VALUES (NEW.`id`, CONCAT('El reporte ha sido registrado, por lo que su estado inicial es ', NEW.`estado_reporte`))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporteincidente`
--

CREATE TABLE `reporteincidente` (
  `id` int(11) NOT NULL,
  `opcion` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `descripcion` text NOT NULL,
  `accionTomada` text NOT NULL,
  `reporte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `nombre` varchar(100) NOT NULL,
  `poligono` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `brigadas`
--
ALTER TABLE `brigadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brigadas_ibfk_1` (`seccion`);

--
-- Indices de la tabla `brigadistas`
--
ALTER TABLE `brigadistas`
  ADD PRIMARY KEY (`correo`),
  ADD KEY `brigadistas_ibfk_1` (`brigada`);

--
-- Indices de la tabla `censos`
--
ALTER TABLE `censos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `censos_ibfk_1` (`brigada`);

--
-- Indices de la tabla `censo_arboles`
--
ALTER TABLE `censo_arboles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `censo_arboles_ibfk_1` (`censo`);

--
-- Indices de la tabla `coordinadores`
--
ALTER TABLE `coordinadores`
  ADD PRIMARY KEY (`correo`);

--
-- Indices de la tabla `estado_reporte`
--
ALTER TABLE `estado_reporte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado_reporte_ibfk_1` (`reporte_id`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporte_ibfk_1` (`brigada_asignada`);

--
-- Indices de la tabla `reporteincidente`
--
ALTER TABLE `reporteincidente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporteincidente_ibfk_1` (`reporte`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `brigadas`
--
ALTER TABLE `brigadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `censos`
--
ALTER TABLE `censos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `censo_arboles`
--
ALTER TABLE `censo_arboles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_reporte`
--
ALTER TABLE `estado_reporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reporteincidente`
--
ALTER TABLE `reporteincidente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `brigadas`
--
ALTER TABLE `brigadas`
  ADD CONSTRAINT `brigadas_ibfk_1` FOREIGN KEY (`seccion`) REFERENCES `secciones` (`nombre`) ON DELETE CASCADE;

--
-- Filtros para la tabla `brigadistas`
--
ALTER TABLE `brigadistas`
  ADD CONSTRAINT `brigadistas_ibfk_1` FOREIGN KEY (`brigada`) REFERENCES `brigadas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `censos`
--
ALTER TABLE `censos`
  ADD CONSTRAINT `censos_ibfk_1` FOREIGN KEY (`brigada`) REFERENCES `brigadas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `censo_arboles`
--
ALTER TABLE `censo_arboles`
  ADD CONSTRAINT `censo_arboles_ibfk_1` FOREIGN KEY (`censo`) REFERENCES `censos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estado_reporte`
--
ALTER TABLE `estado_reporte`
  ADD CONSTRAINT `estado_reporte_ibfk_1` FOREIGN KEY (`reporte_id`) REFERENCES `reporte` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD CONSTRAINT `reporte_ibfk_1` FOREIGN KEY (`brigada_asignada`) REFERENCES `brigadas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reporteincidente`
--
ALTER TABLE `reporteincidente`
  ADD CONSTRAINT `reporteincidente_ibfk_1` FOREIGN KEY (`reporte`) REFERENCES `reporte` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
