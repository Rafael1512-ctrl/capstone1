DROP PROCEDURE IF EXISTS AddTransaction;
DELIMITER ;;
CREATE PROCEDURE AddTransaction(
    IN p_user_id VARCHAR(50), 
    IN p_event_id VARCHAR(50), 
    IN p_ticket_type_id INT,
    IN p_payment_method ENUM('Virtual Account','QRIS'), 
    IN p_payment_status ENUM('Verified','Pending','Decline'), 
    IN p_total_ticket INT
)
BEGIN
    DECLARE v_transaction_id VARCHAR(20);
    DECLARE v_price DOUBLE;
    DECLARE v_quota_left INT;
    DECLARE v_total_amount DOUBLE;
    DECLARE i INT DEFAULT 0;
    DECLARE v_ticket_id VARCHAR(20);

    -- Generate ID Transaksi
    CALL GenerateTransactionID(v_transaction_id);

    -- Ambil harga tiket & quota dari ticket_type
    SELECT price, (quantity_total - quantity_sold)
    INTO v_price, v_quota_left
    FROM ticket_type
    WHERE id = p_ticket_type_id AND event_id = p_event_id
    LIMIT 1;

    -- Cek quota
    IF p_total_ticket > v_quota_left THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quota tiket tidak mencukupi';
    END IF;

    -- Hitung total amount
    SET v_total_amount = p_total_ticket * v_price;

    -- Insert transaksi (Kita simpan null dulu untuk ticket_id di tabel transaksi jika satu transaksi banyak tiket)
    -- Namun di db_tixly tabel transaksi ada ticket_id tunggal. 
    -- Kita akan generate SATU ticket_id utk mewakili transaksi ini atau loop.
    -- Sesuai schema, transaksi punya SATU ticket_id. Kita pakai yang pertama saja.
    
    CALL GenerateTicketID(v_ticket_id);

    INSERT INTO transaksi (
        transaction_id, user_id, ticket_id, payment_date,
        payment_method, payment_status, total_ticket, total_amount
    ) VALUES (
        v_transaction_id, p_user_id, v_ticket_id, NOW(),
        p_payment_method, p_payment_status, p_total_ticket, v_total_amount
    );

    -- Buat record di tabel ticket (satu ticket_id tadi)
    INSERT INTO ticket (
        ticket_id, qr_code, event_id, ticket_type_id, transaction_id, ticket_status
    ) VALUES (
        v_ticket_id, CONCAT('QR-', v_ticket_id), p_event_id, p_ticket_type_id, v_transaction_id, 'Active'
    );

    -- Jika payment status Verified, tambahkan quantity_sold
    IF p_payment_status = 'Verified' THEN
        UPDATE ticket_type
        SET quantity_sold = quantity_sold + p_total_ticket
        WHERE id = p_ticket_type_id;
    END IF;

END ;;
DELIMITER ;
