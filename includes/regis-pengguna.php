<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Dokter</title>
    </head>
<body>
    <form action="control-pengguna.php" method="POST">
        <h2>Registrasi Akun Dokter (Tahap 1)</h2>
        
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" name="register">Lanjut ke Tahap 2</button>
    </form>
</body>
</html><!DOCTYPE html>