<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];
    $sender_id = $_SESSION['user_id'];
    $receiver_id = isset($_GET['user']) ? intval($_GET['user']) : 0;

    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $fileName = null;
    if (!empty($_FILES['file'])) {
        $uploadDir = 'uploads/chat/';
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

        // ตรวจสอบว่าผู้ใช้เคยแชทมาก่อนหรือไม่
        $stmt = $db->prepare("SELECT COUNT(*) FROM tb_messages WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $messageCount = $stmt->fetchColumn();

        // แทรกข้อความของลูกค้า
        $stmt = $db->prepare("INSERT INTO tb_messages (user_id, sender_id, receiver_id, type, message, file_name)
                            VALUES (:user_id, :sender_id, :receiver_id, :type, :message, :file_name)");
        $stmt->execute([
            'user_id' => $user_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'type' => $fileName ? 'file' : 'text',
            'message' => $message,
            'file_name' => $fileName
        ]);

        echo json_encode(['success' => true, 'message' => 'Message sent!']);
    } else {


        $stmt = $db->prepare("INSERT INTO tb_messages (user_id, sender_id, receiver_id, message) VALUES (:user_id, :sender_id, :receiver_id, :message)");
        $stmt->execute([
            'user_id' => $user_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,

        ]);
        // ✅ ตอบกลับทันทีเมื่อมีคำว่า "สวัสดี"
        if (mb_stripos($message, 'สวัสดี') !== false) {
            $autoReply = "
            สวัสดีค่ะ 😊 มีอะไรให้เราช่วยไหมคะ?
            ";

            $stmt = $db->prepare("INSERT INTO tb_messages (user_id, sender_id, receiver_id, type, message)
                            VALUES (:user_id, :sender_id, :receiver_id, 'text', :message)");
            $stmt->execute([
                'user_id' => 17,
                'sender_id' => 17, // admin
                'receiver_id' => $sender_id,
                'message' => $autoReply
            ]);
        }
        // ✅ ตอบกลับอัตโนมัติวันละครั้ง (ถ้าไม่ใช่ "สวัสดี")
        elseif (isFirstMessageToday($db, $user_id)) {
            $autoReply = "
            สวัสดีค่ะ 😊 มีอะไรให้เราช่วยไหมคะ?";


            $stmt = $db->prepare("INSERT INTO tb_messages (user_id, sender_id, receiver_id, type, message)
                            VALUES (:user_id, :sender_id, :receiver_id, 'text', :message)");
            $stmt->execute([
                'user_id' => $user_id,
                'sender_id' => 1,
                'receiver_id' => $sender_id,
                'message' => $autoReply
            ]);
        }


        echo json_encode(['success' => true, 'message' => 'Message sent!']);
    }
}
// ✅ ฟังก์ชันเช็คว่าเคยได้รับ auto-reply วันนี้หรือยัง
function isFirstMessageToday($db, $user_id)
{
    $stmt = $db->prepare("SELECT COUNT(*) FROM tb_messages 
                          WHERE user_id = 17 
                          AND sender_id = 17
                          AND DATE(created_at) = CURDATE()");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchColumn() == 0;
}
