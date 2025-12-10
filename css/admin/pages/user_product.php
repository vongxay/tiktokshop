<!-- ເພີ່ມລາຍລະອຽດຂ່າວ -->
<!-- Button trigger modal -->
<?php
if (!isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
  // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ
  echo '<script> location.replace("?page=login"); </script>';
}
$profile = getCustomerBy($_SESSION['admin_loggedId']);
$custom = $profile['id'];


// ✅ ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
  session_destroy(); // ลบ session
  echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
  exit;
}
?>
<!-- Modal -->

<br>
<h2 style="margin:10px">รายการลูกค้า</h2>

<!-- 🔎 ฟอร์มค้นหา -->
<form method="get" style="margin:10px 0;">
  <input type="hidden" name="page" value="user_product">
  <div style="display:flex; gap:10px; align-items:center;">
    <input type="text" name="search" value="<?php echo $_GET['search'] ?? ''; ?>"
      placeholder="ค้นหาโดยไอดีหรือชื่อผู้ใช้"
      style="padding:6px 10px; border:1px solid #ccc; border-radius:5px; min-width:250px;">
    <button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
    <?php if (!empty($_GET['search'])): ?>
      <a href="?page=user_product" class="btn btn-secondary btn-sm">ล้าง</a>
    <?php endif; ?>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-striped table-sm display">
    <thead>
      <tr>
        <th scope="col">ลำดับ</th>
        <th scope="col">ภาพหลัก</th>
        <th scope="col">สินค่า</th>
        <th scope="col">เจ้าของสินค้า</th>
        <th scope="col">ไอดีเจ้าของสินค้า</th>
        <th scope="col">หมวดหมู่</th>
        <th scope="col">ราคา</th>
        <th scope="col">จำนวนสินค้า</th>
        <th scope="col">จำนวนการดู</th>
        <th scope="col">วันที่</th>
        <th scope="col">สังชื้อ</th>
        <th scope="col">จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $db = connect();

      // ✅ ดักค่าค้นหา
      $where = "WHERE n.customer_id != $custom";
      if (!empty($_GET['search'])) {
        $search = trim($_GET['search']);
        $search = "%$search%";
        $where .= " AND (u.customer_id LIKE :search OR u.username LIKE :search)";
        $stmt = $db->prepare("SELECT n.product_id, n.name, n.money, n.price, n.qty, n.view, n.customer_id, n.img_name, n.created, u.username, u.customer_id, c.name AS category_name  
                                FROM tb_product n 
                                INNER JOIN tb_category c ON (n.category_id = c.id) 
                                INNER JOIN tb_customer u ON n.customer_id = u.id 
                                $where
                                ORDER BY n.id DESC");
        $stmt->execute([':search' => $search]);
      } else {
        $stmt = $db->query("SELECT n.product_id, n.name, n.money, n.price, n.qty, n.view, n.customer_id, n.img_name, n.created, u.username, u.customer_id, c.name AS category_name  
                              FROM tb_product n 
                              INNER JOIN tb_category c ON (n.category_id = c.id) 
                              INNER JOIN tb_customer u ON n.customer_id = u.id 
                              $where
                              ORDER BY n.id DESC");
      }

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $i = 0;
      foreach ($result as $row) {
        $i++;
      ?>
        <tr>
          <td><?php echo $i; ?></td>
          <td>
            <div class="img"><img src="../uploads/<?php echo $row['img_name']; ?>" alt="Paris" style="width:50px" class="img-responsive img-circle">
          </td>
          <td>
            <?php
            $string = $row['name'];
            echo strlen($string) > 20 ? substr($string, 0, 50) . ' ...' : $string;
            ?>
          </td>
          <td><?php echo $row['username']; ?></td>
          <td><?php echo $row['customer_id']; ?></td>
          <td><?php echo $row['category_name']; ?></td>
          <td><?php echo $row['money']; ?><?php echo number_format($row['price']); ?></td>
          <td><?php echo $row['qty']; ?></td>
          <td><?php echo $row['view']; ?> ครั้ง</td>
          <td><?php echo date_format(date_create($row['created']), "วันที d.m.Y เวลา H:i นาที"); ?></td>
          <td>
          <td>
          <div class="btn-group" role="group">
              <?php $searchParam = !empty($_GET['search']) ? '' . urlencode($_GET['search']) : ''; ?>
              <button 
                  type="button" 
                  class="btn btn-sm btn-danger" 
                  data-bs-toggle="modal" 
                  data-bs-target="#qtyModal" 
                  data-product-id="<?php echo $row['product_id']; ?>" 
                  data-product-name="<?php echo htmlspecialchars($row['name']); ?>" 
                  data-product-price="<?php echo $row['price']; ?>" 
                  data-product-img="../uploads/<?php echo $row['img_name']; ?>" 
                  data-search="<?php echo $searchParam; ?>">
                  addcart
              </button>
            </div>

            <!-- ✅ Popup Modal -->
            <div class="modal fade" id="qtyModal" tabindex="-1" aria-labelledby="qtyModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="qtyModalLabel">เพิ่มสินค้าลงตะกร้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <!-- 🖼 แสดงข้อมูลสินค้า -->
                    <div class="d-flex align-items-center mb-3">
                      <img id="modalProductImg" src="" class="rounded me-3" style="width:80px; height:80px; object-fit:cover;">
                      <div>
                        <h6 id="modalProductName" class="mb-1"></h6>
                        <p id="modalProductPrice" class="text-danger fw-bold mb-0"></p>
                      </div>
                    </div>

                    <form id="addCartForm" method="get">
                      <input type="hidden" name="page" value="user_product">
                      <input type="hidden" name="act" value="add">
                      <input type="hidden" name="id" id="modalProductId">
                      <input type="hidden" name="search" id="modalSearch">

                      <div class="mb-3">
                        <label for="qtyInput" class="form-label">จำนวน</label>
                        <input type="number" class="form-control" name="qty" id="qtyInput" value="1" min="1" required>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" form="addCartForm" class="btn btn-danger">เพิ่มลงตะกร้า</button>
                  </div>
                </div>
              </div>
            </div>

          </td>

          <td>
            <div class="btn-group" role="group" aria-label="Basic outlined example">
              <a href="?page=product&method=edit&id=<?php echo $row['product_id']; ?>" class="btn btn-outline-primary btn-sm">แก้ไข</a>
            </div>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<hr>

<center>
  <a href="?page=product" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-check-fill" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0m-.646 5.354a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
    </svg> รายกาของคุณ</a>

  <a href="?page=home" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-heart" viewBox="0 0 16 16">
      <path d="M8 6.982C9.664 5.309 13.825 8.236 8 12 2.175 8.236 6.336 5.309 8 6.982Z" />
      <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.707L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.646a.5.5 0 0 0 .708-.707L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z" />
    </svg> หน้าหลัก</a>
  <?php
  if ($_SESSION['username'] == 'admin') {

  ?>
    <!-- <a href="?page=looking" class="btn btn-dark"> ดูข้อมูล <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
</svg></a> -->
  <?php } ?>
  <hr>
</center>
</div>


<?php

$profile = getCustomerBy($_SESSION['admin_loggedId']);
$id = $profile['id'];
error_reporting(0);

$product_id = $_GET['id'];
$act = $_GET['act'];

// ✅ เก็บค่าค้นหาไว้ ถ้ามี
$searchParam = !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';

if ($act == 'add' && !empty($product_id)) {
  $qty = !empty($_GET['qty']) ? intval($_GET['qty']) : 1; // ✅ อ่านค่าจำนวนจาก Modal
  if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] += $qty;
  } else {
      $_SESSION['cart'][$product_id] = $qty;
  }
  ?>
  <script>
      location.href = "?page=user_product<?php echo $searchParam; ?>";
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

<div class="container my-4">
  <div class="row">
    <!-- 🛒 รายการในตะกร้า -->
    <div class="col-lg-8">
      <h4 class="mb-3 fw-bold">ตะกร้าสินค้า
        <span class="badge bg-secondary">
          <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?> รายการ
        </span>
      </h4>

      <?php
      if (!empty($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $qty) {
          $db = connect();
          $smt = $db->query("SELECT * FROM tb_product WHERE product_id = '$id'");
          $result = $smt->fetchAll(PDO::FETCH_ASSOC);
          foreach ($result as $row) {
            $sum = $row['price'] * $qty;
            $total += $sum;
            $pqty = $row['qty'];
            $string = mb_strimwidth(strip_tags($row['name']), 0, 25, "...");
      ?>
            <div class="card mb-3 shadow-sm">
              <div class="card-body">
                <div class="row align-items-center">

                  <!-- รูปสินค้า -->
                  <div class="col-3 col-md-2">
                    <img src="../uploads/<?php echo $row['img_name']; ?>"
                      class="img-fluid rounded shadow-sm"
                      style="height:60px; object-fit:cover;" alt="product">
                  </div>

                  <!-- ชื่อสินค้า -->
                  <div class="col-9 col-md-4">
                    <span class="fw-semibold"><?php echo $string; ?></span>
                  </div>

                  <!-- ปุ่มจำนวน -->
                  <div class="col-12 col-md-3 my-2 my-md-0">
                    <div class="d-flex justify-content-center justify-content-md-start align-items-center gap-2">
                  
                      <a href="?page=user_product&id=<?php echo $id ?>&act=put"
                        class="btn btn-outline-danger btn-sm">-</a>
                      <input type="text" class="form-control text-center"
                        style="max-width:60px;" value="<?php echo $qty; ?>" disabled>
                      <?php if ($qty >= $pqty) { ?>
                        <a href="#" class="btn btn-outline-secondary btn-sm disabled">+</a>
                      <?php } else { ?>
                        <?php $searchParam = !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>
                        <a href="?page=user_product&id=<?php echo $id.$searchParam; ?>&act=plus"
                          class="btn btn-outline-success btn-sm">+</a>
                      <?php } ?>
                    </div>
                  </div>

                  <!-- ราคา -->
                  <div class="col-6 col-md-2 text-success fw-bold">
                    <?php echo number_format($sum); ?> $
                  </div>

                  <!-- ลบ -->
                  <div class="col-6 col-md-1 text-danger">
                    <a href="?page=user_product&id=<?php echo $id ?>&act=remove"
                      class="btn btn-sm btn-outline-danger">ลบ</a>
                  </div>
                </div>
              </div>
            </div>
      <?php
          }
        }
      } else {
        echo '<div class="alert alert-info">ยังไม่มีสินค้าในตะกร้า <a href="?page=user_product">เลือกซื้อสินค้า</a></div>';
      }
      ?>
    </div>

    <!-- 📦 สรุปรายการ + ที่อยู่จัดส่ง -->
    <?php if (!empty($_SESSION['cart'])) { ?>
      <div class="col-lg-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="fw-bold mb-3">สรุปรายการ</h5>

            <div class="d-flex justify-content-between mb-2">
              <span>จำนวนสินค้า</span>
              <span><?php echo count($_SESSION['cart']); ?> รายการ</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
              <span class="fw-semibold">ยอดรวม</span>
              <span class="fw-bold text-success"><?php echo number_format($total); ?> $</span>
            </div>

            <hr>

            <h6 class="fw-bold mb-3">ข้อมูลการจัดส่ง</h6>
            <form method="POST" action="?page=save_user&id=<?php echo $id; ?>">

              <div class="mb-2">
                <input type="text" name="fname" class="form-control" placeholder="ชื่อ"
                  value="<?php echo $profile['fname']; ?>" required>
              </div>
              <!-- <div class="mb-2">
                <input type="text" name="mobile" class="form-control" placeholder="เบอร์โทร"
                  value="<?php echo $profile['mobile']; ?>" required>
              </div> -->
              <div class="mb-2">
                <input type="text" name="address" class="form-control" placeholder="ที่อยู่"
                  value="<?php echo $profile['address']; ?>" required>
              </div>
              <div class="mb-3">
                <input type="text" name="distric" class="form-control" placeholder="อำเภอ"
                  value="<?php echo $profile['distric']; ?>" required>
              </div>

              <button type="submit" name="submit" class="btn btn-success w-100">✅ เช็คเอาท์</button>
            </form>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>


<script>
  const qtyModal = document.getElementById('qtyModal');
  qtyModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    // ดึงค่า data-* จากปุ่มที่กด
    const productId = button.getAttribute('data-product-id');
    const productName = button.getAttribute('data-product-name');
    const productPrice = button.getAttribute('data-product-price');
    const productImg = button.getAttribute('data-product-img');
    const searchParam = button.getAttribute('data-search');

    // อัพเดทค่าไปที่ฟอร์มใน Modal
    document.getElementById('modalProductId').value = productId;
    document.getElementById('modalSearch').value = searchParam;

    // แสดงข้อมูลใน modal
    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('modalProductPrice').textContent = productPrice + ' $';
    document.getElementById('modalProductImg').src = productImg;
  });
</script>
