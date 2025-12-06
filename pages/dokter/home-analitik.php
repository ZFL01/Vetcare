<?php

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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function initCharts() {
        // Destroy existing charts if they exist to prevent memory leaks/duplicates
        const existingWeekly = Chart.getChart("weeklyChart");
        if (existingWeekly) existingWeekly.destroy();

        const existingMonthly = Chart.getChart("monthlyChart");
        if (existingMonthly) existingMonthly.destroy();

        // Weekly Bar Chart
        const ctx1 = document.getElementById('weeklyChart');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($weeklyData, 'name')); ?>,
                    datasets: [{
                        label: 'Konsultasi',
                        data: <?php echo json_encode(array_column($weeklyData, 'konsultasi')); ?>,
                        backgroundColor: '#14b8a6',
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
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_column($monthlyData, 'name')); ?>,
                    datasets: [{
                        label: 'Total',
                        data: <?php echo json_encode(array_column($monthlyData, 'konsultasi')); ?>,
                        borderColor: '#d946ef',
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

    // Auto init if loaded directly or satisfy the caller
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initCharts, 100);
    }
</script>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-teal-400 to-cyan-600 rounded-2xl p-6 text-white shadow-md text-center">
        <p class="text-sm font-medium opacity-90 mb-2">Total Konsultasi</p>
        <h3 class="text-4xl font-bold mb-1"><?php echo count($consultations) + 1200; // Dummy total ?></h3>
        <p class="text-xs opacity-75">Sejak Bergabung</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-md text-center">
        <p class="text-sm font-medium opacity-90 mb-2">Pasien Kembali</p>
        <h3 class="text-4xl font-bold mb-1">782</h3>
        <p class="text-xs opacity-75">62% dari total pasien</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 text-white shadow-md text-center">
        <p class="text-sm font-medium opacity-90 mb-2">Total Pendapatan</p>
        <h3 class="text-3xl font-bold mb-1">Rp 93.750.000</h3>
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