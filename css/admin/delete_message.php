<?php
// delete_message.php (เพิ่มสิทธิ์แอดมินในการลบข้อความคู่สนทนา)
session_start();
// ตรวจสอบชื่อไฟล์ include ให้ถูกต้อง
include_once('../functions/db.php'); 

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_loggedId'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized or Admin ID missing.']);
    exit;
}

$admin_id = (int)$_SESSION['admin_loggedId']; 
$message_id = isset($_POST['message_id']) ? (int)$_POST['message_id'] : 0;
// ⭐ ดึง ID คู่สนทนา (u_id) มาด้วยเพื่อใช้ในการตรวจสอบสิทธิ์
$u_id = (int)($_SESSION['u_id'] ?? 0); 


if ($message_id <= 0 || $u_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing message ID or Partner ID.']);
    exit;
}

try {
    $db = connect();

    // 1. SELECT เพื่อตรวจสอบความเป็นเจ้าของ/คู่สนทนา และดึงชื่อไฟล์เพื่อลบ
    $stmt_check = $db->prepare("
        SELECT file_name, user_id, sender_id 
        FROM tb_messages 
        WHERE id = :id 
          -- ⭐⭐ เงื่อนไขสำหรับแอดมิน ⭐⭐
          -- 1. ลบข้อความที่ตัวเองส่ง (sender_id/user_id = แอดมิน)
          -- 2. หรือ ลบข้อความที่คู่สนทนาส่ง (sender_id/user_id = คู่สนทนา)
          AND (sender_id = :admin_id OR user_id = :admin_id 
               OR sender_id = :u_id OR user_id = :u_id)
    ");
    $stmt_check->execute([
        ':id' => $message_id, 
        ':admin_id' => $admin_id,
        ':u_id' => $u_id // ID ของคู่สนทนา (ลูกค้า)
    ]);
    $message_data = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$message_data) {
        http_response_code(403);
        echo json_encode(['error' => 'Message not found or you are not authorized to delete it (Not yours or not your current partner\'s).']);
        exit;
    }

    // 2. ถ้าเจอและเป็นเจ้าของ/คู่สนทนา: ลบไฟล์จริง (ถ้ามี)
    if (!empty($message_data['file_name'])) {
        $filePath = "../uploads/chat/" . $message_data['file_name'];
        if (file_exists($filePath)) {
            unlink($filePath); 
        }
    }
    
    // 3. ลบแถวจากฐานข้อมูล
    $deleteStmt = $db->prepare("DELETE FROM tb_messages WHERE id = ?"); 
    
    if ($deleteStmt->execute([$message_id])) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database deletion failed.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>