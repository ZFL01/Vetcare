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

    fetch('/?aksi=location', {
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

function getTimestamp10() {
    const d = new Date();

    const yy = String(d.getFullYear()).slice(2);
    const mm = String(d.getMonth() + 1).padStart(2, "0");
    const dd = String(d.getDate()).padStart(2, "0");
    const hh = String(d.getHours()).padStart(2, "0");
    const mi = String(d.getMinutes()).padStart(2, "0");

    return yy + mm + dd + hh + mi;
}