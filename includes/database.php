<?php
// Database connection configuration for MySQL
class Database
{
    private static $host = 'localhost';
    private static $port = '3306';
    private static $dbname = 'klinikh';
    private static $user = 'root';
    private static $password = '';
    private static ?PDO $pdo = null;

    private function __construct(){}

    public static function getConnection()
    {
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname . ";charset=utf8mb4";
                self::$pdo = new PDO($dsn, self::$user, self::$password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    // User authentication methods
    public function authenticateUser($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT id, email, full_name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Mengubah nama kolom yang dikembalikan
            $authenticatedUser = [
                'id' => $user['id_pengguna'],
                'email' => $user['email'],
                'full_name' => $user['nama'],
            ];
            return $authenticatedUser;
        }
        return false;
    }

    public function registerUser($name, $email, $password)
    {
    public function registerUser($name, $email, $password)
    {
        // Check if email already exists
        $stmt = $this->pdo->prepare("SELECT id_pengguna FROM m_pengguna WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email sudah terdaftar'];
        }

        // Simpan password sebagai PLAIN TEXT (TIDAK AMAN!)
        $plainPassword = $password;

        // Insert user ke tabel m_pengguna
        $stmt = $this->pdo->prepare("INSERT INTO m_pengguna (email, pass, nama) VALUES (?, ?, ?)");
        if ($stmt->execute([$email, $plainPassword, $name])) {
            return ['success' => true, 'message' => 'Pendaftaran berhasil. Silakan masuk.'];
        }
        return ['success' => false, 'message' => 'Pendaftaran gagal'];
    }

    public function initiatePasswordReset($email)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $resetToken = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $userId = $user['id_pengguna'];

            $stmt = $this->pdo->prepare("UPDATE m_pengguna SET reset_token = ?, exp_token = ? WHERE id_pengguna = ?");
            if ($stmt->execute([$resetToken, $expiry, $userId])) {
                $this->sendResetEmail($email, $resetToken);
                return ['success' => true, 'message' => 'Link reset kata sandi telah dikirim ke email Anda.'];
            }
            return ['success' => false, 'message' => 'Gagal menyimpan token reset.'];
        }
        return ['success' => false, 'message' => 'Email tidak ditemukan'];
    }

    public function verifyResetToken($token)
    {
        $stmt = $this->pdo->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            return ['id' => $user['id_pengguna'], 'email' => $user['email']];
        }
        return false;
    }

    public function resetPassword($token, $newPassword)
    {
    public function resetPassword($token, $newPassword)
    {
        $user = $this->verifyResetToken($token);
        if (!$user) {
            return ['success' => false, 'message' => 'Token reset tidak valid atau sudah kadaluarsa'];
        }

        // Simpan password sebagai PLAIN TEXT (TIDAK AMAN!)
        $plainPassword = $newPassword;
        $userId = $user['id'];

        $stmt = $this->pdo->prepare("UPDATE m_pengguna SET pass = ?, reset_token = NULL, exp_token = NOW() WHERE id_pengguna = ?");
        if ($stmt->execute([$plainPassword, $userId])) {
            return ['success' => true, 'message' => 'Kata sandi berhasil diubah'];
        }
        return ['success' => false, 'message' => 'Gagal mengubah kata sandi'];
    }

    private function sendResetEmail($email, $token)
    {
    private function sendResetEmail($email, $token)
    {
        // Implement email sending logic here
        error_log("Password reset token for $email: $token");
    }
}
?>