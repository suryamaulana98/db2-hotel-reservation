<?php
/**
 * ============================================================
 * AdminController - Dashboard & Activity Logs
 * ============================================================
 */
class AdminController extends Controller
{
    /**
     * Dashboard Admin
     * Menampilkan ringkasan: total kamar, booking, pendapatan
     */
    public function dashboard()
    {
        Middleware::isAdmin();

        $roomModel    = $this->model('Room');
        $bookingModel = $this->model('Booking');
        $logModel     = $this->model('ActivityLog');

        // Statistik
        $data = [
            'total_kamar'       => count($roomModel->getAll()),
            'kamar_tersedia'    => $roomModel->countByStatus('tersedia'),
            'kamar_terisi'      => $roomModel->countByStatus('terisi'),
            'total_booking'     => $bookingModel->countAll(),
            'booking_pending'   => $bookingModel->countByStatus('pending'),
            'booking_confirmed' => $bookingModel->countByStatus('confirmed'),
            'recent_logs'       => $logModel->getAll(10),
        ];

        // Total pendapatan menggunakan CURSOR
        try {
            $pendapatan = $bookingModel->getTotalPendapatan();
            $data['total_pendapatan']   = $pendapatan->total_pendapatan ?? 0;
            $data['jumlah_transaksi']   = $pendapatan->jumlah_transaksi ?? 0;
        } catch (Exception $e) {
            $data['total_pendapatan'] = 0;
            $data['jumlah_transaksi'] = 0;
        }

        $this->view('admin/dashboard', $data);
    }

    /**
     * Halaman Activity Logs
     * Data diisi otomatis oleh TRIGGER
     */
    public function logs()
    {
        Middleware::isAdmin();
        $logModel = $this->model('ActivityLog');
        $logs = $logModel->getAll(100);
        $this->view('admin/logs', ['logs' => $logs]);
    }
}
