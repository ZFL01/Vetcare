<?php
/**
 * API Endpoint untuk fetch dokter aktif dengan kategori
 * Response: JSON dengan daftar dokter yang aktif
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../includes/database.php';

// Get kategori filter dari query string (bisa multiple dengan koma)
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Mapping slug ke nama_kateg
$slugToKategori = [
    'peliharaan' => 'Hewan Peliharaan',
    'ternak' => 'Hewan Ternak',
    'eksotis' => 'Hewan Eksotis',
    'akuatik' => 'Hewan Akuatik',
    'kecil' => 'Hewan Kecil',
    'unggas' => 'Hewan Unggas'
];

// Handle multiple kategori (bisa array atau string dengan koma)
$kategoriArray = [];
if ($filter_kategori && $filter_kategori !== 'all') {
    if (is_array($filter_kategori)) {
        $kategoriArray = $filter_kategori;
    } else {
        $kategoriArray = explode(',', $filter_kategori);
    }
    
    // Convert slug ke nama_kateg jika perlu
    $kategoriArray = array_map(function($kat) use ($slugToKategori) {
        $kat = trim($kat);
        return isset($slugToKategori[$kat]) ? $slugToKategori[$kat] : $kat;
    }, $kategoriArray);
    $kategoriArray = array_filter($kategoriArray); // Remove empty
}

try {
    $conn = Database::getConnection();
    
    // Base query
    $query = "SELECT DISTINCT d.id_dokter, d.nama_dokter, d.foto, d.pengalaman, d.rate, d.ttl, 
                     l.nama_klinik, l.alamat, l.lat, l.long
              FROM m_dokter d
              LEFT JOIN m_lokasipraktik l ON d.id_dokter = l.dokter
              WHERE d.status = 'aktif'";
    
    $params = [];
    
    // Filter berdasarkan kategori (support multiple)
    if (!empty($kategoriArray)) {
        $placeholders = str_repeat('?,', count($kategoriArray) - 1) . '?';
        $query .= " AND d.id_dokter IN (
                        SELECT dd.id_dokter FROM detail_dokter dd
                        INNER JOIN m_kategori k ON dd.id_kategori = k.id_kategori
                        WHERE k.nama_kateg IN ($placeholders)
                    )";
        $params = array_merge($params, $kategoriArray);
    }
    
    // Filter berdasarkan search (nama dokter)
    if ($search) {
        $query .= " AND d.nama_dokter LIKE ?";
        $params[] = '%' . $search . '%';
    }
    
    $query .= " ORDER BY d.rate DESC, d.id_dokter ASC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $dokters = [];
    $listIdValid = [];
    
    // Group dokter
    foreach ($results as $row) {
        $id = $row['id_dokter'];
        $dokters[$id] = $row;
        $dokters[$id]['kategori'] = [];
        $dokters[$id]['jadwal'] = [];
        $listIdValid[] = $id;
    }
    
    // Fetch kategori untuk setiap dokter
    if (!empty($listIdValid)) {
        $idValid = implode(',', $listIdValid);
        
        $queryKategori = "SELECT dd.id_dokter, k.nama_kateg FROM m_kategori k
                         INNER JOIN detail_dokter dd ON k.id_kategori = dd.id_kategori
                         WHERE dd.id_dokter IN (" . $idValid . ")";
        $stmtKateg = $conn->query($queryKategori);
        $hasilKateg = $stmtKateg->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($hasilKateg as $row) {
            $id = $row['id_dokter'];
            if (isset($dokters[$id])) {
                $dokters[$id]['kategori'][] = $row['nama_kateg'];
            }
        }
        
        // Fetch jadwal untuk setiap dokter
        $queryJadwal = "SELECT id_dokter, hari, buka, tutup FROM m_hpraktik
                       WHERE id_dokter IN (" . $idValid . ")";
        $stmtJadwal = $conn->query($queryJadwal);
        $hasilJadwal = $stmtJadwal->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($hasilJadwal as $row) {
            $id = $row['id_dokter'];
            $hari = $row['hari'];
            if (isset($dokters[$id])) {
                if (!isset($dokters[$id]['jadwal'][$hari])) {
                    $dokters[$id]['jadwal'][$hari] = [];
                }
                $dokters[$id]['jadwal'][$hari][] = [
                    'buka' => $row['buka'],
                    'tutup' => $row['tutup']
                ];
            }
        }
        
        // Fetch jumlah review dari log_rating
        $queryReview = "SELECT id_dokter, COUNT(*) as jumlah_review 
                      FROM log_rating 
                      WHERE id_dokter IN (" . $idValid . ") AND `liked?` = 1
                      GROUP BY id_dokter";
        $stmtReview = $conn->query($queryReview);
        $hasilReview = $stmtReview->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($hasilReview as $row) {
            $id = $row['id_dokter'];
            if (isset($dokters[$id])) {
                $dokters[$id]['jumlah_review'] = (int)$row['jumlah_review'];
            }
        }
    }
    
    // Tambahkan data default untuk field yang tidak ada di database
    $currentYear = (int)date('Y');
    foreach ($dokters as $id => &$dokter) {
        // Jumlah review default jika tidak ada
        if (!isset($dokter['jumlah_review'])) {
            $dokter['jumlah_review'] = 0;
        }
        
        // Hitung pengalaman dari tahun awal praktik
        // pengalaman di database adalah YEAR type (tahun awal praktik)
        if (isset($dokter['pengalaman']) && $dokter['pengalaman']) {
            $tahunAwal = (int)$dokter['pengalaman'];
            // Jika tahun awal lebih besar dari tahun sekarang, berarti format salah
            if ($tahunAwal > $currentYear) {
                $dokter['pengalaman_tahun'] = 0;
            } else {
                $dokter['pengalaman_tahun'] = $currentYear - $tahunAwal;
            }
        } else {
            $dokter['pengalaman_tahun'] = 0;
        }
        // Simpan juga tahun awal untuk referensi
        $dokter['tahun_awal_praktik'] = isset($dokter['pengalaman']) ? (int)$dokter['pengalaman'] : null;
        
        // Status online (sementara random, bisa diganti dengan logika sesungguhnya)
        $dokter['is_online'] = (rand(0, 100) > 40); // 60% chance online
        
        // Biaya konsultasi default (bisa disesuaikan)
        $dokter['biaya_konsultasi'] = isset($dokter['biaya_konsultasi']) ? $dokter['biaya_konsultasi'] : 75000; // Default Rp 75.000
        
        // Deskripsi berdasarkan kategori pertama
        $kategoriPertama = !empty($dokter['kategori']) ? $dokter['kategori'][0] : 'Umum';
        $deskripsiMap = [
            'Hewan Peliharaan' => 'Berpengalaman menangani hewan peliharaan dan eksotis dengan pendekatan holistik',
            'Hewan Ternak' => 'Spesialis kesehatan dan produktivitas hewan ternak dengan sertifikasi internasional',
            'Hewan Eksotis' => 'Ahli dalam perawatan dan pengobatan hewan eksotis dan langka',
            'Hewan Akuatik' => 'Spesialis kesehatan ikan dan hewan air dengan pengalaman luas',
            'Hewan Kecil' => 'Berpengalaman menangani hewan kecil seperti kelinci, hamster, dan marmut',
            'Hewan Unggas' => 'Ahli dalam diagnosa dan pengobatan penyakit pada hewan unggas',
        ];
        $dokter['deskripsi'] = $deskripsiMap[$kategoriPertama] ?? 'Dokter berpengalaman dalam berbagai kasus kesehatan hewan dengan pendekatan profesional';
        
        // Bahasa yang didukung (default)
        $dokter['bahasa'] = ['Indonesia', 'English'];
    }
    unset($dokter);
    
    // Return response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'total' => count($dokters),
        'data' => array_values($dokters)
    ]);
    
} catch (PDOException $e) {
    error_log("API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
    exit;
} catch (Exception $e) {
    error_log("API General Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
    exit;
}
?>
