<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../controller/pilih_dokter_controller.php';
$assetVersion = time();
?>

<main class="pb-20 bg-gradient-to-b from-white via-purple-50 to-white min-h-[80vh]">
  <div class="container mx-auto px-6 max-w-6xl">

    <!-- Header Section -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">🏥 Pilih Dokter Hewan</h1>
      <p class="text-gray-600">Temukan dokter hewan terbaik untuk hewan kesayangan Anda</p>
    </div>

    <div class="mb-4">
      <label for="searchInput" class="sr-only">Cari Dokter</label>
      <input
        type="text"
        id="searchInput"
        placeholder="Cari nama dokter atau kategori..."
        class="w-full px-5 py-3 bg-white rounded-3xl border border-purple-300 focus:border-transparent focus:ring-2 focus:ring-purple-500 focus:ring-offset-1 focus:ring-offset-white transition-all duration-300 outline-none focus:outline-none focus-visible:outline-none">
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-purple-200/50">
      <div id="filter-kategori-container" class="flex flex-wrap gap-3 mt-6">
        <?php
        // Ambil nama kategori yang sedang aktif (dari controller)
        $activeKategName = $kategori;
        // Cek apakah tidak ada filter sama sekali (alias 'Semua Kategori')
        $isAllActive = empty($activeKategName);
        ?>

        <label class="inline-flex items-center cursor-pointer relative">
          <input
            type="radio"
            name="category-filter"
            class="category-radio sr-only peer"
            value=""
            <?php echo $isAllActive ? 'checked' : ''; ?> />
          <span class="px-6 py-3 rounded-lg transition-colors duration-200 ease-in-out border
           border-purple-300 text-purple-700 peer-checked:bg-purple-600 peer-checked:text-white bg-white hover:bg-purple-50">
            Semua Kategori
          </span>
        </label>
        <?php foreach ($all_kategori as $kat): ?>
          <label class="inline-flex items-center cursor-pointer relative">
            <input type="radio" name="category-filter" class="category-radio sr-only peer"
              value="<?php echo htmlspecialchars($kat->getNamaKateg()); ?>" <?php echo $kategori === $kat->getNamaKateg() ? 'checked' : ''; ?> />
            <span class="px-6 py-3 rounded-lg transition-colors duration-250 ease-in-out border border-purple-300 text-purple-700
             peer-checked:bg-purple-600 peer-checked:text-white bg-white hover:bg-purple-50">
              <?php echo htmlspecialchars($kat->getNamaKateg()); ?>
            </span>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Results Counter -->
    <div class="text-sm text-gray-600 font-medium mb-6">
      Menampilkan <span id="resultCount" class="text-purple-600 font-bold">0</span> dokter
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center py-12">
      <div class="inline-flex items-center gap-3">
        <div class="w-8 h-8 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin"></div>
        <span class="text-gray-600">Memuat dokter...</span>
      </div>
    </div>

    <!-- Dokter List Container -->
    <div id="doktersContainer" class="grid grid-cols-1 md:grid-cols-2 gap-8 hidden">
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="py-12 text-center hidden">
      <p class="text-gray-600 text-lg">😔 Tidak ada dokter yang cocok dengan pencarian Anda</p>
      <p class="text-gray-500 text-sm mt-2">Coba ubah filter kategori atau kata kunci pencarian</p>
    </div>

  </div>
</main>

<!-- Modal Detail Dokter -->
<div id="modalDokter" class="fixed inset-0 z-50 hidden overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen px-4">
    <!-- Backdrop -->
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModal()"></div>

    <!-- Modal Panel -->
    <div
      class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-[420px] sm:w-full" style="max-width:420px !important;">
      <div class="bg-white px-4 py-3" style="max-height:80vh; overflow:auto;">
        <!-- Header Modal -->
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-800">Profile Dokter Hewan</h3>
          <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Content Modal -->
        <div id="modalContent" class="space-y-6">
          <!-- Loading state -->
          <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-purple-200 border-t-purple-600">
            </div>
            <p class="mt-2 text-gray-600">Memuat data dokter...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<data id="slugToKategori" value="<?php echo htmlspecialchars($slugToKategoriJSON, ENT_QUOTES, 'UTF-8'); ?>"></data>
<data id="urlKategori" value="<?php echo htmlspecialchars($kategoriForJS, ENT_QUOTES, 'UTF-8'); ?>"></data>

<script src="controller/pilih_dokter.js?v=<?php echo $assetVersion; ?>"></script>

<style>
  .shadow-card {
    box-shadow: 0 10px 30px rgba(150, 100, 200, 0.08);
  }

  .shadow-card:hover {
    box-shadow: 0 15px 40px rgba(150, 100, 200, 0.15);
    transform: translateY(-2px);
  }

  /* Styling untuk radio kategori - menggunakan Tailwind peer utility */
  .category-radio:checked+span {
<data id="slugToKategori" value="<?php echo htmlspecialchars($slugToKategoriJSON, ENT_QUOTES, 'UTF-8'); ?>"></data>
<data id="urlKategori" value="<?php echo htmlspecialchars($kategoriForJS, ENT_QUOTES, 'UTF-8'); ?>"></data>

<!-- expose API key to JS (set in src/config/config.php) -->
<script>window.GOOGLE_MAPS_API_KEY = '<?php echo GOOGLE_MAPS_API_KEY; ?>';</script>
<script src="controller/doctor_map.js?v=<?php echo $assetVersion; ?>"></script>
<script src="controller/pilih_dokter.js?v=<?php echo $assetVersion; ?>"></script>
  }
</style>