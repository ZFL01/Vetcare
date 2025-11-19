<?php

/**
 * File: pages/profile-dokter.php
 * Halaman profil dokter
 */

$pageTitle = "Profil Dokter - VetCare";
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../services/DAO_dokter.php';
require_once __DIR__ . '/../services/DAO_artikel.php';

// Require login
requireLogin();

// Get current dokter profile
$currentDokter = [
    'id_dokter' => $_SESSION['dokter_id'] ?? null,
    'nama' => $_SESSION['dokter_nama'] ?? null,
    'email' => $_SESSION['dokter_email'] ?? null,
    'foto' => $_SESSION['dokter_foto'] ?? null
];

if (!$currentDokter['id_dokter']) {
    setFlash('error', 'Session login tidak valid. Silakan login kembali!');
    header('Location: ' . BASE_URL . 'pages/auth-dokter.php');
    exit();
}

    $db = Database::getConnection();
    $daoDokter = new DAO_Dokter($db);
    $daoArtikel = new DAO_Artikel($db);

    $dokter = $daoDokter->getById($currentDokter['id_dokter']);

// Get doctor's articles
$artikel_list = $daoArtikel->getByDokter($currentDokter['id_dokter'], 10);

if (!$dokter) {
    setFlash('error', 'Data dokter tidak ditemukan!');
    header('Location: ' . BASE_URL . 'pages/dashboard-dokter.php');
    exit();
}

    // Handle profile update
    if (isset($_POST['update_profile'])) {
        $nama_lengkap = clean($_POST['nama_lengkap']);
        $nomor_telepon = clean($_POST['nomor_telepon']);
        $spesialisasi = clean($_POST['spesialisasi']);
        $pengalaman = (int)clean($_POST['pengalaman']);

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'nomor_telepon' => $nomor_telepon,
            'spesialisasi' => $spesialisasi,
            'pengalaman' => $pengalaman
        ];

    // Handle SIP file upload
    if (isset($_FILES['file_sip']) && $_FILES['file_sip']['error'] == 0) {
        $upload_result = uploadDocument($_FILES['file_sip'], DOCUMENTS_DIR);
        if ($upload_result['success']) {
            // Delete old file if exists
            if ($dokter['file_sip'] && file_exists(DOCUMENTS_DIR . $dokter['file_sip'])) {
                unlink(DOCUMENTS_DIR . $dokter['file_sip']);
            }
            $data['file_sip'] = $upload_result['filename'];
        }
    }

    // Handle STRV file upload
    if (isset($_FILES['file_strv']) && $_FILES['file_strv']['error'] == 0) {
        $upload_result = uploadDocument($_FILES['file_strv'], DOCUMENTS_DIR);
        if ($upload_result['success']) {
            // Delete old file if exists
            if ($dokter['file_strv'] && file_exists(DOCUMENTS_DIR . $dokter['file_strv'])) {
                unlink(DOCUMENTS_DIR . $dokter['file_strv']);
            }
            $data['file_strv'] = $upload_result['filename'];
        }
    }



    if ($daoDokter->updateProfile($currentDokter['id_dokter'], $data)) {
        setFlash('success', 'Profil berhasil diperbarui!');
        header('Location: ' . BASE_URL . 'pages/profile-dokter.php');
        exit();
    } else {
        setFlash('error', 'Gagal memperbarui profil!');
    }
}

// Handle photo upload
if (isset($_POST['update_foto'])) {
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
        $upload_result = uploadImage($_FILES['foto_profil'], PROFILE_DIR);

        if ($upload_result['success']) {
            // Delete old photo if exists
            if ($dokter['foto_profil'] && $dokter['foto_profil'] != 'default-profile.jpg') {
                deleteImage($dokter['foto_profil'], PROFILE_DIR);
            }

            if ($daoDokter->updateFotoProfil($currentDokter['id_dokter'], $upload_result['filename'])) {
                setFlash('success', 'Foto profil berhasil diperbarui!');
                header('Location: ' . BASE_URL . 'pages/profile-dokter.php');
                exit();
            } else {
                setFlash('error', 'Gagal memperbarui foto profil!');
            }
        } else {
            setFlash('error', $upload_result['message']);
        }
    } else {
        setFlash('error', 'Pilih file gambar terlebih dahulu!');
    }
}

// Get statistics
$statistik = $daoDokter->getStatistik($currentDokter['id_dokter']);
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
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
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
            <img src="<?php echo BASE_URL; ?>public/images/dokter/<?php echo $dokter['foto_profil'] ?: 'default-profile.jpg'; ?>"
                alt="Profile Photo" class="w-32 h-32 rounded-full object-cover border-4 border-primary mx-auto mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($dokter['nama_lengkap']); ?></h1>
            <p class="text-lg text-gray-600">
                <?php
                $spesialisasi_text = [
                    'umum' => 'Dokter Hewan Umum',
                    'kucing' => 'Spesialis Kucing',
                    'anjing' => 'Spesialis Anjing',
                    'exotic' => 'Spesialis Hewan Exotic',
                    'bedah' => 'Spesialis Bedah'
                ];
                echo $spesialisasi_text[$dokter['spesialisasi']] ?? 'Dokter Hewan';
                ?>
            </p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="text-3xl font-bold text-primary mb-2"><?php echo $statistik['total_artikel'] ?? 0; ?></div>
            <div class="text-gray-600">Artikel Ditulis</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="text-3xl font-bold text-primary mb-2"><?php echo $statistik['total_jawaban'] ?? 0; ?></div>
            <div class="text-gray-600">Jawaban Diberikan</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="text-3xl font-bold text-primary mb-2"><?php echo $statistik['total_views'] ?? 0; ?></div>
            <div class="text-gray-600">Total Views</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="text-3xl font-bold text-primary mb-2"><?php echo $statistik['pengalaman'] ?? 0; ?> tahun</div>
            <div class="text-gray-600">Pengalaman</div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-8">
        <div class="flex flex-wrap gap-3">
            <button onclick="switchTab('data-diri')" class="tab-btn active px-6 py-2 rounded-lg font-medium bg-primary text-white" id="tab-data-diri">
                📋 Data Diri
            </button>
            <button onclick="switchTab('jadwal')" class="tab-btn px-6 py-2 rounded-lg font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" id="tab-jadwal">
                📅 Jadwal
            </button>
            <button onclick="switchTab('artikel')" class="tab-btn px-6 py-2 rounded-lg font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" id="tab-artikel">
                📝 Artikel
            </button>
            <button onclick="switchTab('riwayat')" class="tab-btn px-6 py-2 rounded-lg font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" id="tab-riwayat">
                📊 Riwayat
            </button>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Photo Upload Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">📸 Foto Profil</h3>

                <div class="text-center mb-6">
                    <img src="<?php echo BASE_URL; ?>public/images/dokter/<?php echo $dokter['foto_profil'] ?: 'default-profile.jpg'; ?>"
                        alt="Current Photo" class="w-24 h-24 rounded-full object-cover border-4 border-primary mx-auto mb-4">
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary transition-colors">
                            <input type="file" name="foto_profil" id="foto_profil" accept="image/*" class="hidden">
                            <label for="foto_profil" class="cursor-pointer">
                                <div class="text-gray-500 mb-2">📁</div>
                                <div class="text-sm text-gray-600">Pilih Foto Baru</div>
                            </label>
                        </div>
                        <button type="submit" name="update_foto" class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-secondary transition-colors">
                            Update Foto
                        </button>
                    </div>
                </form>

                <!-- Change Password Button -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button onclick="openChangePasswordModal()" class="w-full bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                        🔒 Ubah Password
                    </button>
                </div>


            </div>
        </div>

        <!-- Data Diri Section -->
        <div class="lg:col-span-2" id="content-data-diri">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <span class="mr-2">👤</span>Informasi Profil
                    </h3>
                    <button onclick="toggleEditMode()" id="editButton" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                        ✏️ Edit
                    </button>
                </div>

                <!-- Readonly View -->
                <div id="readonlyView" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" value="<?php echo htmlspecialchars($dokter['nama_lengkap'] ?? ''); ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" value="<?php echo htmlspecialchars($dokter['nomor_telepon'] ?? ''); ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Spesialisasi</label>
                            <input type="text" value="<?php echo htmlspecialchars($spesialisasi_text[$dokter['spesialisasi']] ?? ''); ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pengalaman</label>
                            <input type="text" value="<?php echo ($dokter['pengalaman'] ?? 0) . ' tahun'; ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File SIP</label>
                            <?php if (!empty($dokter['file_sip'])): ?>
                                <a href="<?php echo BASE_URL; ?>public/documents/<?php echo $dokter['file_sip']; ?>"
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                    📄 Lihat File SIP
                                </a>
                            <?php else: ?>
                                <div class="text-gray-500 italic">Belum ada file</div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File STRV</label>
                            <?php if (!empty($dokter['file_strv'])): ?>
                                <a href="<?php echo BASE_URL; ?>public/documents/<?php echo $dokter['file_strv']; ?>"
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                    📄 Lihat File STRV
                                </a>
                            <?php else: ?>
                                <div class="text-gray-500 italic">Belum ada file</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Form (Hidden by default) -->
                <form method="POST" action="" enctype="multipart/form-data" id="editForm" class="space-y-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($dokter['nama_lengkap']); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" value="<?php echo htmlspecialchars($dokter['nomor_telepon'] ?? ''); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Spesialisasi *</label>
                            <select name="spesialisasi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                                <option value="umum" <?php echo $dokter['spesialisasi'] == 'umum' ? 'selected' : ''; ?>>Umum</option>
                                <option value="kucing" <?php echo $dokter['spesialisasi'] == 'kucing' ? 'selected' : ''; ?>>Kucing</option>
                                <option value="anjing" <?php echo $dokter['spesialisasi'] == 'anjing' ? 'selected' : ''; ?>>Anjing</option>
                                <option value="exotic" <?php echo $dokter['spesialisasi'] == 'exotic' ? 'selected' : ''; ?>>Hewan Exotic</option>
                                <option value="bedah" <?php echo $dokter['spesialisasi'] == 'bedah' ? 'selected' : ''; ?>>Bedah</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pengalaman (tahun)</label>
                            <input type="number" name="pengalaman" value="<?php echo $dokter['pengalaman'] ?? 0; ?>" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File SIP</label>
                            <div class="file-upload-area" id="sipUploadArea">
                                <input type="file" name="file_sip" id="file_sip" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                                <label for="file_sip" class="cursor-pointer block">
                                    <div class="text-3xl mb-2">📄</div>
                                    <div class="text-sm text-gray-600" id="sipFileName">
                                        <?php echo !empty($dokter['file_sip']) ? 'File saat ini: ' . $dokter['file_sip'] : 'Klik untuk upload file SIP'; ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File STRV</label>
                            <div class="file-upload-area" id="strvUploadArea">
                                <input type="file" name="file_strv" id="file_strv" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                                <label for="file_strv" class="cursor-pointer block">
                                    <div class="text-3xl mb-2">📄</div>
                                    <div class="text-sm text-gray-600" id="strvFileName">
                                        <?php echo !empty($dokter['file_strv']) ? 'File saat ini: ' . $dokter['file_strv'] : 'Klik untuk upload file STRV'; ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" name="update_profile" class="bg-primary text-white py-2 px-6 rounded-lg hover:bg-secondary transition-colors">
                            💾 Simpan Perubahan
                        </button>
                        <button type="button" onclick="cancelEdit()" class="bg-gray-400 text-white py-2 px-6 rounded-lg hover:bg-gray-500 transition-colors">
                            ❌ Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Other Tabs Content (Hidden by default) -->
        <div class="lg:col-span-3 hidden" id="content-jadwal">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">📅 Jadwal Praktik</h3>
                <p class="text-gray-600">Jadwal praktik akan ditampilkan di sini...</p>
            </div>
        </div>

        <div class="lg:col-span-3 hidden" id="content-artikel">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">📝 Artikel Saya</h3>

                <?php if (empty($artikel_list)): ?>
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📝</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Artikel</h4>
                        <p class="text-gray-600 mb-6">Anda belum menulis artikel apapun.</p>
                        <a href="<?php echo BASE_URL; ?>pages/tambah-artikel.php" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition-colors">
                            Tulis Artikel Pertama
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid gap-6">
                        <?php foreach ($artikel_list as $artikel): ?>
                            <div class="bg-gray-50 rounded-xl p-6 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-3">
                                            <h4 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($artikel['judul']); ?></h4>
                                            <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo $artikel['status'] == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                <?php echo ucfirst($artikel['status']); ?>
                                            </span>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                            <span class="flex items-center gap-1">
                                                📅 <?php echo formatTanggal($artikel['created_at']); ?>
                                            </span>
                                            <span class="bg-gray-200 px-3 py-1 rounded-full">
                                                <?php echo ucfirst($artikel['kategori']); ?>
                                            </span>
                                            <?php if ($artikel['status'] == 'published'): ?>
                                                <span class="flex items-center gap-1">
                                                    👁️ <?php echo $artikel['views']; ?> views
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="text-gray-700 mb-4 line-clamp-2">
                                            <?php
                                            $content = strip_tags($artikel['konten']);
                                            echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                                            ?>
                                        </div>
                                    </div>

                                    <div class="flex gap-3 mt-4 lg:mt-0 lg:ml-6">
                                        <a href="<?php echo BASE_URL; ?>artikel/<?php echo $artikel['slug']; ?>" target="_blank"
                                           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors flex items-center gap-2">
                                            <span>👁️</span>
                                            Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="<?php echo BASE_URL; ?>pages/kelola-artikel.php" class="text-primary hover:text-secondary font-medium">
                            Kelola Semua Artikel →
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="lg:col-span-3 hidden" id="content-riwayat">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">📊 Riwayat Aktivitas</h3>
                <p class="text-gray-600">Riwayat aktivitas akan ditampilkan di sini...</p>
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
                <button type="button" onclick="sendVerificationCode()" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                    📤 Kirim Kode
                </button>
                <button type="submit" class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                    ✅ Verifikasi
                </button>
            </div>
            <button type="button" onclick="closeEmailVerificationModal()" class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
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
                <button type="button" onclick="sendPasswordVerificationCode()" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                    📤 Kirim Kode
                </button>
                <button type="button" onclick="closeChangePasswordModal()" class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
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
                <button type="button" onclick="verifyPasswordCode()" class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                    ✅ Verifikasi
                </button>
                <button type="button" onclick="backToPasswordStep1()" class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="confirmPassword" placeholder="Konfirmasi password baru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-primary text-white py-2 px-4 rounded-lg hover:bg-secondary transition-colors">
                        💾 Simpan Password
                    </button>
                    <button type="button" onclick="backToPasswordStep2()" class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition-colors">
                        Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle Edit Mode - Hanya toggle tanpa ubah tombol
    function toggleEditMode() {
        const readonlyView = document.getElementById('readonlyView');
        const editForm = document.getElementById('editForm');
        const editButton = document.getElementById('editButton');
        
        readonlyView.classList.add('hidden');
        editForm.classList.remove('hidden');
        editButton.style.display = 'none'; // Sembunyikan tombol edit
    }

    // Cancel Edit - Kembali ke readonly dan tampilkan tombol edit
    function cancelEdit() {
        const readonlyView = document.getElementById('readonlyView');
        const editForm = document.getElementById('editForm');
        const editButton = document.getElementById('editButton');
        
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
        const contents = ['data-diri', 'artikel', 'riwayat'];
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

    document.getElementById('emailVerificationForm').addEventListener('submit', function(e) {
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

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
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
    window.onclick = function(event) {
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
    document.getElementById('file_sip').addEventListener('change', function(e) {
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

    document.getElementById('file_strv').addEventListener('change', function(e) {
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
    document.getElementById('foto_profil').addEventListener('change', function(e) {
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

<?php require_once __DIR__ . '/../footer-dokter.php'; ?>