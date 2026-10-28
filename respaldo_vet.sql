-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla vet_sanmartin.adjuntos_consultas
CREATE TABLE IF NOT EXISTS `adjuntos_consultas` (
  `id_adjunto` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mascota` bigint unsigned NOT NULL,
  `id_consulta` bigint unsigned DEFAULT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  `categoria` enum('radiografia','laboratorio','foto_clinica','documento','otro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'otro',
  `nombre_original` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruta_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_mime` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peso_kb` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_adjunto`),
  KEY `adjuntos_consultas_id_mascota_foreign` (`id_mascota`),
  KEY `adjuntos_consultas_id_consulta_foreign` (`id_consulta`),
  KEY `adjuntos_consultas_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `adjuntos_consultas_id_consulta_foreign` FOREIGN KEY (`id_consulta`) REFERENCES `consultas` (`id_consulta`) ON DELETE CASCADE,
  CONSTRAINT `adjuntos_consultas_id_mascota_foreign` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`) ON DELETE CASCADE,
  CONSTRAINT `adjuntos_consultas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.adjuntos_consultas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla vet_sanmartin.bitacoras
CREATE TABLE IF NOT EXISTS `bitacoras` (
  `id_bitacora` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint unsigned NOT NULL,
  `tabla` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registro_id` bigint unsigned NOT NULL,
  `accion` enum('create','update','delete') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bitacora`),
  KEY `bitacoras_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `bitacoras_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.bitacoras: ~11 rows (aproximadamente)
INSERT INTO `bitacoras` (`id_bitacora`, `id_usuario`, `tabla`, `registro_id`, `accion`, `fecha_hora`) VALUES
	(1, 1, 'propietarios', 1, 'create', '2026-10-15 09:58:03'),
	(2, 1, 'propietarios', 1, 'update', '2026-10-15 09:58:03'),
	(3, 1, 'propietarios', 1, 'update', '2026-10-15 09:58:56'),
	(4, 1, 'propietarios', 2, 'create', '2026-10-15 10:00:10'),
	(5, 1, 'propietarios', 2, 'update', '2026-10-15 10:00:10'),
	(6, 1, 'propietarios', 2, 'update', '2026-10-15 10:00:21'),
	(7, 1, 'usuarios', 2, 'create', '2026-10-15 10:02:21'),
	(8, 1, 'mascotas', 1, 'create', '2026-10-15 10:08:03'),
	(9, 1, 'usuarios', 1, 'update', '2026-10-15 10:10:48'),
	(10, 1, 'vacunas', 1, 'create', '2026-10-15 10:13:54'),
	(11, 1, 'vacunas', 1, 'update', '2026-10-15 10:14:30'),
	(12, 1, 'vacunas', 1, 'update', '2026-10-15 10:15:40'),
	(13, 1, 'consultas', 1, 'create', '2026-10-15 10:51:20'),
	(14, 1, 'usuarios', 1, 'update', '2026-10-20 21:40:00'),
	(15, 2, 'usuarios', 2, 'update', '2026-10-22 12:13:02');

-- Volcando estructura para tabla vet_sanmartin.consultas
CREATE TABLE IF NOT EXISTS `consultas` (
  `id_consulta` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mascota` bigint unsigned NOT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  `id_veterinario` bigint unsigned DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `motivo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enfermedades_anteriores` text COLLATE utf8mb4_unicode_ci,
  `tratamientos_recientes` text COLLATE utf8mb4_unicode_ci,
  `ultima_desparasitacion` date DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `temperatura` decimal(4,1) DEFAULT NULL,
  `fc` int DEFAULT NULL,
  `fr` int DEFAULT NULL,
  `mucosa` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rc_min` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inspeccion` text COLLATE utf8mb4_unicode_ci,
  `palpacion` text COLLATE utf8mb4_unicode_ci,
  `sintomas` text COLLATE utf8mb4_unicode_ci,
  `diagnostico` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `evolucion_pronostico` text COLLATE utf8mb4_unicode_ci,
  `proxima_cita` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_consulta`),
  KEY `consultas_id_usuario_foreign` (`id_usuario`),
  KEY `consultas_id_mascota_index` (`id_mascota`),
  KEY `idx_consultas_fecha` (`fecha_hora`),
  KEY `consultas_id_veterinario_foreign` (`id_veterinario`),
  CONSTRAINT `consultas_id_mascota_foreign` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`),
  CONSTRAINT `consultas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `consultas_id_veterinario_foreign` FOREIGN KEY (`id_veterinario`) REFERENCES `veterinarios` (`id_veterinario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.consultas: ~0 rows (aproximadamente)
INSERT INTO `consultas` (`id_consulta`, `id_mascota`, `id_usuario`, `id_veterinario`, `fecha_hora`, `motivo`, `enfermedades_anteriores`, `tratamientos_recientes`, `ultima_desparasitacion`, `peso`, `temperatura`, `fc`, `fr`, `mucosa`, `rc_min`, `inspeccion`, `palpacion`, `sintomas`, `diagnostico`, `observaciones`, `evolucion_pronostico`, `proxima_cita`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, '2026-10-15 14:48:00', 'Estornudos y lagrimeo desde hace 3 días', NULL, NULL, NULL, 3.20, 39.1, 180, 30, NULL, NULL, NULL, NULL, NULL, 'Cuadro respiratorio superior leve (rinotraqueititis)', NULL, NULL, '2026-10-22', '2026-10-15 18:51:20', '2026-10-15 18:51:20');

-- Volcando estructura para tabla vet_sanmartin.mascotas
CREATE TABLE IF NOT EXISTS `mascotas` (
  `id_mascota` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_propietario` bigint unsigned NOT NULL,
  `nombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `especie` enum('perro','gato','ave','roedor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `raza` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edad` tinyint DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('macho','hembra') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senas_particulares` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_mascota`),
  KEY `mascotas_id_propietario_index` (`id_propietario`),
  KEY `idx_mascotas_nombre` (`nombre`),
  KEY `idx_mascotas_especie` (`especie`),
  CONSTRAINT `mascotas_id_propietario_foreign` FOREIGN KEY (`id_propietario`) REFERENCES `propietarios` (`id_propietario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.mascotas: ~0 rows (aproximadamente)
INSERT INTO `mascotas` (`id_mascota`, `id_propietario`, `nombre`, `especie`, `raza`, `edad`, `fecha_nacimiento`, `sexo`, `color`, `senas_particulares`, `foto`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Silvestre', 'gato', 'Mestizo', 2, '2025-02-15', 'macho', 'Blanco y Negro', 'ninguna', 'uploads/mascotas/mascota_1792073283.jpeg', 'activo', '2026-10-15 18:08:03', '2026-10-15 18:08:03');

-- Volcando estructura para tabla vet_sanmartin.metodos_complementarios
CREATE TABLE IF NOT EXISTS `metodos_complementarios` (
  `id_metodo` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_consulta` bigint unsigned NOT NULL,
  `fecha_orden` date DEFAULT NULL,
  `examen` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resultados` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_metodo`),
  KEY `metodos_complementarios_id_consulta_foreign` (`id_consulta`),
  CONSTRAINT `metodos_complementarios_id_consulta_foreign` FOREIGN KEY (`id_consulta`) REFERENCES `consultas` (`id_consulta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.metodos_complementarios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla vet_sanmartin.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.migrations: ~0 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2026_08_19_001854_create_propietarios_table', 1),
	(2, '2026_08_19_001907_create_mascotas_table', 1),
	(3, '2026_08_19_001917_create_usuarios_table', 1),
	(4, '2026_08_19_001941_create_consultas_table', 1),
	(5, '2026_08_19_001951_create_tratamientos_table', 1),
	(6, '2026_08_19_001959_create_metodos_complementarios_table', 1),
	(7, '2026_08_19_002009_create_vacunas_table', 1),
	(8, '2026_08_19_002018_create_recetas_table', 1),
	(9, '2026_08_19_002026_create_bitacoras_table', 1),
	(10, '2026_10_09_000001_agregar_correcciones_del_tutor', 1);

-- Volcando estructura para tabla vet_sanmartin.propietarios
CREATE TABLE IF NOT EXISTS `propietarios` (
  `id_propietario` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zona` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calle_av` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_registro` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_propietario`),
  KEY `idx_propietarios_nombre` (`nombre_completo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.propietarios: ~2 rows (aproximadamente)
INSERT INTO `propietarios` (`id_propietario`, `nombre_completo`, `telefono`, `direccion`, `zona`, `calle_av`, `numero`, `nro_registro`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'Luz Miriam Calle Pizza', '75873546', 'Zon San Martin, carretera a viacha', 'San Martin', 'Calle Alcides Arguedas', '12', '0001', 'activo', '2026-10-15 17:58:03', '2026-10-15 17:58:56'),
	(2, 'Sulinda Rossiel Capaquira Apaza', '65478446', 'Garaje Rojo', 'Villa Mercedes "D"', 'Av. Nestor Galindo', '456', '0002', 'activo', '2026-10-15 18:00:10', '2026-10-15 18:00:21');

-- Volcando estructura para tabla vet_sanmartin.recetas
CREATE TABLE IF NOT EXISTS `recetas` (
  `id_receta` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_consulta` bigint unsigned NOT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_receta`),
  UNIQUE KEY `recetas_id_consulta_unique` (`id_consulta`),
  CONSTRAINT `recetas_id_consulta_foreign` FOREIGN KEY (`id_consulta`) REFERENCES `consultas` (`id_consulta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.recetas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla vet_sanmartin.tratamientos
CREATE TABLE IF NOT EXISTS `tratamientos` (
  `id_tratamiento` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_consulta` bigint unsigned NOT NULL,
  `fecha` date DEFAULT NULL,
  `medicamento` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosis` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duracion` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_tratamiento`),
  KEY `tratamientos_id_consulta_foreign` (`id_consulta`),
  CONSTRAINT `tratamientos_id_consulta_foreign` FOREIGN KEY (`id_consulta`) REFERENCES `consultas` (`id_consulta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.tratamientos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla vet_sanmartin.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrasena_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('admin','cliente') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_propietario` bigint unsigned DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuarios_nombre_usuario_unique` (`nombre_usuario`),
  UNIQUE KEY `usuarios_id_propietario_unique` (`id_propietario`),
  CONSTRAINT `usuarios_id_propietario_foreign` FOREIGN KEY (`id_propietario`) REFERENCES `propietarios` (`id_propietario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.usuarios: ~2 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `contrasena_hash`, `rol`, `id_propietario`, `estado`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', '$2y$12$eLGd3V1gJdgSruBXDbFRWu1ZbZpa.KPHwQlz0kiQQaMxyftav6abe', 'admin', NULL, 'activo', NULL, '2026-10-15 07:41:33', '2026-10-21 05:40:00'),
	(2, 'Miriam Calle', '$2y$12$bbJzEB3mIm1bO268bWp4GuUNZTrFNiVVruxTyuDHcB3dcYtOiKa6S', 'cliente', 1, 'activo', NULL, '2026-10-15 18:02:21', '2026-10-22 20:13:02');

-- Volcando estructura para tabla vet_sanmartin.vacunas
CREATE TABLE IF NOT EXISTS `vacunas` (
  `id_vacuna` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mascota` bigint unsigned NOT NULL,
  `tipo_vacuna` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lote` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_aplicacion` date NOT NULL,
  `fecha_proxima_dosis` date DEFAULT NULL,
  `estado_proxima` enum('pendiente','aplicada','omitida') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `observacion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_vacuna`),
  KEY `vacunas_id_mascota_index` (`id_mascota`),
  KEY `idx_vacunas_proxima` (`fecha_proxima_dosis`),
  CONSTRAINT `vacunas_id_mascota_foreign` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.vacunas: ~1 rows (aproximadamente)
INSERT INTO `vacunas` (`id_vacuna`, `id_mascota`, `tipo_vacuna`, `lote`, `fecha_aplicacion`, `fecha_proxima_dosis`, `estado_proxima`, `observacion`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Triple Felina', 'TF-2026-042', '2026-09-10', '2026-10-16', 'pendiente', NULL, '2026-10-15 18:13:54', '2026-10-15 18:15:40');

-- Volcando estructura para tabla vet_sanmartin.veterinarios
CREATE TABLE IF NOT EXISTS `veterinarios` (
  `id_veterinario` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint unsigned NOT NULL,
  `nombre_completo` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro_colegiado` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `especialidad` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_veterinario`),
  UNIQUE KEY `veterinarios_id_usuario_unique` (`id_usuario`),
  CONSTRAINT `veterinarios_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla vet_sanmartin.veterinarios: ~0 rows (aproximadamente)
INSERT INTO `veterinarios` (`id_veterinario`, `id_usuario`, `nombre_completo`, `nro_colegiado`, `especialidad`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Ismael Sacama Ramírez', '762', 'Clínica de animales menores y mayores', 'activo', '2026-10-15 07:41:33', '2026-10-15 07:41:33');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
