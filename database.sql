-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Wersja serwera:               5.7.20-0ubuntu0.16.04.1 - (Ubuntu)
-- Serwer OS:                    Linux
-- HeidiSQL Wersja:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Zrzut struktury tabela info.adve
CREATE TABLE IF NOT EXISTS `adve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `author` varchar(50) NOT NULL DEFAULT '',
  `scopetype` varchar(50) NOT NULL DEFAULT '',
  `isOn` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addedDate` date NOT NULL,
  `addedHour` time NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.adve: ~5 rows (około)
/*!40000 ALTER TABLE `adve` DISABLE KEYS */;
INSERT INTO `adve` (`id`, `uid`, `title`, `content`, `author`, `scopetype`, `isOn`, `addedDate`, `addedHour`) VALUES
	(19, '560c26c81f010', 'Wpłaty składek ubezpieczeniowych', 'Składki ubezpieczeniowe wpłacamy najpóźniej do 10 dnia każdego miesiąca. Proszę o potraktowanie tematu poważnie.', '38', 'klasowe', 'Y', '2015-09-30', '20:15:36'),
	(20, '560c27088db62', 'Codzienne odprawy dyrekcji', 'Codziennie w moim biurze widzimy się o 7:30 by przedyskutować plan działania na dany dzień. Proszę o punktualność.', '38', 'grupowe', 'Y', '2015-09-30', '20:16:40'),
	(21, '560c2742cea1a', 'Dbałość o kulturę pracy', 'Bardzo proszę po skończonej pracy zostawić używane rękawiczki w przeznaczonym do tego kontenerze przy wyjściu. Idą one każdegj nocy do pralni.', '38', 'grupowe', 'Y', '2015-09-30', '20:17:38'),
	(22, '560c288f5e5df', 'Przypomnienie o cotygodniowych raportach', 'Pamiętajcie, że co tydzień oczekuję raportu na temat postępów w projektach', '38', 'grupowe', 'Y', '2015-09-30', '20:23:11'),
	(23, '560c2c18b0a4e', 'W sytuacji konfliktu...', 'Gdybyście mieli jakiekolwiek większe problemy z porozumieniem się z pracownikami, udajcie się do mnie, a nie dajcie się ponieść emocjom. Pozdrawiam :)', '38', 'grupowe', 'Y', '2015-09-30', '20:38:16');
/*!40000 ALTER TABLE `adve` ENABLE KEYS */;

-- Zrzut struktury tabela info.adve_scopes
CREATE TABLE IF NOT EXISTS `adve_scopes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adve_uid` varchar(50) NOT NULL DEFAULT '',
  `scope` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `scope_z_ogloszeniem` (`adve_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.adve_scopes: ~7 rows (około)
/*!40000 ALTER TABLE `adve_scopes` DISABLE KEYS */;
INSERT INTO `adve_scopes` (`id`, `adve_uid`, `scope`) VALUES
	(19, '560c26c81f010', 'pracownicy'),
	(20, '560c27088db62', 'pracownicy'),
	(21, '560c27088db62', '560c20296e534'),
	(22, '560c2742cea1a', 'pracownicy'),
	(23, '560c2742cea1a', '560c2016aa3f5'),
	(24, '560c288f5e5df', '560c219625a7a'),
	(25, '560c2c18b0a4e', '560c1fab20620');
/*!40000 ALTER TABLE `adve_scopes` ENABLE KEYS */;

-- Zrzut struktury tabela info.entries
CREATE TABLE IF NOT EXISTS `entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) NOT NULL DEFAULT '',
  `author` varchar(50) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `scope` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(500) NOT NULL DEFAULT '',
  `parent_uid` varchar(50) NOT NULL DEFAULT '',
  `added_date` date NOT NULL,
  `added_time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.entries: ~11 rows (około)
/*!40000 ALTER TABLE `entries` DISABLE KEYS */;
INSERT INTO `entries` (`id`, `uid`, `author`, `content`, `scope`, `link`, `parent_uid`, `added_date`, `added_time`) VALUES
	(82, '560c280d7b932', '38', 'Ciekawi mnie jak idzie kontrola :)', 'gr560c20c19812e', '', '', '2015-09-30', '20:21:01'),
	(83, '560c283f54558', '38', 'Jak stoimy z kadrami? Każdy ma co robić?', 'gr560c1fab20620', '', '', '2015-09-30', '20:21:51'),
	(84, '560c2a088b483', '55', 'Jakich ochraniaczy na oczy używacie?', 'gr560c2016aa3f5', '', '', '2015-09-30', '20:29:28'),
	(85, '560c2b698931b', '46', 'Jak najbardziej Panie Dyrektorze ;)', 'gr560c1fab20620', '', '560c283f54558', '2015-09-30', '20:35:21'),
	(86, '560c2b9672e94', '38', 'To najważniejsze :)', 'gr560c1fab20620', '', '560c283f54558', '2015-09-30', '20:36:06'),
	(87, '560c2c7a8608f', '62', 'Macie jakieś pomysły na nowe wzory??', 'gr560c219625a7a', '', '', '2015-09-30', '20:39:54'),
	(89, '560c2cfb8ead8', '63', 'To wygląda nieźle (w linku obrazek) ;)', 'gr560c219625a7a', 'http://gokczyzew.pl/sites/all/files/images/16474-f_bombka_z_zimowym_pejzazem_folkstar[1].jpg', '560c2c7a8608f', '2015-09-30', '20:42:03'),
	(90, '560c2d0ee88b4', '62', ':o owszem nieźle', 'gr560c219625a7a', '', '560c2c7a8608f', '2015-09-30', '20:42:22'),
	(91, '560c2def3bfc1', '38', 'Proszę pisać w wszelkich problemach w pracy. Służę pomocą :)', 'clpracownicy', 'http://bonifratrzy.pl/data/images/szpitale/Marysin/legalna_praca.jpg', '', '2015-09-30', '20:46:07'),
	(93, '560c2e08488d0', '62', 'jaki zachęcający obrazek :)', 'clpracownicy', '', '560c2def3bfc1', '2015-09-30', '20:46:32'),
	(94, '57e695ad5c4f4', '1', 'Tak :)', 'gr560c1fab20620', '', '560c283f54558', '2016-09-24', '17:03:09'),
	(95, '5b01d1a997eb3', '1', 'Tylko z plexi-glassu', 'gr560c2016aa3f5', '', '560c2a088b483', '2018-05-20', '21:51:05');
/*!40000 ALTER TABLE `entries` ENABLE KEYS */;

-- Zrzut struktury tabela info.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `author_id` varchar(50) NOT NULL,
  `text` varchar(100) NOT NULL,
  `details` varchar(320) NOT NULL DEFAULT '',
  `subject` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `day` varchar(2) NOT NULL,
  `month` varchar(2) NOT NULL,
  `year` varchar(4) NOT NULL,
  `added_date` varchar(15) NOT NULL,
  `added_hour` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.events: ~7 rows (około)
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`id`, `author_id`, `text`, `details`, `subject`, `type`, `uid`, `day`, `month`, `year`, `added_date`, `added_hour`) VALUES
	(57, '38', 'Omówienie strategii PR i marketingu', 'Spotkamy się w małej sali konferencyjnej o 9:00. Zapraszam!', 'spotkanie', 'grupowe', '560c26250b71a', '2', '10', '2015', '2015-09-30', '20:12:53'),
	(60, '38', 'Ostateczny termin zdania wzorów wstępnych', 'Proszę do 16.10 dostarczyć pierwsze 5 wzorów wstępnych do rozpatrzenia.', 'termin', 'grupowe', '560c27a206b7a', '16', '10', '2015', '2015-09-30', '20:19:14'),
	(62, '38', 'Omówienie modernizacji infrastruktury sieciowej', 'Spotkamy się w małej sali konferencyjnej o 10:00.\nProszę dział finansowy o przygotowanie raportu nt. wolnych środków w budżecie.', 'spotkanie', 'grupowe', '560c27b13b969', '13', '10', '2015', '2015-09-30', '20:19:29'),
	(64, '38', 'Wigilia pracownicza', '', 'święto', 'klasowe', '560c27f36691f', '23', '12', '2015', '2015-09-30', '20:20:35'),
	(65, '38', 'Spotkanie w sprawie nowych pracowników', '8:00 - mała sala konferencyjna', 'spotkanie', 'grupowe', '560c2bc97c1bf', '14', '10', '2015', '2015-09-30', '20:36:57'),
	(67, '38', 'Możliwy awans', 'Chciałbym omówić warunki przeniesienia na wyższe stanowisko.\n11:00 - moje biuro', 'spotkanie', 'indywidualne', '560c2da97db40', '16', '10', '2015', '2015-09-30', '20:44:57'),
	(68, '1', 'Spotkanie', 'W sali konferencyjnej nr 201.', 'spotkanie', 'klasowe', '586c20a60d236', '11', '01', '2017', '2017-01-03', '23:07:34');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;

-- Zrzut struktury tabela info.events_scopes
CREATE TABLE IF NOT EXISTS `events_scopes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_uid` varchar(50) NOT NULL DEFAULT '',
  `scope` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.events_scopes: ~9 rows (około)
/*!40000 ALTER TABLE `events_scopes` DISABLE KEYS */;
INSERT INTO `events_scopes` (`id`, `event_uid`, `scope`) VALUES
	(81, '560c26250b71a', '560c1fc905cd5'),
	(82, '560c26250b71a', '560c1fe2d1379'),
	(86, '560c27a206b7a', '560c20e6c898d'),
	(89, '560c27b13b969', '560c1f84d04e9'),
	(90, '560c27b13b969', '560c1f9c55740'),
	(92, '560c27f36691f', 'pracownicy'),
	(93, '560c2bc97c1bf', '560c1fab20620'),
	(95, '560c2da97db40', '62'),
	(96, '586c20a60d236', 'pracownicy');
/*!40000 ALTER TABLE `events_scopes` ENABLE KEYS */;

-- Zrzut struktury tabela info.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `author` int(10) NOT NULL,
  `people` varchar(255) NOT NULL DEFAULT '',
  `modified` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Zrzucanie danych dla tabeli info.favorites: ~0 rows (około)
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;

-- Zrzut struktury tabela info.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL,
  `author_id` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.groups: ~11 rows (około)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`id`, `uid`, `name`, `author_id`) VALUES
	(24, '560c1f84d04e9', 'dział techniczny', '1'),
	(25, '560c1f9c55740', 'dział finansowy', '1'),
	(26, '560c1fab20620', 'dział HR', '1'),
	(27, '560c1fc905cd5', 'dział PR', '1'),
	(28, '560c1fe2d1379', 'dział marketingu', '1'),
	(29, '560c2016aa3f5', 'dział produkcyjny', '1'),
	(30, '560c20296e534', 'dyrekcja', '1'),
	(31, '560c20837e997', 'zespół wdrażania nowych technologii', '1'),
	(32, '560c20c19812e', 'zespół kontroli sprzętów na hali', '1'),
	(34, '560c219625a7a', 'dział wzornictwa', '1'),
	(35, '560c20e6c898d', 'zespół projektowy nowatorskich wzorów', '1');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Zrzut struktury tabela info.group_members
CREATE TABLE IF NOT EXISTS `group_members` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `groupuid` varchar(50) NOT NULL,
  `memberid` int(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.group_members: ~35 rows (około)
/*!40000 ALTER TABLE `group_members` DISABLE KEYS */;
INSERT INTO `group_members` (`id`, `groupuid`, `memberid`) VALUES
	(46, '560c1f84d04e9', 38),
	(47, '560c1f84d04e9', 41),
	(48, '560c1f84d04e9', 40),
	(49, '560c1f84d04e9', 39),
	(50, '560c1f9c55740', 42),
	(51, '560c1f9c55740', 43),
	(52, '560c1f9c55740', 44),
	(53, '560c1fab20620', 45),
	(54, '560c1fab20620', 46),
	(55, '560c1fc905cd5', 47),
	(56, '560c1fc905cd5', 48),
	(57, '560c1fc905cd5', 49),
	(58, '560c1fe2d1379', 51),
	(59, '560c1fe2d1379', 50),
	(60, '560c2016aa3f5', 54),
	(61, '560c2016aa3f5', 56),
	(62, '560c2016aa3f5', 52),
	(63, '560c2016aa3f5', 57),
	(64, '560c2016aa3f5', 58),
	(65, '560c2016aa3f5', 55),
	(66, '560c2016aa3f5', 53),
	(67, '560c20296e534', 38),
	(68, '560c20296e534', 59),
	(69, '560c20296e534', 60),
	(70, '560c20837e997', 41),
	(71, '560c20837e997', 44),
	(72, '560c20837e997', 58),
	(73, '560c20837e997', 50),
	(74, '560c20c19812e', 38),
	(75, '560c20c19812e', 53),
	(78, '560c219625a7a', 63),
	(79, '560c219625a7a', 62),
	(80, '560c20e6c898d', 52),
	(81, '560c20e6c898d', 53),
	(82, '560c20e6c898d', 62);
/*!40000 ALTER TABLE `group_members` ENABLE KEYS */;

-- Zrzut struktury tabela info.marks
CREATE TABLE IF NOT EXISTS `marks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_uid` varchar(50) NOT NULL DEFAULT '',
  `entry_parent_uid` varchar(50) NOT NULL DEFAULT '',
  `user_id` varchar(50) NOT NULL DEFAULT '',
  `type` enum('plus','minus') NOT NULL DEFAULT 'plus',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.marks: ~3 rows (około)
/*!40000 ALTER TABLE `marks` DISABLE KEYS */;
INSERT INTO `marks` (`id`, `entry_uid`, `entry_parent_uid`, `user_id`, `type`) VALUES
	(100, '560c283f54558', '', '46', 'plus'),
	(101, '560c2b698931b', '560c283f54558', '38', 'plus'),
	(102, '560c2cfb8ead8', '560c2c7a8608f', '62', 'plus'),
	(105, '560c2a088b483', '', '1', 'plus');
/*!40000 ALTER TABLE `marks` ENABLE KEYS */;

-- Zrzut struktury tabela info.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sender` varchar(10) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `recipient` varchar(10) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL DEFAULT '',
  `continuationof` varchar(10) NOT NULL DEFAULT '',
  `date_when_sent` varchar(15) NOT NULL DEFAULT '',
  `hour_when_sent` varchar(15) NOT NULL DEFAULT '',
  `ifread` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.messages: ~0 rows (około)
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;

-- Zrzut struktury tabela info.modifications
CREATE TABLE IF NOT EXISTS `modifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL DEFAULT '',
  `date_field` date NOT NULL,
  `time_field` time NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_name` (`table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.modifications: ~8 rows (około)
/*!40000 ALTER TABLE `modifications` DISABLE KEYS */;
INSERT INTO `modifications` (`id`, `table_name`, `date_field`, `time_field`) VALUES
	(1, 'adve', '2015-09-28', '22:01:51'),
	(2, 'adve_scopes', '2015-09-28', '20:37:43'),
	(3, 'events', '2015-09-28', '20:37:49'),
	(4, 'events_scopes', '2015-09-28', '20:38:19'),
	(5, 'groups', '2015-09-28', '20:38:27'),
	(6, 'group_members', '2015-09-28', '20:38:37'),
	(7, 'replacements', '2015-09-28', '20:38:46'),
	(8, 'users', '2015-09-28', '20:39:16');
/*!40000 ALTER TABLE `modifications` ENABLE KEYS */;

-- Zrzut struktury tabela info.replacements
CREATE TABLE IF NOT EXISTS `replacements` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `replaced` varchar(100) NOT NULL,
  `replacing` varchar(100) NOT NULL,
  `lesson` varchar(50) NOT NULL,
  `room` varchar(50) NOT NULL,
  `scope` varchar(50) NOT NULL,
  `scopetype` varchar(15) NOT NULL DEFAULT '',
  `subject` varchar(50) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.replacements: ~0 rows (około)
/*!40000 ALTER TABLE `replacements` DISABLE KEYS */;
/*!40000 ALTER TABLE `replacements` ENABLE KEYS */;

-- Zrzut struktury tabela info.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '1234',
  `email` varchar(100) NOT NULL DEFAULT '',
  `user_class` varchar(50) NOT NULL,
  `passwordToBeChanged` enum('Y','N') NOT NULL DEFAULT 'Y',
  `loggedin` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli info.users: ~28 rows (około)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `email`, `user_class`, `passwordToBeChanged`, `loggedin`) VALUES
	(1, 'Piotr Muzyczuk', '75cbe02ec058001f0c7c59d4a4129a7177b47c2ed3aa52feba0de4b5d94c68e1', 'muzyczukp@me.com', 'administrator', 'N', 'N'),
	(38, '﻿Nowak Walerian', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'walerian.nowak@gmail.com', 'dyrektor', 'N', 'N'),
	(39, 'Sowa Zbigniew', '1234', 'zbigniew.sowa@gmail.com', 'pracownicy', 'Y', 'N'),
	(40, 'Domańska Grażyna', '1234', 'grazyna.domanska@gmail.com', 'pracownicy', 'Y', 'N'),
	(41, 'Brobek Rafał', '1234', 'rafal.brobek@gmail.com', 'pracownicy', 'Y', 'N'),
	(42, 'Drozda Andrzej', '1234', 'andrzej.drozda@gmail.com', 'pracownicy', 'Y', 'N'),
	(43, 'Greczyn Pelagia', '1234', 'pelagia.greczyn@gmail.com', 'pracownicy', 'Y', 'N'),
	(44, 'Jadziak Andrzej', '1234', 'andrzej.jadziak@gmail.com', 'pracownicy', 'Y', 'N'),
	(45, 'Kobek Rafał', '1234', 'rafal.kobek@gmail.com', 'pracownicy', 'Y', 'N'),
	(46, 'Komornicki Michał', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'michal.komornicki@gmail.com', 'pracownicy', 'N', 'N'),
	(47, 'Komos Michał', '1234', 'michal.komos@gmail.com', 'pracownicy', 'Y', 'N'),
	(48, 'Kowalski Tadeusz', '1234', 'tadeusz.kowalski@gmail.com', 'pracownicy', 'Y', 'N'),
	(49, 'Liczak Robert', '1234', 'robert.liczak@gmail.com', 'pracownicy', 'Y', 'N'),
	(50, 'Sulik Wiktor', '1234', 'wiktor.sulik@gmail.com', 'pracownicy', 'Y', 'N'),
	(51, 'Dąbrowska Wioletta', '1234', 'wioletta.dabrowska@gmail.com', 'pracownicy', 'Y', 'N'),
	(52, 'Kaczmarczyk Urszula', '1234', 'urszula.kaczmarczyk@gmail.com', 'pracownicy', 'Y', 'N'),
	(53, 'Żeligowski Edward', '1234', 'edward.zeligowski@gmail.com', 'pracownicy', 'Y', 'N'),
	(54, 'Gruda Barbara', '1234', 'barbara.gruda@gmail.com', 'pracownicy', 'Y', 'N'),
	(55, 'Policka Krystyna', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'krystyna.policka@gmail.com', 'pracownicy', 'N', 'N'),
	(56, 'Jolecka Jadwiga', '1234', 'jadwiga.jolecka@gmail.com', 'pracownicy', 'Y', 'N'),
	(57, 'Kąkol Zbigniew', '1234', 'zbigniew.kakol@gmail.com', 'pracownicy', 'Y', 'N'),
	(58, 'Kiszewska Barbara', '1234', 'barbara.kiszewska@gmail.com', 'pracownicy', 'Y', 'N'),
	(59, 'Tuja Katarzyna', '1234', 'katarzyna.tuja@gmail.com', 'dyrektor', 'Y', 'N'),
	(60, 'Wróbel Rafał', '1234', 'rafal.wrobel@gmail.com', 'dyrektor', 'Y', 'N'),
	(61, 'Koja Wiesław', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'wieslaw.koja@gmail.com', 'administrator', 'N', 'N'),
	(62, 'Rowerowicz Michał', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'michal.rowerowicz@gmail.com', 'pracownicy', 'N', 'N'),
	(63, 'Dwernik Zbigniew', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'zbigniew.dwernik@gmail.com', 'pracownicy', 'N', 'N'),
	(64, 'sample', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'sample@example.com', 'administrator', 'N', 'N');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
