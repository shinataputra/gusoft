<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit;
}
echo "Selamat datang User, " . $_SESSION['user']['name'];
