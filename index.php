<?php
// Start session at the very beginning to avoid header issues
require_once __DIR__ . '/includes/DAO_user.php';
require_once __DIR__ . '/includes/DAO_dokter.php';
require_once __DIR__ . '/src/config/config.php';
require_once __DIR__ . '/includes/DAO_Article.php';
require_once __DIR__ . '/includes/DAO_others.php';
require_once __DIR__ . '/includes/userService.php';
require_once __DIR__ . '/chat-api-service/dao_chat.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Main entry point for PHP application
$route = isset($_GET['route']) ? $_GET['route'] : '';
$action = isset($_GET['aksi']) ? $_GET['aksi'] : '';

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
            $koor = $lat . ', ' . $long;
            $kotprov = apiControl::getCityProvince($lat, $long);
            if (!$kotprov[0]) {
                $response = ['success' => false, 'message' => 'Gagal mengambil data tempat'];
                $httpCode = 400;
                break;
            }
            $loc = new Location($idLoc, $koor, [$kotprov[0], $kotprov[1]], $idUser);
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
            if (isset($data['chatId'], $data['senderId'], $data['senderRole'], $data['content'])) {
                $result = DAO_MongoDB_Chat::insertMessage(
                    $data['chatId'],
                    $data['senderId'],
                    $data['senderRole'],
                    $data['content']
                );
                if ($result === true) {
                    $response = ['success' => true, 'message' => 'Pesan terkirim.'];
                    $httpCode = 200;
                } else {
                    $response['message'] = 'Gagal menyimpan pesan: ' . $result;
                    $httpCode = 500;
                }
            }
            break;

        case 'getMessages':
            $chatId = $_GET['chatId'] ?? null;
            $since = $_GET['since'] ?? (new DateTime('1970-01-01'))->format(DateTime::ISO8601); // Default awal waktu

            if ($chatId) {
                $messagesOrError = DAO_MongoDB_Chat::getNewMessages($chatId, $since);

                if (is_array($messagesOrError)) {
                    $response = [
                        'success' => true,
                        'serverTimestamp'=>(new DateTime())->format(DateTime::ISO8601),
                        'messages' => $messagesOrError,
                        'message' => 'Pesan baru berhasil diambil.'
                    ];
                    $httpCode = 200;
                } else {
                    $response['message'] = 'Gagal Polling: ' . $messagesOrError;
                    $httpCode = 500;
                }
            } else {
                $response['message'] = 'Chat ID diperlukan untuk Polling.';
                $httpCode = 400;
            }
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
        break;
    case 'auth-dokter':
        $pageTitle = 'Masuk/Daftar Dokter - VetCare';
        $pageDescription = 'Masuk atau daftar akun dokter VetCare';
        $contentFile = 'pages/auth-dokter.php';
        $noHeaderFooter = true;
        $ajaxLoad = true;
        break;
    case 'dashboard-dokter':
        $pageTitle = 'Dashboard Dokter - VetCare';
        $pageDescription = 'Dashboard utama dokter VetCare';
        $contentFile = 'pages/dashboard-dokter.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: ?route='); // Redirect akan bekerja
        exit;
    // --- Route Dashboard Baru ---
    case 'dashboard_member':
        $pageTitle = 'Dashboard Member - VetCare';
        $pageDescription = 'Area akun member dan riwayat konsultasi.';
        $contentFile = 'pages/home.php';
        break;
    case 'dashboard_dokter':
        $pageTitle = 'Dashboard Dokter - VetCare';
        $pageDescription = 'Area pengelolaan jadwal dan konsultasi dokter.';
        $contentFile = 'pages/dashboard_dokter.php';
        break;
    case 'pilih-dokter':
        $pageTitle = 'Pilih Dokter - VetCare';
        $pageDescription = 'Daftar dokter berdasarkan kategori yang dipilih';
        $contentFile = 'pages/pilih-dokter.php';
        break;
    case 'admin':
        header('Location: ' . BASE_URL . 'admin/');
        exit();
    case 'tanya-jawab':
        $pageTitle = 'Tanya Jawab - VetCare';
        $pageDescription = 'Ajukan pertanyaan seputar kesehatan hewan peliharaan Anda';
        $contentFile = 'pages/Tanya-Jawab.php';
        break;
    case 'chat':
        $pageTitle = 'Chat dengan Dokter - VetCare';
        $pageDescription = 'Mulai konsultasi online dengan dokter hewan terpercaya';
        $contentFile = 'chat-api-service/initchat.php';
        break;
    case 'profil':
        $pageTitle = 'Profil - VetCare';
        $pageDescription = 'Lihat dan perbarui informasi profil Anda';
        $contentFile = 'pages/profil-dokter/profile-dokter.php';
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
        break;
    // ...
    default:
        $pageTitle = 'Halaman Tidak Ditemukan - VetCare';
        $pageDescription = 'Halaman yang Anda cari tidak ditemukan';
        $contentFile = '404.php';
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
