<?php

if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
    exit;
}
$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$username = $profile['fname'];

$order_id = $_GET['order_id'];
$product_id = $_GET['product_id'];

$db = connect();
$stmt = $db->query("SELECT * FROM tb_order_detail od 
                    INNER JOIN tb_customer c ON od.customer_id = c.id 
                    WHERE od.product_id = '$product_id' AND od.order_id = '$order_id' LIMIT 1");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $product_price = $row['price_qty'];

    $stmt = $db->query("SELECT * FROM tb_order_detail od 
                        INNER JOIN tb_product p ON od.product_id = p.product_id 
                        WHERE od.product_id = '$product_id' LIMIT 1");
    $productData = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = $productData['name'];

    // คำนวณกำไร (40% ของราคาสินค้า)
    $profit = $product_price * 0.2;

    // ยอดที่ต้องจ่ายจริง = ราคาสินค้า - กำไร
    $total_to_pay = $product_price - $profit;

    // ดึงข้อมูล wallet
    $stmt = $db->prepare("SELECT * FROM tb_wallet WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
    $balance = $wallet ? $wallet['w_price'] : 0;

    // ถ้ากดปุ่มชำระเงิน
if (isset($_POST['pay'])) {
    if ($balance < $total_to_pay) {
        // ใช้ $T['payment_alert_balance']
        echo "<script>alert('{$T['payment_alert_balance']}'); location.href='?page=chat';</script>";
        exit;
    }

    // ✅ หักเฉพาะยอดที่ต้องชำระจริงจาก Wallet
    $new_balance = $balance - $total_to_pay;
    $stmt = $db->prepare("UPDATE tb_wallet SET w_price = ? WHERE customer_id = ?");
    $stmt->execute([$new_balance, $customer_id]);

    // อัปเดตสถานะ order → รอจัดส่ง
    $stmt = $db->prepare("UPDATE tb_order_detail SET statust = 1 WHERE order_id = ? AND product_id = ?");
    $stmt->execute([$order_id, $product_id]);

    // แสดงใบเสร็จ
    ?>
    <div class="container mt-4">
        <div class="card shadow-lg p-4 bg-white rounded" style="border:1px solid #ccc;">
            <h3 class="text-center mb-4" style="font-family: 'Courier New', Courier, monospace;"><?= $T['receipt_title'] ?></h3>
            <p><strong><?= $T['receipt_order_id'] ?>:</strong> <?= htmlspecialchars($order_id) ?></p>
            <p><strong><?= $T['receipt_product_name'] ?>:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong><?= $T['payment_qty_label'] ?>:</strong> <?= $row['qty'] ?> <?= $T['receipt_qty_unit'] ?></p>
            <p style="color:#d9534f;"><strong><?= $T['payment_price_label'] ?>:</strong> <?= number_format($product_price, 2) ?> USD</p>
            <p style="color:#28a745;"><strong><?= $T['payment_profit_label'] ?>:</strong> <?= number_format($profit, 2) ?> USD</p>
            <p style="color:#007bff;"><strong><?= $T['payment_total_pay_label'] ?>:</strong> <?= number_format($total_to_pay, 2) ?> USD</p>
            <hr style="border-top:1px dashed #aaa;">
            <p><strong><?= $T['receipt_paid_by'] ?>:</strong> <?= $username ?></p>
            <p><strong><?= $T['receipt_shipping_address'] ?>:</strong> <?= htmlspecialchars($row['address']) ?>, <?= htmlspecialchars($row['province']) ?></p>
            <hr style="border-top:1px dashed #aaa;">
            <p><strong><?= $T['receipt_remaining_balance'] ?>:</strong> <?= number_format($new_balance, 2) ?> USD</p>
            <div class="text-center mt-4">
                <a href="?page=order" class="btn btn-success"><?= $T['receipt_btn_done'] ?></a>
                <button onclick="window.print()" class="btn btn-outline-dark"><?= $T['receipt_btn_print'] ?></button>
            </div>
        </div>
    </div>
    <?php
    exit;
}

?>

<div class="card mb-3 shadow-lg p-3 mb-3 bg-body rounded" style="max-width:auto;">
    <div class="row g-0">
        <p class="card-text"><a href="?page=order">< <?= $T['payment_back'] ?></a></p>
        <div class="col-md-4 text-center">
            <img src="./uploads/<?= $productData['img_name'] ?>" class="img-fluid rounded-start" alt="...">
            <hr>
            <div style="text-align:center; margin-top:15px;">
                <p><strong><?= $T['payment_qty_label'] ?>:</strong> <?= $row['qty']; ?> <?= $T['receipt_qty_unit'] ?></p>
                <p><strong><?= $T['payment_price_label'] ?>:</strong> <span style="color:#d9534f; font-weight:bold;"><?= number_format($product_price, 2); ?> USD</span></p>
                <p><strong><?= $T['payment_profit_label'] ?>:</strong> <span style="color:#28a745; font-weight:bold;"><?= number_format($profit, 2); ?> USD</span></p>
                <p><strong><?= $T['payment_total_pay_label'] ?>:</strong> <span style="color:#007bff; font-weight:bold;"><?= number_format($total_to_pay, 2); ?> USD</span></p>
            </div>

        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?= $productData['name']; ?></h5>
                <p><strong><?= $T['payment_buyer_label'] ?>:</strong> <?= $row['fname']; ?></p>
                <p><strong><?= $T['payment_address_label'] ?>:</strong> <?= $row['address']; ?>, <?= $row['province']; ?></p>
                <hr>
                <p><strong><?= $T['payment_wallet_label'] ?>:</strong> <?= number_format($balance, 2) ?> USD</p>
                <form method="post">
                    <div class="d-grid gap-2">
                        <?php if ($balance >= $total_to_pay): ?>
                            <button type="submit" name="pay" class="btn btn-dark btn-lg"><?= $T['payment_btn_pay'] ?></button>
                        <?php else: ?>
                            <a href="?page=chat&user=17" class="btn btn-danger btn-lg"><?= $T['payment_btn_topup'] ?></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<style>
.container { max-width: 700px; }
.card { border-radius: 15px; }
.text-success { font-weight: bold; color: green; }
</style>
