<?php
// Header component converted to PHP
$currentPage = isset($_GET['route']) ? $_GET['route'] : '';
?>
<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-purple-200 shadow-card">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center gap-3 group cursor-pointer" onclick="navigateTo('/')">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-glow">
                    <span class="text-white text-2xl">🐾</span>
                </div>
                <span class="text-3xl font-display font-bold text-purple-600 hover:text-purple-700 transition-colors">VetCare</span>
            </div>

            <nav class="hidden md:flex items-center gap-10">
                <button onclick="scrollToSection('dokter')" class="relative text-gray-700 hover:text-purple-600 font-medium transition-all duration-300 group">
                    <span>Dokter</span>
                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-purple-600 group-hover:w-full transition-all duration-300"></div>
                </button>

                <div class="relative" id="services-dropdown">
                    <button onclick="toggleServicesMenu()" class="relative text-gray-700 hover:text-purple-600 font-medium transition-all duration-300 group flex items-center gap-1">
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

            <div class="flex items-center gap-4">
                <button onclick="navigateTo('?route=auth')" class="hidden md:block hover:bg-purple-50 font-medium px-4 py-2 rounded-lg transition-colors">
                    <span class="mr-2">👤</span>
                    Masuk
                </button>
                <button onclick="navigateTo('?route=auth')" class="hidden sm:flex font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-2xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow">
                    <span class="mr-2">✨</span>
                    Daftar Sekarang
                </button>

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
                    <div class="text-gray-700 font-medium mb-2">Layanan</div>
                    <div class="pl-4 space-y-2">
                        <a href="?route=konsultasi-dokter" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">🩺 Konsultasi Dokter</a>
                        <a href="?route=tanya-dokter" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">❓ Tanya Dokter</a>
                        <a href="?route=klinik-terdekat" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">📍 Klinik Terdekat</a>
                        <a href="?route=dokter-ternak" onclick="toggleMobileMenu()" class="block text-left text-sm text-gray-600 hover:text-purple-600 transition-colors">🐄 Dokter Ternak</a>
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
                    <?php if (isset($_SESSION['user'])): ?>
                        <button onclick="navigateTo('?route=dashboard'); toggleMobileMenu()" class="w-full justify-start hover:bg-purple-50 font-medium px-4 py-2 rounded-lg transition-colors flex items-center">
                            <span class="mr-2">👤</span>
                            Dashboard
                        </button>
                        <button onclick="logout(); toggleMobileMenu()" class="w-full font-display font-semibold bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-2xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">🚪</span>
                            Keluar
                        </button>
                    <?php else: ?>
                        <button onclick="navigateTo('?route=auth'); toggleMobileMenu()" class="w-full justify-start hover:bg-purple-50 font-medium px-4 py-2 rounded-lg transition-colors flex items-center">
                            <span class="mr-2">👤</span>
                            Masuk
                        </button>
                        <button onclick="navigateTo('?route=auth'); toggleMobileMenu()" class="w-full font-display font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-2xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-glow">
                            <span class="mr-2">✨</span>
                            Daftar Sekarang
                        </button>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </div>
</header>

<script>
// Header JavaScript functions
function navigateTo(path) {
    window.location.href = path;
}

function scrollToSection(sectionId) {
    // Check if we're on the home page (route is empty or '/')
    const urlParams = new URLSearchParams(window.location.search);
    const currentRoute = urlParams.get('route') || '';
    if (currentRoute !== '' && currentRoute !== '/') {
        // If not on home page, redirect to home with scroll parameter
        window.location.href = '/?scroll=' + sectionId;
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

// Enhanced dropdown functionality for better UX
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('services-dropdown');
    const menu = document.getElementById('services-menu');

    if (dropdown && menu) {
        // Show menu on hover
        dropdown.addEventListener('mouseenter', showServicesMenu);

        // Hide menu when mouse leaves the dropdown area
        dropdown.addEventListener('mouseleave', function(e) {
            // Check if mouse is still within the dropdown bounds
            const rect = dropdown.getBoundingClientRect();
            const x = e.clientX;
            const y = e.clientY;

            if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
                hideServicesMenu();
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
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
