-- ============================================================
-- HOTEL RESERVATION SYSTEM - Database Schema
-- Mata Kuliah: Basis Data 2
-- Konsep: Header-Detail Table (booking_h + booking_d)
-- ============================================================

-- Buat database
CREATE DATABASE IF NOT EXISTS hotel_reservation;
USE hotel_reservation;

-- ============================================================
-- TABEL 1: users
-- Menyimpan data user (tamu) dan admin
-- ============================================================
CREATE TABLE users (
    id_user     INT AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('user', 'admin') DEFAULT 'user',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 2: rooms
-- Menyimpan data kamar hotel
-- ============================================================
CREATE TABLE rooms (
    id_room      INT AUTO_INCREMENT PRIMARY KEY,
    nomor_kamar  VARCHAR(10) NOT NULL UNIQUE,
    tipe_kamar   VARCHAR(50) NOT NULL COMMENT 'standard, deluxe, suite',
    harga        DECIMAL(12,2) NOT NULL,
    status       ENUM('tersedia', 'terisi', 'maintenance') DEFAULT 'tersedia',
    deskripsi    TEXT,
    gambar       VARCHAR(255) DEFAULT NULL,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 3: booking_h (Header Transaksi)
-- Menyimpan data utama / header booking
-- Konsep: 1 booking bisa memiliki banyak detail kamar
-- ============================================================
CREATE TABLE booking_h (
    id_booking      INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_booking DATE NOT NULL,
    id_user         INT NOT NULL,
    grand_total     DECIMAL(15,2) DEFAULT 0,
    status_booking  ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Key ke tabel users
    CONSTRAINT fk_booking_user 
        FOREIGN KEY (id_user) REFERENCES users(id_user)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 4: booking_d (Detail Transaksi)
-- Menyimpan detail kamar yang dibooking
-- Relasi: 1 booking_h memiliki banyak booking_d
-- ============================================================
CREATE TABLE booking_d (
    id_detail        INT AUTO_INCREMENT PRIMARY KEY,
    id_booking       INT NOT NULL,
    id_room          INT NOT NULL,
    tanggal_checkin   DATE NOT NULL,
    tanggal_checkout  DATE NOT NULL,
    harga            DECIMAL(12,2) NOT NULL,
    subtotal         DECIMAL(15,2) NOT NULL,
    
    -- Foreign Key ke booking_h (header)
    CONSTRAINT fk_detail_booking 
        FOREIGN KEY (id_booking) REFERENCES booking_h(id_booking)
        ON DELETE CASCADE ON UPDATE CASCADE,
    
    -- Foreign Key ke rooms
    CONSTRAINT fk_detail_room 
        FOREIGN KEY (id_room) REFERENCES rooms(id_room)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 5: payments
-- Menyimpan data pembayaran (1 booking = 1 payment)
-- ============================================================
CREATE TABLE payments (
    id_payment          INT AUTO_INCREMENT PRIMARY KEY,
    id_booking          INT NOT NULL UNIQUE,
    bukti_bayar         VARCHAR(255) DEFAULT NULL,
    status_verifikasi   ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    tanggal_bayar       DATETIME DEFAULT CURRENT_TIMESTAMP,
    verified_at         DATETIME DEFAULT NULL,
    
    -- Foreign Key ke booking_h
    CONSTRAINT fk_payment_booking 
        FOREIGN KEY (id_booking) REFERENCES booking_h(id_booking)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 6: activity_logs
-- Mencatat log aktivitas sistem (diisi oleh TRIGGER)
-- ============================================================
CREATE TABLE activity_logs (
    id_log      INT AUTO_INCREMENT PRIMARY KEY,
    aktivitas   VARCHAR(255) NOT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
