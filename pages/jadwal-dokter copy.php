<?php
/**
 * File: pages/jadwal-dokter.php
 * Halaman jadwal dokter
 */

$pageTitle = "Jadwal Dokter - VetCare";
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../services/DAO_dokter.php';

// Require login
requireLogin();

$db = Database::getConnection();
$daoDokter = new DAO_dokter($db);

// Get current dokter profile
$dokter = $daoDokter->getById($currentDokter['id_dokter']);

if (!$dokter) {
    setFlash('error', 'Data dokter tidak ditemukan!');
    header('Location: ' . BASE_URL . 'pages/dashboard-dokter.php');
    exit();
}

// Handle schedule update
if (isset($_POST['update_jadwal'])) {
    $jadwal = [];

    // Days of the week
    $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

    foreach ($days as $day) {
        $start_time = clean($_POST[$day . '_start'] ?? '');
        $end_time = clean($_POST[$day . '_end'] ?? '');
        $is_active = isset($_POST[$day . '_active']) ? 1 : 0;

        if ($is_active && !empty($start_time) && !empty($end_time)) {
            $jadwal[$day] = [
                'start' => $start_time,
                'end' => $end_time,
                'active' => $is_active
            ];
        } else {
            $jadwal[$day] = [
                'start' => '',
                'end' => '',
                'active' => $is_active
            ];
        }
    }

    // For now, we'll store in session or you can implement database storage
    // TODO: Implement proper database storage for schedules
    $_SESSION['dokter_jadwal'] = $jadwal;
    setFlash('success', 'Jadwal berhasil diperbarui!');
    header('Location: ' . BASE_URL . 'pages/jadwal-dokter.php');
    exit();
}

// Get current schedule (from session for now)
$current_jadwal = $_SESSION['dokter_jadwal'] ?? [];
?>

<style>
    .schedule-card {
        transition: all 0.3s ease;
    }

    .schedule-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .day-toggle {
        transition: all 0.3s ease;
    }

    .day-toggle.active {
        background-color: #10b981;
        color: white;
    }

    .time-input {
        transition: all 0.3s ease;
    }

    .time-input:disabled {
        background-color: #f9fafb;
        color: #6b7280;
    }
</style>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">📅 Jadwal Praktik</h1>
                <p class="text-lg text-gray-600">Kelola jadwal praktik Anda</p>
            </div>
            <button onclick="toggleEditMode()" id="editButton" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                ✏️ Edit Jadwal
            </button>
        </div>
    </div>

    <!-- Current Schedule Display -->
    <div id="readonlyView" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php
            $days = [
                'senin' => 'Senin',
                'selasa' => 'Selasa',
                'rabu' => 'Rabu',
                'kamis' => 'Kamis',
                'jumat' => 'Jumat',
                'sabtu' => 'Sabtu',
                'minggu' => 'Minggu'
            ];

            foreach ($days as $key => $day_name) {
                $schedule = $current_jadwal[$key] ?? ['active' => 0, 'start' => '', 'end' => ''];
                $is_active = $schedule['active'] ?? 0;
                $start_time = $schedule['start'] ?? '';
                $end_time = $schedule['end'] ?? '';
            ?>
            <div class="schedule-card bg-white rounded-xl shadow-sm p-6 <?php echo $is_active ? 'border-l-4 border-l-primary' : 'border-l-4 border-l-gray-300'; ?>">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800"><?php echo $day_name; ?></h3>
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo $is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'; ?>">
                        <?php echo $is_active ? 'Aktif' : 'Tutup'; ?>
                    </span>
                </div>

                <?php if ($is_active && !empty($start_time) && !empty($end_time)): ?>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary mb-1"><?php echo date('H:i', strtotime($start_time)); ?> - <?php echo date('H:i', strtotime($end_time)); ?></div>
                        <div class="text-sm text-gray-600">WIB</div>
                    </div>
                <?php else: ?>
                    <div class="text-center text-gray-500">
                        <div class="text-lg mb-1">🏠</div>
                        <div class="text-sm">Tidak praktik</div>
                    </div>
                <?php endif; ?>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Edit Schedule Form (Hidden by default) -->
    <form method="POST" action="" id="editForm" class="space-y-6 hidden">
        <div class="bg-white rounded-xl shadow-sm p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">⚙️ Edit Jadwal Praktik</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($days as $key => $day_name) {
                    $schedule = $current_jadwal[$key] ?? ['active' => 0, 'start' => '09:00', 'end' => '17:00'];
                    $is_active = $schedule['active'] ?? 0;
                    $start_time = $schedule['start'] ?? '09:00';
                    $end_time = $schedule['end'] ?? '17:00';
                ?>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo $day_name; ?></h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="<?php echo $key; ?>_active" value="1"
                                class="sr-only peer" <?php echo $is_active ? 'checked' : ''; ?>>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/25 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input type="time" name="<?php echo $key; ?>_start" value="<?php echo $start_time; ?>"
                                class="time-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?php echo !$is_active ? 'disabled' : ''; ?>" <?php echo !$is_active ? 'disabled' : ''; ?>>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input type="time" name="<?php echo $key; ?>_end" value="<?php echo $end_time; ?>"
                                class="time-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?php echo !$is_active ? 'disabled' : ''; ?>" <?php echo !$is_active ? 'disabled' : ''; ?>>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" name="update_jadwal" class="bg-primary text-white py-3 px-8 rounded-lg hover:bg-secondary transition-colors">
                    💾 Simpan Jadwal
                </button>
                <button type="button" onclick="cancelEdit()" class="bg-gray-400 text-white py-3 px-8 rounded-lg hover:bg-gray-500 transition-colors">
                    ❌ Batal
                </button>
            </div>
        </div>
    </form>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-8 mt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">⚡ Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="setWorkingDays()" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors text-left">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">📅</div>
                    <div>
                        <div class="font-medium text-gray-800">Set Hari Kerja</div>
                        <div class="text-sm text-gray-600">Senin - Jumat, 09:00 - 17:00</div>
                    </div>
                </div>
            </button>

            <button onclick="setWeekendOnly()" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors text-left">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">🏖️</div>
                    <div>
                        <div class="font-medium text-gray-800">Weekend Only</div>
                        <div class="text-sm text-gray-600">Sabtu - Minggu, 10:00 - 15:00</div>
                    </div>
                </div>
            </button>

            <button onclick="clearSchedule()" class="p-4 border border-gray-200 rounded-lg hover:border-red-500 hover:bg-red-50 transition-colors text-left">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">🗑️</div>
                    <div>
                        <div class="font-medium text-gray-800">Clear All</div>
                        <div class="text-sm text-gray-600">Hapus semua jadwal</div>
                    </div>
                </div>
            </button>
        </div>
    </div>
</main>

<script>
    // Toggle Edit Mode
    function toggleEditMode() {
        const readonlyView = document.getElementById('readonlyView');
        const editForm = document.getElementById('editForm');
        const editButton = document.getElementById('editButton');

        readonlyView.classList.add('hidden');
        editForm.classList.remove('hidden');
        editButton.style.display = 'none';
    }

    // Cancel Edit
    function cancelEdit() {
        const readonlyView = document.getElementById('readonlyView');
        const editForm = document.getElementById('editForm');
        const editButton = document.getElementById('editButton');

        readonlyView.classList.remove('hidden');
        editForm.classList.add('hidden');
        editButton.style.display = 'flex';
    }

    // Quick Actions
    function setWorkingDays() {
        toggleEditMode();

        // Set working days (Mon-Fri)
        const workingDays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        workingDays.forEach(day => {
            const checkbox = document.querySelector(`input[name="${day}_active"]`);
            const startInput = document.querySelector(`input[name="${day}_start"]`);
            const endInput = document.querySelector(`input[name="${day}_end"]`);

            if (checkbox && startInput && endInput) {
                checkbox.checked = true;
                startInput.value = '09:00';
                endInput.value = '17:00';
                startInput.disabled = false;
                endInput.disabled = false;
                startInput.classList.remove('disabled');
                endInput.classList.remove('disabled');
            }
        });

        // Clear weekends
        const weekends = ['sabtu', 'minggu'];
        weekends.forEach(day => {
            const checkbox = document.querySelector(`input[name="${day}_active"]`);
            const startInput = document.querySelector(`input[name="${day}_start"]`);
            const endInput = document.querySelector(`input[name="${day}_end"]`);

            if (checkbox && startInput && endInput) {
                checkbox.checked = false;
                startInput.disabled = true;
                endInput.disabled = true;
                startInput.classList.add('disabled');
                endInput.classList.add('disabled');
            }
        });
    }

    function setWeekendOnly() {
        toggleEditMode();

        // Clear weekdays
        const weekdays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        weekdays.forEach(day => {
            const checkbox = document.querySelector(`input[name="${day}_active"]`);
            const startInput = document.querySelector(`input[name="${day}_start"]`);
            const endInput = document.querySelector(`input[name="${day}_end"]`);

            if (checkbox && startInput && endInput) {
                checkbox.checked = false;
                startInput.disabled = true;
                endInput.disabled = true;
                startInput.classList.add('disabled');
                endInput.classList.add('disabled');
            }
        });

        // Set weekends
        const weekends = ['sabtu', 'minggu'];
        weekends.forEach(day => {
            const checkbox = document.querySelector(`input[name="${day}_active"]`);
            const startInput = document.querySelector(`input[name="${day}_start"]`);
            const endInput = document.querySelector(`input[name="${day}_end"]`);

            if (checkbox && startInput && endInput) {
                checkbox.checked = true;
                startInput.value = '10:00';
                endInput.value = '15:00';
                startInput.disabled = false;
                endInput.disabled = false;
                startInput.classList.remove('disabled');
                endInput.classList.remove('disabled');
            }
        });
    }

    function clearSchedule() {
        if (confirm('Apakah Anda yakin ingin menghapus semua jadwal?')) {
            toggleEditMode();

            const days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
            days.forEach(day => {
                const checkbox = document.querySelector(`input[name="${day}_active"]`);
                const startInput = document.querySelector(`input[name="${day}_start"]`);
                const endInput = document.querySelector(`input[name="${day}_end"]`);

                if (checkbox && startInput && endInput) {
                    checkbox.checked = false;
                    startInput.value = '09:00';
                    endInput.value = '17:00';
                    startInput.disabled = true;
                    endInput.disabled = true;
                    startInput.classList.add('disabled');
                    endInput.classList.add('disabled');
                }
            });
        }
    }

    // Handle checkbox changes to enable/disable time inputs
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name$="_active"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const day = this.name.replace('_active', '');
                const startInput = document.querySelector(`input[name="${day}_start"]`);
                const endInput = document.querySelector(`input[name="${day}_end"]`);

                if (this.checked) {
                    startInput.disabled = false;
                    endInput.disabled = false;
                    startInput.classList.remove('disabled');
                    endInput.classList.remove('disabled');
                } else {
                    startInput.disabled = true;
                    endInput.disabled = true;
                    startInput.classList.add('disabled');
                    endInput.classList.add('disabled');
                }
            });
        });
    });
</script>

<?php require_once __DIR__ . '/../footer-dokter.php'; ?>
