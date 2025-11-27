<?php
// Tanya Dokter page - Chat consultation service like Halodoc
?>
<div class="min-h-screen pt-20 pb-8 flex flex-col">
    <div class="w-full mx-auto px-4 flex-1 flex flex-col">
        <div class="mb-4">
            <button onclick="navigateTo('?route=')" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Beranda
            </button>
        </div>

        <!-- Chat Header (no card wrapper) -->
        <div class="flex items-center mb-4 pb-4 border-b border-gray-200">
            <img src="public/placeholder.svg" alt="Doctor" class="w-10 h-10 rounded-full mr-3">
            <div>
                <h4 class="font-medium text-gray-800" id="chat-doctor-name">Pilih dokter untuk memulai chat</h4>
                <p class="text-sm text-gray-600" id="chat-doctor-specialty">-</p>
            </div>
            <div class="ml-auto text-green-600 text-sm" id="chat-status">● Online</div>
        </div>

        <!-- Chat Messages (flex-1 to fill available space) -->
        <div class="flex-1 overflow-y-auto pb-40" id="chat-messages">
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p>Mulai percakapan dengan dokter</p>
            </div>
        </div>

        <!-- Chat Input (fixed at bottom; stays visible while messages scroll) -->
        <div id="chat-input-bar" class="px-4 z-40" style="position:fixed;left:0;right:0;bottom:1rem;">
            <div class="mx-auto max-w-3xl">
                <div class="border-t border-gray-200 pt-4 bg-white rounded-t-lg shadow">
                    <!-- Quick questions (moved above input) -->
                    <div class="mb-3 flex flex-wrap gap-2">
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors quick-question" data-question="Anjing saya muntah-muntah, apa yang harus saya lakukan?">Anjing saya muntah-muntah</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors quick-question" data-question="Kucing saya tidak mau makan, ada masalah apa?">Kucing tidak mau makan</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors quick-question" data-question="Bagaimana cara merawat gigi hewan peliharaan?">Perawatan gigi hewan</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors quick-question" data-question="Apakah vaksin rabies wajib untuk anjing?">Vaksin rabies</button>
                    </div>

                    <div class="flex space-x-2">
                        <input type="text" placeholder="Ketik pertanyaan Anda..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none" id="chat-input">
                        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors" id="send-button">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatInputBar = document.getElementById('chat-input-bar');
    const doctorOptions = document.querySelectorAll('.doctor-option');
    const chatDoctorName = document.getElementById('chat-doctor-name');
    const chatDoctorSpecialty = document.getElementById('chat-doctor-specialty');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const quickQuestions = document.querySelectorAll('.quick-question');

    let selectedDoctor = null;

    function kategoriToText(kat) {
        if (!kat) return '';
        if (Array.isArray(kat)) {
            return kat.map(k => {
                if (typeof k === 'string') return k;
                return k.nama_kateg || k.namaK || k.nama || '';
            }).filter(Boolean).join(', ');
        }
        if (typeof kat === 'string') return kat;
        return kat.nama_kateg || kat.namaK || kat.nama || '';
    }

    // Ensure we have konsultasi data or a dokter_id in query string; otherwise redirect to selection
    let __konsultasiFromStorage = null;
    try {
        const raw = sessionStorage.getItem('konsultasiData');
        if (raw) __konsultasiFromStorage = JSON.parse(raw);
    } catch (e) {
        console.warn('konsultasiData parse failed', e);
    }
    const __urlParams = new URLSearchParams(window.location.search);
    const __dokterIdParam = __urlParams.get('dokter_id');
    if (!__konsultasiFromStorage && !__dokterIdParam) {
        // no context to start chat — go back to pilih dokter
        window.location.href = '?route=pilih-dokter';
        return;
    }
    if (__dokterIdParam) {
        window.__chosenDokterId = __dokterIdParam;
    }

    function scrollToBottom() {
        if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendUserMessage(text) {
        const userMessage = document.createElement('div');
        userMessage.className = 'flex justify-end mb-4';
        userMessage.innerHTML = `
            <div class="bg-purple-600 text-white px-4 py-2 rounded-lg max-w-xs break-words">
                ${escapeHtml(text)}
            </div>
        `;
        chatMessages.appendChild(userMessage);
        scrollToBottom();
    }

    function appendDoctorMessage(text, doctorName) {
        const doctorMessage = document.createElement('div');
        doctorMessage.className = 'flex mb-4 items-start';
        doctorMessage.innerHTML = `
            <img src="public/placeholder.svg" alt="Doctor" class="w-8 h-8 rounded-full mr-3 mt-1">
            <div>
                <div class="text-xs text-gray-600 mb-1">${doctorName ? escapeHtml(doctorName) : 'Dokter'}</div>
                <div class="bg-gray-100 px-4 py-2 rounded-lg max-w-xs break-words">${escapeHtml(text)}</div>
            </div>
        `;
        chatMessages.appendChild(doctorMessage);
        scrollToBottom();
    }

    function appendSystemBox(konsultasi) {
        const summary = document.createElement('div');
        summary.className = 'mb-4 p-3 bg-white border border-gray-100 rounded-lg text-sm shadow-sm';

        const title = document.createElement('div');
        title.className = 'font-medium text-gray-800 mb-2';
        title.textContent = 'Ringkasan Konsultasi';
        summary.appendChild(title);

        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-2 gap-2 text-gray-700';

        const fields = [
            ['Nama Hewan', konsultasi.nama_hewan || '-'],
            ['Jenis', konsultasi.jenis_hewan || '-'],
            ['Usia', konsultasi.usia_hewan || '-'],
            ['Keluhan', konsultasi.keluhan_gejala ? (konsultasi.keluhan_gejala.length > 150 ? konsultasi.keluhan_gejala.substring(0,150) + '...' : konsultasi.keluhan_gejala) : '-']
        ];

        fields.forEach(([label, value]) => {
            const el = document.createElement('div');
            el.innerHTML = `<div class="text-xs text-gray-500">${escapeHtml(label)}</div><div class="text-sm font-medium">${escapeHtml(value)}</div>`;
            grid.appendChild(el);
        });

        summary.appendChild(grid);

        // Insert summary above chat messages
        chatMessages.parentNode.insertBefore(summary, chatMessages);
    }

    function escapeHtml(unsafe) {
        if (unsafe === null || unsafe === undefined) return '';
        return String(unsafe)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function setPaymentGate(konsultasi) {
        // Frontend-only gating: if konsultasi.payment_required === true and not paid, disable input and show CTA
        const paid = konsultasi && (konsultasi.paid === true || konsultasi.paid === '1' || konsultasi.paid === 1);
        const paymentRequired = konsultasi && (konsultasi.payment_required === true || konsultasi.payment_required === '1');

        // Remove existing banner if present
        const existing = document.getElementById('payment-banner');
        if (existing) existing.remove();

        if (paymentRequired && !paid) {
            // disable input
            if (chatInput) chatInput.disabled = true;
            if (sendButton) sendButton.disabled = true;

            const banner = document.createElement('div');
            banner.id = 'payment-banner';
            banner.className = 'mb-3 p-3 bg-yellow-50 border border-yellow-100 rounded-lg flex items-center justify-between';
            banner.innerHTML = `
                <div class="text-sm text-yellow-800">Anda harus menyelesaikan pembayaran sebelum memulai chat.</div>
                <div>
                    <a href="?route=checkout&dokter_id=${encodeURIComponent(konsultasi.dokter_id || '')}" class="inline-block bg-yellow-600 text-white px-4 py-2 rounded-lg">Bayar Sekarang</a>
                </div>
            `;
            // Insert banner above input area
            const inputArea = sendButton.closest('div').parentNode; // p-4 border-t
            inputArea.parentNode.insertBefore(banner, inputArea);
        }
    }

    // Basic send handler
    function sendMessage(message) {
        if (!message || !message.trim()) return;

        appendUserMessage(message);

        // Simulate doctor response
        setTimeout(() => {
            appendDoctorMessage('Terima kasih atas pertanyaannya. Saya akan membantu Anda. Bisa ceritakan lebih detail tentang kondisi hewan peliharaan Anda?', chatDoctorName.textContent || 'Dokter');
        }, 800 + Math.random()*700);
    }

    // Wire up send button and input
    sendButton.addEventListener('click', function() {
        const message = chatInput.value.trim();
        if (message) {
            sendMessage(message);
            chatInput.value = '';
        }
    });

    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendButton.click();
        }
    });

    quickQuestions.forEach(button => {
        button.addEventListener('click', function() {
            const question = this.dataset.question;
            sendMessage(question);
        });
    });

    // Handle doctor selection from sidebar (if user clicks there)
    doctorOptions.forEach(option => {
        option.addEventListener('click', function() {
            const nameEl = this.querySelector('h4');
            const subEl = this.querySelector('p');
            const name = nameEl ? nameEl.textContent.trim() : 'Dokter';
            const specialty = subEl ? subEl.textContent.trim() : '';
            chatDoctorName.textContent = name;
            chatDoctorSpecialty.textContent = specialty;
            // clear messages and show starter
            chatMessages.innerHTML = '';
            appendDoctorMessage('Halo, saya ' + name + '. Silakan jelaskan keluhan Anda.', name);
        });
    });

    // Restore konsultasiData if present; if not but a dokter_id was passed, fetch doctor info
    try {
        const raw = sessionStorage.getItem('konsultasiData');
        if (raw) {
            const data = JSON.parse(raw);
            // show summary box
            appendSystemBox(data);

            if (data.dokter_nama) {
                chatDoctorName.textContent = data.dokter_nama;
            }
            if ((!data.dokter_nama || !chatDoctorSpecialty.textContent || chatDoctorSpecialty.textContent === '-') && window.__chosenDokterId) {
                const apiUrl2 = (typeof window.API_BASE_URL !== 'undefined' && window.API_BASE_URL) ? (window.API_BASE_URL + '/controller/pilih_dokter_controller.php?api=true') : 'controller/pilih_dokter_controller.php?api=true';
                fetch(apiUrl2).then(res => res.json()).then(list => {
                    const doc2 = list.find(d => String(d.id) === String(window.__chosenDokterId));
                    if (doc2) {
                        chatDoctorName.textContent = doc2.nama || chatDoctorName.textContent || 'Dokter';
                        const spes2 = kategoriToText(doc2.kategori);
                        chatDoctorSpecialty.textContent = spes2 || chatDoctorSpecialty.textContent || '';
                    }
                }).catch(() => {});
            }

            // Prefill input with keluhan
            if (chatInput && data.keluhan_gejala) {
                chatInput.value = data.keluhan_gejala;
            }

            // Add initial system/doctor message that reflects the konsultasi
            const initialMsg = data.keluhan_gejala ? ('Terima kasih. Saya melihat keluhan Anda: "' + (data.keluhan_gejala.length > 120 ? data.keluhan_gejala.substring(0,120) + '...' : data.keluhan_gejala) + '". Mohon tunggu dokter merespons.') : 'Permintaan konsultasi diterima. Mohon tunggu dokter merespons.';
            appendDoctorMessage(initialMsg, data.dokter_nama || 'Dokter');

            // Payment gating (frontend only)
            setPaymentGate(data);

            // Remove so it won't be reused on refresh
            sessionStorage.removeItem('konsultasiData');
        } else if (window.__chosenDokterId) {
            // We only have dokter_id in query string. Fetch doctor list and populate header.
            const apiUrl = (typeof window.API_BASE_URL !== 'undefined' && window.API_BASE_URL) ? (window.API_BASE_URL + '/controller/pilih_dokter_controller.php?api=true') : 'controller/pilih_dokter_controller.php?api=true';
            fetch(apiUrl).then(res => res.json()).then(list => {
                const doc = list.find(d => String(d.id) === String(window.__chosenDokterId));
                if (doc) {
                    chatDoctorName.textContent = doc.nama || 'Dokter';
                    chatDoctorSpecialty.textContent = kategoriToText(doc.kategori);
                    // also show an initial message
                    appendDoctorMessage('Permintaan konsultasi diterima. Mohon tunggu dokter merespons.', doc.nama || 'Dokter');
                }
            }).catch(err => console.warn('Could not fetch doctor info', err));
        }
    } catch (e) {
        console.warn('Could not restore konsultasi data:', e);
    }

    // Hide any site footer on this page to create a fullscreen chat experience
    try {
        document.querySelectorAll('footer, .footer, #footer, .site-footer').forEach(el => {
            el.style.display = 'none';
        });
    } catch (e) {
        // ignore
    }
    if (chatInputBar && chatInputBar.parentNode !== document.body) {
        document.body.appendChild(chatInputBar);
    }
});
</script>
