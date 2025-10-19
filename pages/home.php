<!-- Main Content -->
<main>
    <!-- Hero Section with Doctor Profile -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-hero opacity-10"></div>

        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8 animate-fade-in">
                    <div class="inline-block">
                       
                    </div>

                    <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 leading-tight">
                        Konsultasi dengan
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-purple-400 animate-gradient-shift">
                            Dokter Hewan
                        </span>
                        Terpercaya
                    </h1>

                    <p class="text-xl text-gray-600 leading-relaxed">
                        Dapatkan perawatan terbaik untuk hewan kesayangan Anda. Konsultasi online 24/7 dengan dokter hewan profesional dan berpengalaman.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="navigateTo('?route=konsultasi-dokter')" class="font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-8 py-4 rounded-2xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow hover:shadow-hero text-lg">
                            <span class="mr-2">🩺</span>
                            Mulai Konsultasi
                        </button>
                        <button onclick="scrollToSection('cara-kerja')" class="font-display font-semibold bg-white text-purple-600 border-2 border-purple-600 px-8 py-4 rounded-2xl hover:bg-purple-50 transition-all duration-300 text-lg">
                            <span class="mr-2">📖</span>
                            Pelajari Lebih Lanjut
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t border-gray-200">
                        <div>
                            <div class="font-display text-3xl font-bold text-purple-600">500+</div>
                            <div class="text-sm text-gray-600">Dokter Ahli</div>
                        </div>
                        <div>
                            <div class="font-display text-3xl font-bold text-purple-600">50K+</div>
                            <div class="text-sm text-gray-600">Konsultasi</div>
                        </div>
                        <div>
                            <div class="font-display text-3xl font-bold text-purple-600">4.9</div>
                            <div class="text-sm text-gray-600">Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Doctor Profile Card -->
                <div class="animate-slide-up">
                    <div class="relative">
                        <div class="absolute -top-4 -right-4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse-glow"></div>
                        <div class="absolute -bottom-8 -left-4 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse-glow"></div>

                        <!-- Doctor Card -->
                        <div class="relative bg-white/90 backdrop-blur-xl rounded-3xl shadow-hero p-8 border border-purple-100">
                            <div id="doctor-profile-image" class="w-full aspect-square bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl mb-6 flex items-center justify-center overflow-hidden">
                                <div class="text-center space-y-4">
                                    <div class="text-6xl">👨‍⚕️</div>
                                    <p class="text-gray-400 text-sm px-4">Foto dokter akan ditampilkan di sini</p>
                                </div>
                            </div>

                            <div id="doctor-profile-info" class="space-y-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-display text-2xl font-bold text-gray-900" id="doctor-name">Dr. Nama Dokter</h3>
                                        <p class="text-purple-600 font-medium" id="doctor-specialty">Spesialisasi</p>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-yellow-400 text-lg">⭐</span>
                                        <span class="font-semibold" id="doctor-rating">5.0</span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-sm" id="doctor-experience">10+ Tahun</span>
                                    <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm" id="doctor-status">🟢 Tersedia</span>
                                </div>

                                <p class="text-gray-600 text-sm" id="doctor-description">
                                    Deskripsi singkat tentang dokter akan ditampilkan di sini.
                                </p>

                                <button onclick="navigateTo('?route=konsultasi-dokter')" class="w-full font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-card">
                                    <span class="mr-2">💬</span>
                                    Chat Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Layanan Kami
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Berbagai layanan kesehatan hewan yang dapat Anda akses dengan mudah
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8">
                <a href="?route=konsultasi-dokter" class="group">
                    <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-3xl p-8 hover:border-purple-300 hover:shadow-card transition-all duration-300 h-full">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-glow">
                            <span class="text-3xl">🩺</span>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                            Konsultasi Dokter
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Chat atau video call langsung dengan dokter hewan berpengalaman
                        </p>
                    </div>
                </a>

                <a href="?route=tanya-dokter" class="group">
                    <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-3xl p-8 hover:border-purple-300 hover:shadow-card transition-all duration-300 h-full">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-glow">
                            <span class="text-3xl">❓</span>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                            Tanya Dokter
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Ajukan pertanyaan seputar kesehatan hewan kesayangan Anda
                        </p>
                    </div>
                </a>

                <a href="?route=klinik-terdekat" class="group">
                    <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-3xl p-8 hover:border-purple-300 hover:shadow-card transition-all duration-300 h-full">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-glow">
                            <span class="text-3xl">📍</span>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                            Klinik Terdekat
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Temukan klinik hewan terdekat dari lokasi Anda
                        </p>
                    </div>
                </a>

                <a href="?route=dokter-ternak" class="group">
                    <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-3xl p-8 hover:border-purple-300 hover:shadow-card transition-all duration-300 h-full">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-glow">
                            <span class="text-3xl">🐄</span>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                            Dokter Ternak
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Layanan kesehatan untuk ternak dan hewan produktif
                        </p>
                    </div>
                </a>

                <a href="?route=dokter-hewan-kecil" class="group">
                    <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-3xl p-8 hover:border-purple-300 hover:shadow-card transition-all duration-300 h-full">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-glow">
                            <span class="text-3xl">🐱</span>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                            Dokter Hewan Kecil
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Layanan kesehatan untuk kucing, anjing, dan hewan peliharaan kecil
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Doctors Section -->
    <section id="dokter" class="py-20 bg-gradient-to-b from-purple-50 to-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Tim Dokter Kami
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Dokter hewan profesional dan berpengalaman siap membantu Anda
                </p>
            </div>

            <div id="doctors-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl shadow-card p-6 border border-gray-100">
                    <div class="aspect-square bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl mb-4 flex items-center justify-center">
                        <span class="text-5xl">👨‍⚕️</span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-gray-900 mb-2">Dr. Nama Dokter 1</h3>
                    <p class="text-purple-600 text-sm mb-3">Spesialis Hewan Kecil</p>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                        <span>⭐ 4.9</span>
                        <span>•</span>
                        <span>15+ Tahun</span>
                    </div>
                    <button class="w-full bg-purple-50 text-purple-600 py-2 rounded-xl hover:bg-purple-100 transition-colors font-medium">
                        Lihat Profile
                    </button>
                </div>

                <div class="bg-white rounded-3xl shadow-card p-6 border border-gray-100">
                    <div class="aspect-square bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl mb-4 flex items-center justify-center">
                        <span class="text-5xl">👩‍⚕️</span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-gray-900 mb-2">Dr. Nama Dokter 2</h3>
                    <p class="text-purple-600 text-sm mb-3">Spesialis Bedah</p>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                        <span>⭐ 4.9</span>
                        <span>•</span>
                        <span>10+ Tahun</span>
                    </div>
                    <button class="w-full bg-purple-50 text-purple-600 py-2 rounded-xl hover:bg-purple-100 transition-colors font-medium">
                        Lihat Profile
                    </button>
                </div>

                <div class="bg-white rounded-3xl shadow-card p-6 border border-gray-100">
                    <div class="aspect-square bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl mb-4 flex items-center justify-center">
                        <span class="text-5xl">👨‍⚕️</span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-gray-900 mb-2">Dr. Nama Dokter 3</h3>
                    <p class="text-purple-600 text-sm mb-3">Spesialis Eksotis</p>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                        <span>⭐ 4.8</span>
                        <span>•</span>
                        <span>8+ Tahun</span>
                    </div>
                    <button class="w-full bg-purple-50 text-purple-600 py-2 rounded-xl hover:bg-purple-100 transition-colors font-medium">
                        Lihat Profile
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="cara-kerja" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Cara Kerja
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    3 langkah mudah untuk mendapatkan perawatan terbaik
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="text-center animate-slide-up">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-glow">
                        <span class="text-4xl text-white font-bold">1</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-gray-900 mb-3">Pilih Layanan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pilih jenis layanan yang Anda butuhkan untuk hewan kesayangan
                    </p>
                </div>

                <div class="text-center animate-slide-up" style="animation-delay: 0.1s">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-glow">
                        <span class="text-4xl text-white font-bold">2</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-gray-900 mb-3">Pilih Dokter</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pilih dokter hewan yang sesuai dengan kebutuhan Anda
                    </p>
                </div>

                <div class="text-center animate-slide-up" style="animation-delay: 0.2s">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-glow">
                        <span class="text-4xl text-white font-bold">3</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-gray-900 mb-3">Mulai Konsultasi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Konsultasi dengan dokter via chat atau video call
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Section -->
    <section id="artikel" class="py-20 bg-gradient-to-b from-white to-purple-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Artikel & Tips
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Informasi dan tips kesehatan hewan dari para ahli
                </p>
            </div>

            <div id="articles-grid" class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl overflow-hidden shadow-card hover:shadow-hero transition-all duration-300">
                    <div class="aspect-video bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center">
                        <span class="text-5xl">📰</span>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-purple-600 mb-2">Kesehatan Hewan</div>
                        <h3 class="font-display text-xl font-bold text-gray-900 mb-2">
                            Tips Merawat Hewan Peliharaan
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Panduan lengkap merawat hewan peliharaan dengan baik dan benar...
                        </p>
                        <button class="text-purple-600 font-medium hover:text-purple-700 transition-colors">
                            Baca Selengkapnya →
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden shadow-card hover:shadow-hero transition-all duration-300">
                    <div class="aspect-video bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center">
                        <span class="text-5xl">📰</span>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-purple-600 mb-2">Nutrisi</div>
                        <h3 class="font-display text-xl font-bold text-gray-900 mb-2">
                            Makanan Sehat untuk Hewan
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Rekomendasi makanan dan nutrisi yang tepat untuk hewan kesayangan...
                        </p>
                        <button class="text-purple-600 font-medium hover:text-purple-700 transition-colors">
                            Baca Selengkapnya →
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden shadow-card hover:shadow-hero transition-all duration-300">
                    <div class="aspect-video bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center">
                        <span class="text-5xl">📰</span>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-purple-600 mb-2">Perawatan</div>
                        <h3 class="font-display text-xl font-bold text-gray-900 mb-2">
                            Cara Memandikan Hewan
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Teknik dan tips memandikan hewan peliharaan dengan aman...
                        </p>
                        <button class="text-purple-600 font-medium hover:text-purple-700 transition-colors">
                            Baca Selengkapnya →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12 animate-fade-in">
                    <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                        Hubungi Kami
                    </h2>
                    <p class="text-xl text-gray-600">
                        Ada pertanyaan? Tim kami siap membantu Anda
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 mb-12">
                    <div class="text-center p-6 bg-purple-50 rounded-2xl">
                        <div class="text-4xl mb-3">📞</div>
                        <h3 class="font-display font-bold text-gray-900 mb-2">Telepon</h3>
                        <p class="text-gray-600">+62 811-2233-4455</p>
                    </div>

                    <div class="text-center p-6 bg-purple-50 rounded-2xl">
                        <div class="text-4xl mb-3">📧</div>
                        <h3 class="font-display font-bold text-gray-900 mb-2">Email</h3>
                        <p class="text-gray-600">info@vethewan.co.id</p>
                    </div>

                    <div class="text-center p-6 bg-purple-50 rounded-2xl">
                        <div class="text-4xl mb-3">📍</div>
                        <h3 class="font-display font-bold text-gray-900 mb-2">Lokasi</h3>
                        <p class="text-gray-600">Jember, Indonesia</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-3xl p-8">
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Nama</label>
                                <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all" placeholder="Nama lengkap Anda">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all" placeholder="email@example.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Pesan</label>
                            <textarea rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all" placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>
                        <button type="submit" class="w-full font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-8 py-4 rounded-2xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">📨</span>
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-hero">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto text-white">
                <h2 class="font-display text-4xl md:text-5xl font-bold mb-6">
                    Siap Memberikan yang Terbaik untuk Hewan Kesayangan?
                </h2>
                <p class="text-xl mb-8 text-white/90">
                    Bergabunglah dengan ribuan pemilik hewan yang mempercayai VetCare
                </p>
                <button onclick="navigateTo('?route=auth')" class="font-display font-semibold bg-white text-purple-600 px-8 py-4 rounded-2xl hover:bg-gray-100 transition-all duration-300 shadow-hero text-lg">
                    <span class="mr-2">✨</span>
                    Daftar Sekarang - Gratis
                </button>
            </div>
        </div>
    </section>
</main>
