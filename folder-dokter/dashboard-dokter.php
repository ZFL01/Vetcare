<?php
/**
 * File: pages/dashboard-dokter.php
 * Dashboard utama dokter
 */

$pageTitle = "Dashboard - VetCare";
require_once __DIR__ . '/../header-dokter.php';
// require_once __DIR__ . '/../includes/DAO_pertanyaan.php';
// require_once __DIR__ . '/../includes/DAO_artikel.php';

// Require login
requireLogin();

$database = new Database();
$db = $database->getConnection();
// $daoPertanyaan = new DAO_Pertanyaan($db);
$daoArtikel = new DAO_Artikel($db);

// Get statistics
// $totalPertanyaan = count($daoPertanyaan->getAll());
// $pertanyaanBaru = count($daoPertanyaan->getByStatus('baru'));
$artikelPublished = count(array_filter($daoArtikel->getByDokter($currentDokter['id_dokter']), function($artikel) {
    return $artikel['status'] == 'published';
}));

// Get recent questions
// $recentQuestions = array_slice($daoPertanyaan->getAll(), 0, 5);

// Get recent articles
// $recentArticles = array_slice($daoArtikel->getByDokter($currentDokter['id_dokter']), 0, 3);
?>


    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-xl p-8 mb-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-2">Selamat Datang, Dr. <?php echo htmlspecialchars(explode(' ', $currentDokter['nama_lengkap'])[0]); ?>! 👋</h1>
                <p class="text-lg opacity-90">Semoga hari Anda penuh dengan kesehatan dan kebahagiaan</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-4xl mb-2">📋</div>
                <div class="text-3xl font-bold text-primary mb-1"><?php echo $totalPertanyaan; ?></div>
                <div class="text-gray-600">Total Pertanyaan</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-4xl mb-2">🔔</div>
                <div class="text-3xl font-bold text-primary mb-1"><?php echo $pertanyaanBaru; ?></div>
                <div class="text-gray-600">Pertanyaan Baru</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-4xl mb-2">📝</div>
                <div class="text-3xl font-bold text-primary mb-1"><?php echo $artikelPublished; ?></div>
                <div class="text-gray-600">Artikel Published</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-4xl mb-2">⭐</div>
                <div class="text-3xl font-bold text-primary mb-1">4.8</div>
                <div class="text-gray-600">Rating Rata-rata</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Questions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        📋 Pertanyaan Terbaru
                    </h3>

                    <?php if (empty($recentQuestions)): ?>
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📭</div>
                            <p class="text-gray-500">Belum ada pertanyaan masuk</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recentQuestions as $question): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($question['judul']); ?></h4>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium <?php
                                            if ($question['status'] == 'baru') echo 'bg-red-100 text-red-800';
                                            elseif ($question['status'] == 'dijawab') echo 'bg-green-100 text-green-800';
                                            else echo 'bg-gray-100 text-gray-800';
                                        ?>">
                                            <?php echo ucfirst($question['status']); ?>
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-600 mb-3">
                                        👤 <?php echo htmlspecialchars($question['nama']); ?> • 📅 <?php echo timeAgo($question['created_at']); ?> • <?php echo ucfirst($question['kategori']); ?>
                                    </div>

                                    <div class="text-gray-700 mb-4">
                                        <?php
                                        $content = htmlspecialchars($question['isi']);
                                        echo strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content;
                                        ?>
                                    </div>

                                    <a href="<?php echo BASE_URL; ?>pages/detail-pertanyaan.php?id=<?php echo $question['id_pertanyaan']; ?>"
                                       class="inline-flex items-center gap-2 text-primary hover:text-secondary transition-colors">
                                        Lihat Detail →
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-6">
                            <a href="<?php echo BASE_URL; ?>pages/tanya-dokter.php"
                               class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                Lihat Semua Pertanyaan →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Articles -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        📝 Artikel Terbaru
                    </h3>

                    <?php if (empty($recentArticles)): ?>
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📝</div>
                            <p class="text-gray-500">Belum ada artikel yang dibuat</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recentArticles as $artikel): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                                    <h4 class="font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($artikel['judul']); ?></h4>
                                    <div class="text-sm text-gray-600 mb-3">
                                        📅 <?php echo formatTanggal($artikel['created_at']); ?> • <?php echo ucfirst($artikel['status']); ?> • <?php echo ucfirst($artikel['kategori']); ?>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span>👁️ <?php echo $artikel['views'] ?? 0; ?> views</span>
                                    </div>
                                    <a href="<?php echo BASE_URL; ?>artikel/<?php echo $artikel['slug']; ?>" target="_blank"
                                       class="inline-flex items-center gap-2 text-primary hover:text-secondary transition-colors mt-3">
                                        Lihat Artikel →
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-6">
                            <a href="<?php echo BASE_URL; ?>pages/kelola-artikel.php"
                               class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                Kelola Artikel →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        ⚡ Aksi Cepat
                    </h3>

                    <div class="space-y-4">
                        <a href="<?php echo BASE_URL; ?>pages/tanya-dokter.php?filter=new"
                           class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <div class="text-2xl">💬</div>
                            <div>
                                <h4 class="font-medium">Jawab Pertanyaan</h4>
                                <p class="text-sm opacity-75"><?php echo $pertanyaanBaru; ?> pertanyaan menunggu</p>
                            </div>
                        </a>

                        <a href="<?php echo BASE_URL; ?>pages/tambah-artikel.php"
                           class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <div class="text-2xl">✏️</div>
                            <div>
                                <h4 class="font-medium">Tulis Artikel</h4>
                                <p class="text-sm opacity-75">Bagikan pengetahuan Anda</p>
                            </div>
                        </a>

                        <a href="<?php echo BASE_URL; ?>pages/chat-pasien.php"
                           class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <div class="text-2xl">💭</div>
                            <div>
                                <h4 class="font-medium">Chat Pasien</h4>
                                <p class="text-sm opacity-75">Komunikasi langsung</p>
                            </div>
                        </a>

                        <a href="<?php echo BASE_URL; ?>pages/profile-dokter.php"
                           class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <div class="text-2xl">👤</div>
                            <div>
                                <h4 class="font-medium">Edit Profil</h4>
                                <p class="text-sm opacity-75">Perbarui informasi Anda</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require_once __DIR__ . '/../footer-dokter.php'; ?>
