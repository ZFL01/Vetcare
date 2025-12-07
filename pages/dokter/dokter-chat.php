<?php
require_once '../../includes/DAO_dokter.php';
require_once '../../src/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
requireLogin(true);

$dokter = $_SESSION['dokter'];

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Dokter Hewan - VetCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: #a78bfa;
            border-radius: 3px;
        }

        #deskripsi {
            overflow-wrap: break-word;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 min-h-screen">
    <!-- Top Navigation Bar -->
    <div class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-paw text-2xl"></i>
                    <h1 class="text-xl font-bold">VetChat</h1>
                </div>
                    <button id="sidebar-toggle" class="md:hidden text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <i class="fas fa-info-circle text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-[calc(100vh-73px)]">
        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col relative">
            <!-- Chat Header -->
            <div class="bg-white border-b border-purple-100 shadow-sm">
                <div class="container mx-auto px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-50 rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fas fa-cat text-purple-600 text-xl nama-hewan-icon"></i>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>
                            <div>
                                <h2 class="font-bold text-lg text-gray-900 nama-hewan">Memuat...</h2>
                                <p class="text-purple-600 text-sm jenis-hewan">Memuat...</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="sidebar-toggle-desktop" class="hidden md:block text-gray-400 hover:text-purple-600 p-2 rounded-lg hover:bg-purple-50 transition-colors">
                                <i class="fas fa-info-circle text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Pesan -->
            <div class="flex-1 overflow-y-auto p-6 scroll-smooth" id="chat-messages"
                style="background: linear-gradient(to bottom, #faf5ff, #f3e8ff);">

            </div>

            <!-- Input Area -->
            <div class="bg-white border-t border-purple-200 p-4 shadow-lg">
                <div class="max-w-4xl mx-auto">
                    <div class="flex gap-3 items-center">
                        <div class="flex-1 relative">
                            <textarea rows="1" id="chat-input"
                                class="w-full px-6 py-4 border-2 border-purple-200 rounded-full focus:outline-none focus:border-purple-500 resize-none bg-purple-50 focus:bg-white transition-all duration-200"
                                placeholder="Ketik pesan Anda..."></textarea>
                        </div>
                        <button id="send-button"
                            class="w-14 h-14 gradient-bg text-white rounded-full hover:scale-110 transition-transform duration-200 flex items-center justify-center shadow-lg flex-shrink-0">
                            <i class="fas fa-paper-plane text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Kanan - Info Pasien -->
        <div id="patient-sidebar" class="w-96 bg-white shadow-xl overflow-y-auto chat-scroll hidden md:block">
            <!-- Sidebar Header -->
            <div class="gradient-bg text-white p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Info Hewan Pasien
                    </h3>
                    <button id="sidebar-close" class="md:hidden text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="bg-white/10 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-purple-100">Pemilik</p>
                            <p id="nama-pemilik" class="font-semibold text-white">Memuat...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Foto Hewan -->
                <div class="text-center mb-8">
                    <div class="relative">
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-100 to-purple-50 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg border-4 border-white">
                            <i class="fas fa-cat text-purple-600 text-6xl nama-hewan-icon"></i>
                        </div>
                        <div class="absolute bottom-2 right-1/2 transform translate-x-16 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xl nama-hewan">Memuat...</h4>
                    <p class="text-purple-600 font-medium jenis-hewan">Memuat...</p>
                </div>

                <!-- Data Hewan -->
                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-4 border border-purple-100">
                        <h5 class="font-bold text-purple-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-purple-600"></i>
                            Data Hewan
                        </h5>

                        <div class="space-y-3">
                            <div class="bg-white rounded-xl p-4 shadow-sm border border-purple-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-birthday-cake text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-purple-600 font-semibold">Usia</p>
                                        <p id="usia" class="font-bold text-gray-900">Memuat...</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl p-4 shadow-sm border border-purple-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-purple-600 font-semibold mb-2">Deskripsi Keluhan</p>
                                        <div id="deskripsi" class="text-sm text-gray-700 leading-relaxed">
                                            Memuat...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</body>

</html>


<script>
    window.lastMessageTime = 0;
    let pollingInterval;

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

    document.addEventListener('DOMContentLoaded', function () {
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const sendButton = document.getElementById('send-button');

        const chatNamaHewan = document.querySelectorAll('.nama-hewan');
        const chatJenisHewan = document.querySelectorAll('.jenis-hewan');
        const chatNamaPemilik = document.getElementById('nama-pemilik');
        const usia = document.getElementById('usia');
        const deskripsi = document.getElementById('deskripsi');


        // Helper: Scroll to bottom
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        //tampilan bubble chat
        function appendMessage(text, isUser = true, senderName = 'Anda') {
            const div = document.createElement('div');
            div.className = `flex w-full mb-4 ${isUser ? 'justify-end' : 'justify-start'}`;

            const bubbleColor = isUser ? 'bg-purple-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-100 shadow-sm rounded-bl-none';

            let html = '';
            html += `
            <div class="max-w-[75%] ${bubbleColor} px-4 py-2.5 rounded-2xl">
                ${!isUser ? `<div class="text-xs font-semibold mb-1 text-purple-600">${escapeHtml(senderName)}</div>` : ''}
                <div class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(text)}</div>
                <div class="text-[10px] opacity-70 text-right mt-1">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
            </div>
        `;

            div.innerHTML = html;
            chatMessages.appendChild(div);
            scrollToBottom();

            // Remove placeholder if exists
            const placeholder = chatMessages.querySelector('.flex.flex-col.items-center');
            if (placeholder) placeholder.remove();
        }

        function appendSendingIndicator(text) {
            const div = document.createElement('div');
            // Tambahkan kelas unik agar mudah dihapus
            div.className = 'flex w-full mb-4 justify-end message-sending-indicator';

            const html = `
                <div class="max-w-[75%] bg-purple-200 text-purple-900 px-4 py-2.5 rounded-2xl rounded-br-none border border-purple-300">
                    <div class="text-xs font-semibold mb-1 flex items-center gap-1 text-purple-700">
                        <svg class="w-3.5 h-3.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Mengirim...</span>
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(text)}</div>
                    <div class="text-[10px] opacity-70 text-right mt-1">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                </div>
            `;

            div.innerHTML = html;
            chatMessages.appendChild(div);
            scrollToBottom();
            return div; // Kembalikan elemen agar bisa di-handle jika gagal
        }

        function removeSendingIndicator() {
            const indicators = chatMessages.querySelectorAll('.message-sending-indicator');
            indicators.forEach(indicator => indicator.remove());
        }

        function loadChatHistory(chatId) {
            if (!chatId) return;
            const since = window.lastMessageTime + 1;
            const apiurl = '/index.php?aksi=getMessages&chat_id=' + chatId + '&since=' + since;

            fetch(apiurl).then(res => {
                if (!res.ok) throw new Error(`gagal memuat pesan: ${res.status}`);
                return res.json();
            })
                .then(data => {
                    removeSendingIndicator();

                    if (data.success && data.messages && data.messages.length > 0) {
                        let latestTimestamp = window.lastMessageTime;
                        data.messages.forEach(msg => {
                            const isUser = msg.senderRole === 'dokter';
                            const senderName = isUser ? 'Anda' : chatNamaHewan.textContent;
                            appendMessage(msg.content, isUser, senderName);

                            const messageData = new Date(msg.timestamp);
                            const currentMsgTime = Math.floor(messageData.getTime() / 1000);

                            if (currentMsgTime > latestTimestamp) {
                                latestTimestamp = currentMsgTime;
                            }
                        });
                        window.lastMessageTime = latestTimestamp;
                    }
                }).catch(err => console.error('error loading chat history:', err));
        }

        // Auto-resize textarea
        chatInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            if (this.value === '') this.style.height = 'auto';
        });


        // Send Message Logic
        function sendMessage() {
            const text = chatInput.value.trim();
            const userId = <?php echo $dokter->getId(); ?>; // ID pengirim (User)
            const chatId = window.currentChatId;

            if (!text || !chatId || !userId) return;

            appendSendingIndicator(text);

            fetch('/index.php?aksi=sendMessage', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    chat_id: chatId,
                    sender_id: userId, // User adalah pengirim
                    sender_role: 'dokter', // Tentukan peran pengirim
                    content: text,
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Gagal menyimpan pesan ke DB:', data.message);
                    } else {
                        console.log('Pesan berhasil disimpan');
                    }
                })
                .catch(err => {
                    console.error('Fetch Error saat mengirim pesan:', err);
                    removeSendingIndicator();
                });
            chatInput.value = '';
            chatInput.style.height = 'auto';
        }


        // Event Listeners
        sendButton.addEventListener('click', sendMessage);

        chatInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        const urlParams = new URLSearchParams(window.location.search);
        const chatId = urlParams.get('chat_id');
        if (!chatId) {
            return;
        }

        window.currentChatId = chatId;


        //fetch form
        const apiFormUrl = '/chat-api-service/controller_chat.php?action=getChatForm&chat_id=' + chatId;
        fetch(apiFormUrl).then(res => {
            if (!res.ok) {
                throw new Error(`Gagal memuat formulir. Status: ${res.status}`);
            } return res.json()
        }).then(data => {
            if (data.success && data.session) {
                const ini = data.session;
                const form = ini.formData;
                chatNamaHewan.forEach(a =>{
                    a.textContent = form.nama_hewan;
                })
                chatJenisHewan.forEach(a =>{
                    a.textContent = form.jenis_hewan;
                })
                usia.textContent = form.usia_hewan;
                console.log(form);
                deskripsi.textContent = form.keluhan_gejala;
            } else {
                console.error('data formulir tidak ditemukan', data.message);
            }
        })

        // Fetch data dokter/user
        const apiUrl = `/chat-api-service/controller_chat.php?action=getChatSession&chat_id=${chatId}`;
        fetch(apiUrl)
            .then(res => {
                if (!res.ok) {
                    throw new Error(`Gagal memuat sesi chat. Status: ${res.status}`);
                } return res.json()
            })
            .then(data => {
                if (data.success && data.session) {
                    const session = data.session;

                    window.chatSessionData = session;

                    chatNamaPemilik.textContent = `${session.email}`;

                    loadChatHistory(chatId);

                    if (pollingInterval) clearInterval(pollingInterval);
                    pollingInterval = setInterval(() => {
                        loadChatHistory(chatId);
                    }, 5000);

                } else {
                    console.error('data sesi chat tidak ditemukan', data.message);
                }
            })
            .catch(err => {
                console.error("Gagal memuat data chat", err);
                chatNamaHewan.textContent = "Error memuat data";
            });

        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarToggleDesktop = document.getElementById('sidebar-toggle-desktop');
        const sidebarClose = document.getElementById('sidebar-close');
        const patientSidebar = document.getElementById('patient-sidebar');

        function toggleSidebar() {
            if (window.innerWidth < 768) {
                // Mobile: show/hide with overlay
                patientSidebar.classList.toggle('hidden');
                if (!patientSidebar.classList.contains('hidden')) {
                    patientSidebar.classList.add('fixed', 'inset-0', 'z-50', 'md:relative', 'md:inset-auto');
                } else {
                    patientSidebar.classList.remove('fixed', 'inset-0', 'z-50', 'md:relative', 'md:inset-auto');
                }
            } else {
                // Desktop: show/hide normally
                patientSidebar.classList.toggle('hidden');
            }
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        if (sidebarToggleDesktop) {
            sidebarToggleDesktop.addEventListener('click', toggleSidebar);
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', () => {
                patientSidebar.classList.add('hidden');
                patientSidebar.classList.remove('fixed', 'inset-0', 'z-50', 'md:relative', 'md:inset-auto');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768 &&
                !patientSidebar.contains(e.target) &&
                !sidebarToggle.contains(e.target) &&
                !sidebarToggleDesktop.contains(e.target) &&
                !patientSidebar.classList.contains('hidden')) {
                patientSidebar.classList.add('hidden');
                patientSidebar.classList.remove('fixed', 'inset-0', 'z-50', 'md:relative', 'md:inset-auto');
            }
        });

        window.addEventListener('beforeunload', () => {
            if (pollingInterval) clearInterval(pollingInterval);
        });
    });
</script>
