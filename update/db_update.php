<?php
$sql = array(
"CREATE TABLE `scans` (  `ID` int(6) NOT NULL,  `parserID` varchar(100) DEFAULT NULL,  `datein` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `status` tinyint(1) DEFAULT '0',  `result` text);",
	"ALTER TABLE `scans`  ADD PRIMARY KEY (`ID`),  ADD KEY `parserID` (`parserID`);",
	"ALTER TABLE `scans`  MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;"


);

?>
