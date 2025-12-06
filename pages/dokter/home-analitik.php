<?php
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-gradient-to-br from-teal-400 to-cyan-600 rounded-2xl p-6 text-white shadow-md text-center">
                    <p class="text-sm font-medium opacity-90 mb-2">Total Konsultasi</p>
                    <h3 class="text-4xl font-bold mb-1"><?php echo count($consultations) + 1200; // Dummy total ?></h3>
                    <p class="text-xs opacity-75">Sejak Bergabung</p>
                </div>
                <div
                    class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-md text-center">
                    <p class="text-sm font-medium opacity-90 mb-2">Pasien Kembali</p>
                    <h3 class="text-4xl font-bold mb-1">782</h3>
                    <p class="text-xs opacity-75">62% dari total pasien</p>
                </div>
                <div
                    class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 text-white shadow-md text-center">
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