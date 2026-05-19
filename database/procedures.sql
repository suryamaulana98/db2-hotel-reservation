-- ============================================================
-- HOTEL RESERVATION SYSTEM - STORED PROCEDURES
-- Mata Kuliah: Basis Data 2
-- ============================================================

USE hotel_reservation;

-- ============================================================
-- PROCEDURE 1: sp_proses_booking
-- Fungsi: Memproses booking lengkap (header + detail)
-- Alur:
--   1. Insert data ke booking_h (header)
--   2. Insert data ke booking_d (detail) untuk setiap kamar
--   3. Hitung subtotal = harga × jumlah malam
--   4. Hitung grand_total = SUM(subtotal) semua detail
--   5. Update grand_total di booking_h
--
-- Parameter:
--   IN  p_id_user       - ID user yang booking
--   IN  p_id_room       - ID kamar yang dipesan
--   IN  p_checkin        - Tanggal check-in
--   IN  p_checkout       - Tanggal check-out
--   OUT p_id_booking     - ID booking yang dibuat (output)
-- ============================================================
DELIMITER //

CREATE PROCEDURE sp_proses_booking(
    IN p_id_user    INT,
    IN p_id_room    INT,
    IN p_checkin    DATE,
    IN p_checkout   DATE,
    OUT p_id_booking INT
)
BEGIN
    DECLARE v_harga     DECIMAL(12,2);
    DECLARE v_malam     INT;
    DECLARE v_subtotal  DECIMAL(15,2);
    
    -- Hitung jumlah malam
    SET v_malam = DATEDIFF(p_checkout, p_checkin);
    
    -- Ambil harga kamar
    SELECT harga INTO v_harga FROM rooms WHERE id_room = p_id_room;
    
    -- Hitung subtotal
    SET v_subtotal = v_harga * v_malam;
    
    -- 1. Insert ke booking_h (header)
    INSERT INTO booking_h (tanggal_booking, id_user, grand_total, status_booking)
    VALUES (CURDATE(), p_id_user, 0, 'pending');
    
    -- Ambil ID booking yang baru dibuat
    SET p_id_booking = LAST_INSERT_ID();
    
    -- 2. Insert ke booking_d (detail)
    INSERT INTO booking_d (id_booking, id_room, tanggal_checkin, tanggal_checkout, harga, subtotal)
    VALUES (p_id_booking, p_id_room, p_checkin, p_checkout, v_harga, v_subtotal);
    
    -- 3. Update grand_total di booking_h
    UPDATE booking_h 
    SET grand_total = (
        SELECT COALESCE(SUM(subtotal), 0) 
        FROM booking_d 
        WHERE id_booking = p_id_booking
    )
    WHERE id_booking = p_id_booking;
    
END //

DELIMITER ;


-- ============================================================
-- PROCEDURE 2: sp_hitung_total_pembayaran
-- Fungsi: Menghitung total yang harus dibayar untuk suatu booking
-- Parameter:
--   IN  p_id_booking - ID booking
--   OUT p_total      - Total pembayaran (output)
-- ============================================================
DELIMITER //

CREATE PROCEDURE sp_hitung_total_pembayaran(
    IN p_id_booking INT,
    OUT p_total DECIMAL(15,2)
)
BEGIN
    SELECT COALESCE(SUM(subtotal), 0) INTO p_total
    FROM booking_d
    WHERE id_booking = p_id_booking;
END //

DELIMITER ;


-- ============================================================
-- PROCEDURE 3: sp_generate_invoice
-- Fungsi: Generate data invoice untuk booking tertentu
-- Menampilkan data lengkap booking beserta detail kamar
-- ============================================================
DELIMITER //

CREATE PROCEDURE sp_generate_invoice(
    IN p_id_booking INT
)
BEGIN
    -- Data header invoice
    SELECT 
        bh.id_booking,
        bh.tanggal_booking,
        u.nama AS nama_tamu,
        u.email,
        bh.grand_total,
        bh.status_booking,
        COALESCE(p.status_verifikasi, 'belum bayar') AS status_pembayaran
    FROM booking_h bh
    JOIN users u ON bh.id_user = u.id_user
    LEFT JOIN payments p ON bh.id_booking = p.id_booking
    WHERE bh.id_booking = p_id_booking;
    
    -- Data detail invoice
    SELECT 
        bd.id_detail,
        r.nomor_kamar,
        r.tipe_kamar,
        bd.tanggal_checkin,
        bd.tanggal_checkout,
        DATEDIFF(bd.tanggal_checkout, bd.tanggal_checkin) AS jumlah_malam,
        bd.harga,
        bd.subtotal
    FROM booking_d bd
    JOIN rooms r ON bd.id_room = r.id_room
    WHERE bd.id_booking = p_id_booking;
END //

DELIMITER ;
