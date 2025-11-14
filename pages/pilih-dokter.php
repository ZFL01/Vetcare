<?php
require_once __DIR__ . '/../header.php';

// Get selected kategori from URL parameter
$kategori = isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : '';

// Get all available categories for filter dropdown
require_once __DIR__ . '/../includes/DAO_dokter.php';
$all_kategori = DAO_kategori::getAllKategori();
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
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search Input -->
        <div class="md:col-span-2">
          <div class="relative">
            <span class="absolute left-4 top-3.5 text-gray-400">🔍</span>
            <input 
              type="text" 
              id="searchInput" 
              placeholder="Cari nama dokter..."
              class="w-full pl-12 pr-4 py-3 border border-purple-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-sm"
            />
          </div>
        </div>

        <!-- Category Filter -->
        <div>
          <select 
            id="categoryFilter"
            class="w-full px-4 py-3 border border-purple-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-sm"
          >
            <option value="all">Semua Kategori</option>
            <?php foreach ($all_kategori as $kat): ?>
              <option value="<?php echo htmlspecialchars($kat->getNamaKateg()); ?>"
                <?php echo $kategori === $kat->getNamaKateg() ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($kat->getNamaKateg()); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Results Counter -->
      <div class="mt-4 text-sm text-gray-600">
        Menampilkan <span id="resultCount">0</span> dokter
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

<script>
// Data store
let allDokters = [];
let selectedCategory = '<?php echo $kategori ?: 'all'; ?>';
let searchKeyword = '';

// DOM Elements
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const doktersContainer = document.getElementById('doktersContainer');
const loadingIndicator = document.getElementById('loadingIndicator');
const emptyState = document.getElementById('emptyState');
const resultCount = document.getElementById('resultCount');

// Event Listeners
searchInput.addEventListener('input', (e) => {
  searchKeyword = e.target.value.trim();
  filterAndDisplayDokters();
});

categoryFilter.addEventListener('change', (e) => {
  selectedCategory = e.target.value;
  // Update URL without page reload
  const newUrl = selectedCategory !== 'all' ? 
    `?route=pilih-dokter&kategori=${encodeURIComponent(selectedCategory)}` : 
    `?route=pilih-dokter`;
  window.history.replaceState({}, '', newUrl);
  
  fetchDokters();
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  fetchDokters();
});

// Fetch dokters from API
async function fetchDokters() {
  try {
    loadingIndicator.classList.remove('hidden');
    doktersContainer.classList.add('hidden');
    emptyState.classList.add('hidden');

    const url = new URL('controller/api_dokter_katalog.php', window.location.origin);
    if (selectedCategory !== 'all') {
      url.searchParams.append('kategori', selectedCategory);
    }

    const response = await fetch(url.toString());
    const data = await response.json();

    if (data.success) {
      allDokters = data.data;
      filterAndDisplayDokters();
    } else {
      console.error('API Error:', data.message);
      showEmptyState();
    }
  } catch (error) {
    console.error('Fetch Error:', error);
    showEmptyState();
  }
}

// Filter and display dokters
function filterAndDisplayDokters() {
  loadingIndicator.classList.add('hidden');

  // Filter based on search keyword
  let filteredDokters = allDokters;
  if (searchKeyword) {
    filteredDokters = allDokters.filter(doc =>
      doc.nama_dokter.toLowerCase().includes(searchKeyword.toLowerCase())
    );
  }

  // Update result count
  resultCount.textContent = filteredDokters.length;

  // Display or show empty state
  if (filteredDokters.length === 0) {
    showEmptyState();
    return;
  }

  // Render dokters
  renderDokters(filteredDokters);
}

// Render dokters to DOM
function renderDokters(dokters) {
  doktersContainer.innerHTML = '';

  dokters.forEach(doc => {
    const kategoriText = doc.kategori && doc.kategori.length > 0 ? 
      doc.kategori.join(', ') : 'Belum dikategorikan';
    
    const jadwalText = formatJadwal(doc.jadwal);
    const hargaKonsultasi = 150000; // Default konsultasi

    const card = document.createElement('div');
    card.className = 'relative rounded-2xl overflow-hidden bg-white shadow-card p-6 border border-purple-200/50 hover:shadow-lg transition-shadow';
    card.innerHTML = `
      <div class="flex gap-4">
        <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0 bg-gray-200">
          <img src="${doc.foto || 'https://i.pravatar.cc/120?img=1'}" 
               alt="${doc.nama_dokter}" 
               class="w-full h-full object-cover" 
               onerror="this.src='https://i.pravatar.cc/120?img=1'" />
        </div>
        <div class="flex-1">
          <h4 class="font-semibold text-gray-800">${escapeHtml(doc.nama_dokter)}</h4>
          <div class="text-sm text-purple-600 mb-2 font-medium">${escapeHtml(kategoriText)}</div>
          <div class="flex items-center text-sm text-gray-600 gap-3 mb-2">
            <span class="text-yellow-400">★</span>
            <span>${parseFloat(doc.rate).toFixed(1)} (${doc.id_dokter} ulasan)</span>
            <span class="text-gray-400">•</span>
            <span>${doc.pengalaman} tahun</span>
          </div>
          <p class="text-sm text-gray-600 mb-3">Dokter berpengalaman dalam berbagai kasus hewan peliharaan dan ternak.</p>

          <div class="text-sm text-gray-500 mb-4">
            <div class="flex items-center gap-2">
              <span class="text-xs">⏰</span> 
              <span>${jadwalText}</span>
            </div>
            <div class="flex items-center gap-2 mt-2">
              <span class="text-xs">📍</span> 
              <span>${escapeHtml(doc.alamat || doc.nama_klinik || 'Lokasi tidak tersedia')}</span>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="text-blue-600 font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(hargaKonsultasi)}</div>
            <button onclick="chatDokter(${doc.id_dokter})" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-purple-700 text-white px-4 py-2 rounded-full text-sm shadow-lg hover:scale-[1.02] transition-transform">
              <span class="w-5 h-5 flex items-center justify-center bg-white/10 rounded-full">💬</span>
              Chat Sekarang
            </button>
          </div>
        </div>
      </div>
    `;

    doktersContainer.appendChild(card);
  });

  doktersContainer.classList.remove('hidden');
}

// Format jadwal
function formatJadwal(jadwal) {
  if (!jadwal || Object.keys(jadwal).length === 0) {
    return 'Jadwal tidak tersedia';
  }

  const today = new Date();
  const daysInIndonesian = {
    'Senin': 'Senin',
    'Selasa': 'Selasa',
    'Rabu': 'Rabu',
    'Kamis': 'Kamis',
    'Jumat': 'Jumat',
    'Sabtu': 'Sabtu',
    'Minggu': 'Minggu'
  };

  // Get first available schedule
  for (let day in jadwal) {
    const times = jadwal[day];
    if (times && times.length > 0) {
      const jam = times[0];
      return `${daysInIndonesian[day]}: ${jam.buka} - ${jam.tutup}`;
    }
  }

  return 'Jadwal tidak tersedia';
}

// Show empty state
function showEmptyState() {
  doktersContainer.classList.add('hidden');
  loadingIndicator.classList.add('hidden');
  emptyState.classList.remove('hidden');
}

// Chat function
function chatDokter(id_dokter) {
  // Implement chat functionality
  alert('Fitur chat akan segera tersedia untuk dokter ID: ' + id_dokter);
}

// Utility function to escape HTML
function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}
</script>

<style>
  .shadow-card { box-shadow: 0 10px 30px rgba(150, 100, 200, 0.08); }
</style>

<style>
  .shadow-card { box-shadow: 0 10px 30px rgba(150, 100, 200, 0.08); }
</style>
