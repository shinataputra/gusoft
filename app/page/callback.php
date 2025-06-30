<?php
require_once __DIR__ . '/app/db.php'; // Sesuaikan path sesuai struktur kamu

// Baca payload dari Duitku (JSON POST)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Simpan log callback
$stmtLog = $pdo->prepare("INSERT INTO payment_logs (merchant_order_id, request_payload) VALUES (:id, :payload)");
$stmtLog->execute([
    'id' => $data['merchantOrderId'] ?? 'UNKNOWN',
    'payload' => $json
]);

// Validasi isi wajib
if (!isset($data['merchantOrderId'], $data['resultCode'])) {
    http_response_code(400);
    exit("Invalid callback");
}

$orderId = $data['merchantOrderId'];
$status = $data['resultCode']; // '00' = sukses, lainnya = gagal

// Cek apakah order ada
$stmtCheck = $pdo->prepare("SELECT * FROM orders WHERE merchant_order_id = :id");
$stmtCheck->execute(['id' => $orderId]);
$order = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    http_response_code(404);
    exit("Order not found");
}

// Update status
if ($status === '00') {
    $stmtUpdate = $pdo->prepare("UPDATE orders SET status = 'PAID', paid_at = NOW() WHERE merchant_order_id = :id");
    $stmtUpdate->execute(['id' => $orderId]);
} else {
    $stmtUpdate = $pdo->prepare("UPDATE orders SET status = 'FAILED' WHERE merchant_order_id = :id");
    $stmtUpdate->execute(['id' => $orderId]);
}

http_response_code(200);
echo "OK";
