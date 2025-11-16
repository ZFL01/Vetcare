/**
 * JavaScript Controller untuk halaman pilih-dokter
 * Menangani semua logika frontend dan interaksi user
 */

// ==================== DATA STORE ====================
let allDokters = [];
let selectedCategories = [];
let searchKeyword = '';

// Mapping slug ke nama_kateg (dari PHP)
let slugToKategori = {};
try {
  const slugElement = document.getElementById('slugToKategori');
  if (slugElement) {
    slugToKategori = JSON.parse(slugElement.textContent || '{}');
  }
} catch (e) {
  console.error('Error parsing slugToKategori:', e);
  slugToKategori = {
    'peliharaan': 'Hewan Peliharaan',
    'ternak': 'Hewan Ternak',
    'eksotis': 'Hewan Eksotis',
    'akuatik': 'Hewan Akuatik',
    'kecil': 'Hewan Kecil',
    'unggas': 'Hewan Unggas'
  };
}

// Handle kategori dari URL
const urlKategoriElement = document.getElementById('urlKategori');
const urlKategori = urlKategoriElement ? urlKategoriElement.textContent.trim() : '';
if (urlKategori) {
  const kategoriFromUrl = slugToKategori[urlKategori] || urlKategori;
  selectedCategories = [kategoriFromUrl];
}

// ==================== DOM ELEMENTS ====================
// Akan diinisialisasi setelah DOM ready
let searchInput;
let categoryCheckboxes;
let btnSemua;
let doktersContainer;
let loadingIndicator;
let emptyState;
let resultCount;
let modalDokter;
let modalContent;

// ==================== UTILITY FUNCTIONS ====================

/**
 * Debounce function untuk optimasi search
 */
let searchTimeout;
function debounceSearch(func, delay) {
  return function(...args) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => func.apply(this, args), delay);
  };
}

/**
 * Escape HTML untuk mencegah XSS
 */
function escapeHtml(text) {
  if (!text) return '';
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return String(text).replace(/[&<>"']/g, m => map[m]);
}

/**
 * Format jadwal detail dengan hari ini dan hari praktik
 */
function formatJadwalDetail(jadwal) {
  if (!jadwal || Object.keys(jadwal).length === 0) {
    return {
      hariIni: 'Tidak praktik hari ini',
      hariPraktik: 'Jadwal tidak tersedia'
    };
  }

  const today = new Date();
  const dayIndex = today.getDay();
  const daysInIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const hariIniNama = daysInIndonesian[dayIndex];
  
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

// ==================== CATEGORY MANAGEMENT ====================

/**
 * Update selected categories dari checkbox
 */
function updateSelectedCategories() {
  if (!categoryCheckboxes || categoryCheckboxes.length === 0) {
    return;
  }
  selectedCategories = Array.from(categoryCheckboxes)
    .filter(cb => cb.checked)
    .map(cb => cb.value);
}

/**
 * Toggle semua kategori
 */
function toggleSemua() {
  if (!categoryCheckboxes || categoryCheckboxes.length === 0) {
    return;
  }
  
  const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
  
  categoryCheckboxes.forEach(cb => {
    cb.checked = !allChecked;
  });
  
  updateSelectedCategories();
  updateSemuaButton();
  fetchDokters();
}

/**
 * Update tampilan tombol Semua
 */
function updateSemuaButton() {
  if (!btnSemua || !categoryCheckboxes || categoryCheckboxes.length === 0) {
    return;
  }
  
  const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
  
  if (allChecked || selectedCategories.length === 0) {
    btnSemua.classList.add('bg-gradient-to-r', 'from-purple-500', 'to-purple-700', 'text-white');
    btnSemua.classList.remove('bg-white', 'text-purple-600', 'border-2', 'border-purple-200');
  } else {
    btnSemua.classList.remove('bg-gradient-to-r', 'from-purple-500', 'to-purple-700', 'text-white');
    btnSemua.classList.add('bg-white', 'text-purple-600', 'border-2', 'border-purple-200');
  }
}

// ==================== API CALLS ====================

/**
 * Fetch dokters from API dengan optimasi
 */
async function fetchDokters() {
  try {
    // Pastikan DOM elements sudah diinisialisasi
    if (!loadingIndicator || !doktersContainer || !emptyState) {
      console.error('DOM elements not initialized in fetchDokters');
      return;
    }

    // Show loading indicator
    loadingIndicator.classList.remove('hidden');
    doktersContainer.classList.add('hidden');
    emptyState.classList.add('hidden');

    // Build URL dengan path relatif dari root aplikasi
    let apiUrl = 'controller/api_dokter_katalog.php';
    const params = new URLSearchParams();
    
    // Handle multiple kategori
    const totalCategories = categoryCheckboxes ? categoryCheckboxes.length : 0;
    if (selectedCategories.length > 0 && selectedCategories.length < totalCategories) {
      params.append('kategori', selectedCategories.join(','));
    }
    
    // Tambahkan search parameter jika ada
    if (searchKeyword) {
      params.append('search', searchKeyword);
    }
    
    // Gabungkan URL dengan parameter
    if (params.toString()) {
      apiUrl += '?' + params.toString();
    }

    // Fetch dengan cache control untuk performa lebih baik
    const response = await fetch(apiUrl, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
      },
      cache: 'no-cache'
    });

    if (!response.ok) {
      const errorText = await response.text();
      console.error('Response error:', errorText);
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    console.log('API Response:', data); // Debug log

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
    showEmptyState();
  }
}

// ==================== FILTER & DISPLAY ====================

/**
 * Filter and display dokters dengan optimasi
 */
function filterAndDisplayDokters() {
  if (!loadingIndicator || !doktersContainer || !emptyState || !resultCount) {
    console.error('DOM elements not initialized yet');
    return;
  }

  loadingIndicator.classList.add('hidden');

  // Filter based on search keyword (client-side filtering untuk UX lebih cepat)
  let filteredDokters = allDokters;
  if (searchKeyword) {
    const keywordLower = searchKeyword.toLowerCase();
    filteredDokters = allDokters.filter(doc => {
      const namaMatch = doc.nama_dokter.toLowerCase().includes(keywordLower);
      const kategoriMatch = doc.kategori && doc.kategori.some(kat => 
        kat.toLowerCase().includes(keywordLower)
      );
      return namaMatch || kategoriMatch;
    });
  }

  console.log('Filtered dokters:', filteredDokters.length); // Debug log

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

/**
 * Show empty state
 */
function showEmptyState() {
  if (!doktersContainer || !loadingIndicator || !emptyState) {
    console.error('DOM elements not initialized in showEmptyState');
    return;
  }
  doktersContainer.classList.add('hidden');
  loadingIndicator.classList.add('hidden');
  emptyState.classList.remove('hidden');
}

// ==================== RENDERING ====================

/**
 * Render dokters to DOM
 */
function renderDokters(dokters) {
  if (!doktersContainer) {
    console.error('doktersContainer not found');
    return;
  }

  console.log('Rendering', dokters.length, 'dokters'); // Debug log
  doktersContainer.innerHTML = '';

  if (dokters.length === 0) {
    console.warn('No dokters to render');
    return;
  }

  dokters.forEach((doc, index) => {
    console.log(`Rendering dokter ${index + 1}:`, doc.nama_dokter); // Debug log
    
    const kategoriText = doc.kategori && doc.kategori.length > 0 ? 
      doc.kategori[0] : 'Dokter Hewan Umum';
    
    const jadwalInfo = formatJadwalDetail(doc.jadwal);
    const hargaKonsultasi = doc.biaya_konsultasi || 75000;
    const jumlahReview = doc.jumlah_review || 0;
    const isOnline = doc.is_online || false;
    const deskripsi = doc.deskripsi || 'Dokter berpengalaman dalam berbagai kasus kesehatan hewan dengan pendekatan profesional';

    const card = document.createElement('div');
    card.className = 'relative rounded-2xl overflow-hidden bg-white shadow-card p-6 border border-purple-200/50 hover:shadow-xl transition-all duration-300 cursor-pointer';
    card.onclick = () => showModalDokter(doc);
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
                <span>${doc.pengalaman_tahun || doc.pengalaman || 0} tahun</span>
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
          ${(doc.bahasa || ['Indonesia', 'English']).map((b, idx) => `
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
          <button onclick="event.stopPropagation(); chatDokter(${doc.id_dokter})" 
                  class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-purple-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all">
            <span>💬</span>
            Chat Sekarang
          </button>
        </div>
      </div>
    `;

    doktersContainer.appendChild(card);
  });

  console.log('Cards appended, showing container'); // Debug log
  console.log('Total cards in container:', doktersContainer.children.length); // Debug log
  
  // Pastikan container ditampilkan
  doktersContainer.classList.remove('hidden');
  
  // Double check
  if (doktersContainer.classList.contains('hidden')) {
    console.error('Container masih hidden setelah remove hidden class');
    doktersContainer.style.display = 'grid'; // Force display
  } else {
    console.log('Container berhasil ditampilkan'); // Debug log
  }
}

// ==================== MODAL FUNCTIONS ====================

/**
 * Show modal detail dokter
 */
function showModalDokter(doc) {
  modalDokter.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  
  const jadwalInfo = formatJadwalDetail(doc.jadwal);
  const hargaKonsultasi = doc.biaya_konsultasi || 75000;
  const jumlahReview = doc.jumlah_review || 0;
  const pengalamanTahun = doc.pengalaman_tahun || doc.pengalaman || 0;
  const kategoriText = doc.kategori && doc.kategori.length > 0 ? 
    doc.kategori.join(', ') : 'Dokter Hewan Umum';
  const deskripsi = doc.deskripsi || 'Dokter berpengalaman dalam berbagai kasus kesehatan hewan dengan pendekatan profesional';
  
  // Format hari praktik untuk modal
  const hariPraktik = [];
  if (doc.jadwal) {
    for (let day in doc.jadwal) {
      if (doc.jadwal[day] && doc.jadwal[day].length > 0) {
        hariPraktik.push(day);
      }
    }
  }
  
  // Get jam praktik
  let jamPraktik = 'Tidak tersedia';
  if (hariPraktik.length > 0 && doc.jadwal[hariPraktik[0]] && doc.jadwal[hariPraktik[0]].length > 0) {
    const jam = doc.jadwal[hariPraktik[0]][0];
    jamPraktik = `${jam.buka} - ${jam.tutup}`;
  }
  
  // Get hari ini
  const today = new Date();
  const dayIndex = today.getDay();
  const daysInIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const hariIniNama = daysInIndonesian[dayIndex];
  
  // Cek jadwal hari ini
  let jadwalHariIni = 'Tidak praktik hari ini';
  if (doc.jadwal && doc.jadwal[hariIniNama] && doc.jadwal[hariIniNama].length > 0) {
    const jam = doc.jadwal[hariIniNama][0];
    jadwalHariIni = `${jam.buka} - ${jam.tutup}`;
  }
  
  // Google Maps URL
  const lat = doc.lat || '';
  const lng = doc.long || '';
  const alamat = doc.alamat || doc.nama_klinik || '';
  const mapsUrl = lat && lng ? 
    `https://www.google.com/maps?q=${lat},${lng}` : 
    `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(alamat)}`;
  
  modalContent.innerHTML = `
    <!-- Info Dokter -->
    <div class="flex gap-4 mb-6">
      <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 border-2 border-purple-100 flex-shrink-0">
        <img src="${doc.foto || 'https://i.pravatar.cc/120?img=' + (doc.id_dokter % 70)}" 
             alt="${escapeHtml(doc.nama_dokter)}" 
             class="w-full h-full object-cover" 
             onerror="this.src='https://i.pravatar.cc/120?img=' + (${doc.id_dokter} % 70)" />
      </div>
      <div class="flex-1">
        <h4 class="font-bold text-xl text-gray-800 mb-1">${escapeHtml(doc.nama_dokter)}</h4>
        <div class="text-sm text-purple-600 mb-2 font-semibold">${escapeHtml(kategoriText)}</div>
        <div class="flex items-center gap-3 text-sm mb-2">
          <div class="flex items-center gap-1">
            <span class="text-yellow-400 text-lg">★</span>
            <span class="font-semibold text-gray-700">${parseFloat(doc.rate || 0).toFixed(1)}</span>
            <span class="text-gray-500">(${jumlahReview} ulasan)</span>
          </div>
          <span class="text-gray-300">•</span>
          <div class="flex items-center gap-1 text-gray-600">
            <span class="text-xs">📅</span>
            <span>${pengalamanTahun} tahun</span>
          </div>
        </div>
        <p class="text-sm text-gray-600 leading-relaxed">${escapeHtml(deskripsi)}</p>
      </div>
    </div>

    <!-- Jadwal Praktik -->
    <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
      <div class="flex items-center gap-2 mb-3">
        <span class="text-lg">⏰</span>
        <h5 class="font-semibold text-purple-700">Jadwal Praktik</h5>
      </div>
      <div class="space-y-2 text-sm">
        <div>
          <span class="font-medium text-gray-700">Hari ini (${hariIniNama}):</span>
          <span class="text-gray-600 ml-2">${jadwalHariIni}</span>
        </div>
        <div>
          <span class="font-medium text-gray-700">Hari Praktik:</span>
          <div class="flex flex-wrap gap-2 mt-2">
            ${hariPraktik.map(day => `
              <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-md text-xs font-medium">${day}</span>
            `).join('')}
          </div>
        </div>
        <div>
          <span class="font-medium text-gray-700">Jam:</span>
          <span class="text-gray-600 ml-2">${jamPraktik}</span>
        </div>
      </div>
    </div>

    <!-- Lokasi Praktik -->
    <div class="bg-white rounded-lg p-4 border border-gray-200">
      <div class="flex items-center gap-2 mb-3">
        <span class="text-lg">📍</span>
        <h5 class="font-semibold text-gray-700">Lokasi Praktik</h5>
      </div>
      <p class="text-sm text-gray-600 mb-3">${escapeHtml(alamat || 'Lokasi tidak tersedia')}</p>
      ${lat && lng ? `
        <div id="mapContainer" class="w-full h-64 rounded-lg overflow-hidden border border-gray-200 mb-3"></div>
        <a href="${mapsUrl}" target="_blank" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
          Buka di Google Maps →
        </a>
      ` : `
        <a href="${mapsUrl}" target="_blank" class="inline-block text-sm text-purple-600 hover:text-purple-700 font-medium">
          Buka di Google Maps →
        </a>
      `}
    </div>

    <!-- Footer Modal -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
      <div>
        <div class="text-xs text-gray-500 mb-0.5">Biaya Konsultasi</div>
        <div class="text-xl font-bold text-purple-600">Rp ${new Intl.NumberFormat('id-ID').format(hargaKonsultasi)}</div>
      </div>
      <div class="flex items-center gap-3">
        <button onclick="closeModal()" 
                class="px-5 py-2.5 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
          Tutup
        </button>
        <button onclick="chatDokter(${doc.id_dokter})" 
                class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-purple-700 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all">
          💬 Chat Sekarang
        </button>
      </div>
    </div>
  `;
  
  // Load Google Maps jika ada koordinat
  if (lat && lng) {
    loadGoogleMap(lat, lng, alamat);
  }
}

/**
 * Close modal
 */
function closeModal() {
  modalDokter.classList.add('hidden');
  document.body.style.overflow = '';
}

/**
 * Load Google Maps (menggunakan iframe untuk menghindari API key requirement)
 */
function loadGoogleMap(lat, lng, alamat) {
  const mapContainer = document.getElementById('mapContainer');
  if (mapContainer) {
    mapContainer.innerHTML = `
      <iframe 
        width="100%" 
        height="100%" 
        style="border:0" 
        loading="lazy" 
        allowfullscreen
        referrerpolicy="no-referrer-when-downgrade"
        src="https://www.google.com/maps?q=${lat},${lng}&hl=id&z=15&output=embed">
      </iframe>
    `;
  }
}

// ==================== ACTION FUNCTIONS ====================

/**
 * Chat function
 */
function chatDokter(id_dokter) {
  window.location.href = `?route=chat&dokter=${id_dokter}`;
}

// ==================== EVENT LISTENERS ====================

// Debounced filter untuk search (akan diinisialisasi setelah DOM ready)
let debouncedFilter;

// ==================== INITIALIZATION ====================

/**
 * Initialize page
 */
document.addEventListener('DOMContentLoaded', () => {
  // Inisialisasi DOM elements setelah DOM ready
  searchInput = document.getElementById('searchInput');
  categoryCheckboxes = document.querySelectorAll('.category-checkbox');
  btnSemua = document.getElementById('btnSemua');
  doktersContainer = document.getElementById('doktersContainer');
  loadingIndicator = document.getElementById('loadingIndicator');
  emptyState = document.getElementById('emptyState');
  resultCount = document.getElementById('resultCount');
  modalDokter = document.getElementById('modalDokter');
  modalContent = document.getElementById('modalContent');

  // Pastikan semua element ada
  if (!searchInput || !doktersContainer || !loadingIndicator || !emptyState || !resultCount) {
    console.error('Error: Required DOM elements not found');
    return;
  }

  // Setup debounced filter
  debouncedFilter = debounceSearch(() => {
    filterAndDisplayDokters();
  }, 300);

  // Event listener untuk search input
  searchInput.addEventListener('input', (e) => {
    searchKeyword = e.target.value.trim();
    debouncedFilter();
  });

  // Event listener untuk checkbox kategori
  categoryCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', (e) => {
      updateSelectedCategories();
      updateSemuaButton();
      fetchDokters();
    });
  });

  // Update checkbox jika ada kategori dari URL
  if (selectedCategories.length > 0) {
    categoryCheckboxes.forEach(cb => {
      if (selectedCategories.includes(cb.value)) {
        cb.checked = true;
      }
    });
  } else {
    // Jika tidak ada kategori, centang semua (Semua)
    categoryCheckboxes.forEach(cb => cb.checked = true);
    updateSelectedCategories();
  }
  
  updateSemuaButton();
  fetchDokters();
});

