<?php
/**
 * File: pages/auth-dokter.php
 * Halaman login dan registrasi dokter
 */



$pageTitle = "Login Dokter - VetCare";
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../includes/DAO_dokter.php';
require_once __DIR__ . '/../includes/DAO_user.php';
require_once __DIR__ . '/../includes/userService.php';


requireGuest();


$show_login_form = true;
$show_register1_form = false;
$show_register2_form = false;

if (isset($_SESSION['show_form_2']) && $_SESSION['show_form_2'] === true) {
    $show_login_form = false;
    $show_register2_form = true;
    
} elseif (isset($_POST['register1']) && isset($_SESSION['show_form_2']) && $_SESSION['show_form_2']===false) {
    $show_login_form = false;
    $show_register1_form = true;
    
} elseif (isset($_GET['tab']) && $_GET['tab'] === 'register') {
    $show_login_form = false;
    $show_register1_form = true;
}

// Handle login
if (isset($_POST['login'])) {
    $email = filter_var(clean($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        setFlash('error', 'Email dan password harus diisi!');
    } else {
        $objUser=new DTO_pengguna(email:$email, pass:$password);
        $pesan=userService::login($objUser);

        if ($pesan[0]) {
            $objDokter= DAO_dokter::getProfilDokter($objUser, true);
            if($objDokter){
                $_SESSION['dokter'] = $objDokter;
                setFlash('success', 'Login berhasil! Selamat datang, Dr. ' . $objDokter->getNama());
                header('Location: ' . BASE_URL . '?route=dashboard-dokter');
                exit();
            } else {
                setFlash('error', 'Gagal memuat profil dokter, silahkan coba lagi nanti');
            }
        } else {
            setFlash('error', $pesan[1]);
        }
    }
}

// Handle registration
if (isset($_POST['register1'])) {
    $email = filter_var(clean($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        setFlash('error', 'Konfirmasi password tidak cocok!');
    } elseif (strlen($password) < 6) {
        setFlash('error', 'Password minimal 6 karakter!');
    }else{
        $objUser = new DTO_pengguna(email:$email, pass:$password, role:'Dokter');
        $hasil = userService::register($objUser);
        if(!$hasil[0]){
            setFlash('error', $hasil[1]);
        }else{
            $_SESSION['temp_idUser']=$hasil[1];
            $_SESSION['show_form_2']=true;
            header('Location: '.$_SERVER['PHP_SELF']);
            exit;
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
            <p>Platform kesehatan hewan terpercaya untuk dokter hewan profesional. Bergabunglah dengan komunitas kami dan berikan pelayanan terbaik untuk hewan peliharaan.</p>
        </div>

        <div class="auth-right">
            <div class="auth-tabs">
                <button class="auth-tab active" onclick="showForm('login')" <?php echo $show_register2_form ? 'disabled' : ''; ?>>Masuk</button>
                <button class="auth-tab" onclick="showForm('register1')" <?php echo $show_register2_form ? '' : ''; ?>>Daftar</button>
            </div>

            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>



            <!-- Login Form -->
            <form class="auth-form active" id="login-form" method="POST">
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
                    <a href="<?php echo BASE_URL; ?>pages/lupa-password.php" class="text-primary hover:text-primary-dark transition-colors">Lupa Password?</a>
                </div>
            </form>

            <!-- Register Form -->
             <form class="auth-form " id="register1-form" method="POST">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" placeholder="Masukkan email" required>
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password *</label>
                    <input type="password" name="confirm_password" placeholder="Ulangi password" required>
                </div>
                <button type="submit" name="register1" class="btn-primary">Buat akun</button>
            </form>
            
            <!--register kedua -->
            <form class="auth-form" id="register2-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan Lengkap Beserta Gelar">
                    </div>

                <div class="form-group">
                    <label>Spesialisasi *</label>
                    <select name="spesialisasi" required>
                        <option value="">Pilih Spesialisasi</option>
                        <option value="Dokter Hewan Umum">Dokter Hewan Umum</option>
                        <option value="Spesialis Kucing">Spesialis Kucing</option>
                        <option value="Spesialis Anjing">Spesialis Anjing</option>
                        <option value="Spesialis Exotic">Spesialis Exotic</option>
                        <option value="Spesialis Bedah">Spesialis Bedah</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Pengalaman (tahun)</label>
                    <input type="number" name="pengalaman" placeholder="Masukkan pengalaman" min="0" value="0">
                </div>

                <div class="form-group">
                    <label>Upload File SIP</label>
                    <input type="file" name="file_sip" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                </div>

                <div class="form-group">
                    <label>Upload File STRV</label>
                    <input type="file" name="file_strv" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                </div>

                <button type="submit" name="register2" class="btn-primary">Daftar Sekarang</button>
                <?php if (!$show_register2_form): ?>
                <div class="auth-links">
                    <p>Sudah punya akun? <a href="#" onclick="showForm('login')">Masuk di sini</a></p>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        function showForm(formType) {
            // Hide all forms
            document.querySelectorAll('.auth-form').forEach(form => {
                form.classList.remove('active');
            });

            // Remove active class from tabs
            document.querySelectorAll('.auth-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            let targetForm;
            let targetTab;
            if(formType==='login'){
                targetForm='login-form';
                targetTab = document.querySelectorAll('.auth-tab')[0];
            }else if(formType==='register1'){
                targetForm='register1-form';
                targetTab=document.querySelectorAll('.auth-tab')[1];
            }else if(formType==='register2'){
                targetForm='register2-form';
                targetTab=document.querySelectorAll('.auth-tab')[1];
            }

            if(targetForm){
                document.getElementById(targetForm).classList.add('active');
            }
            if(targetTab){
                targetTab.classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function(){
            const activeForm = document.querySelector('.auth-form.active');
        
            if (activeForm) {
                const formId = activeForm.id;
                // Atur tab aktif berdasarkan form aktif
                if (formId === 'login-form') {
                    document.querySelectorAll('.auth-tab')[0].classList.add('active');
                } else if (formId === 'register1-form' || formId === 'register2-form') {
                    // Jika form 1 atau form 2 yang aktif, tombol 'Daftar' harus aktif
                    document.querySelectorAll('.auth-tab')[1].classList.add('active');
                }
            }
        });
    </script>
</body>
</html>