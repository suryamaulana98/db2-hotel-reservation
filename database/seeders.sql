-- ============================================================
-- HOTEL RESERVATION SYSTEM - DUMMY DATA (SEEDER)
-- Mata Kuliah: Basis Data 2
-- ============================================================

USE hotel_reservation;

-- ============================================================
-- DATA ADMIN & USER
-- Password: password123 (sudah di-hash dengan password_hash PHP)
-- ============================================================
INSERT INTO users (nama, email, password, role) VALUES
('Admin Hotel', 'admin@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso', 'budi@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Siti Nurhaliza', 'siti@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Ahmad Dahlan', 'ahmad@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- ============================================================
-- DATA KAMAR HOTEL
-- ============================================================
INSERT INTO rooms (nomor_kamar, tipe_kamar, harga, status, deskripsi) VALUES
('101', 'Standard', 350000.00, 'tersedia', 'Kamar standard dengan 1 tempat tidur single, AC, TV, dan kamar mandi dalam.'),
('102', 'Standard', 350000.00, 'tersedia', 'Kamar standard dengan 1 tempat tidur single, AC, TV, dan kamar mandi dalam.'),
('201', 'Deluxe', 550000.00, 'tersedia', 'Kamar deluxe dengan 1 tempat tidur double, AC, TV LED 42 inch, minibar, dan balkon.'),
('202', 'Deluxe', 550000.00, 'tersedia', 'Kamar deluxe dengan 1 tempat tidur double, AC, TV LED 42 inch, minibar, dan balkon.'),
('203', 'Deluxe', 550000.00, 'tersedia', 'Kamar deluxe dengan 1 tempat tidur double, AC, TV LED 42 inch, minibar, dan balkon.'),
('301', 'Suite', 950000.00, 'tersedia', 'Kamar suite mewah dengan ruang tamu terpisah, 1 king bed, jacuzzi, dan pemandangan kota.'),
('302', 'Suite', 950000.00, 'tersedia', 'Kamar suite mewah dengan ruang tamu terpisah, 1 king bed, jacuzzi, dan pemandangan kota.'),
('401', 'Standard', 350000.00, 'tersedia', 'Kamar standard dengan 1 tempat tidur twin, AC, TV, dan kamar mandi dalam.'),
('402', 'Deluxe', 550000.00, 'maintenance', 'Kamar deluxe sedang dalam perbaikan.'),
('501', 'Suite', 950000.00, 'tersedia', 'Presidential suite dengan 2 kamar tidur, ruang tamu luas, dan private pool.');

-- ============================================================
-- DATA BOOKING (menggunakan INSERT langsung, bukan procedure agar seeder sederhana)
-- Catatan: Trigger akan otomatis berjalan saat data di-insert
-- ============================================================

-- Booking 1: Budi memesan kamar 101 (Standard)
INSERT INTO booking_h (tanggal_booking, id_user, grand_total, status_booking)
VALUES ('2026-05-10', 2, 1050000, 'checked_out');

INSERT INTO booking_d (id_booking, id_room, tanggal_checkin, tanggal_checkout, harga, subtotal)
VALUES (1, 1, '2026-05-12', '2026-05-15', 350000, 1050000);

-- Booking 2: Siti memesan kamar 201 (Deluxe)
INSERT INTO booking_h (tanggal_booking, id_user, grand_total, status_booking)
VALUES ('2026-05-15', 3, 1100000, 'confirmed');

INSERT INTO booking_d (id_booking, id_room, tanggal_checkin, tanggal_checkout, harga, subtotal)
VALUES (2, 3, '2026-05-18', '2026-05-20', 550000, 1100000);

-- Booking 3: Ahmad memesan 2 kamar (Standard 102 + Deluxe 202)
INSERT INTO booking_h (tanggal_booking, id_user, grand_total, status_booking)
VALUES ('2026-05-18', 4, 2700000, 'pending');

INSERT INTO booking_d (id_booking, id_room, tanggal_checkin, tanggal_checkout, harga, subtotal)
VALUES (3, 2, '2026-05-20', '2026-05-23', 350000, 1050000);

INSERT INTO booking_d (id_booking, id_room, tanggal_checkin, tanggal_checkout, harga, subtotal)
VALUES (3, 4, '2026-05-20', '2026-05-23', 550000, 1650000);

-- ============================================================
-- DATA PAYMENTS
-- ============================================================
INSERT INTO payments (id_booking, bukti_bayar, status_verifikasi, tanggal_bayar) VALUES
(1, 'bukti_001.jpg', 'verified', '2026-05-10 14:30:00'),
(2, 'bukti_002.jpg', 'pending', '2026-05-15 10:00:00');

-- Set room 1 back to tersedia (booking 1 is checked_out)
UPDATE rooms SET status = 'tersedia' WHERE id_room = 1;
