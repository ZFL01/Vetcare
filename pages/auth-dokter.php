<?php
require_once __DIR__ . '/../includes/DAO_dokter.php';
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../includes/DAO_user.php';
require_once __DIR__ . '/../includes/userService.php';

if (isset($_GET['action']) && $_GET['action'] === 'cancel_registration') {
    $id4Del = $_SESSION['temp_idUser'] ?? null;

    if ($id4Del) {
        $delete_result = userService::deleteUser($id4Del);
        error_log('del ' . $delete_result[0]);

        if ($delete_result[0]) {
            unset($_SESSION['temp_idUser']);
            unset($_SESSION['show_form_2']);
            $response = ['status' => 'success', 'message' => 'Akun pengguna berhasil dihapus.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Gagal menghapus akun di database: ' . $delete_result[1]];
        }
    } else {
        unset($_SESSION['show_form_2']);
        $response = ['status' => 'success', 'message' => 'Sesi pendaftaran sudah bersih.'];
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$pageTitle = "Login Dokter - VetCare";
require_once __DIR__ . '/../src/config/config.php';

$show_login_form = true;
$show_register1_form = false;
$show_register2_form = false;

if (isset($_POST['register1']) && isset($_SESSION['show_form_2']) && $_SESSION['show_form_2'] === false) {
    $show_login_form = false;
    $show_register1_form = true;

} elseif (isset($_GET['tab']) && $_GET['tab'] === 'register' && isset($_SESSION['show_form_2']) && $_SESSION['show_form_2'] === true) {
    $show_login_form = false;
    $show_register1_form = false;
    $show_register2_form = true;
}

$tabRegis = $show_register1_form || $show_register2_form;

function CeknGo(int $idUser)
{
    $_SESSION['temp_idUser'] = $idUser;
    $_SESSION['show_form_2'] = true;
    header('Location: ' . $_SERVER['PHP_SELF'] . '?route=auth-dokter&tab=register');
    exit();
}

// Handle login
if (isset($_POST['login'])) {
    $email = filter_var(clean($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        setFlash('error', 'Email dan password harus diisi!');
    } else {
        $objUser = new DTO_pengguna(email: $email, pass: $password);
        $pesan = userService::login($objUser);

        if ($pesan[0] && $objUser->getRole() === 'Dokter') {
            $objDokter = DAO_dokter::getProfilDokter($objUser, true);
            if ($objDokter) {
                $_SESSION['dokter'] = $objDokter;
                previousPage();
                setFlash('success', 'Login berhasil! Selamat datang, Dr. ' . $objDokter->getNama());
                header('Location: ' . BASE_URL . '?route=dashboard-dokter');
                exit();
            } else if ($objDokter === null) {
                setFlash('error', 'Terdeteksi belum selesai daftar. Silahkan selesaikan registrasi Anda!');
                CeknGo($objUser->getIdUser());
            } else {
                setFlash('error', 'Gagal memuat profil dokter, silahkan coba lagi nanti');
            }
        } else {
            if ($objUser->getRole() !== 'Dokter') {
                setFlash('error', 'Akses ditolak. Hanya dokter yang dapat masuk melalui halaman ini.');
            } else {
                setFlash('error', $pesan[1]);
            }
        }
    }
}

// Handle registration
if (isset($_POST['register1'])) {
    $email = filter_var(clean($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $objUser = new DTO_pengguna(email: $email, pass: $password, role: 'Dokter');

    if ($password !== $confirm_password) {
        setFlash('error', 'Konfirmasi password tidak cocok!');
    } else {
        $cek = userService::login($objUser);
        $cek2 = DAO_dokter::getProfilDokter($objUser, true);
        if ($cek[0] && $objUser->getRole() === 'Dokter' && $cek2 ==null) {
            setFlash('success', 'Email sudah terdaftar. Silahkan lanjutkan tahap registrasi berikutnya.');
            CeknGo($objUser->getIdUser());
            exit();
        } else {
            $objUser->setNewPass($password); //udah dihapus oleh method login
            $hasil = userService::register($objUser);
            if (!$hasil[0]) {
                setFlash('error', $hasil[1]);
            } else {
                CeknGo($hasil[1]);
            }
        }
    }
}

// Handle registration stage 2 (dokter data)
if (isset($_POST['register2'])) {
    $id_user = $_SESSION['temp_idUser'] ?? null;
    
    if (!$id_user) {
        setFlash('error', 'Session expired. Please register again.');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    $nama = clean($_POST['nama'] ?? '');
    $ttl = $_POST['ttl'] ?? '';
    $pengalaman = intval($_POST['pengalaman'] ?? 0);
    $kategori_ids = $_POST['kategori'] ?? [];

    if (empty($nama) || empty($ttl) || empty($kategori_ids)) {
        setFlash('error', 'Nama, tanggal lahir, dan kategori harus diisi!');
    } else {
        try {
            // Upload SIP file
            $file_sip_name = null;
            if (isset($_FILES['file_sip']) && $_FILES['file_sip']['error'] === UPLOAD_ERR_OK) {
                $upload_result = uploadDocument($_FILES['file_sip'], DOCUMENTS_DIR . '/');
                if (!$upload_result['success']) {
                    throw new Exception('Gagal upload file SIP: ' . $upload_result['error']);
                }
                $file_sip_name = $upload_result['filename'];
            }

            // Upload STRV file
            $file_strv_name = null;
            if (isset($_FILES['file_strv']) && $_FILES['file_strv']['error'] === UPLOAD_ERR_OK) {
                $upload_result = uploadDocument($_FILES['file_strv'], DOCUMENTS_DIR . '/');
                if (!$upload_result['success']) {
                    throw new Exception('Gagal upload file STRV: ' . $upload_result['error']);
                }
                $file_strv_name = $upload_result['filename'];
            }

            // Upload foto profil
            $foto_name = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $upload_result = uploadImage($_FILES['foto'], PROFILE_DIR);
                if (!$upload_result['success']) {
                    throw new Exception('Gagal upload foto: ' . $upload_result['message']);
                }
                $foto_name = $upload_result['filename'];
            }

            // Create array of DTO_kateg from kategori IDs
            $datKateg = [];
            foreach ($kategori_ids as $id_kat) {
                $datKateg[] = new DTO_kateg((int)$id_kat, '');
            }

            // Create DTO_dokter object
            $objDokter = new DTO_dokter($id_user, $nama);
            $objDokter->upsertDokter(
                $id_user,
                $nama,
                $ttl,
                'PENDING',  // strv - placeholder, admin akan update
                null,  // exp_strv - nullable, admin akan update
                'PENDING',  // sip - placeholder, admin akan update
                null,  // exp_sip - nullable, admin akan update
                $foto_name ?? 'default-profile.jpg',
                $pengalaman
            );
            $objDokter->setStatus('nonaktif'); // Default status untuk tunggu approval admin

            // Insert dokter ke database
            $insert_result = DAO_dokter::insertDokter($objDokter, $datKateg);
            if (!$insert_result) {
                throw new Exception('Gagal menyimpan data dokter');
            }

            // Clear session
            unset($_SESSION['temp_idUser']);
            unset($_SESSION['show_form_2']);

            setFlash('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan admin. Silahkan login kembali setelah disetujui.');
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;

        } catch (Exception $e) {
            setFlash('error', $e->getMessage());
        }
    }
}
// Get flash message
$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            min-height: 600px;
        }

        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .auth-left h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .auth-left p {
            font-size: 18px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .auth-right {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-tabs {
            display: flex;
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .auth-tab {
            flex: 1;
            padding: 15px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            color: #666;
        }

        .auth-tab.active {
            background: #667eea;
            color: white;
        }

        .auth-form {
            display: none;
        }

        .auth-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .auth-links {
            text-align: center;
            margin-top: 20px;
        }

        .auth-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        .alert {
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

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                max-width: 100%;
            }

            .auth-left {
                padding: 30px 20px;
            }

            .auth-left h1 {
                font-size: 28px;
            }

            .auth-right {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-left">
            <h1>🏥 VetCare</h1>
            <p>Platform kesehatan hewan terpercaya untuk dokter hewan profesional. Bergabunglah dengan komunitas kami
                dan berikan pelayanan terbaik untuk hewan peliharaan.</p>
        </div>

        <div class="auth-right">
            <div class="auth-tabs">
                <button class="auth-tab <?php echo $show_login_form ? 'active' : ''; ?>"
                    onclick="<?php echo $show_register2_form ? 'confirmAndCancel()' : 'showForm(\'login\')'; ?>">Masuk</button>
                <button class="auth-tab <?php echo $tabRegis ? 'active' : ''; ?>" onclick="
                    showForm('register')">Daftar</button>
            </div>

            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form class="auth-form <?php echo $show_login_form ? 'active' : ''; ?>" id="login-form" method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Masukkan email Anda" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" name="login" class="btn-primary">Masuk</button>

                <div class="auth-links">
                    <a href="<?php echo BASE_URL; ?>pages/lupa-password.php"
                        class="text-primary hover:text-primary-dark transition-colors">Lupa Password?</a>
                </div>
            </form>

            <!-- Register Form -->
            <form class="auth-form <?php echo $show_register1_form ? 'active' : ''; ?>" id="register1-form"
                method="POST">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" placeholder="Masukkan email" required>
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" placeholder="Panjang minimal 8 karakter" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password *</label>
                    <input type="password" name="confirm_password" placeholder="Ulangi password" required>
                </div>
                <button type="submit" name="register1" class="btn-primary">Lanjut ke Data Dokter</button>
            </form>

            <!--register kedua -->
            <form class="auth-form <?php echo $show_register2_form ? 'active' : ''; ?>" id="register2-form"
                method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_user" value="<?php echo $_SESSION['temp_idUser'] ?? ''; ?>">
                
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama" placeholder="Masukkan nama lengkap beserta gelar" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir *</label>
                    <input type="date" name="ttl" required>
                </div>

                <div class="form-group">
                    <label>Tahun awal mulai praktik (Tahun)</label>
                    <input type="number" name="pengalaman" placeholder="0" min="0" value="0">
                </div>

                <div class="form-group">
                    <label>Upload File SIP *</label>
                    <input type="file" name="file_sip" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                    <small style="display: block; margin-top: 5px; color: #666;">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                </div>

                <div class="form-group">
                    <label>Upload File STRV *</label>
                    <input type="file" name="file_strv" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                    <small style="display: block; margin-top: 5px; color: #666;">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                </div>

                <div class="form-group">
                    <label>Kategori Spesialisasi *</label>
                    <div style="margin-top: 10px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <?php 
                        $all_kategori = DAO_kategori::getAllKategori();
                        foreach ($all_kategori as $kat): 
                        ?>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="kategori[]" value="<?php echo $kat->getIdK(); ?>" style="width: 18px; height: 18px; cursor: pointer;">
                                <span><?php echo htmlspecialchars($kat->getNamaKateg()); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" name="register2" class="btn-primary">Daftar Sekarang</button>
                <div class="auth-links">
                    <p>Batalkan? <a href="#" onclick="confirmAndCancel(); return false;">Batal registrasi</a></p>
                </div>
            </form>
        </div>

    <script>
        const API_URL = '<?php echo $_SERVER['PHP_SELF']; ?>' + '?route=auth-dokter&action=cancel_registration';
        const tabs = document.querySelectorAll('.auth-tab');

        function showForm(formType) {
            console.log(formType);

            // Hide all forms
            document.querySelectorAll('.auth-form').forEach(form => {
                form.classList.remove('active');
            });

            // Remove active class from tabs
            document.querySelectorAll('.auth-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            if (formType === 'login') {
                document.getElementById('login-form').classList.add('active');
                tabs[0].classList.add('active');
            } else if (formType === 'register') {
                document.getElementById('register1-form').classList.add('active');
                tabs[1].classList.add('active');
            }
        }

        function confirmAndCancel() {
            const confirmation = confirm("Apakah Anda yakin ingin membatalkan pendaftaran? Data yang telah dimasukkan akan dihapus.");
            if (confirmation) {
                fetch(API_URL)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert("Pendaftaran berhasil dibatalkan. Mengalihkan ke halaman Masuk.");
                            showForm('login');
                            window.location.reload();
                        } else {
                            alert("Gagal membatalkan pendaftaran. Coba lagi atau muat ulang halaman. Pesan: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Kesalahan koneksi:", error);
                        alert("Terjadi kesalahan jaringan. Silakan coba lagi.");
                    });
            }
        }
    </script>
</body>

</html>