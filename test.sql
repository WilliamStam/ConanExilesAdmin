-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 20, 2017 at 06:54 PM
-- Server version: 10.1.22-MariaDB
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `conan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blacklist_ip`
--

CREATE TABLE `blacklist_ip` (
	`ID` int(6) NOT NULL,
	`ip` varchar(100) DEFAULT NULL,
	`datein` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	`uID` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blacklist_ip`
--

INSERT INTO `blacklist_ip` (`ID`, `ip`, `datein`, `uID`) VALUES
	(1, '192.168.0.65', '2017-04-20 08:22:56', 1),
	(2, '192.168.0.70,192.168.0.80', '2017-04-20 08:22:56', 1),
	(3, '123.123.123.4', '2017-04-20 09:37:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
	`ID` int(6) NOT NULL,
	`dbId` int(6) DEFAULT NULL,
	`playerId` varchar(200) DEFAULT NULL,
	`char_name` varchar(200) DEFAULT NULL,
	`level` int(3) DEFAULT NULL,
	`rank` int(3) DEFAULT NULL,
	`guild` int(6) DEFAULT NULL,
	`isAlive` tinyint(1) DEFAULT NULL,
	`killerName` varchar(200) DEFAULT NULL,
	`lastTimeOnline` int(12) DEFAULT NULL,
	`banned` tinyint(1) DEFAULT '0',
	`banned_date` datetime DEFAULT NULL,
	`banned_uID` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`ID`, `dbId`, `playerId`, `char_name`, `level`, `rank`, `guild`, `isAlive`, `killerName`, `lastTimeOnline`, `banned`, `banned_date`, `banned_uID`) VALUES
	(2, 1, '76561198001103225', 'WtFnE$$', NULL, NULL, NULL, NULL, NULL, 1492611392, 0, NULL, NULL),
	(3, 2, '76561198001103224', 'PlayerName', NULL, NULL, NULL, NULL, NULL, 1492641562, 1, '2017-04-19 10:15:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `players_ip`
--

CREATE TABLE `players_ip` (
	`ID` int(6) NOT NULL,
	`ip` varchar(100) DEFAULT NULL,
	`playerID` int(6) DEFAULT NULL,
	`date_last_used` timestamp NULL DEFAULT NULL,
	`datein` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `players_ip`
--

INSERT INTO `players_ip` (`ID`, `ip`, `playerID`, `date_last_used`, `datein`) VALUES
	(1, '192.168.0.46', 3, '2017-04-19 04:28:47', '2017-04-20 09:17:35'),
	(2, '192.168.0.40', 2, '2017-04-20 08:33:47', '2017-04-20 10:31:08');

-- --------------------------------------------------------

--
-- Table structure for table `system_users`
--

CREATE TABLE `system_users` (
	`ID` int(6) NOT NULL,
	`username` varchar(30) DEFAULT NULL,
	`password` varchar(50) DEFAULT NULL,
	`fullname` varchar(30) DEFAULT NULL,
	`settings` text,
	`lastlogin` timestamp NULL DEFAULT NULL,
	`lastActivity` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `system_users`
--

INSERT INTO `system_users` (`ID`, `username`, `password`, `fullname`, `settings`, `lastlogin`, `lastActivity`) VALUES
	(1, 'william', '52d49fece63e5ab2a57cc0f228cf7f35', 'William Stam', NULL, '2017-04-20 07:55:59', '2017-04-20 16:53:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blacklist_ip`
--
ALTER TABLE `blacklist_ip`
	ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
	ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `players_ip`
--
ALTER TABLE `players_ip`
	ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `system_users`
--
ALTER TABLE `system_users`
	ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blacklist_ip`
--
ALTER TABLE `blacklist_ip`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `players_ip`
--
ALTER TABLE `players_ip`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `system_users`
--
ALTER TABLE `system_users`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


CREATE TABLE `system` (
	`ID` int(6) NOT NULL,
	`system` varchar(100) DEFAULT NULL,
	`value` varchar(100) DEFAULT NULL
);

ALTER TABLE `system`
	ADD PRIMARY KEY (`ID`);

ALTER TABLE `system`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;