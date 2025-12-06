<?php
$raw_locations = DAO_dokter::allDoktersLocations();
if ($raw_locations === false) {
    $raw_locations = [];
}

// Filter duplikat nama klinik (case-insensitive)
$locations = [];
$seen = [];

foreach ($raw_locations as $loc) {
    $key = strtolower(trim($loc['nama_klinik']));
    if (in_array($key, $seen))
        continue;

    $seen[] = $key;
    $locations[] = $loc; // sudah lengkap: lat, long, kabupaten, provinsi, nama_dokter
}
?>

<div class="pt-32 pb-20">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <button onclick="navigateTo('?route=')"
                class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold transition-colors duration-200">
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

            <div class="bg-white rounded-lg shadow-card p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-xl">📍</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Lokasi Anda</h3>
                            <p class="text-sm text-gray-800 font-medium leading-relaxed break-words"
                                id="user-location-text">
                                Mengambil lokasi...
                            </p>
                        </div>
                    </div>
                    <button onclick="getUserLocation()"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-sm">
                        🔄 Perbarui Lokasi
                    </button>
                </div>
                <div class="text-xs text-gray-500">
                    Klik "Perbarui Lokasi" untuk menghitung jarak ke klinik terdekat
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Daftar Klinik Hewan
                </h2>
                <p class="text-gray-600">
                    Ditemukan <span class="font-semibold text-green-600"><?php echo count($locations); ?></span> klinik
                    unik
                </p>
            </div>

            <div id="clinic-list" class="space-y-6">
                <?php if (empty($locations)): ?>
                    <div class="bg-white rounded-lg shadow-card p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-4xl">🏥</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Klinik Terdaftar di wilayah Anda</h3>
                        <p class="text-gray-600">Saat ini belum ada klinik yang terdaftar dalam sistem.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($locations as $index => $location): ?>
                        <div class="bg-white rounded-xl shadow-card p-6 hover:shadow-xl transition-all duration-300 clinic-card border-l-4 border-purple-500"
                            data-lat="<?php echo htmlspecialchars($location['lat']); ?>"
                            data-lng="<?php echo htmlspecialchars($location['long']); ?>"
                            data-name="<?php echo htmlspecialchars($location['nama_klinik']); ?>">

                            <div class="flex items-start gap-6">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center shadow-sm">
                                        <span class="text-3xl">🏥</span>
                                    </div>
                                </div>

                                <div class="flex-grow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-800 mb-1">
                                                <?php echo htmlspecialchars($location['nama_klinik']); ?>
                                            </h3>
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                        </path>
                                                    </svg>
                                                    <span
                                                        class="font-medium"><?php echo htmlspecialchars($location['nama_dokter']); ?></span>
                                                </span>
                                            </div>
                                        </div>
                                        <span
                                            class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            ● Tersedia
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                        <div class="flex items-center gap-2 text-sm">
                                            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                                                <span class="text-base">📍</span>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Jarak</p>
                                                <p class="font-semibold text-gray-800 distance-text"
                                                    data-index="<?php echo $index; ?>">Menghitung...</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 text-sm">
                                            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                                                <span class="text-base">🗺️</span>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Lokasi</p>
                                                <p class="font-medium text-xs text-gray-800 leading-tight">
                                                    <?php
                                                    $alamat = array_filter([$location['kabupaten'], $location['provinsi']]);
                                                    echo $alamat ? htmlspecialchars(implode(', ', $alamat)) : 'Lokasi tidak diketahui';
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        onclick="openGoogleMaps(<?php echo $location['lat']; ?>, <?php echo $location['long']; ?>)"
                                        class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>Petunjuk Arah ke Klinik</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="mt-12 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg p-8 text-center">
                <h3 class="text-2xl font-display font-bold mb-4">
                    Butuh Bantuan Darurat?
                </h3>
                <p class="mb-6 opacity-90">
                    Untuk kasus darurat, hubungi dokter kami secara online
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="navigateTo('?route=pilih-dokter')"
                        class="inline-block bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
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

    // --- 1. Helper: Hitung Jarak ---
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // --- 2. Update Jarak ---
    function updateDistances() {
        if (userLat === null || userLng === null) return;
        const clinicCards = document.querySelectorAll('.clinic-card');
        const clinicsWithDistance = [];

        clinicCards.forEach((card) => {
            const clinicLat = parseFloat(card.dataset.lat);
            const clinicLng = parseFloat(card.dataset.lng);
            const distance = calculateDistance(userLat, userLng, clinicLat, clinicLng);

            const distanceText = card.querySelector('.distance-text');
            if (distance < 1) {
                distanceText.textContent = `${(distance * 1000).toFixed(0)} m`;
            } else {
                distanceText.textContent = `${distance.toFixed(1)} km`;
            }
            clinicsWithDistance.push({ card: card, distance: distance });
        });

        clinicsWithDistance.sort((a, b) => a.distance - b.distance);
        const clinicList = document.getElementById('clinic-list');
        clinicsWithDistance.forEach(item => { clinicList.appendChild(item.card); });
    } async function getHumanReadableAddress(lat, lng) {
        const el = document.getElementById('user-location-text');
        el.innerHTML = '<span class="text-gray-400 animate-pulse">Mencari lokasi...</span>';

        const cacheKey = `${lat.toFixed(6)},${lng.toFixed(6)}`;
        if (window.locationCache?.[cacheKey]) {
            el.innerHTML = `<span class="font-bold text-purple-700">${window.locationCache[cacheKey]}</span>`;
            return;
        }

        let resultText = `${lat.toFixed(5)}, ${lng.toFixed(5)}`; // fallback terakhir

        try {
            // API tercepat & paling akurat untuk Indonesia
            const res = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=id`);
            const d = await res.json();

            const admin = d.localityInfo?.administrative || [];
            const level10 = admin.find(x => x.adminLevel === 10)?.name || ''; // Desa/Kelurahan
            const level8 = admin.find(x => x.adminLevel === 8)?.name || d.locality || ''; // Kecamatan
            const level6 = admin.find(x => x.adminLevel === 6)?.name || ''; // Kab/Kota (kadang level 5)
            const level5 = admin.find(x => x.adminLevel === 5)?.name || level6 || d.city || '';
            const prov = admin.find(x => x.adminLevel === 4)?.name || admin.find(x => x.adminLevel === 3)?.name || d.principalSubdivision || ''; // Provinsi

            const parts = [];
            if (level10) parts.push(level10);
            if (level8 && level8 !== level10) parts.push(level8);
            if (level5) parts.push(level5.replace('Kabupaten ', '').replace('Kota ', ''));

            if (parts.length >= 2) resultText = parts.join(', ');
            else if (level8 && level5) resultText = `${level8}, ${level5.replace('Kabupaten ', '').replace('Kota ', '')}`;

            fetch('/?aksi=location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    kota: level5,
                    prov: prov
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        console.log("berhasil memuat data lokasi");
                    }
                }).catch(error => {
                    console.error("Error fetching location:", error);
                });

        } catch (e) {
            // Jika BigDataCloud gagal → langsung pakai Nominatim (jarang terjadi)
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=16&addressdetails=1`, {
                    headers: { 'User-Agent': 'VetCareApp/1.0' }
                });
                const a = (await res.json()).address || {};

                const desa = a.village || a.hamlet || a.neighbourhood || '';
                const kec = a.suburb || a.town || a.city_district || '';
                const kota = a.city || a.regency || a.county || a.state_district || '';
                const prov = a.state || '';

                const parts = [];
                if (desa) parts.push(desa);
                if (kec && kec !== desa) parts.push(kec);
                if (kota) parts.push(kota.replace('Kabupaten ', '').replace('Kota ', ''));

                if (parts.length >= 2) resultText = parts.join(', ');

                fetch('/?aksi=location', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng,
                        kota: kota,
                        prov: prov
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            console.log("berhasil memuat data lokasi");
                        }
                    }).catch(error => {
                        console.error("Error fetching location:", error);
                    });

            } catch (e2) {
                fetch('/?aksi=location', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng,
                        kota: '',
                        prov: ''
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log("Fallback lokasi (koordinat saja)");
                    })
                    .catch(err => console.error("Gagal menyimpan fallback:", err));
            }
        }

        // Simpan ke cache & tampilkan
        el.innerHTML = `<span class="font-bold text-purple-700">${resultText}</span>`;
        window.locationCache = window.locationCache || {};
        window.locationCache[cacheKey] = resultText;
    }
    // --- 5. Main Execution ---
    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;

                    updateDistances();                    // Hitung & urutkan jarak
                    getHumanReadableAddress(userLat, userLng); // Alamat user: Jl + Kec + Kota
                    // resolveClinicAddresses(); DIHAPUS!
                },
                (error) => {
                    console.error('GPS Error:', error);
                    document.getElementById('user-location-text')
                        .innerHTML = '<span class="text-red-500">Gagal mendeteksi lokasi.</span>';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            document.getElementById('user-location-text').textContent = 'Browser tidak mendukung GPS.';
        }
    }
    function openGoogleMaps(lat, lng) {
        const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
        window.open(url, '_blank');
    }

    document.addEventListener('DOMContentLoaded', function () {
        getUserLocation();
    });
</script>