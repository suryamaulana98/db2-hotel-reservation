-- ============================================================
-- HOTEL RESERVATION SYSTEM - DATABASE VIEWS
-- Mata Kuliah: Basis Data 2
-- ============================================================

USE hotel_reservation;

-- ============================================================
-- VIEW 1: view_kamar_tersedia
-- Fungsi: Menampilkan daftar kamar yang statusnya 'tersedia'
-- Digunakan: Halaman daftar kamar untuk user
-- ============================================================
CREATE OR REPLACE VIEW view_kamar_tersedia AS
SELECT 
    id_room,
    nomor_kamar,
    tipe_kamar,
    harga,
    deskripsi,
    gambar
FROM rooms
WHERE status = 'tersedia';

-- ============================================================
-- VIEW 2: view_laporan_booking
-- Fungsi: Menampilkan laporan booking lengkap
-- Menggabungkan: users + booking_h + booking_d + rooms
-- Digunakan: Halaman laporan admin
-- ============================================================
CREATE OR REPLACE VIEW view_laporan_booking AS
SELECT 
    bh.id_booking       AS nomor_booking,
    u.nama              AS nama_customer,
    u.email,
    bh.tanggal_booking,
    r.nomor_kamar,
    r.tipe_kamar,
    bd.tanggal_checkin,
    bd.tanggal_checkout,
    bd.harga            AS harga_kamar,
    bd.subtotal,
    bh.grand_total,
    bh.status_booking,
    COALESCE(p.status_verifikasi, 'belum bayar') AS status_pembayaran,
    bh.created_at
FROM booking_h bh
JOIN users u ON bh.id_user = u.id_user
JOIN booking_d bd ON bh.id_booking = bd.id_booking
JOIN rooms r ON bd.id_room = r.id_room
LEFT JOIN payments p ON bh.id_booking = p.id_booking
ORDER BY bh.created_at DESC;
