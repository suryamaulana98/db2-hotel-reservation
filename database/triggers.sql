-- ============================================================
-- HOTEL RESERVATION SYSTEM - TRIGGERS
-- Mata Kuliah: Basis Data 2
-- ============================================================

USE hotel_reservation;

-- ============================================================
-- TRIGGER 1: trg_booking_d_insert
-- Kapan: AFTER INSERT pada tabel booking_d
-- Fungsi: Otomatis mengubah status kamar menjadi 'terisi'
--          saat detail booking ditambahkan
-- ============================================================
DELIMITER //

CREATE TRIGGER trg_booking_d_insert
AFTER INSERT ON booking_d
FOR EACH ROW
BEGIN
    UPDATE rooms 
    SET status = 'terisi' 
    WHERE id_room = NEW.id_room;
END //

DELIMITER ;


-- ============================================================
-- TRIGGER 2: trg_booking_checkout
-- Kapan: AFTER UPDATE pada tabel booking_h
-- Fungsi: Otomatis mengubah status kamar menjadi 'tersedia'
--          saat status booking berubah ke 'checked_out'
-- ============================================================
DELIMITER //

CREATE TRIGGER trg_booking_checkout
AFTER UPDATE ON booking_h
FOR EACH ROW
BEGIN
    -- Jika status berubah menjadi 'checked_out'
    IF NEW.status_booking = 'checked_out' AND OLD.status_booking != 'checked_out' THEN
        UPDATE rooms 
        SET status = 'tersedia' 
        WHERE id_room IN (
            SELECT id_room FROM booking_d WHERE id_booking = NEW.id_booking
        );
    END IF;
    
    -- Jika status berubah menjadi 'cancelled'
    IF NEW.status_booking = 'cancelled' AND OLD.status_booking != 'cancelled' THEN
        UPDATE rooms 
        SET status = 'tersedia' 
        WHERE id_room IN (
            SELECT id_room FROM booking_d WHERE id_booking = NEW.id_booking
        );
    END IF;
END //

DELIMITER ;


-- ============================================================
-- TRIGGER 3: trg_log_booking
-- Kapan: AFTER INSERT pada tabel booking_h
-- Fungsi: Mencatat aktivitas booking baru ke tabel activity_logs
-- Contoh log: "Booking baru #5 dibuat oleh user ID 2"
-- ============================================================
DELIMITER //

CREATE TRIGGER trg_log_booking
AFTER INSERT ON booking_h
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (aktivitas, created_at)
    VALUES (
        CONCAT('Booking baru #', NEW.id_booking, ' dibuat oleh user ID ', NEW.id_user),
        NOW()
    );
END //

DELIMITER ;
