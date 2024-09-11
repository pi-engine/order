-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2024 at 11:52 AM
-- Server version: 8.0.39-0ubuntu0.22.04.1
-- PHP Version: 8.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: 
--

-- --------------------------------------------------------

--
-- Table structure for table order_coupon
--

CREATE TABLE order_coupon (
                                id int NOT NULL,
                                code varchar(255) COLLATE utf8mb4_bin NOT NULL,
                                type varchar(255) COLLATE utf8mb4_bin DEFAULT 'percent',
                                value int NOT NULL,
                                rule json DEFAULT NULL,
                                count_limit int NOT NULL DEFAULT '0',
                                count_used int NOT NULL DEFAULT '0',
                                status int NOT NULL DEFAULT '0',
                                information json DEFAULT NULL,
                                time_create int NOT NULL DEFAULT '0',
                                time_start int NOT NULL DEFAULT '0',
                                time_update int NOT NULL DEFAULT '0',
                                time_expired int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table order_item
--

CREATE TABLE order_item (
                            id int NOT NULL,
                            user_id int NOT NULL,
                            order_id int NOT NULL,
                            ordered_id int DEFAULT NULL,
                            quantity int DEFAULT NULL,
                            unit_price int DEFAULT NULL,
                            tax int DEFAULT NULL,
                            discount int DEFAULT NULL,
                            coupon_id int DEFAULT NULL,
                            gift int DEFAULT NULL,
                            price int DEFAULT NULL,
                            status int NOT NULL DEFAULT '1',
                            information longtext COLLATE utf8mb4_bin,
                            time_create int NOT NULL DEFAULT '0',
                            time_update int NOT NULL DEFAULT '0',
                            time_delete int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table order_order
--
CREATE TABLE order_order (
                             id int NOT NULL,
                             slug varchar(255) COLLATE utf8mb4_bin NOT NULL,
                             user_id int NOT NULL,
                             entity_type varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT 'order',
                             order_type varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
                             status varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT 'waiting',
                             subtotal int DEFAULT NULL,
                             tax int DEFAULT NULL,
                             discount int DEFAULT NULL,
                             coupon_id int DEFAULT NULL,
                             gift varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
                             total_amount int DEFAULT NULL,
                             payment_method varchar(255) COLLATE utf8mb4_bin DEFAULT 'cache',
                             payment longtext COLLATE utf8mb4_bin,
                             information longtext COLLATE utf8mb4_bin,
                             time_create int NOT NULL DEFAULT '0',
                             time_update int NOT NULL DEFAULT '0',
                             time_delete int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table order_coupon
--
ALTER TABLE order_coupon
    ADD PRIMARY KEY (id),
  ADD UNIQUE KEY code (code);

--
-- Indexes for table order_item
--
ALTER TABLE order_item
    ADD PRIMARY KEY (id);

--
-- Indexes for table order_order
--
ALTER TABLE order_order
    ADD PRIMARY KEY (id);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table order_coupon
--
ALTER TABLE order_coupon
    MODIFY id int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table order_item
--
ALTER TABLE order_item
    MODIFY id int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table order_order
--
ALTER TABLE order_order
    MODIFY id int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;