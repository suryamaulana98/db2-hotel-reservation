<?php
/**
 * ============================================================
 * ReportController - Laporan Transaksi
 * ============================================================
 * Implementasi:
 * - VIEW: view_laporan_booking
 * - CURSOR: sp_laporan_pendapatan, sp_laporan_bulanan
 */
class ReportController extends Controller
{
    /**
     * Halaman laporan transaksi
     * Menggunakan: VIEW (view_laporan_booking) + CURSOR (sp_laporan_pendapatan)
     */
    public function index()
    {
        Middleware::isAdmin();

        $bookingModel = $this->model('Booking');

        // Ambil laporan dari VIEW
        $laporan = $bookingModel->getLaporan();

        // Ambil total pendapatan dari CURSOR
        try {
            $pendapatan = $bookingModel->getTotalPendapatan();
        } catch (Exception $e) {
            $pendapatan = (object)['total_pendapatan' => 0, 'jumlah_transaksi' => 0];
        }

        $this->view('admin/reports', [
            'laporan'    => $laporan,
            'pendapatan' => $pendapatan
        ]);
    }

    /**
     * Laporan bulanan
     * Menggunakan: CURSOR → sp_laporan_bulanan
     */
    public function monthly()
    {
        Middleware::isAdmin();

        $tahun = $_GET['tahun'] ?? date('Y');
        $bookingModel = $this->model('Booking');

        try {
            $laporan = $bookingModel->getLaporanBulanan((int)$tahun);
        } catch (Exception $e) {
            $laporan = [];
        }

        $this->view('admin/reports_monthly', [
            'laporan' => $laporan,
            'tahun'   => $tahun
        ]);
    }
}
