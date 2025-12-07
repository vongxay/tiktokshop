<?php
if (!isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ
    echo '<script> location.replace("?page=login"); </script>';
  }
  
  $profile = getCustomerBy($_SESSION['admin_loggedId']);
  $custom_id = $profile['id'];
  $user = "admin";
  $customer_name = $profile['username'];
  if($customer_name != $user){
      echo '<script> location.replace("?page=logout"); </script>';
  }
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

?>
  <div class="b-example-divider"></div>
  <br>
<div class="row">
  <div class="col-sm-4">
    <div class="card">
      <div class="card-body">
      <?php
    if (isset($_POST['changePassword'])) {
      
        $oldpassword = htmlentities($_POST['oldpassword']);
        $newpassword = htmlentities($_POST['newpassword']); 
        $confirmnewpassword = htmlentities($_POST['confirmnewpassword']);
        $store = htmlentities($_POST['store']);
        $file_name = $_FILES['img_name']['name'];
        if (!empty($file_name)) {
            $file_name = rand(000000001, 999999999) . '-' . md5($file_name) . '-' . $file_name;
            $replace = ['', '(', ')'];
            $file_name = str_replace($replace, '-', strtolower($file_name));
            $uploadPath = '../uploads/profile/' . $file_name;
            $final_img = compress_image($_FILES['img_name']['tmp_name'], $uploadPath, 80);
            move_uploaded_file($final_img, $uploadPath);
        if ($newpassword != $confirmnewpassword) {
            echo '<div class="alert alert-danger">รหัสผ่านใหม่ไม่ตรงกัน โปรดลองอีกครั้ง</div>';
        } else {
            $db = connect();
            $stmt = $db->prepare("UPDATE tb_customer SET password = :password, img_name = :img_name, store = :store WHERE id = :id");
            $password = sha1($newpassword);
            $id = $_SESSION['loggedId'];
            $stmt->bindParam("password", $password);
            $stmt->bindParam("store", $store);
            $stmt->bindParam("img_name", $file_name);
            $stmt->bindParam("id", $id);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success" class="close" data-dismiss="modal" aria-label="Close">เปลี่ยนรหัสผ่านแล้ว</div>';
                echo '
                    <script type="text/javascript">
                    setTimeout(function() {
                        location.href = "?page=logout";
                    }, 1000);
                    </script>
                ';
            } else {
                echo '<div class="alert alert-danger" class="close" data-dismiss="modal" aria-label="Close">กะรุนาเลือกรูปก่อน</div>';
            }
        }
     }else{
        echo '<div class="alert alert-danger" class="close" data-dismiss="modal" aria-label="Close">กะรุนาเลือกรูปก่อน</div>';
     }
    }
    ?>
        <style>
            input[type="file"] {
                display: none;
            }
        </style>
        

    <form role="form" action="?page=profile" method="POST" enctype="multipart/form-data">
    <center>
        <label class="custom-file-upload">
            <input type="file" name="img_name" />
            <div class="img"><img src="../uploads/profile/<?php echo $profile['img_name']; ?>" alt="Paris" style="width:70px" class="img-responsive img-circle">
                <br>
                เลือกรูปภาพสินค้าหลักของร้าน
        </label>
    </center>
    <br>
        
      
        <div class="form-group">
        <div class="col-auto">
          <div class="form-group">
              <input type="text" name="store" id="store" class="form-control text-align-center" value="<?php echo $profile['store'];  ?>" placeholder="ชื้ร้านค้าของคุณ" required readonly>
          </div>
          <br>
          <div class="form-group">
              <input type="password" name="oldpassword" id="store" class="form-control" placeholder="ระหัสผ่านเก่า" required>
          </div>
        </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <input type="password" name="newpassword" id="password" class="form-control" placeholder="รหัสผ่านใหม่">
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <input type="password" name="confirmnewpassword" id="password_confirmation" class="form-control" placeholder="ยืนยันรหัสผ่าน">
                </div>
            </div>
        </div>
        <br>
       
        <div class="d-grid gap-2 col-6 mx-auto">
          <button class="btn btn-primary" type="submit" name="changePassword">สร้างร้านค้าของคุณ</button>
        </div>
    </form>
      </div>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="card shadow-lg p-3 mb-3 bg-body rounded">
      <div class="card-body">
        <h5 class="card-title">รายการบัญชีของคุญ</h5>
        <p class="card-text"><li>User: <?php echo $profile['username'] ?></li></p>
        <div class="table-responsive">
        <table class="table" id="example">
        <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">ชื่อ</th>
                    <th scope="col">นามสกุล</th>
                    <th scope="col">ชื่อบัญชี</th>
                    <th scope="col">ร้านค้า</th>
                    <th scope="col">หมายเลขโทรศัพท์</th>
                    <th scope="col">วันและเวลาเข้าระบบ</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"><?php echo $profile['id']; ?></th>
                    <td><?php echo $profile['fname']; ?></td>
                    <td><?php echo $profile['lname']; ?></td>
                    <td><?php echo $profile['username']; ?></td>
                    <td><?php echo $profile['store']; ?></td>
                    <td><?php echo $profile['mobile']; ?></td>
                    <td><?php echo $profile['created']; ?></td>
                </tr>
            </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>
 <center> <a href="?page=home">หน้าหลัก</a></center>
</div>
<hr>