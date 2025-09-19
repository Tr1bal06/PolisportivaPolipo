-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Creato il: Set 19, 2025 alle 17:23
-- Versione del server: 8.0.43
-- Versione PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_uda5idpolisportiva`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ALLENAMENTO`
--

CREATE TABLE `ALLENAMENTO` (
  `CodiceAttivita` int NOT NULL,
  `Tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ALLENAMENTO`
--

INSERT INTO `ALLENAMENTO` (`CodiceAttivita`, `Tipo`) VALUES
(2, 'normale'),
(3, 'normale'),
(4, 'asd'),
(5, 'asd'),
(6, 'asd'),
(7, 'normale'),
(14, 'ads'),
(15, 'ads'),
(16, 'ads'),
(17, 'ad'),
(18, 'normale'),
(36, 'normale'),
(37, 'normale'),
(38, 'normale'),
(39, 'normale'),
(43, 'normale'),
(44, 'normale'),
(45, 'normale'),
(46, 'normale'),
(52, 'normale'),
(62, 'normale'),
(63, 'normale'),
(64, 'normale'),
(65, 'normale'),
(66, 'prova'),
(74, 'prova prova'),
(92, 'normale'),
(93, 'Fabio'),
(95, 'Prova'),
(96, 'Teobaldo interisat'),
(121, 'normale'),
(122, 'normale'),
(124, 'asfasdfasdfasdf'),
(125, '234234234fasdfafsd'),
(126, 'fsdfsdf'),
(127, 'sfdsfsd'),
(128, 'fsfasf'),
(129, 'sfsfsdf'),
(130, 'fsdfsdfsd'),
(132, 'fasdfasdfasf'),
(133, 'asdfasdf'),
(134, 'asfasfasdfafasf'),
(137, 'sfsdfsdfsf'),
(138, 'sdfdsfs'),
(140, 'fdasdfasfas'),
(142, 'safsadfasfs'),
(145, 'normale'),
(146, 'normale'),
(147, 'normale'),
(150, 'ahshjsdafasf'),
(152, 'gdjsje'),
(154, 'normale'),
(155, 'rfgdsfgdsfg'),
(162, 'fsdfsdfasdfs'),
(166, 'sdfsdfsd'),
(167, 'dsfsdfa'),
(168, 'sfsf'),
(169, 'asfasdfasf'),
(170, 'afasfasf'),
(171, 'dadsfsdf'),
(172, 'rgaertteagsdg'),
(173, 'zcvzxvzxcv'),
(174, 'normale');

-- --------------------------------------------------------

--
-- Struttura della tabella `ALLENATORE`
--

CREATE TABLE `ALLENATORE` (
  `Codice` int NOT NULL,
  `CodiceConvocatore` int NOT NULL,
  `CodicePrenotante` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ALLENATORE`
--

INSERT INTO `ALLENATORE` (`Codice`, `CodiceConvocatore`, `CodicePrenotante`) VALUES
(74, 11, 1),
(80, 12, 3),
(85, 14, 5),
(92, 20, 11),
(95, 23, 12),
(96, 24, 13),
(99, 26, 15),
(102, 29, 18),
(112, 34, 22),
(120, 42, 30),
(126, 43, 31);

-- --------------------------------------------------------

--
-- Struttura della tabella `ALTRO_PERSONALE`
--

CREATE TABLE `ALTRO_PERSONALE` (
  `Codice` int NOT NULL,
  `TipoCarica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ALTRO_PERSONALE`
--

INSERT INTO `ALTRO_PERSONALE` (`Codice`, `TipoCarica`) VALUES
(105, 'Idropulitrice'),
(119, 'donna delle pulizie'),
(122, 'segretaria');

-- --------------------------------------------------------

--
-- Struttura della tabella `ASSEMBLEA`
--

CREATE TABLE `ASSEMBLEA` (
  `CodiceConvocatore` int NOT NULL,
  `Data` date NOT NULL,
  `OrdineDelGiorno` varchar(255) DEFAULT NULL,
  `Oggetto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ASSEMBLEA`
--

INSERT INTO `ASSEMBLEA` (`CodiceConvocatore`, `Data`, `OrdineDelGiorno`, `Oggetto`) VALUES
(0, '2025-05-12', '131231313123', '123123312231'),
(0, '2025-05-17', 'Interista', 'Teobaldo'),
(0, '2025-05-22', 'Interista', 'Teobaldo'),
(14, '2025-05-03', 'proviamooo', 'prova1'),
(20, '2025-05-09', 'xcvxcv', 'cxzvcdvxc'),
(20, '2025-05-14', 'sdfsdf', 'sfsdf'),
(20, '2025-05-15', 'prova', 'prova'),
(20, '2025-05-16', 'Interista', 'Teobaldo'),
(20, '2025-05-20', 'ITALIA ', 'prova'),
(20, '2025-05-21', 'sadfasdf', 'teobaldo interista'),
(20, '2025-05-22', '2', 'teobaldo interista'),
(20, '2025-05-23', 'asdfsdfsdf', 'prova'),
(20, '2025-05-24', 'sdfsd', 'dsfdsdf'),
(20, '2025-05-26', 'Inter forza', 'teobaldo interista'),
(20, '2025-05-27', 'ITALIA ', 'prova'),
(20, '2025-05-30', 'sdfsdfsdf', 'teobaldo interista'),
(20, '2025-05-31', 'qeqeqeqe', 'qeqeqeqe'),
(20, '2025-09-15', 'prova', 'sadfasdfs'),
(20, '2025-09-17', 'prova', 'sadfasdfs'),
(20, '2025-09-19', 'sdfsdf', 'sdfsdfsd'),
(20, '2025-09-24', 'asdfasdf', 'sdfsdfasdf'),
(20, '2025-09-25', 'prova', 'cxzvcdvxc'),
(20, '2025-09-26', 'zbzcvb', 'dfgdsgb'),
(20, '2025-09-29', 'aafsdsdaf', 'fd'),
(20, '2025-09-30', 'ASDFASFS', 'ASDFASFASFASFASF'),
(20, '2025-10-03', 'sonasfpijbas', 'aloi hgfasdh oubgaho svb'),
(20, '2025-10-10', 'AVKJIPBA0UOFVA', 'draggafiugbuysvfsaF9YT7ACD VG'),
(20, '2025-10-23', 'sedrjghsbdpiugbs&egrave;piuyvgbfarde&egrave;iuv', 'pasvbdfvuasdfpiuyvadfpvadp'),
(20, '2025-11-20', 'Interista', 'Riunione Ufficiale'),
(25, '2025-05-03', 'proviamooo', 'prova1'),
(26, '2025-05-23', 'ITALIA ', 'prova1'),
(28, '2025-05-17', 'asd', 'Teobaldo'),
(29, '2025-05-17', 'Interista', 'Teobaldo'),
(30, '2025-05-25', '74415415415', '12'),
(36, '2025-05-16', 'Uff', 'Riunione Ufficiale');

-- --------------------------------------------------------

--
-- Struttura della tabella `ATLETA`
--

CREATE TABLE `ATLETA` (
  `Codice` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ATLETA`
--

INSERT INTO `ATLETA` (`Codice`) VALUES
(2),
(75),
(79),
(84),
(90),
(93),
(97),
(101),
(103),
(110),
(113);

-- --------------------------------------------------------

--
-- Struttura della tabella `ATTO`
--

CREATE TABLE `ATTO` (
  `NumProtocollo` varchar(200) NOT NULL,
  `Data` date NOT NULL,
  `OrdineDelGiorno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `PathTesto` varchar(255) DEFAULT NULL,
  `CodiceRedatore` int NOT NULL,
  `Oggetto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ATTO`
--

INSERT INTO `ATTO` (`NumProtocollo`, `Data`, `OrdineDelGiorno`, `PathTesto`, `CodiceRedatore`, `Oggetto`) VALUES
('00_admin', '2025-04-17', 'Admin', '/', 1, 'Admin'),
('111_consigliere_2025', '2025-05-07', 'Consigliere', 'https://drive.google.com/file/d/1PvezHw1tInOOKwLVsrm-sSXZHdYFBjT8/view', 1, 'Nomina di TBL1111111111111 a Consigliere'),
('113_consigliere_2025', '2025-05-07', 'Consigliere', 'https://drive.google.com/file/d/1GVb7f2wLkOapnFxYMDChTm-CYyJX5-yS/view', 1, 'Nomina di JNINDR06T17L407K a Consigliere'),
('114_consigliere_2025', '2025-05-10', 'Consigliere', 'https://drive.google.com/file/d/1sd3P5nvL-hNduP08m6UJy60btD3ziPLn/view', 1, 'Nomina di MGRLRT06T22G224Q a Consigliere'),
('115_consigliere_2025', '2025-05-12', 'Consigliere', 'https://drive.google.com/file/d/1lypE83vpnrbruZSNwD959RJr5Qinq-qB/view', 1, 'Nomina di MGRLRT06T22G226Q a Consigliere'),
('221_socio_2025', '2025-04-27', 'Socio', 'https://drive.google.com/file/d/16ZI-9nvGkZxFUKx7QADi3SJ3Q6ttYOtN/view', 1, 'Nomina di JNINDR06T17L407K a Socio'),
('222_socio_2025', '2025-05-12', 'Socio', 'https://drive.google.com/file/d/1kQHM2a4sx9lV-QKW0aF6AYBFDy1OLUjS/view', 1, 'Nomina di MGRLRT06T22G227Q a Socio'),
('223_socio_2025', '2025-09-08', 'Socio', 'https://drive.google.com/file/d/17Zs9uW8Z_cTlm1043Qx7zJR7JufF4Q7c/view', 1, 'Nomina di MGRLRT06T22G223Q a Socio'),
('331_allenatore_2025', '2025-04-27', 'Allenatore', 'https://drive.google.com/file/d/1cnQvf5m1josEn0E7SdcxnpIWiwem5rAF/view', 1, 'Nomina di JNINDR06T17L407K a Allenatore'),
('332_allenatore_2025', '2025-05-03', 'Allenatore', 'https://drive.google.com/file/d/1lt45C1PNsqeAMRPHYQw6TgnzO7RqZFvT/view', 1, 'Nomina di MGRLRT06T22G224Q a Allenatore'),
('333_allenatore_2025', '2025-05-12', 'Allenatore', 'https://drive.google.com/file/d/1dGKvW8gjpcEGFKqXiuD4Czm9-0b0PjbF/view', 1, 'Nomina di MGRLRT06T22G223Q a Allenatore'),
('334_allenatore_2025', '2025-05-13', 'Allenatore', 'https://drive.google.com/file/d/1JD3e2MJP9CMJIgHokPETlEOQ5cUxSxwS/view', 1, 'Nomina di DLLLLLLLLLLLLLLL a Allenatore'),
('335_allenatore_2025', '2025-09-08', 'Allenatore', 'https://drive.google.com/file/d/1OUITEi5pgrUNpXFv3qHsdsV7Ai5dT-BJ/view', 1, 'Nomina di MGRLRT06T22G225Q a Allenatore'),
('44_atleta', '2025-04-17', 'Atleta', '/', 1, 'Atleta'),
('442_atleta_2025', '2025-04-27', 'Atleta', 'https://drive.google.com/file/d/1j1Hc9xpelBX9omhQx4EolT0D3RTssEDo/view', 1, 'Nomina di JNINDR06T17L407K a Atleta'),
('445_atleta_2025', '2025-05-07', 'Atleta', 'https://drive.google.com/file/d/1Nv0tG62Q4QtDJnlPKmPVaIRd5KnV-frS/view', 1, 'Nomina di TBL1111111111111 a Atleta'),
('446_atleta_2025', '2025-05-12', 'Atleta', 'https://drive.google.com/file/d/1GD6mox40f7i2EbumanyBhPfIg04jxKua/view', 1, 'Nomina di MGRLRT06T22G225Q a Atleta'),
('551_altro_personale_2025', '2025-05-04', 'Altro_Personale', 'https://drive.google.com/file/d/1E-65cVpWUMPTUdp544UXf_mY-GOJAmWJ/view', 1, 'Nomina di JNINDR06T17L407K a Altro_Personale'),
('552_altro_personale_2025', '2025-05-13', 'Altro_Personale', 'https://drive.google.com/file/d/1VgJA8XSoq9vjpyeY4P7BhBKFfYZBihwA/view', 1, 'Nomina di DLLLLLLLLLLLLLLL a Altro_Personale'),
('553_altro_personale_2025', '2025-09-08', 'Altro_Personale', 'https://drive.google.com/file/d/1kVDzoaruXyVEFp9Aaa0DTFKFI5C-YvF-/view', 1, 'Nomina di MGRLRT06T22G224Q a Altro_Personale'),
('662_medico_2025', '2025-04-26', 'Medico', 'https://drive.google.com/file/d/1X2zOlMM4cRcaYevuok7MyIptCPoxnvOy/view', 1, 'Nomina di JNINDR06T17L407K a Medico'),
('663_medico_2025', '2025-05-12', 'Medico', 'https://drive.google.com/file/d/14zzClqcz-ehQ2ISIUosQjUvgJnQGUmTc/view', 1, 'Nomina di MGRLRT06T22G228Q a Medico'),
('664_medico_2025', '2025-05-12', 'Medico', 'https://drive.google.com/file/d/1K5pZZnK_j5xfp6LgefTHKq8nYTvtP4mq/view', 1, 'Nomina di MGRLRT06T22G223Z a Medico'),
('771_sponsor_2025', '2025-05-04', 'Sponsor', 'https://drive.google.com/file/d/1PBJDxRGzZVP7yLhraH3_XPlZ1Ae4Y9_b/view', 1, 'Nomina di TBL1111111111111 a Sponsor'),
('772_sponsor_2025', '2025-05-04', 'Sponsor', 'https://drive.google.com/file/d/1h8MZ8kYFpLXzlaX39YBV9R2ljHNDTUg7/view', 1, 'Nomina di JNINDR06T17L407K a Sponsor'),
('773_sponsor_2025', '2025-05-12', 'Sponsor', 'https://drive.google.com/file/d/1DYJ6fibNemqJC1QV71yjhzFB2zYRN8iX/view', 1, 'Nomina di MGRLRT06T22G229Q a Sponsor'),
('ATTO-000001-2025', '0222-02-22', 'ITALIA ', 'https://drive.google.com/file/d/1Blt3D-esPCsZ5z6LNGPB1vhopgO6pcSc/view', 1, 'prova'),
('ATTO-000001-2026', '0222-02-22', 'ITALIA', 'https://drive.google.com/file/d/1Blt3D-esPCsZ5z6LNGPB1vhopgO6pcSc/view', 1, 'prova'),
('ATTO-000002-2025', '0222-02-22', 'ITALIA', 'https://drive.google.com/file/d/1Blt3D-esPCsZ5z6LNGPB1vhopgO6pcSc/view', 1, 'prova'),
('ATTO-000003-2025', '0222-02-22', 'ITALIA', 'https://drive.google.com/file/d/1Blt3D-esPCsZ5z6LNGPB1vhopgO6pcSc/view', 1, 'prova'),
('ATTO-000004-2025', '2025-05-15', 'asdfasdfs', 'https://drive.google.com/file/d/1mVobykGu84OU6giF9HhsaS6-P0igcgKM/view', 1, 'teobaldo interistafasdfdsfasf'),
('ATTO-000005-2025', '2025-05-23', 'asdfasfa', 'https://drive.google.com/file/d/1hdUotBcGpxHRh5aXhyvNJdREwHgO0t72/view', 1, 'teobaldo interistaasdfasdfd'),
('ATTO-000006-2025', '2025-05-30', 'ITALIA ', 'https://drive.google.com/file/d/1U8EnBSBgiWPWdF3lHGKYFLXyqNH4Xx8U/view', 1, 'teobaldo interistafdsafasdfsdfsaf'),
('ATTO-000007-2025', '2025-05-28', 'prova', 'https://drive.google.com/file/d/16XW3ucS9B_osH7yn0Y4It54IMeBjRh2-/view', 1, 'teobaldo interistsdafasdfgdfgdfg'),
('ATTO-000008-2025', '2025-05-10', 'Interista', 'https://drive.google.com/file/d/1lmaI5U2qio9AZgNIQs6IXP92qtlCdMVQ/view', 1, 'Prestito'),
('ATTO-000009-2025', '2025-05-18', 'provasadfasf', 'https://drive.google.com/file/d/1R52Mw_T5kABUFvesEUGwGSfGCUgTIlH6/view', 1, 'efwerfwqerq'),
('ATTO-000010-2025', '2025-05-11', 'Interista', 'https://drive.google.com/file/d/14driWCRhT9I8zQJAfc3omaepcP9PerPL/view', 109, 'Teobaldo'),
('ATTO-000012-2025', '2025-09-10', 'prova', 'https://drive.google.com/file/d/1_7GLLZL2K2V4jcLOqWpVHD_uUkuCtZth/view', 109, 'cxzvcdvxc'),
('ATTO-000013-2025', '2025-09-16', 'sadfasdf', 'https://drive.google.com/file/d/1-ZJqUDYX7zRoo3PYzoYJg45RpUlQGAmk/view', 109, ' rgfagafdsg'),
('ATTO-000014-2025', '2025-09-10', 'sdfsdf', 'https://drive.google.com/file/d/1Z8AtPnxwrMJvQamqVCkHm2zwS0GN7NWV/view', 109, 'sdfsdfs');

-- --------------------------------------------------------

--
-- Struttura della tabella `ATTO_NOMINA`
--

CREATE TABLE `ATTO_NOMINA` (
  `NumProtocollo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ATTO_NOMINA`
--

INSERT INTO `ATTO_NOMINA` (`NumProtocollo`) VALUES
('00_admin'),
('111_consigliere_2025'),
('113_consigliere_2025'),
('114_consigliere_2025'),
('115_consigliere_2025'),
('221_socio_2025'),
('222_socio_2025'),
('223_socio_2025'),
('331_allenatore_2025'),
('332_allenatore_2025'),
('333_allenatore_2025'),
('334_allenatore_2025'),
('335_allenatore_2025'),
('44_atleta'),
('442_atleta_2025'),
('445_atleta_2025'),
('446_atleta_2025'),
('551_altro_personale_2025'),
('552_altro_personale_2025'),
('553_altro_personale_2025'),
('662_medico_2025'),
('663_medico_2025'),
('664_medico_2025'),
('771_sponsor_2025'),
('772_sponsor_2025'),
('773_sponsor_2025');

-- --------------------------------------------------------

--
-- Struttura della tabella `CAMPO`
--

CREATE TABLE `CAMPO` (
  `ID` int NOT NULL,
  `NomeCampo` varchar(50) DEFAULT NULL,
  `TipoCampo` enum('Calcio','Tennis','Basket','Volley') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `CAMPO`
--

INSERT INTO `CAMPO` (`ID`, `NomeCampo`, `TipoCampo`) VALUES
(1, 'Campo_Calcio_1', 'Calcio'),
(2, 'Campo_Calcio_2', 'Calcio'),
(3, 'Campo_Calcio_3', 'Calcio'),
(4, 'Campo_Calcio_4', 'Calcio'),
(5, 'Campo_Volley_1', 'Volley'),
(6, 'Campo_Volley_2', 'Volley'),
(7, 'Campo_Volley_3', 'Volley'),
(8, 'Campo_Volley_4', 'Volley'),
(9, 'Campo_Tennis_1', 'Tennis'),
(10, 'Campo_Tennis_2', 'Tennis'),
(11, 'Campo_Tennis_3', 'Tennis'),
(12, 'Campo_Tennis_4', 'Tennis'),
(13, 'Campo_Basket_1', 'Basket'),
(14, 'Campo_Basket_2', 'Basket'),
(15, 'Campo_Basket_3', 'Basket'),
(16, 'Campo_Basket_4', 'Basket');

-- --------------------------------------------------------

--
-- Struttura della tabella `CARICA`
--

CREATE TABLE `CARICA` (
  `Codice` int NOT NULL,
  `NomeCarica` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `CARICA`
--

INSERT INTO `CARICA` (`Codice`, `NomeCarica`) VALUES
(1, 'admin'),
(2, 'Atleta'),
(8, 'Consigliere'),
(11, 'Consigliere'),
(31, 'Consigliere'),
(34, 'Presidente'),
(37, 'Consigliere'),
(38, 'Consigliere'),
(39, 'Consigliere'),
(42, 'Consigliere'),
(60, 'Presidente'),
(70, 'Presidente'),
(71, 'Presidente'),
(73, 'Consigliere'),
(74, 'Allenatore'),
(75, 'Atleta'),
(76, 'Medico'),
(77, 'Consigliere'),
(78, 'Socio'),
(79, 'Atleta'),
(80, 'Allenatore'),
(81, 'Medico'),
(83, 'Socio'),
(84, 'Atleta'),
(85, 'Allenatore'),
(86, 'Presidente'),
(87, 'Medico'),
(88, 'Medico'),
(89, 'Medico'),
(90, 'Atleta'),
(91, 'Presidente'),
(92, 'Allenatore'),
(93, 'Atleta'),
(94, 'Socio'),
(95, 'Allenatore'),
(96, 'Allenatore'),
(97, 'Atleta'),
(98, 'Consigliere'),
(99, 'Allenatore'),
(100, 'Sponsor'),
(101, 'Atleta'),
(102, 'Allenatore'),
(103, 'Atleta'),
(104, 'Sponsor'),
(105, 'Altro_Personale'),
(106, 'Consigliere'),
(108, 'Consigliere'),
(109, 'Consigliere'),
(110, 'Atleta'),
(111, 'Consigliere'),
(112, 'Allenatore'),
(113, 'Atleta'),
(114, 'Consigliere'),
(115, 'Sponsor'),
(116, 'Socio'),
(117, 'Medico'),
(118, 'Medico'),
(119, 'Altro_Personale'),
(120, 'Allenatore'),
(122, 'Altro_Personale'),
(125, 'Socio'),
(126, 'Allenatore');

-- --------------------------------------------------------

--
-- Struttura della tabella `CONSIGLIERE`
--

CREATE TABLE `CONSIGLIERE` (
  `Codice` int NOT NULL,
  `Tipo` enum('Presidente','Consigliere') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `CodiceConvocatore` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `CONSIGLIERE`
--

INSERT INTO `CONSIGLIERE` (`Codice`, `Tipo`, `CodiceConvocatore`) VALUES
(1, NULL, NULL),
(31, 'Consigliere', 2),
(34, 'Presidente', 3),
(37, 'Consigliere', 4),
(38, 'Consigliere', 5),
(39, 'Consigliere', 6),
(42, 'Consigliere', 7),
(60, 'Presidente', 8),
(71, 'Presidente', 10),
(73, 'Consigliere', 11),
(77, 'Consigliere', 12),
(86, 'Presidente', 14),
(91, 'Presidente', 19),
(98, 'Consigliere', 25),
(106, 'Consigliere', 32),
(108, 'Consigliere', 33),
(109, 'Consigliere', 20),
(111, 'Consigliere', 26),
(114, 'Consigliere', 36);

-- --------------------------------------------------------

--
-- Struttura della tabella `CONVOCATORE`
--

CREATE TABLE `CONVOCATORE` (
  `Codice` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `CONVOCATORE`
--

INSERT INTO `CONVOCATORE` (`Codice`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36),
(37),
(38),
(39),
(40),
(41),
(42),
(43);

-- --------------------------------------------------------

--
-- Struttura della tabella `DISPONIBILITA`
--

CREATE TABLE `DISPONIBILITA` (
  `CodiceMedico` int NOT NULL,
  `GiornoSettimanale` enum('Lunedi','Martedi','Mercoledi','Giovedi','Venerdi','Sabato','Domenica') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `DISPONIBILITA`
--

INSERT INTO `DISPONIBILITA` (`CodiceMedico`, `GiornoSettimanale`) VALUES
(87, 'Lunedi'),
(87, 'Martedi'),
(87, 'Giovedi'),
(88, 'Lunedi'),
(88, 'Mercoledi'),
(88, 'Giovedi'),
(88, 'Venerdi'),
(88, 'Sabato'),
(88, 'Domenica'),
(117, 'Lunedi'),
(117, 'Martedi'),
(117, 'Giovedi'),
(117, 'Venerdi'),
(118, 'Lunedi'),
(118, 'Martedi'),
(118, 'Mercoledi'),
(118, 'Giovedi'),
(118, 'Venerdi'),
(118, 'Sabato');

-- --------------------------------------------------------

--
-- Struttura della tabella `EDIZIONE_TORNEO`
--

CREATE TABLE `EDIZIONE_TORNEO` (
  `CodiceTorneo` int NOT NULL,
  `Anno` date NOT NULL,
  `Regolamento` varchar(255) NOT NULL,
  `CodiceMedico` int DEFAULT NULL,
  `MaxSquadre` enum('4','8','16') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `EDIZIONE_TORNEO`
--

INSERT INTO `EDIZIONE_TORNEO` (`CodiceTorneo`, `Anno`, `Regolamento`, `CodiceMedico`, `MaxSquadre`) VALUES
(48, '2025-08-26', 'https://drive.google.com/file/d/1J9T5YQ2wpDGw43837kCzWPVOtfJ0M5sh/view', 87, '8'),
(48, '2025-09-15', 'https://drive.google.com/file/d/1E3WlBwZ3ISjzNk3Sdcj2zl3-L13d8rzi/view', 88, '16'),
(48, '2025-09-17', 'https://drive.google.com/file/d/1KEUqEJkOQYKPoxwz0ug8L4rMgnKZRk56/view', 87, '4'),
(48, '2025-09-18', 'https://drive.google.com/file/d/1k9fXUpMyqS1QiiET-Cn2F2FwQb03FdnQ/view', 88, '4'),
(48, '2025-09-19', 'https://drive.google.com/file/d/1Vf86nsR-c7CgxU6rX_Z1nJQF_l-F7ObU/view', 118, '4'),
(48, '2025-09-22', 'https://drive.google.com/file/d/1UAcGYMyjZMUHxhj1PR15dy1Tz7Zi4zVu/view', 88, '4'),
(49, '2025-05-05', 'https://drive.google.com/file/d/16Hfxdv9hcUuf26LH0g5UQrrKq17ztoKd/view', 88, '4'),
(49, '2025-05-06', 'https://drive.google.com/file/d/16Hfxdv9hcUuf26LH0g5UQrrKq17ztoKd/view', 88, '4'),
(49, '2025-05-09', 'https://drive.google.com/file/d/16Hfxdv9hcUuf26LH0g5UQrrKq17ztoKd/view', 88, '4'),
(50, '2025-05-04', 'https://drive.google.com/file/d/1LwJxlXoSx5e1zpX1ca7e-6NPtzQGsjO9/view', 88, '4'),
(50, '2025-05-11', 'https://drive.google.com/file/d/1M3PLGfm8I2NQhgSvGAujJofghQUF4ISk/view', 88, '4'),
(51, '2025-05-04', 'https://drive.google.com/file/d/1o9OoYq09ZfJP_-KkCHZ_p7TaAskTPZKR/view', 88, '4'),
(51, '2025-05-12', 'https://drive.google.com/file/d/1-aCiku08dIerSS6x-EkqGdWOgyeibojM/view', 88, '4'),
(91, '2025-05-12', 'https://drive.google.com/file/d/1EmL-GLciIe1tiqalynVhKmyiInwA9_Gu/view', 87, '4'),
(91, '2025-05-16', 'https://drive.google.com/file/d/1KPLEVMAgHxk-UttnmIXYBVY438sOyGfE/view', 88, '4'),
(91, '2025-05-23', 'https://drive.google.com/file/d/198CbT-ZIyp0OvMYlBLE-K-0EoV70gLPJ/view', 88, '4'),
(149, '2025-05-13', 'https://drive.google.com/file/d/1bEOvLD3QxPuxUHwRvW5nD0jKhuHsFQqV/view', 117, '4'),
(157, '2025-05-30', 'https://drive.google.com/file/d/1BBpjOYX2RDh9fmpzrVJ2k06bMge91xz6/view', 87, '4'),
(159, '2025-07-18', 'https://drive.google.com/file/d/15IKOY6r7elpwqmC8SLRbjjXvsKunbj6R/view', 87, '4'),
(161, '2025-08-15', 'https://drive.google.com/file/d/15EmQIXQt7n4XWTqboYcWZ1N6emjPHRNI/view', 88, '4'),
(210, '2025-09-25', 'https://drive.google.com/file/d/1KQjqyKMIz0wedQEFIaOLyBvNq2LkGuPP/view', 88, '4'),
(211, '2025-10-02', 'https://drive.google.com/file/d/1KxoX2IFnkqKX1samiyw4ak40bkpG5OkB/view', 87, '4');

-- --------------------------------------------------------

--
-- Struttura della tabella `EVENTO_SPECIALE`
--

CREATE TABLE `EVENTO_SPECIALE` (
  `CodiceAttivita` int NOT NULL,
  `Causale` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `EVENTO_SPECIALE`
--

INSERT INTO `EVENTO_SPECIALE` (`CodiceAttivita`, `Causale`) VALUES
(1, 'prova'),
(68, ''),
(70, ''),
(71, 'adsfsdfds'),
(72, ''),
(73, 'sfsdfsdfsdf'),
(76, ''),
(77, ''),
(78, ''),
(79, ''),
(80, 'sono un coglione'),
(139, 'sadfasfsf'),
(151, 'asfasfsdf'),
(163, 'dsfsadfasf'),
(165, 'asdfasdfas'),
(175, 'hh');

-- --------------------------------------------------------

--
-- Struttura della tabella `INSEGNA`
--

CREATE TABLE `INSEGNA` (
  `CodiceAllenatore` int NOT NULL,
  `NomeSport` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `INSEGNA`
--

INSERT INTO `INSEGNA` (`CodiceAllenatore`, `NomeSport`) VALUES
(92, 'Basket'),
(95, 'Basket'),
(99, 'Basket'),
(95, 'Calcio'),
(92, 'Tennis'),
(92, 'Volley'),
(112, 'Volley');

-- --------------------------------------------------------

--
-- Struttura della tabella `INTERVENTO`
--

CREATE TABLE `INTERVENTO` (
  `CodiceConvocatore` int NOT NULL,
  `DataAssemblea` date NOT NULL,
  `Persona` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `INTERVENTO`
--

INSERT INTO `INTERVENTO` (`CodiceConvocatore`, `DataAssemblea`, `Persona`) VALUES
(20, '2025-05-14', 'JNINDR06T17L407K'),
(20, '2025-05-22', 'JNINDR06T17L407K'),
(20, '2025-09-15', 'JNINDR06T17L407K'),
(20, '2025-09-17', 'JNINDR06T17L407K'),
(20, '2025-09-19', 'JNINDR06T17L407K'),
(20, '2025-09-24', 'JNINDR06T17L407K'),
(20, '2025-09-25', 'JNINDR06T17L407K'),
(20, '2025-09-26', 'JNINDR06T17L407K'),
(20, '2025-09-30', 'JNINDR06T17L407K'),
(20, '2025-10-03', 'JNINDR06T17L407K'),
(20, '2025-10-10', 'JNINDR06T17L407K'),
(20, '2025-11-20', 'JNINDR06T17L407K'),
(26, '2025-05-23', 'JNINDR06T17L407K'),
(36, '2025-05-16', 'MGRLRT06T22G223Q'),
(20, '2025-09-25', 'MGRLRT06T22G224Q'),
(20, '2025-10-10', 'MGRLRT06T22G224Q'),
(20, '2025-10-23', 'MGRLRT06T22G224Q'),
(26, '2025-05-23', 'MGRLRT06T22G224Q'),
(36, '2025-05-16', 'MGRLRT06T22G226Q');

-- --------------------------------------------------------

--
-- Struttura della tabella `ISCRIZIONE`
--

CREATE TABLE `ISCRIZIONE` (
  `CodiceAtleta` int NOT NULL,
  `NomeSport` varchar(25) NOT NULL,
  `Tipo` enum('Amatoriale','Agonistico') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ISCRIZIONE`
--

INSERT INTO `ISCRIZIONE` (`CodiceAtleta`, `NomeSport`, `Tipo`) VALUES
(90, 'Calcio', 'Agonistico'),
(93, 'Basket', 'Amatoriale'),
(93, 'Tennis', 'Agonistico'),
(93, 'Volley', 'Agonistico'),
(101, 'Basket', 'Amatoriale'),
(101, 'Calcio', 'Amatoriale'),
(113, 'Basket', 'Amatoriale'),
(113, 'Volley', 'Agonistico');

-- --------------------------------------------------------

--
-- Struttura della tabella `ISCRIZIONI_TORNEO`
--

CREATE TABLE `ISCRIZIONI_TORNEO` (
  `EdizioneTorneo` int NOT NULL,
  `AnnoTorneo` date NOT NULL,
  `NomeSquadra` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `MEDICO`
--

CREATE TABLE `MEDICO` (
  `Codice` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `MEDICO`
--

INSERT INTO `MEDICO` (`Codice`) VALUES
(76),
(81),
(87),
(88),
(89),
(117),
(118);

-- --------------------------------------------------------

--
-- Struttura della tabella `NOMINA`
--

CREATE TABLE `NOMINA` (
  `Persona` varchar(16) NOT NULL,
  `CodiceCarica` int NOT NULL,
  `DataDiNomina` date NOT NULL,
  `DataFine` date NOT NULL,
  `Autenticazione` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `NOMINA`
--

INSERT INTO `NOMINA` (`Persona`, `CodiceCarica`, `DataDiNomina`, `DataFine`, `Autenticazione`) VALUES
('DLLLLLLLLLLLLLLL', 119, '2025-05-13', '2026-05-13', '552_altro_personale_2025'),
('DLLLLLLLLLLLLLLL', 120, '2025-05-13', '2026-05-13', '334_allenatore_2025'),
('JNINDR06T17L407K', 1, '2025-04-17', '2222-01-01', '00_admin'),
('JNINDR06T17L407K', 88, '2025-04-26', '2026-04-26', '662_medico_2025'),
('JNINDR06T17L407K', 92, '2025-04-27', '2026-04-27', '331_allenatore_2025'),
('JNINDR06T17L407K', 93, '2025-04-27', '2026-04-27', '442_atleta_2025'),
('JNINDR06T17L407K', 94, '2025-04-27', '2026-04-27', '221_socio_2025'),
('JNINDR06T17L407K', 104, '2025-05-04', '2026-05-04', '772_sponsor_2025'),
('JNINDR06T17L407K', 105, '2025-05-04', '2026-05-04', '551_altro_personale_2025'),
('JNINDR06T17L407K', 109, '2025-05-07', '2026-05-07', '113_consigliere_2025'),
('MGRLRT06T22G223Q', 112, '2025-05-12', '2026-05-12', '333_allenatore_2025'),
('MGRLRT06T22G223Q', 125, '2025-09-08', '2026-09-08', '223_socio_2025'),
('MGRLRT06T22G223Z', 118, '2025-05-12', '2026-05-12', '664_medico_2025'),
('MGRLRT06T22G224Q', 99, '2025-05-03', '2026-05-03', '332_allenatore_2025'),
('MGRLRT06T22G224Q', 111, '2025-05-10', '2026-05-10', '114_consigliere_2025'),
('MGRLRT06T22G224Q', 122, '2025-09-08', '2026-09-08', '553_altro_personale_2025'),
('MGRLRT06T22G225Q', 113, '2025-05-12', '2026-05-12', '446_atleta_2025'),
('MGRLRT06T22G225Q', 126, '2025-09-08', '2026-09-08', '335_allenatore_2025'),
('MGRLRT06T22G226Q', 114, '2025-05-12', '2026-05-12', '115_consigliere_2025'),
('MGRLRT06T22G227Q', 116, '2025-05-12', '2026-05-12', '222_socio_2025'),
('MGRLRT06T22G228Q', 117, '2025-05-12', '2026-05-12', '663_medico_2025'),
('MGRLRT06T22G229Q', 115, '2025-05-12', '2026-05-12', '773_sponsor_2025'),
('TBL1111111111111', 100, '2025-05-04', '2026-05-04', '771_sponsor_2025'),
('TBL1111111111111', 106, '2025-05-07', '2026-05-07', '111_consigliere_2025'),
('TBL1111111111111', 110, '2025-05-07', '2026-05-07', '445_atleta_2025');

-- --------------------------------------------------------

--
-- Struttura della tabella `PARTITA_TORNEO`
--

CREATE TABLE `PARTITA_TORNEO` (
  `SquadraCasa` varchar(100) NOT NULL,
  `SquadraOspite` varchar(100) NOT NULL,
  `EdizioneTorneo` int NOT NULL,
  `AnnoTorneo` date NOT NULL,
  `ScoreCasa` int DEFAULT NULL,
  `ScoreOspite` int DEFAULT NULL,
  `Round` int NOT NULL,
  `Gruppo` varchar(10) NOT NULL,
  `is_updated` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `PARTITA_UFFICIALE`
--

CREATE TABLE `PARTITA_UFFICIALE` (
  `CodiceAttivita` int NOT NULL,
  `Arbitro` varchar(100) NOT NULL,
  `ScoreCasa` int DEFAULT NULL,
  `ScoreOspite` int DEFAULT NULL,
  `SquadraCasa` varchar(100) NOT NULL,
  `SquadraOspite` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `PARTITA_UFFICIALE`
--

INSERT INTO `PARTITA_UFFICIALE` (`CodiceAttivita`, `Arbitro`, `ScoreCasa`, `ScoreOspite`, `SquadraCasa`, `SquadraOspite`) VALUES
(180, 'PAolo', 5, 0, 'Teobado_interista!!', 'Teobado_juventino'),
(182, 'PAolo', 0, 0, 'Teobado_interista!!', 'Teobado_juventino'),
(191, 'PAolo', 0, 0, 'Teobado_interista!!', 'Teobado_juventino');

-- --------------------------------------------------------

--
-- Struttura della tabella `PERSONA`
--

CREATE TABLE `PERSONA` (
  `CF` varchar(16) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Cognome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `PERSONA`
--

INSERT INTO `PERSONA` (`CF`, `Nome`, `Cognome`) VALUES
('bbbbbbbbbbbbbbbb', 'Chiara', 'Teobaldo'),
('DLLLLLLLLLLLLLLL', 'Laura', 'Dalla Monta'),
('Ioihyfgsdiyfvsda', 'SDFSDFSDFSDF', 'SFSDFSDFSDFSDF'),
('iughispdfpiugsad', 'pino', 'greco'),
('JNINDR06T17L407K', 'Andrea', '555'),
('MGRLRT06T22G223Q', 'Stefano', 'Callegaro'),
('MGRLRT06T22G223Z', 'Valerio', 'Rossi'),
('MGRLRT06T22G224Q', 'alberto', 'Magrini2'),
('MGRLRT06T22G225Q', 'Marco', 'Rossi'),
('MGRLRT06T22G226Q', 'Giulio', 'Cesare'),
('MGRLRT06T22G227Q', 'Federico', 'Bolzonella'),
('MGRLRT06T22G228Q', 'Alessia', 'Brogion'),
('MGRLRT06T22G229Q', 'Leonardo', 'Da Vinci'),
('MGRLRT09T22G220Q', 'Valerio', 'lol'),
('TBL1111111111111', 'Tha', 'Losco'),
('TBLFRC06L26A001L', 'Federico', 'Lollardo');

-- --------------------------------------------------------

--
-- Struttura della tabella `PRENOTANTE`
--

CREATE TABLE `PRENOTANTE` (
  `Codice` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `PRENOTANTE`
--

INSERT INTO `PRENOTANTE` (`Codice`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31);

-- --------------------------------------------------------

--
-- Struttura della tabella `PRENOTAZIONE`
--

CREATE TABLE `PRENOTAZIONE` (
  `IDCampo` int NOT NULL,
  `DataTimeInizio` datetime NOT NULL,
  `DataTimeFine` datetime NOT NULL,
  `TimeStampPrenotazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Prenotante` int NOT NULL,
  `Attivita` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `PRENOTAZIONE`
--

INSERT INTO `PRENOTAZIONE` (`IDCampo`, `DataTimeInizio`, `DataTimeFine`, `TimeStampPrenotazione`, `Prenotante`, `Attivita`) VALUES
(1, '2025-05-03 08:00:00', '2025-05-03 10:00:00', '2025-05-02 00:33:10', 11, 1),
(1, '2025-05-05 08:00:00', '2025-05-05 09:00:00', '2025-05-02 00:33:10', 11, 3),
(1, '2025-05-06 20:00:00', '2025-05-06 21:00:00', '2025-05-02 00:33:10', 11, 4),
(1, '2025-05-07 08:00:00', '2025-05-07 09:00:00', '2025-05-02 00:33:10', 11, 5),
(1, '2025-09-17 08:00:00', '2025-09-17 09:00:00', '2025-09-15 18:41:58', 11, 191),
(1, '2025-09-22 08:00:00', '2025-09-22 22:00:00', '2025-09-17 12:38:54', 11, 48),
(2, '2025-05-03 08:00:00', '2025-05-03 11:00:00', '2025-05-02 06:00:00', 11, 1),
(2, '2025-05-15 08:00:00', '2025-05-15 11:00:00', '2025-05-13 06:48:33', 11, 153),
(3, '2025-05-03 08:00:00', '2025-05-03 09:00:00', '2025-05-03 17:11:42', 15, 39),
(4, '2025-05-09 08:00:00', '2025-05-09 09:00:00', '2025-05-09 22:23:32', 11, 131),
(5, '2025-05-03 08:00:00', '2025-05-03 09:00:00', '2025-05-03 17:00:51', 15, 36),
(5, '2025-05-03 09:00:00', '2025-05-03 10:00:00', '2025-05-03 17:09:13', 15, 38),
(5, '2025-05-04 09:00:00', '2025-05-04 10:00:00', '2025-05-04 15:45:37', 15, 62),
(5, '2025-05-04 10:00:00', '2025-05-04 11:00:00', '2025-05-04 15:52:30', 15, 64),
(5, '2025-05-05 08:00:00', '2025-05-05 09:00:00', '2025-05-04 14:22:24', 15, 49),
(5, '2025-05-06 08:00:00', '2025-05-06 09:00:00', '2025-05-04 14:23:27', 15, 49),
(5, '2025-05-06 09:00:00', '2025-05-06 10:00:00', '2025-05-06 07:44:58', 11, 96),
(5, '2025-05-09 08:00:00', '2025-05-09 21:00:00', '2025-05-04 14:39:05', 15, 49),
(5, '2025-05-10 08:00:00', '2025-05-10 09:00:00', '2025-05-09 22:21:10', 11, 127),
(5, '2025-05-11 08:00:00', '2025-05-11 22:00:00', '2025-05-04 14:41:12', 15, 50),
(5, '2025-05-12 08:00:00', '2025-05-12 22:00:00', '2025-05-04 14:42:59', 15, 51),
(5, '2025-05-13 08:00:00', '2025-05-13 09:00:00', '2025-05-12 17:58:01', 22, 147),
(5, '2025-05-13 09:00:00', '2025-05-13 10:00:00', '2025-05-12 18:42:21', 22, 148),
(5, '2025-05-14 08:00:00', '2025-05-14 09:00:00', '2025-05-07 16:43:11', 15, 121),
(5, '2025-05-14 13:00:00', '2025-05-14 14:00:00', '2025-05-13 05:34:10', 11, 152),
(5, '2025-05-15 08:00:00', '2025-05-15 09:00:00', '2025-05-04 16:30:12', 11, 82),
(5, '2025-05-16 08:00:00', '2025-05-16 09:00:00', '2025-05-04 16:31:59', 11, 83),
(5, '2025-05-21 08:00:00', '2025-05-21 09:00:00', '2025-05-07 16:43:11', 15, 121),
(5, '2025-05-30 08:00:00', '2025-05-30 22:00:00', '2025-05-22 14:42:04', 11, 157),
(5, '2025-08-15 08:00:00', '2025-08-15 22:00:00', '2025-08-09 14:16:48', 11, 161),
(5, '2025-09-16 08:00:00', '2025-09-16 09:00:00', '2025-09-15 17:47:57', 11, 182),
(5, '2025-09-18 08:00:00', '2025-09-18 22:00:00', '2025-09-17 12:35:02', 11, 48),
(6, '2025-05-03 08:00:00', '2025-05-03 09:00:00', '2025-05-03 17:05:26', 15, 37),
(6, '2025-05-03 09:00:00', '2025-05-03 10:00:00', '2025-05-03 18:23:18', 15, 45),
(6, '2025-05-04 08:00:00', '2025-05-04 09:00:00', '2025-05-04 13:56:56', 15, 48),
(6, '2025-05-04 09:00:00', '2025-05-04 10:00:00', '2025-05-04 15:52:11', 15, 63),
(6, '2025-05-04 10:00:00', '2025-05-04 11:00:00', '2025-05-04 16:03:24', 11, 66),
(6, '2025-05-05 08:00:00', '2025-05-05 09:00:00', '2025-05-04 16:06:21', 11, 70),
(6, '2025-05-06 08:00:00', '2025-05-06 09:00:00', '2025-05-04 16:28:56', 11, 80),
(6, '2025-05-07 08:00:00', '2025-05-07 09:00:00', '2025-05-07 16:47:02', 15, 122),
(6, '2025-05-09 08:00:00', '2025-05-09 11:00:00', '2025-05-09 22:20:25', 11, 125),
(6, '2025-05-09 14:00:00', '2025-05-09 15:00:00', '2025-05-09 22:21:45', 11, 128),
(6, '2025-05-10 08:00:00', '2025-05-10 11:00:00', '2025-05-10 14:21:11', 11, 135),
(6, '2025-05-11 08:00:00', '2025-05-11 09:00:00', '2025-05-10 22:02:16', 11, 138),
(6, '2025-05-11 10:00:00', '2025-05-11 11:00:00', '2025-05-10 22:02:21', 11, 139),
(6, '2025-05-12 12:00:00', '2025-05-12 13:00:00', '2025-05-11 13:21:31', 11, 143),
(6, '2025-05-13 08:00:00', '2025-05-13 09:00:00', '2025-05-12 17:55:06', 22, 146),
(6, '2025-05-14 08:00:00', '2025-05-14 09:00:00', '2025-05-07 16:47:02', 15, 122),
(6, '2025-05-14 09:00:00', '2025-05-14 10:00:00', '2025-05-13 07:50:59', 11, 154),
(6, '2025-05-18 08:00:00', '2025-05-18 11:00:00', '2025-05-05 06:07:12', 11, 92),
(6, '2025-05-20 08:00:00', '2025-05-20 09:00:00', '2025-05-12 17:55:06', 22, 146),
(6, '2025-05-21 09:00:00', '2025-05-21 10:00:00', '2025-05-13 07:50:59', 11, 154),
(6, '2025-07-18 08:00:00', '2025-07-18 09:00:00', '2025-07-17 18:40:13', 11, 158),
(6, '2025-07-18 09:00:00', '2025-07-18 10:00:00', '2025-07-17 18:52:56', 11, 160),
(6, '2025-08-26 08:00:00', '2025-08-26 22:00:00', '2025-08-25 17:16:19', 11, 48),
(6, '2025-09-03 09:00:00', '2025-09-03 10:00:00', '2025-09-02 15:57:56', 11, 169),
(6, '2025-09-03 10:00:00', '2025-09-03 11:00:00', '2025-09-02 15:58:45', 11, 170),
(6, '2025-09-03 14:00:00', '2025-09-03 15:00:00', '2025-09-02 15:59:47', 11, 171),
(6, '2025-09-03 15:00:00', '2025-09-03 16:00:00', '2025-09-02 16:00:11', 11, 172),
(6, '2025-09-17 08:00:00', '2025-09-17 22:00:00', '2025-09-16 10:57:27', 11, 48),
(6, '2025-10-02 08:00:00', '2025-10-02 22:00:00', '2025-09-18 16:30:54', 11, 211),
(7, '2025-05-03 08:00:00', '2025-05-03 09:00:00', '2025-05-03 17:17:36', 15, 41),
(7, '2025-05-03 09:00:00', '2025-05-03 10:00:00', '2025-05-03 17:19:18', 15, 42),
(7, '2025-05-03 10:00:00', '2025-05-03 11:00:00', '2025-05-03 18:23:04', 15, 44),
(7, '2025-05-04 08:00:00', '2025-05-04 09:00:00', '2025-05-04 14:00:20', 15, 49),
(7, '2025-05-04 09:00:00', '2025-05-04 10:00:00', '2025-05-04 15:53:40', 18, 65),
(7, '2025-05-04 13:00:00', '2025-05-04 14:00:00', '2025-05-04 16:03:41', 11, 67),
(7, '2025-05-04 14:00:00', '2025-05-04 15:00:00', '2025-05-04 16:03:58', 11, 68),
(7, '2025-05-04 15:00:00', '2025-05-04 16:00:00', '2025-05-04 16:04:09', 11, 69),
(7, '2025-05-05 08:00:00', '2025-05-05 09:00:00', '2025-05-04 16:27:55', 11, 79),
(7, '2025-05-05 10:00:00', '2025-05-05 11:00:00', '2025-05-05 17:24:24', 11, 94),
(7, '2025-05-06 08:00:00', '2025-05-06 09:00:00', '2025-05-04 16:29:31', 11, 81),
(7, '2025-05-09 09:00:00', '2025-05-09 12:00:00', '2025-05-06 06:38:06', 11, 95),
(7, '2025-05-09 12:00:00', '2025-05-09 13:00:00', '2025-05-09 15:12:17', 15, 124),
(7, '2025-05-09 16:00:00', '2025-05-09 17:00:00', '2025-05-09 22:39:13', 11, 133),
(7, '2025-05-10 08:00:00', '2025-05-10 09:00:00', '2025-05-10 21:54:58', 11, 137),
(7, '2025-05-11 09:00:00', '2025-05-11 11:00:00', '2025-05-09 22:21:56', 11, 129),
(7, '2025-05-12 08:00:00', '2025-05-12 22:00:00', '2025-05-05 06:05:22', 11, 91),
(7, '2025-05-13 08:00:00', '2025-05-13 09:00:00', '2025-05-11 14:00:04', 11, 144),
(7, '2025-05-13 12:00:00', '2025-05-13 13:00:00', '2025-05-12 21:41:01', 15, 150),
(7, '2025-05-13 13:00:00', '2025-05-13 14:00:00', '2025-05-12 21:41:43', 11, 151),
(7, '2025-05-16 08:00:00', '2025-05-16 22:00:00', '2025-05-04 16:35:20', 11, 91),
(7, '2025-05-23 08:00:00', '2025-05-23 22:00:00', '2025-05-07 15:46:03', 15, 91),
(7, '2025-05-24 08:00:00', '2025-05-24 09:00:00', '2025-05-22 14:37:01', 11, 155),
(7, '2025-06-08 08:00:00', '2025-06-08 11:00:00', '2025-05-09 22:22:11', 11, 130),
(7, '2025-07-18 08:00:00', '2025-07-18 22:00:00', '2025-07-17 18:41:40', 11, 159),
(7, '2025-08-15 13:00:00', '2025-08-15 14:00:00', '2025-08-14 21:36:57', 11, 162),
(7, '2025-08-30 08:00:00', '2025-08-30 09:00:00', '2025-08-29 17:37:50', 11, 163),
(7, '2025-08-30 10:00:00', '2025-08-30 11:00:00', '2025-08-29 17:39:00', 15, 166),
(7, '2025-08-30 18:00:00', '2025-08-30 19:00:00', '2025-08-29 17:38:37', 11, 165),
(7, '2025-09-02 08:00:00', '2025-09-02 09:00:00', '2025-09-01 15:09:21', 11, 168),
(7, '2025-09-09 09:00:00', '2025-09-09 10:00:00', '2025-09-08 08:55:14', 11, 173),
(7, '2025-09-14 08:00:00', '2025-09-14 09:00:00', '2025-09-13 12:34:58', 11, 175),
(8, '2025-05-03 08:00:00', '2025-05-03 11:00:00', '2025-05-03 18:09:49', 15, 43),
(8, '2025-05-04 08:00:00', '2025-05-04 09:00:00', '2025-05-04 14:15:22', 15, 50),
(8, '2025-05-04 09:00:00', '2025-05-04 10:00:00', '2025-05-04 14:15:47', 15, 51),
(8, '2025-05-09 08:00:00', '2025-05-09 09:00:00', '2025-05-09 22:21:01', 11, 126),
(8, '2025-05-10 11:00:00', '2025-05-10 12:00:00', '2025-05-09 22:39:06', 11, 132),
(8, '2025-05-11 08:00:00', '2025-05-11 09:00:00', '2025-05-11 10:07:13', 11, 141),
(8, '2025-05-11 15:00:00', '2025-05-11 16:00:00', '2025-05-11 10:12:02', 11, 142),
(8, '2025-05-23 08:00:00', '2025-05-23 09:00:00', '2025-05-22 14:37:09', 11, 156),
(8, '2025-08-30 08:00:00', '2025-08-30 09:00:00', '2025-08-29 17:41:34', 11, 167),
(8, '2025-09-25 08:00:00', '2025-09-25 22:00:00', '2025-09-17 13:24:48', 11, 210),
(9, '2025-05-05 08:00:00', '2025-05-05 09:00:00', '2025-05-05 07:41:52', 11, 93),
(11, '2025-05-10 09:00:00', '2025-05-10 10:00:00', '2025-05-10 14:21:22', 11, 136),
(13, '2025-05-11 08:00:00', '2025-05-11 09:00:00', '2025-05-11 09:57:16', 11, 140),
(13, '2025-09-19 08:00:00', '2025-09-19 22:00:00', '2025-09-17 12:43:02', 11, 48),
(14, '2025-05-10 08:00:00', '2025-05-10 09:00:00', '2025-05-09 22:52:36', 11, 134),
(14, '2025-05-17 08:00:00', '2025-05-17 09:00:00', '2025-05-09 22:52:36', 11, 134),
(14, '2025-05-24 08:00:00', '2025-05-24 09:00:00', '2025-05-09 22:52:36', 11, 134),
(16, '2025-05-13 08:00:00', '2025-05-13 22:00:00', '2025-05-12 18:58:25', 26, 149);

-- --------------------------------------------------------

--
-- Struttura della tabella `RECUPERO`
--

CREATE TABLE `RECUPERO` (
  `email` varchar(150) NOT NULL,
  `otp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `RICHIESTA`
--

CREATE TABLE `RICHIESTA` (
  `IDCampo` int NOT NULL,
  `DataTimeInizio` datetime NOT NULL,
  `Prenotante` int NOT NULL,
  `Atleta` int NOT NULL,
  `Stato` enum('Confermato','NonConfermato') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `RICHIESTA`
--

INSERT INTO `RICHIESTA` (`IDCampo`, `DataTimeInizio`, `Prenotante`, `Atleta`, `Stato`) VALUES
(2, '2025-05-03 08:00:00', 11, 11, 'NonConfermato'),
(5, '2025-05-04 09:00:00', 15, 101, 'NonConfermato'),
(5, '2025-05-04 10:00:00', 15, 101, 'NonConfermato'),
(5, '2025-05-06 09:00:00', 11, 101, 'NonConfermato'),
(5, '2025-05-13 08:00:00', 22, 113, 'Confermato'),
(5, '2025-05-14 08:00:00', 11, 101, 'Confermato'),
(7, '2025-05-09 12:00:00', 15, 101, 'NonConfermato'),
(7, '2025-05-13 12:00:00', 15, 113, 'Confermato'),
(7, '2025-05-13 13:00:00', 11, 113, 'Confermato');

-- --------------------------------------------------------

--
-- Struttura della tabella `RICHIESTE_ALL`
--

CREATE TABLE `RICHIESTE_ALL` (
  `Codice` int NOT NULL,
  `Sport` varchar(25) NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  `Stato` enum('Confermato','NonConfermato') NOT NULL,
  `CodApprovante` int DEFAULT NULL,
  `Tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `RICHIESTE_ALL`
--

INSERT INTO `RICHIESTE_ALL` (`Codice`, `Sport`, `Motivo`, `Stato`, `CodApprovante`, `Tipo`) VALUES
(92, 'Calcio', 'prova', 'NonConfermato', NULL, 'Insegnamento'),
(92, 'Tennis', 'ciao', 'NonConfermato', NULL, 'Eliminazione');

-- --------------------------------------------------------

--
-- Struttura della tabella `RICHIESTE_ATL`
--

CREATE TABLE `RICHIESTE_ATL` (
  `Codice` int NOT NULL,
  `Sport` varchar(25) NOT NULL,
  `TipoSport` enum('Amatoriale','Agonistico') NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  `Stato` enum('Confermato','NonConfermato') NOT NULL,
  `CodApprovante` int DEFAULT NULL,
  `Tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `RICHIESTE_ATL`
--

INSERT INTO `RICHIESTE_ATL` (`Codice`, `Sport`, `TipoSport`, `Motivo`, `Stato`, `CodApprovante`, `Tipo`) VALUES
(93, 'Basket', 'Amatoriale', 'ciao', 'NonConfermato', NULL, 'Eliminazione'),
(93, 'Calcio', 'Amatoriale', 'sdfsdfsdf', 'NonConfermato', NULL, 'Iscrizione'),
(93, 'Tennis', 'Agonistico', 'ciao', 'NonConfermato', NULL, 'Eliminazione');

-- --------------------------------------------------------

--
-- Struttura della tabella `RIUNIONE_TECNICA`
--

CREATE TABLE `RIUNIONE_TECNICA` (
  `CodiceAttivita` int NOT NULL,
  `Causale` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `RIUNIONE_TECNICA`
--

INSERT INTO `RIUNIONE_TECNICA` (`CodiceAttivita`, `Causale`) VALUES
(42, 'Teobaldo &egrave; un interista'),
(69, 'dsfsdfsfsdf'),
(81, 'teobaldo scemotto'),
(82, 'teobaldo scemotto'),
(131, 'sdfsdfasf'),
(141, 'sdfsdfsfsdf');

-- --------------------------------------------------------

--
-- Struttura della tabella `SOCIO`
--

CREATE TABLE `SOCIO` (
  `Codice` int NOT NULL,
  `CodiceConvocatore` int NOT NULL,
  `CodicePrenotante` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `SOCIO`
--

INSERT INTO `SOCIO` (`Codice`, `CodiceConvocatore`, `CodicePrenotante`) VALUES
(78, 12, 3),
(83, 14, 5),
(94, 22, 11),
(116, 38, 26),
(125, 34, 22);

-- --------------------------------------------------------

--
-- Struttura della tabella `SPONSOR`
--

CREATE TABLE `SPONSOR` (
  `Codice` int NOT NULL,
  `Nome` varchar(25) NOT NULL,
  `TipoAttivita` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `SPONSOR`
--

INSERT INTO `SPONSOR` (`Codice`, `Nome`, `TipoAttivita`) VALUES
(1, 'acqualete', 'azienda'),
(100, 'PoliBull', 'bene-deficienza'),
(104, 'teresa', 'bullismo'),
(115, 'New_invention SPA', 'Storia');

-- --------------------------------------------------------

--
-- Struttura della tabella `SPONSORIZZAZIONE`
--

CREATE TABLE `SPONSORIZZAZIONE` (
  `CodiceTorneo` int NOT NULL,
  `Anno` date NOT NULL,
  `CodiceSponsor` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `SPONSORIZZAZIONE`
--

INSERT INTO `SPONSORIZZAZIONE` (`CodiceTorneo`, `Anno`, `CodiceSponsor`) VALUES
(0, '2025-05-11', 104),
(51, '2025-05-12', 104),
(91, '2025-05-16', 104),
(91, '2025-05-23', 104),
(159, '2025-07-18', 104),
(91, '2025-05-16', 115),
(91, '2025-05-23', 115);

-- --------------------------------------------------------

--
-- Struttura della tabella `SPORT`
--

CREATE TABLE `SPORT` (
  `Nome` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `SPORT`
--

INSERT INTO `SPORT` (`Nome`) VALUES
('Basket'),
('Calcio'),
('Tennis'),
('Volley');

-- --------------------------------------------------------

--
-- Struttura della tabella `SQUADRA`
--

CREATE TABLE `SQUADRA` (
  `Nome` varchar(100) NOT NULL,
  `Logo` varchar(255) NOT NULL,
  `Allenatore` int NOT NULL,
  `Sport` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `SQUADRA`
--

INSERT INTO `SQUADRA` (`Nome`, `Logo`, `Allenatore`, `Sport`) VALUES
('Teobado_interista!!', 'https://drive.google.com/uc?export=view&id=1PZ3EY68XmiTCwX6Oput1HcEmr66OWntC', 92, 'Calcio'),
('Teobado_juventino', 'https://drive.google.com/uc?export=view&id=14vPZFhF6R6jxgqTN7pr5N_JSpudws0E7', 92, 'Calcio');

-- --------------------------------------------------------

--
-- Struttura della tabella `TELEFONO`
--

CREATE TABLE `TELEFONO` (
  `Numero` varchar(10) NOT NULL,
  `Persona` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `TELEFONO`
--

INSERT INTO `TELEFONO` (`Numero`, `Persona`) VALUES
('3331940489', 'bbbbbbbbbbbbbbbb'),
('3389763635', 'DLLLLLLLLLLLLLLL'),
('2546245642', 'Ioihyfgsdiyfvsda'),
('3703246314', 'iughispdfpiugsad'),
('3279350383', 'JNINDR06T17L407K'),
('3255689789', 'MGRLRT06T22G223Q'),
('3254554123', 'MGRLRT06T22G223Z'),
('3255681231', 'MGRLRT06T22G224Q'),
('3255123131', 'MGRLRT06T22G225Q'),
('3279350334', 'MGRLRT06T22G226Q'),
('3254554565', 'MGRLRT06T22G227Q'),
('3251456452', 'MGRLRT06T22G228Q'),
('3703541123', 'MGRLRT06T22G229Q'),
('3703324243', 'MGRLRT09T22G220Q'),
('3331940484', 'TBL1111111111111'),
('3701234235', 'TBLFRC06L26A001L');

-- --------------------------------------------------------

--
-- Struttura della tabella `TESSERAMENTI`
--

CREATE TABLE `TESSERAMENTI` (
  `Atleta` int NOT NULL,
  `NomeSquadra` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `TIPO_ATTIVITA`
--

CREATE TABLE `TIPO_ATTIVITA` (
  `Codice` int NOT NULL,
  `TIPO_ATTIVITA` enum('Allenamento','Torneo','Partita ufficiale','Evento speciale','Riunione tecnica') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `TIPO_ATTIVITA`
--

INSERT INTO `TIPO_ATTIVITA` (`Codice`, `TIPO_ATTIVITA`) VALUES
(1, 'Partita ufficiale'),
(2, 'Allenamento'),
(3, 'Torneo'),
(4, 'Riunione tecnica'),
(5, 'Evento speciale'),
(6, NULL),
(7, NULL),
(14, NULL),
(15, NULL),
(16, NULL),
(17, NULL),
(18, NULL),
(36, 'Allenamento'),
(37, 'Allenamento'),
(38, 'Allenamento'),
(39, 'Allenamento'),
(41, 'Partita ufficiale'),
(42, 'Riunione tecnica'),
(43, 'Allenamento'),
(44, 'Allenamento'),
(45, 'Allenamento'),
(46, 'Allenamento'),
(48, 'Torneo'),
(49, 'Torneo'),
(50, 'Torneo'),
(51, 'Torneo'),
(52, 'Allenamento'),
(62, 'Allenamento'),
(63, 'Allenamento'),
(64, 'Allenamento'),
(65, 'Allenamento'),
(66, 'Allenamento'),
(67, 'Partita ufficiale'),
(68, 'Evento speciale'),
(69, 'Riunione tecnica'),
(70, 'Evento speciale'),
(71, 'Evento speciale'),
(72, 'Evento speciale'),
(73, 'Evento speciale'),
(74, 'Allenamento'),
(75, 'Partita ufficiale'),
(76, 'Evento speciale'),
(77, 'Evento speciale'),
(78, 'Evento speciale'),
(79, 'Evento speciale'),
(80, 'Evento speciale'),
(81, 'Riunione tecnica'),
(82, 'Riunione tecnica'),
(83, 'Partita ufficiale'),
(91, 'Torneo'),
(92, 'Allenamento'),
(93, 'Allenamento'),
(94, 'Partita ufficiale'),
(95, 'Allenamento'),
(96, 'Allenamento'),
(121, 'Allenamento'),
(122, 'Allenamento'),
(124, 'Allenamento'),
(125, 'Allenamento'),
(126, 'Allenamento'),
(127, 'Allenamento'),
(128, 'Allenamento'),
(129, 'Allenamento'),
(130, 'Allenamento'),
(131, 'Riunione tecnica'),
(132, 'Allenamento'),
(133, 'Allenamento'),
(134, 'Allenamento'),
(135, 'Partita ufficiale'),
(136, 'Partita ufficiale'),
(137, 'Allenamento'),
(138, 'Allenamento'),
(139, 'Evento speciale'),
(140, 'Allenamento'),
(141, 'Riunione tecnica'),
(142, 'Allenamento'),
(143, 'Partita ufficiale'),
(144, 'Partita ufficiale'),
(145, 'Allenamento'),
(146, 'Allenamento'),
(147, 'Allenamento'),
(148, 'Partita ufficiale'),
(149, 'Torneo'),
(150, 'Allenamento'),
(151, 'Evento speciale'),
(152, 'Allenamento'),
(153, 'Partita ufficiale'),
(154, 'Allenamento'),
(155, 'Allenamento'),
(156, 'Partita ufficiale'),
(157, 'Torneo'),
(158, 'Partita ufficiale'),
(159, 'Torneo'),
(160, 'Partita ufficiale'),
(161, 'Torneo'),
(162, 'Allenamento'),
(163, 'Evento speciale'),
(165, 'Evento speciale'),
(166, 'Allenamento'),
(167, 'Allenamento'),
(168, 'Allenamento'),
(169, 'Allenamento'),
(170, 'Allenamento'),
(171, 'Allenamento'),
(172, 'Allenamento'),
(173, 'Allenamento'),
(174, 'Allenamento'),
(175, 'Evento speciale'),
(180, 'Partita ufficiale'),
(182, 'Partita ufficiale'),
(191, 'Partita ufficiale'),
(210, 'Torneo'),
(211, 'Torneo');

-- --------------------------------------------------------

--
-- Struttura della tabella `TORNEO`
--

CREATE TABLE `TORNEO` (
  `CodiceAttivita` int NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Sport` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `TORNEO`
--

INSERT INTO `TORNEO` (`CodiceAttivita`, `Nome`, `Sport`) VALUES
(3, 'Amicizia', 'Calcio'),
(48, 'Teobaldo_Interista', 'volley'),
(49, 'Teobaldo_Interista_10', 'volley'),
(50, 'Teobaldo_Interista_2', 'volley'),
(51, 'Teobaldo_Interista_3', 'volley'),
(91, 'prova', 'volley'),
(149, 'Torneo delle palle', 'basket'),
(157, 'Torneo del amicizia', 'volley'),
(159, 'MashMikan_Juventino', 'volley'),
(161, 'fbdffdgb', 'volley'),
(210, 'klklk', 'Volley'),
(211, 'akdajdhakjdhakjd', 'Volley');

-- --------------------------------------------------------

--
-- Struttura della tabella `UTENTE`
--

CREATE TABLE `UTENTE` (
  `Email` varchar(150) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Persona` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `UTENTE`
--

INSERT INTO `UTENTE` (`Email`, `Password`, `Persona`) VALUES
('allenatore@gmail.com', '$2y$10$PKaZQK1OumIMCy8CJSITZeAEdGt4XDjKiyd70Rkz2Kz2VRKI852ii', 'MGRLRT06T22G223Q'),
('andjin06@gmail.com', '$2y$10$7mgkBmUZLzdGeSR/lgc1eOLf.O8udxym.fjSNYiYOqNhJ4w03pN7W', 'JNINDR06T17L407K'),
('asjdhfiuas@asdfasdifhb.com', '$2y$10$i/dufYOgy90Jnq3D28wvxehwXkDbf4pkDPUrCckM2G6YhiTjbeUEO', 'iughispdfpiugsad'),
('atleta@gmail.com', '$2y$10$sGabiDK.qVuFHuZJ8F7BjuYXtfGWpMPOCPyZgXN5iNRqwblSofvX.', 'MGRLRT06T22G225Q'),
('consigliere@gmail.com', '$2y$10$0HPTu33WIiwiaFJgTUCJH.LWL0QERFVld7J9VPaQ.npwqu70wCcaK', 'MGRLRT06T22G226Q'),
('dallamonta.laura@gmail.com', '$2y$10$e4mvRmkcrb6VBHHfsqviruZKBWAT1wRuw/T.UspMZRLld0f3yGFG6', 'DLLLLLLLLLLLLLLL'),
('federico.teobaldo@gmail.com', '$2y$10$LO/rDVwspLErjETFbNbyKOMt444RvYRXeMRa3hfloA16JMkBCSCV.', 'TBL1111111111111'),
('hello@gmail.com', '$2y$10$kh98tPcY3kgdm4cXEOrFEu95SIL9to3aN6Fp3Wq4zhCInZfFFbOxG', 'Ioihyfgsdiyfvsda'),
('magrini.alberto06@gmail.com', '$2y$10$wbf3M.2w1wl959AzU/8LQe7R.bccDuLqOCSzTOowujv09BhiuPWuG', 'TBLFRC06L26A001L'),
('medico_1@gmail.com', '$2y$10$4HlLGJocRnjxHNCrza1kJ.HX5hYpY62BpknaGTrWTWXSzv2ty1drS', 'MGRLRT06T22G223Z'),
('medico@gmail.com', '$2y$10$VNse6JCD6FEcHtq32FmLLeHk1N2XjEyzRw/sszMt0UpK0tDrhjN1W', 'MGRLRT06T22G228Q'),
('socio@gmail.com', '$2y$10$RKZ1A6ruSCb8zikmBhAEpe5ex70oaidtCbm3xVyvyN0COCVRJrVbK', 'MGRLRT06T22G227Q'),
('sponsor@gmail.com', '$2y$10$eDQ7fbKsgpNUEkbvF4ehEOuYtBBl.N8plqAWhu4XXhpGnAmo9YW8q', 'MGRLRT06T22G229Q'),
('teoxx@gmail.com', '$2y$10$z3iDjYIepNlLglRniYyiKeDQcQbnE1xy1..qvHlBfZN2WM3Qnv4wa', 'MGRLRT09T22G220Q'),
('tribal06magrini@gmail.com', '$2y$10$7FoFb2OYXxrfrDZHbnNAbOuH6hYoUjo3T.VLa9cOTRHMFS.H09C6e', 'MGRLRT06T22G224Q');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `ALLENAMENTO`
--
ALTER TABLE `ALLENAMENTO`
  ADD PRIMARY KEY (`CodiceAttivita`);

--
-- Indici per le tabelle `ALLENATORE`
--
ALTER TABLE `ALLENATORE`
  ADD PRIMARY KEY (`Codice`),
  ADD KEY `CodiceConvocatore` (`CodiceConvocatore`),
  ADD KEY `CodicePrenotante` (`CodicePrenotante`);

--
-- Indici per le tabelle `ALTRO_PERSONALE`
--
ALTER TABLE `ALTRO_PERSONALE`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `ASSEMBLEA`
--
ALTER TABLE `ASSEMBLEA`
  ADD PRIMARY KEY (`CodiceConvocatore`,`Data`);

--
-- Indici per le tabelle `ATLETA`
--
ALTER TABLE `ATLETA`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `ATTO`
--
ALTER TABLE `ATTO`
  ADD PRIMARY KEY (`NumProtocollo`),
  ADD KEY `CodiceRedatore` (`CodiceRedatore`);

--
-- Indici per le tabelle `ATTO_NOMINA`
--
ALTER TABLE `ATTO_NOMINA`
  ADD PRIMARY KEY (`NumProtocollo`);

--
-- Indici per le tabelle `CAMPO`
--
ALTER TABLE `CAMPO`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `CARICA`
--
ALTER TABLE `CARICA`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `CONSIGLIERE`
--
ALTER TABLE `CONSIGLIERE`
  ADD PRIMARY KEY (`Codice`),
  ADD KEY `CodiceConvocatore` (`CodiceConvocatore`);

--
-- Indici per le tabelle `CONVOCATORE`
--
ALTER TABLE `CONVOCATORE`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `DISPONIBILITA`
--
ALTER TABLE `DISPONIBILITA`
  ADD PRIMARY KEY (`CodiceMedico`,`GiornoSettimanale`);

--
-- Indici per le tabelle `EDIZIONE_TORNEO`
--
ALTER TABLE `EDIZIONE_TORNEO`
  ADD PRIMARY KEY (`CodiceTorneo`,`Anno`),
  ADD KEY `CodiceMedico` (`CodiceMedico`);

--
-- Indici per le tabelle `EVENTO_SPECIALE`
--
ALTER TABLE `EVENTO_SPECIALE`
  ADD PRIMARY KEY (`CodiceAttivita`);

--
-- Indici per le tabelle `INSEGNA`
--
ALTER TABLE `INSEGNA`
  ADD PRIMARY KEY (`CodiceAllenatore`,`NomeSport`),
  ADD KEY `NomeSport` (`NomeSport`);

--
-- Indici per le tabelle `INTERVENTO`
--
ALTER TABLE `INTERVENTO`
  ADD PRIMARY KEY (`CodiceConvocatore`,`DataAssemblea`,`Persona`),
  ADD KEY `Persona` (`Persona`);

--
-- Indici per le tabelle `ISCRIZIONE`
--
ALTER TABLE `ISCRIZIONE`
  ADD PRIMARY KEY (`CodiceAtleta`,`NomeSport`),
  ADD KEY `NomeSport` (`NomeSport`);

--
-- Indici per le tabelle `ISCRIZIONI_TORNEO`
--
ALTER TABLE `ISCRIZIONI_TORNEO`
  ADD PRIMARY KEY (`NomeSquadra`,`EdizioneTorneo`,`AnnoTorneo`),
  ADD KEY `EdizioneTorneo` (`EdizioneTorneo`,`AnnoTorneo`);

--
-- Indici per le tabelle `MEDICO`
--
ALTER TABLE `MEDICO`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `NOMINA`
--
ALTER TABLE `NOMINA`
  ADD PRIMARY KEY (`Persona`,`CodiceCarica`,`DataDiNomina`),
  ADD UNIQUE KEY `Autenticazione` (`Autenticazione`),
  ADD KEY `CodiceCarica` (`CodiceCarica`);

--
-- Indici per le tabelle `PARTITA_TORNEO`
--
ALTER TABLE `PARTITA_TORNEO`
  ADD PRIMARY KEY (`SquadraOspite`,`SquadraCasa`,`EdizioneTorneo`,`AnnoTorneo`),
  ADD KEY `EdizioneTorneo` (`EdizioneTorneo`,`AnnoTorneo`),
  ADD KEY `SquadraCasa` (`SquadraCasa`);

--
-- Indici per le tabelle `PARTITA_UFFICIALE`
--
ALTER TABLE `PARTITA_UFFICIALE`
  ADD PRIMARY KEY (`CodiceAttivita`),
  ADD KEY `SquadraCasa` (`SquadraCasa`),
  ADD KEY `SquadraOspite` (`SquadraOspite`);

--
-- Indici per le tabelle `PERSONA`
--
ALTER TABLE `PERSONA`
  ADD PRIMARY KEY (`CF`);

--
-- Indici per le tabelle `PRENOTANTE`
--
ALTER TABLE `PRENOTANTE`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `PRENOTAZIONE`
--
ALTER TABLE `PRENOTAZIONE`
  ADD PRIMARY KEY (`IDCampo`,`DataTimeInizio`),
  ADD KEY `Prenotante` (`Prenotante`),
  ADD KEY `Attivita` (`Attivita`);

--
-- Indici per le tabelle `RECUPERO`
--
ALTER TABLE `RECUPERO`
  ADD PRIMARY KEY (`email`);

--
-- Indici per le tabelle `RICHIESTA`
--
ALTER TABLE `RICHIESTA`
  ADD PRIMARY KEY (`IDCampo`,`DataTimeInizio`),
  ADD KEY `Prenotante` (`Prenotante`),
  ADD KEY `Atleta` (`Atleta`);

--
-- Indici per le tabelle `RICHIESTE_ALL`
--
ALTER TABLE `RICHIESTE_ALL`
  ADD PRIMARY KEY (`Codice`,`Sport`),
  ADD KEY `Sport` (`Sport`),
  ADD KEY `CodApprovante` (`CodApprovante`);

--
-- Indici per le tabelle `RICHIESTE_ATL`
--
ALTER TABLE `RICHIESTE_ATL`
  ADD PRIMARY KEY (`Codice`,`Sport`),
  ADD KEY `Sport` (`Sport`),
  ADD KEY `CodApprovante` (`CodApprovante`);

--
-- Indici per le tabelle `RIUNIONE_TECNICA`
--
ALTER TABLE `RIUNIONE_TECNICA`
  ADD PRIMARY KEY (`CodiceAttivita`);

--
-- Indici per le tabelle `SOCIO`
--
ALTER TABLE `SOCIO`
  ADD PRIMARY KEY (`Codice`),
  ADD KEY `CodiceConvocatore` (`CodiceConvocatore`),
  ADD KEY `CodicePrenotante` (`CodicePrenotante`);

--
-- Indici per le tabelle `SPONSOR`
--
ALTER TABLE `SPONSOR`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `SPONSORIZZAZIONE`
--
ALTER TABLE `SPONSORIZZAZIONE`
  ADD PRIMARY KEY (`CodiceTorneo`,`Anno`,`CodiceSponsor`),
  ADD KEY `CodiceSponsor` (`CodiceSponsor`);

--
-- Indici per le tabelle `SPORT`
--
ALTER TABLE `SPORT`
  ADD PRIMARY KEY (`Nome`);

--
-- Indici per le tabelle `SQUADRA`
--
ALTER TABLE `SQUADRA`
  ADD PRIMARY KEY (`Nome`),
  ADD KEY `Sport` (`Sport`);

--
-- Indici per le tabelle `TELEFONO`
--
ALTER TABLE `TELEFONO`
  ADD PRIMARY KEY (`Numero`),
  ADD KEY `Persona` (`Persona`);

--
-- Indici per le tabelle `TESSERAMENTI`
--
ALTER TABLE `TESSERAMENTI`
  ADD PRIMARY KEY (`Atleta`,`NomeSquadra`),
  ADD KEY `NomeSquadra` (`NomeSquadra`);

--
-- Indici per le tabelle `TIPO_ATTIVITA`
--
ALTER TABLE `TIPO_ATTIVITA`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `TORNEO`
--
ALTER TABLE `TORNEO`
  ADD PRIMARY KEY (`CodiceAttivita`),
  ADD KEY `Sport` (`Sport`);

--
-- Indici per le tabelle `UTENTE`
--
ALTER TABLE `UTENTE`
  ADD PRIMARY KEY (`Email`),
  ADD UNIQUE KEY `Persona` (`Persona`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `CARICA`
--
ALTER TABLE `CARICA`
  MODIFY `Codice` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT per la tabella `CONVOCATORE`
--
ALTER TABLE `CONVOCATORE`
  MODIFY `Codice` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT per la tabella `PRENOTANTE`
--
ALTER TABLE `PRENOTANTE`
  MODIFY `Codice` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT per la tabella `TIPO_ATTIVITA`
--
ALTER TABLE `TIPO_ATTIVITA`
  MODIFY `Codice` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ALLENAMENTO`
--
ALTER TABLE `ALLENAMENTO`
  ADD CONSTRAINT `ALLENAMENTO_ibfk_1` FOREIGN KEY (`CodiceAttivita`) REFERENCES `TIPO_ATTIVITA` (`Codice`);

--
-- Limiti per la tabella `ALLENATORE`
--
ALTER TABLE `ALLENATORE`
  ADD CONSTRAINT `ALLENATORE_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `CARICA` (`Codice`),
  ADD CONSTRAINT `ALLENATORE_ibfk_2` FOREIGN KEY (`CodiceConvocatore`) REFERENCES `CONVOCATORE` (`Codice`),
  ADD CONSTRAINT `ALLENATORE_ibfk_3` FOREIGN KEY (`CodicePrenotante`) REFERENCES `PRENOTANTE` (`Codice`);

--
-- Limiti per la tabella `ALTRO_PERSONALE`
--
ALTER TABLE `ALTRO_PERSONALE`
  ADD CONSTRAINT `ALTRO_PERSONALE_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `CARICA` (`Codice`);

--
-- Limiti per la tabella `ATLETA`
--
ALTER TABLE `ATLETA`
  ADD CONSTRAINT `ATLETA_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `CARICA` (`Codice`);

--
-- Limiti per la tabella `ATTO`
--
ALTER TABLE `ATTO`
  ADD CONSTRAINT `ATTO_ibfk_1` FOREIGN KEY (`CodiceRedatore`) REFERENCES `CONSIGLIERE` (`Codice`);

--
-- Limiti per la tabella `ATTO_NOMINA`
--
ALTER TABLE `ATTO_NOMINA`
  ADD CONSTRAINT `ATTO_NOMINA_ibfk_1` FOREIGN KEY (`NumProtocollo`) REFERENCES `ATTO` (`NumProtocollo`);

--
-- Limiti per la tabella `CONSIGLIERE`
--
ALTER TABLE `CONSIGLIERE`
  ADD CONSTRAINT `CONSIGLIERE_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `CARICA` (`Codice`),
  ADD CONSTRAINT `CONSIGLIERE_ibfk_2` FOREIGN KEY (`CodiceConvocatore`) REFERENCES `CONVOCATORE` (`Codice`);

--
-- Limiti per la tabella `DISPONIBILITA`
--
ALTER TABLE `DISPONIBILITA`
  ADD CONSTRAINT `DISPONIBILITA_ibfk_1` FOREIGN KEY (`CodiceMedico`) REFERENCES `MEDICO` (`Codice`);

--
-- Limiti per la tabella `EDIZIONE_TORNEO`
--
ALTER TABLE `EDIZIONE_TORNEO`
  ADD CONSTRAINT `EDIZIONE_TORNEO_ibfk_1` FOREIGN KEY (`CodiceMedico`) REFERENCES `MEDICO` (`Codice`),
  ADD CONSTRAINT `EDIZIONE_TORNEO_ibfk_2` FOREIGN KEY (`CodiceTorneo`) REFERENCES `TORNEO` (`CodiceAttivita`);

--
-- Limiti per la tabella `EVENTO_SPECIALE`
--
ALTER TABLE `EVENTO_SPECIALE`
  ADD CONSTRAINT `EVENTO_SPECIALE_ibfk_1` FOREIGN KEY (`CodiceAttivita`) REFERENCES `TIPO_ATTIVITA` (`Codice`);

--
-- Limiti per la tabella `INSEGNA`
--
ALTER TABLE `INSEGNA`
  ADD CONSTRAINT `INSEGNA_ibfk_1` FOREIGN KEY (`CodiceAllenatore`) REFERENCES `ALLENATORE` (`Codice`),
  ADD CONSTRAINT `INSEGNA_ibfk_2` FOREIGN KEY (`NomeSport`) REFERENCES `SPORT` (`Nome`);

--
-- Limiti per la tabella `INTERVENTO`
--
ALTER TABLE `INTERVENTO`
  ADD CONSTRAINT `INTERVENTO_ibfk_1` FOREIGN KEY (`CodiceConvocatore`) REFERENCES `ASSEMBLEA` (`CodiceConvocatore`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `INTERVENTO_ibfk_2` FOREIGN KEY (`Persona`) REFERENCES `PERSONA` (`CF`);

--
-- Limiti per la tabella `ISCRIZIONE`
--
ALTER TABLE `ISCRIZIONE`
  ADD CONSTRAINT `ISCRIZIONE_ibfk_1` FOREIGN KEY (`CodiceAtleta`) REFERENCES `ATLETA` (`Codice`),
  ADD CONSTRAINT `ISCRIZIONE_ibfk_2` FOREIGN KEY (`NomeSport`) REFERENCES `SPORT` (`Nome`);

--
-- Limiti per la tabella `MEDICO`
--
ALTER TABLE `MEDICO`
  ADD CONSTRAINT `MEDICO_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `CARICA` (`Codice`);

--
-- Limiti per la tabella `NOMINA`
--
ALTER TABLE `NOMINA`
  ADD CONSTRAINT `NOMINA_ibfk_1` FOREIGN KEY (`Persona`) REFERENCES `PERSONA` (`CF`),
  ADD CONSTRAINT `NOMINA_ibfk_2` FOREIGN KEY (`CodiceCarica`) REFERENCES `CARICA` (`Codice`),
  ADD CONSTRAINT `NOMINA_ibfk_3` FOREIGN KEY (`Autenticazione`) REFERENCES `ATTO_NOMINA` (`NumProtocollo`);

--
-- Limiti per la tabella `PRENOTAZIONE`
--
ALTER TABLE `PRENOTAZIONE`
  ADD CONSTRAINT `PRENOTAZIONE_ibfk_1` FOREIGN KEY (`IDCampo`) REFERENCES `CAMPO` (`ID`),
  ADD CONSTRAINT `PRENOTAZIONE_ibfk_2` FOREIGN KEY (`Prenotante`) REFERENCES `PRENOTANTE` (`Codice`),
  ADD CONSTRAINT `PRENOTAZIONE_ibfk_3` FOREIGN KEY (`Attivita`) REFERENCES `TIPO_ATTIVITA` (`Codice`);

--
-- Limiti per la tabella `RICHIESTE_ALL`
--
ALTER TABLE `RICHIESTE_ALL`
  ADD CONSTRAINT `richieste_all_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `ALLENATORE` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `richieste_all_ibfk_2` FOREIGN KEY (`Sport`) REFERENCES `SPORT` (`Nome`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `richieste_all_ibfk_3` FOREIGN KEY (`CodApprovante`) REFERENCES `ALTRO_PERSONALE` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `RICHIESTE_ATL`
--
ALTER TABLE `RICHIESTE_ATL`
  ADD CONSTRAINT `richieste_atl_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `ATLETA` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `richieste_atl_ibfk_2` FOREIGN KEY (`Sport`) REFERENCES `SPORT` (`Nome`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `richieste_atl_ibfk_3` FOREIGN KEY (`CodApprovante`) REFERENCES `ALTRO_PERSONALE` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `RIUNIONE_TECNICA`
--
ALTER TABLE `RIUNIONE_TECNICA`
  ADD CONSTRAINT `RIUNIONE_TECNICA_ibfk_1` FOREIGN KEY (`CodiceAttivita`) REFERENCES `TIPO_ATTIVITA` (`Codice`);

--
-- Limiti per la tabella `SOCIO`
--
ALTER TABLE `SOCIO`
  ADD CONSTRAINT `SOCIO_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `CARICA` (`Codice`),
  ADD CONSTRAINT `SOCIO_ibfk_2` FOREIGN KEY (`CodiceConvocatore`) REFERENCES `CONVOCATORE` (`Codice`),
  ADD CONSTRAINT `SOCIO_ibfk_3` FOREIGN KEY (`CodicePrenotante`) REFERENCES `PRENOTANTE` (`Codice`);

--
-- Limiti per la tabella `SPONSOR`
--
ALTER TABLE `SPONSOR`
  ADD CONSTRAINT `SPONSOR_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `CARICA` (`Codice`);

--
-- Limiti per la tabella `SPONSORIZZAZIONE`
--
ALTER TABLE `SPONSORIZZAZIONE`
  ADD CONSTRAINT `SPONSORIZZAZIONE_ibfk_1` FOREIGN KEY (`CodiceSponsor`) REFERENCES `SPONSOR` (`Codice`);

--
-- Limiti per la tabella `TELEFONO`
--
ALTER TABLE `TELEFONO`
  ADD CONSTRAINT `TELEFONO_ibfk_1` FOREIGN KEY (`Persona`) REFERENCES `PERSONA` (`CF`);

--
-- Limiti per la tabella `TORNEO`
--
ALTER TABLE `TORNEO`
  ADD CONSTRAINT `TORNEO_ibfk_1` FOREIGN KEY (`CodiceAttivita`) REFERENCES `TIPO_ATTIVITA` (`Codice`),
  ADD CONSTRAINT `TORNEO_ibfk_2` FOREIGN KEY (`Sport`) REFERENCES `SPORT` (`Nome`);

--
-- Limiti per la tabella `UTENTE`
--
ALTER TABLE `UTENTE`
  ADD CONSTRAINT `UTENTE_ibfk_1` FOREIGN KEY (`Persona`) REFERENCES `PERSONA` (`CF`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
