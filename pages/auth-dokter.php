<?php
// Authentication page for doctors with database integration
require_once __DIR__ . '/../vendor/autoload.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
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