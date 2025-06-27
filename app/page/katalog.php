<?php


if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: /gusoft/public/login');
    exit;
}
require __DIR__ . '../../db.php';

// Tangkap filter & search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// Query kategori
$kategori_sql = "SELECT id, name FROM categories ORDER BY name";
$kategori_stmt = $pdo->query($kategori_sql);
$kategori_list = $kategori_stmt->fetchAll(PDO::FETCH_ASSOC);


// Query produk
// $query = "SELECT * FROM products WHERE 1=1";
$query = "
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE 1=1
    ";

$params = [];

if ($search !== '') {
    $query .= " AND name LIKE :search";
    $params['search'] = "%$search%";
}

if ($kategori !== '') {
    $query .= " AND kategori = :kategori";
    $params['kategori'] = $kategori;
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Semua Aplikasi - Gudang Software</title>
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/gusoft/public/">
                <span style="color: #6c63ff;">Gudang Software</span>
            </a>

            <!-- Toggle untuk mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto">
                    <?php if (!isset($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/gusoft/public/login">Login</a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/gusoft/public/<?= $_SESSION['user']['role'] ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/gusoft/app/auth/logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">

        <h2 class="fw-bold mb-4">Temukan Aplikasimu Disini</h2>

        <!-- Filter -->
        <form method="GET" class="mb-4 d-flex flex-wrap gap-2">
            <input type="text" name="search" class="form-control" placeholder="Cari aplikasi..." value="<?= htmlspecialchars($search) ?>" style="max-width: 300px;">
            <select name="kategori" class="form-select" style="max-width: 200px;">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori_list as $kat): ?>
                    <option value="<?= $kat['id'] ?>" <?= $kategori == $kat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-accent">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>

        <!-- Hasil -->
        <?php if (count($apps) === 0): ?>
            <div class="alert alert-warning">Tidak ada aplikasi ditemukan.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($apps as $app): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card product-card shadow-sm">
                            <img src="<?= $app['thumbnail_url'] ?>" class="product-thumbnail" alt="<?= htmlspecialchars($app['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($app['name']) ?></h5>
                                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($app['category_name']) ?></span>
                                <p class="text-muted"><?= mb_strimwidth(strip_tags($app['description']), 0, 80, "...") ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><?= $app['price'] == 0 ? 'Gratis' : 'Rp ' . number_format($app['price']) ?></strong>
                                    <a href="detail_aplikasi.php?id=<?= $app['id'] ?>" class="btn btn-outline-primary btn-sm">Lihat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>