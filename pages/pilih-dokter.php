<?php
require_once __DIR__ . '/../header.php';
?>

<main class="pb-20 bg-gradient-to-b from-white via-purple-50 to-white min-h-[80vh]">
  <div class="container mx-auto px-6 max-w-6xl">

  <?php
  // get category slug
  $kategori = isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : '';

  if ($kategori === '') {
    echo '<div class="py-12">';
    echo '<p class="text-center text-gray-600">Kategori tidak dipilih. <a href="?route=pilih-kategori" class="text-purple-600 underline">Kembali ke pilihan kategori</a></p>';
    echo '</div>';
  } else {
    // generate many sample doctors so the page scrolls
    $doctors = [];
    for ($i = 1; $i <= 24; $i++) {
      $doctors[] = [
        'name' => "drh. Teguh Januar Rifaldi, Amd,S.Kom,M,MT #$i",
        'specialty' => 'Spesialis Hewan Eksotis',
        'rating' => '4.9',
        'reviews' => rand(50, 2000),
        'age' => rand(25, 65) . ' tahun',
        'desc' => 'Berpengalaman mengobati berbagai kasus pada hewan dan memberikan perawatan terbaik untuk pasien Anda.',
        'schedule' => '07:30 - 16:00',
        'days' => 'Senin, Selasa, Kamis, Jum\'at',
        'address' => 'Jl. Maju Mundur No. 34, Alexandria',
        'city' => 'Ibukota Mars',
        'price' => 'Rp ' . number_format(999999999,0,',','.'),
        'avatar' => "https://i.pravatar.cc/120?img=" . ($i % 70 + 1)
      ];
    }

    echo '<div class="mb-6">';
    echo '<a href="?route=pilih-kategori" class="text-sm text-purple-700 hover:underline">&larr; Kembali ke kategori</a>';
    echo '</div>';

    echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-8">';
    foreach ($doctors as $doc) {
      ?>
      <div class="relative rounded-2xl overflow-hidden bg-white shadow-card p-6 border border-purple-200/50">
        <div class="flex gap-4">
          <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0">
            <img src="<?php echo $doc['avatar']; ?>" alt="avatar" class="w-full h-full object-cover" />
          </div>
          <div class="flex-1">
            <h4 class="font-semibold text-gray-800"><?php echo $doc['name']; ?></h4>
            <div class="text-sm text-gray-500 mb-2"><?php echo $doc['specialty']; ?></div>
            <div class="flex items-center text-sm text-gray-600 gap-3 mb-2">
              <span class="text-yellow-400">★</span>
              <span><?php echo $doc['rating']; ?> (<?php echo $doc['reviews']; ?>)</span>
              <span class="text-gray-400">•</span>
              <span><?php echo $doc['age']; ?></span>
            </div>
            <p class="text-sm text-gray-600 mb-3"><?php echo $doc['desc']; ?></p>

            <div class="text-sm text-gray-500 mb-4">
              <div class="flex items-center gap-2"><span class="text-xs">⏰</span> Jadwal Hari Ini: <?php echo $doc['schedule']; ?> <span class="text-gray-400">•</span> <?php echo $doc['days']; ?></div>
              <div class="flex items-center gap-2 mt-2"><span class="text-xs">📍</span> <?php echo $doc['address']; ?> <div class="text-xs text-gray-400"><?php echo $doc['city']; ?></div></div>
            </div>

            <div class="flex items-center justify-between">
              <div class="text-blue-600 font-semibold"><?php echo $doc['price']; ?></div>
              <button class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-purple-700 text-white px-4 py-2 rounded-full text-sm shadow-lg hover:scale-[1.02] transition-transform">
                <span class="w-5 h-5 flex items-center justify-center bg-white/10 rounded-full">💬</span>
                Chat Sekarang
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    echo '</div>';
  }
  ?>

  </div>
</main>

<style>
  .shadow-card { box-shadow: 0 10px 30px rgba(150, 100, 200, 0.08); }
</style>
