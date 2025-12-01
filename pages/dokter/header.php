jekas<?php
// Include required files first (before any output)
require_once __DIR__ . '/src/config/config.php';
require_once __DIR__ . '/services/DAO_dokter.php';
require_once __DIR__ . '/services/DAO_artikel.php';
require_once __DIR__ . '/services/DAO_pertanyaan.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check login status
$isLoggedIn = isLoggedIn();

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
    <script src="public/assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        /* Memastikan editor memiliki sudut melengkung agar serasi dengan form */
        .tox-tinymce {
            border-radius: 0.75rem !important;
        }
    </style>
</head>
<?php
$current_page = basename($_SERVER['SCRIPT_NAME']);
$color = 'purple'; // Keep theme purple
?>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-<?php echo $color; ?>-200 shadow-card">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3 group cursor-pointer" onclick="navigateTo('home_dokter.php')">
                    <div class="w-12 h-12 bg-gradient-to-br from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-glow">
                        <span class="text-white text-2xl">🐾</span>
                    </div>
                    <span class="text-3xl font-display font-bold text-<?php echo $color; ?>-600 hover:text-<?php echo $color; ?>-700 transition-colors">VetCare</span>
                </div>

                <nav class="hidden md:flex items-center gap-10">
                    <button onclick="navigateTo('pages/dashboard-dokter.php')" class="relative text-gray-700 hover:text-<?php echo $color; ?>-600 font-medium transition-all duration-300 group">
                        <span>Dashboard</span>
                        <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 group-hover:w-full transition-all duration-300"></div>
                    </button>

                    <button onclick="navigateTo('pages/tanya-dokter.php')" class="relative text-gray-700 hover:text-<?php echo $color; ?>-600 font-medium transition-all duration-300 group">
                        <span>Pertanyaan</span>
                        <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 group-hover:w-full transition-all duration-300"></div>
                    </button>

                    <button onclick="navigateTo('pages/kelola-artikel.php')" class="relative text-gray-700 hover:text-<?php echo $color; ?>-600 font-medium transition-all duration-300 group">
                        <span>Artikel</span>
                        <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 group-hover:w-full transition-all duration-300"></div>
                    </button>

                    
                </nav>

                <div class="flex items-center gap-4">
                    <?php if ($isLoggedIn): ?>
                        <button onclick="navigateTo('pages/profile-dokter.php')" class="hidden md:block hover:bg-<?php echo $color; ?>-50 font-medium px-4 py-2 rounded-lg transition-colors">
                            <span class="mr-2">👤</span>
                            Profil
                        </button>
                        <button onclick="navigateTo('pages/logout.php')" class="hidden sm:flex font-display font-semibold bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-2xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">🚪</span>
                            Logout
                        </button>
                    <?php else: ?>
                        <button onclick="navigateTo('pages/auth-dokter.php')" class="hidden sm:flex font-display font-semibold bg-gradient-to-r from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 text-white px-6 py-3 rounded-2xl hover:from-<?php echo $color; ?>-600 hover:to-<?php echo $color; ?>-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">🔐</span>
                            Login
                        </button>
                    <?php endif; ?>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2 text-gray-700 hover:text-<?php echo $color; ?>-600 transition-colors" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" id="mobile-menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden border-t border-<?php echo $color; ?>-200 py-4 hidden" id="mobile-menu">
                <nav class="flex flex-col space-y-4">
                    <button onclick="navigateTo('pages/dashboard-dokter.php'); toggleMobileMenu()" class="text-left text-gray-700 hover:text-<?php echo $color; ?>-600 font-medium transition-colors px-4">
                        Dashboard
                    </button>

                    <button onclick="navigateTo('pages/tanya-dokter.php'); toggleMobileMenu()" class="text-left text-gray-700 hover:text-<?php echo $color; ?>-600 font-medium transition-colors px-4">
                        Pertanyaan
                    </button>

                    <button onclick="navigateTo('pages/kelola-artikel.php'); toggleMobileMenu()" class="text-left text-gray-700 hover:text-<?php echo $color; ?>-600 font-medium transition-colors px-4">
                        Artikel
                    </button>

                    <button onclick="navigateTo('pages/chat-pasien.php'); toggleMobileMenu()" class="text-left text-gray-700 hover:text-<?php echo $color; ?>-600 font-medium transition-colors px-4">
                        Chat
                    </button>

                    <div class="px-4 pt-4 border-t border-<?php echo $color; ?>-200 space-y-3">
                        <?php if ($isLoggedIn): ?>
                            <button onclick="navigateTo('pages/profile-dokter.php'); toggleMobileMenu()" class="w-full justify-start hover:bg-<?php echo $color; ?>-50 font-medium px-4 py-2 rounded-lg transition-colors flex items-center">
                                <span class="mr-2">👤</span>
                                Profil
                            </button>
                            <button onclick="navigateTo('pages/logout.php'); toggleMobileMenu()" class="w-full font-display font-semibold bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-2xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-glow">
                                <span class="mr-2">🚪</span>
                                Logout
                            </button>
                        <?php else: ?>
                            <button onclick="navigateTo('pages/auth-dokter.php'); toggleMobileMenu()" class="w-full font-display font-semibold bg-gradient-to-r from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600 text-white px-6 py-3 rounded-2xl hover:from-<?php echo $color; ?>-600 hover:to-<?php echo $color; ?>-700 transition-all duration-300 shadow-glow">
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
            <div class="bg-<?php echo $_SESSION['flash']['type'] === 'success' ? 'green' : 'red'; ?>-100 border border-<?php echo $_SESSION['flash']['type'] === 'success' ? 'green' : 'red'; ?>-400 text-<?php echo $_SESSION['flash']['type'] === 'success' ? 'green' : 'red'; ?>-700 px-4 py-3 rounded-lg">
                <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
            </div>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <script>
        // Header JavaScript functions
        function navigateTo(path) {
            window.location.href = '<?php echo BASE_URL; ?>' + path;
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
    </script>


</body>
</html>
