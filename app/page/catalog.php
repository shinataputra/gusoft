<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Pastikan koneksi DB sudah ada
require_once __DIR__ . '/../db.php';

// Ambil semua kategori dari DB
$kategori_sql = "SELECT id, name FROM categories ORDER BY name";
$kategori_stmt = $pdo->query($kategori_sql);
$kategori_list = $kategori_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Katalog Aplikasi - Gudang Software</title>
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <div class="container py-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Katalog Aplikasi</h2>
            <a href="/gusoft/public/" class="btn btn-outline-secondary">
                <i class="bi bi-house-door"></i> Kembali ke Website
            </a>
        </div>

        <!-- Form Filter -->
        <form id="filterForm" class="mb-4 d-flex flex-wrap gap-2">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari aplikasi..." style="max-width: 300px;">
            <select name="kategori" id="kategoriSelect" class="form-select" style="max-width: 200px;">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori_list as $kat): ?>
                    <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Tempat hasil load katalog -->
        <div id="produkContainer" class="row">
            <!-- Akan diisi oleh AJAX -->
        </div>
    </div>

    <!-- Script AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const kategoriSelect = document.getElementById('kategoriSelect');
            const produkContainer = document.getElementById('produkContainer');

            function loadCatalog() {
                const search = searchInput.value;
                const kategori = kategoriSelect.value;

                fetch(`/gusoft/app/page/load_catalog.php?search=${encodeURIComponent(search)}&kategori=${encodeURIComponent(kategori)}`)
                    .then(res => res.text())
                    .then(html => produkContainer.innerHTML = html)
                    .catch(() => produkContainer.innerHTML = '<div class="alert alert-danger">Gagal memuat data.</div>');
            }

            searchInput.addEventListener('input', loadCatalog);
            kategoriSelect.addEventListener('change', loadCatalog);

            loadCatalog(); // Muat data awal
        });
    </script>

</body>

</html>