<?php

/**
 * File: pages/edit-artikel.php
 * Halaman edit artikel untuk dokter
 */

$pageTitle = "Edit Artikel - VetCare";
require_once __DIR__ . '/../header-dokter.php';
require_once __DIR__ . '/../includes/DAO_artikel.php';

// Require login
requireLogin();

// Get artikel ID
if (!isset($_GET['id'])) {
    setFlash('error', 'ID artikel tidak ditemukan!');
    header('Location: ' . BASE_URL . 'pages/kelola-artikel.php');
    exit();
}

$id_artikel = clean($_GET['id']);

$database = new Database();
$db = $database->getConnection();
$daoArtikel = new DAO_Artikel($db);

// Get artikel
$artikel = $daoArtikel->getById($id_artikel);

if (!$artikel) {
    setFlash('error', 'Artikel tidak ditemukan!');
    header('Location: ' . BASE_URL . 'pages/kelola-artikel.php');
    exit();
}

// Check if artikel belongs to current dokter
if ($artikel['id_dokter'] != $currentDokter['id_dokter']) {
    setFlash('error', 'Anda tidak memiliki akses ke artikel ini!');
    header('Location: ' . BASE_URL . 'pages/kelola-artikel.php');
    exit();
}

// Handle form submission
if (isset($_POST['update_artikel'])) {
    $judul = clean($_POST['judul']);
    $kategori = clean($_POST['kategori']);
    $konten = $_POST['konten']; // Allow HTML
    $status = clean($_POST['status']);

    // Handle file upload
    $gambar = $artikel['gambar']; // Keep existing image by default
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $upload_dir = __DIR__ . '/../public/uploads/artikel/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = 'artikel_' . time() . '_' . uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                // Delete old image if exists
                if ($artikel['gambar'] && file_exists($upload_dir . $artikel['gambar'])) {
                    unlink($upload_dir . $artikel['gambar']);
                }
                $gambar = $new_filename;
            }
        }
    }

    $data = [
        'judul' => $judul,
        'konten' => $konten,
        'kategori' => $kategori,
        'gambar' => $gambar,
        'status' => $status
    ];

    if ($daoArtikel->update($id_artikel, $data)) {
        setFlash('success', 'Artikel berhasil diperbarui!');
        header('Location: ' . BASE_URL . 'pages/kelola-artikel.php');
        exit();
    } else {
        setFlash('error', 'Gagal memperbarui artikel!');
    }
}
?>



<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">✏️ Edit Artikel</h1>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">

            <div>
                <label for="judul" class="block text-lg font-semibold mb-2 text-gray-700">Judul Artikel</label>
                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($artikel['judul']); ?>" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm transition-colors">
            </div>

            <div>
                <label for="kategori" class="block text-lg font-semibold mb-2 text-gray-700">Kategori</label>
                <select id="kategori" name="kategori" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm transition-colors">
                    <option value="">Pilih Kategori</option>
                    <option value="kesehatan-hewan" <?php echo $artikel['kategori'] == 'kesehatan-hewan' ? 'selected' : ''; ?>>Kesehatan Hewan</option>
                    <option value="nutrisi" <?php echo $artikel['kategori'] == 'nutrisi' ? 'selected' : ''; ?>>Nutrisi</option>
                    <option value="perawatan" <?php echo $artikel['kategori'] == 'perawatan' ? 'selected' : ''; ?>>Perawatan</option>
                    <option value="penyakit" <?php echo $artikel['kategori'] == 'penyakit' ? 'selected' : ''; ?>>Penyakit</option>
                    <option value="tips" <?php echo $artikel['kategori'] == 'tips' ? 'selected' : ''; ?>>Tips & Trik</option>
                </select>
            </div>

            <div>
                <label for="konten_editor" class="block text-lg font-semibold mb-2 text-gray-700">Konten Artikel</label>
                <textarea id="konten_editor" name="konten"><?php echo $artikel['konten']; ?></textarea>
            </div>

            <div>
                <label class="block text-lg font-semibold mb-4 text-gray-700">Status Publikasi</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="status-option border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-primary transition-colors <?php echo $artikel['status'] == 'draft' ? 'border-primary bg-primary/5' : ''; ?>" onclick="selectStatus('draft')">
                        <input type="radio" name="status" value="draft" id="status-draft" <?php echo $artikel['status'] == 'draft' ? 'checked' : ''; ?> required class="mr-3">
                        <label for="status-draft" class="cursor-pointer">
                            <strong>Draft</strong>
                            <span class="block text-sm text-gray-600 mt-1">Simpan sebagai draf, belum dipublikasikan</span>
                        </label>
                    </div>
                    <div class="status-option border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-primary transition-colors <?php echo $artikel['status'] == 'published' ? 'border-primary bg-primary/5' : ''; ?>" onclick="selectStatus('published')">
                        <input type="radio" name="status" value="published" id="status-published" <?php echo $artikel['status'] == 'published' ? 'checked' : ''; ?> required class="mr-3">
                        <label for="status-published" class="cursor-pointer">
                            <strong>Published</strong>
                            <span class="block text-sm text-gray-600 mt-1">Publikasikan langsung ke publik</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="<?php echo BASE_URL; ?>pages/kelola-artikel.php" class="flex-1 bg-gray-200 text-gray-800 py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" name="update_artikel" class="flex-1 bg-primary text-white py-3 px-6 rounded-lg hover:bg-secondary transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/tinymce_script.php'; ?>

<script>
    function selectStatus(status) {
        document.getElementById('status-' + status).checked = true;

        document.querySelectorAll('.status-option').forEach(option => {
            option.classList.remove('border-primary', 'bg-primary/5');
        });
        document.querySelector(`input[name="status"][value="${status}"]`).closest('.status-option').classList.add('border-primary', 'bg-primary/5');
    }
</script>

<?php require_once __DIR__ . '/../footer-dokter.php'; ?>