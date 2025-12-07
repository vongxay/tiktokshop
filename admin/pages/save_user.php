<?php
$profile = getCustomerBy($_SESSION['admin_loggedId']);
$customer_id = $profile['id'];

// ✅ ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
    session_destroy(); // ลบ session
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
    exit;
}

if (isset($_POST['submit'])) {
    $fname   = $_POST['fname'];
    $mobile  = $_POST['mobile'];
    $address = $_POST['address'];
    $distric = $_POST['distric'];
    $db      = connect();

    // อัพเดทข้อมูลลูกค้า
    $stmt = $db->prepare("UPDATE tb_customer 
                          SET fname=:fname, mobile=:mobile, address=:address, distric=:distric 
                          WHERE id=:id");
    $stmt->execute([
        ':fname'   => $fname,
        ':mobile'  => $mobile,
        ':address' => $address,
        ':distric' => $distric,
        ':id'      => $customer_id
    ]);

    // ✅ สร้าง order_id หนึ่งครั้งเท่านั้น
    $order_id = generateOdId();

    $success = true;

    foreach ($_SESSION['cart'] as $p_id => $qty) {
        // ดึงข้อมูลสินค้า
        $stmtProd = $db->prepare("SELECT * FROM tb_product WHERE product_id = :pid");
        $stmtProd->execute([':pid' => $p_id]);
        $row = $stmtProd->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user_product_id = $row['customer_id'];
            $total           = $row['price'] * $qty;

            // บันทึก order detail
            $stmt1 = $db->prepare("INSERT INTO tb_order_detail
                (order_id, product_id, customer_id, qty, price_qty, user_product_id, created) 
                VALUES (:order_id, :product_id, :customer_id, :qty, :price_qty, :user_product_id, NOW())");
            $ok1 = $stmt1->execute([
                ':order_id'      => $order_id,
                ':product_id'    => $p_id,
                ':customer_id'   => $customer_id,
                ':qty'           => $qty,
                ':price_qty'     => $total,
                ':user_product_id' => $user_product_id
            ]);

            // อัพเดท stock
            $sumqty = $row['qty'] - $qty;
            $stmt2  = $db->prepare("UPDATE tb_product SET qty=:qty WHERE product_id=:pid");
            $ok2    = $stmt2->execute([':qty' => $sumqty, ':pid' => $p_id]);

            if (!$ok1 || !$ok2) {
                $success = false;
            }
        }
    }

    if ($success) {
        // บันทึก order หลัก
        $stmt4 = $db->prepare("INSERT INTO tb_order(order_id, customer_id, order_date, created) 
                               VALUES(:order_id, :customer_id, NOW(), NOW())");
        $stmt4->execute([
            ':order_id'    => $order_id,
            ':customer_id' => $customer_id
        ]);

        // เคลียร์ cart
        unset($_SESSION['cart']);
        unset($_SESSION['id']);

        echo '<script>
            alert("✅ สั่งซื้อสำเร็จแล้ว!");
            setTimeout(() => { location.href="index.php"; }, 1000);
        </script>';
    } else {
        echo '<script>alert("❌ เกิดข้อผิดพลาดในการสั่งซื้อ");</script>';
    }
}
?>
    