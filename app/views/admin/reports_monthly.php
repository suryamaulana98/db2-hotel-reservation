<?php $pageTitle = 'Laporan Bulanan ' . $tahun; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-calendar3 me-2"></i>Laporan Bulanan <?= $tahun ?></h2>
        <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="alert alert-info rounded-3 mb-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Implementasi:</strong> Data dari <code>CURSOR sp_laporan_bulanan</code>.
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="<?= BASE_URL ?>/admin/reports/monthly" class="d-flex gap-3 align-items-center">
                <label class="fw-semibold mb-0">Tahun:</label>
                <select name="tahun" class="form-select" style="width:150px" onchange="this.form.submit()">
                    <?php for($y=2024;$y<=2027;$y++): ?>
                    <option value="<?=$y?>" <?=$y==$tahun?'selected':''?>><?=$y?></option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr><th>Bulan</th><th>Jumlah Transaksi</th><th>Total Pendapatan</th></tr>
                    </thead>
                    <tbody>
                        <?php $gt=0; if(!empty($laporan)): foreach($laporan as $l): $gt+=$l->total_pendapatan; ?>
                        <tr>
                            <td class="fw-semibold"><?=htmlspecialchars($l->nama_bulan)?></td>
                            <td><?=$l->jumlah_transaksi>0?"<span class='badge bg-primary'>{$l->jumlah_transaksi}</span>":"<span class='text-muted'>-</span>"?></td>
                            <td class="fw-bold"><?=$l->total_pendapatan>0?'Rp '.number_format($l->total_pendapatan,0,',','.'):"<span class='text-muted'>-</span>"?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="3" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if(!empty($laporan)): ?>
                    <tfoot><tr class="table-primary"><td class="fw-bold">Total <?=$tahun?></td><td></td><td class="fw-bold fs-5">Rp <?=number_format($gt,0,',','.')?></td></tr></tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
