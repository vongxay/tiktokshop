
<div class="product-box">
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
<div class="product-box">


    <?php
    if (isset($_POST['edit-product-btn'])) {
        if (isset($_POST['statust'])) {
            $statust = htmlentities($_POST['statust']);
            $id = htmlentities($_REQUEST['id']);
            $db = connect();
            $stmt = $db->prepare("UPDATE tb_order_detail SET statust = :statust WHERE id = :id");
            $stmt->bindParam("statust", $statust);
            $stmt->bindParam("id", $id);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg> เสร็จแล้ว!</strong> แก้ไขเรียบร้อยแล้ว.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
                echo '
                <script type="text/javascript">
                    setTimeout(function() {
                        location.href = "?page=home";
                    }, 1000);
                </script>
            ';
            }
        }
    }
    $serow = "ร่อดำเนีนการ";
    $one = "กำลังเตรียมการ";
    $thow = "อยู่ระหว่างการจัดส่ง";
    $three = "สำเร็จ";
    $st = array($serow, $one, $thow, $three);
    if (isset($_REQUEST['method'])) {
        if ($_REQUEST['method'] == 'edit') {
            $product = getProductC_detail($_REQUEST['id']);
            $product_detil_id = $_GET['id'];

            $db = connect();
            $smt = $db->query("SELECT statust FROM tb_order_detail WHERE id = $product_detil_id");
            $result = $smt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {

    ?>
                <div class="alert alert-success" role="alert">
                    <center>
                        <h3>อับเดดสถานะ สินค้า ของคุณ! ปัดจุบัน: <a style="color:orangered"><?php if ($row['statust'] == 1) {
                                                                                                echo $one;
                                                                                            } elseif ($row['statust'] == 2) {
                                                                                                echo $thow;
                                                                                            } elseif ($row['statust'] == 3) {
                                                                                                echo $three;
                                                                                            } else {
                                                                                                echo $serow;
                                                                                            }  ?></a></h3>
                    </center>
                <?php } ?>
                <form action="?page=home&method=edit&id=<?php echo $_REQUEST['id']; ?>" method="POST">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelect01">สะภานะสินคัา</label>
                        <select class="form-select" id="inputGroupSelect01" name="statust" required>
                            <option selected disabled>กะรุนาเลือกสถานะ...</option>
                            <option value="1">กำลังเตรียมการ</option>
                            <option value="2">อยู้ระหว่างการจัดส่ง</option>
                            <option value="3">สำเร็จ</option>
                        </select>
                    </div>
                    <center>
                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-primary" name="edit-product-btn" type="submit">อับเดด</button>
                            <a href="?page=home" class="btn btn-danger" type="button">ยกเลีก</a>
                        </div>
                    </center>
                </form>
                </div>
            <?php
        }
    } else {
            ?>
            <center>
                <form action="?page=order" method="POST">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="inputPassword6" class="col-form-label">ระหว่าง</label>
                        </div>
                        <div class="col-auto">
                            <input type="date" id="inputPassword6" name="start" class="form-control" value="<?php if (isset($_POST['start'])) {
                                                                                                                echo $_POST['start'];
                                                                                                            }; ?>" aria-describedby="passwordHelpInline" required>
                        </div>

                        <div class="col-auto">
                            <input type="date" id="inputPassword6" name="end" value="<?php if (isset($_POST['end'])) {
                                                                                            echo $_POST['end'];
                                                                                        }; ?>" class="form-control" aria-describedby="passwordHelpInline" required>
                        </div>
                        <div class="col-auto">
                            <span id="passwordHelpInline" class="form-text">
                                <button type="submit" class="btn btn-primary">ค้นหา</button>
                            </span>
                        </div>
                    </div>
                </form>
            </center>
        <?php } ?>
        <hr>
        <div style="overflow-x:auto;" class="table table-hover shadow-lg p-3 mb-3 bg-body rounded">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col w-100">ระหัส</th>
                    <th scope="col">สินค้า</th>
                    <th scope="col">จำนวน</th>
                    <th scope="col">รวมราคา</th>
                    <th scope="col">วันที่</th>
                    <th scope="col">สถานะ</th>
                    <th scope="col">จัดกาน</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if (isset($_POST['start'])) {
                    $start = $_POST['start'];
                    $end = $_POST['end'];

                    $db = connect();
                    $smt = $db->query("SELECT o.price_qty, o.statust, o.qty, o.customer_id as custom, o.order_id, o.created, o.product_id, p.id as product_id, p.customer_id, p.money, p.name as product_name
                    FROM tb_order_detail o 
                    INNER JOIN tb_product p
                        ON o.product_id = p.product_id
                    WHERE p.customer_id = '$custom_id' AND o.created BETWEEN '$start 00:00:00' AND '$end 00:00:00' ORDER BY o.order_id DESC;");
                    $result = $smt->fetchAll(PDO::FETCH_ASSOC);
                    $i = 0;
                    foreach ($result as $row) {
                    $i++;
                        
                ?>
                        <tr>
                            <th scope="row"><?php echo $i; ?></th>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['qty']; ?></td>
                            <td><?php echo number_format($row['price_qty']); ?></td>
                            <td><?php echo date_format(date_create($row['created']), "d-m-Y เวลา h:i:s"); ?></td>
                            <?php if ($row['statust'] == 0) { ?>
                                <td class="table-danger">ค้างชำระ</td>
                            <?php } elseif ($row['statust'] == 1) { ?>
                                <td class="table-warning">กำลังเตรียมการ</td>
                            <?php } elseif ($row['statust'] == 2) { ?>
                                <td class="table-info">อยู่ระหว่างการจัดส่ง</td>
                            <?php } elseif ($row['statust'] == 3) { ?>
                                <td class="table-success">สำเร็จ</td>
                            <?php } ?>
                            <td><a href="?page=order_detail&id=<?php echo $row['order_id']; ?>&product_id=<?php echo $row['product_id']; ?>&customer_id=<?php echo $row['custom']; ?>&name=<?php echo $row['product_name']; ?>&order_date=<?php echo $row['created']; ?>" class="banner-btn"><i class="fa fa-eye"></i> รายระเอียด</a></td>
                        </tr>
                    <?php
                    }
                } else {

                    $db = connect();
                    $smt = $db->query("SELECT o.price_qty, o.statust, o.id, o.qty, o.customer_id as custom, o.order_id, o.created, o.product_id, p.customer_id, p.name as product_name
                    FROM tb_order_detail o 
                    INNER JOIN tb_product p
                    ON o.product_id = p.product_id
                    WHERE p.customer_id = '$custom_id' AND o.statust = 0 ORDER BY o.order_id DESC");
                    $result = $smt->fetchAll(PDO::FETCH_ASSOC);
                    $i = 0;
                    foreach ($result as $row) {
                    $ostatust = $row['statust'];
                    if($ostatust == 0) {
                    $i++;  
                    $_SESSION['alert'] = $i;  
                    ?>
                   
                        <tr>
                            <th scope="row"><?php echo $i; ?></th>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['qty']; ?></td>
                            <td><?php echo number_format($row['price_qty']); ?></td>
                            <td><?php echo date_format(date_create($row['created']), "d-m-Y เวลา h:i:s"); ?></td>
                            <?php if ($row['statust'] == 0) { ?>
                                <td class="table-danger">ค้างชำระ</td>
                            <?php } elseif ($row['statust'] == 1) { ?>
                                <td class="table-warning">กำลังเตรียมการ</td>
                            <?php } elseif ($row['statust'] == 2) { ?>
                                <td class="table-info">อยู่ระหว่างการจัดส่ง</td>
                            <?php } elseif ($row['statust'] == 3) { ?>
                                <td class="">สำเร็จ</td>
                            <?php } ?>
                            <td><a href="?page=order_detail&id=<?php echo $row['order_id']; ?>&product_id=<?php echo $row['product_id']; ?>&customer_id=<?php echo $row['custom']; ?>&name=<?php echo $row['product_name']; ?>&order_date=<?php echo $row['created']; ?>" class="banner-btn"><i class="fa fa-eye"></i> รายระเอียด</a></td>
                        </tr>
                             
                <?php }
                } 
            } ?>
            </tbody>
        </table>
</div>     
