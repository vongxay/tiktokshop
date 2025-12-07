<?php
include_once('../functions/init.php');
session_start();
  $profile = getCustomerBy($_SESSION['admin_loggedId']);
  $customer_id = $profile['id'];
  
    $db = connect();
    $stmt2 = $db->query("SELECT COUNT(user_id) as count FROM tb_messages WHERE status = 0 AND user_id = $customer_id");
    $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result2 as $row2) {
    $count_status = $row2['count'];  
    // $_SESSION['count_status'] = $count_status;

    }
    // ส่งผลลัพธ์กลับในรูปแบบ JSON
    // header('Content-Type: application/json');
    echo json_encode($count_status);
?>