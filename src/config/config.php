<?php
/**
 * File: src/config/config.php
 * Konfigurasi umum aplikasi
 */

// Include database class
require_once __DIR__ . '/../../includes/database.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Base URL
define('BASE_URL', 'http://localhost/Vetcare-1/');

// Upload directories
define('UPLOAD_DIR', __DIR__ . '/../../public/img/');
define('PROFILE_DIR', UPLOAD_DIR . 'dokter/');
define('ARTIKEL_DIR', UPLOAD_DIR . 'artikel/');
define('DOCUMENTS_DIR', __DIR__ . '/../../public/docs/dokter');
define('STRV_DIR', DOCUMENTS_DIR . '/strv/');
define('SIP_DIR', DOCUMENTS_DIR . '/sip/');

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

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['dokter_id']);
}

/**
 * Get current dokter data
 */
function getCurrentDokter() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['dokter_id'],
            'nama' => $_SESSION['dokter_nama'],
            'email' => $_SESSION['dokter_email'],
            'foto' => $_SESSION['dokter_foto'] ?? 'default-profile.jpg',
            'spesialisasi' => $_SESSION['dokter_spesialisasi'] ?? 'umum'
        ];
    }
    return null;
}

/**
 * Redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'pages/auth-dokter.php');
        exit();
    }
}

/**
 * Redirect if already logged in
 */
function requireGuest() {
    if (isLoggedIn()) {
        header('Location: ' . BASE_URL . '?route=dashboard-dokter');
        exit();
    }
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

/**
 * Get and clear flash message
 */
function getFlash() {
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
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format tanggal Indonesia
 */
function formatTanggal($date, $format = 'd M Y') {
    $bulan = [
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    
    $timestamp = strtotime($date);
    $formatted = date($format, $timestamp);
    
    // Replace month with Indonesian
    foreach ($bulan as $key => $value) {
        $formatted = str_replace(date('M', $timestamp), $value, $formatted);
    }
    
    return $formatted;
}

/**
 * Time ago function
 */
function timeAgo($datetime) {
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
function uploadImage($file, $directory) {
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
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'doc_' . uniqid() . '_' . time() . '.' . $extension;
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
function deleteImage($filename, $directory) {
    $filepath = $directory . $filename;
    if (file_exists($filepath) && $filename != 'default-profile.jpg') {
        return unlink($filepath);
    }
    return false;
}

function uploadDocument($file, $target_dir, $prefix = 'doc_') {
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

    // Generate unique filename
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid($prefix, true) . '.' . $file_extension;
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

?>