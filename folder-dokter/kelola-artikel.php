<?php
/**
 * File: pages/kelola-artikel.php
 * Halaman kelola artikel untuk dokter
 */

$pageTitle = "Kelola Artikel - VetCare";
require_once __DIR__ . '/../header-dokter.php';
require_once __DIR__ . '/../includes/DAO_artikel.php';

// Require login
requireLogin();

$database = new Database();
$db = $database->getConnection();
$daoArtikel = new DAO_Artikel($db);

// Handle delete artikel
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id_artikel = clean($_GET['delete']);

    // Check if artikel belongs to current dokter
    $artikel = $daoArtikel->getById($id_artikel);
    if ($artikel && $artikel['id_dokter'] == $currentDokter['id_dokter']) {
        if ($daoArtikel->delete($id_artikel)) {
            setFlash('success', 'Artikel berhasil dihapus!');
        } else {
            setFlash('error', 'Gagal menghapus artikel!');
        }
    } else {
        setFlash('error', 'Artikel tidak ditemukan atau Anda tidak memiliki akses!');
    }

    header('Location: ' . BASE_URL . 'pages/kelola-artikel.php');
    exit();
}

// Get filter
$filter = isset($_GET['filter']) ? clean($_GET['filter']) : 'all';

// Get artikel by dokter
if ($filter == 'draft') {
    $artikel_list = $daoArtikel->getByDokter($currentDokter['id_dokter'], null, 0);
    $artikel_list = array_filter($artikel_list, function($artikel) {
        return $artikel['status'] == 'draft';
    });
} elseif ($filter == 'published') {
    $artikel_list = $daoArtikel->getByDokter($currentDokter['id_dokter'], null, 0);
    $artikel_list = array_filter($artikel_list, function($artikel) {
        return $artikel['status'] == 'published';
    });
} else {
    $artikel_list = $daoArtikel->getByDokter($currentDokter['id_dokter']);
}
?>



    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Kelola Artikel</h1>
                    <p class="text-gray-600">Kelola semua artikel yang telah Anda buat</p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-col sm:flex-row gap-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" onchange="filterArtikel(this.value)">
                        <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>Semua Artikel</option>
                        <option value="draft" <?php echo $filter == 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $filter == 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                    <a href="<?php echo BASE_URL; ?>pages/tambah-artikel.php" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition-colors flex items-center gap-2">
                        <span>✏️</span>
                        Tulis Artikel Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Articles List -->
        <?php if (empty($artikel_list)): ?>
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Artikel</h3>
                <p class="text-gray-600 mb-6">Mulai tulis artikel pertama Anda untuk berbagi pengetahuan</p>
                <a href="<?php echo BASE_URL; ?>pages/tambah-artikel.php" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition-colors">
                    Tulis Artikel Pertama
                </a>
            </div>
        <?php else: ?>
            <div class="grid gap-6">
                <?php foreach ($artikel_list as $artikel): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($artikel['judul']); ?></h3>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo $artikel['status'] == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo ucfirst($artikel['status']); ?>
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                    <span class="flex items-center gap-1">
                                        📅 <?php echo formatTanggal($artikel['created_at']); ?>
                                    </span>
                                    <span class="bg-gray-100 px-3 py-1 rounded-full">
                                        <?php echo ucfirst($artikel['kategori']); ?>
                                    </span>
                                    <?php if ($artikel['status'] == 'published'): ?>
                                        <span class="flex items-center gap-1">
                                            👁️ <?php echo $artikel['views']; ?> views
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="text-gray-700 mb-4 line-clamp-3">
                                    <?php
                                    $content = strip_tags($artikel['konten']);
                                    echo strlen($content) > 200 ? substr($content, 0, 200) . '...' : $content;
                                    ?>
                                </div>
                            </div>

                            <div class="flex gap-3 mt-4 lg:mt-0 lg:ml-6">
                                <a href="<?php echo BASE_URL; ?>artikel/<?php echo $artikel['slug']; ?>" target="_blank"
                                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                                    <span>👁️</span>
                                    Lihat
                                </a>
                                <a href="<?php echo BASE_URL; ?>pages/edit-artikel.php?id=<?php echo $artikel['id_artikel']; ?>"
                                   class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors flex items-center gap-2">
                                    <span>✏️</span>
                                    Edit
                                </a>
                                <button onclick="showDeleteModal(<?php echo $artikel['id_artikel']; ?>, '<?php echo htmlspecialchars($artikel['judul'], ENT_QUOTES); ?>')"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors flex items-center gap-2">
                                    <span>🗑️</span>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>


    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="text-4xl mb-4">🗑️</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Hapus Artikel</h3>
                <p class="text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus artikel "<span id="deleteArticleTitle" class="font-medium"></span>"?
                </p>
                <p class="text-red-600 text-sm mb-6">Tindakan ini tidak dapat dibatalkan.</p>

                <div class="flex gap-4">
                    <button onclick="closeDeleteModal()" class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <a id="deleteLink" href="#" class="flex-1 bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-colors text-center">
                        Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function filterArtikel(filter) {
            window.location.href = '<?php echo BASE_URL; ?>pages/kelola-artikel.php?filter=' + filter;
        }

        function showDeleteModal(id, judul) {
            document.getElementById('deleteArticleTitle').textContent = judul;
            document.getElementById('deleteLink').href = '<?php echo BASE_URL; ?>pages/kelola-artikel.php?delete=' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>

<?php require_once __DIR__ . '/../footer-dokter.php'; ?>
