<?php $pageTitle = 'Login'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-page d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <!-- Flash Message -->
                <?php if (isset($_SESSION['flash'])):
                    $flash = $_SESSION['flash'];
                    unset($_SESSION['flash']);
                ?>
                <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-building fs-1 text-primary"></i>
                            <h3 class="fw-bold mt-2"><?= APP_NAME ?></h3>
                            <p class="text-muted">Silakan login untuk melanjutkan</p>
                        </div>

                        <form method="POST" action="<?= BASE_URL ?>/login">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="contoh@email.com" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Masukkan password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </button>
                        </form>

                        <hr class="my-4">
                        <p class="text-center mb-0 text-muted">
                            Belum punya akun? <a href="<?= BASE_URL ?>/register" class="text-primary fw-semibold">Daftar di sini</a>
                        </p>
                    </div>
                </div>

                <p class="text-center mt-3 text-muted small">
                    Demo: admin@hotel.com / password123
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
