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

<body class="bg-gradient-to-br from-purple-50 to-indigo-100">
    <div class="gradient-bg p-3 text-white text-center">
        <div class="flex items-center gap-3 mb-2 justify-center">
            <i class="fas fa-paw text-2xl"></i>
            <h2 class="text-2xl font-bold">VetChat</h2>
        </div>
    </div>
    <div class="flex h-screen">
        <!-- Area Chat Utama -->
        <div class="flex-1 flex flex-col">
            <!-- Header Chat -->
            <div class="gradient-bg text-white p-3 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-2xl flex items-center gap-2">
                            <i class="fas fa-cat nama-hewan"></i>
                        </h2>
                        <p class="text-purple-100 text-sm mt-1 jenis-hewan">Kucing Persia</p>
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
        <div class="w-96 bg-white shadow-xl overflow-y-auto chat-scroll">
            <div class="gradient-bg text-white p-6">
                <h3 class="text-xl font-bold flex justify-center gap-2" style="font-size: 18px;">
                    <i class="fas fa-info-circle"></i>
                    Info Hewan Pasien
                </h3>
                <h3 id="nama-pemilik" class="text-xl font-bold flex justify-center gap-2" style="font-size: 18px;">
                    <i class="fas fa-info-circle"></i>
                </h3>
            </div>

            <div class="p-6">
                <!-- Foto Hewan -->
                <div class="text-center mb-6">
                    <div
                        class="w-32 h-32 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center shadow-xl">
                        <i class="fas fa-cat text-white text-6xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xl nama-hewan"></h4>
                    <p class="text-purple-600 font-medium jenis-hewan">Kucing Persia</p>
                </div>
                <!-- Data Hewan -->
                <div class="space-y-3 mb-6">
                    <h5 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-purple-600"></i>
                        Data Hewan
                    </h5>
                        <div
                            class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200 text-center">
                            <p class="text-xs text-purple-600 font-semibold mb-1">Usia</p>
                            <p id="usia" class="font-bold text-gray-900">2 tahun</p>
                        </div>
                    <div
                        class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200 text-center">
                        <p class="text-xs text-purple-600 font-semibold mb-1">Deskripsi Keluhan</p>
                        <div id="deskripsi" class="font-bold text-gray-900">
                            Putih
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

        window.addEventListener('beforeunload', () => {
            if (pollingInterval) clearInterval(pollingInterval);
        });
    });
</script>