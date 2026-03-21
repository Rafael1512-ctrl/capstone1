-- Script to Fix Database Schema `db_tixly`
-- This adds the missing columns and fixes the Stored Procedures.

USE db_tixly;

-- 1. Modify ACARA table to support Organizer, Category, Status, and Banner
ALTER TABLE `acara` 
ADD COLUMN `organizer_id` varchar(7) NULL AFTER `event_id`,
ADD COLUMN `category_id` INT NULL AFTER `organizer_id`,
ADD COLUMN `banner_url` varchar(255) NULL AFTER `category_id`,
ADD COLUMN `status` enum('draft','published','cancelled') DEFAULT 'draft' AFTER `banner_url`;

ALTER TABLE `acara`
ADD CONSTRAINT `fk_acara_organizer` FOREIGN KEY (`organizer_id`) REFERENCES `users`(`user_id`);

-- 2. Create TICKET_TYPE table (replacing the hardcoded ENUM in Ticket)
CREATE TABLE `ticket_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` varchar(14) NOT NULL,
  `name` varchar(50) NOT NULL, -- e.g. VIP, Regular, CAT 1
  `price` double NOT NULL,
  `quantity_total` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_tickettype_acara` FOREIGN KEY (`event_id`) REFERENCES `acara`(`event_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Modify TICKET table to link to TICKET_TYPE and order
ALTER TABLE `ticket`
ADD COLUMN `ticket_type_id` INT NULL AFTER `event_id`,
ADD COLUMN `transaction_id` varchar(14) NULL AFTER `ticket_type_id`,
ADD CONSTRAINT `fk_ticket_type` FOREIGN KEY (`ticket_type_id`) REFERENCES `ticket_type`(`id`),
ADD CONSTRAINT `fk_ticket_transaksi` FOREIGN KEY (`transaction_id`) REFERENCES `transaksi`(`transaction_id`);

-- Remove the old 'type' enum and 'price' from ticket since it belongs to ticket_type
ALTER TABLE `ticket` DROP COLUMN `type`;
ALTER TABLE `ticket` DROP COLUMN `price`;

-- 4. Fix AddTransaction Stored Procedure
DROP PROCEDURE IF EXISTS `AddTransaction`;

DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddTransaction`(
    IN `p_user_id` VARCHAR(50), 
    IN `p_event_id` VARCHAR(50), 
    IN `p_ticket_type_id` INT,
    IN `p_payment_method` ENUM('Virtual Account','QRIS'), 
    IN `p_payment_status` ENUM('Verified','Pending','Decline'), 
    IN `p_total_ticket` INT
)
BEGIN
    DECLARE v_transaction_id VARCHAR(20);
    DECLARE v_ticket_id VARCHAR(20);
    DECLARE v_price DECIMAL(10,2);
    DECLARE v_total_amount DECIMAL(10,2);
    DECLARE v_quota_total INT;
    DECLARE v_quota_sold INT;
    DECLARE i INT DEFAULT 1;

    -- Generate Transaction ID
    CALL GenerateTransactionID(v_transaction_id);

    -- Get Price and Quota from Ticket_Type
    SELECT price, quantity_total, quantity_sold 
    INTO v_price, v_quota_total, v_quota_sold
    FROM ticket_type 
    WHERE id = p_ticket_type_id AND event_id = p_event_id 
    LIMIT 1;

    -- Check Quota
    IF (v_quota_sold + p_total_ticket) > v_quota_total THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quota tiket tidak mencukupi';
    END IF;

    -- Calculate Amount
    SET v_total_amount = p_total_ticket * v_price;

    -- Insert into Transaksi
    INSERT INTO transaksi (
        transaction_id, user_id, ticket_id, payment_date,
        payment_method, payment_status, total_ticket, total_amount
    ) VALUES (
        v_transaction_id, p_user_id, NULL, NOW(),
        p_payment_method, p_payment_status, p_total_ticket, v_total_amount
    );

    -- Loop to Generate Tickets
    WHILE i <= p_total_ticket DO
        CALL GenerateTicketID(v_ticket_id);
        
        INSERT INTO ticket (
            ticket_id, qr_code, event_id, ticket_type_id, ticket_status, transaction_id
        ) VALUES (
            v_ticket_id, UUID(), p_event_id, p_ticket_type_id, 'Active', v_transaction_id
        );
        
        SET i = i + 1;
    END WHILE;

    -- If Verified, increase quantity_sold
    IF p_payment_status = 'Verified' THEN
        UPDATE ticket_type
        SET quantity_sold = quantity_sold + p_total_ticket
        WHERE id = p_ticket_type_id;
    END IF;

END ;;
DELIMITER ;
