<?php
    function connect(){
        // Railway MariaDB Connection
        $dbhost = 'shuttle.proxy.rlwy.net';
        $dbport = '24272';
        $dbuser = 'railway';
        $dbpass = '5lEOu_RchXqQbuYYdyLuDACNy6ys2TZA';
        $dbname = 'railway';
        
        try {
            $db = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass, [
                PDO::ATTR_TIMEOUT => 10
            ]);
            // ตั้งให้อ่านภาษาไทยได้
            $db->exec("set names utf8mb4");
            // ตั้งค่าให้แสดง Error
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            // Log error แต่ไม่แสดงรายละเอียดให้ user
            error_log("Database connection failed: " . $e->getMessage());
            die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . $e->getMessage());
        }
    }

?>