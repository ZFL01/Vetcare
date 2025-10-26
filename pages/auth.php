<?php
// PASTIKAN: file database.php berada di direktori 'includes'
// PASTIKAN: kamu sudah menggunakan versi database.php yang aman (dengan password_hash())

// --- AKTIFKAN SESI ---
// Selalu panggil session_start() sebelum output HTML dikirim
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'vendor/autoload.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$message = '';
$messageType = '';

function showLoginForm($message = '', $messageType = '')
{
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Masuk ke Akun Anda</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=login" class="space-y-6">
            <div>
                <label for="email" class="block mb-2 font-semibold">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div>
                <label for="password" class="block mb-2 font-semibold">Kata Sandi</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div class="text-right">
                <a href="?route=auth&action=forgot" class="text-purple-600 hover:underline">Lupa Kata Sandi?</a>
            </div>
            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl 
                hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                Masuk
            </button>
        </form>
        <p class="text-center mt-6">
            Belum punya akun? <a href="?route=auth&action=register" class="text-purple-600 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
    <?php
}

function showRegisterForm($message = '', $messageType = '')
{
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Daftar Akun Baru</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=register" class="space-y-6">
            <div>
                <label for="name" class="block mb-2 font-semibold">Nama Lengkap</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div>
                <label for="email" class="block mb-2 font-semibold">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div>
                <label for="password" class="block mb-2 font-semibold">Kata Sandi</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div>
                <label for="password_confirm" class="block mb-2 font-semibold">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirm" name="password_confirm" required
                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl 
                hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                Daftar
            </button>
        </form>
        <p class="text-center mt-6">
            Sudah punya akun? <a href="?route=auth&action=login" class="text-purple-600 font-semibold hover:underline">Masuk di sini</a>
        </p>
    </div>
    <?php
}

function showForgotPasswordForm($message = '', $messageType = '')
{
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Lupa Kata Sandi</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=forgot" class="space-y-6">
            <div>
                <label for="email" class="block mb-2 font-semibold">Masukkan Email Anda</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl 
                hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                Kirim Link Reset
            </button>
        </form>
        <p class="text-center mt-6">
            Kembali ke <a href="?route=auth&action=login" class="text-purple-600 font-semibold hover:underline">Masuk</a>
        </p>
    </div>
    <?php
}

// Get messages from session if redirected
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$messageType = isset($_SESSION['messageType']) ? $_SESSION['messageType'] : '';
unset($_SESSION['message'], $_SESSION['messageType']);
?>

<!-- Wrapper -->
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
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

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
