<br>
<?php
if (!isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ
    echo '<script> location.replace("?page=login"); </script>';
}
$profile = getCustomerBy($_SESSION['admin_loggedId']);
$custom_id = $profile['id'];

// ✅ ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
  session_destroy(); // ลบ session
  echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
  exit;
}
?>

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



if (isset($_REQUEST['method'])) {
  if ($_REQUEST['method'] == 'add') {
    if (isset($_POST['name']) && isset($_POST['customer_id'])) {
      $name = htmlentities($_POST['name']);
      $customer_id = htmlentities($_POST['customer_id']);
      $file_name = $_FILES['img_name']['name'];
      $db = connect();
      if (!empty($file_name)) {
        $file_name = rand(000000001, 999999999) . '-' . md5($_FILES['img_name']['name']) . '-' . $_FILES['img_name']['name']; 
        $replace = ['', '(', ')'];
        $file_name = str_replace($replace, '-', strtolower($file_name));
        $tempPath = $_FILES['img_name']['tmp_name'];
        $uploadPath = '../uploads/category/' . $file_name;
        $final_img = compress_image($tempPath, $uploadPath, 80);

      if (checkCTName($name)) {
        move_uploaded_file($final_img, $uploadPath);
        $stmt = $db->prepare("INSERT INTO tb_category (name, customer_id, img_name) VALUES (:name, :customer_id, :img_name)");
        $stmt->bindParam("name", $name);
        $stmt->bindParam("customer_id", $customer_id);
        $stmt->bindParam("img_name", $file_name);
        if ($stmt->execute()) {
          echo '<div class="alert alert-success d-flex align-items-center" role="alert">
              <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
              <div>
              เพิ่มข้อมูลสำเร็จ
              </div>
            </div>';
          echo '
							<script type="text/javascript">
								setTimeout(function() {
									location.href = "?page=category";
								}, 3000);
							</script>
						';
        }
      } else {
        echo '<div class="alert alert-warning d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
            <div>
            รายการนี้อยู่ในระบบแล้ว
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>';
      }
    }else{
      echo '<div class="alert alert-warning d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
            <div>
            กรุณาใส่รูปภาพ
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>';
       }
    }
  } else if ($_REQUEST['method'] == 'edit') {
    if (isset($_POST['name'])) {
      $name = htmlentities($_POST['name']);
      $file_name = $_FILES['img_name']['name'];
      $id = htmlentities($_REQUEST['id']);
      if (!empty($file_name)) {
        $file_name = rand(000000001, 999999999) . '-' . md5($file_name) . '-' . $file_name;
        $replace = ['', '(', ')'];
        $file_name = str_replace($replace, '-', strtolower($file_name));
        $uploadPath = '../uploads/category/' . $file_name;
        $final_img = compress_image($_FILES['img_name']['tmp_name'], $uploadPath, 80);
        move_uploaded_file($final_img, $uploadPath);
      $db = connect();
      $stmt = $db->prepare("UPDATE tb_category SET name = :name, img_name = :img_name WHERE id = :id");
      $stmt->bindParam("name", $name);
      $stmt->bindParam("img_name", $file_name);
      $stmt->bindParam("id", $id);
      if ($stmt->execute()) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg> เสร็จแล้ว!</strong> แก้ไขเรียบร้อยแล้ว.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
          echo '
							<script type="text/javascript">
								setTimeout(function() {
									location.href = "?page=category";
								}, 1000);
							</script>
						';
      }
    }
    }
  }
}

function checkCTName($name)
{
  $db = connect();
  $stmt = $db->prepare("SELECT COUNT(*) as count FROM tb_category WHERE name = :name");
  $stmt->bindParam("name", $name);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ((int)$result['count'] > 0) {
    return false;
  } else {
    return true;
  }
}
if (isset($_REQUEST['delete'])) { // ກວດສອບວ່າມີການກົດປຸ່ມລຶບບໍ
  $id = htmlentities($_REQUEST['delete']); // ປະກາດຕົວແປມາຮັບຄ່າລະຫັດປະເພດສິນຄ້າ
  if (!empty($id)) { // ຖ້າຫາກວ່າລະຫັດປະເພດສິນຄ້າບໍ່ຫວ່າງເປົ່າ
    $img = getCategoryById($id);
    $db = connect(); // ເຊື່ອມຕໍ່ຖານຂໍ້ມູນ
    // ກຽມ SQL Query
    $stmt = $db->prepare("DELETE FROM tb_category WHERE id = :id");
    // ຍັດຄ່າເຂົ້າໄປໃນຕົວແປ id
    $stmt->bindParam("id", $id);
    if ($stmt->execute()) { // ຖ້າຫາກວ່າການ run query ບໍ່ມີບັນຫາ
      unlink('../uploads/category/' . $img['img_name']);
      echo '<div class="alert alert-success d-flex align-items-center" role="alert">
      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
      <div>
      รายการที่มีรหัส '.$id.' ถูกลบไปแล้ว
      </div>
    </div>';
    } else { // ຖ້າຫາກວ່າການ run query ມີບັນຫາ
      echo '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> มีปัญหาในการลบหมวดหมู่ผลิตภัณฑ์ โปรดลองอีกครั้ง!!!!</div>';
    }
  }
}
if (isset($_REQUEST['method'])) {
  if ($_REQUEST['method'] == 'show') {
    $product = getProductC($_REQUEST['id']);
?>
    <!-- Modal  delete-->
    <center>
      <div class="form-group">
      คุณต้องการลบรายการ: <a style="color:red; font-weight: bold; font-size: large;"> <?php echo $product['name']; ?></a> หรือไม่ ?
        <br>
      </div>
      <a href="?page=category&delete=<?php echo $product['id']; ?>" class="btn btn-danger"><i class="fa fa-pencil"></i> ยืนยันการลบ</a>
      <a href="?page=category" class="btn btn-warning" data-dismiss="modal">
        <i class="fa fa-pencil"></i> ອອກ</a>
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
}else{
?>
<center>
  <div class="btn-group" role="group" aria-label="Basic outlined example">
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">+ เพิ่มหมวดหมู่</button>
  </div>
</center>
<hr>
<?php }
if(isset($_REQUEST['method'])){
  if($_REQUEST['method'] == 'edit'){
  $product = getProductC($_REQUEST['id']);
  
?>
<center>
    <!-- Modal edit category-->
    <div class="alert alert-primary" role="alert">
    <form action="?page=category&method=edit&id=<?php echo $_REQUEST['id']; ?>" method="POST" enctype="multipart/form-data">
      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <label for="inputPassword6" class="col-form-label">แก้ไขหมวดหมู่</label>
        </div>
        <div class="col-auto">
        <div class="input-group">
              
              <input type="file" aria-label="First name" name="img_name" required class="form-control">
              <span class="input-group-text">names</span>
              <input type="text" aria-label="Last name" name="name" value="<?php echo $product['name']; ?>" class="form-control" placeholder="category name..." >
            </div>
        </div>
        <br>
        
        <div class="col-auto">
          <span id="passwordHelpInline" class="form-text">
          <button type="submit" name="edit-product-btn" class="btn btn-primary">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-save"></i> บันทึกไว้&nbsp;&nbsp;&nbsp;&nbsp;</button>  
          <a href="?page=category" class="btn btn-danger" data-dismiss="modal">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right"></i> ออก&nbsp;&nbsp;&nbsp;&nbsp;</a>
          </span>
        </div>
      </div>
      </form>
    </div>
    
    <br> 
</center>
<?php 
  }
}
?>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="?page=category&method=add" method="POST" role="form" class="form-horizontal form-label-left" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">เพิ่มข้อมูลหมวดหมู่</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 align-items-center">
            <div class="col-auto">
              
            <div class="input-group">
              
              <input type="file" aria-label="First name" name="img_name" required class="form-control">
              <span class="input-group-text">names</span>
              <input type="text" aria-label="Last name" name="name" class="form-control" placeholder="category name..." >
            </div>
              <div class="input-group mb-3 was-validated">

            </div>
            </div>
            <br>
            <div class="col-auto">
              <input type="hidden" name="customer_id" id="user_name" value="<?php echo $custom_id; ?>" class="form-control" aria-describedby="passwordHelpInline">
            </div>
            <div class="col-auto">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
          <button type="submit" id="add_bct_btn" class="btn btn-primary">บันทึก</button>
        </div>
      </div>
    </form>
  </div>
</div>


<div class="table-responsive">
  <table class="table table-striped table-sm table-hover   display" id="example" >
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col" style="width: 500px;">ภาพ</th>
        <th scope="col" style="width: 120px;">ประเภทสินค้า</th>
        <th scope="col">รหัสผู้ประกอบการ</th>
        <th scope="col">จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $db = connect();
      $smt = $db->query("SELECT * FROM tb_category ORDER BY id DESC");
      $result = $smt->fetchAll(PDO::FETCH_ASSOC);
      $i = 0;
      foreach ($result as $row) {
      $i++;

      ?>
        <tr>
          <th scope="row"><?php echo $i; ?></th>
          <td><div class="col-md-1 text-left">
              <img src="../uploads/category/<?php echo $row['img_name']; ?>" alt=""
                  class="img-fluid d-none d-md-block rounded mb-2 shadow ">
          </div></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['customer_id']; ?></td>
          <td>
     
            <a href="?page=category&method=edit&id=<?php echo $row['id'];  ?>" class="btn btn-primary btn-sm">แก้ไข</a>
            <a href="?page=category&method=show&id=<?php echo $row['id']; ?>" type="button" class="btn btn-secondary btn-sm">ลบออก</a>
      
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <hr>
  <center>
    <ol><a href="?page=product"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5z" />
        </svg> ย้อนกลับ</a> || <a href="?page=home"> หน้าแรก <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-right" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z" />
        </svg></a></ol>
  </center>
  <hr>
</div>
</div>