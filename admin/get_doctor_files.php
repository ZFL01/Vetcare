<?php
header('Content-Type: application/json');
// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'Admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get doctor ID from query parameter
$doctor_id = isset($_GET['doctor_id']) ? (int) $_GET['doctor_id'] : 0;

if ($doctor_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid doctor ID']);
    exit;
}
$hasil = DAO_dokter::manageDokter($doctor_id);
echo json_encode([
    'path_sip' => $hasil['path_sip'],
    'path_strv' => $hasil['path_strv']
]);

