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

      const newUrl = selectedCategoryName
        ? '?route=pilih-dokter&kategori=' + encodeURIComponent(selectedCategoryName)
        : '?route=pilih-dokter';
      window.history.pushState({ path: newUrl }, '', newUrl);

      filterAndDisplayDokters();
    });
  });

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

  // 1. Filter Kategori
  if (selectedCategoryName) {
    const categoryLower = selectedCategoryName.toLowerCase();
    filteredDokters = filteredDokters.filter(doc => {
      const kategs = getDokKategs(doc);
      return kategs.some(k => k.toLowerCase() === categoryLower);
    });
  }

  // 2. Filter Search
  if (searchKeyword) {
    const keywordLower = searchKeyword.toLowerCase();
    filteredDokters = filteredDokters.filter(doc => {
      const name = getDokName(doc).toLowerCase();
      const namaMatch = name.includes(keywordLower);
      const kategs = getDokKategs(doc);
      const kategoriMatch = kategs.some(k => k.toLowerCase().includes(keywordLower));
      return namaMatch || kategoriMatch;
    });
  }

  // --- 3. SORTING LOGIC (PERBAIKAN DISINI) ---
  // Urutan: Hijau (Buka) -> Kuning (Akan Buka) -> Kelabu (Tutup)
  filteredDokters.sort((a, b) => {
    // Cek status Available Now (Hijau)
    const aOpen = a.available_now === true;
    const bOpen = b.available_now === true;

    if (aOpen && !bOpen) return -1; // a naik
    if (!aOpen && bOpen) return 1;  // b naik

    // Cek status "Kembali" / Waiting (Kuning)
    const aWait = a.status_text && a.status_text.toLowerCase().includes('kembali');
    const bWait = b.status_text && b.status_text.toLowerCase().includes('kembali');

    if (aWait && !bWait) return -1;
    if (!aWait && bWait) return 1;

    return 0; // Sama kuat
  });
  // -------------------------------------------

  resultCount.textContent = filteredDokters.length;

  if (filteredDokters.length === 0) {
    doktersContainer.classList.add('hidden');
    emptyState.classList.remove('hidden');
    return;
  } else {
    doktersContainer.classList.remove('hidden');
    emptyState.classList.add('hidden');
  }

  // Render ke HTML
  renderDokters(filteredDokters);
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
    // --- 1. DATA PREPARATION ---
    const idForModal = getDokId(dokter);
    const nama = escapeHtml(getDokName(dokter) || 'Dokter');

    // Kategori
    const kategs = getDokKategs(dokter);
    const kategoriList = escapeHtml(kategs.join(', ') || 'Umum');

    // Pengalaman
    const tahunSekarang = new Date().getFullYear();
    const tahunMulai = parseInt(dokter.pengalaman) || tahunSekarang;
    const lamaPraktik = tahunSekarang - tahunMulai;
    const teksPengalaman = lamaPraktik > 0 ? `${lamaPraktik} tahun` : 'Baru';

    // Rating
    let rateVal = parseFloat(dokter.rate || 0);
    if (rateVal <= 1 && rateVal > 0) rateVal = rateVal * 5;
    const displayRate = rateVal.toFixed(1);

    // Lokasi
    const namaKlinik = escapeHtml(dokter.klinik || dokter.namaKlinik || 'Klinik belum diatur');
    const kab = dokter.kabupaten || dokter.kab || '';
    const prov = dokter.provinsi || dokter.prov || '';
    let alamatFull = 'Indonesia';
    if (kab || prov) {
      alamatFull = [kab, prov].filter(Boolean).join(', ');
    }

    // Foto Profile
    const fotoFilename = dokter.foto || dokter.urlFoto;
    let imgHtml;
    if (fotoFilename) {
      imgHtml = `
        <img 
          src="public/img/dokter-profil/${escapeHtml(fotoFilename)}" 
          alt="${nama}" 
          class="w-full h-full object-cover"
          onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-3xl\'>👨‍⚕️</div>';"
        />`;
    } else {
      imgHtml = `<div class="w-full h-full flex items-center justify-center text-purple-600 text-3xl font-bold bg-purple-50">${nama.charAt(0)}</div>`;
    }

    // --- 2. LOGIKA WARNA STATUS (3 WARNA) ---
    const statusText = escapeHtml(dokter.status_text || 'Tutup hari ini');
    const isAvailable = dokter.available_now === true;

    let statusClass = '';

    if (isAvailable) {
      // HIJAU (Tersedia Sekarang)
      statusClass = 'text-green-700 bg-green-100 border-green-200';
    } else if (statusText.toLowerCase().includes('kembali')) {
      // KUNING (Tersedia Nanti/Waiting)
      statusClass = 'text-yellow-700 bg-yellow-100 border-yellow-200';
    } else {
      // KELABU (Tutup)
      statusClass = 'text-gray-500 bg-gray-100 border-gray-200';
    }

    const harga = formatRupiah(dokter.harga);

    // --- 3. HTML STRUCTURE ---
    return `
      <div 
        onclick="showModal(${idForModal})"
        class="bg-white rounded-2xl p-6 shadow-card hover:shadow-xl transition-all duration-300 group cursor-pointer relative flex flex-col h-full border border-gray-100">
        
        <div class="flex gap-6 mb-4">
          
          <div class="flex-shrink-0">
            <div class="w-20 h-20 rounded-full overflow-hidden ring-4 ring-gray-50 group-hover:ring-purple-100 transition-all shadow-sm">
              ${imgHtml}
            </div>
          </div>

          <div class="flex-1 min-w-0 flex flex-col gap-1">
            <h3 class="font-bold text-gray-900 text-xl truncate leading-tight group-hover:text-purple-700 transition-colors">
              ${nama}
            </h3>
            <p class="text-sm text-purple-600 font-medium truncate">${kategoriList}</p>
            
            <div class="mt-1">
                 <span class="inline-block px-3 py-1 text-xs font-bold rounded-lg border ${statusClass}">
                   ${statusText}
                 </span>
            </div>
          </div>
        </div>

        <div class="flex-1 space-y-3 mb-6">
            <div class="flex items-center gap-3 text-sm text-gray-600">
              <span class="flex items-center gap-1 font-bold text-yellow-500 bg-yellow-50 px-2 py-0.5 rounded-md">
                ⭐ ${displayRate}
              </span>
              <span class="flex items-center gap-1 font-medium bg-blue-50 text-blue-700 px-2 py-0.5 rounded-md">
                💼 ${teksPengalaman}
              </span>
            </div>

            <div class="text-sm text-gray-500 border-t border-dashed border-gray-100 pt-3">
               <div class="font-bold text-gray-700 flex items-center gap-1.5 mb-0.5">
                 <span class="text-red-400">📍</span> ${namaKlinik}
               </div>
               <div class="truncate text-gray-400 ml-5 text-xs">${escapeHtml(alamatFull)}</div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between mt-auto">
          <div class="flex flex-col">
             <span class="text-purple-700 font-bold text-xl">Rp ${harga}</span>
          </div>
          
          <button 
            onclick="event.stopPropagation(); window.currentDokterId=${idForModal}; openKonsultasiModal()"
            class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-purple-200 transition-transform active:scale-95 transform hover:-translate-y-0.5">
            Chat Sekarang
          </button>
        </div>
      </div>
    `;
  }).join('');

  doktersContainer.innerHTML = html;
}

/**
 * Menampilkan Modal Detail Dokter dengan FETCH API
 */
async function showModal(idDokter) {
  if (!modalDokter || !modalContent) return;

  // 1. Ambil data dasar dari Array Javascript (Data yang sudah ada)
  const dokter = allDokters.find(d => (d.id ?? d.id_dokter) === idDokter);
  if (!dokter) return;

  // Render Layout Dasar (Langsung muncul)
  const nama = escapeHtml(getDokName(dokter));
  const kategs = escapeHtml(getDokKategs(dokter).join(', ') || 'Umum');
  const harga = formatRupiah(dokter.harga);

  // Rating & Pengalaman
  const tahunSekarang = new Date().getFullYear();
  const tahunMulai = parseInt(dokter.pengalaman) || tahunSekarang;
  const lamaPraktik = tahunSekarang - tahunMulai;
  const teksPengalaman = lamaPraktik > 0 ? `${lamaPraktik} tahun` : 'Baru';

  let rateVal = parseFloat(dokter.rate || 0);
  if (rateVal <= 1 && rateVal > 0) rateVal = rateVal * 5;
  const displayRate = rateVal.toFixed(1);

  // Foto
  const fotoFilename = dokter.foto || dokter.urlFoto;
  const fotoUrl = fotoFilename ? `public/img/dokter-profil/${fotoFilename}` : null;

  // Jadwal (Sudah ada di data dasar, jadi langsung render)
  let jadwalHtml = '';
  if (dokter.jadwal && typeof dokter.jadwal === 'object' && Object.keys(dokter.jadwal).length > 0) {
    jadwalHtml = '<div class="space-y-3 mt-2">';
    for (const [hari, slots] of Object.entries(dokter.jadwal)) {
      const slotStr = Array.isArray(slots)
        ? slots.map(s => {
          const b = s.buka ? s.buka.substring(0, 5) : '';
          const t = s.tutup ? s.tutup.substring(0, 5) : '';
          return `${b} - ${t}`;
        }).join(', ')
        : '';

      jadwalHtml += `
            <div class="flex justify-between text-sm border-b border-gray-100 pb-2 last:border-0">
                <span class="font-medium text-gray-700 w-24">${hari}</span>
                <span class="text-gray-600 text-right flex-1">${slotStr}</span>
            </div>`;
    }
    jadwalHtml += '</div>';
  } else {
    jadwalHtml = '<div class="text-sm text-gray-400 italic py-2">Jadwal praktik belum diatur.</div>';
  }

  const mapContainerId = 'doctor-map-modal-' + idDokter;

  // 2. Render HTML (Lokasi & Map kita set LOADING dulu)
  const html = `
    <div class="flex flex-col h-full">
      <div class="flex items-start gap-4 mb-6">
        <div class="flex-shrink-0">
            ${fotoUrl
      ? `<img src="${escapeHtml(fotoUrl)}" class="w-20 h-20 rounded-full object-cover border-2 border-white shadow-md" onerror="this.src='https://via.placeholder.com/150?text=Dokter'"/>`
      : `<div class="w-20 h-20 rounded-full bg-purple-50 flex items-center justify-center text-3xl font-bold text-purple-600 shadow-sm">${nama.charAt(0)}</div>`}
        </div>
        <div class="pt-1">
           <h2 class="text-xl font-bold text-gray-900 leading-tight mb-1">${nama}</h2>
           <p class="text-sm text-purple-600 font-bold mb-2">${kategs}</p>
           <div class="flex items-center gap-2 text-sm text-gray-500">
             <span class="flex items-center gap-1 text-yellow-500 font-bold">⭐ ${displayRate}</span>
             <span class="text-gray-300">|</span>
             <span>(${teksPengalaman})</span> 
           </div>
        </div>
      </div>

      <div class="space-y-6">
          <div>
            <h3 class="text-sm font-bold text-purple-700 mb-1">Jadwal Praktik Mingguan</h3>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                ${jadwalHtml}
            </div>
          </div>

          <div>
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-2">
               📍 Lokasi Praktik
            </h3>
            <p id="modal-klinik-name" class="text-sm text-gray-600 font-medium mb-3 ml-1 animate-pulse">Memuat lokasi...</p>
            
            <div id="${mapContainerId}" class="w-full h-64 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 relative shadow-inner">
                 <div class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs flex-col gap-2">
                    <div class="w-6 h-6 border-2 border-purple-600 border-t-transparent rounded-full animate-spin"></div>
                    <span>Mengambil Peta...</span>
                 </div>
            </div>
          </div>
      </div>

      <div class="mt-8 pt-4 border-t border-gray-100 flex items-center justify-between gap-4 sticky bottom-0 bg-white">
        <span class="text-2xl font-bold text-purple-700">Rp ${harga}</span>
        <div class="flex gap-3">
             <button onclick="closeModal()" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition">
                Tutup
             </button>
             <button onclick="window.currentDokterId=${idDokter}; openKonsultasiModal()" class="px-8 py-2.5 rounded-xl bg-purple-600 text-white text-sm font-bold hover:bg-purple-700 shadow-lg shadow-purple-200 transition transform hover:-translate-y-0.5">
                Chat
             </button>
        </div>
      </div>
    </div>
  `;

  modalContent.innerHTML = html;
  modalDokter.classList.remove('hidden');

  // 3. FETCH DATA DETAIL (ASYNCHRONOUS)
  try {
    const response = await fetch(`controller/pilih_dokter_controller.php?action=get_detail&id=${idDokter}`);
    const result = await response.json();

    if (result.success && result.data) {
      const detail = result.data;
      const klinikName = detail.klinik || detail.namaKlinik || detail.nama_klinik || 'Klinik belum diatur';

      // Update Nama Klinik
      const klinikEl = document.getElementById('modal-klinik-name');
      if (klinikEl) {
        klinikEl.textContent = klinikName;
        klinikEl.classList.remove('animate-pulse');
      }

      // Update Map
      if (detail.koor && Array.isArray(detail.koor)) {
        const lat = Number(detail.koor[0]);
        const lng = Number(detail.koor[1]);
        const container = document.getElementById(mapContainerId);

        if (container) {
          const bbox = `${lng - 0.005}%2C${lat - 0.005}%2C${lng + 0.005}%2C${lat + 0.005}`;
          container.innerHTML = `<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=${bbox}&layer=mapnik&marker=${lat}%2C${lng}" style="border:0"></iframe>`;
        }
      } else {
        const container = document.getElementById(mapContainerId);
        if (container) container.innerHTML = '<div class="w-full h-full flex items-center justify-center text-gray-400 text-xs bg-gray-50 text-center px-4">Lokasi peta belum diatur oleh dokter</div>';
      }

    }
  } catch (error) {
    console.error('Gagal mengambil detail dokter:', error);
    const container = document.getElementById(mapContainerId);
    if (container) container.innerHTML = '<div class="w-full h-full flex items-center justify-center text-red-400 text-xs bg-red-50">Gagal memuat peta</div>';
  }
}

function closeModal() {
  if (!modalDokter) return;
  modalDokter.classList.add('hidden');
}

// ==================== STARTUP ====================
document.addEventListener('DOMContentLoaded', initDokters);