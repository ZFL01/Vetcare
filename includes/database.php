<?php
// Database connection configuration for MySQL
class Database {
    private $host = 'localhost';
    private $port = '3306';
    private $dbname = 'klinikh';
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
        $stmt = $this->pdo->prepare("SELECT id_pengguna, email, nama, pass, role FROM m_pengguna WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $password === $user['pass']) { // Note: Using plain text password comparison as per existing schema
            unset($user['pass']); // Remove password from return data
            return $user;
        }
        return false;
    }

    public function registerUser($name, $email, $password) {
        // Check if email already exists
        $stmt = $this->pdo->prepare("SELECT id_pengguna FROM m_pengguna WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email sudah terdaftar'];
        }

        // Insert user (storing plain text password as per existing schema)
        $stmt = $this->pdo->prepare("INSERT INTO m_pengguna (nama, email, pass, role) VALUES (?, ?, ?, 'Member')");
        if ($stmt->execute([$name, $email, $password])) {
            return ['success' => true, 'message' => 'Pendaftaran berhasil. Silakan masuk.'];
        }
        return ['success' => false, 'message' => 'Pendaftaran gagal'];
    }

    public function initiatePasswordReset($email) {
        $stmt = $this->pdo->prepare("SELECT id_pengguna FROM m_pengguna WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $resetToken = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // 6-digit token
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store reset token
            $stmt = $this->pdo->prepare("UPDATE m_pengguna SET reset_token = ?, exp_token = ? WHERE id_pengguna = ?");
            $stmt->execute([$resetToken, $expiry, $user['id_pengguna']]);

            // Send email (placeholder - implement actual email sending)
            $this->sendResetEmail($email, $resetToken);

            return ['success' => true, 'message' => 'Link reset kata sandi telah dikirim ke email Anda.'];
        }
        return ['success' => false, 'message' => 'Email tidak ditemukan'];
    }

    public function verifyResetToken($token) {
        $stmt = $this->pdo->prepare("SELECT id_pengguna, email FROM m_pengguna WHERE reset_token = ? AND exp_token > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function resetPassword($token, $newPassword) {
        $user = $this->verifyResetToken($token);
        if (!$user) {
            return ['success' => false, 'message' => 'Token reset tidak valid atau sudah kadaluarsa'];
        }

        $stmt = $this->pdo->prepare("UPDATE m_pengguna SET pass = ?, reset_token = NULL, exp_token = NULL WHERE id_pengguna = ?");
        if ($stmt->execute([$newPassword, $user['id_pengguna']])) {
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

    // Doctor-specific methods
    public function registerDokter($name, $email, $password, $ttl, $strv, $expStrv, $sip, $expSip, $pengalaman) {
        // Check if email already exists
        $stmt = $this->pdo->prepare("SELECT id_pengguna FROM m_pengguna WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email sudah terdaftar'];
        }

        // Insert user as Dokter
        $stmt = $this->pdo->prepare("INSERT INTO m_pengguna (nama, email, pass, role) VALUES (?, ?, ?, 'Dokter')");
        if ($stmt->execute([$name, $email, $password])) {
            $userId = $this->pdo->lastInsertId();

            // Insert doctor details
            $stmt = $this->pdo->prepare("INSERT INTO m_dokter (id_dokter, nama_dokter, ttl, STRV, exp_strv, SIP, exp_SIP, pengalaman) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$userId, $name, $ttl, $strv, $expStrv, $sip, $expSip, $pengalaman])) {
                return ['success' => true, 'message' => 'Pendaftaran dokter berhasil. Silakan masuk.'];
            } else {
                // Rollback user insertion if doctor details fail
                $stmt = $this->pdo->prepare("DELETE FROM m_pengguna WHERE id_pengguna = ?");
                $stmt->execute([$userId]);
                return ['success' => false, 'message' => 'Pendaftaran gagal'];
            }
        }
        return ['success' => false, 'message' => 'Pendaftaran gagal'];
    }

    // Simple in-memory storage for demo (not using database)
    private static $doctors = [];

    public function registerDokterSimple($name, $email, $password, $ttl, $strv, $expStrv, $sip, $expSip, $pengalaman) {
        // Check if email already exists
        foreach (self::$doctors as $doctor) {
            if ($doctor['email'] === $email) {
                return ['success' => false, 'message' => 'Email sudah terdaftar'];
            }
        }

        // Create doctor data
        $doctor = [
            'id' => count(self::$doctors) + 1,
            'name' => $name,
            'email' => $email,
            'password' => $password, // In production, hash this
            'ttl' => $ttl,
            'strv' => $strv,
            'exp_strv' => $expStrv,
            'sip' => $sip,
            'exp_sip' => $expSip,
            'pengalaman' => $pengalaman,
            'role' => 'Dokter'
        ];

        self::$doctors[] = $doctor;
        return ['success' => true, 'message' => 'Pendaftaran dokter berhasil. Silakan masuk.'];
    }

    public function authenticateDokterSimple($email, $password) {
        foreach (self::$doctors as $doctor) {
            if ($doctor['email'] === $email && $doctor['password'] === $password) {
                $user = $doctor;
                unset($user['password']); // Remove password from return data
                return $user;
            }
        }
        return false;
    }
}
?>
