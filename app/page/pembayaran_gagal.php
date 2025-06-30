<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pembayaran Gagal - Gudang Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .failed-box {
            max-width: 480px;
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.06);
        }

        .failed-icon {
            font-size: 3rem;
            color: #dc3545;
        }

        .btn-accent {
            background-color: #6c63ff;
            color: #fff;
            border: none;
        }

        .btn-accent:hover {
            background-color: #584de2;
        }
    </style>
</head>

<body>

    <div class="failed-box text-center">
        <div class="mb-3">
            <i class="bi bi-x-circle-fill failed-icon"></i>
            <h4 class="fw-bold mt-3">Pembayaran Gagal</h4>
            <p class="text-muted">Yah, transaksi kamu tidak berhasil. Coba ulangi nanti atau gunakan metode pembayaran lain.</p>
        </div>

        <div class="d-grid gap-2 mt-4">
            <a href="/gusoft/public/catalog" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
            </a>
            <a href="/gusoft/public/user" class="btn btn-accent">
                <i class="bi bi-grid me-1"></i> Ke Dashboard Saya
            </a>
        </div>
    </div>

</body>

</html>