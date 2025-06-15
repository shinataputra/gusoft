<?php
require '../db.php';

if (isset($_GET['count_only']) && $_GET['count_only'] == 1) {
    $kategoriId = (int) $_GET['kategori'];
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = ?");
    $stmt->execute([$kategoriId]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($total);
    exit;
}


$kategoriId = (int) ($_GET['kategori'] ?? 0);
$offset = (int) ($_GET['offset'] ?? 0);
$limit = (int) ($_GET['limit'] ?? 4);

$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $kategoriId, PDO::PARAM_INT);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

foreach ($products as $product): ?>
    <div class="col-md-3 product-item" data-aos="fade-up" data-aos-duration="500">
        <div class="card h-100 product-card shadow-sm border-0">
            <img src="<?= $product['thumbnail_url'] ?: 'https://via.placeholder.com/400x250?text=Segera+Tersedia' ?>" class="product-thumbnail card-img-top" alt="Gambar Produk">

            <div class="card-body d-flex flex-column">
                <h6 class="card-title text-primary-emphasis"><?= htmlspecialchars($product['name']) ?></h6>
                <p class="text-muted small mb-3"><?= htmlspecialchars($product['description']) ?></p>
                <div class="mt-auto">
                    <div class="fw-bold mb-2 text-accent">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                    <a href="app/auth/login.php" class="btn btn-sm btn-accent w-100">Beli Sekarang</a>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>