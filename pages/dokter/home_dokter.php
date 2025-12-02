<?php
$pageTitle = "Home - VetCare";
include 'header-dokter.php';
?>

<main class="flex-grow">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary to-secondary text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Selamat Datang di VetCare
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
                Platform kesehatan hewan terpercaya untuk dokter hewan profesional
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo BASE_URL; ?>pages/dashboard-dokter.php" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    🏥 Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>pages/dokter-cha.php" class="bg-accent text-white px-8 py-3 rounded-lg font-semibold hover:bg-purple-400 transition-colors">
                    💬 Jawab Pertanyaan
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Fitur Utama</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl text-center hover:shadow-lg transition-shadow">
                    <div class="text-4xl mb-4">📋</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Dashboard</h3>
                    <p class="text-gray-600">Pantau statistik dan aktivitas Anda</p>
                    <a href="<?php echo BASE_URL; ?>pages/dashboard-dokter.php" class="text-primary hover:text-secondary mt-4 inline-block">Lihat →</a>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl text-center hover:shadow-lg transition-shadow">
                    <div class="text-4xl mb-4">💬</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Jawab Pertanyaan</h3>
                    <p class="text-gray-600">Bantu pasien dengan konsultasi</p>
                    <a href="<?php echo BASE_URL; ?>pages/tanya-dokter.php" class="text-primary hover:text-secondary mt-4 inline-block">Mulai →</a>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl text-center hover:shadow-lg transition-shadow">
                    <div class="text-4xl mb-4">📝</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Kelola Artikel</h3>
                    <p class="text-gray-600">Bagikan pengetahuan kesehatan</p>
                    <a href="<?php echo BASE_URL; ?>pages/kelola-artikel.php" class="text-primary hover:text-secondary mt-4 inline-block">Kelola →</a>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl text-center hover:shadow-lg transition-shadow">
                    <div class="text-4xl mb-4">💭</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Bertanya Pasien</h3>
                    <p class="text-gray-600">Komunikasi langsung dengan pasien</p>
                    <a href="<?php echo BASE_URL; ?>pages/chat-pasien.php" class="text-primary hover:text-secondary mt-4 inline-block">Bertanya →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Statistik Platform</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl text-center shadow-sm">
                    <div class="text-4xl mb-4 text-primary">👥</div>
                    <div class="text-3xl font-bold text-gray-800 mb-2">500+</div>
                    <div class="text-gray-600">Dokter Terdaftar</div>
                </div>
                <div class="bg-white p-8 rounded-xl text-center shadow-sm">
                    <div class="text-4xl mb-4 text-secondary">📋</div>
                    <div class="text-3xl font-bold text-gray-800 mb-2">2,500+</div>
                    <div class="text-gray-600">Pertanyaan Dijawab</div>
                </div>
                <div class="bg-white p-8 rounded-xl text-center shadow-sm">
                    <div class="text-4xl mb-4 text-accent">📖</div>
                    <div class="text-3xl font-bold text-gray-800 mb-2">150+</div>
                    <div class="text-gray-600">Artikel Kesehatan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-primary text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">Siap Membantu Hewan Hari Ini?</h2>
            <p class="text-xl mb-8 opacity-90">
                Bergabunglah dengan komunitas dokter hewan profesional di VetCare
            </p>
            <a href="<?php echo BASE_URL; ?>pages/dashboard-dokter.php" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
                Mulai Sekarang →
            </a>
        </div>
    </section>
</main>

<?php include 'footer-dokter.php'; ?>
