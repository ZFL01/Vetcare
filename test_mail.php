<?php
require_once 'includes/userService.php';
require_once 'src/config/config.php';

echo "<h2>Sedang mengetes email...</h2>";

// Tes kirim email langsung tanpa lewat chat
$hasil = emailService::sendCustomEmail(
    'tes_user@example.com', 
    'Cek Masuk Gak?', 
    '<h1>Halo!</h1><p>Ini tes dari script manual.</p>'
);

if ($hasil[0]) {
    echo "Status: <b>BERHASIL dikirim (menurut PHP)</b>.<br>";
    echo "Silakan cek Mailpit sekarang.";
} else {
    echo "Status: <b>GAGAL</b>.<br>";
    echo "Pesan Error: " . $hasil[1];
}
?>