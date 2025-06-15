<?php
session_start();

/* koneksi DB */
require __DIR__ . '/../app/db.php';

/* Hitung base‑path otomatis (folder tempat script berada) */
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';   // contoh: /gusoft/public/
$request  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/* Hilangkan base‑path dan trim / */
$path = trim(str_replace($basePath, '', $request), '/');

/* Routing manual */
switch ($path) {
    case '':
    case 'home':
        require __DIR__ . '/../app/home.php';
        break;

    case 'login':
        require __DIR__ . '/../app/auth/login.php';
        break;

    case 'logout':
        require __DIR__ . '/../app/auth/logout.php';
        break;

    case 'register':
        require __DIR__ . '/../app/auth/register.php';
        break;

    case 'dashboard':
        require '../app/dashboard/admin.php'; // pastikan ini path benar
        break;
    case 'add-produk':
        require __DIR__ . '/../app/dashboard/add_produk.php';
        break;
    case 'edit-produk':
        require __DIR__ . '/../app/dashboard/product_edit.php';
        break;
    case 'delete-produk':
        require __DIR__ . '/../app/dashboard/product_delete.php';
        break;


    case (preg_match('/^produk\/(\d+)$/', $path, $m) ? true : false):
        $_GET['id'] = $m[1];
        require __DIR__ . '/../app/pages/produk_detail.php';   // contoh
        break;

    default:
        http_response_code(404);
        echo "404 - Halaman tidak ditemukan.";
        break;
}
