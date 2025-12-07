<?php
    function connect(){
        // ใช้ Public URL สำหรับ Railway MariaDB
        $dbhost = getenv('MARIADB_PUBLIC_HOST') ?: getenv('MARIADB_HOST') ?: 'shuttle.proxy.rlwy.net';
        $dbport = getenv('MARIADB_PUBLIC_PORT') ?: getenv('MARIADB_PORT') ?: '24272';
        $dbuser = getenv('MARIADB_USER') ?: 'railway';
        $dbpass = getenv('MARIADB_PASSWORD') ?: '';
        $dbname = getenv('MARIADB_DATABASE') ?: 'railway';
        
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