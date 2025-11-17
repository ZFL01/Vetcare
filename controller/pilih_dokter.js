/**
 * JavaScript Controller untuk halaman pilih-dokter
 * Menangani semua logika frontend dan interaksi user (fokus pada search dan modal)
 */

// ==================== DATA STORE & INITIAL STATE ====================
let allDokters = []; // Diisi oleh PHP saat halaman dimuat
let selectedCategoryName = ''; // Nilai nama kategori penuh dari URL
let searchKeyword = '';

// Data dari PHP (Disimpan jika dibutuhkan, tapi tidak digunakan untuk logic filter)
let slugToKategori = {};
try {
  // Mengambil JSON dari tag <script> yang di-echo oleh PHP
  const slugElement = document.getElementById('slugToKategori');
  if (slugElement) {
    slugToKategori = JSON.parse(slugElement.textContent || '{}');
  }
} catch (e) {
  console.error('Error parsing slugToKategori:', e);
}

// Handle kategori aktif dari URL (nama kategori penuh)
const urlKategoriElement = document.getElementById('urlKategori');
const urlKategori = urlKategoriElement ? urlKategoriElement.textContent.trim() : '';

if (urlKategori) {
  // Langsung gunakan nama kategori penuh
  selectedCategoryName = urlKategori;
}


// ==================== DOM ELEMENTS ====================
// Akan diinisialisasi setelah DOM ready
let searchInput;
let categoryRadioButtons; // Diganti dari categoryCheckboxes
let doktersContainer;
let loadingIndicator;
let emptyState;
let resultCount;
let modalDokter;
let modalContent;


// ==================== UTILITY FUNCTIONS ====================

/**
 * Debounce function untuk optimasi search (Dipertahankan)
 */
let searchTimeout;
function debounceSearch(func, delay) {
  return function (...args) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => func.apply(this, args), delay);
  };
}

/**
 * Escape HTML untuk mencegah XSS (Dipertahankan)
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
 * Format jadwal detail (Dioptimasi untuk multiple slot)
 */
function formatJadwalDetail(jadwal) {
  if (!jadwal || Object.keys(jadwal).length === 0) {
    return {
      hariIni: 'Jadwal tidak tersedia',
      hariPraktik: 'Jadwal tidak tersedia'
    };
  }

  const today = new Date();
  const dayIndex = today.getDay();
  const daysInIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const hariIniNama = daysInIndonesian[dayIndex];

  // Cek jadwal hari ini
  let jadwalHariIni = 'Tidak praktik hari ini';

  if (jadwal[hariIniNama] && jadwal[hariIniNama].length > 0) {
    const slots = jadwal[hariIniNama].map(jam => `${jam.buka} - ${jam.tutup}`);
    jadwalHariIni = slots.join(' / ');
  }

  // Kumpulkan semua hari praktik
  const hariPraktik = [];
  for (let day in jadwal) {
    // Cek jika hari tersebut valid dan memiliki jadwal
    if (jadwal[day] && jadwal[day].length > 0 && daysInIndonesian.includes(day)) {
      hariPraktik.push(day);
    }
  }

  return {
    hariIni: jadwalHariIni,
    hariPraktik: hariPraktik.length > 0 ? hariPraktik.join(', ') : 'Jadwal tidak tersedia'
  };
}


// ==================== INITIALIZATION ====================
function unescapeHtml(text) {
  const doc = new DOMParser().parseFromString(text, 'text/html');
  return doc.documentElement.textContent;
}

function initDokters() {
  // Inisialisasi DOM elements
  searchInput = document.getElementById('searchInput');
  categoryRadioButtons = document.querySelectorAll('.category-radio');
  doktersContainer = document.getElementById('doktersContainer');
  loadingIndicator = document.getElementById('loadingIndicator');
  emptyState = document.getElementById('emptyState');
  resultCount = document.getElementById('resultCount');
  modalDokter = document.getElementById('modalDokter');
  modalContent = document.getElementById('modalContent');

  categoryRadioButtons = document.querySelectorAll('.category-radio');

  categoryRadioButtons.forEach(radio => {
    radio.addEventListener('change', (e) => {
      selectedCategoryName = e.target.value.trim();
      const newUrl = selectedCategoryName
        ? '?route=pilih-dokter&kategori=' + encodeURIComponent(selectedCategoryName)
        : '?route=pilih-dokter';
      window.history.pushState({ path: newUrl }, '', newUrl);

      filterAndDisplayDokters();
    });
  });

  // const dokterDataElement = document.getElementById('dokterData');
  // if (dokterDataElement) {
  //   try {
  //     const escapedRawData = dokterDataElement.getAttribute('value') || '[]';
  //     const rawData = unescapeHtml(escapedRawData);
  //     allDokters = JSON.parse(rawData);
  //     console.log('Successfully loaded dokters:', allDokters.length);
  //   } catch (e) {
  //     console.error('Error parsing initial dokter data:', e);
  //     allDokters = [];
  //   }
  // }
  loadingIndicator.classList.remove('hidden');
  fetch('../controller/pilih_dokter_controller.php?api=true')
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      allDokters = data;
      console.log("Data dokter berhasil di-fetch!", allDokters.length);
      filterAndDisplayDokters();
    })
    .catch(error => {
      console.error('Error fetching dokter data:', error);
      doktersContainer.innerHTML = `<p class="text-red-600">Gagal memuat data dokter: ${error.message}</p>`;
      loadingIndicator.classList.add('hidden');
    });

  const urlKategoriElement = document.getElementById('urlKategori');
  if (urlKategoriElement) {
    selectedCategoryName = urlKategoriElement.getAttribute('value') || '';
  }

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
  filterAndDisplayDokters();
}


// ==================== FILTER & DISPLAY ====================

/**
 * Filter and display dokters (Hanya untuk Search Keyword)
 */
function filterAndDisplayDokters() {
  if (!loadingIndicator || !doktersContainer || !emptyState || !resultCount) {
    return;
  }
  loadingIndicator.classList.add('hidden');
  let filteredDokters = allDokters;
  if (filteredDokters.length === 0) {
    showEmptyState();
    return;
  }
  if (selectedCategoryName) {
    const categoryLower = selectedCategoryName.toLowerCase();
    filteredDokters = filteredDokters.filter(doc => {
      // Asumsi doc.kateg adalah array of strings (nama kategori)
      return doc.kategori && doc.kategori.some(kat =>
        kat.toLowerCase() === categoryLower // Pencocokan harus eksak
      );
    });
  }

  if (searchKeyword) {
    const keywordLower = searchKeyword.toLowerCase();
    filteredDokters = filteredDokters.filter(doc => {
      const namaMatch = doc.nama_dokter.toLowerCase().includes(keywordLower);
      const kategoriMatch = doc.kateg && doc.kateg.some(kat =>
        kat.toLowerCase().includes(keywordLower)
      );
      return namaMatch || kategoriMatch;
    });
  }
  resultCount.textContent = filteredDokters.length;

  renderDokters(filteredDokters);
  doktersContainer.classList.remove('hidden');
  emptyState.classList.add('hidden');
}

/**
 * Show empty state
 */
function showEmptyState() {
  if (!doktersContainer || !loadingIndicator || !emptyState) {
    return;
  }
  doktersContainer.classList.add('hidden');
  loadingIndicator.classList.add('hidden');
  emptyState.classList.remove('hidden');
}


// ==================== RENDERING DAN MODAL ====================

/**
 * Merender daftar kartu dokter ke dalam DOM.
 */
function renderDokters(dokters) {
  if (!doktersContainer) return;

  // Implementasi rendering kartu dokter ke doktersContainer
  const html = dokters.map(dokter => {
    const kategoriList = Array.isArray(dokter.kategori) ? dokter.kategori.join(', ') : '';
    const pengalaman = dokter.pengalaman < new Date().getFullYear() ? new Date().getFullYear() - dokter.pengalaman : 0;
    const namaKlinik = dokter.klinik || 'Klinik Tidak Diketahui';
    // Pastikan Anda memanggil showModal dengan id_dokter yang benar
    return `
            <div class="shadow-card p-6 rounded-xl cursor-pointer" onclick="showModal(${dokter.id})">
                <h3 class="text-lg font-semibold text-gray-800">${escapeHtml(dokter.nama)}</h3>
                <p class="text-sm text-purple-600 mb-2">${escapeHtml(kategoriList)}</p>
                <p class="text-gray-500">Pengalaman: ${pengalaman} tahun</p>
                <p class="text-gray-500">Klinik: ${escapeHtml(namaKlinik)}</p>
            </div>
        `;
  }).join('');

  doktersContainer.innerHTML = html;
}


/**
 * Menampilkan modal detail dokter.
 */
function showModal(idDokter) {
  if (!modalDokter || !modalContent) return;

  const dokter = allDokters.find(d => d.id_dokter === idDokter);
  if (!dokter) return;

  const jadwalFormatted = formatJadwalDetail(dokter.jadwal);

  const html = `
        <h2 class="text-2xl font-bold text-gray-800 mb-4">${escapeHtml(dokter.nama_dokter)}</h2>
        
        <div class="mb-4">
            <p class="text-sm font-medium text-purple-600">Kategori Spesialisasi:</p>
            <p class="text-base text-gray-700">${escapeHtml(dokter.kateg.join(', '))}</p>
        </div>
        
        <div class="mb-4">
            <p class="text-sm font-medium text-purple-600">Klinik:</p>
            <p class="text-base text-gray-700">${escapeHtml(dokter.namaKlinik)} (${escapeHtml(dokter.alamat)})</p>
        </div>

        <div class="mb-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
            <p class="text-sm font-medium text-purple-700 mb-1">Jadwal Hari Ini (${jadwalFormatted.hariIni.startsWith('Tidak') ? '❌' : '✅'}):</p>
            <p class="text-base font-semibold text-gray-800">${escapeHtml(jadwalFormatted.hariIni)}</p>
        </div>

        <div class="mb-4">
            <p class="text-sm font-medium text-purple-600">Semua Hari Praktik:</p>
            <p class="text-base text-gray-700">${escapeHtml(jadwalFormatted.hariPraktik)}</p>
        </div>

        <div class="mt-6">
            <button onclick="document.getElementById('modalDokter').classList.add('hidden')" class="w-full py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                Tutup
            </button>
        </div>
    `;

  modalContent.innerHTML = html;
  modalDokter.classList.remove('hidden');
}


// ==================== STARTUP ====================
document.addEventListener('DOMContentLoaded', initDokters);