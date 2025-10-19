<?php
// Tanya Dokter page - Video consultation service
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

        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-display font-bold text-gray-800 mb-4">
                    Tanya Dokter
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Konsultasi langsung dengan dokter hewan via video call
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-card p-8 mb-8">
                <h2 class="text-2xl font-display font-bold text-gray-800 mb-6">Jadwalkan Konsultasi</h2>

                <div class="mb-6">
                    <label class="block text-lg font-semibold text-gray-800 mb-4">Pilih jenis layanan yang dibutuhkan:</label>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer transition-colors">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="text-2xl">🎥</span>
                                </div>
                                <h4 class="font-medium text-gray-800">Konsultasi Online</h4>
                                <p class="text-sm text-gray-600 mt-1">Video call dengan dokter</p>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer transition-colors">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="text-2xl">🏠</span>
                                </div>
                                <h4 class="font-medium text-gray-800">Kunjungan Rumah</h4>
                                <p class="text-sm text-gray-600 mt-1">Dokter datang ke rumah</p>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer transition-colors">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="text-2xl">🚨</span>
                                </div>
                                <h4 class="font-medium text-gray-800">Darurat</h4>
                                <p class="text-sm text-gray-600 mt-1">Konsultasi darurat 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Dokter</h3>
                        <div class="space-y-3">
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer">
                                <img src="public/placeholder.svg" alt="Dr. Sarah Wijaya" class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <h4 class="font-medium text-gray-800">Dr. Sarah Wijaya</h4>
                                    <p class="text-sm text-gray-600">Spesialis Anjing & Kucing</p>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            ★★★★★
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">4.8 (120 ulasan)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer">
                                <img src="public/placeholder.svg" alt="Dr. Michael Chen" class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <h4 class="font-medium text-gray-800">Dr. Michael Chen</h4>
                                    <p class="text-sm text-gray-600">Spesialis Hewan Kecil</p>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            ★★★★★
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">4.9 (95 ulasan)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Jadwal</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none">
                                    <option>09:00 - 10:00</option>
                                    <option>10:00 - 11:00</option>
                                    <option>14:00 - 15:00</option>
                                    <option>15:00 - 16:00</option>
                                    <option>16:00 - 17:00</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Konsultasi</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none">
                                    <option>Video Call (30 menit)</option>
                                    <option>Chat Konsultasi (15 menit)</option>
                                    <option>Emergency Call</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">Total Biaya</p>
                            <p class="text-2xl font-display font-bold text-purple-600">Rp 150.000</p>
                        </div>
                        <button class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-purple-600 hover:to-indigo-700 transition-all duration-300">
                            Jadwalkan Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg p-8 text-center">
                <h3 class="text-2xl font-display font-bold mb-4">
                    Konsultasi Darurat?
                </h3>
                <p class="mb-6 opacity-90">
                    Untuk kasus darurat, hubungi hotline kami 24/7
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="tel:+6281122334455" class="inline-block bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        📞 +62 811-2233-4455
                    </a>
                    <a href="?route=klinik-terdekat" class="inline-block border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition-colors">
                        🏥 Klinik Terdekat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>