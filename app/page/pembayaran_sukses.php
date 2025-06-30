<?php
if (session_status() === PHP_SESSION_NONE) session_start();



if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pembayaran Berhasil - Gudang Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm p-4">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        <h3 class="fw-bold mt-3">Pembayaran Berhasil</h3>
                        <p class="text-muted">Terima kasih, <strong><?= htmlspecialchars($user['name']) ?></strong>. Pembayaran kamu telah diterima.</p>
                    </div>

                    <div class="my-4">
                        <a href="/gusoft/public/user" class="btn btn-accent w-100 py-2">
                            <i class="bi bi-grid me-2"></i> Lihat Aplikasi Saya
                        </a>
                        <a href="/gusoft/public/catalog" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left"></i> Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>