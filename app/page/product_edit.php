<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '../../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data produk berdasarkan ID
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: admin.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Produk tidak ditemukan.";
    exit;
}

$message = '';

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $price       = (int) $_POST['price'];
    $file_url    = $product['file_url'];

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "../uploadapp/";
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_url = '../uploadapp/' . $file_name;
        }
    }

    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, file_url = ? WHERE id = ?");
    $stmt->execute([$name, $description, $price, $file_url, $id]);

    $message = "Produk berhasil diperbarui!";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
    <style>
        .sidebar {
            width: 220px;
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3 sidebar">
            <h4 class="mb-4">Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="admin.php">📦 Kelola Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="tambah_produk.php">➕ Tambah Produk</a>
                </li>
                <li class="nav-item mt-4">
                    <a class="btn btn-sm btn-light" href="../auth/logout.php">Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <main class="p-4 flex-grow-1">
            <h3 class="fw-bold mb-3">Edit Produk</h3>

            <?php if ($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="mt-3">
                <div class="mb-3">
                    <label class="form-label">Nama Aplikasi</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" value="<?= $product['price'] ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">File Aplikasi (opsional)</label>
                    <input type="file" name="file" class="form-control">
                    <?php if ($product['file_url']): ?>
                        <small class="text-muted d-block mt-1">
                            File saat ini: <a href="<?= $product['file_url'] ?>" target="_blank">Download</a>
                        </small>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-accent">💾 Simpan Perubahan</button>
                    <a href="admin.php" class="btn btn-secondary">← Kembali</a>
                </div>
            </form>
        </main>
    </div>
</body>

</html>