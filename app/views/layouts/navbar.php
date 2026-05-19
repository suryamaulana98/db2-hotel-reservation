<?php
/**
 * Layout: Navbar
 * Navigasi utama untuk user dan admin
 */
$user = Middleware::user();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary-gradient sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= BASE_URL ?>/">
            <i class="bi bi-building me-2 fs-4"></i>
            <?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/rooms">
                        <i class="bi bi-door-open me-1"></i> Kamar
                    </a>
                </li>
                <?php if ($user && $user->role === 'user'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/booking/history">
                        <i class="bi bi-clock-history me-1"></i> Riwayat Booking
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($user && $user->role === 'admin'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-1"></i> Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/rooms"><i class="bi bi-door-closed me-2"></i>Kelola Kamar</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/bookings"><i class="bi bi-journal-check me-2"></i>Kelola Booking</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/reports"><i class="bi bi-graph-up me-2"></i>Laporan</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/reports/monthly"><i class="bi bi-calendar3 me-2"></i>Laporan Bulanan</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/logs"><i class="bi bi-list-check me-2"></i>Activity Logs</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($user): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($user->nama) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-muted small"><?= $user->role ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/login">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light btn-sm ms-2 px-3" href="<?= BASE_URL ?>/register">
                        Register
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php
// Flash message
if (isset($_SESSION['flash'])):
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
?>
<div class="container mt-3">
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>
