-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 05, 2023 at 07:53 PM
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assist`
--

INSERT INTO `assist` (`assistID`, `playerID`, `numOfAssists`) VALUES
(1, 2, 12);

-- --------------------------------------------------------

--
-- Table structure for table `club`
--

DROP TABLE IF EXISTS `club`;
CREATE TABLE IF NOT EXISTS `club` (
  `clubID` int(11) NOT NULL AUTO_INCREMENT,
  `clubName` varchar(128) NOT NULL,
  PRIMARY KEY (`clubID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `club`
--

INSERT INTO `club` (`clubID`, `clubName`) VALUES
(1, 'Whitchurch'),
(2, 'Gwent'),
(3, 'Swansea');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clubadmin`
--

INSERT INTO `clubadmin` (`clubAdminID`, `firstName`, `lastName`, `clubID`, `DOB`, `userID`) VALUES
(1, 'testy', 'testy', 1, '2023-02-15', 21);

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fixture`
--

INSERT INTO `fixture` (`fixtureID`, `homeTeamID`, `awayTeamID`, `dateTime`, `location`, `leagueID`, `matchWeek`) VALUES
(1, 1, 2, '19:07:52', 'WIS', 1, 2),
(2, 2, 4, '19:07:52', 'DK', 1, 2),
(3, 1, 5, '19:07:52', 'London', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `gameweek`
--

DROP TABLE IF EXISTS `gameweek`;
CREATE TABLE IF NOT EXISTS `gameweek` (
  `gameWeekID` int(11) NOT NULL AUTO_INCREMENT,
  `gameDate` date NOT NULL,
  PRIMARY KEY (`gameWeekID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gameweek`
--

INSERT INTO `gameweek` (`gameWeekID`, `gameDate`) VALUES
(1, '2023-01-28'),
(2, '2023-02-04');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `goal`
--

INSERT INTO `goal` (`goalsID`, `playerID`, `numOfGoals`) VALUES
(1, 2, 7),
(2, 4, 10);

-- --------------------------------------------------------

--
-- Table structure for table `league`
--

DROP TABLE IF EXISTS `league`;
CREATE TABLE IF NOT EXISTS `league` (
  `leagueID` int(11) NOT NULL,
  `leagueName` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`playerID`, `firstName`, `lastName`, `teamID`, `DOB`, `userID`) VALUES
(2, 'test', 'test', 1, '2023-02-13', 13),
(4, 'test1', 'test2', 1, '2023-02-06', 25);

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`resultID`, `homeTeamID`, `awayTeamID`, `homeTeamScore`, `awayTeamScore`, `leagueID`, `matchWeek`) VALUES
(1, 1, 2, 3, 2, 1, 1),
(2, 2, 1, 4, 2, 1, 1),
(3, 4, 5, 2, 2, 1, 1),
(4, 1, 5, 3, 0, 1, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`teamID`, `clubID`, `leagueID`, `teamName`) VALUES
(1, 1, 1, 'Whitchurch 1\'s'),
(2, 1, 1, 'Whitchurch 2\'s'),
(3, 2, 2, 'Gwent 2\'s'),
(4, 2, 1, 'Gwent 1\'s'),
(5, 3, 1, 'Swansea 1\'s');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `email`, `password`, `accountType`) VALUES
(13, 'test@test.com', '$2y$10$See9ABqGw9YeruPG.FuymOA6QySG8z.6OqlCArviUyUJ71YBlJppe', 'Player'),
(14, 'testytest@test.com', '$2y$10$GUFGjbI3ELnMjyoMLHkLme18kFjF4aUrmgxZ0OIbumQEHJYloLnLG', 'Player'),
(21, 'clubAdmin@test.com', '$2y$10$pr7AINovXlzTcAXQeZPCJu5n/Pm6PkJ5ovvFnX18tJeJHa/HUpSWi', 'Club Admin'),
(25, 'testytestyyyy@test.com', '$2y$10$kd8nCb0EbE7BHUWCFoVrkefkSLyZPipelaL0bVJRs8Xkf8qJPhSZa', 'Player');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
