Y<?php
// Authentication page for doctors with database integration
require_once __DIR__ . '/../includes/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$db = new Database();
$message = '';
$messageType = '';

function showLoginFormDokter($message = '', $messageType = '') {
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Masuk sebagai Dokter</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth-dokter&action=login" class="space-y-6">
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
                <a href="?route=auth-dokter&action=forgot" class="text-purple-600 hover:underline">Lupa Kata Sandi?</a>
            </div>
            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl
                hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                Masuk
            </button>
        </form>
        <p class="text-center mt-6">
            Belum punya akun? <a href="?route=auth-dokter&action=register" class="text-purple-600 font-semibold hover:underline">Daftar sebagai Dokter</a>
        </p>
        <p class="text-center mt-4">
            <a href="?route" class="text-gray-600 hover:underline">← Kembali ke Home</a>
        </p>
    </div>
    <?php
}

function showRegisterFormDokter($message = '', $messageType = '') {
    ?>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Daftar sebagai Dokter</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth-dokter&action=register" enctype="multipart/form-data" class="space-y-8">
            <!-- Step 1: Email and Password -->
            <div id="step1" class="space-y-6">
                <h3 class="text-xl font-bold text-purple-700 mb-4 text-center">Langkah 1: Email dan Kata Sandi</h3>
                <div class="max-w-md mx-auto space-y-6">
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
                </div>
                <div class="text-center">
                    <button type="button" id="nextBtn"
                        class="bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 px-6 rounded-xl
                        hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                        Selanjutnya
                    </button>
                </div>
            </div>

            <!-- Step 2: Full Formulir -->
            <div id="step2" class="space-y-8 hidden">
                <h3 class="text-xl font-bold text-purple-700 mb-4">Langkah 2: Formulir Lengkap</h3>
                <!-- Informasi Pribadi -->
                <div>
                    <h4 class="text-lg font-bold text-purple-700 mb-4">Informasi Pribadi</h4>
                    <div class="grid lg:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block mb-2 font-semibold">Nama Lengkap</label>
                            <input type="text" id="name" name="name" required
                                class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70
                                focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
                        </div>
                        <div>
                            <label for="ttl" class="block mb-2 font-semibold">Tanggal Lahir</label>
                            <input type="date" id="ttl" name="ttl" required
                                class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70
                                focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
                        </div>
                    </div>
                </div>

                <!-- Dokumen -->
                <div>
                    <h4 class="text-lg font-bold text-purple-700 mb-4">Dokumen</h4>
                    <div class="grid lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label for="strv_file" class="block mb-2 font-semibold">Upload STRV</label>
                                <input type="file" id="strv_file" name="strv_file" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70
                                    focus:outline-none focus:ring-4 focus:ring-purple-500/70 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" />
                            </div>
                            <div>
                                <label for="exp_strv" class="block mb-2 font-semibold">Kadaluarsa STRV</label>
                                <input type="date" id="exp_strv" name="exp_strv" required
                                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70
                                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="sip_file" class="block mb-2 font-semibold">Upload SIP</label>
                                <input type="file" id="sip_file" name="sip_file" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70
                                    focus:outline-none focus:ring-4 focus:ring-purple-500/70 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" />
                            </div>
                            <div>
                                <label for="exp_sip" class="block mb-2 font-semibold">Kadaluarsa SIP</label>
                                <input type="date" id="exp_sip" name="exp_sip" required
                                    class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70
                                    focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kredensial -->
                <div>
                    <h4 class="text-lg font-bold text-purple-700 mb-4">Kredensial</h4>
                    <div class="grid lg:grid-cols-2 gap-6">
                        <div>
                            <label for="pengalaman" class="block mb-2 font-semibold">Pengalaman (tahun)</label>
                            <input type="number" id="pengalaman" name="pengalaman" required min="0" max="50"
                                class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70
                                focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
                        </div>
                        <div>
                            <label for="password_confirm" class="block mb-2 font-semibold">Konfirmasi Kata Sandi</label>
                            <input type="password" id="password_confirm" name="password_confirm" required
                                class="w-full px-4 py-3 border border-purple-400 rounded-xl shadow-lg shadow-purple-300/70
                                focus:outline-none focus:ring-4 focus:ring-purple-500/70" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-between">
                    <button type="button" id="backBtn"
                        class="bg-gray-500 text-white font-bold py-3 px-6 rounded-xl hover:bg-gray-600 transition-none shadow-md">
                        Kembali
                    </button>
                    <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 px-6 rounded-xl
                        hover:from-purple-700 hover:to-blue-600 transition-none shadow-md">
                        Daftar sebagai Dokter
                    </button>
                </div>
            </div>
        </form>
        <p class="text-center mt-6">
            Sudah punya akun? <a href="?route=auth-dokter&action=login" class="text-purple-600 font-semibold hover:underline">Masuk di sini</a>
        </p>
        <p class="text-center mt-4">
            <a href="?route" class="text-gray-600 hover:underline">← Kembali ke Home</a>
        </p>
    </div>

    <?php
    echo '<script>
        document.getElementById(\'nextBtn\').addEventListener(\'click\', function() {
            document.getElementById(\'step1\').classList.add(\'hidden\');
            document.getElementById(\'step2\').classList.remove(\'hidden\');
        });
        document.getElementById(\'backBtn\').addEventListener(\'click\', function() {
            document.getElementById(\'step2\').classList.add(\'hidden\');
            document.getElementById(\'step1\').classList.remove(\'hidden\');
        });
    </script>';
    ?>
    <?php
}

function showForgotPasswordFormDokter($message = '', $messageType = '') {
    ?>
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl shadow-purple-400/70 border border-purple-300">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">Lupa Kata Sandi Dokter</h2>
        <?php if ($message): ?>
            <div class="mb-4 text-center text-<?php echo $messageType === 'error' ? 'red-600' : 'green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="?route=auth-dokter&action=forgot" class="space-y-6">
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
            Kembali ke <a href="?route=auth-dokter&action=login" class="text-purple-600 font-semibold hover:underline">Masuk</a>
        </p>
        <p class="text-center mt-4">
            <a href="?route" class="text-gray-600 hover:underline">← Kembali ke Home</a>
        </p>
    </div>
    <?php
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Try simple authentication first (in-memory)
        $user = $db->authenticateDokterSimple($email, $password);
        if (!$user) {
            // Fallback to database authentication
            $user = $db->authenticateUser($email, $password);
            if ($user && $user['role'] === 'Dokter') {
                // Load additional doctor data from database
                $stmt = $db->getConnection()->prepare("SELECT * FROM m_dokter WHERE id_dokter = ?");
                $stmt->execute([$user['id_pengguna']]);
                $doctorData = $stmt->fetch();
                if ($doctorData) {
                    $user = array_merge($user, $doctorData);
                }
            }
        }

        if ($user && $user['role'] === 'Dokter') {
            $_SESSION['user'] = $user;
            header('Location: ?route=dashboard');
            exit;
        } else {
            $message = 'Email atau kata sandi salah, atau Anda bukan dokter terdaftar.';
            $messageType = 'error';
        }
    } elseif ($action === 'register') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $ttl = $_POST['ttl'] ?? '';
        $expStrv = $_POST['exp_strv'] ?? '';
        $expSip = $_POST['exp_sip'] ?? '';
        $pengalaman = (int)($_POST['pengalaman'] ?? 0);

        // Handle file uploads
        $strvFile = $_FILES['strv_file'] ?? null;
        $sipFile = $_FILES['sip_file'] ?? null;

        // Validate file uploads
        $uploadErrors = [];
        if (!$strvFile || $strvFile['error'] !== UPLOAD_ERR_OK) {
            $uploadErrors[] = 'File STRV wajib diupload.';
        }
        if (!$sipFile || $sipFile['error'] !== UPLOAD_ERR_OK) {
            $uploadErrors[] = 'File SIP wajib diupload.';
        }

        if (!empty($uploadErrors)) {
            $message = implode(' ', $uploadErrors);
            $messageType = 'error';
        } elseif ($password !== $passwordConfirm) {
            $message = 'Konfirmasi kata sandi tidak cocok.';
            $messageType = 'error';
        } else {
            // Process file uploads and get file paths
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $strvPath = '';
            $sipPath = '';

            // Upload STRV file
            if ($strvFile) {
                $strvExt = pathinfo($strvFile['name'], PATHINFO_EXTENSION);
                $strvPath = $uploadDir . 'strv_' . time() . '_' . uniqid() . '.' . $strvExt;
                if (!move_uploaded_file($strvFile['tmp_name'], $strvPath)) {
                    $message = 'Gagal mengupload file STRV.';
                    $messageType = 'error';
                }
            }

            // Upload SIP file
            if ($sipFile && empty($message)) {
                $sipExt = pathinfo($sipFile['name'], PATHINFO_EXTENSION);
                $sipPath = $uploadDir . 'sip_' . time() . '_' . uniqid() . '.' . $sipExt;
                if (!move_uploaded_file($sipFile['tmp_name'], $sipPath)) {
                    $message = 'Gagal mengupload file SIP.';
                    $messageType = 'error';
                }
            }

            if (empty($message)) {
                // Try simple registration first (in-memory)
                $result = $db->registerDokterSimple($name, $email, $password, $ttl, $strvPath, $expStrv, $sipPath, $expSip, $pengalaman);
                if (!$result['success']) {
                    // Fallback to database registration
                    $result = $db->registerDokter($name, $email, $password, $ttl, $strvPath, $expStrv, $sipPath, $expSip, $pengalaman);
                }
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                if ($result['success']) {
                    $action = 'login'; // Redirect to login after successful registration
                }
            }
        }
    } elseif ($action === 'forgot') {
        $email = $_POST['email'] ?? '';
        $result = $db->initiatePasswordReset($email);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
    }
}

// Get messages from session if redirected
$message = isset($_SESSION['message']) ? $_SESSION['message'] : $message;
$messageType = isset($_SESSION['messageType']) ? $_SESSION['messageType'] : $messageType;
unset($_SESSION['message'], $_SESSION['messageType']);
?>

<!-- Wrapper -->
<div class="min-h-screen bg-gray-50 px-4 py-8 flex justify-center items-center">
    <?php
    if ($action === 'register') {
        showRegisterFormDokter($message, $messageType);
    } elseif ($action === 'forgot') {
        showForgotPasswordFormDokter($message, $messageType);
    } else {
        showLoginFormDokter($message, $messageType);
    }
    ?>
</div>

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
