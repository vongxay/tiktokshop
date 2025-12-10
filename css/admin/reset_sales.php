<?php
include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $db = connect();
    $id = htmlentities($_POST['id']);

    $stmt = $db->prepare("UPDATE tb_order_detail SET price_qty = 0 WHERE user_product_id = :id");
    $stmt->bindParam(":id", $id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
