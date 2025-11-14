<?php
/**
 * API Endpoint untuk fetch dokter aktif dengan kategori
 * Response: JSON dengan daftar dokter yang aktif
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../services/database.php';

// Get kategori filter dari query string
$filter_kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    $conn = Database::getConnection();
    
    // Base query
    $query = "SELECT DISTINCT d.id_dokter, d.nama_dokter, d.foto, d.pengalaman, d.rate, d.ttl, 
                     l.nama_klinik, l.alamat, l.lat, l.long
              FROM m_dokter d
              LEFT JOIN m_lokasipraktik l ON d.id_dokter = l.dokter
              WHERE d.status = 'aktif' AND d.status_approval = 'approved'";
    
    $params = [];
    
    // Filter berdasarkan kategori
    if ($filter_kategori && $filter_kategori !== 'all') {
        $query .= " AND d.id_dokter IN (
                        SELECT dd.id_dokter FROM detail_dokter dd
                        INNER JOIN m_kategori k ON dd.id_kategori = k.id_kategori
                        WHERE k.nama_kateg = ?
                    )";
        $params[] = $filter_kategori;
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
    }
    
    // Return response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'total' => count($dokters),
        'data' => array_values($dokters)
    ]);
    
} catch (PDOException $e) {
    error_log("API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
}
?>
