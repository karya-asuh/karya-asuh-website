-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Dec 13, 2024 at 02:34 PM
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
-- Database: `karya-asuh-test`
--

-- --------------------------------------------------------

--
-- Table structure for table `creations`
--

CREATE TABLE `creations` (
  `creation_id` char(39) NOT NULL,
  `panti_id` char(39) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` text DEFAULT NULL,
  `min_price` int(11) NOT NULL,
  `type` enum('video','image') NOT NULL,
  `creation_file` varchar(36) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `creations`
--

INSERT INTO `creations` (`creation_id`, `panti_id`, `name`, `desc`, `min_price`, `type`, `creation_file`, `created_at`, `updated_at`) VALUES
('CR-5a7bc267-b888-11ef-818e-ac74b135e55c', 'PA-24aa89e7-b888-11ef-818e-ac74b135e55c', 'Hello I am fish', 'fishes', 100000, 'video', NULL, '2024-12-12 12:55:24', '2024-12-12 12:55:24'),
('CR-7d69d17e-b884-11ef-818e-ac74b135e55c', 'PA-404865d5-b884-11ef-818e-ac74b135e55c', 'Karya anak soleha', 'yang buat anak soleha', 20000, 'image', NULL, '2024-12-12 12:27:45', '2024-12-12 12:27:45');

--
-- Triggers `creations`
--
DELIMITER $$
CREATE TRIGGER `set_creation_before_insert` BEFORE INSERT ON `creations` FOR EACH ROW BEGIN
    -- Automatically set the creation_id with a prefix if not provided
    SET NEW.creation_id = CONCAT('CR-', UUID());
    -- Automatically reject inserted creation that inserted by non panti
    IF NOT EXISTS (
        SELECT 1 
        FROM users 
        WHERE user_id = NEW.panti_id AND role = 'panti'
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid panti_id: Must reference a user with role "panti"';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `panti_details`
--

CREATE TABLE `panti_details` (
  `panti_id` char(39) NOT NULL,
  `fund` int(19) UNSIGNED NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `panti_details`
--

INSERT INTO `panti_details` (`panti_id`, `fund`, `location`, `created_at`, `updated_at`) VALUES
('PA-24aa89e7-b888-11ef-818e-ac74b135e55c', 120000, NULL, '2024-12-12 12:53:54', '2024-12-12 12:57:47'),
('PA-404865d5-b884-11ef-818e-ac74b135e55c', 60000, NULL, '2024-12-12 12:26:02', '2024-12-12 12:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` char(39) NOT NULL,
  `panti_id` char(39) NOT NULL,
  `donor_id` char(39) NOT NULL,
  `creation_id` char(39) NOT NULL,
  `price` int(19) UNSIGNED NOT NULL,
  `status` enum('On Payment','Success','Failed') NOT NULL DEFAULT 'On Payment',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `panti_id`, `donor_id`, `creation_id`, `price`, `status`, `created_at`, `updated_at`) VALUES
('TR-7e41b4b2-b889-11ef-818e-ac74b135e55c', 'PA-24aa89e7-b888-11ef-818e-ac74b135e55c', 'DO-28ba6a0e-b884-11ef-818e-ac74b135e55c', 'CR-5a7bc267-b888-11ef-818e-ac74b135e55c', 100000, 'On Payment', '2024-12-13 13:22:21', '2024-12-13 13:22:21'),
('TR-84ea2bab-b887-11ef-818e-ac74b135e55c', 'PA-404865d5-b884-11ef-818e-ac74b135e55c', 'DO-28ba6a0e-b884-11ef-818e-ac74b135e55c', 'CR-7d69d17e-b884-11ef-818e-ac74b135e55c', 80000, 'Success', '2024-12-13 13:22:21', '2024-12-13 13:22:21'),
('TR-98c47d0f-b888-11ef-818e-ac74b135e55c', 'PA-24aa89e7-b888-11ef-818e-ac74b135e55c', 'DO-28ba6a0e-b884-11ef-818e-ac74b135e55c', 'CR-5a7bc267-b888-11ef-818e-ac74b135e55c', 120000, 'Success', '2024-12-13 13:22:21', '2024-12-13 13:22:21');

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `set_transaction_after_update` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
	IF EXISTS (
        SELECT 1 
        FROM users 
        WHERE user_id = OLD.panti_id AND role = 'panti' && NEW.status = 'Success'
    ) THEN
    	UPDATE panti_details SET panti_details.fund = 			panti_details.fund + OLD.price
		WHERE panti_details.panti_id = OLD.panti_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_transaction_before_insert` BEFORE INSERT ON `transactions` FOR EACH ROW BEGIN
    -- Only set the user_id if it's not explicitly provided
    SET NEW.transaction_id = CONCAT('TR-', UUID());
    
    -- Fetch and assign the panti_id based on creation_id
    SELECT panti_id
    INTO @panti_id
    FROM creations
    WHERE creation_id = NEW.creation_id
    LIMIT 1;

    -- Assign the fetched panti_id to NEW.panti_id
    SET NEW.panti_id = @panti_id;

	-- Check if price is higher than min_price for creations
	IF NOT EXISTS (
    	SELECT 1 FROM creations WHERE creation_id = 			NEW.creation_id AND min_price <= NEW.price
	) THEN
		SIGNAL SQLSTATE '45000'
       	SET MESSAGE_TEXT = 'Invalid price: price must be \t\tequal more than min_price';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_transaction_before_update` BEFORE UPDATE ON `transactions` FOR EACH ROW BEGIN
	IF OLD.status = 'Success' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Cannot modify a transaction with status Success';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` char(39) NOT NULL,
  `username` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` char(72) NOT NULL,
  `role` enum('donor','panti','admin') NOT NULL DEFAULT 'donor',
  `user_image` varchar(36) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `name`, `email`, `password`, `role`, `user_image`, `created_at`, `updated_at`) VALUES
('AD-5665835f-b884-11ef-818e-ac74b135e55c', 'chicken', 'chicken man', 'chicken@gmail.com', 'chicken', 'admin', NULL, '2024-12-12 12:26:39', '2024-12-12 12:26:39'),
('AD-dc9b2170-b887-11ef-818e-ac74b135e55c', 'steak', 'steak man', 'steak@gmail.com', 'steak', 'admin', NULL, '2024-12-12 12:51:53', '2024-12-12 12:51:53'),
('DO-28ba6a0e-b884-11ef-818e-ac74b135e55c', 'pizza', 'pizza man', 'pizza@gmail.com', 'pizza', 'donor', NULL, '2024-12-12 12:25:23', '2024-12-12 12:25:23'),
('PA-24aa89e7-b888-11ef-818e-ac74b135e55c', 'fish', 'fish man', 'fish@gmail.com', 'fish', 'panti', NULL, '2024-12-12 12:53:54', '2024-12-12 12:53:54'),
('PA-404865d5-b884-11ef-818e-ac74b135e55c', 'burger', 'burger man', 'burger@gmail.com', 'burger', 'panti', NULL, '2024-12-12 12:26:02', '2024-12-12 12:26:02');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `set_user_after_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    IF (NEW.role = 'panti') THEN
        INSERT INTO `panti_details` (`panti_id`) VALUES (NEW.user_id);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_user_before_insert` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    -- Only set the user_id if it's not explicitly provided
    IF NEW.role = "donor" THEN
    	SET NEW.user_id = CONCAT('DO-', UUID());
    ELSEIF NEW.role = "panti" THEN
    	SET NEW.user_id = CONCAT('PA-', UUID());
    ELSEIF NEW.role = "admin" THEN
    	SET NEW.user_id = CONCAT('AD-', UUID());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `withdraw_id` char(39) NOT NULL,
  `panti_id` char(39) NOT NULL,
  `admin_id` char(39) DEFAULT NULL,
  `payout_fund` int(19) UNSIGNED NOT NULL,
  `detail` text DEFAULT NULL,
  `status` enum('Request For Withdraw','Withdrawn') NOT NULL DEFAULT 'Request For Withdraw',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `withdraws`
--

INSERT INTO `withdraws` (`withdraw_id`, `panti_id`, `admin_id`, `payout_fund`, `detail`, `status`, `created_at`, `updated_at`) VALUES
('WD-aae6b87b-b887-11ef-818e-ac74b135e55c', 'PA-404865d5-b884-11ef-818e-ac74b135e55c', 'AD-5665835f-b884-11ef-818e-ac74b135e55c', 20000, 'buy me a pizza, I am poor', 'Withdrawn', '2024-12-12 12:50:29', '2024-12-12 12:50:57');

--
-- Triggers `withdraws`
--
DELIMITER $$
CREATE TRIGGER `set_withdraw_before_insert` BEFORE INSERT ON `withdraws` FOR EACH ROW BEGIN
    -- Automatically set the withdraw_id with a prefix if not provided
    SET NEW.withdraw_id = CONCAT('WD-', UUID());
    
    -- Automatically reject inserted creation that is inserted by non-panti
    IF NOT EXISTS (
        SELECT 1 
        FROM users 
        WHERE user_id = NEW.panti_id AND role = 'panti'
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid panti_id: Must reference a user with role "panti"';
    ELSE
    	UPDATE panti_details SET panti_details.fund = 			panti_details.fund - NEW.payout_fund
		WHERE panti_details.panti_id = NEW.panti_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_withdraw_before_update` BEFORE UPDATE ON `withdraws` FOR EACH ROW BEGIN
	IF OLD.status = 'Withdrawn' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Cannot modify a transaction with status Withdrawn';
    END IF;
    
	IF NEW.status = 'Withdrawn' THEN
        -- Verify that the user updating the data has the role 'admin'
        IF NOT EXISTS (
            SELECT 1 
            FROM users
            WHERE user_id = NEW.admin_id AND role = 'admin'
        ) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Only users with role "admin" can update the status to "Withdrawn"';
        END IF;
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `creations`
--
ALTER TABLE `creations`
  ADD PRIMARY KEY (`creation_id`),
  ADD KEY `panti_id` (`panti_id`);

--
-- Indexes for table `panti_details`
--
ALTER TABLE `panti_details`
  ADD UNIQUE KEY `panti_id` (`panti_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `transactions_ibfk_2` (`donor_id`),
  ADD KEY `transactions_ibfk_3` (`creation_id`),
  ADD KEY `transactions_ibfk_1` (`panti_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`withdraw_id`),
  ADD KEY `withdraws_ibfk_1` (`panti_id`),
  ADD KEY `withdraws_ibfk_2` (`admin_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `creations`
--
ALTER TABLE `creations`
  ADD CONSTRAINT `creations_ibfk_1` FOREIGN KEY (`panti_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `panti_details`
--
ALTER TABLE `panti_details`
  ADD CONSTRAINT `fk_panti` FOREIGN KEY (`panti_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`panti_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`creation_id`) REFERENCES `creations` (`creation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD CONSTRAINT `withdraws_ibfk_1` FOREIGN KEY (`panti_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `withdraws_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
