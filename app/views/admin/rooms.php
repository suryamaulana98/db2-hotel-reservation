<?php $pageTitle = 'Kelola Kamar'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-door-closed me-2"></i>Kelola Kamar</h2>
        <a href="<?= BASE_URL ?>/admin/rooms/create" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kamar
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Kamar</th>
                            <th>Tipe</th>
                            <th>Harga/Malam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($room->nomor_kamar) ?></td>
                            <td><span class="badge bg-info rounded-pill"><?= $room->tipe_kamar ?></span></td>
                            <td>Rp <?= number_format($room->harga, 0, ',', '.') ?></td>
                            <td>
                                <?php
                                $sc = match($room->status) {
                                    'tersedia' => 'success', 'terisi' => 'danger', 'maintenance' => 'warning', default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $sc ?> rounded-pill"><?= ucfirst($room->status) ?></span>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/rooms/edit/<?= $room->id_room ?>" class="btn btn-sm btn-outline-warning rounded-pill">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/rooms/delete/<?= $room->id_room ?>" class="btn btn-sm btn-outline-danger rounded-pill"
                                   onclick="return confirm('Yakin hapus kamar ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
