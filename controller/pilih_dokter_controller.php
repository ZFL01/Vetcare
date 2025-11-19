<?php
/**
 * Controller untuk halaman pilih-dokter
 * Menangani logika backend dan persiapan data
 */

// Get selected kategori from URL parameter
$kategori = isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : '';

// Get search term from URL parameter
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Get all available categories for filter dropdown
require_once __DIR__ . '/../includes/DAO_dokter.php';
$all_kategori = DAO_kategori::getAllKategori();

// Get list of doctors - ALWAYS return all dokters for API
// Filtering dilakukan di frontend (JavaScript) untuk konsistensi state
$listDokter = DAO_dokter::getAllDokter();

if (isset($_GET['api']) && $_GET['api'] === 'true') {
    header('Content-Type: application/json');
    echo json_encode($listDokter);
    exit; 
}
// Convert kategori untuk JavaScript
$kategoriForJS = htmlspecialchars($kategori ?: '', ENT_QUOTES, 'UTF-8');
$slugToKategoriJSON = json_encode([]);

