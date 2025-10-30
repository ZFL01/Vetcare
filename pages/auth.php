<?php
// Authentication page with database integration
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/DAO_user.php';
require_once __DIR__ . '/../includes/userService.php';

$content='';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$message = '';
$messageType = '';

function showLoginForm($message = '', $messageType = '')
{
    ob_start();
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
                <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div>
                <label for="password" class="block mb-2 font-semibold">Kata Sandi</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div class="text-right">
                <a href="?route=auth&action=forgot" class="text-purple-600 hover:underline">Lupa Kata Sandi?</a>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl 
                hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                Masuk
            </button>
        </form>
        <p class="text-center mt-6">
            Belum punya akun? <a href="?route=auth&action=register"
                class="text-purple-600 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
    <?php
    $html = ob_get_clean(); // MENGAMBIL OUTPUT YANG DISIMPAN dan membersihkan buffer
    return $html; // MENGEMBALIKAN HTML SEBAGAI STRING
}

function showRegisterForm($message = '', $messageType = '')
{
    ob_start();
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
                <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div>
                <label for="email" class="block mb-2 font-semibold">Email</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div>
                <label for="password" class="block mb-2 font-semibold">Kata Sandi</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <div>
                <label for="password_confirm" class="block mb-2 font-semibold">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirm" name="password_confirm" required class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl 
                hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                Daftar
            </button>
        </form>
        <p class="text-center mt-6">
            Sudah punya akun? <a href="?route=auth&action=login" class="text-purple-600 font-semibold hover:underline">Masuk
                di sini</a>
        </p>
    </div>
    <?php
    $html = ob_get_clean(); // MENGAMBIL OUTPUT YANG DISIMPAN dan membersihkan buffer
    return $html;
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
                <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl 
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $email = $_POST['email'] ?? '';
        $pass = $_POST['password'] ?? '';
        $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($validEmail === false || empty($pass)) {
            $content = showLoginForm('Email tidak valid atau kata sandi kosong');
        } else {

            $objUser = DTO_pengguna::forLogin($validEmail, $pass);
            $user = userService::login($objUser);
            if (!$user[0]) {
                if ($user[1] === 'err') {
                    //error pasti kerna database
                    $message = "Error saat pengambilan data";
                } else {
                    //ambil data ini buat ditampilin di frontend user,
                    // ini pesan error yang beda-beda tergantung errornya.
                    $message = $user[1];
                }
                $content = showLoginForm($message, 'error');
            } else {
                $_SESSION['user'] = $objUser;
                error_log("Berhasil");
                //objUser ini udah otomatis terisi data user, bisa langsung dipake
                if ($objUser->getRole() === 'Member') {
                    header('Location: ?route=dashboard');
                    exit;
                } elseif ($objUser->getRole() === 'Dokter') {
                    header('Location: ?route=dashboard-dokter');
                    exit;
                } elseif ($objUser->getRole() === 'Admin') {
                    header('location: >route=dashboard=admin');
                    exit;
                }
            }
        }
    } elseif ($action === 'register') {
        $email = $_POST['email'] ?? '';
        $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        $pass = $_POST['password'] ?? '';
        $konfirPass = $_POST['password_confirm'] ?? '';

        if ($validEmail === false || (empty($pass) && empty($konfirPass))) {
            $content = showRegisterForm("Format email tidak valid atau ada field wajib yang kosong.", 'error');
        } elseif ($pass !== $konfirPass) {
            $content = showRegisterForm('Password dan konfirmasi password tidak sama!', 'error');
        } else {
            $objUser = DTO_pengguna::forRegist($validEmail, $pass, "Member");
            $user = userService::register($objUser);
            if (!$user[0]) { //jika false, pesan error semua
                if ($user[1] === 'err') {
                    $message = "Error saat validasi data";
                } else {
                    $message = $user[1]; //ambil ini buat ditampilin di frontend
                }
                $content = showRegisterForm($message, 'error');
            } else { //jika gak ada error
                $content = showLoginForm('Berhasil Daftar');
            }
        }
    }
}

// Get messages from session if redirected
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$messageType = isset($_SESSION['messageType']) ? $_SESSION['messageType'] : '';
unset($_SESSION['message'], $_SESSION['messageType']);
?>

<!-- Wrapper -->
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <?php
    if ($content) {
        echo $content;
    } else {
        if ($action === 'register') {
            echo showRegisterForm($message, $messageType);
        } elseif ($action === 'forgot') {
            echo showForgotPasswordForm($message, $messageType);
        } else {
            echo showLoginForm($message, $messageType);
        }
    }
    ?>
</div>

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>