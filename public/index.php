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
        require __DIR__ . '/../app/auth/register.php';
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
        require __DIR__ . '/../app/page/catalog.php';
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

    case (preg_match('/^produk\/(\d+)(?:-[a-z0-9\-]+)?$/', $path, $m) ? true : false):
        $_GET['id'] = $m[1];
        require __DIR__ . '/../app/page/aplication_detail.php';
        break;

    case (preg_match('/^order\/(\d+)$/', $path, $m) ? true : false):
        $_GET['id'] = $m[1];
        require __DIR__ . '/../app/page/order.php';
        break;

    // case (preg_match('/^payment\/(\d+)$/', $path, $m) ? true : false):
    //     $_GET['product_id'] = $m[1];
    //     require __DIR__ . '/../app/page/payment_duitku.php';
    //     break;

    case ($path === 'payment' && $_SERVER['REQUEST_METHOD'] === 'POST'):
        require __DIR__ . '/../app/page/payment_duitku.php';
        break;

    case 'callback':
        require __DIR__ . '/../app/page/callback.php';
        break;

    case 'pembayaran_sukses':
        require __DIR__ . '/../app/page/pembayaran_sukses.php';
        break;









    default:
        http_response_code(404);
        echo "404 - Halaman tidak ditemukan.";
        break;
}
