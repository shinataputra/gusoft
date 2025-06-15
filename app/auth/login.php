<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: /gusoft/public/dashboard"); // arahkan sesuai routing
        exit;
    } else {
        $_SESSION['error'] = "Email atau password salah.";
        header("Location: /gusoft/public/login"); // redirect agar bisa tampilkan error
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>


<body>
    <div class="body-login">
        <div class="login-wrapper">
            <!-- Vector Illustration Area -->
            <div class="login-image" data-aos="fade-up" data-aos-duration="500">
                <img src="/gusoft/assets/images/login-user.svg" alt="Login Illustration">
            </div>

            <!-- Form Area -->
            <div class="login-form">
                <div>

                    <h3 class="mb-2">Selamat Datang di <span class="brand-text">Gudang Software</span></h3>


                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="email" class="mb-1">Email</label>
                        <input id="email" type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="mb-1">Password</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
                </form>

                <p class="text-center mt-4 text-muted">
                    Belum punya akun? <a href="/gusoft/public/register">Daftar sekarang</a>
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