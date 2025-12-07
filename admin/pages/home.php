<?php

// ต้องมีไฟล์ connect() และ getCustomerBy()
// สมมติว่าไฟล์นี้รวมอยู่ในระบบหลักแล้ว

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

// --- ✅ ส่วนที่เพิ่ม: ระบบลบออเดอร์ (Method: delete) ---
if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'delete' && isset($_REQUEST['id'])) {
    $order_detail_id = htmlentities($_REQUEST['id']);
    $db = connect();

    try {
        // ลบรายการจาก tb_order_detail
        $stmt = $db->prepare("DELETE FROM tb_order_detail WHERE id = :id");
        $stmt->bindParam(':id', $order_detail_id);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg> เสร็จแล้ว!</strong> ลบรายการออเดอร์เรียบร้อยแล้ว.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';

            echo '<script type="text/javascript">
                setTimeout(function() {
                    location.href = "?page=home"; // กลับไปหน้าหลัก
                }, 1000);
            </script>';
        } else {
            echo '<div class="alert alert-danger" role="alert">❌ เกิดข้อผิดพลาดในการลบรายการ!</div>';
        }
    } catch (PDOException $e) {
        // ควรมีโค้ดจัดการ error เช่น บันทึก Log
        echo '<div class="alert alert-danger" role="alert">❌ เกิดข้อผิดพลาดทางฐานข้อมูล: ไม่สามารถลบได้</div>';
    }
    exit; // ออกจากการทำงาน PHP
}
// --- จบส่วนที่เพิ่ม ---

?>


<h1 class="visually-hidden">Features examples</h1>

<div class="container px-4 py-5" id="featured-3">

    <h2 class="pb-2 border-bottom" style="display:flex; justify-content:space-between; align-items:center;">
        <span>
            Admin Page: <a href="?page=profile" style="color: green;"><?php echo $profile['username']; ?></a>
            || <a href="?page=logout" style="color:red">Logout</a>
        </span>

    </h2>
    <center>
        <a href="?page=usdt"
            style="padding:5px 10px; background-color:#4CAF50; color:white; border:none; border-radius:4px; cursor:pointer;"
            onclick="updateUSDT()">
            อัปเดต USDT
        </a>
    </center>

    <center>
        <a href="?page=product" class="btn btn-primary" style="margin:5px"><svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" class="bi bi-bag-plus-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0M8.5 8a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V12a.5.5 0 0 0 1 0v-1.5H10a.5.5 0 0 0 0-1H8.5z" />
            </svg><br> รายการสินค้า</a>
        <a href="?page=order_statust" class="btn btn-primary" style="margin:5px"><svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
            </svg><br>การเคลือนไหว</a>
        <a href="?page=report" class="btn btn-primary position-relative" style="margin:5px"><svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0" />
                <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z" />
                <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z" />
                <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567" />
            </svg><br>รายการถ้อนเงิน
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?php
                $db = connect();

                // นับรายการถอนเงินที่รออนุมัติ (status = 0)
                $stmt = $db->query("SELECT COUNT(w_id) as count FROM tb_wallet WHERE status = 1");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                echo "<strong style='color:#fff;'>" . ($result['count'] ?? 0) . "</strong>";
                ?>

            </span></a>
        <script>
            function fetchMessageCount() {
                fetch('get_count.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error:', data.error);
                            return;
                        }
                        document.getElementById('messageCount').textContent = data.count;
                    })
                    .catch(error => console.error('Error fetching message count:', error));
            }

            // เรียก fetchMessageCount ทุก 2 วินาที
            setInterval(fetchMessageCount, 2000);

            // เรียกครั้งแรกทันที
            fetchMessageCount();
        </script>
        <a href="?page=chat_room" class="btn btn-primary" style="margin:5px" c:\xampp\htdocs\binance\admin\pages\chat_room.php><svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
            </svg><br>ห้องสนทนา <span class="badge bg-danger"><span id="messageCount">0</span></a>

        <a href="?page=customer" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
            </svg><br>ข้อมูลลูกค้า</a>
        <?php
        // สมมติว่ามี $customer_id อยู่แล้ว
        $db = connect();

        // ดึงยอดรวมที่รออนุมัติ (status = 0)
        $stmt = $db->prepare("SELECT COUNT(b_id) as pending_amount FROM tb_back WHERE status = 0");

        $stmt->execute();
        $pending = $stmt->fetch(PDO::FETCH_ASSOC);
        $pending_amount = $pending['pending_amount'] ?? 0;
        ?>

        <a href="?page=wallet_history" class="btn btn-primary" style="text-align:center; display:inline-block; width:140px; padding:7px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                <path d="M12 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h10zM2 0a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V3a3 3 0 0 0-3-3H2z" />
            </svg>
            <br>
            ลูกค้าเติมเงิน <span class="badge bg-danger" id="messageCount"><?php echo number_format($pending_amount); ?></span>
        </a>

    </center>


</div>



<div class="b-example-divider"></div>

<br>



<?php if (!empty($latest['img_name'])): ?>
    <img src="../pages/uploads/usd/<?php echo $latest['img_name']; ?>" alt="USD Image" style="height:40px; margin-left:10px;">
<?php endif; ?>

<div class="container px-4 py-5" id="featured-3">

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
            unset($_SESSION['alert']);

            // ✅ ทำเฉพาะเมื่อสถานะ = 3
            if ($statust == 3) {
                $sql = "SELECT od.price_qty, p.customer_id AS owner_id
                        FROM tb_order_detail od 
                        INNER JOIN tb_product p ON od.product_id = p.product_id 
                        WHERE od.id = :id LIMIT 1";
                $q = $db->prepare($sql);
                $q->execute(['id' => $id]);
                $order = $q->fetch(PDO::FETCH_ASSOC);

                if ($order) {
                    $price_qty   = $order['price_qty']; 
                    // $profit      = $price_qty;      
                    $total_income = $price_qty;   // ✅ ยอดขาย + กำไร 40%

                    $owner_id = $order['owner_id']; 

                    if (empty($owner_id)) {
                        die("❌ ไม่พบ customer_id ของเจ้าของสินค้า");
                    }

                    // ตรวจสอบ wallet ของเจ้าของ
                    $check = $db->prepare("SELECT * FROM tb_wallet WHERE customer_id = :cid LIMIT 1");
                    $check->execute(['cid' => $owner_id]);
                    $wallet = $check->fetch(PDO::FETCH_ASSOC);

                    if ($wallet) {
                        $new_balance = $wallet['w_price'] + $total_income;
                        $updateWallet = $db->prepare("UPDATE tb_wallet SET w_price = :balance WHERE customer_id = :cid");
                        $updateWallet->execute(['balance' => $new_balance, 'cid' => $owner_id]);
                    } else {
                        $insertWallet = $db->prepare("INSERT INTO tb_wallet (customer_id, w_price) VALUES (:cid, :balance)");
                        $insertWallet->execute(['cid' => $owner_id, 'balance' => $total_income]);
                    }
                }
            }

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

    

    $serow = "ค้างช้ำระ";

    $one = "อยู่ระหว่างการจัดส่ง";

    $thow = "อยู่ระหว่างการจัดส่ง";

    $three = "ชำระแล้ว";

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
                            <option value="0">ค้างชำระ</option>
                            <option value="2">อยู่ระหว่างการจัดส่ง</option>
                            <option value="3">จัดส่งเสร็จ</option>

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

                <form action="?page=home" method="POST">

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


        <?php if (!empty($latest['img_name'])): ?>
            <img src="../pages/uploads/usd/<?php echo $latest['img_name']; ?>" alt="USD Image" style="height:40px; margin-left:10px;">
        <?php endif; ?>

        <div style="overflow-x:auto;" class="table table-hover shadow-lg p-3 mb-3 bg-body rounded">

            <table class="table table-hover shadow-lg p-3 mb-3 bg-body rounded" id="example">

                <thead>

                    <tr>

                        <th scope="col">No</th>

                        <th scope="col">ภาพ</th>

                        <th scope="col">สินค้า</th>

                        <th scope="col">เจ้าของสินค้า</th>

                        <th scope="col">จำนวน</th>

                        <th scope="col">รวมราคา</th>

                        <th scope="col">วันที่ซื้อ</th>

                        <th scope="col">สถานะ</th>

                        <th scope="col">จัดการ</th>
                        
                        <th scope="col">ลบ</th> </tr>

                </thead>

                <tbody>

                    <?php



                    if (isset($_POST['start'])) {

                        $start = $_POST['start'];

                        $end = $_POST['end'];

                        $db = connect();

                        $smt = $db->query("SELECT o.id, o.price_qty, o.statust, o.qty, o.customer_id as custom, o.order_id, o.created, o.product_id, p.id as product_id, p.img_name, p.customer_id, p.money, p.name as product_name, c.username

            FROM tb_order_detail o 

            INNER JOIN tb_product p

                ON o.product_id = p.product_id INNER JOIN tb_customer c ON p.customer_id = c.id

            WHERE o.created BETWEEN '$start 00:00:00' AND '$end 00:00:00' ORDER BY o.order_id DESC;");

                        $result = $smt->fetchAll(PDO::FETCH_ASSOC);

                        $i = 0;

                        foreach ($result as $row) {

                            $i++;



                            $string = $row['product_name'];

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

                                <td>
                                    <div class="img"><img src="../uploads/<?php echo $row['img_name']; ?>" alt="Paris" style="width:50px" class="img-responsive img-circle">
                                </td>

                                <td><?php echo $string; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['qty']; ?></td>

                                <td>$<?php echo number_format($row['price_qty']); ?></td>

                                <td><?php echo date_format(date_create($row['created']), "วันที d-m-Y เวลา h:i:s"); ?></td>

                                <?php if ($row['statust'] == 0) { ?>

                                    <td class="table-danger">ค้างชำระ</td>

                                <?php } elseif ($row['statust'] == 1) { ?>

                                    <td class="table-success">ชำระแล้ว รอจัดส่ง</td>

                                <?php } elseif ($row['statust'] == 2) { ?>

                                    <td class="table-info">อยู่ระหว่างการจัดส่ง</td>

                                <?php } elseif ($row['statust'] == 3) { ?>

                                    <td class="">เสร็จ</td>


                                <?php } elseif ($row['statust'] == 4) { ?>

                                    <td class="">ถอนแล้ว</td>

                                <?php } ?>

                                <?php

                                // คอลัมน์ "จัดการ" เดิม
                                if ($row['statust'] == 3 || $row['statust'] == 4) {

                                ?>

                                    <td> <a href="?page=home&method=edit&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> อับเดดสถานะ</a></td>

                                <?php

                                } else {

                                ?>

                                    <td> <a href="?page=home&method=edit&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> อับเดดสถานะ</a></td>

                                <?php } ?>

                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                        <i class="bi bi-trash-fill"></i> ลบ
                                    </button>
                                </td>

                            </tr>

                        <?php

                        }
                    } else {
                        $db = connect();
                        $smt = $db->query("SELECT o.id, o.price_qty, o.statust, o.id, o.qty, o.customer_id as custom, o.order_id, o.created, o.product_id, p.customer_id, p.img_name, p.name as product_name, c.username

                    FROM tb_order_detail o 

                    INNER JOIN tb_product p

                    ON o.product_id = p.product_id INNER JOIN tb_customer c ON p.customer_id = c.id

                    ORDER BY o.order_id DESC");

                        $result = $smt->fetchAll(PDO::FETCH_ASSOC);

                        $i = 0;

                        foreach ($result as $row) {

                            $i++;

                            $string = $row['product_name'];

                            $string = strip_tags($string);

                            if (strlen($string) > 20) {



                                // truncate string

                                $stringCut = substr($string, 0, 50);

                                $endPoint = strrpos($stringCut, ' ');



                                //if the string doesn't contain any space then it will cut without word basis.

                                $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                            }

                        ?>

                            <tr>

                                <th scope="row"><?php echo $i; ?></th>

                                <td>
                                    <div class="img"><img src="../uploads/<?php echo $row['img_name']; ?>" alt="Paris" style="width:50px" class="img-responsive img-circle">
                                </td>

                                <td><?php echo $string; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['qty']; ?></td>

                                <td>$<?php echo number_format($row['price_qty']); ?></td>

                                <td><?php echo date_format(date_create($row['created']), "วันที d-m-Y เวลา h:i:s"); ?></td>

                                <?php if ($row['statust'] == 0) { ?>

                                    <td class="table-danger">ค้างชำระ</td>

                                <?php } elseif ($row['statust'] == 1) { ?>

                                    <td class="table-success">ชำระแล้ว รอจัดส่ง</td>

                                <?php } elseif ($row['statust'] == 2) { ?>

                                    <td class="table-info">อยู่ระหว่างการจัดส่ง</td>

                                <?php } elseif ($row['statust'] == 3) { ?>

                                    <td class="">เสร็จ</td>


                                <?php } elseif ($row['statust'] == 4) { ?>

                                    <td class="">ถอนแล้ว</td>

                                <?php } ?>

                                <?php

                                // คอลัมน์ "จัดการ" เดิม
                                if ($row['statust'] == 3 || $row['statust'] == 4) {

                                ?>
                                    <td> <a href="?page=home&method=edit&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> อับเดดสถานะ</a></td>

                                <?php

                                } else {

                                ?>

                                    <td> <a href="?page=home&method=edit&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> อับเดดสถานะ</a></td>

                                <?php } ?>

                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                        <i class="bi bi-trash-fill"></i> ลบ
                                    </button>
                                </td>

                            </tr>



                    <?php }
                    } ?>

                </tbody>

            </table>

        </div>

</div>

</div>

<script>
    function confirmDelete(id) {
        if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการออเดอร์นี้? การกระทำนี้ไม่สามารถยกเลิกได้ และจะไม่มีผลต่อยอดเงินของลูกค้าหรือเจ้าของสินค้า!')) {
            // หากผู้ใช้กดยืนยัน ให้เปลี่ยนหน้าไปยัง URL ที่มี method=delete
            window.location.href = '?page=home&method=delete&id=' + id;
        }
    }
</script>