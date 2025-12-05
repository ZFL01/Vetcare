<?php
// Database connection configuration for MySQL

class Database{
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
                custom_log("Database connection failed: " . $e->getMessage(), LOG_TYPE::ERROR);
                throw new \RuntimeException("Tidak dapat tersambung ke database");
            }
        }
        return self::$pdo;
    }
}

?>