<?php
// PASTIKAN: file database.php berada di direktori 'includes'
// PASTIKAN: kamu sudah menggunakan versi database.php yang aman (dengan password_hash())
define('ROOT_PATH', dirname(__DIR__)); 
// --- AKTIFKAN SESI ---
// Selalu panggil session_start() sebelum output HTML dikirim
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/database.php'; // Sesuaikan path ini jika perlu

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$message = '';
$messageType = '';
function showLoginForm($message = '', $messageType = '', $role = 'member') {
    $roleTitle = ucfirst($role);
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Masuk sebagai <?php echo $roleTitle; ?></h2>
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

function showRegisterForm($message = '', $messageType = '', $role = 'member') {
    $roleTitle = ucfirst($role);
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Daftar sebagai <?php echo $roleTitle; ?></h2>
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

function showForgotPasswordForm($message = '', $messageType = '') {
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

// Handle POST requests before any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = Database::authenticateUser($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;

            // Cek role untuk redirect yang benar (berdasarkan kolom 'role' di m_pengguna)
            // Asumsi: Jika user adalah Dokter, dia akan redirect ke area dokter
            if (isset($user['role']) && $user['role'] === 'Dokter') {
                header('Location: ?route=dashboard_dokter');
            } else {
                header('Location: ?route=dashboard_member'); // Default ke halaman member/utama
            }
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
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlinikH - Otentikasi</title>
    <!-- Memuat Tailwind CSS (Pastikan ini ada di lingkungan kamu) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya tambahan atau font kustom */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
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
</body>
</html>