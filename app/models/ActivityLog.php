<?php
/**
 * ============================================================
 * Model: ActivityLog
 * Tabel: activity_logs
 * ============================================================
 * Fungsi: Membaca log aktivitas (data diisi oleh TRIGGER)
 * Data di-insert otomatis oleh trigger trg_log_booking
 */
class ActivityLog
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ambil semua log (terbaru di atas)
     */
    public function getAll($limit = 50)
    {
        $this->db->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Hitung total log
     */
    public function countAll()
    {
        $this->db->query("SELECT COUNT(*) as total FROM activity_logs");
        $result = $this->db->single();
        return $result->total;
    }
}
