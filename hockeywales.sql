-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 12, 2023 at 08:55 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hockeywales`
--

-- --------------------------------------------------------

--
-- Table structure for table `apperance`
--

DROP TABLE IF EXISTS `apperance`;
CREATE TABLE IF NOT EXISTS `apperance` (
  `appearanceID` int(128) NOT NULL AUTO_INCREMENT,
  `playerID` int(128) NOT NULL,
  `numOfAppearances` int(128) NOT NULL,
  PRIMARY KEY (`appearanceID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `apperance`
--

INSERT INTO `apperance` (`appearanceID`, `playerID`, `numOfAppearances`) VALUES
(1, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `assist`
--

DROP TABLE IF EXISTS `assist`;
CREATE TABLE IF NOT EXISTS `assist` (
  `assistID` int(128) NOT NULL AUTO_INCREMENT,
  `playerID` int(128) NOT NULL,
  `numOfAssists` int(128) NOT NULL,
  PRIMARY KEY (`assistID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assist`
--

INSERT INTO `assist` (`assistID`, `playerID`, `numOfAssists`) VALUES
(1, 2, 12),
(2, 6, 2),
(3, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

DROP TABLE IF EXISTS `availability`;
CREATE TABLE IF NOT EXISTS `availability` (
  `availabilityID` int(11) NOT NULL AUTO_INCREMENT,
  `playerID` int(11) NOT NULL,
  `fixtureID` int(11) NOT NULL,
  `available` tinyint(1) NOT NULL,
  PRIMARY KEY (`availabilityID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `availability`
--

INSERT INTO `availability` (`availabilityID`, `playerID`, `fixtureID`, `available`) VALUES
(1, 2, 2, 1),
(2, 4, 6, 1),
(3, 2, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `club`
--

DROP TABLE IF EXISTS `club`;
CREATE TABLE IF NOT EXISTS `club` (
  `clubID` int(11) NOT NULL AUTO_INCREMENT,
  `clubName` varchar(128) NOT NULL,
  PRIMARY KEY (`clubID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `club`
--

INSERT INTO `club` (`clubID`, `clubName`) VALUES
(1, 'Whitchurch'),
(2, 'Gwent'),
(3, 'Swansea'),
(4, 'Cardiff university'),
(5, 'Clifton Robinsons'),
(6, 'Bridgend'),
(7, 'Neath'),
(8, 'Cardiff and Met');

-- --------------------------------------------------------

--
-- Table structure for table `clubadmin`
--

DROP TABLE IF EXISTS `clubadmin`;
CREATE TABLE IF NOT EXISTS `clubadmin` (
  `clubAdminID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(128) NOT NULL,
  `lastName` varchar(128) NOT NULL,
  `clubID` int(11) NOT NULL,
  `DOB` date NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`clubAdminID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clubadmin`
--

INSERT INTO `clubadmin` (`clubAdminID`, `firstName`, `lastName`, `clubID`, `DOB`, `userID`) VALUES
(1, 'Alex', 'Thomas', 1, '2002-01-15', 21),
(2, 'Marcus', 'Hodge', 3, '2007-02-04', 26),
(3, 'Cardiff', 'Met', 8, '2007-02-01', 27);

-- --------------------------------------------------------

--
-- Table structure for table `fixture`
--

DROP TABLE IF EXISTS `fixture`;
CREATE TABLE IF NOT EXISTS `fixture` (
  `fixtureID` int(11) NOT NULL AUTO_INCREMENT,
  `homeTeamID` int(11) NOT NULL,
  `awayTeamID` int(11) NOT NULL,
  `dateTime` time NOT NULL,
  `location` varchar(128) NOT NULL,
  `leagueID` int(11) NOT NULL,
  `matchWeek` int(11) NOT NULL,
  PRIMARY KEY (`fixtureID`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fixture`
--

INSERT INTO `fixture` (`fixtureID`, `homeTeamID`, `awayTeamID`, `dateTime`, `location`, `leagueID`, `matchWeek`) VALUES
(6, 1, 4, '11:25:01', 'Whitchurch high school', 1, 4),
(7, 2, 5, '19:07:52', 'Treforest', 1, 4),
(8, 4, 9, '19:07:52', 'Neath', 1, 4),
(9, 11, 7, '19:07:52', 'WIS', 1, 4),
(10, 10, 6, '19:07:52', 'Cmwbran', 1, 4),
(54, 9, 1, '11:25:01', 'Whitchurch high school', 1, 6),
(53, 10, 9, '19:07:52', 'Cmwbran', 1, 6),
(52, 10, 9, '19:07:52', 'Cmwbran', 1, 5),
(48, 1, 8, '11:25:01', 'Whitchurch high school', 1, 5),
(51, 11, 5, '19:07:52', 'WIS', 1, 5),
(50, 4, 6, '19:07:52', 'Neath', 1, 5),
(49, 2, 7, '19:07:52', 'Treforest', 1, 5),
(55, 5, 4, '19:07:52', 'WIS', 1, 6),
(56, 11, 2, '19:07:52', 'Neath', 1, 6),
(57, 6, 7, '19:07:52', 'Treforest', 1, 6),
(58, 6, 7, '19:07:52', 'Treforest', 1, 7),
(59, 11, 2, '19:07:52', 'Neath', 1, 7),
(60, 5, 4, '19:07:52', 'WIS', 1, 7),
(61, 9, 1, '11:25:01', 'Whitchurch high school', 1, 7),
(62, 10, 9, '19:07:52', 'Cmwbran', 1, 7),
(63, 10, 9, '19:07:52', 'Cmwbran', 1, 8),
(64, 11, 5, '19:07:52', 'WIS', 1, 8),
(65, 4, 6, '19:07:52', 'Neath', 1, 8),
(66, 2, 7, '19:07:52', 'Treforest', 1, 8),
(67, 1, 8, '11:25:01', 'Whitchurch high school', 1, 8),
(68, 10, 9, '19:07:52', 'Cmwbran', 1, 9),
(69, 11, 5, '19:07:52', 'WIS', 1, 9),
(70, 4, 6, '19:07:52', 'Neath', 1, 9),
(71, 2, 7, '19:07:52', 'Treforest', 1, 9),
(72, 1, 8, '11:25:01', 'Whitchurch high school', 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `gameweek`
--

DROP TABLE IF EXISTS `gameweek`;
CREATE TABLE IF NOT EXISTS `gameweek` (
  `gameWeekID` int(11) NOT NULL AUTO_INCREMENT,
  `gameDate` date NOT NULL,
  PRIMARY KEY (`gameWeekID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gameweek`
--

INSERT INTO `gameweek` (`gameWeekID`, `gameDate`) VALUES
(1, '2023-01-28'),
(2, '2023-02-04'),
(3, '2023-02-11'),
(4, '2023-02-18'),
(5, '2023-02-25'),
(6, '2023-03-04'),
(7, '2023-03-11'),
(8, '2023-03-18'),
(9, '2023-03-25');

-- --------------------------------------------------------

--
-- Table structure for table `goal`
--

DROP TABLE IF EXISTS `goal`;
CREATE TABLE IF NOT EXISTS `goal` (
  `goalsID` int(128) NOT NULL AUTO_INCREMENT,
  `playerID` int(128) NOT NULL,
  `numOfGoals` int(128) NOT NULL,
  PRIMARY KEY (`goalsID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `goal`
--

INSERT INTO `goal` (`goalsID`, `playerID`, `numOfGoals`) VALUES
(1, 2, 7),
(2, 4, 10),
(3, 5, 12),
(4, 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `league`
--

DROP TABLE IF EXISTS `league`;
CREATE TABLE IF NOT EXISTS `league` (
  `leagueID` int(11) NOT NULL,
  `leagueName` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `league`
--

INSERT INTO `league` (`leagueID`, `leagueName`) VALUES
(1, 'League 1'),
(2, 'League 2');

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
CREATE TABLE IF NOT EXISTS `player` (
  `playerID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(128) NOT NULL,
  `lastName` varchar(128) NOT NULL,
  `teamID` int(11) NOT NULL,
  `DOB` date NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`playerID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`playerID`, `firstName`, `lastName`, `teamID`, `DOB`, `userID`) VALUES
(2, 'David', 'John', 1, '2004-02-05', 13),
(4, 'Robert', 'Paul', 2, '2023-02-06', 25),
(5, 'Peter', 'Jones', 2, '2003-10-01', 14),
(6, 'Met', 'Player', 11, '2007-02-04', 28);

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS `result`;
CREATE TABLE IF NOT EXISTS `result` (
  `resultID` int(11) NOT NULL AUTO_INCREMENT,
  `homeTeamID` int(11) NOT NULL,
  `awayTeamID` int(11) NOT NULL,
  `homeTeamScore` int(11) NOT NULL,
  `awayTeamScore` int(11) NOT NULL,
  `leagueID` int(11) NOT NULL,
  `matchWeek` int(11) NOT NULL,
  PRIMARY KEY (`resultID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`resultID`, `homeTeamID`, `awayTeamID`, `homeTeamScore`, `awayTeamScore`, `leagueID`, `matchWeek`) VALUES
(1, 1, 2, 3, 2, 1, 1),
(2, 4, 5, 4, 2, 1, 1),
(3, 6, 7, 2, 2, 1, 1),
(4, 8, 9, 3, 0, 1, 1),
(5, 10, 11, 3, 1, 1, 1),
(6, 1, 4, 5, 2, 1, 2),
(7, 2, 5, 7, 0, 1, 2),
(8, 6, 9, 1, 0, 1, 2),
(9, 11, 3, 3, 0, 1, 2),
(10, 10, 8, 0, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `teamID` int(11) NOT NULL AUTO_INCREMENT,
  `clubID` int(11) NOT NULL,
  `leagueID` int(11) NOT NULL,
  `teamName` varchar(128) NOT NULL,
  PRIMARY KEY (`teamID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`teamID`, `clubID`, `leagueID`, `teamName`) VALUES
(1, 1, 1, 'Whitchurch 1\'s'),
(2, 1, 1, 'Whitchurch 2\'s'),
(4, 2, 1, 'Gwent 1\'s'),
(3, 2, 2, 'Gwent 2\'s'),
(5, 3, 1, 'Swansea 1\'s'),
(6, 4, 1, 'Cardiff University 1\'s'),
(7, 5, 1, 'Clifton Robinsons 1\'s'),
(8, 6, 1, 'Bridgend 1\'s'),
(9, 7, 1, 'Neath 1\'s'),
(10, 8, 1, 'Cardiff and Met 1\'s'),
(11, 8, 1, 'Cardiff and Met 2\'s'),
(12, 1, 2, 'Whitchurch 3\'s');

-- --------------------------------------------------------

--
-- Table structure for table `tempplayer`
--

DROP TABLE IF EXISTS `tempplayer`;
CREATE TABLE IF NOT EXISTS `tempplayer` (
  `tempPlayerID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(128) NOT NULL,
  `lastName` varchar(128) NOT NULL,
  `clubID` int(11) NOT NULL,
  `DOB` date NOT NULL,
  `tempUserID` int(11) NOT NULL,
  PRIMARY KEY (`tempPlayerID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tempplayer`
--

INSERT INTO `tempplayer` (`tempPlayerID`, `firstName`, `lastName`, `clubID`, `DOB`, `tempUserID`) VALUES
(6, 'Bobby', 'Thomas', 1, '2007-02-01', 9);

-- --------------------------------------------------------

--
-- Table structure for table `tempresult`
--

DROP TABLE IF EXISTS `tempresult`;
CREATE TABLE IF NOT EXISTS `tempresult` (
  `tempResultID` int(11) NOT NULL AUTO_INCREMENT,
  `homeTeamID` int(11) NOT NULL,
  `awayTeamID` int(11) NOT NULL,
  `homeTeamScore` int(11) DEFAULT NULL,
  `awayTeamScore` int(11) DEFAULT NULL,
  `leagueID` int(11) NOT NULL,
  `matchWeek` int(11) NOT NULL,
  `status` varchar(128) NOT NULL,
  PRIMARY KEY (`tempResultID`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tempresult`
--

INSERT INTO `tempresult` (`tempResultID`, `homeTeamID`, `awayTeamID`, `homeTeamScore`, `awayTeamScore`, `leagueID`, `matchWeek`, `status`) VALUES
(8, 2, 5, 2, 1, 1, 2, 'sent'),
(11, 11, 1, 2, 2, 1, 3, 'sent'),
(10, 1, 3, 2, 1, 1, 2, 'sent'),
(12, 2, 10, NULL, NULL, 1, 3, 'waiting'),
(13, 4, 9, NULL, NULL, 1, 3, 'waiting'),
(14, 5, 8, NULL, NULL, 1, 3, 'waiting'),
(15, 6, 7, NULL, NULL, 1, 3, 'waiting');

-- --------------------------------------------------------

--
-- Table structure for table `tempuser`
--

DROP TABLE IF EXISTS `tempuser`;
CREATE TABLE IF NOT EXISTS `tempuser` (
  `tempUserID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `accountType` char(11) NOT NULL,
  PRIMARY KEY (`tempUserID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tempuser`
--

INSERT INTO `tempuser` (`tempUserID`, `email`, `password`, `accountType`) VALUES
(9, 'Bobby@player.com', '$2y$10$aDbIgGfh0dbR3mS2EtpKDeGw9n6hYJtIh8uJ5wFTjlMchwAIHhVJ2', 'Player');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `accountType` char(11) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `email`, `password`, `accountType`) VALUES
(13, 'WillMorgan@example.com', '$2y$10$See9ABqGw9YeruPG.FuymOA6QySG8z.6OqlCArviUyUJ71YBlJppe', 'Player'),
(14, 'BobbyThomas@example.com', '$2y$10$GUFGjbI3ELnMjyoMLHkLme18kFjF4aUrmgxZ0OIbumQEHJYloLnLG', 'Player'),
(21, 'Whitchurch@example.com', '$2y$10$pr7AINovXlzTcAXQeZPCJu5n/Pm6PkJ5ovvFnX18tJeJHa/HUpSWi', 'Club Admin'),
(27, 'cardiffMet@example.com', '$2y$10$DtIy7C/OqDLNUY4ocfbKsuuHHNUhqVdO4eR5OG.d3zzNjIpbqYTyy', 'Club Admin'),
(26, 'Swansea@example.com', '$2y$10$KL8NhTeor4o3KOJny9A3o.YnTRNqKt896J7mPcUJKURxZBlf4uxbq', 'Club Admin'),
(25, 'KevinTitus@example.com', '$2y$10$kd8nCb0EbE7BHUWCFoVrkefkSLyZPipelaL0bVJRs8Xkf8qJPhSZa', 'Player'),
(28, 'metPlayer@example.com', '$2y$10$ben3A6X705BZKveVJXZmp.GYQNySvyB2qNeP8qtP6xZdiaG3W6AAC', 'Player');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
