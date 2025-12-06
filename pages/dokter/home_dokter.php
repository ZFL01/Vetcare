<?php
$pageTitle = "Dashboard - VetCare";
include 'header-dokter.php'; // Header tetap di-include

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

$chats = DAO_chat::getAllChats(idDokter: $doctorId);
if (!empty($chats)) {
    $allChats = $chats[0];
    $idChats = $allChats[1];
    $consultation = DAO_MongoDB_Chat::getConsultationForm($idChats);
}

$stats = [
    'todayConsultations' => count($consultations),
    'totalPatients' => $doctorInfo['totalPatients'], // Hitung total dari real + dummy
    'avgRating' => $doctorInfo['rating'],
    'revenue' => 'Rp ' . number_format(count($consultations) * 75000, 0, ',', '.')
];

// Dummy Data Chart
$weeklyData = [];
$daysOfWeek = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
for ($i = 0; $i < 7; $i++)
    $weeklyData[] = ['name' => $daysOfWeek[$i], 'konsultasi' => rand(5, 25)];

$monthlyData = [];
$monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
for ($i = 0; $i < 6; $i++)
    $monthlyData[] = ['name' => $monthNames[$i], 'konsultasi' => rand(100, 250)];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Styling Navigasi Kapsul (Pill) */
    .pill-container {
        display: inline-flex;
        background-color: #F3F4F6;
        /* Gray-100 */
        padding: 0.375rem;
        /* p-1.5 */
        border-radius: 9999px;
        /* rounded-full */
    }

    .pill-item {
        padding: 0.5rem 1.5rem;
        /* py-2 px-6 */
        border-radius: 9999px;
        /* rounded-full */
        font-size: 0.875rem;
        /* text-sm */
        font-weight: 500;
        /* font-medium */
        cursor: pointer;
        transition: all 0.2s;
        color: #6B7280;
        /* text-gray-500 */
        background-color: transparent;
        border: none;
    }

    .pill-item.active {
        background-color: white;
        color: #111827;
        /* text-gray-900 */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        font-weight: 700;
    }

    .pill-item:hover:not(.active) {
        color: #374151;
        /* text-gray-700 */
    }

    /* Animasi Fade */
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

    /* Scrollbar */
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
</style>

<div class="min-h-screen bg-[#F9FAFB] pt-24 font-sans">

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

        <div class="mb-8">
            <div class="pill-container">
                <button class="pill-item active" onclick="switchTab('overview')" id="tab-overview">Overview</button>
                <button class="pill-item" onclick="switchTab('consultations')"
                    id="tab-consultations">Konsultasi</button>
                <button class="pill-item" onclick="switchTab('analytics')" id="tab-analytics">Analitik</button>
            </div>
        </div>

        <div id="content-overview" class="tab-content fade-in">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-[#2563EB] rounded-2xl p-6 text-white shadow-md relative overflow-hidden h-32 flex flex-col justify-between group hover:scale-[1.01] transition-transform">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1 opacity-90">Konsultasi Hari Ini</p>
                        <h3 class="text-4xl font-bold"><?php echo $stats['todayConsultations']; ?></h3>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-comment-alt text-xl"></i>
                    </div>
                </div>

                <div
                    class="bg-[#00C46F] rounded-2xl p-6 text-white shadow-md relative overflow-hidden h-32 flex flex-col justify-between hover:scale-[1.01] transition-transform">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1 opacity-90">Total Pasien</p>
                        <h3 class="text-4xl font-bold"><?php echo $stats['totalPatients']; ?></h3>
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
                            <h3 class="text-4xl font-bold"><?php echo $stats['avgRating']; ?></h3>
                            <i class="fas fa-star text-white ml-2 text-lg"></i>
                        </div>
                    </div>
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
                                    <?php foreach ($consultations as $consultation): ?>
                                        <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-md transition-all cursor-pointer group"
                                            onclick="startChat('<?php echo $consultation['id']; ?>')">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-start gap-4 flex-1">
                                                    <div class="relative shrink-0">
                                                        <img src="<?php echo $consultation['avatar']; ?>"
                                                            class="w-14 h-14 rounded-full object-cover border border-gray-200">
                                                    </div>

                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center flex-wrap gap-2 mb-1">
                                                            <h4 class="font-bold text-gray-900 text-base">
                                                                <?php echo htmlspecialchars($consultation['patientName']); ?>
                                                            </h4>
                                                            <span
                                                                class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-md border border-gray-200">
                                                                <?php echo htmlspecialchars($consultation['petType']); ?>
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-500 mb-2">
                                                            Hewan: <span
                                                                class="font-medium text-gray-700"><?php echo htmlspecialchars($consultation['petName']); ?></span>
                                                        </p>

                                                        <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                                            <?php echo htmlspecialchars($consultation['complaint']); ?>
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-3 mt-4 text-xs text-gray-500 font-medium">
                                                            <span class="flex items-center">
                                                                <i class="far fa-clock mr-1.5"></i>
                                                                <?php echo $consultation['time']; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="ml-4 flex flex-col items-end">
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

                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-teal-700 font-bold mb-4 text-xs uppercase tracking-wider">Statistik Minggu Ini
                        </h3>
                        <div class="space-y-3">
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
                                <span class="text-sm text-teal-800 font-medium">Pendapatan</span>
                                <span class="font-bold text-teal-700"><?php echo $stats['revenue']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="content-consultations" class="tab-content hidden fade-in">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Semua Riwayat Konsultasi</h2>
                <?php if (empty($consultations)): ?>
                    <p class="text-gray-500 text-center py-8">Belum ada data riwayat.</p>
                <?php else: ?>
                    <div class="grid gap-3">
                        <?php foreach ($consultations as $consultation): ?>
                            <div class="flex items-center justify-between p-4 bg-white hover:bg-gray-50 rounded-xl border border-gray-100 transition-colors cursor-pointer"
                                onclick="startChat('<?php echo $consultation['id']; ?>')">
                                <div class="flex items-center gap-4">
                                    <img src="<?php echo $consultation['avatar']; ?>"
                                        class="w-10 h-10 rounded-full bg-gray-100 object-cover">
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">
                                            <?php echo htmlspecialchars($consultation['patientName']); ?>
                                        </p>
                                        <p class="text-xs text-gray-500"><?php echo $consultation['fullDate']; ?> •
                                            <?php echo htmlspecialchars($consultation['petName']); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Selesai</span>
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="content-analytics" class="tab-content hidden fade-in">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-gray-700 font-bold mb-6 flex items-center">
                        <i class="fas fa-chart-bar text-teal-500 mr-2"></i> Grafik Mingguan
                    </h3>
                    <div class="h-72"><canvas id="weeklyChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-gray-700 font-bold mb-6 flex items-center">
                        <i class="fas fa-chart-area text-purple-500 mr-2"></i> Grafik Bulanan
                    </h3>
                    <div class="h-72"><canvas id="monthlyChart"></canvas></div>
                </div>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Tab Logic
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('content-' + tabName).classList.remove('hidden');

        document.querySelectorAll('.pill-item').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');

        if (tabName === 'analytics') setTimeout(initCharts, 100);
    }

    // Chat Navigation
    function startChat(chatId) {
        window.location.href = '/?route=dokter-chat&chat_id=' + chatId;
    }

    // Chart.js Setup
    let weeklyChart, monthlyChart;
    function initCharts() {
        if (weeklyChart) weeklyChart.destroy();
        if (monthlyChart) monthlyChart.destroy();

        // Weekly Bar Chart
        const ctx1 = document.getElementById('weeklyChart');
        if (ctx1) {
            weeklyChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($weeklyData, 'name')); ?>,
                    datasets: [{
                        label: 'Konsultasi',
                        data: <?php echo json_encode(array_column($weeklyData, 'konsultasi')); ?>,
                        backgroundColor: '#14b8a6', // Teal
                        borderRadius: 6,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f3f4f6', borderDash: [5, 5] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Monthly Line Chart
        const ctx2 = document.getElementById('monthlyChart');
        if (ctx2) {
            monthlyChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_column($monthlyData, 'name')); ?>,
                    datasets: [{
                        label: 'Total',
                        data: <?php echo json_encode(array_column($monthlyData, 'konsultasi')); ?>,
                        borderColor: '#d946ef', // Fuchsia
                        backgroundColor: 'rgba(217, 70, 239, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#d946ef',
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f3f4f6', borderDash: [5, 5] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }
</script>