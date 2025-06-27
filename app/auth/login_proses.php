<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        // Arahkan sesuai role
        if ($user['role'] === 'admin') {
            header("Location: /gusoft/public/admin");
        } elseif ($user['role'] === 'user') {
            header("Location: /gusoft/public/user");
        } else {
            $_SESSION['error'] = "Role tidak dikenali.";
            header("Location: /gusoft/public/login");
        }
        exit;
    } else {
        $_SESSION['error'] = "Email atau password salah.";
        header("Location: /gusoft/public/login");
        exit;
    }
} else {
    header("Location: /gusoft/public/login");
    exit;
}
