<?php
/**
 * ============================================================
 * Class Database - Koneksi Database menggunakan PDO
 * ============================================================
 * 
 * Fungsi:
 * - Membuat koneksi ke database MySQL menggunakan PDO
 * - Menyediakan method untuk prepared statement
 * - Pattern: Singleton (1 koneksi untuk seluruh aplikasi)
 * 
 * Cara Pakai di Model:
 *   $db = Database::getInstance();
 *   $db->query("SELECT * FROM rooms WHERE id_room = :id");
 *   $db->bind(':id', 1);
 *   $result = $db->resultSet(); // array of rows
 *   $single = $db->single();   // single row
 */
class Database
{
    private static $instance = null;
    private $pdo;
    private $stmt;

    /**
     * Constructor: buat koneksi PDO
     */
    private function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Koneksi database gagal: ' . $e->getMessage());
        }
    }

    /**
     * Singleton: pastikan hanya 1 instance Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Siapkan query SQL (prepared statement)
     */
    public function query($sql)
    {
        $this->stmt = $this->pdo->prepare($sql);
        return $this;
    }

    /**
     * Bind parameter ke prepared statement
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):    $type = PDO::PARAM_INT;  break;
                case is_bool($value):   $type = PDO::PARAM_BOOL; break;
                case is_null($value):   $type = PDO::PARAM_NULL; break;
                default:                $type = PDO::PARAM_STR;  break;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * Eksekusi prepared statement
     */
    public function execute()
    {
        return $this->stmt->execute();
    }

    /**
     * Ambil semua baris hasil query
     */
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Ambil satu baris hasil query
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Hitung jumlah baris yang terpengaruh
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * Ambil ID terakhir yang di-insert
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Akses langsung PDO (untuk CALL procedure, dll)
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}
