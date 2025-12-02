<?php
$loggedIn = isset($_SESSION['user']); $userRole = ''; $isDokter=false; $fotoProfile = null;
if ($loggedIn){
    $userRole = $_SESSION['user']->getRole();
    $isDokter = $userRole === 'Dokter' && isset($_SESSION['dokter']);
    $fotoProfile = ($isDokter) ? $_SESSION['dokter']->getFoto() : null;
}
                
?>
<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-purple-200 shadow-card">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center gap-3 group cursor-pointer" onclick="navigateTo('?route=')">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-glow">
                    <span class="text-white text-xl md:text-2xl">🐾</span>
                </div>
                <span class="text-2xl md:text-3xl font-display font-bold text-purple-600 hover:text-purple-700 transition-colors">VetCare</span>
            </div>

            <nav class="hidden md:flex items-center gap-6 lg:gap-10">
                <button onclick="scrollToSection('dokter')" class="relative text-gray-700 hover:text-purple-600 font-medium transition-all duration-300 group">
                    <span>Dokter</span>
                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-purple-600 group-hover:w-full transition-all duration-300"></div>
                </button>

                <div class="relative" id="services-container">
                    <button class="relative text-gray-700 hover:text-purple-600 font-medium transition-all duration-300 group flex items-center gap-1">
                        <span>Layanan</span>
                        <svg class="w-4 h-4 transition-transform duration-300" id="services-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-purple-600 group-hover:w-full transition-all duration-300"></div>
                    </button>

                    <div class="absolute top-full left-0 mt-2 w-64 bg-white backdrop-blur-xl border border-gray-200 rounded-xl shadow-hero py-3 z-[100] hidden opacity-0 transform scale-95 transition-all duration-300" id="services-menu">
                        <div class="px-3 py-2 border-b border-gray-100 mb-2">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Layanan Kami</h4>
                        </div>
                        <a href="?route=konsultasi-dokter" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 rounded-lg mx-2">
                            <span class="text-lg">🩺</span>
                            <div>
                                <div class="font-medium">Konsultasi Dokter</div>
                                <div class="text-xs text-gray-500">Chat & Video Call</div>
                            </div>
                        </a>
                        <a href="?route=tanya-dokter" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 rounded-lg mx-2">
                            <span class="text-lg">❓</span>
                            <div>
                                <div class="font-medium">Tanya Dokter</div>
                                <div class="text-xs text-gray-500">Konsultasi Tertulis</div>
                            </div>
                        </a>
                        <a href="?route=klinik-terdekat" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 rounded-lg mx-2">
                            <span class="text-lg">📍</span>
                            <div>
                                <div class="font-medium">Klinik Terdekat</div>
                                <div class="text-xs text-gray-500">Temukan Lokasi</div>
                            </div>
                        </a>
                        <a href="?route=dokter-ternak" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 rounded-lg mx-2">
                            <span class="text-lg">🐄</span>
                            <div>
                                <div class="font-medium">Dokter Ternak</div>
                                <div class="text-xs text-gray-500">Hewan Produktif</div>
                            </div>
                        </a>
                        <a href="?route=dokter-hewan-kecil" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 rounded-lg mx-2">
                            <span class="text-lg">🐱</span>
                            <div>
                                <div class="font-medium">Dokter Hewan Kecil</div>
                                <div class="text-xs text-gray-500">Kucing, Anjing & Hewan Peliharaan</div>
                            </div>
                        </a>
                    </div>
                </div>
                <button onclick="scrollToSection('cara-kerja')" class="relative text-gray-700 hover:text-purple-600 font-medium transition-all duration-300 group">
                    <span>Cara Kerja</span>
                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-purple-600 group-hover:w-full transition-all duration-300"></div>
                </button>

                <button onclick="scrollToSection('artikel')" class="relative text-gray-700 hover:text-purple-600 font-medium transition-all duration-300 group">
                    <span>Artikel</span>
                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-purple-600 group-hover:w-full transition-all duration-300"></div>
                </button>

                <button onclick="scrollToSection('kontak')" class="relative text-gray-700 hover:text-purple-600 font-medium transition-all duration-300 group">
                    <span>Kontak</span>
                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-purple-600 group-hover:w-full transition-all duration-300"></div>
                </button>
            </nav>

            <div class="flex items-center gap-3 md:gap-4">
                <?php if ($loggedIn): ?>
                    <div class="relative hidden md:block" id="user-menu-container">
                        <button class="flex items-center gap-2 hover:bg-purple-50 font-medium px-4 py-2 rounded-lg transition-colors group" onclick="toggleUserMenu()">
                            <?php if ($isDokter): ?>
                                <?php if ($fotoProfile): ?>
                                    <img src="<?php echo URL_FOTO . 'dokter-profil/' . $fotoProfile; ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-purple-200">
                                <?php else: ?>
                                    <img src="<?php echo URL_FOTO . 'dokter-profil/default-profile.webp'; ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-purple-200">
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                                    <span class="text-sm">👤</span>
                                </div>
                            <?php endif; ?>
                            
                            <span class="text-gray-700 group-hover:text-purple-600 transition-colors">
                                <?php 
                                if ($isDokter) {
                                    $nama = $_SESSION['dokter']->getNama();
                                    // Remove 'dr.' or 'Dr.' prefix if present
                                    $nama = preg_replace('/^dr\.\s*/i', '', $nama);
                                    echo htmlspecialchars($nama);
                                } else {
                                    echo htmlspecialchars(censorEmail($_SESSION['user']->getEmail()));
                                }
                                ?>
                            </span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute top-full right-0 mt-2 w-48 bg-white backdrop-blur-xl border border-gray-200 rounded-xl shadow-hero py-2 z-[100] hidden opacity-0 transform scale-95 transition-all duration-300" id="user-menu">
                            <?php if ($isDokter): ?>
                                <a href="?route=profil" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                    <span>👨‍⚕️</span>
                                    Profil Saya
                                </a>
                            <?php endif; ?>
                            <button onclick="logout()" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <span>🚪</span>
                                Keluar
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <button onclick="navigateTo('?route=auth')" class="hidden md:block hover:bg-purple-50 font-medium px-4 py-2 rounded-lg transition-colors">
                        <span class="mr-2">👤</span>
                        Masuk
                    </button>
                    <button onclick="navigateTo('?route=auth')" class="hidden sm:flex font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-2xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow">
                        <span class="mr-2">✨</span>
                        Daftar Sekarang
                    </button>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-gray-700 hover:text-purple-600 transition-colors" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" id="mobile-menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden border-t border-purple-200 py-4 hidden" id="mobile-menu">
            <nav class="flex flex-col space-y-4">
                <button onclick="scrollToSection('dokter'); toggleMobileMenu()" class="text-left text-gray-700 hover:text-purple-600 font-medium transition-colors px-4">
                    Dokter
                </button>

                <div class="px-4">
                    <button onclick="toggleMobileSubmenu('mobile-layanan')" class="flex items-center justify-between w-full font-medium text-gray-700 hover:text-purple-600 transition-colors">
                        Layanan
                        <svg id="arrow-mobile-layanan" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="mobile-layanan" class="hidden mt-2 space-y-2 pl-4 border-l-2 border-purple-100 ml-1">
                        <a href="?route=konsultasi-dokter" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">🩺 Konsultasi Dokter</a>
                        <a href="?route=tanya-dokter" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">❓ Tanya Dokter</a>
                        <a href="?route=klinik-terdekat" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">📍 Klinik Terdekat</a>
                        <a href="?route=dokter-ternak" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">🐄 Dokter Ternak</a>
                        <a href="?route=dokter-hewan-kecil" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">🐱 Dokter Hewan Kecil</a>
                    </div>
                </div>

                <button onclick="scrollToSection('cara-kerja'); toggleMobileMenu()" class="text-left text-gray-700 hover:text-purple-600 font-medium transition-colors px-4">
                    Cara Kerja
                </button>

                <button onclick="scrollToSection('artikel'); toggleMobileMenu()" class="text-left text-gray-700 hover:text-purple-600 font-medium transition-colors px-4">
                    Artikel
                </button>

                <button onclick="scrollToSection('kontak'); toggleMobileMenu()" class="text-left text-gray-700 hover:text-purple-600 font-medium transition-colors px-4">
                    Kontak
                </button>

                <div class="px-4 pt-4 border-t border-purple-200 space-y-3">
                    <?php if ($loggedIn){
                    ?>
                        <div class="flex items-center gap-3 mb-4">
                            <?php if ($isDokter): ?>
                                <?php if ($fotoProfile): ?>
                                    <img src="<?php echo URL_FOTO . 'dokter-profil/' . $fotoProfile; ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-purple-200">
                                <?php else: ?>
                                    <img src="<?php echo URL_FOTO . 'dokter-profil/default-profile.webp'; ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-purple-200">
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-lg">
                                    <?php echo strtoupper(substr($userRole, 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800">
                                    <?php 
                                    if ($isDokter) {
                                        $nama = $_SESSION['dokter']->getNama();
                                        $nama = preg_replace('/^dr\.\s*/i', '', $nama);
                                        echo htmlspecialchars($nama);
                                    } else {
                                        echo htmlspecialchars(censorEmail($_SESSION['user']->getEmail()));
                                    }
                                    ?>
                                </span>
                                <span class="text-xs text-gray-500"><?php echo $_SESSION['user']->getRole(); ?></span>
                            </div>
                        </div>

                        <?php if ($_SESSION['user']->getRole() === 'Dokter'): ?>
                            <button onclick="navigateTo('?route=profil'); toggleMobileMenu()" class="w-full justify-start hover:bg-purple-50 font-medium px-4 py-2 rounded-lg transition-colors flex items-center">
                                <span class="mr-2">👨‍⚕️</span>
                                Profil Saya
                            </button>
                        <?php endif; ?>
                        <button onclick="logout(); toggleMobileMenu()" class="w-full font-display font-semibold bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-2xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">🚪</span>
                            Keluar
                        </button>
                    <?php }else{ ?>
                        <button id='auth' onclick="navigateTo('?route=auth'); toggleMobileMenu()" class="w-full justify-start hover:bg-purple-50 font-medium px-4 py-2 rounded-lg transition-colors flex items-center">
                            <span class="mr-2">👤</span>
                            Masuk
                        </button>
                        <button id="auth" onclick="navigateTo('?route=auth&action=register'); toggleMobileMenu()" class="w-full font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-2xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">✨</span>
                            Daftar Sekarang
                        </button>
                    <?php } ?> 
                </div>
            </nav>
        </div>
    </div>
</header>

<script>
// Header JavaScript functions
function navigateTo(path) {
    // If path is just '?route=', redirect to home
    if (path === '?route=') {
        window.location.href = '?route=';
    } else {
        window.location.href = path;
    }
}

function scrollToSection(sectionId) {
    // Check if we're on the home page (route is empty or '/')
    const urlParams = new URLSearchParams(window.location.search);
    const currentRoute = urlParams.get('route') || '';
    if (currentRoute !== '' && currentRoute !== '/') {
        // If not on home page, redirect to home with scroll parameter
        window.location.href = '?route=&scroll=' + sectionId;
        return;
    }
    // If on home page, scroll to the section
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function showServicesMenu() {
    const menu = document.getElementById('services-menu');
    const chevron = document.getElementById('services-chevron');
    menu.classList.remove('hidden');
    menu.classList.add('opacity-100', 'scale-100');
    menu.classList.remove('opacity-0', 'scale-95');
    chevron.style.transform = 'rotate(180deg)';
}

function hideServicesMenu() {
    const menu = document.getElementById('services-menu');
    const chevron = document.getElementById('services-chevron');
    menu.classList.add('opacity-0', 'scale-95');
    menu.classList.remove('opacity-100', 'scale-100');
    setTimeout(() => {
        menu.classList.add('hidden');
    }, 300);
    chevron.style.transform = 'rotate(0deg)';
}

function toggleServicesMenu() {
    const menu = document.getElementById('services-menu');
    const chevron = document.getElementById('services-chevron');
    if (menu.classList.contains('hidden')) {
        showServicesMenu();
    } else {
        hideServicesMenu();
    }
}

function toggleUserMenu() {
    const menu = document.getElementById('user-menu');
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        // Small delay to allow display:block to apply before opacity transition
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
document.addEventListener('click', function(e) {
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

// Enhanced dropdown functionality for better UX
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('services-container');
    const menu = document.getElementById('services-menu');
    let hideTimeout;

    if (container && menu) {
        // Show menu on hover
        container.addEventListener('mouseenter', showServicesMenu);

        // Hide menu when mouse leaves the container area with delay
        container.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(hideServicesMenu, 150); // 150ms delay
        });

        // Cancel hide when entering the menu
        menu.addEventListener('mouseenter', function() {
            if (hideTimeout) {
                clearTimeout(hideTimeout);
            }
        });

        // Hide menu when mouse leaves the menu
        menu.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(hideServicesMenu, 150);
        });

        // Prevent menu clicks from closing the menu
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!container.contains(e.target) && !menu.contains(e.target)) {
                hideServicesMenu();
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideServicesMenu();
            }
        });
    }
});

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

function toggleMobileSubmenu(id) {
    const submenu = document.getElementById(id);
    const arrow = document.getElementById('arrow-' + id);
    if (submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        submenu.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}

function logout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        window.location.href = '?route=logout';
    }
}

// Check for scroll parameter on page load
window.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const scrollTo = urlParams.get('scroll');
    if (scrollTo) {
        setTimeout(() => {
            scrollToSection(scrollTo);
        }, 100);
    }
});
</script>

<script>
// Ensure the page content sits below the fixed header by applying
// an equivalent padding-top to <body> equal to the header height.
// This keeps layout correct across screen sizes and when header height
// changes (mobile vs desktop).
function adjustBodyPaddingForHeader() {
    try {
        var hdr = document.querySelector('header');
        if (!hdr) return;
        var height = hdr.offsetHeight || 0;
        document.body.style.paddingTop = height + 'px';
    } catch (e) {
        // fail silently
        console && console.warn && console.warn('adjustBodyPaddingForHeader error', e);
    }
}

// Debounce helper for resize
var _resizeTimer;
window.addEventListener('DOMContentLoaded', function() {
    adjustBodyPaddingForHeader();
});
window.addEventListener('load', function() {
    adjustBodyPaddingForHeader();
});
window.addEventListener('resize', function() {
    clearTimeout(_resizeTimer);
    _resizeTimer = setTimeout(adjustBodyPaddingForHeader, 120);
});
</script>
