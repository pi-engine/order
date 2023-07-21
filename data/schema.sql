
--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
                              `id` int NOT NULL,
                              `user_id` int NOT NULL,
                              `order_id` int NOT NULL,
                              `ordered_id` int DEFAULT NULL,
                              `quantity` int DEFAULT NULL,
                              `unit_price` int DEFAULT NULL,
                              `tax` int DEFAULT NULL,
                              `discount` int DEFAULT NULL,
                              `gift` int DEFAULT NULL,
                              `price` int DEFAULT NULL,
                              `status` int NOT NULL DEFAULT '1',
                              `information` json DEFAULT NULL,
                              `time_create` int NOT NULL DEFAULT '0',
                              `time_update` int NOT NULL DEFAULT '0',
                              `time_delete` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `order_order`
--

CREATE TABLE `order_order` (
                               `id` int NOT NULL,
                               `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
                               `user_id` int NOT NULL,
                               `entity_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'order',
                               `order_type` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
                               `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'waiting',
                               `subtotal` int DEFAULT NULL,
                               `tax` int DEFAULT NULL,
                               `discount` int DEFAULT NULL,
                               `gift` int DEFAULT NULL,
                               `total_amount` int DEFAULT NULL,
                               `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT 'cache',
                               `information` json DEFAULT NULL,
                               `time_create` int NOT NULL DEFAULT '0',
                               `time_update` int NOT NULL DEFAULT '0',
                               `time_delete` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_order`
--
ALTER TABLE `order_order`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_order`
--
ALTER TABLE `order_order`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;