<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
}
// error_reporting(0);
$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];

// ✅ ตรวจสอบ lock
if (isset($profile['lock']) && $profile['lock'] == 1) {
    echo "
    <script>
Swal.fire({
    title: '{$T['lock_title']}',
    text: '{$T['lock_text']}',
    icon: 'error',
    allowOutsideClick: false,   // ❌ ห้ามคลิกนอก Popup
    allowEscapeKey: false,      // ❌ ห้ามกด ESC
    allowEnterKey: false,       // ❌ ห้ามปิดด้วย Enter
    showCancelButton: false,    // ❌ ไม่มีปุ่มยกเลิก
    confirmButtonText: '{$T['lock_btn']}',
    confirmButtonColor: '#d33'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = '?page=chat&user=17'; // ✅ กดปุ่มแล้วค่อยไป
    }
});
</script>

    ";
    exit(); // หยุดการทำงานต่อ
}

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
    .profile-header {
        background-color: rgb(46, 45, 45);
        padding: 20px;
        border-radius: 10px;
        color: #fff;
        opacity: 0;
        /* ซ่อนตอนแรก */
        transform: translateY(20px);
        /* เลื่อนลงเล็กน้อย */
        transition: all 0.8s ease-in-out;
    }

    .profile-header.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<?php
$db = connect();
$customer_id = $customer_id; // รหัสผู้ใช้ร้าน

// ตรวจสอบว่ามีข้อมูลย้อนหลัง 7 วันหรือยัง
$check = $db->prepare("
    SELECT COUNT(*) AS count 
    FROM tb_visits 
    WHERE customer_id = :cid AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
");
$check->execute(['cid' => $customer_id]);
$existsCount = $check->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

if ($existsCount == 0) {
    // สร้างข้อมูล random สำหรับ 7 วันที่ผ่านมา
    for ($i = 0; $i < 7; $i++) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $visitsCount = rand(20, 200); // random 20-200 คน

        $insert = $db->prepare("
            INSERT INTO tb_visits (customer_id, visits_count, created_at) 
            VALUES (:cid, :visits, :date)
        ");
        $insert->execute([
            'cid' => $customer_id,
            'visits' => $visitsCount,
            'date' => $date
        ]);
    }

    // ใช้ $T['debug_visits_created'] แทนข้อความภาษาไทย
    echo "{$T['debug_visits_created']}<br>"; 
}

// -----------------------------
// ดึงสถิติผู้เข้าชมวันนี้
// -----------------------------
$visit1Stmt = $db->prepare("
    SELECT visits_count AS visits_1day 
    FROM tb_visits 
    WHERE customer_id = :cid AND created_at = CURDATE()
");
$visit1Stmt->execute(['cid' => $customer_id]);
$visits1day = $visit1Stmt->fetch(PDO::FETCH_ASSOC)['visits_1day'] ?? 0;

// ถ้าเป็น 0 ให้ตั้งค่าอย่างน้อย 20
if ($visits1day < 20) {
    $visits1day = 20;
}

// -----------------------------
// ตรวจสอบวันที่สมัคร / login ครั้งแรก
// -----------------------------
$userStmt = $db->prepare("SELECT created FROM tb_customer WHERE id = :cid");
$userStmt->execute(['cid' => $customer_id]);
$registerDate = $userStmt->fetch(PDO::FETCH_ASSOC)['created'] ?? null;

$today = new DateTime();
$register = new DateTime($registerDate);
$diffDays = $today->diff($register)->days;

// -----------------------------
// ดึงยอดผู้เข้าชมย้อนหลัง 7 วัน
// -----------------------------
if ($diffDays < 7) {
    // ใช้ $T['visits_less_7day'] แทน "ยังไม่ครบ 7 วัน"
    $visits7day = $T['visits_less_7day']; 
} else {
    $visit7Stmt = $db->prepare("
        SELECT SUM(visits_count) AS visits_7day 
        FROM tb_visits 
        WHERE customer_id = :cid AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    ");
    $visit7Stmt->execute(['cid' => $customer_id]);
    $visits7day = $visit7Stmt->fetch(PDO::FETCH_ASSOC)['visits_7day'] ?? 0;
}

// -----------------------------
// แสดงผล
// -----------------------------
// echo "ผู้เข้าชมวันนี้: " . $visits1day . " คน<br>";
// echo "ยอดผู้เข้าชมย้อนหลัง 7 วัน: " . $visits7day;
?>


<div class="profile-container" style="margin-top: 60px; margin-bottom: 50px;">
    <div class="profile-header" id="profileHeader" style="background: #1f1f1f; padding: 20px; border-radius: 12px; color: #fff;">
        <?php
        // รูปโปรไฟล์
        $profileImg = !empty($profile['img_name']) ? "uploads/profile/{$profile['img_name']}" : "img/logo/logo02.png";
        ?>
        <div class="profile-top" style="display:flex; align-items:center; gap:15px;">
            <label for="upload" class="upload-btn" style="cursor:pointer;">
                <img src="<?php echo $profileImg; ?>" alt="Profile Image" class="profile-logo" style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:2px solid #fff;">
            </label>
            <div class="profile-info" style="line-height:1.3;">
                <p class="id" style="font-size:16px; font-weight:bold;"><?php echo $T['user_id_label']; ?>: <?php echo $profile['customer_id']; ?></p>
                <p class="username" style="font-size:14px;">
                    @<?php echo $profile['username']; ?> | <?php
                                                            // ใช้ Key จาก $T สำหรับ VIP Levels
                                                            $vipLevels = [
                                                                $T['vip_level_0'], $T['vip_level_1'], $T['vip_level_2'], 
                                                                $T['vip_level_3'], $T['vip_level_4'], $T['vip_level_5']
                                                            ];
                                                            $vipName = $vipLevels[$profile['vip_id']] ?? $T['vip_level_0'];
                                                            echo $vipName;
                                                            ?>
                </p>

            </div>
        </div>

        <p class="visits" style="font-size:14px; color:#fff;">
            <?php echo $T['visits_1day']; ?>: <strong style="color:rgb(242, 242, 9);"><?php echo $visits1day; ?></strong> | <?php echo $T['visits_7day']; ?>: <strong style="color:rgb(242, 242, 9);"><?php echo $visits7day; ?> </strong>
        </p>
        <?php
        // ดึง wallet และยอดรวม
        $db = connect();
        $walletStmt = $db->prepare("SELECT w_price FROM tb_wallet WHERE customer_id = :cid LIMIT 1");
        $walletStmt->execute(['cid' => $customer_id]);
        $wallet = $walletStmt->fetch(PDO::FETCH_ASSOC);
        $walletBalance = $wallet['w_price'] ?? 0;

        // ยอดขาย + กำไร + จำนวนสินค้า
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

        // คำนวณยอดค้างชำระจริง = ราคาสินค้า - กำไร 20%
        $orderStmt = $db->prepare("
        SELECT SUM(price_qty - (price_qty * 0.2)) as pending_real
        FROM tb_order_detail 
        WHERE user_product_id = :cid AND statust = 0
        ");
        $orderStmt->execute(['cid' => $customer_id]);
        $pendingOrder = $orderStmt->fetch(PDO::FETCH_ASSOC)['pending_real'] ?? 0;


        $stmt = $db->prepare("SELECT COUNT(*) as total_products FROM tb_product WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalProducts = $result['total_products'];

        ?>

        <div class="wallet-info" style="margin-top:20px; display:flex; flex-wrap:wrap; gap:15px;">
            <div class="info-card" style="background:#2c2c2c; padding:15px; border-radius:10px; flex:1; min-width:150px; text-align:center;">
                <p style="margin:0; font-size:14px;"><?php echo $T['wallet_balance']; ?></p>
                <p style="margin:0; font-size:18px; font-weight:bold;">$<?php echo number_format($walletBalance, 2); ?></p>

            </div>
            <div class="info-card" style="background:#2c2c2c; padding:15px; border-radius:10px; flex:1; min-width:150px; text-align:center;">
                <p style="margin:0; font-size:14px;"><?php echo $T['total_sales']; ?></p>
                <p style="margin:0; font-size:18px; font-weight:bold;">$<?php echo number_format($totalSales, 2); ?></p>
            </div>
            <div class="info-card" style="background:#2c2c2c; padding:15px; border-radius:10px; flex:1; min-width:150px; text-align:center;">
                <p style="margin:0; font-size:14px;"><?php echo $T['profit']; ?></p>
                <p style="margin:0; font-size:18px; font-weight:bold;">$<?php echo number_format($totalProfit, 2); ?></p>
            </div>
            <div class="info-card" style="background:#2c2c2c; padding:15px; border-radius:10px; flex:1; min-width:150px; text-align:center;">
                <p style="margin:0; font-size:14px;"><?php echo $T['product_count']; ?></p>
                <p style="margin:0; font-size:18px; font-weight:bold;"><?php echo $totalProducts; ?></p>
            </div>
            <div class="info-card" style="background:#2c2c2c; padding:15px; border-radius:10px; flex:1; min-width:150px; text-align:center;">
                <p style="margin:0; font-size:14px;"><?php echo $T['pending_payment']; ?></p>
                <p style="margin:0; font-size:18px; font-weight:bold;">$<?php echo number_format($pendingOrder, 2); ?></p>
            </div>
        </div>

        <div class="action-buttons" style="margin-top:20px; display:flex; gap:10px; flex-wrap:wrap; justify-content:center;">
            <a href="?page=edit_profiles" class="follow-btn" style="background:#ff2e63; color:#fff; padding:10px 15px; border-radius:8px; text-decoration:none;"><?php echo $T['edit_profile_btn']; ?></a>
            <a href="?page=logout" class="follow-btn" style="background:#555; color:#fff; padding:10px 15px; border-radius:8px; text-decoration:none;"><?php echo $T['logout_btn']; ?></a>
        </div>

    </div>


    <div class="stats">
        <?php
        // ใช้ Key จาก $T สำหรับสถานะคำสั่งซื้อ
        $statuses = [
            0 => ['label_key' => 'status_0', 'icon' => 'bi-cash-coin', 'color' => 'text-danger'],
            1 => ['label_key' => 'status_1', 'icon' => 'bi-box-seam', 'color' => 'text-warning'],
            2 => ['label_key' => 'status_2', 'icon' => 'bi-truck', 'color' => 'text-primary'],
            3 => ['label_key' => 'status_3', 'icon' => 'bi-check-circle-fill', 'color' => 'text-success'],
        ];

        foreach ($statuses as $status => $info) {
            $stmt = $db->query("SELECT COUNT(order_id) as count 
                                FROM tb_order_detail 
                                WHERE user_product_id = '$customer_id' AND statust = $status");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $row['count'] ?? 0;

            // ถ้า status = 0 ให้ไป order.php, ถ้าไม่ใช่ให้ไป order_list
            $link = ($status == 0)
                ? "?page=order&statust=$status"
                : "?page=order_list&statust=$status";
        ?>

            <a href="<?php echo $link; ?>" style="text-decoration:none;">
                <p class="bio <?php echo $info['color']; ?>">
                    <i class="bi <?php echo $info['icon']; ?> me-2"></i>
                    <span><strong><?php echo $count; ?></strong> <?php echo $T[$info['label_key']]; ?></span>
                </p>
            </a>

        <?php } ?>


    </div>
    <div class="menu-grid">
        <a href="?page=payment" class="menu-item orange">
            <i class="bi bi-wallet2"></i>
            <p><?php echo $T['menu_payment']; ?></p>
        </a>
        <a href="?page=withdraw" class="menu-item orange">
            <i class="bi bi-arrow-down-circle"></i>
            <p><?php echo $T['menu_withdraw']; ?></p>
        </a>
        <a href="?page=money_today" class="menu-item orange">
            <i class="bi bi-piggy-bank"></i>
            <p><?php echo $T['menu_money_log']; ?></p>
        </a>

        <a href="?page=my_order" class="menu-item red">
            <i class="bi bi-receipt"></i>
            <p><?php echo $T['menu_order_history']; ?></p>
        </a>
        <a href="?page=order_today" class="menu-item pink">
            <i class="bi bi-calendar-check"></i>
            <p><?php echo $T['menu_daily_list']; ?></p>
        </a>

        <a href="?page=edit_profiles" class="menu-item blue">
            <i class="bi bi-person-badge"></i>
            <p><?php echo $T['menu_my_profile']; ?></p>
        </a>
        <a href="?page=edit_profiles" class="menu-item green">
            <i class="bi bi-geo-alt"></i>
            <p><?php echo $T['menu_address']; ?></p>
        </a>
        <a href="?page=change_password" class="menu-item teal">
            <i class="bi bi-key"></i>
            <p><?php echo $T['menu_change_password']; ?></p>
        </a>

        <a href="?page=add_product" class="menu-item blue">
            <i class="bi bi-file-earmark-text"></i>
            <p><?php echo $T['menu_add_product']; ?></p>
        </a>
        <a href="#promo-section" class="menu-item orange">
            <i class="bi bi-card-image"></i>
            <p><?php echo $T['menu_promo_card']; ?></p>
        </a>

        <a href="?page=chat&user=17" class="menu-item green">
            <i class="bi bi-chat-dots"></i>
            <p><?php echo $T['menu_live_chat']; ?></p>
        </a>

    </div>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <div class="service">
        <a href="?page=market" class="icon" style="color:#333; font-size: 20px; margin: 7px;"><i class="bi bi-shop-window"></i>
            <p style="font-size: 14px; margin-top: -5px; font-weight: bolder;"><?php echo $T['store']; ?></p>
        </a>
        <a href="?page=video" class="icon" style="color:#333; font-size: 20px; margin: 7px;"><i class="bi bi-play-btn"></i>
            <p style="font-size: 14px; margin-top: -5px; font-weight: bolder;"><?php echo $T['video']; ?></p>
        </a>
        <a href="?page=chat&user=17" class="icon" style="color:#333; font-size: 20px; margin: 7px;"><i class="bi bi-headset"></i>
            <p style="font-size: 14px; margin-top: -5px; font-weight: bolder"><?php echo $T['customer_service']; ?></p>
        </a>
        <a href="https://www.channelengine.com/en/blog/tiktok-shop-what-is-it-and-how-does-it-work#:~:text=TikTok%20Shop%20is%20the%20social,start%20selling%20on%20TikTok%20Shop." class="icon" style="color:#333; font-size: 20px; margin: 7px;" target="_blank"><i class="bi bi-bag-heart-fill"></i>
            <p style="font-size: 14px; margin-top: -5px; font-weight: bolder"><?php echo $T['about']; ?></p>
        </a>
    </div>
    <hr>
    <div class="product-list" id="promo-section" style="margin: 10px 10px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
        <?php
        $db = connect();
        $stmt = $db->query("SELECT * FROM tb_product WHERE customer_id = '$customer_id' ORDER BY id DESC");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            echo '<div class="data"><i class="bi bi-bag-x"></i></div>';
        } else {
            foreach ($result as $row) {
                $profit = $row['price'] * 0.2;
        ?>
                <div class="product" style="position: relative; border-radius: 8px; overflow: hidden; background-color: #fff;">
                    <img src="uploads/<?php echo $row['img_name']; ?>" alt="Product Image" style="width: 100%; display: block; border-radius: 8px;">
                    <div class="profit-badge">
                        +<?php echo number_format($profit); ?> $
                    </div>
                    <p style="text-align:left; margin-left:10px">
                        <?php
                        $string = strip_tags($row['name']);
                        if (strlen($string) > 5) {
                            $stringCut = substr($string, 0, 35);
                            $endPoint = strrpos($stringCut, ' ');
                            $string = $endPoint ? substr($stringCut, 0, $endPoint) : $stringCut;
                        }
                        echo $string;
                        ?>
                    </p>
                    <p class="price" style="text-align:justify; margin-left:10px">
                        $<?php echo number_format($row['price'], 2); ?>
                    </p>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>

<script>
    const popup = document.getElementById('qrPopup');
    const showPopupBtn = document.getElementById('showPopupBtn');
    const closePopupBtn = document.getElementById('closePopupBtn');

    showPopupBtn.addEventListener('click', () => {
        popup.style.display = 'flex';
    });

    closePopupBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });

    // Close popup if user clicks outside the popup content
    window.addEventListener('click', (event) => {
        if (event.target === popup) {
            popup.style.display = 'none';
        }
    });
</script>

<style>
    .stats {
        display: flex;
        justify-content: space-around;
        /* กระจายปุ่มให้ห่างเท่า ๆ กัน */
        align-items: center;
        background: #f5f5f5;
        padding: 10px;
        border-radius: 12px;
        margin: 10px 0;
    }

    .stats a {
        text-decoration: none;
        color: #000;
        /* สีดำ */
    }

    .stats .bio {
        display: flex;
        flex-direction: column;
        /* เรียงเป็นแนวตั้ง */
        align-items: center;
        /* จัดกลางแนวนอน */
        font-size: 14px;
        margin: 0;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        text-align: center;
    }

    .stats .bio i {
        font-size: 28px;
        /* ขยาย icon */
        margin-bottom: 4px;
        /* เว้นช่องว่างด้านล่าง icon */
    }

    .stats .bio:hover {
        background: #e0e0e0;
        /* Hover effect */
    }

    .stats strong {
        font-size: 16px;
        display: block;
    }

    .service {
        display: flex;
        justify-content: center;
        /* จัดให้อยู่ตรงกลางแนวนอน */
        align-items: center;
        /* จัดให้อยู่ตรงกลางแนวตั้ง (ถ้าต้องการเต็มจอ) */
        gap: 20px;
        /* ระยะห่างระหว่าง icon */
        margin-top: 20px;
        /* ปรับ margin ตามต้องการ */
        flex-wrap: wrap;
        /* ถ้าเกินบรรทัดจะตัดบรรทัดใหม่ */
    }

    .service .icon {
        text-align: center;
        text-decoration: none;
    }

    .profit-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background-color: rgba(255, 165, 0, 0.85);
        /* ส้มใส */
        color: #fff;
        padding: 5px 10px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }

    .data {
        font-size: 150px;
        margin-left: 69%;
        height: fit-content;
        margin-top: 20px;
        opacity: 10%;

    }

    .profile-container {
        width: 100%;

    }

    .profile-header {
        text-align: center;
        padding: 20px;
        border-bottom: 1px solid #ddd;
    }

    .profile-logo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .username {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stats {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .stats span {
        font-size: 0.9em;
        color: #555;
    }

    .follow-btn {
        background-color: #ddd;
        color: #555;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 1em;
        cursor: pointer;
        margin-bottom: 15px;
        width: auto;
    }

    .follow-btn:hover {
        background-color: #ddd;
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .bio {
        font-size: 0.9em;
        color: #555;
        margin-bottom: 10px;
    }

    .website-link {
        font-size: 0.9em;
        color: #007aff;
        text-decoration: none;
    }

    .website-link:hover {
        text-decoration: underline;
    }

    /* เมนู */
    .profile-menu {
        display: flex;
        justify-content: center;
        gap: 50px;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .profile-menu span {
        font-size: 1.5em;
        cursor: pointer;
        color: #888;
    }

    .profile-menu .active {
        color: #000;
        border-bottom: 2px solid #000;
        padding-bottom: 5px;
    }

    /* Popup Overlay */
    .popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    /* Popup Content */
    .popup-content {
        background: #fff;
        padding: 20px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        width: 300px;
        position: relative;
    }

    .popup-content h2 {
        font-size: 18px;
        color: #333;
        margin-bottom: 15px;
    }

    .popup-content img {
        width: 150px;
        height: 150px;
        margin-bottom: 10px;
    }

    .popup-content p {
        font-size: 14px;
        color: #555;
    }

    /* Close Button */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        color: #333;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-btn:hover {
        color: #ff2e63;
    }
</style>
<style>
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px 10px;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .menu-item {
        text-decoration: none;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.2s ease-in-out;
    }

    .menu-item i {
        font-size: 30px;
        margin-bottom: 5px;
    }

    .menu-item p {
        font-size: 13px;
        font-weight: bold;
        margin: 0;
    }

    /* เอฟเฟก Hover */
    .menu-item:hover {
        transform: translateY(-5px);
        opacity: 0.8;
    }

    /* สีไอคอน */
    .orange i {
        color: #FF9800;
    }

    .red i {
        color: #F44336;
    }

    .blue i {
        color: #2196F3;
    }

    .green i {
        color: #4CAF50;
    }

    .teal i {
        color: #009688;
    }

    .pink i {
        color: #E91E63;
    }
</style>


<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>

<script>
    // ให้ค่อย ๆ แสดงผลหลังจากโหลดเสร็จ
    document.addEventListener("DOMContentLoaded", function() {
        const header = document.getElementById("profileHeader");
        header.classList.add("show");
    });
</script>