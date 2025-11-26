<?php
session_start();
require_once 'includes/auth.php';

// Cek jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: admin_direct.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin-login'])) {
    // Ambil inputan
    $email = $_POST['email'] ?? ''; 
    $password = $_POST['password'] ?? '';
    
    try {
        if (admin_login($email, $password)) {
            header('Location: admin_direct.php');
            exit;
        } else {
            $error = 'Email atau password salah';
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VetCare Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-50 via-violet-50 to-fuchsia-50 min-h-screen flex items-center justify-center">
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4">
        <div class="text-center mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-violet-500 p-4 rounded-full mx-auto mb-4 w-fit">
                <span class="text-3xl">🐾</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">VetCare Admin</h1>
            <p class="text-gray-600 mt-2">Masuk ke dashboard admin</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
<form method="POST">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
            </div>
            <button type="submit" name="admin-login"
                    class="w-full bg-gradient-to-r from-purple-500 to-violet-600 text-white py-3 rounded-lg font-medium hover:from-purple-600 hover:to-violet-700 transition mt-6">
                Masuk
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">Belum punya akun admin? 
                <a href="register.php" class="text-purple-600 hover:text-purple-800 font-medium">Daftar disini</a>
            </p>
        </div>
    </div>
</body>
</html>