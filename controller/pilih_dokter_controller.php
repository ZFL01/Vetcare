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
require_once __DIR__ . '/../src/config/config.php';
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

// Get current time once for availability checking
$now = date('H:i:s');

// Enrich ALL dokters with availability info
// Tampilkan dokter yang punya jadwal hari ini (online atau akan buka)
// JANGAN tampilkan dokter dengan status "Tutup hari ini"
$enrichedDokters = [];

foreach ($listDokter as $dok) {
    $jadwal = $dok->getJadwal();
    
    // Check if doctor has schedule for today
    $hariEntry = (is_array($jadwal) && isset($jadwal[$hariIni])) ? $jadwal[$hariIni] : [];
    
    // Skip dokter yang tidak punya jadwal hari ini -> REMOVED to show all doctors
    // if (empty($hariEntry)) {
    //     continue;
    // }
    
    // Check if currently within any time slot
    $currentSlot = null;
    $availableNow = false;
    $nextSlot = null;
    
    foreach ($hariEntry as $range) {
        $buka = isset($range['buka']) ? date('H:i:s', strtotime($range['buka'])) : null;
        $tutup = isset($range['tutup']) ? date('H:i:s', strtotime($range['tutup'])) : null;
        if (!$buka || !$tutup) continue;

        // Handle jam yang melewati tengah malam (e.g., 15:00 - 00:00 means 15:00 - 24:00)
        // Jika tutup < buka, berarti slot melewati tengah malam
        if ($tutup < $buka) {
            // Slot melewati midnight: buka - 24:00 atau 00:00 - tutup hari berikutnya
            // Sekarang dokter available jika: now >= buka OR now < tutup
            if ($now >= $buka || $now < $tutup) {
                $currentSlot = ['buka' => $buka, 'tutup' => $tutup];
                $availableNow = true;
                break;
            }
        } else {
            // Normal slot dalam satu hari
            if ($now >= $buka && $now < $tutup) {
                $currentSlot = ['buka' => $buka, 'tutup' => $tutup];
                $availableNow = true;
                break;
            }
        }
    }

    // Find next slot (if not currently available)
    if (!$availableNow) {
        foreach ($hariEntry as $range) {
            $buka = isset($range['buka']) ? date('H:i:s', strtotime($range['buka'])) : null;
            $tutup = isset($range['tutup']) ? date('H:i:s', strtotime($range['tutup'])) : null;
            if (!$buka || !$tutup) continue;

            // Handle jam yang melewati tengah malam
            if ($tutup < $buka) {
                // Slot melewati midnight: cek apakah now < buka (belum mulai), jika ya ini adalah next slot
                if ($now < $buka) {
                    $nextSlot = ['buka' => $buka, 'tutup' => $tutup];
                    break;
                }
                // Jika now >= buka, maka next slot adalah yang berikutnya (skip ini)
            } else {
                // Normal slot: find the first slot yang starts after now
                if ($buka > $now) {
                    $nextSlot = ['buka' => $buka, 'tutup' => $tutup];
                    break;
                }
            }
        }
    }

    // Convert DTO to array and inject availability fields
    $dokArr = $dok->jsonSerialize();
    $dokArr['available_now'] = $availableNow;
    $dokArr['current_slot'] = $currentSlot;
    $dokArr['next_slot'] = $nextSlot;
    
    if ($availableNow) {
        $dokArr['status_text'] = 'Tersedia sekarang';
    } else if ($nextSlot) {
        $dokArr['status_text'] = 'Tersedia kembali ' . substr($nextSlot['buka'], 0, 5);
    } else {
        // Tidak ada slot available hari ini (semua sudah tutup)
        $dokArr['status_text'] = 'Tutup hari ini';
    }

    $enrichedDokters[] = $dokArr;
}

// Use enriched list
$listDokter = $enrichedDokters;

if (isset($_GET['api']) && $_GET['api'] === 'true') {
    header('Content-Type: application/json');
    echo json_encode($listDokter);
    exit;
}
// Convert kategori untuk JavaScript
$kategoriForJS = htmlspecialchars($kategori ?: '', ENT_QUOTES, 'UTF-8');
$slugToKategoriJSON = json_encode([]);

