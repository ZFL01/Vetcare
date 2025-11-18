<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/DAO_dokter.php';

// Get kategori filter dari query string (bisa multiple dengan koma)
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Mapping slug ke nama_kateg
//gak usah mapping, cukup dari objek kategori yang dikirim (id, nama)

$dokters = DAO_dokter::getAllDokter();
    // Return response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'total' => count($dokters),
        'data' => array_values($dokters)
    ]);

?>
