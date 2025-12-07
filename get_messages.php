<?php
session_start();
include_once('functions/init.php');

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$_SESSION['user_id'] = $customer_id; // user_id ของเรา
$user_id = $_SESSION['user_id'];

// รับ ID คู่สนทนาจาก query string
$receiver_id = isset($_GET['user']) ? intval($_GET['user']) : 0;

$db = connect();

if ($user_id > 0 && $receiver_id > 0) {
    // ดึงข้อความเฉพาะระหว่างเราและคู่สนทนา
    $stmt = $db->prepare("
        SELECT 
            m.id,
            m.message,
            m.type,
            m.file_name,
            m.timestamp,
            m.sender_id,
            m.receiver_id,
            u.username,
            u.fname,
            u.img_name
        FROM tb_messages m
        INNER JOIN tb_customer u ON m.sender_id = u.id
        WHERE 
            (m.sender_id = :user_id AND m.receiver_id = :receiver_id)
            OR (m.sender_id = :receiver_id AND m.receiver_id = :user_id)
        ORDER BY m.timestamp ASC
    ");

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($messages);
} else {
    echo json_encode([]);
}
?>
