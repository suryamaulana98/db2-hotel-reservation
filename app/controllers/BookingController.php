<?php
/**
 * ============================================================
 * BookingController - Proses Booking Hotel
 * ============================================================
 * Implementasi utama:
 * - STORED PROCEDURE: sp_proses_booking
 * - TRIGGER: otomatis dijalankan saat insert booking_d
 * - VIEW: view_laporan_booking
 */
class BookingController extends Controller
{
    /**
     * Form booking kamar (User)
     */
    public function create($roomId)
    {
        Middleware::isUser();

        $roomModel = $this->model('Room');
        $room = $roomModel->findById($roomId);

        if (!$room || $room->status !== 'tersedia') {
            $this->setFlash('danger', 'Kamar tidak tersedia.');
            $this->redirect('/rooms');
            return;
        }

        $this->view('booking/form', ['room' => $room]);
    }

    /**
     * Proses simpan booking (User)
     * Menggunakan: STORED PROCEDURE → sp_proses_booking
     * TRIGGER akan otomatis berjalan: trg_booking_d_insert, trg_log_booking
     */
    public function store()
    {
        Middleware::isUser();

        $roomId   = $_POST['room_id'] ?? 0;
        $checkin  = $_POST['tanggal_checkin'] ?? '';
        $checkout = $_POST['tanggal_checkout'] ?? '';
        $userId   = $_SESSION['user']->id_user;

        // Validasi
        if (empty($checkin) || empty($checkout)) {
            $this->setFlash('danger', 'Tanggal check-in dan check-out harus diisi.');
            $this->redirect('/booking/create/' . $roomId);
            return;
        }

        if ($checkout <= $checkin) {
            $this->setFlash('danger', 'Tanggal check-out harus setelah check-in.');
            $this->redirect('/booking/create/' . $roomId);
            return;
        }

        try {
            $bookingModel = $this->model('Booking');

            // Panggil STORED PROCEDURE
            $bookingId = $bookingModel->prosesBooking($userId, $roomId, $checkin, $checkout);

            $this->setFlash('success', 'Booking berhasil! Silakan upload bukti pembayaran.');
            $this->redirect('/payment/upload/' . $bookingId);
        } catch (Exception $e) {
            $this->setFlash('danger', 'Gagal membuat booking: ' . $e->getMessage());
            $this->redirect('/booking/create/' . $roomId);
        }
    }

    /**
     * Riwayat booking user
     */
    public function history()
    {
        Middleware::isUser();

        $bookingModel = $this->model('Booking');
        $bookings = $bookingModel->getByUserId($_SESSION['user']->id_user);

        $this->view('booking/history', ['bookings' => $bookings]);
    }

    /**
     * Detail booking
     */
    public function detail($id)
    {
        Middleware::isLoggedIn();

        $bookingModel = $this->model('Booking');
        $booking = $bookingModel->getDetail($id);

        if (!$booking) {
            $this->setFlash('danger', 'Booking tidak ditemukan.');
            $this->redirect('/booking/history');
            return;
        }

        $this->view('booking/detail', $booking);
    }

    // ============================================================
    // ADMIN METHODS
    // ============================================================

    /**
     * Daftar semua booking (Admin)
     */
    public function adminIndex()
    {
        Middleware::isAdmin();
        $bookingModel = $this->model('Booking');
        $bookings = $bookingModel->getAllBookings();
        $this->view('admin/bookings', ['bookings' => $bookings]);
    }

    /**
     * Update status booking (Admin)
     * TRIGGER akan otomatis berjalan saat status berubah ke 'checked_out'
     */
    public function updateStatus($id)
    {
        Middleware::isAdmin();
        $status = $_POST['status'] ?? '';
        $validStatuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            $this->setFlash('danger', 'Status tidak valid.');
            $this->redirect('/admin/bookings');
            return;
        }

        $bookingModel = $this->model('Booking');
        $bookingModel->updateStatus($id, $status);

        $this->setFlash('success', 'Status booking #' . $id . ' berhasil diubah menjadi ' . $status);
        $this->redirect('/admin/bookings');
    }
}
