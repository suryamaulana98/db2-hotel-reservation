<?php
/**
 * ============================================================
 * PaymentController - Pembayaran
 * ============================================================
 * Menangani: Upload bukti bayar (user), Verifikasi (admin)
 */
class PaymentController extends Controller
{
    /**
     * Form upload bukti bayar (User)
     */
    public function uploadForm($bookingId)
    {
        Middleware::isUser();

        $bookingModel = $this->model('Booking');
        $booking = $bookingModel->getDetail($bookingId);

        if (!$booking) {
            $this->setFlash('danger', 'Booking tidak ditemukan.');
            $this->redirect('/booking/history');
            return;
        }

        $this->view('payment/upload', $booking);
    }

    /**
     * Proses upload bukti bayar (User)
     */
    public function upload($bookingId)
    {
        Middleware::isUser();

        // Cek apakah sudah ada payment
        $paymentModel = $this->model('Payment');
        $existing = $paymentModel->findByBookingId($bookingId);
        if ($existing) {
            $this->setFlash('warning', 'Bukti pembayaran sudah diupload sebelumnya.');
            $this->redirect('/booking/history');
            return;
        }

        // Validasi file
        if (!isset($_FILES['bukti_bayar']) || $_FILES['bukti_bayar']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('danger', 'File bukti pembayaran harus diupload.');
            $this->redirect('/payment/upload/' . $bookingId);
            return;
        }

        // Upload file
        $file = $_FILES['bukti_bayar'];
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'bukti_' . $bookingId . '_' . time() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

        // Simpan ke database
        $paymentModel->create($bookingId, $filename);

        $this->setFlash('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        $this->redirect('/booking/history');
    }

    /**
     * Verifikasi pembayaran (Admin)
     */
    public function verify($paymentId)
    {
        Middleware::isAdmin();

        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['verified', 'rejected'])) {
            $this->setFlash('danger', 'Status verifikasi tidak valid.');
            $this->redirect('/admin/bookings');
            return;
        }

        $paymentModel = $this->model('Payment');
        $paymentModel->verify($paymentId, $status);

        // Jika verified, update status booking menjadi confirmed
        if ($status === 'verified') {
            // Cari booking_id dari payment
            $this->db = Database::getInstance();
            $this->db->query("SELECT id_booking FROM payments WHERE id_payment = :id");
            $this->db->bind(':id', $paymentId);
            $payment = $this->db->single();

            if ($payment) {
                $bookingModel = $this->model('Booking');
                $bookingModel->updateStatus($payment->id_booking, 'confirmed');
            }
        }

        $this->setFlash('success', 'Pembayaran berhasil di' . ($status === 'verified' ? 'verifikasi' : 'tolak') . '.');
        $this->redirect('/admin/bookings');
    }
}
