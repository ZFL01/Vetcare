<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'Admin') {
    header('Location: login.php');
    exit;
}

// Handle logout
if (isset($_POST['admin-logout'])) {
    session_destroy();
    // Redirect to member homepage (go up one directory from /admin/)
    header('Location: ../');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin-add_category'])) {
    $new_name = trim($_POST['name']);

    if (!empty($new_name)) {
        // Use existing DAO method to add category
        $newKategori = new DTO_kateg(0, $new_name);
        DAO_kategori::newKategori($newKategori);

        header('Location: admin_direct.php?tab=categories');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin-delete_category'])) {
    $id_del = (int) $_POST['admin-delete_category'];

    try {
        // Use existing DAO method to delete category
        $kategoriToDelete = new DTO_kateg($id_del, '');
        DAO_kategori::delKateg($kategoriToDelete);

        header('Location: admin_direct.php?tab=categories');
        exit;
    } catch (Exception $e) {
        die("Gagal menghapus kategori. Pastikan kategori tidak sedang digunakan oleh data dokter. Error: " . $e->getMessage());
    }
}

// Handle approve/reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin-action'])) {
    $id_dokter = (int) ($_POST['id_dokter'] ?? 0);
    $action = $_POST['admin-action'];

    try {
        if ($action === 'approve') {
            $no_strv = $_POST['no_strv'] ?? '';
            $no_sip = $_POST['no_sip'] ?? '';
            $exp_strv = $_POST['exp_strv'] ?? '';
            $exp_sip = $_POST['exp_sip'] ?? '';

            $dokterData = new DTO_dokter($id_dokter, '', '');
            $dokterData->setDoc($no_sip, $exp_sip, $no_strv, $exp_strv);

            DAO_dokter::updateDokter($id_dokter, $dokterData, 'aktif', true);

        } elseif ($action === 'reject') {
            $dokterData = new DTO_dokter($id_dokter, '', '');

            DAO_dokter::updateDokter($id_dokter, $dokterData, 'pending', true);
        }

        $_SESSION['success'] = $action === 'approve' ? 'Dokter berhasil disetujui!' : 'Dokter berhasil ditolak!';
        header('Location: admin_direct.php?tab=authentication');
        exit;
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

// Load doctor data AFTER all POST processing to get fresh data
$vets = DAO_dokter::tabelAdmin();

// Calculate stats
$stats['total'] = count($vets);
$stats['pending'] = 0;
$stats['approved'] = 0;
$pendingVets = [];
$activeVets = [];
foreach ($vets as $vet) {
    $status = $vet->getStatus();

    // Check for both 'pending' and 'nonaktif' as pending status
    $isPending = ($status == 'pending' || $status == 'nonaktif');
    $isActive = ($status == 'aktif');

    $stats['pending'] += $isPending ? 1 : 0;
    $stats['approved'] += $isActive ? 1 : 0;

    if ($isPending) {
        $pendingVets[] = $vet;
    } elseif ($isActive) {
        $activeVets[] = $vet;
    }
}

$activeTab = $_GET['tab'] ?? 'dashboard';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="flex min-h-screen">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="flex-1 md:ml-64">
        <div
            class="bg-gradient-to-r from-purple-600 via-violet-600 to-fuchsia-600 shadow-lg fixed top-0 left-0 right-0 md:left-64 z-40">
            <div class="px-8 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Dashboard Admin VetCare</h1>
                        <p class="text-purple-100 text-sm mt-1">Sistem Manajemen Dokter Hewan</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-white text-sm text-right">
                            <p class="text-purple-100">Logged in as</p>
                            <p class="font-medium"><?= htmlspecialchars($_SESSION['user']->getEmail()) ?></p>
                        </div>
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="admin-logout" value="1"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 pt-24">

            <?php if ($activeTab === 'dashboard'): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Total Dokter</p>
                                <p class="text-4xl font-bold mt-3"><?= $stats['total'] ?></p>
                                <p class="text-purple-100 text-xs mt-2">Terdaftar</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.75c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 10c-3.7 0-7 2-7 4.5v.75c0 .6.4 1 1 1h12c.6 0 1-.4 1-1v-.75c0-2.5-3.3-4.5-7-4.5z" />
                                </svg>


                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-xl p-6 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-amber-100 text-sm font-medium">Menunggu Verifikasi</p>
                                <p class="text-4xl font-bold mt-3"><?= $stats['pending'] ?></p>
                                <p class="text-amber-100 text-xs mt-2">Review Pending</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Aktif</p>
                                <p class="text-4xl font-bold mt-3"><?= $stats['approved'] ?></p>
                                <p class="text-green-100 text-xs mt-2">Diverifikasi</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mb-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2 flex items-center justify-center gap-3">
                            <span class="text-4xl">📊</span>
                            Dashboard Analitik Dokter
                        </h2>
                        <div class="w-24 h-1 bg-gradient-to-r from-purple-500 to-violet-600 mx-auto mt-4 rounded-full">
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="w-full h-[1200px] rounded-xl border-2 border-purple-200 overflow-hidden shadow-lg bg-gray-50">
                            <iframe title="visualisasi_data_all" class="w-full h-full"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYThiZWY5MDQtN2Q5MS00ODYzLTliNTUtZjY1ZmJhM2EwMzlhIiwidCI6ImE2OWUxOWU4LWYwYTQtNGU3Ny1iZmY2LTk1NjRjODgxOWIxNCJ9"
                                frameborder="0" allowFullScreen="true" loading="lazy" style="zoom: 0.8;"></iframe>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-gray-500 text-sm mb-4">
                            Dashboard Power BI menampilkan analisis lengkap sebaran lokasi, kategori Terpopuler, dan
                            statistik kepuasan pelanggan.
                        </p>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Dokter Menunggu Verifikasi</h2>
                        <span
                            class="bg-amber-100 text-amber-800 px-4 py-2 rounded-full font-semibold"><?= $stats['pending'] ?>
                            Pending</span>
                    </div>

                    <?php if ($stats['pending'] > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">👨‍⚕️ Nama Dokter</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Status</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingVets as $vet): ?>
                                        <tr class="border-b border-gray-100 hover:bg-purple-50/50 transition">
                                            <td class="px-6 py-4 text-gray-900 font-medium"><?= htmlspecialchars($vet->getNama()) ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-4 py-2 bg-gradient-to-r from-amber-100 to-orange-100 text-amber-800 text-sm font-bold rounded-full">
                                                    ⏳ <?= htmlspecialchars($vet->getStatus()) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button
                                                    onclick="showModal(<?= $vet->getId() ?>, '<?= htmlspecialchars($vet->getNama()) ?>', '<?= htmlspecialchars($vet->getSIP() ?? '') ?>', '<?= htmlspecialchars($vet->getSTRV() ?? '') ?>', '<?= htmlspecialchars($vet->getExp_SIP() ?? '') ?>', '<?= htmlspecialchars($vet->getExp_STRV() ?? '') ?>')"
                                                    class="px-4 py-2 bg-gradient-to-r from-purple-500 to-violet-600 text-white rounded-lg hover:from-purple-600 hover:to-violet-700 font-medium transition shadow-md hover:shadow-lg">
                                                    Lihat Detail →
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-600 text-lg">Semua dokter sudah diverifikasi!</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($activeTab === 'authentication'): ?>
                <div class="space-y-6">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8">
                        <div class="mb-8 pb-6 border-b-2 border-purple-200">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">📋 Daftar Dokter</h2>
                            <p class="text-gray-600">Total: <span
                                    class="font-bold text-purple-600"><?= count($vets) ?></span> dokter | Pending: <span
                                    class="font-bold text-amber-600"><?= $stats['pending'] ?></span></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($vets as $vet): ?>
                                <div
                                    class="bg-gradient-to-br from-white to-purple-50 border-2 border-purple-200 rounded-xl p-6 hover:shadow-2xl hover:scale-105 transition transform duration-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900"><?= htmlspecialchars($vet->getNama()) ?>
                                            </h3>
                                            <p class="text-xs text-gray-500">ID: <?= $vet->getId() ?></p>
                                        </div>
                                        <span
                                            class="px-3 py-1 bg-<?= $vet->getStatus() === 'aktif' ? 'green' : 'amber' ?>-100 text-<?= $vet->getStatus() === 'aktif' ? 'green' : 'amber' ?>-800 text-xs font-bold rounded-full">
                                            <?= $vet->getStatus() === 'aktif' ? '✓ Aktif' : '⏳ Pending' ?>
                                        </span>
                                    </div>

                                    <div class="space-y-2 mb-5 pb-5 border-b border-purple-200">
                                        <div class="text-sm">
                                            <span class="font-semibold text-gray-600">SIP:</span>
                                            <span
                                                class="text-gray-900 font-mono text-xs bg-gray-100 px-2 py-1 rounded ml-1"><?= $vet->getSIP() ?: 'N/A' ?></span>
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-semibold text-gray-600">STRV:</span>
                                            <span
                                                class="text-gray-900 font-mono text-xs bg-gray-100 px-2 py-1 rounded ml-1"><?= $vet->getSTRV() ?: 'N/A' ?></span>
                                        </div>
                                    </div>

                                    <button
                                        onclick="showModal(<?= $vet->getId() ?>, '<?= htmlspecialchars($vet->getNama()) ?>', '<?= htmlspecialchars($vet->getSIP() ?? '') ?>', '<?= htmlspecialchars($vet->getSTRV() ?? '') ?>', '<?= htmlspecialchars($vet->getExp_SIP() ?? '') ?>', '<?= htmlspecialchars($vet->getExp_STRV() ?? '') ?>')"
                                        class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-violet-600 text-white rounded-lg hover:from-purple-600 hover:to-violet-700 font-semibold transition shadow-md hover:shadow-lg text-sm">
                                        🔍 Verifikasi
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            </main>

            <div id="doctorModal"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 overflow-y-auto">
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full animate-slideUp my-4">
                    <div
                        class="bg-gradient-to-r from-purple-500 to-violet-600 p-4 flex items-center justify-between rounded-t-2xl">
                        <div>
                            <h3 class="text-xl font-bold text-white">🔍 Verifikasi Dokter</h3>
                            <p class="text-purple-100 text-xs mt-0.5">Periksa dan verifikasi dokumen</p>
                        </div>
                        <button type="button" onclick="closeModal()"
                            class="text-white hover:text-purple-200 transition flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6">
                        <form method="POST">
                            <input type="hidden" id="doctorId" name="id_dokter">

                            <div class="mb-5 pb-5 border-b-2 border-gray-200">
                                <label
                                    class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">👨‍⚕️
                                    Nama Dokter</label>
                                <input type="text" id="doctorName" disabled
                                    class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg bg-gray-100 text-gray-600 font-semibold text-sm">
                            </div>

                            <div class="mb-5 pb-5 border-b-2 border-gray-200">
                                <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                                    <span
                                        class="bg-gradient-to-r from-purple-500 to-violet-600 text-white px-2 py-0.5 rounded-lg font-bold text-xs">SIP</span>
                                    <span class="text-gray-600 text-xs">Surat Izin Praktik</span>
                                </h4>

                                <div class="mb-3 p-3 bg-purple-50 border-2 border-purple-300 rounded-lg">
                                    <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">📎 File</p>
                                    <div id="sipFileDisplay" class="text-xs text-purple-600 font-semibold">
                                        <span class="text-gray-400">Mengambil...</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nomor
                                            SIP</label>
                                        <input type="text" name="no_sip" id="noSip" placeholder="Misal: 123/SIP/2023"
                                            required
                                            class="w-full px-3 py-2 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-xs font-mono">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Berlaku
                                            Hingga</label>
                                        <input type="date" name="exp_sip" id="expSip" required
                                            class="w-full px-3 py-2 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-xs">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                                    <span
                                        class="bg-gradient-to-r from-violet-500 to-fuchsia-600 text-white px-2 py-0.5 rounded-lg font-bold text-xs">STRV</span>
                                    <span class="text-gray-600 text-xs">Surat Tanda Registrasi Veteriner</span>
                                </h4>

                                <div class="mb-3 p-3 bg-violet-50 border-2 border-violet-300 rounded-lg">
                                    <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">📎 File</p>
                                    <div id="strvFileDisplay" class="text-xs text-violet-600 font-semibold">
                                        <span class="text-gray-400">Mengambil...</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nomor
                                            STRV</label>
                                        <input type="text" name="no_strv" id="noStrv" placeholder="Misal: 789/STRV/2023"
                                            required
                                            class="w-full px-3 py-2 border-2 border-violet-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent text-xs font-mono">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Berlaku
                                            Hingga</label>
                                        <input type="date" name="exp_strv" id="expStrv" required
                                            class="w-full px-3 py-2 border-2 border-violet-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent text-xs">
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3 mt-6 pt-5 border-t-2 border-gray-200">
                                <button type="submit" name="admin-action" value="approve"
                                    class="flex-1 px-3 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition font-bold shadow-md hover:shadow-lg text-sm">
                                    ✓ Setujui & Simpan
                                </button>
                                <button type="submit" name="admin-action" value="reject"
                                    class="flex-1 px-3 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:from-red-600 hover:to-rose-700 transition font-bold shadow-md hover:shadow-lg text-sm">
                                    ✕ Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if ($activeTab === 'categories'):
                $categories = DAO_kategori::getAllKategori(); ?>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8">
                    <div class="flex justify-between items-center mb-8 pb-6 border-b-2 border-purple-200">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">🏥 Kategori Spesialisasi</h2>
                            <p class="text-gray-600">Kelola kategori spesialisasi dokter hewan</p>
                        </div>
                        <button onclick="openAddModal()"
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-bold transition shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Tambah Kategori
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($categories as $cat):
                            ?>
                            <div
                                class="group bg-gradient-to-br from-white to-purple-50 border-2 border-purple-200 rounded-2xl p-6 hover:shadow-2xl hover:scale-105 transition transform duration-300">
                                <div class="mb-4">
                                    <h3 class="font-bold text-xl text-gray-900 mb-2">
                                        <?= htmlspecialchars($cat->getNamaKateg()) ?>
                                    </h3>
                                    <p class="text-sm text-gray-600 leading-relaxed">Spesialisasi dokter hewan dalam bidang
                                        <?= strtolower(htmlspecialchars($cat->getNamaKateg())) ?>
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-purple-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-gray-500 text-xs">ID: <?= $cat->getIdK() ?></span>
                                        <span
                                            class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-semibold text-xs">Aktif</span>
                                    </div>
                                    <button
                                        onclick="confirmDelete(<?= $cat->getIdK() ?>, '<?= htmlspecialchars($cat->getNamaKateg(), ENT_QUOTES) ?>')"
                                        class="w-full px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-lg font-semibold transition text-sm flex items-center justify-center gap-2 border border-red-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($categories)): ?>
                            <div class="col-span-full text-center py-16">
                                <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Kategori</h3>
                                <p class="text-gray-600 mb-6">Tambahkan kategori spesialisasi pertama Anda</p>
                                <button onclick="openAddModal()"
                                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-violet-600 text-white rounded-xl font-bold hover:from-purple-600 hover:to-violet-700 transition shadow-lg inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Kategori
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="addCategoryModal"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full animate-slideUp">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-t-2xl">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-2xl font-bold text-white">➕ Tambah Kategori Baru</h3>
                                    <p class="text-blue-100 text-sm mt-1">Tambahkan spesialisasi dokter hewan</p>
                                </div>
                                <button type="button" onclick="closeAddModal()"
                                    class="text-white hover:text-blue-200 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <form method="POST" class="p-6">
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    🏥 Nama Kategori
                                </label>
                                <input type="text" name="name" placeholder="Contoh: Sapi, Reptil, dll" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 placeholder-gray-400">
                                <p class="text-xs text-gray-500 mt-2">Masukkan nama spesialisasi dokter hewan</p>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" onclick="closeAddModal()"
                                    class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold transition">
                                    Batal
                                </button>
                                <button type="submit" name="admin-add_category" value="1"
                                    class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-bold transition shadow-lg">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    function openAddModal() {
                        document.getElementById('addCategoryModal').style.display = 'flex';
                    }

                    function closeAddModal() {
                        document.getElementById('addCategoryModal').style.display = 'none';
                    }

                    function confirmDelete(id, name) {
                        if (confirm(`Apakah Anda yakin ingin menghapus kategori "${name}"?\n\nPeringatan: Kategori yang sudah digunakan oleh dokter mungkin akan menyebabkan error!`)) {
                            // Create form and submit
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '';

                            const inputId = document.createElement('input');
                            inputId.type = 'hidden';
                            inputId.name = 'admin-delete_category';
                            inputId.value = id;

                            form.appendChild(inputId);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }

                    // Close modal when clicking outside
                    window.addEventListener('click', function (event) {
                        const modal = document.getElementById('addCategoryModal');
                        if (event.target === modal) {
                            closeAddModal();
                        }
                    });
                </script>
            <?php endif; ?>
            <script>
                function showModal(id, name, sip, strv, expSip, expStrv) {
                    document.getElementById('doctorId').value = id;
                    document.getElementById('doctorName').value = name;
                    document.getElementById('noSip').value = sip;
                    document.getElementById('noStrv').value = strv;
                    document.getElementById('expSip').value = expSip;
                    document.getElementById('expStrv').value = expStrv;

                    const pathsip = '../public/docs/sip/';
                    const pathstrv = '../public/docs/strv/';

                    // Fetch file paths from server
                    fetch('get_doctor_files.php?doctor_id=' + id)
                        .then(response => response.json())
                        .then(data => {
                            if (data.path_sip) {
                                document.getElementById('sipFileDisplay').innerHTML = '<a href="' + pathsip + data.path_sip + '" target="_blank" class="hover:underline">📎 ' + data.path_sip.split('/').pop() + '</a>';
                            } else {
                                document.getElementById('sipFileDisplay').innerHTML = '<span class="text-gray-400">Belum ada file SIP</span>';
                            }

                            if (data.path_strv) {
                                document.getElementById('strvFileDisplay').innerHTML = '<a href="' + pathstrv + data.path_strv + '" target="_blank" class="hover:underline">📎 ' + data.path_strv.split('/').pop() + '</a>';
                            } else {
                                document.getElementById('strvFileDisplay').innerHTML = '<span class="text-gray-400">Belum ada file STRV</span>';
                            }
                        });

                    document.getElementById('doctorModal').style.display = 'flex';
                }

                function closeModal() {
                    document.getElementById('doctorModal').style.display = 'none';
                }

                window.onclick = function (event) {
                    const modal = document.getElementById('doctorModal');
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                }
            </script>

            <?php include __DIR__ . '/includes/footer.php'; ?>