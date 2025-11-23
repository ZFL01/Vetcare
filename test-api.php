<?php
/**
 * Test script untuk memverifikasi API endpoint
 */
header('Content-Type: application/json');

require_once __DIR__ . '/controller/pilih_dokter_controller.php';

// Debug output
$debug = [
    'api_called' => true,
    'listDokter_count' => count($listDokter),
    'all_kategori_count' => count($all_kategori),
    'hasAnySchedule' => $hasAnySchedule ?? false,
    'kategori' => $kategori,
    'searchTerm' => $searchTerm,
    'data' => $listDokter
];

echo json_encode($debug, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
