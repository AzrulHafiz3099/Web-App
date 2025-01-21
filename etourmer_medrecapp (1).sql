-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2025 at 04:55 PM
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
-- Database: `etourmer_medrecapp`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `GetNextDrugDetails` () RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE next_id INT;
    
    -- Get the highest DrugID, convert it to an integer, and increment by 1
    SELECT COALESCE(MAX(CAST(SUBSTRING(DrugID, 3) AS UNSIGNED)), 0) + 1 INTO next_id FROM drug_details;
    
    RETURN next_id;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `GetNextDrugSupply` () RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE next_id INT;
    
    -- Get the maximum existing ID and add 1
    SELECT COALESCE(MAX(CAST(SUBSTRING(SupplyID, 3) AS UNSIGNED)), 0) + 1 INTO next_id FROM drug_supply;
    
    RETURN next_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` varchar(255) NOT NULL,
  `UserID` varchar(255) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartID`, `UserID`, `createdDate`, `Status`) VALUES
('C_0001', 'US_0002', '2025-01-19', 'Completed'),
('C_0002', 'US_0002', '2025-01-19', 'Completed'),
('C_0003', 'US_0002', '2025-01-19', 'Failed'),
('C_0004', 'US_0002', '2025-01-19', 'Completed'),
('C_0005', 'US_0006', '2025-01-19', 'Completed'),
('C_0006', 'US_0006', '2025-01-19', 'Completed'),
('C_0007', 'US_0006', '2025-01-19', 'Completed'),
('C_0008', 'US_0006', '2025-01-19', 'Completed'),
('C_0009', 'US_0006', '2025-01-19', 'active'),
('C_0010', 'US_0002', '2025-01-19', 'active'),
('C_0012', 'US_0003', '2025-01-20', 'active'),
('C_0013', 'US_0008', '2025-01-20', 'active');

--
-- Triggers `cart`
--
DELIMITER $$
CREATE TRIGGER `GenerateCartID` BEFORE INSERT ON `cart` FOR EACH ROW BEGIN
    SET NEW.CartID = CONCAT('C_', LPAD(GetNextCart(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `CartItemID` varchar(255) NOT NULL,
  `CartID` varchar(255) DEFAULT NULL,
  `DrugID` varchar(255) DEFAULT NULL,
  `GenericName` varchar(255) NOT NULL,
  `DrugImage` varchar(255) NOT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`CartItemID`, `CartID`, `DrugID`, `GenericName`, `DrugImage`, `Quantity`, `Price`) VALUES
('CI_0001', 'C_0001', 'D_0001', 'Paracetamol', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/paracetamol.jpg', 1, 6.60),
('CI_0002', 'C_0002', 'D_0002', 'Antibiotic', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/venom.jpg', 1, 10.20),
('CI_0003', 'C_0003', 'D_0004', '32432432', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/43223423', 1, 6.60),
('CI_0004', 'C_0005', 'D_0002', 'Antibiotic', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/venom.jpg', 1, 10.20),
('CI_0005', 'C_0006', 'D_0002', 'Antibiotic', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/venom.jpg', 2, 10.20),
('CI_0006', 'C_0007', 'D_0002', 'Antibiotic', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/venom.jpg', 2, 10.20),
('CI_0007', 'C_0008', 'D_0002', 'Antibiotic', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/venom.jpg', 1, 10.20),
('CI_0008', 'C_0008', 'D_0001', 'Paracetamol', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/paracetamol.jpg', 1, 6.60),
('CI_0009', 'C_0004', 'D_0001', 'Paracetamol', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/paracetamol.jpg', 1, 6.60),
('CI_0010', 'C_0004', 'D_0002', 'Antibiotic', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/venom.jpg', 1, 10.20),
('CI_0011', 'C_0010', 'D_0002', 'Antibiotic', 'https://www.etourmersing.com/BackEnd-APi/MedRec/DrugImage/venom.jpg', 1, 10.20);

--
-- Triggers `cart_item`
--
DELIMITER $$
CREATE TRIGGER `GenerateCartItemID` BEFORE INSERT ON `cart_item` FOR EACH ROW BEGIN
    SET NEW.CartItemID = CONCAT('CI_', LPAD(GetNextCartItem(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_details`
--

CREATE TABLE `drug_details` (
  `DrugID` varchar(255) NOT NULL,
  `DrugHeaderID` varchar(255) DEFAULT NULL,
  `SupplyID` varchar(255) DEFAULT NULL,
  `BrandName` varchar(255) DEFAULT NULL,
  `GenericName` varchar(255) DEFAULT NULL,
  `Active_Ingredient` varchar(255) DEFAULT NULL,
  `Dosage` varchar(255) DEFAULT NULL,
  `Dosage_Form` varchar(255) DEFAULT NULL,
  `DosageUsage` varchar(255) NOT NULL,
  `Manufacturer` varchar(255) DEFAULT NULL,
  `Manufacture_Date` date DEFAULT NULL,
  `SideEffects` text DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `DrugImage` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_details`
--

INSERT INTO `drug_details` (`DrugID`, `DrugHeaderID`, `SupplyID`, `BrandName`, `GenericName`, `Active_Ingredient`, `Dosage`, `Dosage_Form`, `DosageUsage`, `Manufacturer`, `Manufacture_Date`, `SideEffects`, `Price`, `DrugImage`) VALUES
('D_0001', 'DH_0001', 'S_0001', 'Panadol', 'Paracetamol', 'ubat', '650', 'tablet', '1 pill per day', 'paracetamol ', '2024-12-12', 'Sleepy\r\n', 6.60, 'paracetamol.jpg'),
('D_0002', 'DH_0002', 'S_0002', 'Anti', 'Antibiotic', 'Venom', '1000', 'Liquid', '2 pills per day', 'Marvel', '2024-12-11', 'Buff', 10.20, 'venom.jpg'),
('D_0003', 'DH_0002', 'S_0003', 'test', 'tt', 'tt', 'tt', 'tt', '3 pills per day', 'tt', '2024-12-24', 'tt', 10.20, 'tt'),
('D_0004', 'DH_0001', 'S_0004', 'Test3', '32432432', '32432432', '432432432', '4324324', '4 pills per day', '34234242', '2024-12-11', '32423432', 6.60, '43223423');

--
-- Triggers `drug_details`
--
DELIMITER $$
CREATE TRIGGER `GenerateDrugDetailsID` BEFORE INSERT ON `drug_details` FOR EACH ROW BEGIN
    SET NEW.DrugID = CONCAT('D_', LPAD(GetNextDrugDetails(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_header`
--

CREATE TABLE `drug_header` (
  `DrugHeaderID` varchar(255) NOT NULL,
  `Category_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_header`
--

INSERT INTO `drug_header` (`DrugHeaderID`, `Category_Name`) VALUES
('DH_0001', 'Painkiller'),
('DH_0002', 'Antibiotics');

--
-- Triggers `drug_header`
--
DELIMITER $$
CREATE TRIGGER `GenerateDrugHeaderID` BEFORE INSERT ON `drug_header` FOR EACH ROW BEGIN
    SET NEW.DrugHeaderID = CONCAT('DH_', LPAD(GetNextDrugHeader(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_price`
--

CREATE TABLE `drug_price` (
  `PriceID` varchar(255) NOT NULL,
  `DrugID` varchar(255) DEFAULT NULL,
  `Vendor_Name` varchar(255) DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_price`
--

INSERT INTO `drug_price` (`PriceID`, `DrugID`, `Vendor_Name`, `Location`, `Price`) VALUES
('PR_0001', 'D_0003', 'dsfsdf', 'fsdfds', 10.20);

--
-- Triggers `drug_price`
--
DELIMITER $$
CREATE TRIGGER `GeneratePriceID` BEFORE INSERT ON `drug_price` FOR EACH ROW BEGIN
    SET NEW.PriceID = CONCAT('PR_', LPAD(GetNextDrugPrice(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_supply`
--

CREATE TABLE `drug_supply` (
  `SupplyID` varchar(255) NOT NULL,
  `DrugHeaderID` varchar(255) DEFAULT NULL,
  `VendorID` varchar(255) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Manufacture_Date` date DEFAULT NULL,
  `ExpiryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_supply`
--

INSERT INTO `drug_supply` (`SupplyID`, `DrugHeaderID`, `VendorID`, `Quantity`, `Manufacture_Date`, `ExpiryDate`) VALUES
('S_0001', 'DH_0001', 'V_0001', 4, '2024-12-13', '2024-12-13'),
('S_0002', 'DH_0002', 'V_0001', 3, '2024-12-10', '2024-12-13'),
('S_0003', 'DH_0001', 'V_0002', 12, '2024-12-11', '2024-12-10'),
('S_0004', 'DH_0002', 'V_0001', 4, '2024-12-13', '2024-12-13'),
('S_0005', 'DH_0002', 'V_0001', 6, '2025-01-21', '2025-01-15'),
('S_0006', 'DH_0001', 'V_0002', 12, '2025-01-16', '2025-11-21');

--
-- Triggers `drug_supply`
--
DELIMITER $$
CREATE TRIGGER `GenerateDrugSupplyID` BEFORE INSERT ON `drug_supply` FOR EACH ROW BEGIN
    SET NEW.SupplyID = CONCAT('S_', LPAD(GetNextDrugSupply(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `OrderID` varchar(255) NOT NULL,
  `CartID` varchar(255) DEFAULT NULL,
  `TotalPrice` decimal(10,2) DEFAULT NULL,
  `OrderDate` date DEFAULT NULL,
  `PaymentMethod` varchar(255) NOT NULL,
  `PaymentStatus` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`OrderID`, `CartID`, `TotalPrice`, `OrderDate`, `PaymentMethod`, `PaymentStatus`) VALUES
('O_0001', 'C_0001', 6.60, '2025-01-20', 'COD', 'Pending'),
('O_0002', 'C_0002', 10.20, '2025-01-20', 'FPX', 'Success'),
('O_0003', 'C_0003', 6.60, '2025-01-20', 'FPX', 'Failed'),
('O_0004', 'C_0005', 10.20, '2025-01-20', 'COD', 'Pending'),
('O_0005', 'C_0006', 20.40, '2025-01-20', 'COD', 'Pending'),
('O_0006', 'C_0007', 20.40, '2025-01-20', 'COD', 'Pending'),
('O_0007', 'C_0008', 16.80, '2025-01-20', 'COD', 'Pending'),
('O_0008', 'C_0004', 16.80, '2025-01-20', 'COD', 'Pending');

--
-- Triggers `order`
--
DELIMITER $$
CREATE TRIGGER `GenerateOrderID` BEFORE INSERT ON `order` FOR EACH ROW BEGIN
    SET NEW.OrderID = CONCAT('O_', LPAD(GetNextOrder(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PatientID` varchar(255) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `SymptomID` varchar(255) DEFAULT NULL,
  `DrugHeaderID` varchar(255) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `Gender` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `MedicalHistory` varchar(255) DEFAULT NULL,
  `Phonenumber` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PatientID`, `UserID`, `SymptomID`, `DrugHeaderID`, `Name`, `Age`, `Gender`, `Address`, `MedicalHistory`, `Phonenumber`) VALUES
('P_0001', 'US_0001', 'SY_0001', 'DH_0001', 'Amir Hamzah', 22, 'Male', 'Durian Tunggal', 'None', '0123456789'),
('P_0002', 'US_0002', 'SY_0001', NULL, 'Azrul2', 22, 'Male', '02121', 'None', '0123456789'),
('P_0006', 'US_0002', 'SY_0002', NULL, '21212121', 2121, 'Male', '212121', '2121', '212121'),
('P_0008', 'US_0002', 'SY_0001', 'DH_0001', 'AzrulTestHosting', 22, 'Male', 'Melaka', 'None', '0123456789'),
('P_0009', 'US_0006', 'SY_0001', NULL, 'hi', 23, 'Female', 'hj', 'ghg', '788');

--
-- Triggers `patient`
--
DELIMITER $$
CREATE TRIGGER `GeneratePatientID` BEFORE INSERT ON `patient` FOR EACH ROW BEGIN
    SET NEW.PatientID = CONCAT('P_', LPAD(GetNextPatient(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `ReceiptID` varchar(255) NOT NULL,
  `UserID` varchar(255) DEFAULT NULL,
  `OrderID` varchar(255) DEFAULT NULL,
  `GenericNames` varchar(255) NOT NULL,
  `Quantities` varchar(255) NOT NULL,
  `PaymentDate` date DEFAULT NULL,
  `TotalAmount` decimal(10,2) DEFAULT NULL,
  `PaymentMethod` varchar(255) DEFAULT NULL,
  `TransactionReference` varchar(255) DEFAULT NULL,
  `Vendor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`ReceiptID`, `UserID`, `OrderID`, `GenericNames`, `Quantities`, `PaymentDate`, `TotalAmount`, `PaymentMethod`, `TransactionReference`, `Vendor`) VALUES
('RC_0001', 'US_0002', 'O_0001', 'Paracetamol', '1', '2025-01-20', 6.60, 'COD', NULL, 'Farmasi Durian Tunggal'),
('RC_0002', 'US_0002', 'O_0002', 'Antibiotic', '1', '2025-01-20', 10.20, 'FPX', '3fs7vzn5', 'Farmasi Durian Tunggal'),
('RC_0003', 'US_0006', 'O_0004', 'Antibiotic', '1', '2025-01-20', 10.20, 'COD', NULL, 'Farmasi Durian Tunggal'),
('RC_0004', 'US_0006', 'O_0005', 'Antibiotic', '2', '2025-01-20', 20.40, 'COD', NULL, 'Farmasi Durian Tunggal'),
('RC_0005', 'US_0006', 'O_0006', 'Antibiotic', '2', '2025-01-20', 20.40, 'COD', NULL, 'Farmasi Durian Tunggal'),
('RC_0006', 'US_0006', 'O_0007', 'Paracetamol,Antibiotic', '1,1', '2025-01-20', 16.80, 'COD', NULL, 'Farmasi Durian Tunggal'),
('RC_0007', 'US_0002', 'O_0008', 'Antibiotic,Paracetamol', '1,1', '2025-01-20', 16.80, 'COD', NULL, 'Farmasi Durian Tunggal');

--
-- Triggers `receipt`
--
DELIMITER $$
CREATE TRIGGER `GenerateReceiptID` BEFORE INSERT ON `receipt` FOR EACH ROW BEGIN
    SET NEW.ReceiptID = CONCAT('RC_', LPAD(GetNextReceipt(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `ReminderID` varchar(255) NOT NULL,
  `PatientID` varchar(255) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `GenericName` varchar(255) NOT NULL,
  `ReminderDate` date DEFAULT NULL,
  `ReminderTime` time NOT NULL,
  `isCompleted` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminder`
--

INSERT INTO `reminder` (`ReminderID`, `PatientID`, `Title`, `Description`, `GenericName`, `ReminderDate`, `ReminderTime`, `isCompleted`) VALUES
('R_0001', 'P_0002', '2121', '2121', 'Antibiotic', '2024-12-30', '09:14:00', 'Completed'),
('R_0031', 'P_0002', 'hdhdgs', 'hdhxhx', 'Paracetamol', '2025-01-01', '20:56:00', 'Completed'),
('R_0032', 'P_0002', '8.57', '8.57', 'Paracetamol', '2025-01-01', '20:57:00', 'Completed'),
('R_0033', 'P_0002', '9.06', '9.06', 'Paracetamol', '2025-01-01', '21:06:00', 'Active'),
('R_0034', 'P_0002', '9.08', '9.08', 'Paracetamol', '2025-01-01', '21:08:00', 'Completed'),
('R_0035', 'P_0002', 'vggg', 'gggg', 'Paracetamol', '2025-01-01', '21:10:00', 'Completed'),
('R_0036', 'P_0002', '9.12', '9.12', 'Paracetamol', '2025-01-01', '21:12:00', 'Completed'),
('R_0037', 'P_0008', 'Alarm Testing', 'Reminder Testing', 'Paracetamol', '2025-01-01', '23:09:00', 'Completed'),
('R_0038', 'P_0008', 'Alarm Night', 'For dad', 'Paracetamol', '2025-01-01', '23:12:00', 'Completed'),
('R_0039', 'P_0002', 'Alarm test', 'testing', 'Paracetamol', '2025-01-01', '23:16:00', 'Completed'),
('R_0040', 'P_0002', 'Alarm at 12 am', 'alarm 12 am', 'Paracetamol', '2025-01-02', '00:00:00', 'Completed'),
('R_0041', 'P_0002', 'gaga', 'ss', 'Paracetamol', '2025-01-03', '17:04:00', 'Completed'),
('R_0042', 'P_0009', 'vino', 'ok', 'Paracetamol', '2025-01-19', '16:06:00', 'Active'),
('R_0043', 'P_0002', 'brtul', 'test', 'Paracetamol', '2025-01-20', '04:13:00', 'Completed');

--
-- Triggers `reminder`
--
DELIMITER $$
CREATE TRIGGER `GenerateReminderID` BEFORE INSERT ON `reminder` FOR EACH ROW BEGIN
    SET NEW.ReminderID = CONCAT('R_', LPAD(GetNextReminder(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

CREATE TABLE `symptoms` (
  `SymptomID` varchar(255) NOT NULL,
  `DrugHeaderID` varchar(255) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Severity` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`SymptomID`, `DrugHeaderID`, `Description`, `Severity`) VALUES
('SY_0001', 'DH_0001', 'Headache', 'Minimum'),
('SY_0002', 'DH_0002', 'Sakit', 'Maximum');

--
-- Triggers `symptoms`
--
DELIMITER $$
CREATE TRIGGER `GenerateSymptomID` BEFORE INSERT ON `symptoms` FOR EACH ROW BEGIN
    SET NEW.SymptomID = CONCAT('SY_', LPAD(GetNextSymptoms(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `UserID` varchar(255) NOT NULL,
  `Fullname` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Phonenumber` varchar(255) DEFAULT NULL,
  `DateOFBirth` date DEFAULT NULL,
  `ProfilePicture` varchar(255) DEFAULT NULL,
  `Role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`UserID`, `Fullname`, `Email`, `Password`, `Phonenumber`, `DateOFBirth`, `ProfilePicture`, `Role`) VALUES
('US_0001', 'Azrul', 'azrultest', '1234', '123431', NULL, '21321', '213'),
('US_0002', 'Vishinu', 'admin@gmail.com', '$2y$10$.Ugby1JCx76KmGhiXz1T3.MPMybiCNaZ/Kf3wLbzt4rCdJPtjvOnu', '0123456789', '2024-12-10', 'pp.jpg', 'Admin'),
('US_0003', 'Haikal', 'haikal@gmail.com', '$2y$10$w3BvkZNpi1ce00E7eZHLIO/JxXaP4ZjeNSNVa7n4FQcBQSfPs368S', NULL, NULL, NULL, 'user'),
('US_0004', 'jeevethaa', 'jeevethaa18@gmail.com', '$2y$10$2olsBT2S.i.NaKUf9OdLheb6ojzVlHqljFjkyz4uUPp36iXvSuUvC', NULL, NULL, NULL, 'user'),
('US_0005', 'monisha', 'monisha18@gmail.com', '$2y$10$nxcXyFUoah6VlXdtbXxBwuJcHhqtiKmnMGq.V4e/wHK41QwnxW/2.', NULL, NULL, NULL, 'user'),
('US_0006', 'vino', 'vino@gmail.com', '$2y$10$khaWljJLD56oAzjMmMxr2uP16r7T65lOyZcrLnxtpYvmSArw5Y2R.', NULL, NULL, NULL, 'user'),
('US_0007', 'tamil', 'tamil@gmail.com', '$2y$10$72ljBbT9xjuo/hddrd8gVONRC7kg7ceioZ1w/8dpIxhOGt7dZdYdy', NULL, NULL, NULL, 'user'),
('US_0008', 'a', 'amir@gmail.com', '$2y$10$QAtPAI/1dJB.TRGsYB0sZuLFUFPMv8RW8I.m/iFKXmEfw/XWpGqHy', '1', '2025-01-20', 'IMG20250120100903.jpg', 'user');

--
-- Triggers `user_account`
--
DELIMITER $$
CREATE TRIGGER `GenerateUserID` BEFORE INSERT ON `user_account` FOR EACH ROW BEGIN
    SET NEW.UserID = CONCAT('US_', LPAD(GetNextUserAccount(), 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `VendorID` varchar(200) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Address` text DEFAULT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Latitude` varchar(200) DEFAULT NULL,
  `Longitude` varchar(200) DEFAULT NULL,
  `ProfilePicture` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`VendorID`, `Fullname`, `Username`, `Password`, `Address`, `ContactNumber`, `Email`, `Latitude`, `Longitude`, `ProfilePicture`) VALUES
('V_0001', 'Farmasi Durian Tunggal', 'FDM', '1234', 'DT966 PEKAN, 76100 Durian Tunggal, Melaka', '06-553 3061', 'fdm@gmail.com', '2.3129343991111866', '102.28264782702448', 'fdm.png'),
('V_0002', 'Farmasi NK Melaka', 'FNK', '1234', 'Farmasi NK, DT18, Jalan Pusat Perniagaan Durian Tunggal 2 Pusat Perniagaan Durian Tunggal Durian Tunggal, 76100 Alor Gajah, Melaka', '065490927', 'fnk@gmail.com', '2.312388173095135', '102.28001973178266', 'fnk.jpg');

--
-- Triggers `vendor`
--
DELIMITER $$
CREATE TRIGGER `GenerateVendorID` BEFORE INSERT ON `vendor` FOR EACH ROW BEGIN
    SET NEW.VendorID = CONCAT('V_', LPAD(GetNextVendor(), 4, '0'));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`CartItemID`),
  ADD KEY `CartID` (`CartID`),
  ADD KEY `DrugID` (`DrugID`);

--
-- Indexes for table `drug_details`
--
ALTER TABLE `drug_details`
  ADD PRIMARY KEY (`DrugID`),
  ADD KEY `DrugHeaderID` (`DrugHeaderID`),
  ADD KEY `SupplyID` (`SupplyID`);

--
-- Indexes for table `drug_header`
--
ALTER TABLE `drug_header`
  ADD PRIMARY KEY (`DrugHeaderID`);

--
-- Indexes for table `drug_price`
--
ALTER TABLE `drug_price`
  ADD PRIMARY KEY (`PriceID`),
  ADD KEY `DrugID` (`DrugID`);

--
-- Indexes for table `drug_supply`
--
ALTER TABLE `drug_supply`
  ADD PRIMARY KEY (`SupplyID`),
  ADD KEY `DrugHeaderID` (`DrugHeaderID`),
  ADD KEY `VendorID` (`VendorID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `CartID` (`CartID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`),
  ADD KEY `fk_patient_userid` (`UserID`),
  ADD KEY `fk_patient_symptomid` (`SymptomID`),
  ADD KEY `fk_patient_drugheaderid` (`DrugHeaderID`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`ReceiptID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`ReminderID`),
  ADD KEY `fk_reminder_patientid` (`PatientID`);

--
-- Indexes for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`SymptomID`),
  ADD KEY `fk_symptoms_drugheaderid` (`DrugHeaderID`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`VendorID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user_account` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`DrugID`) REFERENCES `drug_details` (`DrugID`) ON DELETE CASCADE;

--
-- Constraints for table `drug_details`
--
ALTER TABLE `drug_details`
  ADD CONSTRAINT `drug_details_ibfk_1` FOREIGN KEY (`DrugHeaderID`) REFERENCES `drug_header` (`DrugHeaderID`),
  ADD CONSTRAINT `drug_details_ibfk_2` FOREIGN KEY (`SupplyID`) REFERENCES `drug_supply` (`SupplyID`);

--
-- Constraints for table `drug_price`
--
ALTER TABLE `drug_price`
  ADD CONSTRAINT `drug_price_ibfk_1` FOREIGN KEY (`DrugID`) REFERENCES `drug_details` (`DrugID`);

--
-- Constraints for table `drug_supply`
--
ALTER TABLE `drug_supply`
  ADD CONSTRAINT `drug_supply_ibfk_1` FOREIGN KEY (`DrugHeaderID`) REFERENCES `drug_header` (`DrugHeaderID`),
  ADD CONSTRAINT `drug_supply_ibfk_2` FOREIGN KEY (`VendorID`) REFERENCES `vendor` (`VendorID`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`) ON DELETE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `fk_patient_drugheaderid` FOREIGN KEY (`DrugHeaderID`) REFERENCES `drug_header` (`DrugHeaderID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_patient_symptomid` FOREIGN KEY (`SymptomID`) REFERENCES `symptoms` (`SymptomID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_patient_userid` FOREIGN KEY (`UserID`) REFERENCES `user_account` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `receipt_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user_account` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `receipt_ibfk_2` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`) ON DELETE CASCADE;

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `fk_reminder_patientid` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE;

--
-- Constraints for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD CONSTRAINT `fk_symptoms_drugheaderid` FOREIGN KEY (`DrugHeaderID`) REFERENCES `drug_header` (`DrugHeaderID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
