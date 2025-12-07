<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
}
$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$order_id = $_GET['order_id'];

$db = connect();

// ตรวจสอบว่ามีการเลือกสินค้าเฉพาะหรือไม่
$product_id = $_GET['product_id'] ?? null;

// ดึงรายการสินค้า
if ($product_id) {
    $stmt = $db->prepare("SELECT od.*, p.name, p.price, p.img_name 
                          FROM tb_order_detail od 
                          INNER JOIN tb_product p ON od.product_id = p.product_id 
                          WHERE od.order_id = :order_id AND od.product_id = :product_id");
    $stmt->execute(['order_id' => $order_id, 'product_id' => $product_id]);
} else {
    $stmt = $db->prepare("SELECT od.*, p.name, p.price, p.img_name 
                          FROM tb_order_detail od 
                          INNER JOIN tb_product p ON od.product_id = p.product_id 
                          WHERE od.order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลลูกค้า
$stmtCust = $db->prepare("SELECT c.* 
                          FROM tb_customer c 
                          INNER JOIN tb_order_detail od ON c.id = od.customer_id 
                          WHERE od.order_id = :order_id LIMIT 1");
$stmtCust->execute(['order_id' => $order_id]);
$customer = $stmtCust->fetch(PDO::FETCH_ASSOC);

// เก็บยอดรวม
$totalSales = 0;
$totalProfit = 0;
?>

<div class="invoice-container shadow-lg p-4 mb-5 bg-white rounded" style="margin-top:90px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-danger"><?php echo $T['invoice_header_title']; ?></h2>
            <p class="text-muted mb-0"><?php echo $T['invoice_order_number']; ?>: <b>#<?= $order_id; ?></b></p>
            <p class="text-muted"><?php echo $T['invoice_date']; ?>: <?= date("d/m/Y"); ?></p>
        </div>
        <div>
            <img src="img/logo/logo02.png" alt="Company Logo" width="90" style="border-radius:50%;">
        </div>
    </div>
    <hr>

    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="fw-bold"><?php echo $T['invoice_customer_info']; ?></h5>
            <p><b><?php echo $T['invoice_customer_name']; ?>:</b> <?= $customer['fname'] . " " . $customer['lname']; ?></p>
            <p><b><?php echo $T['invoice_customer_address']; ?>:</b> <?= $customer['address'] . ", " . $customer['home'] . ", " . $customer['distric'] . ", " . $customer['province']; ?></p>
            <p><b><?php echo $T['invoice_customer_phone']; ?>:</b> <?= $customer['mobile']; ?></p>
        </div>
        <div class="col-md-6">
            <h5 class="fw-bold"><?php echo $T['invoice_seller']; ?></h5>
            <p><?php echo $T['invoice_seller_name']; ?></p>
            <p><?php echo $T['invoice_customer_address']; ?>: <?php echo $T['invoice_seller_address']; ?></p>
        </div>
    </div>

    <?php
    $totalToPay = 0;

    foreach ($products as $product):
        $quantity = $product['qty'];
        $unit_price = $product['price'];

        // คำนวณยอดขายและกำไรสินค้า
        $itemTotal = $unit_price * $quantity;
        $itemProfit = $itemTotal * 0.2;

        // ยอดที่ต้องชำระจริง
        $itemPay = $itemTotal - $itemProfit;

        // บวกยอดขายและกำไรรวม
        $totalSales += $itemTotal;
        $totalProfit += $itemProfit;
        $totalToPay += $itemPay; // เพิ่มตัวแปรรวมยอดต้องชำระ
    ?>
        <div class="product-card p-3 mb-3 shadow-sm rounded d-flex align-items-center gap-3">
            <img src="./uploads/<?= $product['img_name']; ?>" width="100" class="rounded">
            <div class="product-info flex-grow-1">
                <h6 class="fw-bold"><?= $product['name']; ?></h6>
                <p class="mb-1"><?php echo $T['invoice_qty_label_short']; ?>: <?= $quantity; ?></p>
                <p class="mb-1"><?php echo $T['invoice_unit_price_label']; ?>: <?= number_format($unit_price, 2); ?> USD</p>
                <p class="mb-1"><?php echo $T['invoice_subtotal_short']; ?>: <?= number_format($itemTotal, 2); ?> USD</p>
                <p class="mb-1 text-success"><?php echo $T['invoice_profit_short']; ?>: <?= number_format($itemProfit, 2); ?> USD</p>
                <p class="mb-0 text-primary"><?php echo $T['invoice_pay_short']; ?>: <?= number_format($itemPay, 2); ?> USD</p>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="text-end mt-4">
        <h5 class="fw-bold"><?php echo $T['invoice_summary_title']; ?></h5>
        <p class="fw-bold fs-5"><?php echo $T['invoice_total_sales']; ?>: <?= number_format($totalSales, 2); ?> USD</p>
        <p class="fw-bold fs-5 text-success"><?php echo $T['invoice_total_profit']; ?>: <?= number_format($totalProfit, 2); ?> USD</p>
        <p class="fw-bold fs-5 text-primary"><?php echo $T['invoice_total_pay']; ?>: <?= number_format($totalToPay, 2); ?> USD</p>
    </div>


    <div class="text-end mt-3">
        <a href="?page=order_list&statust=3" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> <?php echo $T['invoice_btn_back']; ?>
        </a>
    </div>
</div>

<style>
    .invoice-container {
        max-width: 900px;
        margin: auto;
    }

    .invoice-container h2,
    h5 {
        margin-bottom: 10px;
    }

    .product-card {
        background-color: #f8f9fa;
        transition: transform 0.2s;
    }

    .product-card:hover {
        transform: translateY(-3px);
    }

    .product-card h6 {
        margin-bottom: 5px;
    }
</style>