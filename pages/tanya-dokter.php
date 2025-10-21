<?php
// Tanya Dokter page - Chat consultation service like Halodoc
?>
<div class="pt-32 pb-20">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <button onclick="navigateTo('?route=')" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Beranda
            </button>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-display font-bold text-gray-800 mb-4">
                    Tanya Dokter
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Tanyakan masalah kesehatan hewan peliharaan Anda langsung ke dokter hewan melalui chat
                </p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Doctor Selection Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-card p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Dokter</h3>
                        <div class="space-y-3">
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer doctor-option" data-doctor="sarah">
                                <img src="public/placeholder.svg" alt="Dr. Sarah Wijaya" class="w-12 h-12 rounded-full mr-4">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800">Dr. Sarah Wijaya</h4>
                                    <p class="text-sm text-gray-600">Spesialis Anjing & Kucing</p>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            ★★★★★
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">4.8 (120 ulasan)</span>
                                    </div>
                                    <p class="text-xs text-green-600 mt-1">● Online</p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer doctor-option" data-doctor="michael">
                                <img src="public/placeholder.svg" alt="Dr. Michael Chen" class="w-12 h-12 rounded-full mr-4">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800">Dr. Michael Chen</h4>
                                    <p class="text-sm text-gray-600">Spesialis Hewan Kecil</p>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            ★★★★★
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">4.9 (95 ulasan)</span>
                                    </div>
                                    <p class="text-xs text-green-600 mt-1">● Online</p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer doctor-option" data-doctor="lisa">
                                <img src="public/placeholder.svg" alt="Dr. Lisa Putri" class="w-12 h-12 rounded-full mr-4">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800">Dr. Lisa Putri</h4>
                                    <p class="text-sm text-gray-600">Spesialis Ternak</p>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            ★★★★☆
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">4.7 (85 ulasan)</span>
                                    </div>
                                    <p class="text-xs text-orange-600 mt-1">○ Sibuk</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h4 class="font-medium text-gray-800 mb-2">Spesialisasi</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="specialty" value="anjing-kucing" class="text-purple-600">
                                    <span class="ml-2 text-sm">Anjing & Kucing</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="specialty" value="hewan-kecil" class="text-purple-600">
                                    <span class="ml-2 text-sm">Hewan Kecil</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="specialty" value="ternak" class="text-purple-600">
                                    <span class="ml-2 text-sm">Ternak</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Interface -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-card h-96 flex flex-col">
                        <!-- Chat Header -->
                        <div class="p-4 border-b border-gray-200 flex items-center">
                            <img src="public/placeholder.svg" alt="Doctor" class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <h4 class="font-medium text-gray-800" id="chat-doctor-name">Pilih dokter untuk memulai chat</h4>
                                <p class="text-sm text-gray-600" id="chat-doctor-specialty">-</p>
                            </div>
                            <div class="ml-auto text-green-600 text-sm" id="chat-status">● Online</div>
                        </div>

                        <!-- Chat Messages -->
                        <div class="flex-1 p-4 overflow-y-auto" id="chat-messages">
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p>Mulai percakapan dengan dokter</p>
                            </div>
                        </div>

                        <!-- Chat Input -->
                        <div class="p-4 border-t border-gray-200">
                            <div class="flex space-x-2">
                                <input type="text" placeholder="Ketik pertanyaan Anda..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none" id="chat-input">
                                <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors" id="send-button">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Biaya chat: Rp 25.000 per sesi (30 menit)</p>
                        </div>
                    </div>

                    <!-- Quick Questions -->
                    <div class="mt-6 bg-white rounded-lg shadow-card p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pertanyaan Cepat</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="p-3 text-left border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors quick-question" data-question="Anjing saya muntah-muntah, apa yang harus saya lakukan?">
                                Anjing saya muntah-muntah
                            </button>
                            <button class="p-3 text-left border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors quick-question" data-question="Kucing saya tidak mau makan, ada masalah apa?">
                                Kucing tidak mau makan
                            </button>
                            <button class="p-3 text-left border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors quick-question" data-question="Bagaimana cara merawat gigi hewan peliharaan?">
                                Perawatan gigi hewan
                            </button>
                            <button class="p-3 text-left border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors quick-question" data-question="Apakah vaksin rabies wajib untuk anjing?">
                                Vaksin rabies
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Section -->
            <div class="mt-8 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg p-8 text-center">
                <h3 class="text-2xl font-display font-bold mb-4">
                    Kasus Darurat?
                </h3>
                <p class="mb-6 opacity-90">
                    Untuk kasus darurat yang membutuhkan penanganan segera, hubungi hotline kami
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="tel:+6281122334455" class="inline-block bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        🚨 +62 811-2233-4455
                    </a>
                    <a href="?route=klinik-terdekat" class="inline-block border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition-colors">
                        🏥 Cari Klinik Terdekat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simple chat functionality
document.addEventListener('DOMContentLoaded', function() {
    const doctorOptions = document.querySelectorAll('.doctor-option');
    const chatDoctorName = document.getElementById('chat-doctor-name');
    const chatDoctorSpecialty = document.getElementById('chat-doctor-specialty');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const quickQuestions = document.querySelectorAll('.quick-question');

    const doctors = {
        sarah: { name: 'Dr. Sarah Wijaya', specialty: 'Spesialis Anjing & Kucing' },
        michael: { name: 'Dr. Michael Chen', specialty: 'Spesialis Hewan Kecil' },
        lisa: { name: 'Dr. Lisa Putri', specialty: 'Spesialis Ternak' }
    };

    let selectedDoctor = null;

    // Select doctor
    doctorOptions.forEach(option => {
        option.addEventListener('click', function() {
            const doctorKey = this.dataset.doctor;
            selectedDoctor = doctors[doctorKey];
            chatDoctorName.textContent = selectedDoctor.name;
            chatDoctorSpecialty.textContent = selectedDoctor.specialty;
            chatMessages.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <p>Mulai percakapan dengan ${selectedDoctor.name}</p>
                    <p class="text-sm mt-2">Biaya: Rp 25.000 per sesi</p>
                </div>
            `;
        });
    });

    // Send message
    function sendMessage(message) {
        if (!selectedDoctor) {
            alert('Pilih dokter terlebih dahulu');
            return;
        }

        // Add user message
        const userMessage = document.createElement('div');
        userMessage.className = 'flex justify-end mb-4';
        userMessage.innerHTML = `
            <div class="bg-purple-600 text-white px-4 py-2 rounded-lg max-w-xs">
                ${message}
            </div>
        `;
        chatMessages.appendChild(userMessage);

        // Simulate doctor response
        setTimeout(() => {
            const doctorMessage = document.createElement('div');
            doctorMessage.className = 'flex mb-4';
            doctorMessage.innerHTML = `
                <img src="public/placeholder.svg" alt="Doctor" class="w-8 h-8 rounded-full mr-3 mt-1">
                <div class="bg-gray-100 px-4 py-2 rounded-lg max-w-xs">
                    Terima kasih atas pertanyaannya. Saya akan membantu Anda. Bisa ceritakan lebih detail tentang kondisi hewan peliharaan Anda?
                </div>
            `;
            chatMessages.appendChild(doctorMessage);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 1000);

        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    sendButton.addEventListener('click', function() {
        const message = chatInput.value.trim();
        if (message) {
            sendMessage(message);
            chatInput.value = '';
        }
    });

    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendButton.click();
        }
    });

    // Quick questions
    quickQuestions.forEach(button => {
        button.addEventListener('click', function() {
            const question = this.dataset.question;
            sendMessage(question);
        });
    });
});
</script>
