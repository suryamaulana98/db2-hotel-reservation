<?php
/**
 * ============================================================
 * RoomController - Kelola Kamar Hotel
 * ============================================================
 * Menangani: Tampil kamar (user), CRUD kamar (admin)
 * Implementasi: Menggunakan VIEW database (view_kamar_tersedia)
 */
class RoomController extends Controller
{
    /**
     * Halaman daftar kamar (Public)
     * Menggunakan: VIEW → view_kamar_tersedia
     */
    public function index()
    {
        $roomModel = $this->model('Room');
        $rooms = $roomModel->getAvailable(); // Query ke VIEW
        $this->view('rooms/index', ['rooms' => $rooms]);
    }

    /**
     * Detail kamar (Public)
     */
    public function detail($id)
    {
        $roomModel = $this->model('Room');
        $room = $roomModel->findById($id);

        if (!$room) {
            $this->setFlash('danger', 'Kamar tidak ditemukan.');
            $this->redirect('/rooms');
            return;
        }

        $this->view('rooms/detail', ['room' => $room]);
    }

    // ============================================================
    // ADMIN METHODS
    // ============================================================

    /**
     * Daftar kamar (Admin)
     */
    public function adminIndex()
    {
        Middleware::isAdmin();
        $roomModel = $this->model('Room');
        $rooms = $roomModel->getAll();
        $this->view('admin/rooms', ['rooms' => $rooms]);
    }

    /**
     * Form tambah kamar (Admin)
     */
    public function createForm()
    {
        Middleware::isAdmin();
        $this->view('rooms/form', ['room' => null, 'action' => 'create']);
    }

    /**
     * Simpan kamar baru (Admin)
     */
    public function store()
    {
        Middleware::isAdmin();

        $data = [
            'nomor_kamar' => trim($_POST['nomor_kamar'] ?? ''),
            'tipe_kamar'  => $_POST['tipe_kamar'] ?? 'Standard',
            'harga'       => $_POST['harga'] ?? 0,
            'status'      => $_POST['status'] ?? 'tersedia',
            'deskripsi'   => trim($_POST['deskripsi'] ?? ''),
            'gambar'      => null
        ];

        // Handle upload gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $data['gambar'] = $this->uploadImage($_FILES['gambar']);
        }

        $roomModel = $this->model('Room');
        $roomModel->create($data);

        $this->setFlash('success', 'Kamar berhasil ditambahkan.');
        $this->redirect('/admin/rooms');
    }

    /**
     * Form edit kamar (Admin)
     */
    public function editForm($id)
    {
        Middleware::isAdmin();
        $roomModel = $this->model('Room');
        $room = $roomModel->findById($id);

        if (!$room) {
            $this->setFlash('danger', 'Kamar tidak ditemukan.');
            $this->redirect('/admin/rooms');
            return;
        }

        $this->view('rooms/form', ['room' => $room, 'action' => 'edit']);
    }

    /**
     * Update kamar (Admin)
     */
    public function update($id)
    {
        Middleware::isAdmin();

        $data = [
            'nomor_kamar' => trim($_POST['nomor_kamar'] ?? ''),
            'tipe_kamar'  => $_POST['tipe_kamar'] ?? 'Standard',
            'harga'       => $_POST['harga'] ?? 0,
            'status'      => $_POST['status'] ?? 'tersedia',
            'deskripsi'   => trim($_POST['deskripsi'] ?? ''),
        ];

        // Handle upload gambar baru
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $data['gambar'] = $this->uploadImage($_FILES['gambar']);
        }

        $roomModel = $this->model('Room');
        $roomModel->update($id, $data);

        $this->setFlash('success', 'Kamar berhasil diperbarui.');
        $this->redirect('/admin/rooms');
    }

    /**
     * Hapus kamar (Admin)
     */
    public function delete($id)
    {
        Middleware::isAdmin();
        $roomModel = $this->model('Room');

        try {
            $roomModel->delete($id);
            $this->setFlash('success', 'Kamar berhasil dihapus.');
        } catch (Exception $e) {
            $this->setFlash('danger', 'Gagal menghapus kamar. Kamar mungkin masih memiliki booking.');
        }

        $this->redirect('/admin/rooms');
    }

    /**
     * Helper: upload gambar kamar
     */
    private function uploadImage($file)
    {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'room_' . time() . '_' . rand(100, 999) . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return $filename;
    }
}
