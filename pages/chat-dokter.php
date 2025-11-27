<?php
// Halaman Tanya Dokter - Layanan konsultasi chat
// Header utama sudah di-handle oleh index.php, jadi tidak perlu include lagi.
?>
<style>
    /* Sembunyikan footer situs utama (yang menggunakan tag footer) agar tampilan chat fullscreen */
    body > footer, footer.bg-gray-900 {
        display: none !important;
    }
    /* Pastikan main container mengambil sisa height */
    main {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 80px); /* 80px approx header height */
        overflow: hidden;
    }
</style>

<!-- Container Utama: Full height relative to main -->
<div class="flex flex-col h-full bg-gray-50 relative">
    
    <!-- Header Kedua: Profil Dokter & Navigasi Kembali -->
    <div class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm z-30 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="text-gray-500 hover:text-purple-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </button>
            
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="public/placeholder.svg" alt="Dokter" class="w-10 h-10 rounded-full object-cover border border-gray-100">
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 text-sm leading-tight" id="chat-doctor-name">Memuat Dokter...</h4>
                    <p class="text-xs text-purple-600 font-medium" id="chat-doctor-specialty">-</p>
                </div>
            </div>
        </div>
        
        <!-- Status -->
        <div class="flex items-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Online
            </span>
        </div>
    </div>

    <!-- Area Chat Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 scroll-smooth" id="chat-messages">
        <!-- Placeholder State -->
        <div class="flex flex-col items-center justify-center h-full text-gray-400 opacity-60">
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <p class="text-sm">Mulai percakapan dengan dokter</p>
        </div>
    </div>

    <!-- Footer: Quick Questions & Input -->
    <div class="bg-white border-t border-gray-200 p-4 shrink-0 z-40 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="max-w-4xl mx-auto w-full">
            <!-- Quick Questions -->
            <div class="flex gap-2 overflow-x-auto pb-3 mb-2 no-scrollbar" id="quick-questions-container">
                <button class="quick-question whitespace-nowrap px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-full border border-purple-100 transition-colors" data-question="Anjing saya muntah-muntah">
                    Anjing muntah
                </button>
                <button class="quick-question whitespace-nowrap px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-full border border-purple-100 transition-colors" data-question="Kucing tidak mau makan">
                    Kucing mogok makan
                </button>
                <button class="quick-question whitespace-nowrap px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-full border border-purple-100 transition-colors" data-question="Jadwal vaksinasi">
                    Jadwal vaksin
                </button>
                <button class="quick-question whitespace-nowrap px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-full border border-purple-100 transition-colors" data-question="Konsultasi biaya">
                    Biaya berobat
                </button>
            </div>

            <!-- Input Area -->
            <div class="flex items-end gap-3 bg-gray-50 p-2 rounded-2xl border border-gray-200 focus-within:border-purple-400 focus-within:ring-2 focus-within:ring-purple-100 transition-all">
                <textarea 
                    id="chat-input"
                    rows="1"
                    placeholder="Ketik pesan Anda..." 
                    class="flex-1 bg-transparent border-0 focus:ring-0 resize-none text-sm py-3 px-2 max-h-32"
                    style="min-height: 44px;"
                ></textarea>
                
                <button class="p-2 text-gray-400 hover:text-purple-600 transition-colors self-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                </button>

                <button id="send-button" class="p-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 active:scale-95 transition-all shadow-md shadow-purple-200 self-end">
                    <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const chatDoctorName = document.getElementById('chat-doctor-name');
    const chatDoctorSpecialty = document.getElementById('chat-doctor-specialty');
    const quickQuestions = document.querySelectorAll('.quick-question');

    // Auto-resize textarea
    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        if(this.value === '') this.style.height = 'auto';
    });

    // Helper: Scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Helper: Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Helper: Create Message Bubble
    function appendMessage(text, isUser = true, senderName = 'Anda') {
        const div = document.createElement('div');
        div.className = `flex w-full mb-4 ${isUser ? 'justify-end' : 'justify-start'}`;
        
        const bubbleColor = isUser ? 'bg-purple-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-100 shadow-sm rounded-bl-none';
        
        let html = '';
        if (!isUser) {
            html += `
                <img src="public/placeholder.svg" alt="Dokter" class="w-8 h-8 rounded-full mr-2 self-end mb-1">
            `;
        }
        
        html += `
            <div class="max-w-[75%] ${bubbleColor} px-4 py-2.5 rounded-2xl">
                ${!isUser ? `<div class="text-xs font-semibold mb-1 text-purple-600">${escapeHtml(senderName)}</div>` : ''}
                <div class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(text)}</div>
                <div class="text-[10px] opacity-70 text-right mt-1">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
            </div>
        `;
        
        div.innerHTML = html;
        chatMessages.appendChild(div);
        scrollToBottom();
        
        // Remove placeholder if exists
        const placeholder = chatMessages.querySelector('.flex.flex-col.items-center');
        if (placeholder) placeholder.remove();
    }

    // Send Message Logic
    function sendMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        appendMessage(text, true);
        chatInput.value = '';
        chatInput.style.height = 'auto'; // Reset height

        // Simulate Doctor Reply
        setTimeout(() => {
            const replies = [
                "Halo! Ada yang bisa saya bantu dengan hewan peliharaan Anda?",
                "Bisa diceritakan lebih detail gejalanya?",
                "Baik, saya mengerti. Sudah berapa lama kondisi ini terjadi?",
                "Apakah ada gejala lain yang menyertai?"
            ];
            const randomReply = replies[Math.floor(Math.random() * replies.length)];
            appendMessage(randomReply, false, chatDoctorName.textContent);
        }, 1000 + Math.random() * 2000);
    }

    // Event Listeners
    sendButton.addEventListener('click', sendMessage);
    
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    quickQuestions.forEach(btn => {
        btn.addEventListener('click', function() {
            chatInput.value = this.dataset.question;
            sendMessage();
        });
    });

    // --- Data Loading Logic (Konsultasi / Dokter) ---
    
    // 1. Cek Data Konsultasi dari SessionStorage (Flow normal dari form konsultasi)
    try {
        const rawKonsultasi = sessionStorage.getItem('konsultasiData');
        if (rawKonsultasi) {
            const data = JSON.parse(rawKonsultasi);
            
            // Set Info Dokter
            if (data.dokter_nama) chatDoctorName.textContent = data.dokter_nama;
            
            // Tampilkan Ringkasan Konsultasi sebagai pesan sistem/awal
            const summaryHtml = `
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-4 text-sm text-blue-800">
                    <h5 class="font-semibold mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Ringkasan Konsultasi
                    </h5>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                        <span class="text-blue-600/70">Hewan:</span> <span class="font-medium">${escapeHtml(data.nama_hewan)} (${escapeHtml(data.jenis_hewan)})</span>
                        <span class="text-blue-600/70">Usia:</span> <span class="font-medium">${escapeHtml(data.usia_hewan)}</span>
                        <span class="text-blue-600/70 col-span-2 mt-1">Keluhan:</span>
                        <span class="col-span-2 font-medium italic">"${escapeHtml(data.keluhan_gejala)}"</span>
                    </div>
                </div>
            `;
            
            // Insert summary before messages
            const summaryDiv = document.createElement('div');
            summaryDiv.innerHTML = summaryHtml;
            chatMessages.appendChild(summaryDiv);
            
            // Hapus placeholder
            const placeholder = chatMessages.querySelector('.flex.flex-col.items-center');
            if (placeholder) placeholder.remove();

            // Pesan pembuka dokter
            setTimeout(() => {
                appendMessage(`Halo, saya ${data.dokter_nama || 'Dokter'}. Saya sudah membaca keluhan tentang ${escapeHtml(data.nama_hewan)}. Bisa kirimkan foto kondisinya?`, false, data.dokter_nama);
            }, 500);

            // Bersihkan session agar tidak muncul terus saat refresh (opsional, tergantung flow)
            // sessionStorage.removeItem('konsultasiData'); 
        } else {
            // 2. Fallback: Cek URL Parameter dokter_id (Flow langsung klik chat)
            const urlParams = new URLSearchParams(window.location.search);
            const dokterId = urlParams.get('dokter_id');
            
            if (dokterId) {
                // Fetch data dokter
                const apiUrl = 'controller/pilih_dokter_controller.php?api=true';
                fetch(apiUrl)
                    .then(res => res.json())
                    .then(list => {
                        const doc = list.find(d => String(d.id) === String(dokterId));
                        if (doc) {
                            chatDoctorName.textContent = doc.nama;
                            // Helper untuk kategori
                            const getKategori = (k) => {
                                if (Array.isArray(k)) return k.map(i => i.nama_kateg || i.nama).join(', ');
                                return k.nama_kateg || k.nama || '-';
                            };
                            chatDoctorSpecialty.textContent = getKategori(doc.kategori);
                            
                            // Pesan pembuka
                            appendMessage(`Halo, saya ${doc.nama}. Ada yang bisa saya bantu?`, false, doc.nama);
                        }
                    })
                    .catch(err => console.error("Gagal memuat data dokter", err));
            } else {
                chatDoctorName.textContent = "Pilih Dokter";
                chatDoctorSpecialty.textContent = "Silakan kembali ke menu Dokter";
            }
        }
    } catch (e) {
        console.error("Error parsing session data", e);
    }
});
</script>
