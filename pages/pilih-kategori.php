<?php
require_once __DIR__ . '/../header.php';
?>

<main class="pb-20 bg-gradient-to-b from-white via-purple-50 to-white min-h-[80vh]">
  <div class="container mx-auto px-6 max-w-6xl">
    <div class="flex items-center gap-4 mb-4">
      </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      <?php
      $categories = [
        [
          'title' => 'Hewan Peliharaan',
          'desc' => 'Kucing, anjing, dan hewan peliharaan rumahan',
          'img' => 'https://images.unsplash.com/photo-1517423440428-a5a00ad493e8?auto=format&fit=crop&w=1400&q=80',
          'slug' => 'peliharaan'
        ],
        [
          'title' => 'Hewan Ternak',
          'desc' => 'Sapi, kambing, domba, dan hewan ternak lainnya',
          'img' => 'https://images.unsplash.com/photo-1504208434309-cb69f4fe52b0?auto=format&fit=crop&w=1400&q=80',
          'slug' => 'ternak'
        ],
        [
          'title' => 'Hewan Eksotis',
          'desc' => 'Reptil, burung eksotis, dan hewan langka lainnya',
          'img' => 'https://images.unsplash.com/photo-1513451713350-dee890297c4a?auto=format&fit=crop&w=1400&q=80',
          'slug' => 'eksotis'
        ],
        [
          'title' => 'Hewan Akuatik',
          'desc' => 'Ikan hias, ikan hasil tambak, dan hewan air lainnya',
          'img' => 'https://images.unsplash.com/photo-1501706362039-c6e8094d57a0?auto=format&fit=crop&w=1400&q=80',
          'slug' => 'akuatik'
        ],
        [
          'title' => 'Hewan Kecil',
          'desc' => 'Kelinci, hamster, marmut, dan hewan kecil lainnya',
          'img' => 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?auto=format&fit=crop&w=1400&q=80',
          'slug' => 'kecil'
        ],
        [
          'title' => 'Hewan Unggas',
          'desc' => 'Ayam, bebek, burung puyuh, dan hewan unggas lainnya',
          'img' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit-crop&w=1400&q=80',
          
          // ### KESALAHANNYA DI SINI TADI ###
          'slug' => 'unggas' 
        ],
      ];

      foreach ($categories as $cat): ?>
        <a href="?route=konsultasi-dokter&kategori=<?php echo urlencode($cat['slug']); ?>" class="group block" style="height:449px;">
          
          <div class="relative rounded-2xl overflow-hidden bg-white shadow-card flex flex-col h-full border border-purple-200/50 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-[#8026D9]/25 group-hover:border-[#8026D9]">
            
            <div class="relative overflow-hidden" style="height:236px;max-height:236px;">
              <img loading="lazy" src="<?php echo $cat['img']; ?>" alt="<?php echo htmlspecialchars($cat['title']); ?>" style="width:100%;height:100%;object-fit:cover; will-change: transform;" class="transform transition-transform duration-300 ease-out group-hover:scale-110" />
            </div>

            <div class="p-6 flex-1 flex flex-col" style="min-height:213px;">
              <h3 class="text-lg font-semibold text-purple-700 mb-1"><?php echo $cat['title']; ?></h3>
              <p class="text-sm text-gray-500 mb-4"><?php echo $cat['desc']; ?></p>

              <div class="mt-auto pt-2">
                <button class="w-full text-purple-600 border border-purple-200 rounded-md py-2 font-medium transition-all duration-300 group-hover:bg-gradient-to-r group-hover:from-[#A855F7] group-hover:to-[#9333EA] group-hover:text-white group-hover:border-transparent">Pilih Kategori</button>
              </div>
            </div>

          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</main>

<style>
  /* small fallback styles for the purple shadow/glow used in header */
  .shadow-card { box-shadow: 0 10px 30px rgba(150, 100, 200, 0.08); }
  @media (min-width: 768px) {
    /* Kita tidak perlu ini lagi karena shadow di-handle Tailwind */
    /* .group:hover .shadow-card { box-shadow: 0 18px 40px rgba(140, 75, 180, 0.12); } */
  }
</style>