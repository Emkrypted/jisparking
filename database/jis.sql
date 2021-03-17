-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-09-2020 a las 19:48:04
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jis`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banks`
--

CREATE TABLE `banks` (
  `bank_id` bigint(20) UNSIGNED NOT NULL,
  `bank` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `banks`
--

INSERT INTO `banks` (`bank_id`, `bank`, `created_at`, `updated_at`) VALUES
(1, 'Banco De Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(2, 'Banco Internacional', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(3, 'Scotiabank Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(4, 'Banco De Credito e Inversiones', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(5, 'Corpbanca', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(6, 'Banco Bice', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(7, 'Hsbc Bank (Chile)', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(8, 'Banco Santander-Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(9, 'Banco Internacional', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(10, 'Banco Security', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(11, 'Dresdner Banque Nationale de Paris', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(12, 'Banco del Estado de Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(13, 'Banco Ripley', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(14, 'Scotiabank Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(15, 'Banco Consorcio', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(16, 'Banco Crédito e Inversiones', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(17, 'Banco Do Brasil S.A.', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(18, 'Banco Bilbao Vizcaya Argentaria, Chile (BBVA)', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(19, 'Banco Del Estado De Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(27, 'Corpbanca', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(28, 'Banco Bice', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(29, 'Banco de A.Edwards', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(31, 'HSBC Bank (Chile)', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(32, 'Bank of America', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(33, 'Citibank N.A.', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(34, 'Banco Real S.A.', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(35, 'Banco Santiago', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(36, 'Banco do Estado de Sao Paulo', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(37, 'Banco Santander ? Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(38, 'Banco Exterior', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(39, 'Banco Itaú Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(40, 'Banco Sudameris', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(41, 'The Chase Manhattan Bank N.A.', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(42, 'American Express Bank', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(43, 'Banco de la Nación Argentina', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(45, 'The Bank Of Tokyo ? Mitsubishi', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(46, 'ABN AMRO Bank', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(49, 'Banco Security', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(51, 'Banco Falabella', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(52, 'Deutsche Bank (Chile)', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(53, 'Banco Ripley', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(54, 'Rabobank Chile (ex HNS Banco)', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(55, 'Banco Consorcio (ex Banco Monex)', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(56, 'Banco Penta', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(57, 'Banco Paris', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(60, 'China Construction Bank, Agencia en Chile', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(504, 'Scotiabank Azul (Ex BBVA)', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(507, 'Banco del Desarrollo', '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(672, 'Coopeuch', '2000-01-01 03:00:00', '2000-01-01 03:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branch_offices`
--

CREATE TABLE `branch_offices` (
  `branch_office_id` bigint(20) UNSIGNED NOT NULL,
  `branch_office` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dte_code` int(11) NOT NULL,
  `watch_status` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `branch_offices`
--

INSERT INTO `branch_offices` (`branch_office_id`, `branch_office`, `dte_code`, `watch_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 'EL PAMPINO', 75671692, 1, 1, '2000-01-01 03:00:00', '2020-07-24 02:40:28'),
(2, 'PARQUE', 75671718, 1, 1, '2000-01-01 03:00:00', '2019-08-13 22:50:59'),
(3, 'BALMACEDA', 78315452, 2, 1, '2000-01-01 03:00:00', '2020-07-24 02:44:42'),
(4, 'COPIAPO', 77155146, 1, 1, '2000-01-01 03:00:00', '2019-08-13 19:15:18'),
(5, 'LA SERENA', 75983120, 1, 1, '2000-01-01 03:00:00', '2019-08-13 19:15:59'),
(6, 'OVALLE', 77101360, 1, 1, '2000-01-01 03:00:00', '2019-05-23 23:59:12'),
(7, 'QUILLOTA', 75576725, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(8, 'REÑACA', 76293940, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(9, 'STA ISABEL CARRERAS', 79598612, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(10, 'STA ISABEL VALPARAISO', 79056747, 1, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(11, 'STA ISABEL VICUÑA', 79056742, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(13, 'VALPARAISO', 81058640, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(14, 'MALL PASEO DEL VALLE', 78803833, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(15, 'UNIMARC VIÑA', 79191436, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(16, 'ALVI MAIPU', 77588295, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(17, 'FCO. BILBAO', 77323551, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(18, 'HITES PUENTE', 78365300, 1, 1, '2000-01-01 03:00:00', '2020-07-26 22:24:23'),
(19, 'LOS MILITARES', 76062890, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(20, 'MEDS MAIPU', 79155284, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(21, 'ÑUBLE', 78262142, 1, 1, '2000-01-01 03:00:00', '2019-05-24 00:13:04'),
(22, 'TOTTUS NATANIEL', 78468986, 1, 1, '2000-01-01 03:00:00', '2019-09-05 17:52:35'),
(23, 'TOTTUS SAN BERNARDO', 78284698, 1, 1, '2000-01-01 03:00:00', '2019-09-05 17:55:22'),
(24, 'TOTTUS VICUÑA MACK', 77603263, 1, 1, '2000-01-01 03:00:00', '2019-09-05 17:55:50'),
(25, 'VICENTE VALDES', 76841297, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(26, 'CHILLAN', 76229324, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(27, 'OSORNO P. LYNCH', 76727872, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(29, 'U. CONCEPCION', 79166688, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(30, 'UNIMARC BORIES', 79038406, 0, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(31, 'UNIMARC LAUTARO', 79038389, 0, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(32, 'UNIMARC LINARES', 79110413, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(33, 'EL MUSEO', 76160964, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(34, 'MALL CENTRO', 75455125, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(35, 'MALL MELIPILLA', 75645667, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(36, 'MALL QUILIN', 79576893, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(37, 'MALL RANCAGUA\r\n', 0, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(38, 'MALL SAN BERNARDO', 79086925, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(39, 'MALL SAN FERNANDO', 79884281, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(40, 'TOTTUS QUILLOTA', 0, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(41, 'TOTTUS CATEDRAL', 79623916, 0, 1, '2000-01-01 03:00:00', '2019-09-05 17:56:40'),
(42, 'UNIV AUTONOMA', 79248293, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(43, 'M10 VILLA ALEMANA', 79636862, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(44, 'SODIMAC IQUIQUE', 0, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(45, 'STRIP RECOLETA', 79842393, 1, 1, '2000-01-01 03:00:00', '2020-03-10 17:17:53'),
(46, 'CLINICA AUTONOMA', 79861076, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(48, 'TOTTUS VIVACETA', 79709715, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(49, 'TOTTUS RENGO', 79811950, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(50, 'TOTTUS TALAGANTE', 79854553, 1, 1, '2000-01-01 03:00:00', '2019-09-05 17:58:10'),
(51, 'SODIMAC LA FLORIDA', 0, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(52, 'HITES TALCA', 80105248, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(53, 'COPIAPO HENRIQUEZ', 80073663, 0, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(54, 'STA ISABEL VILLA ALEMANA', 80150681, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(55, 'TOTTUS SAN BERNARDO ESTACION', 79924643, 1, 1, '2000-01-01 03:00:00', '2019-09-05 17:59:54'),
(56, 'STRIP IRARRAZAVAL', 79968310, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(57, 'STA ISABEL HUERFANOS', 80233999, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(58, 'TOTTUS SAN FERNARDO', 81191665, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(60, 'PAJARITOS 3030', 80342320, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(61, 'STRIP REÑACA', 80620543, 0, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(62, 'UNIMARC VICUÑA', 80620500, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(65, 'LIDER OSORNO', 80686477, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(68, 'TOTTUS LA CISTERNA', 81043328, 0, 0, '2000-01-01 03:00:00', '2019-09-05 18:02:05'),
(69, 'LIDER PUERTO MONTT', 81043286, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(70, 'LIDER PUERTO VARAS', 82765411, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(71, 'MORANDE 801', 83433782, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(72, 'MALL COQUIMBO', 81155976, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(74, 'STA ISABEL LOS ANGELES', 81382262, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(75, 'SITIO ESPAÑA', 81382932, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(76, 'LIDER TALAGANTE', 82320944, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(78, 'EDIFICIO BANCOESTADO', 81506275, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(79, 'LIDER VALDIVIA', 82765444, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(80, 'OFICINA', 0, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(81, 'STA ISABEL VIÑA', 82670901, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(83, 'STA ISABEL CONCEPCION', 82802510, 0, 0, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(84, 'STA ISABEL VICUÑA MACK', 82795927, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(85, 'LIDER ARTIGAS', 82919693, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(86, 'UNIMARC TANGUE', 83640499, 2, 1, '2000-01-01 03:00:00', '2020-08-02 21:57:11'),
(87, 'Almacen', 0, 1, 1, '2000-01-01 03:00:00', NULL),
(88, 'CASABLANCA', 83873758, 1, 1, '2000-01-01 03:00:00', '2020-03-09 19:06:45'),
(89, 'LIDER GRAN AVENIDA', 83930365, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(90, 'LIDER RANCAGUA', 83934463, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00'),
(91, 'Venta de Insumos', 0, 1, 1, '2000-01-01 03:00:00', '2000-01-01 03:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `civil_states`
--

CREATE TABLE `civil_states` (
  `civil_state_id` bigint(20) UNSIGNED NOT NULL,
  `civil_state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `civil_states`
--

INSERT INTO `civil_states` (`civil_state_id`, `civil_state`, `created_at`, `updated_at`) VALUES
(1, 'Soltero (a)', NULL, NULL),
(2, 'Casado (a)', NULL, NULL),
(3, 'Separado (a)', NULL, NULL),
(4, 'Viudo (a)', NULL, NULL),
(5, 'Divorciado (a)', NULL, NULL),
(6, 'N/A', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `rut` int(10) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `commune_id` bigint(20) UNSIGNED NOT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coins`
--

CREATE TABLE `coins` (
  `coin_id` bigint(20) UNSIGNED NOT NULL,
  `coin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `communes`
--

CREATE TABLE `communes` (
  `commune_id` bigint(20) UNSIGNED NOT NULL,
  `commune` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `communes`
--

INSERT INTO `communes` (`commune_id`, `commune`, `region_id`, `created_at`, `updated_at`) VALUES
(1, 'Arica', 15, NULL, NULL),
(2, 'Camarones', 15, NULL, NULL),
(3, 'General Lagos', 15, NULL, NULL),
(4, 'Putre', 15, NULL, NULL),
(5, 'Alto Hospicio', 1, NULL, NULL),
(6, 'Iquique', 1, NULL, NULL),
(7, 'Camiña', 1, NULL, NULL),
(8, 'Colchane', 1, NULL, NULL),
(9, 'Huara', 1, NULL, NULL),
(10, 'Pica', 1, NULL, NULL),
(11, 'Pozo Almonte', 1, NULL, NULL),
(12, 'Antofagasta', 2, NULL, NULL),
(13, 'Mejillones', 2, NULL, NULL),
(14, 'Sierra Gorda', 2, NULL, NULL),
(15, 'Taltal', 2, NULL, NULL),
(16, 'Calama', 2, NULL, NULL),
(17, 'Ollague', 2, NULL, NULL),
(18, 'San Pedro de Atacama', 2, NULL, NULL),
(19, 'María Elena', 2, NULL, NULL),
(20, 'Tocopilla', 2, NULL, NULL),
(21, 'Chañaral', 3, NULL, NULL),
(22, 'Diego de Almagro', 3, NULL, NULL),
(23, 'Caldera', 3, NULL, NULL),
(24, 'Copiapó', 3, NULL, NULL),
(25, 'Tierra Amarilla', 3, NULL, NULL),
(26, 'Alto del Carmen', 3, NULL, NULL),
(27, 'Freirina', 3, NULL, NULL),
(28, 'Huasco', 3, NULL, NULL),
(29, 'Vallenar', 3, NULL, NULL),
(30, 'Canela', 4, NULL, NULL),
(31, 'Illapel', 4, NULL, NULL),
(32, 'Los Vilos', 4, NULL, NULL),
(33, 'Salamanca', 4, NULL, NULL),
(34, 'Andacollo', 4, NULL, NULL),
(35, 'Coquimbo', 4, NULL, NULL),
(36, 'La Higuera', 4, NULL, NULL),
(37, 'La Serena', 4, NULL, NULL),
(38, 'Paihuaco', 4, NULL, NULL),
(39, 'Vicuña', 4, NULL, NULL),
(40, 'Combarbalá', 4, NULL, NULL),
(41, 'Monte Patria', 4, NULL, NULL),
(42, 'Ovalle', 4, NULL, NULL),
(43, 'Punitaqui', 4, NULL, NULL),
(44, 'Río Hurtado', 4, NULL, NULL),
(45, 'Isla de Pascua', 5, NULL, NULL),
(46, 'Calle Larga', 5, NULL, NULL),
(47, 'Los Andes', 5, NULL, NULL),
(48, 'Rinconada', 5, NULL, NULL),
(49, 'San Esteban', 5, NULL, NULL),
(50, 'La Ligua', 5, NULL, NULL),
(51, 'Papudo', 5, NULL, NULL),
(52, 'Petorca', 5, NULL, NULL),
(53, 'Zapallar', 5, NULL, NULL),
(54, 'Hijuelas', 5, NULL, NULL),
(55, 'La Calera', 5, NULL, NULL),
(56, 'La Cruz', 5, NULL, NULL),
(57, 'Limache', 5, NULL, NULL),
(58, 'Nogales', 5, NULL, NULL),
(59, 'Olmué', 5, NULL, NULL),
(60, 'Quillota', 5, NULL, NULL),
(61, 'Algarrobo', 5, NULL, NULL),
(62, 'Cartagena', 5, NULL, NULL),
(63, 'El Quisco', 5, NULL, NULL),
(64, 'El Tabo', 5, NULL, NULL),
(65, 'San Antonio', 5, NULL, NULL),
(66, 'Santo Domingo', 5, NULL, NULL),
(67, 'Catemu', 5, NULL, NULL),
(68, 'Llaillay', 5, NULL, NULL),
(69, 'Panquehue', 5, NULL, NULL),
(70, 'Putaendo', 5, NULL, NULL),
(71, 'San Felipe', 5, NULL, NULL),
(72, 'Santa María', 5, NULL, NULL),
(73, 'Casablanca', 5, NULL, NULL),
(74, 'Concón', 5, NULL, NULL),
(75, 'Juan Fernández', 5, NULL, NULL),
(76, 'Puchuncaví', 5, NULL, NULL),
(77, 'Quilpué', 5, NULL, NULL),
(78, 'Quintero', 5, NULL, NULL),
(79, 'Valparaíso', 5, NULL, NULL),
(80, 'Villa Alemana', 5, NULL, NULL),
(81, 'Viña del Mar', 5, NULL, NULL),
(82, 'Colina', 0, NULL, NULL),
(83, 'Lampa', 0, NULL, NULL),
(84, 'Tiltil', 0, NULL, NULL),
(85, 'Pirque', 0, NULL, NULL),
(86, 'Puente Alto', 0, NULL, NULL),
(87, 'San José de Maipo', 0, NULL, NULL),
(88, 'Buin', 0, NULL, NULL),
(89, 'Calera de Tango', 0, NULL, NULL),
(90, 'Paine', 0, NULL, NULL),
(91, 'San Bernardo', 0, NULL, NULL),
(92, 'Alhué', 0, NULL, NULL),
(93, 'Curacaví', 0, NULL, NULL),
(94, 'María Pinto', 0, NULL, NULL),
(95, 'Melipilla', 0, NULL, NULL),
(96, 'San Pedro', 0, NULL, NULL),
(97, 'Cerrillos', 0, NULL, NULL),
(98, 'Cerro Navia', 0, NULL, NULL),
(99, 'Conchalí', 0, NULL, NULL),
(100, 'El Bosque', 0, NULL, NULL),
(101, 'Estación Central', 0, NULL, NULL),
(102, 'Huechuraba', 0, NULL, NULL),
(103, 'Independencia', 0, NULL, NULL),
(104, 'La Cisterna', 0, NULL, NULL),
(105, 'La Granja', 0, NULL, NULL),
(106, 'La Florida', 0, NULL, NULL),
(107, 'La Pintana', 0, NULL, NULL),
(108, 'La Reina', 0, NULL, NULL),
(109, 'Las Condes', 0, NULL, NULL),
(110, 'Lo Barnechea', 0, NULL, NULL),
(111, 'Lo Espejo', 0, NULL, NULL),
(112, 'Lo Prado', 0, NULL, NULL),
(113, 'Macul', 0, NULL, NULL),
(114, 'Maipú', 0, NULL, NULL),
(115, 'Ñuñoa', 0, NULL, NULL),
(116, 'Pedro Aguirre Cerda', 0, NULL, NULL),
(117, 'Peñalolén', 0, NULL, NULL),
(118, 'Providencia', 0, NULL, NULL),
(119, 'Pudahuel', 0, NULL, NULL),
(120, 'Quilicura', 0, NULL, NULL),
(121, 'Quinta Normal', 0, NULL, NULL),
(122, 'Recoleta', 0, NULL, NULL),
(123, 'Renca', 0, NULL, NULL),
(124, 'San Miguel', 0, NULL, NULL),
(125, 'San Joaquín', 0, NULL, NULL),
(126, 'San Ramón', 0, NULL, NULL),
(127, 'Santiago', 0, NULL, NULL),
(128, 'Vitacura', 0, NULL, NULL),
(129, 'El Monte', 0, NULL, NULL),
(130, 'Isla de Maipo', 0, NULL, NULL),
(131, 'Padre Hurtado', 0, NULL, NULL),
(132, 'Peñaflor', 0, NULL, NULL),
(133, 'Talagante', 0, NULL, NULL),
(134, 'Codegua', 6, NULL, NULL),
(135, 'Coínco', 6, NULL, NULL),
(136, 'Coltauco', 6, NULL, NULL),
(137, 'Doñihue', 6, NULL, NULL),
(138, 'Graneros', 6, NULL, NULL),
(139, 'Las Cabras', 6, NULL, NULL),
(140, 'Machalí', 6, NULL, NULL),
(141, 'Malloa', 6, NULL, NULL),
(142, 'Mostazal', 6, NULL, NULL),
(143, 'Olivar', 6, NULL, NULL),
(144, 'Peumo', 6, NULL, NULL),
(145, 'Pichidegua', 6, NULL, NULL),
(146, 'Quinta de Tilcoco', 6, NULL, NULL),
(147, 'Rancagua', 6, NULL, NULL),
(148, 'Rengo', 6, NULL, NULL),
(149, 'Requínoa', 6, NULL, NULL),
(150, 'San Vicente de Tagua Tagua', 6, NULL, NULL),
(151, 'La Estrella', 6, NULL, NULL),
(152, 'Litueche', 6, NULL, NULL),
(153, 'Marchihue', 6, NULL, NULL),
(154, 'Navidad', 6, NULL, NULL),
(155, 'Peredones', 6, NULL, NULL),
(156, 'Pichilemu', 6, NULL, NULL),
(157, 'Chépica', 6, NULL, NULL),
(158, 'Chimbarongo', 6, NULL, NULL),
(159, 'Lolol', 6, NULL, NULL),
(160, 'Nancagua', 6, NULL, NULL),
(161, 'Palmilla', 6, NULL, NULL),
(162, 'Peralillo', 6, NULL, NULL),
(163, 'Placilla', 6, NULL, NULL),
(164, 'Pumanque', 6, NULL, NULL),
(165, 'San Fernando', 6, NULL, NULL),
(166, 'Santa Cruz', 6, NULL, NULL),
(167, 'Cauquenes', 7, NULL, NULL),
(168, 'Chanco', 7, NULL, NULL),
(169, 'Pelluhue', 7, NULL, NULL),
(170, 'Curicó', 7, NULL, NULL),
(171, 'Hualañé', 7, NULL, NULL),
(172, 'Licantén', 7, NULL, NULL),
(173, 'Molina', 7, NULL, NULL),
(174, 'Rauco', 7, NULL, NULL),
(175, 'Romeral', 7, NULL, NULL),
(176, 'Sagrada Familia', 7, NULL, NULL),
(177, 'Teno', 7, NULL, NULL),
(178, 'Vichuquén', 7, NULL, NULL),
(179, 'Colbún', 7, NULL, NULL),
(180, 'Linares', 7, NULL, NULL),
(181, 'Longaví', 7, NULL, NULL),
(182, 'Parral', 7, NULL, NULL),
(183, 'Retiro', 7, NULL, NULL),
(184, 'San Javier', 7, NULL, NULL),
(185, 'Villa Alegre', 7, NULL, NULL),
(186, 'Yerbas Buenas', 7, NULL, NULL),
(187, 'Constitución', 7, NULL, NULL),
(188, 'Curepto', 7, NULL, NULL),
(189, 'Empedrado', 7, NULL, NULL),
(190, 'Maule', 7, NULL, NULL),
(191, 'Pelarco', 7, NULL, NULL),
(192, 'Pencahue', 7, NULL, NULL),
(193, 'Río Claro', 7, NULL, NULL),
(194, 'San Clemente', 7, NULL, NULL),
(195, 'San Rafael', 7, NULL, NULL),
(196, 'Talca', 7, NULL, NULL),
(197, 'Arauco', 8, NULL, NULL),
(198, 'Cañete', 8, NULL, NULL),
(199, 'Contulmo', 8, NULL, NULL),
(200, 'Curanilahue', 8, NULL, NULL),
(201, 'Lebu', 8, NULL, NULL),
(202, 'Los Álamos', 8, NULL, NULL),
(203, 'Tirúa', 8, NULL, NULL),
(204, 'Alto Biobío', 8, NULL, NULL),
(205, 'Antuco', 8, NULL, NULL),
(206, 'Cabrero', 8, NULL, NULL),
(207, 'Laja', 8, NULL, NULL),
(208, 'Los Ángeles', 8, NULL, NULL),
(209, 'Mulchén', 8, NULL, NULL),
(210, 'Nacimiento', 8, NULL, NULL),
(211, 'Negrete', 8, NULL, NULL),
(212, 'Quilaco', 8, NULL, NULL),
(213, 'Quilleco', 8, NULL, NULL),
(214, 'San Rosendo', 8, NULL, NULL),
(215, 'Santa Bárbara', 8, NULL, NULL),
(216, 'Tucapel', 8, NULL, NULL),
(217, 'Yumbel', 8, NULL, NULL),
(218, 'Chiguayante', 8, NULL, NULL),
(219, 'Concepción', 8, NULL, NULL),
(220, 'Coronel', 8, NULL, NULL),
(221, 'Florida', 8, NULL, NULL),
(222, 'Hualpén', 8, NULL, NULL),
(223, 'Hualqui', 8, NULL, NULL),
(224, 'Lota', 8, NULL, NULL),
(225, 'Penco', 8, NULL, NULL),
(226, 'San Pedro de La Paz', 8, NULL, NULL),
(227, 'Santa Juana', 8, NULL, NULL),
(228, 'Talcahuano', 8, NULL, NULL),
(229, 'Tomé', 8, NULL, NULL),
(230, 'Bulnes', 8, NULL, NULL),
(231, 'Chillán', 8, NULL, NULL),
(232, 'Chillán Viejo', 8, NULL, NULL),
(233, 'Cobquecura', 8, NULL, NULL),
(234, 'Coelemu', 8, NULL, NULL),
(235, 'Coihueco', 8, NULL, NULL),
(236, 'El Carmen', 8, NULL, NULL),
(237, 'Ninhue', 8, NULL, NULL),
(238, 'Ñiquen', 8, NULL, NULL),
(239, 'Pemuco', 8, NULL, NULL),
(240, 'Pinto', 8, NULL, NULL),
(241, 'Portezuelo', 8, NULL, NULL),
(242, 'Quillón', 8, NULL, NULL),
(243, 'Quirihue', 8, NULL, NULL),
(244, 'Ránquil', 8, NULL, NULL),
(245, 'San Carlos', 8, NULL, NULL),
(246, 'San Fabián', 8, NULL, NULL),
(247, 'San Ignacio', 8, NULL, NULL),
(248, 'San Nicolás', 8, NULL, NULL),
(249, 'Treguaco', 8, NULL, NULL),
(250, 'Yungay', 8, NULL, NULL),
(251, 'Carahue', 9, NULL, NULL),
(252, 'Cholchol', 9, NULL, NULL),
(253, 'Cunco', 9, NULL, NULL),
(254, 'Curarrehue', 9, NULL, NULL),
(255, 'Freire', 9, NULL, NULL),
(256, 'Galvarino', 9, NULL, NULL),
(257, 'Gorbea', 9, NULL, NULL),
(258, 'Lautaro', 9, NULL, NULL),
(259, 'Loncoche', 9, NULL, NULL),
(260, 'Melipeuco', 9, NULL, NULL),
(261, 'Nueva Imperial', 9, NULL, NULL),
(262, 'Padre Las Casas', 9, NULL, NULL),
(263, 'Perquenco', 9, NULL, NULL),
(264, 'Pitrufquén', 9, NULL, NULL),
(265, 'Pucón', 9, NULL, NULL),
(266, 'Saavedra', 9, NULL, NULL),
(267, 'Temuco', 9, NULL, NULL),
(268, 'Teodoro Schmidt', 9, NULL, NULL),
(269, 'Toltén', 9, NULL, NULL),
(270, 'Vilcún', 9, NULL, NULL),
(271, 'Villarrica', 9, NULL, NULL),
(272, 'Angol', 9, NULL, NULL),
(273, 'Collipulli', 9, NULL, NULL),
(274, 'Curacautín', 9, NULL, NULL),
(275, 'Ercilla', 9, NULL, NULL),
(276, 'Lonquimay', 9, NULL, NULL),
(277, 'Los Sauces', 9, NULL, NULL),
(278, 'Lumaco', 9, NULL, NULL),
(279, 'Purén', 9, NULL, NULL),
(280, 'Renaico', 9, NULL, NULL),
(281, 'Traiguén', 9, NULL, NULL),
(282, 'Victoria', 9, NULL, NULL),
(283, 'Corral', 14, NULL, NULL),
(284, 'Lanco', 14, NULL, NULL),
(285, 'Los Lagos', 14, NULL, NULL),
(286, 'Máfil', 14, NULL, NULL),
(287, 'Mariquina', 14, NULL, NULL),
(288, 'Paillaco', 14, NULL, NULL),
(289, 'Panguipulli', 14, NULL, NULL),
(290, 'Valdivia', 14, NULL, NULL),
(291, 'Futrono', 14, NULL, NULL),
(292, 'La Unión', 14, NULL, NULL),
(293, 'Lago Ranco', 14, NULL, NULL),
(294, 'Río Bueno', 14, NULL, NULL),
(295, 'Ancud', 10, NULL, NULL),
(296, 'Castro', 10, NULL, NULL),
(297, 'Chonchi', 10, NULL, NULL),
(298, 'Curaco de Vélez', 10, NULL, NULL),
(299, 'Dalcahue', 10, NULL, NULL),
(300, 'Puqueldón', 10, NULL, NULL),
(301, 'Queilén', 10, NULL, NULL),
(302, 'Quemchi', 10, NULL, NULL),
(303, 'Quellón', 10, NULL, NULL),
(304, 'Quinchao', 10, NULL, NULL),
(305, 'Calbuco', 10, NULL, NULL),
(306, 'Cochamó', 10, NULL, NULL),
(307, 'Fresia', 10, NULL, NULL),
(308, 'Frutillar', 10, NULL, NULL),
(309, 'Llanquihue', 10, NULL, NULL),
(310, 'Los Muermos', 10, NULL, NULL),
(311, 'Maullín', 10, NULL, NULL),
(312, 'Puerto Montt', 10, NULL, NULL),
(313, 'Puerto Varas', 10, NULL, NULL),
(314, 'Osorno', 10, NULL, NULL),
(315, 'Puero Octay', 10, NULL, NULL),
(316, 'Purranque', 10, NULL, NULL),
(317, 'Puyehue', 10, NULL, NULL),
(318, 'Río Negro', 10, NULL, NULL),
(319, 'San Juan de la Costa', 10, NULL, NULL),
(320, 'San Pablo', 10, NULL, NULL),
(321, 'Chaitén', 10, NULL, NULL),
(322, 'Futaleufú', 10, NULL, NULL),
(323, 'Hualaihué', 10, NULL, NULL),
(324, 'Palena', 10, NULL, NULL),
(325, 'Aisén', 11, NULL, NULL),
(326, 'Cisnes', 11, NULL, NULL),
(327, 'Guaitecas', 11, NULL, NULL),
(328, 'Cochrane', 11, NULL, NULL),
(329, 'O\'higgins', 11, NULL, NULL),
(330, 'Tortel', 11, NULL, NULL),
(331, 'Coihaique', 11, NULL, NULL),
(332, 'Lago Verde', 11, NULL, NULL),
(333, 'Chile Chico', 11, NULL, NULL),
(334, 'Río Ibáñez', 11, NULL, NULL),
(335, 'Antártica', 12, NULL, NULL),
(336, 'Cabo de Hornos', 12, NULL, NULL),
(337, 'Laguna Blanca', 12, NULL, NULL),
(338, 'Punta Arenas', 12, NULL, NULL),
(339, 'Río Verde', 12, NULL, NULL),
(340, 'San Gregorio', 12, NULL, NULL),
(341, 'Porvenir', 12, NULL, NULL),
(342, 'Primavera', 12, NULL, NULL),
(343, 'Timaukel', 12, NULL, NULL),
(344, 'Natales', 12, NULL, NULL),
(345, 'Torres del Paine', 12, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contract_types`
--

CREATE TABLE `contract_types` (
  `contract_type_id` bigint(20) UNSIGNED NOT NULL,
  `contract_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contract_types`
--

INSERT INTO `contract_types` (`contract_type_id`, `contract_type`, `created_at`, `updated_at`) VALUES
(1, 'Indefinido', NULL, NULL),
(2, 'Part - Time 10 hrs', NULL, NULL),
(3, 'Part - Time 20 hrs', NULL, NULL),
(4, 'Part - Time 25 hrs', NULL, NULL),
(5, 'Part - Time 30 hrs', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document_types`
--

CREATE TABLE `document_types` (
  `document_type_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `document_types`
--

INSERT INTO `document_types` (`document_type_id`, `document_type`, `created_at`, `updated_at`) VALUES
(1, 'Carta de Amonestación', NULL, NULL),
(4, 'Certificado de Antigüedad', NULL, NULL),
(5, 'Liquidaciones de Sueldo', NULL, NULL),
(6, 'Papeleta de Vacaciones', NULL, NULL),
(7, 'Falta de Atraso', NULL, NULL),
(8, 'Abandono de Trabajo', NULL, NULL),
(9, 'Falta Injustificada', NULL, NULL),
(10, 'Permiso', NULL, NULL),
(11, 'Cambio de Sucursal', NULL, NULL),
(12, 'Actualización de Contrato', NULL, NULL),
(13, 'Anexo de Puntualidad', NULL, NULL),
(14, 'Carta de Compromiso', NULL, NULL),
(15, 'Carta de Felicitación', NULL, NULL),
(16, 'Carta de Término (Necesidades de la Empresa)', NULL, NULL),
(17, 'Carta de Término (No Concurrencia)', NULL, NULL),
(18, 'Carta de Término (Vencimiento de Plazo)', NULL, NULL),
(19, 'RIOHS', NULL, NULL),
(20, 'ODI', NULL, NULL),
(21, 'Contrato', NULL, NULL),
(22, 'Finiquito', NULL, NULL),
(23, 'Horario Laboral', NULL, NULL),
(24, 'Anexos Protección al Empleo', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dtes`
--

CREATE TABLE `dtes` (
  `dtes_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employees`
--

CREATE TABLE `employees` (
  `rut` int(10) UNSIGNED NOT NULL,
  `gender_id` bigint(20) UNSIGNED NOT NULL,
  `nationality_id` bigint(20) UNSIGNED NOT NULL,
  `civil_state_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `providence_id` bigint(20) UNSIGNED NOT NULL,
  `commune_id` bigint(20) UNSIGNED NOT NULL,
  `health_id` bigint(20) UNSIGNED NOT NULL,
  `pention_id` bigint(20) UNSIGNED NOT NULL,
  `contract_type_id` bigint(20) UNSIGNED NOT NULL,
  `branch_office_id` bigint(20) UNSIGNED NOT NULL,
  `father_lastname` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_lastname` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cellphone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `born_date` date NOT NULL,
  `entrance_health` date NOT NULL,
  `entrance_pention` date NOT NULL,
  `entrance_company` date NOT NULL,
  `exit_company` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE `events` (
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `event_type_id` bigint(20) UNSIGNED NOT NULL,
  `title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event_types`
--

CREATE TABLE `event_types` (
  `event_type_id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expense_types`
--

CREATE TABLE `expense_types` (
  `id_expense_type` bigint(20) NOT NULL,
  `expense_type` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `accounting_account` bigint(20) DEFAULT NULL,
  `group_detail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `expense_types`
--

INSERT INTO `expense_types` (`id_expense_type`, `expense_type`, `type`, `accounting_account`, `group_detail`) VALUES
(3, 'Materiales Mantención Primaria', 1, 443000324, 'MANTENCION'),
(4, 'Útiles y Artículos de Oficina', 1, 443000306, 'MATERIALES'),
(5, 'Gasto Colación', 1, 443000351, 'REMUNERACIONES\r\n'),
(6, 'Gastos Locomoción', 1, 443000352, 'REMUNERACIONES\r\n'),
(7, 'Gtos de Alojamiento Y Estadia', 1, 443000332, 'VARIOS'),
(8, 'Gasto de Valija y Encomienda', 1, 443000353, 'SERVICIOS'),
(9, 'Gasto Publicidad', 1, 443000309, 'SERVICIOS'),
(10, 'Uniforme Personal', 1, 443000327, 'REMUNERACIONES\r\n'),
(11, 'Fotocopias e Impresos', 1, 443000305, 'SERVICIOS'),
(12, 'Materiales de Higiene', 1, 443000323, 'MATERIALES'),
(13, 'Combustible y Lubricantes', 3, 222000209, 'VARIOS'),
(14, 'Gasto Tag y Peaje', 1, 443000316, 'VARIOS'),
(15, 'Gastos Notariales', 1, 443000315, 'VARIOS'),
(16, 'Mantención TI', 1, 443000328, 'SERVICIOS'),
(17, 'Multas', 1, 443000326, 'VARIOS'),
(18, 'Siniestros Clientes', 1, 443000325, 'VARIOS'),
(19, 'Mantención y Reparación', 1, 443000354, 'MANTENCION'),
(20, 'Mantención Vehículos', 3, 222000209, 'MANTENCION'),
(21, 'Gastos Flete', 1, 443000317, 'SERVICIOS'),
(22, 'Gasto Beneficio Trabajador', 1, 443000334, 'REMUNERACIONES\r\n'),
(23, 'Finiquito', 1, 443000336, 'REMUNERACIONES\r\n'),
(24, 'Activo Fijo', 1, 112000206, 'ACTIVO FIJO'),
(25, 'Ingresos por Abonados', 2, 441000102, 'INGRESOS'),
(26, 'Gastos Comunes', 2, 443000312, 'ARRIENDOS'),
(27, 'Arriendos', 2, 443000302, 'ARRIENDOS'),
(28, 'Contratos Leasing', 3, 111000129, 'ACTIVO FIJO'),
(29, 'Arriendos Lockers', 2, 443000329, 'ARRIENDOS'),
(30, 'Gastos Bancarios', 2, 443000320, 'VARIOS'),
(31, 'Gastos Generales', 2, 443000301, 'VARIOS'),
(32, 'Seguros', 2, 443000304, 'VARIOS'),
(33, 'Materiales Operacionales', 1, 443000322, 'MATERIALES'),
(34, 'Gastos Arriendos Flota', 2, 443000331, 'SERVICIOS'),
(35, 'Gastos Telefonico', 2, 443000303, 'SERVICIOS'),
(36, 'Inventario de Materiales', 3, 222000209, 'INVENTARIO'),
(37, 'Gasto Patente Municipal', 1, 443000321, 'VARIOS'),
(38, 'Honorarios', 1, 443000344, 'REMUNERACIONES\r\n'),
(39, 'Gtos. Electricidad', 1, 443000310, 'SERVICIOS'),
(40, 'Ingreso por Centros de Lavados', 2, 441000105, 'INGRESOS'),
(41, 'Ingreso por Administración', 2, 441000104, 'INGRESOS'),
(44, 'Iva Débito', 3, 221000226, 'INGRESOS'),
(45, 'Iva Credito', 3, 111000122, 'INGRESOS'),
(46, 'Ingresos Boleta Fiscal', 2, 441000101, 'INGRESOS'),
(47, 'Bono Meta Mensual', 2, 443101005, 'REMUNERACIONES\r\n'),
(48, 'Bono Meta Trimestral', 2, 443101006, 'REMUNERACIONES\r\n'),
(49, 'Aporte Celular', 2, 443101007, 'REMUNERACIONES\r\n'),
(50, 'Bono Asist Y Puntualidad', 2, 443101009, 'REMUNERACIONES\r\n'),
(51, 'Sueldo Base', 2, 443101101, 'REMUNERACIONES\r\n'),
(52, 'Gratificacion', 2, 443101102, 'REMUNERACIONES\r\n'),
(53, 'Horas Extras', 2, 443101103, 'REMUNERACIONES\r\n'),
(54, 'Bono Turno Apoyo', 2, 443101104, 'REMUNERACIONES\r\n'),
(55, 'Aguinaldo', 2, 443101106, 'REMUNERACIONES\r\n'),
(56, 'Asignacion Familiar', 2, 443101109, 'REMUNERACIONES\r\n'),
(57, 'Bono Sala Cuna', 2, 443101110, 'REMUNERACIONES\r\n'),
(58, 'Fam Retro Activo', 2, 443101111, 'REMUNERACIONES\r\n'),
(59, 'Cesantia Empleador', 2, 443101112, 'REMUNERACIONES\r\n'),
(60, 'Seguro De Inv. Y Sobrev.', 2, 443101113, 'REMUNERACIONES\r\n'),
(61, 'Seg. Complementario Salud Emp', 2, 443000333, 'REMUNERACIONES\r\n'),
(62, 'Mutual Seguridad', 2, 443101115, 'REMUNERACIONES\r\n'),
(63, 'Viatico', 2, 443101116, 'REMUNERACIONES\r\n'),
(64, 'Otros Impuesto Por Pagar', 3, 221000225, 'OTROS'),
(65, 'Retenciones por Pagar', 3, 221000223, 'OTROS'),
(67, 'Caja', 3, 111000101, 'ACTIVO'),
(68, 'Banco', 3, 111000102, 'ACTIVO'),
(69, 'Existencias Inventario', 1, 113000311, 'ACTIVO'),
(70, 'Gasto Afp', 2, 443101199, '\r\nELIMINAR'),
(71, 'Gasto Salud', 2, 443101198, '\nELIMINAR'),
(72, 'Colación', 2, 443101351, 'REMUNERACIONES\r\n'),
(73, 'Movilización', 2, 443101352, 'REMUNERACIONES\r\n'),
(74, 'Arriendo NC Exenta', 2, 443000999, 'ARRIENDOS'),
(75, 'Devolución por Excedentes', 2, 443000355, 'SERVICIOS'),
(76, 'Imponible Suspensión', 2, 444444000, 'REMUNERACIONES');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genders`
--

CREATE TABLE `genders` (
  `gender_id` bigint(20) UNSIGNED NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `genders`
--

INSERT INTO `genders` (`gender_id`, `gender`, `created_at`, `updated_at`) VALUES
(1, 'Femenino', NULL, NULL),
(2, 'Masculino', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `healths`
--

CREATE TABLE `healths` (
  `health_id` bigint(20) UNSIGNED NOT NULL,
  `health` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `healths`
--

INSERT INTO `healths` (`health_id`, `health`, `created_at`, `updated_at`) VALUES
(1, 'FONASA', NULL, NULL),
(2, 'Isapre Banmedica', NULL, NULL),
(3, 'Isapre Consalud', '0000-00-00 00:00:00', NULL),
(4, 'Isapre VidaTres', NULL, NULL),
(5, 'Isapre Colmena', NULL, NULL),
(6, 'Isapre Cruz Blanca S.A', NULL, NULL),
(7, 'Isapre Chuquicamata', NULL, NULL),
(8, 'Isapre Óptima', NULL, NULL),
(9, 'Isapre Institución de Salud Previsional Fusat Ltda', NULL, NULL),
(10, 'Isapre Fundación Bco. Estado', NULL, NULL),
(11, 'Isapre Mas Vida', NULL, NULL),
(12, 'Isapre Rio Blanco', NULL, NULL),
(13, 'Isapre Cruz del norte', NULL, NULL),
(14, 'Isapre San Lorenzo Isapre Ltda', NULL, NULL),
(15, 'Dipreca', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories`
--

CREATE TABLE `inventories` (
  `inventory_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `branch_office_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_type_id` bigint(20) UNSIGNED NOT NULL,
  `document_number` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_types`
--

CREATE TABLE `inventory_types` (
  `inventory_type_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '1797_10_12_000000_create_regions_table', 1),
(2, '1798_10_12_000000_create_providences_table', 1),
(3, '1798_10_12_000001_create_communes_table', 1),
(4, '1799_10_12_000000_create_rols_table', 1),
(5, '1800_10_12_000000_create_users_table', 1),
(6, '1899_10_12_000000_create_inventorytypes_table', 1),
(7, '1899_10_12_000000_create_productcategories_table', 1),
(8, '1899_10_12_000001_create_products_table', 1),
(9, '1900_10_12_000000_create_banks_table', 1),
(10, '1900_10_12_000000_create_branchoffices_table', 1),
(11, '1900_10_12_000000_create_dtes_table', 1),
(12, '1900_10_12_000000_create_eventtypes_table', 1),
(13, '1900_10_12_000000_create_healths_table', 1),
(14, '1900_10_12_000000_create_inventories_table', 1),
(15, '1900_10_12_000000_create_pentions_table', 1),
(16, '1900_10_12_000000_create_supervisorsbranchoffices_table', 1),
(17, '1900_10_12_000001_create_events_table', 1),
(18, '1901_10_12_000000_create_civilstates_table', 1),
(19, '1902_10_12_000000_create_coins_table', 1),
(20, '1903_10_12_000000_create_contracttypes_table', 1),
(21, '1903_10_12_000000_create_documenttypes_table', 1),
(22, '1904_10_12_000000_create_genders_table', 1),
(23, '1904_10_12_000000_create_nationalities_table', 1),
(24, '2014_10_12_100000_create_password_resets_table', 1),
(25, '2015_10_12_000000_create_clients_table', 1),
(26, '2015_10_12_000000_create_employees_table', 1),
(27, '2015_10_12_000000_create_suppliers_table', 1),
(28, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nationalities`
--

CREATE TABLE `nationalities` (
  `nationality_id` bigint(20) UNSIGNED NOT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `nationalities`
--

INSERT INTO `nationalities` (`nationality_id`, `nationality`, `created_at`, `updated_at`) VALUES
(1, 'Chileno (a)', NULL, NULL),
(2, 'Colombiano (a)', NULL, NULL),
(3, 'Haitiano (a)', NULL, NULL),
(4, 'Paraguayo (a)', NULL, NULL),
(5, 'Venezolano (a)', NULL, NULL),
(7, 'Boliviano (a)', NULL, NULL),
(8, 'Peruano (a)', NULL, NULL),
(9, 'Ecuatoriano (a)', NULL, NULL),
(10, 'Cubano (a)', NULL, NULL),
(11, 'Argentino (a)', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pentions`
--

CREATE TABLE `pentions` (
  `pention_id` bigint(20) UNSIGNED NOT NULL,
  `pention` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pentions`
--

INSERT INTO `pentions` (`pention_id`, `pention`, `created_at`, `updated_at`) VALUES
(1, 'AFP-Cuprum', NULL, NULL),
(2, 'AFP-Habitat', NULL, NULL),
(3, 'AFP-Plan Vital', NULL, NULL),
(4, 'AFP-Provida', NULL, NULL),
(5, 'AFP-Capital', NULL, NULL),
(6, 'AFP-Modelo', NULL, NULL),
(7, 'AFP-Uno', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_category_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_stock` int(11) NOT NULL,
  `max_stock` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_categories`
--

CREATE TABLE `product_categories` (
  `product_category_id` bigint(20) UNSIGNED NOT NULL,
  `product_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `providences`
--

CREATE TABLE `providences` (
  `providence_id` bigint(20) UNSIGNED NOT NULL,
  `providence` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regions`
--

CREATE TABLE `regions` (
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordinal_region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `regions`
--

INSERT INTO `regions` (`region_id`, `region`, `ordinal_region`, `created_at`, `updated_at`) VALUES
(0, 'Metropolitana de Santiago', 'RM', NULL, NULL),
(1, 'Tarapacá', 'I', NULL, NULL),
(2, 'Antofagasta', 'II', NULL, NULL),
(3, 'Atacama', 'III', NULL, NULL),
(4, 'Coquimbo', 'IV', NULL, NULL),
(5, 'Valparaiso', 'V', NULL, NULL),
(6, 'Libertador General Bernardo O\'Higgins', 'VI', NULL, NULL),
(7, 'Maule', 'VII', NULL, NULL),
(8, 'Biobío', 'VIII', NULL, NULL),
(9, 'La Araucanía', 'IX', NULL, NULL),
(10, 'Los Lagos', 'X', NULL, NULL),
(11, 'Aisén del General Carlos Ibáñez del Campo', 'XI', NULL, NULL),
(12, 'Magallanes y de la Antártica Chilena', 'XII', NULL, NULL),
(14, 'Los Ríos', 'XIV', NULL, NULL),
(15, 'Arica y Parinacota', 'XV', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rols`
--

CREATE TABLE `rols` (
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `rol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rols`
--

INSERT INTO `rols` (`rol_id`, `rol`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Analista', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Colaborador', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Supervisor', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Tesorero', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Gestor de Contenidos', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Auditor', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Analista de RH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Candidato', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Analista de Selección', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Gerente', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Gestor de Contenido', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Auditor de Operaciones', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Cliente', '2019-07-24 04:00:00', '2019-07-24 04:00:00'),
(15, 'Contabilidad', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Mantenimiento', '2019-06-04 04:00:00', '2019-06-14 04:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supervisors_branch_offices`
--

CREATE TABLE `supervisors_branch_offices` (
  `branch_office_id` bigint(20) UNSIGNED NOT NULL,
  `rut` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
--

CREATE TABLE `suppliers` (
  `rut` int(10) UNSIGNED NOT NULL,
  `payment_commitment` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `rut` int(10) UNSIGNED NOT NULL,
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `names` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`rut`, `rol_id`, `names`, `email`, `remember_token`, `created_at`, `updated_at`) VALUES
(5246210, 3, 'Hector Gerardo Alcayaga Peralta', 'halcayaga@jisparking.cl', NULL, NULL, NULL),
(6152617, 3, 'Mirna Eugenia Gonzalez Ahumada', 'mgonzalez@jisparking.cl', NULL, NULL, NULL),
(6307871, 3, 'Bernardo Fernando Navarrete Gonzalez', 'bnavarrete@jisparking.cl', NULL, NULL, NULL),
(6628494, 3, 'Rosa Veronica Olmos Silva', 'rolmos@jisparking.cl', NULL, NULL, NULL),
(6802969, 3, 'Margarita Del Carmen Gonzalez Ahumada', 'magonzalez@jisparking.cl', NULL, NULL, NULL),
(6974036, 3, 'Ruben Osvaldo Saavedra Muñoz', 'rsaavedra@jisparking.cl', NULL, NULL, NULL),
(7066149, 3, 'Juan  Francisco Zamorano Beas', 'jzamorano@jisparking.cl', NULL, NULL, NULL),
(7174172, 4, 'Luis Gregorio Miranda Montero', 'luismiranda@jisparking.cl', NULL, NULL, NULL),
(7274753, 3, 'Pedro Zozimo Pereira Contreras', 'ppereira@jisparking.cl', NULL, NULL, NULL),
(7473121, 3, 'Sonia Orlanda  Duran  Avendaño', 'sduran@jisparking.cl', NULL, NULL, NULL),
(7835227, 3, 'Rita Elsa  Guzmán Gomez', 'rguzman@jisparking.cl', NULL, NULL, NULL),
(8000996, 3, 'Alejandro Luis Adolfo Campos De Las Riberas', 'acampos@jisparking.cl', NULL, NULL, NULL),
(8068401, 3, 'Estrella Del Carmen Torres Zuñiga', 'etorres@jisparking.cl', NULL, NULL, NULL),
(8142157, 3, 'Betty Maria Aranda Carvajal', 'baranda@jisparking.cl', NULL, NULL, NULL),
(8602937, 3, 'Alejandro Hector Del Canto Zepeda', 'acanto@jisparking.cl', NULL, NULL, NULL),
(8820315, 3, 'Benjamin Leonardo Bruyer Gonzalez', 'leonardobruyer@jisparking.cl', NULL, NULL, NULL),
(8883533, 3, 'Patricia Marcela Mancilla Mayorga', 'pmancilla@jisparking.cl', NULL, NULL, NULL),
(9134412, 3, 'Gladys Antonia Villegas  Alvarez', 'svillegas@jisparking.cl', NULL, NULL, NULL),
(9360615, 3, 'Carlos Roberto Hidalgo  Miranda', 'chidalgo@jisparking.cl', NULL, NULL, NULL),
(9426000, 3, 'Gabriel Ivan Arriagada Fuentes', 'garriagada@jisparking.cl', NULL, NULL, NULL),
(9482390, 3, 'Hernando Jose Gonzalez Pinilla', 'hgonzalez@jisparking.cl', NULL, NULL, NULL),
(9535406, 3, 'Rigoberto Ivan Cerda Guerra', 'rcerda@jisparking.cl', NULL, NULL, NULL),
(9874855, 3, 'Gema Geraldina Galdamez Galdamez', 'ggaldamez@jisparking.cl', NULL, NULL, NULL),
(10033721, 1, 'Cristian Andres Inzunza Gonzalez', 'cristianinzunza@jisparking.cl', NULL, NULL, NULL),
(10033741, 1, 'Marcelo Alejandro Inzunza Gonzalez', 'marceloinzunza@jisparking.cl', NULL, NULL, NULL),
(10137545, 3, 'Margarita Del Carmen Contreras Paredes', 'mcontreras@jisparking.cl', NULL, NULL, NULL),
(10291162, 3, 'Flor Cecilia Juica  Luna ', 'fjuica@jisparking.cl', NULL, NULL, NULL),
(10313233, 3, 'Mercedes Del Carmen Pontigo Farias', 'mpontigo@jisparking.cl', NULL, NULL, NULL),
(10331197, 4, 'Miriam Luz Zapata Barra', 'miriamzapata@jisparking.cl', NULL, NULL, NULL),
(10431151, 3, 'Judith Carolina Baeza Gonzalez', 'jbaeza@jisparking.cl', NULL, NULL, NULL),
(10572595, 3, 'Victor Hernan  Saldivia Aguilar', 'vsaldivia@jisparking.cl', NULL, NULL, NULL),
(10626085, 3, 'Eliana De Jesus Romero Espinosa', '', NULL, NULL, NULL),
(10628514, 4, 'Maria Soledad Nuñez Torrecilla', 'marianunez@jisparking.cl', NULL, NULL, NULL),
(10725575, 3, 'Jorge Ricardo Flores Pasten', 'jflores@jisparking.cl', NULL, NULL, NULL),
(10750262, 3, 'Roxana Del Carmen  Navarro Oyarce', 'rnavarro@jisparking.cl', NULL, NULL, NULL),
(10790603, 1, 'Patricio Marcelo Gomez Gonzalez', 'patriciogomez@jisparking.cl', NULL, NULL, NULL),
(10923452, 4, 'Fidelia Del Carmen Contreras Gonzalez', 'fideliacontreras@jisparking.cl', NULL, NULL, NULL),
(10958841, 3, 'Ingrid Elizabeth Gomez Jara', 'igomez@jisparking.cl', NULL, NULL, NULL),
(10963260, 3, 'Maria Adelaida Velasquez Gallardo', 'mvelasquez@jisparking.cl', NULL, NULL, NULL),
(11166398, 3, 'Iris Gricelda Cordova Figueroa', 'icordova@jisparking.cl', NULL, NULL, NULL),
(11243121, 3, 'Artemio Alfonso  Barra Panes', 'abarra@jisparking.cl', NULL, NULL, NULL),
(11656483, 3, 'Ricardo Fernando Gonzalez Gonzalez', 'rgonzalez@jisparking.cl', NULL, NULL, NULL),
(11670463, 4, 'Carlos Vicente Hernández Olguín', 'carloshernandez@jisparking.cl', NULL, NULL, NULL),
(11694645, 4, 'Ana Maria Estolaza Peralta', 'aestolaza@jisparking.cl', NULL, NULL, NULL),
(11756501, 3, 'Richard Antonio Aranda Puja', 'raranda@jisparking.cl', NULL, NULL, NULL),
(11819715, 3, 'Graciela Carolina Contreras Gonzalez', 'gcontreras@jisparking.cl', NULL, NULL, NULL),
(11831400, 3, 'Miryam  Paula Araya Mora', 'maraya@jisparking.cl', NULL, NULL, NULL),
(11849551, 3, 'Ivonne Alejandra Morales Morales', 'imorales@jisparking.cl', NULL, NULL, NULL),
(11938713, 3, 'Walter Arnaldo Pasten Cortes', 'wpasten@jisparking.cl', NULL, NULL, NULL),
(12025353, 3, 'Amora Cecilia Almuna Medel', 'aalmuna@jisparking.cl', NULL, NULL, NULL),
(12067461, 3, 'Patricia Esmeralda Estolaza Peralta', 'pestolaza@jisparking.cl', NULL, NULL, NULL),
(12069398, 3, 'Juan Manuel  Calderon Peralta', 'jcalderon@jisparking.cl', NULL, NULL, NULL),
(12233298, 4, 'Elizabeth Patricia Gonzalez Carrasco', 'egonzalez@jisparking.cl', NULL, NULL, NULL),
(12252924, 3, 'Margarita Nora  Herrera Arriagada', 'mherrera@jisparking.cl', NULL, NULL, NULL),
(12297111, 3, 'Sandra Del Carmen Salazar Salazar', 'ssalazar@jisparking.cl', NULL, NULL, NULL),
(12444870, 3, 'Eduardo Alejandro Cortes Rivero', 'ecortes@jisparking.cl', NULL, NULL, NULL),
(12501219, 3, 'Erika Magaly Figueroa Contreras', 'efigueroa@jisparking.cl', NULL, NULL, NULL),
(12642557, 4, 'Marco Antonio Figueroa Diaz', 'marcofigueroa@jisparking.cl', NULL, NULL, NULL),
(12791259, 3, 'Ruth Maribel Norambuena Cofre', 'rnorambuena@jisparking.cl', NULL, NULL, NULL),
(12793546, 3, 'Guadalupe Del Carmen Gonzalez Toro', 'ggonzalez@jisparking.cl', NULL, NULL, NULL),
(12885196, 3, 'Jessica Del Carmen Valenzuela Hueraman', 'jvalenzuela@jisparking.cl', NULL, NULL, NULL),
(12921934, 7, 'Yessica Alejandra Sanhueza Valenzuela', 'yessicasanhueza@jsiparking.cl', NULL, NULL, NULL),
(12996111, 3, 'Viviana Del Carmen  Navarro Gonzalez', 'vnavarro@jisparking.cl', NULL, NULL, NULL),
(13042688, 3, 'Jose Antonio Inzunza Toledo', 'joseinzunza@jisparking.cl', NULL, NULL, NULL),
(13175573, 4, 'Yenny Karina  Escobar Quezada', 'yennyescobar@jisparking.cl', NULL, NULL, NULL),
(13252171, 3, 'Carolina Soledad Flores Aedo', 'carolina@gmail.com', NULL, NULL, NULL),
(13405296, 3, 'Olga Magaly  Carrion Sanhueza', 'ocarrion@jisparking.cl', NULL, NULL, NULL),
(13492547, 6, 'Angelica Loreto  Aballay Sanchez', 'angelicaaballay@jisparking.cl', NULL, NULL, NULL),
(13708510, 3, 'Alejandrina Del Carmen Contreras Lopez', 'acontreras@jisparking.cl', NULL, NULL, NULL),
(13764500, 3, 'Alicia Andrea Suarez Tapia', 'asuarez@jisparking.cl', NULL, NULL, NULL),
(13917718, 4, 'Cristian Ruben Adriazola Sandoval', 'cristianadriazola@jisparking.cl', NULL, NULL, NULL),
(14003387, 4, 'Ariana Elizabeth Lopez Lopez', 'alopez@jisparking.cl', NULL, NULL, NULL),
(14008227, 3, 'Francisco Eduardo Mendez Ayala', 'fmendez@jisparking.cl', NULL, NULL, NULL),
(14313469, 9, 'Nancy estelvina Gallardo Rivera', 'ngallardo@jisparking.cl', NULL, NULL, NULL),
(14352987, 3, 'Marisol alejandra Monsalve Aguirre', 'mmonsalve@jisparking.cl', NULL, NULL, NULL),
(14481738, 3, 'Evelyn Damarys Molina Saavedra', 'emolina@jisparking.cl', NULL, NULL, NULL),
(14688597, 3, 'Tomas  Soria Guerra', 'tsoria@jisparking.cl', NULL, NULL, NULL),
(15071507, 3, 'Hector Alfredo  Guerra Serrano', 'hguerra@jisparking.cl', NULL, NULL, NULL),
(15081798, 3, 'Andrea Alejandra Mena Olivares', 'amena@jisparking.cl', NULL, NULL, NULL),
(15438533, 3, 'Valeria Alejandra  Contreras Carrasco', 'vcontreras@jisparking.cl', NULL, NULL, NULL),
(15538007, 1, 'Rodrigo Esteban Cabezas Zuñiga', 'rodrigocabezas@jisparking.cl', NULL, NULL, NULL),
(15583027, 3, 'Karina Andrea Flores Garcia', 'kflores@jisparking.cl', NULL, NULL, NULL),
(15757306, 3, 'Dario De Jesus Contreras Martinez', 'dcontreras@jisparking.cl', NULL, NULL, NULL),
(15808735, 4, 'Adriana Paulina Castillo Aguirre', 'adrianacastillo@jisparking.cl', NULL, NULL, NULL),
(15910256, 3, 'Jose Raul Segura Paz', 'jsegura@jisparking.cl', NULL, NULL, NULL),
(15918421, 3, 'Sandra Jimena Sandoval Vega', 'ssandoval@jisparking.cl', NULL, NULL, NULL),
(15947022, 3, 'Jessica Alejandra Gomez Nahuelhuen', 'jgomez@jisparking.cl', NULL, NULL, NULL),
(16281232, 6, 'Gonzalo Ignacio  Aballay Sanchez', 'gonzaloaballay@jisparking.cl', NULL, NULL, NULL),
(16311308, 3, 'Jonathan Jesus  Sanchez Ibarra', 'jsanchez@jisparking.cl', NULL, NULL, NULL),
(16404869, 3, 'Fabiola Yamilet  Fuentealba Viveros', 'ffuentealba@jisparking.cl', NULL, NULL, NULL),
(16411890, 3, 'Carolina Andrea Rojas  Merino', 'crojas@jisparking.cl', NULL, NULL, NULL),
(16543379, 3, 'Manuel Alejandro Esparza Medina', 'mesparza@jisparking.cl', NULL, NULL, NULL),
(16625137, 3, 'María josé Tapia Aguilera', 'mtapia@jisparking.cl', NULL, NULL, NULL),
(16727358, 4, 'Gloria Eliana  Nahuelhueique Vera', 'gnahuelhueique@jisparking.cl', NULL, NULL, NULL),
(16782049, 3, 'Jacqueline Noemi Filun Salgado', 'jfilun@jisparking.cl', NULL, NULL, NULL),
(16787383, 1, 'Juan Emilio Caroca Rojas', 'juancaroca@jisparking.cl', NULL, NULL, NULL),
(16788913, 3, 'Daisy Dayana Diaz Garrido', 'ddiaz@jisparking.cl', NULL, NULL, NULL),
(16849126, 3, 'Sofia Alejandra Mundaca Mundaca', 'smundaca@jisparking.cl', NULL, NULL, NULL),
(17049320, 4, 'Cristian Andres Cabezas Zuñiga', 'cristiancabezas@jisparking.cl', NULL, NULL, NULL),
(17113779, 3, 'Johanna Andrea Del Carmen Andrades', 'jcarmen@jisparking.cl', NULL, NULL, NULL),
(17125113, 4, 'Debora Eunice Filun Filun', 'deborafilun@jisparking.cl', NULL, NULL, NULL),
(17128461, 3, 'Katherine Andrea  Pavez Rubio', 'kpavez@jisparking.cl', NULL, NULL, NULL),
(17200607, 3, 'Magdianita Andrea  Saez Duran', 'msaez@jisparking.cl', NULL, NULL, NULL),
(17258553, 3, 'Genesis Ylean  Peña Pardo', 'gpena@jisparking.cl', NULL, NULL, NULL),
(17390237, 3, 'Nicole Andrea Reitter Cordova', 'nreitter@jisparking.cl', NULL, NULL, NULL),
(17707004, 3, 'Tania Andrea Mallea Zamorano', 'tmallea@jisparking.cl', NULL, NULL, NULL),
(17748864, 9, 'Francisca carola Gonzalez Arriagada', 'fgonzalez@jisparking.cl', NULL, NULL, NULL),
(17906315, 3, 'Tabata Andrea Toro Silva', 'ttoro@jisparking.cl', NULL, NULL, NULL),
(17927553, 3, 'Soledad De Las Mercedes Burboa Aguilera', 'sburboa@jisparking.cl', NULL, NULL, NULL),
(17995199, 3, 'Estephanie Valeria  Toro Saldaño', 'etoro@jisparking.cl', NULL, NULL, NULL),
(18202568, 3, 'Diana Victoria Luna Yañez', 'dluna@jisparking.cl', NULL, NULL, NULL),
(18237541, 3, 'Minoshka Andrea Urzua  Robles ', 'murzua@jisparking.cl', NULL, NULL, NULL),
(18239456, 3, 'Natali Saledad  Carcamo Aguilar', 'ncarcamo@jisparking.cl', NULL, NULL, NULL),
(18255994, 3, 'Camila alejandra Retamales Contreras', 'cretamales@jisparking.cl', NULL, NULL, NULL),
(18267465, 3, 'Victor Hugo Mella Torres', 'vmella@jisparking.cl', NULL, NULL, NULL),
(18273096, 3, 'Max Henry Henning Escarate', 'mhenning@jisparking.cl', NULL, NULL, NULL),
(18291733, 4, 'Jacquelinne Nicolle Valverde Urra', 'jacquelinevalverde@jisparking.cl', NULL, NULL, NULL),
(18495158, 3, 'Fabian Gonzalo  Aranda Gutierrez', 'faranda@jisparking.cl', NULL, NULL, NULL),
(18566961, 3, 'Belen Lorena Cerro Saldias', 'bcerro@jisparking.cl', NULL, NULL, NULL),
(18579236, 3, 'Carolina Alicia Muñoz Angulo', 'cmunoz@jisparking.cl', NULL, NULL, NULL),
(18662719, 5, 'Nicolas Sebastian   Farias Gahona', 'nicolasfarias@jisparking.cl', NULL, NULL, NULL),
(18805028, 3, 'Melanny catalina Iturriaga Valverde', 'miturriaga@jisparking.cl', NULL, NULL, NULL),
(18842465, 3, 'Yessenia Del Carmen Arriaza Brito', 'yarriaza@jisparking.cl', NULL, NULL, NULL),
(19004974, 3, 'Jean Paul Michael Arancibia Meza', 'jarancibia@jisparking.cl', NULL, NULL, NULL),
(19181860, 3, 'Vanesa Johana Viviana Guentrepan Navarro', 'vguentrepan@jisparking.cl', NULL, NULL, NULL),
(19208732, 3, 'Joaquin Abel  Barraza Cabezas', 'jbarraza@jisparking.cl', NULL, NULL, NULL),
(19216814, 3, 'Fernando Abraham Godoy  Perez', 'fgodoy@jisparking.cl', NULL, NULL, NULL),
(19229525, 3, 'José Miguel  Aravena Fuentes', 'jaravena@jisparking.cl', NULL, NULL, NULL),
(19355444, 8, 'Amir Salvador  Garfe Wong', 'amirgarfe@jisparking.cl', NULL, NULL, NULL),
(19390553, 3, 'Rodolfo Andres Espinoza Vega', 'respinoza@jisparking.cl', NULL, NULL, NULL),
(19640455, 9, 'Catalina valesca Saez Aguilar', 'csaez@jisparkin.cl', NULL, NULL, NULL),
(19691447, 3, 'Teresa Margarita Contreras Véliz', 'tcontreras@jisparking.cl', NULL, NULL, NULL),
(19844084, 3, 'Karla Nicole  Cabezas Zuñiga', 'kcabezas@jisparking.cl', NULL, NULL, NULL),
(19847848, 3, 'Yanira constansa Mora Mateluna', 'ymora@jisparking.cl', NULL, NULL, NULL),
(20057484, 3, 'Alejandra Ignacia  Inzunza Cordero', 'ainzunza@jisparking.cl', NULL, NULL, NULL),
(20060059, 3, 'Constanza Alejandra  Orrego Sandoval', 'corrego@jisparking.cl', NULL, NULL, NULL),
(20227575, 3, 'Diana Alexandra  Burboa Aguilera', 'dianaburboa@jisparking.cl', NULL, NULL, NULL),
(20483426, 3, 'Juan eduardo Toledo Campos', 'jtoledo@jisparking.cl', NULL, NULL, NULL),
(20544133, 3, 'Ambar ornella Mora Godoy', 'mmora@jisparking.cl', NULL, NULL, NULL),
(20591509, 3, 'William Jean Claude Añigual Aguilera', 'vanigual@jisparking.cl', NULL, NULL, NULL),
(20603792, 3, 'Mario Andres   Miranda  Martinez', 'mmiranda@jisparking.cl', NULL, NULL, NULL),
(20729074, 3, 'Nicolas alfonso Vergara Alfaro', 'nvergara@jisparking.cl', NULL, NULL, NULL),
(21828453, 3, 'Elena Tomasa Ruiz Diaz De Mujica', 'eruiz@jisparking.cl', NULL, NULL, NULL),
(21902443, 4, 'David Wilder Gomez Figueroa', 'davidgomez@jisparking.cl', NULL, NULL, NULL),
(22588139, 3, 'Homar Sanchez Alvarado', 'hsanchez@jisparking.cl', NULL, NULL, NULL),
(22845770, 3, 'Franchesca Rubí Silva Villavicencio', 'fsilva@jisparking.cl', NULL, NULL, NULL),
(23468753, 3, 'Luis Antonio Huasasquiche Wan', 'lhuasasquiche@jisparking.cl', NULL, NULL, NULL),
(23645227, 3, 'Maria Martha Cabanillas Flores', 'mcabanillas@jisparking.cl', NULL, NULL, NULL),
(23881022, 3, 'Winger Enrique Ricaldi Calderon', 'wricaldi@jisparking.cl', NULL, NULL, NULL),
(23955081, 3, 'Wilson Medina Alva', 'wmedina@jisparking.cl', NULL, NULL, NULL),
(24623559, 3, 'Grabiela Del Rocio  Parravicini Sipiran', 'gparravicini@jisparking.cl', NULL, NULL, NULL),
(24773648, 3, 'Delvy Zamira Hernandez Arias', 'dhernandez@jisparking.cl', NULL, NULL, NULL),
(24806581, 4, 'Yeimy Viviana  Vera  Reyes', 'vivianavera@jisparking.cl', NULL, NULL, NULL),
(24811238, 3, 'Sinthya Karina Palomino Echegaray', 'spalomino@jisparking.cl', NULL, NULL, NULL),
(24816016, 3, 'Claudia Milena  Tabon Pineda', 'ctabon@jisparking.cl', NULL, NULL, NULL),
(24959860, 3, 'Luis Alfonso  Julio Pereira', 'ljulio@jisparking.cl', NULL, NULL, NULL),
(25259612, 3, 'Odalis Chambilla Roque', 'ochambilla@jisparking.cl', NULL, NULL, NULL),
(25274872, 3, 'Jovanny Martin Flores Mora', 'jflores@jisparking.cl', NULL, NULL, NULL),
(25310541, 3, 'Nurvys Yasnair Toro -', 'ntoro@jisparking.cl', NULL, NULL, NULL),
(25310683, 3, 'Edwin Alexander Arteaga Barrios', 'earteaga@jisparking.cl', NULL, NULL, NULL),
(25383726, 8, 'Marielena Del Valle  Cazorla Rangel', 'marielenacazorla@jisparking.cl', NULL, NULL, NULL),
(25486922, 3, 'Mirian Marlene  Zambrano Loor', 'mzambrano@jisparking.cl', NULL, NULL, NULL),
(25546345, 3, 'Magnolia   Puerta Pareja', 'mpuerta@jisparking.cl', NULL, NULL, NULL),
(25687759, 3, 'Estela Granja Borja', 'egranja@jisparking.cl', NULL, NULL, NULL),
(25705970, 16, 'Cesar Javier  Botello Pacheco', 'cesarbotello@jisparking.cl', NULL, NULL, NULL),
(25759768, 3, 'Bryan Deyver Guarniz Vasquez', 'bguarniz@jisparking.cl', NULL, NULL, NULL),
(25810610, 3, 'Deisy Del Milagro Azabache Yaipen', 'dazabache@jisparking.cl', NULL, NULL, NULL),
(25939855, 3, 'Katherine Veronica  Chiquito Valiente', 'kchiquito@jisparking.cl', NULL, NULL, NULL),
(25968479, 3, 'Jean Jacques Sylvens -', 'jjacques@jisparking.cl', NULL, NULL, NULL),
(25987295, 9, 'Jhonny Petion Petion', 'jpetion@jisparking.cl', NULL, NULL, NULL),
(26057740, 3, 'Miguel Angel  Ticona Romero', 'mticona@jisparking.cl', NULL, NULL, NULL),
(26072855, 3, 'Carlos Bayardo Estupiñan Sifontes', 'cestupinan@jisparking.cl', NULL, NULL, NULL),
(26173938, 3, 'Oscar Horacio Prada Duarte', 'oprada@jisparking.cl', NULL, NULL, NULL),
(26247446, 3, 'Juan  Camilo Marin Garcia', 'jmarin@jisparking.cl', NULL, NULL, NULL),
(26299443, 3, 'Eva Johana  Letzguss Coruma', 'eletzguss@jisparking.cl', NULL, NULL, NULL),
(26303301, 3, 'Feguenson Jean Jacques Philistin', 'jphilistin@jisparking.cl', NULL, NULL, NULL),
(26380591, 3, 'Gary Luis Fernandez Ochoa', 'gfernandez@jisparking.cl', NULL, NULL, NULL),
(26468143, 3, 'Jorge Jesus  Patiño Pino', 'jpatino@jisparking.cl', NULL, NULL, NULL),
(26474129, 3, 'Franci Del Pilar Jaten De Romero', 'fjaten@jisparking.cl', NULL, NULL, NULL),
(26488988, 3, 'Luis Javier Melean Sanhez', 'lmelean@jisparking.cl', NULL, NULL, NULL),
(26567665, 3, 'Luis ernesto Miranda Lucien', 'umiranda@jisparking.cl', NULL, NULL, NULL),
(26597593, 3, 'Hilton Joaquin Masters Masters', 'hmasters@jisparking.cl', NULL, NULL, NULL),
(26598819, 3, 'Jenifer Nataly Huertas Flores', 'jhuertas@jisparking.cl', NULL, NULL, NULL),
(26671285, 3, 'Pedro Pablo Peña Padilla', 'ppena@jisparking.cl', NULL, NULL, NULL),
(26803446, 3, 'Carlos Eduardo Bruestlen Tovar', 'cbruestlen@jisparking.cl', NULL, NULL, NULL),
(26810764, 3, 'Arianny Stefany Sifontes Orta', 'asifontes@jisparking.cl', NULL, NULL, NULL),
(26812916, 3, 'Arnoldo José Torres Valladares', 'atorres@jisparking.cl', NULL, NULL, NULL),
(26826390, 3, 'Clemente Flores Flores', 'clflores@jisparking.cl', NULL, NULL, NULL),
(26913967, 4, 'Kelida Del Valle  Cazorla Rangel', 'kcazorla@jisparking.cl', NULL, NULL, NULL),
(26980959, 9, 'Frank vicente Ovalles Virahonda', 'fovalles@jisparking.cl', NULL, NULL, NULL),
(27008183, 3, 'Granda isabel Patiño Pino', 'gpatino@jisparking.cl', NULL, NULL, NULL),
(27105204, 3, 'Maria josé Sequera Tovar', 'msequera@jisparking.cl', NULL, NULL, NULL),
(27141399, 3, 'Jesus Rafael Cova Huerta', 'jesuscova@jisparking.cl', NULL, NULL, NULL),
(27173020, 3, 'Maria Lourdes  Briceño Mendoza', 'mbriceno@jisparking.cl', NULL, NULL, NULL),
(44230720, 3, 'Juliette Carolina   Murillo Cervantes', '', NULL, NULL, NULL),
(99999999, 3, 'Prueba Prueba Prueba', 'pprueba@jisparking.cl', NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indices de la tabla `branch_offices`
--
ALTER TABLE `branch_offices`
  ADD PRIMARY KEY (`branch_office_id`);

--
-- Indices de la tabla `civil_states`
--
ALTER TABLE `civil_states`
  ADD PRIMARY KEY (`civil_state_id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`rut`),
  ADD KEY `clients_region_id_foreign` (`region_id`),
  ADD KEY `clients_commune_id_foreign` (`commune_id`);

--
-- Indices de la tabla `coins`
--
ALTER TABLE `coins`
  ADD PRIMARY KEY (`coin_id`);

--
-- Indices de la tabla `communes`
--
ALTER TABLE `communes`
  ADD PRIMARY KEY (`commune_id`);

--
-- Indices de la tabla `contract_types`
--
ALTER TABLE `contract_types`
  ADD PRIMARY KEY (`contract_type_id`);

--
-- Indices de la tabla `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`document_type_id`);

--
-- Indices de la tabla `dtes`
--
ALTER TABLE `dtes`
  ADD PRIMARY KEY (`dtes_id`);

--
-- Indices de la tabla `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`rut`),
  ADD KEY `employees_gender_id_foreign` (`gender_id`),
  ADD KEY `employees_nationality_id_foreign` (`nationality_id`),
  ADD KEY `employees_civil_state_id_foreign` (`civil_state_id`),
  ADD KEY `employees_region_id_foreign` (`region_id`),
  ADD KEY `employees_providence_id_foreign` (`providence_id`),
  ADD KEY `employees_commune_id_foreign` (`commune_id`),
  ADD KEY `employees_health_id_foreign` (`health_id`),
  ADD KEY `employees_pention_id_foreign` (`pention_id`),
  ADD KEY `employees_contract_type_id_foreign` (`contract_type_id`),
  ADD KEY `employees_branch_office_id_foreign` (`branch_office_id`);

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `events_event_type_id_foreign` (`event_type_id`);

--
-- Indices de la tabla `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`event_type_id`);

--
-- Indices de la tabla `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id_expense_type`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`gender_id`);

--
-- Indices de la tabla `healths`
--
ALTER TABLE `healths`
  ADD PRIMARY KEY (`health_id`);

--
-- Indices de la tabla `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `inventories_supplier_id_foreign` (`supplier_id`),
  ADD KEY `inventories_branch_office_id_foreign` (`branch_office_id`),
  ADD KEY `inventories_product_id_foreign` (`product_id`),
  ADD KEY `inventories_inventory_type_id_foreign` (`inventory_type_id`);

--
-- Indices de la tabla `inventory_types`
--
ALTER TABLE `inventory_types`
  ADD PRIMARY KEY (`inventory_type_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nationalities`
--
ALTER TABLE `nationalities`
  ADD PRIMARY KEY (`nationality_id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `pentions`
--
ALTER TABLE `pentions`
  ADD PRIMARY KEY (`pention_id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `products_product_category_id_foreign` (`product_category_id`);

--
-- Indices de la tabla `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`product_category_id`);

--
-- Indices de la tabla `providences`
--
ALTER TABLE `providences`
  ADD PRIMARY KEY (`providence_id`);

--
-- Indices de la tabla `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`region_id`);

--
-- Indices de la tabla `rols`
--
ALTER TABLE `rols`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indices de la tabla `supervisors_branch_offices`
--
ALTER TABLE `supervisors_branch_offices`
  ADD PRIMARY KEY (`rut`),
  ADD KEY `supervisors_branch_offices_branch_office_id_foreign` (`branch_office_id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`rut`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`rut`),
  ADD KEY `users_rol_id_foreign` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `banks`
--
ALTER TABLE `banks`
  MODIFY `bank_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=673;

--
-- AUTO_INCREMENT de la tabla `branch_offices`
--
ALTER TABLE `branch_offices`
  MODIFY `branch_office_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `civil_states`
--
ALTER TABLE `civil_states`
  MODIFY `civil_state_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `rut` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `coins`
--
ALTER TABLE `coins`
  MODIFY `coin_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `communes`
--
ALTER TABLE `communes`
  MODIFY `commune_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT de la tabla `contract_types`
--
ALTER TABLE `contract_types`
  MODIFY `contract_type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `document_types`
--
ALTER TABLE `document_types`
  MODIFY `document_type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `dtes`
--
ALTER TABLE `dtes`
  MODIFY `dtes_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `rut` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `events`
--
ALTER TABLE `events`
  MODIFY `event_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `event_types`
--
ALTER TABLE `event_types`
  MODIFY `event_type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `genders`
--
ALTER TABLE `genders`
  MODIFY `gender_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `healths`
--
ALTER TABLE `healths`
  MODIFY `health_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `inventories`
--
ALTER TABLE `inventories`
  MODIFY `inventory_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_types`
--
ALTER TABLE `inventory_types`
  MODIFY `inventory_type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `nationalities`
--
ALTER TABLE `nationalities`
  MODIFY `nationality_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pentions`
--
ALTER TABLE `pentions`
  MODIFY `pention_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `product_category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `providences`
--
ALTER TABLE `providences`
  MODIFY `providence_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `regions`
--
ALTER TABLE `regions`
  MODIFY `region_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `rols`
--
ALTER TABLE `rols`
  MODIFY `rol_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `supervisors_branch_offices`
--
ALTER TABLE `supervisors_branch_offices`
  MODIFY `rut` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `rut` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `rut` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_commune_id_foreign` FOREIGN KEY (`commune_id`) REFERENCES `communes` (`commune_id`),
  ADD CONSTRAINT `clients_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`),
  ADD CONSTRAINT `clients_rut_foreign` FOREIGN KEY (`rut`) REFERENCES `users` (`rut`);

--
-- Filtros para la tabla `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_branch_office_id_foreign` FOREIGN KEY (`branch_office_id`) REFERENCES `branch_offices` (`branch_office_id`),
  ADD CONSTRAINT `employees_civil_state_id_foreign` FOREIGN KEY (`civil_state_id`) REFERENCES `civil_states` (`civil_state_id`),
  ADD CONSTRAINT `employees_commune_id_foreign` FOREIGN KEY (`commune_id`) REFERENCES `communes` (`commune_id`),
  ADD CONSTRAINT `employees_contract_type_id_foreign` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_types` (`contract_type_id`),
  ADD CONSTRAINT `employees_gender_id_foreign` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`),
  ADD CONSTRAINT `employees_health_id_foreign` FOREIGN KEY (`health_id`) REFERENCES `healths` (`health_id`),
  ADD CONSTRAINT `employees_nationality_id_foreign` FOREIGN KEY (`nationality_id`) REFERENCES `nationalities` (`nationality_id`),
  ADD CONSTRAINT `employees_pention_id_foreign` FOREIGN KEY (`pention_id`) REFERENCES `pentions` (`pention_id`),
  ADD CONSTRAINT `employees_providence_id_foreign` FOREIGN KEY (`providence_id`) REFERENCES `providences` (`providence_id`),
  ADD CONSTRAINT `employees_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`),
  ADD CONSTRAINT `employees_rut_foreign` FOREIGN KEY (`rut`) REFERENCES `users` (`rut`);

--
-- Filtros para la tabla `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_event_type_id_foreign` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`event_type_id`);

--
-- Filtros para la tabla `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_branch_office_id_foreign` FOREIGN KEY (`branch_office_id`) REFERENCES `branch_offices` (`branch_office_id`),
  ADD CONSTRAINT `inventories_inventory_type_id_foreign` FOREIGN KEY (`inventory_type_id`) REFERENCES `inventory_types` (`inventory_type_id`),
  ADD CONSTRAINT `inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `inventories_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `users` (`rut`);

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`product_category_id`);

--
-- Filtros para la tabla `supervisors_branch_offices`
--
ALTER TABLE `supervisors_branch_offices`
  ADD CONSTRAINT `supervisors_branch_offices_branch_office_id_foreign` FOREIGN KEY (`branch_office_id`) REFERENCES `branch_offices` (`branch_office_id`),
  ADD CONSTRAINT `supervisors_branch_offices_rut_foreign` FOREIGN KEY (`rut`) REFERENCES `users` (`rut`);

--
-- Filtros para la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_rut_foreign` FOREIGN KEY (`rut`) REFERENCES `users` (`rut`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `rols` (`rol_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
