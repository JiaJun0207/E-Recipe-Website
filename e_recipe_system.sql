-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2025 at 08:16 AM
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
-- Database: `e_recipe_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL,
  `adminName` varchar(255) NOT NULL,
  `adminPass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `adminName`, `adminPass`) VALUES
(1, 'Admin1', '$2y$10$1234567890abcdefgHIJKLMNOPQRSTUV'),
(2, 'Admin2', '$2y$10$abcdefgHIJKLMNOPQRSTUV1234567890'),
(3, 'admin', '$2y$10$/r03B4a1ITmPjFD622j/kegoFkHj5AcMJGtECvCXUHyMOf.uL5Li.');

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `favID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favourite`
--

CREATE TABLE `favourite` (
  `favID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `recipeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourite`
--

INSERT INTO `favourite` (`favID`, `userID`, `recipeID`) VALUES
(1, 1, 2),
(2, 2, 4),
(3, 3, 1),
(4, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `recipeID` int(11) DEFAULT NULL,
  `ratingID` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `feedbackDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackID`, `userID`, `recipeID`, `ratingID`, `comment`, `feedbackDate`) VALUES
(1, 1, 1, 1, 'Delicious and easy to make!', '2025-02-08 08:15:34'),
(3, 3, 3, 3, 'Grilled chicken came out juicy and flavorful.', '2025-02-08 08:15:34'),
(4, 4, 4, 4, 'Loved this cake! Perfect texture and taste.', '2025-02-08 08:15:34'),
(25, 7, 1, 4, 'try it', '2025-02-11 06:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `meal_difficulty`
--

CREATE TABLE `meal_difficulty` (
  `diffID` int(11) NOT NULL,
  `mealDiff` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_difficulty`
--

INSERT INTO `meal_difficulty` (`diffID`, `mealDiff`) VALUES
(1, 'Easy'),
(2, 'Medium'),
(3, 'Hard');

-- --------------------------------------------------------

--
-- Table structure for table `meal_type`
--

CREATE TABLE `meal_type` (
  `typeID` int(11) NOT NULL,
  `mealType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_type`
--

INSERT INTO `meal_type` (`typeID`, `mealType`) VALUES
(1, 'Breakfast'),
(2, 'Lunch'),
(3, 'Dinner'),
(4, 'Dessert'),
(5, 'just kidding abc');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `ratingID` int(11) NOT NULL,
  `ratingNum` int(11) NOT NULL,
  `ratingText` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`ratingID`, `ratingNum`, `ratingText`) VALUES
(1, 1, 'Poor'),
(2, 2, 'Fair'),
(3, 3, 'Good'),
(4, 4, 'Very Good'),
(5, 5, 'Excellent');

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `recipeID` int(11) NOT NULL,
  `recipeImg` varchar(255) DEFAULT NULL,
  `recipeName` varchar(255) NOT NULL,
  `recipeIngred` text NOT NULL,
  `recipeDesc` text NOT NULL,
  `recipeDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `recipeStatus` varchar(255) NOT NULL,
  `remark` text DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `diffID` int(11) DEFAULT NULL,
  `typeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipeID`, `recipeImg`, `recipeName`, `recipeIngred`, `recipeDesc`, `recipeDate`, `recipeStatus`, `remark`, `userID`, `diffID`, `typeID`) VALUES
(1, NULL, 'Pancakes', 'Flour, Milk, Eggs, Sugar, Butter', 'Mix ingredients, cook on pan until golden brown.', '2025-02-08 08:15:34', 'Approved', NULL, 1, 1, 1),
(2, NULL, 'Spaghetti Bolognese', 'Spaghetti, Tomato Sauce, Ground Beef, Garlic', 'Cook spaghetti, prepare sauce, mix and serve.', '2025-02-08 08:15:34', 'Approved', NULL, 2, 2, 2),
(3, NULL, 'Grilled Chicken', 'Chicken, Olive Oil, Lemon, Spices', 'Marinate chicken, grill until cooked.', '2025-02-08 08:15:34', 'Approved', NULL, 3, 2, 3),
(4, NULL, 'Chocolate Cake', 'Flour, Cocoa Powder, Sugar, Eggs, Butter', 'Mix ingredients, bake at 180°C for 30 min.', '2025-02-08 08:15:34', 'Rejected', NULL, 4, 3, 4),
(5, 'uploads/67a72e862b45a_1739009670.png', 'testing testing 123', '1 chicken', 'iuzgvahsedifnszd', '2025-02-08 10:14:30', 'Pending', NULL, 6, 3, 5),
(6, 'uploads/67a730e13ccf3_1739010273.jpg', '66666', '66666', '666666', '2025-02-08 10:24:33', 'Pending', NULL, 6, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `registered_user`
--

CREATE TABLE `registered_user` (
  `userID` int(11) NOT NULL,
  `userImg` varchar(255) DEFAULT NULL,
  `userName` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userPass` varchar(255) NOT NULL,
  `userBio` text DEFAULT NULL,
  `favID` int(11) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `userStatus` enum('Active','Inactive','Banned') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registered_user`
--

INSERT INTO `registered_user` (`userID`, `userImg`, `userName`, `userEmail`, `userPass`, `userBio`, `favID`, `reset_token`, `userStatus`) VALUES
(1, NULL, 'John Doe', 'johndoe@example.com', '$2y$10$aDPlsa35QmcEtM7n/A6h9ecOiIEqgXw1yYZKNHhQzP2GZytbGqY72', 'Home cook, loves Italian food', NULL, NULL, 'Active'),
(2, NULL, 'Alice Smith', 'alice.smith@example.com', '$2y$10$ZTTrXsFmsYVp9eXGXBqxw.zV5XJGvH1lM7Gk.V23mFOzvl3Po9OGi', 'Pastry chef', NULL, NULL, 'Active'),
(3, NULL, 'Bob Brown', 'bob.brown@example.com', '$2y$10$fwC5cm9TYi3bROH9Cl5hZeqFTquTR2yk9nS5hMxfpaU9DdHbG4pmC', 'BBQ expert', NULL, NULL, 'Active'),
(4, NULL, 'Emily White', 'emily.white@example.com', '$2y$10$6A0/bD7cd1LP6rVdXoONQO5zZf1AYMSL/xPtM8lGf59mSm4ayHeTC', 'Vegan food lover', NULL, NULL, 'Active'),
(6, 'uploads/ikun.jpeg', 'kunkun', 'yongqi218@gmail.com', '$2y$10$J7HoVucKMA9hZwyZUNkMtOofcSCJkUpFCKkj0ZiB82AAcUfVrZAji', 'kunkun', NULL, NULL, 'Active'),
(7, 'uploads/2b1502f45463fbad7fde27fa54a59912.jpg', 'JiaJun', 'chanjiajun321@gmail.com', '$2y$10$9g0odYbX4/QpfqU0OX7ZoOuvNJYdtLcarSM7IO7Fotdkunjt.tK4u', '这家伙很懒什么也没留下~', NULL, NULL, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`favID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `recipeID` (`recipeID`);

--
-- Indexes for table `favourite`
--
ALTER TABLE `favourite`
  ADD PRIMARY KEY (`favID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackID`);

--
-- Indexes for table `meal_difficulty`
--
ALTER TABLE `meal_difficulty`
  ADD PRIMARY KEY (`diffID`);

--
-- Indexes for table `meal_type`
--
ALTER TABLE `meal_type`
  ADD PRIMARY KEY (`typeID`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`ratingID`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`recipeID`);

--
-- Indexes for table `registered_user`
--
ALTER TABLE `registered_user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `favID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `favourite`
--
ALTER TABLE `favourite`
  MODIFY `favID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `meal_difficulty`
--
ALTER TABLE `meal_difficulty`
  MODIFY `diffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `meal_type`
--
ALTER TABLE `meal_type`
  MODIFY `typeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `ratingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `recipeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `registered_user`
--
ALTER TABLE `registered_user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `registered_user` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`recipeID`) REFERENCES `recipe` (`recipeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
