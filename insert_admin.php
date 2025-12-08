<?php
$dbhost = 'shuttle.proxy.rlwy.net';
$dbport = '24272';
$dbuser = 'railway';
$dbpass = '5lEOu_RchXqQbuYYdyLuDACNy6ys2TZA';
$dbname = 'railway';

try {
    $db = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass);
    $db->exec("set names utf8mb4");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "INSERT INTO tb_customer (customer_id, username, password, fname, lname, mobile, email, store, img_name, address, home, distric, province, company, vip_id, bank_account, bank_name, bank_username, statust_log, created, created_log, `lock`) VALUES (600372, 'Admin', 'b405ac6848e9445d2c6f210bb0f20d7f9af5b01c', 'Admin', 'Tk-shop', '', '', 'Tk-shop', '', '', '', '', '', '', '5', 0, '', '', 1, NOW(), NOW(), 0)";
    
    $db->exec($sql);
    echo "Admin account inserted successfully!\n";
    echo "Username: Admin\n";
    echo "Password: admin+555\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
