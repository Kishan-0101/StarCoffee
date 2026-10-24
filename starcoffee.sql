-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 09:48 AM
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
-- Database: `starcoffee`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(4) NOT NULL,
  `a_email` varchar(50) DEFAULT NULL,
  `a_pwd` varchar(255) NOT NULL,
  `a_time` varchar(100) DEFAULT NULL,
  `a_status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

-- No admin records are seeded; add only synthetic data for demos.

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(4) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `category_image` longtext DEFAULT NULL,
  `category_time` varchar(100) NOT NULL,
  `category_status` int(1) NOT NULL DEFAULT 1,
  `category_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_image`, `category_time`, `category_status`, `category_description`) VALUES
(1, 'Americano', 'cat_688750b706a2f3.05494694.png', '1753698487', 1, 'Discover the bold simplicity of our Americano – smooth espresso softened with hot water, creating a rich yet light coffee experience. Perfect for those who love strong coffee with a refined taste.'),
(2, 'Cappuccino', 'cat_688755f81cbd10.00298123.png', '1753699832', 1, 'Cappuccino is a timeless favorite - a bold shot of espresso blended with steamed milk and topped with a thick, velvety foam. Every cup delivers the perfect harmony of strength and creaminess.'),
(3, 'Latte', 'cat_6888fbf48bf395.20968452.png', '1753807860', 1, 'Savor our classic Latte – velvety steamed milk embraced by a smooth espresso shot, finished with a delicate layer of foam. Creamy, balanced, and effortlessly comforting.'),
(4, 'Mocha', 'cat_6888fcb9d7c141.40385173.png', '1753808057', 1, 'Delight in our Mocha – rich espresso melded with steamed milk and luscious chocolate syrup, crowned with creamy foam. A decadent blend of coffee and cocoa in every sip.'),
(5, 'Espresso', 'cat_6888fd529cc187.80946009.png', '1753808210', 1, 'Espresso is the purest form of coffee – a concentrated shot brewed under high pressure, delivering bold flavor, rich aroma, and a smooth crema on top. It’s the foundation of all great coffee drinks.'),
(6, 'Iced Coffee', 'cat_6888fde3bef6f6.18638325.png', '1753808355', 1, 'Chill out with our Iced Coffee – smooth cold-brewed or chilled drip coffee poured over ice for a crisp, refreshing sip. Perfectly invigorating and endlessly customizable to suit your taste.'),
(7, 'Frappe', 'cat_6888fe88434832.69726061.png', '1753808520', 1, 'Beat the heat with our refreshing Frappé – a delightfully creamy iced coffee blend topped with signature foam. Cool, smooth, and perfectly sweet for your summer coffee cravings.'),
(8, 'Flat White', 'cat_6888ff3762c913.89006776.png', '1753808695', 1, 'Flat White is a creamy blend of rich espresso and silky steamed milk, finished with a thin layer of microfoam. With its smooth texture and balanced flavor, it’s the perfect choice for those who love a softer, velvety coffee experience.'),
(9, 'Cold Brew', 'cat_6888ff8eeb8230.13877282.png', '1753808782', 1, 'Cold Brew is coffee reimagined – steeped slowly in cold water for hours to create a naturally smooth, bold, and less acidic flavor. Refreshing and energizing, it’s the perfect choice for hot days and cool vibes.'),
(10, 'Black Coffee', 'cat_6889003e0a0b30.73514854.png', '1753808958', 1, 'Black Coffee is pure and unfiltered – freshly brewed without milk or cream, offering a bold, robust, and aromatic taste. It’s the perfect choice for true coffee enthusiasts who enjoy coffee in its most authentic form.'),
(11, 'Irish Coffee', 'cat_688900e4424711.13763238.png', '1753809124', 1, 'Warm up with our Irish Coffee – rich hot coffee blended with Irish whiskey and a touch of sweetness, topped with a velvety layer of cream. A classic, comforting sip with a spirited kick.'),
(12, 'Cortado', 'cat_6894b1d6e4c5d1.51002998.png', '1754575318', 1, 'Cortado is a smooth balance of espresso and warm milk, crafted in equal parts to cut through the coffee’s intensity without losing its bold flavor. It’s a simple yet refined choice for coffee lovers who appreciate harmony in every sip.'),
(13, 'Doppio', 'cat_68aaa0314517d7.47508796.png', '1756012593', 1, 'Doppio is a double shot of espresso, rich in aroma and full of intensity. Twice the strength, twice the energy – it’s crafted for those who love their coffee bold and powerful.'),
(14, 'Macchiato', 'cat_68ab0688e313b4.83702969.png', '1756038792', 1, 'Elevate your coffee moment with our Macchiato – a bold espresso “stained” with just a dollop of steamed milk foam. Intense flavor, subtly softened for the perfect pick-me-up.'),
(15, 'Affogato', 'cat_68ab076ebdee35.03558197.png', '1756039022', 1, 'Affogato is the ultimate fusion of dessert and coffee – a scoop of creamy vanilla ice cream drenched with a hot shot of espresso. Sweet, bold, and indulgent, it’s the perfect treat for coffee lovers who crave something unique.');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `preferred_contact` varchar(10) NOT NULL DEFAULT 'email',
  `is_consent` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

-- No contacts records are seeded; add only synthetic data for demos.

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_key` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(32) NOT NULL,
  `customer_address` text NOT NULL,
  `payment_method` enum('cod','upi') NOT NULL,
  `upi_id` varchar(100) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

-- No orders records are seeded; add only synthetic data for demos.

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(4) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size_label` varchar(32) DEFAULT NULL,
  `oz` int(11) DEFAULT NULL,
  `syrup_pumps` int(11) DEFAULT NULL,
  `ice` varchar(32) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

-- No order_items records are seeded; add only synthetic data for demos.

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `p_id` int(4) NOT NULL,
  `p_category` varchar(50) DEFAULT NULL,
  `p_name` varchar(255) DEFAULT NULL,
  `p_price` bigint(100) DEFAULT NULL,
  `p_image` longtext DEFAULT NULL,
  `p_short_description` longtext DEFAULT NULL,
  `p_time` varchar(100) DEFAULT NULL,
  `p_status` int(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`p_id`, `p_category`, `p_name`, `p_price`, `p_image`, `p_short_description`, `p_time`, `p_status`) VALUES
(1, '3', 'Vanilla Latte', 345, '1756643608_popular-coffee-1.png', 'Delight in our Vanilla Latte – a comforting mix of rich espresso, steamed milk, and a hint of sweet vanilla, creating a creamy and aromatic coffee experience.', '1756019151', 1),
(2, '9', 'Classic Coffee', 299, '1756019335_popular-coffee-2.png', 'Savor the timeless taste of our Classic Coffee – freshly brewed to deliver a bold, rich flavor that captures the true essence of coffee in every cup.', '1756019335', 1),
(3, '4', 'Mocha Coffee', 395, '1756019429_popular-coffee-3.png', 'Indulge in our Mocha Coffee – a rich blend of bold espresso, smooth milk, and decadent chocolate, perfectly balanced for coffee and cocoa lovers alike.', '1756019429', 1),
(7, '4', 'Iced Mocha Coffee', 395, '1756631057_products-coffee-1.png', 'Chill out with our Iced Coffee Mocha – a refreshing blend of bold espresso, creamy milk, rich chocolate, and ice, crafted to satisfy both coffee and chocolate lovers.', '1756631057', 1),
(8, '9', 'Coffee With Cream', 325, '1756631185_products-coffee-2.png', 'Experience the smooth richness of our Coffee with Cream – freshly brewed coffee blended with silky cream for a perfectly balanced, velvety taste in every sip.', '1756631185', 1),
(9, '2', 'Cappuccino Coffee', 345, '1756631287_products-coffee-3.png', 'Our Cappuccino Coffee is a timeless classic – a bold shot of espresso topped with steamed milk and a thick, frothy foam for the perfect balance of strength and creaminess.', '1756631287', 1),
(10, '9', 'Coffee With Milk', 325, '1756631385_products-coffee-4.png', 'Enjoy the comforting simplicity of our Coffee with Milk – freshly brewed coffee gently blended with warm, creamy milk for a smooth and satisfying flavor.', '1756631385', 1),
(11, '6', 'Classic Iced Coffee', 299, '1756631480_products-coffee-5.png', 'Refresh yourself with our Classic Iced Coffee – freshly brewed coffee served over ice, delivering a smooth, bold taste with a crisp and cooling finish.', '1756631480', 1),
(12, '7', 'Iced Coffee Frappe', 395, '1756643649_products-coffee-6.png', 'Treat yourself to our Iced Coffee Frappe – a chilled blend of rich coffee, creamy milk, and ice, whipped to perfection for a smooth, frothy, and refreshing delight.', '1756631572', 1),
(13, '10', 'Blonde Roast – Veranda Blend', 299, '1761214459_Blonde_Roast.png', 'A smooth, subtly sweet light roast crafted from Latin American beans, the Veranda Blend® offers delicate layers of soft cocoa and lightly toasted nuts. Its mellow flavor and gentle finish make it perfect for those who enjoy a lighter, more approachable cup of coffee that still delivers rich aroma and warmth in every sip.', '1761214459', 1),
(14, '10', 'Medium Roast – Pike Place Roast', 299, '1761219765_Medium_Roast.png', 'A smooth and well-balanced medium roast, Pike Place® Roast features rich chocolate and toasted nut notes. Its approachable flavor makes it perfect for daily enjoyment, whether brewed fresh at home or savored in a café.', '1761219765', 1),
(15, '10', 'Medium Roast – Guatemala Casi Cielo', 399, '1761219997_Guatemala_Casi_Cielo.png', 'A bright and flavorful medium roast, Guatemala Casi Cielo® offers sweet citrus and floral notes with a hint of cocoa. Grown at high altitudes, this coffee delivers a clean, vibrant cup for those who enjoy a refined and aromatic coffee experience.', '1761219997', 1),
(16, '10', 'Dark Roast – Caffe Verona', 345, '1761220330_Dark_roast.png', 'A rich and full-bodied dark roast, Caffè Verona® blends smooth chocolatey notes with a hint of caramel. Its bold, robust flavor makes it perfect for dark roast lovers looking for a comforting and indulgent coffee experience.', '1761220330', 1),
(17, '9', 'Caffe Misto', 345, '1761220603_Caffe_Misto.png', 'A perfect balance of freshly brewed coffee and steamed milk, Caffè Misto offers a smooth, mellow flavor with a gentle aroma. Its creamy texture and subtle coffee notes make it a comforting choice for any time of day, ideal for those who enjoy a lighter, refined coffee experience without added sweetness.', '1761220603', 1),
(18, '5', 'Iced Shaken Espresso', 395, '1761221436_Shaken_Espresso.png', 'A bold and refreshing espresso drink, the Iced Shaken Espresso combines rich espresso shots with a touch of sweetness, shaken over ice to create a light, frothy texture. Its invigorating flavor and chilled, energizing finish make it perfect for a quick caffeine boost on hot days.', '1761221436', 1),
(19, '5', 'Iced Apple Crisp Oatmilk Shaken Espresso', 425, '1761221745_Apple_Crisp_Espresso.png', 'A festive and refreshing espresso beverage, this drink combines rich espresso with apple and cinnamon flavors, lightly sweetened and shaken over ice with creamy oat milk. Its crisp, balanced taste and smooth texture make it a perfect seasonal treat for coffee lovers seeking something unique and flavorful.', '1761221745', 1),
(20, '5', 'Iced Brown Sugar Oatmilk Shaken Espresso', 425, '1761221897_Brown_Sugar_Espresso.png', 'A smooth and indulgent iced espresso drink, combining bold espresso shots with brown sugar and cinnamon flavors, shaken over ice and blended with creamy oat milk. Its sweet-spiced aroma and velvety texture make it a refreshing and satisfying choice for any time of day.', '1761221897', 1),
(21, '5', 'Iced Hazelnut Oatmilk Shaken Espresso', 425, '1761222077_Hazelnut_Espresso.png', 'A nutty and refreshing iced espresso beverage, featuring bold espresso shots shaken with hazelnut syrup and creamy oat milk over ice. Its smooth, slightly sweet flavor and frothy texture make it a perfect choice for a deliciously energizing coffee break.', '1761222077', 1),
(22, '5', 'Iced Espresso', 345, '1761222332_Iced_Espresso.png', 'A simple yet bold iced coffee, featuring freshly pulled espresso shots served over ice. Its rich, robust flavor and smooth finish make it a perfect pick-me-up for coffee lovers who enjoy a straightforward, refreshing espresso experience.', '1761222332', 1),
(23, '1', 'Iced Americano', 345, '1761225025_Iced_Americano.png', 'A classic and refreshing iced coffee, the Iced Americano combines rich espresso shots with cold water over ice. Its bold, smooth flavor and clean finish make it an ideal choice for those who enjoy a strong, uncomplicated coffee experience.', '1761225025', 1),
(24, '1', 'Iced Caffe Americano', 345, '1761225177_Iced_Caffe_Americano.png', 'A chilled take on the classic Americano, this drink features rich espresso shots poured over ice and cold water. Its bold, smooth flavor and crisp finish make it perfect for a refreshing caffeine boost on warm days.', '1761225177', 1),
(25, '4', 'Iced White Chocolate Mocha', 395, '1761225544_Chocolate_Mocha.png', 'A sweet and indulgent iced coffee drink, combining rich espresso with creamy white chocolate and cold milk, served over ice. Topped with whipped cream, it offers a smooth, velvety texture and a delightful balance of sweetness and bold coffee flavor.', '1761225544', 1),
(26, '4', 'Iced Caffe Mocha', 395, '1761225667_Caffe_Mocha.png', 'A classic indulgence, the Iced Caffè Mocha blends rich espresso with chocolate syrup and cold milk, served over ice. Topped with whipped cream, it delivers a perfect balance of chocolatey sweetness and bold coffee flavor, ideal for a refreshing treat any time of day.', '1761225667', 1),
(27, '14', 'Iced Caramel Macchiato', 395, '1761225791_Caramel_Macchiato.png', 'A popular iced espresso beverage, the Iced Caramel Macchiato combines cold milk with rich espresso, layered with vanilla syrup and topped with a drizzle of caramel. Its sweet, creamy, and bold flavors make it a refreshing and indulgent coffee treat.', '1761225791', 1),
(28, '8', 'Iced Flat White', 299, '1761225894_Flat_White.png', 'A smooth and velvety iced espresso drink, the Iced Flat White features ristretto espresso shots combined with cold milk for a creamy texture. Its bold coffee flavor with a silky finish makes it perfect for those who enjoy a strong yet refreshing coffee experience.', '1761225894', 1),
(29, '8', 'Vanilla Bean Flat White', 370, '1761226319_Vanilla_Bean_Flat_White.png', 'The Vanilla Bean Crème Flat White is a rich and creamy blend of vanilla bean, milk, and ice, topped with whipped cream. This indulgent beverage offers a smooth, velvety texture and a delightful vanilla flavor, making it a perfect treat for those who enjoy a sweet, caffeine-free option.', '1761226319', 1),
(30, '5', 'Espresso Martini', 450, '1761226854_Espresso_Martini.png', 'A bold and sophisticated espresso-based beverage infused with the essence of martini. Rich espresso combines with hints of chocolate and coffee liqueur flavors, served chilled with a smooth, velvety finish. Perfect for those seeking an indulgent, stylish coffee experience.', '1761226854', 1),
(31, '14', 'Vanilla Sweet Cream Macchiato', 395, '1761227029_Vanilla_Sweet_Cream_Macchiato.png', 'A layered espresso beverage with rich vanilla flavor, cold milk, and a smooth vanilla sweet cream topping. Its harmonious balance of bold espresso and sweet, creamy notes makes it an indulgent and visually appealing coffee treat.', '1761227029', 1),
(32, '8', 'Caramel Flat White', 299, '1761227269_Caramal_Flat_White.png', 'A smooth and velvety espresso-based drink, combining ristretto shots with steamed milk and a drizzle of rich caramel. Its bold coffee flavor and creamy texture are perfectly balanced with a hint of sweetness, making it an indulgent choice for coffee lovers.', '1761227269', 1),
(33, '14', 'Caramel Macchiato', 280, '1762278718_Caramel_Machiato.png', 'A smooth and layered coffee made with rich espresso poured over velvety steamed milk and a hint of vanilla. Finished with a slow caramel drizzle on top, it offers a warm, silky sweetness balanced with bold espresso intensity — comforting, aromatic, and indulgently satisfying in every sip.', '1762278718', 1),
(34, '12', 'Cortado', 240, '1762278919_Coratado.png', 'A balanced and smooth coffee made with equal parts rich espresso and warm steamed milk. The Cortado tempers the espresso’s natural intensity without masking its character, delivering a clean, velvety taste with a gentle creamy finish — simple, refined, and crafted for pure coffee appreciation.', '1762278919', 1),
(35, '12', 'Pecan Oatmilk Cortado', 365, '1762279039_Oatmil_Cortado.png', 'A seasonal espresso delight featuring three ristretto shots of Blonde Espresso Roast layered with steamed oat milk, infused with warm notes of pecan, browned butter, and holiday baking spices. Finished with a pecan-crunch topping, it delivers a smooth yet bold sip of indulgence.', '1762279039', 1),
(36, '12', 'Brown Sugar Oatmilk Cortado', 365, '1762279157_Brown_Sugar_Cortado.png', 'A smooth and indulgent espresso drink made with ristretto shots and creamy oat milk, sweetened by rich brown sugar and a hint of cinnamon. With its velvety texture and gently spiced sweetness, it offers a comforting yet balanced experience — perfect for sipping slowly or enjoying between tasks.', '1762279157', 1),
(37, '13', 'Doppio Shot', 220, '1762279430_Doppio_Shot.png', 'A straightforward double-espresso shot — pure, intense, and highly focused. The Doppio delivers the bold character of two espresso shots back to back, with a rich crema and full-bodied flavor that’s ideal for true coffee enthusiasts craving maximum espresso impact.', '1762279430', 1),
(38, '13', 'Doppio Con Panna', 260, '1762279681_Doppio_Con_Panna.png', 'A classic espresso delight that balances intensity with indulgence. The Espresso Con Panna features a rich, bold shot of espresso crowned with a smooth dollop of lightly whipped cream. The creamy sweetness enhances the espresso’s deep flavor notes without overpowering its natural complexity, making it a refined treat for espresso lovers who enjoy a touch of luxury.', '1762279681', 1),
(39, '15', 'Affogato', 350, '1762279881_cat_68ab076ebdee35.03558197.png', 'A luxurious dessert-style coffee treat, the Affogato combines a hot shot of rich, freshly brewed espresso poured over a scoop of creamy vanilla ice cream. The contrast of hot and cold, bitter and sweet, creates a deeply satisfying flavor experience. Silky, sweet cream melts into bold espresso, making every spoonful smooth, indulgent, and unforgettable — perfect for coffee lovers with a sweet tooth.', '1762279881', 1),
(40, '3', 'Iced Pumpkin Spice Latte', 365, '1762280140_Pumkin_Latte.png', 'A cool, seasonal twist on the fall favorite — this iced version of the Pumpkin Spice Latte combines chilled espresso, milk, and classic spice mix (cinnamon, nutmeg, clove) served over ice with whipped cream on top. Rich yet refreshing, it captures autumn flavors in a chilled, satisfying coffee treat.', '1762280140', 1),
(41, '3', 'Iced Pecan Crunch Oatmilk Latte', 410, '1762280311_Peacun_Crunch_Latte.png', 'A smooth iced latte crafted with rich espresso, creamy oatmilk, and a sweet, nutty pecan syrup. Finished with crunchy caramel-pecan topping for a delightful texture and warm toasted-nut aroma. Refreshing, silky, and perfect for those who love a balanced blend of sweetness, nuttiness, and bold coffee flavor.', '1762280311', 1),
(42, '3', 'Iced Cinnamon Dolce Latte', 390, '1762280447_DolceLatte.png', 'A chilled and cozy delight, the Iced Cinnamon Dolce Latte combines smooth espresso with creamy milk and a sweet cinnamon dolce syrup. Served over ice and topped with a hint of cinnamon, this drink offers the perfect balance of warmth and refreshment — sweet, mildly spiced, and incredibly comforting in every sip.', '1762280447', 1),
(43, '7', 'Mocha Frappe', 370, '1762413252_cat_6888fe88434832.69726061.png', 'A rich and creamy blended coffee drink made with espresso, chocolate syrup, and milk, topped with whipped cream for a smooth, indulgent finish. The Mocha Frappe delivers a perfect balance of bold coffee flavor and sweet chocolate richness — a refreshing treat for any time of day.', '1762413252', 1),
(44, '7', 'Strawberry Creme Frappuccino', 420, '1762413479_Strawberry_Frappe.png', 'A smooth, creamy blend of milk, ice, and sweet strawberry purée, topped with a generous swirl of whipped cream. This caffeine-free delight offers a refreshing burst of fruity flavor with a velvety texture, making it a perfect choice for those who prefer something sweet, cool, and indulgent without the coffee kick.', '1762413479', 1),
(45, '7', 'Caramel Frappuccino', 430, '1762413695_Caramel_Frappe.png', 'A signature StarCoffee classic — this creamy, icy blend of rich coffee, milk, and buttery caramel syrup is topped with whipped cream and a smooth caramel drizzle. The Caramel Frappuccino delivers the perfect mix of sweetness and bold coffee flavor, offering a refreshing and indulgent pick-me-up for any time of the day.', '1762413695', 1),
(46, '7', 'Mocha Cookie Crumble Frappuccino', 460, '1762413874_Mocha_Cookie_Frappe.png', 'A decadent blend of rich mocha sauce, coffee, milk, and ice, layered with chocolate cookie crumbles and topped with whipped cream and more cookie bits. The Mocha Cookie Crumble Frappuccino is a dessert in a cup — creamy, crunchy, and irresistibly chocolatey, perfect for those who love a bold coffee flavor with a sweet, indulgent twist.', '1762413874', 1),
(47, '7', 'Caramel Ribbon Crunch Frappuccino', 470, '1762414037_Caramel_Ribbon_Frappe.png', 'A layered caramel masterpiece blending buttery caramel syrup, coffee, milk, and ice for a smooth, creamy base. Topped with whipped cream, rich dark caramel sauce, and crunchy caramel-sugar topping, this Frappuccino delivers a perfect mix of sweetness, texture, and bold coffee flavor — a true treat for caramel lovers seeking a luxurious, icy indulgence.', '1762414037', 1),
(48, '9', 'Hot Chocolate', 320, '1762414377_Hot_Chocolate.png', 'A timeless classic made with steamed milk and rich chocolate syrup, topped with a swirl of whipped cream for extra smoothness. The Hot Chocolate offers a perfectly balanced blend of creamy sweetness and deep cocoa flavor — warm, comforting, and ideal for cozy moments or chilly days.', '1762414377', 1),
(49, '9', 'Chocolate Cream Cold Brew', 440, '1762415813_Caramel_Chocolate_Brew.png', 'A refreshing twist on cold brew coffee — this drink combines smooth, slow-steeped cold brew with a creamy chocolate protein blend, creating a perfect balance of energy and indulgence. Topped with a light chocolate cream foam, it delivers rich cocoa notes, subtle sweetness, and a satisfying boost of protein — ideal for coffee lovers who want flavor with function.', '1762415813', 1),
(50, '3', 'Iced Vanilla Cream Latte', 430, '1762416029_Iced_Vanilla_Latte.png', 'A smooth and energizing iced latte crafted with rich espresso, creamy milk, and a boost of vanilla protein for extra nourishment. Served over ice and topped with a light vanilla cream foam, it delivers a perfect blend of sweetness, coffee boldness, and silky texture — refreshing, satisfying, and ideal for an anytime pick-me-up.', '1762416029', 1),
(51, '3', 'StarCoffee Blonde Vanilla Latte', 410, '1762416325_StarCoffee_Blonde_Latte.png', 'A smooth and mellow espresso drink made with StarCoffee® Blonde Espresso, steamed milk, and a hint of sweet vanilla syrup. The lighter roast brings out soft, creamy notes with gentle sweetness and a velvety finish — perfect for those who enjoy a balanced latte that’s flavorful yet smooth and easy to sip.', '1762416325', 1),
(52, '6', 'Iced Pumpkin Cream Coffee', 380, '1762416730_Iced_Pumpkin_Coffee.png', 'A refreshing autumn-inspired coffee that blends smooth cold brew with hints of pumpkin spice and creamy sweetness. Topped with a velvety pumpkin cream foam and a dusting of pumpkin spice, this iced beverage offers the perfect balance of bold coffee flavor and cozy seasonal warmth — cool, creamy, and irresistibly festive.', '1762416730', 1),
(53, '6', 'Iced London Fog Latte', 360, '1762416877_Iced_London_Fog.png', 'A refreshing twist on the classic tea latte, this drink combines chilled Earl Grey tea with creamy milk and a touch of vanilla syrup. The result is a smooth, floral, and subtly sweet beverage with hints of bergamot and citrus — light, elegant, and perfectly soothing for a calm, refreshing break.', '1762416877', 1),
(54, '2', 'Salted Caramel Cream Cappuccino', 390, '1762417279_Salted_Cappuccino.png', 'A rich and frothy cappuccino infused with buttery caramel syrup and topped with a smooth salted caramel cream foam. This indulgent blend perfectly balances sweetness and a hint of salt, enhancing the bold espresso flavor beneath. Each sip delivers a creamy, velvety texture with a touch of caramelized warmth — a comforting yet luxurious coffee experience.', '1762417279', 1),
(55, '2', 'Chocolate Cream Cappuccino', 400, '1762417441_Chocolate_Cream_Cappuccino.png', 'A delicious fusion of bold espresso, steamed milk, and rich chocolate syrup, topped with a layer of velvety chocolate cream foam. The Chocolate Cream Cappuccino offers a perfect harmony of deep cocoa notes and smooth coffee flavor, creating a creamy, indulgent treat that’s both comforting and energizing. Ideal for chocolate and coffee lovers alike.', '1762417441', 1),
(56, '2', 'Pumpkin Cream Cappuccino', 410, '1762417586_Pumpkin_Cream_Cappuccino.png', 'A seasonal favorite that blends rich espresso with steamed milk and the cozy flavors of pumpkin spice. Topped with smooth pumpkin cream foam and a sprinkle of pumpkin spice dust, this cappuccino delivers a warm, aromatic balance of sweetness, spice, and bold coffee flavor — perfect for savoring the taste of autumn in every sip.', '1762417586', 1),
(57, '2', 'Nondairy Vanilla Sweet Cream Cappuccino', 420, '1762417713_Nondairy_Cappuccino.png', 'A smooth and creamy cappuccino crafted with bold espresso and your choice of nondairy milk, perfectly blended with sweet vanilla syrup. Finished with a light vanilla cream foam, this drink offers a rich, velvety texture and delicate sweetness — a perfect balance for those who prefer a dairy-free yet indulgent coffee experience.', '1762417713', 1),
(58, '2', 'Cappuccino', 330, '1762417856_Cappuccino.png', 'A timeless espresso classic made with equal parts rich espresso, steamed milk, and a thick layer of frothy milk foam. The Cappuccino offers a perfect balance of bold coffee flavor and creamy texture, delivering a smooth, aromatic, and satisfying experience — ideal for both morning energy and relaxed coffee breaks.', '1762417856', 1),
(59, '1', 'Caffe Americano', 310, '1762418073_Caffe_Americano.png', 'A simple yet bold coffee made by blending rich espresso shots with hot water, creating a smooth, full-bodied flavor and light crema on top. The Caffè Americano delivers the perfect balance of strength and smoothness — a clean, robust coffee experience for those who enjoy pure espresso taste with a lighter body.', '1762418073', 1),
(60, '11', 'Irish Coffee', 450, '1762418611_Irish_Coffee.png', 'A rich and comforting coffee beverage crafted with freshly brewed coffee, a hint of brown sugar, and topped with smooth whipped cream. The Irish Coffee delivers a deep, aromatic flavor with gentle sweetness and a creamy finish — a perfect blend of warmth, smoothness, and indulgence for a truly classic coffee experience.', '1762418611', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

-- No reviews records are seeded; add only synthetic data for demos.

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

-- No users records are seeded; add only synthetic data for demos.

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_phone` (`phone`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_key` (`order_key`),
  ADD KEY `idx_orders_user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_order_items_order_id` (`order_id`),
  ADD KEY `idx_order_items_product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `idx_reviews_user` (`user_id`),
  ADD KEY `idx_reviews_order` (`order_id`),
  ADD KEY `idx_reviews_product` (`product_id`),
  ADD KEY `idx_reviews_created` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `p_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`p_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
