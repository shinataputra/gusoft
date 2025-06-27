<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/gusoft/assets/css/style.css">
</head>

<body>
    <div class="body-login">
        <div class="login-wrapper">
            <div class="login-image">
                <img src="/gusoft/assets/images/login-user.svg" alt="Ilustrasi Login">
            </div>
            <div class="login-form">
                <h3 class="mb-3">Login </br><span class="brand-text">Gudang Software</span></h3>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="post" action="/gusoft/app/auth/login_proses.php">
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
                </form>

                <p class="text-center mt-4 text-muted">
                    Belum punya akun? <a href="/gusoft/public/register">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>