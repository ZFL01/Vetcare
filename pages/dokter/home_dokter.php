<?php
$pageTitle = "Dashboard - VetCare";
include 'header-dokter.php'; // Header tetap di-include

$origin = true;
if (isset($_GET['tab'])) {
    switch ($_GET['tab']) {
        case 'consultations':
            include_once 'home-konsultasi.php';
            break;
        case 'analytics':
            include_once 'home-analitik.php';
            break;
        case 'overview':
            break;
    }
    exit();
}

// --- LOGIKA DATA (PHP) ---
$doctorId = $_SESSION['dokter']->getId();
$doctorInfo = [
    'name' => $_SESSION['dokter']->getNama(),
    'rating' => $_SESSION['dokter']->getRate() ?? 4.9,
    'totalPatients' => 0
];

require_once __DIR__ . '/../../chat-api-service/dao_chat.php';

$allChats = [];
$consultations = [];
$rating = 0;
$WeekTr = 0;
$idChats = [];

$chats = DAO_chat::getAllChats(idDokter: $doctorId, now: true);
if (!empty($chats)) {
    $allChats = $chats[0];
    $idChats = $chats[1];
    $hasil = DAO_MongoDB_Chat::getConsultationForm($idChats) ?? [];

    foreach ($hasil as $item) {
        $formData = $item['data'];
        $consultations[$item['idChat']] = [
            'nama_hewan' => $formData['nama_hewan'],
            'jenis_hewan' => $formData['jenis_hewan'],
            'usia_hewan' => $formData['usia_hewan'],
            'keluhan' => $formData['keluhan_gejala'],
        ];
    }
}
$countWeekday = count($idChats);

$totalTransaksi = DAO_chat::getRating($doctorId);
if (!empty($totalTransaksi)) {
    if ($totalTransaksi['total'] > 0) {
        $WeekTr = $totalTransaksi['total'];
        $rating = round($totalTransaksi['total'] / $totalTransaksi['suka'], 2);
    } else {
        $WeekTr = 0;
        $rating = 0;
    }
}

$stats = [
    'todayConsultations' => count($consultations), // Termasuk dummy
    'totalPatients' => $doctorInfo['totalPatients'] + 1, // +1 dummy
    'avgRating' => $doctorInfo['rating'],
    'revenue' => 'Rp ' . number_format(count($consultations) * 75000, 0, ',', '.')
];

// Dummy Data Chart

$origin = true;
if (isset($_GET['tab'])) {
    switch ($_GET['tab']) {
        case 'consultations':
            include_once 'home-konsultasi.php';
            break;
        case 'analytics':
            include_once 'home-analitik.php';
            break;
        case 'overview':
            break;
    }
    exit();
}


?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Styling Navigasi Kapsul (Pill) - FIX: display inline-flex */
    .pill-container {
        display: inline-flex;
        background-color: #F3F4F6;
        padding: 0.375rem;
        border-radius: 9999px;
        white-space: nowrap;
        /* Mencegah turun ke bawah */
    }

    .pill-item {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        color: #6B7280;
        background-color: transparent;
        border: none;
    }

    .pill-item.active {
        background-color: white;
        color: #111827;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        font-weight: 700;
    }

    .pill-item:hover:not(.active) {
        color: #374151;
    }

    /* Scrollbar Halus */
    .custom-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="min-h-screen bg-[#F9FAFB] pt-24 font-sans">

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

        <div class="mb-8">
            <div class="pill-container">
                <button class="pill-item active" onclick="switchTab('overview')"
                    data-tab="tab-overview">Overview</button>
                <button class="pill-item" onclick="switchTab('consultations')"
                    data-tab="tab-consultations">Konsultasi</button>
                <button class="pill-item" onclick="switchTab('analytics')" data-tab="tab-analytics">Analitik</button>
            </div>
        </div>

        <div id="content-overview" class="tab-content fade-in">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-[#2563EB] rounded-2xl p-6 text-white shadow-md relative overflow-hidden h-32 flex flex-col justify-between group hover:scale-[1.01] transition-transform">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1 opacity-90">Konsultasi Minggu Ini</p>
                        <h3 class="text-4xl font-bold"><?php echo $countWeekday; ?></h3>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-comment-alt text-xl"></i>
                    </div>
                </div>
                <div
                    class="bg-[#00C46F] rounded-2xl p-6 text-white shadow-md relative overflow-hidden h-32 flex flex-col justify-between hover:scale-[1.01] transition-transform">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1 opacity-90">Total Transaksi Selesai</p>
                        <h3 class="text-4xl font-bold"><?php echo $WeekTr; ?></h3>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-[#D946EF] to-[#EC4899] rounded-2xl p-6 text-white shadow-md relative overflow-hidden h-32 flex flex-col justify-between hover:scale-[1.01] transition-transform">
                    <div>
                        <p class="text-purple-100 text-sm font-medium mb-1 opacity-90">Rating Rata-rata</p>
                        <div class="flex items-center">
                            <h3 class="text-4xl font-bold"><?php echo $rating; ?></h3>
                            <i class="fas fa-star text-white ml-2 text-lg"></i>
                        </div>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                Konsultasi Aktif
                            </h2>
                            <button onclick="switchTab('consultations')"
                                class="px-4 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                Lihat Semua
                            </button>
                        </div>

                        <div class="p-6 h-[500px] overflow-y-auto custom-scroll bg-[#F9FAFB]">
                            <?php if (empty($consultations)): ?>
                                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                        <i class="fas fa-inbox text-2xl opacity-40"></i>
                                    </div>
                                    <p class="text-sm">Tidak ada konsultasi aktif.</p>
                                </div>
                            <?php else: ?>
                                <div class="space-y-4">
                                    <?php foreach ($allChats as $chat): ?>
                                        <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-md transition-all cursor-pointer group"
                                            onclick="startChat('<?php $idChat = $chat->getIdChat();
                                            echo $idChat; ?>')">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-start gap-4 flex-1">

                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center flex-wrap gap-2 mb-1">
                                                            <h4 class="font-bold text-gray-900 text-base">
                                                                <?php echo htmlspecialchars($chat->getEmail()); ?>
                                                            </h4>
                                                            <span
                                                                class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-md border border-gray-200">
                                                                <?php echo htmlspecialchars($consultations[$idChat]['jenis_hewan']); ?>
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-gray-500 mb-2">
                                                            Hewan: <span
                                                                class="font-medium text-gray-700"><?php echo htmlspecialchars($consultations[$idChat]['nama_hewan']); ?></span>
                                                        </p>
                                                        <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                                            <?php echo htmlspecialchars($consultations[$idChat]['keluhan']); ?>
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-3 mt-4 text-xs text-gray-500 font-medium">
                                                            <span class="flex items-center">
                                                                <i class="far fa-clock mr-1.5"></i>
                                                                <?php echo $chat->getWaktuMulai(); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex flex-col items-end justify-center">
                                                <button
                                                    class="bg-[#00A99D] hover:bg-teal-700 text-white font-medium rounded-lg px-6 py-2 transition flex items-center text-sm shadow-sm">
                                                    <i class="fas fa-comment-dots mr-2"></i> Chat
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>

<div id="tabContent" class="tab-content fade-in">
</div>

</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function switchTab(tabName) {
        const tabContent = document.getElementById('tabContent');
        const contentOverview = document.getElementById('content-overview');

        tabContent.innerHTML = `
            < div id = "content-overview" class="tab-content fade-in" >
            </div >
        `;
        if (tabName === 'overview') {
            contentOverview.classList.remove('hidden');
        } else {
            contentOverview.classList.add('hidden');
        }

        fetch(`?tab=${tabName}`)
            .then(response => response.text())
            .then(html => {
                tabContent.innerHTML = html;
                if (typeof initCharts === 'function') {
                    initCharts();
                }
            })
            .catch(error => {
                tabContent.innerHTML = `<div class="text-center p-8 text-red-600">❌ Error: ${error.message}</div>`;
                console.error(error);
            });
    }

    function startChat(chatId) {
        window.location.href = 'dokter-chat.php?chat_id=' + chatId;
    }
</script>