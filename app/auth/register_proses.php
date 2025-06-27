<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);

    // Validasi kosong
    if (!$name || !$email || !$password || !$phone) {
        $_SESSION['error'] = "Semua kolom wajib diisi kecuali alamat.";
        header("Location: /gusoft/register");
        exit;
    }

    // Cek apakah email sudah digunakan
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email sudah terdaftar.";
        header("Location: /gusoft/public/register");
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, 'user')");
    $success = $stmt->execute([$name, $email, $hashedPassword, $phone, $address]);

    if ($success) {
        $_SESSION['success'] = "Registrasi berhasil. Silakan login.";
        header("Location: /gusoft/public/login");
        exit;
    } else {
        $_SESSION['error'] = "Registrasi gagal. Silakan coba lagi.";
        header("Location: /gusoft/public/register");
        exit;
    }
} else {
    header("Location: /gusoft/public/register");
    exit;
}
