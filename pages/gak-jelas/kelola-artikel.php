<?php
/**
 * File: pages/kelola-artikel.php
 * Halaman kelola artikel untuk dokter
 */

$pageTitle = "Kelola Artikel - VetCare";
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../services/DAO_artikel.php';

// Add Tailwind CDN in head if not already included
if (!defined('TAILWIND_LOADED')) {
    define('TAILWIND_LOADED', true);
    echo '<script src="https://cdn.tailwindcss.com"></script>';
}

// Require login
requireLogin();

$db = Database::getConnection();
$daoArtikel = new DAO_Artikel($db);

// Handle delete artikel
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id_artikel = clean($_GET['delete']);

    // Check if artikel belongs to current dokter
    $artikel = $daoArtikel->getById($id_artikel);
    if ($artikel && isset($currentDokter['id_dokter']) && $artikel['id_dokter'] == $currentDokter['id_dokter']) {
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
if (isset($currentDokter['id_dokter'])) {
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
} else {
    $artikel_list = [];
}
?>

<!-- Main Content -->
<main class="min-h-screen bg-gray-50 py-8 pt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                        <span class="text-4xl">📝</span>
                        Kelola Artikel
                    </h1>
                    <p class="text-gray-600">Kelola semua artikel yang telah Anda buat</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <select class="px-5 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white text-gray-700 font-medium transition-all hover:border-gray-400" 
                            onchange="filterArtikel(this.value)">
                        <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>📚 Semua Artikel</option>
                        <option value="draft" <?php echo $filter == 'draft' ? 'selected' : ''; ?>>📄 Draft</option>
                        <option value="published" <?php echo $filter == 'published' ? 'selected' : ''; ?>>✅ Published</option>
                    </select>
                    
                    <a href="<?php echo BASE_URL; ?>pages/tambah-artikel.php" 
                       class="bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-secondary transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2 font-medium">
                        <span class="text-lg">✏️</span>
                        <span>Tulis Artikel Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Articles List -->
        <?php if (empty($artikel_list)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                <div class="text-8xl mb-6 opacity-50">📝</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Artikel</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Mulai tulis artikel pertama Anda untuk berbagi pengetahuan dan pengalaman dengan komunitas
                </p>
                <a href="<?php echo BASE_URL; ?>pages/tambah-artikel.php" 
                   class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-lg hover:bg-secondary transition-all shadow-md hover:shadow-lg font-medium">
                    <span class="text-lg">✏️</span>
                    <span>Tulis Artikel Pertama</span>
                </a>
            </div>
        <?php else: ?>
            <div class="grid gap-6">
                <?php foreach ($artikel_list as $artikel): ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all duration-300">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900 flex-1 pr-4 hover:text-primary transition-colors">
                                    <?php echo htmlspecialchars($artikel['judul']); ?>
                                </h3>
                                <span class="px-4 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap <?php echo $artikel['status'] == 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                    <?php echo $artikel['status'] == 'published' ? '✅ Published' : '📄 Draft'; ?>
                                </span>
                            </div>

                            <!-- Meta Information -->
                            <div class="flex flex-wrap items-center gap-4 mb-4">
                                <span class="flex items-center gap-2 text-sm text-gray-600">
                                    <span>📅</span>
                                    <span><?php echo formatTanggal($artikel['created_at']); ?></span>
                                </span>
                                
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <?php echo ucfirst($artikel['kategori']); ?>
                                </span>
                                
                                <?php if ($artikel['status'] == 'published'): ?>
                                    <span class="flex items-center gap-2 text-sm text-gray-600">
                                        <span>👁️</span>
                                        <span class="font-medium"><?php echo number_format($artikel['views']); ?></span>
                                        <span>views</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Content Preview -->
                            <div class="text-gray-700 mb-6 line-clamp-2 leading-relaxed">
                                <?php
                                $content = strip_tags($artikel['konten']);
                                echo strlen($content) > 200 ? substr($content, 0, 200) . '...' : $content;
                                ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-100">
                                <a href="<?php echo BASE_URL; ?>artikel/<?php echo $artikel['slug']; ?>" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 transition-all font-medium">
                                    <span>👁️</span>
                                    <span>Lihat</span>
                                </a>
                                
                                <a href="<?php echo BASE_URL; ?>pages/edit-artikel.php?id=<?php echo $artikel['id_artikel']; ?>"
                                   class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-secondary transition-all font-medium shadow-sm hover:shadow">
                                    <span>✏️</span>
                                    <span>Edit</span>
                                </a>
                                
                                <button onclick="showDeleteModal(<?php echo $artikel['id_artikel']; ?>, '<?php echo htmlspecialchars($artikel['judul'], ENT_QUOTES); ?>')"
                                        class="inline-flex items-center gap-2 bg-red-500 text-white px-5 py-2.5 rounded-lg hover:bg-red-600 transition-all font-medium shadow-sm hover:shadow">
                                    <span>🗑️</span>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🗑️</span>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Hapus Artikel</h3>
                
                <p class="text-gray-600 mb-2">
                    Apakah Anda yakin ingin menghapus artikel:
                </p>
                
                <p class="text-gray-900 font-semibold mb-4">
                    "<span id="deleteArticleTitle"></span>"
                </p>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-6">
                    <p class="text-red-700 text-sm font-medium">
                        ⚠️ Tindakan ini tidak dapat dibatalkan
                    </p>
                </div>

                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" 
                            class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium">
                        Batal
                    </button>
                    <a id="deleteLink" 
                       href="#" 
                       class="flex-1 bg-red-500 text-white py-3 px-4 rounded-lg hover:bg-red-600 transition-all text-center font-medium shadow-sm hover:shadow">
                        Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>

<?php require_once __DIR__ . '/../footer-dokter.php'; ?>