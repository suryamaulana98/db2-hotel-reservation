<?php $pageTitle = 'Riwayat Booking'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-clock-history me-2"></i>Riwayat Booking Saya</h2>

    <?php if (empty($bookings)): ?>
        <div class="text-center py-5">
            <i class="bi bi-journal-x fs-1 text-muted"></i>
            <p class="text-muted mt-3">Belum ada riwayat booking.</p>
            <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary rounded-pill">Cari Kamar</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tanggal Booking</th>
                        <th>Jumlah Kamar</th>
                        <th>Grand Total</th>
                        <th>Status Booking</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $i => $b): ?>
                    <tr>
                        <td class="fw-semibold">#<?= $b->id_booking ?></td>
                        <td><?= date('d M Y', strtotime($b->tanggal_booking)) ?></td>
                        <td><span class="badge bg-info"><?= $b->jumlah_kamar ?> kamar</span></td>
                        <td class="fw-bold">Rp <?= number_format($b->grand_total, 0, ',', '.') ?></td>
                        <td>
                            <?php
                            $statusClass = match($b->status_booking) {
                                'pending' => 'warning',
                                'confirmed' => 'primary',
                                'checked_in' => 'info',
                                'checked_out' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $statusClass ?> rounded-pill"><?= ucfirst($b->status_booking) ?></span>
                        </td>
                        <td>
                            <?php
                            $payClass = match($b->status_pembayaran) {
                                'verified' => 'success',
                                'pending' => 'warning',
                                'rejected' => 'danger',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $payClass ?> rounded-pill"><?= ucfirst($b->status_pembayaran) ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/booking/detail/<?= $b->id_booking ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <?php if ($b->status_pembayaran === 'belum bayar'): ?>
                            <a href="<?= BASE_URL ?>/payment/upload/<?= $b->id_booking ?>" class="btn btn-sm btn-success rounded-pill">
                                <i class="bi bi-upload"></i> Bayar
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
