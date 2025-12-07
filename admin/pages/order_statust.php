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
<div class="table-responsive">
<div class="container px-4 py-5" id="featured-3">
<table class="table" id="example">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">ระหัด Order</th>
      <th scope="col">ผู้สังชื้</th>
      <th scope="col">สะถานที่ส่ง</th>
      <th scope="col">บ้าน</th>
      <th scope="col">อำเถอ</th>
      <th scope="col">จังหวัด</th>
      <th scope="col">เบอโทร</th>
      <th scope="col">วันทีสังชื้</th>
      <th scope="col">สถานะ</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $profile = getCustomerBy($_SESSION['admin_loggedId']);
        $custom_id = $profile['id'];
         $db = connect();
         $smt = $db->query("SELECT * FROM tb_customer c INNER JOIN tb_order od ON od.customer_id = c.id ");
         $result = $smt->fetchAll(PDO::FETCH_ASSOC);
         $i = 0;
         foreach ($result as $row) {
         $i++;
    ?>
    
    <tr class="link">
      <th scope="row"><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $i; ?> </a></th>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $row['order_id']; ?></a></td>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $row['username']; ?></a></td>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $row['address']; ?></a></td>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $row['home']; ?></a></td>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $row['distric']; ?></a></td>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $row['province']; ?></a></td>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $row['mobile']; ?></a></td>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>"><?php echo $row['order_date']; ?></a></td>
      <td><a class="link" href="?page=order_detail&id=<?php echo $row['order_id']; ?>">Yess</a></td>
    </tr>
   
    <?php } ?>
   
  </tbody>
</table>
<a href="?page=home">< ยอนกลับ</a>
</div>
</div>

<style>
    .link{
        color: black;
    }
    .link:hover{
        color: blue;
    }
</style>
<?php

$profile = getCustomerBy($_SESSION['admin_loggedId']);
$custom = $profile['id'];

?>
<div class="container px-4 py-5" id="featured-3">
    <center>
        <h2>สรุปรายการ ของคุณ</h2>
    </center>
    <form action="?page=order_statust" method="POST">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="inputPassword6" class="col-form-label">ระหว่าง</label>
            </div>
            <div class="col-auto">
                <input type="date" id="inputPassword6" name="start" class="form-control"
                    value="<?php if(isset($_POST['start'])){echo $_POST['start'];} ;?>"
                    aria-describedby="passwordHelpInline" required>
            </div>

            <div class="col-auto">
                <input type="date" id="inputPassword6" name="end"
                    value="<?php if(isset($_POST['end'])){echo $_POST['end'];} ;?>" class="form-control"
                    aria-describedby="passwordHelpInline" required>
            </div>
            <div class="col-auto">
                <span id="passwordHelpInline" class="form-text">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </span>
            </div>
        </div>
    

    </form>
    <br>
    <div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">ระหัส ออเดอ</th>
                <th scope="col">สินค้า</th>
                <th scope="col">จำนวยขาย</th>
                <th scope="col">รวมราคา</th>
                <th scope="col">วันที</th>
                <th scope="col">สถานะ</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if(isset($_POST['start'])){
                  $start = $_POST['start']; 
                  $end = $_POST['end']; 
               
                $profile = getCustomerBy($_SESSION['admin_loggedId']);
                $custom_id = $profile['id'];
                $db = connect();
                $stmt = $db->query("SELECT od.product_id, od.qty, od.price_qty, od.order_id, od.statust, p.name, od.created FROM tb_order_detail od INNER JOIN tb_product p ON od.product_id = p.product_id WHERE od.created BETWEEN '$start 00:00:00' AND '$end 00:00:00' ORDER BY od.id DESC");
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $i=0;
                foreach($result as $row){
                $i++;
                $string = $row['name'];
                $string = strip_tags($string);
                if (strlen($string) > 20) {
    
                    // truncate string
                    $stringCut = substr($string, 0, 25);
                    $endPoint = strrpos($stringCut, ' ');
    
                    //if the string doesn't contain any space then it will cut without word basis.
                    $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                }
            ?>
            <tr>
                <th scope="row"><?php echo $i; ?></th>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo $string; ?></td>
                <td><?php echo $row['qty']; ?></td>
                <td><?php echo number_format($row['price_qty']) ; ?></td>
                <td><?php echo date_format(date_create($row['created']),"วันที d เดือน m ปี Y เวลา h:i:s"); ?></td>
                <?php if ($row['statust'] == 0) { ?>
                                <td class="table-danger">ค้างชำระ</td>
                            <?php } elseif ($row['statust'] == 1) { ?>
                                <td class="table-dark">ถอนแล้ว</td>
                            <?php } elseif ($row['statust'] == 2) { ?>
                                <!-- <td class="table-info">อยู่ระหว่างการจัดส่ง</td> -->
                            <?php } elseif ($row['statust'] == 3) { ?>
                                <td class="table-info">สำเร็จ</td>
                            <?php } ?>   
            </tr>
            <?php } ?>
            <tr style="font-size: 17px;">
                <th scope="row"></th>
                <td colspan="2"></td>
                <td>รายกานขายทังหมด</td>
                <?php
                     $db = connect();
                     $stmt = $db->query("SELECT SUM(price_qty) as allsum FROM tb_order_detail od INNER JOIN tb_product p ON od.product_id = p.product_id WHERE od.statust = 3 AND od.created BETWEEN '$start 00:00:00' AND '$end 00:00:00' ORDER BY od.id DESC");
                     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                     $i=0;
                     foreach($result as $row){
                     $i++;
                ?>
                <td style="font-size: 20px; color:green">รวมราคา: <?php echo number_format($row['allsum']) ; ?> </td>
                <?php } 
                 }else{
                   
                 
                ?>
            </tr>
        </tbody>
    </table>
    </div>
    <?php

    echo '<div class="alert alert-success d-flex align-items-center" role="alert" style="font-size:20px">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
    <div>
    คันหารายการขายเพือสะรุบรายได้
    </div>
    </div>';
                    }
    ?>
  
    
</div>

