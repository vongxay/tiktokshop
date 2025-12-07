<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
    exit;
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$db = connect();

// ดึงรายการสั่งซื้อทั้งหมดของผู้ใช้
$stmt = $db->prepare("
    SELECT od.*, p.name AS product_name, p.img_name, p.price AS unit_price, DATE(od.created) AS order_date
    FROM tb_order_detail od
    INNER JOIN tb_product p ON od.product_id = p.product_id
    WHERE od.user_product_id = :cid
    ORDER BY od.created DESC
");
$stmt->execute(['cid' => $customer_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// จัดกลุ่มตามวัน
$ordersByDate = [];
foreach ($orders as $order) {
    $dateKey = $order['order_date'];
    $ordersByDate[$dateKey][] = $order;
}
?>

<div style="max-width:900px; margin:50px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
    <h2 class="text-center text-danger mb-4"><?php echo $T['invoice_title']; ?></h2>

    <?php if ($ordersByDate): ?>
        <?php foreach ($ordersByDate as $date => $dateOrders): ?>
            <?php
            $dailyTotal = 0;
            $dailyProfit = 0;
            foreach ($dateOrders as $order) {
                $dailyTotal += $order['price_qty'];
                $dailyProfit += $order['price_qty']*0.2;
            }
            ?>
            <div style="margin-bottom:25px; border:1px solid #eee; border-radius:10px; padding:15px; background:#f9f9f9;">
                <h4 style="margin-bottom:10px; color:#ff2e63;"><?php echo $T['invoice_date_label']; ?>: <?= date("d/m/Y", strtotime($date)) ?></h4>

                <?php foreach ($dateOrders as $order): ?>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:10px; border-bottom:1px solid #ddd;">
                        <div style="display:flex; align-items:center; gap:15px;">
                            <img src="./uploads/<?= $order['img_name']; ?>" alt="<?= $order['product_name']; ?>" width="60" style="border-radius:8px;">
                            <div>
                                <p style="margin:0; font-weight:bold;"><?= $order['product_name']; ?></p>
                                <p style="margin:0; font-size:14px; color:#555;">
                                    <?php echo $T['invoice_qty_unit']; ?>: <?= $order['qty']; ?> | <?php echo $T['invoice_unit_price']; ?>: $<?= number_format($order['unit_price'],2); ?> | <?php echo $T['invoice_time_label']; ?>: <?= date("H:i", strtotime($order['created'])) ?>
                                </p>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <p style="margin:0; font-weight:bold;"><?php echo $T['invoice_subtotal']; ?>: $<?= number_format($order['price_qty'],2); ?></p>
                            <p style="margin:0; font-size:14px; color:green;"><?php echo $T['invoice_profit']; ?>: $<?= number_format($order['price_qty']*0.2,2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div style="text-align:right; margin-top:10px;">
                    <p style="font-weight:bold; font-size:16px;"><?php echo $T['invoice_daily_total']; ?>: $<?= number_format($dailyTotal,2); ?></p>
                    <p style="font-weight:bold; font-size:16px; color:green;"><?php echo $T['invoice_daily_profit']; ?>: $<?= number_format($dailyProfit,2); ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <?php
        // สรุปยอดรวมทั้งหมด
        $total_sales = array_sum(array_map(function($d){ return array_sum(array_column($d,'price_qty')); }, $ordersByDate));
        $total_profit = array_sum(array_map(function($d){ return array_sum(array_column($d,'price_qty'))*0.2; }, $ordersByDate));
        ?>
        <div style="text-align:right; margin-top:20px; font-weight:bold; font-size:18px;">
            <p><?php echo $T['invoice_grand_total']; ?>: $<?= number_format($total_sales,2); ?></p>
            <p style="color:green;"><?php echo $T['invoice_grand_profit']; ?>: $<?= number_format($total_profit,2); ?></p>
        </div>
    <?php else: ?>
        <p class="text-center text-muted"><?php echo $T['invoice_no_order']; ?></p>
    <?php endif; ?>
</div>