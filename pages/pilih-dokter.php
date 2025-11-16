<?php
require_once __DIR__ . '/../header.php';

// Include controller untuk logika backend
require_once __DIR__ . '/../controller/pilih_dokter_controller.php';
?>

<main class="pb-20 bg-gradient-to-b from-white via-purple-50 to-white min-h-[80vh]">
  <div class="container mx-auto px-6 max-w-6xl">

    <!-- Header Section -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">🏥 Pilih Dokter Hewan</h1>
      <p class="text-gray-600">Temukan dokter hewan terbaik untuk hewan kesayangan Anda</p>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-purple-200/50">
      <!-- Search Input -->
      <div class="mb-6">
        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl z-10 pointer-events-none">🔍</span>
          <input 
            type="text" 
            id="searchInput" 
            placeholder="Cari dokter hewan..."
            class="w-full pl-14 pr-4 py-3.5 border border-purple-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-sm transition-all text-base placeholder-gray-400"
          />
        </div>
      </div>

      <!-- Category Filter dengan Checklist -->
      <div class="mb-4">
        <div class="flex items-center gap-3 flex-wrap">
          <button 
            id="btnSemua"
            onclick="toggleSemua()"
            class="px-6 py-3 rounded-lg font-semibold text-base transition-all shadow-md hover:shadow-lg active:scale-95 bg-gradient-to-r from-purple-500 to-purple-700 text-white">
            Semua
          </button>
          <?php foreach ($all_kategori as $kat): ?>
            <label class="inline-flex items-center cursor-pointer relative">
              <input 
                type="checkbox" 
                class="category-checkbox sr-only peer" 
                value="<?php echo htmlspecialchars($kat->getNamaKateg()); ?>"
                <?php echo $kategori === $kat->getNamaKateg() ? 'checked' : ''; ?>
              />
              <span class="px-6 py-3 rounded-lg font-medium text-base transition-all border-2 border-purple-200 text-purple-600 bg-white hover:bg-purple-50 peer-checked:bg-gradient-to-r peer-checked:from-purple-500 peer-checked:to-purple-700 peer-checked:text-white peer-checked:border-transparent shadow-sm hover:shadow-md whitespace-nowrap select-none">
                <?php echo htmlspecialchars($kat->getNamaKateg()); ?>
              </span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Results Counter -->
      <div class="text-sm text-gray-600 font-medium">
        Menampilkan <span id="resultCount" class="text-purple-600 font-bold">0</span> dokter
      </div>
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
  <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    <!-- Backdrop -->
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModal()"></div>

    <!-- Modal Panel -->
    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
      <div class="bg-white px-6 py-4">
        <!-- Header Modal -->
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-800">Profile Dokter Hewan</h3>
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
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-purple-200 border-t-purple-600"></div>
            <p class="mt-2 text-gray-600">Memuat data dokter...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Data untuk JavaScript (dari PHP controller) -->
<script type="application/json" id="slugToKategori"><?php echo $slugToKategoriJSON; ?></script>
<script type="text/plain" id="urlKategori"><?php echo $kategoriForJS; ?></script>

<!-- Include JavaScript Controller -->
<script src="controller/pilih_dokter.js"></script>

<style>
  .shadow-card { 
    box-shadow: 0 10px 30px rgba(150, 100, 200, 0.08); 
  }
  .shadow-card:hover {
    box-shadow: 0 15px 40px rgba(150, 100, 200, 0.15);
    transform: translateY(-2px);
  }
  
  /* Styling untuk checkbox kategori - menggunakan Tailwind peer utility */
  .category-checkbox:checked + span {
    background: linear-gradient(to right, #a855f7, #9333ea) !important;
    color: white !important;
    border-color: transparent !important;
  }
  
  /* Pastikan checkbox tetap tersembunyi tapi tetap fungsional */
  .category-checkbox {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
  }
  
  /* Visual feedback untuk hover */
  label:hover .category-checkbox:not(:checked) + span {
    background-color: #faf5ff;
    border-color: #c084fc;
  }
</style>
