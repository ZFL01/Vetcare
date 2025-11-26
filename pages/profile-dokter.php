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
        $upload_result = uploadDocument($_FILES['file_sip'], DOCUMENTS_DIR, 'sip_');
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
        $upload_result = uploadDocument($_FILES['file_strv'], DOCUMENTS_DIR, 'strv_');
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
elseif(isset($_POST['update_kategori_submit'])){
    $kategori = isset($_POST['kategori_ids']) ? $_POST['kategori_ids'] : [];
    $dataKateg = [];
    
    foreach($kategori as $k){
        $dataKateg[]= new DTO_kateg((int)$k);
    }
    $updateKategori = DAO_dokter::setKategDokter(true, $profil->getId(), $dataKateg);
    if($updateKategori){
        setFlash('success', 'Spesialisasi berhasil diperbarui!');
    } else {
        setFlash('error', 'Gagal memperbarui Spesialisasi!');
    }
    header('Location: ' . BASE_URL . 'index.php?route=profil');
    exit();
}
elseif($action === 'verify_pass'){
    $head = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    $bool = empty($head) || strtolower($head) !== 'xmlhttprequest';
    if($bool){
        http_response_code(403);
        exit();
    }
    $pass = $_POST['password']??'';
    header('Content-Type: application/json');

    if(!$pass){
        echo json_encode(['success'=>false, 'message'=>'Password tidak boleh kosong']);
        exit();
    }
    $currentDokter->setNewPass($pass);
    $hasil = userService::login($currentDokter);
    if($hasil[0]){
        $_SESSION['can_change_pass']=true;
        echo json_encode(['success'=>true, 'message'=>'Anda bisa melanjutkan perubahan Password']);
        exit();
    }else{
        echo json_encode(['success'=>false, 'message'=>$hasil[1]]);
        exit();
    }
}
elseif($action === 'change_pass'){
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'){
        http_response_code(403);
        exit();
    }elseif(!isset($_SESSION['can_change_pass'])||!$_SESSION['can_change_pass']){
        setFlash('error', 'Anda harus verifikasi password terlebih dahulu!');
        header('Location: ' . BASE_URL . 'index.php?route=profil');
        exit();
    }
    $newPassword = $_POST['new_password'] ?? '';
    $currentUser = $_SESSION['user'] ?? null; 
    header('Content-Type: application/json');
    if(strlen($newPassword) < 8){
        echo json_encode(['success'=>false, 'message'=>'Password minimal 8 karakter']);
        exit();
    }
    $currentUser->setNewPass($newPassword);
    $hasil = userService::changePass($currentUser);
    if($hasil[0]){
        unset($_SESSION['can_change_pass']);
        echo json_encode([
            'success' => true, 
            'message' => 'Password berhasil diubah!'
        ]);
        exit();
    }else{
        echo json_encode(['success'=>false, 'message'=>$hasil[1]]);
        exit();
    }
}elseif ($action === 'update_full_schedule') {
    header('Content-Type: application/json');
    
    $head = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    if(empty($head) || strtolower($head) !== 'xmlhttprequest'){
        http_response_code(403);
        exit();
    }

    $scheduleData = $_POST['schedule'] ?? []; 
    $finalSchedule = [];
    $idDokter = $profil->getId();
    $savedCount = 0;

    foreach ($scheduleData as $dayIndex => $slots) {
        if (!is_numeric($dayIndex) || !array_key_exists((int)$dayIndex, HARI_ID)) {
        continue;
        }
        if (!is_array($slots) || empty($slots)) {
            continue; 
        }
        foreach ($slots as $slotId => $slot) {
            $buka = $slot['buka'] ?? null;
            $tutup = $slot['tutup'] ?? null;
            
            if ($buka && $tutup) {
                $dayName = HARI_ID[$dayIndex]; 
                
                $finalSchedule[$dayName][] = [
                    'buka' => $buka,
                    'tutup' => $tutup
                ];
                $savedCount++;
            }
        }
    }
    $status = DAO_dokter::setJadwal($idDokter, $finalSchedule);
    
    if ($status) {
        echo json_encode(['success' => true, 'message' => "Jadwal berhasil diperbarui. Total $savedCount sesi disimpan."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan jadwal ke database.']);
    }
    exit();
}

// Get statistics
$flash = getFlash();
include 'base.php';
include_once 'header.php';
    ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/leaflet/leaflet.css">

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
        max-width: 400px;
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
</style></head>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
        <div class="text-center">
            <img src="<?php echo URL_FOTO . 'dokter-profil/' . ($profil->getFoto() ?: 'default-profile.webp'); ?>"
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
            <button onclick="switchTab('tempat-klinik')"
                class="tab-btn px-6 py-2 rounded-lg font-medium bg-gray-200 text-gray-700 hover:bg-gray-300"
                id="tab-tempat-klinik">
                Tempat Klinik
            </button>
        </div>
        <?php if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>" style='text-align:center'>
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
                    <img src="<?php echo URL_FOTO . 'dokter-profil/' . ($profil->getFoto() ?: 'default-profile.webp'); ?>"
                        alt="Current Photo"
                        class="w-24 h-24 rounded-full object-cover border-4 border-primary mx-auto mb-4">
                </div>

                <!-- Change Password Button -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button onclick="cekPass()"
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
                                echo SIP_DIR; ?>public/documents/<?php echo $profil->getPathSIP();
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
         <!-- Ini_Jadwal -->
        <div class="lg:col-span-2 hidden" id="content-jadwal">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">📅 Sesuaikan Jadwal Praktik</h3>
                
                <form id="scheduleForm" method="POST" action=""> 
                    <input type="hidden" name="action" value="update_full_schedule">
                    
                    <div id="scheduleContainer" class="space-y-6">
                        
                        <?php
                        $allDaysMap = HARI_ID;
                        $dokterJadwal = DAO_dokter::getJadwal($profil);

                        foreach ($allDaysMap as $dayIndex => $dayName):
                            $isScheduled = isset($dokterJadwal[$dayName]);
                            $sesiList = $isScheduled ? $dokterJadwal[$dayName] : [];
                        ?>
                        
                        <div class="border rounded-xl p-4 <?= $isScheduled ? 'border-purple-400 bg-purple-50' : 'border-gray-300' ?>" 
                            data-day-index="<?= $dayIndex ?>" data-day-name="<?= $dayName ?>">
                            
                            <div class="flex justify-between items-center mb-3 border-b pb-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="days_active[]" 
                                        value="<?= $dayIndex ?>"
                                        <?= $isScheduled ? 'checked' : '' ?>
                                        class="h-5 w-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500 day-toggle"
                                    >
                                    <span class="ml-3 text-xl font-bold text-gray-800"><?= htmlspecialchars($dayName) ?></span>
                                </label>
                                <button type="button" 
                                    class="text-sm py-1 px-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition" 
                                    onclick="addSlot(this, '<?= $dayIndex ?>')">
                                    + Tambah Sesi
                                </button>
                            </div>

                            <div class="space-y-2 schedule-slots-list" id="slots-<?= $dayIndex ?>">

                                <?php 
                                // Loop 2: Tampilkan Sesi yang Sudah Ada
                                foreach ($sesiList as $i => $sesi):
                                    // Indeks $i digunakan untuk array name di JS
                                ?>
                                <div class="grid grid-cols-4 gap-2 items-center slot-row" data-slot-id="<?= $i ?>">
                                    <span class="col-span-1">Buka</span>
                                    <input type="time" 
                                        name="schedule[<?= $dayIndex ?>][<?= $i ?>][buka]" 
                                        value="<?= $sesi->getBuka() ?>" 
                                        required
                                        class="col-span-1 border border-gray-300 rounded-lg p-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                                    
                                    <span class="col-span-1">Tutup</span>
                                    <input type="time" 
                                        name="schedule[<?= $dayIndex ?>][<?= $i ?>][tutup]" 
                                        value="<?= $sesi->getTutup() ?>" 
                                        required
                                        class="col-span-1 border border-gray-300 rounded-lg p-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                                    
                                    <button type="button" 
                                        class="col-span-2 text-sm text-red-600 hover:text-red-800 flex items-center justify-start gap-1"
                                        onclick="removeSlot(this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4"></path></svg>
                                        Hapus
                                    </button>
                                </div>
                                <?php endforeach; ?>
                                
                            </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="pt-6 border-t border-gray-200 mt-6">
                        <button type="submit" name="update_jadwal_submit"
                            class="bg-purple-600 text-white py-2 px-6 rounded-lg hover:bg-purple-700 transition-colors">
                            💾 Simpan Semua Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ini_Kategori -->
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

        <!-- Ini_Tempat-Klinik -->
        <div class="lg:col-span-2 hidden" id="content-tempat-klinik">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-center items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                        <span class="mr-2">📍</span> Tempat Klinik
                    </h3>
                </div>
                <?php 
                $status = DAO_dokter::getAlamat($profil);
                $koor = $profil->getKoor();
                $lat = (is_array($koor) && isset($koor[0])) ? $koor[0] :'';
                $long = (is_array($koor) && isset($koor[1])) ? $koor[1] :'';
                ?>
                <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                    <div class="space-y-4">
                        <label style="text-align: center;" class="block text-lg font-medium text-gray-800 mb-3">Lokasi Klinik Anda (Opsional):
                        <br><p6 style="text-align: center; font-size: 12px;">Ini akan ditampilkan di laman info profil Anda pada client-side (membutuhkan izin lokasi)</p6></label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Klinik *</label>
                                <input type="text" name="nama_klinik"
                                    value="<?php echo $profil->getNamaKlinik()?: ''; ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    required>
                            </div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">Tandai Lokasi Klinik di Peta:</label>
                            <div id="map-klinik" style="height: 300px; width: 100%; border-radius: 8px; border: 1px solid #ccc;"></div>
                            <div>
                                <input type="hidden" name="latitude" id="input-latitude" value="<?php echo htmlspecialchars($lat); ?>">
                                <input type="hidden" name="longitude" id="input-longitude" value="<?php echo htmlspecialchars($long); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" name="update_tempat_klinik_submit"
                            class="bg-primary text-white py-2 px-6 rounded-lg hover:bg-secondary transition-colors">
                            💾 Update Tempat Klinik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Pass Verification Modal -->
<div id="passwordVerif" class="modal">
    <div class="modal-content">
        <h2 style="text-align: center;" class="text-2xl font-bold text-gray-800 mb-6">✉️ Verifikasi</h2>
        <form id="PassVerificationForm" class="space-y-4">
            <div>
                <label style="text-align: center;" class="block text-sm font-medium text-gray-700 mb-2">Masukkan Password Anda</label>
                <input type="password" id="verifyPass" placeholder="Masukkan password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                    required>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                    ✅ Verifikasi
                </button>
                <button type="button" onclick="closePassVerif()"
                    class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
                    Tutup
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <div id="newPass" class="space-y-4 hidden">
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
                    <button type="button" onclick="closeChangePasswordModal()"
                        class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
                        Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</html>
<?php include_once 'footer.php'; ?>

<script>
    src="<?php echo BASE_URL?>public/assets/leaflet/leaflet.js"
    let map = null;


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

        // Hide all content sections
        const contents = ['data-diri', 'jadwal', 'kategori', 'tempat-klinik'];
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
        if (tabName === 'tempat-klinik' && map){
            map.invalidateSize();
        }
    }

    // Pass Verification Modal
    function cekPass() {
        document.getElementById('passwordVerif').classList.add('active');
    }

    function closePassVerif() {
        document.getElementById('passwordVerif').classList.remove('active');
        document.getElementById('PassVerificationForm').reset();
    }

    // Change Password Modal
    function openChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.add('active');
        document.getElementById('newPass').classList.remove('hidden');
    }

    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.remove('active');
        document.getElementById('changePasswordForm').reset();
    }

        function handleVerifyPass(e) {
            e.preventDefault();
            const verifPass = document.getElementById('verifyPass').value;
            if(!verifPass){
                alert('Masukkan Password Anda!');
                return;
            }
            const btnSubm = e.target.querySelector('button[type="submit"]');
            btnSubm.disabled = true;
            btnSubm.textContent = 'Mengirim.....';

            fetch('?route=profil&action=verify_pass', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    password: verifPass
                })
            }).then(response => response.json()).then(data=>{
                btnSubm.disabled = false;
                btnSubm.textContent = 'Memverifikasi...';

                if(data.success){
                    alert(data.message);
                    closePassVerif();
                    openChangePasswordModal();
                }else{
                    alert('verifikasi gagal: '+data.message);
                }
            }).catch(error=>{
                btnSubm.disabled = false;
                btnSubm.textContent = 'Verifikasi';
                alert('Terjadi kesalahan: '+error.message);
            })
        }

        function handleChangePassword(e){
        e.preventDefault();
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmPassword').value;

        if (newPass !== confirmPass) {
            alert('Password tidak cocok!');
            return;
        }
        if (newPass.length < 8) {
            alert('Password minimal 8 karakter!');
            return;
        }

        // Tampilkan indikator loading
        const submitBtn = e.target.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';

        // ➡️ Ubah endpoint dan body
        fetch('?route=profil&action=change_pass', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded', 
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                new_password: newPass
            })
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Simpan Password';

            if (data.success) {
                alert('Password berhasil diubah!');
                closeChangePasswordModal();
                location.reload();
            } else {
                alert('Gagal mengubah password: ' + data.message);
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.textContent = '💾 Simpan Password';
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah password');
        });
    }

document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('PassVerificationForm').addEventListener('submit', handleVerifyPass);
    document.getElementById('changePasswordForm').addEventListener('submit', handleChangePassword);

    window.onclick = function (event) {
        const verifModal = document.getElementById('passwordVerif');
        const passwordModal = document.getElementById('changePasswordModal');

        if (event.target === verifModal) {
            closePassVerif();
        }
        if (event.target === passwordModal) {
            closeChangePasswordModal();
        }
    }
});

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

    let slotCounter = 100; 

    function addSlot(button, dayIndex) {
        const slotsList = document.getElementById(`slots-${dayIndex}`);
        const newSlotId = slotCounter++;
        
        const newSlotHtml = `
            <div class="grid grid-cols-4 gap-2 items-center slot-row" data-slot-id="${newSlotId}">
            <span class="col-span-1">Buka</span>    
            <input type="time" 
                    name="schedule[${dayIndex}][${newSlotId}][buka]" 
                    value="" 
                    required
                    class="col-span-1 border border-gray-300 rounded-lg p-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                
            <span class="col-span-1">Tutup</span>
            <input type="time" 
                    name="schedule[${dayIndex}][${newSlotId}][tutup]" 
                    value="" 
                    required
                    class="col-span-1 border border-gray-300 rounded-lg p-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                
                <button type="button" 
                    class="col-span-2 text-sm text-red-600 hover:text-red-800 flex items-center justify-start gap-1"
                    onclick="removeSlot(this)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4"></path></svg>
                    Hapus
                </button>
            </div>
        `;

        slotsList.insertAdjacentHTML('beforeend', newSlotHtml);
    }

    function removeSlot(button) {
        const row = button.closest('.slot-row');
        if (row) {
            row.remove();
        }
    }
    
    document.querySelectorAll('.day-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const dayIndex = this.value;
            const slotsList = document.getElementById(`slots-${dayIndex}`);
            if (!this.checked) {
                slotsList.innerHTML = `<p class="text-sm text-gray-500 italic">Jadwal dinonaktifkan. Centang untuk menambah sesi.</p>`;
            } else {
                if (slotsList.children.length <= 1) { 
                     slotsList.innerHTML = ''; 
                     addSlot(this, dayIndex);
                }
            }
        });
    });
    
    function handleSubmitSchedule(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // 1. Kumpulkan Data Formulir
    const formData = new FormData(form);
    
    // 2. Tampilkan Loading dan Nonaktifkan Tombol
    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Menyimpan Jadwal...';
    
    // 3. Konfigurasi Fetch Request
    fetch('?route=profil&action=update_full_schedule', { 
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Cek status code (terutama 200 OK atau error)
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        // 4. Handle Respon Sukses
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        
        if (data.success) {
            alert(data.message || 'Jadwal berhasil diperbarui!');
            location.reload(); 
        } else {
            alert('Gagal menyimpan jadwal: ' + (data.message || 'Terjadi kesalahan server.'));
        }
    })
    .catch(error => {
        // 5. Handle Error
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        console.error('Error saat submit jadwal:', error);
        alert('Terjadi kesalahan koneksi atau server: ' + error.message);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('scheduleForm').addEventListener('submit', handleSubmitSchedule);
});

//ini lokasi peta
document.addEventListener('DOMContentLoaded', function() {
    const savedProvinsi = "<?php echo htmlspecialchars($profil->getProv() ?? ''); ?>";
    const savedKabupaten = "<?php echo htmlspecialchars($profil->getKab() ?? ''); ?>";
    const initLat = '<?php echo $profil->getKoor()[0] ?? ''; ?>';
    const initLng = '<?php echo $profil->getKoor()[1] ?? ''; ?>';
    const defaultLat = -6.2088; // Koordinat default ( Jakarta)
    const defaultLng = 106.8456;

    if(!initLat && savedProvinsi){
        let query = `${savedKabupaten ? savedKabupaten + ',' : ''} ${savedProvinsi}, Indonesia`;
    
        const nominatimURL = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=1`;
        fetch(nominatimURL)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const result = data[0];
                    initialLat = parseFloat(result.lat);
                    initialLng = parseFloat(result.lon);
                    initializeMap(initialLat, initialLng);
                } else {
                    initializeMap(initialLat, initialLng);
                }
            })
            .catch(error => {
                console.error('Geocoding wilayah gagal:', error);
                initializeMap(initialLat, initialLng);
            });
    }else{
            initializeMap(initLat, initLng);
    }

    function initializeMap(lat, lng){
        const inputLat = document.getElementById('input-latitude');
        const inputLng = document.getElementById('input-longitude');
    

    // --- INISIALISASI PETA ---
    map = L.map('map-klinik').setView([lat, lng], 13); // Zoom level 13

    // Tambahkan Tile Layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // --- INISIALISASI MARKER (PIN) ---
    const marker = L.marker([lat, lng], {
        draggable: true // 🎯 Kunci: Membuat marker dapat digeser
    }).addTo(map);

    inputLat.value = lat.toFixed(6); // Simpan hingga 6 desimal
    inputLng.value = lng.toFixed(6);

    // --- EVENT LISTENER UNTUK DRAG MARKER ---
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        inputLat.value = position.lat.toFixed(6);
        inputLng.value = position.lon.toFixed(6);
    });
    
    // Opsional: Double-click di peta untuk memindahkan marker (user experience lebih baik)
    map.on('dblclick', function(e) {
        marker.setLatLng(e.latlng);
        inputLat.value = e.latlng.lat.toFixed(6);
        inputLng.value = e.latlng.lon.toFixed(6);
    })
}
});
</script>