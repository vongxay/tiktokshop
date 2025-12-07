</section>

</main>
<nav class="footer-nav">
    <a href="?page=home"><i class="bi bi-house-door-fill"></i>
        <p style="font-size: 14px; margin-top: -5px;"><?php echo $T['nav_home']; ?></p>
    </a>
    
    <a href="?page=product"><i class="bi bi-bag-fill"></i>
        <p style="font-size: 14px; margin-top: -5px;"><?php echo $T['nav_product']; ?></p>
    </a>
    
    <a href="?page=add_product"><i class="bi bi-plus-circle-fill" style="font-size:35px"></i></a>
    
    <a href="?page=cart"><i class="bi bi-cart-plus-fill"></i>
    <?php
        if (!empty($_SESSION['cart'])) {
    ?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="margin-left:-110px; margin-top:20px; font-size:12px">
        <?php echo count($_SESSION['cart']); ?>
        </span>
        <?php } ?>

        <p style="font-size: 14px; margin-top: -5px;"><?php echo $T['nav_cart']; ?></p>
    </a> 
    
    <?php
        // ดึงจำนวน order ที่ statust < 3
        $stmt = $db->query("SELECT COUNT(order_id) as pending_count 
                            FROM tb_order_detail 
                            WHERE user_product_id = '$customer_id' AND statust < 3");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $pending_count = $row['pending_count'] ?? 0;
        ?>

        <a href="?page=profiles" style="position: relative; display: inline-block; text-align:center;">
            <i class="bi bi-person-fill" style="font-size: 20px; position: relative;">
                <?php if ($pending_count > 0) { ?>
                    <span style="
                        position: absolute;
                        top: -5px;
                        right: -5px;
                        width: 10px;
                        height: 10px;
                        background-color: red;
                        border-radius: 50%;
                        display: inline-block;
                        border: 2px solid white;">
                    </span>
                <?php } ?>
            </i>
            <p style="font-size: 14px; margin-top: -5px;"><?php echo $T['nav_profiles']; ?></p>
        </a>

</nav>