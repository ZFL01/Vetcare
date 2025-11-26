<?php
require_once __DIR__ . '/includes/data.php';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="flex min-h-screen">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="flex-1">
        <!-- HEADER TOPBAR -->
        <div class="bg-gradient-to-r from-purple-600 via-violet-600 to-fuchsia-600 shadow-lg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Dashboard Admin VetCare</h1>
                        <p class="text-purple-100 text-sm mt-1">Sistem Manajemen Dokter Hewan</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="p-2 bg-white/20 backdrop-blur-sm rounded-lg hover:bg-white/30 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </button>
                        <button class="p-2 bg-white/20 backdrop-blur-sm rounded-lg hover:bg-white/30 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="p-8">
            <?php if ($activeTab === 'dashboard'): ?>
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                        <p class="text-white/80 text-sm font-medium">Total Dokter</p>
                        <p class="text-4xl font-bold mt-2"><?= $stats['total'] ?></p>
                        <p class="text-white/80 text-xs mt-2">Terdaftar</p>
                    </div>
                    <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl shadow-xl p-6 text-white">
                        <p class="text-white/80 text-sm font-medium">Pending</p>
                        <p class="text-4xl font-bold mt-2"><?= $stats['pending'] ?></p>
                        <p class="text-white/80 text-xs mt-2">Menunggu Review</p>
                    </div>
                    <div class="bg-gradient-to-br from-fuchsia-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white">
                        <p class="text-white/80 text-sm font-medium">Disetujui</p>
                        <p class="text-4xl font-bold mt-2"><?= $stats['approved'] ?></p>
                        <p class="text-white/80 text-xs mt-2">Aktif</p>
                    </div>
                    <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl shadow-xl p-6 text-white">
                        <p class="text-white/80 text-sm font-medium">Ditolak</p>
                        <p class="text-4xl font-bold mt-2"><?= $stats['rejected'] ?></p>
                        <p class="text-white/80 text-xs mt-2">Tidak Lolos</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl p-6 border border-white/20">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Peta Sebaran Dokter</h3>
                        <div class="relative h-80 bg-gradient-to-br from-purple-100 via-violet-100 to-fuchsia-100 rounded-xl overflow-hidden shadow-inner flex items-center justify-center">
                            <div class="text-center">
                                <div class="bg-gradient-to-r from-purple-500 to-violet-500 p-4 rounded-full mx-auto mb-4 shadow-xl">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <p class="text-gray-700 font-bold text-lg">Indonesia</p>
                                <p class="text-gray-600 mt-2"><?= $stats['total'] ?> Lokasi Terdaftar</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl p-6 border border-white/20">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Status Pendaftaran</h3>
                        <div class="space-y-4 mt-4">
                            <?php
                            $total = $stats['total'] > 0 ? $stats['total'] : 1;
                            $percApproved = round(($stats['approved'] / $total) * 100);
                            $percPending = round(($stats['pending'] / $total) * 100);
                            $percRejected = round(($stats['rejected'] / $total) * 100);
                            ?>
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Disetujui</span>
                                    <span class="text-sm font-bold text-fuchsia-600"><?= $stats['approved'] ?> (<?= $percApproved ?>%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-fuchsia-500 to-pink-600 h-3 rounded-full" style="width: <?= $percApproved ?>%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Pending</span>
                                    <span class="text-sm font-bold text-violet-600"><?= $stats['pending'] ?> (<?= $percPending ?>%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-violet-500 to-purple-600 h-3 rounded-full" style="width: <?= $percPending ?>%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Ditolak</span>
                                    <span class="text-sm font-bold text-rose-600"><?= $stats['rejected'] ?> (<?= $percRejected ?>%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-rose-500 to-red-600 h-3 rounded-full" style="width: <?= $percRejected ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif ($activeTab === 'authentication'): ?>
                <?php
                $viewDetail = isset($_GET['vet_id']);
                if ($viewDetail):
                    include __DIR__ . '/includes/authentication_detail.php';
                else:
                ?>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Menunggu Approval (<?= count($pendingVets) ?>)</h3>
                        <p class="text-sm text-gray-600 mt-1"><?= count($pendingVets) ?> dokter menunggu review</p>
                    </div>
                    
                    <!-- List Pending Vets -->
                    <div class="space-y-4">
                        <?php foreach ($pendingVets as $vet): ?>
                            <a href="?tab=authentication&vet_id=<?= $vet['id'] ?>" class="block p-4 rounded-lg border border-gray-200 hover:border-purple-500 hover:shadow-lg transition bg-gray-50 hover:bg-purple-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="font-bold text-lg text-gray-900"><?= htmlspecialchars($vet['name']) ?></div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            <svg class="w-4 h-4 inline text-purple-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                                            <?= htmlspecialchars($vet['email']) ?>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2">Terdaftar: <?= $vet['registrationDate'] ?></div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Pending</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($pendingVets) === 0): ?>
                        <div class="text-center py-12">
                            <p class="text-gray-500">Tidak ada dokter yang menunggu approval</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            <?php elseif ($activeTab === 'categories'): ?>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                    <h3 class="text-xl font-bold mb-4">Kelola Kategori</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($categories as $cat): ?>
                            <div class="p-4 rounded-lg border">
                                <div class="text-2xl"><?= $cat['icon'] ?></div>
                                <div class="font-medium mt-2"><?= htmlspecialchars($cat['name']) ?></div>
                                <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($cat['description']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
