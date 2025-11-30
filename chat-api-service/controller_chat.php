<?php
require_once __DIR__ . '/dao_chat.php';
require_once __DIR__ . '/../includes/DAO_User.php';
require_once __DIR__ . '/../includes/DAO_Dokter.php';
require_once __DIR__ . '/../includes/DAO_others.php';
require_once __DIR__ . '/../src/config/config.php';


function initChat($idChat, $idDokter, $idUser)
{
    if (!$idChat || !$idDokter || !$idUser) {
        return ['success' => false, 'message' => 'ID tidak ditemukan atau tidak valid.'];
    }
    $chatId = null;
    $message = '';
    $ended = false;
    $exist = DAO_chat::findChatRoom($idDokter, $idUser);
    $now = time();
    if ($exist !==null) {
        $end = $exist->getWaktuSelesai();

        if ($now >= $end) {
            $ended = true;
        } else {
            $chatId = $exist->getIdChat();
            $ended = false;
        }
    }elseif ($exist ===null || $ended) {
        $FormatNow = date('Y-m-d H:i:s', $now);
        $hasil = DAO_chat::registChatRoom($idChat, $idUser, $idDokter, $FormatNow);
        if ($hasil) {
            $chatId = $idChat;
            $message = "Chat room berhasil dibuat.";
        } else {
            return ['success' => false, 'message' => 'Gagal membuat chat room.'];
        }
    }

    if ($exist) {
        $existingChat = DAO_MongoDB_Chat::findChatRoom($chatId);
        if (!$existingChat) {
            $created = createMongoDB_Chat($chatId);
            if($created['success'] == false){
                return ['success' => false, 'message' => 'Gagal memuat log chat. ' . $created['message']];
            }
        }
    }
    return ['success' => true, 'message' => $message, 'chat_id' => $chatId];
}

function createMongoDB_Chat($chatId)
{
    $mongoChatObjectId = DAO_MongoDB_Chat::createChatRoom($chatId);
    if (!is_string($mongoChatObjectId || str_starts_with($mongoChatObjectId, 'Gagal'))) {
        // MongoDB Creation Failed
        return ['success' => false, 'message' => 'Gagal membuat log chat. ' . $mongoChatObjectId];
    }
    return ['success'=>true, 'message'=>"Log chat MongoDB juga dipastikan/dibuat."];
}


// --- LOGIKA ROUTING CONTROLLER INI ---
if (isset($_GET['action']) && $_GET['action'] === 'initChat') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Asumsi: ID pengguna sudah ada di session
    $idUser = $_SESSION['user']->getIdUser();
    $idDokter = $data['id_dokter'] ?? null;
    $idChat = $data['id_chat'] ?? null;

    // Safety check untuk idDokter
    if (empty($idDokter) || $idUser != $data['id_user'] || empty($idChat)) {
        $response = ['success' => false, 'message' => 'ID tidak valid.'];
        $httpCode = 400;
    } else {
        $result = initChat($idChat, $idDokter, $idUser);
        $response = $result;
        $httpCode = $result['success'] ? 200 : 500;
    }

    // Output JSON
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo json_encode($response);
    exit;
}
?>