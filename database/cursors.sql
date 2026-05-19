-- ============================================================
-- HOTEL RESERVATION SYSTEM - CURSORS
-- Mata Kuliah: Basis Data 2
-- 
-- CURSOR digunakan di dalam Stored Procedure
-- untuk melakukan looping (iterasi) baris per baris
-- ============================================================

USE hotel_reservation;

-- ============================================================
-- CURSOR 1: sp_laporan_pendapatan
-- Fungsi: Menghitung total pendapatan hotel dari semua 
--          transaksi booking yang sudah checked_out
--
-- Cara Kerja Cursor:
--   1. DECLARE cursor → mendefinisikan query SELECT
--   2. OPEN cursor → eksekusi query, siapkan result set
--   3. FETCH cursor → ambil data baris per baris
--   4. LOOP → proses setiap baris (akumulasi total)
--   5. CLOSE cursor → tutup cursor, bebaskan memori
-- ============================================================
DELIMITER //

CREATE PROCEDURE sp_laporan_pendapatan(
    OUT p_total_pendapatan DECIMAL(15,2),
    OUT p_jumlah_transaksi INT
)
BEGIN
    DECLARE v_grand_total DECIMAL(15,2);
    DECLARE v_done INT DEFAULT FALSE;
    
    -- 1. DECLARE CURSOR: ambil grand_total dari booking yang sudah checkout
    DECLARE cur_pendapatan CURSOR FOR
        SELECT grand_total 
        FROM booking_h 
        WHERE status_booking = 'checked_out';
    
    -- Handler: set v_done = TRUE ketika tidak ada data lagi
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = TRUE;
    
    -- Inisialisasi variabel
    SET p_total_pendapatan = 0;
    SET p_jumlah_transaksi = 0;
    
    -- 2. OPEN CURSOR
    OPEN cur_pendapatan;
    
    -- 3-4. LOOP: FETCH dan proses baris per baris
    loop_pendapatan: LOOP
        FETCH cur_pendapatan INTO v_grand_total;
        
        -- Jika tidak ada data lagi, keluar dari loop
        IF v_done THEN
            LEAVE loop_pendapatan;
        END IF;
        
        -- Akumulasi total pendapatan
        SET p_total_pendapatan = p_total_pendapatan + v_grand_total;
        SET p_jumlah_transaksi = p_jumlah_transaksi + 1;
    END LOOP loop_pendapatan;
    
    -- 5. CLOSE CURSOR
    CLOSE cur_pendapatan;
END //

DELIMITER ;


-- ============================================================
-- CURSOR 2: sp_laporan_bulanan
-- Fungsi: Membuat laporan transaksi per bulan
-- Looping: Mengiterasi setiap booking_d untuk menghitung
--           pendapatan per bulan berdasarkan tanggal check-in
-- ============================================================
DELIMITER //

CREATE PROCEDURE sp_laporan_bulanan(
    IN p_tahun INT
)
BEGIN
    DECLARE v_bulan INT;
    DECLARE v_subtotal DECIMAL(15,2);
    DECLARE v_done INT DEFAULT FALSE;
    
    -- Cursor: ambil data bulanan
    DECLARE cur_bulanan CURSOR FOR
        SELECT 
            MONTH(bd.tanggal_checkin) AS bulan,
            bd.subtotal
        FROM booking_d bd
        JOIN booking_h bh ON bd.id_booking = bh.id_booking
        WHERE YEAR(bd.tanggal_checkin) = p_tahun
          AND bh.status_booking IN ('confirmed', 'checked_in', 'checked_out')
        ORDER BY bulan;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = TRUE;
    
    -- Temporary table untuk menyimpan hasil
    DROP TEMPORARY TABLE IF EXISTS tmp_laporan_bulanan;
    CREATE TEMPORARY TABLE tmp_laporan_bulanan (
        bulan INT,
        nama_bulan VARCHAR(20),
        total_pendapatan DECIMAL(15,2) DEFAULT 0,
        jumlah_transaksi INT DEFAULT 0
    );
    
    -- Insert 12 bulan
    INSERT INTO tmp_laporan_bulanan (bulan, nama_bulan) VALUES
        (1, 'Januari'), (2, 'Februari'), (3, 'Maret'),
        (4, 'April'), (5, 'Mei'), (6, 'Juni'),
        (7, 'Juli'), (8, 'Agustus'), (9, 'September'),
        (10, 'Oktober'), (11, 'November'), (12, 'Desember');
    
    -- OPEN CURSOR
    OPEN cur_bulanan;
    
    -- LOOP baris per baris
    loop_bulanan: LOOP
        FETCH cur_bulanan INTO v_bulan, v_subtotal;
        
        IF v_done THEN
            LEAVE loop_bulanan;
        END IF;
        
        -- Update total pendapatan dan jumlah transaksi per bulan
        UPDATE tmp_laporan_bulanan
        SET total_pendapatan = total_pendapatan + v_subtotal,
            jumlah_transaksi = jumlah_transaksi + 1
        WHERE bulan = v_bulan;
    END LOOP loop_bulanan;
    
    -- CLOSE CURSOR
    CLOSE cur_bulanan;
    
    -- Tampilkan hasil laporan
    SELECT * FROM tmp_laporan_bulanan ORDER BY bulan;
    
    -- Bersihkan temporary table
    DROP TEMPORARY TABLE IF EXISTS tmp_laporan_bulanan;
END //

DELIMITER ;
