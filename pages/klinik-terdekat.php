<?php
// Klinik Terdekat page - Find nearby clinics
// Fetch all doctor locations from database
$locations = DAO_dokter::allDoktersLocations();
if ($locations === false) {
    $locations = [];
}
?>
<div class="pt-32 pb-20">
    <div class="container mx-auto px-4">
       <div class="mb-8">
            <button onclick="navigateTo('?route=')" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Beranda
            </button>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <span class="text-4xl text-green-600">📍</span>
                </div>
                <h1 class="text-4xl font-display font-bold text-gray-800 mb-4">
                    Klinik Terdekat
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Temukan klinik hewan terdekat dari lokasi Anda
                </p>
            </div>

            <!-- Location Status -->
            <div class="bg-white rounded-lg shadow-card p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-xl">📍</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Lokasi Anda</h3>
                            <p class="text-sm text-gray-600" id="user-location-text">Mengambil lokasi...</p>
                        </div>
                    </div>
                    <button onclick="getUserLocation()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-sm">
                        🔄 Perbarui Lokasi
                    </button>
                </div>
                <div class="text-xs text-gray-500">
                    Klik "Perbarui Lokasi" untuk menghitung jarak ke klinik terdekat
                </div>
            </div>

            <!-- Clinic Results -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Daftar Klinik Hewan
                </h2>
                <p class="text-gray-600">
                    Ditemukan <span class="font-semibold text-green-600"><?php echo count($locations); ?></span> klinik
                </p>
            </div>

            <div class="space-y-6" id="clinic-list">
                <?php if (empty($locations)): ?>
                    <div class="bg-white rounded-lg shadow-card p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-4xl">🏥</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Klinik Terdaftar</h3>
                        <p class="text-gray-600">Saat ini belum ada klinik yang terdaftar dalam sistem.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($locations as $index => $location): ?>
                        <div class="bg-white rounded-xl shadow-card p-6 hover:shadow-xl transition-all duration-300 clinic-card border-l-4 border-purple-500" 
                             data-lat="<?php echo htmlspecialchars($location['lat']); ?>" 
                             data-lng="<?php echo htmlspecialchars($location['long']); ?>"
                             data-name="<?php echo htmlspecialchars($location['nama_klinik']); ?>">
                            
                            <div class="flex items-start gap-6">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center shadow-sm">
                                        <span class="text-3xl">🏥</span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-800 mb-1">
                                                <?php echo htmlspecialchars($location['nama_klinik']); ?>
                                            </h3>
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span class="font-medium"><?php echo htmlspecialchars($location['nama_dokter']); ?></span>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            ● Tersedia
                                        </span>
                                    </div>

                                    <!-- Info Grid -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                        <!-- Distance -->
                                        <div class="flex items-center gap-2 text-sm">
                                            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                                                <span class="text-base">📍</span>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Jarak</p>
                                                <p class="font-semibold text-gray-800 distance-text" data-index="<?php echo $index; ?>">Menghitung...</p>
                                            </div>
                                        </div>

                                        <!-- Coordinates -->
                                        <div class="flex items-center gap-2 text-sm">
                                            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                                                <span class="text-base">🗺️</span>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Koordinat</p>
                                                <p class="font-mono text-xs text-gray-700">
                                                    <?php echo number_format($location['lat'], 4); ?>, <?php echo number_format($location['long'], 4); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <button onclick="openGoogleMaps(<?php echo $location['lat']; ?>, <?php echo $location['long']; ?>)" 
                                            class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>Petunjuk Arah ke Klinik</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Emergency Section -->
            <div class="mt-12 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg p-8 text-center">
                <h3 class="text-2xl font-display font-bold mb-4">
                    Butuh Bantuan Darurat?
                </h3>
                <p class="mb-6 opacity-90">
                    Untuk kasus darurat, hubungi dokter kami secara online
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="navigateTo('?route=pilih-dokter')" class="inline-block bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        🩺 Konsultasi Online
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let userLat = null;
let userLng = null;

// Calculate distance between two coordinates using Haversine formula
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the Earth in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const distance = R * c;
    return distance;
}

// Update distances for all clinics
function updateDistances() {
    if (userLat === null || userLng === null) {
        return;
    }

    const clinicCards = document.querySelectorAll('.clinic-card');
    const clinicsWithDistance = [];

    clinicCards.forEach((card, index) => {
        const clinicLat = parseFloat(card.dataset.lat);
        const clinicLng = parseFloat(card.dataset.lng);
        const distance = calculateDistance(userLat, userLng, clinicLat, clinicLng);
        
        const distanceText = card.querySelector('.distance-text');
        if (distance < 1) {
            distanceText.textContent = `${(distance * 1000).toFixed(0)} m`;
        } else {
            distanceText.textContent = `${distance.toFixed(1)} km`;
        }

        clinicsWithDistance.push({
            card: card,
            distance: distance
        });
    });

    // Sort clinics by distance
    clinicsWithDistance.sort((a, b) => a.distance - b.distance);
    
    // Reorder DOM elements
    const clinicList = document.getElementById('clinic-list');
    clinicsWithDistance.forEach(item => {
        clinicList.appendChild(item.card);
    });
}

// Get user's current location
function getUserLocation() {
    const locationText = document.getElementById('user-location-text');
    locationText.textContent = 'Mengambil lokasi...';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;
                locationText.textContent = `${userLat.toFixed(6)}, ${userLng.toFixed(6)}`;
                updateDistances();
            },
            (error) => {
                console.error('Error getting location:', error);
                locationText.textContent = 'Gagal mengambil lokasi. Klik "Perbarui Lokasi" untuk mencoba lagi.';
                alert('Tidak dapat mengakses lokasi Anda. Pastikan Anda telah mengizinkan akses lokasi di browser.');
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    } else {
        locationText.textContent = 'Browser tidak mendukung geolocation';
        alert('Browser Anda tidak mendukung fitur geolocation.');
    }
}

// Open Google Maps with directions
function openGoogleMaps(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, '_blank');
}

// Auto-get location on page load
document.addEventListener('DOMContentLoaded', function() {
    getUserLocation();
});
</script>
