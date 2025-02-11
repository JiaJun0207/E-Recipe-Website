-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2025 at 10:52 AM
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

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`favID`, `userID`, `recipeID`, `created_at`) VALUES
(19, 7, 7, '2025-02-11 08:17:22'),
(20, 7, 8, '2025-02-11 09:51:57'),
(21, 7, 10, '2025-02-11 09:52:05'),
(22, 7, 12, '2025-02-11 09:52:08'),
(23, 7, 13, '2025-02-11 09:52:11'),
(24, 7, 14, '2025-02-11 09:52:14');

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
(4, 4, 4, 4, 'Loved this cake! Perfect texture and taste.', '2025-02-08 08:15:34');

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
  `recipeStatus` enum('Pending','Approved','Rejected','Re-Submitted') NOT NULL,
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
(6, 'uploads/67a730e13ccf3_1739010273.jpg', '66666', '66666', '666666', '2025-02-08 10:24:33', 'Pending', NULL, 6, 2, 5),
(7, 'uploads/67aafd6043af1_1739259232.jpg', 'Simple Green Salad', 'balsamic vinegar\r\nolive oil\r\nmaple syrup\r\ngarlic powder\r\nsalt + pepp', 'Every table needs this Simple Green Salad! Fresh spring greens drizzled with a maple balsamic dressing and sprinkled with whatever crunchies your salad-loving heart desires.\r\nSince we really cannot take any credit for the spring greens, they’re just doing their thing, let’s talk about this dressing. It’s got all the elements you need to really make it feel special even though it could not be easier and it’s just a few jar-shakes away from being yours all day every day. Deep rich balsamic, a hint of maple sweetness, a subtle little garlic bite. Just get everything together in a jar and then that magnificence is going to be at the ready for you in your fridge all week long.', '2025-02-11 07:33:52', 'Approved', NULL, 7, 1, 2),
(8, 'uploads/67aaff1e1fd62_1739259678.jpg', 'Tiramisu', 'Espresso \r\nDark rum \r\nMascarpone\r\nZabaglione\r\nSugar\r\nCream \r\nVanilla extract \r\nLadyfingers ', 'Tiramisu is a classic Italian dessert of ladyfingers soaked in bold espresso and rum, enveloped in layers of thick, velvety mascarpone and custard cream. It is simply decadent, and oh-so elegant. The complex flavor of tiramisu is a product of the delicate soaked ladyfingers and luscious cream layer. The cream layer is composed of mascarpone, rum, vanilla-scented whipped cream, and custard. A classic tiramisu recipe calls for raw egg yolks. Since I know some are hesitant to eat raw eggs, I lightly and carefully cook the custard (also called zabaglione) until thick and creamy, keeping my recipe true to the classic flavor and using Italian pastry methods.\r\nFor a gorgeous presentation, be sure to make the tiramisu recipe a night in advance, as it needs several hours to set properly. A chilled tiramisu slices like a dream into neat squares! And if you’re looking for more no-bake desserts, then try my éclair cake recipe, Nanaimo bars recipe, or easy Oreo pie!\r\n1. Combine the espresso and dark rum in a medium bowl.\r\n\r\n2. To a large bowl, add the mascarpone cheese along with the remaining rum. Whisk together or beat with a hand mixer. Set aside for now.\r\n3. Make the custard (you’re basically making a zabaglione here). If you have a double-boiler, combine the egg yolks and granulated sugar in the top. If not, whisk them together in a heat-proof medium mixing bowl. Place the bowl over a pot of simmering water, ensuring that the bowl doesn’t touch the water. Continue to whisk until the sugar has dissolved. Once the egg yolk mixture is pale yellow and thickened, it is ready. This will take 5 to 8 minutes.\r\n\r\n4. Pour the egg yolk mixture into the mascarpone and whisk until combined. Refrigerate for 15 minutes.\r\n5. Combine the heavy cream and vanilla in a large mixing with an electric mixer or the bowl of a stand mixer fitted with the whisk attachment. Beat on medium until stiff peaks form (3 to 5 minutes). Keep an eye on the cream as if it is over-beaten, it will turn into butter! Fold the whipped cream into the cold mascarpone mixture.\r\n6. Prepare the ladyfingers by dipping each side briefly into the espresso and rum mixture. Each side only needs to be dipped for a second or two, otherwise, the cookies will absorb too much liquid and become soggy. Arrange the lady fingers in a single layer in a 9×13-inch dish. You may need to break one row of ladyfingers so they fit. Try not to leave any gaps.\r\n7. Add half the mascarpone mixture on top of the ladyfingers and smooth it out using a spatula. Dip more ladyfingers in the espresso mixture and arrange them in a layer on top of the mascarpone cream layer.\r\n\r\n8. Spoon the rest of the mascarpone mixture on top of the second layer of ladyfingers and smooth it out. So you will end up with a layer of ladyfingers at the bottom, then a layer of mascarpone cream, then another layer of cookies, and one more layer of mascarpone cream. Dust the tiramisu recipe generously with unsweetened cocoa powder and chill overnight. Allowing your tiramisu time to set will give you neat layers and make slicing it much easier.', '2025-02-11 07:41:18', 'Approved', NULL, 7, 3, 4),
(10, 'uploads/67ab1a417f057_1739266625.png', 'Crispy Fried Chicken Burger', 'Chicken choices:\r\n▢2 x 250g/8oz chicken breast (I use this)\r\n▢4 boneless thigh fillets\r\n\r\nMarinade:\r\n▢3/4 cup buttermilk (Note 1 for sub)\r\n▢1 1/2 tsp cooking / kosher salt\r\n▢1 large egg\r\n\r\nBurgers:\r\n▢4 soft buns , split and lightly toasted (mine are 10cm/4″)\r\n▢2 – 3 cups iceberg lettuce , finely sliced (or other leafy greens of choice)\r\n▢2 tomatoes , sliced (optional, sometimes I do)\r\n▢2 gherkins / dill pickles , sliced on an angle\r\n\r\nGarlic mayo:\r\n▢2/3 cup mayonnaise , preferably whole egg\r\n▢3/4 tsp garlic , finely grated (1 clove)\r\n\r\nCrunchy coating:\r\n▢3/4 cup flour , plain / all purpose\r\n▢1/4 cup corn flour / cornstarch\r\n\r\nFried chicken seasoning:\r\n▢1/4 tsp celery salt (or heaped 1/8 tsp celery seed)\r\n▢1/2 tsp sweet paprika\r\n▢1/8 tsp cayenne pepper (inc to 1/2 tsp for spicy ZINGER burger!)\r\n▢1/2 tsp onion powder\r\n▢1 tsp garlic powder\r\n▢1/4 tsp mustard powder\r\n▢1/4 tsp ginger powder\r\n▢1/2 tsp dried thyme\r\n▢1/2 tsp dried oregano\r\n▢1/2 tsp cooking salt / kosher salt\r\n▢1 1/2 tsp black pepper\r\n\r\nTo fry:\r\n▢3 cups vegetable oil (or canola or peanut) (2.5cm/1″ depth in cooking vessel)', 'Our Fried Chicken Burger is way crispier than yours, the chicken is way juicier and it tastes way better. The trick is to mix a bit of marinade into the flour coating – this creates lumps that fries up into an awesome ultra crunchy crust. ', '2025-02-11 09:37:05', 'Approved', NULL, 7, 2, 3),
(11, 'uploads/67ab1c5b7cfb5_1739267163.png', 'Homemade Low-Sugar Bubble Milk Tea', '2 bags of green tea (or any tea flavor of your choice)\r\n2 servings of tapioca pearls (about ⅓ cup per serving)\r\nMilk to taste\r\nSugar to taste', 'Today I want to share the recipe of one of my favorite beverages of all-time, a Homemade Low-Sugar Bubble Milk Tea!\r\n\r\nBubble tea/boba (珍珠奶茶) is a popular beverage that originated from Taiwan, and the yummy tapioca pearls that typically sit at the bottom of the tea are the reason why it’s called ‘bubble tea’.\r\nI often find the bubble tea sold at the bubble tea shops are too sweet for me (even after asking for 50% sugar), which gives this beverage a bad rep for ‘unhealthy’ due to the high sugar level. Now that we are making it ourselves at home, we can adjust the sugar amount how ever we want (for me, it’s less sweet), which is a huge plus!', '2025-02-11 09:46:03', 'Approved', NULL, 7, 1, 4),
(12, 'uploads/67ab1cf54aaaf_1739267317.png', 'Lamb, turnip & celeriac hotpot', '2 ½ tbsp vegetable oil\r\n6 lamb neck\r\nfillets (1.25kg/2lb 12oz), cut into large chunks or halved\r\n9 shallots\r\npeeled and kept whole\r\n3 tbsp plain flour\r\n1 garlic bulb\r\n1l lamb\r\nor beef stock\r\n3 merguez sausages\r\nsliced\r\n2 large turnips\r\n(about 500g/1lb 2oz), peeled and very thinly sliced\r\n1 celeriac\r\n(about 500g/1lb 2oz), peeled and very thinly sliced\r\nmashed potato\r\nand pickled cabbage, to serve\r\nFor the spice blend\r\n6 cloves\r\n3 star anise\r\n1 ½ tsp rosemary\r\n3 garlic cloves\r\n1 tbsp Dijon mustard', 'Lamb neck is a hugely underused cut, topped with sliced, seasonal root veg, this winter staple is ideal for a budget-conscious family meal or dinner party main', '2025-02-11 09:48:37', 'Approved', NULL, 7, 3, 3),
(13, 'uploads/67ab1d53d2dd7_1739267411.png', 'Cauli-kale sausage bake', '680g cauliflower florets\r\n180g sliced kale\r\n1 tbsp olive oil\r\n400g sausages\r\nmeat squeezed out\r\n700g cheese sauce\r\n(shop-bought is fine, or see a recipe in our goes well with section)\r\n100g cheddar\r\ngrated\r\nmixed leaves\r\nto serve (optional)', 'This cheap, crowd-pleasing meal is easy, hearty fare. This needs hardly any prep and it\'s pure comfort food, with a good helping of veg and hot bubbling cheese', '2025-02-11 09:50:11', 'Approved', NULL, 7, 2, 2),
(14, 'uploads/67ab1daa86ee1_1739267498.png', 'Summer-in-winter chicken', '1 tbsp olive oil\r\n4 boneless skinless chicken breasts\r\n200g pack cherry tomatoes\r\n3 tbsp pesto\r\n3 tbsp crème fraîche\r\n(half fat is fine)\r\nfresh basil\r\nif you have it', 'Pining for summer? This simply seasoned chicken fried with beautiful, ripe, cherry tomatoes in a creamy sauce is guaranteed to hit the spot. Add a dollop of pesto for an extra layer of nutty flavours', '2025-02-11 09:51:38', 'Approved', NULL, 7, 2, 2);

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
(7, 'uploads/2b1502f45463fbad7fde27fa54a59912.jpg', 'JiaJun', 'chanjiajun321@gmail.com', '$2y$10$9g0odYbX4/QpfqU0OX7ZoOuvNJYdtLcarSM7IO7Fotdkunjt.tK4u', '这家伙很懒什么也没留下~', NULL, NULL, 'Active'),
(8, 'uploads/83b0cf4ffe077b4744d6788f6e2e47eb--fate-zero-fate-stay-night.jpg', 'JiaLun', 'chanjialun321@gmail.com', '$2y$10$lrv7Z0rEig5YAV23t07/2eUGT9OP/mJn/iSkmnRBW8Ndr7AtUrE1e', NULL, NULL, NULL, 'Active');

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
  MODIFY `favID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `recipeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `registered_user`
--
ALTER TABLE `registered_user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
