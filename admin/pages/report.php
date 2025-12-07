<?php
if (!isset($_SESSION['admin_username'])) {
    echo '<script> location.replace("?page=login"); </script>';
    exit;
}

$profile = getCustomerBy($_SESSION['admin_loggedId']);
$custom  = $profile['id'];

// ✅ ตรวจสอบสิทธิ์แอดมิน
if ($profile['statust_log'] != 1) {
    session_destroy();
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
    exit;
}

$db = connect();

// --------------------- จัดการคำขอถอน --------------------- //
if (isset($_POST['approve'])) {
    $withdrawId = intval($_POST['withdraw_id']);

    $stmt = $db->prepare("SELECT * FROM tb_withdraw WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $withdrawId]);
    $withdraw = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($withdraw) {
        $cid    = $withdraw['customer_id'];
        $amount = $withdraw['amount'];

        // ดึง wallet
        $stmt2 = $db->prepare("SELECT w_price FROM tb_wallet WHERE customer_id = :cid");
        $stmt2->execute(['cid' => $cid]);
        $wallet  = $stmt2->fetch(PDO::FETCH_ASSOC);
        $balance = $wallet['w_price'];

        if ($balance >= $amount) {
            // หักเงินและรีเซ็ต status wallet
            $newBalance = $balance - $amount;
            $update = $db->prepare("UPDATE tb_wallet SET w_price = :balance, status = 0 WHERE customer_id = :cid");
            $update->execute(['balance' => $newBalance, 'cid' => $cid]);

            // อัปเดตคำขอ
            $db->prepare("UPDATE tb_withdraw 
                          SET status = 'approved', updated_at = NOW() 
                          WHERE id = :id")->execute(['id' => $withdrawId]);

            echo "<div class='alert alert-success'>✅ อนุมัติการถอนเรียบร้อย</div>";
        } else {
            echo "<div class='alert alert-danger'>❌ ยอดเงินไม่เพียงพอ</div>";
        }
    }
}

if (isset($_POST['reject'])) {
    $withdrawId = intval($_POST['withdraw_id']);

    // อัปเดตสถานะ
    $db->prepare("UPDATE tb_withdraw 
                  SET status = 'rejected', updated_at = NOW() 
                  WHERE id = :id")->execute(['id' => $withdrawId]);

    // คืนค่า wallet status
    $withdraw = $db->prepare("SELECT customer_id FROM tb_withdraw WHERE id = :id LIMIT 1");
    $withdraw->execute(['id' => $withdrawId]);
    $cid = $withdraw->fetchColumn();
    $db->prepare("UPDATE tb_wallet SET status = 0 WHERE customer_id = :cid")->execute(['cid' => $cid]);

    echo "<div class='alert alert-warning'>⚠️ ปฏิเสธคำขอถอนเรียบร้อย</div>";
}

// --------------------- ดึงข้อมูล --------------------- //
// pending
$stmt = $db->query("SELECT w.id, c.username, w.amount, w.created_at 
                    FROM tb_withdraw w 
                    JOIN tb_customer c ON w.customer_id = c.id 
                    WHERE w.status = 'pending'");
$withdraws = $stmt->fetchAll(PDO::FETCH_ASSOC);

// history
$history = $db->query("SELECT w.id, c.username, w.amount, w.status, w.created_at, w.updated_at
                       FROM tb_withdraw w
                       JOIN tb_customer c ON w.customer_id = c.id
                       WHERE w.status IN ('approved','rejected')
                       ORDER BY w.updated_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container px-4 py-5" id="featured-3">
    <div class="table-responsive">

        <h2>📌 รายการคำขอถอน (รออนุมัติ)</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ผู้ใช้</th>
                    <th>จำนวนเงิน</th>
                    <th>วันที่ขอ</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($withdraws): ?>
                    <?php foreach ($withdraws as $w): ?>
                        <tr>
                            <td><?= htmlspecialchars($w['username']) ?></td>
                            <td>$<?= number_format($w['amount'], 2) ?></td>
                            <td><?= $w['created_at'] ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="withdraw_id" value="<?= $w['id'] ?>">
                                    <button name="approve" class="btn btn-success btn-sm">อนุมัติ</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="withdraw_id" value="<?= $w['id'] ?>">
                                    <button name="reject" class="btn btn-danger btn-sm">ปฏิเสธ</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">- ไม่มีคำขอถอน -</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2 class="mt-5">📜 ประวัติการอนุมัติ / ปฏิเสธ</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ผู้ใช้</th>
                    <th>จำนวนเงิน</th>
                    <th>วันที่ขอถอน</th>
                    <th>สถานะ</th>
                    <th>วันที่ดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($history): ?>
                    <?php foreach ($history as $h): ?>
                        <tr>
                            <td><?= htmlspecialchars($h['username']) ?></td>
                            <td>$<?= number_format($h['amount'], 2) ?></td>
                            <td><?= $h['created_at'] ?></td>
                            <td>
                                <?php if ($h['status'] == 'approved'): ?>
                                    <span class="badge bg-success">อนุมัติ</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">ปฏิเสธ</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $h['updated_at'] ?? '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">- ยังไม่มีประวัติ -</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="?page=home" class="btn btn-secondary mt-3">⬅ ย้อนกลับ</a>

    </div>
</div>