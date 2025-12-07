<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $amount = intval($_POST['amount']);

    $upload_dir = __DIR__ . "/uploads/slip/"; // โฟลเดอร์ต้องมีอยู่แล้ว
    $slip_name = "";
    if (isset($_FILES['slip']) && $_FILES['slip']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['slip']['name'], PATHINFO_EXTENSION));
        $slip_name = "slip_" . time() . "." . $ext;
        $target = $upload_dir . $slip_name;

        if (!move_uploaded_file($_FILES['slip']['tmp_name'], $target)) {
            // ใช้ $T['payment_alert_upload_fail']
            die($T['payment_alert_upload_fail']);
        }
    }


    $db = connect();
    $stmt = $db->prepare("INSERT INTO tb_back (b_amount, customer_id, status, slip, created) 
                          VALUES (:price, :customer_id, :status, :slip, CURDATE())");
    $stmt->execute([
        ':price' => $amount,
        ':customer_id' => $customer_id,
        ':status' => 0,
        ':slip' => $slip_name
    ]);

    // ใช้ $T['payment_alert_success']
    echo "<script>alert('{$T['payment_alert_success']}');location='?page=payment';</script>";
}

// สมมติยอดเงินที่ต้องชำระ (คุณสามารถเปลี่ยนเป็นดึงจาก tb_order_detail ตาม order ได้)
$total_amount = 1500.00;

// ดึงข้อมูลจากตาราง code (ตารางที่คุณให้มา)
$db = connect();
$stmt = $db->prepare("SELECT * FROM tb_usd ORDER BY id ASC"); // ← ใส่ชื่อตารางจริง
$stmt->execute();
$walletsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// แปลงข้อมูลให้อยู่ในรูป array เหมือนเดิม
$wallets = [];
foreach ($walletsData as $row) {
    if (!empty($row['img_name'])) {
        $wallets[strtolower($row['code_name'])] = [
            "address" => $row['code'],
            "qr"      => "pages/uploads/slip/" . $row['img_name'] // <-- ปรับ path ให้ถูกต้อง
        ];
    }
}

?>



<div class="container" style="margin-top: 65px; margin-bottom: 20px;">
    <h2><?php echo $T['payment_form_title']; ?></h2>

    <form action="?page=payment" method="post" enctype="multipart/form-data" class="payment-form">

        <div class="form-group">
            <label for="amount"><?php echo $T['payment_amount_label']; ?>:</label>
            <input type="text" id="amount" name="amount" value="" placeholder="<?php echo $T['payment_amount_placeholder']; ?>" required>
        </div>
        <script>
            document.getElementById("amount").addEventListener("input", function() {
                // ลบทุกตัวที่ไม่ใช่ตัวเลขและทศนิยม
                this.value = this.value.replace(/[^0-9.]/g, '');
            });
        </script>

        <div class="form-group">
            <label for="method"><?php echo $T['payment_method_label']; ?>:</label>
            <select id="method" name="method" onchange="showQR()">
                <?php foreach ($wallets as $key => $w): ?>
                    <option value="<?php echo $key; ?>"><?php echo strtoupper($key); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="qr-container" class="qr-box">
            <?php $first = reset($wallets); ?>
            <img id="qr-image" src="<?php echo $first['qr']; ?>" alt="QR Code">
            <div style="display:flex; align-items:center; justify-content:center; gap:10px; flex-wrap:wrap;">
                <p id="wallet-address" style="margin:0;"><?php echo $first['address']; ?></p>
                <button type="button" class="btn-copy" onclick="copyAddress()"><?php echo $T['payment_copy_btn']; ?></button>
            </div>

        </div>
        <script>
            // สร้าง object wallets จาก PHP แบบปลอดภัย
            const wallets = <?php echo json_encode($wallets, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
            const paymentCopyBtnText = "<?php echo $T['payment_copy_btn']; ?>";
            const paymentCopiedBtnText = "<?php echo $T['payment_copied_btn']; ?>";


            // function แสดง QR และ Address
            function showQR() {
                let method = document.getElementById("method").value;
                const qrImage = document.getElementById("qr-image");
                const walletAddress = document.getElementById("wallet-address");

                if (wallets[method]) {
                    qrImage.src = wallets[method].qr;
                    walletAddress.innerText = wallets[method].address;
                }
            }

            // เรียก showQR() ตอนโหลดหน้า เพื่อให้แสดง QR ของค่าแรก
            document.addEventListener("DOMContentLoaded", function() {
                showQR();
            });
        </script>

        <script>
            function copyAddress() {
                let address = document.getElementById("wallet-address").innerText;
                navigator.clipboard.writeText(address).then(() => {
                    const btn = document.querySelector(".btn-copy");
                    // ใช้ตัวแปรแปล
                    btn.innerText = paymentCopiedBtnText;
                    setTimeout(() => {
                        btn.innerText = paymentCopyBtnText;
                    }, 2000);
                }).catch(err => {
                    console.error("ไม่สามารถคัดลอกได้: ", err);
                });
            }
        </script>


        <div class="form-group">
            <label for="slip"><?php echo $T['payment_upload_label']; ?>:</label>

            <input type="file" id="slip" name="slip" accept="image/*" required onchange="previewSlip(event)" style="display:none;">

            <label for="slip" class="custom-file-btn">
                <?php echo $T['payment_upload_btn']; ?>
            </label>

            <span id="file-name-display" style="font-size:13px; color:#555; margin-left: 10px;"></span>
        </div>

        <div id="slip-preview-box" style="display:none; margin-top:10px; text-align:center;">
            <p><b><?php echo $T['payment_slip_preview']; ?>:</b></p>
            <img id="slip-preview" src="" alt="Slip Preview"
                style="max-width:250px; border:1px solid #ddd; border-radius:8px; padding:5px;">
        </div>

        <button type="submit" class="btn-pay"><?php echo $T['payment_confirm_btn']; ?></button>
    </form>
</div>

<script>
    function previewSlip(event) {
        const file = event.target.files[0]; // ไฟล์ที่เลือก
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewBox = document.getElementById("slip-preview-box");
                const previewImg = document.getElementById("slip-preview");
                previewImg.src = e.target.result; // ใส่รูปลง img
                previewBox.style.display = "block"; // แสดง container
            }
            reader.readAsDataURL(file);
        }
    }
</script>

<?php
// ดึงข้อมูลจาก tb_wallet
$db = connect();
$stmt = $db->prepare("SELECT * FROM tb_back WHERE customer_id = :cid ORDER BY b_id DESC");
$stmt->execute([':cid' => $customer_id]);
$wallets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="container" style="margin-top:15px; margin-bottom:100px;">
    <h2><?php echo $T['payment_history_title']; ?></h2>

    <div class="history-list">
        <?php if (count($wallets) > 0): ?>
            <?php foreach ($wallets as $i => $w): ?>
                <div class="history-item">
                    <div class="history-header">
                        <span class="history-number"><?php echo $i + 1; ?></span>
                        <span class="history-status 
                            <?php
                            echo $w['status'] == 0 ? 'pending' : ($w['status'] == 1 ? 'success' : 'cancel');
                            ?>">
                            <?php
                            // ใช้ Key สำหรับสถานะ
                            if ($w['status'] == 0) {
                                echo $T['history_status_0'];
                            } elseif ($w['status'] == 1) {
                                echo $T['history_status_1'];
                            } else {
                                echo $T['history_status_2'];
                            }
                            ?>
                        </span>
                    </div>
                    <div class="history-body">
                        <p><strong><?php echo $T['history_amount_label']; ?>:</strong> $<?php echo number_format($w['b_amount']); ?> </p>
                        <p><strong><?php echo $T['history_date_label']; ?>:</strong> <?php echo $w['created']; ?></p>
                        <?php if (!empty($w['slip'])): ?>
                            <p>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; color:#666;"><?php echo $T['history_no_record']; ?></p>
        <?php endif; ?>
    </div>
</div>



<style>
    .custom-file-btn {
        display: inline-block;
        padding: 8px 15px;
        background-color:rgb(156, 155, 155);
        /* สีปุ่มที่คุณต้องการ */
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .custom-file-btn:hover {
        background-color:rgb(105, 101, 101);
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .history-item {
        background: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .history-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .history-number {
        font-weight: bold;
        color: #333;
    }

    .history-status {
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        color: #fff;
    }

    .history-status.pending {
        background-color: #f39c12;
    }

    .history-status.success {
        background-color: #2ecc71;
    }

    .history-status.cancel {
        background-color: #e74c3c;
    }

    .history-body p {
        margin: 5px 0;
        color: #555;
    }

    .slip-img {
        max-width: 100px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .container {
        max-width: 500px;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);

    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    .payment-form .form-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 6px;
    }

    input[type="text"],
    select,
    input[type="file"] {
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .qr-box {
        text-align: center;
        margin: 20px 0;
    }

    .qr-box img {
        width: 200px;
        height: 200px;
        margin-bottom: 10px;
        border: 1px solid #eee;
        border-radius: 10px;
    }

    .qr-box p {
        font-size: 14px;
        word-break: break-all;
        color: #2c3e50;
    }

    .btn-pay {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background: #219150;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-pay:hover {
        background: #219150;
    }
</style>