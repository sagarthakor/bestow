-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 19, 2019 at 12:28 AM
-- Server version: 5.6.44-cll-lve
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Dwarkesh_bill`
--
CREATE DATABASE IF NOT EXISTS `Dwarkesh_bill` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Dwarkesh_bill`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `change_background_image`
--

CREATE TABLE `change_background_image` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `change_background_image`
--

INSERT INTO `change_background_image` (`id`, `img`) VALUES
(1, 'architecture-background-brick-194096.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `logo`
--

CREATE TABLE `logo` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logo`
--

INSERT INTO `logo` (`id`, `img`) VALUES
(2, 'DP.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_add_invoice_entry_mst`
--

CREATE TABLE `tbl_add_invoice_entry_mst` (
  `id` int(11) NOT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `invoice_name` varchar(255) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `business_details_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `poso_number` varchar(11) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `paymentdue_date` date DEFAULT NULL,
  `subtotal` decimal(11,2) DEFAULT NULL,
  `gst_amount` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `notes` text,
  `footer` text,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_add_invoice_entry_mst`
--

INSERT INTO `tbl_add_invoice_entry_mst` (`id`, `company_logo`, `invoice_name`, `summary`, `business_details_id`, `customer_id`, `invoice_number`, `poso_number`, `invoice_date`, `paymentdue_date`, `subtotal`, `gst_amount`, `total`, `notes`, `footer`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'assets/images/logo.png', 'Invoice', 'it business', NULL, 1, '1', 'po00111', '2019-08-20', '2019-08-31', '439990.00', '13499.82', '453489.82', 'Pleach Check ur all details in invoice ', 'Dwarkesh@2019', '2019-08-20 12:58:50', 1, NULL, NULL, 1),
(3, 'assets/images/logo.png', 'Invoice', 'itvcvdcv', NULL, 1, '2', 'poso2222', '2019-09-05', '2019-09-07', '2500.00', '90.00', '2590.00', 'check mobile quality ', 'Dwarkesh@2019', '2019-08-20 15:08:25', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_add_invoice_sub_entry_mst`
--

CREATE TABLE `tbl_add_invoice_sub_entry_mst` (
  `id` int(11) NOT NULL,
  `add_invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_add_invoice_sub_entry_mst`
--

INSERT INTO `tbl_add_invoice_sub_entry_mst` (`id`, `add_invoice_id`, `product_id`, `description`, `quantity`, `price`, `tax_id`, `amount`) VALUES
(1, 1, 6, 'series2 wireless', 10, '12999.00', 1, '129990.00'),
(2, 1, 1, 'samsung a80', 5, '62000.00', 1, '310000.00'),
(5, 3, 1, 'sony c9 ultra dual', 5, '500.00', 1, '2500.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city_mst`
--

CREATE TABLE `tbl_city_mst` (
  `id` int(11) NOT NULL,
  `city_name` varchar(255) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_city_mst`
--

INSERT INTO `tbl_city_mst` (`id`, `city_name`, `province_id`, `country_id`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'Vadodara', 1, 5, '2019-07-29 15:22:35', 1, NULL, NULL, 1),
(2, 'Tacoma', 2, 3, '2019-07-30 12:37:54', 1, NULL, NULL, 1),
(3, 'vyara', 1, 1, '2019-07-30 17:19:34', 1, '2019-07-30 17:19:44', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_country_mst`
--

CREATE TABLE `tbl_country_mst` (
  `id` int(11) NOT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_country_mst`
--

INSERT INTO `tbl_country_mst` (`id`, `country_name`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(2, 'China', '2019-07-26 17:05:43', 1, '2019-07-30 17:15:26', 1, 0),
(3, 'United States', '2019-07-26 17:06:11', 1, NULL, NULL, 1),
(4, 'UK', '2019-07-29 12:39:23', 1, NULL, NULL, 1),
(5, 'India', '2019-08-07 11:01:34', 1, NULL, NULL, 1),
(7, 'Germoney', '2019-08-12 14:27:55', 1, NULL, NULL, 1),
(8, 'Germoney', '2019-08-12 14:36:41', 1, NULL, NULL, 1),
(9, 'Germoney', '2019-08-12 15:04:37', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_currency_mst`
--

CREATE TABLE `tbl_currency_mst` (
  `id` int(11) NOT NULL,
  `currency_name` varchar(255) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_currency_mst`
--

INSERT INTO `tbl_currency_mst` (`id`, `currency_name`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'INR', '2019-08-07 10:58:06', 1, NULL, NULL, 1),
(2, 'Doller', '2019-07-30 17:12:37', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_mst`
--

CREATE TABLE `tbl_customer_mst` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `ac_number` varchar(255) DEFAULT NULL,
  `address_line1` text,
  `address_line2` text,
  `country_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `toll_free` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `shipping_address_status` int(11) DEFAULT NULL,
  `ship_to_contact` varchar(255) DEFAULT NULL,
  `sa_address_line1` varchar(255) DEFAULT NULL,
  `sa_address_line2` varchar(255) DEFAULT NULL,
  `sa_country_id` int(11) DEFAULT NULL,
  `sa_province_id` int(11) DEFAULT NULL,
  `sa_city_id` int(11) DEFAULT NULL,
  `sa_zip_code` varchar(255) DEFAULT NULL,
  `delivery_instructions` text,
  `sa_phone` varchar(255) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_customer_mst`
--

INSERT INTO `tbl_customer_mst` (`id`, `customer_name`, `email`, `first_name`, `last_name`, `currency_id`, `ac_number`, `address_line1`, `address_line2`, `country_id`, `province_id`, `city_id`, `zip_code`, `phone`, `fax`, `mobile`, `toll_free`, `website`, `shipping_address_status`, `ship_to_contact`, `sa_address_line1`, `sa_address_line2`, `sa_country_id`, `sa_province_id`, `sa_city_id`, `sa_zip_code`, `delivery_instructions`, `sa_phone`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'Ekta Patel', 'ekta@dwarkeshit.com', 'Ekta', 'Patel', 1, '020210018525', 'Omkar Residency , Near narayan school', 'Waghodia road- 390019', 3, 2, 2, '390019', '9726829390', '02652542154', '7284861939', '180018002020', 'www.dwarkeshit.com', NULL, 'Dhaval', 'vasad road, highway', 'Vadodara', 5, 1, 1, '390029', 'check quatation bill', '1214151612', '2019-07-30 14:29:19', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_estimate_entry_mst`
--

CREATE TABLE `tbl_estimate_entry_mst` (
  `id` int(11) NOT NULL,
  `estimate_name` varchar(255) DEFAULT NULL,
  `estimate_number` varchar(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `expires_date` date DEFAULT NULL,
  `estimate_po_so` varchar(255) DEFAULT NULL,
  `subheading` varchar(255) DEFAULT NULL,
  `footer` text,
  `memo` varchar(255) DEFAULT NULL,
  `subtotal` decimal(11,2) DEFAULT NULL,
  `gst_amount` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_estimate_entry_mst`
--

INSERT INTO `tbl_estimate_entry_mst` (`id`, `estimate_name`, `estimate_number`, `customer_id`, `currency_id`, `date`, `expires_date`, `estimate_po_so`, `subheading`, `footer`, `memo`, `subtotal`, `gst_amount`, `total`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'Estimate', '1', 1, 1, '2019-08-01', '2019-08-31', 'poso', 'Mobile Quotation', 'Dwarkesh@2019', 'Good', '515000.00', '11700.00', '526700.00', '2019-08-20 10:08:44', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_estimate_sub_entry_mst`
--

CREATE TABLE `tbl_estimate_sub_entry_mst` (
  `id` int(11) NOT NULL,
  `estimate_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_estimate_sub_entry_mst`
--

INSERT INTO `tbl_estimate_sub_entry_mst` (`id`, `estimate_id`, `item_id`, `description`, `quantity`, `price`, `tax_id`, `amount`) VALUES
(1, 1, 1, 'apple', 10, '50000.00', 1, '500000.00'),
(2, 1, 6, 'apple', 1, '15000.00', 1, '15000.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item_mst`
--

CREATE TABLE `tbl_item_mst` (
  `id` int(11) NOT NULL,
  `item_type` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `selling_price` decimal(11,2) DEFAULT NULL,
  `description` text,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_item_mst`
--

INSERT INTO `tbl_item_mst` (`id`, `item_type`, `item_name`, `item_code`, `unit_id`, `selling_price`, `description`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'Goods', 'IT laptop2', '002', 2, '25001.00', 'dsad', '2019-08-17 10:42:35', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_module_mst`
--

CREATE TABLE `tbl_module_mst` (
  `id` int(10) UNSIGNED NOT NULL,
  `module_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_module_mst`
--

INSERT INTO `tbl_module_mst` (`id`, `module_title`, `icon`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'Dashboard', 'ti-dashboard', '2019-08-17 12:26:13', 1, NULL, NULL, 0),
(2, 'User Masters', 'fa fa-users', '2019-08-17 12:26:29', 1, NULL, NULL, 1),
(3, 'Module Masters', 'fa fa-cog', '2019-08-17 12:26:43', 1, NULL, NULL, 1),
(4, 'Masters', 'fa fa-database', '2019-08-17 12:26:53', 1, NULL, NULL, 1),
(5, 'Customer', 'fa fa-users', '2019-08-20 18:44:17', 1, NULL, NULL, 1),
(6, 'Items', 'fa fa-shopping-bag', '2019-08-20 18:44:26', 1, NULL, NULL, 1),
(8, 'Products', 'fa fa-product-hunt', '2019-08-20 18:44:48', 1, NULL, NULL, 1),
(12, 'Estimates', 'fa fa-calculator', '2019-08-22 17:14:18', 1, NULL, NULL, 1),
(13, 'Invoice', 'fa fa-files-o', '2019-08-22 17:14:32', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_permission_mst`
--

CREATE TABLE `tbl_permission_mst` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `sub_module_id` int(11) DEFAULT NULL,
  `view` tinyint(4) NOT NULL,
  `add` tinyint(4) NOT NULL,
  `edit` tinyint(4) NOT NULL,
  `delete` tinyint(4) NOT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_permission_mst`
--

INSERT INTO `tbl_permission_mst` (`id`, `user_id`, `module_id`, `sub_module_id`, `view`, `add`, `edit`, `delete`, `add_date`, `add_uid`, `update_date`, `update_uid`, `status`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(2, 1, 2, 2, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(3, 1, 2, 3, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(4, 1, 3, 4, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(5, 1, 3, 5, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(6, 1, 4, 6, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(7, 1, 4, 7, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(8, 1, 4, 8, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(9, 1, 4, 9, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(10, 1, 4, 10, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(11, 1, 4, 11, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(12, 1, 5, 12, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(13, 1, 6, 13, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(14, 1, 7, 14, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(15, 1, 8, 15, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(16, 1, 9, 16, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(17, 1, 9, 17, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(18, 1, 11, 18, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(19, 1, 11, 19, 1, 1, 1, 1, '2019-08-21 11:42:30', 1, '2019-08-21 11:42:30', 1, 1),
(20, 2, 1, 1, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(21, 2, 2, 2, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(22, 2, 2, 3, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(23, 2, 3, 4, 0, 0, 0, 0, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(24, 2, 3, 5, 0, 0, 0, 0, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(25, 2, 4, 6, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(26, 2, 4, 7, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(27, 2, 4, 8, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(28, 2, 4, 9, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(29, 2, 4, 10, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(30, 2, 4, 11, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(31, 2, 5, 12, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(32, 2, 6, 13, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(33, 2, 7, 14, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(34, 2, 8, 15, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(35, 2, 9, 16, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(36, 2, 9, 17, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(37, 2, 11, 18, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(38, 2, 11, 19, 1, 1, 1, 1, '2019-08-21 11:52:56', 1, '2019-08-21 11:52:56', 2, 1),
(39, 3, 2, 2, 1, 1, 1, 1, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(40, 3, 2, 3, 1, 1, 1, 1, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(41, 3, 3, 4, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(42, 3, 3, 5, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(43, 3, 4, 6, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(44, 3, 4, 7, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(45, 3, 4, 8, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(46, 3, 4, 9, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(47, 3, 4, 10, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(48, 3, 4, 11, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(49, 3, 5, 12, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(50, 3, 6, 13, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(51, 3, 7, 14, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(52, 3, 8, 15, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(53, 3, 9, 16, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(54, 3, 9, 17, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(55, 3, 11, 18, 0, 0, 0, 0, '2019-08-21 15:26:30', 3, '2019-08-21 15:26:30', 3, 1),
(56, 1, 2, 23, 1, 1, 1, 1, '2019-08-21 17:32:26', 1, NULL, NULL, 1),
(57, 5, 2, 2, 1, 1, 1, 1, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(58, 5, 2, 3, 1, 1, 1, 1, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(59, 5, 3, 4, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(60, 5, 3, 5, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(61, 5, 4, 6, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(62, 5, 4, 7, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(63, 5, 4, 8, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(64, 5, 4, 9, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(65, 5, 4, 10, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(66, 5, 4, 11, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(67, 5, 5, 12, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(68, 5, 6, 13, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(69, 5, 7, 14, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(70, 5, 8, 15, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(71, 5, 9, 16, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(72, 5, 9, 17, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(73, 5, 11, 18, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(74, 5, 11, 19, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(75, 5, 2, 21, 0, 0, 0, 0, '2019-08-22 12:58:33', 1, NULL, NULL, 1),
(76, 6, 2, 2, 1, 1, 1, 1, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(77, 6, 2, 3, 1, 1, 1, 1, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(78, 6, 3, 4, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(79, 6, 3, 5, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(80, 6, 4, 6, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(81, 6, 4, 7, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(82, 6, 4, 8, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(83, 6, 4, 9, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(84, 6, 4, 10, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(85, 6, 4, 11, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(86, 6, 5, 12, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(87, 6, 6, 13, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(88, 6, 7, 14, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(89, 6, 8, 15, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(90, 6, 9, 16, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(91, 6, 9, 17, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(92, 6, 11, 18, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(93, 6, 11, 19, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(94, 6, 2, 21, 0, 0, 0, 0, '2019-08-22 13:00:28', 1, NULL, NULL, 1),
(95, 1, 12, 24, 1, 1, 1, 1, '2019-08-22 17:15:38', 1, NULL, NULL, 1),
(96, 1, 13, 25, 1, 1, 1, 1, '2019-08-22 17:15:54', 1, NULL, NULL, 1),
(97, 1, 4, 26, 1, 1, 1, 1, '2019-08-22 17:22:21', 1, NULL, NULL, 1),
(98, 7, 0, 0, 0, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(99, 7, 2, 2, 1, 1, 1, 1, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(100, 7, 2, 3, 1, 1, 1, 1, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(101, 7, 3, 4, 0, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(102, 7, 3, 5, 0, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(103, 7, 4, 6, 1, 1, 1, 1, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(104, 7, 4, 7, 1, 1, 1, 1, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(105, 7, 4, 8, 1, 1, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(106, 7, 4, 9, 1, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(107, 7, 4, 10, 1, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(108, 7, 4, 11, 1, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(109, 7, 5, 12, 1, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(110, 7, 6, 13, 1, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(111, 7, 0, 0, 0, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(112, 7, 8, 15, 1, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(113, 7, 0, 0, 0, 0, 0, 0, '2019-08-23 16:30:15', 1, NULL, NULL, 1),
(130, 9, 0, 0, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(131, 9, 2, 2, 1, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(132, 9, 2, 3, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(133, 9, 3, 4, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(134, 9, 3, 5, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(135, 9, 4, 6, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(136, 9, 4, 7, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(137, 9, 4, 8, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(138, 9, 4, 9, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(139, 9, 4, 10, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(140, 9, 4, 11, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(141, 9, 5, 12, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(142, 9, 6, 13, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(143, 9, 0, 0, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(144, 9, 8, 15, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(145, 9, 0, 0, 0, 0, 0, 0, '2019-09-05 15:33:49', 3, NULL, NULL, 1),
(146, 10, 0, 0, 0, 0, 0, 0, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(147, 10, 2, 2, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(148, 10, 2, 3, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(149, 10, 3, 4, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(150, 10, 3, 5, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(151, 10, 4, 6, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(152, 10, 4, 7, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(153, 10, 4, 8, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(154, 10, 4, 9, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(155, 10, 4, 10, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(156, 10, 4, 11, 1, 1, 1, 1, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(157, 10, 5, 12, 1, 1, 1, 0, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(158, 10, 6, 13, 1, 0, 0, 0, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(159, 10, 0, 0, 0, 0, 0, 0, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(160, 10, 8, 15, 1, 0, 0, 0, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(161, 10, 0, 0, 0, 0, 0, 0, '2019-09-05 15:35:41', 3, NULL, NULL, 1),
(162, 8, 0, 0, 0, 0, 0, 0, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(163, 8, 2, 2, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(164, 8, 2, 3, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(165, 8, 3, 4, 0, 0, 0, 0, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(166, 8, 3, 5, 0, 0, 0, 0, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(167, 8, 4, 6, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(168, 8, 4, 7, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(169, 8, 4, 8, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(170, 8, 4, 9, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(171, 8, 4, 10, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(172, 8, 4, 11, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(173, 8, 5, 12, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1),
(174, 8, 6, 13, 1, 1, 1, 1, '2019-09-17 19:13:14', 8, '2019-09-17 19:13:14', 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_mst`
--

CREATE TABLE `tbl_product_mst` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `packing` varchar(255) DEFAULT NULL,
  `income_account` varchar(255) DEFAULT NULL,
  `price` float(11,2) DEFAULT NULL,
  `gst_id` int(11) DEFAULT NULL,
  `hsn` varchar(20) DEFAULT NULL,
  `discount` decimal(11,2) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `description` text,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product_mst`
--

INSERT INTO `tbl_product_mst` (`id`, `product_name`, `packing`, `income_account`, `price`, `gst_id`, `hsn`, `discount`, `unit_id`, `description`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'Mobile', NULL, 'Sales', 15000.00, NULL, NULL, NULL, NULL, 'Purase mobile', '2019-07-30 11:41:44', 1, NULL, NULL, 1),
(6, 'Airpods', '24', 'Sales', 15000.00, 1, '100HSN', '1000.00', 2, 'Airpods', '2019-08-17 10:26:21', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_province_mst`
--

CREATE TABLE `tbl_province_mst` (
  `id` int(11) NOT NULL,
  `province_name` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_province_mst`
--

INSERT INTO `tbl_province_mst` (`id`, `province_name`, `country_id`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'Gujarata', 5, '2019-07-26 17:49:24', 1, NULL, NULL, 1),
(2, 'Washington', 3, '2019-07-26 17:50:59', 1, NULL, NULL, 1),
(3, 'West Virginia', 3, '2019-07-26 17:51:15', 1, NULL, NULL, 1),
(4, 'Maharashtra', 5, '2019-07-26 17:51:44', 1, NULL, NULL, 1),
(5, 'UP', 5, '2019-07-29 13:02:17', 1, NULL, NULL, 1),
(6, 'MP', 1, '2019-07-29 13:02:53', 1, '2019-07-30 17:17:18', 1, 0),
(7, 'Delhi', 5, '2019-08-08 16:10:24', 1, NULL, NULL, 1),
(8, '', 5, '2019-08-19 13:35:49', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role_level_mst`
--

CREATE TABLE `tbl_role_level_mst` (
  `id` int(11) NOT NULL,
  `level_name` varchar(191) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_role_level_mst`
--

INSERT INTO `tbl_role_level_mst` (`id`, `level_name`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'System', '2019-08-21 17:33:29', 1, NULL, NULL, 1),
(2, 'Super Admin', '2019-08-21 17:34:07', 1, NULL, NULL, 1),
(3, 'Admin', '2019-08-21 17:34:20', 1, NULL, NULL, 1),
(4, 'Employee', '2019-08-21 17:34:25', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role_mst`
--

CREATE TABLE `tbl_role_mst` (
  `id` int(11) NOT NULL,
  `role_name` varchar(191) DEFAULT NULL,
  `level_id` int(11) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_role_mst`
--

INSERT INTO `tbl_role_mst` (`id`, `role_name`, `level_id`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'Developer', 1, '2019-08-17 10:32:30', 1, NULL, NULL, 1),
(2, 'Super Admin', 2, '2019-08-17 10:34:44', 1, NULL, NULL, 1),
(3, 'Admin', 3, '2019-08-17 10:36:33', 1, NULL, NULL, 1),
(4, 'HR', 4, '2019-08-17 10:36:48', 1, NULL, NULL, 1),
(5, 'Sales Manager', 4, '2019-08-17 16:21:31', 1, NULL, NULL, 1),
(6, 'Purchase Manager', 4, '2019-08-17 16:21:38', 1, NULL, NULL, 1),
(7, 'Employee', 4, '2019-08-21 11:43:21', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sub_module_mst`
--

CREATE TABLE `tbl_sub_module_mst` (
  `id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `sub_title` varchar(191) DEFAULT NULL,
  `file_url_name` varchar(191) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sub_module_mst`
--

INSERT INTO `tbl_sub_module_mst` (`id`, `module_id`, `sub_title`, `file_url_name`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 1, 'Dashboard', 'dashboard', '2019-08-17 12:27:06', 1, NULL, NULL, 1),
(2, 2, 'User', 'view_user', '2019-08-17 12:27:13', 1, NULL, NULL, 1),
(3, 2, 'Role', 'view_role', '2019-08-17 12:27:26', 1, NULL, NULL, 1),
(4, 3, 'Module', 'view_module', '2019-08-17 12:28:22', 1, NULL, NULL, 1),
(5, 3, 'Sub Module', 'view_sub_module', '2019-08-17 12:28:38', 1, NULL, NULL, 1),
(6, 4, 'Tax', 'tax', '2019-08-17 15:46:50', 1, NULL, NULL, 1),
(7, 4, 'Currency', 'currency', '2019-08-17 15:49:03', 1, NULL, NULL, 1),
(8, 4, 'Country', 'country', '2019-08-20 18:46:22', 1, NULL, NULL, 1),
(9, 4, 'Province', 'province', '2019-08-20 18:47:13', 1, NULL, NULL, 1),
(10, 4, 'City', 'city', '2019-08-20 18:47:31', 1, NULL, NULL, 1),
(11, 4, 'Terms Condition', 'term_condition', '2019-08-20 18:48:09', 1, NULL, NULL, 1),
(12, 5, 'Customer', 'customer_view', '2019-08-20 18:48:56', 1, NULL, NULL, 1),
(13, 6, 'Items', 'item', '2019-08-20 18:49:16', 1, NULL, NULL, 1),
(15, 8, 'Products', 'add_product', '2019-08-20 18:51:04', 1, NULL, NULL, 1),
(24, 12, 'Estimates', 'quotation', '2019-08-22 17:15:38', 1, NULL, NULL, 1),
(25, 13, 'Invoice', 'invoice', '2019-08-22 17:15:54', 1, NULL, NULL, 1),
(26, 4, 'Units', 'unit', '2019-08-22 17:22:21', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tax_mst`
--

CREATE TABLE `tbl_tax_mst` (
  `id` int(11) NOT NULL,
  `tax_name` varchar(255) DEFAULT NULL,
  `abbreviation` varchar(255) DEFAULT NULL,
  `tax_rate` int(11) DEFAULT NULL COMMENT '%',
  `description` varchar(255) DEFAULT NULL,
  `tax_number` varchar(255) DEFAULT NULL,
  `show_tax_no_invoice_status` int(11) DEFAULT NULL,
  `tax_recoverable_status` int(11) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_tax_mst`
--

INSERT INTO `tbl_tax_mst` (`id`, `tax_name`, `abbreviation`, `tax_rate`, `description`, `tax_number`, `show_tax_no_invoice_status`, `tax_recoverable_status`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'GST', 'Abbreviation', 18, 'IGST', '1111', 1, 0, '2019-07-29 16:35:29', 1, NULL, NULL, 1),
(2, 'CZCXC', 'XC', 21, 'ZXz', 'Z', NULL, NULL, '2019-07-30 17:15:05', 1, '2019-07-30 17:15:08', 1, 0),
(3, 'dffgd', 'fdgdg', 12, 'gdfsg', '434234234234', NULL, NULL, '2019-08-07 13:05:23', 1, '2019-08-22 17:40:32', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_term_condition_mst`
--

CREATE TABLE `tbl_term_condition_mst` (
  `id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `description` text,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_term_condition_mst`
--

INSERT INTO `tbl_term_condition_mst` (`id`, `country_id`, `description`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 5, '<div><ol style=\"margin-right: 0px; margin-left: 20px; padding: 0px; list-style-position: initial; list-style-image: initial;\"><li style=\"margin: 0px; padding: 0px;\">Products damaged during the transit will not be covered under the warranty.</li><li style=\"margin: 0px; padding: 0px;\">The product carries a 90 days warranty unless otherwise stated.</li><li style=\"margin: 0px; padding: 0px;\">The product carries only manufacturerâ€™s warranty and no return or exchange will be entertained.</li></ol></div>', '2019-08-19 14:04:34', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unit_mst`
--

CREATE TABLE `tbl_unit_mst` (
  `id` int(11) NOT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_unit_mst`
--

INSERT INTO `tbl_unit_mst` (`id`, `unit`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`) VALUES
(1, 'KGS', '2019-08-17 00:00:00', NULL, NULL, NULL, 1),
(2, 'NOS', '2019-08-17 00:00:00', NULL, NULL, NULL, 1),
(3, 'GRAM', '2019-08-17 08:58:05', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_mst`
--

CREATE TABLE `tbl_user_mst` (
  `id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `add_uid` int(11) DEFAULT NULL,
  `del_date` datetime DEFAULT NULL,
  `del_uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `user_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_mst`
--

INSERT INTO `tbl_user_mst` (`id`, `name`, `phone`, `email`, `role_id`, `password`, `add_date`, `add_uid`, `del_date`, `del_uid`, `status`, `user_status`) VALUES
(1, 'Developer', '1234567890', 'developer@gmail.com', 1, 'e10adc3949ba59abbe56e057f20f883e', '2019-08-20 19:06:19', 0, NULL, NULL, 1, 1),
(2, 'Nikunj Patel', '1234567890', 'superadmin@gmail.com', 2, 'e10adc3949ba59abbe56e057f20f883e', '2019-08-20 19:08:14', 1, NULL, NULL, 1, 0),
(3, 'Anant Patel', '1234567890', 'admin@gmail.com', 3, 'e10adc3949ba59abbe56e057f20f883e', '2019-08-20 19:19:26', 2, '2019-09-17 18:13:22', 1, 0, 1),
(4, 'Manisha ', '1234567890', 'manisha@gmail.om', 3, 'e10adc3949ba59abbe56e057f20f883e', '2019-08-20 19:24:09', 3, '2019-09-17 18:12:53', 3, 0, 1),
(5, 'dhara', '123456789', 'dhara@gmail.com', 7, 'e10adc3949ba59abbe56e057f20f883e', '2019-08-22 12:58:33', 3, '2019-09-17 18:12:51', 3, 0, 1),
(7, 'Bhargav Shastri', '9662222272', 'bhargav@dwarkeshit.com', 2, '21232f297a57a5a743894a0e4a801fc3', '2019-08-23 16:30:15', 1, '2019-08-23 16:35:24', 1, 0, 0),
(8, 'Bhargav Shastri', '9662222272', 'bhargav@dwarkeshit.com', 2, '21232f297a57a5a743894a0e4a801fc3', '2019-08-23 16:34:58', 1, NULL, NULL, 1, 0),
(9, 'Sujit', '9558105606', 'sujit@dwarkeshit.com', 2, '202cb962ac59075b964b07152d234b70', '2019-09-05 15:33:49', 3, '2019-09-17 18:12:48', 3, 0, 0),
(10, 'Sujit', '9558105606', 'sujit@dwarkeshit.com', 2, '202cb962ac59075b964b07152d234b70', '2019-09-05 15:35:41', 3, '2019-09-17 18:12:46', 3, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `change_background_image`
--
ALTER TABLE `change_background_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logo`
--
ALTER TABLE `logo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_add_invoice_entry_mst`
--
ALTER TABLE `tbl_add_invoice_entry_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_add_invoice_sub_entry_mst`
--
ALTER TABLE `tbl_add_invoice_sub_entry_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_city_mst`
--
ALTER TABLE `tbl_city_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_country_mst`
--
ALTER TABLE `tbl_country_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_currency_mst`
--
ALTER TABLE `tbl_currency_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_mst`
--
ALTER TABLE `tbl_customer_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_estimate_entry_mst`
--
ALTER TABLE `tbl_estimate_entry_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_estimate_sub_entry_mst`
--
ALTER TABLE `tbl_estimate_sub_entry_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_item_mst`
--
ALTER TABLE `tbl_item_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_module_mst`
--
ALTER TABLE `tbl_module_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_permission_mst`
--
ALTER TABLE `tbl_permission_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_mst`
--
ALTER TABLE `tbl_product_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_province_mst`
--
ALTER TABLE `tbl_province_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_role_level_mst`
--
ALTER TABLE `tbl_role_level_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_role_mst`
--
ALTER TABLE `tbl_role_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sub_module_mst`
--
ALTER TABLE `tbl_sub_module_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tax_mst`
--
ALTER TABLE `tbl_tax_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_term_condition_mst`
--
ALTER TABLE `tbl_term_condition_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_unit_mst`
--
ALTER TABLE `tbl_unit_mst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_mst`
--
ALTER TABLE `tbl_user_mst`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `change_background_image`
--
ALTER TABLE `change_background_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logo`
--
ALTER TABLE `logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_add_invoice_entry_mst`
--
ALTER TABLE `tbl_add_invoice_entry_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_add_invoice_sub_entry_mst`
--
ALTER TABLE `tbl_add_invoice_sub_entry_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_city_mst`
--
ALTER TABLE `tbl_city_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_country_mst`
--
ALTER TABLE `tbl_country_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_currency_mst`
--
ALTER TABLE `tbl_currency_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_customer_mst`
--
ALTER TABLE `tbl_customer_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_estimate_entry_mst`
--
ALTER TABLE `tbl_estimate_entry_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_estimate_sub_entry_mst`
--
ALTER TABLE `tbl_estimate_sub_entry_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_item_mst`
--
ALTER TABLE `tbl_item_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_module_mst`
--
ALTER TABLE `tbl_module_mst`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_permission_mst`
--
ALTER TABLE `tbl_permission_mst`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `tbl_product_mst`
--
ALTER TABLE `tbl_product_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_province_mst`
--
ALTER TABLE `tbl_province_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_role_level_mst`
--
ALTER TABLE `tbl_role_level_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_role_mst`
--
ALTER TABLE `tbl_role_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_sub_module_mst`
--
ALTER TABLE `tbl_sub_module_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_tax_mst`
--
ALTER TABLE `tbl_tax_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_term_condition_mst`
--
ALTER TABLE `tbl_term_condition_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_unit_mst`
--
ALTER TABLE `tbl_unit_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_user_mst`
--
ALTER TABLE `tbl_user_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Database: `Dwarkesh_tsk`
--
CREATE DATABASE IF NOT EXISTS `Dwarkesh_tsk` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Dwarkesh_tsk`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(10) NOT NULL,
  `postTitle` varchar(50) NOT NULL,
  `postBy` varchar(20) NOT NULL,
  `postImg` varchar(300) NOT NULL,
  `description` varchar(600) NOT NULL,
  `timestamps` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `postTitle`, `postBy`, `postImg`, `description`, `timestamps`) VALUES
(2, 'LARAVAL', 'Nikunj Patel', 'bl_1.jpg', '<p><span style=\"color: rgb(126, 126, 126); font-family: Exo, sans-serif; background-color: rgb(239, 243, 246);\">Laravel is a free, open-source[3] PHP web framework, created by Taylor Otwell and intended for the development of web applications following the Model View Controller (MVC) architectural pattern and based on Symfony. Some of the features of Laravel are a modular packaging system with a dedicated dependency manager, different ways for accessing relational databases, utilities that aid in application deployment and maintenance, and its orientation toward syntactic suga</span><br></p>', '2019-09-06 11:17:14'),
(10, 'ECOMMERCE', 'MANISHA KHANDELWAL', 'bl_2.jpg', '<span style=\"color: rgb(126, 126, 126); font-family: Exo, sans-serif; background-color: rgb(239, 243, 246);\">Laravel is a free, open-source[3] PHP web framework, created by Taylor Otwell and intended for the development of web applications following the Model View Controller (MVC) architectural pattern and based on Symfony. Some of the features of Laravel are a modular packaging system with a dedicated dependency manager, different ways for accessing relational databases, utilities that aid in application deployment and maintenance, and its orientation toward syntactic suga</span> ', '2019-09-06 13:42:30'),
(11, 'MISSION MARS', 'Sujit Mak', '7.jpg', '<span style=\"color: rgb(126, 126, 126); font-family: Exo, sans-serif; background-color: rgb(239, 243, 246);\">Laravel is a free, open-source[3] PHP web framework, created by Taylor Otwell and intended for the development of web applications following the Model View Controller (MVC) architectural pattern and based on Symfony. Some of the features of Laravel are a modular packaging system with a dedicated dependency manager, different ways for accessing relational databases, utilities that aid in application deployment and maintenance, and its orientation toward syntactic suga</span> ', '2019-09-06 13:43:03');

-- --------------------------------------------------------

--
-- Table structure for table `career`
--

CREATE TABLE `career` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_no` varchar(100) NOT NULL,
  `technical_skill` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `experience` int(10) NOT NULL,
  `notice_period` varchar(100) NOT NULL,
  `resume` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `career`
--

INSERT INTO `career` (`id`, `fname`, `email`, `mobile_no`, `technical_skill`, `designation`, `experience`, `notice_period`, `resume`) VALUES
(15, 'sujit', 'sujit@dwarkeshit.com', '9897456613', 'JAVA + CORE PHP + MYSQL', 'JAVA Developer', 1, '1 Month', 'SUJIT-Resume2.pdf'),
(16, 'Dhaval', 'dhaval@dwarkeshit.com', '7894561230', 'CORE PHP + LARAVEL ', 'Laravel Developer', 1, '1 Month', '3.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `timestamp`) VALUES
(1, 'Web Design', '2019-08-19 04:48:23'),
(3, 'Web Site', '2019-09-05 09:31:38'),
(2, 'Web Application', '2019-09-03 14:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `change_background_image`
--

CREATE TABLE `change_background_image` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clientlogo`
--

CREATE TABLE `clientlogo` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clientlogo`
--

INSERT INTO `clientlogo` (`id`, `image`, `alt`, `timestamp`) VALUES
(4, '2019_04_05_11_07_18am01.jpg', 'Security Service For Delta Web Solution', '2019-04-05 11:07:18'),
(5, '2019_04_05_11_07_30am02.jpg', 'Ayurveda For Delta Web Solution', '2019-04-05 11:07:30'),
(6, '2019_04_05_11_07_42am03.jpg', 'Academy For Delta Web Solution', '2019-04-05 11:07:42'),
(7, '2019_04_05_11_07_52am04.jpg', 'Infra For Delta Web Solution', '2019-04-05 11:07:52'),
(8, '2019_04_05_11_08_09am05.jpg', 'Construction Company For Delta Web Solution', '2019-04-05 11:08:09'),
(9, '2019_04_05_11_08_24am06.jpg', 'IT For Delta Web Solution', '2019-04-05 11:08:24'),
(10, '2019_04_05_11_08_35am07.jpg', 'Cafe For Delta Web Solution', '2019-04-05 11:08:35'),
(11, '2019_04_05_11_08_46am08.jpg', 'Auto Word For Delta Web Solution', '2019-04-05 11:08:46'),
(12, '2019_04_05_11_08_56am09.jpg', 'Solar For Delta Web Solution', '2019-04-05 11:08:56'),
(13, '2019_04_05_11_09_10am10.jpg', 'Institute For Delta Web Solution', '2019-04-05 11:09:10'),
(14, '2019_04_05_11_09_20am11.jpg', 'Hotel For Delta Web Solution', '2019-04-05 11:09:20'),
(15, '2019_04_05_11_09_26am12.jpg', 'Hotel For Delta Web Solution', '2019-04-05 11:09:26'),
(16, '2019_04_05_11_09_39am13.jpg', 'IT For Delta Web Solution', '2019-04-05 11:09:39'),
(17, '2019_04_05_11_09_49am14.jpg', 'IT For Delta Web Solution', '2019-04-05 11:09:49'),
(18, '2019_04_05_11_10_01am15.jpg', 'Academy For Delta Web Solution', '2019-04-05 11:10:01'),
(27, '2019_06_22_05_50_13pm02.jpg', 'Dwarkesh IT', '2019-06-22 12:20:13'),
(26, '2019_06_22_05_50_07pm01.jpg', 'Dwarkesh IT', '2019-06-22 12:20:07'),
(33, '2019_06_22_05_50_45pm08.jpg', 'Dwarkesh IT', '2019-06-22 12:20:45'),
(34, '2019_06_22_05_50_51pm09.jpg', 'Dwarkesh IT', '2019-06-22 12:20:51'),
(35, '2019_06_22_05_51_07pm10.jpg', 'Dwarkesh IT', '2019-06-22 12:21:07'),
(36, '2019_06_22_05_51_17pm11.jpg', 'Dwarkesh IT', '2019-06-22 12:21:17'),
(37, '2019_06_22_05_51_28pm12.jpg', 'Dwarkesh IT', '2019-06-22 12:21:28'),
(38, '2019_06_22_05_51_35pm13.jpg', 'Dwarkesh IT', '2019-06-22 12:21:35');

-- --------------------------------------------------------

--
-- Table structure for table `innerpage_slider`
--

CREATE TABLE `innerpage_slider` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `inquiryid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `comments` longtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inquiry`
--

INSERT INTO `inquiry` (`inquiryid`, `name`, `email`, `phone`, `subject`, `comments`, `timestamp`) VALUES
(1, 'Pratik Shah', 'pshah5132@gmail.com', '9723840340', 'Technical Issue', 'asX', '2019-04-04 19:34:36'),
(2, 'Nikunj', 'nikpatel511@hotmail.com', '9429812012', 'Web design', 'kgdmkgkg', '2019-04-09 04:34:18'),
(3, 'Nikunj Patel', 'sanjivm43@gmail.com', '9898363557', 'sddsa', 'dsfsaf', '2019-04-09 04:35:48'),
(4, 'nikunj', 'nikpatel511@hotmail.com', '9429812012', 'dynamic', 'idfsfdsf', '2019-04-24 03:52:09'),
(5, 'nikunj', 'sanjivm43@gmail.com', '9898363557', '789', 'msdgdngnddng', '2019-04-28 14:48:11'),
(6, 'nikunj', 'sadfo@gmail.com', '942985698', 'sdfsdf', 'sdfsfsfsd', '2019-05-06 10:03:45'),
(7, 'bik', 'nikpatel511@gmail.com', '8697897897', 'vbmvgmjbhmj', 'ghjhgjghjgh', '2019-06-02 10:01:57'),
(8, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:03:40'),
(9, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:04:34'),
(10, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:05:26'),
(11, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:06:29'),
(12, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:08:01'),
(13, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:14:23'),
(14, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:15:54'),
(15, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:17:04'),
(16, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:17:44'),
(17, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:19:31'),
(18, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:20:45'),
(19, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:21:30'),
(20, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:22:14'),
(21, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:26:10'),
(22, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:32:30'),
(23, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:34:50'),
(24, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:41:09'),
(25, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:41:11'),
(26, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:45:49'),
(27, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:47:35'),
(28, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:47:54'),
(29, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:55:34'),
(30, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:56:02'),
(31, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:56:58'),
(32, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:57:23'),
(33, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:58:26'),
(34, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:59:08'),
(35, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:59:46'),
(36, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:01:21'),
(37, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:02:30'),
(38, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:02:44'),
(39, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:03:03'),
(40, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:05:05'),
(41, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:05:30'),
(42, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:06:21'),
(43, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:07:50'),
(44, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:08:41'),
(45, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', ' Test email Test email Test email Test email Test emailTest emailTest emailTest email', '2019-06-02 14:12:18'),
(46, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', ' Test email Test email Test email Test email Test emailTest emailTest emailTest email', '2019-06-02 14:23:54'),
(47, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', ' Test email Test email Test email Test email Test emailTest emailTest emailTest email', '2019-06-02 14:31:22'),
(48, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'safdasf', '2019-06-05 04:53:27'),
(49, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'dsgfdsg', '2019-06-05 04:55:48'),
(50, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'dsafaf', '2019-06-05 06:34:35'),
(51, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'eff', '2019-06-05 06:44:41'),
(52, 'nikunj 511', 'nik@softworldsolution.com', '9429812012', 'website', 'dfgdgfgd', '2019-06-05 06:47:37'),
(53, 'TEST', 'test@gmail.com', '1234567890', 'TEST', 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest', '2019-06-05 06:52:10'),
(54, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'sdsf', '2019-06-05 06:55:24'),
(55, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'sdafdfsd', '2019-06-05 07:01:42'),
(56, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'sdafdfsd', '2019-06-05 07:09:13'),
(57, 'Manisha', 'nik@softworldsolution.com', '9429812012', 'website', 'dfsdfsfs', '2019-06-05 07:12:48'),
(58, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'dsfsg', '2019-06-05 07:16:46'),
(59, 'nikunj', 'nikpatel511@gmail.com', '9429812012', 'Test', 'fdgdgdf', '2019-06-05 13:29:20'),
(60, 'nikunj', 'nikpatel511@gmail.com', '9429812012', 'Web site', 'Static web site', '2019-06-14 04:24:44'),
(61, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'Bye ', '2019-09-02 04:53:37'),
(62, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'Bye ', '2019-09-02 04:53:59'),
(63, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'ndjkhkkjhkkj', '2019-09-02 06:09:36'),
(64, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'scscaca', '2019-09-02 06:24:11'),
(65, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'ssdasdasda', '2019-09-02 06:46:57'),
(66, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'ssdasdasda', '2019-09-02 06:47:18'),
(67, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'ssdasdasda', '2019-09-02 06:47:47'),
(68, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'dsdefgsvs', '2019-09-02 06:48:22');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user_id` varchar(70) NOT NULL,
  `password` varchar(70) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user_id`, `password`, `timestamp`) VALUES
(1, 'admin', 'b43e691700a8a4f5c1e903b6bc29a60a', '2019-04-03 17:07:22');

-- --------------------------------------------------------

--
-- Table structure for table `logo`
--

CREATE TABLE `logo` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logo`
--

INSERT INTO `logo` (`id`, `img`) VALUES
(1, 'download.png');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `id` int(11) NOT NULL,
  `project_link` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `frontImg` varchar(500) NOT NULL,
  `bredCrumpImg` varchar(500) NOT NULL,
  `frontContentImg` varchar(500) NOT NULL,
  `screenShotImg` varchar(500) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `complated_date` varchar(100) NOT NULL,
  `skill` varchar(100) NOT NULL,
  `project_description` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (`id`, `project_link`, `project_name`, `category_id`, `alt`, `frontImg`, `bredCrumpImg`, `frontContentImg`, `screenShotImg`, `client_name`, `created_by`, `complated_date`, `skill`, `project_description`, `timestamp`) VALUES
(29, 'http://snp-studio.com/', 'Stegenga + PARTNERS A Professional Studio', 3, 'Stegenga by Dwarkesh', '10.jpg', '11.jpg', '07.jpg', '08.1568627818.jpg,09.1568627818.jpg,white_panel2.png', 'Stegenga + PARTNERS', 'Dwarkesh Business Solution LLP', '09/13/2019', 'Bootstrap, jQuery', '<h3 class=\"col-about-title\" style=\"font-family: montserratsemibold; color: rgb(61, 61, 61); margin-top: 0px; margin-bottom: 1.1em; font-size: 1.875em;\">We turn ideas into works of people and purpose<span class=\"text-primary\" style=\"color: rgb(197, 164, 126); text-transform: capitalize;\">.</span></h3><div class=\"col-about-info\" style=\"font-family: montserratlight, sans-serif; font-size: 16px;\"><p style=\"margin-bottom: 1.6em;\">We are a diverse team of design professionals with a people-centric philosophy at the heart of the design process. Our mission is to deliver exceptional design ideas and solutions through the creative blending of human need, expertise, value creation, and environmental stewardship.</p><p>The Studioâ€™s belief is that better buildings make for a better world. A successful building is one that improves daily life for the people who live and work around it.</p></div>', '2019-09-16 09:56:58'),
(30, 'https://bankodesign.com/', 'Banko design', 3, 'Banko Design by Dwarkesh', '02.jpg', '04.jpg', '01.jpg', '03.1568627970.jpg,05.1568627970.jpg,06.1568627970.jpg,white_panel1.png', 'Banko design', 'Dwarkesh Business Solution LLP', '09/03/2019', 'Wordpress, PHP, MySQL, jQuery', '<div class=\"wpb_text_column wpb_content_element \" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px 0px 35px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center;\"><div class=\"wpb_wrapper\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"><h4 style=\"background: 0px 0px; border: 0px; margin-top: 0px; margin-bottom: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; font-family: &quot;PT Serif&quot;, sans-serif; font-size: 22px; line-height: 1.24em; font-weight: 700; font-style: italic; letter-spacing: -0.5px; color: rgb(0, 0, 0);\">Interior Design, Streamlined</h4></div></div><div class=\"vc_empty_space\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center; height: 12px;\"><span class=\"vc_empty_space_inner\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"></span></div><div class=\"wpb_text_column wpb_content_element \" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px 0px 35px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center;\"><div class=\"wpb_wrapper\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"><h2 style=\"background: 0px 0px; border: 0px; margin-top: 0px; margin-bottom: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; font-weight: 900; color: rgb(0, 0, 0); font-size: 50px; line-height: 1.08em;\">The Banko Way</h2></div></div><div class=\"vc_empty_space\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center; height: 8px;\"><span class=\"vc_empty_space_inner\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"></span></div><div class=\"qodef-separator-holder clearfix  qodef-separator-center\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; position: relative; height: auto; font-size: 0px; line-height: 1em; text-align: center; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif;\"><div class=\"qodef-separator\" style=\"background: 0px 0px; border-width: 0px 0px 3px; border-top-style: initial; border-right-style: initial; border-bottom-style: solid; border-left-style: initial; border-color: rgb(219, 219, 219); border-image: initial; margin: 10px 0px; padding: 0px; vertical-align: middle; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; position: relative; display: inline-block; width: 225px;\"></div></div><div class=\"vc_empty_space\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center; height: 12px;\"><span class=\"vc_empty_space_inner\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"></span></div><div class=\"qodef-custom-font-holder\" data-font-size=\"18\" data-line-height=\"25\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; letter-spacing: 0px; text-align: center; font-family: Catamaran; font-size: 18px; line-height: 25px; font-weight: 600; color: rgb(150, 150, 150);\">Banko Design is leading the forefront in boutique interior design for the senior living, multifamily, healthcare and hospitality markets. Our fourteen-member team of designers, interior architects and purchasing agents work to manage every aspect of your interior design project. Weâ€™ve streamlined the entire design and procurement process to create a seamless experience for our clients.</div>', '2019-09-16 09:59:30'),
(31, 'https://apidel.in/', 'Apidel Technologies', 3, 'Apidel made by Dwarkesh', '13.jpg', '15.jpg', '12.jpg', '14.jpg,16.jpg,17.jpg,white_panel1.1568628218.png', 'Apidel', 'Dwarkesh Business Solution LLP', '09/05/2019', 'Bootstrap, PHP, jQuery', '<p><span style=\"color: rgb(34, 34, 34); font-family: Roboto, sans-serif; font-size: 16px; background-color: rgb(247, 248, 250);\">The foundation stone for Apidel was laid in 2012, with the basic principles of infinity, long term vision and continuous progress in achieving its vision and mission of being the preferred staffing partner for all its client requirements. Apidel is progressively spreading its wings to cater various market segments &amp; LOBs like&nbsp;</span><span class=\"colortext\" style=\"margin: 0px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 16px; line-height: inherit; font-family: Roboto, sans-serif; vertical-align: top; color: rgb(7, 170, 165); background-color: rgb(247, 248, 250);\">Information Technology, Healthcare, Pharmaceutical, Analytics, Banking and Financial Services</span><span style=\"color: rgb(34, 34, 34); font-family: Roboto, sans-serif; font-size: 16px; background-color: rgb(247, 248, 250);\">.</span><br></p>', '2019-09-16 10:03:38'),
(32, 'https://www.panachebeauty.ca/', 'Panache Beauty Salon', 3, 'Panache Beauty Salon by Dwarkesh', '21.jpg', '22.jpg', '18.jpg', '19.jpg,20.jpg,white_panel2.1568628418.png', 'Panache Beauty Salon', 'Dwarkesh Business Solution LLP', '07/09/2019', 'PHP, jQuery', '<div class=\"container z-bigger\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 15px; line-height: inherit; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Helvetica, Arial, sans-serif; vertical-align: baseline; position: relative; width: 1320px; z-index: 20; background-color: rgb(212, 79, 88);\"><h4 style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; font-size: 24px; line-height: 32px; font-family: Poppins, sans-serif; vertical-align: baseline; color: rgb(0, 0, 0); text-align: center;\">We donâ€™t make your trips from work to salon go to waste or your blowout session turn into a yawning one. We make sure we listen to you &amp; your skin &amp; hair responds to us â€“ much like a whisperer.</h4></div><div class=\"container z-bigger\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 15px; line-height: inherit; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Helvetica, Arial, sans-serif; vertical-align: baseline; position: relative; width: 1320px; z-index: 20; background-color: rgb(212, 79, 88);\"><div class=\"twelve columns\" data-scroll-reveal=\"enter bottom move 100px over 1s after 0.3s\" data-scroll-reveal-id=\"5\" data-scroll-reveal-initialized=\"true\" data-scroll-reveal-complete=\"true\" style=\"margin: 25px; padding: 0px; border: 0px; font: inherit; vertical-align: baseline; float: left; display: inline; width: 1270px;\"><div class=\"services-wrap\" style=\"margin: 0px; padding: 40px; border: 0px; font: inherit; vertical-align: baseline; position: relative;\"><h4 style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; font-size: 24px; line-height: 32px; font-family: Poppins, sans-serif; vertical-align: baseline; text-align: center; color: rgb(0, 0, 0) !important;\">Welcome to Panache Beauty salon &amp; SPA â€“ the Brampton hair Salon &amp; SPA where youâ€™ll receive a guaranteed Class A service to make you look &amp; feel sensational but isnâ€™t that all salons promise? Well, we deliver, you say what more?</h4></div></div></div>', '2019-09-16 10:06:58'),
(33, 'http://sonuchem.com/', 'Sonu Chem', 3, 'Sonu Chem by Dwarkesh', '24.jpg', '26.jpg', '23.jpg', '25.jpg,27.jpg,28.jpg,white_panel1.1568628706.png', 'Sonu Chem', 'Dwarkesh Business Solution LLP', '05/08/2019', 'Wordpress, Bootstrap, PHP, MySQL, jQuery', '<p><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">Established in the year&nbsp;</span><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">1992</b><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">&nbsp;we, â€œ</span><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">Sonu Chem</b><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">â€</span><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">,&nbsp;</b><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">are a prominent name engaged in supplying a wide range of&nbsp;</span><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">Industrial Chemicals.&nbsp;</b><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">Under this range, we offer&nbsp;</span><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">Zinc Chloride,</b><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">&nbsp;Zinc Sulphate,&nbsp;Zinc Oxide,</b><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">&nbsp;VAT Dyes, Solubilised Dyes (Indigosol),&nbsp;</b><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">&nbsp;</span><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">Pigments&nbsp;</b><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">and&nbsp;</span><b style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">many more.&nbsp;</b><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">Under the proficient guidance of,&nbsp;</span><b style=\"margin-bottom: 0px; color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">â€˜Mr. Kaushik Shahâ€™,&nbsp;</b><span style=\"color: rgb(134, 134, 134); font-family: Montserrat, Helvetica, Arial, sans-serif; word-spacing: -1px;\">(Proprietor) we have been able to serve our clients in the most promising manner. Further, he understands requirements of the clients evolving with the time, and frame more centric policies.</span><br></p>', '2019-09-16 10:11:46'),
(34, 'https://dxbia.com/', 'DXBIA', 3, 'DXBIA by Dwarkesh', '33.jpg', '32.jpg', '29.jpg', '30.jpg,31.jpg,white_panel2.1568629275.png', 'DXBIA', 'Dwarkesh Business Solution LLP', '08/07/2019', 'Wordpress, Bootstrap, PHP, MySQL, jQuery', '<p><span style=\"color: rgb(124, 124, 128); font-family: Poppins, sans-serif;\">At Dxbia, We strive to achieve the highest level of Customer Satisfaction. We believe this is the best way to reach out millions of hearts by selling products with added value. Our aim is to provide you the best products with an exclusive design that over satisfy your needs and your expectations and deliver you the pleasure of owning it.</span><br></p>', '2019-09-16 10:21:15'),
(35, 'http://creartisan.com/', 'CreArtisan Creative Giftings', 3, 'CreArtisan by Dwarkesh', '43.jpg', '44.jpg', '40.jpg', '41.jpg,42.jpg,white_panel2.1568629770.png', 'CreArtisan Creative Giftings', 'Dwarkesh Business Solution LLP', '03/06/2019', 'Wordpress, PHP, MySQL, jQuery', '<p>We believe Creative gifting is the best way to communicate the love &amp; express gratitude. For a gesture as personal as this, we made our personal artistry and crafting ways available, moulded into shape of beautiful products that should advertently repair frown faces and uplift the bright ones!</p>', '2019-09-16 10:29:30'),
(36, 'http://www.gcsmc.org/', 'GCS Medical College, Hospital and Research Centre', 3, 'GCS Medical College by Dwarkesh', '54.jpg', '55.jpg', '51.jpg', '52.jpg,53.jpg,white_panel2.1568636458.png', 'GCS Medical College', 'Dwarkesh Business Solution LLP', '05/08/2019', 'Bootstrap, jQuery', '<p class=\"text-justify mt-40\" style=\"-webkit-tap-highlight-color: transparent; margin-top: 40px; margin-bottom: 10px; font-size: 15px; line-height: 23px; font-family: &quot;Open Sans&quot;, sans-serif; color: rgb(42, 42, 42) !important;\"><span class=\"pl-30\" style=\"-webkit-tap-highlight-color: transparent; padding-left: 30px !important;\">Gujarat cancer society was &nbsp;founded in 1961 and since then it has been providing care to the patient suffering from cancer at M.P.Shah Cancer Hospital, Civil Hospital Compound, Ahmedabad and Community Oncology Centre (COC), Vasna. Till date, lacs of patients have been diagnosed and treated.</span></p><p class=\"text-justify mt-20\" style=\"-webkit-tap-highlight-color: transparent; margin-bottom: 10px; font-size: 15px; line-height: 23px; font-family: &quot;Open Sans&quot;, sans-serif; margin-top: 20px !important; color: rgb(42, 42, 42) !important;\"><span class=\"pl-30\" style=\"-webkit-tap-highlight-color: transparent; padding-left: 30px !important;\">In appreciation of the work undertaken by GCS, the Government of Gujarat has given us the responsibility to develop a new medical college under Public Private Partnership. The Government of Gujarat has given 25 acres of land at the New Swadeshi Mill Compound, Naroda Road, Asarwa, Ahmedabad.</span></p>', '2019-09-16 12:20:58'),
(37, 'http://www.drkcpatel.com/', 'DR. K. C. PATEL', 3, 'DR. K. C. PATEL by Dwarkesh', '77.jpg', '76.jpg', '73.jpg', '74.jpg,75.jpg,white_panel2.1568698755.png', 'DR. K. C. PATEL', 'Dwarkesh Business Solution LLP', '06/11/2019', 'Bootstrap, jQuery', '<p><span style=\"color: rgb(102, 102, 119); font-family: &quot;Open Sans&quot;, sans-serif; text-align: justify; background-color: rgb(241, 241, 241);\">Since last 50 years Dr. K. C. Patel is associated with Shri Chimanbhai Patel Orthopaedic hospital, Bhailal amin general hospital, Sterling hospital, V.I.N.S and many other hospital Of vadodara. We have a team of young telented (more than 20 years of experience) doctors.</span><br></p>', '2019-09-17 05:39:15'),
(38, 'http://www.vibrantwavesvadodara.org/', 'Vibrant Waves International Academy', 3, 'Vibrant Waves by Dwarkesh', '37.jpg', '35.jpg', '34.jpg', '36.jpg,38.jpg,39.jpg,white_panel1.1568701746.png', 'Vibrant Waves International Academy', 'Dwarkesh Business Solution LLP', '06/12/2019', 'Bootstrap, jQuery', '<p style=\"margin-bottom: 10.5px; color: rgb(102, 102, 102); font-family: Monda, sans-serif; font-size: 15px;\">Vibrant Waves International Academy claims to be one of the most modernized schools in Bajwa, Vadodara. There are traces of it from the year 2014.Vibrant Waves International Academy, Bajwa, has developed very gracefully. The introduction of the MODERN curriculum and teaching patterns since the beginning of the system has added greatly to the development of the School.2018-19 being the fifth successful academic year, we are running high on number.The present set up has got pre-primary and primary sections.</p>', '2019-09-17 06:29:06'),
(39, 'http://winnersstudio.co.in/', 'Winners Studio', 3, 'Winners Studio by Dwarkesh', '46.jpg', '48.jpg', '45.jpg', '47.jpg,49.jpg,50.jpg,white_panel1.1568702207.png', 'Winners Studio', 'Dwarkesh Business Solution LLP', '08/12/2019', 'Bootstrap, PHP, jQuery', '<p><span style=\"color: rgb(51, 51, 51); font-family: Helvetica; font-size: medium; text-align: justify;\">Located in the heart of SANSKAR-NAGRI Vadodara, the cultural capital of Gujarat, Winners Studio was established on the 31st opportunistic day of the month of July 2017, with the strong beliefs of perseverance, innovation and responsibility towards the artistsâ€™ community, rested in the founder and director of WINNERS STUDIO, Harshil Chauhan, and his flawless &amp; dedicated team.</span><br></p>', '2019-09-17 06:36:47'),
(40, 'http://electrolineindia.com/', 'Electro Line, Power of Energy System', 3, 'Electro Line by Dwarkesh', '119.jpg', '121.jpg', '117.jpg', '118.jpg,120.jpg,white_panel2.1568702923.png', 'Electro Line, Power of Energy System', 'Dwarkesh Business Solution LLP', '08/26/2019', 'Wordpress, PHP, MySQL, jQuery\r\n\r\n\r\n\r\n', '<h2 style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px 0px 10px; border: 0px; outline-style: initial; outline-width: 0px; background: rgb(247, 247, 247); font-size: 26px; vertical-align: baseline; color: rgb(51, 51, 51); line-height: 1em; font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline-style: initial; outline-width: 0px; background: transparent; vertical-align: baseline; color: rgb(255, 199, 0);\">Welcome To The Electro Line</span></h2><p style=\"padding: 0px 0px 1em; border: 0px; outline-style: initial; outline-width: 0px; background: rgb(247, 247, 247); vertical-align: baseline; color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Established in the year 1989, we Electro Line are a distinguished name in the field of manufacturing and supplying of UPS, Inverters, C.V.T., Servo, Stabilizer, Solar Products Such as Solar Street</p>', '2019-09-17 06:48:43'),
(41, 'https://www.goldenautoworld.com/', 'Golden Auto World', 3, 'Golden Auto World by Dwarkesh', '57.jpg', '59.jpg', '56.jpg', '58.jpg,60.jpg,61.jpg,white_panel1.1568703372.png', 'Golden Auto World', 'Dwarkesh Business Solution LLP', '07/10/2019', 'Wordpress, PHP, MySQL, jQuery\r\n\r\n\r\n\r\n', '<p><span style=\"color: rgb(52, 52, 52); font-family: Lato;\">We Provide Automotive Locksmith Services such as Dotted Keys, Im-Mobilizer Keys, Transponder Keys, And Sensor Keys, Remote Keys And Flip Keys, Keypad Change And Remote Repair Service, Normal Key To Flip Key, After Market Remote To Flip Key, Interlock Keys, Lost Key Solution And Key Service At Spot, Lock Repair, Key Cutting From Key Number, On-Spot Key Solution,Battery Change And Silicon Cover For All Car Model, Circuit Repair, Broken Key Shell Replacement, Automotive Keys, Electronic Keys, Smart Keys.</span><br></p>', '2019-09-17 06:56:12'),
(42, 'http://www.thegreendesert.co.in/', 'The Green Desert', 3, 'The Green Desert by Dwarkesh', '70.jpg', '70.jpg', '67.jpg', '69.jpg,71.jpg,72.jpg,white_panel1.1568703777.png', 'The Green Desert', 'Dwarkesh Business Solution LLP', '06/19/2019', 'Bootstrap, jQuery', '<p><span style=\"font-family: Poppins, sans-serif; font-size: 16px; text-align: justify;\">The Green Desert is one of the best happening venues located near the Vaishnav Devi Circle, Ahmedabad City located in the middle of connecting both the city Gandhinagar â€“ Ahmedabad. This thoughtfully designed garden and family restaurant is a home to multiple cuisines. Our restaurant and garden can sit up to 300 guests or can be hired for corporate events. The restaurant captures the final rays of the setting sun, a perfect place to be at the garden enjoying the company of friends and the great service of The Green Desert. The restaurant and surroundings are suited for a casual lunch, family gatherings, a romantic dinner, weddings and celebrations. The relaxed, warm atmosphere is in itself a conversation starter.</span><br></p>', '2019-09-17 07:02:57'),
(43, 'http://natrajhotel.co.in/', 'Natraj Hotel', 3, 'Natraj Hotel by Dwarkesh', '81.jpg', '80.jpg', '78.jpg', '79.jpg,82.jpg,83.jpg,white_panel1.1568711379.png', 'Natraj Hotel', 'Dwarkesh Business Solution LLP', '09/04/2019', 'jQuery, HTML, CSS', '<p><span style=\"color: rgb(51, 51, 51); font-family: Raleway, Arial, Helvetica, sans-serif; font-size: 16px; font-weight: 600; text-align: justify;\">The Hotel Natraj &amp; Resort , one of the first boutique hotels located at Delwad near Gandhinagar â€“ Mahudi Highway Road exudes luxury, sophistication and intimacy. This Hotel provides an elevated standard of style, contemporary design and decor. The state-of-the-art technology and amenities for commerce, leisure and relaxation makes our hotel in Gandhinagar an ideal choice for patrons, guests and travelers whether on business, leisure or a weekend retreat who visit Mahudi. The Hotel Natraj &amp; Resort near Mahudi Temple promises complete indulgence with its facilities, restaurants and Party Plot - making it a preferred destination of discerning patrons looking for luxury hotels Near Gandhinagar. Home to extravagance â€“ Hotel Natraj is a place to relax your senses and pamper yourself with the best Gandhinagar hotels have to offer.</span><br></p>', '2019-09-17 09:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_details`
--

CREATE TABLE `portfolio_details` (
  `id` int(11) NOT NULL,
  `project_keyword` varchar(100) NOT NULL,
  `project_title` varchar(100) NOT NULL,
  `company_logo` varchar(255) NOT NULL,
  `img_banner` varchar(255) NOT NULL,
  `mobile_img` varchar(255) NOT NULL,
  `mobile_feature` text NOT NULL,
  `project_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `customerID` int(5) NOT NULL,
  `fullName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `emailID` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phoneNum` bigint(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`customerID`, `fullName`, `lastName`, `emailID`, `password`, `phoneNum`, `country`, `state`, `timestamp`) VALUES
(1, 'Sujit', 'Mak', 'mcshiujtal@gmail.com', '124', 9558105606, '', '', '0000-00-00 00:00:00'),
(2, 'Dhara', 'Joshi', 'mcshiujtal@gmail.com', '123', 123, '', '', '2012-02-05 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `replyInquiry`
--

CREATE TABLE `replyInquiry` (
  `repleyID` int(10) NOT NULL,
  `inquiryid` int(10) NOT NULL,
  `repley` varchar(300) NOT NULL,
  `rtimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `solution`
--

CREATE TABLE `solution` (
  `solutionID` varchar(10) NOT NULL,
  `ticketID` varchar(10) NOT NULL,
  `customerID` int(5) NOT NULL,
  `solution` varchar(500) NOT NULL,
  `stimestampe` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `solution`
--

INSERT INTO `solution` (`solutionID`, `ticketID`, `customerID`, `solution`, `stimestampe`) VALUES
('Q2JQZC71Z5', 'KQJQZC51Z5', 2, 'Please Check Your Connection Cables..', '2019-08-30 11:23:25'),
('ZHXFRNFCN6', '53797DXGGL', 2, '32345544564584', '2019-09-03 13:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticketID` varchar(10) NOT NULL,
  `customerID` int(5) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `emailID` varchar(255) NOT NULL,
  `homeAddress` varchar(100) NOT NULL,
  `zipCode` int(6) NOT NULL,
  `phoneNo` int(10) NOT NULL,
  `referBy` varchar(20) NOT NULL,
  `querySubject` varchar(100) NOT NULL,
  `query` varchar(500) NOT NULL,
  `attachment` varchar(300) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-pending,1-complete',
  `timestampes` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticketID`, `customerID`, `firstName`, `lastName`, `emailID`, `homeAddress`, `zipCode`, `phoneNo`, `referBy`, `querySubject`, `query`, `attachment`, `status`, `timestampes`) VALUES
('53797DXGGL', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'Hang PC', 'download.png', '1', '2019-08-30 10:09:25'),
('KQJQZC51Z5', 2, 'Jigar', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'Not Start', 'favicon.ico', '1', '2019-08-30 10:11:50'),
('E87E2VTZKZ', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:28:40'),
('PR1FG5PPCD', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:33:03'),
('DL79RWBMOP', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:33:56'),
('MMX7RD2YPY', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:34:43'),
('QEO6POQJTE', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:35:51'),
('ZJMIGJ8I6W', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:36:46'),
('7FP3228MYP', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:37:42'),
('MXE2OBMS9Z', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:41:45'),
('IYMUIULM65', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:46:07'),
('H5QU4LEEMF', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:50:49'),
('59OTJE8TTC', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 08:39:08'),
('573FIQ4WJ6', 1, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'hkjhjkhkj', 'About Pc', 'fbhcfhd', 'Screenshot from 2019-08-30 12-57-57.png', '0', '2019-09-02 08:47:02'),
('9CQZ1ZIMA0', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'NOT WORKING...', 'bitbukket.png', '0', '2019-09-03 05:03:06'),
('FAQ2ZZW6DN', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'kjhkhk', 'OKOKOKKOO', '7.jpg', '0', '2019-09-05 11:08:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `career`
--
ALTER TABLE `career`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientlogo`
--
ALTER TABLE `clientlogo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `innerpage_slider`
--
ALTER TABLE `innerpage_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`inquiryid`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `portfolio_details`
--
ALTER TABLE `portfolio_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `replyInquiry`
--
ALTER TABLE `replyInquiry`
  ADD PRIMARY KEY (`repleyID`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `solution`
--
ALTER TABLE `solution`
  ADD PRIMARY KEY (`solutionID`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticketID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `career`
--
ALTER TABLE `career`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clientlogo`
--
ALTER TABLE `clientlogo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `innerpage_slider`
--
ALTER TABLE `innerpage_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `inquiryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `portfolio_details`
--
ALTER TABLE `portfolio_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `customerID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `replyInquiry`
--
ALTER TABLE `replyInquiry`
  MODIFY `repleyID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Database: `dwarkesh_user_dbs_sol`
--
CREATE DATABASE IF NOT EXISTS `dwarkesh_user_dbs_sol` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dwarkesh_user_dbs_sol`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(10) NOT NULL,
  `postTitle` varchar(50) NOT NULL,
  `postBy` varchar(20) NOT NULL,
  `postImg` varchar(300) NOT NULL,
  `description` varchar(600) NOT NULL,
  `timestamps` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `postTitle`, `postBy`, `postImg`, `description`, `timestamps`) VALUES
(2, 'LARAVAL', 'Nikunj Patel', 'bl_1.jpg', '<p><span style=\"color: rgb(126, 126, 126); font-family: Exo, sans-serif; background-color: rgb(239, 243, 246);\">Laravel is a free, open-source[3] PHP web framework, created by Taylor Otwell and intended for the development of web applications following the Model View Controller (MVC) architectural pattern and based on Symfony. Some of the features of Laravel are a modular packaging system with a dedicated dependency manager, different ways for accessing relational databases, utilities that aid in application deployment and maintenance, and its orientation toward syntactic suga</span><br></p>', '2019-09-06 11:17:14'),
(10, 'ECOMMERCE', 'MANISHA KHANDELWAL', 'bl_2.jpg', '<span style=\"color: rgb(126, 126, 126); font-family: Exo, sans-serif; background-color: rgb(239, 243, 246);\">Laravel is a free, open-source[3] PHP web framework, created by Taylor Otwell and intended for the development of web applications following the Model View Controller (MVC) architectural pattern and based on Symfony. Some of the features of Laravel are a modular packaging system with a dedicated dependency manager, different ways for accessing relational databases, utilities that aid in application deployment and maintenance, and its orientation toward syntactic suga</span> ', '2019-09-06 13:42:30'),
(11, 'MISSION MARS', 'Sujit Mak', '7.jpg', '<span style=\"color: rgb(126, 126, 126); font-family: Exo, sans-serif; background-color: rgb(239, 243, 246);\">Laravel is a free, open-source[3] PHP web framework, created by Taylor Otwell and intended for the development of web applications following the Model View Controller (MVC) architectural pattern and based on Symfony. Some of the features of Laravel are a modular packaging system with a dedicated dependency manager, different ways for accessing relational databases, utilities that aid in application deployment and maintenance, and its orientation toward syntactic suga</span> ', '2019-09-06 13:43:03');

-- --------------------------------------------------------

--
-- Table structure for table `career`
--

CREATE TABLE `career` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_no` varchar(100) NOT NULL,
  `technical_skill` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `experience` int(10) NOT NULL,
  `notice_period` varchar(100) NOT NULL,
  `resume` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `career`
--

INSERT INTO `career` (`id`, `fname`, `email`, `mobile_no`, `technical_skill`, `designation`, `experience`, `notice_period`, `resume`) VALUES
(15, 'sujit', 'sujit@dwarkeshit.com', '9897456613', 'JAVA + CORE PHP + MYSQL', 'JAVA Developer', 1, '1 Month', 'SUJIT-Resume2.pdf'),
(16, 'Dhaval', 'dhaval@dwarkeshit.com', '7894561230', 'CORE PHP + LARAVEL ', 'Laravel Developer', 1, '1 Month', '3.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `timestamp`) VALUES
(1, 'Web Design', '2019-08-19 04:48:23'),
(3, 'Web Site', '2019-09-05 09:31:38'),
(2, 'Web Application', '2019-09-03 14:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `change_background_image`
--

CREATE TABLE `change_background_image` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clientlogo`
--

CREATE TABLE `clientlogo` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clientlogo`
--

INSERT INTO `clientlogo` (`id`, `image`, `alt`, `timestamp`) VALUES
(4, '2019_04_05_11_07_18am01.jpg', 'Security Service For Delta Web Solution', '2019-04-05 11:07:18'),
(5, '2019_04_05_11_07_30am02.jpg', 'Ayurveda For Delta Web Solution', '2019-04-05 11:07:30'),
(6, '2019_04_05_11_07_42am03.jpg', 'Academy For Delta Web Solution', '2019-04-05 11:07:42'),
(7, '2019_04_05_11_07_52am04.jpg', 'Infra For Delta Web Solution', '2019-04-05 11:07:52'),
(8, '2019_04_05_11_08_09am05.jpg', 'Construction Company For Delta Web Solution', '2019-04-05 11:08:09'),
(9, '2019_04_05_11_08_24am06.jpg', 'IT For Delta Web Solution', '2019-04-05 11:08:24'),
(10, '2019_04_05_11_08_35am07.jpg', 'Cafe For Delta Web Solution', '2019-04-05 11:08:35'),
(11, '2019_04_05_11_08_46am08.jpg', 'Auto Word For Delta Web Solution', '2019-04-05 11:08:46'),
(12, '2019_04_05_11_08_56am09.jpg', 'Solar For Delta Web Solution', '2019-04-05 11:08:56'),
(13, '2019_04_05_11_09_10am10.jpg', 'Institute For Delta Web Solution', '2019-04-05 11:09:10'),
(14, '2019_04_05_11_09_20am11.jpg', 'Hotel For Delta Web Solution', '2019-04-05 11:09:20'),
(15, '2019_04_05_11_09_26am12.jpg', 'Hotel For Delta Web Solution', '2019-04-05 11:09:26'),
(16, '2019_04_05_11_09_39am13.jpg', 'IT For Delta Web Solution', '2019-04-05 11:09:39'),
(17, '2019_04_05_11_09_49am14.jpg', 'IT For Delta Web Solution', '2019-04-05 11:09:49'),
(18, '2019_04_05_11_10_01am15.jpg', 'Academy For Delta Web Solution', '2019-04-05 11:10:01'),
(27, '2019_06_22_05_50_13pm02.jpg', 'Dwarkesh IT', '2019-06-22 12:20:13'),
(26, '2019_06_22_05_50_07pm01.jpg', 'Dwarkesh IT', '2019-06-22 12:20:07'),
(33, '2019_06_22_05_50_45pm08.jpg', 'Dwarkesh IT', '2019-06-22 12:20:45'),
(34, '2019_06_22_05_50_51pm09.jpg', 'Dwarkesh IT', '2019-06-22 12:20:51'),
(35, '2019_06_22_05_51_07pm10.jpg', 'Dwarkesh IT', '2019-06-22 12:21:07'),
(36, '2019_06_22_05_51_17pm11.jpg', 'Dwarkesh IT', '2019-06-22 12:21:17'),
(37, '2019_06_22_05_51_28pm12.jpg', 'Dwarkesh IT', '2019-06-22 12:21:28'),
(38, '2019_06_22_05_51_35pm13.jpg', 'Dwarkesh IT', '2019-06-22 12:21:35');

-- --------------------------------------------------------

--
-- Table structure for table `innerpage_slider`
--

CREATE TABLE `innerpage_slider` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `inquiryid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `comments` longtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inquiry`
--

INSERT INTO `inquiry` (`inquiryid`, `name`, `email`, `phone`, `subject`, `comments`, `timestamp`) VALUES
(1, 'Pratik Shah', 'pshah5132@gmail.com', '9723840340', 'Technical Issue', 'asX', '2019-04-04 19:34:36'),
(2, 'Nikunj', 'nikpatel511@hotmail.com', '9429812012', 'Web design', 'kgdmkgkg', '2019-04-09 04:34:18'),
(3, 'Nikunj Patel', 'sanjivm43@gmail.com', '9898363557', 'sddsa', 'dsfsaf', '2019-04-09 04:35:48'),
(4, 'nikunj', 'nikpatel511@hotmail.com', '9429812012', 'dynamic', 'idfsfdsf', '2019-04-24 03:52:09'),
(5, 'nikunj', 'sanjivm43@gmail.com', '9898363557', '789', 'msdgdngnddng', '2019-04-28 14:48:11'),
(6, 'nikunj', 'sadfo@gmail.com', '942985698', 'sdfsdf', 'sdfsfsfsd', '2019-05-06 10:03:45'),
(7, 'bik', 'nikpatel511@gmail.com', '8697897897', 'vbmvgmjbhmj', 'ghjhgjghjgh', '2019-06-02 10:01:57'),
(8, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:03:40'),
(9, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:04:34'),
(10, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:05:26'),
(11, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:06:29'),
(12, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:08:01'),
(13, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:14:23'),
(14, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:15:54'),
(15, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:17:04'),
(16, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:17:44'),
(17, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:19:31'),
(18, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:20:45'),
(19, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:21:30'),
(20, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:22:14'),
(21, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', 'testtestetsdetdttertdtetdtetetdtdetdd', '2019-06-02 10:26:10'),
(22, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:32:30'),
(23, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:34:50'),
(24, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:41:09'),
(25, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:41:11'),
(26, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:45:49'),
(27, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:47:35'),
(28, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:47:54'),
(29, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:55:34'),
(30, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:56:02'),
(31, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:56:58'),
(32, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:57:23'),
(33, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:58:26'),
(34, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:59:08'),
(35, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 10:59:46'),
(36, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:01:21'),
(37, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:02:30'),
(38, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:02:44'),
(39, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:03:03'),
(40, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:05:05'),
(41, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:05:30'),
(42, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:06:21'),
(43, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:07:50'),
(44, 'deviation.co.in', 'nikpatel511@gmail.com', '9429812012', 'vbmvgmjbhmj', 'erfsetrewt', '2019-06-02 11:08:41'),
(45, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', ' Test email Test email Test email Test email Test emailTest emailTest emailTest email', '2019-06-02 14:12:18'),
(46, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', ' Test email Test email Test email Test email Test emailTest emailTest emailTest email', '2019-06-02 14:23:54'),
(47, 'Pratik', 'pshah5132@gmail.com', '9723840340', 'Test', ' Test email Test email Test email Test email Test emailTest emailTest emailTest email', '2019-06-02 14:31:22'),
(48, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'safdasf', '2019-06-05 04:53:27'),
(49, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'dsgfdsg', '2019-06-05 04:55:48'),
(50, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'dsafaf', '2019-06-05 06:34:35'),
(51, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'eff', '2019-06-05 06:44:41'),
(52, 'nikunj 511', 'nik@softworldsolution.com', '9429812012', 'website', 'dfgdgfgd', '2019-06-05 06:47:37'),
(53, 'TEST', 'test@gmail.com', '1234567890', 'TEST', 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest', '2019-06-05 06:52:10'),
(54, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'sdsf', '2019-06-05 06:55:24'),
(55, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'sdafdfsd', '2019-06-05 07:01:42'),
(56, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'sdafdfsd', '2019-06-05 07:09:13'),
(57, 'Manisha', 'nik@softworldsolution.com', '9429812012', 'website', 'dfsdfsfs', '2019-06-05 07:12:48'),
(58, 'nikunj', 'nik@softworldsolution.com', '9429812012', 'website', 'dsfsg', '2019-06-05 07:16:46'),
(59, 'nikunj', 'nikpatel511@gmail.com', '9429812012', 'Test', 'fdgdgdf', '2019-06-05 13:29:20'),
(60, 'nikunj', 'nikpatel511@gmail.com', '9429812012', 'Web site', 'Static web site', '2019-06-14 04:24:44'),
(61, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'Bye ', '2019-09-02 04:53:37'),
(62, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'Bye ', '2019-09-02 04:53:59'),
(63, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'ndjkhkkjhkkj', '2019-09-02 06:09:36'),
(64, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'scscaca', '2019-09-02 06:24:11'),
(65, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'ssdasdasda', '2019-09-02 06:46:57'),
(66, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'ssdasdasda', '2019-09-02 06:47:18'),
(67, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'ssdasdasda', '2019-09-02 06:47:47'),
(68, 'Sujit', 'sujit@dwarkeshit.com', '7984511234', 'OKOK', 'dsdefgsvs', '2019-09-02 06:48:22');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user_id` varchar(70) NOT NULL,
  `password` varchar(70) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user_id`, `password`, `timestamp`) VALUES
(1, 'admin', 'b43e691700a8a4f5c1e903b6bc29a60a', '2019-04-03 17:07:22');

-- --------------------------------------------------------

--
-- Table structure for table `logo`
--

CREATE TABLE `logo` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logo`
--

INSERT INTO `logo` (`id`, `img`) VALUES
(1, 'download.png');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `id` int(11) NOT NULL,
  `project_link` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `frontImg` varchar(500) NOT NULL,
  `bredCrumpImg` varchar(500) NOT NULL,
  `frontContentImg` varchar(500) NOT NULL,
  `screenShotImg` varchar(500) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `complated_date` varchar(100) NOT NULL,
  `skill` varchar(100) NOT NULL,
  `project_description` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (`id`, `project_link`, `project_name`, `category_id`, `alt`, `frontImg`, `bredCrumpImg`, `frontContentImg`, `screenShotImg`, `client_name`, `created_by`, `complated_date`, `skill`, `project_description`, `timestamp`) VALUES
(29, 'http://snp-studio.com/', 'Stegenga + PARTNERS A Professional Studio', 3, 'Stegenga by Dwarkesh', '10.jpg', '11.jpg', '07.jpg', '08.1568627818.jpg,09.1568627818.jpg,white_panel2.png', 'Stegenga + PARTNERS', 'Dwarkesh Business Solution LLP', '09/13/2019', 'Bootstrap, jQuery', '<h3 class=\"col-about-title\" style=\"font-family: montserratsemibold; color: rgb(61, 61, 61); margin-top: 0px; margin-bottom: 1.1em; font-size: 1.875em;\">We turn ideas into works of people and purpose<span class=\"text-primary\" style=\"color: rgb(197, 164, 126); text-transform: capitalize;\">.</span></h3><div class=\"col-about-info\" style=\"font-family: montserratlight, sans-serif; font-size: 16px;\"><p style=\"margin-bottom: 1.6em;\">We are a diverse team of design professionals with a people-centric philosophy at the heart of the design process. Our mission is to deliver exceptional design ideas and solutions through the creative blending of human need, expertise, value creation, and environmental stewardship.</p><p>The Studioâ€™s belief is that better buildings make for a better world. A successful building is one that improves daily life for the people who live and work around it.</p></div>', '2019-09-16 09:56:58'),
(30, 'https://bankodesign.com/', 'Banko design', 3, 'Banko Design by Dwarkesh', '02.jpg', '04.jpg', '01.jpg', '03.1568627970.jpg,05.1568627970.jpg,06.1568627970.jpg,white_panel1.png', 'Banko design', 'Dwarkesh Business Solution LLP', '09/03/2019', 'Wordpress, PHP, MySQL, jQuery', '<div class=\"wpb_text_column wpb_content_element \" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px 0px 35px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center;\"><div class=\"wpb_wrapper\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"><h4 style=\"background: 0px 0px; border: 0px; margin-top: 0px; margin-bottom: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; font-family: &quot;PT Serif&quot;, sans-serif; font-size: 22px; line-height: 1.24em; font-weight: 700; font-style: italic; letter-spacing: -0.5px; color: rgb(0, 0, 0);\">Interior Design, Streamlined</h4></div></div><div class=\"vc_empty_space\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center; height: 12px;\"><span class=\"vc_empty_space_inner\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"></span></div><div class=\"wpb_text_column wpb_content_element \" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px 0px 35px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center;\"><div class=\"wpb_wrapper\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"><h2 style=\"background: 0px 0px; border: 0px; margin-top: 0px; margin-bottom: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; font-weight: 900; color: rgb(0, 0, 0); font-size: 50px; line-height: 1.08em;\">The Banko Way</h2></div></div><div class=\"vc_empty_space\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center; height: 8px;\"><span class=\"vc_empty_space_inner\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"></span></div><div class=\"qodef-separator-holder clearfix  qodef-separator-center\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; position: relative; height: auto; font-size: 0px; line-height: 1em; text-align: center; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif;\"><div class=\"qodef-separator\" style=\"background: 0px 0px; border-width: 0px 0px 3px; border-top-style: initial; border-right-style: initial; border-bottom-style: solid; border-left-style: initial; border-color: rgb(219, 219, 219); border-image: initial; margin: 10px 0px; padding: 0px; vertical-align: middle; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; position: relative; display: inline-block; width: 225px;\"></div></div><div class=\"vc_empty_space\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; color: rgb(122, 122, 122); font-family: Catamaran, sans-serif; font-size: 16px; text-align: center; height: 12px;\"><span class=\"vc_empty_space_inner\" style=\"background: 0px 0px; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent;\"></span></div><div class=\"qodef-custom-font-holder\" data-font-size=\"18\" data-line-height=\"25\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; margin: 0px; padding: 0px; vertical-align: baseline; outline-style: initial; outline-width: 0px; -webkit-tap-highlight-color: transparent; letter-spacing: 0px; text-align: center; font-family: Catamaran; font-size: 18px; line-height: 25px; font-weight: 600; color: rgb(150, 150, 150);\">Banko Design is leading the forefront in boutique interior design for the senior living, multifamily, healthcare and hospitality markets. Our fourteen-member team of designers, interior architects and purchasing agents work to manage every aspect of your interior design project. Weâ€™ve streamlined the entire design and procurement process to create a seamless experience for our clients.</div>', '2019-09-16 09:59:30'),
(32, 'https://www.panachebeauty.ca/', 'Panache Beauty Salon', 3, 'Panache Beauty Salon by Dwarkesh', '21.jpg', '22.jpg', '18.jpg', '19.jpg,20.jpg,white_panel2.1568628418.png', 'Panache Beauty Salon', 'Dwarkesh Business Solution LLP', '07/09/2019', 'PHP, jQuery', '<div class=\"container z-bigger\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 15px; line-height: inherit; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Helvetica, Arial, sans-serif; vertical-align: baseline; position: relative; width: 1320px; z-index: 20; background-color: rgb(212, 79, 88);\"><h4 style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; font-size: 24px; line-height: 32px; font-family: Poppins, sans-serif; vertical-align: baseline; color: rgb(0, 0, 0); text-align: center;\">We donâ€™t make your trips from work to salon go to waste or your blowout session turn into a yawning one. We make sure we listen to you &amp; your skin &amp; hair responds to us â€“ much like a whisperer.</h4></div><div class=\"container z-bigger\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 15px; line-height: inherit; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Helvetica, Arial, sans-serif; vertical-align: baseline; position: relative; width: 1320px; z-index: 20; background-color: rgb(212, 79, 88);\"><div class=\"twelve columns\" data-scroll-reveal=\"enter bottom move 100px over 1s after 0.3s\" data-scroll-reveal-id=\"5\" data-scroll-reveal-initialized=\"true\" data-scroll-reveal-complete=\"true\" style=\"margin: 25px; padding: 0px; border: 0px; font: inherit; vertical-align: baseline; float: left; display: inline; width: 1270px;\"><div class=\"services-wrap\" style=\"margin: 0px; padding: 40px; border: 0px; font: inherit; vertical-align: baseline; position: relative;\"><h4 style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; font-size: 24px; line-height: 32px; font-family: Poppins, sans-serif; vertical-align: baseline; text-align: center; color: rgb(0, 0, 0) !important;\">Welcome to Panache Beauty salon &amp; SPA â€“ the Brampton hair Salon &amp; SPA where youâ€™ll receive a guaranteed Class A service to make you look &amp; feel sensational but isnâ€™t that all salons promise? Well, we deliver, you say what more?</h4></div></div></div>', '2019-09-16 10:06:58'),
(35, 'http://creartisan.com/', 'CreArtisan Creative Giftings', 3, 'CreArtisan by Dwarkesh', '43.jpg', '44.jpg', '40.jpg', '41.jpg,42.jpg,white_panel2.1568629770.png', 'CreArtisan Creative Giftings', 'Dwarkesh Business Solution LLP', '03/06/2019', 'Wordpress, PHP, MySQL, jQuery', '<p>We believe Creative gifting is the best way to communicate the love &amp; express gratitude. For a gesture as personal as this, we made our personal artistry and crafting ways available, moulded into shape of beautiful products that should advertently repair frown faces and uplift the bright ones!</p>', '2019-09-16 10:29:30'),
(36, 'http://www.gcsmc.org/', 'GCS Medical College, Hospital and Research Centre', 3, 'GCS Medical College by Dwarkesh', '54.jpg', '55.jpg', '51.jpg', '52.jpg,53.jpg,white_panel2.1568636458.png', 'GCS Medical College', 'Dwarkesh Business Solution LLP', '05/08/2019', 'Bootstrap, jQuery', '<p class=\"text-justify mt-40\" style=\"-webkit-tap-highlight-color: transparent; margin-top: 40px; margin-bottom: 10px; font-size: 15px; line-height: 23px; font-family: &quot;Open Sans&quot;, sans-serif; color: rgb(42, 42, 42) !important;\"><span class=\"pl-30\" style=\"-webkit-tap-highlight-color: transparent; padding-left: 30px !important;\">Gujarat cancer society was &nbsp;founded in 1961 and since then it has been providing care to the patient suffering from cancer at M.P.Shah Cancer Hospital, Civil Hospital Compound, Ahmedabad and Community Oncology Centre (COC), Vasna. Till date, lacs of patients have been diagnosed and treated.</span></p><p class=\"text-justify mt-20\" style=\"-webkit-tap-highlight-color: transparent; margin-bottom: 10px; font-size: 15px; line-height: 23px; font-family: &quot;Open Sans&quot;, sans-serif; margin-top: 20px !important; color: rgb(42, 42, 42) !important;\"><span class=\"pl-30\" style=\"-webkit-tap-highlight-color: transparent; padding-left: 30px !important;\">In appreciation of the work undertaken by GCS, the Government of Gujarat has given us the responsibility to develop a new medical college under Public Private Partnership. The Government of Gujarat has given 25 acres of land at the New Swadeshi Mill Compound, Naroda Road, Asarwa, Ahmedabad.</span></p>', '2019-09-16 12:20:58'),
(37, 'http://www.drkcpatel.com/', 'DR. K. C. PATEL', 3, 'DR. K. C. PATEL by Dwarkesh', '77.jpg', '76.jpg', '73.jpg', '74.jpg,75.jpg,white_panel2.1568698755.png', 'DR. K. C. PATEL', 'Dwarkesh Business Solution LLP', '06/11/2019', 'Bootstrap, jQuery', '<p><span style=\"color: rgb(102, 102, 119); font-family: &quot;Open Sans&quot;, sans-serif; text-align: justify; background-color: rgb(241, 241, 241);\">Since last 50 years Dr. K. C. Patel is associated with Shri Chimanbhai Patel Orthopaedic hospital, Bhailal amin general hospital, Sterling hospital, V.I.N.S and many other hospital Of vadodara. We have a team of young telented (more than 20 years of experience) doctors.</span><br></p>', '2019-09-17 05:39:15'),
(38, 'http://www.vibrantwavesvadodara.org/', 'Vibrant Waves International Academy', 3, 'Vibrant Waves by Dwarkesh', '37.jpg', '35.jpg', '34.jpg', '36.jpg,38.jpg,39.jpg,white_panel1.1568701746.png', 'Vibrant Waves International Academy', 'Dwarkesh Business Solution LLP', '06/12/2019', 'Bootstrap, jQuery', '<p style=\"margin-bottom: 10.5px; color: rgb(102, 102, 102); font-family: Monda, sans-serif; font-size: 15px;\">Vibrant Waves International Academy claims to be one of the most modernized schools in Bajwa, Vadodara. There are traces of it from the year 2014.Vibrant Waves International Academy, Bajwa, has developed very gracefully. The introduction of the MODERN curriculum and teaching patterns since the beginning of the system has added greatly to the development of the School.2018-19 being the fifth successful academic year, we are running high on number.The present set up has got pre-primary and primary sections.</p>', '2019-09-17 06:29:06'),
(39, 'http://winnersstudio.co.in/', 'Winners Studio', 3, 'Winners Studio by Dwarkesh', '46.jpg', '48.jpg', '45.jpg', '47.jpg,49.jpg,50.jpg,white_panel1.1568702207.png', 'Winners Studio', 'Dwarkesh Business Solution LLP', '08/12/2019', 'Bootstrap, PHP, jQuery', '<p><span style=\"color: rgb(51, 51, 51); font-family: Helvetica; font-size: medium; text-align: justify;\">Located in the heart of SANSKAR-NAGRI Vadodara, the cultural capital of Gujarat, Winners Studio was established on the 31st opportunistic day of the month of July 2017, with the strong beliefs of perseverance, innovation and responsibility towards the artistsâ€™ community, rested in the founder and director of WINNERS STUDIO, Harshil Chauhan, and his flawless &amp; dedicated team.</span><br></p>', '2019-09-17 06:36:47'),
(40, 'http://electrolineindia.com/', 'Electro Line, Power of Energy System', 3, 'Electro Line by Dwarkesh', '119.jpg', '121.jpg', '117.jpg', '118.jpg,120.jpg,white_panel2.1568702923.png', 'Electro Line, Power of Energy System', 'Dwarkesh Business Solution LLP', '08/26/2019', 'Wordpress, PHP, MySQL, jQuery\r\n\r\n\r\n\r\n', '<h2 style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px 0px 10px; border: 0px; outline-style: initial; outline-width: 0px; background: rgb(247, 247, 247); font-size: 26px; vertical-align: baseline; color: rgb(51, 51, 51); line-height: 1em; font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline-style: initial; outline-width: 0px; background: transparent; vertical-align: baseline; color: rgb(255, 199, 0);\">Welcome To The Electro Line</span></h2><p style=\"padding: 0px 0px 1em; border: 0px; outline-style: initial; outline-width: 0px; background: rgb(247, 247, 247); vertical-align: baseline; color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Established in the year 1989, we Electro Line are a distinguished name in the field of manufacturing and supplying of UPS, Inverters, C.V.T., Servo, Stabilizer, Solar Products Such as Solar Street</p>', '2019-09-17 06:48:43'),
(41, 'https://www.goldenautoworld.com/', 'Golden Auto World', 3, 'Golden Auto World by Dwarkesh', '57.jpg', '59.jpg', '56.jpg', '58.jpg,60.jpg,61.jpg,white_panel1.1568703372.png', 'Golden Auto World', 'Dwarkesh Business Solution LLP', '07/10/2019', 'Wordpress, PHP, MySQL, jQuery\r\n\r\n\r\n\r\n', '<p><span style=\"color: rgb(52, 52, 52); font-family: Lato;\">We Provide Automotive Locksmith Services such as Dotted Keys, Im-Mobilizer Keys, Transponder Keys, And Sensor Keys, Remote Keys And Flip Keys, Keypad Change And Remote Repair Service, Normal Key To Flip Key, After Market Remote To Flip Key, Interlock Keys, Lost Key Solution And Key Service At Spot, Lock Repair, Key Cutting From Key Number, On-Spot Key Solution,Battery Change And Silicon Cover For All Car Model, Circuit Repair, Broken Key Shell Replacement, Automotive Keys, Electronic Keys, Smart Keys.</span><br></p>', '2019-09-17 06:56:12'),
(42, 'http://www.thegreendesert.co.in/', 'The Green Desert', 3, 'The Green Desert by Dwarkesh', '70.jpg', '70.jpg', '67.jpg', '69.jpg,71.jpg,72.jpg,white_panel1.1568703777.png', 'The Green Desert', 'Dwarkesh Business Solution LLP', '06/19/2019', 'Bootstrap, jQuery', '<p><span style=\"font-family: Poppins, sans-serif; font-size: 16px; text-align: justify;\">The Green Desert is one of the best happening venues located near the Vaishnav Devi Circle, Ahmedabad City located in the middle of connecting both the city Gandhinagar â€“ Ahmedabad. This thoughtfully designed garden and family restaurant is a home to multiple cuisines. Our restaurant and garden can sit up to 300 guests or can be hired for corporate events. The restaurant captures the final rays of the setting sun, a perfect place to be at the garden enjoying the company of friends and the great service of The Green Desert. The restaurant and surroundings are suited for a casual lunch, family gatherings, a romantic dinner, weddings and celebrations. The relaxed, warm atmosphere is in itself a conversation starter.</span><br></p>', '2019-09-17 07:02:57'),
(43, 'http://natrajhotel.co.in/', 'Natraj Hotel', 3, 'Natraj Hotel by Dwarkesh', '81.jpg', '80.jpg', '78.jpg', '79.jpg,82.jpg,83.jpg,white_panel1.1568711379.png', 'Natraj Hotel', 'Dwarkesh Business Solution LLP', '09/04/2019', 'jQuery, HTML, CSS', '<p><span style=\"color: rgb(51, 51, 51); font-family: Raleway, Arial, Helvetica, sans-serif; font-size: 16px; font-weight: 600; text-align: justify;\">The Hotel Natraj &amp; Resort , one of the first boutique hotels located at Delwad near Gandhinagar â€“ Mahudi Highway Road exudes luxury, sophistication and intimacy. This Hotel provides an elevated standard of style, contemporary design and decor. The state-of-the-art technology and amenities for commerce, leisure and relaxation makes our hotel in Gandhinagar an ideal choice for patrons, guests and travelers whether on business, leisure or a weekend retreat who visit Mahudi. The Hotel Natraj &amp; Resort near Mahudi Temple promises complete indulgence with its facilities, restaurants and Party Plot - making it a preferred destination of discerning patrons looking for luxury hotels Near Gandhinagar. Home to extravagance â€“ Hotel Natraj is a place to relax your senses and pamper yourself with the best Gandhinagar hotels have to offer.</span><br></p>', '2019-09-17 09:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_details`
--

CREATE TABLE `portfolio_details` (
  `id` int(11) NOT NULL,
  `project_keyword` varchar(100) NOT NULL,
  `project_title` varchar(100) NOT NULL,
  `company_logo` varchar(255) NOT NULL,
  `img_banner` varchar(255) NOT NULL,
  `mobile_img` varchar(255) NOT NULL,
  `mobile_feature` text NOT NULL,
  `project_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `customerID` int(5) NOT NULL,
  `fullName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `emailID` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phoneNum` bigint(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`customerID`, `fullName`, `lastName`, `emailID`, `password`, `phoneNum`, `country`, `state`, `timestamp`) VALUES
(1, 'Sujit', 'Mak', 'mcshiujtal@gmail.com', '124', 9558105606, '', '', '0000-00-00 00:00:00'),
(2, 'Dhara', 'Joshi', 'mcshiujtal@gmail.com', '123', 123, '', '', '2012-02-05 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `replyInquiry`
--

CREATE TABLE `replyInquiry` (
  `repleyID` int(10) NOT NULL,
  `inquiryid` int(10) NOT NULL,
  `repley` varchar(300) NOT NULL,
  `rtimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `solution`
--

CREATE TABLE `solution` (
  `solutionID` varchar(10) NOT NULL,
  `ticketID` varchar(10) NOT NULL,
  `customerID` int(5) NOT NULL,
  `solution` varchar(500) NOT NULL,
  `stimestampe` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `solution`
--

INSERT INTO `solution` (`solutionID`, `ticketID`, `customerID`, `solution`, `stimestampe`) VALUES
('Q2JQZC71Z5', 'KQJQZC51Z5', 2, 'Please Check Your Connection Cables..', '2019-08-30 11:23:25'),
('ZHXFRNFCN6', '53797DXGGL', 2, '32345544564584', '2019-09-03 13:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticketID` varchar(10) NOT NULL,
  `customerID` int(5) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `emailID` varchar(255) NOT NULL,
  `homeAddress` varchar(100) NOT NULL,
  `zipCode` int(6) NOT NULL,
  `phoneNo` int(10) NOT NULL,
  `referBy` varchar(20) NOT NULL,
  `querySubject` varchar(100) NOT NULL,
  `query` varchar(500) NOT NULL,
  `attachment` varchar(300) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-pending,1-complete',
  `timestampes` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticketID`, `customerID`, `firstName`, `lastName`, `emailID`, `homeAddress`, `zipCode`, `phoneNo`, `referBy`, `querySubject`, `query`, `attachment`, `status`, `timestampes`) VALUES
('53797DXGGL', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'Hang PC', 'download.png', '1', '2019-08-30 10:09:25'),
('KQJQZC51Z5', 2, 'Jigar', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'Not Start', 'favicon.ico', '1', '2019-08-30 10:11:50'),
('E87E2VTZKZ', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:28:40'),
('PR1FG5PPCD', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:33:03'),
('DL79RWBMOP', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:33:56'),
('MMX7RD2YPY', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:34:43'),
('QEO6POQJTE', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:35:51'),
('ZJMIGJ8I6W', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:36:46'),
('7FP3228MYP', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:37:42'),
('MXE2OBMS9Z', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:41:45'),
('IYMUIULM65', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:46:07'),
('H5QU4LEEMF', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 07:50:49'),
('59OTJE8TTC', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'pojojlkjljlkj', 'bitbukket.png', '0', '2019-09-02 08:39:08'),
('573FIQ4WJ6', 1, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'hkjhjkhkj', 'About Pc', 'fbhcfhd', 'Screenshot from 2019-08-30 12-57-57.png', '0', '2019-09-02 08:47:02'),
('9CQZ1ZIMA0', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'About Pc', 'NOT WORKING...', 'bitbukket.png', '0', '2019-09-03 05:03:06'),
('FAQ2ZZW6DN', 2, 'sujit', 'Mak', 'mcshiujtal@gmail.com', 'Surat', 390021, 2147483647, 'Yash', 'kjhkhk', 'OKOKOKKOO', '7.jpg', '0', '2019-09-05 11:08:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `career`
--
ALTER TABLE `career`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientlogo`
--
ALTER TABLE `clientlogo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `innerpage_slider`
--
ALTER TABLE `innerpage_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`inquiryid`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `portfolio_details`
--
ALTER TABLE `portfolio_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `replyInquiry`
--
ALTER TABLE `replyInquiry`
  ADD PRIMARY KEY (`repleyID`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `solution`
--
ALTER TABLE `solution`
  ADD PRIMARY KEY (`solutionID`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticketID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `career`
--
ALTER TABLE `career`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clientlogo`
--
ALTER TABLE `clientlogo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `innerpage_slider`
--
ALTER TABLE `innerpage_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `inquiryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `portfolio_details`
--
ALTER TABLE `portfolio_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `customerID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `replyInquiry`
--
ALTER TABLE `replyInquiry`
  MODIFY `repleyID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
