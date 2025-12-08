<?php
// Start session at the very beginning to avoid header issues
require_once __DIR__ . '/includes/DAO_user.php';
require_once __DIR__ . '/includes/DAO_dokter.php';
require_once __DIR__ . '/src/config/config.php';
require_once __DIR__ . '/includes/DAO_others.php';
require_once __DIR__ . '/includes/userService.php';
require_once __DIR__ . '/chat-api-service/dao_chat.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Main entry point for PHP application
$dokter = isset($_SESSION['dokter']);

$route = isset($_GET['route']) ? $_GET['route'] : ($dokter ? 'dashboard-dokter' : '');
$action = isset($_GET['aksi']) ? $_GET['aksi'] : ''; //permintaan ajax (JS)

// Define page variables
$pageTitle = '';
$divNotFound = '<div class="pt-32 pb-20 text-center"><h1 class="text-4xl font-bold text-gray-800">Page not found</h1></div>';
$pageDescription = '';
$ajaxLoad = false;
$response = ['success' => false, 'messages' => [], 'message' => 'Data tidak lengkap.'];
$httpCode = 200;
$data = null;

//logika controller
if ($action) {
    header('Content-Type: application/json');

    if ($action === 'sendComplaint') {
        $head = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        if (empty($head) || strtolower($head) !== 'xmlhttprequest') {
            http_response_code(403);
            exit();
        }
        $email = $_POST['email'] ?? '';
        $pesan = $_POST['pesan'] ?? '';
        $reason = $email . '<br><br>' . $pesan;

        $dat = new DTO_pengguna(email: $email);
        $status = emailService::sendEmail($dat, index_email::COMPLAINT->getData($reason));

        if ($status) {
            echo json_encode(['success' => true, 'message' => "Komplain Anda sudah kami terima, Terima kasih atas perhatiannya"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Silahkan hubungi lewat nomor WA yang tercantum.']);
        }
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $response = ['success' => false, 'message' => 'Payload JSON tidak valid.'];
            $httpCode = 400;
            http_response_code($httpCode);
            echo json_encode($response);
            exit;
        }
    }

    switch ($action) {
        case 'location':
            if ($data === null) {
                $response = ['success' => false, 'message' => 'Aksi location memerlukan data POST JSON.'];
                $httpCode = 400;
                break;
            }

            $idLoc = isset($_SESSION['id_location']) ? $_SESSION['id_location'] : null;
            $idUser = isset($_SESSION['user']) ? $_SESSION['user']->getIdUser() : null;
            $lat = $data['latitude'];
            $long = $data['longitude'];
            $kota = $data['kota'] ?? '';
            $prov = $data['prov'] ?? '';
            $koor = $lat . ', ' . $long;
            error_log("halo?");
            if(empty($kota) || empty($prov)){
                $kotprov = apiControl::getCityProvince($lat, $long);
                if (!$kotprov[0]) {
                    $response = ['success' => false, 'message' => 'Gagal mengambil data tempat'];
                    $httpCode = 400;
                    break;
                }else{
                    $kota = $kotprov[0];
                    $prov = $kotprov[1];
                }
            }

            error_log("kota: $kota, prov: $prov");
            $loc = new Location($idLoc, $koor, [$kota, $prov], $idUser);
            $status = DAO_location::insertLocation($loc);
            if (is_string($status)) {
                $_SESSION['id_location'] = $status;
            }
            if ($status) {
                $response = ['success' => true, 'message' => ''];
                $httpCode = 200;
            } else {
                $response = ['success' => false, 'message' => 'gagal input data'];
                $httpCode = 500;
            }
            break;

        case 'sendMessage':
            if (isset($data['chat_id'], $data['sender_id'], $data['sender_role'], $data['content'])) {
                $result = DAO_MongoDB_Chat::insertMessage(
                    $data['chat_id'],
                    $data['sender_id'],
                    $data['sender_role'],
                    $data['content']
                );
                if ($result === true) {
                    $participants = DAO_chat::getParticipantsByChatId($data['chat_id']);
                    if ($participants) {
                        $receiverId = $data['sender_role'] === 'user' ? $participants['dokter_id'] : $participants['user_id'];
                        $recipientEmail = DAO_pengguna::getEmailById($receiverId);
                        if ($recipientEmail) {
                            $subject = $data['sender_role'] === 'user' ? 'Konsultasi baru dari Member' : 'Anda mendapat balasan dari Dokter';
                            $body = '<h3>Notifikasi Chat Vetcare</h3><p>' . htmlspecialchars($data['content']) . '</p>';
                            $mailRes = emailService::sendCustomEmail($recipientEmail, $subject, $body);
                            if (is_array($mailRes) && $mailRes[0] === true) {
                                custom_log("Email notifikasi dikirim ke: $recipientEmail | chat: {$data['chat_id']}", LOG_TYPE::ACTIVITY);
                            } else {
                                $err = is_array($mailRes) && isset($mailRes[1]) ? $mailRes[1] : 'unknown error';
                                custom_log("Gagal mengirim email ke: $recipientEmail | chat: {$data['chat_id']} | reason: $err", LOG_TYPE::ERROR);
                            }
                        } else {
                            custom_log("Email penerima tidak ditemukan untuk ID: $receiverId", LOG_TYPE::ERROR);
                        }
                    }
                    $response = ['success' => true, 'message' => 'Pesan terkirim.'];
                    $httpCode = 200;
                } else {
                    $response['message'] = 'Gagal menyimpan pesan: ' . $result;
                    $httpCode = 500;
                }
            } else {
                $response['message'] = 'Data tidak lengkap.';
                $httpCode = 400;
            }
            break;

        case 'getMessages':
            $chatId = $_GET['chat_id'] ?? null;
            $since = $_GET['since'] ?? 0; // Default awal waktu

            if ($chatId) {
                $messagesOrError = DAO_MongoDB_Chat::getNewMessages($chatId, $since);

                if (is_array($messagesOrError)) {
                    $response = [
                        'success' => true,
                        'serverTimestamp' => (new DateTime())->format(DateTime::ISO8601),
                        'messages' => $messagesOrError,
                        'message' => 'Pesan baru berhasil diambil.'
                    ];
                    $httpCode = 200;
                } else {
                    $response['message'] = 'Gagal Polling: ' . $messagesOrError;
                    $httpCode = 500;
                }
            } else {
                $response['message'] = 'Chat ID tidak ditemukan Polling.';
                $httpCode = 400;
            }
            break;
        case 'logout':
            session_unset();
            session_destroy();
            $response = ['success' => true, 'message' => 'berrhasil logout'];
            $httpCode = 200;
            break;
        default:
            $response = ['success' => false, 'message' => 'Aksi tidak valid.'];
            $httpCode = 404;
    }
    http_response_code($httpCode);
    echo json_encode($response);
    exit;
}

// Route handling: Tentukan file konten dan jalankan logika controller
switch ($route) {
    case 'auth':
        $pageTitle = 'Masuk/Daftar - VetCare';
        $pageDescription = 'Masuk atau daftar akun VetCare';
        $contentFile = 'pages/auth.php';
        $noHeaderFooter = true;
        custom_log("Route {" . $route . "} accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
    case 'auth-dokter':
        $pageTitle = 'Masuk/Daftar Dokter - VetCare';
        $pageDescription = 'Masuk atau daftar akun dokter VetCare';
        $contentFile = 'pages/auth-dokter.php';
        $noHeaderFooter = true;
        $ajaxLoad = true;
        custom_log("Route {" . $route . "} accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
    case 'dashboard-dokter':
        requireLogin(true);
        dokterAllowed(true);
        $_SESSION['prev_page'] = $route;
        $contentFile = 'pages/dokter/home_dokter.php';
        header('Location: '. $contentFile);
        custom_log("Route {". $route. "} accessed on root :". ROOT_DIR. '/'. $contentFile, LOG_TYPE::ROUTING);
        exit();
    case 'logout':
        session_unset();
        session_destroy();
        header('Location: ?route=');
        custom_log("Route {". $route. "} :", LOG_TYPE::ROUTING);
        exit;
    // --- Route Dashboard Baru ---
    case 'dashboard_member':
        $pageTitle = 'Dashboard Member - VetCare';
        $pageDescription = 'Area akun member dan riwayat konsultasi.';
        $contentFile = 'pages/home.php';
        custom_log("Route {" . $route . "} accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
    case 'pilih-dokter':
        $pageTitle = 'Pilih Dokter - VetCare';
        $pageDescription = 'Daftar dokter berdasarkan kategori yang dipilih';
        $contentFile = 'pages/pilih-dokter.php';
        custom_log("Route {" . $route . "} accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
    case 'admin':
        header('Location: ' . BASE_URL . 'admin/');
        exit();
    case 'tanya-jawab':
        requireLogin(false);
        dokterAllowed(false);
        $pageTitle = 'Tanya Jawab - VetCare';
        $pageDescription = 'Ajukan pertanyaan seputar kesehatan hewan peliharaan Anda';
        $contentFile = 'pages/Tanya-Jawab.php';
        custom_log("Route {" . $route . "} accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
    case 'chat':
        requireLogin(false);
        dokterAllowed(false);
        $pageTitle = 'Chat dengan Dokter - VetCare';
        $pageDescription = 'Mulai konsultasi online dengan dokter hewan terpercaya';
        $contentFile = 'pages/chat-dokter.php';
        custom_log("Route {" . $route . "} accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
    case 'profil':
        requireLogin(true);
        dokterAllowed(true);
        $_SESSION['prev_page'] = $route;
        $pageTitle = 'Profil - VetCare';
        $pageDescription = 'Lihat dan perbarui informasi profil Anda';
        $noHeaderFooter = true;
        $contentFile = 'pages/dokter/profile-dokter.php';
        custom_log("Route {" . $route . "} accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
    // --- Route Lainnya: Hanya setting variabel ---
    case '':
    case '/':
        $contentFile = 'pages/home.php';
        break;

    case 'klinik-terdekat':
        $pageTitle = 'Mencari Klinik Hewan Terdekat - VetCare';
        $pageDescription = 'Temukan klinik hewan terdekat dari lokasi Anda';
        $contentFile = 'pages/klinik-terdekat.php';
        custom_log("Route {" . $route . "} accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
    // ...
    default:
        $pageTitle = 'Halaman Tidak Ditemukan - VetCare';
        $pageDescription = 'Halaman yang Anda cari tidak ditemukan';
        $contentFile = '404.php';
        custom_log("Route {" . $route . "} not found accessed on root :" . ROOT_DIR . '/' . $contentFile, LOG_TYPE::ROUTING);
        break;
}

// Output content
if (isset($noHeaderFooter) && $noHeaderFooter) {
    if (file_exists($contentFile)) {
        error_log($contentFile);
        if (!$ajaxLoad) {
            include 'base.php';
        }
        include $contentFile;
    } else {
        echo $divNotFound;
    }
} else {
    if ($ajaxLoad) {
        isset($contentFile) && file_exists($contentFile) ? include $contentFile : $divNotFound;
        exit;
    }
    // Include base template
    include 'base.php';
    ?>
    <div class="min-h-screen bg-gray-50">
        <?php include 'header.php'; ?>
        <main>
            <?php
            if (file_exists($contentFile)) {
                include $contentFile;
            } else {
                echo $divNotFound;
            }
            ?>
        </main>
        <?php include 'footer.php'; ?>
    </div>
    <?php
}
