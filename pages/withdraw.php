<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$db = connect();

// ดึงยอด wallet
$stmt = $db->prepare("SELECT w_price, status FROM tb_wallet WHERE customer_id = :cid LIMIT 1");
$stmt->execute(['cid' => $customer_id]);
$wallet = $stmt->fetch(PDO::FETCH_ASSOC);
$walletBalance = $wallet['w_price'] ?? 0;

if (isset($_POST['withdraw_btn'])) {
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        // ใช้ $T['alert_amount_invalid']
        $_SESSION['msg'] = '<div class="alert alert-danger">'.$T['alert_amount_invalid'].'</div>';
    } elseif ($amount > $walletBalance) {
        // ใช้ $T['alert_insufficient_funds']
        $_SESSION['msg'] = '<div class="alert alert-danger">'.$T['alert_insufficient_funds'].'</div>';
    } else {
        $insert = $db->prepare("INSERT INTO tb_withdraw (customer_id, amount, status, created_at) 
                                VALUES (:cid, :amount, 'pending', NOW())");
        $insert->execute(['cid' => $customer_id, 'amount' => $amount]);

        $update = $db->prepare("UPDATE tb_wallet SET status = 1 WHERE customer_id = :cid");
        $update->execute(['cid' => $customer_id]);

        // ใช้ $T['alert_request_pending']
        $_SESSION['msg'] = '<div class="alert alert-warning">'.$T['alert_request_pending'].'</div>';
    }

    // ✅ redirect ด้วย JavaScript (ไม่ติด error headers already sent)
    echo "<script>location.replace('?page=withdraw');</script>";
    exit;
}


// ดึงประวัติการถอน
$history = $db->prepare("SELECT amount, status, created_at, updated_at 
                         FROM tb_withdraw 
                         WHERE customer_id = :cid 
                         ORDER BY created_at DESC");
$history->execute(['cid' => $customer_id]);
$withdraws = $history->fetchAll(PDO::FETCH_ASSOC);

// แสดงข้อความแจ้งเตือนจาก Session
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']); // ล้างข้อความหลังจากแสดง

?>

<div style="display:flex;justify-content:center;align-items:center;min-height:80vh;background:#f5f5f5; margin-bottom: 70px;">
    <div style="max-width:700px;width:100%;padding:20px;background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
        <h2 class="text-center text-danger mb-4"><?php echo $T['withdraw_title']; ?></h2>

        <?= $msg ?>

        <p class="text-center"><?php echo $T['withdraw_balance_label']; ?>: <strong>$<?= number_format($walletBalance,2) ?></strong></p>

        <form method="POST" style="margin-top:20px;">
            <div style="margin-bottom:15px;">
                <label><?php echo $T['withdraw_amount_label']; ?></label>
                <input type="number" name="amount" class="form-control" placeholder="00.00" step="0.01" min="1" 
                    <?= ($wallet['status'] == 1 ? 'disabled' : '') ?> required>
            </div>
            <button type="submit" name="withdraw_btn" class="btn btn-danger w-100" 
                    <?= ($wallet['status'] == 1 ? 'disabled' : '') ?>>
                <?php echo $T['withdraw_btn_confirm']; ?>
            </button>
        </form>

        <?php if ($wallet['status'] == 1): ?>
            <div class="alert alert-info text-center mt-3">
                <?php echo $T['alert_has_pending']; ?>
            </div>
        <?php endif; ?>


        <hr class="my-4">

        <h4 class="mb-3"><?php echo $T['withdraw_history_title']; ?></h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?php echo $T['table_header_amount']; ?></th>
                    <th><?php echo $T['table_header_status']; ?></th>
                    <th><?php echo $T['table_header_date_req']; ?></th>
                    <th><?php echo $T['table_header_date_proc']; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($withdraws): ?>
                    <?php foreach ($withdraws as $w): ?>
                        <tr>
                            <td>$<?= number_format($w['amount'],2) ?></td>
                            <td>
                                <?php if ($w['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark"><?php echo $T['status_pending_badge']; ?></span>
                                <?php elseif ($w['status'] == 'approved'): ?>
                                    <span class="badge bg-success"><?php echo $T['status_approved_badge']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><?php echo $T['status_rejected_badge']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $w['created_at'] ?></td>
                            <td><?= $w['updated_at'] ?? '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center"><?php echo $T['table_no_history']; ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
