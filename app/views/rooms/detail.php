<?php $pageTitle = 'Detail Kamar ' . $room->nomor_kamar; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/rooms">Kamar</a></li>
            <li class="breadcrumb-item active">Kamar <?= $room->nomor_kamar ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Room Image -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <?php if ($room->gambar): ?>
                    <img src="<?= BASE_URL ?>/uploads/<?= $room->gambar ?>" class="img-fluid" alt="Kamar <?= $room->nomor_kamar ?>">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 350px;">
                        <i class="bi bi-building fs-1 text-muted"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Room Info -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <span class="badge bg-primary rounded-pill px-3 mb-3"><?= $room->tipe_kamar ?></span>
                    <h2 class="fw-bold">Kamar <?= htmlspecialchars($room->nomor_kamar) ?></h2>
                    
                    <div class="my-4">
                        <span class="display-6 fw-bold text-primary">Rp <?= number_format($room->harga, 0, ',', '.') ?></span>
                        <span class="text-muted fs-5">/malam</span>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Status</h6>
                        <?php
                        $statusClass = match($room->status) {
                            'tersedia' => 'success',
                            'terisi' => 'danger',
                            'maintenance' => 'warning',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?= $statusClass ?> rounded-pill px-3 py-2">
                            <?= ucfirst($room->status) ?>
                        </span>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Deskripsi</h6>
                        <p class="text-muted"><?= htmlspecialchars($room->deskripsi ?? 'Kamar nyaman dengan fasilitas lengkap.') ?></p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Fasilitas</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark border"><i class="bi bi-snow me-1"></i> AC</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-tv me-1"></i> TV</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-wifi me-1"></i> WiFi</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-droplet me-1"></i> Kamar Mandi</span>
                        </div>
                    </div>

                    <?php if ($room->status === 'tersedia' && Middleware::check() && Middleware::user()->role === 'user'): ?>
                        <a href="<?= BASE_URL ?>/booking/create/<?= $room->id_room ?>" class="btn btn-primary btn-lg w-100 rounded-pill">
                            <i class="bi bi-calendar-check me-2"></i> Booking Sekarang
                        </a>
                    <?php elseif (!Middleware::check()): ?>
                        <a href="<?= BASE_URL ?>/login" class="btn btn-outline-primary btn-lg w-100 rounded-pill">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login untuk Booking
                        </a>
                    <?php elseif ($room->status !== 'tersedia'): ?>
                        <button class="btn btn-secondary btn-lg w-100 rounded-pill" disabled>
                            Kamar Tidak Tersedia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
