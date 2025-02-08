-- phpMyAdmin SQL Dump
-- version 5.2.1
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 03:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `e_recipe_system`;
USE `e_recipe_system`;

-- --------------------------------------------------------

-- Table structure for table `admin`
CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL AUTO_INCREMENT,
  `adminName` varchar(255) NOT NULL,
  `adminPass` varchar(255) NOT NULL,
  PRIMARY KEY (`adminID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (`adminID`, `adminName`, `adminPass`) VALUES
(1, 'Admin1', '$2y$10$1234567890abcdefgHIJKLMNOPQRSTUV'), 
(2, 'Admin2', '$2y$10$abcdefgHIJKLMNOPQRSTUV1234567890');

-- --------------------------------------------------------

-- Table structure for table `favourite`
CREATE TABLE `favourite` (
  `favID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `recipeID` int(11) DEFAULT NULL,
  PRIMARY KEY (`favID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `favourite` (`userID`, `recipeID`) VALUES
(1, 2),
(2, 4),
(3, 1),
(4, 3);

-- --------------------------------------------------------

-- Table structure for table `feedback`
CREATE TABLE `feedback` (
  `feedbackID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `recipeID` int(11) DEFAULT NULL,
  `ratingID` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `feedbackDate` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`feedbackID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `feedback` (`userID`, `recipeID`, `ratingID`, `comment`) VALUES
(1, 1, 1, 'Delicious and easy to make!'),
(2, 2, 2, 'The sauce was a bit too tangy for my taste.'),
(3, 3, 3, 'Grilled chicken came out juicy and flavorful.'),
(4, 4, 4, 'Loved this cake! Perfect texture and taste.');

-- --------------------------------------------------------

-- Table structure for table `meal_difficulty`
CREATE TABLE `meal_difficulty` (
  `diffID` int(11) NOT NULL AUTO_INCREMENT,
  `mealDiff` varchar(255) NOT NULL,
  PRIMARY KEY (`diffID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `meal_difficulty` (`mealDiff`) VALUES
('Easy'),
('Medium'),
('Hard');

-- --------------------------------------------------------

-- Table structure for table `meal_type`
CREATE TABLE `meal_type` (
  `typeID` int(11) NOT NULL AUTO_INCREMENT,
  `mealType` varchar(255) NOT NULL,
  PRIMARY KEY (`typeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `meal_type` (`mealType`) VALUES
('Breakfast'),
('Lunch'),
('Dinner'),
('Dessert');

-- --------------------------------------------------------

-- Table structure for table `rating`
CREATE TABLE `rating` (
  `ratingID` int(11) NOT NULL AUTO_INCREMENT,
  `ratingNum` int(11) NOT NULL,
  PRIMARY KEY (`ratingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `rating` (`ratingNum`) VALUES
(5),
(4),
(3),
(5);

-- --------------------------------------------------------

-- Table structure for table `recipe`
CREATE TABLE `recipe` (
  `recipeID` int(11) NOT NULL AUTO_INCREMENT,
  `recipeImg` varchar(255) DEFAULT NULL,
  `recipeName` varchar(255) NOT NULL,
  `recipeIngred` text NOT NULL,
  `recipeDesc` text NOT NULL,
  `recipeDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `recipeStatus` varchar(255) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `diffID` int(11) DEFAULT NULL,
  `typeID` int(11) DEFAULT NULL,
  PRIMARY KEY (`recipeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `recipe` (`recipeName`, `recipeIngred`, `recipeDesc`, `recipeStatus`, `userID`, `diffID`, `typeID`) VALUES
('Pancakes', 'Flour, Milk, Eggs, Sugar, Butter', 'Mix ingredients, cook on pan until golden brown.', 'Approved', 1, 1, 1),
('Spaghetti Bolognese', 'Spaghetti, Tomato Sauce, Ground Beef, Garlic', 'Cook spaghetti, prepare sauce, mix and serve.', 'Approved', 2, 2, 2),
('Grilled Chicken', 'Chicken, Olive Oil, Lemon, Spices', 'Marinate chicken, grill until cooked.', 'Approved', 3, 2, 3),
('Chocolate Cake', 'Flour, Cocoa Powder, Sugar, Eggs, Butter', 'Mix ingredients, bake at 180°C for 30 min.', 'Approved', 4, 3, 4);

-- --------------------------------------------------------

-- Table structure for table `registered_user`
CREATE TABLE `registered_user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userImg` varchar(255) DEFAULT NULL,
  `userName` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userPass` varchar(255) NOT NULL,
  `userBio` text DEFAULT NULL,
  `favID` int(11) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `registered_user` (`userName`, `userEmail`, `userPass`, `userBio`) VALUES
('John Doe', 'johndoe@example.com', '$2y$10$aDPlsa35QmcEtM7n/A6h9ecOiIEqgXw1yYZKNHhQzP2GZytbGqY72', 'Home cook, loves Italian food'),
('Alice Smith', 'alice.smith@example.com', '$2y$10$ZTTrXsFmsYVp9eXGXBqxw.zV5XJGvH1lM7Gk.V23mFOzvl3Po9OGi', 'Pastry chef'),
('Bob Brown', 'bob.brown@example.com', '$2y$10$fwC5cm9TYi3bROH9Cl5hZeqFTquTR2yk9nS5hMxfpaU9DdHbG4pmC', 'BBQ expert'),
('Emily White', 'emily.white@example.com', '$2y$10$6A0/bD7cd1LP6rVdXoONQO5zZf1AYMSL/xPtM8lGf59mSm4ayHeTC', 'Vegan food lover');

COMMIT;