SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `php-ass2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `php-ass2`;

DROP TABLE IF EXISTS `parkinglocs`;
CREATE TABLE `parkinglocs` (
  `ParkingID` int(11) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Capacity` int(11) DEFAULT NULL,
  `CostPerHour` decimal(10,2) DEFAULT NULL,
  `CostPerHourLateCheckOut` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `parkingrecords`;
CREATE TABLE `parkingrecords` (
  `RecordID` int(11) NOT NULL,
  `ParkingID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `CheckInTime` datetime DEFAULT NULL,
  `CheckOutTime` datetime DEFAULT NULL,
  `RealCheckOutTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `usertable`;
CREATE TABLE `usertable` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `parkinglocs`
  ADD PRIMARY KEY (`ParkingID`);

ALTER TABLE `parkingrecords`
  ADD PRIMARY KEY (`RecordID`),
  ADD KEY `parkingrecords_ibfk_1` (`ParkingID`),
  ADD KEY `parkingrecords_ibfk_2` (`UserID`);

ALTER TABLE `usertable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);


ALTER TABLE `parkinglocs`
  MODIFY `ParkingID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `parkingrecords`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usertable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `parkingrecords`
  ADD CONSTRAINT `parkingrecords_ibfk_1` FOREIGN KEY (`ParkingID`) REFERENCES `parkinglocs` (`ParkingID`),
  ADD CONSTRAINT `parkingrecords_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `usertable` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
