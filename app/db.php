<?php
$host = 'localhost';
$db   = 'dbgs';
$user = 'root';
$pass = 'root';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
// $dsn = mysqli_connect($host, $user, $pass, $db);
// $dsn = new mysqli($host, $user, $pass, $db);

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
