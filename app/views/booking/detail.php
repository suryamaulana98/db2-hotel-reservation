<?php $pageTitle = 'Detail Booking #' . $header->id_booking; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/booking/history">Riwayat Booking</a></li>
            <li class="breadcrumb-item active">Booking #<?= $header->id_booking ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Header Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 py-3">
                    <h5 class="mb-0 fw-bold">Booking #<?= $header->id_booking ?></h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Nama Tamu</div>
                        <div class="col-sm-8 fw-semibold"><?= htmlspecialchars($header->nama) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Email</div>
                        <div class="col-sm-8"><?= htmlspecialchars($header->email) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Tanggal Booking</div>
                        <div class="col-sm-8"><?= date('d M Y', strtotime($header->tanggal_booking)) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Status</div>
                        <div class="col-sm-8">
                            <?php
                            $sc = match($header->status_booking) {
                                'pending' => 'warning', 'confirmed' => 'primary',
                                'checked_in' => 'info', 'checked_out' => 'success',
                                'cancelled' => 'danger', default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $sc ?> rounded-pill px-3"><?= ucfirst($header->status_booking) ?></span>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Detail Kamar (booking_d)</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kamar</th>
                                    <th>Tipe</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Harga/Malam</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($details as $d): ?>
                                <tr>
                                    <td class="fw-semibold"><?= $d->nomor_kamar ?></td>
                                    <td><?= $d->tipe_kamar ?></td>
                                    <td><?= date('d M Y', strtotime($d->tanggal_checkin)) ?></td>
                                    <td><?= date('d M Y', strtotime($d->tanggal_checkout)) ?></td>
                                    <td>Rp <?= number_format($d->harga, 0, ',', '.') ?></td>
                                    <td class="fw-bold">Rp <?= number_format($d->subtotal, 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <td colspan="5" class="text-end fw-bold">Grand Total</td>
                                    <td class="fw-bold fs-5">Rp <?= number_format($header->grand_total, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2"></i>Pembayaran</h6>
                    <?php if ($payment): ?>
                        <p><strong>Status:</strong> 
                            <?php
                            $pc = match($payment->status_verifikasi) {
                                'verified' => 'success', 'pending' => 'warning', 'rejected' => 'danger', default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $pc ?>"><?= ucfirst($payment->status_verifikasi) ?></span>
                        </p>
                        <p><strong>Tanggal Bayar:</strong><br><?= date('d M Y H:i', strtotime($payment->tanggal_bayar)) ?></p>
                        <?php if ($payment->bukti_bayar): ?>
                        <p><strong>Bukti Bayar:</strong></p>
                        <img src="<?= BASE_URL ?>/uploads/<?= $payment->bukti_bayar ?>" class="img-fluid rounded-3 shadow-sm" alt="Bukti">
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="bi bi-exclamation-triangle fs-3 text-warning"></i>
                            <p class="text-muted mt-2">Belum ada pembayaran</p>
                            <a href="<?= BASE_URL ?>/payment/upload/<?= $header->id_booking ?>" class="btn btn-success btn-sm rounded-pill">
                                <i class="bi bi-upload me-1"></i> Upload Bukti Bayar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
