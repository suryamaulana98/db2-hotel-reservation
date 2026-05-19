<?php $pageTitle = 'Daftar Kamar'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Temukan Kamar Impian Anda</h1>
        <p class="text-muted fs-5">Pilih kamar yang sesuai dengan kebutuhan dan budget Anda</p>
    </div>

    <!-- Room Cards -->
    <?php if (empty($rooms)): ?>
        <div class="text-center py-5">
            <i class="bi bi-emoji-frown fs-1 text-muted"></i>
            <p class="text-muted mt-3">Tidak ada kamar tersedia saat ini.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($rooms as $room): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card room-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <!-- Room Image -->
                    <div class="room-img-wrapper">
                        <?php if ($room->gambar): ?>
                            <img src="<?= BASE_URL ?>/uploads/<?= $room->gambar ?>" class="card-img-top room-img" alt="Kamar <?= $room->nomor_kamar ?>">
                        <?php else: ?>
                            <div class="room-img-placeholder d-flex align-items-center justify-content-center">
                                <i class="bi bi-image fs-1 text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <span class="badge bg-primary position-absolute top-0 end-0 m-3 rounded-pill px-3">
                            <?= $room->tipe_kamar ?>
                        </span>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar <?= htmlspecialchars($room->nomor_kamar) ?></h5>
                        </div>
                        <p class="text-muted small mb-3"><?= htmlspecialchars(substr($room->deskripsi ?? 'Kamar nyaman untuk Anda', 0, 80)) ?>...</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-5 fw-bold text-primary">Rp <?= number_format($room->harga, 0, ',', '.') ?></span>
                                <span class="text-muted small">/malam</span>
                            </div>
                            <a href="<?= BASE_URL ?>/rooms/detail/<?= $room->id_room ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                Detail <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
