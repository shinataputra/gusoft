<?php
require '../db.php';

function slugify($text)
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
}


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

$query = "
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE 1=1
";

$params = [];

if ($search !== '') {
    $query .= " AND p.name LIKE :search";
    $params['search'] = "%$search%";
}

if ($kategori !== '') {
    $query .= " AND c.id = :kategori";
    $params['kategori'] = $kategori;
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($apps) === 0): ?>
    <div class="col-12">
        <div class="alert alert-warning">Tidak ada aplikasi ditemukan.</div>
    </div>
<?php endif; ?>

<?php foreach ($apps as $app): ?>
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card shadow-sm product-card h-100">
            <img src="<?= $app['thumbnail_url'] ?>" class="product-thumbnail" alt="<?= htmlspecialchars($app['name']) ?>">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($app['name']) ?></h5>



                <p class="text-muted"><?= mb_strimwidth(strip_tags($app['description']), 0, 80, "...") ?></p>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                    <strong><?= $app['price'] == 0 ? 'Gratis' : 'Rp ' . number_format($app['price']) ?></strong>

                    <a href="/gusoft/public/produk/<?= $app['id'] ?>-<?= slugify($app['name']) ?>" class="btn btn-outline-primary btn-sm">Lihat</a>




                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>