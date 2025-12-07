<?php
    function connect(){
        // ใช้ค่าจาก Environment Variables (Railway) หรือค่า default
        $dbhost = getenv('MARIADB_PUBLIC_HOST') ?: getenv('MARIADB_HOST') ?: 'localhost';
        $dbport = getenv('MARIADB_PUBLIC_PORT') ?: getenv('MARIADB_PORT') ?: '3306';
        $dbuser = getenv('MARIADB_USER') ?: 'root';
        $dbpass = getenv('MARIADB_PASSWORD') ?: '';
        $dbname = getenv('MARIADB_DATABASE') ?: 'railway';
        
        try {
            $db = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass);
            // ตั้งให้อ่านภาษาไทยได้
            $db->exec("set names utf8mb4");
            // ตั้งค่าให้แสดง Error
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            // Log error แต่ไม่แสดงรายละเอียดให้ user
            error_log("Database connection failed: " . $e->getMessage());
            die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้ กรุณาลองใหม่อีกครั้ง");
        }
    }

?>