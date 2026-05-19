<?php
/**
 * ============================================================
 * Model: User
 * Tabel: users
 * ============================================================
 * Fungsi: Mengelola data user (register, login, get data)
 */
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Register user baru
     * Password di-hash menggunakan password_hash()
     */
    public function register($data)
    {
        $this->db->query("INSERT INTO users (nama, email, password, role) VALUES (:nama, :email, :password, 'user')");
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        return $this->db->execute();
    }

    /**
     * Login: cari user berdasarkan email, verifikasi password
     */
    public function login($email, $password)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $user = $this->db->single();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    /**
     * Cek apakah email sudah terdaftar
     */
    public function emailExists($email)
    {
        $this->db->query("SELECT id_user FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    /**
     * Ambil data user berdasarkan ID
     */
    public function findById($id)
    {
        $this->db->query("SELECT * FROM users WHERE id_user = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Ambil semua user (untuk admin)
     */
    public function getAll()
    {
        $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $this->db->resultSet();
    }
}
