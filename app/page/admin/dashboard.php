<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../../db.php';

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
    <title>Dashboard Admin - Gudang Software</title>
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
    <div class="d-flex">
        <!-- Sidebar Admin Panel -->
        <div id="sidebar" class="sidebar p-3 text-white" style="min-height: 100vh; width: 240px; background: linear-gradient(135deg, #6c64fb, #4a458e); font-size: 0.95rem;">
            <h6 class="fw-bold mb-3">Admin Panel</h6>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white d-flex align-items-center px-2 py-1" href="/gusoft/public/dashboard">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2 ms-3">
                    <a class="nav-link text-white-50 d-flex align-items-center px-2 py-1" href="/gusoft/public/add-produk">
                        <i class="bi bi-file-earmark-plus me-2"></i> Tambah Produk
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="btn btn-light btn-sm w-100 d-flex align-items-center justify-content-center text-dark" href="/gusoft/public/logout">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container py-4">
            <h4 class="fw-bold mb-4" style="color: #4a458e;">Daftar Produk</h4>

            <!-- Filter -->
            <div class="mb-4">
                <input type="text" id="searchInput" class="form-control form-control-sm shadow-sm" placeholder="Cari nama aplikasi...">
            </div>

            <!-- Grid Produk -->
            <div class="row" id="productGrid">

                <?php
                function categoryColor($name)
                {
                    // Hasilkan warna HEX dari nama (stabil)
                    $hash = md5($name);
                    return '#' . substr($hash, 0, 6); // contoh hasil: #a3f5b1
                }

                foreach ($products as $p): ?>
                    <div class="col-12 col-sm-6 col-lg-3 mb-4 product-card">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <?php if (!empty($p['thumbnail_url'])): ?>
                                <img src="<?= htmlspecialchars($p['thumbnail_url']) ?>" class="card-img-top" style="height: 140px; object-fit: cover;" alt="Thumbnail Produk">
                            <?php endif; ?>

                            <div class="card-body p-3">
                                <h6 class="fw-semibold mb-1" style="color: #33334b;"><?= htmlspecialchars($p['name']) ?></h6>
                                <span class="category-badge mb-2" style="background-color: <?= categoryColor($p['category_name']) ?>;">
                                    <?= htmlspecialchars($p['category_name']) ?>
                                </span>

                                <p class="small text-muted mb-1"><?= mb_strimwidth(strip_tags($p['description']), 0, 60, '...') ?></p>
                                <p class="fw-semibold text-success mb-2">Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                            </div>

                            <div class="card-footer bg-white border-0 pt-0 px-3 pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="<?= htmlspecialchars($p['file_url']) ?>" target="_blank" class="btn btn-sm btn-outline-success" title="Unduh"><i class="bi bi-download"></i></a>
                                    <div>
                                        <a href="/edit-produk?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <a href="/gusoft/public/delete-produk?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin hapus produk ini?')"><i class="bi bi-trash"></i></a>
                                    </div>
                                </div>

                                <!-- Gambar kecil -->
                                <div class="d-flex justify-content-start gap-1">
                                    <?php if ($p['image1_url']): ?>
                                        <img src="<?= htmlspecialchars($p['image1_url']) ?>" alt="1" width="38" height="38" class="rounded border">
                                    <?php endif; ?>
                                    <?php if ($p['image2_url']): ?>
                                        <img src="<?= htmlspecialchars($p['image2_url']) ?>" alt="2" width="38" height="38" class="rounded border">
                                    <?php endif; ?>
                                    <?php if ($p['image3_url']): ?>
                                        <img src="<?= htmlspecialchars($p['image3_url']) ?>" alt="3" width="38" height="38" class="rounded border">
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