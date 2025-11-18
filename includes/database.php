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
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

//initiate connection for CouchDB
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
class CouchDB{
    private static $DB_URL = 'http://127.0.0.1:5984/chat_messages/';
    private static $DB_AUTH = ['Admin', '12345'];
    private static $clientInstance = null;
    
    static function getConn(){
        if(self::$clientInstance===null){
            try{
                self::$clientInstance = new Client([
                    'base_url'=>self::$DB_URL,
                    'auth'=>self::$DB_AUTH,
                    //memastikan CouchDB merespon dengan JSON
                    'headers'=>[
                        'Accept'=> 'application/json',
                        'Content-Type'=>'application/json'
                    ],
                    //opsional, untuk koneksi yang lambat
                    'timeout'=>5.0
                ]);
            }catch(\Exception $e){
                error_log("CouchDB Connection Error : ".$e->getMessage());
                throw new \RuntimeException("Tidak dapat tersambung ke CouchDB");
            }
        }
        return self::$clientInstance;
    }
    private function __construct(){}
    private function __clone(){}
}

?>