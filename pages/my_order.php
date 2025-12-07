<?php include_once('includes/braner.php'); ?>

<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];

$db = connect();
$stmt = $db->prepare("
    SELECT od.*, p.name as product_name, p.img_name 
    FROM tb_order_detail od
    INNER JOIN tb_product p ON od.product_id = p.product_id
    WHERE od.user_product_id = :user_id
    ORDER BY od.created DESC
");
$stmt->bindParam(':user_id', $customer_id, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container" style="margin-top: 65px; margin-bottom: 20px;">
    <h2><?php echo $T['my_sales_list_title']; ?></h2>

    <div class="order-container">
        <?php foreach($orders as $row): ?>
            <?php
                $trimmed = mb_strimwidth($row['product_name'], 0, 25, "...", "UTF-8");
            ?>
            <div class="order-item">
                <img src="uploads/<?php echo $row['img_name']; ?>" alt="Product">
                <div class="order-details">
                    <div class="order-name"><?php echo $trimmed; ?></div>
                    <div class="order-date"><?php echo date('d/m/Y H:i', strtotime($row['created'])); ?></div>
                </div>
                <div class="order-price">$<?php echo number_format($row['price_qty'],2); ?></div>
                <div class="order-qty"><?php echo $T['order_qty_label']; ?>: <?php echo $row['qty']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.order-container {
    margin-top: 20px;
}

.order-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    margin-bottom: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}

.order-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.order-item img {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
}

.order-details {
    flex: 1;
    margin-left: 12px;
}

.order-name {
    font-size: 16px;
    font-weight: bold;
    color: #34495e;
}

.order-date {
    font-size: 12px;
    color: #7f8c8d;
}

.order-price {
    font-size: 16px;
    font-weight: bold;
    color: #e74c3c;
    margin-left: 15px;
}

.order-qty {
    font-size: 14px;
    margin-left: 10px;
    color: #2c3e50;
}
</style>
