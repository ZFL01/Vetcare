<?php
// Cek jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['user']) && $_SESSION['user']->getRole() === 'Admin') {
    header('Location: ?access=dashboard');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin-login'])) {
    // Ambil inputan
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email) {
        setFlash('error', 'Email tidak valid!');
        header('Location: ?route=auth');
        exit();
    }
    $obj = new DTO_pengguna(email: $email, pass: $password);

    $hasil = userService::login($obj);
    if ($hasil[0]) {
        if ($obj->getRole() === 'Admin') {
            $_SESSION['user'] = $obj;
            header('Location: ?access=dashboard');
            exit;
        } else {
            setFlash('error', 'Anda tidak punya akses! <br>Silahkan login di sini');
            custom_log('Mencoba akses Admin dengan email : ' . $email, LOG_TYPE::ROUTING);
            header('Location: ../?route=auth');
            exit;
        }
    } else {
        setFlash('error', $hasil[1]);
        header('Location: ../?route=auth');
        exit;
    }
}

$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VetCare Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="bg-gradient-to-br from-purple-50 via-violet-50 to-fuchsia-50 min-h-screen flex items-center justify-center">
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4">
        <div class="text-center mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-violet-500 p-4 rounded-full mx-auto mb-4 w-fit">
                <span class="text-3xl">🐾</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">VetCare Admin</h1>
            <p class="text-gray-600 mt-2">Masuk ke dashboard admin</p>
        </div>

        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
            </div>
            <button type="submit" name="admin-login"
                class="w-full bg-gradient-to-r from-purple-500 to-violet-600 text-white py-3 rounded-lg font-medium hover:from-purple-600 hover:to-violet-700 transition mt-6">
                Masuk
            </button>
        </form>
    </div>
</body>

</html>