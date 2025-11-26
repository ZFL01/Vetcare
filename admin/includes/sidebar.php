
<?php
// You may need a small script to toggle sidebar collapse on small screens (included here)
?>

<div id="sidebar" class="w-64 bg-white/90 backdrop-blur-md border-r border-purple-300 min-h-screen p-5 shadow-md fixed md:relative z-30 transform md:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Mobile hamburger button -->
    <div class="md:hidden flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-purple-700 flex items-center gap-2">
            <span>🐾</span> VetCare
        </h2>
        <button id="sidebarToggle" class="text-purple-700 focus:outline-none" aria-label="Toggle sidebar">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- User info section -->
    <div class="hidden md:flex items-center gap-3 mb-8 p-3 rounded-md bg-purple-100">
        <img src="https://ui-avatars.com/api/?name=Admin" alt="Admin Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-purple-500">
        <div>
            <p class="text-gray-800 font-semibold">Admin</p>
            <p class="text-xs text-purple-600">Administrator</p>
        </div>
    </div>

    <!-- Sidebar title for medium+ screens -->
    <div class="mb-6 md:hidden">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <span>🐾</span> VetCare
        </h2>
        <p class="text-xs text-gray-500 mt-1">Admin Panel</p>
    </div>

    <!-- Nav links -->
    <nav class="flex flex-col space-y-3">
        <a href="?tab=dashboard" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition transform duration-200 ease-in-out
           <?php echo $activeTab === 'dashboard' ? 'bg-gradient-to-r from-purple-600 to-violet-700 text-white shadow-lg scale-105' : 'text-gray-700 hover:bg-purple-100 hover:shadow-sm hover:scale-105' ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="font-semibold">Dashboard</span>
        </a>
        <a href="?tab=authentication" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition transform duration-200 ease-in-out
           <?php echo $activeTab === 'authentication' ? 'bg-gradient-to-r from-purple-600 to-violet-700 text-white shadow-lg scale-105' : 'text-gray-700 hover:bg-purple-100 hover:shadow-sm hover:scale-105' ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-semibold">Autentikasi Dokter</span>
        </a>
        <a href="?tab=categories" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition transform duration-200 ease-in-out
           <?php echo $activeTab === 'categories' ? 'bg-gradient-to-r from-purple-600 to-violet-700 text-white shadow-lg scale-105' : 'text-gray-700 hover:bg-purple-100 hover:shadow-sm hover:scale-105' ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="font-semibold">Kelola Kategori</span>
        </a>
    </nav>
</div>

<script>
// Sidebar toggle script for mobile
document.getElementById('sidebarToggle').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
    } else {
        sidebar.classList.add('-translate-x-full');
    }
});
</script>
