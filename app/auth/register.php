<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
    <style>
        /* Tambahan khusus untuk halaman register agar lebih lebar */
        .login-wrapper {
            max-width: 960px;
            /* default biasanya 700-800px, ini dibuat lebih lebar */
            width: 100%;
            margin: 40px auto;
            display: flex;
            gap: 40px;
            padding: 20px;
        }

        .login-form {
            flex: 1.2;
        }

        .login-image {
            flex: 1;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="body-login">
        <div class="login-wrapper">
            <!-- Gambar ilustrasi -->
            <div class="login-image" data-aos="fade-up">
                <img src="/gusoft/assets/images/login-user.svg" alt="Ilustrasi Register">
            </div>

            <!-- Formulir Pendaftaran -->
            <div class="login-form">
                <h3 class="mb-2">Buat Akun </br> <span class="brand-text">Gudang Software</span></h3>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="post" action="/gusoft/app/auth/register_proses.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Aktif</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor HP</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat (Opsional)</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">Daftar Sekarang</button>
                </form>

                <p class="text-center mt-4 text-muted">
                    Sudah punya akun? <a href="/gusoft/public/login">Login di sini</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>