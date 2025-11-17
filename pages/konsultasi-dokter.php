<?php
// Konsultasi Dokter page converted from React KonsultasiDokter.tsx
$articles = [
    [
        'title' => 'Tips Menjaga Kesehatan Anjing',
        'author' => 'Dr. Sarah Wijaya',
        'date' => '15 September 2025',
        'rating' => 4.8,
    ],
    [
        'title' => 'Panduan Vaksinasi Kucing',
        'author' => 'Dr. Michael Chen',
        'date' => '12 September 2025',
        'rating' => 4.9,
    ],
    [
        'title' => 'Nutrisi untuk Hewan Peliharaan',
        'author' => 'Dr. Lisa Rahman',
        'date' => '10 September 2025',
        'rating' => 4.7,
    ],
];
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
                    Konsultasi Dokter
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Artikel dan panduan kesehatan hewan dari dokter berpengalaman
                </p>
            </div>

            <div class="grid gap-6 mb-12">
                <?php foreach ($articles as $article): ?>
                <div class="bg-white rounded-lg shadow-card hover:shadow-lg transition-all duration-300 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">
                                oleh <?php echo htmlspecialchars($article['author']); ?>
                            </p>
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo htmlspecialchars($article['date']); ?>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 fill-yellow-400 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                    <?php echo htmlspecialchars($article['rating']); ?>
                                </div>
                            </div>
                        </div>
                        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">Baca</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg p-8 text-center">
                <h3 class="text-2xl font-display font-bold mb-4">
                    Butuh Konsultasi Langsung?
                </h3>
                <p class="mb-6 opacity-90">
                    Hubungi dokter hewan kami untuk konsultasi video call
                </p>
                <a href="?route=tanya-dokter" class="inline-block bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Mulai Konsultasi
                </a>
            </div>
        </div>
    </div>
</div>
