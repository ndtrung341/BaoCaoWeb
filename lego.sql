-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 24, 2021 lúc 01:56 PM
-- Phiên bản máy phục vụ: 10.4.20-MariaDB
-- Phiên bản PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lego`
--
CREATE DATABASE IF NOT EXISTS `lego` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `lego`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(20) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `modifed_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `created_date`, `modifed_date`, `status`) VALUES
(1, 'duytrung', '19004222@st.vlute.edu.vn', 'abcd1234', '2021-09-13 14:26:13', '2021-09-13 14:26:13', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`product_code`),
  KEY `FK_CART_PRODUCT` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `account` int(11) NOT NULL,
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `total` decimal(13,0) NOT NULL DEFAULT 0,
  `payment` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `payment` (`payment`),
  KEY `account` (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Bẫy `orders`
--
DROP TRIGGER IF EXISTS `changeStatus`;
DELIMITER $$
CREATE TRIGGER `changeStatus` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
	SET NEW.status = IF(NEW.payment=1,1,0);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders_detail`
--

DROP TABLE IF EXISTS `orders_detail`;
CREATE TABLE IF NOT EXISTS `orders_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_code` varchar(10) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `price` decimal(13,0) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(13,0) NOT NULL,
  PRIMARY KEY (`id`,`orders_code`,`product_code`),
  KEY `FK_ORDERS_DETAIL_PRODUCT` (`product_code`),
  KEY `FK_ORDERS_DETAIL_ORDERS` (`orders_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Bẫy `orders_detail`
--
DROP TRIGGER IF EXISTS `DeleteUpdateTotal`;
DELIMITER $$
CREATE TRIGGER `DeleteUpdateTotal` BEFORE DELETE ON `orders_detail` FOR EACH ROW UPDATE orders  
    SET orders.total = orders.total - OLD.quantity*OLD.price
    WHERE orders.code = OLD.orders_code
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `TotalProduct`;
DELIMITER $$
CREATE TRIGGER `TotalProduct` BEFORE INSERT ON `orders_detail` FOR EACH ROW BEGIN
	SET NEW.total = NEW.price * NEW.quantity;
    UPDATE product p SET p.quantity = p.quantity - NEW.quantity WHERE p.product_code=NEW.product_code;
    UPDATE orders  
    SET orders.total = orders.total + NEW.total
    WHERE orders.code = NEW.orders_code;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `payment`
--

INSERT INTO `payment` (`id`, `name`) VALUES
(1, 'Thẻ tín dụng'),
(2, 'Thanh toán khi nhận hàng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(100) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(13,0) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `image` text NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `product_code` (`product_code`),
  KEY `FK_PRODUCT_THEME` (`theme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `product_code`, `theme_id`, `name`, `description`, `price`, `quantity`, `created_date`, `modified_date`, `status`, `image`) VALUES
(1, '42131', 2, 'App-Controlled Cat® D11 Bulldozer', 'Celebrate a hero of the construction world as you build a replica version of the biggest Cat® dozer with this LEGO® Technic™ App-Controlled Cat® D11 Bulldozer (42131) kit. This large set lets adults enjoy quality ‘me time’ focusing on their passion. Just like the real Cat® bulldozer, this model is built in modular sections. LEGO fans will love the new-for-October-2021 LEGO element – a large track that can be tightened and loosened. And the build is just the start! With many authentic features and functions packed into this model, there’s lots to discover.\r\n\r\nTake control of your bulldozer model\r\nUse the CONTROL+ app to operate and explore this heavy-duty machine. Use the app to drive and steer. The app delivers realistic movement as you raise, lower and tilt the blade or raise and lower the ripper and ladder.\r\n\r\nExplore engineering\r\nLEGO Technic buildable models feature realistic movement and mechanisms that let LEGO builders explore engineering concepts in an approachable and realistic way.\r\n\r\nBuild a mighty construction vehicle packed with details with this huge LEGO® Technic™ App-Controlled Cat® D11 Bulldozer 42131 model building set for adults.\r\nEnjoy a rewarding build, then explore the model’s many features and functions. See the new-for-October-2021 LEGO® track element that gives the bulldozer its realistic movement.\r\nUse the CONTROL+ app to operate your bulldozer. Bring the bulldozer model to life as you drive and steer, then raise, lower and tilt the blade or raise and lower the ripper and ladder.\r\nLooking for the best gifts for yourself or any fan of Cat® bulldozers and other cool vehicles? This impressive construction vehicle set makes a top gift idea.\r\nThis Cat® replica measures over 10 in. (26 cm) high, 22 in. (57 cm) long and 14.5 in. (37 cm) wide.\r\nRequires 6 x AA batteries (not included). The set also includes 2 x no. 15 motors, 2 x large motors and 1 x hub.\r\nThis Cat® model is meticulously detailed to replicate the full-sized model with authentic colors and graphics that Cat® enthusiasts will love.\r\nThe LEGO® Technic™ universe offers advanced buildable models for LEGO fans who are looking for an immersive building challenge.\r\nLEGO® Technic™ components meet rigorous industry standards to ensure they are consistent, compatible and connect reliably every time – it’s been that way since 1958.\r\nLEGO® Technic™ components are dropped, heated, crushed, twisted and analyzed to make sure they meet strict global safety standards.', '10250000', 2, '2021-10-10 19:02:49', '2021-10-10 19:02:49', 1, 'upload/products/42131/ilMEm.png'),
(2, '42112', 2, 'Concrete Mixer Truck', 'Bring the world of construction to life for kids who love to see how things work. Youngsters with a passion for vehicles will enjoy building this LEGO® Technic™ Concrete Mixer Truck (42112). With 8 robust wheels, a moving motor, opening driver’s cab and large mixing drum, it looks just like the real thing. Kids can turn the drum manually or set it to automatic to see it turn as they push the truck. When they turn the drum counterclockwise, they\'ll see the concrete unload.\r\n\r\nCreative play at the toy construction site\r\nImaginative role play is easy with this construction toy building set. The truck comes with 100 1x1 LEGO bricks to use as the concrete. Kids can turn the bricks in the blender then use the handle to control the funnel and pour the concrete into position.\r\n\r\nAdvanced building kits for kids\r\nLEGO Technic model building kits are ideal for LEGO fans ready for a new challenge. These vehicles look and move like the real thing, so kids will love playing with their creations.\r\n\r\nKids who love construction vehicle playsets will discover lots of cool functions in this LEGO® Technic™ Concrete Mixer Truck (42112). With a rotating mixer drum, this model looks and moves just like the real thing.\r\nWith 1,163 pieces, kids will enjoy a challenging build before exploring all the features of the concrete mixer truck, including 100 1x1 LEGO® system bricks to use as the concrete.\r\nTurn the drum manually to mix the concrete or set it to automatic and see the mixer rotate as the truck drives along. Turn the drum counterclockwise to unload the concrete, using the funnel to guide it.\r\nKids who love concrete truck toys will have lots of fun exploring all the features of this concrete mixer truck construction model kit. It makes a great birthday present or holiday gift for kids aged 10 and up.\r\nThe concrete truck toy measures over 16” (42cm) long, 7” (19cm) high and 4” (12cm) wide, making it ideal for creative play.\r\nNo batteries are required for this construction toy so the fun starts right away and never slows down.\r\nPrinted instructions are included for this concrete truck toy LEGO® Technic™ building set for kids.\r\nLEGO® Technic™ model kits give kids a great introduction to engineering. With awesome designs and realistic functions, LEGO Technic offers advanced building kits packed with realistic features.\r\nLEGO® Technic™ sets meet the highest industry standards, which ensures each of these building sets for kids connects easily every time.\r\nLEGO® Technic™ sets are thoroughly tested to make sure each one meets the highest global safety and quality standards.', '7800000', 3, '2021-10-10 19:10:17', '2021-10-10 19:10:17', 1, 'upload/products/42112/KNBKv.png'),
(3, '42121', 2, 'Heavy-Duty Excavator', 'Looking for a special gift for kids who love construction toy sets? This 2-in-1 LEGO® Technic™ Heavy-Duty Excavator (42121) toy has plenty of features for hours of creative play. Boys and girls aged 8 and up will enjoy an immersive building challenge before exploring the digger toy\'s realistic features.\r\n\r\nSee how an excavator works\r\nDigging, driving, rotating; there\'s so much to discover in this cool kids\' excavator toy. Check out the shovel that moves up and down, plus the movable arm, running belts and rotating body. There’s also a detailed cabin with a driver’s seat and control panel. The set comes with extra LEGO brick ‘stones’ to dig and 4 cones. When it’s time for a new challenge, the model rebuilds into a tracked tractor with backhoe.\r\n\r\nA rewarding build for young LEGO fans\r\nThe LEGO Technic universe offers advanced building toys for LEGO fans ready for their next challenge. With realistic mechanisms, these rewarding sets are a great way to introduce kids to the world of engineering.\r\n\r\nWhether it’s a birthday, Christmas, or ‘just because’ gift, young construction fans will love building and exploring this 2-in-1 LEGO® Technic™ Heavy-Duty Excavator (42121) toy set.\r\nPacked with realistic features, this digger toy set lets kids see how construction vehicles really work. Check out the moving shovel and arm, plus running belts, just like on a real excavator.\r\nWhen kids are ready for a new challenge, the 2-in-1 toy model rebuilds into a tracked tractor with backhoe.\r\nThis cool excavator toy for kids is ideal for boys and girls aged 8 and up who love construction vehicles.\r\nWith the authentic cabin and control panel to explore, it’s easy for kids to imagine life on a construction site with this cool excavator toy.\r\nRole-play is easy with this cool set. There are 4 cones to manage the building site, plus extra LEGO® pieces to use as bricks for scooping up with the digger truck toy.\r\nThis kids\' excavator toy model measures over 9 in. (23 cm) high, with arm up,14.5 in. (37 cm) long, with arm fully extended, and 4 in. (11 cm) wide.\r\nThe LEGO® Technic™ universe opens up the world of engineering for young LEGO fans ready for their next building challenge.\r\nLEGO® components meet stringent industry standards to ensure they are consistent, compatible and connect reliably every time – it\'s been that way since 1958.\r\nLEGO® components are dropped, heated, crushed, twisted and analyzed to make sure they meet strict global safety standards.', '4500000', 3, '2021-10-10 19:13:53', '2021-10-10 19:13:53', 1, 'upload/products/42121/I21Tt.png'),
(4, '42095', 2, 'Remote-Controlled Stunt Racer', 'Pull high-speed wheelies, spins and turns, and traverse rough terrain with this fully motorized LEGO® Technic™ 42095 Remote-Controlled Stunt Racer. This tough model features large ground-gripping tracks with large rear sprockets for optimal acceleration, plus a modern design with a fresh yellow and blue color scheme and decorative stickers. Drive forward, backward, left or right and make 360° turns. Rebuild this 2-in-1 remote-controlled tracked vehicle to create a Remote-Controlled Racer for a double build-and-play experience.\r\n\r\nFeatures a high-speed, fully motorized, remote-controlled vehicle with tracks and large rear sprockets for amazing acceleration.\r\nCheck out the fresh yellow and blue color scheme with cool stickers.\r\nTraverse rough terrain and obstacles, drive forward, backward, left and right, perform 360° turns and pull awesome wheelies at high speed.\r\nIncludes the following LEGO® Power Functions components: 2 large motors, receiver, battery box and a remote control.\r\nThis LEGO® Technic™ set is designed to provide an immersive and rewarding building experience, and features realistic movement and mechanisms, helping young builders enhance their motor skills, hand-eye coordination and imagination.\r\nThis 2-in-1 motorized toy rebuilds into a Remote-Controlled Racer.\r\nRemote-Controlled Stunt Racer measures over 6” (17cm) high, 8” (22cm) long and 5” (15cm) wide.\r\nRemote-Controlled Racer measures over 4” (12cm) high, 7” (20cm) long and 7” (19cm) wide.', '6530000', 3, '2021-10-10 19:17:44', '2021-10-10 19:17:44', 1, 'upload/products/42095/FcR7i.png'),
(5, '71738', 3, 'Zane\'s Titan Mech Battle', 'Get young ninja fans role-playing exciting scenes from season 5 of the LEGO® NINJAGO® TV series, with this Zane’s Titan Mech Battle (71738) Legacy set. Featuring posable legs and arms, a sword and spinning chainsaw in its hands, plus 2 spring-loaded shooters and a cockpit for ninja minifigures, kids will love this modern update of the original mech.\r\n\r\nHours of fun for passionate NINJAGO fans\r\nThis ninja toy features 4 minifigures: ninjas Zane and Jay Legacy plus 2 Ghost Warriors Ghoultar and Soul Archer, all armed with cool weapons including a bow and arrow and scythe to inspire endless NINJAGO battling fun. New for January 2021, Jay Legacy is a special golden collectible minifigure to celebrate 10 years of NINJAGO toys.\r\n\r\nTop gifts for imaginative kids\r\nNINJAGO building toys open up a world of exciting adventures for kids. Little ninjas can team up with their heroes to battle a cast of evil villains as they role-play with an amazing collection of cool toys including dragons, mechs and temples.\r\n\r\nLEGO® NINJAGO® Legacy Zane’s Titan Mech Battle (71738) is a modern update on a classic playset featuring a highly posable, chainsaw-wielding mech toy for kids to stage thrilling battles.\r\nAction-packed battle set includes 4 minifigures from season 5 of the NINJAGO® TV series: ninjas Jay Legacy and Zane, plus Ghost Warriors Ghoultar and Soul Archer, all armed with cool weapons.\r\nThis awesome mech toy has posable arms and legs, a sword and chainsaw in its hands, and 2 spring-loaded shootersfor kids to create fast-moving stories.\r\nIncludes a golden Jay Legacy collectible minifigure with a small stand to celebrate the 10th anniversary of NINJAGO® toys.\r\nLook out for more special collectible golden minifigures in other NINJAGO® sets: Tournament of Elements (71735), Boulder Blaster (71736) and X-1 Ninja Charger (71737).\r\nThis 840-piece ninja playset provides a fun and rewarding build for ages 9 and up and makes a great birthday or holiday gift for kids who love LEGO® building and ninja action!\r\nMeasuring over 10 in. (26 cm) tall, 3.5 in. (9 cm) long and 5.5 in. (15 cm) wide Zane’s Titan Mech looks great on display in a child’s bedroom between playtime battles.\r\nLEGO® NINJAGO® offers an amazing collection of cool playsets that put a smile on any kid\'s face and lets them learn positive life skills through exciting adventures with their ninja heroes.\r\nFor more than six decades, LEGO® bricks have been made from the highest-quality materials to ensure they pull apartconsistently, every time. Ninja skills not needed!\r\nLEGO® building bricks meet stringent safety standards, so kids are in safe hands.', '4780000', 10, '2021-10-10 19:20:03', '2021-10-10 19:20:03', 1, 'upload/products/71738/gvafO.png'),
(6, '71722', 3, 'Skull Sorcerer\'s Dungeons', 'Action-packed LEGO® NINJAGO® Skull Sorcerer’s Dungeons (71722) playset with 8 minifigures, including ninjas Hero Cole, Hero Zane and Hero Lloyd. The toy dungeons let creative kids enjoy countless hours re-enacting breathtaking battles from the TV series.\r\n\r\nThe ultimate LEGO board game\r\nThis building set for kids also acts as a thrilling NINJAGO board game. Ninja fans use the dice spinner to plot their way through the dungeons, avoiding traps, lava, moving bridges and the Skull Sorcerer\'s warriors as they seek to grasp the Shadow Blade of Deliverance and free a ninja from prison. It can also be combined with other LEGO sets to create a giant board game.\r\n\r\nLEGO toys offer a world of creative fun.\r\nLEGO NINJAGO building toys for kids provide an escape to a fantasy world of non-stop action where they can fight with their ninja heroes against the forces of evil. NINJAGO fans will love indulging in role play with ninja weapons, buildable figures, cars, dragons and much more.\r\n\r\nLEGO® board game and dungeons playset featuring Hero Cole, Hero Zane and Hero Lloyd to play out scenes from the NINJAGO® TV series. The ideal ninja toy for kids who love making their own stories and playing board games.\r\nThis LEGO® NINJAGO® Skull Sorcerer\'s Dungeons (71722) playset boasts 8 figures for kids to play out thrilling NINJAGO® dungeon adventures. The dice spinner doubles the fun and lets them compete in a LEGO® board game.\r\nThis awesome playset can be used as both toy dungeons for staging battles from the NINJAGO® TV series and a LEGO® board game for kids to plot their way past the traps and hazards before confronting the Skull Sorcerer.\r\nThis 1,171-piece LEGO® NINJAGO® board game with a vast array of figures offers a rewarding building task for boys and girls aged 9+ and makes a very cool birthday or LEGO gift for any occasion for creative kids.\r\nNew for June 2020, this ninja toy is just the right size to play with at home or to proudly display in a bedroom. The toy dungeons measure over 12” (31cm) high, 10” (27cm) long and 16” (43cm) wide.\r\nNo batteries are required for your little ninjas to enjoy dungeon adventures – it is ready for creative fun as they share the action with friends as soon as it is built. The play never has to stop!\r\nThis NINJAGO® board game is accompanied by helpful and detailed instructions, so youngsters can put it together with real confidence to get the fun started quickly.\r\nLEGO® NINJAGO® building sets for kids will inspire their imaginations and provide a passage into an amazing world of adventure where they can team up with their favorite ninja heroes for endless exciting battles.\r\nThe LEGO® bricks used to build this fun toy meet the highest industry standards, ensuring they are always consistent, compatible and pull apart with ease every time. Not even the Skull Sorcerer can defeat them!\r\nThe LEGO® bricks used to build this action toy with ninja weapons have been tirelessly tested and meet the highest global safety and quality standards. You are in safe hands with LEGO toys.', '5000000', 4, '2021-10-10 19:22:16', '2021-10-10 19:22:16', 1, 'upload/products/71722/Qrxt7.png'),
(7, '71742', 3, 'Overlord Dragon', 'Little ninjas can enjoy recreating action-filled scenes from season 2 of the LEGO® NINJAGO® TV series with the Overlord Dragon (71742) playset. Featuring posable legs, head and wings adorned with blades, the dragon can take several different stances to inspire role-play fun.\r\n\r\nEndless thrills for young builders\r\nThe posable dragon toy is accompanied by 2 minifigures: ninja Golden Lloyd armed with a katana on his back and the Overlord wielding his own sharp blade, so kids have all they need to play out exhilarating battles in the sky and on the ground in this classic fight between good and evil.\r\n\r\nUnique toys for creative kids\r\nLEGO NINJAGO toys offer a world of all-consuming fun as kids travel to an exciting fantasy world where they can join forces with their heroes to battle a cast of evil villains playing with an amazing collection of cool toys including dragons, jets and vehicles.\r\n\r\nLEGO® NINJAGO® Legacy Overlord Dragon (71742) playset featuring a cool and highly posable dragon for kids to enjoy hours of flying fun.\r\nImpressive dragon playset with 2 minifigures from season 2 of the LEGO® NINJAGO® TV series: Overlord armed with a blade and ninja Golden Lloyd with a katana strapped to his back.\r\nThis fun dragon toy has posable head, legs and bladed wings for NINJAGO® fans to create fast-moving stories in the sky or on the ground.\r\nThis battle playset provides a rewarding building experience for kids aged 7 and up and is the perfect LEGO® gift to help them fulfil their passion for ninjas and being creative.\r\nThe Overlord Dragon measures over 5.5 in. (12 cm) high, 18 in. (46 cm) long and 17.5 in. (45 cm) wide to look impressive displayed in a child\'s bedroom between playtime battles.\r\nLEGO® NINJAGO® includes an amazing collection of cool playsets that put a smile on any kid\'s face and lets them learn positive life skills through exciting adventures with their ninja heroes.\r\nFor more than six decades, LEGO® bricks have been made from the highest-quality materials to ensure they pull apart consistently, every time. Ninja skills not needed!\r\nLEGO® building bricks meet stringent safety standards, so kids are in safe hands.', '9000000', 2, '2021-10-10 19:24:39', '2021-10-10 19:24:39', 1, 'upload/products/71742/3ddCJ.png'),
(9, '75725', 1, 'A-wing Starfighter™', 'Inspire memories of the classic Star Wars: Return of the Jedi movie with this cool Ultimate Collectors Series LEGO® A-wing Starfighter (75275) building set! The sleek, arrowhead shape is beautifully reproduced in LEGO bricks and fans will appreciate authentic details such as the pivoting laser cannons and new-for-May-2020 removable canopy on the cockpit.\r\n\r\nCool display piece\r\nA testing building challenge for ages 18+ to enjoy solo or with friends and family, this model comes with a display stand, information plaque and an A-wing Pilot minifigure. And look out for other collectible build-and-display models, including the Stormtrooper Helmet (75276) and Boba Fett Helmet (75277).\r\n\r\nUniverse of excitement!\r\nThe LEGO Group has been creating brick-built versions of iconic starships, vehicles, locations and characters from the Star Wars™ universe since 1999. LEGO Star Wars is its most successful theme, with building sets that make great birthday, Christmas or surprise gifts for fans of all ages.\r\n\r\nCreate a stunning display with the first-ever LEGO® Ultimate Collector Series version of the A-wing Starfighter (75275) from Star Wars: Return of the Jedi, featuring a new-for-January-2020 removable cockpit canopy!\r\nStar Wars™ fans will love authentic details such as the pivoting laser cannons. Also includes an adjustable display stand, information plaque and A-wing Pilot character to complete a striking Star Wars centerpiece.\r\nThis A-wing LEGO® set is part of the Star Wars™ Ultimate Collector Series (UCS). Also check out the brilliant Stormtrooper Helmet (75276) and Boba Fett Helmet (75277) build-to-display models.\r\nThis 1,673-piece LEGO® Star Wars™ A-wing fighter building set offers a fun, rewarding challenge for Star Wars fans aged 18+, and makes a fantastic birthday gift, Christmas present or special occasion surprise.\r\nMeasuring 10.5” (27cm) high including display stand, 16.5” (42cm) long and 10” (26cm) wide, this collectible LEGO® Star Wars™ A-wing fighter model is bound to make a big impression displayed at home or in the office.\r\nThis battery-free building set is a peaceful way for adults to unwind after a hard day at work. Forget your worries for a while, find your building Zen and create an incredible LEGO® Star Wars™ A-wing model!\r\nThinking of buying this A-wing Starfighter model kit for a Star Wars™ fan who is new to LEGO® sets? No worries. It comes with step-by-step instructions so they can take on this challenging build with confidence.\r\nThere is a huge variety of LEGO® Star Wars™ building sets to delight fans of all ages, whether they want to play out famous movie scenes, create their own epic stories or just build and display the awesome models.\r\nYou won’t need to use the Force to connect or pull apart LEGO® bricks! They meet the highest industry standards to ensure consistency and a perfect, easy connection.\r\nLEGO® bricks and pieces are tested in almost every way imaginable so you can be sure that this cool Star Wars™ A-wing fighter building set meets the highest safety standards on planet Earth (and in any galaxy!).', '6500000', 3, '2021-10-10 19:28:05', '2021-10-10 19:28:05', 0, 'upload/products/75725/OKHQz.png'),
(10, '75315', 1, 'Imperial Light Cruiser™', 'Open up a galaxy of Star Wars: The Mandalorian Season 2 adventures for fans with this LEGO® brick-built model of the Imperial Light Cruiser (75315). It features a bridge that doubles as a handle for flying, 2 rotating turrets with spring-loaded shooters, plus 2 mini TIE Fighters and a launcher. A hatch gives easy access to the cabin which has a hologram table and storage for the electrobinoculars and other accessory elements.\r\n\r\nBattle play\r\nThis premium-quality set comes with 5 LEGO minifigures: The Mandalorian, Cara Dune, Fennec Shand, Moff Gideon and a Dark Trooper, plus a LEGO figure of the Child (Grogu), affectionately known as Baby Yoda. Cool weapons include The Mandalorian’s Amban phase-pulse blaster and spear and Moff Gideon’s darksaber for hero vs. villain play.\r\n\r\nLEGO Star Wars™ fun\r\nA top gift idea for trend-setting kids and any fan, the set comes with step-by-step building instructions. Explore the entire LEGO Star Wars range to find other construction sets that will delight all ages.\r\n\r\nStar Wars: The Mandalorian fans can recreate epic hero vs. villain battles from Season 2 with the first-ever LEGO® brick-built model of the Imperial Light Cruiser (75315).\r\nIncludes 5 LEGO® minifigures: The Mandalorian, Cara Dune, Fennec Shand, Moff Gideon and a new-for-August-2021 Dark Trooper, plus a LEGO figure of the Child (Grogu), affectionately known as Baby Yoda.\r\nThe starship features a bridge that doubles as a handle for flying, 2 rotating turrets with spring-loaded shooters, 2 mini TIE Fighters and a launcher, plus a hatch to access the cabin.\r\nCool weapons and accessory elements include The Mandalorian’s Amban phase-pulse blaster and spear, Moff Gideon’s darksaber, 2 thermal detonators and electrobinoculars to inspire creative play.\r\nThis awesome, creative building toy makes the best birthday present, holiday gift or fun treat for trend-setting kids and any Star Wars™ fan aged 10 and up.\r\nThe Imperial Light Cruiser measures over 5 in. (13 cm) high, 22.5 in. (58 cm) long and 8.5 in. (22 cm) wide. It’s fun to build solo or with friends and makes an attention-grabbing display piece.\r\nThinking of buying this 1,336-piece, buildable playset for a Star Wars™ fan who is a LEGO® beginner? No problem. It comes with easy-to-follow instructions so they can build with confidence.\r\nLEGO® Star Wars™ sets are great for creative kids and adult fans to recreate scenes from the Star Wars saga, play out their own fun adventures and display the collectible building toys.\r\nLEGO® components meet stringent industry standards to ensure they are compatible and connect consistently – it’s been that way since 1958.\r\nLEGO® bricks and pieces are tested to the max to ensure they comply with rigorous safety standards.\r\n', '8400000', 4, '2021-10-10 19:30:09', '2021-10-10 19:30:09', 1, 'upload/products/75315/X4Hco.png'),
(11, '75257', 1, 'Millennium Falcon™', 'Inspire youngsters and grownups with this 75257 LEGO® Star Wars™ Millennium Falcon model. This brick-built version of the iconic Corellian freighter features an array of details, like rotating top and bottom gun turrets, 2 spring-loaded shooters, a lowering ramp and an opening cockpit with space for 2 minifigures. The top panels also open out to reveal a detailed interior in which kids will love to play out scenes from the Star Wars: The Rise of Skywalker movie featuring Star Wars characters Finn, Chewbacca, Lando Calrissian, Boolio, C-3PO, R2-D2 and D-O. This iconic LEGO Star Wars set also makes a great collectible for any fan.\r\n\r\nIncludes 7 LEGO® Star Wars™ characters: Finn, Chewbacca, C-3PO, Lando Calrissian and Boolio minifigures, plus R2-D2 and D-O LEGO droid figures.\r\nLEGO® Star Wars™ Millennium Falcon model external features include a rotating top and bottom gun turrets (bottom turret fits 2 minifigures), 2 spring-loaded shooters, a lowering ramp and an opening cockpit with space for 2 minifigures.\r\nInterior details include a cargo area with 2 containers, navigation computer with rotating chair, couch and Dejarik hologame table, galley, bunk, hidden smuggling compartment and a hyperdrive with repair tools.\r\nWeapons include Chewbacca’s stud-firing bowcaster, Finn’s blaster and Lando’s blaster.\r\nInspire role-play scenes from the Star Wars: The Rise of Skywalker movie with the legendary Corellian freighter.\r\nThis LEGO® Star Wars™ set makes a great birthday gift, Christmas present or just a Star Wars collectible for any occasion.\r\nStarship measures over 5” (14cm) high, 17” (44cm) long and 12” (32cm) wide.', '12000000', 2, '2021-10-10 19:31:54', '2021-10-10 19:31:54', 1, 'upload/products/75257/Qh1IW.png'),
(12, '21056', 4, 'Taj Mahal', 'Whether you have been lucky enough to visit the Taj Mahal yourself and want a special souvenir of the experience, dream of visiting one day or just appreciate elegant buildings, this LEGO® Architecture Landmarks Collection set (21056) is ideal for you.\r\n\r\nWondrous details\r\nThis inspiring, hands-on challenge to enjoy in your spare time lets you recreate a symbol of eternal love and an architectural wonder of the world in LEGO style. Revel in authentic details such as the crypt with tombs of Mumtaz and Shah Jahan, central chamber with 2 cenotaphs, monumental iwans, main dome, 4 chhatris and 4 slender minarets. A LEGO brick inscribed ‘Taj Mahal’ completes a scale model that is sure to wow people wherever it is displayed.\r\n\r\nDelightful gift\r\nPart of a collection of premium-quality LEGO model kits for adults, this set makes a fantastic treat for yourself. It also makes an inspirational gift for LEGO fans and anyone in your life who is passionate about the Taj Mahal, India, travel or architecture.\r\n\r\nBuild and display your own symbol of eternal love with this fabulously detailed LEGO® Architecture Landmarks Collection model (21056) of the Taj Mahal.\r\nThe crypt with tombs of Mumtaz and Shah Jahan, central chamber with 2 cenotaphs, iwans, main dome, 4 chhatris, 4 minarets and other authentic details are beautifully recreated in LEGO® style.\r\nThe central chamber can be removed to view the crypt below. The scale model also includes a LEGO® brick inscribed ‘Taj Mahal’ to complete a conversation-starting centerpiece.\r\nWhether you’ve been to or dream of visiting India and the Taj Mahal, or you just love travel and architecture, this building set is for you. It also makes a very special gift for creative friends.\r\nMeasuring 7.5 in. (20 cm) high, 9 in (23 cm) wide and 9 in. (23 cm) deep, this buildable architectural model makes an impressive display piece for your home or office.\r\nThis collectible, 2,022-piece, construction set is all about the pleasure of immersing yourself in a complex build that leaves you feeling relaxed, refreshed and with a real sense of achievement.\r\nIncludes step-by-step instructions so even LEGO® beginners can build confidently, plus a booklet telling the Taj Mahal story (English language only. Download other languages at LEGO.com/architecture).\r\nThis LEGO® Architecture Taj Mahal set is part of a collection of premium-quality building kits designed for adults like you who enjoy hands-on, creative projects.\r\nLEGO® components meet rigorous industry standards to ensure that they connect securely and strongly.\r\nLEGO® bricks and pieces are dropped, heated, crushed, twisted and analyzed to make sure that they satisfy stringent global safety standards.', '3700000', 2, '2021-10-10 19:34:05', '2021-10-10 19:34:05', 1, 'upload/products/21056/pmGza.png'),
(13, '21504', 4, 'The White House', 'You’re busy. It feels like you’re always on the go. So when you do get some free time, you like to recharge your batteries by focusing on a fun, creative challenge. That’s what building The White House with LEGO® bricks is all about.\r\n\r\nRevel in the details\r\nWith this hands-on, minds-on craft project, you\'ll recreate details from the neoclassical columns of the president\'s Executive Residence and colonnades connecting the East and West Wings to the surrounding gardens and fountain. This LEGO Architecture White House model (21054) can be divided into 3 sections for closer inspection and includes a LEGO brick inscribed ‘The White House’ to complete an inspiring display piece.\r\n\r\nLEGO sets for your lifestyle\r\nEscape the daily grind and click your stress away creating this stylish model that will look great in your home or office. Part of a collection of LEGO building sets for adults interested in art, design, architecture and pop culture, it makes the best gift for you or the hobbyist inyour life.\r\n\r\nThis LEGO® Architecture display model of The White House (21054) beautifully captures the neoclassical design and splendor of this world-famous residence occupied by every United States president since 1800.\r\nEnjoy this LEGO® building set for adults, featuring details like the Executive Residence, West Wing, East Wing and connecting colonnades of The White House, plus the Rose Garden and Jacqueline Kennedy Garden.\r\nThe model can easily be divided into 3 sections for closer inspection of the architectural details. It also has a LEGO® brick inscribed ‘The White House’ to complete a conversation-starting display piece.\r\nIf you’ve been to or dream of visiting The White House, 1600 Pennsylvania Avenue, Washington DC, or you just love travel, architecture, history and design, this is the creative building kit for you.\r\nMeasuring 4” (11cm) high, 18” (47cm) wide and 7” (20cm) deep, this buildable LEGO® Architecture model is a super display item for your home or office. It\'s also makes the best gift for creative friends.\r\nNo batteries required – this stylish DIY project is all about recreating The White House with LEGO® bricks and immersing yourself in a focused activity that leaves you feeling relaxed and refreshed when you’re finished.\r\nIncludes clear instructions so even LEGO® newcomers can build with confidence, plus a coffee-table-style booklet telling The White House story (English language only. Download other languages at LEGO.com/architecture).\r\nThis construction set for adults is part of a collection of inspiring LEGO® model kits designed for you, the discerning hobbyist, as you look for your next creative project.\r\nLEGO® building bricks meet the highest industry standards, which ensures they are consistent, compatible and connect and pull apart easily every time – it\'s been that way since 1958.\r\nLEGO® bricks and pieces are rigorously tested, ensuring that every construction set meets the highest safety and quality standards, which means this White House miniature model is as robust as it is beautiful.', '10000000', 2, '2021-10-10 19:35:30', '2021-10-14 19:24:39', 1, 'upload/products/21504/oRoBl.png'),
(26, '75309', 1, 'Republic Gunship™', 'Spark memories of the epic Battle of Geonosis in Star Wars: The Clone Wars with this Ultimate Collector Series build-and-display model of a Republic Gunship (75309). Authentic features of a Low Altitude Assault Transport (LAAT) vehicle are beautifully reproduced in LEGO® bricks, including the pilot cockpits, swing-out spherical gun turrets, 2 cannons on top, super-long wings, opening sides and rear hatch and interior details.\r\n\r\nFirst choice for LEGO fans\r\nVoted for by LEGO fans to become an Ultimate Collector Series set, this awesome buildable model has a display stand with an information plaque, plus Clone Trooper Commander and Mace Windu LEGO minifigures. Step-by-step instructions are included so you can immerse yourself in the building challenge and enjoy the fun, creative process.\r\n\r\nImpressive gift\r\nPart of a collection of LEGO Star Wars™ building kits for adults, this premium-quality set makes a special gift for yourself, Star Wars enthusiasts or passionate LEGO fans in your life.\r\n\r\nEnjoy quality time creating the first-ever Ultimate Collector Series version of a Republic Gunship (75309), just like those seen during the Battle of Geonosis in Star Wars: The Clone Wars.\r\nThis build-and-display model is packed with authentic features including 2 pilot cockpits, swing-out spherical gun turrets, 2 cannons, super-long wings and opening sides and rear hatch.\r\nThe display stand has an information plaque and spaces for 2 included LEGO® Star Wars™ minifigures: Clone Trooper Commander with a blaster and Mace Windu with a lightsaber.\r\nThe Star Wars™ Republic Gunship was voted for by LEGO® fans as their top choice to become the next Ultimate Collector Series (UCS) set in an online poll. So, by popular demand, here it is!\r\nThis premium-quality set offers an immersive, rewarding building challenge and makes the best birthday or holiday gift for yourself, a devoted Star Wars™ fan or passionate LEGO® enthusiast.\r\nMeasuring over 13 in. (33 cm) high, 27 in. (68 cm) long and 29 in. (74 cm) wide, including the stand, this is a LEGO® Star Wars™ collectible you’ll want to photograph and share with others.\r\nThis 3,292-piece building kit comes with illustrated, step-by-step instructions so even a Star Wars™ fan who is a newcomer to LEGO® sets can take on the complex build with confidence.\r\nThis LEGO® Star Wars™ set for adults is part of a collection of building kits designed for you, the discerning hobbyist, who enjoys hands-on, creative DIY projects to unwind in your spare time.\r\nLEGO® components comply with stringent industry standards, meaning they are consistent, compatible and connect securely for robust builds.\r\nLEGO® bricks and pieces are dropped, heated, crushed, twisted and analyzed to ensure that they satisfy rigorous global safety standards.', '4500000', 4, '2021-11-11 20:35:22', '2021-11-11 20:35:22', 1, 'upload/products/75309/O30Xw.png'),
(27, '75288', 1, 'AT-AT™', 'Relive the Battle of Hoth and other classic Star Wars™ trilogy scenes with this AT-AT (75288) LEGO® building kit for kids! Different sections of the All Terrain Armored Transport vehicle open up for easy play, and it has spring-loaded shooters, plus a speeder bike inside. Fans will also love authentic details such as a winch to pull up Luke and his thermal detonator element.\r\n\r\nThe Empire vs. Rebel Alliance\r\nThis action-packed set includes 6 LEGO minifigures – Luke Skywalker, General Veers, 2 AT-AT Drivers and 2 Snowtroopers. They all have weapons, including Luke’s Lightsaber and the Snowtroopers’ tripod gun, to inspire Star Wars role-play missions. A wonderful gift idea for any LEGO Star Wars collector, it’s great for solo or group play.\r\n\r\nLove for LEGO Star Wars sets!\r\nThe LEGO Group has been recreating iconic starships, vehicles, locations and characters from the Star Wars universe for more than two decades and LEGO Star Wars has become a hugely successful theme. What\'s not to love?\r\nFans of the classic Star Wars™ trilogy and the LEGO® Star Wars: The Skywalker Saga video game will love recreating Battle of Hoth action with this detailed, posable LEGO brick version of the iconic AT-AT (75288) Walker.\r\nThis fun, creative building toy for kids includes 6 LEGO® Star Wars™ minifigures – Luke Skywalker, General Veers, 2 AT-AT Drivers and 2 Snowtroopers, all with weapons to role-play the Empire vs. Rebel Alliance battles.\r\nThe AT-AT has a cockpit for 3 LEGO® minifigures, foldout panels, spring-loaded shooters, a speeder bike, winch, bottom hatch so Luke can throw in the thermal detonator element, and more for realistic, creative play.\r\nThis 1,267-piece set makes a super birthday present, holiday gift or special treat for boys and girls aged 10+ who can look forward to a rewarding building challenge and hours of fun solo or social play.\r\nThe AT-AT vehicle measures over 13” (34cm) high, 14.5” (38cm) long and 5.5” (15cm) wide. Fans can also drive it in the LEGO® Star Wars: The Skywalker Saga video game.\r\nNo batteries are needed for this Star Wars™ AT-AT Walker building kit – it’s constructed purely with LEGO® bricks and powered by kids’ imaginations for unlimited galactic adventures and creative fun.\r\nThinking of buying this awesome construction toy for a Star Wars™ fan new to LEGO® sets? No worries. It comes with step-by-step instructions so they can take on this complex building challenge with confidence.\r\nLEGO® Star Wars™ building toys are greatfor kids and adult fans to construct, display and recreate classic Star Wars saga scenes or create their own fun missions. There’s something for everyone!\r\nNo need to summon up the Force to connect or pull apart LEGO® bricks! They meet the highest industry standards so you can be assured that the bricks in this construction playset fit together perfectly and with ease.\r\nLEGO® bricks and pieces are heated, crushed, dropped, twisted and analyzed to ensure that every action-packed Star Wars™ set meets the highest quality and safety standards here on Earth – and in galaxies far, far away!', '3700000', 3, '2021-11-11 20:56:26', '2021-11-11 20:56:26', 1, 'upload/products/75288/TYfPT.png'),
(28, '75256', 1, 'Kylo Ren\'s Shuttle™', 'Play out space-faring LEGO® Star Wars™ adventures with 75256 Kylo Ren’s Shuttle. This brick-built version of the Supreme Leader’s personal transport ship from the Star Wars: The Rise of Skywalker movie has lots of details to excite kids and collectors alike, such as a rotatable right engine for folding in the wings when landing and the ability to shorten the wings by folding the top part down. There\'s also an opening access ramp, 2 spring-loaded shooters and a cockpit with space to sit Supreme Leader Kylo Ren and 2 minifigures behind. As well as the Supreme Leader, this great LEGO Star Wars ship also includes General Pryde, a Sith Trooper, First Order Stormtrooper and 2 Knights of Ren for instant play action.\r\n\r\nIncludes 6 LEGO® Star Wars™ minifigures: Supreme Leader Kylo Ren, General Pryde, a Sith Trooper, a First Order Stormtrooper and 2 Knights of Ren.\r\nKylo Ren’s command shuttle features rotatable right engine for folding in the wings when landing, the ability to shorten the wings by folding the top part down, 2 spring-loaded shooters, lowering access ramp and a 3-minifigure cockpit.\r\nWeapons include Kylo Ren\'s Lightsaber, General Pryde\'s blaster pistol, the Sith Trooper\'s blaster rifle, the First Order Stormtrooper’s blaster and the Knights of Ren’s axe and mace.\r\nIncludes a new-for-October-2019 Knights of Ren helmet design.\r\nInspire role-play scenes from the Star Wars: The Rise of Skywalker movie with Kylo Ren’s personal transport ship.\r\nMakes a great birthday gift, Christmas present or just a Star Wars™ gift for any occasion.\r\nLEGO® Star Wars™ ship with wings extended measures over 13” (35cm) high, 8” (21cm) long and 19” (50cm) wide.', '3000000', 2, '2021-11-11 20:59:27', '2021-11-11 20:59:27', 1, 'upload/products/75256/VCGJn.png'),
(29, '75301', 1, 'Luke Skywalker’s X-Wing Fighter™', 'Children become heroes in their own epic stories with this cool LEGO® brick version of Luke Skywalker’s X-wing Fighter (75301) from the classic Star Wars™ trilogy. It’s packed with authentic details to delight fans, including an opening LEGO minifigure cockpit with space behind for R2-D2, wings that can be switched to attack position at the touch of a button, retractable landing gear and 2 spring-loaded shooters.\r\n\r\nPlay and display\r\nThis awesome building toy for kids features Luke Skywalker, Princess Leia and General Dodonna LEGO minifigures, each with weapons, including Luke’s lightsaber, plus an R2-D2 LEGO droid figure.\r\n\r\nThe best buildable toys for kids\r\nThe LEGO Group has been recreating iconic starships, vehicles, locations and characters from the Star Wars universe since 1999. LEGO Star Wars is now its most successful theme with fun gift ideas for creative kids, and adults too.\r\n\r\nRecreate scenes from the classic Star Wars™ trilogy with this awesome building toy for kids, featuring a LEGO® brick-built version of Luke Skywalker’s iconic X-wing Fighter (75301).\r\nIncludes Luke Skywalker, Princess Leia and General Dodonna LEGO® minifigures, each with weapons including Luke’s lightsaber, plus an R2-D2 LEGO droid figure for role-play adventures.\r\nThe X-wing features an opening LEGO® minifigure cockpit, space for R2-D2, wings that can be switched to attack position at the touch of a button, retractable landing gear and 2 spring-loaded shooters.\r\nFun to build and play with solo or as a group activity, this building toy makes a super birthday present, holiday gift or surprise treat for creative kids and any Star Wars™ fan aged 9 and up.\r\nThis starfighter construction model measures over 3 in. (8 cm) high, 12.5 in. (31 cm) long and 11 in. (28 cm) wide, and looks awesome displayed in a child’s bedroom between playtime missions.\r\nBuying for a big Star Wars™ fan who is a LEGO® beginner? Don’t worry. This set comes with step-by-step, illustrated instructions so they can build with Jedi-like confidence.\r\nLEGO® Star Wars™ sets are fabulous for kids (and adult fans) to recreate scenes from the saga, dream up original stories or just build and proudly display the construction models.\r\nEver since 1958, LEGO® components have met stringent industry standards, meaning they are compatible and connect consistently – no need to use the Force!\r\nLEGO® components are tested in almost every way imaginable to ensure they meet rigorous safety standards.\r\n', '2500000', 2, '2021-11-11 21:01:15', '2021-11-11 21:01:15', 1, 'upload/products/75301/GzWoE.png'),
(30, '75300', 1, 'Imperial TIE Fighter™', 'Kids can role-play as the villains from the classic Star Wars™ trilogy with this cool LEGO® brick version of the Imperial TIE Fighter (75300). Capturing the authentic, sleek design of an iconic starfighter in the Imperial fleet, it features an opening LEGO minifigure cockpit and 2 spring-loaded shooters.\r\n\r\nRole-play adventures\r\nThere are also 2 LEGO minifigures: a TIE Fighter Pilot with a blaster pistol and Stormtrooper with a blaster, plus an NI-L8 Protocol Droid to inspire fun, creative role play and storytelling.\r\n\r\nStar Wars action in LEGO style\r\nThe LEGO Group has been creating brick-built versions of iconic Star Wars starfighters, vehicles, locations and characters since 1999. It’s become a hugely successful theme with an awesome assortment of toy building sets and the best gift ideas for kids and fans of all ages.\r\n\r\nFans can build their own missile-shooting LEGO® brick version of the iconic Imperial TIE Fighter (75300) and reimagine scenes from the classic Star Wars™ trilogy with this buildable playset.\r\nIncludes 2 LEGO® Star Wars™ minifigures: a TIE Fighter Pilot with a blaster pistol and a Stormtrooper with a blaster, plus an NI-L8 Protocol Droid LEGO figure for role-play adventures.\r\nThe TIE Fighter features an opening LEGO® minifigure cockpit and 2 spring-loaded shooters for action-packed play.\r\nGreat for solo building or sharing the fun with friends and family, this set makes the best birthday present, holiday gift or surprise treat for creative kids and any Star Wars™ fan aged 8 and up.\r\nMeasuring over 6.5 in. (17 cm) high, 5.5 in. (14 cm) long and 6 in. (15 cm) wide, it makes a striking display piece in any child’s bedroom between playtime battles.\r\nLooking for an engaging building toy for a child who is new to LEGO® sets? This 432-piece Star Wars™ set comes with clear instructions so they can build independently and with Jedi-level confidence.\r\nLEGO® Star Wars™ sets are fantastic for kids (and adult fans) to recreate scenes from the saga, play out their own creative stories or just build and display the collectible construction models.\r\nEver since 1958, LEGO® components have met stringent industry standards to ensure they are compatible and connect consistently – no need to use the Force.\r\nLEGO® components are dropped, heated, crushed, twisted and rigorously analyzed to satisfy demanding safety standards.', '2700000', 3, '2021-11-11 21:02:59', '2021-11-11 21:02:59', 1, 'upload/products/75300/3DI3E.png'),
(31, '75316', 1, 'Mandalorian Starfighter™', 'Let kids create a detailed, buildable LEGO® model of a Mandalorian Starfighter (75316) and relive memorable Star Wars: The Clone Wars action. It features an opening LEGO minifigure cockpit for 2 LEGO minifigures, 2 stud shooters, 2 spring-loaded shooters and adjustable wings – rotate them vertically for landing or fold them down and rotate the cockpit for an authentic streamlined flight formation.\r\n\r\nEpic adventures\r\nThere are 3 new-for-August-2021 LEGO minifigures of Mandalorian warriors Bo-Katan Kryze, Gar Saxon and a Mandalorian Loyalist, plus 5 blaster pistols and a jetpack for each minifigure to spark children’s imaginations. This fun building toy includes step-by-step instructions and makes an awesome gift for Star Wars™ fans and any creative kid.\r\n\r\nGalaxy of fun\r\nThe LEGO Group has been recreating starships, vehicles, locations and characters from the Star Wars universe since 1999. LEGO Star Wars has become its most successful theme with wonderful sets to delight fans of all ages.\r\n\r\nPassionate Star Wars: The Clone Wars fans will love building and playing with this highly detailed LEGO® brick version of a Mandalorian Starfighter (75316).\r\nIncludes 3 new-for-August-2021 LEGO® minifigures: Bo-Katan Kryze, Gar Saxon and a Mandalorian Loyalist, all with blaster pistols (5 in total) and jetpacks, to inspire imaginative play.\r\nFeatures an opening 2 LEGO® minifigure cockpit, 2 stud shooters, 2 spring-loaded shooters and adjustable wings – rotate vertically for landing; fold them down and rotate the cockpit for flight mode.\r\nGreat for solo or group play, this fun, action-packed building toy makes an awesome birthday present, holiday gift or special surprise for creative kids aged 9 and up.\r\nThe Mandalorian Starfighter measures over 2.5 in. (6 cm) high, 13 in. (33 cm) long and 11.5 in. (30 cm) wide and makes a striking display piece between playtime missions.\r\nThinking of buying this 544-piece building set for a Star Wars™ fan who is a LEGO® beginner? No problem. Step-by-step instructions are included so they can build with Jedi-like confidence.\r\nThe LEGO® Star Wars™ range has sets to excite people of all ages, whether they want to recreate scenes from the saga, role-play their own stories or just display the collectible construction models.\r\nEver since 1958, LEGO® components have met stringent industry quality standards to ensure that they connect simply and securely.\r\nLEGO® bricks and pieces are dropped, heated, crushed, twisted and rigorously analyzed to meet stringent global safety standards.', '3400000', 2, '2021-11-11 21:05:32', '2021-11-11 21:05:32', 1, 'upload/products/75316/zpKtj.png'),
(32, '75251', 1, 'Darth Vader\'s Castle', 'Set the scene for action on planet Mustafar with LEGO® Star Wars 75251 Darth Vader’s Castle! This building set of Vader’s menacing-looking home features lots of great details, including a brick-built lava flow, an underground hangar with mouse droid and docking station, ancient Sith shrine with holocron, racks for extra ammunition, and secret compartments hiding more Sith relics. The hangar also houses a buildable TIE Advanced Fighter model with stud shooters and space to sit the Darth Vader™ minifigure. There’s also a bacta tank, Darth Vader’s meditation chamber with holographic communication unit, and a meeting platform at the top with a defensive stud-shooter cannon. As well as classic Darth Vader, the set also includes a bacta tank version of the Dark Lord, plus 2 Royal Guards and an Imperial Transport Pilot.', '4000000', 3, '2021-11-11 21:07:59', '2021-11-11 21:07:59', 1, 'upload/products/75251/Fgn0h.png');
INSERT INTO `product` (`id`, `product_code`, `theme_id`, `name`, `description`, `price`, `quantity`, `created_date`, `modified_date`, `status`, `image`) VALUES
(33, '75249', 1, 'Resistance Y-Wing Starfighter™', 'Inspire young minds and collectors with this LEGO® Star Wars™ 75249 Resistance Y-Wing Starfighter model. This updated version of the classic fighter-bomber, a.k.a. wishbone, featured in the Star Wars: The Rise of Skywalker movie, has a new-for-October-2019 color scheme, opening cockpit with space for a minifigure inside, firing spring-loaded shooters and dropping bombs that will inspire kids to create action-packed Star Wars scenes with their friends. With Poe Dameron, Zorii Bliss and First Order Snowtrooper Star Wars characters, plus D-O and astromech droid LEGO figures, this starship playset makes a great addition to any fan’s collection.', '2500000', 3, '2021-11-11 21:10:01', '2021-11-11 21:10:01', 1, 'upload/products/75249/EHiqs.png'),
(34, '75284', 1, 'Knights of Ren™ Transport Ship', 'Fans can relive epic Star Wars: The Rise of Skywalker action scenes with this Knights of Ren Transport Ship (75284) LEGO® building set. Hidden ‘skis’ underneath this Star Wars™ villains’ starship replicate the hover effect, and it has 2 opening cockpits for the Knights of Ren, a compartment for a captured LEGO minifigure and 2 spring-loaded shooters to inspire creative play.\r\n\r\nKnights of Ren vs. Rey\r\nBrilliant for solo or group play, this spacecraft building kit comes with 3 LEGO Star Wars minifigures – Rey and 2 Knights of Ren – for role-play battles. It combines brilliantly with other LEGO Star Wars construction playsets for even more creative fun and it makes an eye-catching display piece in any room between playtime missions.\r\n\r\nGreat gifts!\r\nThe LEGO Group has been recreating iconic starships, vehicles, locations and characters from the legendary Star Wars universe since 1999. LEGO Star Wars is now its most successful theme with top birthday, holiday and surprise gifts for kids and adults.', '1500000', 0, '2021-11-11 21:11:16', '2021-11-11 21:11:16', 1, 'upload/products/75284/yz9wa.png'),
(35, '75302', 1, 'Imperial Shuttle™', '<p>Fans can play out exciting scenes from the classic Star Wars™ trilogy with this buildable Imperial Shuttle (75302) model. The elegant design of the shuttle is beautifully recreated in LEGO® bricks, with an opening minifigure cockpit, opening main compartment with space for 2 LEGO minifigures, foldable wings for flight and landing mode, plus 2 stud shooters. Between playtime adventures, it makes a cool display piece. Role-play fun This awesome building toy for kids also includes Darth Vader,Imperial Officer and Luke Skywalker LEGO minifigures with lightsabers and a blaster pistol. Awesome gifts The LEGO Group has been recreating iconic starships, vehicles, locations and characters from the Star Wars universe since 1999. LEGO Star Wars is now its most successful theme, with fun, creative gift ideas for fans of all ages.</p>', '1500000', 0, '2021-11-11 21:12:56', '2021-11-23 10:53:48', 1, 'upload/products/75302/qM7a5.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_image`
--

DROP TABLE IF EXISTS `product_image`;
CREATE TABLE IF NOT EXISTS `product_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(100) NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_IMAGE_PRODUCT` (`product_code`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `product_image`
--

INSERT INTO `product_image` (`id`, `product_code`, `image`) VALUES
(1, '42131', 'upload/products/42131/ilMEm.png'),
(2, '42131', 'upload/products/42131/k5Fzf.png'),
(3, '42131', 'upload/products/42131/MRfmL.png'),
(4, '42131', 'upload/products/42131/trQEk.png'),
(5, '42112', 'upload/products/42112/KNBKv.png'),
(6, '42112', 'upload/products/42112/1Pj92.png'),
(7, '42112', 'upload/products/42112/o3D0u.png'),
(8, '42112', 'upload/products/42112/KgMP9.png'),
(13, '42121', 'upload/products/42121/I21Tt.png'),
(14, '42121', 'upload/products/42121/BVY85.png'),
(15, '42121', 'upload/products/42121/5Qona.png'),
(16, '42121', 'upload/products/42121/j6JPX.png'),
(17, '42095', 'upload/products/42095/FcR7i.png'),
(18, '42095', 'upload/products/42095/McBre.png'),
(19, '42095', 'upload/products/42095/mPbSF.png'),
(20, '71738', 'upload/products/71738/gvafO.png'),
(21, '71738', 'upload/products/71738/KJWW6.png'),
(22, '71738', 'upload/products/71738/eA0M0.png'),
(23, '71722', 'upload/products/71722/Qrxt7.png'),
(24, '71722', 'upload/products/71722/Qm3pd.png'),
(28, '71742', 'upload/products/71742/3ddCJ.png'),
(29, '71742', 'upload/products/71742/bUUZS.png'),
(30, '71742', 'upload/products/71742/AhNwb.png'),
(31, '75725', 'upload/products/75725/OKHQz.png'),
(32, '75725', 'upload/products/75725/TmuQX.png'),
(33, '75725', 'upload/products/75725/S7Nir.png'),
(34, '75315', 'upload/products/75315/X4Hco.png'),
(35, '75315', 'upload/products/75315/lxvoy.png'),
(36, '75315', 'upload/products/75315/TeEM3.png'),
(37, '75257', 'upload/products/75257/Qh1IW.png'),
(38, '21056', 'upload/products/21056/pmGza.png'),
(80, '21504', 'upload/products/21504/oRoBl.png'),
(92, '75309', 'upload/products/75309/O30Xw.png'),
(93, '75309', 'upload/products/75309/xtIQj.png'),
(94, '75309', 'upload/products/75309/LZ47r.png'),
(95, '75309', 'upload/products/75309/o1p0X.png'),
(96, '75309', 'upload/products/75309/0Ujwc.png'),
(97, '75288', 'upload/products/75288/TYfPT.png'),
(98, '75288', 'upload/products/75288/dQN7S.png'),
(99, '75256', 'upload/products/75256/VCGJn.png'),
(100, '75256', 'upload/products/75256/5I2Ln.png'),
(101, '75256', 'upload/products/75256/FvX9X.png'),
(102, '75301', 'upload/products/75301/GzWoE.png'),
(103, '75301', 'upload/products/75301/XCUEQ.png'),
(104, '75300', 'upload/products/75300/3DI3E.png'),
(105, '75316', 'upload/products/75316/zpKtj.png'),
(106, '75316', 'upload/products/75316/yVoeJ.png'),
(107, '75316', 'upload/products/75316/akfrc.png'),
(108, '75251', 'upload/products/75251/Fgn0h.png'),
(109, '75251', 'upload/products/75251/NSJv0.png'),
(110, '75249', 'upload/products/75249/EHiqs.png'),
(111, '75249', 'upload/products/75249/MTHeV.png'),
(112, '75284', 'upload/products/75284/yz9wa.png'),
(167, '75302', 'upload/products/75302/qM7a5.png'),
(168, '75302', 'upload/products/75302/7Mdie.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reset_password`
--

DROP TABLE IF EXISTS `reset_password`;
CREATE TABLE IF NOT EXISTS `reset_password` (
  `token` varchar(12) NOT NULL,
  `email` text NOT NULL,
  `expired` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`token`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `theme`
--

DROP TABLE IF EXISTS `theme`;
CREATE TABLE IF NOT EXISTS `theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `theme`
--

INSERT INTO `theme` (`id`, `theme`) VALUES
(1, 'Star Wars™'),
(2, 'Technic™'),
(3, 'NINJAGO®'),
(4, 'Architecture');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `created_date`, `modified_date`) VALUES
(1, 'duytrung', '19004222@st.vlute.edu.vn', '$2y$10$nGDKDQ/YZiDGnJ1Vg75BO.2I1Eu4Sr1qRHD5neazvufDY7oWBOMNu', '2021-11-13 10:32:50', '2021-11-24 19:47:34'),
(2, 'nguyentrung', 'duytrung341@gmail.com', '$2y$10$z4JwUYdFY2oCcAvTXVCoquJ9pEVrPLYx1g5t.bgeBzoGPjOU/X5p6', '2021-11-23 19:33:58', '2021-11-23 19:44:15');

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_CART_PRODUCT` FOREIGN KEY (`product_code`) REFERENCES `product` (`product_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CART_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`payment`) REFERENCES `payment` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`account`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders_detail`
--
ALTER TABLE `orders_detail`
  ADD CONSTRAINT `FK_ORDERS_DETAIL_ORDERS` FOREIGN KEY (`orders_code`) REFERENCES `orders` (`code`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_ORDERS_DETAIL_PRODUCT` FOREIGN KEY (`product_code`) REFERENCES `product` (`product_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_PRODUCT_THEME` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `FK_IMAGE_PRODUCT` FOREIGN KEY (`product_code`) REFERENCES `product` (`product_code`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
