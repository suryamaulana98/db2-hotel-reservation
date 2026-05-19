<?php
/**
 * ============================================================
 * AuthController - Autentikasi User
 * ============================================================
 * Menangani: Login, Register, Logout
 * Menggunakan: Session PHP, Password Hashing
 */
class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function loginForm()
    {
        // Jika sudah login, redirect
        if (Middleware::check()) {
            $user = Middleware::user();
            if ($user->role === 'admin') {
                $this->redirect('/admin/dashboard');
            } else {
                $this->redirect('/rooms');
            }
            return;
        }
        $this->view('auth/login');
    }

    /**
     * Proses login
     */
    public function login()
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validasi input
        if (empty($email) || empty($password)) {
            $this->setFlash('danger', 'Email dan password harus diisi.');
            $this->redirect('/login');
            return;
        }

        $userModel = $this->model('User');
        $user = $userModel->login($email, $password);

        if ($user) {
            // Simpan data user ke session
            $_SESSION['user'] = $user;
            $this->setFlash('success', 'Login berhasil! Selamat datang, ' . $user->nama);

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                $this->redirect('/admin/dashboard');
            } else {
                $this->redirect('/rooms');
            }
        } else {
            $this->setFlash('danger', 'Email atau password salah.');
            $this->redirect('/login');
        }
    }

    /**
     * Tampilkan form register
     */
    public function registerForm()
    {
        if (Middleware::check()) {
            $this->redirect('/rooms');
            return;
        }
        $this->view('auth/register');
    }

    /**
     * Proses register
     */
    public function register()
    {
        $nama     = trim($_POST['nama'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        // Validasi
        if (empty($nama) || empty($email) || empty($password)) {
            $this->setFlash('danger', 'Semua field harus diisi.');
            $this->redirect('/register');
            return;
        }

        if ($password !== $confirm) {
            $this->setFlash('danger', 'Konfirmasi password tidak cocok.');
            $this->redirect('/register');
            return;
        }

        if (strlen($password) < 6) {
            $this->setFlash('danger', 'Password minimal 6 karakter.');
            $this->redirect('/register');
            return;
        }

        $userModel = $this->model('User');

        // Cek email sudah terdaftar
        if ($userModel->emailExists($email)) {
            $this->setFlash('danger', 'Email sudah terdaftar.');
            $this->redirect('/register');
            return;
        }

        // Simpan user baru
        $userModel->register([
            'nama'     => $nama,
            'email'    => $email,
            'password' => $password
        ]);

        $this->setFlash('success', 'Registrasi berhasil! Silakan login.');
        $this->redirect('/login');
    }

    /**
     * Logout: hapus session
     */
    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        session_start();
        $this->setFlash('success', 'Anda telah logout.');
        $this->redirect('/login');
    }
}
