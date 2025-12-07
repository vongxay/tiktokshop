<?php
    function connect(){
        // ใช้ Environment Variables จาก Railway
        $dbhost = getenv('MARIADB_HOST') ?: getenv('MYSQLHOST') ?: 'localhost';
        $dbuser = getenv('MARIADB_USER') ?: getenv('MYSQLUSER') ?: 'root';
        $dbpass = getenv('MARIADB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '';
        $dbname = getenv('MARIADB_DATABASE') ?: getenv('MYSQLDATABASE') ?: 'railway';
        $dbport = getenv('MARIADB_PORT') ?: getenv('MYSQLPORT') ?: '3306';
        
        $db = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass);
        // ຕັ້ງໃຫ້ອ່ານພາສາລາວໄດ້
        $db->exec("set names utf8mb4");
        // ຕັ້ງຄ່າໃຫ້ມັນຟ້ອງ Error ໃຫ້ໃນເວລາທີ່ເຮົາຂຽນຜິດ
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // ສົ່ງຄ່າຂອງຕົວເຊື່ອມຕໍ່ຖານຂໍ້ມູນກັບ
        return $db;

    }

?>