<?php
// Start session at the very beginning to avoid header issues
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Main entry point for PHP application
$route = isset($_GET['route']) ? $_GET['route'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Define page variables
$pageTitle = '';
$pageDescription = '';
$ajaxLoad = false;

// Route handling
switch ($route) {
    case '':
    case '/':
        $pageTitle = 'VetCare - Perawatan Hewan Terbaik';
        $pageDescription = 'Platform Telemedicine #1 untuk Hewan - Konsultasi online dengan dokter hewan terpercaya';
        $contentFile = 'pages/home.php';
        break;
    case 'konsultasi-dokter':
        $pageTitle = 'Konsultasi Dokter - VetCare';
        $pageDescription = 'Konsultasi langsung dengan dokter hewan via video call';
        $contentFile = 'pages/konsultasi-dokter.php';
        break;
    case 'tanya-dokter':
        $pageTitle = 'Tanya Dokter - VetCare';
        $pageDescription = 'Konsultasi langsung dengan dokter hewan via video call';
        $contentFile = 'pages/tanya-dokter.php';
        break;

    case 'klinik-terdekat':
        $pageTitle = 'Klinik Terdekat - VetCare';
        $pageDescription = 'Temukan klinik hewan terdekat di sekitar Anda';
        $contentFile = 'pages/klinik-terdekat.php';
        break;
    case 'dokter-ternak':
        $pageTitle = 'Dokter Ternak - VetCare';
        $pageDescription = 'Layanan kesehatan untuk ternak dan hewan produktif';
        $contentFile = 'pages/dokter-ternak.php';
        break;
    case 'dokter-hewan-kecil':
        $pageTitle = 'Dokter Hewan Kecil - VetCare';
        $pageDescription = 'Layanan kesehatan untuk kucing, anjing, dan hewan peliharaan kecil';
        $contentFile = 'pages/dokter-hewan-kecil.php';
        break;
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
        session_start();
        session_destroy();
        header('Location: ?route=');
        exit;
    default:
        $pageTitle = 'Halaman Tidak Ditemukan - VetCare';
        $pageDescription = 'Halaman yang Anda cari tidak ditemukan';
        $contentFile = 'pages/404.php';
        break;
}

// Output content
if (isset($noHeaderFooter) && $noHeaderFooter) {
    if (file_exists($contentFile)) {
        if(!$ajaxLoad){
            include 'base.php';
        }
        include $contentFile;
    } else {
        echo '<div class="pt-32 pb-20 text-center"><h1 class="text-4xl font-bold text-gray-800">Page not found</h1></div>';
    }
} else {
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
                echo '<div class="pt-32 pb-20 text-center"><h1 class="text-4xl font-bold text-gray-800">Page not found</h1></div>';
            }
            ?>
        </main>
        <?php include 'footer.php'; ?>
    </div>
    <?php
}
