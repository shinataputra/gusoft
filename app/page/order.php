<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../app/db.php';

// Cek login
if (!isset($_SESSION['user'])) {
    header('Location: /gusoft/public/login.php');
    exit;
}

$user = $_SESSION['user'];
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID aplikasi tidak ditemukan.";
    exit;
}

// Ambil data produk + kategori
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = :id
");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Aplikasi tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pembelian - <?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://app-sandbox.duitku.com/lib/js/duitku.js"></script>
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
</head>


<script>
    $(function() {
        $('#btnBayarDuitku').on('click', function(e) {
            var productId = $(this).data('product-id');
            var $btn = $(this);
            $btn.prop('disabled', true).text('Memproses...');

            $.ajax({
                url: '/gusoft/public/payment', // pastikan URL sesuai route payment_duitku.php Anda
                type: 'POST',
                data: {
                    product_id: productId
                },
                dataType: 'json',
                success: function(result) {
                    if (result.reference) {
                        checkout.process(result.reference, {
                            successEvent: function(res) {
                                // bisa redirect ke dashboard atau tampilkan pesan
                                window.location.href = '/gusoft/public/pembayaran_sukses';
                            },
                            pendingEvent: function(res) {
                                alert('Pembayaran pending, silakan cek status.');
                                $btn.prop('disabled', false).text('Bayar Sekarang via Duitku');
                            },
                            errorEvent: function(res) {
                                alert('Terjadi error, coba lagi.');
                                $btn.prop('disabled', false).text('Bayar Sekarang via Duitku');
                            },
                            closeEvent: function(res) {
                                $btn.prop('disabled', false).text('Bayar Sekarang via Duitku');
                            }
                        });
                    } else {
                        alert(result.error || 'Gagal memproses pembayaran.');
                        $btn.prop('disabled', false).text('Bayar Sekarang via Duitku');
                    }
                },
                error: function(xhr) {
                    alert('Gagal menghubungi server.');
                    $btn.prop('disabled', false).text('Bayar Sekarang via Duitku');
                }
            });
        });
    });
</script>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border rounded-3">
                    <div class="card-header bg-white text-center border-bottom">
                        <i class="bi bi-receipt text-accent fs-2"></i>
                        <h5 class="fw-bold mb-0 mt-2">Konfirmasi Pembelian</h5>
                        <small class="text-muted">Silakan periksa detail aplikasi sebelum melanjutkan pembayaran</small>
                    </div>
                    <div class="card-body">

                        <!-- Info Aplikasi -->
                        <div class="d-flex mb-4 p-3 bg-light rounded shadow-sm border align-items-center flex-wrap flex-md-nowrap">
                            <div class="me-4 mb-3 mb-md-0">
                                <img src="<?= $product['thumbnail_url'] ?>" alt="Thumbnail <?= htmlspecialchars($product['name']) ?>" width="110" class="rounded border shadow-sm">
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <h4 class="fw-bold mb-2 text-primary-emphasis text-truncate" style="max-width: 100%;">
                                    <i class="bi bi-box-seam me-2 text-accent"></i><?= htmlspecialchars($product['name']) ?>
                                </h4>
                                <p class="mb-1 text-muted">
                                    <i class="bi bi-tags me-1 text-secondary"></i> Kategori:
                                    <strong><?= htmlspecialchars($product['category_name']) ?></strong>
                                </p>
                                <p class="mb-0 text-muted">
                                    <i class="bi bi-cash-stack me-1 text-secondary"></i> Harga:
                                    <strong class="fs-5 text-dark">
                                        <?= $product['price'] == 0 ? 'Gratis' : 'Rp ' . number_format($product['price']) ?>
                                    </strong>
                                </p>
                            </div>
                        </div>



                        <!-- Info Teknis -->
                        <ul class="list-group list-group-flush mb-4 border rounded">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-info-circle me-2 brand-text"></i> Versi
                                </div>
                                <span class="fw-semibold">1.2.0</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-laptop me-2 text-success"></i> Kompatibel
                                </div>
                                <span class="fw-semibold">Windows, Android</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-hdd me-2 text-warning"></i> Ukuran File
                                </div>
                                <span class="fw-semibold">12.3 MB</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-calendar-event me-2 text-secondary"></i> Tanggal Rilis
                                </div>
                                <span class="fw-semibold"><?= date('d M Y', strtotime($product['created_at'])) ?></span>
                            </li>
                        </ul>


                        <!-- Tombol -->
                        <?php if ($product['price'] == 0): ?>
                            <a href="<?= $product['file_url'] ?>" class="btn btn-success w-100 py-2">
                                <i class="bi bi-download me-1"></i> Unduh Aplikasi Sekarang
                            </a>
                        <?php else: ?>
                            <button type="button" id="btnBayarDuitku" class="btn btn-accent w-100 py-3 fs-5 shadow-sm"
                                data-product-id="<?= $product['id'] ?>">
                                <i class="bi bi-cash-coin me-2"></i> Bayar Sekarang via Duitku
                            </button>
                        <?php endif; ?>

                        <a href="/gusoft/public/catalog" class="btn btn-outline-secondary w-100 mt-3">
                            <i class="bi bi-arrow-left"></i> Kembali ke Katalog
                        </a>
                    </div>
                    <div class="card-footer text-center text-muted small">
                        Transaksi ini bersifat digital dan tidak memerlukan tanda tangan fisik.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>