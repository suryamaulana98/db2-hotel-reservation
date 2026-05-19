<?php $pageTitle = 'Booking Kamar ' . $room->nomor_kamar; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-calendar-check me-2"></i> Form Booking
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Info Kamar -->
                    <div class="alert alert-light border rounded-3 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="fw-bold mb-1">Kamar <?= htmlspecialchars($room->nomor_kamar) ?> - <?= $room->tipe_kamar ?></h6>
                                <p class="text-muted mb-0 small"><?= htmlspecialchars($room->deskripsi ?? '') ?></p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span class="fs-5 fw-bold text-primary">Rp <?= number_format($room->harga, 0, ',', '.') ?></span>
                                <span class="text-muted small">/malam</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Booking -->
                    <form method="POST" action="<?= BASE_URL ?>/booking/store" id="bookingForm">
                        <input type="hidden" name="room_id" value="<?= $room->id_room ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tanggal_checkin" class="form-label fw-semibold">Tanggal Check-In</label>
                                <input type="date" class="form-control" id="tanggal_checkin" name="tanggal_checkin" 
                                       min="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_checkout" class="form-label fw-semibold">Tanggal Check-Out</label>
                                <input type="date" class="form-control" id="tanggal_checkout" name="tanggal_checkout" 
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                            </div>
                        </div>

                        <!-- Estimasi Harga -->
                        <div class="card bg-light border-0 mt-4 rounded-3">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Estimasi Biaya</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Harga per malam</span>
                                    <span>Rp <?= number_format($room->harga, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Jumlah malam</span>
                                    <span id="jumlahMalam">-</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total</span>
                                    <span class="text-primary" id="totalHarga">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4 rounded-pill">
                            <i class="bi bi-check-circle me-2"></i> Konfirmasi Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Hitung estimasi harga otomatis
const hargaPerMalam = <?= $room->harga ?>;
const checkinInput = document.getElementById('tanggal_checkin');
const checkoutInput = document.getElementById('tanggal_checkout');

function hitungTotal() {
    const checkin = new Date(checkinInput.value);
    const checkout = new Date(checkoutInput.value);
    
    if (checkin && checkout && checkout > checkin) {
        const malam = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
        document.getElementById('jumlahMalam').textContent = malam + ' malam';
        document.getElementById('totalHarga').textContent = 'Rp ' + (hargaPerMalam * malam).toLocaleString('id-ID');
    }
}

checkinInput.addEventListener('change', function() {
    // Set minimum checkout = checkin + 1 hari
    const minCheckout = new Date(this.value);
    minCheckout.setDate(minCheckout.getDate() + 1);
    checkoutInput.min = minCheckout.toISOString().split('T')[0];
    hitungTotal();
});
checkoutInput.addEventListener('change', hitungTotal);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
