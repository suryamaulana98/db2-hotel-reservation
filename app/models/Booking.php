<?php
/**
 * ============================================================
 * Model: Booking
 * Tabel: booking_h (header) + booking_d (detail)
 * ============================================================
 * Fungsi: 
 * - Proses booking menggunakan STORED PROCEDURE
 * - Menampilkan riwayat booking
 * - Menampilkan laporan menggunakan VIEW
 */
class Booking
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Proses booking menggunakan STORED PROCEDURE
     * Memanggil: sp_proses_booking(user_id, room_id, checkin, checkout)
     * 
     * Ini adalah implementasi utama Stored Procedure di PHP
     */
    public function prosesBooking($userId, $roomId, $checkin, $checkout)
    {
        $pdo = $this->db->getPdo();
        $stmt = $pdo->prepare("CALL sp_proses_booking(:user, :room, :checkin, :checkout, @id_booking)");
        $stmt->bindValue(':user', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':room', $roomId, PDO::PARAM_INT);
        $stmt->bindValue(':checkin', $checkin);
        $stmt->bindValue(':checkout', $checkout);
        $stmt->execute();
        $stmt->closeCursor();

        // Ambil output parameter (ID booking yang dibuat)
        $result = $pdo->query("SELECT @id_booking AS id_booking")->fetch();
        return $result->id_booking;
    }

    /**
     * Ambil riwayat booking user
     */
    public function getByUserId($userId)
    {
        $this->db->query("SELECT bh.*, 
                            (SELECT COUNT(*) FROM booking_d WHERE id_booking = bh.id_booking) as jumlah_kamar,
                            COALESCE(p.status_verifikasi, 'belum bayar') as status_pembayaran
                          FROM booking_h bh
                          LEFT JOIN payments p ON bh.id_booking = p.id_booking
                          WHERE bh.id_user = :user_id
                          ORDER BY bh.created_at DESC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * Ambil detail booking (header + detail kamar)
     */
    public function getDetail($bookingId)
    {
        // Ambil header
        $this->db->query("SELECT bh.*, u.nama, u.email 
                          FROM booking_h bh 
                          JOIN users u ON bh.id_user = u.id_user 
                          WHERE bh.id_booking = :id");
        $this->db->bind(':id', $bookingId);
        $header = $this->db->single();

        if (!$header) return null;

        // Ambil detail kamar
        $this->db->query("SELECT bd.*, r.nomor_kamar, r.tipe_kamar 
                          FROM booking_d bd 
                          JOIN rooms r ON bd.id_room = r.id_room 
                          WHERE bd.id_booking = :id");
        $this->db->bind(':id', $bookingId);
        $details = $this->db->resultSet();

        // Ambil payment
        $this->db->query("SELECT * FROM payments WHERE id_booking = :id");
        $this->db->bind(':id', $bookingId);
        $payment = $this->db->single();

        return [
            'header'  => $header,
            'details' => $details,
            'payment' => $payment
        ];
    }

    /**
     * Ambil semua booking (Admin) menggunakan VIEW
     */
    public function getAllBookings()
    {
        $this->db->query("SELECT DISTINCT bh.id_booking, bh.tanggal_booking, 
                            u.nama, u.email, bh.grand_total, bh.status_booking,
                            COALESCE(p.status_verifikasi, 'belum bayar') as status_pembayaran,
                            (SELECT COUNT(*) FROM booking_d WHERE id_booking = bh.id_booking) as jumlah_kamar,
                            bh.created_at
                          FROM booking_h bh
                          JOIN users u ON bh.id_user = u.id_user
                          LEFT JOIN payments p ON bh.id_booking = p.id_booking
                          ORDER BY bh.created_at DESC");
        return $this->db->resultSet();
    }

    /**
     * Update status booking (Admin)
     * Trigger akan otomatis berjalan saat status berubah
     */
    public function updateStatus($bookingId, $status)
    {
        $this->db->query("UPDATE booking_h SET status_booking = :status WHERE id_booking = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $bookingId);
        return $this->db->execute();
    }

    /**
     * Ambil laporan booking menggunakan DATABASE VIEW
     * Query ke: view_laporan_booking
     */
    public function getLaporan()
    {
        $this->db->query("SELECT * FROM view_laporan_booking");
        return $this->db->resultSet();
    }

    /**
     * Hitung total booking
     */
    public function countAll()
    {
        $this->db->query("SELECT COUNT(*) as total FROM booking_h");
        $result = $this->db->single();
        return $result->total;
    }

    /**
     * Hitung booking berdasarkan status
     */
    public function countByStatus($status)
    {
        $this->db->query("SELECT COUNT(*) as total FROM booking_h WHERE status_booking = :status");
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result->total;
    }

    /**
     * Hitung total pendapatan menggunakan CURSOR
     * Memanggil: sp_laporan_pendapatan
     */
    public function getTotalPendapatan()
    {
        $pdo = $this->db->getPdo();
        $stmt = $pdo->prepare("CALL sp_laporan_pendapatan(@total, @jumlah)");
        $stmt->execute();
        $stmt->closeCursor();

        $result = $pdo->query("SELECT @total AS total_pendapatan, @jumlah AS jumlah_transaksi")->fetch();
        return $result;
    }

    /**
     * Laporan bulanan menggunakan CURSOR
     * Memanggil: sp_laporan_bulanan
     */
    public function getLaporanBulanan($tahun)
    {
        $pdo = $this->db->getPdo();
        $stmt = $pdo->prepare("CALL sp_laporan_bulanan(:tahun)");
        $stmt->bindValue(':tahun', $tahun, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt->closeCursor();
        return $result;
    }
}
