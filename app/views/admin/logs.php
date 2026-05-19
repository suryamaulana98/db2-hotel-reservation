<?php $pageTitle = 'Activity Logs'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-list-check me-2"></i>Activity Logs</h2>
    <div class="alert alert-info rounded-3 mb-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Implementasi:</strong> Data ini diisi otomatis oleh <code>TRIGGER trg_log_booking</code> setiap ada booking baru.
    </div>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr><th>#</th><th>Aktivitas</th><th>Waktu</th></tr>
                    </thead>
                    <tbody>
                        <?php if(empty($logs)): ?>
                        <tr><td colspan="3" class="text-center text-muted py-4">Belum ada log.</td></tr>
                        <?php else: $i=1; foreach($logs as $log): ?>
                        <tr>
                            <td><?=$i++?></td>
                            <td><?=htmlspecialchars($log->aktivitas)?></td>
                            <td class="text-muted"><?=date('d/m/Y H:i:s',strtotime($log->created_at))?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
