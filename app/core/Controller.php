<?php
/**
 * ============================================================
 * Class Controller - Base Controller
 * ============================================================
 * 
 * Fungsi:
 * - Parent class untuk semua controller
 * - Menyediakan helper method:
 *   1. view()  → render halaman view
 *   2. model() → load model class
 *   3. redirect() → redirect ke URL lain
 * 
 * Semua controller extends class ini.
 */
class Controller
{
    /**
     * Render view file dan kirimkan data
     * 
     * @param string $viewPath  Path view relatif dari folder views/ (contoh: 'rooms/index')
     * @param array  $data      Data yang dikirim ke view
     */
    protected function view($viewPath, $data = [])
    {
        // Extract data menjadi variabel (contoh: $data['rooms'] → $rooms)
        extract($data);

        $file = __DIR__ . '/../views/' . $viewPath . '.php';

        if (file_exists($file)) {
            require_once $file;
        } else {
            die("View '{$viewPath}' tidak ditemukan.");
        }
    }

    /**
     * Load model class
     * 
     * @param  string $modelName  Nama model (contoh: 'Room')
     * @return object Instance dari model
     */
    protected function model($modelName)
    {
        $file = __DIR__ . '/../models/' . $modelName . '.php';

        if (file_exists($file)) {
            require_once $file;
            return new $modelName();
        } else {
            die("Model '{$modelName}' tidak ditemukan.");
        }
    }

    /**
     * Redirect ke URL
     * 
     * @param string $url URL tujuan (relatif dari BASE_URL)
     */
    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Set flash message ke session
     */
    protected function setFlash($type, $message)
    {
        $_SESSION['flash'] = [
            'type'    => $type,    // success, danger, warning, info
            'message' => $message
        ];
    }
}
