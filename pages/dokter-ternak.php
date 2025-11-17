<?php
// Dokter Ternak page - Livestock veterinary services
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
                <div class="w-20 h-20 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <span class="text-4xl text-orange-600">🐄</span>
                </div>
                <h1 class="text-4xl font-display font-bold text-gray-800 mb-4">
                    Dokter Ternak
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Layanan kesehatan untuk ternak dan hewan produktif
                </p>
            </div>

            <!-- Services Overview -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-white rounded-lg shadow-card p-6 text-center hover:shadow-hero transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-glow">
                        <span class="text-2xl text-white">🐄</span>
                    </div>
                    <h3 class="font-display text-lg font-bold text-gray-900 mb-2">Sapi & Kerbau</h3>
                    <p class="text-gray-600 text-sm">Kesehatan ternak besar</p>
                </div>

                <div class="bg-white rounded-lg shadow-card p-6 text-center hover:shadow-hero transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-glow">
                        <span class="text-2xl text-white">🐐</span>
                    </div>
                    <h3 class="font-display text-lg font-bold text-gray-900 mb-2">Kambing & Domba</h3>
                    <p class="text-gray-600 text-sm">Perawatan ternak kecil</p>
                </div>

                <div class="bg-white rounded-lg shadow-card p-6 text-center hover:shadow-hero transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-glow">
                        <span class="text-2xl text-white">🐔</span>
                    </div>
                    <h3 class="font-display text-lg font-bold text-gray-900 mb-2">Ayam & Unggas</h3>
                    <p class="text-gray-600 text-sm">Kesehatan unggas</p>
                </div>

                <div class="bg-white rounded-lg shadow-card p-6 text-center hover:shadow-hero transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-glow">
                        <span class="text-2xl text-white">🐖</span>
                    </div>
                    <h3 class="font-display text-lg font-bold text-gray-900 mb-2">Babi & Lainnya</h3>
                    <p class="text-gray-600 text-sm">Ternak lainnya</p>
                </div>
            </div>

            <!-- Consultation Form -->
            <div class="bg-white rounded-lg shadow-card p-8 mb-8">
                <h2 class="text-2xl font-display font-bold text-gray-800 mb-6">Konsultasi Dokter Ternak</h2>

                <form class="space-y-6">
                    <!-- Animal Type -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-800 mb-4">Jenis Hewan Ternak</label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="p-4 border border-gray-200 rounded-lg hover:border-orange-300 cursor-pointer transition-colors">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-2xl">🐄</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Sapi</h4>
                                </div>
                            </div>

                            <div class="p-4 border border-gray-200 rounded-lg hover:border-orange-300 cursor-pointer transition-colors">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-2xl">🐂</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Kerbau</h4>
                                </div>
                            </div>

                            <div class="p-4 border border-gray-200 rounded-lg hover:border-orange-300 cursor-pointer transition-colors">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-2xl">🐐</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Kambing</h4>
                                </div>
                            </div>

                            <div class="p-4 border border-gray-200 rounded-lg hover:border-orange-300 cursor-pointer transition-colors">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-2xl">🐑</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Domba</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Problem Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Masalah</label>
                        <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="Jelaskan gejala atau masalah yang dialami hewan ternak Anda..."></textarea>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="Nama Anda">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="+62">
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="Kota/Kabupaten, Provinsi">
                    </div>

                    <!-- Service Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Layanan</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">
                            <option>Konsultasi Online</option>
                            <option>Kunjungan Lokasi</option>
                            <option>Pemeriksaan Kandang</option>
                            <option>Vaksinasi Massal</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 border-t border-gray-200">
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">📞</span>
                            Minta Konsultasi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Veterinarians -->
            <div class="bg-white rounded-lg shadow-card p-8 mb-8">
                <h2 class="text-2xl font-display font-bold text-gray-800 mb-6">Dokter Ternak Kami</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-orange-300 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-50 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">👨‍⚕️</span>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-display text-lg font-bold text-gray-800 mb-1">Dr. Ahmad Santoso</h3>
                            <p class="text-orange-600 text-sm mb-2">Spesialis Ternak Besar</p>
                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                <span class="flex items-center gap-1">
                                    <span class="text-yellow-400">⭐</span>
                                    4.8 (67 ulasan)
                                </span>
                                <span>•</span>
                                <span>12+ Tahun</span>
                            </div>
                            <p class="text-gray-600 text-sm">Berpengalaman dalam kesehatan sapi, kerbau, dan ternak besar lainnya.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-orange-300 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-50 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">👩‍⚕️</span>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-display text-lg font-bold text-gray-800 mb-1">Dr. Maya Putri</h3>
                            <p class="text-green-600 text-sm mb-2">Spesialis Unggas</p>
                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                <span class="flex items-center gap-1">
                                    <span class="text-yellow-400">⭐</span>
                                    4.9 (89 ulasan)
                                </span>
                                <span>•</span>
                                <span>8+ Tahun</span>
                            </div>
                            <p class="text-gray-600 text-sm">Ahli dalam kesehatan ayam, itik, dan berbagai jenis unggas.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Section -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg p-8 text-center">
                <h3 class="text-2xl font-display font-bold mb-4">
                    Darurat Ternak?
                </h3>
                <p class="mb-6 opacity-90">
                    Untuk kasus darurat ternak, hubungi hotline khusus kami
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="tel:+6281122334455" class="inline-block bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        📞 +62 811-2233-4455
                    </a>
                    <a href="?route=klinik-terdekat" class="inline-block border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition-colors">
                        🏥 Klinik Terdekat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
