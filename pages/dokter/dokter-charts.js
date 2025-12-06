function initCharts(weeklyData, monthlyData) {
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
                labels: weeklyData.map(d => d.name),
                datasets: [{
                    label: 'Konsultasi',
                    data: weeklyData.map(d => d.konsultasi),
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
                labels: monthlyData.map(m => m.name),
                datasets: [{
                    label: 'Total',
                    data: monthlyData.map(m => m.konsultasi),
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
