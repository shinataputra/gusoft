<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/db.php';

// Ambil kategori yang punya produk
$kategoriStmt = $pdo->query("SELECT c.id, c.name FROM categories c WHERE EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.id)");
$categories = $kategoriStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Jual Aplikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/gusoft/public/">
                <span style="color: #6c63ff;">Gudang Software</span>
            </a>

            <!-- Toggle untuk mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto">
                    <?php if (!isset($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/gusoft/public/login">Login</a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/gusoft/public/<?= $_SESSION['user']['role'] ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/gusoft/app/auth/logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Hero Section -->
    <section style="min-height: 100vh; background: #f2f6fc; display: flex; align-items: center;">
        <div class="container">
            <div class="row align-items-center">

                <!-- Konten Teks -->
                <div class="col-md-6 mb-5 mb-md-0" data-aos="fade-right" data-aos-delay="100">
                    <h1 class="display-4 fw-bold" style="color: #4a458e;">Gudang Software</h1>
                    <p class="lead" style="color: #33334b;">Temukan berbagai aplikasi terbaik untuk kebutuhan harian maupun profesional Anda. Mudah, cepat, dan terpercaya.</p>
                    <a href="/gusoft/public/catalog" class="btn btn-lg mt-4 px-4 py-2" style="background-color: #6c64fb; color: #fff; border-radius: 10px;">
                        Jelajahi Sekarang
                    </a>
                    <p class="mt-3 text-muted fst-italic" style="font-size: 0.95rem;">
                        Dipercaya oleh ribuan pengguna sebagai tempat terbaik mencari software siap pakai.
                    </p>
                </div>

                <!-- Gambar/Ilustrasi -->
                <div class="col-md-6 text-center">
                    <div style="position: relative;">
                        <img src="/gusoft/assets/images/hero-section.svg" alt="Ilustrasi Aplikasi" class="img-fluid" style="max-height: 400px;" data-aos="fade-left" data-aos-delay="100">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Beli Sekali, Gunakan Selamanya -->

    <section class="py-5" style="background-color: #f9f9fb;">
        <div class="container text-center" data-aos="fade-up">
            <h2 class="fw-bold mb-4" style="color: #4a458e;">Beli Sekali, Gunakan Selamanya</h2>
            <p class="lead mb-5" style="color: #33334b;">
                Miliki source code aplikasi pilihan Anda tanpa batas waktu.<br />
                Mudah digunakan, langsung dipakai, dan jadi milik Anda sepenuhnya.
            </p>
            <div class="row justify-content-center g-4">
                <div class="col-md-3">
                    <div class="p-4 bg-white rounded shadow-sm h-100" style="border-top: 4px solid #6c64fb;" data-aos="zoom-in" data-aos-delay="100">
                        <div class="mb-3">
                            <i class="bi bi-lightning-charge-fill fs-1 text-primary" style="color: #6c64fb;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #4a458e;">Mudah</h5>
                        <p class="small text-muted">Tinggal download, edit jika perlu, langsung jalankan.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-white rounded shadow-sm h-100" style="border-top: 4px solid #a67c85;" data-aos="zoom-in" data-aos-delay="200">
                        <div class="mb-3">
                            <i class="bi bi-wallet2 fs-1" style="color: #a67c85;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #4a458e;">Terjangkau</h5>
                        <p class="small text-muted">Harga hemat tanpa biaya langganan. Investasi sekali untuk selamanya.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-white rounded shadow-sm h-100" style="border-top: 4px solid #ffb8b8;" data-aos="zoom-in" data-aos-delay="300">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill fs-1" style="color: #ffb8b8;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #4a458e;">100% Milik Anda</h5>
                        <p class="small text-muted">Source code penuh tanpa batasan. Bebas modifikasi & distribusi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Section Highlight -->
    <section class="highlight-section d-flex align-items-center" style="min-height: 100vh; background: #f6f7ff;">
        <div class="container">
            <div class="row g-4 align-items-center">

                <!-- Carousel -->
                <div class="col-md-7" data-aos="fade-right" data-aos-delay="100">
                    <div id="carouselExample" class="carousel slide shadow rounded-4" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2"></button>
                        </div>
                        <div class="carousel-inner rounded-4">
                            <div class="carousel-item active">
                                <img src="/gusoft/assets/images/slide1.jpg" class="d-block w-100" alt="Slide 1">
                            </div>
                            <div class="carousel-item">
                                <img src="/gusoft/assets/images/slide2.jpg" class="d-block w-100" alt="Slide 2">
                            </div>
                            <div class="carousel-item">
                                <img src="/gusoft/assets/images/slide3.jpg" class="d-block w-100" alt="Slide 3">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>

                <!-- Banner Text -->
                <div class="col-md-5" data-aos="fade-left" data-aos-delay="200">
                    <h2 class="fw-bold" style="color: #4a458e;">Solusi Aplikasi Siap Pakai</h2>
                    <p class="text-muted">
                        Temukan berbagai software terbaik untuk kebutuhan bisnis, edukasi, dan produktivitas. Unduh instan dan mulai gunakan tanpa ribet.
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Aman & terpercaya</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Banyak pilihan kategori</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Dukungan penuh</li>
                    </ul>
                    <a href="#produk" class="btn btn-lg mt-3 text-white" style="background: #6c64fb;">Lihat Produk</a>
                </div>

            </div>
        </div>
    </section>


    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold" style="color: #4a458e;">Semua Jawaban Ada di Sini</h2>
                <p class="text-muted lead">Bingung mencari aplikasi yang tepat? Kami hadir dengan solusi lengkap dan tanpa ribet.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="p-4 bg-white rounded shadow-sm h-100 d-flex">
                        <div class="me-3">
                            <i class="bi bi-patch-check-fill brand-text fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold" style="color: #4a458e;">Aplikasi Siap Pakai</h5>
                            <p class="text-muted small mb-0">Langsung install & jalankan. Tidak perlu paham coding untuk bisa mulai.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="p-4 bg-white rounded shadow-sm h-100 d-flex">
                        <div class="me-3">
                            <i class="bi bi-patch-check-fill brand-text fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold" style="color: #4a458e;">Lengkap dan Variatif</h5>
                            <p class="text-muted small mb-0">Tersedia berbagai kategori: toko, sekolah, layanan publik, dan banyak lagi.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="p-4 bg-white rounded shadow-sm h-100 d-flex">
                        <div class="me-3">
                            <i class="bi bi-patch-check-fill brand-text fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold" style="color: #4a458e;">Bebas Modifikasi</h5>
                            <p class="text-muted small mb-0">Dapatkan source code penuh. Kustomisasi sesuka Anda tanpa batasan.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="p-4 bg-white rounded shadow-sm h-100 d-flex">
                        <div class="me-3">
                            <i class="bi bi-patch-check-fill brand-text fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold" style="color: #4a458e;">Dukungan Terpercaya</h5>
                            <p class="text-muted small mb-0">Butuh bantuan? Kami siap memberikan dokumentasi & panduan singkat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Produk Berdasarkan Kategori -->
    <section class="py-5">
        <div class="container">
            <?php foreach ($categories as $cat): ?>
                <h4 id="kategori-<?= $cat['id'] ?>" class="mb-4"><?= htmlspecialchars($cat['name']) ?></h4>
                <div class="row g-4 mb-4" id="produk-container-<?= $cat['id'] ?>"></div>
                <div class="text-end mb-5">
                    <button id="load-btn-<?= $cat['id'] ?>" class="btn btn-outline-primary btn-sm" onclick="loadMore(<?= $cat['id'] ?>)">Tampilkan Lebih Banyak</button>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    <!-- Footer -->
    <footer class="pt-5 pb-4 text-light" style="background-color: #33334b;">
        <div class="container">
            <div class="row gy-4">

                <!-- Informasi Layanan -->
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3" style="color: #ffb8b8;">Gudang Software</h5>
                    <p class="text-light small">
                        Jual source code premium: PHP Native, Codeigniter, Laravel, dan lainnya dengan harga terjangkau.
                        Tersedia juga jasa sewa CBT, SIAKAD, serta hosting RDM & E-Rapor.
                    </p>
                </div>

                <!-- Kontak -->
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3" style="color: #ffb8b8;">Hubungi Kami</h5>
                    <ul class="list-unstyled text-light small">
                        <li><i class="bi bi-globe"></i> www.contohwebsite.com</li>
                        <li><i class="bi bi-envelope"></i> support@contohwebsite.com</li>
                        <li><i class="bi bi-whatsapp"></i> 0822-1234-5678</li>
                    </ul>
                </div>
            </div>

            <hr class="border-light my-4" />

            <!-- Metode Pembayaran -->
            <div class="row mb-4">
                <div class="col">
                    <h6 class="fw-bold mb-3" style="color: #ffb8b8;">Metode Pembayaran</h6>
                    <img src="/gusoft/assets/images/payments/visa.svg" alt="BCA" height="40" class="me-2" />
                    <img src="/gusoft/assets/images/payments/bank-bca.svg" alt="BRI" height="40" class="me-2" />
                    <img src="/gusoft/assets/images/payments/bank-bni.svg" alt="OVO" height="40" class="me-2" />
                    <img src="/gusoft/assets/images/payments/bank-mandiri.svg" alt="Gopay" height="40" class="me-2" />
                    <img src="/gusoft/assets/images/payments/bank-cimb.svg" alt="DANA" height="40" class="me-2" />
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="row mb-4">
                <div class="col">
                    <h6 class="fw-bold mb-3" style="color: #ffb8b8;">Ikuti Kami</h6>
                    <a href="#" class="text-light me-3 fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-light me-3 fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-light me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-light me-3 fs-5"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <hr class="border-light my-3" />

            <!-- Copyright -->
            <div class="row">
                <div class="col-md-6 text-light small">
                    &copy; 2025 Gudang Software. All rights reserved.
                </div>
                <div class="col-md-6 text-end text-muted small">
                    <a href="#" class="text-light text-decoration-none me-3">Kebijakan Privasi</a>
                    <a href="#" class="text-light text-decoration-none">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const loaded = {};
        const allCounts = {};
        const initialLimit = 4;

        async function loadMore(kategoriId) {
            const container = document.getElementById(`produk-container-${kategoriId}`);
            const isExpanded = loaded[kategoriId] >= allCounts[kategoriId];

            const button = document.getElementById(`load-btn-${kategoriId}`);

            if (isExpanded) {
                // SEMBUNYIKAN produk di luar limit awal
                const items = container.querySelectorAll('.product-item');
                items.forEach((item, index) => {
                    if (index >= initialLimit) {
                        item.classList.add('hide');
                        setTimeout(() => item.remove(), 400); // tunggu transisi
                    }
                });

                loaded[kategoriId] = initialLimit;
                button.innerText = 'Tampilkan Lebih Banyak';
            } else {
                await loadProducts(kategoriId, initialLimit, loaded[kategoriId]);
                loaded[kategoriId] += initialLimit;

                if (loaded[kategoriId] >= allCounts[kategoriId]) {
                    button.innerText = 'Tampilkan Lebih Sedikit';
                }
            }
        }


        async function loadProducts(kategoriId, limit, offset) {
            const response = await fetch(`/gusoft/app/page/load_produk.php?kategori=${kategoriId}&offset=${offset}&limit=${limit}`);
            const html = await response.text();
            document.getElementById(`produk-container-${kategoriId}`).insertAdjacentHTML('beforeend', html);
        }

        async function getTotalProducts(kategoriId) {
            const res = await fetch(`/gusoft/app/page/load_produk.php?count_only=1&kategori=${kategoriId}`);
            const json = await res.json();
            return json.total || 0;
        }

        // Load awal
        <?php foreach ($categories as $cat): ?>
                (async () => {
                    const id = <?= $cat['id'] ?>;
                    allCounts[id] = await getTotalProducts(id);
                    await loadProducts(id, initialLimit, 0);
                    loaded[id] = initialLimit;
                })();
        <?php endforeach; ?>
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>


</body>

</html>