-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 28, 2026 alle 11:41
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medplus_db`
--
CREATE DATABASE IF NOT EXISTS `medplus_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `medplus_db`;

-- --------------------------------------------------------

--
-- Struttura della tabella `centro_medico`
--

CREATE TABLE `centro_medico` (
  `ID_CentroMedico` int(11) NOT NULL,
  `Citta` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `centro_medico`
--

INSERT INTO `centro_medico` (`ID_CentroMedico`, `Citta`) VALUES
(1, 'Lurate Caccivio'),
(2, 'Bulgarograsso'),
(3, 'Fino Mornasco'),
(4, 'Luisago'),
(5, 'Guanzate'),
(6, 'Cadorago'),
(7, 'Como Camerlata'),
(8, 'Varese'),
(9, 'Malnate'),
(10, 'Olgiate Comasco'),
(11, 'Cermenate'),
(12, 'Novedrate'),
(13, 'Appiano Gentile');

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazione`
--

CREATE TABLE `prenotazione` (
  `ID_Prenotazione` int(11) NOT NULL,
  `Utente` varchar(60) NOT NULL,
  `Medico` varchar(60) NOT NULL,
  `Data` date NOT NULL,
  `Ora` enum('08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00') NOT NULL,
  `Prestazione` int(11) NOT NULL,
  `Stato` enum('Prenotato','Arrivato','Da Refertare','Dimesso') NOT NULL,
  `CentroMedico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prenotazione`
--

INSERT INTO `prenotazione` (`ID_Prenotazione`, `Utente`, `Medico`, `Data`, `Ora`, `Prestazione`, `Stato`, `CentroMedico`) VALUES
(19, 'marco.cattaneo@gmail.com', 'christian.bellini@gmail.com', '2026-05-28', '08:00', 7, 'Dimesso', 5),
(20, 'sofia.conti@gmail.com', 'noemi.martinelli@gmail.com', '2026-05-29', '08:00', 25, 'Prenotato', 13);

-- --------------------------------------------------------

--
-- Struttura della tabella `prestazione`
--

CREATE TABLE `prestazione` (
  `ID_Prestazione` int(11) NOT NULL,
  `NomePrestazione` varchar(60) NOT NULL,
  `Costo` decimal(6,2) NOT NULL,
  `Specializzazione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prestazione`
--

INSERT INTO `prestazione` (`ID_Prestazione`, `NomePrestazione`, `Costo`, `Specializzazione`) VALUES
(1, 'Elettrocardiogramma', 100.00, 2),
(2, 'Ecocolordoppler Cardiaco', 90.00, 2),
(3, 'Holter ECG (24/48h)', 110.00, 2),
(4, 'RX Torace (2 proiezioni)', 60.00, 3),
(5, 'Ecografia Addome Completo', 90.00, 3),
(6, 'Risonanza Magnetica (RMN) Encefalo', 220.00, 3),
(7, 'TAC Addome (senza contrasto)', 170.00, 3),
(8, 'Visita Ortopedica', 130.00, 1),
(9, 'Infiltrazione Articolare', 70.00, 1),
(10, 'Intervento Tunnel Carpale (amb.)', 900.00, 1),
(15, 'Visita Ginecologica + Eco TV', 170.00, 4),
(16, 'Pap Test (escluso esame citologico)', 40.00, 4),
(17, 'Ecografia Morfologica', 40.00, 4),
(18, 'Visita Dermatologica', 130.00, 5),
(19, 'Mappatura nei (Dermatoscopia)', 260.00, 5),
(20, 'Crioterapia (a seduta)', 90.00, 5),
(21, 'Visita Otorinolaringoiatrica', 140.00, 6),
(22, 'Esame Audiometrico', 55.00, 6),
(23, 'Laringoscopia a fibre ottiche', 100.00, 6),
(24, 'Gastroscopia (con sedazione)', 350.00, 7),
(25, 'Colonscopia (con sedazione)', 500.00, 7),
(28, 'Pacchetto Base (Emocromo, Glicemia, Lipidi)', 55.00, 8),
(29, 'Dosaggio Ormonale Tiroideo (TSH, FT3, FT4)', 40.00, 8);

-- --------------------------------------------------------

--
-- Struttura della tabella `referto`
--

CREATE TABLE `referto` (
  `ID_Referto` int(11) NOT NULL,
  `DataPubblicazione` date NOT NULL,
  `Prenotazione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `referto`
--

INSERT INTO `referto` (`ID_Referto`, `DataPubblicazione`, `Prenotazione`) VALUES
(10, '2026-05-28', 19);

-- --------------------------------------------------------

--
-- Struttura della tabella `specializzato`
--

CREATE TABLE `specializzato` (
  `Specializzante` varchar(100) NOT NULL,
  `Specializzato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `specializzato`
--

INSERT INTO `specializzato` (`Specializzante`, `Specializzato`) VALUES
('aldo.brambilla@gmail.com', 5),
('alessandro.greco@gmail.com', 1),
('alice.fontana@gmail.com', 1),
('aurora.fabbri@gmail.com', 4),
('beatrice.costa@gmail.com', 3),
('camilla.riva@gmail.com', 4),
('christian.bellini@gmail.com', 3),
('edoardo.villa@gmail.com', 5),
('elena.mancini@gmail.com', 8),
('federica.lombardi@gmail.com', 1),
('filippo.marchetti@gmail.com', 3),
('giulio.magni@gmail.com', 2),
('greta.neri@gmail.com', 6),
('lorenzo.barbieri@gmail.com', 6),
('marco.desantis@gmail.com', 8),
('nicolo.serra@gmail.com', 2),
('noemi.martinelli@gmail.com', 7),
('riccardo.parisi@gmail.com', 2),
('sara.pellegrini@gmail.com', 4),
('tommaso.grassi@gmail.com', 7),
('valentina.ricci@gmail.com', 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `specializzazione`
--

CREATE TABLE `specializzazione` (
  `ID_Specializzazione` int(11) NOT NULL,
  `NomeSpecializzazione` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `specializzazione`
--

INSERT INTO `specializzazione` (`ID_Specializzazione`, `NomeSpecializzazione`) VALUES
(1, 'Ortopedia'),
(2, 'Cardiologia'),
(3, 'Radiologia'),
(4, 'Ginecologia'),
(5, 'Dermatologia'),
(6, 'Otorinolaringoiatria'),
(7, 'Gastroenterologia'),
(8, 'Analisi Cliniche');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Ruolo` enum('Paziente','Medico','Amministratore') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`Nome`, `Cognome`, `Email`, `Password`, `Ruolo`) VALUES
('Aldo', 'Brambilla', 'aldo.brambilla@gmail.com', '$2y$10$fvyS5r8TP8ff9k8QBmKujux0bnILzD8zwR.9ofRxK3/6/7leF.tee', 'Medico'),
('Alessandro', 'Greco', 'alessandro.greco@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Alice', 'Fontana', 'alice.fontana@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Andrea', 'Galli', 'andrea.galli@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Aurora', 'Fabbri', 'aurora.fabbri@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Beatrice', 'Costa', 'beatrice.costa@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Camilla', 'Riva', 'camilla.riva@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Chiara', 'Vitale', 'chiara.vitale@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Christian', 'Bellini', 'christian.bellini@gmail.com', '$2y$10$JWQZpanLvoJlPM20SEJPrOlQu6PLyXhkwJhL3/BClo3IUhr9KS2Fy', 'Medico'),
('Davide', 'Moretti', 'davide.moretti@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Edoardo', 'Villa', 'edoardo.villa@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Elena', 'Mancini', 'elena.mancini@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Elisa', 'Romano', 'elisa.romano@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Federica', 'Lombardi', 'federica.lombardi@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Filippo', 'Marchetti', 'filippo.marchetti@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Giulia', 'Ferraro', 'giulia.ferraro@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Giulio', 'Magni', 'giulio.magni@gmail.com', '$2y$10$GEcylmHcswaySnEk/SCqauYYZ4kxcD.wre86d/7Iay5VBJvdzctWG', 'Medico'),
('Greta', 'Neri', 'greta.neri@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Lorenzo', 'Barbieri', 'lorenzo.barbieri@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Luca', 'Bernardi', 'luca.bernardi@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Marco', 'Cattaneo', 'marco.cattaneo@gmail.com', '$2y$10$j5SC.nMMI7GQ6UBGgoD4puV.QyU3k.Z3hwMGlvTr2zpaW3Bs87Df2', 'Paziente'),
('Marco', 'De Santis', 'marco.desantis@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Martina', 'Leone', 'martina.leone@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Matteo', 'Rinaldi', 'matteo.rinaldi@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Nicolò', 'Serra', 'nicolo.serra@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Noemi', 'Martinelli', 'noemi.martinelli@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Riccardo', 'Parisi', 'riccardo.parisi@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Samuele', 'Colombo', 'samuele.colombo@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Amministratore'),
('Sara', 'Pellegrini', 'sara.pellegrini@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Simone', 'Caruso', 'simone.caruso@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Sofia', 'Conti', 'sofia.conti@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Paziente'),
('Tommaso', 'Grassi', 'tommaso.grassi@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico'),
('Valentina', 'Ricci', 'valentina.ricci@gmail.com', '$2y$10$GI10hUAe6QfffJ4mvDHL7ORpeKJT7JsA.UHKfx6g6DDanhM/UE/dm', 'Medico');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `centro_medico`
--
ALTER TABLE `centro_medico`
  ADD PRIMARY KEY (`ID_CentroMedico`);

--
-- Indici per le tabelle `prenotazione`
--
ALTER TABLE `prenotazione`
  ADD PRIMARY KEY (`ID_Prenotazione`),
  ADD KEY `Utente` (`Utente`),
  ADD KEY `Medico` (`Medico`),
  ADD KEY `Prestazione` (`Prestazione`),
  ADD KEY `CentroMedico` (`CentroMedico`);

--
-- Indici per le tabelle `prestazione`
--
ALTER TABLE `prestazione`
  ADD PRIMARY KEY (`ID_Prestazione`),
  ADD KEY `Specializzazione` (`Specializzazione`);

--
-- Indici per le tabelle `referto`
--
ALTER TABLE `referto`
  ADD PRIMARY KEY (`ID_Referto`),
  ADD KEY `Prenotazione` (`Prenotazione`);

--
-- Indici per le tabelle `specializzato`
--
ALTER TABLE `specializzato`
  ADD PRIMARY KEY (`Specializzante`,`Specializzato`),
  ADD KEY `Specializzante` (`Specializzante`),
  ADD KEY `Specializzato` (`Specializzato`);

--
-- Indici per le tabelle `specializzazione`
--
ALTER TABLE `specializzazione`
  ADD PRIMARY KEY (`ID_Specializzazione`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`Email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `centro_medico`
--
ALTER TABLE `centro_medico`
  MODIFY `ID_CentroMedico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `prenotazione`
--
ALTER TABLE `prenotazione`
  MODIFY `ID_Prenotazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT per la tabella `prestazione`
--
ALTER TABLE `prestazione`
  MODIFY `ID_Prestazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT per la tabella `referto`
--
ALTER TABLE `referto`
  MODIFY `ID_Referto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `specializzazione`
--
ALTER TABLE `specializzazione`
  MODIFY `ID_Specializzazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `prenotazione`
--
ALTER TABLE `prenotazione`
  ADD CONSTRAINT `prenotazione_ibfk_1` FOREIGN KEY (`Utente`) REFERENCES `utente` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prenotazione_ibfk_2` FOREIGN KEY (`Medico`) REFERENCES `utente` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prenotazione_ibfk_3` FOREIGN KEY (`Prestazione`) REFERENCES `prestazione` (`ID_Prestazione`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prenotazione_ibfk_4` FOREIGN KEY (`CentroMedico`) REFERENCES `centro_medico` (`ID_CentroMedico`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `prestazione`
--
ALTER TABLE `prestazione`
  ADD CONSTRAINT `prestazione_ibfk_1` FOREIGN KEY (`Specializzazione`) REFERENCES `specializzazione` (`ID_Specializzazione`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `referto`
--
ALTER TABLE `referto`
  ADD CONSTRAINT `referto_ibfk_1` FOREIGN KEY (`Prenotazione`) REFERENCES `prenotazione` (`ID_Prenotazione`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `specializzato`
--
ALTER TABLE `specializzato`
  ADD CONSTRAINT `specializzato_ibfk_1` FOREIGN KEY (`Specializzante`) REFERENCES `utente` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `specializzato_ibfk_2` FOREIGN KEY (`Specializzato`) REFERENCES `specializzazione` (`ID_Specializzazione`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
