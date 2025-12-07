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

    $chatId = $idChat; // Default pakai ID baru
    $message = '';

    // Ambil data chat terakhir di SQL
    $existingChatSQL = DAO_chat::findChatRoom($idDokter, $idUser);
    custom_log("Cek exist di sql: ", LOG_TYPE::ACTIVITY);

    // Tentukan apakah kita HARUS membuat chat baru?
    $shouldCreateNew = false;
    $now = time();

    if ($existingChatSQL === null) {
        // 1. Belum ada chat sama sekali
        $shouldCreateNew = true;
        custom_log("Chat tidak ditemukan, set create new.", LOG_TYPE::ACTIVITY);
    } else {
        // 2. Chat ada, cek apakah sudah expired
        $end = $existingChatSQL->getWaktuSelesai();
        if ($now >= $end) {
            $shouldCreateNew = true; // Ada tapi expired, buat baru
            custom_log("Chat expired, set create new.", LOG_TYPE::ACTIVITY);
        } else {
            // 3. Masih aktif, pakai yang lama
            $shouldCreateNew = false;
            $chatId = $existingChatSQL->getIdChat(); // Override $chatId dengan yang lama
            custom_log("Chat aktif ditemukan: " . $chatId, LOG_TYPE::ACTIVITY);
        }
    }

    // --- PROSES PEMBUATAN CHAT BARU (MYSQL & FORM) ---
    if ($shouldCreateNew) {
        $harga = DAO_dokter::getHarga($idDokter);
        if ($harga === null) {
            return ['success' => false, 'message' => 'Gagal memuat harga dokter.'];
        }

        $FormatNow = date('Y-m-d H:i:s', $now);
        // Registrasi ke MySQL
        $hasil = DAO_chat::registChatRoom($chatId, $idUser, $idDokter, $FormatNow, $harga);
        custom_log("regist chat room sql: " . ($hasil ? 'sukses' : 'gagal'), LOG_TYPE::ACTIVITY);

        if ($hasil) {
            // Simpan Form ke MongoDB
            $form = DAO_MongoDB_Chat::insertConsultationForm($chatId, $formKonsul);
            custom_log("insert form di mongodb result: " . ($form === true ? 'sukses' : 'gagal'), LOG_TYPE::ACTIVITY);

            if ($form === true) {
                $message = "Chat room berhasil dibuat dan formulir tersimpan.";
            } else {
                custom_log("Gagal menyimpan form ke MongoDB", LOG_TYPE::ERROR);
                return ['success' => false, 'message' => 'Gagal membuat Formulir.'];
            }
        } else {
            return ['success' => false, 'message' => 'Gagal membuat Chat Room SQL.'];
        }
    }

    // --- PROSES PENGECEKAN/PEMBUATAN ROOM MONGODB (Log Chat) ---
    // Logika ini sekarang jalan untuk chat BARU maupun LAMA

    custom_log("Mulai cek room MongoDB untuk ChatID: " . $chatId, LOG_TYPE::ACTIVITY);

    $existingMongoChat = DAO_MongoDB_Chat::findChatRoom($chatId);

    if (!$existingMongoChat) {
        custom_log("Room MongoDB belum ada, membuat baru...", LOG_TYPE::ACTIVITY);
        $created = createMongoDB_Chat($chatId);

        if ($created['success'] == false) {
            return ['success' => false, 'message' => 'Gagal memuat log chat MongoDB. ' . $created['message']];
        }
        custom_log("Room MongoDB berhasil dibuat.", LOG_TYPE::ACTIVITY);
    } else {
        custom_log("Room MongoDB sudah ada.", LOG_TYPE::ACTIVITY);
    }

    return ['success' => true, 'message' => $message, 'chat_id' => $chatId];
}

function createMongoDB_Chat($chatId)
{
    custom_log("masuk fungsi createmongodbchat: " . $chatId, LOG_TYPE::ACTIVITY);

    $mongoChatObjectId = DAO_MongoDB_Chat::createChatRoom($chatId);

    // Cek apakah hasilnya BUKAN string (berarti error object?) atau string yang diawali 'Gagal'
    if (!is_string($mongoChatObjectId) || str_starts_with($mongoChatObjectId, 'Gagal')) {
        return ['success' => false, 'message' => 'Gagal membuat log chat. ' . json_encode($mongoChatObjectId)];
    }

    custom_log("Sukses create di mongodb.", LOG_TYPE::ACTIVITY);
    return ['success' => true, 'message' => "Log chat MongoDB siap."];
}

// --- LOGIKA ROUTING CONTROLLER INI ---
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'initChat') {
        custom_log("masuk initchat: ", LOG_TYPE::ACTIVITY);
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $idUser = $_SESSION['user']->getIdUser();
        $dokterId = $data['id_dokter'] ?? null;
        $idChat = $data['id_chat'] ?? null;
        $formKonsul = $data['formKonsul'] ?? null;
        $response = "";

        // Safety check untuk idDokter
        if (empty($dokterId) || $idUser != $data['id_user'] || empty($idChat) || empty($formKonsul)) {
            $response = ['success' => false, 'message' => 'ID tidak valid atau form kosong.'];
            custom_log("tidak valid atau form kosong: ", LOG_TYPE::ACTIVITY);
            $httpCode = 400;
        } else {
            $idDokter = hashId($dokterId, false);
            $idChat .= $idDokter;
            custom_log("ini id chat : " . $idChat, LOG_TYPE::ACTIVITY);
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
    } elseif ($_GET['action'] === 'getChatForm') {
        $chatId = $_GET['chat_id'] ?? null;
        if (!$chatId) {
            $response = ['success' => false, 'message' => 'Chat ID tidak ditemukan di URL.'];
            header('Content-Type: application/json', true, 400);
            echo json_encode($response);
            exit;
        }
        $chatSession = DAO_MongoDB_Chat::getChatForm($chatId);
        if ($chatSession) {
            $response = [
                'success' => true,
                'message' => 'Sesi chat ini ditemukan.',
                'session' => $chatSession
            ];
            header('Content-Type: application/json', true, 200);
        } else {
            $response = ['success' => false, 'message' => 'Sesi chat ini tidak ditemukan di kepemilikan Anda.'];
            header('Content-Type: application/json', true, 404);
        }
        echo json_encode($response);
        exit;

    } elseif ($_GET['action'] === 'submitRating') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Metode permintaan tidak diizinkan.']);
            return;
        }

        $json_data = file_get_contents('php://input');

        $data = json_decode($json_data);

        if ($data === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data JSON tidak valid.']);
            return;
        }
        $chatId = $data->id ?? null;
        $rating = $data->rating ?? 1;
        $idDokter = $data->dokterId ?? null;

        if (!$chatId || !$idDokter) {
            $response = ['success' => false, 'message' => "Chat ID atau rating tidak ditemukan di request. $chatId | $rating | $idDokter"];
            header('Content-Type: application/json', true, 400);
            echo json_encode($response);
            exit;
        }
        $result = DAO_chat::registChatRoom($chatId, liked: ['isLiked' => true, 'rating' => $rating]);

        $dokter = hashId($idDokter, false);
        $nilai = DAO_chat::getRating($dokter);
        $total = 0;
        $jumlah = 0;
        if (!empty($nilai)) {
            $total = $nilai['total'];
            $jumlah = $nilai['suka'];
        }
        if ($total > 0) {
            $rating = round($jumlah / $total, 2);
        }
        $hasil = DAO_dokter::updateRate($dokter, $rating);
        if ($result && $hasil[0]) {
            $response = [
                'success' => true,
                'message' => 'Rating berhasil disubmit.',
            ];
            header('Content-Type: application/json', true, 200);
        } else {
            $response = ['success' => false, 'message' => 'Gagal menyimpan rating.'];
            header('Content-Type: application/json', true, 500);
        }
        echo json_encode($response);
        exit;
    }
}
?>