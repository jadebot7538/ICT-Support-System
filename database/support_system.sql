-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 07:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `support_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_account`
--

CREATE TABLE `admin_account` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_account`
--

INSERT INTO `admin_account` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$UJZDhv7NQWV2lKmkSkHFS.UhhLpMrNpAILxy9EQUtO6Ae.ABkg5/e'),
(2, 'admin2', 'admin2\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `gov_id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `ext` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `gov_id`, `last_name`, `first_name`, `middle_name`, `ext`, `created_at`, `is_deleted`) VALUES
(1, 206693, 'ABAD', 'BENJAMIN', 'ORMACIDO', 'JR', '2025-05-02 02:36:23', '0'),
(2, 224092, 'AGAPITO', 'ERWIN', 'LUCINDO', '', '2025-05-02 02:36:23', '0'),
(3, 681270, 'AGATON', 'JERMAINE', 'TOBIAS', '', '2025-05-02 02:36:23', '0'),
(4, 502511, 'AMURAO', 'OLIVIA', 'GULBEN', '', '2025-05-02 02:36:23', '0'),
(5, 417554, 'AREJA', 'SOLOMON ', 'SALONGA', '', '2025-05-02 02:36:23', '0'),
(6, 792726, 'ATRAJE', 'ED SHAYNE', 'SAGCAL', '', '2025-05-02 02:36:23', '0'),
(7, 128195, 'ATRAJE', 'JONN BRENT', 'SANQUI', '', '2025-05-02 02:36:23', '0'),
(8, 296288, 'ATRAJE', 'SHERMAINE', 'MARTINEZ', '', '2025-05-02 02:36:23', '0'),
(9, 257989, 'AUSTRIA', 'CHRISTIAN', 'GABRIEL', '', '2025-05-02 02:36:23', '0'),
(10, 451973, 'BADILLA', 'GRACE', 'LIBUNAO', '', '2025-05-02 02:36:23', '0'),
(11, 367049, 'BAGUISA', 'FLAVIANO', 'JUAN', 'JR', '2025-05-02 02:36:23', '0'),
(12, 858115, 'BAGUISA', 'EDGARDO', 'BUENAVENTURA', '', '2025-05-02 02:36:23', '0'),
(13, 975919, 'BALAGTAS', 'JEAN AIRA', 'INGALLA', '', '2025-05-02 02:36:23', '0'),
(14, 329759, 'BAÑEZ', 'REYNALDO', 'ESCAÑO', 'JR.', '2025-05-02 02:36:23', '0'),
(15, 790761, 'BARIOGA', 'JUNE', 'JAGON', '', '2025-05-02 02:36:23', '0'),
(16, 205867, 'BAUTISTA', 'CHERRY', 'BARTIDO', '', '2025-05-02 02:36:23', '0'),
(17, 994948, 'BAYLON', 'JESSIE', 'PUNTIL', '', '2025-05-02 02:36:23', '0'),
(18, 902680, 'BENEMERITO', 'JUNE', 'LABUCAY', '', '2025-05-02 02:36:23', '0'),
(19, 902132, 'BUADO', 'MARIA THERESA', 'VALDERAMA', '', '2025-05-02 02:36:23', '0'),
(20, 937830, 'BUSUEGO', 'MERNAN', 'MACASAYA', '', '2025-05-02 02:36:23', '0'),
(21, 221480, 'CALLANTA', 'MARYJANE', 'MADRID', '', '2025-05-02 02:36:23', '0'),
(22, 100752, 'CALLEJO', 'ANGELO BRANDO', 'DAYAG', '', '2025-05-02 02:36:23', '0'),
(23, 888190, 'CALPITO', 'KRISSIALEN', 'DOROGA', '', '2025-05-02 02:36:23', '0'),
(24, 343449, 'CAMACHO', 'SHERILYN', 'ANCHETA', '', '2025-05-02 02:36:23', '0'),
(25, 236943, 'CAÑON', 'CHRISTOPHER', 'NAGAÑO', '', '2025-05-02 02:36:23', '0'),
(26, 157351, 'CASTILLO', 'CRESTA-LEE', 'CABIGAO', '', '2025-05-02 02:36:23', '0'),
(27, 569494, 'CASTRO', 'MARK JEFFREY', 'PASCUAL', '', '2025-05-02 02:36:23', '0'),
(28, 685880, 'CASTRO', 'BENJ OLIVER', 'JAVIER', '', '2025-05-02 02:36:23', '0'),
(29, 930795, 'CORDOVA', 'JOHN CARLO', 'CATALAN', '', '2025-05-02 02:36:23', '0'),
(30, 527628, 'CRISTOBAL', 'EDNA', 'TIQUIA', '', '2025-05-02 02:36:23', '0'),
(31, 751447, 'CRUZ', 'DEXTER', 'GREFIEL', '', '2025-05-02 02:36:23', '0'),
(32, 952072, 'CRUZ', 'CARLO JAY ', 'CAYANGA', '', '2025-05-02 02:36:23', '0'),
(33, 852567, 'CRUZ', 'MARIBETH', 'OANES', '', '2025-05-02 02:36:23', '0'),
(34, 492366, 'CUEVAS', 'IVAN', 'VIVAR', '', '2025-05-02 02:36:23', '0'),
(35, 408994, 'DAGAWIN', 'KATE', 'ZAFRA', '', '2025-05-02 02:36:23', '0'),
(36, 846559, 'DARIO', 'GERALDINE', 'GULAPA', '', '2025-05-02 02:36:23', '0'),
(37, 884207, 'DAYAG', 'PRINCESS', 'TACTACAN', '', '2025-05-02 02:36:23', '0'),
(38, 207752, 'DAYAO', 'BIENVENIDO', 'SANTIAGO', '', '2025-05-02 02:36:23', '0'),
(39, 252229, 'DE GUZMAN', 'MARC LOUISE', 'ADRINEDA', '', '2025-05-02 02:36:23', '0'),
(40, 800625, 'DE GUZMAN', 'FRANCES ANGELIQUE', 'EUGENIO', '', '2025-05-02 02:36:23', '0'),
(41, 897577, 'DE GUZMAN', 'MARIA LOURDES', 'ADRINEDA', '', '2025-05-02 02:36:23', '0'),
(42, 979781, 'DE GUZMAN', 'JOEL', 'EMPAYNADO', '', '2025-05-02 02:36:23', '0'),
(43, 170722, 'DE LEON', 'DEBBIE ANNE', 'COMA', '', '2025-05-02 02:36:23', '0'),
(44, 249874, 'DELA CRUZ', 'HONEY LEITH', 'ALVAREZ', '', '2025-05-02 02:36:23', '0'),
(45, 285419, 'DELA CRUZ', 'VIVENCIA', 'CASTILLO', '', '2025-05-02 02:36:23', '0'),
(46, 410918, 'DELA CRUZ', 'JOCELYN', 'MIRANDA', '', '2025-05-02 02:36:23', '0'),
(47, 958418, 'DELOS REYES', 'OSCAR', 'UERA', 'JR.', '2025-05-02 02:36:23', '0'),
(48, 817307, 'DUCUSIN', 'PAULO', 'FERRER', '', '2025-05-02 02:36:23', '0'),
(49, 288636, 'DUCUSIN', 'BERNAN ALEXIS', 'PALOMO', '', '2025-05-02 02:36:23', '0'),
(50, 921537, 'ENCOMIENDA', 'RYAN', 'ALMIROL', '', '2025-05-02 02:36:23', '0'),
(51, 784148, 'ESTACIO', 'IRENE', 'P', ' ', '2025-05-02 02:36:23', '0'),
(52, 831571, 'ESTEBAN', 'MA. CELINE ', 'LAGUIMUN', '', '2025-05-02 02:36:23', '0'),
(53, 492520, 'ESTEBAN', 'ZANDIE', 'VILLAGRACIA', '', '2025-05-02 02:36:23', '0'),
(54, 986598, 'FAGELA', 'MARK GEMMILL', 'MACABANGUN', '', '2025-05-02 02:36:23', '0'),
(55, 867124, 'FAJARDO', 'BIEN EXEQUIEL', 'AGUILAR', '', '2025-05-02 02:36:23', '0'),
(56, 0, 'FERMIN', 'EDNA', 'VILLAROMAN', '', '2025-05-02 02:36:23', '0'),
(57, 881731, 'FERRY', 'JASMIN', 'CRUZ', '', '2025-05-02 02:36:23', '0'),
(58, 333908, 'FLORES', 'MON KEVIN', 'RODRIGUEZ', '', '2025-05-02 02:36:23', '0'),
(59, 393709, 'FRANCISCO', 'EMILY', 'CAPUYON', '', '2025-05-02 02:36:23', '0'),
(60, 730691, 'GALANG', 'LANCER', 'ESMABE', '', '2025-05-02 02:36:23', '0'),
(61, 501518, 'GALDORES', 'JERICO', 'FULGUERAS', '', '2025-05-02 02:36:23', '0'),
(62, 578203, 'GAMIT', 'ORLANDO', 'BALLESTEROS', '', '2025-05-02 02:36:23', '0'),
(63, 559092, 'GARCIA', 'LAILA KRISCHELLE', 'URGENTE', '', '2025-05-02 02:36:23', '0'),
(64, 284449, 'GERONIMO', 'JOSEPH PAOLO', 'ABRATIGUE', '', '2025-05-02 02:36:23', '0'),
(65, 609250, 'GONZALES', 'PINKY', 'PEREZ', '', '2025-05-02 02:36:23', '0'),
(66, 889537, 'GROSPE', 'ROMMEL', 'TOQUERO', '', '2025-05-02 02:36:23', '0'),
(67, 915863, 'GUBA', 'TROY', 'URAGA', '', '2025-05-02 02:36:23', '0'),
(68, 940159, 'GUEVARRA', 'DARREN JOSHUA', 'LUSTRE', '', '2025-05-02 02:36:23', '0'),
(69, 156418, 'GUILLERMO', 'JESSICA ANNE', 'GARCIA', '', '2025-05-02 02:36:23', '0'),
(70, 977936, 'HERNANDEZ', 'SHERILL', 'DOROGA', '', '2025-05-02 02:36:23', '0'),
(71, 888651, 'HIPOLITO', 'ALVIN', 'VILLAJUAN', '', '2025-05-02 02:36:23', '0'),
(72, 269219, 'HIPOLITO', 'DAISYROSE', 'SORIANO', '', '2025-05-02 02:36:23', '0'),
(73, 637486, 'HIPOLITO', 'GLORIA LOREN', 'RIOROSO', '', '2025-05-02 02:36:23', '0'),
(74, 890613, 'HONORIO ', 'MERRY DAWN ', 'FRONDA', '', '2025-05-02 02:36:23', '0'),
(75, 290868, 'IGNACIO', 'ROSE ANN', 'SANGIL', '', '2025-05-02 02:36:23', '0'),
(76, 148826, 'JACINTO', 'AIME MARIE', 'NICOLAS', '', '2025-05-02 02:36:23', '0'),
(77, 398233, 'JOSON', 'JOHN CARLO', 'MANINGAS', '', '2025-05-02 02:36:23', '0'),
(78, 464803, 'JOSON', 'DANIEL', 'DELA CRUZ', '', '2025-05-02 02:36:23', '0'),
(79, 987654, 'JUAN', 'PHILIP', 'RIOS', '', '2025-05-02 02:36:23', '0'),
(80, 506073, 'LEGASPI', 'JET', 'VENTURINA', '', '2025-05-02 02:36:23', '0'),
(81, 805509, 'LISING', 'ALBERTO ', 'DELA CRUZ', '', '2025-05-02 02:36:23', '0'),
(82, 661369, 'LIWAG', 'RAQUEL', 'ESMABE', '', '2025-05-02 02:36:23', '0'),
(83, 268060, 'LIWAG', 'VERONICA ANN', 'COMA', '', '2025-05-02 02:36:23', '0'),
(84, 457369, 'LLAMAS', 'JAYSON', 'DOMINGO', '', '2025-05-02 02:36:23', '0'),
(85, 832208, 'LOPEZ', 'MA. ELIZABETH', 'NOCUM', '', '2025-05-02 02:36:23', '0'),
(86, 731802, 'LOPEZ', 'NUELZON', 'LABASAN', '', '2025-05-02 02:36:23', '0'),
(87, 552752, 'LORENZO', 'ROSEMARIE', 'PANGAN', '', '2025-05-02 02:36:23', '0'),
(88, 533109, 'MACABATA', 'RICA NICHOLE', 'TORRES', '', '2025-05-02 02:36:23', '0'),
(89, 477902, 'MACAPAGAL', 'WILFREDO', 'PASCUAL', 'JR.', '2025-05-02 02:36:23', '0'),
(90, 832805, 'MACAPAGAL', 'ARIEL', 'RAMIREZ', '', '2025-05-02 02:36:23', '0'),
(91, 190650, 'MACAPAGAL', 'MANILYN', 'BREIS', '', '2025-05-02 02:36:23', '0'),
(92, 383802, 'MACASAYA', 'FRANCIA', 'BUENDIA', '', '2025-05-02 02:36:23', '0'),
(93, 864059, 'MACASAYA', 'DANIEL', 'BUENDIA', '', '2025-05-02 02:36:23', '0'),
(94, 895171, 'MACTAL', 'CHRISTOPHER B.J. LEVI', 'VALDEZ', '', '2025-05-02 02:36:23', '0'),
(95, 240467, 'MALLARI', 'DONNA', 'DACANAY', '', '2025-05-02 02:36:23', '0'),
(96, 680891, 'MALLARI', 'ANGELICA', 'REYES', '', '2025-05-02 02:36:23', '0'),
(97, 644136, 'MANABAT', 'RICHARD', 'RAYMUNDO', '', '2025-05-02 02:36:23', '0'),
(98, 156379, 'MANAOIS', 'ALMA', 'DE LEON', '', '2025-05-02 02:36:23', '0'),
(99, 339003, 'MANGAHAS', 'JERICO LOUIS', 'CRUZ', '', '2025-05-02 02:36:23', '0'),
(100, 706187, 'MANIQUIZ', 'FERDIE', 'VALENZUELA', '', '2025-05-02 02:36:23', '0'),
(101, 519244, 'MANLAPIG', 'CHRISTIAN', 'SANDOVAL', '', '2025-05-02 02:36:23', '0'),
(102, 998987, 'MANUEL', 'ALVIN', 'DE LEON', '', '2025-05-02 02:36:23', '0'),
(103, 263618, 'MARIANO', 'RAPHAEL', 'VESTIDAS', '', '2025-05-02 02:36:23', '0'),
(104, 158835, 'MARQUEZ', 'MICHAEL', 'VILLAFLORES ', '', '2025-05-02 02:36:23', '0'),
(105, 127188, 'MARTINEZ', 'KARYL LANE', 'BONZO', '', '2025-05-02 02:36:23', '0'),
(106, 408115, 'MENDOZA', 'JOHN ARIEL ARISTOTLE', 'MARTIN', '', '2025-05-02 02:36:23', '0'),
(107, 580106, 'MENDOZA', 'NAPOLEON FERDINAND ', 'DELGADO', '', '2025-05-02 02:36:23', '0'),
(108, 781007, 'MENDOZA', 'PAUL KEVIN', 'GOYAL', '', '2025-05-02 02:36:23', '0'),
(109, 595357, 'MIRANDA', 'JANFERSON', 'MAURE', '', '2025-05-02 02:36:23', '0'),
(110, 516453, 'NAGAÑO', 'EMMANUEL', 'MAGNO', '', '2025-05-02 02:36:23', '0'),
(111, 548486, 'NANGEL', 'FREDERICK', 'SANGALANG', '', '2025-05-02 02:36:23', '0'),
(112, 732960, 'NARITO', 'JUNE', 'BINUYA', '', '2025-05-02 02:36:23', '0'),
(113, 836645, 'NAZAR', 'MELVIN ', 'GALVEZ ', '', '2025-05-02 02:36:23', '0'),
(114, 707158, 'NICOLAS', 'HEDGIE', 'LUTUACO', '', '2025-05-02 02:36:23', '0'),
(115, 154219, 'NOCUM', 'ELEONOR', 'ARENAS', '', '2025-05-02 02:36:23', '0'),
(116, 555124, 'NUPALIA', 'NOEL', 'BELTRAN', '', '2025-05-02 02:36:23', '0'),
(117, 729542, 'O\'CONNOR', 'EDUARDO', 'VERSOZA', '', '2025-05-02 02:36:23', '0'),
(118, 129437, 'OCAMPO', 'ALMA ', 'ALVAREZ', '', '2025-05-02 02:36:23', '0'),
(119, 903089, 'ONDRADE', 'CAMILLA MARIE', 'DIMALIWAT', '', '2025-05-02 02:36:23', '0'),
(120, 573917, 'ORENA', 'SHARON', 'PALOMAR', '', '2025-05-02 02:36:23', '0'),
(121, 749446, 'OROBIA', 'MARIBEL', 'DIONISIO', '', '2025-05-02 02:36:23', '0'),
(122, 256056, 'ORTIZ', 'BARRY JOSE', 'CAMACHO', 'III', '2025-05-02 02:36:23', '0'),
(123, 321110, 'PADOLINA', 'MARIA ISOBEL', 'FERMIN', '', '2025-05-02 02:36:23', '0'),
(124, 508425, 'PADUNAN', 'RODOLFO', 'CIPRIANJO', 'JR', '2025-05-02 02:36:23', '0'),
(125, 201374, 'PAJARILLAGA', 'MARK', 'VALDEZ', '', '2025-05-02 02:36:23', '0'),
(126, 100682, 'PALILIO', 'ROMEO', 'MANIQUIZ', '', '2025-05-02 02:36:23', '0'),
(127, 564594, 'PALLADO', 'RANDALL', 'VICTORIA ', '', '2025-05-02 02:36:23', '0'),
(128, 463292, 'PANGAN', 'VIRGILIO', 'JACINTO', '', '2025-05-02 02:36:23', '0'),
(129, 562171, 'PANGILINAN', 'JEFFREY', 'CUNANAN', '', '2025-05-02 02:36:23', '0'),
(130, 411863, 'PAPA', 'NOEL JEROME', 'PALAD', '', '2025-05-02 02:36:23', '0'),
(131, 536111, 'PASCUAL', 'ALGER', 'SANTIAGO', '', '2025-05-02 02:36:23', '0'),
(132, 361044, 'PASCUAL', 'NAPOLEON', 'ALFONSO', '', '2025-05-02 02:36:23', '0'),
(133, 114402, 'PASTRANA', 'RUBY ', 'FELIX ', '', '2025-05-02 02:36:23', '0'),
(134, 108341, 'PERALTA', 'SHIELA', 'DUNGAO', '', '2025-05-02 02:36:23', '0'),
(135, 102892, 'PEREZ', 'PATRICIA CARRIELIN', 'MARIANO', '', '2025-05-02 02:36:23', '0'),
(136, 234567, 'PERIA', 'MARK JOSEPH', 'DELOS SANTOS', '', '2025-05-02 02:36:23', '0'),
(137, 377264, 'PLACIDO', 'RAUL', 'DE GUZMAN', '', '2025-05-02 02:36:23', '0'),
(138, 307027, 'PLAZA', 'ROMEO EMERALD', 'GARCIA', '', '2025-05-02 02:36:23', '0'),
(139, 913515, 'PORTANA', 'NESTOR ', 'PASCUAL', 'JR.', '2025-05-02 02:36:23', '0'),
(140, 517043, 'PRIETO', 'RUSSELLENIE', 'DAMACIO', '', '2025-05-02 02:36:23', '0'),
(141, 313227, 'PURIFICACION', 'MARVIN', 'GARCIA', '', '2025-05-02 02:36:23', '0'),
(142, 548437, 'QUEJADA', 'ROSE', 'PASCUAL', '', '2025-05-02 02:36:23', '0'),
(143, 115386, 'QUINTAO', 'CATHERINE JOY', 'SANTIAGO', '', '2025-05-02 02:36:23', '0'),
(144, 962770, 'QUIZON', 'RODOLFO', 'BOTE', 'JR', '2025-05-02 02:36:23', '0'),
(145, 396602, 'RED', 'CHRISTIAN GYVER', 'BANIAGA', '', '2025-05-02 02:36:23', '0'),
(146, 424481, 'RELUSCO', 'ROCHELLE ANGELA', 'SABLAY', '', '2025-05-02 02:36:23', '0'),
(147, 691402, 'REYES', 'REGIE BOY', 'BENITO', '', '2025-05-02 02:36:23', '0'),
(148, 665009, 'REYES', 'REYNALDO', 'ORDONEZ', 'JR.', '2025-05-02 02:36:23', '0'),
(149, 104074, 'REYES', 'MARIA KRISTINA', 'CAYANGA', '', '2025-05-02 02:36:23', '0'),
(150, 386196, 'REYES', 'MICHAEL', 'SAULO', '', '2025-05-02 02:36:23', '0'),
(151, 353441, 'RONQUILLO', 'BENELYN', 'TIMBANG', '', '2025-05-02 02:36:23', '0'),
(152, 174096, 'RUBI', 'RODEL', 'DELA MERCED', '', '2025-05-02 02:36:23', '0'),
(153, 376614, 'RUFINO', 'NIMROD', 'JACINTO', '', '2025-05-02 02:36:23', '0'),
(154, 276120, 'SAN GABRIEL', 'ERICKSON ', 'DEL ROSARIO', '', '2025-05-02 02:36:23', '0'),
(155, 303126, 'SANTIAGO', 'RAE CARLO', 'BERNABE', '', '2025-05-02 02:36:23', '0'),
(156, 170456, 'SANTIAGO', 'MELVIN', 'MENDOZA', '', '2025-05-02 02:36:23', '0'),
(157, 911855, 'SANTIAGO', 'JING ALEXIS', 'VICENTE', '', '2025-05-02 02:36:23', '0'),
(158, 893880, 'SANTIAGO', 'DEBBIE DAWN', 'ARENAS', '', '2025-05-02 02:36:23', '0'),
(159, 130281, 'SANTOS', 'MICHAEL JOHN', 'DEL MUNDO', '', '2025-05-02 02:36:23', '0'),
(160, 249441, 'SANTOS', 'PINKY', 'BARBACENA', '', '2025-05-02 02:36:23', '0'),
(161, 261775, 'SANTOS', 'JEAN CAYLA', 'DOMINGO', '', '2025-05-02 02:36:23', '0'),
(162, 369176, 'SANTURAY', 'GARY LEO', 'DOMINGO', '', '2025-05-02 02:36:23', '0'),
(163, 579667, 'SAYSON', 'SAMUEL', 'TRANQUILINO', '', '2025-05-02 02:36:23', '0'),
(164, 932573, 'SISON', 'LILIBETH', 'MADRID', '', '2025-05-02 02:36:23', '0'),
(165, 907875, 'SOMBILLO', 'FILIPINA', 'BARTIDO', '', '2025-05-02 02:36:23', '0'),
(166, 190331, 'SOMERA', 'INOCENCIO', 'REYES', 'JR.', '2025-05-02 02:36:23', '0'),
(167, 220749, 'SORIANO', 'ELLEN JANE', 'SANTOS', '', '2025-05-02 02:36:23', '0'),
(168, 608389, 'SORIANO', 'MARLON', 'DELA CRUZ', '', '2025-05-02 02:36:23', '0'),
(169, 667663, 'SORIANO', 'ROSALINDA', 'ESPIRITU', '', '2025-05-02 02:36:23', '0'),
(170, 727940, 'SORIANO', 'KARYL MIKAELA', 'GOMEZ', '', '2025-05-02 02:36:23', '0'),
(171, 144784, 'TALPLACIDO', 'ARMANDO', 'ALFARO', '', '2025-05-02 02:36:23', '0'),
(172, 389637, 'TAN', 'JONALYN', 'BERMUDEZ', '', '2025-05-02 02:36:23', '0'),
(173, 879471, 'TEE', 'JANA', 'GUMISAD', '', '2025-05-02 02:36:23', '0'),
(174, 302215, 'TOLENTINO', 'KENNETH', 'MAGTALAS', '', '2025-05-02 02:36:23', '0'),
(175, 498561, 'TOMINEZ ', 'JAYSON', 'RAMILO', '', '2025-05-02 02:36:23', '0'),
(176, 660214, 'TORIBIO', 'JAN MICHAEL', 'REYES', '', '2025-05-02 02:36:23', '0'),
(177, 285583, 'TORRES', 'MICHAEL JEROME', 'TORRES', '', '2025-05-02 02:36:23', '0'),
(178, 741516, 'URGENTE', 'CHRISTIAN', 'FAUSTINO', '', '2025-05-02 02:36:23', '0'),
(179, 902843, 'VEGIGA', 'ROEL', 'LEAÑO', '', '2025-05-02 02:36:23', '0'),
(180, 265374, 'VERAYO', 'ROEL', 'BAGAN', '', '2025-05-02 02:36:23', '0'),
(181, 214908, 'VIADO', 'GERTRUDES', 'ALBAY', '', '2025-05-02 02:36:23', '0'),
(182, 813438, 'VIBAL', 'VICENTE', 'ESTEBAN', '', '2025-05-02 02:36:23', '0'),
(183, 221985, 'VILLAFLOR', 'LEIFE', 'BARTIDO', '', '2025-05-02 02:36:23', '0'),
(184, 304314, 'VILLANUEVA', 'MICOLE BRYLLE ', 'LAJOM', '', '2025-05-02 02:36:23', '0'),
(185, 651936, 'VILLANUEVA', 'MARIO ', 'DATOR', 'JR', '2025-05-02 02:36:23', '0'),
(186, 935640, 'VILLANUEVA', 'MARK IAN', 'DATOR', '', '2025-05-02 02:36:23', '0'),
(187, 955750, 'YANGO', 'AIRA CELINE', 'BUENCAMINO', '', '2025-05-02 02:36:23', '0');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `location_type_id` int(11) DEFAULT NULL,
  `parent_location_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`location_id`, `location_name`, `location_type_id`, `parent_location_id`, `created_at`, `is_deleted`) VALUES
(3, 'Office of the Department Manager', 1, NULL, '2025-02-16 08:02:03', '0'),
(4, 'ICT Unit', 4, 7, '2025-02-16 08:02:03', '0'),
(5, 'Public Relation Office Unit', 4, 3, '2025-02-16 08:02:03', '0'),
(6, 'Legal Services', 4, 8, '2025-02-16 08:02:03', '0'),
(7, 'Office of the EOD Manager', 2, NULL, '2025-02-16 08:02:03', '0'),
(8, 'Office of the ADFIN Manager', 2, NULL, '2025-02-16 08:02:03', '0'),
(9, 'Administrative Section', 3, 24, '2025-02-16 08:02:03', '0'),
(10, 'Finance Section', 3, 24, '2025-02-16 08:02:03', '0'),
(11, 'Property Unit', 4, 9, '2025-02-16 08:02:03', '0'),
(12, 'General Services Security Unit ', 4, 9, '2025-02-16 08:02:03', '0'),
(13, 'Pantabangan Lake Resort and Hotel', 4, 9, '2025-02-16 08:02:03', '0'),
(14, 'Medical Services Unit', 4, 9, '2025-02-16 08:02:03', '0'),
(15, 'Cashiering Unit', 4, 10, '2025-02-16 08:02:03', '0'),
(16, 'Fisa Unit', 4, 10, '2025-02-16 08:02:03', '0'),
(18, 'Engineering Section', 3, 23, '2025-02-16 08:02:03', '0'),
(19, 'Operation Section', 3, 23, '2025-02-16 08:02:03', '0'),
(20, 'Equipment Management Section', 3, 23, '2025-02-16 08:02:03', '0'),
(21, 'Institutional Development Section', 3, 23, '2025-02-16 08:02:03', '0'),
(22, 'BAC Unit', 4, 3, '2025-02-18 07:24:43', '0'),
(23, 'Engineering and Operation Division', 2, NULL, '2025-03-03 06:38:42', '0'),
(24, 'Administrative and Finance Division', 2, NULL, '2025-03-03 06:44:12', '0'),
(25, 'Personnel and Records Unit', 4, 9, '2025-03-03 08:28:04', '0'),
(26, 'DM Secretary', 4, 3, '2025-03-03 08:30:12', '0'),
(27, 'DM Secretary', 4, 7, '2025-03-03 08:30:12', '0'),
(28, 'DM Secretary', 4, 8, '2025-03-03 08:30:12', '0');

-- --------------------------------------------------------

--
-- Table structure for table `location_type`
--

CREATE TABLE `location_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location_type`
--

INSERT INTO `location_type` (`id`, `name`) VALUES
(1, 'Department'),
(2, 'Division'),
(3, 'Section'),
(4, 'Unit');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_activity`
--

CREATE TABLE `maintenance_activity` (
  `id` int(11) NOT NULL,
  `other_status` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `service_status_id` int(11) DEFAULT NULL,
  `personnel_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_activity`
--

INSERT INTO `maintenance_activity` (`id`, `other_status`, `remarks`, `service_status_id`, `personnel_id`, `request_id`, `created_at`) VALUES
(101, NULL, NULL, 1, 2, 1, '2025-04-03 02:47:03'),
(102, NULL, 'asjdjasj asdashas dadaksdhas djnwfjioqwen fwen fwqef nweoq fnqewo pfjqewf oqjw ofjwneq fiojwf ioqj fioqejf ewoi jfqewoif jqweifojweqfo wjeqfpjeqw iewofjqwoepf jweqifo jqwef pewqf jqweiofjqwepof jqwie ofjqweop jwef iopqwje foqi', 2, 2, 2, '2025-04-03 02:47:03'),
(103, NULL, NULL, 1, 2, 3, '2025-04-03 02:47:03'),
(104, NULL, NULL, 3, 2, 4, '2025-04-03 02:47:03'),
(105, NULL, NULL, 2, 2, 5, '2025-04-03 02:47:03'),
(106, NULL, NULL, 1, 2, 6, '2025-04-03 02:47:03'),
(107, NULL, NULL, 3, 2, 7, '2025-04-03 02:47:03'),
(108, NULL, NULL, 2, 2, 8, '2025-04-03 02:47:03'),
(109, NULL, NULL, 1, 2, 9, '2025-04-03 02:47:03'),
(110, NULL, NULL, 2, 2, 10, '2025-04-03 02:47:03'),
(111, NULL, NULL, 1, 2, 11, '2025-04-03 02:47:03'),
(112, NULL, NULL, 3, 2, 12, '2025-04-03 02:47:03'),
(113, NULL, NULL, 2, 2, 13, '2025-04-03 02:47:03'),
(114, NULL, NULL, 1, 2, 14, '2025-04-03 02:47:03'),
(115, NULL, NULL, 2, 2, 15, '2025-04-03 02:47:03'),
(116, NULL, NULL, 1, 2, 16, '2025-04-03 02:47:03'),
(117, NULL, NULL, 3, 2, 17, '2025-04-03 02:47:03'),
(118, NULL, NULL, 2, 2, 18, '2025-04-03 02:47:03'),
(119, NULL, NULL, 1, 2, 19, '2025-04-03 02:47:03'),
(120, NULL, NULL, 2, 2, 20, '2025-04-03 02:47:03'),
(121, NULL, NULL, 1, 2, 21, '2025-04-03 02:47:03'),
(122, NULL, NULL, 3, 2, 22, '2025-04-03 02:47:03'),
(123, NULL, NULL, 2, 2, 23, '2025-04-03 02:47:03'),
(124, NULL, NULL, 1, 2, 24, '2025-04-03 02:47:03'),
(125, NULL, NULL, 2, 2, 25, '2025-04-03 02:47:03'),
(126, NULL, NULL, 1, 2, 26, '2025-04-03 02:47:03'),
(127, NULL, NULL, 3, 2, 27, '2025-04-03 02:47:03'),
(128, NULL, NULL, 2, 2, 28, '2025-04-03 02:47:03'),
(129, NULL, NULL, 1, 2, 29, '2025-04-03 02:47:03'),
(130, NULL, NULL, 2, 2, 30, '2025-04-03 02:47:03'),
(131, NULL, NULL, 1, 2, 31, '2025-04-03 02:47:03'),
(132, NULL, NULL, 3, 2, 32, '2025-04-03 02:47:03'),
(133, NULL, NULL, 2, 2, 33, '2025-04-03 02:47:03'),
(134, NULL, NULL, 1, 2, 34, '2025-04-03 02:47:03'),
(135, NULL, NULL, 2, 2, 35, '2025-04-03 02:47:03'),
(136, NULL, NULL, 1, 2, 36, '2025-04-03 02:47:03'),
(137, NULL, NULL, 3, 2, 37, '2025-04-03 02:47:03'),
(138, NULL, NULL, 2, 2, 38, '2025-04-03 02:47:03'),
(139, NULL, NULL, 1, 2, 39, '2025-04-03 02:47:03'),
(140, NULL, NULL, 2, 2, 40, '2025-04-03 02:47:03'),
(141, NULL, NULL, 1, 2, 41, '2025-04-03 02:47:03'),
(142, NULL, NULL, 3, 2, 42, '2025-04-03 02:47:03'),
(143, NULL, NULL, 2, 2, 43, '2025-04-03 02:47:03'),
(144, NULL, NULL, 1, 2, 44, '2025-04-03 02:47:03'),
(145, NULL, NULL, 2, 2, 45, '2025-04-03 02:47:03'),
(146, NULL, NULL, 1, 2, 46, '2025-04-03 02:47:03'),
(147, NULL, NULL, 3, 2, 47, '2025-04-03 02:47:03'),
(148, NULL, NULL, 2, 2, 48, '2025-04-03 02:47:03'),
(149, NULL, NULL, 1, 2, 49, '2025-04-03 02:47:03'),
(150, NULL, NULL, 2, 2, 50, '2025-04-03 02:47:03');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_personnel`
--

CREATE TABLE `maintenance_personnel` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_personnel`
--

INSERT INTO `maintenance_personnel` (`id`, `first_name`, `middle_name`, `last_name`, `is_deleted`) VALUES
(2, 'Mark Ian', '', 'Villanueva', '0'),
(17, 'test', 'test', 'test', '1'),
(18, 'a', '', 'sad', '1'),
(19, 'asdasd', 'asd2as', 'dasdasd', '1');

-- --------------------------------------------------------

--
-- Table structure for table `new_employee`
--

CREATE TABLE `new_employee` (
  `id` int(11) NOT NULL,
  `gov_id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `ext` varchar(255) NOT NULL,
  `status` enum('accepted','rejected','pending') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `status` enum('request','pending','completed','reject') DEFAULT 'request',
  `sub_category_id` int(11) DEFAULT NULL,
  `other_category` varchar(255) DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `ref_no`, `status`, `sub_category_id`, `other_category`, `location_id`, `emp_id`, `emp_name`, `created_at`) VALUES
(1, '2025-010001', 'completed', 1, NULL, 24, 206693, 'Juan DELACRUZ', '2025-04-03 02:45:44'),
(2, '2025-010002', 'completed', 2, NULL, 11, 206694, 'Maria SANTOS', '2025-04-05 02:45:44'),
(3, '2025-010003', 'completed', 3, NULL, 22, 206695, 'Jose RAMOS', '2025-04-03 02:45:44'),
(4, '2025-010001', 'completed', 4, NULL, 3, 206696, 'Ana REYES', '2025-04-02 02:45:44'),
(5, '2025-010001', 'completed', 19, NULL, 4, 206697, 'Carlos MENDOZA', '2025-04-03 02:45:44'),
(6, '2025-010001', 'completed', 4, NULL, 3, 206698, 'Elena GOMEZ', '2025-04-03 02:45:44'),
(7, '2025-010001', 'completed', 12, NULL, 3, 206699, 'Luis TORRES', '2025-04-03 02:45:44'),
(8, '2025-010001', 'completed', 3, NULL, 3, 206700, 'Rosa FERNANDEZ', '2025-04-03 02:45:44'),
(9, '2025-010001', 'completed', 4, NULL, 3, 206701, 'Pedro CRUZ', '2025-04-03 02:45:44'),
(10, '2025-010001', 'completed', 20, NULL, 3, 206702, 'Carmen VILLANUEVA', '2025-04-03 02:45:44'),
(11, '2025-010001', 'completed', 1, NULL, 3, 206703, 'Fernando LOPEZ', '2025-04-03 02:45:44'),
(12, '2025-010001', 'completed', 2, NULL, 3, 206704, 'Teresa DOMINGUEZ', '2025-04-03 02:45:44'),
(13, '2025-010001', 'completed', 3, NULL, 3, 206705, 'Raul HERRERA', '2025-04-03 02:45:44'),
(14, '2025-010001', 'completed', 4, NULL, 3, 206706, 'Gloria PEREZ', '2025-04-03 02:45:44'),
(15, '2025-010001', 'completed', 10, NULL, 3, 206707, 'Diego MARTINEZ', '2025-04-03 02:45:44'),
(16, '2025-010001', 'completed', 1, NULL, 3, 206708, 'Isabel RIVERA', '2025-04-03 02:45:44'),
(17, '2025-010001', 'completed', 2, NULL, 3, 206709, 'Victor CASTILLO', '2025-04-03 02:45:44'),
(18, '2025-010001', 'completed', 3, NULL, 3, 206710, 'Silvia ORTEGA', '2025-04-03 02:45:44'),
(19, '2025-010001', 'completed', 4, NULL, 3, 206711, 'Javier RODRIGUEZ', '2025-04-03 02:45:44'),
(20, '2025-010001', 'completed', 5, NULL, 3, 206712, 'Maricel NAVARRO', '2025-04-03 02:45:44'),
(21, '2025-010001', 'completed', 1, NULL, 3, 206713, 'Antonio FUENTES', '2025-04-03 02:45:44'),
(22, '2025-010001', 'completed', 2, NULL, 3, 206714, 'Patricia MENDEZ', '2025-04-03 02:45:44'),
(23, '2025-010001', 'completed', 3, NULL, 3, 206715, 'Hector VEGA', '2025-04-03 02:45:44'),
(24, '2025-010001', 'completed', 4, NULL, 3, 206716, 'Nora SANTIAGO', '2025-04-03 02:45:44'),
(25, '2025-010001', 'completed', 15, NULL, 3, 206717, 'Oscar BAUTISTA', '2025-04-03 02:45:44'),
(26, '2025-010001', 'completed', 1, NULL, 3, 206718, 'Beatriz JIMENEZ', '2025-04-03 02:45:44'),
(27, '2025-010001', 'completed', 2, NULL, 3, 206719, 'Edgardo DIAZ', '2025-04-03 02:45:44'),
(28, '2025-010001', 'completed', 3, NULL, 3, 206720, 'Lucia RAMIREZ', '2025-04-03 02:45:44'),
(29, '2025-010001', 'completed', 4, NULL, 3, 206721, 'Ramon VALENCIA', '2025-04-03 02:45:44'),
(30, '2025-010001', 'completed', 5, NULL, 3, 206722, 'Consuelo VASQUEZ', '2025-04-03 02:45:44'),
(31, '2025-010001', 'completed', 1, NULL, 3, 206723, 'Eduardo SALAZAR', '2025-04-03 02:45:44'),
(32, '2025-010001', 'completed', 2, NULL, 3, 206724, 'Estrella CRISTOBAL', '2025-04-03 02:45:44'),
(33, '2025-010001', 'completed', 3, NULL, 3, 206725, 'Gabriel SORIANO', '2025-04-03 02:45:44'),
(34, '2025-010001', 'completed', 4, NULL, 3, 206726, 'Miranda CORDOVA', '2025-04-03 02:45:44'),
(35, '2025-010001', 'completed', 5, NULL, 3, 206727, 'Ricardo LIM', '2025-04-03 02:45:44'),
(36, '2025-010001', 'completed', 1, NULL, 3, 206728, 'Jocelyn REYES', '2025-04-03 02:45:44'),
(37, '2025-010001', 'completed', 2, NULL, 3, 206729, 'Benedicto TAN', '2025-04-03 02:45:44'),
(38, '2025-010001', 'completed', 3, NULL, 3, 206730, 'Zenaida MALABANAN', '2025-04-03 02:45:44'),
(39, '2025-010001', 'completed', 4, NULL, 3, 206731, 'Nelson CASTRO', '2025-04-03 02:45:44'),
(40, '2025-010001', 'completed', 5, NULL, 3, 206732, 'Melinda JAVIER', '2025-04-03 02:45:44'),
(41, '2025-010001', 'completed', 1, NULL, 3, 206733, 'Dante BALTAZAR', '2025-04-03 02:45:44'),
(42, '2025-010001', 'completed', 2, NULL, 3, 206734, 'Virginia MONTANO', '2025-04-03 02:45:44'),
(43, '2025-010001', 'completed', 3, NULL, 3, 206735, 'Leonardo AGUILAR', '2025-04-03 02:45:44'),
(44, '2025-010001', 'completed', 4, NULL, 3, 206736, 'Alicia SANDOVAL', '2025-04-03 02:45:44'),
(45, '2025-010001', 'completed', 5, NULL, 3, 206737, 'Samuel BERNAL', '2025-04-03 02:45:44'),
(46, '2025-010001', 'completed', 1, NULL, 3, 206738, 'Marina CANLAS', '2025-04-03 02:45:44'),
(47, '2025-010001', 'completed', 2, NULL, 3, 206739, 'Francisco ABAD', '2025-04-03 02:45:44'),
(48, '2025-010001', 'completed', 3, NULL, 3, 206740, 'Lourdes MANALO', '2025-04-03 02:45:44'),
(49, '2025-010001', 'completed', 4, NULL, 3, 206741, 'Julio PEREZ', '2025-04-03 02:45:44'),
(50, '2025-0100050', 'completed', 5, NULL, 3, 206742, 'Carolina MORALES', '2025-04-03 02:45:44'),
(51, '2025-010001', 'completed', 1, NULL, 3, 206693, 'Juan DELACRUZ', '2025-04-03 02:45:58'),
(52, '2025-010001', 'completed', 2, NULL, 3, 206694, 'Maria SANTOS', '2025-04-03 02:45:58'),
(53, '2025-010001', 'completed', 3, NULL, 3, 206695, 'Jose RAMOS', '2025-04-03 02:45:58'),
(54, '2025-010001', 'completed', 4, NULL, 3, 206696, 'Ana REYES', '2025-04-03 02:45:58'),
(55, '2025-010001', 'completed', 5, NULL, 3, 206697, 'Carlos MENDOZA', '2025-04-03 02:45:58'),
(56, '2025-010001', 'completed', 1, NULL, 3, 206698, 'Elena GOMEZ', '2025-04-03 02:45:58'),
(57, '2025-010001', 'completed', 2, NULL, 3, 206699, 'Luis TORRES', '2025-04-03 02:45:58'),
(58, '2025-010001', 'completed', 3, NULL, 3, 206700, 'Rosa FERNANDEZ', '2025-04-03 02:45:58'),
(59, '2025-010001', 'completed', 4, NULL, 3, 206701, 'Pedro CRUZ', '2025-04-03 02:45:58'),
(60, '2025-010001', 'completed', 5, NULL, 3, 206702, 'Carmen VILLANUEVA', '2025-04-03 02:45:58'),
(61, '2025-010001', 'completed', 1, NULL, 3, 206703, 'Fernando LOPEZ', '2025-04-03 02:45:58'),
(62, '2025-010001', 'completed', 2, NULL, 3, 206704, 'Teresa DOMINGUEZ', '2025-04-03 02:45:58'),
(63, '2025-010001', 'completed', 3, NULL, 3, 206705, 'Raul HERRERA', '2025-04-03 02:45:58'),
(64, '2025-010001', 'completed', 4, NULL, 3, 206706, 'Gloria PEREZ', '2025-04-03 02:45:58'),
(65, '2025-010001', 'completed', 5, NULL, 3, 206707, 'Diego MARTINEZ', '2025-04-03 02:45:58'),
(66, '2025-010001', 'completed', 1, NULL, 3, 206708, 'Isabel RIVERA', '2025-04-03 02:45:58'),
(67, '2025-010001', 'completed', 2, NULL, 3, 206709, 'Victor CASTILLO', '2025-04-03 02:45:58'),
(68, '2025-010001', 'completed', 3, NULL, 3, 206710, 'Silvia ORTEGA', '2025-04-03 02:45:58'),
(69, '2025-010001', 'completed', 4, NULL, 3, 206711, 'Javier RODRIGUEZ', '2025-04-03 02:45:58'),
(70, '2025-010001', 'completed', 5, NULL, 3, 206712, 'Maricel NAVARRO', '2025-04-03 02:45:58'),
(71, '2025-010001', 'completed', 1, NULL, 3, 206713, 'Antonio FUENTES', '2025-04-03 02:45:58'),
(72, '2025-010001', 'completed', 2, NULL, 3, 206714, 'Patricia MENDEZ', '2025-04-03 02:45:58'),
(73, '2025-010001', 'completed', 3, NULL, 3, 206715, 'Hector VEGA', '2025-04-03 02:45:58'),
(74, '2025-010001', 'completed', 4, NULL, 3, 206716, 'Nora SANTIAGO', '2025-04-03 02:45:58'),
(75, '2025-010001', 'completed', 5, NULL, 3, 206717, 'Oscar BAUTISTA', '2025-04-03 02:45:58'),
(76, '2025-010001', 'completed', 1, NULL, 3, 206718, 'Beatriz JIMENEZ', '2025-04-03 02:45:58'),
(77, '2025-010001', 'completed', 2, NULL, 3, 206719, 'Edgardo DIAZ', '2025-04-03 02:45:58'),
(78, '2025-010001', 'completed', 3, NULL, 3, 206720, 'Lucia RAMIREZ', '2025-04-03 02:45:58'),
(79, '2025-010001', 'completed', 4, NULL, 3, 206721, 'Ramon VALENCIA', '2025-04-03 02:45:58'),
(80, '2025-010001', 'completed', 5, NULL, 3, 206722, 'Consuelo VASQUEZ', '2025-04-03 02:45:58'),
(81, '2025-010001', 'completed', 1, NULL, 3, 206723, 'Eduardo SALAZAR', '2025-04-03 02:45:58'),
(82, '2025-010001', 'completed', 2, NULL, 3, 206724, 'Estrella CRISTOBAL', '2025-04-03 02:45:58'),
(83, '2025-010001', 'completed', 3, NULL, 3, 206725, 'Gabriel SORIANO', '2025-04-03 02:45:58'),
(84, '2025-010001', 'completed', 4, NULL, 3, 206726, 'Miranda CORDOVA', '2025-04-03 02:45:58'),
(85, '2025-010001', 'completed', 5, NULL, 3, 206727, 'Ricardo LIM', '2025-04-03 02:45:58'),
(86, '2025-010001', 'completed', 1, NULL, 3, 206728, 'Jocelyn REYES', '2025-04-03 02:45:58'),
(87, '2025-010001', 'completed', 2, NULL, 3, 206729, 'Benedicto TAN', '2025-04-03 02:45:58'),
(88, '2025-010001', 'completed', 3, NULL, 3, 206730, 'Zenaida MALABANAN', '2025-04-03 02:45:58'),
(89, '2025-010001', 'completed', 4, NULL, 3, 206731, 'Nelson CASTRO', '2025-04-03 02:45:58'),
(90, '2025-010001', 'completed', 5, NULL, 3, 206732, 'Melinda JAVIER', '2025-04-03 02:45:58'),
(91, '2025-010001', 'completed', 1, NULL, 3, 206733, 'Dante BALTAZAR', '2025-04-03 02:45:58'),
(92, '2025-010001', 'completed', 2, NULL, 3, 206734, 'Virginia MONTANO', '2025-04-03 02:45:58'),
(93, '2025-010001', 'completed', 3, NULL, 3, 206735, 'Leonardo AGUILAR', '2025-04-03 02:45:58'),
(94, '2025-010001', 'completed', 4, NULL, 3, 206736, 'Alicia SANDOVAL', '2025-04-03 02:45:58'),
(95, '2025-010001', 'completed', 5, NULL, 3, 206737, 'Samuel BERNAL', '2025-04-03 02:45:58'),
(96, '2025-010001', 'completed', 1, NULL, 3, 206738, 'Marina CANLAS', '2025-04-03 02:45:58'),
(97, '2025-010001', 'completed', 2, NULL, 3, 206739, 'Francisco ABAD', '2025-04-03 02:45:58'),
(98, '2025-010001', 'completed', 3, NULL, 3, 206740, 'Lourdes MANALO', '2025-04-03 02:45:58'),
(99, '2025-010001', 'completed', 4, NULL, 3, 206741, 'Julio PEREZ', '2025-04-03 02:45:58'),
(100, '2025-010001', 'completed', 5, NULL, 3, 206742, 'Carolina MORALES', '2025-04-03 02:45:58');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `name`, `created_at`, `is_deleted`) VALUES
(1, 'Hardware Issue', '2025-02-25 16:39:38', '0'),
(2, 'Software Issue', '2025-02-25 16:39:38', '0'),
(3, 'Network and Connectivity', '2025-02-25 16:39:38', '0'),
(4, 'System Account and Access Management', '2025-02-25 16:39:38', '0'),
(5, 'Additional ICT Services', '2025-02-25 16:39:38', '0');

-- --------------------------------------------------------

--
-- Table structure for table `service_status`
--

CREATE TABLE `service_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_status`
--

INSERT INTO `service_status` (`id`, `name`) VALUES
(1, 'Resolved/Completed'),
(2, 'For Outsource Service'),
(3, 'For Parts Replacement');

-- --------------------------------------------------------

--
-- Table structure for table `sub_service`
--

CREATE TABLE `sub_service` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_service`
--

INSERT INTO `sub_service` (`id`, `name`, `service_id`, `created_at`, `is_deleted`) VALUES
(1, 'Computer/Desktop/Laptop Issues', 1, '2025-02-25 16:40:43', '0'),
(2, 'Printer/Scanner Problems', 1, '2025-02-25 16:40:43', '0'),
(3, 'Peripheral Devices (Mouse, Keyboard, Monitor)', 1, '2025-02-25 16:40:43', '0'),
(4, 'Network Equipment(Routers, Switches, Access Points)', 1, '2025-02-25 16:40:43', '0'),
(5, 'Others Hardware Repair and Troubleshooting', 1, '2025-02-25 16:40:43', '0'),
(6, 'Application erros', 2, '2025-02-25 16:41:24', '0'),
(7, 'License and Activation Issues', 2, '2025-02-25 16:41:24', '0'),
(8, 'Installation of New Software', 2, '2025-02-25 16:41:24', '0'),
(9, 'Virus Scanning and Removal', 2, '2025-02-25 16:41:24', '0'),
(10, 'File Backup Assistance', 2, '2025-02-25 16:41:24', '0'),
(11, 'Internet Connectivity Issues', 3, '2025-02-25 16:41:35', '0'),
(12, 'Slow Network Speed', 3, '2025-02-25 16:41:35', '0'),
(13, 'Records Management System Issue', 4, '2025-02-25 16:42:02', '0'),
(14, 'Online Leave Application System Issue', 4, '2025-02-25 16:42:02', '0'),
(15, 'Document Tracking System Issue', 4, '2025-02-25 16:42:02', '0'),
(16, 'Other Information System Issue', 4, '2025-02-25 16:42:02', '0'),
(17, 'LED Wall and Sound System Assistance', 5, '2025-02-25 16:42:40', '0'),
(18, 'Virtual Meeting Assistance', 5, '2025-02-25 16:42:40', '0'),
(19, 'CCTV Maintenance', 5, '2025-02-25 16:42:40', '0'),
(20, 'ID Printing Request', 5, '2025-02-25 16:42:40', '0'),
(29, 'testr2', 4, '2025-04-29 03:06:15', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_account`
--
ALTER TABLE `admin_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `parent_location_id` (`parent_location_id`),
  ADD KEY `location_location_type_id` (`location_type_id`);

--
-- Indexes for table `location_type`
--
ALTER TABLE `location_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_activity`
--
ALTER TABLE `maintenance_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_activity_service_status_fk` (`service_status_id`),
  ADD KEY `maintenance_activity_personnel_fk` (`personnel_id`),
  ADD KEY `maintenance_activity_request_fk` (`request_id`);

--
-- Indexes for table `maintenance_personnel`
--
ALTER TABLE `maintenance_personnel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_employee`
--
ALTER TABLE `new_employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_sub_category_fk` (`sub_category_id`),
  ADD KEY `request_location_fk` (`location_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_status`
--
ALTER TABLE `service_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_service`
--
ALTER TABLE `sub_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_category_category_fk` (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_account`
--
ALTER TABLE `admin_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `location_type`
--
ALTER TABLE `location_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `maintenance_activity`
--
ALTER TABLE `maintenance_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `maintenance_personnel`
--
ALTER TABLE `maintenance_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `new_employee`
--
ALTER TABLE `new_employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_status`
--
ALTER TABLE `service_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sub_service`
--
ALTER TABLE `sub_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_location_type_id` FOREIGN KEY (`location_type_id`) REFERENCES `location_type` (`id`),
  ADD CONSTRAINT `parent_location_id` FOREIGN KEY (`parent_location_id`) REFERENCES `location` (`location_id`);

--
-- Constraints for table `maintenance_activity`
--
ALTER TABLE `maintenance_activity`
  ADD CONSTRAINT `maintenance_activity_personnel_fk` FOREIGN KEY (`personnel_id`) REFERENCES `maintenance_personnel` (`id`),
  ADD CONSTRAINT `maintenance_activity_request_fk` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `maintenance_activity_service_status_fk` FOREIGN KEY (`service_status_id`) REFERENCES `service_status` (`id`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_location_fk` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`),
  ADD CONSTRAINT `request_sub_category_fk` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_service` (`id`);

--
-- Constraints for table `sub_service`
--
ALTER TABLE `sub_service`
  ADD CONSTRAINT `sub_category_category_fk` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
