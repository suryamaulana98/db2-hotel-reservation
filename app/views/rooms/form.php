<?php
$isEdit = isset($room) && $room !== null;
$pageTitle = $isEdit ? 'Edit Kamar ' . $room->nomor_kamar : 'Tambah Kamar Baru';
?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-door-closed me-2"></i><?= $pageTitle ?></h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data"
                          action="<?= $isEdit ? BASE_URL . '/admin/rooms/update/' . $room->id_room : BASE_URL . '/admin/rooms/store' ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nomor_kamar" class="form-label fw-semibold">Nomor Kamar</label>
                                <input type="text" class="form-control" id="nomor_kamar" name="nomor_kamar" 
                                       value="<?= $isEdit ? htmlspecialchars($room->nomor_kamar) : '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tipe_kamar" class="form-label fw-semibold">Tipe Kamar</label>
                                <select class="form-select" id="tipe_kamar" name="tipe_kamar" required>
                                    <?php foreach (['Standard', 'Deluxe', 'Suite'] as $tipe): ?>
                                    <option value="<?= $tipe ?>" <?= ($isEdit && $room->tipe_kamar === $tipe) ? 'selected' : '' ?>>
                                        <?= $tipe ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="harga" class="form-label fw-semibold">Harga per Malam (Rp)</label>
                                <input type="number" class="form-control" id="harga" name="harga" 
                                       value="<?= $isEdit ? $room->harga : '' ?>" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <?php foreach (['tersedia', 'terisi', 'maintenance'] as $s): ?>
                                    <option value="<?= $s ?>" <?= ($isEdit && $room->status === $s) ? 'selected' : '' ?>>
                                        <?= ucfirst($s) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= $isEdit ? htmlspecialchars($room->deskripsi) : '' ?></textarea>
                            </div>
                            <div class="col-12">
                                <label for="gambar" class="form-label fw-semibold">Gambar Kamar</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                <?php if ($isEdit && $room->gambar): ?>
                                <small class="text-muted">Gambar saat ini: <?= $room->gambar ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i> <?= $isEdit ? 'Perbarui' : 'Simpan' ?>
                            </button>
                            <a href="<?= BASE_URL ?>/admin/rooms" class="btn btn-outline-secondary px-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
