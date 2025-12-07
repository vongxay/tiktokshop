<?php
if (!isset($_SESSION['username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
    echo '<script> location.replace("?page=login"); </script>';
}
$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];


?>
<div class="container" style="margin-top: 65px; margin-bottom: 20px;">

    <style>
        .order-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
        }

        .order-details {
            flex-grow: 1;
            margin-left: 10px;
        }

        .order-name {
            font-size: 16px;
            font-weight: bold;
        }

        .order-price {
            color: #28a745;
            font-weight: bold;
        }

        .order-status {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
    <div class="order-container">

<h2><?php echo $T['order_list_title']; ?></h2>
<?php
$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];

// กำหนด Key สถานะสำหรับการเข้าถึงตัวแปร $T
$status_keys = [
    0 => 'status_0',
    1 => 'status_1',
    2 => 'status_2',
    3 => 'status_3',
];

if (isset($_GET['statust'])) {
    $statust = $_GET['statust'];

    $db = connect();
    $stmt = $db->query("SELECT * FROM tb_order_detail od 
                INNER JOIN tb_product p ON od.product_id = p.product_id 
                WHERE od.user_product_id = $customer_id AND statust = $statust");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        $text = $row['name'];
        $trimmed = mb_strimwidth($text, 0, 25, "...", "UTF-8");

        // ✅ ใช้ Key ที่แปลแล้วจาก $T
        $current_status_key = $status_keys[$row['statust']] ?? 'status_0';
        $order_statust = $T[$current_status_key];
?>

        <?php if ($row['statust'] == 3): ?>
            <a href="?page=sucess_detail&order_id=<?= $row['order_id'] ?>&product_id=<?= $row['product_id'] ?>" class="order-item" style="text-decoration:none; color:inherit;">
                <img src="uploads/<?= $row['img_name']; ?>" alt="Product">
                <div class="order-details">
                    <div class="order-name"><?= $trimmed; ?></div>
                    <div class="order-status"><?= $order_statust; ?></div>
                </div>
                <div class="order-price">$<?= $row['price_qty']; ?></div>
            </a>
        <?php else: ?>
            <div class="order-item">
                <img src="uploads/<?= $row['img_name']; ?>" alt="Product">
                <div class="order-details">
                    <div class="order-name"><?= $trimmed; ?></div>
                    <div class="order-status"><?= $order_statust; ?></div>
                </div>
                <div class="order-price">$<?= $row['price_qty']; ?></div>
            </div>
        <?php endif; ?>

<?php
    }
}
?>

</div>
</div>