<?php
/**
 * ============================================================
 * HOTEL RESERVATION SYSTEM - Entry Point
 * ============================================================
 * 
 * Semua request masuk ke file ini melalui .htaccess rewrite.
 * File ini:
 * 1. Memuat konfigurasi
 * 2. Memuat core classes
 * 3. Start session
 * 4. Memuat routes
 * 5. Menjalankan router
 */

// Start session untuk authentication
session_start();

// Load konfigurasi
require_once __DIR__ . '/../config/config.php';

// Load core classes
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/App.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Middleware.php';

// Load semua route
require_once __DIR__ . '/../routes/web.php';

// Jalankan router
App::run();
