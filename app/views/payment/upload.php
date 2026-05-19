<?php $pageTitle = 'Upload Bukti Pembayaran'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-success text-white rounded-top-4 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-upload me-2"></i>Upload Bukti Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Info Booking -->
                    <div class="alert alert-info rounded-3">
                        <strong>Booking #<?= $header->id_booking ?></strong><br>
                        Total: <strong class="fs-5">Rp <?= number_format($header->grand_total, 0, ',', '.') ?></strong>
                    </div>

                    <!-- Detail Kamar -->
                    <h6 class="fw-semibold mb-2">Kamar yang dipesan:</h6>
                    <ul class="list-group list-group-flush mb-4">
                        <?php foreach ($details as $d): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Kamar <?= $d->nomor_kamar ?> (<?= $d->tipe_kamar ?>)</span>
                            <span class="fw-semibold">Rp <?= number_format($d->subtotal, 0, ',', '.') ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Form Upload -->
                    <form method="POST" action="<?= BASE_URL ?>/payment/upload/<?= $header->id_booking ?>" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="bukti_bayar" class="form-label fw-semibold">File Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="bukti_bayar" name="bukti_bayar" 
                                   accept="image/*,.pdf" required>
                            <div class="form-text">Format: JPG, PNG, PDF. Maksimal 2MB.</div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-pill">
                            <i class="bi bi-cloud-upload me-2"></i> Upload Bukti Bayar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
