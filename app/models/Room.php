<?php
/**
 * ============================================================
 * Model: Room
 * Tabel: rooms
 * ============================================================
 * Fungsi: Mengelola data kamar hotel (CRUD, query view)
 */
class Room
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ambil kamar tersedia menggunakan DATABASE VIEW
     * Query ke: view_kamar_tersedia (bukan langsung ke tabel rooms)
     */
    public function getAvailable()
    {
        $this->db->query("SELECT * FROM view_kamar_tersedia");
        return $this->db->resultSet();
    }

    /**
     * Ambil semua kamar (untuk admin)
     */
    public function getAll()
    {
        $this->db->query("SELECT * FROM rooms ORDER BY nomor_kamar ASC");
        return $this->db->resultSet();
    }

    /**
     * Ambil satu kamar berdasarkan ID
     */
    public function findById($id)
    {
        $this->db->query("SELECT * FROM rooms WHERE id_room = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Tambah kamar baru (Admin)
     */
    public function create($data)
    {
        $this->db->query("INSERT INTO rooms (nomor_kamar, tipe_kamar, harga, status, deskripsi, gambar) 
                          VALUES (:nomor, :tipe, :harga, :status, :deskripsi, :gambar)");
        $this->db->bind(':nomor', $data['nomor_kamar']);
        $this->db->bind(':tipe', $data['tipe_kamar']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':status', $data['status'] ?? 'tersedia');
        $this->db->bind(':deskripsi', $data['deskripsi'] ?? '');
        $this->db->bind(':gambar', $data['gambar'] ?? null);
        return $this->db->execute();
    }

    /**
     * Update data kamar (Admin)
     */
    public function update($id, $data)
    {
        $sql = "UPDATE rooms SET nomor_kamar = :nomor, tipe_kamar = :tipe, 
                harga = :harga, status = :status, deskripsi = :deskripsi";
        
        if (isset($data['gambar'])) {
            $sql .= ", gambar = :gambar";
        }
        $sql .= " WHERE id_room = :id";

        $this->db->query($sql);
        $this->db->bind(':nomor', $data['nomor_kamar']);
        $this->db->bind(':tipe', $data['tipe_kamar']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':deskripsi', $data['deskripsi'] ?? '');
        if (isset($data['gambar'])) {
            $this->db->bind(':gambar', $data['gambar']);
        }
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Hapus kamar (Admin)
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM rooms WHERE id_room = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Hitung jumlah kamar per status
     */
    public function countByStatus($status)
    {
        $this->db->query("SELECT COUNT(*) as total FROM rooms WHERE status = :status");
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result->total;
    }
}
