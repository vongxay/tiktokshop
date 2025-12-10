<?php
include_once('../functions/init.php');
session_start();
$_SESSION['u_id'];
$u_id = $_SESSION['u_id'];
$receiver_id = $_SESSION['user_id'];
// echo $u_id;
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
        u.username,
        u.id AS user_id,
        u.customer_id
    FROM tb_messages m
    JOIN tb_customer u ON m.user_id = u.id
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
echo json_encode($messages);

?>


