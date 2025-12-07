<div class="product-list" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin: 60px 10px 50px 10px;">

<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$db = connect();

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $stmt = $db->prepare("SELECT * FROM tb_product 
                          WHERE name LIKE :search 
                          AND customer_id != :customer_id 
                          ORDER BY id DESC");
    $stmt->execute([
        ':search' => "%$search%",
        ':customer_id' => $customer_id
    ]);

} else if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $stmt = $db->prepare("SELECT * FROM tb_product 
                          WHERE category_id = :category_id 
                          ORDER BY id DESC");
    $stmt->execute([':category_id' => $category_id]);

} else if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM tb_product 
                          WHERE product_id = :product_id 
                          ORDER BY id DESC");
    $stmt->execute([':product_id' => $product_id]);

} else {
    $stmt = $db->prepare("SELECT * FROM tb_product 
                          WHERE customer_id != :customer_id 
                          ORDER BY id DESC");
    $stmt->execute([':customer_id' => $customer_id]);
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $text = $row['name'];
    $trimmed = mb_strimwidth($text, 0, 20, "...", "UTF-8");
    if(!empty($row['img_name'])){
?>
    <a href="?page=product_detail&id=<?php echo $row['product_id']; ?>" style="flex: 0 0 calc(33.33% - 20px); text-decoration: none; color: inherit;">
        <div class="product" style="margin:auto; border: 1px solid #ddd; border-radius: 10px; padding: 10px; text-align: center;">
            <img src="uploads/<?php echo $row['img_name']; ?>" alt="<?php echo $trimmed; ?>" style="width: 100%; height: auto; border-radius: 10px;">
            <p><?php echo $trimmed; ?></p>
            <p class="price">$<?php echo number_format($row['price'], 2); ?></p>
        </div>
    </a>
<?php 
    } 
}
?>
</div>

<style>
@media (max-width: 992px) {
    .product-list a {
        flex: 0 0 calc(50% - 20px); /* 2 ต่อแถวบน tablet */
    }
}

@media (max-width: 576px) {
    .product-list a {
        flex: 0 0 100%; /* 1 ต่อแถวบนมือถือ */
    }
}
</style>

<?php
include_once('includes/footwallet.php');
?>
<br>