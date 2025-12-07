<?php
/**
 * Database Connection Configuration
 * ใช้ Environment Variables หรือค่า default สำหรับ Railway
 */
function connect() {
    // Railway MariaDB connection settings
    $dbhost = getenv('MARIADB_PUBLIC_HOST') ?: getenv('MARIADB_HOST') ?: 'shuttle.proxy.rlwy.net';
    $dbport = getenv('MARIADB_PUBLIC_PORT') ?: getenv('MARIADB_PORT') ?: '24272';
    $dbuser = getenv('MARIADB_USER') ?: 'railway';
    $dbpass = getenv('MARIADB_PASSWORD') ?: '5lEOu_RchXqQbuYYdyLuDACNy6ys2TZA';
    $dbname = getenv('MARIADB_DATABASE') ?: 'railway';
    
    try {
        $dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $db = new PDO($dsn, $dbuser, $dbpass, $options);
        return $db;
        
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        die("Database connection failed. Please try again later.");
    }
}
?>