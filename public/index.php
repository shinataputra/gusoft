<?php
session_start();

/* koneksi DB */
require __DIR__ . '/../app/db.php';


/* Hitung base‑path otomatis (folder tempat script berada) */
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
$request  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/* Hilangkan base‑path dan trim / */
$path = trim(str_replace($basePath, '', $request), '/');

/* Routing manual */
switch ($path) {
    case '':
    case '/':
        require __DIR__ . '/../app/landingpage.php';
        break;

    case 'login':
        require __DIR__ . '/../app/auth/login.php';
        break;

    case 'logout':
        require __DIR__ . '/../app/auth/logout.php';
        break;

    case 'register':
        require __DIR__ . '/../app/page/register.php';
        break;

    case 'dashboard':
        require '../app/page/admin.php';
        break;
    case 'admin':
        require '../app/page/admin/dashboard.php';
        break;
    case 'user':
        require '../app/page/user/dashboard.php';
        break;

    case 'catalog':
        require __DIR__ . '/../app/page/katalog.php';
        break;

    case 'add-produk':
        require __DIR__ . '/../app/page/add_produk.php';
        break;
    case 'edit-produk':
        require __DIR__ . '/../app/page/product_edit.php';
        break;
    case 'delete-produk':
        require __DIR__ . '/../app/page/product_delete.php';
        break;


    case (preg_match('/^produk\/(\d+)$/', $path, $m) ? true : false):
        $_GET['id'] = $m[1];
        require __DIR__ . '/../app/page/produk_detail.php';
        break;

    default:
        http_response_code(404);
        echo "404 - Halaman tidak ditemukan.";
        break;
}
