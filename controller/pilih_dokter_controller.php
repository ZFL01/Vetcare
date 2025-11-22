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

// Enrich dokter data with additional info
foreach ($listDokter as $dokter) {
    DAO_dokter::getInfoDokter($dokter);
}

// Filter dokter by today's schedule (only if they have any schedule data)
// If no schedule exists yet, show all doctors anyway
date_default_timezone_set('Asia/Jakarta');
// Map English day names to Indonesian capitalized names (as stored in database)
$daysMap = [
    0 => 'Minggu',   
    1 => 'Senin',    
    2 => 'Selasa',   
    3 => 'Rabu',     
    4 => 'Kamis',    
    5 => 'Jumat',    
    6 => 'Sabtu'     
];
$hariIni = $daysMap[(int)date('w')];

// Check if any doctor has schedule data
$hasAnySchedule = false;
foreach ($listDokter as $dokter) {
    if (is_array($dokter->getJadwal()) && !empty($dokter->getJadwal())) {
        $hasAnySchedule = true;
        break;
    }
}

// Only filter if we have schedule data; otherwise show all doctors
if ($hasAnySchedule) {
    $dokterHariIni = array_filter($listDokter, function($dokter) use ($hariIni) {
        $jadwal = $dokter->getJadwal();
        return is_array($jadwal) && !empty($jadwal[$hariIni]);
    });

    // If filter produced results, use them. If not (nobody praktik hari ini),
    // fallback to showing all doctors so page doesn't show zero unexpectedly.
    if (!empty($dokterHariIni)) {
        $listDokter = array_values($dokterHariIni);
    }
}

if (isset($_GET['api']) && $_GET['api'] === 'true') {
    header('Content-Type: application/json');
    echo json_encode($listDokter);
    exit; 
}
// Convert kategori untuk JavaScript
$kategoriForJS = htmlspecialchars($kategori ?: '', ENT_QUOTES, 'UTF-8');
$slugToKategoriJSON = json_encode([]);

