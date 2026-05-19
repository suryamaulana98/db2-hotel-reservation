<?php
/**
 * ============================================================
 * Model: Payment
 * Tabel: payments
 * ============================================================
 * Fungsi: Mengelola pembayaran (upload bukti, verifikasi)
 */
class Payment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Simpan bukti pembayaran
     */
    public function create($bookingId, $buktiBayar)
    {
        $this->db->query("INSERT INTO payments (id_booking, bukti_bayar, status_verifikasi, tanggal_bayar) 
                          VALUES (:booking, :bukti, 'pending', NOW())");
        $this->db->bind(':booking', $bookingId);
        $this->db->bind(':bukti', $buktiBayar);
        return $this->db->execute();
    }

    /**
     * Ambil payment berdasarkan booking ID
     */
    public function findByBookingId($bookingId)
    {
        $this->db->query("SELECT * FROM payments WHERE id_booking = :id");
        $this->db->bind(':id', $bookingId);
        return $this->db->single();
    }

    /**
     * Verifikasi pembayaran (Admin)
     * @param string $status 'verified' atau 'rejected'
     */
    public function verify($paymentId, $status)
    {
        $this->db->query("UPDATE payments SET status_verifikasi = :status, verified_at = NOW() WHERE id_payment = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $paymentId);
        return $this->db->execute();
    }

    /**
     * Ambil semua payment (Admin)
     */
    public function getAll()
    {
        $this->db->query("SELECT p.*, bh.id_booking, u.nama 
                          FROM payments p 
                          JOIN booking_h bh ON p.id_booking = bh.id_booking
                          JOIN users u ON bh.id_user = u.id_user
                          ORDER BY p.tanggal_bayar DESC");
        return $this->db->resultSet();
    }
}
