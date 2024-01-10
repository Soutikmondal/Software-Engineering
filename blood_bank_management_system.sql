-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2024 at 12:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blood_bank_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bloodavailable`
--

CREATE TABLE `bloodavailable` (
  `BID` int(11) NOT NULL,
  `Blood_Type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bloodavailable`
--

INSERT INTO `bloodavailable` (`BID`, `Blood_Type`, `Quantity`) VALUES
(2002, 'A-', 8),
(3003, 'B+', 10),
(3003, 'O+', 5),
(4004, 'AB-', 15),
(5005, 'O-', 3);

-- --------------------------------------------------------

--
-- Table structure for table `bloodbank`
--

CREATE TABLE `bloodbank` (
  `BID` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Contact` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bloodbank`
--

INSERT INTO `bloodbank` (`BID`, `Name`, `Address`, `Contact`) VALUES
(2002, 'Kolkata Blood Center', 'ABC, Saltlake', 'kolkata.bloodcenter@example.com'),
(3003, 'Mumbai Blood Bank', '123 Gandhi Street', 'contact@mumbaibloodbank.com'),
(4004, 'Chennai Blood Center', '456 Main Street', 'chennai.bloodcenter@example.com'),
(5005, 'Hyderabad Blood Bank', 'LMN Avenue', 'hyderabad.bloodbank@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `dispatchlogs`
--

CREATE TABLE `dispatchlogs` (
  `RID` int(11) NOT NULL,
  `Blood_Type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dispatchlogs`
--

INSERT INTO `dispatchlogs` (`RID`, `Blood_Type`, `Quantity`) VALUES
(1001, 'O+', 2),
(2002, 'A-', 4),
(3003, 'B+', 3),
(4004, 'AB-', 5),
(5005, 'O-', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pendingrequests`
--

CREATE TABLE `pendingrequests` (
  `RID` int(11) NOT NULL,
  `Blood_Type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendingrequests`
--

INSERT INTO `pendingrequests` (`RID`, `Blood_Type`, `Quantity`) VALUES
(1001, 'O+', 2),
(2002, 'A-', 4),
(3003, 'B+', 3),
(4004, 'AB-', 5),
(5005, 'O-', 1);

-- --------------------------------------------------------

--
-- Table structure for table `recipients`
--

CREATE TABLE `recipients` (
  `RID` int(11) NOT NULL,
  `Name` text DEFAULT NULL,
  `Blood_Type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Priority` text DEFAULT NULL,
  `Contact` text DEFAULT NULL,
  `Address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipients`
--

INSERT INTO `recipients` (`RID`, `Name`, `Blood_Type`, `Quantity`, `Priority`, `Contact`, `Address`) VALUES
(1001, 'John Doe', 'O+', 2, 'High', 'john.doe@example.com', '123 Main Street'),
(2002, 'Jane Smith', 'A-', 4, 'Medium', 'jane.smith@example.com', '456 Oak Avenue'),
(3003, 'Bob Johnson', 'B+', 3, 'High', 'bob.johnson@example.com', '789 Pine Street'),
(4004, 'Alice Williams', 'AB-', 5, 'Low', 'alice.williams@example.com', '101 Elm Road'),
(5005, 'David Miller', 'O-', 1, 'Medium', 'david.miller@example.com', '202 Maple Avenue');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bloodavailable`
--
ALTER TABLE `bloodavailable`
  ADD PRIMARY KEY (`BID`,`Blood_Type`);

--
-- Indexes for table `bloodbank`
--
ALTER TABLE `bloodbank`
  ADD PRIMARY KEY (`BID`);

--
-- Indexes for table `dispatchlogs`
--
ALTER TABLE `dispatchlogs`
  ADD PRIMARY KEY (`RID`,`Blood_Type`);

--
-- Indexes for table `pendingrequests`
--
ALTER TABLE `pendingrequests`
  ADD PRIMARY KEY (`RID`,`Blood_Type`);

--
-- Indexes for table `recipients`
--
ALTER TABLE `recipients`
  ADD PRIMARY KEY (`RID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bloodavailable`
--
ALTER TABLE `bloodavailable`
  ADD CONSTRAINT `bloodavailable_ibfk_1` FOREIGN KEY (`BID`) REFERENCES `bloodbank` (`BID`);

--
-- Constraints for table `dispatchlogs`
--
ALTER TABLE `dispatchlogs`
  ADD CONSTRAINT `dispatchlogs_ibfk_1` FOREIGN KEY (`RID`) REFERENCES `recipients` (`RID`);

--
-- Constraints for table `pendingrequests`
--
ALTER TABLE `pendingrequests`
  ADD CONSTRAINT `pendingrequests_ibfk_1` FOREIGN KEY (`RID`) REFERENCES `recipients` (`RID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
