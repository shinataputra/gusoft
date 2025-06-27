<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '../../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    exit("Unauthorized.");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data produk
    $stmt = $pdo->prepare("SELECT file_url, thumbnail_url, image1_url, image2_url, image3_url FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product) {
        $filesToDelete = [
            $product['file_url'],
            $product['thumbnail_url'],
            $product['image1_url'],
            $product['image2_url'],
            $product['image3_url']
        ];

        // Hapus file jika ada
        foreach ($filesToDelete as $file) {
            if ($file && file_exists(__DIR__ . '/../' . ltrim($file, '/'))) {
                unlink(__DIR__ . '/../' . ltrim($file, '/'));
            }
        }

        // Hapus data dari database
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: /gusoft/public/dashboard");
exit;
