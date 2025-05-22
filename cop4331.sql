
CREATE DATABASE COP4331;
USE COP4331;

CREATE TABLE `Users` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `FirstName` VARCHAR(50) NOT NULL DEFAULT ",
  `LastName` VARCHAR(50) NOT NULL DEFAULT ",
  `Login` VARCHAR(50) NOT NULL DEFAULT ",
  `Password` VARCHAR(50) NOT NULL DEFAULT ",
  PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

CREATE TABLE `Contacts` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `FirstName` VARCHAR(50) NOT NULL DEFAULT ",
  `LastName` VARCHAR(50) NOT NULL DEFAULT ",
  `Phone` VARCHAR(50) NOT NULL DEFAULT ",
  `Email` VARCHAR(50) NOT NULL DEFAULT ",
  `UserID` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('Sam','Hill','SamH','Test');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('Anna','Smith','AnnaS','1234');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('John','Doe','JDoe','pass');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('Emily','Stone','EStone','pwd');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('Mike','Brown','MBrown','abc');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('Olivia','Green','OGreen','qwerty');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('David','Lee','DLee','letmein');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('Grace','Kim','GraceK','hello');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('Brian','Chen','BChen','secure');
INSERT INTO Users (FirstName, LastName, Login, Password) VALUES ('Natalie','Cruz','NCruz','welcome');

INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Laura','Adams','555-1234','laura@example.com',1);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Steve','Nash','555-5678','steve@example.com',2);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Karen','Young','555-8765','karen@example.com',3);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Tom','Wilson','555-3456','tom@example.com',4);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Amy','Pond','555-6543','amy@example.com',5);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Jake','Peralta','555-7777','jake@example.com',6);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Rosa','Diaz','555-8888','rosa@example.com',7);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Terry','Jeffords','555-9999','terry@example.com',8);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Holt','Raymond','555-1111','holt@example.com',9);
INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES ('Gina','Linetti','555-2222','gina@example.com',10);

create user 'TheBeast' identified by 'WeLoveCOP4331';
grant all privileges on COP4331.* to 'TheBeast'@'%';
