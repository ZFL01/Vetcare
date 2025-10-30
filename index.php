<?php
// Start session at the very beginning to avoid header issues
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Main entry point for PHP application
$route = isset($_GET['route']) ? $_GET['route'] : '';

// ----------------------------------------------------
// BAGIAN 1: LOGIKA KONTROLER (MEMPROSES DATA & REDIRECT)
// ----------------------------------------------------

// Definisikan variabel default
$pageTitle = 'VetCare - Perawatan Hewan Terbaik';
$pageDescription = 'Platform Telemedicine #1 untuk Hewan - Konsultasi online dengan dokter hewan terpercaya';
$contentFile = 'pages/home.php';

// Route handling: Tentukan file konten dan jalankan logika controller
switch ($route) {
    case 'auth':
        $pageTitle = 'Masuk/Daftar - VetCare';
        $pageDescription = 'Masuk atau daftar akun VetCare';
        // LANGKAH KRITIS: Muat auth.php DI SINI. 
        // Logika POST handler di auth.php akan dieksekusi.
        // Jika login berhasil, header('Location: ...') akan dipanggil.
        include 'pages/auth.php'; 
        // Setelah auth.php diproses, kita set $contentFile ke string kosong
        // agar bagian View di bawah tidak memuat auth.php lagi.
        $contentFile = ''; 
        break;
    case 'auth-dokter':
        $pageTitle = 'Masuk/Daftar Dokter - VetCare';
        $pageDescription = 'Masuk atau daftar akun dokter VetCare';
        $contentFile = 'pages/auth-dokter.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: ?route='); // Redirect akan bekerja
        exit;
    // --- Route Dashboard Baru ---
    case 'dashboard_member':
        $pageTitle = 'Dashboard Member - VetCare';
        $pageDescription = 'Area akun member dan riwayat konsultasi.';
        $contentFile = 'pages/home.php';
        break;
    case 'dashboard_dokter':
        $pageTitle = 'Dashboard Dokter - VetCare';
        $pageDescription = 'Area pengelolaan jadwal dan konsultasi dokter.';
        $contentFile = 'pages/dashboard_dokter.php';
        break;
    // --- Route Lainnya: Hanya setting variabel ---
    case '':
    case '/':
        $contentFile = 'pages/home.php';
        break;
    // ...
    default:
        $pageTitle = 'Halaman Tidak Ditemukan - VetCare';
        $pageDescription = 'Halaman yang Anda cari tidak ditemukan';
        $contentFile = 'pages/404.php';
        break;
}


// ----------------------------------------------------
// PENGAMBILAN KONTEN UTAMA (Render View ke Variabel)
// ----------------------------------------------------

// Jika $contentFile masih memiliki nama file (bukan rute 'auth' atau 'logout')
if ($contentFile !== '') {
    // Mulai buffering sementara untuk menangkap output dari file konten (view)
    ob_start(); 
    if (file_exists($contentFile)) {
        include $contentFile;
    } else {
        echo '<div class="pt-32 pb-20 text-center"><h1 class="text-4xl font-bold text-gray-800">Page not found</h1></div>';
    }
    // Simpan konten yang sudah ter-render (misal: form login)
    $pageContent = ob_get_clean();
} else {
    // Jika auth.php (controller) sudah di-include di atas, kontennya sudah terbuat 
    // atau redirect sudah terjadi. Kita set konten menjadi string kosong.
    $pageContent = '';
}


// ----------------------------------------------------
// BAGIAN 2: TAMPILAN (VIEW) - Mencetak HTML FINAL
// ----------------------------------------------------

// Jika header belum terkirim (artinya tidak ada redirect yang terjadi di auth.php)
if (!headers_sent()) {
    // 1. Muat base template (HEADER HTML, <head> dan <body> pembuka)
    include 'base.php'; 

    // 2. Output struktur tampilan
    ?>
    <div class="min-h-screen bg-gray-50">
        <?php include 'header.php'; // Muat header navigasi ?>
        <main>
            <!-- Tampilkan konten $pageContent yang sudah diproses -->
            <?php echo $pageContent; ?>
        </main>
        <?php include 'footer.php'; // Muat footer navigasi ?>
    </div>
    </body>
    </html>
    <?php
    // Kirim semua output yang tersimpan di buffer (termasuk base.php, header.php, konten)
    ob_end_flush();
} else {
    // Jika header sudah terkirim (REDIRECT berhasil), kita hanya membuang buffer HTML yang sudah dibuat.
    ob_end_clean();
}
