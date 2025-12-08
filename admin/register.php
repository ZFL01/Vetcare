<?php
// Jika user sudah login, arahkan ke dashboard
if (isset($_SESSION['user']) && $_SESSION['user']->getRole() === 'Admin') {
    header('Location: admin_direct.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin-register'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi Password
    if ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } elseif (strlen($password) < 8) { // Sesuaikan panjang minimal password
        $error = 'Password terlalu pendek (minimal 8 karakter)!';
    }elseif(!$email){
        $error = 'Email tidak valid';
    }else {
        $obj = new DTO_pengguna(email:$email, pass:$password, role:'Admin');
        $hasil = userService::register($obj);
        if($hasil[0]){
            $message = 'Registrasi berhasil! Akun Admin telah dibuat.';
            header('Location: ?access=auth');
        } else {
            $error = $hasil[1];
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
        
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                <?= htmlspecialchars($message) ?> <br>
                <a href="?access=auth" class="font-bold underline">Klik disini untuk Login</a>
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
                <a href="?access=auth" class="text-purple-600 hover:text-purple-800 font-medium">Login disini</a>
            </p>
        </div>
    </div>
</body>
</html>