<?php
require_once __DIR__ . '/dao_chat.php';
require_once __DIR__ . '/../includes/DAO_user.php';
require_once __DIR__ . '/../includes/DAO_dokter.php';
require_once __DIR__ . '/../includes/DAO_others.php';
require_once __DIR__ . '/../src/config/config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function initChat($idChat, $idDokter, $idUser, $formKonsul)
{
    if (!$idChat || !$idDokter || !$idUser) {
        return ['success' => false, 'message' => 'ID tidak ditemukan atau tidak valid.'];
    }
    $chatId = null;
    $message = '';
    $ended = false;
    $exist = DAO_chat::findChatRoom($idDokter, $idUser);
    $now = time();
    if ($exist !== null) {
        $end = $exist->getWaktuSelesai();

        if ($now >= $end) {
            $ended = true;
        } else {
            $chatId = $exist->getIdChat();
            $ended = false;
        }
    } elseif ($exist === null || $ended) {
        $FormatNow = date('Y-m-d H:i:s', $now);
        $hasil = DAO_chat::registChatRoom($idChat, $idUser, $idDokter, $FormatNow);
        $message = 'berjalan di sini sebelum hasil';
        if ($hasil) {
            $form = DAO_MongoDB_Chat::insertConsultationForm($idChat, $formKonsul);
            if ($form === true) {
                $chatId = $idChat;
                $message = "Chat room berhasil dibuat dan formulir tersimpan.";
            } else {
                // Penanganan jika simpan form gagal, tapi chat sudah dibuat di MySQL
                custom_log("Gagal menyimpan form ke MongoDB: " . $form, LOG_TYPE::ERROR);
                return ['success' => false, 'message' => 'Gagal membuat Formulir.'];
            }
            $chatId = $idChat;
        } else {
            return ['success' => false, 'message' => 'Gagal membuat Chat Room.'];
        }
    }

    if ($exist) {
        $existingChat = DAO_MongoDB_Chat::findChatRoom($chatId);
        if (!$existingChat) {
            $created = createMongoDB_Chat($chatId);
            if ($created['success'] == false) {
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
    return ['success' => true, 'message' => "Log chat MongoDB juga dipastikan/dibuat."];
}


// --- LOGIKA ROUTING CONTROLLER INI ---
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'initChat') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        error_log("adakah? ". (isset($_SESSION['user'])));
        $idUser = $_SESSION['user']->getIdUser();
        $dokterId = $data['id_dokter'] ?? null;
        $idChat = $data['id_chat'] ?? null;
        $formKonsul = $data['formKonsul'] ?? null;

        // Safety check untuk idDokter
        if (empty($dokterId) || $idUser != $data['id_user'] || empty($idChat) || empty($formKonsul)) {
            $response = ['success' => false, 'message' => 'ID tidak valid atau form kosong.'];
            $httpCode = 400;
        } else {
            $idDokter = hashId($dokterId, false);
            $idChat .= $idDokter;
            custom_log("ini id chat : ".$idChat, LOG_TYPE::ACTIVITY);
            $hashedIdChat = md5($idChat);
            $result = initChat($hashedIdChat, $idDokter, $idUser, $formKonsul);
            $response = $result;
            $httpCode = $result['success'] ? 200 : 500;
        }

        // Output JSON
        header('Content-Type: application/json');
        http_response_code($httpCode);
        echo json_encode($response);
        exit;
    } elseif ($_GET['action'] === 'getChatSession') {
        $chatId = $_GET['chat_id'] ?? null;

        if (!$chatId) {
            $response = ['success' => false, 'message' => 'Chat ID tidak ditemukan di URL.'];
            header('Content-Type: application/json', true, 400);
            echo json_encode($response);
            exit;
        }
        $userId = $_SESSION['user']->getIdUser();
        $dokterId = isset($_SESSION['dokter']) ? $_SESSION['dokter']->getId() : null;

        $chatSession = DAO_chat::thisChatRoom($chatId, $userId, $dokterId);
        if ($chatSession) {
            $response = [
                'success' => true,
                'session' => $chatSession
            ];
            header('Content-Type: application/json', true, 200);
        } else {
            $response = ['success' => false, 'message' => 'Sesi chat ini tidak ditemukan di kepemilikan Anda.'];
            header('Content-Type: application/json', true, 404);
        }
        echo json_encode($response);
        exit;
    }
}
?>