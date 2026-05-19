<?php $pageTitle = 'Dashboard Admin'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h2>

    <!-- Statistik Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 card-stat">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary-soft text-primary rounded-3 p-3 me-3">
                            <i class="bi bi-door-open fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Total Kamar</p>
                            <h3 class="fw-bold mb-0"><?= $total_kamar ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 card-stat">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success-soft text-success rounded-3 p-3 me-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Kamar Tersedia</p>
                            <h3 class="fw-bold mb-0"><?= $kamar_tersedia ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 card-stat">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning-soft text-warning rounded-3 p-3 me-3">
                            <i class="bi bi-journal-check fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Total Booking</p>
                            <h3 class="fw-bold mb-0"><?= $total_booking ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 card-stat">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info-soft text-info rounded-3 p-3 me-3">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Pendapatan</p>
                            <h3 class="fw-bold mb-0 fs-5">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Status Booking</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge bg-warning me-1">●</span> Pending</span>
                        <strong><?= $booking_pending ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge bg-primary me-1">●</span> Confirmed</span>
                        <strong><?= $booking_confirmed ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><span class="badge bg-success me-1">●</span> Transaksi Selesai</span>
                        <strong><?= $jumlah_transaksi ?></strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Kamar</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge bg-success me-1">●</span> Tersedia</span>
                        <strong><?= $kamar_tersedia ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge bg-danger me-1">●</span> Terisi</span>
                        <strong><?= $kamar_terisi ?></strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Quick Links</h6>
                    <a href="<?= BASE_URL ?>/admin/rooms" class="btn btn-outline-primary btn-sm w-100 mb-2">Kelola Kamar</a>
                    <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-outline-primary btn-sm w-100 mb-2">Kelola Booking</a>
                    <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-outline-primary btn-sm w-100">Laporan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Logs (dari TRIGGER) -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2"></i>Activity Log Terbaru (dari Trigger)</h6>
            <a href="<?= BASE_URL ?>/admin/logs" class="btn btn-sm btn-outline-primary rounded-pill">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <?php if (empty($recent_logs)): ?>
                    <div class="list-group-item text-center text-muted py-4">Belum ada aktivitas.</div>
                <?php else: ?>
                    <?php foreach ($recent_logs as $log): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-dot fs-4 text-primary"></i><?= htmlspecialchars($log->aktivitas) ?></span>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($log->created_at)) ?></small>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
