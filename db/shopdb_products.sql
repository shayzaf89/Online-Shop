-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 212.111.42.168    Database: shopdb
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `catalogNumber` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(2000) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `createdDateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discountPerCent` int(3) NOT NULL DEFAULT '0',
  `limitQuantity` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catalogNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Series 2 42mm 38mm WiFi Stainless Steel Sport Band Smartwatch','Apple Watch',150.00,50,'2019-01-05 09:07:36',20,10),(2,'Gear S3 Frontier 46mm Watch Stainless Steel Case Black Band R760','Samsung Galaxy',150.00,45,'2019-01-05 09:17:22',10,15),(3,'Heart Rate Monitor Bracelet Wristband for iOS Android','Waterproof Smart Watch',100.00,8,'2019-01-05 12:28:14',0,0),(4,'Smart Wristband Bracelet Watch OLED 50m Waterproof','Original Xiaomi Mi Band 3',170.00,15,'2019-01-05 12:28:26',15,20),(5,'Wristband Bracelet Pedometer Sport Fitness Tracker','ID115Plus BT Smart Watch',75.00,7,'2019-01-05 12:29:24',0,0),(6,'Smart Watch Phone Sport Watch For Android iOS','LEMFO LF23 Bluetooth Waterproof',20.00,15,'2019-01-05 12:29:40',0,0),(7,'HTC Samsung Android Phone Camera SIM Slot','LATEST DZ09 Bluetooth Smart Watch',200.00,7,'2019-01-05 12:31:21',0,0),(8,'Smart Watch For Android HTC Samsung iPhone iOS','Mate Wrist Waterproof Bluetooth',350.00,6,'2019-01-05 12:31:34',0,0);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-12 18:16:34
