<?php
session_start();
include_once('functions/init.php');

if (!isset($_SESSION['loggedId'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$_SESSION['user_id'] = $customer_id;
$user_id = $_SESSION['user_id'];

$db = connect();

$stmt = $db->prepare("
    SELECT 
        CASE 
            WHEN m.sender_id = :user_id THEN m.receiver_id
            ELSE m.sender_id
        END AS chat_partner_id,
        u.username,
        u.fname,
        u.img_name,
        MAX(m.timestamp) AS last_time,
        (
            SELECT m2.message
            FROM tb_messages m2
            WHERE 
                (m2.sender_id = CASE WHEN m.sender_id = :user_id THEN m.receiver_id ELSE m.sender_id END 
                 AND m2.receiver_id = :user_id)
                OR 
                (m2.sender_id = :user_id 
                 AND m2.receiver_id = CASE WHEN m.sender_id = :user_id THEN m.receiver_id ELSE m.sender_id END)
            ORDER BY m2.timestamp DESC
            LIMIT 1
        ) AS last_message
    FROM tb_messages m
    JOIN tb_customer u ON u.id = CASE 
                                    WHEN m.sender_id = :user_id THEN m.receiver_id
                                    ELSE m.sender_id
                                END
    WHERE m.sender_id = :user_id OR m.receiver_id = :user_id
    GROUP BY chat_partner_id, u.username, u.fname, u.img_name
    ORDER BY last_time DESC
");

$stmt->execute([':user_id' => $user_id]);
$chat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($chat_list);
exit;
