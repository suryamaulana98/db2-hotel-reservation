<?php $pageTitle = 'Kelola Booking'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-journal-check me-2"></i>Kelola Booking</h2>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Tamu</th>
                            <th>Tanggal</th>
                            <th>Kamar</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data booking.</td></tr>
                        <?php else: ?>
                        <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td class="fw-semibold">#<?= $b->id_booking ?></td>
                            <td>
                                <?= htmlspecialchars($b->nama) ?>
                                <br><small class="text-muted"><?= $b->email ?></small>
                            </td>
                            <td><?= date('d/m/Y', strtotime($b->tanggal_booking)) ?></td>
                            <td><span class="badge bg-info"><?= $b->jumlah_kamar ?> kamar</span></td>
                            <td class="fw-bold">Rp <?= number_format($b->grand_total, 0, ',', '.') ?></td>
                            <td>
                                <?php
                                $sc = match($b->status_booking) {
                                    'pending' => 'warning', 'confirmed' => 'primary',
                                    'checked_in' => 'info', 'checked_out' => 'success',
                                    'cancelled' => 'danger', default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $sc ?> rounded-pill"><?= ucfirst($b->status_booking) ?></span>
                            </td>
                            <td>
                                <?php
                                $pc = match($b->status_pembayaran) {
                                    'verified' => 'success', 'pending' => 'warning',
                                    'rejected' => 'danger', default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $pc ?> rounded-pill"><?= ucfirst($b->status_pembayaran) ?></span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill" data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/booking/detail/<?= $b->id_booking ?>"><i class="bi bi-eye me-2"></i>Detail</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><h6 class="dropdown-header">Ubah Status Booking</h6></li>
                                        <?php foreach (['confirmed', 'checked_in', 'checked_out', 'cancelled'] as $s): ?>
                                        <?php if ($b->status_booking !== $s): ?>
                                        <li>
                                            <form method="POST" action="<?= BASE_URL ?>/admin/bookings/status/<?= $b->id_booking ?>">
                                                <input type="hidden" name="status" value="<?= $s ?>">
                                                <button type="submit" class="dropdown-item"><?= ucfirst($s) ?></button>
                                            </form>
                                        </li>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                        
                                        <?php if ($b->status_pembayaran === 'pending'): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><h6 class="dropdown-header">Verifikasi Pembayaran</h6></li>
                                        <?php
                                        // Get payment ID
                                        $db = Database::getInstance();
                                        $db->query("SELECT id_payment FROM payments WHERE id_booking = :id");
                                        $db->bind(':id', $b->id_booking);
                                        $pay = $db->single();
                                        if ($pay):
                                        ?>
                                        <li>
                                            <form method="POST" action="<?= BASE_URL ?>/admin/payments/verify/<?= $pay->id_payment ?>">
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="dropdown-item text-success"><i class="bi bi-check-circle me-2"></i>Verifikasi</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="<?= BASE_URL ?>/admin/payments/verify/<?= $pay->id_payment ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-x-circle me-2"></i>Tolak</button>
                                            </form>
                                        </li>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
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
