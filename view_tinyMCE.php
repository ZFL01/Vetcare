<!--
    File: views/artikel_form.php
    Deskripsi: Form untuk menambah/mengedit artikel, menggunakan TinyMCE (Minimalis GPL).
-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tulis Artikel Baru</title>
    <!-- Memuat Tailwind CSS untuk styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Memuat pustaka TinyMCE lokal dari folder assets -->
    <script src="public/assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        /* Memastikan editor memiliki sudut melengkung agar serasi dengan form */
        .tox-tinymce {
            border-radius: 0.75rem !important;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-8 font-sans">

<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-2xl shadow-purple-200/50">
    <h1 class="text-3xl font-bold text-purple-700 mb-6 border-b pb-4">Buat Artikel Baru</h1>

    <form method="POST" action="/?route=article">
        
        <div class="mb-4">
            <label for="judul" class="block text-lg font-semibold mb-2 text-gray-700">Judul Artikel</label>
            <input type="text" id="judul" name="judul" required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-purple-500 focus:border-purple-500 shadow-md transition duration-150">
        </div>

        <div class="mb-6">
            <label for="konten_editor" class="block text-lg font-semibold mb-2 text-gray-700">Isi Artikel (TinyMCE)</label>
            <!-- ID Disesuaikan dengan selector TinyMCE -->
            <textarea id="konten_editor" name="konten_editor"></textarea>
        </div>

        <div class="mb-6">
            <label for="tag" class="block text-lg font-semibold mb-2 text-gray-700">Tag/Kategori</label>
            <select id="tag" name="tag" required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-purple-500 focus:border-purple-500 shadow-md transition duration-150">
                <option value="">Pilih Tag</option>
                <option value="1">Gizi</option>
                <option value="2">Kesehatan Mental</option>
                <option value="3">Olahraga</option>
            </select>
        </div>

        <button type="submit"
            class="w-full bg-purple-600 text-white font-bold py-3 rounded-xl hover:bg-purple-700 transition duration-200 shadow-lg transform hover:scale-[1.01]">
            Simpan Artikel
        </button>
    </form>
</div>

<script>
    tinymce.init({
        selector: '#konten_editor', 
        // Menggunakan lisensi GPL (Gratis/Open Source) untuk menghindari peringatan.
        license_key: 'gpl',
        menubar: false,
        branding: false, 
        statusbar: false,

        // Plugins yang minimalis, tetapi menyertakan 'image'
        plugins: 'advlist autolink lists link image code help',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | image code help',
        height: 500,

        // Konfigurasi Upload Gambar (CRUCIAL)
        images_upload_url: 'api/upload_image.php', 
        
        // Custom handler untuk menangani AJAX dan response JSON
        images_upload_handler: function (blobInfo, progress) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', 'api/upload_image.php'); 

                xhr.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = () => {
                    if (xhr.status === 403) {
                        reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                        return;
                    }

                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }

                    const json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }

                    // Mengembalikan URL publik gambar
                    resolve(json.location);
                };

                xhr.onerror = () => {
                    reject('Image upload failed due to a network error.');
                };

                const formData = new FormData();
                // Nama field harus 'file' agar sesuai dengan $_FILES['file'] di PHP
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            });
        },
        
        image_title: true, 
        automatic_uploads: true,
        file_picker_types: 'image',
        relative_urls: false, 
        remove_script_host: false,
    });
</script>

</body>
</html>
