<?php
include 'includes/header.php';


// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}
$_SESSION['u_id'];
// รับข้อมูลจากผู้ใช้
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_SESSION['u_id'];
    $u_id = $_SESSION['u_id'];
    $message = $_POST['message'];
    $status = 1;
    $db = connect();
    $fileName = null;
    if (!empty($_FILES['file'])) {
        $uploadDir = '../uploads/chat/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $originalFileName = $_FILES['file']['name'];
            $fileName = time() . '_' . basename($originalFileName); // ป้องกันชื่อไฟล์ซ้ำ
            $destPath = $uploadDir . $fileName;

            // ตรวจสอบชนิดไฟล์และขนาด
            $allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            $maxFileSize = 5 * 1024 * 1024; // 5 MB

            if ($_FILES['file']['size'] > $maxFileSize) {
                echo json_encode(['success' => false, 'error' => 'File size exceeds 5MB.']);
                exit;
            }

            if (!in_array($_FILES['file']['type'], $allowedFileTypes)) {
                echo json_encode(['success' => false, 'error' => 'Invalid file type.']);
                exit;
            }

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                echo json_encode(['success' => false, 'error' => 'Failed to upload file.']);
                exit;
            }
        }

        if (empty($message) && !$fileName) {
            echo json_encode(['success' => false, 'error' => 'Please enter a message or upload a file.']);
            exit;
        }

        $db = connect();
        if (!$db) {
            die("Database connection failed.");
        }

  
        // แทรกข้อความ (status = 0 คือยังไม่อ่าน)
        $stmt = $db->prepare("INSERT INTO tb_messages (user_id, sender_id, receiver_id, type, message, file_name, status)
                            VALUES (:user_id, :sender_id, :receiver_id, :type, :message, :file_name, 0)");
        $stmt->execute([
            'user_id' => $user_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'type' => $fileName ? 'file' : 'text',
            'message' => $message,
            'file_name' => $fileName
        ]);

        echo json_encode(['success' => true, 'message' => 'Message sent!']);
    }else{
    // บันทึกข้อความลงฐานข้อมูล (status = 0 หมายถึงยังไม่อ่าน)
    $stmt = $db->prepare("INSERT INTO tb_messages (user_id, sender_id, receiver_id, message, status) VALUES (:user_id, :sender_id, :receiver_id, :message, 0)");
    $stmt->execute(['user_id' => $user_id, 'sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'message' => $message]);
    echo json_encode(['success' => true, 'message' => 'Message sent!']);
    }
}
?>
