-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2025 a las 16:19:01
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `scb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

DROP TABLE IF EXISTS `docentes`;
CREATE TABLE `docentes` (
  `iddocente` int(11) NOT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `dui` varchar(15) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

DROP TABLE IF EXISTS `generos`;
CREATE TABLE `generos` (
  `idgenero` int(11) NOT NULL,
  `genero` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

DROP TABLE IF EXISTS `libros`;
CREATE TABLE `libros` (
  `idlibro` int(11) NOT NULL,
  `isbn` text NOT NULL,
  `titulo` text NOT NULL,
  `autor` text NOT NULL,
  `idgenero` int(11) DEFAULT NULL,
  `ejemplares` int(11) DEFAULT NULL,
  `estado` tinyint(11) DEFAULT 1,
  `idubicacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `libros`
--
DROP TRIGGER IF EXISTS `	trg_libros_bu_estado_por_stock`;
DELIMITER $$
CREATE TRIGGER `	trg_libros_bu_estado_por_stock` BEFORE UPDATE ON `libros` FOR EACH ROW BEGIN
  IF NEW.ejemplares IS NULL OR NEW.ejemplares <= 0 THEN
    SET NEW.estado = 0;
  ELSE
    SET NEW.estado = 1;
  END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_libros_bi_estado_por_stock`;
DELIMITER $$
CREATE TRIGGER `trg_libros_bi_estado_por_stock` BEFORE INSERT ON `libros` FOR EACH ROW BEGIN
  IF NEW.ejemplares IS NULL OR NEW.ejemplares <= 0 THEN
    SET NEW.estado = 0;
  ELSE
    SET NEW.estado = 1;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

DROP TABLE IF EXISTS `prestamos`;
CREATE TABLE `prestamos` (
  `idprestamo` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idlibro` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fechaprestamo` date DEFAULT NULL,
  `fecharetorno` date DEFAULT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `prestamos`
--
DROP TRIGGER IF EXISTS `trg_prestamo_after_delete`;
DELIMITER $$
CREATE TRIGGER `trg_prestamo_after_delete` AFTER DELETE ON `prestamos` FOR EACH ROW BEGIN
    IF OLD.estado = 1 THEN
        UPDATE libros
        SET ejemplares = ejemplares + OLD.cantidad
        WHERE idlibro = OLD.idlibro;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_prestamos_ai_descuenta`;
DELIMITER $$
CREATE TRIGGER `trg_prestamos_ai_descuenta` AFTER INSERT ON `prestamos` FOR EACH ROW BEGIN
  IF NEW.estado = 1 THEN
    UPDATE libros
    SET ejemplares = ejemplares - NEW.cantidad
    WHERE idlibro = NEW.idlibro;
  END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_prestamos_au_ajusta`;
DELIMITER $$
CREATE TRIGGER `trg_prestamos_au_ajusta` AFTER UPDATE ON `prestamos` FOR EACH ROW BEGIN
  -- Caso A: Cambio de libro en un préstamo que sigue activo
  IF NEW.estado <> 2 AND OLD.idlibro <> NEW.idlibro THEN
    -- devolver al libro anterior
    UPDATE libros
      SET ejemplares = ejemplares + OLD.cantidad
      WHERE idlibro = OLD.idlibro;

    -- descontar del nuevo (asumimos stock válido; si quieres, agrega una validación BEFORE UPDATE)
    UPDATE libros
      SET ejemplares = ejemplares - NEW.cantidad
      WHERE idlibro = NEW.idlibro;
  END IF;

  -- Caso B: Devolución del préstamo (por estado o por fecha de retorno)
  IF (OLD.estado <> 2 AND NEW.estado = 2)
     OR (OLD.fecharetorno IS NULL AND NEW.fecharetorno IS NOT NULL) THEN

    IF OLD.idlibro = NEW.idlibro THEN
      -- mismo libro: regresa 1
      UPDATE libros
        SET ejemplares = ejemplares + OLD.cantidad
        WHERE idlibro = NEW.idlibro;
    ELSE
      -- idlibro cambió en el mismo UPDATE: regresa al libro original (OLD)
      UPDATE libros
        SET ejemplares = ejemplares + OLD.cantidad
        WHERE idlibro = OLD.idlibro;
    END IF;
  END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_prestamos_bi_check_stock`;
DELIMITER $$
CREATE TRIGGER `trg_prestamos_bi_check_stock` BEFORE INSERT ON `prestamos` FOR EACH ROW BEGIN
  DECLARE v_stock INT;

  -- Solo valida si el préstamo entra como "activo" (prestado)
  IF NEW.estado = 1 THEN
    SELECT ejemplares INTO v_stock
    FROM libros
    WHERE idlibro = NEW.idlibro
    FOR UPDATE;

    IF v_stock IS NULL THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El libro no existe.';
    END IF;

    IF v_stock <= 0 THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No hay ejemplares disponibles para prestar.';
    END IF;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Docente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
CREATE TABLE `ubicaciones` (
  `idubicacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `email` text NOT NULL,
  `passw` varchar(255) NOT NULL,
  `idrol` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `usuario`, `email`, `passw`, `idrol`, `estado`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$zPqZkhYBNOtuI/gnvwIeiuLHfn8bIEa19a3CB6LAvAB1C1U..CoAy', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`iddocente`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`idgenero`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`idlibro`),
  ADD KEY `fk_libro_ubicacion` (`idubicacion`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`idprestamo`),
  ADD KEY `idusuario` (`idusuario`),
  ADD KEY `idlibro` (`idlibro`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idrol`),
  ADD UNIQUE KEY `rol` (`rol`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`idubicacion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `email` (`email`) USING HASH,
  ADD KEY `idrol` (`idrol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `iddocente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `idgenero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `idlibro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `idprestamo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `idubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`);

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `fk_libro_ubicacion` FOREIGN KEY (`idubicacion`) REFERENCES `ubicaciones` (`idubicacion`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`idlibro`) REFERENCES `libros` (`idlibro`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`idrol`) REFERENCES `roles` (`idrol`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
