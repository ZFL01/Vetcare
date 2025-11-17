<?php
// 404 Error page - Page not found
?>
<div class="pt-32 pb-20">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <div class="w-32 h-32 bg-gradient-to-br from-red-100 to-red-50 rounded-full flex items-center justify-center mx-auto mb-8">
                <span class="text-6xl">😿</span>
            </div>

            <h1 class="text-6xl font-display font-bold text-gray-900 mb-4">404</h1>
            <h2 class="text-3xl font-display font-bold text-gray-800 mb-6">Halaman Tidak Ditemukan</h2>

            <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan atau tidak tersedia.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="navigateTo('?route')" class="font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-8 py-4 rounded-2xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow">
                
                    <span class="mr-2">🏠</span>
                    Kembali ke Beranda
                </button>
                <button onclick="navigateTo('?route=tanya-dokter')" class="font-display font-semibold bg-white text-purple-600 border-2 border-purple-600 px-8 py-4 rounded-2xl hover:bg-purple-50 transition-all duration-300">
                    <span class="mr-2">💬</span>
                    Hubungi Dukungan
                </button>
            </div>

            <div class="mt-12 p-6 bg-purple-50 rounded-2xl">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Halaman Populer</h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <a href="?route=konsultasi-dokter" class="text-left p-3 bg-white rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="font-medium text-purple-600">🩺 Konsultasi Dokter</div>
                        <div class="text-sm text-gray-600">Chat dengan dokter hewan</div>
                    </a>
                    <a href="?route=klinik-terdekat" class="text-left p-3 bg-white rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="font-medium text-purple-600">📍 Klinik Terdekat</div>
                        <div class="text-sm text-gray-600">Temukan klinik hewan</div>
                    </a>
                    <a href="?route=tanya-dokter" class="text-left p-3 bg-white rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="font-medium text-purple-600">❓ Tanya Dokter</div>
                        <div class="text-sm text-gray-600">Ajukan pertanyaan</div>
                    </a>
                    <a href="?route=dokter-ternak" class="text-left p-3 bg-white rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="font-medium text-purple-600">🐄 Dokter Ternak</div>
                        <div class="text-sm text-gray-600">Layanan ternak</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
