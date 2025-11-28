function getUserLocation(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(
            successCallback, errorCallback, {enableHighAccuracy: true, timeout:5000, maximumAge:0}
        );
    }else{
        alert('Geolokasi tidak didukung di browser ini');
    }
}

function successCallback(position){
    const lat = position.coords.latitude;
    const long = position.coords.longitude;

    console.log(`Lokasi Anda: Lat=${lat}, Long=${long}`);

    fetch('/?action=location', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({latitude: lat, longitude: long})
    })
    .then(response=>response.json())
    .then(data=>{
        console.log('lokasi tersimpan');
    });
}

function errorCallback(error){
    let msg = 'Terjadi error: ';

    switch(error.code){
        case error.PERMISSION_DENIED:
            msg += 'Akses ditolak oleh pengguna';
            break;
        case error.POSITION_UNAVAILABLE:
            msg += 'Informasi lokasi tidak tersedia (jaringan atau GPS bermasalah)';
            break;
        case error.TIMEOUT:
            msg += 'Waktu pengambilan lokasi habis';
            break;
        case error.UNKNOWN_ERROR:
            msg += 'Error tidak diketahui';
            break;
    }
    console.log(msg);
}