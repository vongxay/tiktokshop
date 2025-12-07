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


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['b_id'])) {
    $b_id = intval($_POST['b_id']);
    $db = connect();

    // ดึงข้อมูลรายการเติมเงินจาก tb_back
    $stmt = $db->prepare("SELECT customer_id, b_amount FROM tb_back WHERE b_id = :b_id");
    $stmt->execute([':b_id' => $b_id]);
    $back = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($back) {
        $customer_id = $back['customer_id'];
        $amount = $back['b_amount'];

        // ตรวจสอบว่ามี wallet ของลูกค้าคนนี้หรือไม่
        $stmt = $db->prepare("SELECT * FROM tb_wallet WHERE customer_id = :customer_id");
        $stmt->execute([':customer_id' => $customer_id]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($wallet) {
            // ถ้ามี wallet อยู่แล้ว ให้เพิ่มเงิน
            $new_balance = $wallet['w_price'] + $amount;
            $stmt = $db->prepare("UPDATE tb_wallet SET w_price = :new_balance WHERE customer_id = :customer_id");
            $stmt->execute([
                ':new_balance' => $new_balance,
                ':customer_id' => $customer_id
            ]);
        } else {
            // ถ้าไม่มี wallet ให้สร้างใหม่
            $stmt = $db->prepare("INSERT INTO tb_wallet (customer_id, w_price) VALUES (:customer_id, :w_price)");
            $stmt->execute([
                ':customer_id' => $customer_id,
                ':w_price' => $amount
            ]);
        }

        // อัปเดตสถานะรายการเติมเงินใน tb_back เป็นอนุมัติ
        $stmt = $db->prepare("UPDATE tb_back SET status = 1 WHERE b_id = :b_id");
        $stmt->execute([':b_id' => $b_id]);

        echo "<script>alert('อนุมัติเรียบร้อย และอัปเดตยอดเงินใน wallet แล้ว');location='?page=wallet_history';</script>";
    } else {
        echo "<script>alert('ไม่พบรายการเติมเงินนี้');location='?page=wallet_history';</script>";
    }
}


// ดึงรายการเติมเงินจาก tb_back พร้อมชื่อผู้ใช้จาก tb_customer
$db = connect();
$stmt = $db->prepare("
    SELECT b.*, c.username, c.fname, c.lname 
    FROM tb_back b
    LEFT JOIN tb_customer c ON b.customer_id = c.id
    ORDER BY b.b_id DESC
");
$stmt->execute();
$wallet_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container" style="margin-top:20px; margin-bottom:100px;">
    <h2>ประวัติการเติมเงิน</h2>

    <!-- ปุ่มย้อนกลับ -->
    <div style="margin-bottom:15px;">
        <a href="?page=home" class="" style="text-decoration:none; padding:8px 12px; border-radius:6px; color:#000;">
            ← ย้อนกลับ
        </a>
    </div>
    <?php if(count($wallet_history) > 0): ?>
        <?php foreach($wallet_history as $i => $w): ?>
            <div class="wallet-item" style="border:1px solid #ddd; border-radius:8px; padding:12px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
                
                <div>
                    <strong>ผู้ใช้:</strong> <?php echo htmlspecialchars($w['username']); ?> (<?php echo htmlspecialchars($w['fname'].' '.$w['lname']); ?>)<br>
                    <strong>จำนวนเงิน: $</strong><?php echo number_format($w['b_amount']); ?> <br>
                    <small>วันที่: <?php echo date('d/m/Y', strtotime($w['created'])); ?></small>
                </div>

                <div>
                    <?php
                        if($w['status'] == 0) {
                            echo '<span class="badge bg-warning">รออนุมัติ</span>';
                        } elseif($w['status'] == 1) {
                            echo '<span class="badge bg-success">อนุมัติ</span>';
                        } else {
                            echo '<span class="badge bg-danger">ยกเลิก</span>';
                        }
                    ?>
                </div>

                <div>
                    <?php if(!empty($w['slip'])): ?>
                        <a href="../pages/uploads/slip/<?php echo $w['slip']; ?>" target="_blank">
                            <img src="../pages/uploads/slip/<?php echo $w['slip']; ?>" alt="Slip" style="width:80px; height:auto; border:1px solid #ddd; border-radius:6px;">
                        </a>
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </div>

                <!-- ปุ่มอนุมัติ -->
                <div>
                    <?php if($w['status'] == 0): ?>
                        <form method="post" action="?page=wallet_history" style="display:inline;">
                            <input type="hidden" name="b_id" value="<?php echo $w['b_id']; ?>">
                            <button type="submit" class="btn btn-success">อนุมัติ</button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">ยังไม่มีรายการเติมเงิน</p>
    <?php endif; ?>
</div>


