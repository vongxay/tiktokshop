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

$order_id = $_GET['order_id'];
$user_id = $_GET['user_id'];
$db = connect();
$stmt = $db->query("SELECT * FROM tb_customer WHERE id = $user_id");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {

?>

    <div class="container px-4 py-5" id="featured-3">

        <div class="container rounded bg-white mt-5 mb-5 shadow-lg p-3 mb-3 bg-body rounded">

            <?php

            if (isset($_POST['edit-product-btn'])) {

                if (isset($_POST['statust'])) {

                    $statust = htmlentities($_POST['statust']);

                    $db = connect();

                    $stmt = $db->prepare("UPDATE tb_order_detail SET statust = :statust WHERE user_product_id = $user_id AND order_id = '$order_id'");

                    $stmt->bindParam("statust", $statust);

                    if ($stmt->execute()) {
                        $stmt = $db->prepare("UPDATE tb_repay SET statust = 1 WHERE customer_id = $user_id AND order_id = '$order_id'");

                        // $stmt->bindParam("statust", $statust);
    
                        if ($stmt->execute()) {
                        unset($_SESSION['alert']);

                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">

                            <strong><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg> เสร็จแล้ว!</strong> แก้ไขเรียบร้อยแล้ว.

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        </div>';

                        echo '

                

                <script type="text/javascript">

                    setTimeout(function() {

                        location.href = "?page=report";

                    }, 1000);

                </script>

            ';

                    }

                }

            }
        }

            ?>

            <div class="row">

                <div class="col-md-3 border-right">

                    <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="../uploads/profile/<?php echo $row['img_name']; ?>"><span class="font-weight-bold"><?php echo $row['username']; ?></span><span class="text-black-50"><?php echo $row['store']; ?></span><span> </span></div>

                </div>

                <div class="col-md-5 border-right">

                    <form action="?page=user_detail&user_id=<?php echo $user_id; ?>&order_id=<?php echo $order_id; ?>" method="POST">

                        <div class="p-3 py-5">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h4 class="text-right">ข้อมูลสวนบุกคน</h4>

                            </div>

                            <div class="row mt-2">

                                <div class="col-md-6"><label class="labels">ชื่อบันชี</label><input type="text" class="form-control" placeholder="ชื่อบันชี" value="<?php echo $row['username']; ?>" readonly></div>

                                <div class="col-md-6"><label class="labels">ตะหลาด</label><input type="text" class="form-control" value="<?php echo $row['store']; ?>" placeholder="ตะหลาด" readonly></div>

                            </div>

                            <div class="row mt-3">

                                <div class="col-md-12"><label class="labels">เบอโทร</label><input type="text" class="form-control" placeholder="เบอโทร" value="<?php echo $row['mobile']; ?>" readonly></div>

                                <div class="col-md-12"><label class="labels">ที่อยู่ประจุบัน</label><input type="text" class="form-control" placeholder="ที่อยู่ประจุบัน" value="<?php echo $row['address']; ?>" readonly></div>

                                <div class="col-md-12"><label class="labels">จังหวัด</label><input type="text" class="form-control" placeholder="จังหวัด" value="<?php echo $row['province']; ?>" readonly></div>

                                <div class="col-md-12"><label class="labels">อำเภอ</label><input type="text" class="form-control" placeholder="อำเภอ" value="<?php echo $row['distric']; ?>" readonly></div>

                                <div class="col-md-12"><label class="labels">บ้าน</label><input type="text" class="form-control" placeholder="บ้าน" value="<?php echo $row['home']; ?>" readonly></div>

                            </div>

                            <?php
                           
                            $stmt = $db->query("SELECT SUM(price_qty) as sumprice FROM tb_order_detail WHERE order_id = '$order_id' AND user_product_id = $user_id");

                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($result as $show) {

                            ?>

                                <div class="row mt-3">

                                    <div class="col-md-6"><label class="labels">อนุมัติกานถอนเงิน</label><select class="form-select" id="inputGroupSelect01" name="statust" required>

                                            <option selected disabled required>กะรุนาเลือกสถานะ...</option>

                                            <option value="4">ถอนแล้ว</option>

                                            <!-- <option value="2">อยู้ระหว่างการจัดส่ง</option> -->
<!-- 
                                            <option value="3">สำเร็จ</option> -->

                                        </select></div>

                                    <div class="col-md-6"><label class="labels">ยอดขาย</label><input type="text" class="form-control" value="$<?php echo number_format($show['sumprice'] * 20 / 100); ?>" placeholder="ยอดขาย" readonly></div>

                                </div>

                            <?php } ?>



                            <div class="mt-5 text-center"><a href="?page=report" class="btn btn-dark profile-button">ย้อนกลับ</a> <button class="btn btn-primary profile-button" type="submit" name="edit-product-btn">บันทึกข้มูล</button></div>

                        </div>

                    </form>

                </div>

                <div class="col-md-4">

                    <div class="p-3 py-5">

                        <div class="d-flex justify-content-between align-items-center experience"><span>วันที่เข้าเป็นสมาชิก : <?php echo $row['created']; ?> </div><br>

                        <?php

                        $stmt = $db->query("SELECT SUM(price_qty) as sumprice FROM tb_order_detail WHERE statust = 0 AND user_product_id = $user_id");

                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $pr) {
                        ?>

                            <div class="col-md-12"><label class="labels">ยอดค้างชำระ</label><input type="text" class="form-control" placeholder="ยอดค้างชำระ" value="$<?php if(isset($pr['sumprice'])>0){echo number_format($pr['sumprice']); }else{ echo "0";} ?>" readonly></div> <br>

                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>

    </div>



<?php

}

?>