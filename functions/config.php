<?php
    function connect(){
        $dbhost = 'sql103.infinityfree.com';
        $dbuser = 'if0_40647466';
        $dbpass = 'DkBV3VVPv631X';
        $dbname = 'if0_40647466_db_tkshop';
        $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        // ຕັ້ງໃຫ້ອ່ານພາສາລາວໄດ້
        $db->exec("set names utf8mb4");
        // ຕັ້ງຄ່າໃຫ້ມັນຟ້ອງ Error ໃຫ້ໃນເວລາທີ່ເຮົາຂຽນຜິດ
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // ສົ່ງຄ່າຂອງຕົວເຊື່ອມຕໍ່ຖານຂໍ້ມູນກັບ
        return $db;

    }

?>