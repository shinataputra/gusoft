<?php
require_once '../app/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = :id
    LIMIT 1
";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Produk tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Gudang Software</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
</head>

<body>

    <!-- Hero Section -->
    <section class="py-5 highlight-section">
        <div class="container">
            <a href="/gusoft/public/catalog" class="btn btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left"></i> Kembali ke Katalog
            </a>
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div id="carouselApp" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded shadow-sm">
                            <div class="carousel-item active">
                                <img src="<?= $product['thumbnail_url'] ?>" class="d-block w-100" alt="Thumbnail">
                            </div>
                            <?php foreach (['image1_url', 'image2_url', 'image3_url'] as $img): ?>
                                <?php if (!empty($product[$img])): ?>
                                    <div class="carousel-item">
                                        <img src="<?= $product[$img] ?>" class="d-block w-100" alt="Gambar Tambahan">
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselApp" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselApp" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bold mb-3"><?= htmlspecialchars($product['name']) ?></h2>
                    <p class="text-muted mb-1">Kategori: <strong><?= htmlspecialchars($product['category_name']) ?></strong></p>
                    <p class="text-muted">Tanggal Rilis: <?= date('d M Y', strtotime($product['created_at'])) ?></p>
                    <h4 class="my-3 text-accent"><?= $product['price'] == 0 ? 'Gratis' : 'Rp ' . number_format($product['price']) ?></h4>

                    <?php if ($product['price'] == 0): ?>
                        <a href="<?= $product['file_url'] ?>" class="btn btn-accent" download>
                            <i class="bi bi-download"></i> Unduh Aplikasi
                        </a>
                    <?php else: ?>

                        <?php if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/gusoft/assets/css/style.css')) echo 'CSS TIDAK KETEMU'; ?>

                        <div class="mt-4">
                            <a href="/gusoft/public/order/<?= $product['id'] ?>" class="btn btn-accent w-100 py-3 fs-5 d-flex align-items-center justify-content-center shadow-sm">
                                <i class="bi bi-cart-check me-2 fs-5"></i> Dapatkan Aplikasi Sekarang
                            </a>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Deskripsi -->
    <section class="py-5 bg-white">
        <div class="container">
            <h4 class="fw-bold mb-3">Deskripsi Aplikasi</h4>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>
    </section>

    <!-- Spesifikasi -->
    <section class="py-5 bg-light">
        <div class="container">
            <h4 class="fw-bold mb-4">Spesifikasi Aplikasi</h4>
            <div class="row g-4">
                <!-- Kiri: Detail Teknis -->
                <div class="col-md-6">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 brand-text fs-4">
                            <i class="bi bi-tag"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Versi</div>
                            <small class="text-muted">1.2.0</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 brand-text fs-4">
                            <i class="bi bi-hdd"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Ukuran File</div>
                            <small class="text-muted">12.3 MB</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 brand-text fs-4">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Kompatibel</div>
                            <small class="text-muted">Windows, Android</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="me-3 brand-text fs-4">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Dibuat Oleh</div>
                            <small class="text-muted">Gudang Software</small>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Fitur Utama -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded border-start border-4 border-brand shadow-sm h-100">
                        <h6 class="fw-bold mb-3 brand-text">Fitur Utama</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> Rekap otomatis
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> Ekspor ke Excel
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> Multi-user login
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill text-success me-2"></i> Desain responsif
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>



    <section class="py-4 bg-white">
        <div class="container">
            <h5 class="fw-bold mb-3">Info Tambahan</h5>
            <div class="row g-3">
                <!-- Rating -->
                <div class="col-md-6">
                    <div class="border rounded px-3 py-2 small shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong>Rating Pengguna</strong>
                            <span class="text-warning">★★★★☆</span>
                        </div>
                        <div class="text-muted">4.0 dari 87 ulasan</div>
                    </div>
                </div>

                <!-- Unduhan -->
                <div class="col-md-6">
                    <div class="border rounded px-3 py-2 small shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong>Jumlah Unduhan</strong>
                            <span>2.315 kali</span>
                        </div>
                        <div class="text-muted">Versi 1.2.0 - <?= date('d M Y', strtotime($product['created_at'])) ?></div>
                    </div>
                </div>

                <!-- Wishlist + Review Link -->

                <div class="col-md-12">
                    <div id="reviewCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner text-center small border rounded shadow-sm px-4 py-3 bg-light">
                            <div class="carousel-item active">
                                <div class="fw-semibold mb-2">Ulasan Pengguna</div>
                                <p class="text-muted fst-italic">“Aplikasinya sangat membantu.”</p>
                                <small class="text-muted">– Nurul A.</small>
                            </div>
                            <div class="carousel-item">
                                <div class="fw-semibold mb-2">Ulasan Pengguna</div>
                                <p class="text-muted fst-italic">“Ringan, praktis, dan cocok untuk guru.”</p>
                                <small class="text-muted">– Budi P.</small>
                            </div>
                            <div class="carousel-item">
                                <div class="fw-semibold mb-2">Ulasan Pengguna</div>
                                <p class="text-muted fst-italic">“Fitur ekspor nilainya sangat membantu.”</p>
                                <small class="text-muted">– Sri L.</small>
                            </div>
                        </div>

                        <!-- Tombol navigasi custom -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#reviewCarousel" data-bs-slide="prev">
                            <span class="d-flex justify-content-center align-items-center border border-secondary rounded-circle" style="width: 30px; height: 30px;">
                                <i class="bi bi-chevron-left text-secondary" style="font-size: 1rem;"></i>
                            </span>
                            <span class="visually-hidden">Sebelumnya</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#reviewCarousel" data-bs-slide="next">
                            <span class="d-flex justify-content-center align-items-center border border-secondary rounded-circle" style="width: 30px; height: 30px;">
                                <i class="bi bi-chevron-right text-secondary" style="font-size: 1rem;"></i>
                            </span>
                            <span class="visually-hidden">Berikutnya</span>
                        </button>

                    </div>
                </div>


            </div>
        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>