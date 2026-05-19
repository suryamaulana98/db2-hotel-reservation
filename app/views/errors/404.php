<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-primary">404</h1>
            <p class="fs-4 text-muted">Halaman yang Anda cari tidak ditemukan.</p>
            <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-house me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
