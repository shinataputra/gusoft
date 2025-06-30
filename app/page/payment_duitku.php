<?php
// if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '../../db.php';

if (!isset($_SESSION['user'])) {
    session_start();
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

$user = $_SESSION['user'];
$product_id = $_POST['product_id'] ?? $_GET['product_id'] ?? null;

// Ambil data produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    http_response_code(404);
    echo json_encode(['error' => 'Produk tidak ditemukan.']);
    exit;
}

$merchantCode = "DS17319";
$apiKey = "7d4863bc71a4df2b6ef15bb1ad749e9b";
$paymentAmount = $product['price'];
$merchantOrderId = 'ORDER' . time();
$signature = md5($merchantCode . $merchantOrderId . $paymentAmount . $apiKey);

$data = [
    'paymentAmount' => $paymentAmount,
    'merchantOrderId' => $merchantOrderId,
    'productDetails' => $product['name'],
    'email' => $user['email'],
    'customerVaName' => $user['name'],
    'callbackUrl' => 'https://fd58-36-73-202-28.ngrok-free.app/gusoft/public/callback',
    'returnUrl' => 'https://fd58-36-73-202-28.ngrok-free.app/gusoft/public/pembayaran_sukses',
    'signature' => $signature,
    'merchantCode' => $merchantCode,
    // 'paymentMethod' => 'BC',
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
    ]
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
// === DEBUG LOG, tambahkan setelah curl_exec ===
if ($response === false) {
    echo 'Curl error: ' . curl_error($curl);
}
curl_close($curl);

// Cek hasil response dan http code
if ($httpCode == 200) {
    header('Content-Type: application/json');
    echo $response;
    exit;
} else {
    http_response_code($httpCode);
    // DEBUG: tampilkan kode dan isi response
    echo 'HTTP Code: ' . $httpCode . "\n";
    echo $response;
}


curl_close($curl);

if ($httpCode == 200) {
    header('Content-Type: application/json');
    echo $response;
    exit;
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Request ke Duitku gagal']);
}
