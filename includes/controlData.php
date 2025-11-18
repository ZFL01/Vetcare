<?php
header('Content-Type: application/json');
require_once 'DAO_dokter.php';

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Aksi tidak valid atau tidak ditemukan.', 'data' => null];

try {
    switch ($action) {
        // Katalog dokter
        case 'get_all_dokter':
            $data = DAO_dokter::getAllDokter(); 
            $response['status'] = 'success';
            $response['message'] = 'katalog berhasil dimuat';
            $response['data']=$data;
            break;
        // Detail dokter
        case 'info_dokter':
            $jsonInput=file_get_contents('php://input');
            $partialDat=json_decode($jsonInput,true);

            $dokter_id = $partialDat['id'] ?? 0;
            if($dokter_id===0){http_response_code(404);}
            
            $dokter_dto = new DTO_dokter($dokter_id, $partialDat['nama'],
        $partialDat['foto'],$partialDat['pengalaman'], $partialDat['rate'],
    $partialDat['kategori'], $partialDat['jadwal']); 
            if (DAO_Dokter::getInfoDokter($dokter_dto)) { 
                $response['status'] = 'success';
                $response['message'] = 'Detail dokter dimuat.';
                $response['data'] = $dokter_dto;
            } else {
                http_response_code(404);
                $response['message'] = 'Dokter tidak ditemukan.';
            }
            break;
        case 'get_all_artikel':
            
        default:
            http_response_code(400); 
    }
} catch (Exception $e) { error_log('[controlData] : '.$e->getMessage());}

echo json_encode($response);
exit;

?>