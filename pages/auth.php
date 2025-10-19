<?php
// Start session at the very beginning to avoid header issues
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authentication page with database integration
require_once __DIR__ . '/../includes/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$db = new Database();
$message = '';
$messageType = '';

function showLoginForm($message = '', $messageType = '') {
    ?>
    <div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-lg mt-20">
        <h2 class="text-2xl font-bold mb-6 text-center">Masuk ke Akun Anda</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=login">
            <div class="mb-4">
                <label for="email" class="block mb-1 font-semibold">Email</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-1 font-semibold">Kata Sandi</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
            <div class="mb-4 text-right">
                <a href="?route=auth&action=forgot" class="text-purple-600 hover:underline">Lupa Kata Sandi?</a>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300">Masuk</button>
        </form>
        <p class="mt-6 text-center">
            Belum punya akun? <a href="?route=auth&action=register" class="text-purple-600 hover:underline">Daftar sekarang</a>
        </p>
    </div>
    <?php
}

function showRegisterForm($message = '', $messageType = '') {
    ?>
    <div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-lg mt-20">
        <h2 class="text-2xl font-bold mb-6 text-center">Daftar Akun Baru</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=register">
            <div class="mb-4">
                <label for="name" class="block mb-1 font-semibold">Nama Lengkap</label>
                <input type="text" id="name" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-1 font-semibold">Email</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-1 font-semibold">Kata Sandi</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
            <div class="mb-4">
                <label for="password_confirm" class="block mb-1 font-semibold">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirm" name="password_confirm" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300">Daftar</button>
        </form>
        <p class="mt-6 text-center">
            Sudah punya akun? <a href="?route=auth&action=login" class="text-purple-600 hover:underline">Masuk di sini</a>
        </p>
    </div>
    <?php
}

function showForgotPasswordForm($message = '', $messageType = '') {
    ?>
    <div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-lg mt-20">
        <h2 class="text-2xl font-bold mb-6 text-center">Lupa Kata Sandi</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=forgot">
            <div class="mb-4">
                <label for="email" class="block mb-1 font-semibold">Masukkan Email Anda</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300">Kirim Link Reset</button>
        </form>
        <p class="mt-6 text-center">
            Kembali ke <a href="?route=auth&action=login" class="text-purple-600 hover:underline">Masuk</a>
        </p>
    </div>
    <?php
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = $db->authenticateUser($email, $password);
        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: ?route=');
            exit;
        } else {
            $message = 'Email atau kata sandi salah.';
            $messageType = 'error';
        }
    } elseif ($action === 'register') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        if ($password !== $password_confirm) {
            $message = 'Konfirmasi kata sandi tidak cocok.';
            $messageType = 'error';
        } else {
            $result = $db->registerUser($name, $email, $password);
            if ($result['success']) {
                $message = $result['message'];
                $messageType = 'success';
                $action = 'login';
            } else {
                $message = $result['message'];
                $messageType = 'error';
            }
        }
    } elseif ($action === 'forgot') {
        $email = $_POST['email'] ?? '';
        $result = $db->initiatePasswordReset($email);
        if ($result['success']) {
            $message = $result['message'];
            $messageType = 'success';
            $action = 'login';
        } else {
            $message = $result['message'];
            $messageType = 'error';
        }
    }
}
?>
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <?php
    if ($action === 'register') {
        showRegisterForm($message, $messageType);
    } elseif ($action === 'forgot') {
        showForgotPasswordForm($message, $messageType);
    } else {
        showLoginForm($message, $messageType);
    }
    ?>
</div>
