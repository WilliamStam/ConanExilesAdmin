-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 25, 2017 at 06:35 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `players_ip`
--

CREATE TABLE `players_ip` (
	`ID` int(6) NOT NULL,
	`ip` varchar(100) DEFAULT NULL,
	`port` int(10) DEFAULT NULL,
	`playerID` int(6) DEFAULT NULL,
	`timestamp` varchar(100) DEFAULT NULL,
	`datein` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `players_logins`
--

CREATE TABLE `players_logins` (
	`ID` int(6) NOT NULL,
	`playerID` int(6) DEFAULT NULL,
	`login_timestamp` varchar(40) DEFAULT NULL,
	`logout_timestamp` varchar(40) DEFAULT NULL,
	`duration` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scans`
--

CREATE TABLE `scans` (
	`ID` int(6) NOT NULL,
	`parserID` varchar(100) DEFAULT NULL,
	`datein` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`status` tinyint(1) DEFAULT '0',
	`result` text,
	`execute_time` decimal(20,18) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
	`ID` int(6) NOT NULL,
	`system` varchar(100) DEFAULT NULL,
	`value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
	(1, 'william', '52d49fece63e5ab2a57cc0f228cf7f35', 'William Stam', NULL, '2017-04-25 17:49:32', '2017-04-25 20:33:36');

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
-- Indexes for table `players_logins`
--
ALTER TABLE `players_logins`
	ADD PRIMARY KEY (`ID`),
	ADD KEY `playerID` (`playerID`);

--
-- Indexes for table `scans`
--
ALTER TABLE `scans`
	ADD PRIMARY KEY (`ID`),
	ADD KEY `parserID` (`parserID`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
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
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `players_ip`
--
ALTER TABLE `players_ip`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `players_logins`
--
ALTER TABLE `players_logins`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scans`
--
ALTER TABLE `scans`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `system`
--
ALTER TABLE `system`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `system_users`
--
ALTER TABLE `system_users`
	MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;