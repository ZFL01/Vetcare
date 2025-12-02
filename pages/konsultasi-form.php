<?php
/**
 * Konsultasi Form Modal
 * Pop-up form yang muncul sebelum user masuk ke 
 *  dokter
 * Berisi: Nama Hewan, Jenis Hewan, Usia Hewan, Keluhan/Gejala
 * Plus section Kasus Darurat dengan daftar klinik terdekat
 */
$user = $_SESSION['user']->getIdUser();
?>

<!-- Modal Konsultasi Form -->
<div id="konsultasiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen px-4">
    <!-- Backdrop -->
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeKonsultasiModal()"></div>

    <!-- Modal Panel -->
    <div
      class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl sm:w-full"
      style="max-width:600px !important;">
      <div class="bg-white px-6 py-4" style="max-height:85vh; overflow:auto;">
        <!-- Header dengan background gradient purple -->
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
          <div>
            <h3 class="text-2xl font-bold text-gray-800">Informasi Konsultasi</h3>
            <p class="text-sm text-purple-600 mt-1">dengan <span id="dokterNama">Dokter Hewan</span></p>
          </div>
          <button onclick="closeKonsultasiModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Info Box dengan warna blue yang sesuai tema -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
          <p class="text-sm text-blue-800">
            <span class="font-medium">ℹ️</span> Informasi yang Anda berikan akan membantu dokter hewan memberikan
            konsultasi yang lebih akurat dan tepat.
          </p>
        </div>

        <!-- Form -->
        <form id="konsultasiForm" onsubmit="submitKonsultasi(event)" class="space-y-6">

          <!-- Nama Hewan -->
          <div>
            <label class="block text-lg font-semibold text-gray-800 mb-2">
              Nama Hewan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_hewan" placeholder="Contoh: Kitty, Max, Bella"
              class="w-full px-4 py-3 bg-gray-100 rounded-lg border border-gray-200 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all"
              required>
          </div>

          <!-- Jenis Hewan -->
          <div>
            <label class="block text-lg font-semibold text-gray-800 mb-2">
              Jenis Hewan <span class="text-red-500">*</span>
            </label>
            <select name="jenis_hewan"
              class="w-full px-4 py-3 bg-gray-100 rounded-lg border border-gray-200 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all appearance-none cursor-pointer"
              required>
              <option value="">Pilih jenis hewan</option>
              <option value="anjing">Anjing</option>
              <option value="kucing">Kucing</option>
              <option value="kelinci">Kelinci</option>
              <option value="hamster">Hamster</option>
              <option value="burung">Burung</option>
              <option value="reptil">Reptil</option>
              <option value="ikan">Ikan</option>
              <option value="lainnya">Lainnya</option>
            </select>
          </div>

          <!-- Usia Hewan -->
          <div>
            <label class="block text-lg font-semibold text-gray-800 mb-2">
              Usia Hewan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="usia_hewan" placeholder="Contoh: 2 tahun, 6 bulan, 3 minggu"
              class="w-full px-4 py-3 bg-gray-100 rounded-lg border border-gray-200 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all"
              required>
          </div>

          <!-- Keluhan / Gejala -->
          <div>
            <label class="block text-lg font-semibold text-gray-800 mb-2">
              Keluhan / Gejala <span class="text-red-500">*</span>
            </label>
            <textarea name="keluhan_gejala"
              placeholder="Jelaskan keluhan atau gejala yang dialami hewan Anda secara detail. Contoh: Tidak mau makan sejak 2 hari lalu, terlihat lemas, dan muntah beberapa kali."
              class="w-full px-4 py-3 bg-gray-100 rounded-lg border border-gray-200 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all resize-none"
              rows="5" minlength="10" required></textarea>
            <p class="text-xs text-gray-600 mt-1" id="charCount">0 karakter (minimal 10 karakter)</p>
          </div>

          <!-- Form Buttons -->
          <div class="flex gap-3 pt-4 border-t border-gray-200">
            <button type="button" onclick="closeKonsultasiModal()"
              class="flex-1 px-4 py-3 text-base bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
              Kembali
            </button>
            <button type="submit"
              class="flex-1 px-4 py-3 text-base bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all font-semibold shadow-md">
              Lanjut ke Konsultasi
            </button>
          </div>
        </form>

        <!-- Kasus Darurat Section -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200">
            <h3 class="text-lg font-bold text-red-800 mb-2">🚨 Kasus Darurat</h3>
            <p class="text-sm text-red-700">
              Jika hewan Anda mengalami kondisi darurat, segera hubungi klinik terdekat atau layanan darurat 24 jam.
            </p>
          </div>

          <h4 class="text-base font-semibold text-gray-800 mb-3">Klinik Terdekat</h4>
          <div class="space-y-3">
            <div
              class="flex items-start p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors cursor-pointer">
              <div class="flex-1">
                <p class="font-medium text-gray-800">Jati Mulnoyo Pet Care</p>
                <p class="text-xs text-gray-600 mt-1">Jl. Slamet Indeks 10, Surabaya</p>
                <p class="text-xs text-green-600 mt-1 font-semibold">• Buka 24 Jam</p>
              </div>
              <div class="text-purple-600 font-semibold text-xs bg-purple-100 px-3 py-1 rounded-full">3.2 km</div>
            </div>

            <div
              class="flex items-start p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors cursor-pointer">
              <div class="flex-1">
                <p class="font-medium text-gray-800">Rumah Sakit Hewan Pusat</p>
                <p class="text-xs text-gray-600 mt-1">Jl. Dr. Soebandi 45, Surabaya</p>
                <p class="text-xs text-orange-600 mt-1 font-semibold">• Tutup (Buka jam 06:00)</p>
              </div>
              <div class="text-purple-600 font-semibold text-xs bg-purple-100 px-3 py-1 rounded-full">5.1 km</div>
            </div>

            <div
              class="flex items-start p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors cursor-pointer">
              <div class="flex-1">
                <p class="font-medium text-gray-800">Klinik Hewan Mulia Jaya</p>
                <p class="text-xs text-gray-600 mt-1">Jl. Raya Wonokromo 78, Surabaya</p>
                <p class="text-xs text-green-600 mt-1 font-semibold">• Buka 24 Jam</p>
              </div>
              <div class="text-purple-600 font-semibold text-xs bg-purple-100 px-3 py-1 rounded-full">6.8 km</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="public/service.js"></script>
<script>
  // Track character count for textarea
  document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.querySelector('textarea[name="keluhan_gejala"]');
    const charCount = document.getElementById('charCount');

    if (textarea) {
      textarea.addEventListener('input', function () {
        charCount.textContent = this.value.length + ' karakter (minimal 10 karakter)';
      });
    }
  });

  function openKonsultasiModal() {
    const modal = document.getElementById('konsultasiModal');
    if (modal) {
      modal.classList.remove('hidden');
      // Reset form
      const form = document.getElementById('konsultasiForm');
      if (form) form.reset();

      // Get dokter name from allDokters jika ada
      if (window.currentDokterId && typeof allDokters !== 'undefined') {
        const dokter = allDokters.find(d => (d.id ?? d.id_dokter) === window.currentDokterId);
        const dokterNama = document.getElementById('dokterNama');
        if (dokter && dokterNama) {
          dokterNama.textContent = dokter.nama_dokter || dokter.nama || 'Dokter Hewan';
        }
      }
    }
  }

  function closeKonsultasiModal() {
    const modal = document.getElementById('konsultasiModal');
    if (modal) {
      modal.classList.add('hidden');
    }
  }

  function submitKonsultasi(event) {
    event.preventDefault();
    const form = document.getElementById('konsultasiForm');
    const formData = new FormData(form);

    const dokterId = window.currentDokterId;
    if (!dokterId) {
      alert('Error: Dokter tidak dipilih');
      closeKonsultasiModal();
      return;
    }

    // Collect form data
    const Konsuldata = {
      nama_hewan: formData.get('nama_hewan'),
      jenis_hewan: formData.get('jenis_hewan'),
      usia_hewan: formData.get('usia_hewan'),
      keluhan_gejala: formData.get('keluhan_gejala'),
      dokter_id: window.currentDokterId || null
    };

    console.log('[KONSULTASI] Data submitted:', Konsuldata);


    // Close modal

    // Redirect ke halaman chat-dokter dengan dokter ID
    let idUser = '<?php echo $user; ?>';
    let idChat = "C" + getTimestamp10() + '-U' + idUser + 'D' + dokterId;
    const initChatURL= '<?php BASE_URL;?>chat-api-service/controller_chat.php?action=initChat';

    fetch(initChatURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        id_chat: idChat,
        id_user: idUser,
        id_dokter: window.currentDokterId,
        formKonsul: Konsuldata
      })
    }).then(response => {
      if (!response.ok) {
        throw new Error(`Network response was not ok (${response.status})`);
      }
      return response.json();
    })
      .then(data => {
        if (data.success) {
          sessionStorage.setItem('konsultasiData', JSON.stringify(Konsuldata));
          closeKonsultasiModal();
          window.location.href = '?route=chat&chat_id=' + data.chat_id;
        } else {
          console.error('[KONSULTASI] Error initializing chat:', data);
          alert(`Error: Gagal menginisialisasi chat. Silakan coba lagi. Detail: ${data.message || 'Tidak ada detail'}`);
        }
      })
      .catch(error => {
        console.error('[KONSULTASI] Fetch error:', error);
        alert(`Error: Gagal menginisialisasi chat. Silakan coba lagi. Detail: ${error.message || 'Tidak ada detail'}`);
      });
  }
</script>