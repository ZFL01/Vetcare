<?php
/**
 * File: src/config/config.php
 * Konfigurasi umum aplikasi
 */

// Include database class
require_once __DIR__ . '/../../includes/database.php';

// Session Configuration (1 Week Lifetime)
ini_set('session.gc_maxlifetime', 604800);
session_set_cookie_params(604800);

// Timezone
date_default_timezone_set('Asia/Jakarta');

//get dynamic path
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

$host = $_SERVER['HTTP_HOST'];

$script_filename = $_SERVER['SCRIPT_FILENAME'];

$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace(basename($script_name), '', $script_name);

$base_path = rtrim($base_path, '/\\');
if ($base_path === '/' || $base_path === '\\') {
    $base_path = '';
}
$dynamicBaseUrl = $protocol . $host . $base_path . '/';

// Define Global Base URL
define('BASE_URL', $dynamicBaseUrl);

define('ROOT_DIR', dirname(dirname(__DIR__)));

// Upload directories
define('UPLOAD_DIR', __DIR__ . '/../../public/img/');
define('PROFILE_DIR', UPLOAD_DIR . 'dokter-profil/');
define('ARTIKEL_DIR', UPLOAD_DIR . 'artikel/');
define('DOCUMENTS_DIR', __DIR__ . '/../../public/docs/');
define('STRV_DIR', DOCUMENTS_DIR . 'strv/');
define('SIP_DIR', DOCUMENTS_DIR . 'sip/');

define('URL_FOTO', BASE_URL . 'public/img/');
define('FOTO_DI_DOKTER', BASE_URL . '../../public/img/dokter-profil/');
define('URL_SIP_DOC', BASE_URL . 'public/docs/sip/');
define('URL_STRV_DOC', BASE_URL . 'public/docs/strv/');

// Allowed file types
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Create upload directories if not exist
if (!file_exists(PROFILE_DIR)) {
    mkdir(PROFILE_DIR, 0777, true);
}
if (!file_exists(ARTIKEL_DIR)) {
    mkdir(ARTIKEL_DIR, 0777, true);
}
if (!file_exists(DOCUMENTS_DIR)) {
    mkdir(DOCUMENTS_DIR, 0777, true);
}
if (!file_exists(STRV_DIR)) {
    mkdir(STRV_DIR, 0777, true);
}
if (!file_exists(SIP_DIR)) {
    mkdir(SIP_DIR, 0777, true);
}

// API KEY nya gugel maps
define('GOOGLE_MAPS_API_KEY', '');

/**
 * Check if user is logged in
 */
function isLoggedIn(bool $isDokter)
{
    if ($isDokter) {
        return isset($_SESSION['dokter']) && isset($_SESSION['user']);
    } else {
        return isset($_SESSION['user']);
    }
}

/**
 * Redirect if not logged in
 */
function requireLogin(bool $isDokter, string $onPage = '')
{
    if (!isLoggedIn($isDokter)) {
        error_log("ada di " . $onPage);
        if ($onPage) {
            $_SESSION['prev_page'] = $onPage;
        }
        setFlash('Autentikasi dibutuhkan!', 'Silahkan login terlebih dahulu.');
        header('Location: ' . ROOT_DIR . '/index.php?route=auth');
        exit();
    }
}
function dokterAllowed(bool $allow)
{
    if (!$allow && isLoggedIn(true)) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?route=dashboard-dokter');
        exit();
    }
}

/**
 * Set flash message
 */
function setFlash($type, $message)
{
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

/**
 * Get and clear flash message
 */
function getFlash()
{
    if (isset($_SESSION['flash_message'])) {
        $flash = [
            'type' => $_SESSION['flash_type'],
            'message' => $_SESSION['flash_message']
        ];
        unset($_SESSION['flash_type']);
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Sanitize input
 */
function clean($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

define('HARI_ID', [
    0 => 'Minggu',
    1 => 'Senin',
    2 => 'Selasa',
    3 => 'Rabu',
    4 => 'Kamis',
    5 => 'Jumat',
    6 => 'Sabtu'
]);

function formatRupiah($angka)
{
    $rupiah = 'Rp' . number_format($angka, 0, ',', '.');
    return $rupiah;
}

define('BULAN_ID', [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'Mei',
    'Jun',
    'Jul',
    'Ags',
    'Sep',
    'Okt',
    'Nov',
    'Des'
]);

function formatTanggal($date, $format = 'd M Y')
{
    $bulan = BULAN_ID;

    $timestamp = strtotime($date);
    $formatted = date($format, $timestamp);

    // Replace month with Indonesian
    foreach ($bulan as $key => $value) {
        $formatted = str_replace(date('M', $timestamp), $value, $formatted);
    }

    return $formatted;
}

function previousPage()
{
    if (isset($_SESSION['prev_page']) && $_SESSION['prev_page'] !== '') {
        error_log($_SESSION['prev_page']);
        header('Location: ' . BASE_URL . '?route=' . $_SESSION['prev_page']);
        // unset($_SESSION['prev_page']);
        exit;
    }
}

/**
 * Time ago function
 */
function timeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Baru saja';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' menit yang lalu';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' jam yang lalu';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' hari yang lalu';
    } else {
        return formatTanggal($datetime);
    }
}

/**
 * Upload image helper
 */
function uploadImage($file, $directory, $prefix, $customId = null)
{
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'message' => 'Tidak ada file yang diupload'];
    }

    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }

    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }

    // --- LOGIC ID FIX ---
    $id = $customId;
    if ($id === null) {
        if (isset($_SESSION['user']) && is_object($_SESSION['user'])) {
            $id = $_SESSION['user']->getIdUser();
        } else {
            $id = time(); // Fallback safe ID agar tidak crash
        }
    }
    // --------------------

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . $id . date('ymd') . '.' . $extension;
    $filepath = $directory . $filename;

    // Move file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    }

    return ['success' => false, 'message' => 'Gagal mengupload file'];
}

/**
 * Delete image helper
 */
function deleteImage($filename, $directory)
{
    $filepath = $directory . $filename;
    if (file_exists($filepath) && $filename != 'default-profile.jpg') {
        return unlink($filepath);
    }
    return false;
}

function uploadDocument($file, $target_dir, $prefix, $customId = null)
{
    $result = ['success' => false, 'filename' => '', 'error' => ''];

    // Check if file is uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'File upload error';
        return $result;
    }

    // Check file size (max 5MB)
    if ($file['size'] > 5000000) {
        $result['error'] = 'File terlalu besar. Maksimal 5MB';
        return $result;
    }

    // Check file type (allow PDF, DOC, DOCX, JPG, PNG)
    $allowed_types = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/jpg',
        'image/png'
    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_types)) {
        $result['error'] = 'Tipe file tidak didukung. Gunakan PDF, DOC, DOCX, JPG, atau PNG';
        return $result;
    }

    // --- LOGIC ID FIX ---
    $id = $customId;
    if ($id === null) {
        if (isset($_SESSION['user']) && is_object($_SESSION['user'])) {
            $id = $_SESSION['user']->getIdUser();
        } else {
            $id = time(); // Fallback safe ID agar tidak crash
        }
    }
    // --------------------

    // Generate unique filename
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = $prefix . $id . date('ymdHis') . '.' . $file_extension;
    $target_path = $target_dir . $new_filename;

    // Create directory if not exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $result['success'] = true;
        $result['filename'] = $new_filename;
    } else {
        $result['error'] = 'Gagal mengupload file';
    }

    return $result;
}

define('LOCATIONIQ_API', 'pk.f36f3d13ab6674ab62602323da859b26');
define('MONGODB_URI', "mongodb+srv://klinikH:QZ6Bqc8HAH5LPa7g@cluster0.rgxz9ub.mongodb.net/?appName=Cluster0");
define('MONGODB_DBNAME', 'klinikH');

define('SALT_HASH', 'iniSaltHashKlinikH');
define('HASH_LENGTH', 8);


define('ACTIVITY_LOG_FILE', __DIR__ . '/../Vetcare logs/activity.log');
define('ERROR_LOG_FILE', __DIR__ . '/../Vetcare logs/error.log');
define('ROUTING_LOG_FILE', __DIR__ . '/../Vetcare logs/routing.log');

enum LOG_TYPE
{
    case ACTIVITY;
    case ERROR;
    case ROUTING;

    function getLogTypeString()
    {
        return match ($this) {
            self::ACTIVITY => [ACTIVITY_LOG_FILE, isset($_SESSION['user']) ? $_SESSION['user']->getEmail() : 'guest'],
            self::ERROR => [ERROR_LOG_FILE, ''],
            self::ROUTING => [ROUTING_LOG_FILE, isset($_SESSION['user']) ? censorEmail($_SESSION['user']->getEmail()) . '(' . $_SESSION['user']->getRole() . ')' : 'guest'],
        };
    }
}

function custom_log($message, LOG_TYPE $type = LOG_TYPE::ERROR)
{
    $user = $type->getLogTypeString()[1];
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] {$user} {$message}\n"; // Format + newline
    $dest = $type->getLogTypeString()[0];

    error_log($log_entry, 3, $dest);
}

define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', '');
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_FROM_ADDRESS', 'noreply@eaude-vetcare.mif.myhost.id');
define('MAIL_FROM_NAME', 'VetCare');

?>