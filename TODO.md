# TODO: Implementasi Auth Dokter

## Tugas Utama
- [x] Buat halaman `pages/auth-dokter.php` dengan form login, register, dan forgot password khusus dokter
- [x] Modifikasi `includes/database.php` untuk menambahkan method `registerDokter()`
- [x] Tambahkan link "Masuk sebagai Dokter" di `footer.php`

## Detail Implementasi
- [x] Form register dokter dengan field tambahan: Tanggal Lahir, STRV, Exp STRV, SIP, Exp SIP, Pengalaman
- [x] Method `registerDokter()` insert ke m_pengguna (role 'Dokter') dan m_dokter
- [x] Link di footer mengarah ke `?route=auth-dokter`

## Testing
- [x] Test halaman auth-dokter.php
- [x] Verifikasi insert data ke database
- [x] Tambahkan styling tombol seperti "Daftar Sekarang" di header
- [x] Tambahkan route auth-dokter di index.php
- [x] Implementasi dual storage (in-memory + database fallback)
- [x] Ubah layout form register menjadi landscape (horizontal) dan sesuaikan ukuran container
