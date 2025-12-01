<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Geolocation API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f9;
        }
    </style>
</head>
<body class="p-8">

    <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow-2xl border border-gray-100">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Aplikasi Penentu Lokasi (Demo)</h1>
        <p class="text-gray-600 mb-6 text-center">
            Tekan tombol di bawah untuk meminta lokasi Anda saat ini menggunakan Geolocation API dari Browser.
        </p>

        <button id="getLocationBtn" onclick="getUserLocation()"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
            Ambil Lokasi Saya
        </button>

        <!-- Area untuk menampilkan status dan hasil -->
        <div id="statusMessage" class="mt-6 p-4 text-center rounded-lg text-sm bg-blue-100 text-blue-800 hidden">
            Menunggu izin...
        </div>

        <div id="resultContainer" class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 hidden">
            <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-1">Hasil Koordinat</h3>
            <p class="text-gray-600">
                <span class="font-medium text-gray-900">Latitude:</span> 
                <span id="latitudeDisplay" class="font-mono text-pink-600">N/A</span>
            </p>
            <p class="text-gray-600">
                <span class="font-medium text-gray-900">Longitude:</span> 
                <span id="longitudeDisplay" class="font-mono text-pink-600">N/A</span>
            </p>
            <p class="text-gray-600 mt-2 text-xs">
                <span class="font-medium text-gray-900">Akurasi:</span> 
                <span id="accuracyDisplay" class="font-mono text-gray-500">N/A</span> meter
            </p>
        </div>
    </div>

    <script src="/public/service.js">

        function showMessage(message, type = 'info') {
            const statusDiv = document.getElementById('statusMessage');
            statusDiv.textContent = message;
            statusDiv.classList.remove('hidden', 'bg-blue-100', 'bg-red-100', 'text-blue-800', 'text-red-800');
            
            if (type === 'info') {
                statusDiv.classList.add('bg-blue-100', 'text-blue-800');
            } else if (type === 'error') {
                statusDiv.classList.add('bg-red-100', 'text-red-800');
            }
        }

        function getLocation() {
            // 1. Cek apakah browser mendukung Geolocation
            if (!navigator.geolocation) {
                showMessage('Maaf, Browser Anda tidak mendukung Geolocation API.', 'error');
                return;
            }

            // 2. Tampilkan pesan status sebelum meminta izin
            showMessage('Meminta izin lokasi...');
            document.getElementById('resultContainer').classList.add('hidden');

            // 3. Panggil API utama
            navigator.geolocation.getCurrentPosition(
                // SUCCESS CALLBACK: Dipanggil jika pengguna memberikan izin
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const acc = position.coords.accuracy;

                    showMessage('Lokasi berhasil didapatkan!', 'info');
                    
                    // Tampilkan hasil di container
                    document.getElementById('latitudeDisplay').textContent = lat.toFixed(6);
                    document.getElementById('longitudeDisplay').textContent = lng.toFixed(6);
                    document.getElementById('accuracyDisplay').textContent = acc.toFixed(2);
                    document.getElementById('resultContainer').classList.remove('hidden');

                    // Di sini Anda akan menjalankan AJAX/Fetch API untuk mengirim lat/lng ke server PHP
                    // Contoh: sendToServer(lat, lng);
                },

                // ERROR CALLBACK: Dipanggil jika terjadi error (pengguna menolak, timeout, dll.)
                (error) => {
                    let errorMessage = 'Terjadi kesalahan tidak dikenal.';
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Akses lokasi ditolak oleh pengguna. Mohon izinkan akses lokasi di browser.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Informasi lokasi tidak tersedia (misalnya GPS nonaktif).';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Permintaan lokasi melebihi batas waktu.';
                            break;
                        // default sudah tertangani di awal
                    }
                    showMessage(`Gagal mendapatkan lokasi: ${errorMessage}`, 'error');
                    document.getElementById('resultContainer').classList.add('hidden');
                },

                // OPTIONS (Pilihan Tambahan - Sangat Penting!)
                {
                    enableHighAccuracy: true, // Mencoba mendapatkan akurasi terbaik (membutuhkan waktu lebih lama dan baterai lebih banyak)
                    timeout: 5000,           // Batas waktu 5 detik sebelum error
                    maximumAge: 0            // Tidak menggunakan hasil cache lokasi sebelumnya
                }
            );
        }
        
        // Memastikan pesan status tersembunyi saat pertama kali dimuat
        showMessage('');
        document.getElementById('statusMessage').classList.add('hidden');

    </script>
</body>
</html>