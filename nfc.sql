-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2023 at 07:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nfc`
--

-- --------------------------------------------------------

--
-- Table structure for table `farmer`
--

CREATE TABLE `farmer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `rate` double NOT NULL,
  `discount` double NOT NULL,
  `details` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pending` double NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer`
--

INSERT INTO `farmer` (`id`, `name`, `mobile`, `rate`, `discount`, `details`, `address`, `pending`, `status`) VALUES
(1, 'Mosin', '8888763562', 10, 1, '-', 'Kop', 0, 1),
(2, 'Demo', '2222222223', 0, 0, 'Kop', 'Kop', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `farmer_payment`
--

CREATE TABLE `farmer_payment` (
  `id` int(11) NOT NULL,
  `farmerid` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` double NOT NULL,
  `mode` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updatedby` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer_payment`
--

INSERT INTO `farmer_payment` (`id`, `farmerid`, `date`, `amount`, `mode`, `details`, `lastupdate`, `updatedby`, `status`) VALUES
(1, 0, '2023-11-29', 500, 'UPI', 'Advance', '2023-11-30 17:39:43', 1, 0),
(2, 1, '2023-11-29', 500, 'UPI', 'Advance', '2023-11-30 17:40:16', 1, 1),
(3, 1, '2023-11-16', 100, 'Cheque', 'Advance', '2023-12-02 17:45:36', 1, 0),
(4, 1, '2023-12-01', 100, 'UPI', 'Aa', '2023-12-03 17:10:48', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `farmer_purchase`
--

CREATE TABLE `farmer_purchase` (
  `id` int(11) NOT NULL,
  `farmerid` int(11) NOT NULL,
  `date` date NOT NULL,
  `carate` double NOT NULL,
  `weight` double NOT NULL,
  `totalweight` double NOT NULL,
  `discount` double NOT NULL,
  `actualweight` double NOT NULL,
  `rate` double NOT NULL,
  `totalamount` double NOT NULL,
  `status` int(11) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer_purchase`
--

INSERT INTO `farmer_purchase` (`id`, `farmerid`, `date`, `carate`, `weight`, `totalweight`, `discount`, `actualweight`, `rate`, `totalamount`, `status`, `time`) VALUES
(1, 1, '0000-00-00', 2000, 10, 0, 5, 19000, 2.5, 11875, 0, '2023-12-10 20:05:12'),
(2, 1, '0000-00-00', 1000, 10, 0, 1, 9900, 1, 2475, 0, '2023-12-10 20:06:25'),
(3, 1, '2023-12-06', 1000, 10, 10000, 1, 9900, 1, 2475, 1, '2023-12-10 20:08:37'),
(4, 1, '2023-12-05', 1000, 6, 6000, 5, 5970, 1, 1492.5, 1, '2023-12-11 22:49:36');

-- --------------------------------------------------------

--
-- Table structure for table `labour_vendor`
--

CREATE TABLE `labour_vendor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `details` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pending` double NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labour_vendor`
--

INSERT INTO `labour_vendor` (`id`, `name`, `mobile`, `details`, `address`, `pending`, `status`) VALUES
(1, 'Mosin M', '8888763564', 'demo', 'kolahpur', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `labour_vendor_payment`
--

CREATE TABLE `labour_vendor_payment` (
  `id` int(11) NOT NULL,
  `labourvendorid` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` double NOT NULL,
  `mode` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updatedby` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labour_vendor_payment`
--

INSERT INTO `labour_vendor_payment` (`id`, `labourvendorid`, `date`, `amount`, `mode`, `details`, `lastupdate`, `updatedby`, `status`) VALUES
(1, 1, '2023-11-28', 600, 'UPI', '-', '2023-12-03 16:37:01', 1, 1),
(2, 1, '2023-11-26', 200, 'UPI', 'd', '2023-12-03 16:37:16', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `labour_vendor_work`
--

CREATE TABLE `labour_vendor_work` (
  `id` int(11) NOT NULL,
  `labourvendorid` int(11) NOT NULL,
  `date` date NOT NULL,
  `gents` int(11) NOT NULL,
  `ladies` int(11) NOT NULL,
  `gentscharges` double NOT NULL,
  `ladiescharges` double NOT NULL,
  `vehicle` varchar(100) NOT NULL,
  `vehiclecharges` double NOT NULL,
  `location` text NOT NULL,
  `amount` double NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labour_vendor_work`
--

INSERT INTO `labour_vendor_work` (`id`, `labourvendorid`, `date`, `gents`, `ladies`, `gentscharges`, `ladiescharges`, `vehicle`, `vehiclecharges`, `location`, `amount`, `time`, `status`) VALUES
(1, 1, '2023-11-29', 5, 1, 0, 0, '', 0, '-', 1000, '2023-12-03 17:05:20', 1),
(2, 1, '2023-12-04', 10, 5, 0, 0, '', 0, '10', 1000, '2023-12-10 17:44:33', 1),
(3, 1, '2023-12-05', 1, 1, 0, 0, '', 0, '1', 1000, '2023-12-10 17:45:59', 1),
(4, 1, '2023-12-05', 10, 5, 500, 100, '', 5, '2000', 7500, '2023-12-11 18:30:38', 1),
(5, 1, '2023-12-05', 10, 5, 500, 100, '', 5, '2000', 7500, '2023-12-11 18:31:03', 1),
(6, 1, '2023-12-05', 10, 5, 500, 100, 'MH09EK9844', 5, '2000', 7500, '2023-12-11 18:31:53', 1),
(7, 1, '2023-12-12', 20, 10, 100, 50, 'MH09EK9844', 500, 'dem', 3000, '2023-12-11 18:38:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `email`, `name`, `status`) VALUES
(1, 'mosin', 'e10adc3949ba59abbe56e057f20f883e', 'shdinde@gmail.com', 'Shailesh Dinde', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `details` text NOT NULL,
  `address` text NOT NULL,
  `pending` double NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`id`, `name`, `mobile`, `details`, `address`, `pending`, `status`) VALUES
(1, 'Mosin', '8888763560', 'Kop', 'Kolhapur', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payment`
--

CREATE TABLE `vendor_payment` (
  `id` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` double NOT NULL,
  `mode` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updatedby` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_payment`
--

INSERT INTO `vendor_payment` (`id`, `vendorid`, `date`, `amount`, `mode`, `details`, `lastupdate`, `updatedby`, `status`) VALUES
(1, 1, '2023-12-05', 1000, 'UPI', 'demo', '2023-12-10 18:01:29', 1, 1),
(2, 1, '2023-12-06', 1000, 'Online', 'demo', '2023-12-11 18:01:04', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_purchase`
--

CREATE TABLE `vendor_purchase` (
  `id` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `date` date NOT NULL,
  `caret_rate` double NOT NULL,
  `rope_rate` double NOT NULL,
  `paper_rate` double NOT NULL,
  `tape_rate` double NOT NULL,
  `box_rate` double NOT NULL,
  `collingbox_rate` double NOT NULL,
  `caret_quantity` double NOT NULL,
  `rope_quantity` double NOT NULL,
  `paper_quantity` double NOT NULL,
  `tape_quantity` double NOT NULL,
  `box_quantity` double NOT NULL,
  `collingbox_quantity` double NOT NULL,
  `discount` double NOT NULL,
  `other_charges` decimal(10,0) NOT NULL,
  `total` double NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lastupdateby` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_purchase`
--

INSERT INTO `vendor_purchase` (`id`, `vendorid`, `date`, `caret_rate`, `rope_rate`, `paper_rate`, `tape_rate`, `box_rate`, `collingbox_rate`, `caret_quantity`, `rope_quantity`, `paper_quantity`, `tape_quantity`, `box_quantity`, `collingbox_quantity`, `discount`, `other_charges`, `total`, `lastupdate`, `lastupdateby`, `status`) VALUES
(1, 1, '2023-12-05', 10, 10, 45, 300, 32, 2000, 100, 20, 500, 20, 1000, 10, 0, 0, 81700, '2023-12-10 18:23:01', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `amount` double NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`id`, `name`, `details`, `mobile`, `amount`, `status`) VALUES
(1, 'Mosin', 'Kolhapur', '8888763562', 0, 1),
(2, 'Jain', 'Pune', '8888763560', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `worker_payment`
--

CREATE TABLE `worker_payment` (
  `id` int(11) NOT NULL,
  `workerid` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` double NOT NULL,
  `mode` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updatedby` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worker_payment`
--

INSERT INTO `worker_payment` (`id`, `workerid`, `date`, `amount`, `mode`, `details`, `lastupdate`, `updatedby`, `status`) VALUES
(1, 2, '2023-11-30', 500, 'UPI', 'Tea', '2023-12-02 17:26:27', 1, 1),
(2, 1, '2023-12-01', 1000, 'UPI', '-', '2023-12-02 17:26:44', 1, 1),
(3, 1, '2023-11-27', 500, 'UPI', ' ', '2023-12-02 17:26:59', 1, 1),
(4, 1, '2023-12-01', 500, 'Cheque', '-', '2023-12-03 17:11:47', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `farmer`
--
ALTER TABLE `farmer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `farmer_payment`
--
ALTER TABLE `farmer_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farmer_purchase`
--
ALTER TABLE `farmer_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labour_vendor`
--
ALTER TABLE `labour_vendor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `labour_vendor_payment`
--
ALTER TABLE `labour_vendor_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labour_vendor_work`
--
ALTER TABLE `labour_vendor_work`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payment`
--
ALTER TABLE `vendor_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_purchase`
--
ALTER TABLE `vendor_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_payment`
--
ALTER TABLE `worker_payment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `farmer`
--
ALTER TABLE `farmer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `farmer_payment`
--
ALTER TABLE `farmer_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `farmer_purchase`
--
ALTER TABLE `farmer_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `labour_vendor`
--
ALTER TABLE `labour_vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `labour_vendor_payment`
--
ALTER TABLE `labour_vendor_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `labour_vendor_work`
--
ALTER TABLE `labour_vendor_work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor_payment`
--
ALTER TABLE `vendor_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor_purchase`
--
ALTER TABLE `vendor_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `worker_payment`
--
ALTER TABLE `worker_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
