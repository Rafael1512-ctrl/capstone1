DROP PROCEDURE IF EXISTS AddTransaction;
DELIMITER ;;
CREATE PROCEDURE AddTransaction(
    IN p_user_id VARCHAR(50), 
    IN p_event_id VARCHAR(50), 
    IN p_payment_method ENUM('Virtual Account','QRIS'), 
    IN p_payment_status ENUM('Verified','Pending','Decline'), 
    IN p_total_ticket INT
)
BEGIN
    DECLARE v_transaction_id VARCHAR(20);
    DECLARE v_ticket_id VARCHAR(20);
    DECLARE v_price DECIMAL(10,2);
    DECLARE v_quota INT;
    DECLARE v_total_amount DECIMAL(10,2);

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
