<?php
session_start();
include_once 'database.php';
include_once 'DAO_dokter.php'; // Perlu untuk ambil kategori

// Cek apakah user sudah melewati tahap 1
if (!isset($_SESSION['id_dokter_verifikasi'])) {
    header('Location: control-pengguna.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lengkapi Data Dokter (Tahap 2)</title>
    </head>
<body>

    <form action="control-doktor.php" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold text-purple-700 mb-6">Lengkapi Data Anda</h2>
        <p class="text-gray-700 mb-2">Email: <strong><?php echo htmlspecialchars($_SESSION['email_dokter_verifikasi']); ?></strong></p>
        <p class="text-gray-600 mb-6">Data Anda akan ditinjau oleh Admin sebelum diaktifkan.</p>

        <div class="space-y-6">
            <!-- Nama Lengkap -->
            <div>
                <label for="nama_lengkap" class="block font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required
                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label for="ttl" class="block font-semibold text-gray-700 mb-2">Tanggal Lahir *</label>
                <input type="date" id="ttl" name="ttl" required
                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Foto Profil -->
            <div>
                <label for="foto" class="block font-semibold text-gray-700 mb-2">Foto Profil *</label>
                <input type="file" id="foto" name="foto_profil" accept="image/*" required
                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG. Maksimal 5MB</p>
            </div>

            <!-- Pengalaman -->
            <div>
                <label for="pengalaman" class="block font-semibold text-gray-700 mb-2">Pengalaman (Tahun)</label>
                <input type="number" id="pengalaman" name="pengalaman" value="0" min="0"
                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Dokumen Section -->
            <hr class="my-6">
            <h3 class="text-xl font-bold text-purple-700 mb-4">📄 Data Lisensi</h3>

            <!-- Nomor STRV -->
            <div>
                <label for="strv" class="block font-semibold text-gray-700 mb-2">Nomor STRV *</label>
                <input type="text" id="strv" name="strv" required
                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                       placeholder="Contoh: 1234.5678.901.2021">
            </div>

            <!-- Exp STRV -->
            <div>
                <label for="exp_strv" class="block font-semibold text-gray-700 mb-2">Tanggal Kedaluwarsa STRV *</label>
                <input type="date" id="exp_strv" name="exp_strv" required
                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Nomor SIP -->
            <div>
                <label for="sip" class="block font-semibold text-gray-700 mb-2">Nomor SIP *</label>
                <input type="text" id="sip" name="sip" required
                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                       placeholder="Contoh: 1234.5678.901.2021">
            </div>

            <!-- Exp SIP -->
            <div>
                <label for="exp_sip" class="block font-semibold text-gray-700 mb-2">Tanggal Kedaluwarsa SIP *</label>
                <input type="date" id="exp_sip" name="exp_sip" required
                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block font-semibold text-gray-700 mb-4">Kategori/Spesialisasi *</label>
                <div class="space-y-3">
                    <?php
                    $list_kategori = DAO_kategori::getAllKategori();
                    foreach ($list_kategori as $kateg) {
                        echo '<label class="flex items-center gap-3 cursor-pointer">';
                        echo '<input type="checkbox" name="kategori[]" value="' . $kateg->getIdK() . '" class="w-5 h-5 rounded text-purple-600">';
                        echo '<span class="text-gray-700">' . htmlspecialchars($kateg->getNamaKateg()) . '</span>';
                        echo '</label>';
                    }
                    ?>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="kirim_verifikasi"
                    class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-blue-600 transition-colors mt-8">
                ✓ Kirim Data untuk Verifikasi
            </button>
        </div>
    </form>
</body>
</html>