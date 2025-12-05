<?php
$pageTitle = "Dashboard - VetCare";
include 'header-dokter.php';

// --- DATA LOGIC (PHP) ---
$doctorId = $_SESSION['dokter']->getId();
$doctorInfo = [
    'name' => $_SESSION['dokter']->getNama(),
    'specialty' => 'Spesialis Hewan Kecil & Eksotis',
    'avatar' => $_SESSION['dokter']->getFoto() ? FOTO_DI_DOKTER . $_SESSION['dokter']->getFoto() : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['dokter']->getNama()) . '&background=random',
    'rating' => $_SESSION['dokter']->getRate() ?? 4.9,
    'totalPatients' => 0
];

require_once __DIR__ . '/../../chat-api-service/dao_chat.php';
$allChats = DAO_chat::getAllChats(idDokter: $doctorId);
$consultations = [];

try {
    $mongoClient = new MongoDB\Client(MONGODB_URI);
    $db = $mongoClient->selectDatabase(MONGODB_DBNAME);
    $formsCollection = $db->selectCollection('Konsultasi_forms');
    $chatsCollection = $db->selectCollection('chats');

    foreach ($allChats as $chat) {
        $chatId = $chat->getIdChat();
        $formDoc = $formsCollection->findOne(['chatId' => $chatId]);

        if ($formDoc) {
            $formData = $formDoc['formData'];
            $waktuMulai = $chat->getWaktuMulai();
            $chatDoc = $chatsCollection->findOne(['_id' => $chatId]);
            $hasNewMessages = false;
            $unreadCount = 0;

            if ($chatDoc && isset($chatDoc['messages']) && count($chatDoc['messages']) > 0) {
                foreach ($chatDoc['messages'] as $msg) {
                    if ($msg['senderRole'] === 'user') {
                        $unreadCount++;
                        $hasNewMessages = true;
                    }
                }
            }

            $status = 'active';
            if ($unreadCount > 2)
                $status = 'waiting';

            $consultations[] = [
                'id' => $chatId,
                'patientName' => $chat->getEmail(),
                'petName' => $formData['nama_hewan'] ?? 'Unknown',
                'petType' => ucfirst($formData['jenis_hewan'] ?? 'Unknown'),
                'complaint' => $formData['keluhan_gejala'] ?? 'Tidak ada keluhan spesifik',
                'time' => date('H:i', strtotime($waktuMulai)),
                'fullDate' => date('d M Y', strtotime($waktuMulai)),
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($formData['nama_hewan'] ?? 'Pet') . '&background=random',
                'status' => $status,
                'unreadMessages' => $unreadCount
            ];
        }
    }
    $doctorInfo['totalPatients'] = count($allChats);
} catch (Exception $e) {
    error_log('MongoDB Error: ' . $e->getMessage());
}

$stats = [
    'todayConsultations' => count($consultations),
    'waiting' => count(array_filter($consultations, fn($c) => $c['status'] == 'waiting')),
    'active' => count(array_filter($consultations, fn($c) => $c['status'] == 'active')),
    'totalPatients' => $doctorInfo['totalPatients'],
    'avgRating' => $doctorInfo['rating'],
    'revenue' => 'Rp 1.200.000'
];

// Data Chart Dummy
$weeklyData = [];
$daysOfWeek = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
for ($i = 0; $i < 7; $i++)
    $weeklyData[] = ['name' => $daysOfWeek[$i], 'konsultasi' => rand(5, 25)];

$monthlyData = [];
$monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
for ($i = 0; $i < 6; $i++)
    $monthlyData[] = ['name' => $monthNames[$i], 'konsultasi' => rand(100, 250)];

function getStatusBadgeClass($status)
{
    return match ($status) {
        'waiting' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'active' => 'bg-green-100 text-green-700 border-green-200',
        'completed' => 'bg-gray-100 text-gray-700 border-gray-200',
        default => 'bg-gray-100 text-gray-700'
    };
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .tabs-list {
        @apply inline-flex h-10 items-center justify-center rounded-lg bg-gray-100 p-1 text-gray-500;
    }

    .tab-trigger {
        @apply inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50;
        cursor: pointer;
    }

    .tab-trigger.active {
        @apply bg-white text-gray-950 shadow-sm;
    }

    .tab-trigger:not(.active):hover {
        @apply text-gray-900;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-teal-50 via-blue-50 to-cyan-50 font-sans">

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <div class="tabs-list grid w-full grid-cols-3 lg:w-[400px]">
                <div class="tab-trigger active" onclick="switchTab('overview')" id="tab-overview">Overview</div>
                <div class="tab-trigger" onclick="switchTab('consultations')" id="tab-consultations">Konsultasi</div>
                <div class="tab-trigger" onclick="switchTab('analytics')" id="tab-analytics">Analitik</div>
            </div>
        </div>

        <div id="content-overview" class="tab-content animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg shadow-blue-500/20 relative overflow-hidden group">
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Konsultasi Hari Ini</p>
                            <h3 class="text-3xl font-bold"><?php echo $stats['todayConsultations']; ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <i class="far fa-comments text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg shadow-green-500/20 relative overflow-hidden">
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Pasien</p>
                            <h3 class="text-3xl font-bold"><?php echo $stats['totalPatients']; ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-6 text-white shadow-lg shadow-purple-500/20 relative overflow-hidden">
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Rating Rata-rata</p>
                            <div class="flex items-center">
                                <h3 class="text-3xl font-bold"><?php echo $stats['avgRating']; ?></h3>
                                <i class="fas fa-star text-yellow-300 ml-2 text-lg"></i>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-800">Konsultasi Aktif</h2>
                            <button onclick="switchTab('consultations')"
                                class="text-sm font-medium text-teal-600 hover:text-teal-700">
                                Lihat Semua
                            </button>
                        </div>

                        <div class="p-6 h-[500px] overflow-y-auto custom-scroll">
                            <?php if (empty($consultations)): ?>
                                <div class="text-center py-10 text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3 opacity-30"></i>
                                    <p>Tidak ada konsultasi aktif saat ini.</p>
                                </div>
                            <?php else: ?>
                                <div class="space-y-4">
                                    <?php foreach ($consultations as $consultation): ?>
                                        <div
                                            class="bg-white border border-gray-100 rounded-xl p-4 hover:shadow-md transition-shadow duration-200">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-start space-x-4 flex-1">
                                                    <img src="<?php echo $consultation['avatar']; ?>"
                                                        class="w-12 h-12 rounded-full object-cover border border-gray-200">

                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center space-x-2 mb-1">
                                                            <h4 class="font-semibold text-gray-900 truncate">
                                                                <?php echo htmlspecialchars($consultation['patientName']); ?>
                                                            </h4>
                                                            <span
                                                                class="bg-gray-100 text-gray-600 text-[10px] font-medium px-2 py-0.5 rounded-full border border-gray-200">
                                                                <?php echo htmlspecialchars($consultation['petType']); ?>
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-600 mb-1">
                                                            <span class="font-medium text-gray-700">Hewan:</span>
                                                            <?php echo htmlspecialchars($consultation['petName']); ?>
                                                        </p>
                                                        <p class="text-sm text-gray-500 line-clamp-2 mb-3">
                                                            <?php echo htmlspecialchars($consultation['complaint']); ?>
                                                        </p>

                                                        <div class="flex items-center gap-3">
                                                            <div class="flex items-center text-xs text-gray-500">
                                                                <i class="far fa-clock mr-1.5"></i>
                                                                <?php echo $consultation['time']; ?>
                                                            </div>
                                                            <span
                                                                class="text-xs px-2.5 py-0.5 rounded-full font-medium border <?php echo getStatusBadgeClass($consultation['status']); ?>">
                                                                <?php echo ucfirst($consultation['status']); ?>
                                                            </span>
                                                            <?php if ($consultation['unreadMessages'] > 0): ?>
                                                                <span
                                                                    class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium border border-red-200">
                                                                    <?php echo $consultation['unreadMessages']; ?> pesan baru
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col space-y-2 ml-4">
                                                    <button onclick="startChat('<?php echo $consultation['id']; ?>')"
                                                        class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shadow-teal-200">
                                                        <i class="fas fa-comment-alt mr-2"></i> Chat
                                                    </button>
                                                    <button
                                                        class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                                        <i class="fas fa-video mr-2"></i> Video
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

                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-teal-700 font-bold mb-4">Statistik Minggu Ini</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Konsultasi Selesai</span>
                                <span class="font-bold text-gray-900">131</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Pasien Kembali</span>
                                <span class="font-bold text-gray-900">87</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-3 bg-teal-50 rounded-lg border border-teal-100">
                                <span class="text-sm text-teal-800">Pendapatan</span>
                                <span class="font-bold text-teal-900"><?php echo $stats['revenue']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div id="content-consultations" class="tab-content hidden animate-fade-in">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Semua Riwayat Konsultasi</h2>
                <?php if (empty($consultations)): ?>
                    <p class="text-gray-500 text-center">Belum ada data.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($consultations as $consultation): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-4">
                                    <img src="<?php echo $consultation['avatar']; ?>" class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="font-bold text-gray-900">
                                            <?php echo htmlspecialchars($consultation['patientName']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo $consultation['fullDate']; ?></p>
                                    </div>
                                </div>
                                <button onclick="startChat('<?php echo $consultation['id']; ?>')"
                                    class="text-sm text-teal-600 hover:underline">Lihat Detail</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="content-analytics" class="tab-content hidden animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-gray-700 font-bold mb-4">Grafik Mingguan</h3>
                    <div class="h-72"><canvas id="weeklyChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-gray-700 font-bold mb-4">Grafik Bulanan</h3>
                    <div class="h-72"><canvas id="monthlyChart"></canvas></div>
                </div>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('content-' + tabName).classList.remove('hidden');

        document.querySelectorAll('.tab-trigger').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');

        if (tabName === 'analytics') setTimeout(initCharts, 100);
    }

    function startChat(chatId) {
        window.location.href = '/?route=dokter-chat&chat_id=' + chatId;
    }

    let weeklyChart, monthlyChart;
    function initCharts() {
        if (weeklyChart) weeklyChart.destroy();
        if (monthlyChart) monthlyChart.destroy();

        const ctx1 = document.getElementById('weeklyChart');
        if (ctx1) {
            weeklyChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($weeklyData, 'name')); ?>,
                    datasets: [{
                        label: 'Konsultasi',
                        data: <?php echo json_encode(array_column($weeklyData, 'konsultasi')); ?>,
                        backgroundColor: '#14b8a6',
                        borderRadius: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } } }
            });
        }

        const ctx2 = document.getElementById('monthlyChart');
        if (ctx2) {
            monthlyChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_column($monthlyData, 'name')); ?>,
                    datasets: [{
                        label: 'Total',
                        data: <?php echo json_encode(array_column($monthlyData, 'konsultasi')); ?>,
                        borderColor: '#14b8a6',
                        backgroundColor: 'rgba(20, 184, 166, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } } }
            });
        }
    }
</script>