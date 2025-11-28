<?php
// Authentication page with database integration
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../includes/DAO_user.php';
require_once __DIR__ . '/../includes/userService.php';

$content = '';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$message = '';
$messageType = '';

function showLoginForm()
{
    ob_start();

    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Masuk ke Akun Anda</h2>
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                <?php echo $flash['message']; ?>
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
            <button onclick="getUserLocation()" type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl 
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

function showRegisterForm()
{
    ob_start();
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Daftar Akun Baru</h2>
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=register" class="space-y-6">
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

function showForgotPasswordForm()
{
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Lupa Kata Sandi</h2>
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                <?php echo $flash['message']; ?>
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
    $html = ob_get_clean(); // MENGAMBIL OUTPUT YANG DISIMPAN dan membersihkan buffer
    return $html;
}
function showResetForm()
{
    if (!isset($_SESSION['reset_email'])) {
        showLoginForm();
        exit;
    }
    $expiryTime = 0;
    if (isset($_SESSION['emailKey'])) {
        $expiryTime = $_SESSION['emailKey'] + 300;
    }
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Reset Password</h2>
        <h5 class="block mb-2 font-semibold">Silahkan Periksa Email Anda</h5>
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=reset" class="space-y-6">
            <div>
                <label for="OTP" class="block mb-2 font-semibold">Masukkan Kode OTP Anda</label>
                <input type="text" id="OTP" name="OTP" required class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70 
                        focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl 
                hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                Kirim Kode OTP
            </button>
        </form>
        <div class="auth-links">
            <p>Bukan Email Anda? <a href="?route=auth&action=forgot"
                    class="text-purple-600 font-semibold hover:underline">Ganti Email</a></p>
            <p>Belum menerima OTP? <a href="?route=auth&action=reset"
                    class="text-purple-600 font-semibold hover:underline">kirim Ulang</a>
                <span id="timerDisplay" class="text-red-500 font-medium ml-2" style="display: none;">
                    (<span id="countdown"></span>)
                </span>
            </p>
            <input type="hidden" id="cooldownExpiry" value="<?= $expiryTime ?>">
        </div>
        <p class="text-center mt-6">
            Kembali ke <a href="?route=auth&action=login" class="text-purple-600 font-semibold hover:underline">Masuk</a>
        </p>
    </div>
    <?php
    $html = ob_get_clean(); // MENGAMBIL OUTPUT YANG DISIMPAN dan membersihkan buffer
    return $html;
}
function gantiSandi()
{
    if (!isset($_SESSION['reset_email'])) {
        showLoginForm();
        exit;
    }
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Reset Password</h2>
        <h5 class="block mb-2 font-semibold" style="text-align: center;">Untuk Email :
            <?php echo htmlspecialchars(censorEmail($_SESSION['reset_email'])); ?>
        </h5>
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth&action=ganti" class="space-y-6">
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
                Ganti Kata Sandi
            </button>
        </form>
    </div>
    <?php
    $html = ob_get_clean(); // MENGAMBIL OUTPUT YANG DISIMPAN dan membersihkan buffer
    return $html;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $email = $_POST['email'] ?? '';
        $pass = $_POST['password'] ?? '';
        $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($validEmail === false || empty($pass)) {
            setFlash('error', 'Email tidak valid atau kata sandi kosong');
            $content = showLoginForm();
        } else {

            $objUser = new DTO_pengguna(email: $validEmail, pass: $pass);
            $user = userService::login($objUser);
            if (!$user[0]) {
                $message = $user[1];
                setFlash('error', $message);
                $content = showLoginForm();
            } else {
                $_SESSION['user'] = $objUser;
                if ($objUser->getRole() === 'Member') {
                    previousPage();
                    header('Location: index.php');
                    exit;
                } elseif ($objUser->getRole() === 'Dokter') {
                    $objDokter = DAO_dokter::getProfilDokter($objUser, true);
                    if ($objDokter) {
                        $_SESSION['dokter'] = $objDokter;
                        previousPage();
                        setFlash('success', 'Login berhasil! Selamat datang, Dr. ' . $objDokter->getNama());
                        header('Location: ' . BASE_URL . 'index.php?route=dashboard-dokter');
                        exit();
                    } else if ($objDokter === null) {
                        setFlash('error', 'Terdeteksi belum selesai daftar. Silahkan selesaikan registrasi Anda!');
                        require_once 'auth-dokter.php';
                        CeknGo($objUser->getIdUser());
                    } else {
                        setFlash('error', 'Gagal memuat profil dokter, silahkan coba lagi nanti');
                    }
                } elseif ($objUser->getRole() === 'Admin') {
                    header('Location: ?route=admin');
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
            setFlash('error', 'Format email tidak valid atau ada field wajib yang kosong.');
            $content = showRegisterForm();
        } elseif ($pass !== $konfirPass) {
            setFlash('error', 'Password dan konfirmasi password tidak sama!');
            $content = showRegisterForm();
        } else {
            $objUser = new DTO_pengguna(email: $validEmail, pass: $pass, role: "Member");
            $user = userService::register($objUser);
            if (!$user[0]) { //jika false, pesan error semua
                $message = $user[1];
                setFlash('error', $message);
                $content = showRegisterForm();
            } else { //jika gak ada error
                setFlash('success', 'Berhasil Daftar');
                $content = showLoginForm();
            }
        }
    } elseif ($action === 'forgot') {
        $email = $_POST['email'] ?? '';
        $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($validEmail === false) {
            setFlash('error', 'Format email tidak valid');
            $content = showForgotPasswordForm();
        } else {
            $objUser = new DTO_pengguna(email: $validEmail);
            $user = userService::sendEmail($objUser, index_email::Forgot);
            if (!$user[0]) {
                $message = $user[1];
                setFlash('error', $message);
                $content = showForgotPasswordForm();
            } else {
                $_SESSION['emailKey'] = time();
                $_SESSION['reset_email'] = $validEmail;
                setFlash('success', 'Link reset password telah dikirim ke email ' . censorEmail($validEmail));
                $content = showResetForm();
            }
        }
    } elseif ($action === 'reset') {
        $kode = $_POST['OTP'] ?? '';
        if ($kode === '') {
            setFlash('error', 'Kode OTP tidak valid');
            $content = showResetForm();
        } else {
            $cd = 300;

            if (isset($_SESSION['emailKey']) && (time() - $_SESSION['emailKey'] < $cd)) {
                $remain = $cd - (time() - $_SESSION['emailKey']);
                $content = showResetForm();
            }
            $objUser = new DTO_pengguna(email: $_SESSION['reset_email'], resetToken: $kode);
            $user = userService::verifyToken($objUser);
            if (!$user[0]) {
                $message = $user[1];
                setFlash('error', $message);
                $content = showResetForm();
            } else {
                setFlash('success', 'Password berhasil direset');
                $content = gantiSandi();
            }
        }
    } elseif ($action === 'ganti') {
        $pass = $_POST['password'] ?? '';
        $konfirPass = $_POST['password_confirm'] ?? '';
        if ($pass !== $konfirPass) {
            setFlash('error', 'Password dan konfirmasi password tidak sama!');
            $content = showResetForm();
        } else {
            $objUser = new DTO_pengguna(email: $_SESSION['reset_email'], pass: $pass);
            $user = userService::changePass($objUser);
            if (!$user[0]) {
                $message = $user[1];
                setFlash('error', $message);
                $content = showResetForm();
            } else {
                unset($_SESSION['reset_email']);
                unset($_SESSION['emailKey']);
                setFlash('success', 'Password berhasil diganti');
                $content = showLoginForm();
            }
        }
    }
}
?>

<!-- Wrapper -->
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <?php
    if ($content) {
        echo $content;
    } else {
        if ($action === 'register') {
            echo showRegisterForm();
        } elseif ($action === 'forgot') {
            echo showForgotPasswordForm();
        } elseif ($action === 'reset') {
            echo showResetForm();
        } else {
            echo showLoginForm();
        }
    }
    ?>
</div>
<style>
    .alert {
        text-align: center;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-error {
        background: #fee;
        color: #c33;
        border: 1px solid #fcc;
    }

    .alert-success {
        background: #efe;
        color: #363;
        border: 1px solid #cfc;
    }
</style>
<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="<?php echo BASE_URL;?>/public/service.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const expiryTimeInput = document.getElementById('cooldownExpiry');
        const resendLink = document.getElementById('resendLink');
        const timerDisplay = document.getElementById('timerDisplay');
        const countdownElement = document.getElementById('countdown');

        const expiryTimestamp = parseInt(expiryTimeInput.value);
        const originalHref = resendLink.href;

        if (expiryTimestamp > Math.floor(Date.now() / 1000)) {
            startCountdown(expiryTimestamp);
        }

        function startCountdown(expiry) {
            resendLink.removeAttribute('href');
            resendLink.style.cursor = 'default';
            resendLink.style.color = 'gray';
            resendLink.style.textDecoration = 'none';

            timerDisplay.style.display = 'inline';

            const interval = setInterval(() => {
                const now = Math.floor(Date.now() / 1000);
                const remaining = expiry - now;

                if (remaining <= 0) {
                    clearInterval(interval);

                    resendLink.setAttribute('href', originalHref);
                    resendLink.style.cursor = 'pointer';
                    resendLink.style.color = '';
                    resendLink.style.textDecoration = 'underline';

                    timerDisplay.style.display = 'none';

                } else {
                    const minutes = Math.floor(remaining / 60);
                    const seconds = remaining % 60;

                    const formattedSeconds = seconds.toString().padStart(2, '0');

                    countdownElement.textContent = `${minutes}:${formattedSeconds}`;
                }
            }, 1000);
        }
        resendLink.addEventListener('click', function (e) {
            if (resendLink.getAttribute('href') === null) {
                e.preventDefault();
            }
        });
    });
</script>