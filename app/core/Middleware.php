<?php
/**
 * ============================================================
 * Class Middleware - Pengecekan Akses (Authentication)
 * ============================================================
 * 
 * Fungsi:
 * - Mengecek apakah user sudah login
 * - Mengecek role user (admin/user)
 * - Redirect jika tidak memiliki akses
 * 
 * Cara Pakai di Controller:
 *   Middleware::isLoggedIn();   // Wajib login
 *   Middleware::isAdmin();      // Wajib admin
 *   Middleware::isUser();       // Wajib user biasa
 */
class Middleware
{
    /**
     * Cek apakah user sudah login
     * Jika belum → redirect ke halaman login
     */
    public static function isLoggedIn()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = [
                'type'    => 'warning',
                'message' => 'Silakan login terlebih dahulu.'
            ];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * Cek apakah user adalah admin
     * Jika bukan → redirect ke halaman utama
     */
    public static function isAdmin()
    {
        self::isLoggedIn();
        if ($_SESSION['user']->role !== 'admin') {
            $_SESSION['flash'] = [
                'type'    => 'danger',
                'message' => 'Anda tidak memiliki akses admin.'
            ];
            header('Location: ' . BASE_URL . '/rooms');
            exit;
        }
    }

    /**
     * Cek apakah user adalah user biasa
     */
    public static function isUser()
    {
        self::isLoggedIn();
        if ($_SESSION['user']->role !== 'user') {
            header('Location: ' . BASE_URL . '/admin/dashboard');
            exit;
        }
    }

    /**
     * Cek apakah user sudah login (return boolean)
     */
    public static function check()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Ambil data user yang sedang login
     */
    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }
}
