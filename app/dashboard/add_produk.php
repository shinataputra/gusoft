<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '../../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$message = '';

// Proses tambah produk
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $price       = (int) $_POST['price'];
    $category_id = (int) $_POST['category_id'];
    if ($category_id <= 0) {
        $category_id = null; //
    }
    $file_url = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = __DIR__ . '/../../public/upload/app/';
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_url = '/gusoft/public/upload/app/' . $file_name;
        }
    }


    $upload_dir = __DIR__ . '/../../public/upload/img/';

    function upload_image($field_name, $upload_dir)
    {
        if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] == 0) {
            $file_name = time() . "_" . basename($_FILES[$field_name]["name"]);
            $target_path = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES[$field_name]["tmp_name"], $target_path)) {
                return '/gusoft/public/upload/img/' . $file_name;
            }
        }
        return null;
    }


    $thumbnail = upload_image('thumbnail', $upload_dir);
    $image1 = upload_image('image1', $upload_dir);
    $image2 = upload_image('image2', $upload_dir);
    $image3 = upload_image('image3', $upload_dir);


    $stmt = $pdo->prepare("INSERT INTO products 
        (name, description, price, file_url, thumbnail_url, image1_url, image2_url, image3_url, category_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $name,
        $description,
        $price,
        $file_url,
        $thumbnail,
        $image1,
        $image2,
        $image3,
        $category_id
    ]);


    $message = "Produk berhasil ditambahkan!";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
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
                    <a class="nav-link text-white fw-bold" href="/gusoft/public/dashboard/">Produk</a>
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
            <h3>Tambah Produk Baru</h3>

            <?php if ($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="mt-4 bg-white p-4 rounded shadow-sm">
                <!-- <h4 class="mb-4">Form Tambah Produk</h4> -->

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Aplikasi</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Aplikasi Pembayaran" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control form-control-sm" placeholder="Contoh: 50000" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Tulis penjelasan singkat aplikasi..." required></textarea>
                    </div>

                    <?php
                    $kategoriStmt = $pdo->query("SELECT id, name FROM categories");
                    $allCategories = $kategoriStmt->fetchAll();
                    ?>
                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($allCategories as $kat): ?>
                                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="col-12">
                        <label class="form-label">File Aplikasi (.zip, .pdf, .exe, dll)</label>
                        <input type="file" name="file" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gambar Tampilan 1</label>
                        <input type="file" name="image1" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gambar Tampilan 2</label>
                        <input type="file" name="image2" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gambar Tampilan 3</label>
                        <input type="file" name="image3" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100">Tambah Produk</button>
                </div>
            </form>

        </div>
    </div>
</body>

</html>