<?php
// Klinik Terdekat page - Find nearby clinics
?>
<div class="pt-32 pb-20">
    <div class="container mx-auto px-4">
        <a href="?route=" class="inline-flex items-center mb-8 text-gray-600 hover:text-purple-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>

        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <span class="text-4xl text-green-600">📍</span>
                </div>
                <h1 class="text-4xl font-display font-bold text-gray-800 mb-4">
                    Klinik Terdekat
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Temukan klinik hewan terdekat dari lokasi Anda
                </p>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-card p-6 mb-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Anda</label>
                        <div class="relative">
                            <input type="text" placeholder="Masukkan alamat atau kota" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Layanan</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                            <option>Semua Layanan</option>
                            <option>Klinik Umum</option>
                            <option>Klinik Spesialis</option>
                            <option>Rumah Sakit Hewan</option>
                            <option>Apotek Hewan</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <button class="w-full md:w-auto bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300">
                        <span class="mr-2">🔍</span>
                        Cari Klinik
                    </button>
                </div>
            </div>

            <!-- Clinic Results -->
            <div class="space-y-6">
                <!-- Clinic Card 1 -->
                <div class="bg-white rounded-lg shadow-card p-6 hover:shadow-hero transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 bg-gradient-to-br from-green-100 to-green-50 rounded-lg flex items-center justify-center">
                                <span class="text-3xl">🏥</span>
                            </div>
                        </div>

                        <div class="flex-grow">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-display font-bold text-gray-800 mb-1">Klinik Hewan Jakarta Pusat</h3>
                                    <p class="text-gray-600 mb-2">Jl. Sudirman No. 123, Jakarta Pusat</p>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <span class="text-yellow-400">⭐</span>
                                            4.7 (245 ulasan)
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span>📍</span>
                                            2.3 km
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span>🕒</span>
                                            Buka 24 jam
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 md:mt-0">
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Buka</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm">Klinik Umum</span>
                                <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-sm">Operasi</span>
                                <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-full text-sm">Vaksinasi</span>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300">
                                    <span class="mr-2">📞</span>
                                    Hubungi
                                </button>
                                <button class="flex-1 border-2 border-green-500 text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-green-50 transition-all duration-300">
                                    <span class="mr-2">📍</span>
                                    Petunjuk Arah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clinic Card 2 -->
                <div class="bg-white rounded-lg shadow-card p-6 hover:shadow-hero transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                                <span class="text-3xl">🏥</span>
                            </div>
                        </div>

                        <div class="flex-grow">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-display font-bold text-gray-800 mb-1">RSH Jakarta Selatan</h3>
                                    <p class="text-gray-600 mb-2">Jl. Gatot Subroto No. 456, Jakarta Selatan</p>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <span class="text-yellow-400">⭐</span>
                                            4.9 (189 ulasan)
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span>📍</span>
                                            4.1 km
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span>🕒</span>
                                            08:00 - 20:00
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 md:mt-0">
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Buka</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="bg-red-50 text-red-700 px-3 py-1 rounded-full text-sm">Rumah Sakit</span>
                                <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-sm">ICU</span>
                                <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-full text-sm">Emergency</span>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
                                    <span class="mr-2">📞</span>
                                    Hubungi
                                </button>
                                <button class="flex-1 border-2 border-blue-500 text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-all duration-300">
                                    <span class="mr-2">📍</span>
                                    Petunjuk Arah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clinic Card 3 -->
                <div class="bg-white rounded-lg shadow-card p-6 hover:shadow-hero transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-purple-50 rounded-lg flex items-center justify-center">
                                <span class="text-3xl">🏥</span>
                            </div>
                        </div>

                        <div class="flex-grow">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-display font-bold text-gray-800 mb-1">Klinik Hewan Menteng</h3>
                                    <p class="text-gray-600 mb-2">Jl. Teuku Umar No. 789, Jakarta Pusat</p>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <span class="text-yellow-400">⭐</span>
                                            4.6 (156 ulasan)
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span>📍</span>
                                            3.7 km
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span>🕒</span>
                                            09:00 - 18:00
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 md:mt-0">
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Tutup</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm">Klinik Umum</span>
                                <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm">Grooming</span>
                                <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-full text-sm">Vaksinasi</span>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-purple-600 hover:to-purple-700 transition-all duration-300">
                                    <span class="mr-2">📞</span>
                                    Hubungi
                                </button>
                                <button class="flex-1 border-2 border-purple-500 text-purple-600 px-6 py-2 rounded-lg font-semibold hover:bg-purple-50 transition-all duration-300">
                                    <span class="mr-2">📍</span>
                                    Petunjuk Arah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Section -->
            <div class="mt-12 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg p-8 text-center">
                <h3 class="text-2xl font-display font-bold mb-4">
                    Butuh Bantuan Darurat?
                </h3>
                <p class="mb-6 opacity-90">
                    Untuk kasus darurat, hubungi hotline kami 24/7
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="tel:+6281122334455" class="inline-block bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        📞 +62 811-2233-4455
                    </a>
                    <a href="?route=tanya-dokter" class="inline-block border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition-colors">
                        🩺 Konsultasi Online
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
