<?php
/**
 * File: pages/chat-pasien.php
 * Halaman untuk pasien mengajukan pertanyaan kepada dokter
 */
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../includes/DAO_others.php';
requireLogin(false);

$objUser = $_SESSION['user'];

$pageTitle = "Tanya Dokter - VetCare";

$dataTag = DAO_Tag::getAllTags();

// Handle form submission
if (isset($_POST['submit_pertanyaan'])) {
    $judul = clean($_POST['judul']);
    $isi = clean($_POST['isi']);
    $kategori = clean($_POST['kategori']);
    $kategoriId=null;

    $found=null;
    foreach($dataTag as $tag){
        if (strtolower($tag->getTag()) === strtolower($kategori)){
            $found=$tag;
            break;
        }
    }
    if($found !==null){
        $kategoriId=$found->getIdTag();
    }else{
        $newTagId=DAO_Tag::insertTag($kategori);
        if ($newTagId !== false){
            $kategoriId=$newTagId;
        } else{
            setFlash('error', 'Gagal menambahkan kategori baru. Silakan coba lagi.');
        }
    }

    // Validation
    if (empty($judul) || empty($isi) || empty($kategoriId)) {
        setFlash('error', 'Semua field harus diisi!');
    } else {
        $tanya = new DTO_tanyajawab();
        $tanya->forCreateAsk($objUser, $judul, $isi, $kategoriId);
        $hasil = DAO_Tanya::insertAsk($tanya);

        if ($hasil) {
            setFlash('success', 'Pertanyaan berhasil dikirim! Dokter akan segera menjawab.');
            header('Location: ' . $_SERVER['PHP_SELF'] . '?route=tanya-dokter');
            exit();
        } else {
            setFlash('error', 'Gagal mengirim pertanyaan. Silakan coba lagi.');
        }
    }
}
// Get flash message
$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7C3AED',
                        secondary: '#A855F7',
                        accent: '#C084FC',
                        dark: '#581C87',
                    }
                }
            }
        }
    </script>
    <style>
        .question-form {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-header p {
            opacity: 0.9;
            font-size: 16px;
        }

        .form-body {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #7C3AED;
            background: white;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 180px;
            line-height: 1.6;
        }

        .btn-submit {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #7C3AED 0%, #A855F7 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-box h3 {
            color: #374151;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box ul {
            color: #6b7280;
            line-height: 1.6;
        }

        .info-box li {
            margin-bottom: 5px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .info-box li:before {
            content: "✓";
            color: #16a34a;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .form-body {
                padding: 25px;
            }

            .form-header {
                padding: 20px;
            }

            .form-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-12 pt-20">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8" style="padding-top: 3%;">
            <a href="<?php echo BASE_URL; ?>" class="inline-flex items-center text-primary hover:text-secondary transition-colors">
                <span class="text-2xl mr-2">🏥</span>
                <span class="text-xl font-bold">VetCare</span>
            </a>
        </div>

        <!-- Question Form -->
        <div class="question-form">
            <div class="form-header">
                <h1>💬 Tanya Dokter</h1>
                <p>Ajukan pertanyaan kesehatan hewan Anda kepada dokter spesialis</p>
            </div>

            <div class="form-body">
                <?php if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                        <span><?php echo $flash['type'] == 'error' ? '❌' : '✅'; ?></span>
                        <?php echo $flash['message']; ?>
                    </div>
                <?php endif; ?>

                <div class="info-box">
                    <h3>📋 Informasi Penting</h3>
                    <ul>
                        <li>Pertanyaan Anda akan dijawab oleh dokter hewan berpengalaman</li>
                        <li>Jawaban biasanya diberikan dalam 24-48 jam</li>
                        <li>Berikan detail yang lengkap tentang kondisi hewan Anda</li>
                        <li>Pastikan email Anda aktif untuk menerima notifikasi</li>
                    </ul>
                </div>

                <form method="POST" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori Pertanyaan *</label>
                        <input type="text" id="kategori" name="kategori" placeholder="Ketik kategori atau pilih dari saran">
                        <input type="hidden" id="kategoriId" name="kategoriId">
                        <div id="kategori-suggestions" class="relative">
                            <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 hidden"></ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="judul">Judul Pertanyaan *</label>
                        <input type="text" id="judul" name="judul" placeholder="Berikan judul yang jelas dan singkat" required maxlength="150">
                    <div id='judul-jml-karakter' class='text-right text-sm text-gray-500 mb-1'></div>
                    </div>

                    <div class="form-group">
                        <label for="isi">Detail Pertanyaan *</label>
                        <textarea id="isi" name="isi" placeholder="Jelaskan detail pertanyaan Anda dengan lengkap. Sertakan:
• Jenis hewan dan ras
• Usia hewan
• Gejala yang dialami
• Riwayat kesehatan sebelumnya (opsional)
• Perawatan yang sudah dilakukan (jika sudah)" required maxlength="1000"></textarea>
                    <div id='isi-jml-karakter' class='text-right text-sm text-gray-500 mb-1'></div>
                    </div>
                    <button type="submit" name="submit_pertanyaan" class="btn-submit">
                        🚀 Kirim Pertanyaan
                    </button>
                </form>

                <div class="text-center mt-6 text-sm text-gray-600">
                    <p>Butuh bantuan segera? <a href="tel:+6281234567890" class="text-primary hover:text-secondary font-medium">Hubungi Emergency: +62 812-3456-7890</a></p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>&copy; 2024 VetCare. Hak Cipta Dilindungi.</p>
        </div>
    </div>

    <script>
        // Auto-resize textarea
        const judul = document.getElementById('judul');
        const hitunganJudul = document.getElementById('judul-jml-karakter');
        const maxjudul= judul.getAttribute('maxlength');
        
        const textarea = document.getElementById('isi');
        const isiHitungan = document.getElementById('isi-jml-karakter');
        const maxIsi= textarea.getAttribute('maxlength');

        function updateCharacterCount(input, countElement, maxChars) {
            const remaining = maxChars - input.value.length;
            countElement.textContent = `${remaining} karakter tersisa`;
        }
        updateCharacterCount(judul, hitunganJudul, maxjudul);
        updateCharacterCount(textarea, isiHitungan, maxIsi);

        judul.addEventListener('input', ()=> {
            updateCharacterCount(judul, hitunganJudul, maxjudul);
        });

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
            updateCharacterCount(textarea, isiHitungan, maxIsi);
        });

        // Form validation enhancement
        document.querySelector('form').addEventListener('submit', function(e) {
            const judul = document.getElementById('judul').value.trim();
            const isi = document.getElementById('isi').value.trim();

            if (judul.length < 10) {
                alert('Judul pertanyaan minimal 10 karakter');
                e.preventDefault();
                return false;
            }

            if (isi.length < 50) {
                alert('Detail pertanyaan minimal 50 karakter untuk membantu dokter memahami kondisi hewan Anda');
                e.preventDefault();
                return false;
            }
        });
    </script>
    <script>
        const kategoriInput = document.getElementById('kategori');
        const kategoriId = document.getElementById('kategoriId');
        const suggestionsBox = document.getElementById('kategori-suggestions').querySelector('ul');
        const kategoriList = <?php echo json_encode($dataTag); ?>;

            kategoriInput.addEventListener('input', function(){
                const inputValue = kategoriInput.value.toLowerCase();
            suggestionsBox.innerHTML = '';
            kategoriId.value='';
            if (inputValue.length===0){
                suggestionsBox.classList.add('hidden');
                return;
            }
            console.log(kategoriList);
            const filteredSuggest=kategoriList.filter(kategori => kategori.tag.toLowerCase().includes(inputValue));
            if (filteredSuggest.length>0){
                filteredSuggest.forEach(suggestion=>{
                    const li = document.createElement('li');
                    li.textContent = suggestion.tag;
                    li.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100');
                    li.addEventListener('click', ()=>{
                        kategoriInput.value = suggestion.tag;
                        kategoriId.value = suggestion.idTag;
                        suggestionsBox.classList.add('hidden');
                    });
                    suggestionsBox.appendChild(li);
                });
                suggestionsBox.classList.remove('hidden');
            } else{
                suggestionsBox.classList.add('hidden');
            }
            });
    </script>
</body>
</html>