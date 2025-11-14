<?php
session_start();
include_once 'database.php';
include_once 'DAO_dokter.php';

// Definisikan lokasi upload
define('PROFILE_DIR', dirname(__DIR__) . '/public/img/dokter/');

// Create directory if not exist
if (!is_dir(PROFILE_DIR)) mkdir(PROFILE_DIR, 0755, true);

// Cek apakah user sudah login dan submit form
if (!isset($_SESSION['id_dokter_verifikasi']) || !isset($_POST['kirim_verifikasi'])) {
    header('Location: registrasi-awal.php');
    exit();
}

// 1. Ambil ID dari session
$id_dokter = $_SESSION['id_dokter_verifikasi'];

// 2. Ambil semua data dari form
$nama = $_POST['nama_lengkap'];
$ttl = $_POST['ttl'];
$pengalaman = (int) $_POST['pengalaman'];
$strv = $_POST['strv'];
$exp_strv = $_POST['exp_strv'];
$sip = $_POST['sip'];
$exp_sip = $_POST['exp_sip'];
$kategori_ids = $_POST['kategori'] ?? [];

// 3. Handle Upload Foto
$nama_file_foto = 'default-profile.jpg';
$errors = [];

if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
    $file_tmp = $_FILES['foto_profil']['tmp_name'];
    $file_name = $_FILES['foto_profil']['name'];
    $file_size = $_FILES['foto_profil']['size'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Validate
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        $errors[] = "Format foto harus JPG, PNG, atau GIF";
    }
    if ($file_size > 5 * 1024 * 1024) { // 5MB
        $errors[] = "Ukuran foto maksimal 5MB";
    }
    
    if (empty($errors)) {
        $nama_file_baru = 'doc_' . uniqid() . '_' . time() . '.' . $ext;
        $tujuan_upload = PROFILE_DIR . $nama_file_baru;
        
        if (move_uploaded_file($file_tmp, $tujuan_upload)) {
            $nama_file_foto = $nama_file_baru;
        } else {
            $errors[] = "Gagal mengunggah foto.";
        }
    }
} else {
    $errors[] = "Foto profil wajib diisi.";
}

// Check errors
if (!empty($errors)) {
    echo "<h2>Kesalahan Registrasi:</h2>";
    echo "<ul>";
    foreach ($errors as $err) {
        echo "<li>" . htmlspecialchars($err) . "</li>";
    }
    echo "</ul>";
    echo '<a href="javascript:history.back()">Kembali</a>';
    exit();
}

// 6. Siapkan DTO Kategori
$list_dto_kategori = [];
foreach ($kategori_ids as $k_id) {
    $list_dto_kategori[] = new DTO_kateg($k_id, null);
}

// 7. Masukkan ke Database dengan status 'nonaktif' (menunggu approval admin)
try {
    // Panggil fungsi insert
    $status_insert = DAO_dokter::insertDokter($dokter_dto, $list_dto_kategori);

    if ($status_insert) {
        // Update status ke 'nonaktif' (menunggu approval)
        $conn = Database::getConnection();
        $updateQuery = "UPDATE m_dokter SET status = 'nonaktif' WHERE id_dokter = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$id_dokter]);

        // Hapus session
        unset($_SESSION['id_dokter_verifikasi']);
        unset($_SESSION['email_dokter_verifikasi']);

        ?>
        <div style="max-width: 600px; margin: 50px auto; padding: 30px; background: #f8f9fa; border-radius: 10px; text-align: center;">
            <h2 style="color: #28a745; margin-bottom: 15px;">✓ Registrasi Berhasil!</h2>
            <p style="margin: 15px 0; color: #333;">Data Anda telah berhasil disimpan.</p>
            <p style="margin: 15px 0; color: #666; font-size: 14px;">
                Akun Anda sedang menunggu persetujuan dari Admin untuk diaktifkan.<br>
                Kami akan mengirimkan notifikasi via email saat status berubah.
            </p>
            <div style="margin-top: 30px;">
                <p style="color: #999; font-size: 13px;">Terima kasih telah mendaftar sebagai mitra Vetcare!</p>
                <a href="?route=auth" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                    ← Kembali ke Halaman Login
                </a>
            </div>
        </div>
        <?php

    } else {
        throw new Exception("Gagal memasukkan data dokter.");
    }

} catch (Exception $e) {
    // Hapus foto jika DB gagal
    if ($nama_file_foto != 'default-profile.jpg' && file_exists(PROFILE_DIR . $nama_file_foto)) {
        unlink(PROFILE_DIR . $nama_file_foto);
    }
    die("Registrasi Gagal: " . $e->getMessage());
}
?>
