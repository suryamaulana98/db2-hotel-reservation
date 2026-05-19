<?php
/**
 * ============================================================
 * HOTEL RESERVATION SYSTEM - Konfigurasi Aplikasi
 * ============================================================
 * File ini berisi pengaturan database dan aplikasi.
 * Sesuaikan dengan konfigurasi Laragon/XAMPP Anda.
 */

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'hotel_reservation');
define('DB_USER', 'root');
define('DB_PASS', '');     // Kosong untuk Laragon/XAMPP default

// Konfigurasi Aplikasi
define('APP_NAME', 'Hotel Reservation System');
define('BASE_URL', 'http://localhost/project-hotel/public');

// Konfigurasi Upload
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
