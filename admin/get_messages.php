<?php
include_once('../functions/init.php');
session_start();
header('Content-Type: application/json');

$u_id = isset($_SESSION['u_id']) ? $_SESSION['u_id'] : 0;
$receiver_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($u_id == 0 || $receiver_id == 0) {
    echo json_encode([]);
    exit;
}

$db = connect();
// ดึงข้อความทั้งหมด
$stmt = $db->prepare("
    SELECT 
        m.id AS message_id,
        m.message,
        m.type,
        m.file_name,
        m.timestamp,
        m.sender_id,
        m.receiver_id,
        m.status,
        u.username,
        u.id AS user_id,
        u.customer_id,
        u.img_name
    FROM tb_messages m
    JOIN tb_customer u ON m.sender_id = u.id
    WHERE 
        (m.sender_id = :u_id AND m.receiver_id = :receiver_id)
        OR (m.sender_id = :receiver_id AND m.receiver_id = :u_id)
    ORDER BY m.timestamp ASC
");
$stmt->execute([
    ':u_id' => $u_id,
    ':receiver_id' => $receiver_id
]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// อัพเดทสถานะเป็น "อ่านแล้ว" สำหรับข้อความที่ส่งมาหาเรา (admin)
$updateStmt = $db->prepare("
    UPDATE tb_messages 
    SET status = 1 
    WHERE receiver_id = :receiver_id AND sender_id = :u_id AND status = 0
");
$updateStmt->execute([
    ':receiver_id' => $receiver_id,
    ':u_id' => $u_id
]);

echo json_encode($messages);

?>


