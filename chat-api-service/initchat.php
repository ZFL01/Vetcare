<?php
//ambil dari php aja, fetch api controller, init chat, dan lanjut fetch ke js bagian chat
if (!isset($_GET['dokter_id'])) {
    header('Location: '.BASE_URL.'index.php?route=pilih-dokter');
    exit;
}else{
    $dokter = 
    $user = $_SESSION['user']->getId_User();
}
?>
<style>
    body>footer,
    footer.bg-gray-900 {
        display: none !important;
    }

    main {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 80px);
        overflow: hidden;
    }
</style>

<div class="flex flex-col h-full bg-gray-50 relative">
<!-- Profil Dokter & Navigasi Kembali -->
    <div class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm z-30 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="text-gray-500 hover:text-purple-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </button>
        </div></div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 items-center">
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8" style="min-height: 150px;">
            <div class="flex items-center gap-3">
                <div class="flex">
                    <img id="dokter-foto" alt="Dokter"
                        class="w-32 h-32 rounded-full object-cover border-4 border-primary mx-auto mb-6">
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full">
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 text-sm leading-tight" id="chat-doctor-name">Memuat Dokter...
                    </h4>
                    <p class="text-xs text-purple-600 font-medium" id="dokter-rate"></p>
                </div>
                <div>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Online
                    </span>
                </div>
            </div>
        </div>
        <div >
            <button onclick="initChat()" class="tab-btn active px-6 py-2 rounded-lg font-medium bg-green-500 text-white">
                Hubungi dokter sekarang!
            </button>
        </div>
    </main>
</div>

<style>
    /* Hide scrollbar for quick questions but allow scroll */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script src="/public/service.js">
    let dokterId = 0;
    document.addEventListener('DOMContentLoaded', function () {
        const chatDoctorName = document.getElementById('chat-doctor-name');
        const foto = document.getElementById('dokter-foto');
        const rate = document.getElementById('dokter-rate')
        
        // --- Data Loading Logic (Konsultasi / Dokter) ---
        try {
                const urlParams = new URLSearchParams(window.location.search);
                let dokterId = urlParams.get('dokter_id');

                if (dokterId) {
                    // Fetch data dokter
                    const apiUrl = `controller/pilih_dokter_controller.php?pilih=${dokterId}`;
                    fetch(apiUrl)
                        .then(res => res.json())
                        .then(doc => {
                            if (!doc) {
                                return;
                            }
                            chatDoctorName.textContent = doc.nama || '-';
                            foto.src = '/public/img/dokter-profil/'+doc.foto;
                            rate.textContent = (doc.rate * 100) + '%';
                        })
                        .catch(err => console.error("Gagal memuat data dokter", err));
                } else {
                    chatDoctorName.textContent = "Pilih Dokter";
                    chatDoctorSpecialty.textContent = "Silakan kembali ke menu Dokter";
                }
            } catch (e) {
                console.error("Error parsing session data", e);
            }
        }
    );
    
    function initChat(){
        let idUser = '<?php echo $user;?>';
        let id_chat = "C"+ timestamp10() + '-U'+idUser+'D'+dokterId;

    }
</script>