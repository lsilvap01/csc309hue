-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 06, 2015 at 05:50 AM
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
  `username` varchar(150) NOT NULL,
  `password` varchar(40) NOT NULL
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CoworkingSpace`
--

INSERT INTO `CoworkingSpace` (`idSpace`, `idOwner`, `address`, `availableVacancies`, `price`, `description`, `leaseAgreement`, `reputation`, `name`) VALUES
(4, 2, 'asdDas 2', 1, '50', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla tincidunt, est at vestibulum ultricies, velit quam vehicula massa, egestas ornare sapien velit ac risus. Integer et ex id erat tincidunt dapibus. Curabitur ac erat et purus tristique scelerisque nec et elit. In sed maximus leo. Nam consequat risus nec euismod iaculis. In gravida nisl et lectus mollis, id porta turpis faucibus. Donec ut odio in augue vulputate hendrerit.', NULL, NULL, 'Space 1'),
(5, 3, 'asdDas 2', 100, '50', 'asdfasdf', 'space5Lease.pdf', NULL, 'Space 2'),
(6, 3, 'asdDas 2', 100, '50', 'asdfasdf', 'space5Lease.pdf', NULL, 'Space 2'),
(7, 3, 'asdDas 2', 100, '50', 'asdfasdf', 'space5Lease.pdf', NULL, 'Space 2'),
(8, 3, 'asdDas 2', 100, '50', 'asdfasdf', 'space5Lease.pdf', NULL, 'Space 2');

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CWSpacePost`
--

INSERT INTO `CWSpacePost` (`idSpacePost`, `idReplyTo`, `idSpace`, `idTenant`, `message`) VALUES
(1, NULL, 4, NULL, 'test'),
(2, NULL, 4, NULL, 'test'),
(3, NULL, 4, NULL, 'lala'),
(4, NULL, 4, NULL, 'testeeee'),
(5, NULL, 4, NULL, 'lalaaaa'),
(6, 5, 4, NULL, 'ok'),
(7, 3, 4, NULL, 'blabla'),
(8, NULL, 5, 1, 'okok'),
(9, NULL, 5, NULL, 'lalalalala'),
(10, 9, 5, 1, 'alright'),
(11, NULL, 5, 1, 'dfg'),
(12, NULL, 5, 1, 'aa'),
(13, NULL, 5, 1, 'cxc'),
(14, NULL, 5, 1, 'asd'),
(15, NULL, 5, 1, 'ffff'),
(16, NULL, 5, 1, 'gg'),
(17, 16, 5, 1, 'bla\nnene'),
(18, 16, 5, 1, 'bla '''),
(19, 16, 5, 1, 'asfa\naasd'),
(20, NULL, 5, 1, 'multi<br />line'),
(21, 20, 5, 1, 'multi<br />line<br />comment'),
(22, NULL, 5, 1, '<b>teste</b>'),
(23, NULL, 5, 1, '<');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Photo`
--

INSERT INTO `Photo` (`idPhoto`, `idSpace`, `url`) VALUES
(1, 5, 'space5Photo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Team`
--

CREATE TABLE IF NOT EXISTS `Team` (
`idTeam` int(11) NOT NULL,
  `idSpace` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Team`
--

INSERT INTO `Team` (`idTeam`, `idSpace`, `name`) VALUES
(1, 5, 'aasdf'),
(2, 5, 'asdfasd'),
(3, 5, 'asdfasd'),
(4, 5, 'asdfasd'),
(5, 5, 'lala'),
(6, 5, 'lala');

-- --------------------------------------------------------

--
-- Table structure for table `TeamMember`
--

CREATE TABLE IF NOT EXISTS `TeamMember` (
`idMember` int(11) NOT NULL,
  `idTeam` int(11) DEFAULT NULL,
  `idTenant` int(11) DEFAULT NULL,
  `approved` char(1) NOT NULL DEFAULT 'n'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `TeamMember`
--

INSERT INTO `TeamMember` (`idMember`, `idTeam`, `idTenant`, `approved`) VALUES
(1, 1, 1, 'y'),
(2, 1, NULL, 'y'),
(3, 2, 1, 'y'),
(4, 2, NULL, 'y'),
(5, 3, 1, 'y'),
(6, 3, NULL, 'y'),
(7, 4, 1, 'y'),
(8, 4, NULL, 'y'),
(9, 5, 1, 'y'),
(10, 6, 1, 'y');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Tenant`
--

INSERT INTO `Tenant` (`idTenant`, `idUser`, `idSpace`, `approved`, `spaceRating`, `startDate`, `endDate`) VALUES
(1, 2, 5, 'y', 14, '2015-03-12', NULL),
(11, 2, 7, 'y', 0, '2015-03-12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
`idUser` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `gender` char(1) NOT NULL,
  `birthdate` date NOT NULL,
  `profession` varchar(50) DEFAULT NULL,
  `professionalExperience` text,
  `professionalSkills` text,
  `selfDescription` text,
  `fieldsOfInterest` text,
  `address` varchar(150) DEFAULT NULL,
  `blocked` char(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`idUser`, `name`, `email`, `password`, `gender`, `birthdate`, `profession`, `professionalExperience`, `professionalSkills`, `selfDescription`, `fieldsOfInterest`, `address`, `blocked`) VALUES
(2, 'Lucas', 'lsilvap01@gmail.com', '175f972aaf0f5ded83a28be3f8c6523b', 'm', '1994-10-05', NULL, NULL, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla tincidunt, est at vestibulum ultricies, velit quam vehicula massa, egestas ornare sapien velit ac risus. Integer et ex id erat tincidunt dapibus. Curabitur ac erat et purus tristique scelerisque nec et elit. In sed maximus leo. Nam consequat risus nec euismod iaculis. In gravida nisl et lectus mollis, id porta turpis faucibus. Donec ut odio in augue vulputate hendrerit.', NULL, NULL, NULL),
(3, 'Teste', 'teste@teste.com', '6be3e21d2328f8a4a514c26bb773d26e', 'm', '1990-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Teste', 'teste2@teste.com', '175f972aaf0f5ded83a28be3f8c6523b', 'm', '1990-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'asd', 'asd@ate.com', '175f972aaf0f5ded83a28be3f8c6523b', 'm', '1994-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'teste', 'asdfasd@a.com', '175f972aaf0f5ded83a28be3f8c6523b', 'm', '1993-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'vovo', 'vovo@teste.com', '175f972aaf0f5ded83a28be3f8c6523b', 'm', '1234-03-31', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'asdfasd', 'teste33@teste.com', '175f972aaf0f5ded83a28be3f8c6523b', 'm', '1994-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `UserRating`
--

CREATE TABLE IF NOT EXISTS `UserRating` (
`idUserRating` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idUserRated` int(11) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UserRating`
--

INSERT INTO `UserRating` (`idUserRating`, `idUser`, `idUserRated`, `rating`) VALUES
(1, 3, 2, 5),
(5, 2, 3, 6);

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
-- Indexes for table `UserRating`
--
ALTER TABLE `UserRating`
 ADD PRIMARY KEY (`idUserRating`), ADD KEY `UserRating_fk1` (`idUser`), ADD KEY `UserRating_fk2` (`idUserRated`);

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
MODIFY `idSpace` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `CWSpacePost`
--
ALTER TABLE `CWSpacePost`
MODIFY `idSpacePost` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `Message`
--
ALTER TABLE `Message`
MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Photo`
--
ALTER TABLE `Photo`
MODIFY `idPhoto` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Team`
--
ALTER TABLE `Team`
MODIFY `idTeam` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `TeamMember`
--
ALTER TABLE `TeamMember`
MODIFY `idMember` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `TeamPost`
--
ALTER TABLE `TeamPost`
MODIFY `idTeamPost` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Tenant`
--
ALTER TABLE `Tenant`
MODIFY `idTenant` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `UserRating`
--
ALTER TABLE `UserRating`
MODIFY `idUserRating` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
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

--
-- Constraints for table `UserRating`
--
ALTER TABLE `UserRating`
ADD CONSTRAINT `UserRating_fk1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`),
ADD CONSTRAINT `UserRating_fk2` FOREIGN KEY (`idUserRated`) REFERENCES `User` (`idUser`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
