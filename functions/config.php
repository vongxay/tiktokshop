<?php
    function connect(){
        // ใช้ Environment Variables จาก Railway
        $dbhost = getenv('MARIADB_HOST') ?: getenv('MYSQLHOST') ?: 'localhost';
        $dbuser = getenv('MARIADB_USER') ?: getenv('MYSQLUSER') ?: 'root';
        $dbpass = getenv('MARIADB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '';
        $dbname = getenv('MARIADB_DATABASE') ?: getenv('MYSQLDATABASE') ?: 'railway';
        $dbport = getenv('MARIADB_PORT') ?: getenv('MYSQLPORT') ?: '3306';
        
        try {
            $db = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass);
            // ตั้งให้อ่านภาษาไทยได้
            $db->exec("set names utf8mb4");
            // ตั้งค่าให้แจ้ง Error
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

?>