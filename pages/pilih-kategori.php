<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../includes/DAO_dokter.php';

$categories = DAO_kategori::getAllKategori();
?>

<main class="pb-20 bg-gradient-to-b from-white via-purple-50 to-white min-h-[80vh]">
  <div class="container mx-auto px-6 max-w-6xl">
    <div class="flex items-center gap-4 mb-4">
    </div>
<?php
if (empty($categories)) {
    echo '<div class="text-center py-20"><h1 class="text-2xl font-semibold text-gray-600">Belum ada kategori dokter tersedia.</h1></div>';
} else {
    echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">';
    
    foreach ($categories as $catObj): 
        if (!($catObj instanceof DTO_kateg)) continue;
        $title = $catObj->getNamaKateg();
        $img = $catObj->getFotoKateg();
        
        // URL HANYA MENGGUNAKAN NAMA KATEGORI
        ?>
  <a href="?route=pilih-dokter&kategori=<?php echo urlencode($title); ?>" class="group block" style="height:449px;">
        <div class="relative rounded-2xl overflow-hidden bg-white shadow-card flex flex-col h-full border border-purple-200/50 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-[#8026D9]/25 group-hover:border-[#8026D9]">
          <div class="relative overflow-hidden" style="height:236px;max-height:236px;">
            <img loading="lazy" src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($title); ?>" style="width:100%;height:100%;object-fit:cover; will-change: transform;" class="transform transition-transform duration-300 ease-out group-hover:scale-110" />
          </div>
          <div class="p-6 flex-1 flex flex-col" style="min-height:213px;">
            <h3 class="text-lg font-semibold text-purple-700 mb-1"><?php echo htmlspecialchars($title); ?></h3>
            <div class="mt-auto pt-2">
              <button class="w-full text-purple-600 border border-purple-200 rounded-md py-2 font-medium transition-all duration-300 group-hover:bg-gradient-to-r group-hover:from-[#A855F7] group-hover:to-[#9333EA] group-hover:text-white group-hover:border-transparent">Pilih Kategori</button>
            </div>
          </div>
        </div>
      </a>
    <?php endforeach;
    echo '</div>';
} ?>
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