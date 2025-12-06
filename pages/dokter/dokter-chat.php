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
    <div class="gradient-bg p-6 text-white text-center">
        <div class="flex items-center gap-3 mb-2 justify-center">
            <i class="fas fa-paw text-2xl"></i>
            <h2 class="text-2xl font-bold">VetChat</h2>
        </div>
    </div>
    <div class="flex h-screen">
        <!-- Area Chat Utama -->
        <div class="flex-1 flex flex-col">
            <!-- Header Chat -->
            <div class="gradient-bg text-white p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-2xl flex items-center gap-2">
                            <i class="fas fa-cat"></i>
                            Fluffy
                        </h2>
                        <p class="text-purple-100 text-sm mt-1">Kucing Persia • Budi Santoso</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="bg-white bg-opacity-20 backdrop-blur-sm px-4 py-2 rounded-full text-sm flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            Online
                        </span>
                    </div>
                </div>
            </div>

            <!-- Area Pesan -->
            <div class="flex-1 overflow-y-auto p-6 chat-scroll"
                style="background: linear-gradient(to bottom, #faf5ff, #f3e8ff);">
                <div class="max-w-4xl mx-auto space-y-4">
                    <!-- Pesan dari Pemilik -->
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <div
                                class="bg-white rounded-3xl rounded-tl-none p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                                <p class="text-gray-800">Selamat pagi dokter, kucing saya Fluffy sudah 2 hari ini tidak
                                    mau makan sama sekali.</p>
                                <p class="text-xs text-gray-400 mt-2">10:25</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan dari Dokter -->
                    <div class="flex items-start gap-3 justify-end">
                        <div class="flex-1 flex justify-end">
                            <div
                                class="gradient-bg text-white rounded-3xl rounded-tr-none p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                                <p>Baik Pak Budi, saya akan bantu. Boleh ceritakan gejala lainnya yang terlihat pada
                                    Fluffy?</p>
                                <p class="text-xs text-purple-200 mt-2 text-right">10:26</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan dari Pemilik -->
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <div
                                class="bg-white rounded-3xl rounded-tl-none p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                                <p class="text-gray-800">Matanya terlihat berair dan dia lebih banyak tidur dari
                                    biasanya dok.</p>
                                <p class="text-xs text-gray-400 mt-2">10:27</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan dari Dokter -->
                    <div class="flex items-start gap-3 justify-end">
                        <div class="flex-1 flex justify-end">
                            <div
                                class="gradient-bg text-white rounded-3xl rounded-tr-none p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                                <p>Terima kasih informasinya. Saya sarankan untuk membawa Fluffy ke klinik untuk
                                    pemeriksaan lebih lanjut ya Pak.</p>
                                <p class="text-xs text-purple-200 mt-2 text-right">10:28</p>
                            </div>
                        </div>
                    </div>

                    <!-- Typing Indicator -->
                    <div class="flex items-start gap-3">
                        <div class="bg-white rounded-3xl rounded-tl-none p-4 shadow-md w-20">
                            <div class="flex gap-1">
                                <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce"
                                    style="animation-delay: 0ms"></span>
                                <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce"
                                    style="animation-delay: 150ms"></span>
                                <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce"
                                    style="animation-delay: 300ms"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="bg-white border-t border-purple-200 p-4 shadow-lg">
                <div class="max-w-4xl mx-auto">
                    <div class="flex gap-3 items-center">
                        <div class="flex-1 relative">
                            <textarea rows="1"
                                class="w-full px-6 py-4 border-2 border-purple-200 rounded-full focus:outline-none focus:border-purple-500 resize-none bg-purple-50 focus:bg-white transition-all duration-200"
                                placeholder="Ketik pesan Anda..."></textarea>
                        </div>
                        <button
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
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    Info Pasien
                </h3>
            </div>

            <div class="p-6">
                <!-- Foto Hewan -->
                <div class="text-center mb-6">
                    <div
                        class="w-32 h-32 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center shadow-xl">
                        <i class="fas fa-cat text-white text-6xl"></i>
                    </div>
                    <h4 id="nama-hewan" class="font-bold text-gray-900 text-xl">Fluffy</h4>
                    <p id="jenis-hewan" class="text-purple-600 font-medium">Kucing Persia</p>
                </div>

                <!-- Data Hewan -->
                <div class="space-y-3 mb-6">
                    <h5 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-purple-600"></i>
                        Data Hewan
                    </h5>

                    <div class="grid grid-cols-2 gap-3">
                        <div
                            class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200 text-center">
                            <p class="text-xs text-purple-600 font-semibold mb-1">Usia</p>
                            <p id="usia" class="font-bold text-gray-900">2 tahun</p>
                        </div>
                        <div
                            class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200 text-center">
                            <p class="text-xs text-purple-600 font-semibold mb-1">Berat</p>
                            <p id="berat" class="font-bold text-gray-900">4.5 kg</p>
                        </div>
                    </div>
                    <div class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200 text-center">
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