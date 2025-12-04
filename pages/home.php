
    <!-- Hero Section with Doctor Profile -->
    <section class="relative pb-20 overflow-hidden">
        <!-- gradient background - set pointer-events-none so it doesn't block buttons -->
        <div class="absolute inset-0 bg-gradient-hero opacity-10 pointer-events-none"></div>

        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8 animate-fade-in">
                    <div class="inline-block">

                    </div>

                    <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 leading-tight">
                        Konsultasi dengan
                        <span class="text-transparent bg-clip-text bg-gradient-indigo-violet animate-gradient-shift">
                            Dokter Hewan
                        </span>
                        Terpercaya
                    </h1>

                    <p class="text-xl text-gray-600 leading-relaxed">
                        Dapatkan perawatan terbaik untuk hewan kesayangan Anda. Konsultasi online 24/7 dengan dokter
                        hewan profesional dan berpengalaman.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 relative z-30">
                        <button onclick="navigateTo('?route=pilih-dokter')" class="font-display font-semibold bg-gradient-indigo-violet text-white px-8 py-4 rounded-2xl hover:bg-gradient-violet-indigo transition-all duration-300 shadow-glow hover:shadow-hero text-lg">
                            <span class="mr-2">🩺</span>
                            Mulai Konsultasi
                        </button>
                        <button onclick="scrollToSection('cara-kerja')"
                            class="font-display font-semibold bg-white text-purple-600 border-2 border-purple-600 px-8 py-4 rounded-2xl hover:bg-purple-50 transition-all duration-300 text-lg">
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
                        <div
                            class="absolute -top-4 -right-4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse-glow">
                        </div>
                        <div
                            class="absolute -bottom-8 -left-4 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse-glow">
                        </div>

                        <!-- Doctor Card -->
                          <div
                            class="relative bg-white/90 backdrop-blur-xl rounded-3xl shadow-hero p-8 border border-purple-100">
                            <div id="doctor-profile-image"
                                class="w-full aspect-square bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl mb-6 flex items-center justify-center overflow-hidden relative">
                                <div class="slideshow-container w-full h-full relative">
                                    <div
                                        class="slide active text-center space-y-4 flex flex-col justify-center items-center">
                                        <div class="text-6xl">👨‍⚕️</div>
                                        <p class="text-gray-400 text-sm px-4">Foto dokter akan ditampilkan di sini</p>
                                    </div>
                                    <div class="slide text-center space-y-4 flex flex-col justify-center items-center">
                                        <div class="text-6xl">👩‍⚕️</div>
                                        <p class="text-gray-400 text-sm px-4">Dokter spesialis hewan kecil</p>
                                    </div>
                                    <div class="slide text-center space-y-4 flex flex-col justify-center items-center">
                                        <div class="text-6xl">🧑‍⚕️</div>
                                        <p class="text-gray-400 text-sm px-4">Dokter spesialis ternak</p>
                                    </div>
                                    <div class="slide text-center space-y-4 flex flex-col justify-center items-center">
                                        <div class="text-6xl">👨‍🔬</div>
                                        <p class="text-gray-400 text-sm px-4">Dokter spesialis bedah</p>
                                    </div>
                                </div>
                            </div>

                            <div id="doctor-profile-info" class="space-y-4 fade-content">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-display text-2xl font-bold text-gray-900" id="doctor-name">Dr.
                                            Ahmad Santoso</h3>
                                        <p class="text-purple-600 font-medium" id="doctor-specialty">Dokter Hewan Umum
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-yellow-400 text-lg">⭐</span>
                                        <span class="font-semibold" id="doctor-rating">4.9</span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-sm"
                                        id="doctor-experience">12+ Tahun</span>
                                    <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm"
                                        id="doctor-status">🟢 Tersedia</span>
                                </div>

                                <p class="text-gray-600 text-sm" id="doctor-description">
                                    Dokter hewan berpengalaman dengan spesialisasi dalam diagnosis dan pengobatan
                                    berbagai jenis penyakit hewan.
                                </p>

                                <button onclick="navigateTo('?route=konsultasi-dokter')" class="w-full font-display font-semibold bg-gradient-indigo-violet text-white px-6 py-3 rounded-xl hover:bg-gradient-violet-indigo transition-all duration-300 shadow-card">
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

            <div class="grid md:grid-cols-3 lg:grid-cols-3 gap-12 max-w-6xl mx-auto">
                <!-- Konsultasi Dokter -->
                <a href="?route=pilih-dokter" class="group service-card animate-slide-up">
                    <div
                        class="bg-gradient-to-br from-white via-purple-50 to-purple-100 border-2 border-purple-200 rounded-3xl p-10 hover:border-purple-400 hover:shadow-2xl transition-all duration-500 h-full relative overflow-hidden">
                        <!-- Ripple Effect -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-purple-400/20 to-pink-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl">
                        </div>

                        <!-- Badge -->
                        <div
                            class="absolute top-4 right-4 bg-gradient-to-r from-green-400 to-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                            24/7
                        </div>

                        <div class="relative z-10">
                            <div class="w-24 h-24 bg-gradient-indigo-violet rounded-3xl flex items-center justify-center mb-8 group-hover:rotate-12 group-hover:scale-110 transition-all duration-500 shadow-glow relative">
                                <span class="text-5xl filter drop-shadow-lg">🩺</span>
                                <!-- Glow Effect -->
                                <div class="absolute inset-0 bg-gradient-indigo-violet rounded-3xl opacity-0 group-hover:opacity-30 blur-xl transition-opacity duration-500"></div>
                            </div>

                            <h3
                                class="font-display text-3xl font-bold text-gray-900 mb-4 group-hover:text-purple-700 transition-colors duration-300">
                                Konsultasi Dokter
                            </h3>
                            <p class="text-gray-600 leading-relaxed text-lg mb-6">
                                Konsultasi online dengan dokter hewan profesional kapan saja, di mana saja. Dapatkan jawaban cepat untuk pertanyaan kesehatan hewan Anda.
                        </div>
                    </div>
                </a>
                
                <!-- Klinik Terdekat -->
                <a href="?route=klinik-terdekat" class="group service-card animate-slide-up"
                    style="animation-delay: 0.2s">
                    <div
                        class="bg-gradient-to-br from-white via-purple-50 to-purple-100 border-2 border-purple-200 rounded-3xl p-10 hover:border-purple-400 hover:shadow-2xl transition-all duration-500 h-full relative overflow-hidden">
                        <!-- Ripple Effect -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-purple-400/20 to-pink-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl">
                        </div>

                        <!-- Badge -->
                        <div
                            class="absolute top-4 right-4 bg-gradient-to-r from-orange-400 to-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                            Populer
                        </div>

                        <div class="relative z-10">
                            <div class="w-24 h-24 bg-gradient-indigo-violet rounded-3xl flex items-center justify-center mb-8 group-hover:rotate-12 group-hover:scale-110 transition-all duration-500 shadow-glow relative">
                                <span class="text-5xl filter drop-shadow-lg">📍</span>
                                <!-- Glow Effect -->
                                <div class="absolute inset-0 bg-gradient-indigo-violet rounded-3xl opacity-0 group-hover:opacity-30 blur-xl transition-opacity duration-500"></div>
                            </div>

                            <h3
                                class="font-display text-3xl font-bold text-gray-900 mb-4 group-hover:text-purple-700 transition-colors duration-300">
                                Klinik Terdekat
                            </h3>

                            <p class="text-gray-600 leading-relaxed text-lg mb-6">
                                Temukan klinik hewan terdekat dengan navigasi real-time. Lihat ulasan, jam operasional,
                                dan layanan.
                            </p>

                        </div>
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
                    <div
                        class="aspect-square bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl mb-4 flex items-center justify-center">
                        <span class="text-5xl">👨‍⚕️</span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-gray-900 mb-2">Dr. Nama Dokter 1</h3>
                    <p class="text-purple-600 text-sm mb-3">Spesialis Hewan Kecil</p>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                        <span>⭐ 4.9</span>
                        <span>•</span>
                        <span>15+ Tahun</span>
                    </div>
                    <button
                        class="w-full bg-purple-50 text-purple-600 py-2 rounded-xl hover:bg-purple-100 transition-colors font-medium">
                        Lihat Profile
                    </button>
                </div>

                <div class="bg-white rounded-3xl shadow-card p-6 border border-gray-100">
                    <div
                        class="aspect-square bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl mb-4 flex items-center justify-center">
                        <span class="text-5xl">👩‍⚕️</span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-gray-900 mb-2">Dr. Nama Dokter 2</h3>
                    <p class="text-purple-600 text-sm mb-3">Spesialis Bedah</p>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                        <span>⭐ 4.9</span>
                        <span>•</span>
                        <span>10+ Tahun</span>
                    </div>
                    <button
                        class="w-full bg-purple-50 text-purple-600 py-2 rounded-xl hover:bg-purple-100 transition-colors font-medium">
                        Lihat Profile
                    </button>
                </div>

                <div class="bg-white rounded-3xl shadow-card p-6 border border-gray-100">
                    <div
                        class="aspect-square bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl mb-4 flex items-center justify-center">
                        <span class="text-5xl">👨‍⚕️</span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-gray-900 mb-2">Dr. Nama Dokter 3</h3>
                    <p class="text-purple-600 text-sm mb-3">Spesialis Eksotis</p>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                        <span>⭐ 4.8</span>
                        <span>•</span>
                        <span>8+ Tahun</span>
                    </div>
                    <button
                        class="w-full bg-purple-50 text-purple-600 py-2 rounded-xl hover:bg-purple-100 transition-colors font-medium">
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
                    <div class="w-20 h-20 bg-gradient-indigo-violet rounded-full flex items-center justify-center mx-auto mb-6 shadow-glow">
                        <span class="text-4xl text-white font-bold">1</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-gray-900 mb-3">Pilih Layanan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pilih jenis layanan yang Anda butuhkan untuk hewan kesayangan
                    </p>
                </div>

                <div class="text-center animate-slide-up" style="animation-delay: 0.1s">
                    <div class="w-20 h-20 bg-gradient-indigo-violet rounded-full flex items-center justify-center mx-auto mb-6 shadow-glow">
                        <span class="text-4xl text-white font-bold">2</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-gray-900 mb-3">Pilih Dokter</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pilih dokter hewan yang sesuai dengan kebutuhan Anda
                    </p>
                </div>

                <div class="text-center animate-slide-up" style="animation-delay: 0.2s">
                    <div class="w-20 h-20 bg-gradient-indigo-violet rounded-full flex items-center justify-center mx-auto mb-6 shadow-glow">
                        <span class="text-4xl text-white font-bold">3</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-gray-900 mb-3">Mulai Konsultasi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Konsultasi dengan dokter via chat atau berkunjung ke Klinik terdekat dengan Anda
                    </p>
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
                                <input type="text"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all"
                                    placeholder="Nama lengkap Anda">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all"
                                    placeholder="email@example.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Pesan</label>
                            <textarea rows="5"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all"
                                placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>
                        <button type="submit" class="w-full font-display font-semibold bg-gradient-indigo-violet text-white px-8 py-4 rounded-2xl hover:bg-gradient-violet-indigo transition-all duration-300 shadow-glow">
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
                <button onclick="navigateTo('?route=auth')"
                    class="font-display font-semibold bg-white text-purple-600 px-8 py-4 rounded-2xl hover:bg-gray-100 transition-all duration-300 shadow-hero text-lg">
                    <span class="mr-2">✨</span>
                    Daftar Sekarang - Gratis
                </button>
            </div>
        </div>
    </section>

<style>
    .slideshow-container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.5s ease-in-out;
    }

    .slide.active {
        opacity: 1;
        transform: translateX(0);
    }

    .slide.prev {
        transform: translateX(-100%);
    }

    .fade-content {
        transition: opacity 0.5s ease-in-out;
    }

    .fade-content.fade-out {
        opacity: 0;
    }

    .fade-content.fade-in {
        opacity: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.slide');
        const doctorInfo = document.getElementById('doctor-profile-info');
        let currentSlide = 0;

        // Doctor data for each slide
        const doctorData = [
            {
                name: "Dr. Ahmad Santoso",
                specialty: "Dokter Hewan Umum",
                rating: "4.9",
                experience: "12+ Tahun",
                status: "🟢 Tersedia",
                description: "Dokter hewan berpengalaman dengan spesialisasi dalam diagnosis dan pengobatan berbagai jenis penyakit hewan."
            },
            {
                name: "Dr. Sari Wijaya",
                specialty: "Spesialis Hewan Kecil",
                rating: "4.8",
                experience: "10+ Tahun",
                status: "🟢 Tersedia",
                description: "Spesialis dalam perawatan kesehatan kucing, anjing, dan hewan peliharaan kecil lainnya."
            },
            {
                name: "Dr. Budi Rahman",
                specialty: "Spesialis Ternak",
                rating: "4.9",
                experience: "15+ Tahun",
                status: "🟢 Tersedia",
                description: "Ahli dalam kesehatan ternak dan hewan produktif dengan pengalaman luas di bidang nutrisi dan pencegahan penyakit."
            },
            {
                name: "Dr. Maya Putri",
                specialty: "Spesialis Bedah",
                rating: "5.0",
                experience: "8+ Tahun",
                status: "🟢 Tersedia",
                description: "Dokter bedah hewan dengan keahlian khusus dalam operasi ortopedi dan bedah darurat."
            }
        ];

        function updateDoctorInfo(index) {
            const data = doctorData[index];
            doctorInfo.classList.add('fade-out');

            setTimeout(() => {
                document.getElementById('doctor-name').textContent = data.name;
                document.getElementById('doctor-specialty').textContent = data.specialty;
                document.getElementById('doctor-rating').textContent = data.rating;
                document.getElementById('doctor-experience').textContent = data.experience;
                document.getElementById('doctor-status').textContent = data.status;
                document.getElementById('doctor-description').textContent = data.description;

                doctorInfo.classList.remove('fade-out');
                doctorInfo.classList.add('fade-in');
            }, 250);
        }

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active', 'prev');
                if (i === index) {
                    slide.classList.add('active');
                } else if (i === (index - 1 + slides.length) % slides.length) {
                    slide.classList.add('prev');
                }
            });
            updateDoctorInfo(index);
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        // Start slideshow
        showSlide(currentSlide);
        setInterval(nextSlide, 2500); // Change slide every 5 seconds
    });
</script>