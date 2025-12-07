<?php
include_once('../functions/init.php');
session_start();
$profile = getCustomerBy($_SESSION['admin_loggedId']);
$customer_id = $profile['id'];

$db = connect();
    // Query ข้อมูล
    $stmt = $db->query("SELECT COUNT(*) AS message_count FROM tb_messages WHERE receiver_id = $customer_id AND status = 0");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ส่งผลลัพธ์กลับในรูปแบบ JSON
    $get_count = 0; // ตั้งค่าเริ่มต้น
    foreach ($result as $row) {
        $get_count = $row['message_count'];
    }
    echo json_encode(['count' => $get_count]);

?>