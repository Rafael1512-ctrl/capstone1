-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_tixly
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acara`
--

DROP TABLE IF EXISTS `acara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acara` (
  `event_id` varchar(14) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `schedule_time` datetime NOT NULL,
  `ticket_quota` int(11) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `report`
--

DROP TABLE IF EXISTS `report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report` (
  `report_id` varchar(14) NOT NULL,
  `event_id` varchar(14) NOT NULL,
  `total_sales` int(11) NOT NULL,
  `total_visitor` int(11) NOT NULL,
  `total_revenue` double DEFAULT NULL,
  PRIMARY KEY (`report_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `report_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `acara` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `ticket_id` varchar(14) NOT NULL,
  `type` enum('VIP','Regular') NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `event_id` varchar(14) NOT NULL,
  `ticket_status` enum('Active','Used','Expired') NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`ticket_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `acara` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaksi` (
  `transaction_id` varchar(14) NOT NULL,
  `user_id` varchar(7) DEFAULT NULL,
  `ticket_id` varchar(8) DEFAULT NULL,
  `payment_date` datetime NOT NULL,
  `payment_method` enum('Virtual Account','QRIS') NOT NULL,
  `payment_status` enum('Verified','Pending','Decline') NOT NULL,
  `total_ticket` int(11) NOT NULL,
  `total_amount` double DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `user_id` (`user_id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` varchar(7) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'db_tixly'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AddTransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddTransaction`(IN `p_user_id` VARCHAR(50), IN `p_event_id` VARCHAR(50), IN `p_payment_method` ENUM('Cash','Transfer','CreditCard'), IN `p_payment_status` ENUM('Verified','Pending','Decline'), IN `p_total_ticket` INT)
BEGIN

    DECLARE v_transaction_id VARCHAR(20);

    DECLARE v_ticket_id VARCHAR(20);

    DECLARE v_price DECIMAL(10,2);

    DECLARE v_total_amount DECIMAL(10,2);

    DECLARE v_quota INT;



    -- Generate ID

    CALL GenerateTransactionID(v_transaction_id);

    CALL GenerateTicketID(v_ticket_id);



    -- Ambil harga tiket & quota

    SELECT price, ticket_quota

    INTO v_price, v_quota

    FROM ticket

    WHERE event_id = p_event_id

    LIMIT 1;



    -- Cek quota

    IF p_total_ticket > v_quota THEN

        SIGNAL SQLSTATE '45000'

        SET MESSAGE_TEXT = 'Quota tiket tidak mencukupi';

    END IF;



    -- Hitung total amount

    SET v_total_amount = p_total_ticket * v_price;



    -- Insert transaksi

    INSERT INTO transaksi (

        transaction_id, user_id, ticket_id, payment_date,

        payment_method, payment_status, total_ticket, total_amount

    ) VALUES (

        v_transaction_id, p_user_id, v_ticket_id, NOW(),

        p_payment_method, p_payment_status, p_total_ticket, v_total_amount

    );



    -- Jika payment status Verified, kurangi quota tiket

    IF p_payment_status = 'Verified' THEN

        UPDATE ticket

        SET ticket_quota = ticket_quota - p_total_ticket

        WHERE event_id = p_event_id;

    END IF;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AddUser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddUser`(IN `p_email` VARCHAR(100), IN `p_name` VARCHAR(100), IN `p_pass` VARCHAR(100), IN `p_role_id` INT)
BEGIN

    DECLARE new_id VARCHAR(10);

    DECLARE last_num INT;



    -- Ambil angka terakhir dari user_id

    SELECT IFNULL(MAX(CAST(SUBSTRING(user_id, 3) AS UNSIGNED)), 0) 

    INTO last_num

    FROM users;



    -- Generate ID baru dengan format T-xxxxx

    SET new_id = CONCAT('T-', LPAD(last_num + 1, 5, '0'));



    -- Insert user baru

    INSERT INTO users (user_id, name,email, pass, role_id)

    VALUES (new_id, p_name,p_email,  p_pass, p_role_id);

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GenerateEventID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GenerateEventID`(OUT `p_event_id` VARCHAR(20))
BEGIN

    DECLARE v_today VARCHAR(6);

    DECLARE v_seq INT;



    -- Format tanggal: DDMMYY

    SET v_today = DATE_FORMAT(NOW(), '%d%m%y');



    -- Ambil nomor urut terakhir untuk hari ini

    SELECT IFNULL(MAX(CAST(SUBSTRING(event_id, 10) AS UNSIGNED)), 0) + 1

    INTO v_seq

    FROM acara

    WHERE SUBSTRING(event_id, 4, 6) = v_today;



    -- Generate event_id

    SET p_event_id = CONCAT('TC-', v_today, '-', LPAD(v_seq, 4, '0'));

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GenerateReportID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GenerateReportID`(OUT `p_report_id` VARCHAR(20))
BEGIN

    DECLARE v_today VARCHAR(6);

    DECLARE v_seq INT;



    -- Format tanggal: DDMMYY

    SET v_today = DATE_FORMAT(NOW(), '%d%m%y');



    -- Ambil nomor urut terakhir untuk hari ini

    SELECT IFNULL(MAX(CAST(SUBSTRING(report_id, 10) AS UNSIGNED)), 0) + 1

    INTO v_seq

    FROM report

    WHERE SUBSTRING(report_id, 4, 6) = v_today;



    -- Generate report_id

    SET p_report_id = CONCAT('TC-', v_today, '-', LPAD(v_seq, 4, '0'));

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GenerateTicketID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GenerateTicketID`(OUT `p_ticket_id` VARCHAR(20))
BEGIN

    DECLARE v_today VARCHAR(6);

    DECLARE v_seq INT;



    -- Format tanggal: DDMMYY

    SET v_today = DATE_FORMAT(NOW(), '%d%m%y');



    -- Ambil nomor urut terakhir untuk hari ini

    SELECT IFNULL(MAX(CAST(SUBSTRING(ticket_id, 10) AS UNSIGNED)), 0) + 1

    INTO v_seq

    FROM ticket

    WHERE SUBSTRING(ticket_id, 4, 6) = v_today;



    -- Generate ticket_id

    SET p_ticket_id = CONCAT('TC-', v_today, '-', LPAD(v_seq, 4, '0'));

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GenerateTransactionID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GenerateTransactionID`(OUT `p_transaction_id` VARCHAR(20))
BEGIN

    DECLARE v_today VARCHAR(6);

    DECLARE v_seq INT;



    -- Format tanggal: DDMMYY

    SET v_today = DATE_FORMAT(NOW(), '%d%m%y');



    -- Ambil nomor urut terakhir untuk hari ini

    SELECT IFNULL(MAX(CAST(SUBSTRING(transaction_id, 10) AS UNSIGNED)), 0) + 1

    INTO v_seq

    FROM transaksi

    WHERE SUBSTRING(transaction_id, 4, 6) = v_today;



    -- Generate transaction_id

    SET p_transaction_id = CONCAT('TC-', v_today, '-', LPAD(v_seq, 4, '0'));

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `UpdateReportRevenue` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateReportRevenue`(IN `p_event_id` VARCHAR(50))
BEGIN

    DECLARE v_total_revenue DOUBLE;



    -- Hitung total revenue dari transaksi Verified

    SELECT IFNULL(SUM(total_amount),0)

    INTO v_total_revenue

    FROM transaksi

    WHERE event_id = p_event_id

      AND payment_status = 'Verified';



    -- Update ke tabel report

    UPDATE report

    SET total_revenue = v_total_revenue

    WHERE event_id = p_event_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `UpdateReportSales` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateReportSales`(IN `p_event_id` VARCHAR(50))
BEGIN

    DECLARE v_total_sales INT;



    -- Hitung total sales dari transaksi Verified

    SELECT IFNULL(SUM(total_ticket),0)

    INTO v_total_sales

    FROM transaksi

    WHERE event_id = p_event_id

      AND payment_status = 'Verified';



    -- Update ke tabel report

    UPDATE report

    SET total_sales = v_total_sales

    WHERE event_id = p_event_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `UpdateReportVisitor` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateReportVisitor`(IN `p_event_id` VARCHAR(50))
BEGIN

    DECLARE v_total_visitor INT;



    -- Hitung jumlah tiket yang sudah digunakan (status = 'Used')

    SELECT COUNT(*)

    INTO v_total_visitor

    FROM ticket

    WHERE event_id = p_event_id

      AND ticket_status = 'Used';



    -- Update ke tabel report

    UPDATE report

    SET total_visitor = v_total_visitor

    WHERE event_id = p_event_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-21  7:46:17
