<?php

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Gudang Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">

    <style>
        .sidebar {
            width: 220px;
            min-height: 100vh;
        }

        .card-app {
            min-height: 250px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>


            <!-- Main Content -->
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">

                <!-- Header Top -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-0">Dashboard Pengguna</h3>
                        <p class="text-muted mb-0">Halo, <strong><?= htmlspecialchars($user['name']) ?></strong> 👋 Selamat datang di Gudang Software.</p>
                    </div>
                    <a href="/gusoft/public/" class="btn btn-accent">
                        <i class="bi bi-house-door"></i> Kembali ke Website
                    </a>
                </div>

                <!-- Konten Aplikasi -->
                <?php if (empty($apps)): ?>
                    <div class="alert alert-warning">
                        Kamu belum membeli aplikasi apapun.
                    </div>
                    <a href="/gusoft/public/catalog" class="btn btn-accent">
                        <i class="bi bi-bag-plus"></i> Beli Aplikasi
                    </a>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($apps as $app): ?>
                            <div class="col-6 col-md-4 col-xl-3 mb-4">
                                <div class="card shadow-sm card-app h-100">
                                    <img src="<?= $app['thumbnail'] ?>" class="card-img-top product-thumbnail" alt="<?= $app['nama'] ?>">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= $app['nama'] ?></h5>
                                        <p class="card-text text-muted">Dibeli: <?= $app['tanggal_beli'] ?></p>
                                        <a href="<?= $app['file_path'] ?>" class="btn btn-accent mt-auto w-100" download>Unduh</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>



        </div>
    </div>

</body>

</html>