<?php
session_start();
require '../db.php';

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

    // Cek jika ada file baru
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "../uploadapp/";
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_url = '../uploadapp/' . $file_name;
        }
    }

    // Update ke DB
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, file_url = ? WHERE id = ?");
    $stmt->execute([$name, $description, $price, $file_url, $id]);

    $message = "Produk berhasil diperbarui!";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <h4>Admin Panel</h4>
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a class="nav-link text-white" href="admin.php">Produk</a>
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="tambah_produk.php">Tambah Produk</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item mt-4">
                    <a class="btn btn-sm btn-light" href="../auth/logout.php">Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container py-4">
            <h3>Edit Produk</h3>

            <?php if ($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="mt-3">
                <div class="mb-3">
                    <label>Nama Aplikasi</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" value="<?= $product['price'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>File Aplikasi (opsional)</label>
                    <input type="file" name="file" class="form-control">
                    <?php if ($product['file_url']): ?>
                        <small class="text-muted">File saat ini: <a href="<?= $product['file_url'] ?>" target="_blank">Download</a></small>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="admin.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</body>

</html>