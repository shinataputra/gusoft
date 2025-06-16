<?php
require __DIR__ . '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, whatsapp, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $whatsapp, $password]);

    $_SESSION['success'] = "Registrasi berhasil. Silakan login.";
    header("Location: login.php");
    exit;
}
?>

<div class="container py-5" style="max-width: 500px;" data-aos="fade-up">
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #4a458e;">Daftar Akun</h2>
        <p class="text-muted small">Buat akun untuk menyimpan & mengakses aplikasi yang Anda beli.</p>
    </div>

    <form action="/gusoft/app/auth/register_process.php" method="POST" class="bg-white shadow rounded p-4">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
            <input type="text" class="form-control" name="whatsapp" id="whatsapp" placeholder="08xxxxxxxxxx" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Ulangi Kata Sandi</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
        </div>

        <button type="submit" class="btn w-100 text-white py-2 fw-semibold" style="background-color: #6c63ff;">Daftar Sekarang</button>

        <p class="mt-3 text-center text-muted small">
            Sudah punya akun? <a href="/gusoft/public/login" class="text-decoration-none" style="color: #6c63ff;">Login di sini</a>
        </p>
    </form>
</div>
<?php
require __DIR__ . '/../../footer.php';
