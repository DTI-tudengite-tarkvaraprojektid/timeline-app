-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Loomise aeg: Mai 02, 2019 kell 05:17 PM
-- Serveri versioon: 10.2.22-MariaDB
-- PHP versioon: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Andmebaas: `if18_steven_sa_2`
--

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) NOT NULL,
  `event_id` int(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `removed` timestamp NULL DEFAULT NULL,
  `type` enum('TEXT','URL','IMAGE','FILE','VIDEO','AUDIO') NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `timeline_id` int(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `removed` timestamp NULL DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `title` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `timeline`
--

CREATE TABLE IF NOT EXISTS `timeline` (
  `id` int(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `removed` timestamp NULL DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `removed` timestamp NULL DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indeksid tõmmistatud tabelitele
--

--
-- Indeksid tabelile `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FKcontent545468` (`event_id`);

--
-- Indeksid tabelile `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `FKevent249610` (`timeline_id`);

--
-- Indeksid tabelile `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeksid tabelile `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT tõmmistatud tabelitele
--

--
-- AUTO_INCREMENT tabelile `content`
--
ALTER TABLE `content`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT tabelile `event`
--
ALTER TABLE `event`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT tabelile `timeline`
--
ALTER TABLE `timeline`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT tabelile `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- Tõmmistatud tabelite piirangud
--

--
-- Piirangud tabelile `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `FKcontent545468` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`);

--
-- Piirangud tabelile `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `FKevent249610` FOREIGN KEY (`timeline_id`) REFERENCES `timeline` (`id`),
  ADD CONSTRAINT `FKevent638751` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
