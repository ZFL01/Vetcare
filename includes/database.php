<?php
// Database connection configuration for MySQL
class Database {
    private $host = 'localhost';
    private $port = '3306';
    private $dbname = 'vetcare_db';
    private $user = 'root';
    private $password = ''; 
    private $pdo;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }

    // User authentication methods
    public function authenticateUser($email, $password) {
        $stmt = $this->pdo->prepare("SELECT id, email, full_name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']); // Remove password hash from return data
            return $user;
        }
        return false;
    }

    public function registerUser($name, $email, $password) {
        // Check if email already exists
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email sudah terdaftar'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password_hash, full_name) VALUES (?, ?, ?)");
        if ($stmt->execute([$email, $hashedPassword, $name])) {
            return ['success' => true, 'message' => 'Pendaftaran berhasil. Silakan masuk.'];
        }
        return ['success' => false, 'message' => 'Pendaftaran gagal'];
    }

    public function initiatePasswordReset($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $resetToken = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store reset token
            $stmt = $this->pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
            $stmt->execute([$resetToken, $expiry, $user['id']]);

            // Send email (placeholder - implement actual email sending)
            $this->sendResetEmail($email, $resetToken);

            return ['success' => true, 'message' => 'Link reset kata sandi telah dikirim ke email Anda.'];
        }
        return ['success' => false, 'message' => 'Email tidak ditemukan'];
    }

    public function verifyResetToken($token) {
        $stmt = $this->pdo->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function resetPassword($token, $newPassword) {
        $user = $this->verifyResetToken($token);
        if (!$user) {
            return ['success' => false, 'message' => 'Token reset tidak valid atau sudah kadaluarsa'];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        if ($stmt->execute([$hashedPassword, $user['id']])) {
            return ['success' => true, 'message' => 'Kata sandi berhasil diubah'];
        }
        return ['success' => false, 'message' => 'Gagal mengubah kata sandi'];
    }

    private function sendResetEmail($email, $token) {
        // Implement email sending logic here
        // For demo, just log the token
        error_log("Password reset token for $email: $token");

        // Placeholder: In production, send actual email with reset link
        // Example: mail($email, 'Reset Password', "Click here to reset: http://yourdomain.com/reset-password?token=$token");
    }
}
?>
