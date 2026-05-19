<?php $pageTitle = 'Laporan Transaksi'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-graph-up me-2"></i>Laporan Transaksi</h2>

    <!-- Summary dari CURSOR -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-cash-stack fs-1 mb-2"></i>
                    <h6>Total Pendapatan (dari Cursor)</h6>
                    <h2 class="fw-bold">Rp <?= number_format($pendapatan->total_pendapatan ?? 0, 0, ',', '.') ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-receipt fs-1 mb-2"></i>
                    <h6>Jumlah Transaksi Selesai</h6>
                    <h2 class="fw-bold"><?= $pendapatan->jumlah_transaksi ?? 0 ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Info implementasi -->
    <div class="alert alert-info rounded-3 mb-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Implementasi Basis Data 2:</strong> 
        Data di bawah diambil dari <code>VIEW view_laporan_booking</code> dan total pendapatan dihitung menggunakan <code>CURSOR sp_laporan_pendapatan</code>.
    </div>

    <!-- Tabel Laporan dari VIEW -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Data dari VIEW: view_laporan_booking</h6>
            <a href="<?= BASE_URL ?>/admin/reports/monthly" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="bi bi-calendar3 me-1"></i> Laporan Bulanan
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Booking</th>
                            <th>Customer</th>
                            <th>Kamar</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Subtotal</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data.</td></tr>
                        <?php else: ?>
                        <?php foreach ($laporan as $l): ?>
                        <tr>
                            <td class="fw-semibold">#<?= $l->nomor_booking ?></td>
                            <td><?= htmlspecialchars($l->nama_customer) ?></td>
                            <td><?= $l->nomor_kamar ?> (<?= $l->tipe_kamar ?>)</td>
                            <td><?= date('d/m/Y', strtotime($l->tanggal_checkin)) ?></td>
                            <td><?= date('d/m/Y', strtotime($l->tanggal_checkout)) ?></td>
                            <td>Rp <?= number_format($l->subtotal, 0, ',', '.') ?></td>
                            <td class="fw-bold">Rp <?= number_format($l->grand_total, 0, ',', '.') ?></td>
                            <td>
                                <?php
                                $sc = match($l->status_booking) {
                                    'pending' => 'warning', 'confirmed' => 'primary',
                                    'checked_in' => 'info', 'checked_out' => 'success',
                                    'cancelled' => 'danger', default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $sc ?> rounded-pill"><?= ucfirst($l->status_booking) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
