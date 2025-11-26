<?php
session_start();
require_once 'includes/db.php';

// Jika user sudah login, arahkan ke dashboard
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: admin_direct.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin-register'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi Password
    if ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } elseif (strlen($password) < 5) { // Sesuaikan panjang minimal password
        $error = 'Password terlalu pendek (minimal 5 karakter)!';
    } else {
        try {
            // 1. Cek apakah email sudah terpakai
            $checkSql = "SELECT id_pengguna FROM m_pengguna WHERE email = :email";
            $stmt = $pdo->prepare($checkSql);
            $stmt->execute(['email' => $email]);

            if ($stmt->rowCount() > 0) {
                $error = 'Email sudah terdaftar! Silakan login.';
            } else {
                // 2. Hash Password (biar aman kayak baris 33 di gambar)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // 3. Insert ke Database
                // Hapus kolom 'nama_lengkap' karena tidak ada di tabelmu
                // Set role jadi 'Admin' (A besar sesuai gambar)
                $sql = "INSERT INTO m_pengguna (email, pass, role) VALUES (:email, :pass, 'Admin')";
                $stmt = $pdo->prepare($sql);
                
                if ($stmt->execute([
                    'email' => $email,
                    'pass' => $hashed_password
                ])) {
                    $message = 'Registrasi berhasil! Akun Admin telah dibuat.';
                } else {
                    $error = 'Gagal mendaftar, terjadi kesalahan database.';
                }
            }
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin - VetCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-50 via-violet-50 to-fuchsia-50 min-h-screen flex items-center justify-center">
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4 my-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Admin Baru</h1>
            <p class="text-gray-600 mt-2">Buat akun untuk mengelola VetCare</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                <?= htmlspecialchars($message) ?> <br>
                <a href="login.php" class="font-bold underline">Klik disini untuk Login</a>
            </div>
        <?php endif; ?>
        
<form method="POST">
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <button type="submit" name="admin-register" class="w-full bg-gradient-to-r from-purple-500 to-violet-600 text-white py-3 rounded-lg font-medium hover:from-purple-600 hover:to-violet-700 transition mt-6">
                Daftar Sekarang
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">Sudah punya akun? 
                <a href="login.php" class="text-purple-600 hover:text-purple-800 font-medium">Login disini</a>
            </p>
        </div>
    </div>
</body>
</html>