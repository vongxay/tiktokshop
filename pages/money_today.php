<?php
$db = connect();

// ดึง wallet ปัจจุบัน
$walletStmt = $db->prepare("SELECT w_price FROM tb_wallet WHERE customer_id = :cid LIMIT 1");
$walletStmt->execute(['cid' => $customer_id]);
$wallet = $walletStmt->fetch(PDO::FETCH_ASSOC);
$walletBalance = $wallet['w_price'] ?? 0;

// ยอดขายรวม, กำไร, จำนวนสินค้า (ที่ขายแล้ว)
$salesStmt = $db->prepare("
    SELECT 
        SUM(od.price_qty) AS total_sales,
        SUM(od.price_qty * 0.2) AS total_profit,
        COUNT(p.product_id) AS product_count
    FROM tb_order_detail od
    INNER JOIN tb_product p ON od.product_id = p.product_id
    WHERE p.customer_id = :cid AND od.statust = 3
");
$salesStmt->execute(['cid' => $customer_id]);
$salesData = $salesStmt->fetch(PDO::FETCH_ASSOC);
$totalSales = $salesData['total_sales'] ?? 0;
$totalProfit = $salesData['total_profit'] ?? 0;
$productCount = $salesData['product_count'] ?? 0;

// ออเดอร์ค้างชำระ
$orderStmt = $db->prepare("SELECT SUM(price_qty) as pending FROM tb_order_detail WHERE user_product_id = :cid AND statust = 0");
$orderStmt->execute(['cid' => $customer_id]);
$pendingOrder = $orderStmt->fetch(PDO::FETCH_ASSOC)['pending'] ?? 0;

// รายรับและรายจ่ายวันนี้
$todayStmt = $db->prepare("
    SELECT 
        SUM(od.price_qty) AS income,
        SUM(od.price_qty - (od.price_qty * 0.2)) AS expense_real
    FROM tb_order_detail od
    INNER JOIN tb_product p ON od.product_id = p.product_id
    WHERE p.customer_id = :cid 
      AND od.statust = 3
      AND DATE(od.created) = CURDATE()
");
$todayStmt->execute(['cid' => $customer_id]);
$todayData = $todayStmt->fetch(PDO::FETCH_ASSOC);

// รายรับวันนี้ (รวมเต็ม)
$incomeToday = $todayData['income'] ?? 0;

// รายจ่ายจริงวันนี้ = ยอดขาย - กำไร 20%
$expenseToday = $todayData['expense_real'] ?? 0;

?>


<div class="dashboard-cards" style="display:flex; flex-wrap:wrap; gap:20px; margin-top:20px;">
    <?php
    $cards = [
        // ใช้ Key จาก $T ในการกำหนด title
        ['title' => $T['metric_wallet_balance'], 'value' => $walletBalance, 'color' => '#4caf50', 'icon' => 'bi-wallet2'],
        ['title' => $T['metric_total_sales'], 'value' => $totalSales, 'color' => '#2196f3', 'icon' => 'bi-currency-dollar'],
        ['title' => $T['metric_total_profit'], 'value' => $totalProfit, 'color' => '#ff9800', 'icon' => 'bi-cash-stack'],
        // ['title' => 'สินค้าที่ขาย', 'value' => $productCount, 'color' => '#9c27b0', 'icon' => 'bi-box-seam'],
        ['title' => $T['metric_pending_order'], 'value' => $pendingOrder, 'color' => '#f44336', 'icon' => 'bi-exclamation-circle'],
        ['title' => $T['metric_income_today'], 'value' => $incomeToday, 'color' => '#00bcd4', 'icon' => 'bi-arrow-up-circle'],
        ['title' => $T['metric_expense_today'], 'value' => $expenseToday, 'color' => '#e91e63', 'icon' => 'bi-arrow-down-circle'],
    ];

    foreach ($cards as $c):
    ?>
        <div class="card" style="flex:1; min-width:180px; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:20px; display:flex; align-items:center; gap:15px; margin-top:60px">
            <div style="font-size:32px; color:<?= $c['color'] ?>;">
                <i class="bi <?= $c['icon'] ?>"></i>
            </div>
            <div>
                <p style="margin:0; font-size:14px; color:#555;"><?= $c['title'] ?></p>
                <p style="margin:0; font-size:18px; font-weight:bold;">$<?= number_format($c['value'], 2) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<br>
<br>