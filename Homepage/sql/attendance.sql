-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 05:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_info`
--

CREATE TABLE `academic_info` (
  `RegisterNo` varchar(10) NOT NULL,
  `Semester1` int(5) NOT NULL,
  `Semester2` int(5) NOT NULL,
  `Semester3` int(5) NOT NULL,
  `Semester4` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_info`
--

INSERT INTO `academic_info` (`RegisterNo`, `Semester1`, `Semester2`, `Semester3`, `Semester4`) VALUES
('1', 80, 80, 80, 80);

--
-- Triggers `academic_info`
--
DELIMITER $$
CREATE TRIGGER `verify_academic_info` AFTER UPDATE ON `academic_info` FOR EACH ROW BEGIN
    -- Check if any of the student's academic details were modified
    IF NEW.Semester1 != OLD.Semester1 OR
       NEW.Semester2 != OLD.Semester2 OR
       NEW.Semester3 != OLD.Semester3 OR
       NEW.Semester4 != OLD.Semester4 THEN
       
       -- Update the Status to 'Not Verified' for the corresponding student in student_user table
       UPDATE student_user
       SET Status = 'Not Verified'
       WHERE RegisterNo = NEW.RegisterNo;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE `admin_user` (
  `ID` int(10) NOT NULL,
  `Name` text NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `MobileNo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`ID`, `Name`, `UserName`, `Email`, `Password`, `MobileNo`) VALUES
(1, 'Admin', 'Admin@123', 'admin@gmail.com', 'Admin@123', '9764985463');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_record`
--

CREATE TABLE `attendance_record` (
  `RegisterNo` varchar(20) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Status` enum('Present','Absent') DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Hour` enum('1st Hour','2nd Hour','3rd Hour','4th Hour','5th Hour') DEFAULT NULL,
  `Batch` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_record`
--

INSERT INTO `attendance_record` (`RegisterNo`, `Name`, `Status`, `Date`, `Hour`, `Batch`) VALUES
('1', 'Sam', 'Present', '2025-03-13', '1st Hour', '2024-2026');

-- --------------------------------------------------------

--
-- Table structure for table `bank_info`
--

CREATE TABLE `bank_info` (
  `RegisterNo` varchar(10) NOT NULL,
  `BankName` text NOT NULL,
  `AccountNumber` varchar(255) NOT NULL,
  `IFSCCODE` varchar(50) NOT NULL,
  `BankBranch` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_info`
--

INSERT INTO `bank_info` (`RegisterNo`, `BankName`, `AccountNumber`, `IFSCCODE`, `BankBranch`) VALUES
('1', 'ABC', '1234567899999', 'ABC9874563', 'TN');

--
-- Triggers `bank_info`
--
DELIMITER $$
CREATE TRIGGER `verify_bank_info` AFTER UPDATE ON `bank_info` FOR EACH ROW BEGIN
    -- Check if any of the student's bank details were modified
    IF NEW.BankName != OLD.BankName OR
       NEW.AccountNumber != OLD.AccountNumber OR
       NEW.IFSCCODE != OLD.IFSCCODE OR
       NEW.BankBranch != OLD.BankBranch THEN
       
       -- Update the Status to 'Not Verified' for the corresponding student in student_user table
       UPDATE student_user
       SET Status = 'Not Verified'
       WHERE RegisterNo = NEW.RegisterNo;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `personal_info`
--

CREATE TABLE `personal_info` (
  `RegisterNo` varchar(10) NOT NULL,
  `Name` text NOT NULL,
  `MobileNo` varchar(20) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `Community` text NOT NULL,
  `CommunityName` text NOT NULL,
  `DateOfBirth` date NOT NULL,
  `Gender` text NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Religion` text NOT NULL,
  `AadharNo` varchar(15) NOT NULL,
  `Batch` varchar(20) NOT NULL,
  `FatherName` text NOT NULL,
  `AnnualIncome` int(10) NOT NULL,
  `Disability` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_info`
--

INSERT INTO `personal_info` (`RegisterNo`, `Name`, `MobileNo`, `Address`, `Community`, `CommunityName`, `DateOfBirth`, `Gender`, `Email`, `Religion`, `AadharNo`, `Batch`, `FatherName`, `AnnualIncome`, `Disability`) VALUES
('1', 'Sam', '9648751200', 'TN', 'BC', 'ABC', '2001-09-15', 'Male', 'Sam123@gmail.com', 'Hindu', '154298867458', '2024-2026', 'Rajesh', 50000, 'No');

--
-- Triggers `personal_info`
--
DELIMITER $$
CREATE TRIGGER `verify_student_data` AFTER UPDATE ON `personal_info` FOR EACH ROW BEGIN
    -- Check if any of the student's personal details were modified
    IF NEW.Name != OLD.Name OR
       NEW.MobileNo != OLD.MobileNo OR
       NEW.Address != OLD.Address OR
       NEW.Community != OLD.Community OR
       NEW.CommunityName != OLD.CommunityName OR
       NEW.DateOfBirth != OLD.DateOfBirth OR
       NEW.Gender != OLD.Gender OR
       NEW.Email != OLD.Email OR
       NEW.Religion != OLD.Religion OR
       NEW.AadharNo != OLD.AadharNo OR
       NEW.FatherName != OLD.FatherName OR
       NEW.AnnualIncome != OLD.AnnualIncome OR
       NEW.Disability != OLD.Disability THEN
       
       -- Update the Status to 'Verified' for the corresponding student in student_user table
       UPDATE student_user
       SET Status = 'Not Verified'
       WHERE RegisterNo = NEW.RegisterNo;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `project_info`
--

CREATE TABLE `project_info` (
  `RegisterNo` int(10) NOT NULL,
  `ProjectTitle` text NOT NULL,
  `GuideName` text NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_info`
--

INSERT INTO `project_info` (`RegisterNo`, `ProjectTitle`, `GuideName`, `Description`) VALUES
(1, 'SIMS', 'Guide', 'AMS');

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE `queries` (
  `query_id` int(5) NOT NULL,
  `register_no` varchar(20) NOT NULL,
  `student_name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `query_description` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `query_raised_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_taken_time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queries`
--

INSERT INTO `queries` (`query_id`, `register_no`, `student_name`, `email`, `subject`, `query_description`, `file_path`, `status`, `query_raised_time`, `action_taken_time`) VALUES
(5, '1', 'Sam', 'Sam123@gmail.com', 'Personal Information', 'Need to update my personal Data', '', 'Resolved', '2025-03-21 16:44:48', '2025-03-21');

-- --------------------------------------------------------

--
-- Table structure for table `staff_user`
--

CREATE TABLE `staff_user` (
  `ID` int(10) NOT NULL,
  `StaffName` text NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `PhoneNo` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `DepartmentID` int(10) NOT NULL,
  `DepartmentName` text NOT NULL,
  `Designation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_user`
--

INSERT INTO `staff_user` (`ID`, `StaffName`, `UserName`, `Email`, `PhoneNo`, `Password`, `DepartmentID`, `DepartmentName`, `Designation`) VALUES
(27, 'Rajesh', 'Staff_123', 'staff@gmail.com', '9874563210', 'Test@123', 1, 'B.Sc CS', 'Assistant Professor');

-- --------------------------------------------------------

--
-- Table structure for table `student_user`
--

CREATE TABLE `student_user` (
  `RegisterNo` varchar(10) NOT NULL,
  `Name` text NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Status` enum('','Verified','Not Verified') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_user`
--

INSERT INTO `student_user` (`RegisterNo`, `Name`, `UserName`, `Password`, `Email`, `Status`) VALUES
('1', 'Sam', 'Sam@123', 'TestStd@123', 'Sam123@gmail.com', 'Verified');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `MobileNo` (`MobileNo`);

--
-- Indexes for table `attendance_record`
--
ALTER TABLE `attendance_record`
  ADD UNIQUE KEY `Batch` (`Batch`,`Date`,`Hour`);

--
-- Indexes for table `personal_info`
--
ALTER TABLE `personal_info`
  ADD UNIQUE KEY `MobileNo` (`MobileNo`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`query_id`);

--
-- Indexes for table `staff_user`
--
ALTER TABLE `staff_user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `PhoneNo` (`PhoneNo`);

--
-- Indexes for table `student_user`
--
ALTER TABLE `student_user`
  ADD PRIMARY KEY (`RegisterNo`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
  MODIFY `query_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff_user`
--
ALTER TABLE `staff_user`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
