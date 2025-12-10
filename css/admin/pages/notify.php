<?php

if (isset($_SESSION['admin_username'])) {
    echo '<script> location.replace("?page=home"); </script>';
}
$profile = getCustomerBy($_SESSION['admin_loggedId']);
$username = $profile['username'];
// $status_log = $profile['status_log'];

// ✅ ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
    session_destroy(); // ลบ session
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
    exit;
}

// LINE Notify Token ที่คุณได้รับ
$token = '81iCSjP4ZDejufQ8TaW8RP2nXQQNBuJsynR861CC5Uu';

// ฟังก์ชันในการส่งการแจ้งเตือน
function sendLineNotify($message, $token) {
    $url = 'https://notify-api.line.me/api/notify';
    $data = array('message' => $message);
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Bearer ' . $token,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

// ตรวจสอบว่ามีการส่งข้อมูลมาจาก JavaScript
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ข้อความแจ้งเตือน
    $message = "มีคนเข้าใช้งานเว็บไซต์ tkshop!  " . $username;
    
    // ส่งการแจ้งเตือนไปยัง LINE
    sendLineNotify($message, $token);
}
?>
