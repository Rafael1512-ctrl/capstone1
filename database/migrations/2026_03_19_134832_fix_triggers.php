<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Hapus trigger lama
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_payments');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_payments');
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_ticket_reservations');
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_orders');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_organizer_dashboard');

        // Buat ulang dengan perbaikan
        DB::unprepared('
            CREATE TRIGGER before_insert_orders
            BEFORE INSERT ON orders
            FOR EACH ROW
            BEGIN
                IF NEW.order_number IS NULL THEN
                    SET NEW.order_number = CONCAT("ORD-", DATE_FORMAT(NOW(), "%Y%m%d"), "-", LPAD(FLOOR(RAND() * 10000), 4, "0"));
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_insert_payments
            AFTER INSERT ON payments
            FOR EACH ROW
            BEGIN
                DECLARE v_order_status VARCHAR(20);
                
                IF NEW.status = "success" THEN
                    UPDATE orders SET status = "paid", updated_at = NOW() WHERE id = NEW.order_id;
                    
                    UPDATE ticket_types tt
                    JOIN order_items oi ON tt.id = oi.ticket_type_id
                    SET tt.quantity_sold = tt.quantity_sold + oi.quantity
                    WHERE oi.order_id = NEW.order_id;
                    
                    INSERT INTO tickets (order_id, ticket_type_id, unique_code, created_at)
                    SELECT oi.order_id, oi.ticket_type_id, UUID(), NOW()
                    FROM order_items oi
                    CROSS JOIN (
                        SELECT 1 as n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
                    ) numbers
                    WHERE oi.order_id = NEW.order_id
                      AND numbers.n <= oi.quantity;
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_update_payments
            AFTER UPDATE ON payments
            FOR EACH ROW
            BEGIN
                IF NEW.status = "success" AND OLD.status != "success" THEN
                    UPDATE orders SET status = "paid", updated_at = NOW() WHERE id = NEW.order_id;
                    
                    UPDATE ticket_types tt
                    JOIN order_items oi ON tt.id = oi.ticket_type_id
                    SET tt.quantity_sold = tt.quantity_sold + oi.quantity
                    WHERE oi.order_id = NEW.order_id;
                    
                    INSERT INTO tickets (order_id, ticket_type_id, unique_code, created_at)
                    SELECT oi.order_id, oi.ticket_type_id, UUID(), NOW()
                    FROM order_items oi
                    CROSS JOIN (
                        SELECT 1 as n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
                    ) numbers
                    WHERE oi.order_id = NEW.order_id
                      AND numbers.n <= oi.quantity;
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER before_insert_ticket_reservations
            BEFORE INSERT ON ticket_reservations
            FOR EACH ROW
            BEGIN
                DECLARE available INT;
                
                SELECT tt.quantity_total - tt.quantity_sold - COALESCE(SUM(tr.quantity), 0)
                INTO available
                FROM ticket_types tt
                LEFT JOIN ticket_reservations tr ON tt.id = tr.ticket_type_id AND tr.expires_at > NOW()
                WHERE tt.id = NEW.ticket_type_id
                GROUP BY tt.id;
                
                IF available < NEW.quantity THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Stok tiket tidak mencukupi untuk reservasi";
                END IF;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE sp_organizer_dashboard(IN p_organizer_id BIGINT)
            BEGIN
                SELECT 
                    e.id AS event_id,
                    e.title AS event_title,
                    COUNT(DISTINCT o.id) AS total_orders,
                    SUM(o.total_amount) AS total_revenue,
                    AVG(o.total_amount) AS avg_order_value
                FROM events e
                LEFT JOIN orders o ON e.id = o.event_id AND o.status = "paid"
                WHERE e.organizer_id = p_organizer_id
                GROUP BY e.id, e.title;
                
                SELECT 
                    tt.event_id,
                    tt.id AS ticket_type_id,
                    tt.name AS ticket_name,
                    tt.price,
                    tt.quantity_sold,
                    (tt.quantity_sold * tt.price) AS revenue
                FROM ticket_types tt
                JOIN events e ON tt.event_id = e.id
                WHERE e.organizer_id = p_organizer_id
                ORDER BY tt.event_id, revenue DESC;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_orders');
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_payments');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_payments');
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_ticket_reservations');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_organizer_dashboard');
    }
};