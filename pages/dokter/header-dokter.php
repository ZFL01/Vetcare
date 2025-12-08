<?php
// CRITICAL: Load DAO_dokter FIRST before config.php
// Karena config.php melakukan session_start(), dan session berisi object DTO_dokter
// Class DTO_dokter harus sudah di-load sebelum session di-unserialize
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/DAO_dokter.php';
require_once __DIR__ . '/../../includes/DAO_user.php';
require_once __DIR__ . '/../../includes/DAO_others.php';
require_once __DIR__ . '/../../src/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sekarang panggil requireLogin setelah semua class di-load
requireLogin(true);

// Check login status
$isLoggedIn = isset($_SESSION['dokter']);
$isDokter = $isLoggedIn;
$fotoProfile = null;
$userRole = 'Dokter';
$loggedIn = $isLoggedIn;

if ($isLoggedIn) {
    $fotoProfile = $_SESSION['dokter']->getFoto();
}

// Set page title
$pageTitle = $pageTitle ?? 'VetCare - Dashboard Dokter';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7C3AED',
                        secondary: '#A855F7',
                        accent: '#C084FC',
                        dark: '#581C87',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Memuat pustaka TinyMCE lokal dari folder assets -->
    <script src="/public/assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        /* Memastikan editor memiliki sudut melengkung agar serasi dengan form */
        .tox-tinymce {
            border-radius: 0.75rem !important;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/public/cat.ico" sizes="16x16 32x32">
    <link rel="shortcut icon" type="image/x-icon" href="/public/cat.ico">
</head>
<?php
$current_page = basename($_SERVER['SCRIPT_NAME']);
$color = 'purple'; // Keep theme purple
?>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <header
        class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-<?php echo $color; ?>-200 shadow-card">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3 group cursor-pointer"
                    onclick="navigateTo('/?route=dashboard-dokter')">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-glow">
                        <span class="text-white text-2xl">🐾</span>
                    </div>
                    <span
                        class="text-3xl font-display font-bold text-<?php echo $color; ?>-600 hover:text-<?php echo $color; ?>-700 transition-colors">VetCare</span>
                </div>
                <div class="flex items-center gap-4">
                    <?php if ($isLoggedIn): ?>
                        <div class="relative hidden md:block" id="user-menu-container">
                            <button
                                class="flex items-center gap-2 hover:bg-<?php echo $color; ?>-50 font-medium px-4 py-2 rounded-lg transition-colors group"
                                onclick="toggleUserMenu()">
                                <?php if ($fotoProfile): ?>
                                    <img src="<?php echo FOTO_DI_DOKTER . $fotoProfile; ?>" alt="Profile"
                                        class="w-8 h-8 rounded-full object-cover border border-<?php echo $color; ?>-200">
                                <?php else: ?>
                                    <div
                                        class="w-8 h-8 bg-<?php echo $color; ?>-100 rounded-full flex items-center justify-center text-<?php echo $color; ?>-600">
                                        <span class="text-sm">👨‍⚕️</span>
                                    </div>
                                <?php endif; ?>

                                <span class="text-gray-700 group-hover:text-<?php echo $color; ?>-600 transition-colors">
                                    <?php
                                    $nama = $_SESSION['dokter']->getNama();
                                    // Remove 'dr.' or 'Dr.' prefix if present
                                    $nama = preg_replace('/^dr\\.\\s*/i', '', $nama);
                                    echo htmlspecialchars($nama);
                                    ?>
                                </span>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-<?php echo $color; ?>-600 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="absolute top-full right-0 mt-2 w-48 bg-white backdrop-blur-xl border border-gray-200 rounded-xl shadow-hero py-2 z-[100] hidden opacity-0 transform scale-95 transition-all duration-300"
                                id="user-menu">
                                <a href="/../../?route=profil"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-<?php echo $color; ?>-50 hover:text-<?php echo $color; ?>-600 transition-colors">
                                    <span>👨‍⚕️</span>
                                    Profil Saya
                                </a>
                                <button onclick="logout()"
                                    class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <span>🚪</span>
                                    Keluar
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <button onclick="navigateTo('pages/auth-dokter.php')"
                            class="hidden sm:flex font-display font-semibold bg-gradient-to-r from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 text-white px-6 py-3 rounded-2xl hover:from-<?php echo $color; ?>-600 hover:to-<?php echo $color; ?>-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">🔐</span>
                            Login
                        </button>
                    <?php endif; ?>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2 text-gray-700 hover:text-<?php echo $color; ?>-600 transition-colors"
                        onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" id="mobile-menu-icon" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden border-t border-<?php echo $color; ?>-200 py-4 hidden" id="mobile-menu">
                <nav class="flex flex-col space-y-4">
                    <div class="px-4 pt-4 border-t border-<?php echo $color; ?>-200 space-y-3">
                        <?php if ($isLoggedIn): ?>
                            <div class="flex items-center gap-3 mb-4">
                                <div>
                                    <?php if ($fotoProfile): ?>
                                        <img src="<?php echo FOTO_DI_DOKTER . $fotoProfile; ?>" alt="Profile"
                                            class="w-10 h-10 rounded-full object-cover border border-<?php echo $color; ?>-200">
                                    <?php else: ?>
                                        <div
                                            class="w-10 h-10 bg-<?php echo $color; ?>-100 rounded-full flex items-center justify-center text-<?php echo $color; ?>-600 font-bold text-lg">
                                            <span>👨‍⚕️</span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-800">
                                            <?php
                                            $nama = $_SESSION['dokter']->getNama();
                                            $nama = preg_replace('/^dr\\.\\s*/i', '', $nama);
                                            echo htmlspecialchars($nama);
                                            ?>
                                        </span>
                                        <span class="text-xs text-gray-500">Dokter</span>
                                    </div>
                                </div>

                                <button onclick="navigateTo('/../../?route=profil'); toggleMobileMenu()"
                                    class="w-full justify-start hover:bg-<?php echo $color; ?>-50 font-medium px-4 py-2 rounded-lg transition-colors flex items-center">
                                    <span class="mr-2">👨‍⚕️</span>
                                    Profil Saya
                                </button>
                                <button onclick="logout(); toggleMobileMenu()"
                                    class="w-full font-display font-semibold bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-2xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-glow">
                                    <span class="mr-2">🚪</span>
                                    Keluar
                                </button>
                            <?php else: ?>
                                <button onclick="navigateTo('pages/auth-dokter.php'); toggleMobileMenu()"
                                    class="w-full font-display font-semibold bg-gradient-to-r from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 text-white px-6 py-3 rounded-2xl hover:from-<?php echo $color; ?>-600 hover:to-<?php echo $color; ?>-700 transition-all duration-300 shadow-glow">
                                    <span class="mr-2">🔐</span>
                                    Login
                                </button>
                            <?php endif; ?>
                        </div>
                </nav>
            </div>
        </div>
    </header>


    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
            <div
                class="bg-<?php echo $_SESSION['flash']['type'] === 'success' ? 'green' : 'red'; ?>-100 border border-<?php echo $_SESSION['flash']['type'] === 'success' ? 'green' : 'red'; ?>-400 text-<?php echo $_SESSION['flash']['type'] === 'success' ? 'green' : 'red'; ?>-700 px-4 py-3 rounded-lg">
                <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
            </div>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <script>
        // Header JavaScript functions
        function navigateTo(path) {
            window.location.href = path;
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('mobile-menu-icon');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            } else {
                menu.classList.add('hidden');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
            }
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                fetch('/../?aksi=logout')
                    .then(response => {
                        if (response.ok) {
                            window.location.href = '/../?route=';
                        }
                    })
                    .catch(error => {
                        console.error('Error logging out:', error);
                    });
                // Gunakan BASE_URL dari PHP
            }
        }

        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.remove('opacity-0', 'scale-95');
                    menu.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                menu.classList.add('opacity-0', 'scale-95');
                menu.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 300);
            }
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function (e) {
            const menu = document.getElementById('user-menu');
            const container = document.getElementById('user-menu-container');
            if (menu && container && !container.contains(e.target)) {
                menu.classList.add('opacity-0', 'scale-95');
                menu.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 300);
            }
        });

    </script>
</body>

</html>