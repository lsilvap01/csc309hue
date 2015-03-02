-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 03, 2015 at 12:54 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `csc309`
--

-- --------------------------------------------------------

--
-- Table structure for table `AdminUser`
--

CREATE TABLE IF NOT EXISTS `AdminUser` (
`idAdmin` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CoworkingSpace`
--

CREATE TABLE IF NOT EXISTS `CoworkingSpace` (
`idSpace` int(11) NOT NULL,
  `idOwner` int(11) DEFAULT NULL,
  `address` varchar(150) NOT NULL,
  `availableVacancies` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,0) NOT NULL DEFAULT '0',
  `description` text,
  `leaseAgreement` varchar(150) DEFAULT NULL,
  `reputation` float DEFAULT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CWSpacePost`
--

CREATE TABLE IF NOT EXISTS `CWSpacePost` (
`idSpacePost` int(11) NOT NULL,
  `idReplyTo` int(11) DEFAULT NULL,
  `idSpace` int(11) DEFAULT NULL,
  `idTenant` int(11) DEFAULT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Message`
--

CREATE TABLE IF NOT EXISTS `Message` (
`idMessage` int(11) NOT NULL,
  `message` text NOT NULL,
  `idSender` int(11) DEFAULT NULL,
  `idReceiver` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Photo`
--

CREATE TABLE IF NOT EXISTS `Photo` (
`idPhoto` int(11) NOT NULL,
  `idSpace` int(11) DEFAULT NULL,
  `url` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Team`
--

CREATE TABLE IF NOT EXISTS `Team` (
`idTeam` int(11) NOT NULL,
  `idSpace` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TeamMember`
--

CREATE TABLE IF NOT EXISTS `TeamMember` (
`idMember` int(11) NOT NULL,
  `idTeam` int(11) DEFAULT NULL,
  `idTenant` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TeamPost`
--

CREATE TABLE IF NOT EXISTS `TeamPost` (
`idTeamPost` int(11) NOT NULL,
  `idReplyTo` int(11) DEFAULT NULL,
  `idMember` int(11) DEFAULT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tenant`
--

CREATE TABLE IF NOT EXISTS `Tenant` (
`idTenant` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `idSpace` int(11) DEFAULT NULL,
  `approved` char(1) NOT NULL DEFAULT 'n',
  `spaceRating` float NOT NULL DEFAULT '0',
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
`idUser` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(20) NOT NULL,
  `gender` char(1) NOT NULL,
  `birthdate` date NOT NULL,
  `profession` varchar(50) DEFAULT NULL,
  `professionalExperience` text,
  `professionalSkills` text,
  `selfDescription` text,
  `fieldsOfInterest` text,
  `address` varchar(150) DEFAULT NULL,
  `blocked` char(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AdminUser`
--
ALTER TABLE `AdminUser`
 ADD PRIMARY KEY (`idAdmin`), ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `CoworkingSpace`
--
ALTER TABLE `CoworkingSpace`
 ADD PRIMARY KEY (`idSpace`), ADD KEY `CoworkingSpace_fk1` (`idOwner`);

--
-- Indexes for table `CWSpacePost`
--
ALTER TABLE `CWSpacePost`
 ADD PRIMARY KEY (`idSpacePost`), ADD KEY `CWSpacePost_fk1` (`idReplyTo`), ADD KEY `CWSpacePost_fk2` (`idSpace`), ADD KEY `CWSpacePost_fk3` (`idTenant`);

--
-- Indexes for table `Message`
--
ALTER TABLE `Message`
 ADD PRIMARY KEY (`idMessage`), ADD KEY `Message_fk1` (`idSender`), ADD KEY `Message_fk2` (`idReceiver`);

--
-- Indexes for table `Photo`
--
ALTER TABLE `Photo`
 ADD PRIMARY KEY (`idPhoto`), ADD KEY `Photo_fk1` (`idSpace`);

--
-- Indexes for table `Team`
--
ALTER TABLE `Team`
 ADD PRIMARY KEY (`idTeam`), ADD KEY `Team_fk1` (`idSpace`);

--
-- Indexes for table `TeamMember`
--
ALTER TABLE `TeamMember`
 ADD PRIMARY KEY (`idMember`), ADD KEY `TeamMember_fk1` (`idTeam`), ADD KEY `TeamMember_fk2` (`idTenant`);

--
-- Indexes for table `TeamPost`
--
ALTER TABLE `TeamPost`
 ADD PRIMARY KEY (`idTeamPost`), ADD KEY `TeamPost_fk1` (`idReplyTo`), ADD KEY `TeamPost_fk2` (`idMember`);

--
-- Indexes for table `Tenant`
--
ALTER TABLE `Tenant`
 ADD PRIMARY KEY (`idTenant`), ADD KEY `Tenant_fk1` (`idUser`), ADD KEY `Tenant_fk2` (`idSpace`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
 ADD PRIMARY KEY (`idUser`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AdminUser`
--
ALTER TABLE `AdminUser`
MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CoworkingSpace`
--
ALTER TABLE `CoworkingSpace`
MODIFY `idSpace` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CWSpacePost`
--
ALTER TABLE `CWSpacePost`
MODIFY `idSpacePost` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Message`
--
ALTER TABLE `Message`
MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Photo`
--
ALTER TABLE `Photo`
MODIFY `idPhoto` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Team`
--
ALTER TABLE `Team`
MODIFY `idTeam` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TeamMember`
--
ALTER TABLE `TeamMember`
MODIFY `idMember` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TeamPost`
--
ALTER TABLE `TeamPost`
MODIFY `idTeamPost` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Tenant`
--
ALTER TABLE `Tenant`
MODIFY `idTenant` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `CoworkingSpace`
--
ALTER TABLE `CoworkingSpace`
ADD CONSTRAINT `CoworkingSpace_fk1` FOREIGN KEY (`idOwner`) REFERENCES `User` (`idUser`);

--
-- Constraints for table `CWSpacePost`
--
ALTER TABLE `CWSpacePost`
ADD CONSTRAINT `CWSpacePost_fk1` FOREIGN KEY (`idReplyTo`) REFERENCES `CWSpacePost` (`idSpacePost`),
ADD CONSTRAINT `CWSpacePost_fk2` FOREIGN KEY (`idSpace`) REFERENCES `CoworkingSpace` (`idSpace`),
ADD CONSTRAINT `CWSpacePost_fk3` FOREIGN KEY (`idTenant`) REFERENCES `Tenant` (`idTenant`);

--
-- Constraints for table `Message`
--
ALTER TABLE `Message`
ADD CONSTRAINT `Message_fk1` FOREIGN KEY (`idSender`) REFERENCES `User` (`idUser`),
ADD CONSTRAINT `Message_fk2` FOREIGN KEY (`idReceiver`) REFERENCES `User` (`idUser`);

--
-- Constraints for table `Photo`
--
ALTER TABLE `Photo`
ADD CONSTRAINT `Photo_fk1` FOREIGN KEY (`idSpace`) REFERENCES `CoworkingSpace` (`idSpace`);

--
-- Constraints for table `Team`
--
ALTER TABLE `Team`
ADD CONSTRAINT `Team_fk1` FOREIGN KEY (`idSpace`) REFERENCES `CoworkingSpace` (`idSpace`);

--
-- Constraints for table `TeamMember`
--
ALTER TABLE `TeamMember`
ADD CONSTRAINT `TeamMember_fk1` FOREIGN KEY (`idTeam`) REFERENCES `Team` (`idTeam`),
ADD CONSTRAINT `TeamMember_fk2` FOREIGN KEY (`idTenant`) REFERENCES `Tenant` (`idTenant`);

--
-- Constraints for table `TeamPost`
--
ALTER TABLE `TeamPost`
ADD CONSTRAINT `TeamPost_fk1` FOREIGN KEY (`idReplyTo`) REFERENCES `TeamPost` (`idTeamPost`),
ADD CONSTRAINT `TeamPost_fk2` FOREIGN KEY (`idMember`) REFERENCES `TeamMember` (`idMember`);

--
-- Constraints for table `Tenant`
--
ALTER TABLE `Tenant`
ADD CONSTRAINT `Tenant_fk1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`),
ADD CONSTRAINT `Tenant_fk2` FOREIGN KEY (`idSpace`) REFERENCES `CoworkingSpace` (`idSpace`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
