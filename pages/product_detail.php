<div class="product-page" style="margin-top: 60px; margin-bottom:60px">
    <?php
    if (!isset($_SESSION['username'])) {
        echo '<script> location.replace("?page=login"); </script>';
    }
    error_reporting(0);
    $product_id = $_GET['id'];
    $act = $_GET['act'];

    if ($act == 'add' && !empty($product_id)) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        } ?>
        <script type="text/javascript">
            setTimeout(function() {
                location.href = "?page=product_detail&id=<?php echo $product_id; ?>";
            }, 10);
        </script>
    <?php
    }


    if ($act == 'put' && !empty($product_id))  //ลบลายกานที่ละ1
    {

        $_SESSION['cart'][$product_id] -= 1;
    } elseif ($_SESSION['cart'][$product_id] < 1) {
        unset($_SESSION['cart'][$product_id]);
    }

    if ($act == 'plus' && !empty($product_id))  //เพีมลายกานที่ละ1
    {
        $_SESSION['cart'][$product_id] += 1;
    } elseif ($_SESSION['cart'][$product_id] < 1) {
        unset($_SESSION['cart'][$product_id]);
    }


    if ($act == 'remove' && !empty($product_id))  //ยกเลิกการสั่งซื้อ
    {
        unset($_SESSION['cart'][$product_id]);
    }

    if ($act == 'update') {
        $amount_array = $_POST['amount'];
        foreach ($amount_array as $p_id => $amount) {
            $_SESSION['cart'][$p_id] = $amount;
        }
    }
    ?>
    <?php
    //  get product id
    $db = connect();
    $smt = $db->query("SELECT c.id, c.store, p.id, p.product_id, p.name, p.category_id, p.customer_id, p.name, p.img_name, p.price, p.money, p.qty, p.description, p.free_pay, p.view FROM tb_product p INNER JOIN tb_customer c ON p.customer_id = c.id WHERE p.product_id = '$product_id'");
    $result = $smt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $product) {
        $code = $product['view'];
        $category_id = $product['category_id'];
    }
    ?>
    <div class="product-image">
        <img src="uploads/<?php echo $product['img_name']; ?>" alt="Product Image">
    </div>

    <script>
        // The function below will start the confirmation dialog
        function confirmAction() {
            // ใช้ $T['confirm_add_cart']
            let confirmAction = confirm("<?php echo $T['confirm_add_cart']; ?> <?php echo $product['name']; ?> ");
            if (confirmAction) {
                window.location = '?page=product_detail&id=<?php echo $product['product_id']; ?>&act=add';
            } else {
                window.location = '?page=home';
            }
        }
    </script>

    <div class="product-info" style="font-size:20px">
        <h1 style="color:#ff2e63; font-weight:bold"><?php echo $product['name']; ?></h1>
        <p style="font-size:larger; font-weight: bolder;">$<?php echo number_format($product['price'], 2); ?></p>

        <button class="follow-btn" onclick="confirmAction()"><?php echo $T['add_to_cart_btn']; ?></button>

        <p class="description">
            <?php echo $product['description']; ?>
        </p>
        <div class="status">
            <span><?php echo $T['status_quantity']; ?>: <strong><?php echo $T['status_no_info']; ?></strong></span>
            <span><?php echo $T['status_stock']; ?>: <strong><?php echo $product['qty']; ?></strong></span>
            <span><?php echo $T['status_shop']; ?>: <strong><?php echo $product['store']; ?></strong></span>
        </div>
        <hr>
        <div class="rating">
            <span><?php echo $T['status_comment']; ?>: </span>
            <span><?php echo $T['status_rate']; ?> <?php echo $T['rate_percent']; ?></span>
        </div>
    </div>


    <div class="related-products">
        <h2><?php echo $T['related_products']; ?></h2>
        <div class="related-products-container">
            <?php
            $related_smt = $db->query("SELECT * FROM tb_product WHERE category_id = '$category_id' AND product_id != '$product_id' LIMIT 10");
            $related_products = $related_smt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($related_products as $rel) {
            ?>
                <div class="related-product-card">
                    <a href="?page=product_detail&id=<?php echo $rel['product_id']; ?>">
                        <img src="uploads/<?php echo $rel['img_name']; ?>" alt="<?php echo $rel['name']; ?>">

                        <p>$<?php echo number_format($rel['price'], 2); ?></p>
                    </a>
                    <button onclick="window.location='?page=product_detail&id=<?php echo $rel['product_id']; ?>&act=add'" class="follow-btn"><?php echo $T['add_to_cart_btn']; ?></button>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<style>
    .related-products {
        margin-top: 40px;
    }

    .related-products h2 {
        font-size: 20px;
        color: #ff2e63;
        margin-bottom: 15px;
    }

    .related-products-container {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding-bottom: 10px;
    }

    .related-product-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        flex: 0 0 150px;
        text-align: center;
        padding: 10px;
    }

    .related-product-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }

    .related-product-card h3 {
        font-size: 14px;
        margin: 10px 0 5px 0;
    }

    .related-product-card p {
        font-size: 13px;
        color: #555;
        margin-bottom: 5px;
    }

    .related-product-card .follow-btn {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 15px;
    }
</style>

<style>
    .product-page {
        display: flex;
        flex-direction: column;
        height: auto
    }

    .navbar {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .follow-btn {
        background-color: #ff2e63;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 1em;
        cursor: pointer;
        margin-bottom: 15px;
        width: 100%;
    }

    .follow-btn:hover {
        background-color: #e02453;
    }

    .navbar .icon {
        font-size: 20px;
        margin-right: 10px;
    }

    .navbar .title {
        font-size: 18px;
        font-weight: bold;
    }

    .product-image {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #fff;
        width: auto;
    }

    .product-image img {
        max-width: 100%;
        height: 100%;
    }

    .product-info {
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
    }

    .product-info h1 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .product-info .description {
        font-size: 14px;
        color: #555;
        margin-bottom: 15px;
    }

    .product-info .status {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .product-info .rating {
        font-size: 14px;
        color: #999;
    }

    .bottom-bar {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: #fff;
        padding: 10px 0;
        border-top: 1px solid #ddd;
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    .bottom-bar .btn {
        flex: 1;
        margin: 0 5px;
        padding: 10px;
        text-align: center;
        font-size: 14px;
        border: none;
        border-radius: 5px;
    }

    .save-btn {
        background-color: #ffcc00;
        color: #fff;
    }

    .support-btn {
        background-color: #ff7700;
        color: #fff;
    }

    .cart-btn {
        background-color: #0099ff;
        color: #fff;
    }

    .wishlist-btn {
        background-color: #999;
        color: #fff;
    }

    .buy-btn {
        background-color: #ff0000;
        color: #fff;
    }
</style>