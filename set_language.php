<?php
session_start();
include('languages.php');
// ตรวจสอบว่ามีการส่งค่าภาษามาหรือไม่
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang']; // เก็บค่าภาษาใน Session
}

// ย้อนกลับไปยังหน้าก่อนหน้า
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>