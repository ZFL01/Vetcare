<?php
// Start output buffering to prevent header errors
ob_start();

// Base template with HTML head and common elements
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle : 'VetCare - Perawatan Hewan Terbaik'; ?></title>
        <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Platform Telemedicine #1 untuk Hewan - Konsultasi online dengan dokter hewan terpercaya'; ?>" />
    <meta name="author" content="VetCare" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" href="public/tailwind.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
        .font-display {
            font-family: 'Poppins', 'Inter', system-ui, sans-serif;
        }
        html {
            scroll-behavior: smooth;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: hsl(270 70% 50%);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: hsl(270 70% 35%);
        }
    </style>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="public/cat.ico" sizes="16x16 32x32">
    <link rel="shortcut icon" type="image/x-icon" href="public/cat.ico">

    <meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle : 'VetCare'; ?>" />
    <meta property="og:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Platform Telemedicine #1 untuk Hewan'; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="public/placeholder.svg" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo isset($pageTitle) ? $pageTitle : 'VetCare'; ?>" />
    <meta name="twitter:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Platform Telemedicine #1 untuk Hewan'; ?>" />
    <meta name="twitter:image" content="public/placeholder.svg" />
</head>
<body class="bg-gray-50 text-gray-900">
    <?php
    // Content will be included here
    ?>
</body>
</html>
