<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../db.php';

// Autentikasi admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /gusoft/public/login");
    exit;
}

// Ambil semua produk
$stmt = $pdo->query("SELECT p.*, c.name AS category_name
                     FROM products p
                     JOIN categories c ON p.category_id = c.id
                     ORDER BY p.id DESC");
$products = $stmt->fetchAll();
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3 sidebar">
            <h4>Admin Panel</h4>
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a class="nav-link text-white fw-bold" href="/gusoft/public/dashboard">Produk</a>
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/gusoft/public/add-produk">Tambah Produk</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item mt-4">
                    <a class="btn btn-sm btn-light" href="/gusoft/public/logout">Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container py-4">
            <h3 class="mb-4">Daftar Produk</h3>

            <!-- Filter -->
            <div class="mb-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari nama aplikasi...">
            </div>

            <!-- Grid Produk -->
            <div class="row" id="productGrid">
                <?php foreach ($products as $p): ?>
                    <div class="col-md-4 mb-4 product-card">
                        <div class="card card-app h-100 shadow-sm">
                            <?php if (!empty($p['thumbnail_url'])): ?>
                                <img src="<?= htmlspecialchars($p['thumbnail_url']) ?>" class="card-img-top" alt="Thumbnail Produk">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                                <p class="card-text"><span class="badge bg-secondary"><?= htmlspecialchars($p['category_name']) ?></span></p>
                                <p class="card-text"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
                                <p class="card-text"><strong>Rp <?= number_format($p['price'], 0, ',', '.') ?></strong></p>
                            </div>

                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="<?= htmlspecialchars($p['file_url']) ?>" target="_blank" class="btn btn-sm btn-success">Unduh</a>
                                    <div>
                                        <a href="/edit-produk?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="/gusoft/public/delete-produk?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus produk ini?')">Hapus</a>
                                    </div>
                                </div>
                                <!-- Tiga gambar tampilan -->
                                <div class="d-flex justify-content-between">
                                    <?php if ($p['image1_url']): ?>
                                        <img src="<?= htmlspecialchars($p['image1_url']) ?>" alt="Tampilan 1" width="60" height="60" class="rounded me-1">
                                    <?php endif; ?>
                                    <?php if ($p['image2_url']): ?>
                                        <img src="<?= htmlspecialchars($p['image2_url']) ?>" alt="Tampilan 2" width="60" height="60" class="rounded me-1">
                                    <?php endif; ?>
                                    <?php if ($p['image3_url']): ?>
                                        <img src="<?= htmlspecialchars($p['image3_url']) ?>" alt="Tampilan 3" width="60" height="60" class="rounded">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Filter Script -->
    <script>
        const searchInput = document.getElementById('searchInput');
        const productCards = document.querySelectorAll('.product-card');

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            productCards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                card.style.display = title.includes(query) ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>