CREATE TABLE `User` (
  `idUser` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50),
  `email` VARCHAR(100),
  `password` VARCHAR(20),
  `gender` CHAR(1),
  `birthdate` DATE,
  `profession` VARCHAR(50),
  `professionalExperience` TEXT,
  `professionalSkills` TEXT,
  `selfDescription` TEXT,
  `fieldsOfInterest` TEXT,
  `address` VARCHAR(150),
  `blocked` CHAR(1),
  PRIMARY KEY  (`idUser`)
);

CREATE TABLE `Message` (
  `idMessage` INT NOT NULL AUTO_INCREMENT,
  `message` TEXT,
  `idSender` INT,
  `idReceiver` INT,
  PRIMARY KEY  (`idMessage`)
);

CREATE TABLE `CoworkingSpace` (
  `idSpace` INT NOT NULL AUTO_INCREMENT,
  `idOwner` INT,
  `address` VARCHAR(150),
  `availableVacancies` INT,
  `price` DECIMAL,
  `description` TEXT,
  `leaseAgreement` VARCHAR(150),
  `reputation` FLOAT,
  `name` VARCHAR(50),
  PRIMARY KEY  (`idSpace`)
);

CREATE TABLE `Tenant` (
  `idTenant` INT NOT NULL AUTO_INCREMENT,
  `idUser` INT,
  `idSpace` INT,
  `approved` CHAR(1),
  `spaceRating` FLOAT,
  `startDate` DATE,
  `endDate` DATE,
  PRIMARY KEY  (`idTenant`)
);

CREATE TABLE `Team` (
  `idTeam` INT NOT NULL AUTO_INCREMENT,
  `idSpace` INT,
  `name` VARCHAR(50),
  PRIMARY KEY  (`idTeam`)
);

CREATE TABLE `TeamMember` (
  `idMember` INT NOT NULL AUTO_INCREMENT,
  `idTeam` INT,
  `idTenant` INT,
  PRIMARY KEY  (`idMember`)
);

CREATE TABLE `TeamPost` (
  `idTeamPost` INT NOT NULL AUTO_INCREMENT,
  `idReplyTo` INT,
  `idMember` INT,
  `message` TEXT,
  PRIMARY KEY  (`idTeamPost`)
);

CREATE TABLE `CWSpacePost` (
  `idSpacePost` INT NOT NULL AUTO_INCREMENT,
  `idReplyTo` INT,
  `idSpace` INT,
  `idTenant` INT,
  `message` TEXT,
  PRIMARY KEY  (`idSpacePost`)
);

CREATE TABLE `Photo` (
  `idPhoto` INT NOT NULL AUTO_INCREMENT,
  `idSpace` INT,
  PRIMARY KEY  (`idPhoto`)
);

CREATE TABLE `AdminUser` (
  `idAdmin` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20),
  `password` VARCHAR(20),
  PRIMARY KEY  (`idAdmin`)
);


ALTER TABLE `Message` ADD CONSTRAINT `Message_fk1` FOREIGN KEY (`idSender`) REFERENCES User(`idUser`);
ALTER TABLE `Message` ADD CONSTRAINT `Message_fk2` FOREIGN KEY (`idReceiver`) REFERENCES User(`idUser`);
ALTER TABLE `CoworkingSpace` ADD CONSTRAINT `CoworkingSpace_fk1` FOREIGN KEY (`idOwner`) REFERENCES User(`idUser`);
ALTER TABLE `Tenant` ADD CONSTRAINT `Tenant_fk1` FOREIGN KEY (`idUser`) REFERENCES User(`idUser`);
ALTER TABLE `Tenant` ADD CONSTRAINT `Tenant_fk2` FOREIGN KEY (`idSpace`) REFERENCES CoworkingSpace(`idSpace`);
ALTER TABLE `Team` ADD CONSTRAINT `Team_fk1` FOREIGN KEY (`idSpace`) REFERENCES CoworkingSpace(`idSpace`);
ALTER TABLE `TeamMember` ADD CONSTRAINT `TeamMember_fk1` FOREIGN KEY (`idTeam`) REFERENCES Team(`idTeam`);
ALTER TABLE `TeamMember` ADD CONSTRAINT `TeamMember_fk2` FOREIGN KEY (`idTenant`) REFERENCES Tenant(`idTenant`);
ALTER TABLE `TeamPost` ADD CONSTRAINT `TeamPost_fk1` FOREIGN KEY (`idReplyTo`) REFERENCES TeamPost(`idTeamPost`);
ALTER TABLE `TeamPost` ADD CONSTRAINT `TeamPost_fk2` FOREIGN KEY (`idMember`) REFERENCES TeamMember(`idMember`);
ALTER TABLE `CWSpacePost` ADD CONSTRAINT `CWSpacePost_fk1` FOREIGN KEY (`idReplyTo`) REFERENCES CWSpacePost(`idSpacePost`);
ALTER TABLE `CWSpacePost` ADD CONSTRAINT `CWSpacePost_fk2` FOREIGN KEY (`idSpace`) REFERENCES CoworkingSpace(`idSpace`);
ALTER TABLE `CWSpacePost` ADD CONSTRAINT `CWSpacePost_fk3` FOREIGN KEY (`idTenant`) REFERENCES Tenant(`idTenant`);
ALTER TABLE `Photo` ADD CONSTRAINT `Photo_fk1` FOREIGN KEY (`idSpace`) REFERENCES CoworkingSpace(`idSpace`);