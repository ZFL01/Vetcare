<?php
session_start();
include_once 'database.php';
include_once 'DAO_dokter.php';

if (isset($_POST['register'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $conn = Database::getConnection();

    // 1. Cek apakah email sudah ada
    $sqlCheck = "SELECT id_pengguna FROM m_pengguna WHERE email = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->execute([$email]);

    if ($stmtCheck->fetch()) {
        die("Email ini sudah terdaftar. Silakan login atau gunakan email lain.");
    }

    // 2. Jika aman, buat pengguna baru
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sqlUser = "INSERT INTO m_pengguna (email, pass, role) VALUES (?, ?, 'Dokter')";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->execute([$email, $hashed_password]);

        // 3. Dapatkan ID baru
        $new_dokter_id = $conn->lastInsertId();

        // 4. Simpan ID di session untuk digunakan di form berikutnya
        $_SESSION['id_dokter_verifikasi'] = $new_dokter_id;
        $_SESSION['email_dokter_verifikasi'] = $email;

        // 5. Arahkan ke form data lengkap
        header('Location: regis-doktor.php');
        exit();

    } catch (Exception $e) {
        die("Registrasi Gagal: " . $e->getMessage());
    }
}
?>