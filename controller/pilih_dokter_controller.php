<?php
/**
 * Controller untuk halaman pilih-dokter
 * Menangani logika backend dan persiapan data
 */

require_once __DIR__ . '/../includes/DAO_dokter.php';
require_once __DIR__ . '/../src/config/config.php';

// ==========================================
// 1. API ENDPOINT (REQUEST KHUSUS DARI JS)
// ==========================================
if (isset($_GET['action']) && $_GET['action'] === 'get_detail' && isset($_GET['id'])) {
    // Bersihkan output buffer agar tidak ada HTML nyasar
    ob_clean();
    header('Content-Type: application/json');

    $hashId = hashId($_GET['id'], false);
    $idDokter = (int) $hashId;

    // Buat objek dummy hanya untuk menampung ID
    // Kita perlu trick sedikit karena getInfoDokter butuh objek DTO
    // Tapi kita bisa buat method static baru atau pakai cara ini:
    $dokterDTO = new DTO_dokter(id_dokter: $idDokter);

    // Ambil info detail (Lokasi, STR, Klinik) dari database
    $found = DAO_dokter::getInfoDokter($dokterDTO);

    if ($found) {
        echo json_encode([
            'success' => true,
            'data' => $dokterDTO->jsonSerialize()
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
    exit; // STOP eksekusi, jangan load halaman HTML
}

// ==========================================
// 2. HALAMAN UTAMA (LOAD LIST DOKTER)
// ==========================================

// Get selected kategori from URL parameter
$kategori = isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : '';

// Get all available categories for filter dropdown
require_once __DIR__ . '/../includes/DAO_dokter.php';
require_once __DIR__ . '/../src/config/config.php';

if (isset($_GET['pilih'])) {
    header('Content-Type: application/json');
    if($_GET['pilih'] > 0){
        $dokter = DAO_dokter::getProfilDokter(initiate:false, idDokter: $_GET['pilih']);
        echo json_encode($dokter);
        exit;
    }else{
        echo json_encode(false);
        exit;
    }
}

$all_kategori = DAO_kategori::getAllKategori();

// Get list of doctors
$listDokter = DAO_dokter::getAllDokter();
// Detail lokasi/koordinat hanya diambil saat user klik kartu (via JS).
date_default_timezone_set('Asia/Jakarta');
$daysMap = HARI_ID;
$hariIni = $daysMap[(int) date('w')];
$now = date('H:i:s');

$enrichedDokters = [];

foreach ($listDokter as $dok) {
    $jadwal = $dok->getJadwal();
    $hariEntry = (is_array($jadwal) && isset($jadwal[$hariIni])) ? $jadwal[$hariIni] : [];

    $currentSlot = null;
    $availableNow = false;
    $nextSlot = null;

    foreach ($hariEntry as $range) {
        $buka = isset($range['buka']) ? date('H:i:s', strtotime($range['buka'])) : null;
        $tutup = isset($range['tutup']) ? date('H:i:s', strtotime($range['tutup'])) : null;
        if (!$buka || !$tutup)
            continue;

        if ($tutup < $buka) {
            if ($now >= $buka || $now < $tutup) {
                $currentSlot = ['buka' => $buka, 'tutup' => $tutup];
                $availableNow = true;
                break;
            }
        } else {
            if ($now >= $buka && $now < $tutup) {
                $currentSlot = ['buka' => $buka, 'tutup' => $tutup];
                $availableNow = true;
                break;
            }
        }
    }

    if (!$availableNow) {
        foreach ($hariEntry as $range) {
            $buka = isset($range['buka']) ? date('H:i:s', strtotime($range['buka'])) : null;
            $tutup = isset($range['tutup']) ? date('H:i:s', strtotime($range['tutup'])) : null;
            if (!$buka || !$tutup)
                continue;

            if ($tutup < $buka) {
                if ($now < $buka) {
                    $nextSlot = ['buka' => $buka, 'tutup' => $tutup];
                    break;
                }
            } else {
                if ($buka > $now) {
                    $nextSlot = ['buka' => $buka, 'tutup' => $tutup];
                    break;
                }
            }
        }
    }

    $dokArr = $dok->jsonSerialize();
    $dokArr['available_now'] = $availableNow;
    $dokArr['current_slot'] = $currentSlot;
    $dokArr['next_slot'] = $nextSlot;

    if ($availableNow) {
        $dokArr['status_text'] = 'Tersedia sekarang';
    } else if ($nextSlot) {
        $dokArr['status_text'] = 'Tersedia kembali ' . substr($nextSlot['buka'], 0, 5);
    } else {
        $dokArr['status_text'] = 'Tutup hari ini';
    }

    $enrichedDokters[] = $dokArr;
}

$listDokter = $enrichedDokters;

if (isset($_GET['api']) && $_GET['api'] === 'true') {
    header('Content-Type: application/json');
    echo json_encode($listDokter);
    exit;
}

$kategoriForJS = htmlspecialchars($kategori ?: '', ENT_QUOTES, 'UTF-8');
$slugToKategoriJSON = json_encode([]);
?>