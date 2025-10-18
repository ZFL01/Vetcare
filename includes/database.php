<?php
// Database connection configuration for Supabase
class Database {
    private $host = 'db.fagopqqedwafpkuqpohk.supabase.co';
    private $port = '5432';
    private $dbname = 'postgres';
    private $user = 'postgres';
    private $password = 'your_password_here'; // Replace with actual password
    private $pdo;

    public function __construct() {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
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
        $stmt = $this->pdo->prepare("SELECT id, email, full_name FROM public.profiles WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $this->getUserPassword($user['id']))) {
            return $user;
        }
        return false;
    }

    public function registerUser($name, $email, $password) {
        // Check if email already exists
        $stmt = $this->pdo->prepare("SELECT id FROM public.profiles WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $this->pdo->prepare("INSERT INTO public.profiles (id, email, full_name) VALUES (gen_random_uuid(), ?, ?)");
        if ($stmt->execute([$email, $name])) {
            $userId = $this->pdo->lastInsertId();
            // Store password hash (in a real app, you'd use Supabase auth)
            $this->storeUserPassword($userId, $hashedPassword);
            return ['success' => true, 'message' => 'Registration successful'];
        }
        return ['success' => false, 'message' => 'Registration failed'];
    }

    public function initiatePasswordReset($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM public.profiles WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $resetToken = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store reset token (you'd typically have a password_resets table)
            $stmt = $this->pdo->prepare("UPDATE public.profiles SET updated_at = ? WHERE id = ?");
            $stmt->execute([$expiry, $user['id']]);

            // Send email (placeholder - implement actual email sending)
            $this->sendResetEmail($email, $resetToken);

            return ['success' => true, 'message' => 'Password reset link sent to your email'];
        }
        return ['success' => false, 'message' => 'Email not found'];
    }

    private function getUserPassword($userId) {
        // In a real implementation, you'd have a separate table for passwords
        // For demo purposes, returning a placeholder
        return password_hash('demo_password', PASSWORD_DEFAULT);
    }

    private function storeUserPassword($userId, $hashedPassword) {
        // Store password hash securely
        // In production, use Supabase Auth instead of custom password storage
    }

    private function sendResetEmail($email, $token) {
        // Implement email sending logic here
        // For demo, just log the token
        error_log("Password reset token for $email: $token");
    }
}
?>
