<?php
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'display': ['Poppins', 'Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: 'hsl(270 70% 50%)',
                            light: 'hsl(270 70% 65%)',
                            dark: 'hsl(270 70% 35%)',
                        },
                        secondary: {
                            DEFAULT: 'hsl(280 60% 60%)',
                            light: 'hsl(280 60% 75%)',
                        },
                        success: 'hsl(270 60% 55%)',
                        warning: 'hsl(270 65% 60%)',
                    },
                    backgroundImage: {
                        'gradient-hero': 'linear-gradient(135deg, hsl(270 70% 50%) 0%, hsl(275 65% 55%) 50%, hsl(280 60% 60%) 100%)',
                        'gradient-primary': 'linear-gradient(135deg, hsl(270 70% 50%) 0%, hsl(270 70% 65%) 100%)',
                        'gradient-secondary': 'linear-gradient(135deg, hsl(280 60% 60%) 0%, hsl(280 60% 75%) 100%)',
                        'gradient-glass': 'linear-gradient(145deg, hsl(270 50% 90% / 0.15), hsl(270 50% 85% / 0.08))',
                    },
                    boxShadow: {
                        'glow': '0 0 40px hsl(270 70% 50% / 0.35)',
                        'hero': '0 20px 60px hsl(270 70% 50% / 0.25)',
                        'card': '0 8px 32px hsl(270 70% 50% / 0.18)',
                    },
                    animation: {
                        'fade-in': 'fade-in 0.5s ease-out',
                        'slide-up': 'slide-up 0.6s ease-out',
                        'gradient-shift': 'gradient-shift 3s ease-in-out infinite',
                        'scale-bounce': 'scale-bounce 2s ease-in-out infinite',
                        'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
                    },
                    keyframes: {
                        'fade-in': {
                            from: { opacity: '0' },
                            to: { opacity: '1' },
                        },
                        'slide-up': {
                            from: { opacity: '0', transform: 'translateY(20px)' },
                            to: { opacity: '1', transform: 'translateY(0)' },
                        },
                        'gradient-shift': {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        'scale-bounce': {
                            '0%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.05)' },
                            '100%': { transform: 'scale(1)' },
                        },
                        'pulse-glow': {
                            '0%, 100%': { boxShadow: '0 0 20px hsl(270 70% 50% / 0.4)' },
                            '50%': { boxShadow: '0 0 40px hsl(270 70% 50% / 0.8)' },
                        },
                    },
                },
            },
        }
    </script>

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
