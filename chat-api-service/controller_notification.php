<?php
/**
 * Controller untuk Notifikasi Chat
 * Endpoint: chat-api-service/controller_notification.php
 */

require_once __DIR__ . '/../includes/DAO_others.php'; // Untuk DAO_chat
require_once __DIR__ . '/dao_chat.php'; // Untuk DAO_MongoDB_Chat
require_once __DIR__ . '/../src/config/config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper untuk respons JSON
function jsonResponse($success, $data = [], $message = '')
{
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}

// Cek Login
if (!isset($_SESSION['user']) && !isset($_SESSION['dokter'])) {
    jsonResponse(false, [], 'Unauthorized');
}

$action = $_GET['action'] ?? '';

// --- 1. CHECK NOTIFICATION (Polling) ---
if ($action === 'checkNotification') {

    // Tentukan user role dan ID
    $userId = null;
    $dokterId = null;
    $role = '';

    if (isset($_SESSION['dokter'])) {
        $dokterId = $_SESSION['dokter']->getId();
        $role = 'dokter';
    } elseif (isset($_SESSION['user'])) {
        $userId = $_SESSION['user']->getIdUser();
        $role = 'user';
    }

    try {
        // Ambil semua Chat ID yang aktif dari MySQL
        // DAO_chat::getAllChats mengembalikan array [DTO_objs, ID_Chats]
        $chatData = DAO_chat::getAllChats($userId, $dokterId, true);

        $unreadTotal = 0;

        // Jika ada chat aktif
        if (!empty($chatData) && isset($chatData[1]) && is_array($chatData[1])) {
            $activeChatIds = $chatData[1];

            // Query ke MongoDB untuk hitung pesan unread
            // Kita butuh hitung pesan yang dikirim oleh LAWAN bicara
            // Jika saya 'dokter', saya cari pesan dari 'user' (senderRole != 'dokter')
            $unreadTotal = DAO_MongoDB_Chat::getUnreadCount($activeChatIds, $role);
        }

        jsonResponse(true, ['unread' => $unreadTotal]);

    } catch (Exception $e) {
        custom_log("Check Notification Error: " . $e->getMessage(), LOG_TYPE::ERROR);
        jsonResponse(false, ['unread' => 0], 'Error checking notifications');
    }

}

// --- 2. MARK AS READ ---
elseif ($action === 'markRead') {

    $chatId = $_POST['chat_id'] ?? '';

    if (empty($chatId)) {
        jsonResponse(false, [], 'Chat ID required');
    }

    $role = isset($_SESSION['dokter']) ? 'dokter' : 'user';

    // Update status di MongoDB
    // Kita tandai 'read' untuk pesan yang BUKAN dari kita (senderRole != $role)
    $count = DAO_MongoDB_Chat::markMessagesAsRead($chatId, $role);

    jsonResponse(true, ['modified' => $count]);

} else {
    jsonResponse(false, [], 'Invalid action');
}
?>