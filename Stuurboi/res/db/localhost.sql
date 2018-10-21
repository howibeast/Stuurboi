-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2018 at 07:57 AM
-- Server version: 10.2.12-MariaDB
-- PHP Version: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id4974121_stuurboi`
--
CREATE DATABASE IF NOT EXISTS `id4974121_stuurboi` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `id4974121_stuurboi`;

-- --------------------------------------------------------

--
-- Table structure for table `confimations`
--

CREATE TABLE `confimations` (
  `userId` int(11) NOT NULL,
  `driverId` int(11) NOT NULL,
  `pickupLocation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destinationLocation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `confirmPickup` enum('pickedUp','awaiting','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'awaiting',
  `confirmDelivery` enum('delivered','awaiting','noReceiver') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'awaiting',
  `dateCreated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `confimations`
--

INSERT INTO `confimations` (`userId`, `driverId`, `pickupLocation`, `destinationLocation`, `confirmPickup`, `confirmDelivery`, `dateCreated`) VALUES
(71, 52, 'Auckland Park, Johannesburg, South Africa', 'Klipspruit, Soweto, South Africa', 'pickedUp', 'delivered', '2018-10-14'),
(72, 52, 'Auckland Park, Johannesburg, South Africa', 'Klipspruit, Soweto, South Africa', 'pickedUp', 'delivered', '2018-10-14');

-- --------------------------------------------------------

--
-- Table structure for table `creditcards`
--

CREATE TABLE `creditcards` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `nameOnCard` varchar(50) NOT NULL,
  `cardNumber` bigint(20) NOT NULL,
  `expiryMonth` int(11) NOT NULL,
  `expiryYear` int(11) NOT NULL,
  `cvv` int(11) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `creditcards`
--

INSERT INTO `creditcards` (`id`, `userId`, `nameOnCard`, `cardNumber`, `expiryMonth`, `expiryYear`, `cvv`, `dateCreated`) VALUES
(1, 56, 'visa', 1562458566385, 12, 2018, 125, '2018-09-07 07:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `idNumber` varchar(50) NOT NULL,
  `idDocument` varchar(255) NOT NULL,
  `licenceNumber` varchar(255) NOT NULL,
  `licenceDocument` varchar(255) NOT NULL,
  `drivingExperience` varchar(255) NOT NULL,
  `criminalRecord` varchar(255) NOT NULL,
  `clearenceCertificate` varchar(255) NOT NULL,
  `facePhoto` varchar(255) NOT NULL,
  `pdp` varchar(255) NOT NULL,
  `licencePlate` varchar(9) NOT NULL,
  `model` varchar(50) NOT NULL,
  `transportType` enum('car','bike','bakkie','truck') NOT NULL DEFAULT 'car'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `userId`, `idNumber`, `idDocument`, `licenceNumber`, `licenceDocument`, `drivingExperience`, `criminalRecord`, `clearenceCertificate`, `facePhoto`, `pdp`, `licencePlate`, `model`, `transportType`) VALUES
(1, 55, '978546223514', 'doc', '456674dfd8', 'doc', '25', 'no', 'doc', 'doc', 'doc', '123 abc', 'something', 'car');

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 0, 'CODEX@123', 0, 0, 0, NULL, '2017-10-12 13:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `cardNumber` bigint(20) NOT NULL,
  `expiryMonth` int(11) NOT NULL,
  `expiryYear` int(11) NOT NULL,
  `cvv` int(11) NOT NULL,
  `nameOnCard` varchar(50) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `driverId` int(11) NOT NULL,
  `toAddress` varchar(255) NOT NULL,
  `fromAddress` varchar(255) NOT NULL,
  `receiverName` varchar(50) NOT NULL,
  `receiverCell` varchar(35) NOT NULL,
  `vehicleType` enum('car','bike','truck','bakkie') NOT NULL DEFAULT 'car',
  `estimationPrice` double NOT NULL,
  `status` enum('pending','cancelled','accepted') NOT NULL DEFAULT 'pending',
  `ratings` float DEFAULT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `userId`, `driverId`, `toAddress`, `fromAddress`, `receiverName`, `receiverCell`, `vehicleType`, `estimationPrice`, `status`, `ratings`, `dateCreated`) VALUES
(32, 55, 52, 'Klipspruit, Soweto, South Africa', 'Auckland Park, Johannesburg, South Africa', 'Mr Moodley', '0982442893', 'bike', 539.32, 'pending', 4, '2018-10-13 20:51:41');

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `driverId` int(10) NOT NULL,
  `requestId` int(11) NOT NULL,
  `pickupLocation` varchar(255) NOT NULL,
  `destinationLocation` varchar(255) NOT NULL,
  `duration` double NOT NULL,
  `mileage` double NOT NULL,
  `fare` double NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `gender` enum('MALE','FEMALE') NOT NULL,
  `email` varchar(100) NOT NULL,
  `cellNumber` varchar(15) NOT NULL,
  `password` varchar(100) NOT NULL,
  `avatar` text NOT NULL,
  `status` enum('active','unactive') NOT NULL DEFAULT 'unactive',
  `userType` enum('client','driver','owner','admin') NOT NULL DEFAULT 'client',
  `verificationCode` varchar(20) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `newUser` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `gender`, `email`, `cellNumber`, `password`, `avatar`, `status`, `userType`, `verificationCode`, `dateCreated`, `newUser`) VALUES
(52, 'MInenhle', 'Sibanda', 'MALE', 'mini.msib@gmail.com', '0643665645', 'e807f1fcf82d132f9bb018ca6738a19f', 'res/images/profilePics/default.jpg', 'active', 'driver', '2tsky', '2018-09-07 02:36:26', 0),
(55, 'ntuthuko', 'zikalala', 'FEMALE', 'ntuthuko.punka@gmail.com', '0786543873', 'e807f1fcf82d132f9bb018ca6738a19f', 'res/images/profilePics/default.jpg', 'active', 'client', 'kirot', '2018-09-07 04:57:45', 1),
(56, 'velly', 'vamba', 'MALE', 'velly.vamba@gmail.com', '0783420562', '25d55ad283aa400af464c76d713c07ad', 'res/images/profilePics/default.jpg', 'active', 'admin', 'gzjzl', '2018-09-07 07:01:26', 1),
(57, 'hloni', 'mphuthi', 'MALE', 'lmphuti@gmail.com', '0717738509', '25d55ad283aa400af464c76d713c07ad', 'res/images/profilePics/default.jpg', 'unactive', 'driver', 'qpqi3', '2018-09-07 10:58:54', 1),
(58, 'Vusi', 'Baloyi', 'MALE', 'vusi@gmail.com', '0786549856', '25f9e794323b453885f5181f1b624d0b', '123456789', 'unactive', 'client', '2avld', '2018-09-23 10:50:10', 1),
(59, 'Vusi', 'Baloyi', 'MALE', 'vusi@gmail.com', '0786549856', '25f9e794323b453885f5181f1b624d0b', '123456789', 'unactive', 'client', '7qsgw', '2018-09-24 13:07:26', 1),
(60, 'Vusi', 'Baloyi', 'MALE', 'vusi@gmail.com', '0786549856', '25f9e794323b453885f5181f1b624d0b', '123456789', 'unactive', 'client', 'sn8up', '2018-09-24 16:27:34', 1),
(61, 'hdhh', 'jdjdh', 'MALE', 'k@gmail.com', '0895886388', '8249d8a5582b1c1170e8c78d017a1697', '123456789', 'unactive', 'client', 't2n1y', '2018-09-24 17:29:41', 1),
(62, 'hdhh', 'jdjdh', 'MALE', 'k@gmail.com', '0895886388', '8249d8a5582b1c1170e8c78d017a1697', '123456789', 'unactive', 'client', 'cmx2q', '2018-09-24 17:29:44', 1),
(63, 'Vusi', 'Baloyi', 'MALE', 'vusi@gmail.com', '0786549856', '25f9e794323b453885f5181f1b624d0b', '123456789', 'unactive', 'client', 'jvwa2', '2018-09-24 18:03:09', 1),
(64, 'uuhsh', 'bbdhjdj', 'MALE', 'mn@gmail.com', '099738892', '9ec89bccce3887eb47b650e474a03b5e', '123456789', 'unactive', 'client', 't9z8c', '2018-09-24 18:21:35', 1),
(65, 'uuhsh', 'bbdhjdj', 'MALE', 'mn@gmail.com', '099738892', '9ec89bccce3887eb47b650e474a03b5e', '123456789', 'unactive', 'client', 'o7jdu', '2018-09-24 18:21:44', 1),
(66, 'uuhsh', 'bbdhjdj', 'MALE', 'mn@gmail.com', '099738892', '9ec89bccce3887eb47b650e474a03b5e', '123456789', 'unactive', 'client', '9ozk0', '2018-09-24 18:21:49', 1),
(67, 'uuhsh', 'bbdhjdj', 'MALE', 'mn@gmail.com', '099738892', '9ec89bccce3887eb47b650e474a03b5e', '123456789', 'unactive', 'client', 'cmdrc', '2018-09-24 18:22:09', 1),
(68, 'uuhsh', 'bbdhjdj', 'MALE', 'mn@gmail.com', '099738892', '9ec89bccce3887eb47b650e474a03b5e', '123456789', 'unactive', 'client', '9c2n0', '2018-09-24 18:22:12', 1),
(69, 'hj', 'bg', 'MALE', 'gvh@gmail.com', '008668965', '8a34731cf1cc3c71c66ac526db91838d', '123456789', 'unactive', 'client', 'kisaj', '2018-09-24 21:09:22', 1),
(70, 'ijh', 'jhhj', 'MALE', 'hhjhh', 'hgjhgg', '5ca0e27212f250a5b101fd1d4613c825', '123456789', 'unactive', 'client', 't8q8s', '2018-09-24 21:21:13', 1),
(72, 'ijh', 'jhhj', 'MALE', 'hhjhh', 'hgjhgg@gmail.co', '5ca0e27212f250a5b101fd1d4613c825', '123456789', 'unactive', 'client', 'f0clq', '2018-09-24 21:22:03', 1),
(73, 'ijh', 'jhhj', 'MALE', 'hhjhh', 'hgjhgg@gmail.co', '5ca0e27212f250a5b101fd1d4613c825', '123456789', 'unactive', 'client', 'th0ti', '2018-09-24 21:22:03', 1),
(74, 'ijh', 'jhhj', 'MALE', 'hgjhgg@gmail.com', '086569756', '10e9b00ee7450155fc919281176e36d1', '123456789', 'unactive', 'client', '7104d', '2018-09-24 21:22:42', 1),
(75, 'ijhhhjb', 'jhhjvvxjj', 'MALE', 'hgjhgg@gmail.com', '086569756', '10e9b00ee7450155fc919281176e36d1', '123456789', 'unactive', 'client', '70cdd', '2018-09-24 21:23:09', 1),
(76, 'gh', 'dg', 'MALE', 'b@gmail.com', '09875446664', 'ef464b179302ad845ee0dc40b1ae9d51', '123856789', 'unactive', 'client', '9t002', '2018-09-24 21:33:41', 1),
(77, 'hg', 'fh', 'MALE', 'nh@gmail.com', '0986658865', '6afc5df6975a0ec04b03e0350d86d180', '123456789', 'unactive', 'client', '1zupo', '2018-09-24 21:37:42', 1),
(78, 'hg', 'fh', 'MALE', 'nh@gmail.com', '0986658865', '6afc5df6975a0ec04b03e0350d86d180', '123456789', 'unactive', 'client', 'f5ibk', '2018-09-24 21:37:51', 1),
(79, 'hg', 'fh', 'MALE', 'gh@gmail.com', 'vjh', '7423834b32c4192a289ae35867a3c415', '123456789', 'unactive', 'client', 'mynh5', '2018-09-24 21:40:39', 1),
(80, 'nbv', 'dfg', 'MALE', 'mk@gmail.com', '087896557', 'b06533564ff4fbd4a0566462ccd2a9eb', '123456789', 'unactive', 'client', '7g7ko', '2018-09-24 21:46:17', 1),
(81, 'vbbf', 'sfhdd', 'FEMALE', 'bf@gmail.com', '887545776544', 'ea2084bb5e5cf3c1d60c33e2ffca7b90', '123456789', 'unactive', 'client', 'tkmqy', '2018-09-24 22:00:38', 1),
(82, 'vbbf', 'sfhdd', 'FEMALE', 'bf@gmail.com', '887545776544', 'ea2084bb5e5cf3c1d60c33e2ffca7b90', '123456789', 'unactive', 'client', 'mt49t', '2018-09-24 22:00:41', 1),
(83, 'vbbf', 'sfhdd', 'FEMALE', 'bf@gmail.com', '887545776544', 'ea2084bb5e5cf3c1d60c33e2ffca7b90', '123456789', 'unactive', 'client', 'd4daz', '2018-09-24 22:01:04', 1),
(84, 'gfdf', 'gghh', 'MALE', 'chh@gmail.com', '9865345677', '07e5fffad055d0fd5d81ce4eebd980ec', '123456789', 'unactive', 'client', 'fnin3', '2018-09-24 22:05:21', 1),
(85, 'gfdf', 'gghh', 'MALE', 'chh@gmail.com', '9865345677', '07e5fffad055d0fd5d81ce4eebd980ec', '123456789', 'unactive', 'client', 'sv4y6', '2018-09-24 22:05:27', 1),
(86, 'mig', 'ddhgf', 'MALE', 'bc@gmail.com', '98633575433', 'df22776575796bc8d020bb56010768cb', '123456789', 'unactive', 'client', 'rve36', '2018-09-24 22:06:48', 1),
(87, 'mig', 'ddhgf', 'MALE', 'bc@gmail.com', '98633575433', 'df22776575796bc8d020bb56010768cb', '123456789', 'unactive', 'client', 'mi63r', '2018-09-24 22:06:55', 1),
(88, 'mig', 'ddhgf', 'MALE', 'bc@gmail.com', '98633575433', 'df22776575796bc8d020bb56010768cb', '123456789', 'unactive', 'client', 'gzmpu', '2018-09-24 22:07:01', 1),
(89, 'mig', 'ddhgf', 'MALE', 'bc@gmail.com', '98633575433', 'df22776575796bc8d020bb56010768cb', '123456789', 'unactive', 'client', 'g6v3c', '2018-09-24 22:13:44', 1),
(90, 'bbcg', 'fghugv', 'MALE', 'tgjhvg', '9666889', '643760ec0d9c5667a1b71a733af8e38f', '123456789', 'unactive', 'client', 'ko7yv', '2018-09-24 22:16:15', 1),
(91, 'bbcg', 'fghugv', 'MALE', 'tgjhvg', '9666889', '643760ec0d9c5667a1b71a733af8e38f', '123456789', 'unactive', 'client', 'rtrpb', '2018-09-24 22:16:37', 1),
(92, 'hbvh', 'gcghh', 'MALE', 'hvfhhh', 'hcchvcghj', '7a5a289b3dde95bb577c5409b6879231', '123456789', 'unactive', 'client', '8p4di', '2018-09-24 22:47:14', 1),
(93, 'hbvh', 'gcghh', 'MALE', 'hvfhhh', 'hcchvcghj', '7a5a289b3dde95bb577c5409b6879231', '123456789', 'unactive', 'client', '3e58p', '2018-09-24 22:47:29', 1),
(94, 'nhgh', 'hdyjj', 'MALE', 'hv', 'cchjj', 'fd346ebf073bbf76b40fe77923fb5ff4', '123456789', 'unactive', 'client', 'tme7f', '2018-09-24 22:54:23', 1),
(95, 'bvhjbn', 'hvvujk', 'MALE', 'hbvhjnj', 'gvjnvui', '7411948ddbf604ed69af76855930622e', '123456789', 'unactive', 'client', '34jpw', '2018-09-24 23:02:24', 1),
(96, 'hfsdff', 'chhgff', 'MALE', 'jujgff', 'dfghffd', 'da460db42393eddc65141062fc33fc5e', '123456789', 'unactive', 'client', 'nj8ho', '2018-09-24 23:08:56', 1),
(97, 'hgghb', 'vcghbb', 'MALE', 'vvhjnbb', 'gcghbvbb', 'e1528e4bcd2c124e77729824efa65292', '123456789', 'unactive', 'client', 'pqaas', '2018-09-24 23:11:09', 1),
(98, 'hgghb', 'vcghbb', 'MALE', 'vvhjnbb', 'gcghbvbb', 'e1528e4bcd2c124e77729824efa65292', '123456789', 'unactive', 'client', '89hyl', '2018-09-24 23:11:12', 1),
(99, 'jbcghj', 'vcghhggg', 'MALE', 'gghjbvh', 'gchjbbh', '62dfaae8b079ec66d3f09605a12d2e30', '123456789', 'unactive', 'client', 'lhy3b', '2018-09-24 23:14:36', 1),
(100, 'chvct', 'fzdfhh', 'MALE', 'hgxfh', 'fdfhhcch', '73d32dc4d4b4171cfc2c5a5afe721273', '123456789', 'unactive', 'client', 'f0b47', '2018-09-24 23:21:42', 1),
(101, 'fyhvv', 'hcvhbv', 'MALE', 'hcghbbb', 'bcgu cg', '0576e89c5273c54532513a7b57d34251', '123456789', 'unactive', 'client', '9bxer', '2018-09-25 06:27:29', 1),
(102, 'h', 'h', 'MALE', 'b', 'v', '270606590fe38a53ccb0e5d231d7dfd3', '123456789', 'unactive', 'client', '71kk6', '2018-09-25 06:32:05', 1),
(103, 'kfkjfj', 'fnnfkk', 'MALE', 'mfnfmkrn', 'nfnfkjrn', '71dfbb2ce385f8503318a28cea04168a', 'docs/newUser/image.png', 'unactive', 'client', 'f2apz', '2018-09-25 08:19:14', 1),
(104, 'Testing', 'mdgh', 'MALE', 'vvcchbc', 'bbjjj@gmail.com', '2046a430e20083fa0ecc62d198d15cc1', 'docs/newUser/image.png', 'unactive', 'client', 'mubvd', '2018-09-25 23:04:36', 1),
(105, 'jjfj', 'ncnkfk', 'MALE', 'fjjjf', 'jdjkfjkf', '69404935ffc499eaec2499ad58f34b59', 'docs/newUser/image.png', 'unactive', 'client', 'og8zo', '2018-09-25 23:29:10', 1),
(106, 'b', 'h', 'MALE', 'h', 'g', '2510c39011c5be704182423e3a695e91', 'docs/newUser/image.png', 'unactive', 'client', 'e7g36', '2018-09-25 23:54:31', 1),
(107, 'h', 'g', 'MALE', 'h', 'g', '2510c39011c5be704182423e3a695e91', 'docs/newUser/image.png', 'unactive', 'client', 'j9xsw', '2018-09-26 00:11:12', 1),
(108, 'driver', 'driver', 'MALE', 'driver@gamil.com', '087765543', 'e10adc3949ba59abbe56e057f20f883e', 'docs/newUser/image.png', 'unactive', 'driver', 'dgn9d', '2018-10-14 12:56:46', 1),
(109, 'n', 'n', 'FEMALE', 'ntuzikalala@gmail.com', '008764333', '25f9e794323b453885f5181f1b624d0b', 'docs/newUser/image.png', 'active', 'driver', 'mzdjj', '2018-10-14 15:27:57', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `confimations`
--
ALTER TABLE `confimations`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `creditcards`
--
ALTER TABLE `creditcards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `confimations`
--
ALTER TABLE `confimations`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `creditcards`
--
ALTER TABLE `creditcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
