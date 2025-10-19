<!-- Dokter Hewan Kecil Page -->
<main class="pt-32 pb-20">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-16 animate-fade-in">
            <div class="inline-block mb-6">
                <span class="text-6xl">🐱</span>
            </div>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Dokter Hewan Kecil
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Layanan kesehatan khusus untuk kucing, anjing, dan hewan peliharaan kecil lainnya
            </p>
        </div>

        <!-- Services Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-3xl shadow-card p-8 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">💉</span>
                </div>
                <h3 class="font-display text-xl font-bold text-gray-900 mb-3">Vaksinasi</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Vaksinasi lengkap untuk melindungi hewan peliharaan dari berbagai penyakit
                </p>
                <div class="text-purple-600 font-semibold">Mulai dari Rp 150.000</div>
            </div>

            <div class="bg-white rounded-3xl shadow-card p-8 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🩺</span>
                </div>
                <h3 class="font-display text-xl font-bold text-gray-900 mb-3">Pemeriksaan Umum</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Pemeriksaan kesehatan rutin untuk memantau kondisi hewan peliharaan
                </p>
                <div class="text-purple-600 font-semibold">Mulai dari Rp 100.000</div>
            </div>

            <div class="bg-white rounded-3xl shadow-card p-8 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🦷</span>
                </div>
                <h3 class="font-display text-xl font-bold text-gray-900 mb-3">Perawatan Gigi</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Pembersihan karang gigi dan perawatan kesehatan mulut
                </p>
                <div class="text-purple-600 font-semibold">Mulai dari Rp 200.000</div>
            </div>

            <div class="bg-white rounded-3xl shadow-card p-8 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">✂️</span>
                </div>
                <h3 class="font-display text-xl font-bold text-gray-900 mb-3">Grooming</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Perawatan bulu, kuku, dan kebersihan hewan peliharaan
                </p>
                <div class="text-purple-600 font-semibold">Mulai dari Rp 75.000</div>
            </div>

            <div class="bg-white rounded-3xl shadow-card p-8 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🔬</span>
                </div>
                <h3 class="font-display text-xl font-bold text-gray-900 mb-3">Laboratorium</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Tes darah, urine, dan pemeriksaan laboratorium lainnya
                </p>
                <div class="text-purple-600 font-semibold">Mulai dari Rp 250.000</div>
            </div>

            <div class="bg-white rounded-3xl shadow-card p-8 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🏥</span>
                </div>
                <h3 class="font-display text-xl font-bold text-gray-900 mb-3">Rawat Inap</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Perawatan intensif untuk hewan peliharaan yang membutuhkan pengawasan khusus
                </p>
                <div class="text-purple-600 font-semibold">Mulai dari Rp 500.000/hari</div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-3xl p-8 text-center">
            <h2 class="font-display text-3xl font-bold text-gray-900 mb-4">
                Butuh Konsultasi?
            </h2>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                Konsultasikan kesehatan hewan peliharaan Anda dengan dokter hewan kami secara online atau offline
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="navigateTo('?route=konsultasi-dokter')" class="font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-8 py-4 rounded-2xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow">
                    <span class="mr-2">🩺</span>
                    Konsultasi Online
                </button>
                <button onclick="navigateTo('?route=klinik-terdekat')" class="font-display font-semibold bg-white text-purple-600 border-2 border-purple-600 px-8 py-4 rounded-2xl hover:bg-purple-50 transition-all duration-300">
                    <span class="mr-2">📍</span>
                    Temukan Klinik
                </button>
            </div>
        </div>
    </div>
</main>
