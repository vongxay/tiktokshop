<?php
include_once('includes/braner.php');
?>

<?php
include_once('includes/product_braner.php');
?>


<div class="product-list" style="margin-bottom: 50px;">

    <?php
        $db = connect();
        $stmt = $db->query("SELECT * FROM tb_product ORDER BY RAND() DESC LIMIT 60");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row){
        if(!empty($row['img_name'])){
    ?>
    <a href="?page=product_detail&id=<?php echo $row['product_id']; ?>">
        <div class="product">
            <img src="uploads/<?php echo $row['img_name']; ?>" alt="Product 1">
            <p> <?php
                    $text = $row['name'];
                    $trimmed = mb_strimwidth($text, 0, 30, "...", "UTF-8");
                    // ผลลัพธ์: ยินดีต้อนรับเข้าสู่...
                    ?>
                <?php echo $trimmed; ?></p>
            <p class="price">$<?php echo number_format($row['price'],2) ?></p>

        </div>
    </a>
    <?php 
    }
} 
?>
</div>

<?php
include_once('includes/footwallet.php');
?>




