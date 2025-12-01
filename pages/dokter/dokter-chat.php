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
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-indigo-100">
    <div class="flex h-screen">
        <!-- Sidebar Daftar Chat -->
        <div class="w-80 bg-white shadow-xl flex flex-col">
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-paw text-2xl"></i>
                    <h2 class="text-2xl font-bold">VetChat</h2>
                </div>
                <p class="text-purple-100 text-sm">3 konsultasi aktif</p>
            </div>
            
            <div class="overflow-y-auto flex-1 chat-scroll">
                <!-- Chat Item 1 - Active -->
                <div class="p-4 border-l-4 border-purple-600 bg-purple-50 cursor-pointer hover:bg-purple-100 transition-all duration-200">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-purple-900 text-lg">Fluffy</h3>
                        <span class="text-xs text-purple-600 bg-purple-200 px-2 py-1 rounded-full">10:30</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-purple-700 font-medium">Kucing Persia</p>
                        <p class="text-sm text-gray-700">Tidak mau makan</p>
                        <p class="text-sm text-gray-500">Budi Santoso</p>
                    </div>
                </div>

                <!-- Chat Item 2 -->
                <div class="p-4 border-l-4 border-transparent hover:border-purple-400 cursor-pointer hover:bg-gray-50 transition-all duration-200">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-gray-900 text-lg">Max</h3>
                        <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded-full">09:15</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-600 font-medium">Anjing Golden Retriever</p>
                        <p class="text-sm text-gray-700">Diare dan lemas</p>
                        <p class="text-sm text-gray-500">Siti Rahma</p>
                    </div>
                </div>

                <!-- Chat Item 3 -->
                <div class="p-4 border-l-4 border-transparent hover:border-purple-400 cursor-pointer hover:bg-gray-50 transition-all duration-200">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-gray-900 text-lg">Momo</h3>
                        <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded-full">Kemarin</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-600 font-medium">Hamster</p>
                        <p class="text-sm text-gray-700">Mata berair</p>
                        <p class="text-sm text-gray-500">Andi Wijaya</p>
                    </div>
                </div>
            </div>
        </div>

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
                        <span class="bg-white bg-opacity-20 backdrop-blur-sm px-4 py-2 rounded-full text-sm flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            Online
                        </span>
                    </div>
                </div>
            </div>

            <!-- Area Pesan -->
            <div class="flex-1 overflow-y-auto p-6 chat-scroll" style="background: linear-gradient(to bottom, #faf5ff, #f3e8ff);">
                <div class="max-w-4xl mx-auto space-y-4">
                    <!-- Pesan dari Pemilik -->
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <div class="bg-white rounded-3xl rounded-tl-none p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                                <p class="text-gray-800">Selamat pagi dokter, kucing saya Fluffy sudah 2 hari ini tidak mau makan sama sekali.</p>
                                <p class="text-xs text-gray-400 mt-2">10:25</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan dari Dokter -->
                    <div class="flex items-start gap-3 justify-end">
                        <div class="flex-1 flex justify-end">
                            <div class="gradient-bg text-white rounded-3xl rounded-tr-none p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                                <p>Baik Pak Budi, saya akan bantu. Boleh ceritakan gejala lainnya yang terlihat pada Fluffy?</p>
                                <p class="text-xs text-purple-200 mt-2 text-right">10:26</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan dari Pemilik -->
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <div class="bg-white rounded-3xl rounded-tl-none p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                                <p class="text-gray-800">Matanya terlihat berair dan dia lebih banyak tidur dari biasanya dok.</p>
                                <p class="text-xs text-gray-400 mt-2">10:27</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan dari Dokter -->
                    <div class="flex items-start gap-3 justify-end">
                        <div class="flex-1 flex justify-end">
                            <div class="gradient-bg text-white rounded-3xl rounded-tr-none p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                                <p>Terima kasih informasinya. Saya sarankan untuk membawa Fluffy ke klinik untuk pemeriksaan lebih lanjut ya Pak.</p>
                                <p class="text-xs text-purple-200 mt-2 text-right">10:28</p>
                            </div>
                        </div>
                    </div>

                    <!-- Typing Indicator -->
                    <div class="flex items-start gap-3">
                        <div class="bg-white rounded-3xl rounded-tl-none p-4 shadow-md w-20">
                            <div class="flex gap-1">
                                <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
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
                            <textarea 
                                rows="1" 
                                class="w-full px-6 py-4 border-2 border-purple-200 rounded-full focus:outline-none focus:border-purple-500 resize-none bg-purple-50 focus:bg-white transition-all duration-200"
                                placeholder="Ketik pesan Anda..."
                            ></textarea>
                        </div>
                        <button class="w-14 h-14 gradient-bg text-white rounded-full hover:scale-110 transition-transform duration-200 flex items-center justify-center shadow-lg flex-shrink-0">
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
                    <div class="w-32 h-32 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center shadow-xl">
                        <i class="fas fa-cat text-white text-6xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xl">Fluffy</h4>
                    <p class="text-purple-600 font-medium">Kucing Persia</p>
                </div>

                <!-- Data Utama -->
                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl p-4 mb-6 shadow-md">
                    <h5 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-user-circle text-purple-600"></i>
                        Informasi Pemilik
                    </h5>
                    <p class="text-gray-700 font-medium">Budi Santoso</p>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                        <i class="fas fa-phone text-purple-500"></i>
                        +62 812-3456-7890
                    </p>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                        <i class="fas fa-envelope text-purple-500"></i>
                        budi.santoso@email.com
                    </p>
                </div>

                <!-- Data Hewan -->
                <div class="space-y-3 mb-6">
                    <h5 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-purple-600"></i>
                        Data Hewan
                    </h5>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200">
                            <p class="text-xs text-purple-600 font-semibold mb-1">Usia</p>
                            <p class="font-bold text-gray-900">2 tahun</p>
                        </div>
                        <div class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200">
                            <p class="text-xs text-purple-600 font-semibold mb-1">Berat</p>
                            <p class="font-bold text-gray-900">4.5 kg</p>
                        </div>
                        <div class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200">
                            <p class="text-xs text-purple-600 font-semibold mb-1">Jenis Kelamin</p>
                            <p class="font-bold text-gray-900">Jantan</p>
                        </div>
                        <div class="bg-white border-2 border-purple-100 rounded-2xl p-3 hover:border-purple-300 transition-colors duration-200">
                            <p class="text-xs text-purple-600 font-semibold mb-1">Warna</p>
                            <p class="font-bold text-gray-900">Putih</p>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Medis -->
                <div class="mb-6">
                    <h5 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-history text-purple-600"></i>
                        Riwayat Medis
                    </h5>
                    <div class="space-y-3">
                        <div class="border-l-4 border-green-500 bg-green-50 pl-4 py-3 rounded-r-xl hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-bold text-sm text-gray-900">Vaksinasi Rabies</p>
                                <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded-full">Selesai</span>
                            </div>
                            <p class="text-xs text-gray-600">3 bulan lalu • Dr. Sarah</p>
                        </div>
                        <div class="border-l-4 border-blue-500 bg-blue-50 pl-4 py-3 rounded-r-xl hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-bold text-sm text-gray-900">Check-up Rutin</p>
                                <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full">Selesai</span>
                            </div>
                            <p class="text-xs text-gray-600">2 bulan lalu • Dr. Ahmad</p>
                        </div>
                        <div class="border-l-4 border-purple-500 bg-purple-50 pl-4 py-3 rounded-r-xl hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-bold text-sm text-gray-900">Grooming</p>
                                <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded-full">Selesai</span>
                            </div>
                            <p class="text-xs text-gray-600">1 bulan lalu • VetCare Clinic</p>
                        </div>
                    </div>
                </div>

                <!-- Catatan Penting -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-2xl p-4 shadow-md">
                    <h5 class="font-bold text-orange-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-orange-600"></i>
                        Catatan Penting
                    </h5>
                    <div class="space-y-2">
                        <div class="bg-white bg-opacity-50 rounded-lg p-2">
                            <p class="text-sm text-orange-900 font-medium">⚠️ Alergi makanan laut</p>
                        </div>
                        <div class="bg-white bg-opacity-50 rounded-lg p-2">
                            <p class="text-sm text-orange-900 font-medium">💊 Sedang konsumsi vitamin</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 space-y-2">
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar-check"></i>
                        Jadwalkan Kunjungan
                    </button>
                    <button class="w-full bg-white hover:bg-purple-50 text-purple-600 border-2 border-purple-600 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-prescription"></i>
                        Kirim Resep
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>