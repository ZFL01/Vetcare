<?php
// Start session at the very beginning to avoid header issues
require_once __DIR__ . '/includes/DAO_user.php';
require_once __DIR__ . '/includes/DAO_dokter.php';
require_once __DIR__ . '/src/config/config.php';
require_once __DIR__ . '/includes/DAO_Article.php';
require_once __DIR__ . '/includes/DAO_others.php';
require_once __DIR__ . '/includes/userService.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Main entry point for PHP application
$route = isset($_GET['route']) ? $_GET['route'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Define page variables
$pageTitle = '';
$divNotFound = '<div class="pt-32 pb-20 text-center"><h1 class="text-4xl font-bold text-gray-800">Page not found</h1></div>';
$pageDescription = '';
$ajaxLoad = false;

// Route handling: Tentukan file konten dan jalankan logika controller
switch ($route) {
    case 'auth':
        $pageTitle = 'Masuk/Daftar - VetCare';
        $pageDescription = 'Masuk atau daftar akun VetCare';
        $contentFile = 'pages/auth.php';
        $noHeaderFooter = true;
        break;
    case 'auth-dokter':
        $pageTitle = 'Masuk/Daftar Dokter - VetCare';
        $pageDescription = 'Masuk atau daftar akun dokter VetCare';
        $contentFile = 'pages/auth-dokter.php';
        $noHeaderFooter = true;
        $ajaxLoad = true;
        break;
    case 'dashboard-dokter':
        $pageTitle = 'Dashboard Dokter - VetCare';
        $pageDescription = 'Dashboard utama dokter VetCare';
        $contentFile = 'pages/dashboard-dokter.php';
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
    case 'pilih-dokter':
        $pageTitle = 'Pilih Dokter - VetCare';
        $pageDescription = 'Daftar dokter berdasarkan kategori yang dipilih';
        $contentFile = 'pages/pilih-dokter.php';
        break;
    case 'admin-manage-dokter':
        $pageTitle = 'Manajemen Dokter - VetCare Admin';
        $pageDescription = 'Admin panel untuk verifikasi dan approval dokter';
        $contentFile = 'pages/admin-manage-dokter.php';
        break;
    case 'tanya-jawab':
        $pageTitle = 'Tanya Jawab - VetCare';
        $pageDescription = 'Ajukan pertanyaan seputar kesehatan hewan peliharaan Anda';
        $contentFile = 'pages/Tanya-Jawab.php';
        break;
    case 'chat':
        $pageTitle = 'Chat dengan Dokter - VetCare';
        $pageDescription = 'Mulai konsultasi online dengan dokter hewan terpercaya';
        $contentFile = 'pages/chat-dokter.php';
        break;
    case 'profil':
        $pageTitle = 'Profil - VetCare';
        $pageDescription = 'Lihat dan perbarui informasi profil Anda';
        $contentFile = 'pages/profil-dokter/profile-dokter.php';
        break;
    // --- Route Lainnya: Hanya setting variabel ---
    case '':
    case '/':
        $contentFile = 'pages/home.php';
        break;

    case 'klinik-terdekat':
        $pageTitle = 'Mencari Klinik Hewan Terdekat - VetCare';
        $pageDescription = '';
        $contentFile = 'klinik-terdekat.php';
    // ...
    default:
        $pageTitle = 'Halaman Tidak Ditemukan - VetCare';
        $pageDescription = 'Halaman yang Anda cari tidak ditemukan';
        $contentFile = 'pages/404.php';
        break;
}

// Output content
if (isset($noHeaderFooter) && $noHeaderFooter) {
    if (file_exists($contentFile)) {
        error_log($contentFile);
        if(!$ajaxLoad){
            include 'base.php';
        }
        include $contentFile;
    } else {
        echo $divNotFound;
    }
} else {
    if($ajaxLoad){
        isset($contentFile) && file_exists($contentFile) ? include $contentFile : $divNotFound;
        exit;
    }
// Include base template
include 'base.php';
    ?>
    <div class="min-h-screen bg-gray-50">
        <?php include 'header.php'; ?>
        <main>
            <?php
            if (file_exists($contentFile)) {
                include $contentFile;
            } else {
                echo $divNotFound;
            }
            ?>
        </main>
        <?php include 'footer.php'; ?>
    </div>
    <?php
}
