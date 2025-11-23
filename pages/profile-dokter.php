<?php

/**
 * File: pages/profile-dokter.php
 * Halaman profil dokter
 * Tidak terima upload foto
 * Tambahkan field harga
 * Kelola file
 * 
 */
$pageTitle = "Profil Dokter - VetCare";
require_once __DIR__ . '/../header-dokter.php';
require_once __DIR__ . '/../includes/DAO_dokter.php';
require_once __DIR__ . '/../includes/DAO_artikel.php';

// Require login
requireLogin(true, 'profil');
// Get current dokter profile
$currentDokter = $_SESSION['user'];

$profil = DAO_dokter::getProfilDokter($currentDokter, false);
$spesialisasi = $profil->getKategori();
$msg = DAO_dokter::manageDokter($profil);

if (!$profil) {
    setFlash('error', 'Gagal mengambil data, silahkan login ulang!');
    header('Location: ' . BASE_URL . 'index.php?route=dashboard-dokter');
    exit();
}

// Handle profile update
if (isset($_POST['update_profile'])) {
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $ttl = ($_POST['tgl_lahir']);
    $kab = clean($_POST['kabupaten']);
    $prov = clean($_POST['provinsi']);
    $pengalaman = (int) clean($_POST['pengalaman']);
    $harga = (int) clean($_POST['harga']);
    $data = [];

    // Handle SIP file upload
    if (isset($_FILES['file_sip']) && $_FILES['file_sip']['error'] == 0) {
        $upload_result = uploadDocument($_FILES['file_sip'], DOCUMENTS_DIR);
        if ($upload_result['success']) {
            // Delete old file if exists
            if ($profil->getSIP() && file_exists(DOCUMENTS_DIR . $profil->getSIP())) {
                unlink(DOCUMENTS_DIR . $profil->getSIP());
            }
            $data['sip'] = $upload_result['filename'];
        }
    }

    // Handle STRV file upload
    if (isset($_FILES['file_strv']) && $_FILES['file_strv']['error'] == 0) {
        $upload_result = uploadDocument($_FILES['file_strv'], DOCUMENTS_DIR);
        if ($upload_result['success']) {
            // Delete old file if exists
            if ($profil->getSIP() && file_exists(DOCUMENTS_DIR . $profil->getSIP())) {
                unlink(DOCUMENTS_DIR . $profil->getSIP());
            }
            $data['strv'] = $upload_result['filename'];
        }
    }

    $profil->upsertDokter($profil->getId(), $nama_lengkap, $ttl, pengalaman:$pengalaman, kab:$kab, prov:$prov, harga:$harga);
    $updateSuccess = DAO_dokter::updateDokter($profil->getId(), $profil, $profil->getStatus());

    if(isset($data['sip']) || isset($data['strv'])){
        $profil->setDocPath(isset($data['sip']) ? $data['sip']:null, isset($data['strv']) ? $data['strv']:null);
        $updateDoc = DAO_dokter::updateDocument($profil);
    }

    if ($updateSuccess[0]) {
        if(!$updateDoc[0]){
            setFlash('error', 'Gagal memperbarui dokumen! Namun, Profil berhasil diperbarui!');
        }
        setFlash('success', 'Profil berhasil diperbarui!');
    } else {
        setFlash('error', $updateSuccess[1]);
    }
    header('Location: ' . BASE_URL . 'index.php?route=profil');
    exit();
}

// Get statistics
$flash = getFlash();
    ?>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 2rem;
        border-radius: 1rem;
        max-width: 500px;
        width: 90%;
        animation: slideUp 0.3s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .tab-btn {
        transition: all 0.3s ease;
    }

    .tab-btn.active {
        background-color: #10b981;
        color: white;
    }

    .file-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload-area:hover {
        border-color: #10b981;
        background-color: #f0fdf4;
    }

    .file-upload-area.has-file {
        border-color: #10b981;
        background-color: #f0fdf4;
    }
</style>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
        <div class="text-center">
            <img src="<?php echo BASE_URL; ?>public/images/dokter/<?php echo $profil->getFoto() ?: 'default-profile.webp'; ?>"
                alt="Profile Photo" class="w-32 h-32 rounded-full object-cover border-4 border-primary mx-auto mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($profil->getNama()); ?></h1>
            <p class="text-lg text-gray-600">
                <?php
                foreach ($spesialisasi as $key => $value) {
                    echo $value['namaK'] . ($key < count($spesialisasi) - 1 ? ', ' : '');
                }
                ?>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="text-3xl font-bold text-primary mb-2"><?php echo (date('Y') - $profil->getPengalaman()); ?>
                tahun</div>
            <div class="text-gray-600">Pengalaman</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="text-3xl font-bold text-primary mb-2"><?php echo ($profil->getRate() * 100); ?>%</div>
            <div class="text-gray-600">Like Rate</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="text-3xl font-bold text-primary mb-2"><?php echo $profil->getStatus(); ?></div>
            <div class="text-gray-600"><?php echo $profil->getStatus() == 'nonaktif' ? 'Silahkan update dokumen Anda' : 'Status';?></div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-8">
        <div class="flex flex-wrap gap-3 justify-center">
            <button onclick="switchTab('data-diri')"
                class="tab-btn active px-6 py-2 rounded-lg font-medium bg-primary text-white" id="tab-data-diri">
                📋 Data Diri
            </button>
            <button onclick="switchTab('jadwal')"
                class="tab-btn px-6 py-2 rounded-lg font-medium bg-gray-200 text-gray-700 hover:bg-gray-300"
                id="tab-jadwal">
                📅 Jadwal
            </button>
            <button onclick="switchTab('kategori')"
                class="tab-btn px-6 py-2 rounded-lg font-medium bg-gray-200 text-gray-700 hover:bg-gray-300"
                id="tab-kategori">
                📊 kategori
            </button>
        </div>
        <?php if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                        <span><?php echo $flash['type'] == 'error' ? '❌' : '✅'; ?></span>
                        <?php echo $flash['message']; ?>
                    </div>
                <?php endif; ?>
    </div>

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Photo Upload Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">📸 Foto Profil</h3>

                <div class="text-center mb-6">
                    <img src="<?php echo BASE_URL; ?>public/images/dokter/<?php echo $profil->getFoto() ?: 'default-profile.webp'; ?>"
                        alt="Current Photo"
                        class="w-24 h-24 rounded-full object-cover border-4 border-primary mx-auto mb-4">
                </div>

                <!-- Change Password Button -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button onclick="openChangePasswordModal()"
                        class="w-full bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                        🔒 Ubah Password
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Diri Section -->
        <div class="lg:col-span-2" id="content-data-diri">
            <div class="bg-white rounded-xl shadow-sm p-6" id="profileCardWrapper">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <span class="mr-2">👤</span>Informasi Profil
                    </h3>
                    <button onclick="toggleEditMode('readonlyView', 'editForm', 'editButton')" id="editButton"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                        ✏️ Edit
                    </button>
                </div>

                <!-- Readonly View -->
                <div id="readonlyView" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" value="<?php echo htmlspecialchars($profil->getNama() ?? ''); ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="text" value="<?php echo htmlspecialchars($profil->getTTL() ?? ''); ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten</label>
                            <input type="text" value="<?php echo $profil->getKab() ?? ''; ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                            <input type="text" value="<?php echo $profil->getProv() ?? ''; ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pengalaman (Tahun Mulai)</label>
                            <input type="text" value="<?php echo $profil->getPengalaman() ?? 0; ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                            <input type="text" value="<?php echo formatRupiah($profil->getHarga() ?? 0); ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SIP</label>
                            <a href="<?php if ($msg) {
                                echo BASE_URL; ?>public/documents/<?php echo $profil->getPathSIP();
                            } ?>" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                📄Nomor SIP : <?php echo $profil->getSIP(); ?>
                            </a>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">STRV</label>
                            <a href="<?php if ($msg) {
                                echo BASE_URL; ?>public/documents/<?php echo $profil->getPathSTRV();
                            }
                            ; ?>" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                📄Nomor STRV : <?php echo $profil->getSTRV(); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="hidden" id="editForm">
                    <!-- Edit Form (Hidden by default) -->
                    <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="nama_lengkap"
                                    value="<?php echo htmlspecialchars($profil->getNama()); ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                                <input type="date" name="tgl_lahir"
                                    value="<?php echo htmlspecialchars($profil->getTTL()); ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">kabupaten</label>
                                <input type="text" name="kabupaten" value="<?php echo $profil->getKab(); ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                <input type="text" name="provinsi" value="<?php echo $profil->getProv(); ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pengalaman (Tahun Mulai)</label>
                                <input type="number" name="pengalaman" value="<?php echo date('Y', $profil->getPengalaman()); ?>"
                                    min="1900"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">harga Persesi Konsultasi</label>
                                <input type="number" name="harga" value="<?php echo $profil->getHarga(); ?>"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File SIP</label>
                                <div class="file-upload-area" id="sipUploadArea">
                                    <input type="file" name="file_sip" id="file_sip"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                                    <label for="file_sip" class="cursor-pointer block">
                                        <div class="text-3xl mb-2">📄</div>
                                        <div class="text-sm text-gray-600" id="sipFileName">Klik untuk upload file SIP
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File STRV</label>
                                <div class="file-upload-area" id="strvUploadArea">
                                    <input type="file" name="file_strv" id="file_strv"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                                    <label for="file_strv" class="cursor-pointer block">
                                        <div class="text-3xl mb-2">📄</div>
                                        <div class="text-sm text-gray-600" id="strvFileName">Klik untuk upload file STRV
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" name="update_profile"
                                class="bg-primary text-white py-2 px-6 rounded-lg hover:bg-secondary transition-colors">
                                💾 Simpan Perubahan
                            </button>
                            <button type="button" onclick="cancelEdit('readonlyView', 'editForm', 'editButton')"
                                class="bg-gray-400 text-white py-2 px-6 rounded-lg hover:bg-gray-500 transition-colors">
                                ❌ Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Other Tabs Content (Hidden by default) -->
        <div class="lg:col-span-2 hidden" id="content-jadwal">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">📅 Jadwal Praktik</h3>
                <p class="text-gray-600">Jadwal praktik akan ditampilkan di sini...</p>
            </div>
        </div>
        <div class="lg:col-span-2 hidden" id="content-kategori">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-center items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                        <span class="mr-2">📊</span> kategori
                    </h3>
                </div>
                <form method="POST" action="" class="space-y-6">
                    <input type="hidden" name="action" value="update_kategori">
                    <div class="space-y-4">
                        <label class="block text-lg font-medium text-gray-800 mb-3">Pilih Spesialisasi Anda:</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <?php
                            $allK = DAO_kategori::getAllKategori();
                            $profilKategori = $profil->getKategori();
                            $profilKategoriIds = array_column($profilKategori, 'idK');

                            foreach ($allK as $kItem):
                                $curId = $kItem->getIdK();
                                $isChecked = in_array($curId, $profilKategoriIds) ? 'checked': '';
                                ?>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="kategori_ids[]" value="<?php echo $curId; ?>"
                                        <?php echo $isChecked; ?>
                                        class="h-5 w-5 text-primary rounded border-gray-300 focus:ring-primary">
                                    <span
                                        class="ml-2 text-gray-700"><?php echo htmlspecialchars($kItem->getNamaKateg()); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-sm text-gray-500 mt-4">Centang kategori yang sesuai dengan spesialisasi praktik
                            Anda.</p>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" name="update_kategori_submit"
                            class="bg-primary text-white py-2 px-6 rounded-lg hover:bg-secondary transition-colors">
                            💾 Update Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Email Verification Modal -->
<div id="emailVerificationModal" class="modal">
    <div class="modal-content">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">✉️ Verifikasi Email</h2>
        <form id="emailVerificationForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="verifyEmail" value="<?php echo htmlspecialchars($dokter['email']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Verifikasi</label>
                <input type="text" id="verificationCode" placeholder="Masukkan kode verifikasi"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="sendVerificationCode()"
                    class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                    📤 Kirim Kode
                </button>
                <button type="submit"
                    class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                    ✅ Verifikasi
                </button>
            </div>
            <button type="button" onclick="closeEmailVerificationModal()"
                class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
                Tutup
            </button>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <!-- Step 1: Email Verification -->
        <div id="passwordStep1" class="space-y-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">🔒 Ubah Password - Verifikasi Email</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="verifyEmailPassword" value="<?php echo htmlspecialchars($dokter['email']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="sendPasswordVerificationCode()"
                    class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                    📤 Kirim Kode
                </button>
                <button type="button" onclick="closeChangePasswordModal()"
                    class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
                    Batal
                </button>
            </div>
        </div>

        <!-- Step 2: Code Verification -->
        <div id="passwordStep2" class="space-y-4 hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">🔒 Ubah Password - Masukkan Kode</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Verifikasi</label>
                <input type="text" id="passwordVerificationCode" placeholder="Masukkan kode verifikasi"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="verifyPasswordCode()"
                    class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                    ✅ Verifikasi
                </button>
                <button type="button" onclick="backToPasswordStep1()"
                    class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
                    Kembali
                </button>
            </div>
        </div>

        <!-- Step 3: New Password -->
        <div id="passwordStep3" class="space-y-4 hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">🔒 Ubah Password - Password Baru</h2>
            <form id="changePasswordForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" id="newPassword" placeholder="Masukkan password baru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="confirmPassword" placeholder="Konfirmasi password baru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        required>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-primary text-white py-2 px-4 rounded-lg hover:bg-secondary transition-colors">
                        💾 Simpan Password
                    </button>
                    <button type="button" onclick="backToPasswordStep2()"
                        class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
                        Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle Edit Mode - Hanya toggle tanpa ubah tombol
    function toggleEditMode(readonlyId, editFormId, editButtonId) {
        const readonlyView = document.getElementById(readonlyId);
        const editForm = document.getElementById(editFormId);
        const editButton = document.getElementById(editButtonId);

        readonlyView.classList.add('hidden');
        editForm.classList.remove('hidden');
        editButton.style.display = 'none'; // Sembunyikan tombol edit
    }

    // Cancel Edit - Kembali ke readonly dan tampilkan tombol edit
    function cancelEdit(readonlyId, editFormId, editButtonId) {
        const readonlyView = document.getElementById(readonlyId);
        const editForm = document.getElementById(editFormId);
        const editButton = document.getElementById(editButtonId);

        readonlyView.classList.remove('hidden');
        editForm.classList.add('hidden');
        editButton.style.display = 'flex'; // Tampilkan kembali tombol edit
    }

    // Switch Tabs
    function switchTab(tabName) {
        // Special handling for jadwal tab - redirect to jadwal page
        if (tabName === 'jadwal') {
            window.location.href = '<?php echo BASE_URL; ?>pages/jadwal-dokter.php';
            return;
        }

        // Hide all content sections
        const contents = ['data-diri', 'jadwal', 'kategori'];
        contents.forEach(content => {
            document.getElementById('content-' + content).classList.add('hidden');
            document.getElementById('tab-' + content).classList.remove('active', 'bg-primary', 'text-white');
            document.getElementById('tab-' + content).classList.add('bg-gray-200', 'text-gray-700');
        });

        // Show selected content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        document.getElementById('tab-' + tabName).classList.add('active', 'bg-primary', 'text-white');
        document.getElementById('tab-' + tabName).classList.remove('bg-gray-200', 'text-gray-700');

        // Reset edit mode saat pindah tab
        if (tabName === 'data-diri') {
            cancelEdit();
        }
    }

    // Email Verification Modal
    function openEmailVerificationModal() {
        document.getElementById('emailVerificationModal').classList.add('active');
    }

    function closeEmailVerificationModal() {
        document.getElementById('emailVerificationModal').classList.remove('active');
    }

    function sendVerificationCode() {
        alert('Kode verifikasi telah dikirim ke email Anda!');
    }

    document.getElementById('emailVerificationForm').addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Email berhasil diverifikasi!');
        closeEmailVerificationModal();
    });

    // Change Password Modal
    function openChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.add('active');
        showPasswordStep(1);
    }

    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.remove('active');
        document.getElementById('changePasswordForm').reset();
        document.getElementById('passwordVerificationCode').value = '';
        showPasswordStep(1);
    }

    function showPasswordStep(step) {
        document.getElementById('passwordStep1').classList.add('hidden');
        document.getElementById('passwordStep2').classList.add('hidden');
        document.getElementById('passwordStep3').classList.add('hidden');
        document.getElementById('passwordStep' + step).classList.remove('hidden');
    }

    function sendPasswordVerificationCode() {
        alert('Kode verifikasi telah dikirim ke email Anda!');
        showPasswordStep(2);
    }

    function verifyPasswordCode() {
        const code = document.getElementById('passwordVerificationCode').value;
        if (code.trim() === '') {
            alert('Masukkan kode verifikasi!');
            return;
        }
        // Here you would verify the code with the server
        alert('Kode verifikasi berhasil!');
        showPasswordStep(3);
    }

    function backToPasswordStep1() {
        showPasswordStep(1);
        document.getElementById('passwordVerificationCode').value = '';
    }

    function backToPasswordStep2() {
        showPasswordStep(2);
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
    }

    document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmPassword').value;

        if (newPass !== confirmPass) {
            alert('Password tidak cocok!');
            return;
        }

        if (newPass.length < 6) {
            alert('Password minimal 6 karakter!');
            return;
        }

        // Send AJAX request to update password
        fetch('api/change-password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                new_password: newPass
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password berhasil diubah!');
                    closeChangePasswordModal();
                } else {
                    alert('Gagal mengubah password: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah password');
            });
    });

    // Close modals when clicking outside
    window.onclick = function (event) {
        const emailModal = document.getElementById('emailVerificationModal');
        const passwordModal = document.getElementById('changePasswordModal');

        if (event.target === emailModal) {
            closeEmailVerificationModal();
        }
        if (event.target === passwordModal) {
            closeChangePasswordModal();
        }
    }

    // File upload handlers
    document.getElementById('file_sip').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const uploadArea = document.getElementById('sipUploadArea');
        const fileName = document.getElementById('sipFileName');

        if (file) {
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                this.value = '';
                return;
            }

            fileName.textContent = 'File dipilih: ' + file.name;
            uploadArea.classList.add('has-file');
        } else {
            fileName.textContent = 'Klik untuk upload file SIP';
            uploadArea.classList.remove('has-file');
        }
    });

    document.getElementById('file_strv').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const uploadArea = document.getElementById('strvUploadArea');
        const fileName = document.getElementById('strvFileName');

        if (file) {
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                this.value = '';
                return;
            }

            fileName.textContent = 'File dipilih: ' + file.name;
            uploadArea.classList.add('has-file');
        } else {
            fileName.textContent = 'Klik untuk upload file STRV';
            uploadArea.classList.remove('has-file');
        }
    });



    // Preview foto profil
    document.getElementById('foto_profil').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar!');
                this.value = '';
                return;
            }

            // Validate file size (2MB for images)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar terlalu besar! Maksimal 2MB');
                this.value = '';
                return;
            }
        }
    });
</script>