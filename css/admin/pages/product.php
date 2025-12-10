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
<div class="from px-2 py-5" id="featured-3">
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">รายละเอียด</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="?page=product" method="POST" enctype="multipart/form-data">
          <div class="input-group mb-3 was-validated">
            <label class="input-group-text" for="inputGroupSelect01">หมวดหมู่</label>
            <select class="form-select was-validated" name="category_id" required>
              <option selected disabled value="">เลือกหมวดหมู่...</option>
              <?php
            
              $categoryuser = getProductCategoryUser();
              $i = 0;
              foreach ($categoryuser as $row) {
              $i++;
              ?>
              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
              <?php
            }
          
              ?>
            </select>
          </div>
          <div class="input-group flex-nowrap was-validated">
            <span class="input-group-text" id="addon-wrapping">ชื่อผลิตภัณฑ์</span>
            <input type="text" class="form-control" placeholder="ชื่อผลิตภัณฑ์" name="name" aria-label="Username" aria-describedby="addon-wrapping" required>
          </div>
          <br>
          <div class="input-group mb-3 was-validated">
            <input type="file" class="form-control" accept="image/png, image/jpeg, image/gif" name="img_name" required>
          </div>
          <label for="exampleFormControlTextarea1" class="form-label">รายละเอียด</label>
          <div class="input-group flex-nowrap was-validated">
          
            <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3" required></textarea>
            
          </div>
          <br>
          <div class="input-group flex-nowrap was-validated">
            <span class="input-group-text" id="addon-wrapping">$</span>
            <input type="number" class="form-control" placeholder="ราคา" name="price" aria-label="Username" aria-describedby="addon-wrapping" required>
          </div>
          <div class="input-group flex-nowrap was-validated">
            <span class="input-group-text" id="addon-wrapping">Q</span>
            <input type="number" class="form-control" placeholder="จำนวน" name="qty" aria-label="Username" aria-describedby="addon-wrapping" required>
          </div>
          <br>
          <div class="input-group mb-3 was-validated">
            <label class="input-group-text" for="inputGroupSelect01">ประเภทของการจัดส่ง</label>
            <select class="form-select was-validated" name="free_pay" required>
              <option selected disabled value="">เลือก...</option>
              <?php
            
            $db = connect(); // ປະກາດຕົວແປມາຮັບຄ່າຕົວເຊື່ອມຕໍ່ຖານຂໍ້ມູນ
            $ct = $db->query("SELECT * FROM tb_payment ORDER BY id ASC");
            // ປະກາດຕົວແປເພື່ອຮັບຄ່າຂໍ້ມູນທີ່ດຶງມາ
            $result = $ct->fetchAll(PDO::FETCH_ASSOC);
              $i = 0;
              foreach ($result as $row) {
              $i++;
              ?>
              <option value="<?php echo $row['payment']; ?>"><?php echo $row['payment']; ?></option>
              <?php
            }
          
              ?>
            </select>
          </div>
          <input type="hidden" class="form-control" placeholder="ชื่อผลิตภัณฑ์" value="<?php echo $profile['id']; ?>" name="customer_id" aria-label="username" aria-describedby="addon-wrapping" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
        <button type="submit" name="save-product-btn" class="btn btn-primary">เพิ่มรายการ</button>
      </div>
      </form>
    </div>
  </div>
</div>

<br>
<h2>
  <center>จัดการข้อมูลผลิตภัณฑ์</center>
</h2>
<hr>
<?php
function compress_image($source_url, $destination_url, $quality)
{
  $info = getimagesize($source_url);

  if ($info['mime'] == 'image/jpeg')
    $image = imagecreatefromjpeg($source_url);

  elseif ($info['mime'] == 'image/gif')
    $image = imagecreatefromgif($source_url);

  elseif ($info['mime'] == 'image/png')
    $image = imagecreatefrompng($source_url);

  imagejpeg($image, $destination_url, $quality);
  return $destination_url;
}

if (isset($_POST['edit-product-btn'])) {
  $id = $_REQUEST['edit'];
  if (!empty($id)) {
    $category_id = htmlentities($_POST['category_id']);
    $name = htmlentities($_POST['name']);
    $description = htmlentities($_POST['description']);
    $price = htmlentities($_POST['price']);
    $qty = htmlentities($_POST['qty']);
    $file_name = $_FILES['img_name']['name'];
    if (!empty($file_name)) {
      $file_name = rand(000000001, 999999999) . '-' . md5($file_name) . '-' . $file_name;
      $replace = ['', '(', ')'];
      $file_name = str_replace($replace, '-', strtolower($file_name));
      $uploadPath = '../uploads/' . $file_name;
      $final_img = compress_image($_FILES['img_name']['tmp_name'], $uploadPath, 80);
      move_uploaded_file($final_img, $uploadPath);
      $db = connect();
      $stmt = $db->prepare("UPDATE tb_product SET category_id = :category_id, name = :name, description = :description, price = :price, qty = :qty,  img_name = :img_name WHERE product_id = :product_id");
      $stmt->bindParam("category_id", $category_id);
      $stmt->bindParam("name", $name);
      $stmt->bindParam("description", $description);
      $stmt->bindParam("price", $price);
      $stmt->bindParam("qty", $qty);
      $stmt->bindParam("img_name", $file_name);
      $stmt->bindParam("product_id", $id);
      if ($stmt->execute()) {
        echo '<div class="alert alert-success">แก้ไขเรียบร้อยแล้ว</div>';
      } else {
        echo '<div class="alert alert-danger">มีปัญหาในการแก้ไขข้อมูล โปรดลองอีกครั้ง</div>';
      }
    } else {
      $db = connect();
      $stmt = $db->prepare("UPDATE tb_product SET category_id = :category_id, name = :name, description = :description, price = :price, qty = :qty WHERE product_id = :product_id");
      $stmt->bindParam("category_id", $category_id);
      $stmt->bindParam("name", $name);
      $stmt->bindParam("description", $description);
      $stmt->bindParam("price", $price);
      $stmt->bindParam("qty", $qty);
      $stmt->bindParam("product_id", $id);
      if ($stmt->execute()) {
        echo '<div class="alert alert-success">แก้ไขเรียบร้อยแล้ว</div>';
      } else {
        echo '<div class="alert alert-danger">มีปัญหาในการแก้ไขข้อมูล โปรดลองอีกครั้ง</div>';
      }
    }
  }
}


// Add news
if (isset($_POST['save-product-btn'])) {
  $category_id = htmlentities($_POST['category_id']);
  $customer_id = htmlentities($_POST['customer_id']);
  $name = htmlentities($_POST['name']);
  $free_pay = htmlentities($_POST['free_pay']);
  $qty = htmlentities($_POST['qty']);
  $price = htmlentities($_POST['price']);
  $description = htmlentities($_POST['description']);
  $file_name = $_FILES['img_name']['name'];
  if (!empty($file_name)) {
    $file_name = rand(000000001, 999999999) . '-' . md5($_FILES['img_name']['name']) . '-' . $_FILES['img_name']['name']; 
    $replace = ['', '(', ')'];
    $file_name = str_replace($replace, '-', strtolower($file_name));
    $tempPath = $_FILES['img_name']['tmp_name'];
    $uploadPath = '../uploads/' . $file_name;
    $final_img = compress_image($tempPath, $uploadPath, 80);
    if (!empty($category_id) && !empty($customer_id) && !empty($name) && !empty($description) && !empty($file_name)) {

      move_uploaded_file($final_img, $uploadPath);
  
      $db = connect();
      $stmt = $db->prepare("INSERT INTO tb_product (category_id, product_id, customer_id, name, description, img_name, price, qty, free_pay, created) VALUES (:category_id, :product_id, :customer_id, :name, :description, :img_name, :price, :qty, :free_pay, NOW())");
      $stmt->bindParam("category_id", $category_id);
      $product_id = generateProId();
      $stmt->bindParam("product_id", $product_id);
      $stmt->bindParam("customer_id", $customer_id);
      $stmt->bindParam("name", $name);
      $stmt->bindParam("description", $description);
      $stmt->bindParam("price", $price);
      $stmt->bindParam("qty", $qty);
      $stmt->bindParam("free_pay", $free_pay);
      $stmt->bindParam("img_name", $file_name);
  
      if ($stmt->execute()) {
        echo '<div class="alert alert-success">เพิ่มรายการสำเร็จ  </div>';
        echo '<script type="text/javascript">
								setTimeout(function() {
									location.href = "?page=product";
								}, 2000);
							</script>';
      } else {
        echo '<div class="alert alert-danger">เกิดปัญหาในการเพิ่มรายการ โปรดตรวจสอบข้อมูลและลองอีกครั้ง</div>';
      }
    } else {
      echo '<div class="alert alert-danger">กรุณากรอกข้อมูลให้ครบถ้วน</div>';
    }
  }
}

if (isset($_REQUEST['method'])) {
  if ($_REQUEST['method'] == 'delete') {
    $id = $_REQUEST['id'];
    if (!empty($id)) {
      $img = getProductById($id);
      $db = connect();
      $stmt = $db->prepare("DELETE FROM tb_product WHERE product_id = :product_id");
      $stmt->bindParam("product_id", $id);
      if ($stmt->execute()) {
        unlink('../uploads/' . $img['img_name']);
        echo '<div class="alert alert-success">รายการที่มีรหัส ' . $id .  ' ถูกลบไปแล้ว</div>';
        echo '<script type="text/javascript">
        setTimeout(function() {
          location.href = "?page=product";
        }, 2000);
      </script>';
      } else {
        echo '<div class="alert alert-danger">มีปัญหาในการลบรายการ โปรดลองอีกครั้ง</div>';
      }
    }
  }
}
if (isset($_REQUEST['method'])) {
  if ($_REQUEST['method'] == 'edit') {
    $product = getProductById($_REQUEST['id']);
 

?>
    <!--ແກ້ໄຂລາຍການ-->

    <div class="alert alert-success" role="alert">
      <h4 class="alert-heading">แก้ไขรายการ</h4>
      <form method="post" action="?page=product&edit=<?php echo $_REQUEST['id']; ?>" class="form-horizontal form-label-left" enctype="multipart/form-data">
        <!--2-->
        <div class="form-group">
          <label class="control-label col-md-2 col-sm-2 col-xs-12"> หมวดหมู่ <span class="required">*</span></label>
          <div class="col-md-4 col-sm-4 col-xs-12">
            <select class="category_id form-control" id="category_id" name="category_id" required>
              <option value="">เลือก..</option>
              <?php
              $db = connect(); // ປະກາດຕົວແປມາຮັບຄ່າຕົວເຊື່ອມຕໍ່ຖານຂໍ້ມູນ
              $ct = $db->query("SELECT * FROM tb_category  ORDER BY name ASC");
              // ປະກາດຕົວແປເພື່ອຮັບຄ່າຂໍ້ມູນທີ່ດຶງມາ
              $result = $ct->fetchAll(PDO::FETCH_ASSOC);
              foreach ($result as $b) {
              ?>
                <option value="<?php echo $b['id']; ?>" <?php if ($product['category_id'] === $b['id']) {
                                                          echo 'selected="selected"';
                                                        } ?>><?php echo $b['name']; ?></option>';
              <?php
              }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2 col-sm-2 col-xs-12">ชื่อผลิตภัณฑ์ <span class="required">*</span></label>
          <div class="col-md-4 col-sm-4 col-xs-12">
            <input type="text" name="name" id="name" required="required" class="form-control " value="<?php echo $product['name']; ?>" placeholder="ຊື່ ຫຼື ຫົວຂໍ້ລາຍການ">
          </div>
          <label class="control-label col-md-2 col-sm-2 col-xs-12">ภาพ <span class="required">*</span></label>
          <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="input-group">
              <input type="file" accept="image/png, image/jpeg, image/gif" name="img_name" class="form-control" id="img_name" />
            </div>
            </span>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2 col-sm-2 col-xs-12">คำอธิบาย <span class="required">*</span></label>
          </label>
          <div class="col-md-4 col-sm-4 col-xs-12">
          <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3" required><?php echo $product['description']; ?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2 col-sm-2 col-xs-12">ราคา $ <span class="required">*</span></label>
          </label>
          <div class="col-md-4 col-sm-4 col-xs-12">
            <input type="number" name="price" id="price" required="required" class="form-control " value="<?php echo  $product['price']; ?>" autocomplete="off" placeholder="price">
          </div>

        </div>

        <div class="form-group">
          <label class="control-label col-md-2 col-sm-2 col-xs-12">จัดการ <span class="required">*</span></label>
          </label>
          <div class="col-md-4 col-sm-4 col-xs-12">
            <input type="number" name="qty" id="qty" required="required" class="form-control " value="<?php echo $product['qty']; ?>" autocomplete="off" placeholder="qty">
          </div>

        </div>
        <div class="form-group">
          <div class="col-md-2">

          </div>
          <br>
          <div class="col-md-10">
            <button type="submit" class="btn btn-primary btn-lg" name="edit-product-btn">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-save"></i> บันทึก&nbsp;&nbsp;&nbsp;&nbsp;</button>
            <a href="?page=product" class="btn btn-danger btn-lg" data-dismiss="modal">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right"></i> ออก&nbsp;&nbsp;&nbsp;&nbsp;</a>
          </div>
        </div>
      </form>
    </div>

  <?php
  }
} else {
  ?>
  <center>
    <div class="btn-group" role="group" aria-label="Basic outlined example">
      <?php 
      if ($_SESSION['username'] == 'admin') { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
      ?>
    <!-- <a href="?page=work" class="btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
      <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
      <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
    </svg> ตำแหน่ง</a> -->
    <?php } ?>
      <!-- <a href="?page=category" class="btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tags-fill" viewBox="0 0 16 16">
      <path d="M2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586V2zm3.5 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
      <path d="M1.293 7.793A1 1 0 0 1 1 7.086V2a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l.043-.043-7.457-7.457z"/>
    </svg> จัดการหมวดหมู่รายการ</a> -->
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">+ เพิ่มรายละเอียดสินค้า</button>
    </div>
  </center>
  <?php
}
if (isset($_REQUEST['method'])) {
  if ($_REQUEST['method'] == 'show') {
    $product = getProductById($_REQUEST['id']);
   
  ?>

    <!-- Modal  delete-->
    <center>
    <div class="form-group">
    คุณต้องการลบรายการ: <a style="color:red; font-weight: bold; font-size: large;"> <?php echo $product['name']; ?></a> หรือไม่ ?
      <br> ວັນທີ: <?php echo date_format(date_create($product['created']), "d.m.Y"); ?>
    </div>
    <a href="?page=product&method=delete&id=<?php echo $product['product_id']; ?>" class="btn btn-danger"><i class="fa fa-pencil"></i> ยืนยันการลบ</a>
    <a href="?page=product" class="btn btn-warning" data-dismiss="modal">
      <i class="fa fa-pencil"></i> ออก</a>
    <div class="form-group">
      <div class="col-md-2">
      </div>
      <div class="col-md-10">
        <hr>
      </div>
    </div>
    </center>
<?php
  }
}
?>
<br>
<h2 style="margin:10px">สินค้าเจ้าของร้าน</h2>
<div class="table-responsive">
  <table class="table table-striped table-sm display" id="example">
    <thead>
      <tr>
        <th scope="col">ลำดับ</th>
        <th scope="col">ภาพหลัก</th>
        <th scope="col">สินค่า</th>
        <th scope="col">หมวดหมู่</th>
        <th scope="col">ราคา</th>
        <th scope="col">จำนวนสินค้า</th>
        <th scope="col">จำนวนการดู</th>
        <th scope="col">วันที่</th>
        <th scope="col">จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $db = connect();
      $smt = $db->query("SELECT n.product_id, n.name, n.money, n.price, n.qty, n.view, n.customer_id, n.img_name, n.created, c.name AS category_name  FROM tb_product n INNER JOIN tb_category c ON (n.category_id = c.id) WHERE n.customer_id = $custom ORDER BY n.id DESC");
      $result = $smt->fetchAll(PDO::FETCH_ASSOC);
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
          <?php $string = $row['name'];
            if (strlen($string) > 20) {
                $trimstring = substr($string, 0, 50) . ' ...';
            } else {
                $trimstring = $string;
            }
            echo $trimstring;
            //Output : Lorem Ipsum is simply dum [readmore...][1]
            ?>
        </td>
          <td><?php echo $row['category_name']; ?></td>
          <td><?php echo $row['money']; ?><?php echo number_format($row['price']); ?></td> 
          <td><?php echo $row['qty']; ?></td>             
          <td><?php echo $row['view']; ?> ครั้ง</td>
          <td><?php echo date_format(date_create($row['created']),"วันที d.m.Y เวลา H:i นาที" ); ?></td>
          <td>
            <div class="btn-group" role="group" aria-label="Basic outlined example">
              <a href="?page=product&method=edit&id=<?php echo $row['product_id']; ?>" class="btn btn-outline-primary btn-sm">แก้ไข</a>
              <a href="?page=product&method=show&id=<?php echo $row['product_id']; ?>" class="btn btn-outline-danger btn-sm">ลบ</a>
            </div>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<hr>

<center>
<a href="?page=user_product" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-check-fill" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0m-.646 5.354a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
</svg> รายการลูกค้า</a>

  <a href="?page=home" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-heart" viewBox="0 0 16 16">
  <path d="M8 6.982C9.664 5.309 13.825 8.236 8 12 2.175 8.236 6.336 5.309 8 6.982Z"/>
  <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.707L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.646a.5.5 0 0 0 .708-.707L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
</svg> ย้อนกลับ</a> 
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