<?php include_once('includes/braner.php'); ?>

<?php
if (!isset($_SESSION['username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
    echo '<script> location.replace("?page=login"); </script>';
}
$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];


?>

<div class="container">
    <h3><?php echo $T['my_sales_list_title']; ?></h3>
    <ul class="order-list">
        <!-- Order Item 1 -->
        <?php

        $db = connect();

        $smt = $db->query("SELECT o.price_qty, o.statust, o.qty, o.customer_id, o.user_product_id as custom, o.order_id, o.created, o.product_id, p.id as pid, p.customer_id, p.money, p.cproduct_id, p.name as product_name, p.img_name

                FROM tb_order_detail o 

                INNER JOIN tb_product p

                    ON o.product_id = p.product_id

                WHERE o.user_product_id = '$customer_id' AND o.statust = 0 ORDER BY o.order_id DESC;");

        $result = $smt->fetchAll(PDO::FETCH_ASSOC);

        $i = 0;
        foreach ($result as $row) {
            $i++;

            $string = $row['product_name'];
            $string = strip_tags($string);
            if (strlen($string) > 0) {

                // truncate string
                $stringCut = substr($string, 0, 25);
                $endPoint = strrpos($stringCut, ' ');

                //if the string doesn't contain any space then it will cut without word basis.
                $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            }

        ?>
            <a href="?page=order_detail&order_id=<?php echo $row['order_id']; ?>&product_id=<?php echo $row['product_id']; ?>" style="color:#333">
                <li class="order-item">
                    <div class="order-info">
                        <img src="uploads/<?php echo $row['img_name']; ?>" alt="Product Image" class="order-image">
                        <div class="order-details">
                            <div class="order-name"><?php echo $string; ?></div>
                            <div class="order-price"><?php echo $row['order_id']; ?> </div>
                        </div>

                    </div>
                    <?php echo $row['money']; ?><?php echo $row['price_qty']; ?>
                    <div class="order-actions">
                        Qty (<?php echo $row['qty']; ?>)
                    </div>
                </li>
            </a>
        <?php
        }

        ?>

    </ul>
</div>



<style>
    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .order-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .order-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 10px;
        border-bottom: 1px solid #ddd;
        transition: background-color 0.2s;
    }

    .order-item:hover {
        background-color: #f1f1f1;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-info {
        display: flex;
        align-items: center;
    }

    .order-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        margin-right: 15px;
        object-fit: cover;
        border: 1px solid #ddd;
    }

    .order-details {
        line-height: 1.2;
    }

    .order-name {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    .order-price {
        font-size: 14px;
        color: #666;
    }

    .order-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        color: #fff;
        transition: background-color 0.2s;
    }

    .btn.view {
        background-color: #007bff;
    }

    .btn.view:hover {
        background-color: #0056b3;
    }

    .btn.cancel {
        background-color: #333;
    }

    .btn.cancel:hover {
        background-color: #333;
        color: #fff;
    }
</style>