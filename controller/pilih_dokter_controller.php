<?php
/**
 * Controller untuk halaman pilih-dokter
 * Menangani logika backend dan persiapan data
 */

// Get selected kategori from URL parameter
$kategori = isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : '';

// Get all available categories for filter dropdown
require_once __DIR__ . '/../includes/DAO_dokter.php';
$all_kategori = DAO_kategori::getAllKategori();

// Mapping slug ke nama_kateg (untuk digunakan di JavaScript juga)
$slugToKategori = [
    'peliharaan' => 'Hewan Peliharaan',
    'ternak' => 'Hewan Ternak',
    'eksotis' => 'Hewan Eksotis',
    'akuatik' => 'Hewan Akuatik',
    'kecil' => 'Hewan Kecil',
    'unggas' => 'Hewan Unggas'
];

// Convert kategori untuk JavaScript
$kategoriForJS = htmlspecialchars($kategori ?: '', ENT_QUOTES, 'UTF-8');
$slugToKategoriJSON = json_encode($slugToKategori, JSON_UNESCAPED_UNICODE);

