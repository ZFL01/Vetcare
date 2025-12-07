<?php
require_once __DIR__ . '/../../includes/DAO_others.php';

// Dummy Data Chart
$weeklyData = [];
$daysOfWeek = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
for ($i = 0; $i < 7; $i++)
    $weeklyData[] = ['name' => $daysOfWeek[$i], 'konsultasi' => rand(5, 25)];

$monthlyData = [];
$monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
for ($i = 0; $i < 12; $i++)
    $monthlyData[] = ['name' => $monthNames[$i], 'konsultasi' => 0];

// Get Real Revenue
$totalRevenue = 0;
$totalKonsultasi = 0;
$pasienKembali = 0;
$totalPasien = 0;
$persentaseKembali = 0;

if (isset($doctorId)) {
    $totalRevenue = ringkasanTransaksiDoker::getTotalPendapatan($doctorId);
    $totalKonsultasi = ringkasanTransaksiDoker::getTotalKonsultasi($doctorId);
    $pasienKembali = ringkasanTransaksiDoker::getPasienKembali($doctorId);
    $totalPasien = ringkasanTransaksiDoker::getTotalPasien($doctorId);

    if ($totalPasien > 0) {
        $persentaseKembali = round(($pasienKembali / $totalPasien) * 100);
    }

    // Weekly Data Integration
    $rawWeekly = ringkasanTransaksiDoker::getStatistikMingguan($doctorId);
    foreach ($weeklyData as $index => &$day) { // $weeklyData already has 0-6 index from definition at top
        // $index 0 is Monday, which matches WEEKWEEK(date, 1) - 1? No, WEEKDAY returns 0 for Monday.
        // So index matches directly.
        $day['konsultasi'] = $rawWeekly[$index] ?? 0;
    }

    // Monthly Data Integration
    $currentYear = date('Y');
    $rawMonthly = ringkasanTransaksiDoker::getStatistikBulanan($doctorId, $currentYear);
    foreach ($monthlyData as $index => &$month) {
        // $index is 0-11, months are 1-12
        $monthNum = $index + 1;
        $month['konsultasi'] = $rawMonthly[$monthNum] ?? 0;
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data from PHP
    var weeklyData = <?php echo json_encode($weeklyData); ?>;
    var monthlyData = <?php echo json_encode($monthlyData); ?>;

    function runInit() {
        if (typeof initCharts === 'function') {
            initCharts(weeklyData, monthlyData);
        }
    }

    // Auto init if loaded directly or satisfy the caller
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(runInit, 100);
    } else {
        document.addEventListener('DOMContentLoaded', runInit);
    }

    // Also expose for manual calling from home_dokter.php
    window.runDoctorCharts = runInit;
</script>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-teal-400 to-cyan-600 rounded-2xl p-6 text-white shadow-md text-center">
        <p class="text-sm font-medium opacity-90 mb-2">Total Konsultasi</p>
        <h3 class="text-4xl font-bold mb-1"><?php echo $totalKonsultasi; ?></h3>
        <p class="text-xs opacity-75">Sejak Bergabung</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-md text-center">
        <p class="text-sm font-medium opacity-90 mb-2">Pasien Kembali</p>
        <h3 class="text-4xl font-bold mb-1"><?php echo $pasienKembali; ?></h3>
        <p class="text-xs opacity-75"><?php echo $persentaseKembali; ?>% dari total pasien</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 text-white shadow-md text-center">
        <p class="text-sm font-medium opacity-90 mb-2">Total Pendapatan</p>
        <h3 class="text-3xl font-bold mb-1">Rp <?php echo number_format($totalRevenue, 0, ',', '.'); ?></h3>
        <p class="text-xs opacity-75">Sejak Bergabung</p>
    </div>
</div>

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