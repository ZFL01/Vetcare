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

function formatRupiah(val) {
  if (val === null || val === undefined || val === '' || isNaN(Number(val))) return '-';
  try {
    return new Intl.NumberFormat('id-ID').format(Number(val));
  } catch (_) {
    return String(val);
  }
}

/**
 * Format jadwal detail (Dioptimasi untuk multiple slot)
 */
function formatJadwalHariIni(jadwal) {
  if (!jadwal) return 'Jadwal tidak tersedia';

  const today = new Date();
  // Map day numbers to capitalized Indonesian day names (as stored in database)
  const daysInIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const hariIniNama = daysInIndonesian[today.getDay()];

  if (jadwal[hariIniNama] && jadwal[hariIniNama].length > 0) {
    const slots = jadwal[hariIniNama].map(jam => `${jam.buka} - ${jam.tutup}`);
    return slots.join(' / ');
  }

  return 'Tidak praktik hari ini';
}

// ==================== DATA NORMALIZATION HELPERS ====================
// Helpers to normalize DTO fields coming from server (different endpoints
// sometimes return slightly different property names).
function getDokName(d) {
  return (d.nama || d.nama_dokter || '') + '';
}

function getDokKategs(d) {
  const raw = d.kategori || d.kateg || [];
  if (!Array.isArray(raw)) return [];
  return raw.map(k => {
    if (!k && k !== 0) return '';
    if (typeof k === 'string') return k;
    return (k.nama_kateg || k.nama_kategori || k.namaK || k.nama || '') + '';
  });
}

function getDokId(d) {
  return d.id ?? d.id_dokter ?? null;
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
      console.log('[KATEGORI CHANGE] selectedCategoryName:', selectedCategoryName);

      // When switching to "Semua Kategori" (empty value), optionally clear search too
      // Uncomment line below if you want to auto-clear search when clicking "all"
      // if (!selectedCategoryName) {
      //   searchKeyword = '';
      //   searchInput.value = '';
      // }

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
  fetch('controller/pilih_dokter_controller.php?api=true')
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
    const urlValue = urlKategoriElement.getAttribute('value') || '';
    selectedCategoryName = urlValue;
    console.log('[INIT] selectedCategoryName from URL:', selectedCategoryName);

    // Also update the radio button to reflect URL state
    const matchingRadio = document.querySelector(`.category-radio[value="${urlValue}"]`);
    if (matchingRadio) {
      matchingRadio.checked = true;
      console.log('[INIT] Radio button checked for:', urlValue);
    }
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
    console.log('[SEARCH INPUT] searchKeyword:', searchKeyword);
    debouncedFilter();

    // Update URL dengan parameter search
    const urlParams = new URLSearchParams(window.location.search);
    if (searchKeyword) {
      urlParams.set('search', searchKeyword);
    } else {
      urlParams.delete('search');
    }

    const newUrl = '?route=pilih-dokter&' + urlParams.toString();
    window.history.pushState({ path: newUrl }, '', newUrl);
  });
  filterAndDisplayDokters();
}


// ==================== FILTER & DISPLAY ====================

/**
 * Filter and display dokters (Support kategori + search)
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

  console.log('[FILTER] Start with allDokters.length:', allDokters.length);
  console.log('[FILTER] selectedCategoryName:', selectedCategoryName, 'searchKeyword:', searchKeyword);

  // Filter by kategori (jika dipilih)
  if (selectedCategoryName) {
    const categoryLower = selectedCategoryName.toLowerCase();
    filteredDokters = filteredDokters.filter(doc => {
      const kategs = getDokKategs(doc);
      const match = kategs.some(k => k.toLowerCase() === categoryLower);
      return match;
    });
    console.log('[FILTER] After kategori filter:', filteredDokters.length);
  }

  // Filter by search keyword (jika ada)
  if (searchKeyword) {
    const keywordLower = searchKeyword.toLowerCase();
    filteredDokters = filteredDokters.filter(doc => {
      const name = getDokName(doc).toLowerCase();
      const namaMatch = name.includes(keywordLower);
      const kategs = getDokKategs(doc);
      const kategoriMatch = kategs.some(k => k.toLowerCase().includes(keywordLower));
      const result = namaMatch || kategoriMatch;
      if (result) {
        console.log('[SEARCH MATCH]', getDokName(doc), 'name match:', namaMatch, 'kateg match:', kategoriMatch);
      }
      return result;
    });
    console.log('[FILTER] After search filter:', filteredDokters.length);
  }

  resultCount.textContent = filteredDokters.length;
  renderDokters(filteredDokters);

  if (filteredDokters.length === 0) {
    showEmptyState();
  } else {
    doktersContainer.classList.remove('hidden');
    emptyState.classList.add('hidden');
  }
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

  const html = dokters.map(dokter => {
    const kategs = getDokKategs(dokter);
    const kategoriList = kategs.join(', ');
    const pengalaman = typeof dokter.pengalaman === 'number' ? dokter.pengalaman : (dokter.pengalaman ?? 0);
    const namaKlinik = dokter.klinik || dokter.namaKlinik || dokter.nama_klinik || '';
    const alamat = dokter.alamat || '';
    const idForModal = getDokId(dokter);
    const displayName = getDokName(dokter) || 'Dokter';
    const foto = dokter.foto || dokter.urlFoto || null;
    const rate = dokter.rate ?? '-';
    const jadwalHariIni = formatJadwalHariIni(dokter.jadwal);
    const harga = dokter.harga;

    return `
      <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100 w-full flex flex-col justify-between min-h-0 cursor-pointer" onclick="showModal(${idForModal})">
        <div>
            <div class="flex items-center gap-4 mb-4">
              <div class="flex-shrink-0">
                ${foto
        ? `<img src="${escapeHtml(foto)}" alt="Foto Dokter" class="w-20 h-20 rounded-full object-cover border-2 border-purple-200 shadow-md"/>`
        : `<div class="w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-3xl text-white">👩‍⚕️</div>`
      }
              </div>
              <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900">${escapeHtml(displayName)}</h3> 
                <p class="text-sm text-gray-600"> Spesialis ${escapeHtml(kategoriList || 'Spesialisasi belum diatur')}</p>
                <div class="flex items-center gap-3 mt-2 text-sm text-gray-600">
                  <span class="flex items-center gap-1"><span class="text-yellow-400">⭐</span>${escapeHtml(String(rate))}</span>
                  <span>•</span>
                  <span>${escapeHtml(String(pengalaman))} tahun</span>
                </div>
              </div>
            </div>

            

            <div class="space-y-4 text-sm">
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 flex-shrink-0 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 text-base">🕘</div>
                <div class="font-medium text-gray-800">Jadwal Hari Ini: ${escapeHtml(jadwalHariIni)}</div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 flex-shrink-0 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 text-base">📍</div>
                <div>
                  <div class="font-medium text-gray-800">${escapeHtml(namaKlinik || 'Klinik belum diatur')}</div>
                  <div class="text-gray-500">${escapeHtml(alamat || 'Alamat tidak tersedia')}</div>
                </div>
              </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-100">
          <div class="text-purple-600 font-semibold text-lg">Rp ${formatRupiah(harga)}</div>
          <button class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow" onclick="window.location.href = '?route=chat&dokter_id=${idForModal}'; event.stopPropagation();">
            <span class="text-base"></span>
            Chat Sekarang
          </button>
        </div>
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

  const dokter = allDokters.find(d => (d.id ?? d.id_dokter) === idDokter);
  if (!dokter) return;

  const foto = dokter.foto || dokter.urlFoto || null;
  const nama = getDokName(dokter);
  const kategs = getDokKategs(dokter).join(', ');
  const klinik = dokter.klinik || dokter.namaKlinik || dokter.nama_klinik || '';
  const alamat = dokter.alamat || '';
  const harga = dokter.harga ?? dokter.price ?? null;

  // Build full jadwal HTML
  let jadwalHtml = '';
  if (dokter.jadwal && typeof dokter.jadwal === 'object') {
    const hariKeys = Object.keys(dokter.jadwal);
    if (hariKeys.length === 0) {
      jadwalHtml = '<div class="text-xs text-gray-600">Jadwal tidak tersedia</div>';
    } else {
      jadwalHtml = '<div class="space-y-1">';
      hariKeys.forEach(h => {
        const slots = dokter.jadwal[h] || [];
        const slotsText = (Array.isArray(slots) && slots.length > 0)
          ? slots.map(s => `${s.buka} - ${s.tutup}`).join(' / ')
          : 'Tidak praktik';
        jadwalHtml += `
          <div class="flex items-start justify-between px-2 py-1 text-xs">
            <div class="font-medium text-gray-700">${escapeHtml(h)}</div>
            <div class="text-gray-600">${escapeHtml(slotsText)}</div>
          </div>`;
      });
      jadwalHtml += '</div>';
    }
  } else {
    jadwalHtml = '<div class="text-xs text-gray-600">Jadwal tidak tersedia</div>';
  }

  // Map / koordinat
  let mapHtml = '';
  let mapContainerId = null;
  if (dokter.koor && Array.isArray(dokter.koor) && dokter.koor.length === 2) {
    const lat = dokter.koor[0];
    const lng = dokter.koor[1];
    mapContainerId = 'doctor-map-' + idDokter;
    mapHtml = `
      <div class="mt-3">
        <div id="${mapContainerId}" style="height:260px;border-radius:10px;overflow:hidden;border:1px solid #eee"></div>
      </div>`;
  }

  const html = `
    <div class="flex flex-col gap-3">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
          ${foto ? `<img src="${escapeHtml(foto)}" alt="Foto Dokter" class="w-20 h-20 rounded-full object-cover border-2 border-purple-200 shadow-md"/>` : `<div class="w-20 h-20 rounded-full bg-purple-100 flex items-center justify-center text-2xl">👩‍⚕️</div>`}
        </div>
        <div class="flex-1">
          <h2 class="text-base font-bold text-gray-800">${escapeHtml(nama)}</h2>
          <p class="text-xs text-purple-600">${escapeHtml(kategs || 'Spesialisasi belum diatur')}</p>
          <div class="mt-1 text-xs text-gray-500 font-medium">
            ⭐ ${dokter.rate || 0} (${dokter.pengalaman || 0} tahun)
          </div>
        </div>
      </div>

      <div class="p-0 bg-transparent">
        <p class="text-xs font-medium text-purple-700 mb-2">Jadwal Praktik</p>
        ${jadwalHtml}
      </div>

      <div>
        <p class="text-xs font-medium text-gray-700 mb-2">📍 Lokasi Praktik</p>
        <p class="text-xs text-gray-700 font-medium">${escapeHtml(klinik)}</p>
        <p class="text-xs text-gray-600">${escapeHtml(alamat)}</p>
      </div>

      ${mapHtml}

      <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
        <div class="text-purple-600 font-semibold text-base">Rp ${formatRupiah(harga)}</div>
        <div class="flex gap-3">
          <button onclick="document.getElementById('modalDokter').classList.add('hidden')" class="px-4 py-2 text-sm bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Tutup</button>
          <button onclick="window.location.href='?route=chat&dokter_id=${idDokter}'" class="px-4 py-2 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700">Chat</button>
        </div>
      </div>
    </div>
  `;

  modalContent.innerHTML = html;
  modalDokter.classList.remove('hidden');
  // Initialize map if coordinates present. Prefer Google Maps if API key provided,
  // otherwise fall back to an OpenStreetMap iframe so the user always sees a map.
  if (mapContainerId) {
    const coords = dokter.koor;
    const container = document.getElementById(mapContainerId);
    const hasGmapsKey = !!(window.GOOGLE_MAPS_API_KEY && window.GOOGLE_MAPS_API_KEY.trim());

    function renderOsmFallback(lat, lng, targetEl) {
      // Use an OSM static iframe (no API key required)
      const src = `https://www.openstreetmap.org/export/embed.html?bbox=${encodeURIComponent(Number(lng)-0.01)}%2C${encodeURIComponent(Number(lat)-0.01)}%2C${encodeURIComponent(Number(lng)+0.01)}%2C${encodeURIComponent(Number(lat)+0.01)}&layer=mapnik&marker=${encodeURIComponent(Number(lat))}%2C${encodeURIComponent(Number(lng))}`;
      targetEl.innerHTML = `<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="${src}" style="border:0;border-radius:12px"></iframe>`;
    }

    if (hasGmapsKey && window.VetcareMap && typeof window.VetcareMap.initDoctorMap === 'function') {
      window.VetcareMap.initDoctorMap(mapContainerId, Number(coords[0]), Number(coords[1]))
        .catch(err => {
          console.warn('Map init failed:', err.message || err);
          try { renderOsmFallback(coords[0], coords[1], container); } catch(e){}
        });
    } else {
      // No Google Maps key / API available -> render OSM fallback
      try { renderOsmFallback(coords[0], coords[1], container); } catch(e){}
    }
  }
}


/**
 * Menutup modal detail dokter.
 */
function closeModal() {
  if (!modalDokter) return;
  modalDokter.classList.add('hidden');
}


// ==================== STARTUP ====================
document.addEventListener('DOMContentLoaded', initDokters);