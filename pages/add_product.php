<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
    exit;
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];

// ถ้าไม่มีร้านค้าให้ไปหน้า market (ไม่ต้องแปล)
if (empty($profile['store'])) {
    echo '<script> location.replace("?page=market"); </script>';
    exit;
}

// ✅ ถ้าเป็น Admin ห้ามเพิ่มสินค้า
if (isset($profile['username']) && $profile['username'] == 'Admin' || $profile['username'] == 'admin') {
    echo "
    <script>
    Swal.fire({
        title: '{$T['admin_title']}',
        text: '{$T['admin_text']}',
        icon: 'error',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showCancelButton: false
    }).then(() => {
        window.location.href = '?page=profiles';
    });
    </script>";
    exit;
}

// ✅ เชื่อมต่อฐานข้อมูล
$db = connect();

// ตรวจสอบจำนวนสินค้าสูงสุดตาม VIP ก่อน insert
$ctm = $db->prepare("SELECT COUNT(*) AS product_count FROM tb_product WHERE customer_id = :cid");
$ctm->execute(['cid' => $customer_id]);
$currentProductCount = (int)($ctm->fetch(PDO::FETCH_ASSOC)['product_count'] ?? 0);

// ดึงระดับ VIP ของลูกค้า
$vip = $profile['vip_id'] ?? 0;

// กำหนด limit ตามระดับ VIP
switch ($vip) {
    case 1: $limit = 100; break;
    case 2: $limit = 140; break;
    case 3: $limit = 200; break;
    case 4: $limit = 500; break;
    case 5: $limit = PHP_INT_MAX; break; // ไม่จำกัด
    default: $limit = 40; break; // FREE
}

// ถ้าถึง limit แล้ว ห้ามเพิ่ม
if ($currentProductCount >= $limit && $limit != PHP_INT_MAX) {
    // แทนที่ {limit} ในข้อความแปล
    $limit_text = str_replace('{limit}', $limit, $T['limit_text']); 

    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: '{$T['limit_title']}',
            text: '$limit_text',
            showConfirmButton: true
        }).then(() => {
            location.href='?page=profiles';
        });
    </script>";
    exit;
}

// ✅ ฟังก์ชันตรวจสอบว่าสินค้าซ้ำหรือไม่ (เช็กเฉพาะร้านเดียวกัน)
function checkCTName($cproduct_id, $customer_id)
{
    $db = connect();
    $stmt = $db->prepare("SELECT COUNT(*) AS count 
                          FROM tb_product 
                          WHERE cproduct_id = :cproduct_id 
                          AND customer_id = :customer_id");
    $stmt->execute([
        ':cproduct_id' => $cproduct_id,
        ':customer_id' => $customer_id
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return ((int)$result['count'] === 0);
}

// ✅ ส่วนเพิ่มสินค้า
if (isset($_POST['save-product-btn'])) {

    $category_id = trim($_POST['category_id']);
    $customer_id = trim($_POST['customer_id']);
    $cproduct_id = trim($_POST['cproduct_id']);
    $name = trim($_POST['name']);
    $free_pay = trim($_POST['free_pay']);
    $qty = trim($_POST['qty']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $img_name = trim($_POST['img_name']);

    if (checkCTName($cproduct_id, $customer_id)) {
        if (!empty($category_id) && !empty($customer_id) && !empty($name) && !empty($description) && !empty($img_name)) {

            $stmt = $db->prepare("INSERT INTO tb_product 
                (category_id, product_id, cproduct_id, customer_id, name, description, img_name, price, qty, free_pay, created) 
                VALUES 
                (:category_id, :product_id, :cproduct_id, :customer_id, :name, :description, :img_name, :price, :qty, :free_pay, NOW())");

            $product_id = generateProId();

            $stmt->execute([
                ':category_id' => $category_id,
                ':product_id' => $product_id,
                ':cproduct_id' => $cproduct_id,
                ':customer_id' => $customer_id,
                ':name' => $name,
                ':description' => $description,
                ':img_name' => $img_name,
                ':price' => $price,
                ':qty' => $qty,
                ':free_pay' => $free_pay
            ]);

            echo "<script>
                Swal.fire({
                    title: '{$T['save_success_title']}',
                    text: '{$T['save_success_text']}',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000
                });
                setTimeout(() => location.href='?page=add_product', 2000);
            </script>";

        } else {
            // ใช้ $T['save_fail_text'] แทน "กรุณากรอกข้อมูลให้ครบถ้วน"
            echo '<div class="alert alert-danger">'.$T['save_fail_text'].'</div>';
        }
    } else {
        echo "<script>
            Swal.fire({
                title: '{$T['product_exists_title']}',
                text: '{$T['product_exists_text']}',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000
            });
            setTimeout(() => location.href='?page=add_product', 2000);
        </script>";
    }
}

// ✅ ดึงสินค้าทั้งหมด (ของ user id 17)
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $category_id = (int)$_GET['category_id'];
    $stmt = $db->prepare("
        SELECT p.id, p.product_id, p.cproduct_id, p.description, p.category_id, p.name, p.price, 
               p.img_name, p.qty, p.free_pay, p.money, p.view 
        FROM tb_product p 
        INNER JOIN tb_customer c ON c.id = p.customer_id 
        WHERE p.customer_id = 17 AND p.category_id = :category_id
        ORDER BY p.product_id DESC
    ");
    $stmt->execute([':category_id' => $category_id]);
} else {
    $stmt = $db->prepare("
        SELECT p.id, p.product_id, p.cproduct_id, p.description, p.category_id, p.name, p.price, 
               p.img_name, p.qty, p.free_pay, p.money, p.view 
        FROM tb_product p 
        INNER JOIN tb_customer c ON c.id = p.customer_id 
        WHERE p.customer_id = 17
        ORDER BY p.product_id DESC
    ");
    $stmt->execute();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="product-list">
  <?php foreach ($result as $row): ?>
    <div class="product">
        <img src="uploads/<?= $row['img_name']; ?>" alt="Product">
        <p class="price">$<?= number_format($row['price'], 2); ?></p>
        <a href="#" class="view-details"
           data-product-id="<?= $row['product_id']; ?>"
           data-category-id="<?= $row['category_id']; ?>"
           data-product-name="<?= htmlspecialchars($row['name']); ?>"
           data-qty="<?= $row['qty']; ?>"
           data-description="<?= htmlspecialchars($row['description']); ?>"
           data-free_pay="<?= $row['free_pay']; ?>"
           data-product-price="<?= number_format($row['price'], 2); ?>"
           data-product-img="uploads/<?= $row['img_name']; ?>"
           data-url="<?= $row['img_name']; ?>"
           data-bs-toggle="modal"
           data-bs-target="#bottomModal">
           <i class="bi bi-plus-circle-fill" style="font-size:25px; color:#aaa;"></i>
        </a>
    </div>
  <?php endforeach; ?>
</div>

<div class="modal fade modal-slide-up" id="bottomModal" tabindex="-1" aria-labelledby="bottomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bottomModalLabel"><?php echo $T['modal_details']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modal-img" src="" alt="Product Image" style="width: 100%; height: auto; border-radius: 8px; margin-bottom: 15px;">
        <h5 id="modal-name"></h5>
        <p id="modal-price"></p>
      </div>
      <div class="modal-footer">
        <form id="productForm" action="?page=add_product" method="POST">
          <input type="hidden" name="category_id" id="categoryId">
          <input type="hidden" name="cproduct_id" id="productId">
          <input type="hidden" name="name" id="productName">
          <input type="hidden" name="price" id="productPrice">
          <input type="hidden" name="qty" id="qty">
          <input type="hidden" name="description" id="description">
          <input type="hidden" name="img_name" id="productImage">
          <input type="hidden" name="free_pay" id="free_pay">
          <input type="hidden" name="customer_id" value="<?= $customer_id; ?>">
          <button type="submit" name="save-product-btn" class="btn btn-secondary"><?php echo $T['modal_add_list']; ?></button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.view-details').forEach(link => {
  link.addEventListener('click', function() {
    const data = this.dataset;
    document.getElementById('modal-name').textContent = data.productName;
    document.getElementById('modal-price').textContent = `$${data.productPrice}`;
    document.getElementById('modal-img').src = data.productImg;

    document.getElementById('categoryId').value = data.categoryId;
    document.getElementById('productId').value = data.productId;
    document.getElementById('qty').value = data.qty;
    document.getElementById('description').value = data.description;
    document.getElementById('free_pay').value = data.free_pay;
    document.getElementById('productName').value = data.productName;
    document.getElementById('productPrice').value = data.productPrice;
    document.getElementById('productImage').value = data.url;
  });
});
</script>

<style>
.product-list {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
  margin: 65px 10px 50px 10px;
}
.product {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  text-align: center;
}
.product img {
  width: 100%;
  border-radius: 8px;
}
.product .price {
  margin-top: 5px;
  font-weight: bold;
}
.view-details i {
  font-size: 25px;
  color: #aaa;
  transition: color 0.3s;
}
.view-details:hover i {
  color: #000;
}
.modal.modal-slide-up {
  position: fixed;
  bottom: 0;
  transform: translateY(100%);
  transition: transform 0.3s ease-in-out;
}
.modal.modal-slide-up.show {
  transform: translateY(0);
}
.modal-content {
  border-radius: 10px 10px 0 0;
}
</style>

<?php include_once('includes/footwallet.php'); ?>
