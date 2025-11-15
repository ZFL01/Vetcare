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
            <span class="absolute left-4 top-3.5 text-gray-400 text-lg">🔍</span>
            <input 
              type="text" 
              id="searchInput" 
              placeholder="Cari dokter hewan..."
              class="w-full pl-12 pr-4 py-3 border border-purple-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-sm transition-all"
            />
          </div>
        </div>

        <!-- Category Filter -->
        <div>
          <select 
            id="categoryFilter"
            class="w-full px-4 py-3 border border-purple-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-sm bg-white cursor-pointer transition-all"
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
      <div class="mt-4 text-sm text-gray-600 font-medium">
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

<script>
// Data store
let allDokters = [];
// Handle kategori dari URL (bisa slug atau nama_kateg)
let selectedCategory = '<?php echo htmlspecialchars($kategori ?: 'all', ENT_QUOTES, 'UTF-8'); ?>';
let searchKeyword = '';

// Mapping slug ke nama_kateg untuk filter dropdown
const slugToKategori = {
  'peliharaan': 'Hewan Peliharaan',
  'ternak': 'Hewan Ternak',
  'eksotis': 'Hewan Eksotis',
  'akuatik': 'Hewan Akuatik',
  'kecil': 'Hewan Kecil',
  'unggas': 'Hewan Unggas'
};

// Convert slug ke nama_kateg jika perlu
if (selectedCategory !== 'all' && slugToKategori[selectedCategory]) {
  selectedCategory = slugToKategori[selectedCategory];
}

// DOM Elements
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const doktersContainer = document.getElementById('doktersContainer');
const loadingIndicator = document.getElementById('loadingIndicator');
const emptyState = document.getElementById('emptyState');
const resultCount = document.getElementById('resultCount');

// Debounce function untuk optimasi search
let searchTimeout;
function debounceSearch(func, delay) {
  return function(...args) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => func.apply(this, args), delay);
  };
}

// Event Listeners dengan debounce untuk performa lebih baik
const debouncedFilter = debounceSearch(() => {
  filterAndDisplayDokters();
}, 300);

searchInput.addEventListener('input', (e) => {
  searchKeyword = e.target.value.trim();
  // Gunakan debounce untuk menghindari terlalu banyak render
  debouncedFilter();
});

categoryFilter.addEventListener('change', (e) => {
  selectedCategory = e.target.value;
  // Update URL without page reload
  const newUrl = selectedCategory !== 'all' ? 
    `?route=pilih-dokter&kategori=${encodeURIComponent(selectedCategory)}` : 
    `?route=pilih-dokter`;
  window.history.replaceState({}, '', newUrl);
  
  // Reset search ketika filter kategori berubah
  searchKeyword = '';
  searchInput.value = '';
  
  fetchDokters();
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  // Update dropdown jika ada kategori dari URL
  if (selectedCategory !== 'all') {
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
      categoryFilter.value = selectedCategory;
    }
  }
  
  // Fetch dokters
  fetchDokters();
});

// Fetch dokters from API dengan optimasi
async function fetchDokters() {
  try {
    // Show loading indicator
    loadingIndicator.classList.remove('hidden');
    doktersContainer.classList.add('hidden');
    emptyState.classList.add('hidden');

    // Build URL dengan path relatif dari root aplikasi
    let apiUrl = 'controller/api_dokter_katalog.php';
    const params = new URLSearchParams();
    
    if (selectedCategory !== 'all') {
      params.append('kategori', selectedCategory);
    }
    // Tambahkan search parameter jika ada
    if (searchKeyword) {
      params.append('search', searchKeyword);
    }
    
    // Gabungkan URL dengan parameter
    if (params.toString()) {
      apiUrl += '?' + params.toString();
    }

    console.log('Fetching dari:', apiUrl); // Debug log

    // Fetch dengan cache control untuk performa lebih baik
    const response = await fetch(apiUrl, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
      },
      cache: 'no-cache'
    });

    console.log('Response status:', response.status); // Debug log

    if (!response.ok) {
      const errorText = await response.text();
      console.error('Response error:', errorText);
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    console.log('Response data:', data); // Debug log

    if (data.success) {
      allDokters = data.data || [];
      console.log('Dokter ditemukan:', allDokters.length); // Debug log
      filterAndDisplayDokters();
    } else {
      console.error('API Error:', data.message);
      showEmptyState();
    }
  } catch (error) {
    console.error('Fetch Error:', error);
    console.error('Error details:', error.message, error.stack);
    showEmptyState();
  }
}

// Filter and display dokters dengan optimasi
function filterAndDisplayDokters() {
  loadingIndicator.classList.add('hidden');

  // Filter based on search keyword (client-side filtering untuk UX lebih cepat)
  let filteredDokters = allDokters;
  if (searchKeyword) {
    const keywordLower = searchKeyword.toLowerCase();
    filteredDokters = allDokters.filter(doc => {
      const namaMatch = doc.nama_dokter.toLowerCase().includes(keywordLower);
      // Bisa juga search berdasarkan kategori
      const kategoriMatch = doc.kategori && doc.kategori.some(kat => 
        kat.toLowerCase().includes(keywordLower)
      );
      return namaMatch || kategoriMatch;
    });
  }

  // Update result count
  resultCount.textContent = filteredDokters.length;

  // Display or show empty state
  if (filteredDokters.length === 0) {
    showEmptyState();
    return;
  }

  // Render dokters dengan debounce untuk performa
  renderDokters(filteredDokters);
}

// Render dokters to DOM
function renderDokters(dokters) {
  doktersContainer.innerHTML = '';

  dokters.forEach(doc => {
    const kategoriText = doc.kategori && doc.kategori.length > 0 ? 
      doc.kategori[0] : 'Dokter Hewan Umum';
    
    const jadwalInfo = formatJadwalDetail(doc.jadwal);
    const hargaKonsultasi = doc.biaya_konsultasi || 75000;
    const jumlahReview = doc.jumlah_review || 0;
    const isOnline = doc.is_online || false;
    const deskripsi = doc.deskripsi || 'Dokter berpengalaman dalam berbagai kasus kesehatan hewan dengan pendekatan profesional';
    const bahasa = doc.bahasa || ['Indonesia', 'English'];

    const card = document.createElement('div');
    card.className = 'relative rounded-2xl overflow-hidden bg-white shadow-card p-6 border border-purple-200/50 hover:shadow-xl transition-all duration-300';
    card.innerHTML = `
      <div class="flex flex-col gap-4">
        <!-- Header dengan foto dan badge online -->
        <div class="flex gap-4">
          <div class="relative flex-shrink-0">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 border-2 border-purple-100">
              <img src="${doc.foto || 'https://i.pravatar.cc/120?img=' + (doc.id_dokter % 70)}" 
                   alt="${escapeHtml(doc.nama_dokter)}" 
                   class="w-full h-full object-cover" 
                   onerror="this.src='https://i.pravatar.cc/120?img=' + (${doc.id_dokter} % 70)" />
            </div>
            ${isOnline ? '<span class="absolute -top-1 -right-1 bg-green-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full border-2 border-white">Online</span>' : ''}
          </div>
          
          <div class="flex-1 min-w-0">
            <h4 class="font-bold text-lg text-gray-800 mb-1">${escapeHtml(doc.nama_dokter)}</h4>
            <div class="text-sm text-purple-600 mb-2 font-semibold">${escapeHtml(kategoriText)}</div>
            
            <!-- Rating dan Pengalaman -->
            <div class="flex items-center gap-3 text-sm mb-2">
              <div class="flex items-center gap-1">
                <span class="text-yellow-400 text-lg">★</span>
                <span class="font-semibold text-gray-700">${parseFloat(doc.rate || 0).toFixed(1)}</span>
                <span class="text-gray-500">(${jumlahReview} ulasan)</span>
              </div>
              <span class="text-gray-300">•</span>
              <div class="flex items-center gap-1 text-gray-600">
                <span class="text-xs">📅</span>
                <span>${doc.pengalaman || 0} tahun</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Deskripsi -->
        <p class="text-sm text-gray-600 leading-relaxed">${escapeHtml(deskripsi)}</p>

        <!-- Jadwal Hari Ini -->
        <div class="bg-purple-50 rounded-lg p-3 border border-purple-100">
          <div class="text-xs font-semibold text-purple-700 mb-1">Jadwal Hari Ini:</div>
          <div class="text-sm text-gray-700">${jadwalInfo.hariIni}</div>
          <div class="text-xs text-gray-600 mt-1">Praktik: ${jadwalInfo.hariPraktik}</div>
        </div>

        <!-- Lokasi -->
        <div class="flex items-start gap-2 text-sm text-gray-600">
          <span class="text-base mt-0.5">📍</span>
          <span class="flex-1">${escapeHtml(doc.alamat || doc.nama_klinik || 'Lokasi tidak tersedia')}</span>
        </div>

        <!-- Bahasa -->
        <div class="flex items-center gap-2">
          <span class="text-xs text-gray-500">Bahasa:</span>
          ${bahasa.map((b, idx) => `
            <button class="px-2 py-1 text-xs rounded-md transition-colors ${
              idx === 0 
                ? 'bg-purple-100 text-purple-700 font-medium' 
                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
            }">
              ${escapeHtml(b)}
            </button>
          `).join('')}
        </div>

        <!-- Footer dengan harga dan tombol -->
        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
          <div>
            <div class="text-xs text-gray-500 mb-0.5">Biaya Konsultasi</div>
            <div class="text-lg font-bold text-purple-600">Rp ${new Intl.NumberFormat('id-ID').format(hargaKonsultasi)}</div>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="videoCallDokter(${doc.id_dokter})" 
                    class="w-10 h-10 flex items-center justify-center bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-colors"
                    title="Video Call">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
              </svg>
            </button>
            <button onclick="chatDokter(${doc.id_dokter})" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-purple-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all">
              <span>💬</span>
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

// Format jadwal detail dengan hari ini dan hari praktik
function formatJadwalDetail(jadwal) {
  if (!jadwal || Object.keys(jadwal).length === 0) {
    return {
      hariIni: 'Tidak praktik hari ini',
      hariPraktik: 'Jadwal tidak tersedia'
    };
  }

  const today = new Date();
  const dayIndex = today.getDay(); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
  const daysInIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const hariIniNama = daysInIndonesian[dayIndex];
  
  // Mapping nama hari ke format database (jika berbeda)
  const hariMapping = {
    'Minggu': 'Minggu',
    'Senin': 'Senin',
    'Selasa': 'Selasa',
    'Rabu': 'Rabu',
    'Kamis': 'Kamis',
    'Jumat': 'Jumat',
    'Sabtu': 'Sabtu'
  };

  // Cek jadwal hari ini
  let jadwalHariIni = null;
  const hariIniKey = hariMapping[hariIniNama];
  if (jadwal[hariIniKey] && jadwal[hariIniKey].length > 0) {
    const jam = jadwal[hariIniKey][0];
    jadwalHariIni = `${jam.buka} - ${jam.tutup}`;
  }

  // Kumpulkan semua hari praktik
  const hariPraktik = [];
  for (let day in jadwal) {
    if (jadwal[day] && jadwal[day].length > 0) {
      hariPraktik.push(day);
    }
  }

  return {
    hariIni: jadwalHariIni || 'Tidak praktik hari ini',
    hariPraktik: hariPraktik.length > 0 ? hariPraktik.join(', ') : 'Jadwal tidak tersedia'
  };
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
  window.location.href = `?route=chat&dokter=${id_dokter}`;
}

// Video call function
function videoCallDokter(id_dokter) {
  // Implement video call functionality
  window.location.href = `?route=video-call&dokter=${id_dokter}`;
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
  .shadow-card { 
    box-shadow: 0 10px 30px rgba(150, 100, 200, 0.08); 
  }
  .shadow-card:hover {
    box-shadow: 0 15px 40px rgba(150, 100, 200, 0.15);
    transform: translateY(-2px);
  }
</style>
