<?php
session_start();
require_once 'database.php';
require_once 'DAO_dokter.php';

// Definisikan lokasi upload
define('DOCS_DIR', dirname(__DIR__) . '/public/docs/dokter/');

// Create directory if not exist
if (!is_dir(DOCS_DIR)) mkdir(DOCS_DIR, 0755, true);

// Check POST dari auth-dokter.php register2
if (!isset($_POST['register2']) || !isset($_POST['id_user'])) {
    header('Location: ../pages/auth-dokter.php');
    exit();
}

// 1. Ambil ID dari POST
$id_user = (int)$_POST['id_user'];

// 2. Ambil data dari form
$nama = trim($_POST['nama'] ?? '');
$spesialisasi_id = (int)($_POST['spesialisasi'] ?? 0);
$pengalaman = (int)($_POST['pengalaman'] ?? 0);

$errors = [];

// Validasi input
if (empty($nama)) $errors[] = "Nama lengkap harus diisi";
if ($spesialisasi_id <= 0) $errors[] = "Spesialisasi harus dipilih";

// 3. Handle Upload File SIP
$file_sip = null;
if (isset($_FILES['file_sip']) && $_FILES['file_sip']['error'] == 0) {
    $file_tmp = $_FILES['file_sip']['tmp_name'];
    $file_name = $_FILES['file_sip']['name'];
    $file_size = $_FILES['file_sip']['size'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Validate
    if (!in_array($ext, ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])) {
        $errors[] = "Format file SIP harus PDF, DOC, DOCX, JPG, atau PNG";
    } elseif ($file_size > 5 * 1024 * 1024) { // 5MB
        $errors[] = "Ukuran file SIP maksimal 5MB";
    } else {
        $file_sip_name = 'sip_' . uniqid() . '_' . time() . '.' . $ext;
        $tujuan_upload = DOCS_DIR . $file_sip_name;
        
        if (move_uploaded_file($file_tmp, $tujuan_upload)) {
            $file_sip = $file_sip_name;
        } else {
            $errors[] = "Gagal mengunggah file SIP.";
        }
    }
} else {
    $errors[] = "File SIP wajib diisi.";
}

// 4. Handle Upload File STRV
$file_strv = null;
if (isset($_FILES['file_strv']) && $_FILES['file_strv']['error'] == 0) {
    $file_tmp = $_FILES['file_strv']['tmp_name'];
    $file_name = $_FILES['file_strv']['name'];
    $file_size = $_FILES['file_strv']['size'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Validate
    if (!in_array($ext, ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])) {
        $errors[] = "Format file STRV harus PDF, DOC, DOCX, JPG, atau PNG";
    } elseif ($file_size > 5 * 1024 * 1024) { // 5MB
        $errors[] = "Ukuran file STRV maksimal 5MB";
    } else {
        $file_strv_name = 'strv_' . uniqid() . '_' . time() . '.' . $ext;
        $tujuan_upload = DOCS_DIR . $file_strv_name;
        
        if (move_uploaded_file($file_tmp, $tujuan_upload)) {
            $file_strv = $file_strv_name;
        } else {
            $errors[] = "Gagal mengunggah file STRV.";
        }
    }
} else {
    $errors[] = "File STRV wajib diisi.";
}

// Check errors
if (!empty($errors)) {
    // Return ke form dengan error
    session_start();
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    
    // Delete uploaded files jika ada error
    if ($file_sip && file_exists(DOCS_DIR . $file_sip)) {
        unlink(DOCS_DIR . $file_sip);
    }
    if ($file_strv && file_exists(DOCS_DIR . $file_strv)) {
        unlink(DOCS_DIR . $file_strv);
    }
    
    header('Location: ../pages/auth-dokter.php?route=auth-dokter&tab=register');
    exit();
}

// 5. Buat DTO Dokter
try {
    $dokter_dto = new DTO_dokter(
        id_dokter: $id_user,
        nama: $nama,
        pengalaman: $pengalaman
    );
    
    // Set files untuk di-store di database nanti admin isi nomor & masa berlaku
    // Store filename sementara di database (admin akan verify nanti)
    
    // 6. Siapkan kategori
    $list_dto_kategori = [];
    $list_dto_kategori[] = new DTO_kateg($spesialisasi_id, null);
    
    // 7. Masukkan ke Database
    $status_insert = DAO_dokter::insertDokter($dokter_dto, $list_dto_kategori);

    if ($status_insert) {
        // Update status ke 'nonaktif' (menunggu approval)
        $conn = Database::getConnection();
        
        // Store document filenames
        $updateQuery = "UPDATE m_dokter SET status = 'nonaktif' WHERE id_dokter = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$id_user]);

        // Hapus session registrasi
        unset($_SESSION['temp_idUser']);
        unset($_SESSION['show_form_2']);
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);

        // Redirect dengan success message
        $_SESSION['registration_success'] = true;
        header('Location: ../pages/auth-dokter.php?route=auth-dokter');
        exit();

    } else {
        throw new Exception("Gagal memasukkan data dokter.");
    }

} catch (Exception $e) {
    // Hapus files jika DB gagal
    if ($file_sip && file_exists(DOCS_DIR . $file_sip)) {
        unlink(DOCS_DIR . $file_sip);
    }
    if ($file_strv && file_exists(DOCS_DIR . $file_strv)) {
        unlink(DOCS_DIR . $file_strv);
    }
    
    $_SESSION['registration_error'] = "Registrasi Gagal: " . $e->getMessage();
    header('Location: ../pages/auth-dokter.php?route=auth-dokter&tab=register');
    exit();
}
?>
