<?php


if (!isset($_SESSION['admin_username'])) {
    echo '<script> location.replace("?page=login"); </script>';
    exit;
}

$profile = getCustomerBy($_SESSION['admin_loggedId']);
$custom_id = $profile['id'];
$customer_name = $profile['username'];

// ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
    session_destroy();
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
    exit;
}

$db = connect();

// Handle edit customer form
if (isset($_POST['edit-product-btn'])) {
    $id = (int) $_POST['id'];
    $username = htmlentities($_POST['username']);
    $store = htmlentities($_POST['store']);
    $fname = htmlentities($_POST['fname']);
    $lname = htmlentities($_POST['lname']);
    $mobile = htmlentities($_POST['mobile']);
    $address = htmlentities($_POST['address']);
    $home = htmlentities($_POST['home']);
    $distric = htmlentities($_POST['distric']);
    $province = htmlentities($_POST['province']);
    $vip_id = !empty($_POST['vip_id']) ? htmlentities($_POST['vip_id']) : 0;
    $bank_account = htmlentities($_POST['bank_account']);
    $bank_username = htmlentities($_POST['bank_username']);
    $bank_name = htmlentities($_POST['bank_name']);
    $w_price = floatval($_POST['w_price'] ?? 0);
    $walletAction = $_POST['wallet_action'] ?? '';
    
    // 🔑 เพิ่ม customer_id ที่รับจากฟอร์ม
    $customer_id = htmlentities($_POST['customer_id']); 

    // Update customer
    $stmt = $db->prepare("UPDATE tb_customer 
        SET username=:username, store=:store, fname=:fname, lname=:lname,
            mobile=:mobile, address=:address, home=:home, distric=:distric,
            province=:province, vip_id=:vip_id, bank_account=:bank_account,
            bank_username=:bank_username, bank_name=:bank_name, 
            customer_id=:customer_id /* 🔑 เพิ่ม customer_id ใน UPDATE */
        WHERE id=:id
    ");
    $stmt->execute([
        ':username'=>$username, ':store'=>$store, ':fname'=>$fname, ':lname'=>$lname,
        ':mobile'=>$mobile, ':address'=>$address, ':home'=>$home, ':distric'=>$distric,
        ':province'=>$province, ':vip_id'=>$vip_id, ':bank_account'=>$bank_account,
        ':bank_username'=>$bank_username, ':bank_name'=>$bank_name, 
        ':customer_id'=>$customer_id, /* 🔑 ผูกค่า customer_id */
        ':id'=>$id
    ]);

    // Handle wallet
    $stmt = $db->prepare("SELECT w_price FROM tb_wallet WHERE customer_id = :cid LIMIT 1");
    $stmt->execute(['cid' => $id]);
    $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
    $walletBalance = $wallet['w_price'] ?? 0;

    if ($walletAction === 'add' && $w_price > 0) {
        if ($wallet) {
            $update = $db->prepare("UPDATE tb_wallet SET w_price = w_price + :amount WHERE customer_id = :cid");
            $update->execute(['amount'=>$w_price, 'cid'=>$id]);
        } else {
            $insert = $db->prepare("INSERT INTO tb_wallet (customer_id, w_price, status) VALUES (:cid, :amount, 0)");
            $insert->execute(['cid'=>$id, 'amount'=>$w_price]);
        }
    } elseif ($walletAction === 'deduct' && $w_price > 0) {
        if ($w_price > $walletBalance) {
            echo '<div class="alert alert-danger">❌ ยอดเงินไม่เพียงพอสำหรับถอน</div>';
        } elseif ($wallet) {
            $update = $db->prepare("UPDATE tb_wallet SET w_price = w_price - :amount WHERE customer_id = :cid");
            $update->execute(['amount'=>$w_price, 'cid'=>$id]);
        }
    }

    echo '<div class="alert alert-success">บันทึกข้อมูลเรียบร้อยแล้ว!</div>';
}

// Handle toggle lock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_lock'], $_POST['customer_id'])) {
    $cid = (int) $_POST['customer_id'];
    $stmt = $db->prepare("SELECT `lock` FROM tb_customer WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $cid]);
    $cur = (int) ($stmt->fetchColumn() ?? 0);
    $new = $cur === 1 ? 0 : 1;

    $upd = $db->prepare("UPDATE tb_customer SET `lock` = :new WHERE id = :id");
    $upd->execute([':new' => $new, ':id' => $cid]);

    echo "<script>alert('อัปเดตเรียบร้อยแล้ว'); window.location='?page=customer';</script>";
    exit;
}

// Handle delete customer
if (isset($_GET['method'], $_GET['id']) && $_GET['method'] === 'delet') {
    $id = (int) $_GET['id'];
    $stmt = $db->prepare("DELETE FROM tb_customer WHERE id = :id");
    $stmt->execute([':id'=>$id]);
    echo "<script>alert('ลบข้อมูลเรียบร้อยแล้ว'); window.location='?page=customer';</script>";
    exit;
}
?>
<?php
// ตรวจสอบ method=edit
if (isset($_GET['method']) && $_GET['method'] === 'edit' && isset($_GET['id'])) {
    $edit_id = (int)$_GET['id'];
    $stmt = $db->prepare("SELECT * FROM tb_customer WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $edit_id]);
    $editCustomer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($editCustomer):
        // Wallet
        $stmtWallet = $db->prepare("SELECT w_price FROM tb_wallet WHERE customer_id=:cid LIMIT 1");
        $stmtWallet->execute(['cid'=>$edit_id]);
        $wallet = $stmtWallet->fetch(PDO::FETCH_ASSOC);
        $walletBalance = $wallet['w_price'] ?? 0;
?>

<div class="container py-5">
    <h3 class="mb-4">แก้ไขข้อมูลลูกค้า: <strong><?php echo $editCustomer['username']; ?></strong></h3>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo $editCustomer['id']; ?>">
        <input type="hidden" name="wallet_action" id="wallet_action" value="">

        <div class="row g-3">

            <div class="col-md-6">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">ข้อมูลส่วนตัว</div>
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <label>รหัสลูกค้า (Customer ID)</label>
                            <input type="text" name="customer_id" class="form-control" value="<?php echo $editCustomer['customer_id']; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label>ชื่อบัญชีผู้ใช้</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $editCustomer['username']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>ตลาด</label>
                            <input type="text" name="store" class="form-control" value="<?php echo $editCustomer['store']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>ชื่อ</label>
                            <input type="text" name="fname" class="form-control" value="<?php echo $editCustomer['fname']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>นามสกุล</label>
                            <input type="text" name="lname" class="form-control" value="<?php echo $editCustomer['lname']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>เบอร์โทร</label>
                            <input type="text" name="mobile" class="form-control" value="<?php echo $editCustomer['mobile']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>ที่อยู่</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $editCustomer['address']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>บ้าน</label>
                            <input type="text" name="home" class="form-control" value="<?php echo $editCustomer['home']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>อำเภอ</label>
                            <input type="text" name="distric" class="form-control" value="<?php echo $editCustomer['distric']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>จังหวัด</label>
                            <input type="text" name="province" class="form-control" value="<?php echo $editCustomer['province']; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-success text-white">บัญชีธนาคาร</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>บัญชีธนาคาร</label>
                            <input type="text" name="bank_account" class="form-control" value="<?php echo $editCustomer['bank_account']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>ชื่อบัญชีธนาคาร</label>
                            <input type="text" name="bank_username" class="form-control" value="<?php echo $editCustomer['bank_username']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>ชื่อธนาคาร</label>
                            <input type="text" name="bank_name" class="form-control" value="<?php echo $editCustomer['bank_name']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>ระดับ VIP</label>
                            <select name="vip_id" class="form-select">
                                <option value="" disabled>เลือกระดับ VIP</option>
                                <?php
                                $stmtVIP = $db->query("SELECT * FROM tb_vip");
                                $vips = $stmtVIP->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($vips as $vip):
                                ?>
                                <option value="<?php echo $vip['vip_id']; ?>" <?php echo ($editCustomer['vip_id'] == $vip['vip_id'])?'selected':''; ?>>
                                    <?php echo $vip['vip_number']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3 mt-3">
                            <label>ยอด Wallet ปัจจุบัน: $<?php echo number_format($walletBalance); ?></label>
                            <input type="number" name="w_price" class="form-control" value="0" min="0">
                            <div class="mt-2 d-flex gap-2">
                                <button type="submit" name="edit-product-btn" class="btn btn-success flex-fill" onclick="document.getElementById('wallet_action').value='add'">เติมเงิน</button>
                                <button type="submit" name="edit-product-btn" class="btn btn-danger flex-fill" onclick="document.getElementById('wallet_action').value='deduct'">ถอนเงิน</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 text-center">
            <button type="submit" name="edit-product-btn" class="btn btn-primary btn-lg me-2">บันทึกข้อมูล</button>
            <a href="?page=customer" class="btn btn-secondary btn-lg">กลับ</a>
        </div>
    </form>
</div>


<?php
    endif;
}
?>
  
<div class="table-responsive">
    <div class="container px-4 py-5" id="featured-3">
        <h2 class="mb-4">ข้อมูลผู้ใช้</h2><div class="d-flex justify-content-end mb-3">
        <a href="?page=home">
            <i class="bi bi-arrow-left-circle"></i> ย้อนกลับ
        </a>
    </div>

        <table class="table table-striped table-hover text-center" id="example">
            <thead class="table">
                <tr>
                    <th>No</th>
                    <th>รูปภาพ</th>
                    <th>ไอดี</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>บัญชีผู้ใช้</th>
                    <th>ตลาด</th>
                    <th>เบอร์โทร</th>
                    <th>บัญชีธนาคาร</th>
                    <th>ชื่อบัญชี</th>
                    <th>ชื่อธนาคาร</th>
                    <th>VIP</th>
                    <th>Wallet</th>
                    <th>จัดการ</th>
                    <th>ลบ</th>
                    <th>แชท</th>
                    <th>Lock</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $stmt = $db->query("SELECT * FROM tb_customer ORDER BY id DESC");
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i = 0;
            foreach ($customers as $row):
                $i++;
                $cust_id = $row['id'];

                // VIP
                $vipLevels = ["ลูกค้าทั่วไป","VIP I","VIP II","VIP III","VIP IV","VIP V"];
                $vipText = $vipLevels[$row['vip_id']] ?? "ลูกค้าทั่วไป";

                // Wallet
                $stmtWallet = $db->prepare("SELECT w_price FROM tb_wallet WHERE customer_id=:cid LIMIT 1");
                $stmtWallet->execute(['cid'=>$cust_id]);
                $wallet = $stmtWallet->fetch(PDO::FETCH_ASSOC);
                $walletBalance = $wallet['w_price'] ?? 0;

                // Lock
                $currentLock = (int)$row['lock'];

                // Message count
                $stmtMsg = $db->query("SELECT COUNT(message) as messages FROM tb_messages WHERE user_id=$cust_id");
                $msgCount = $stmtMsg->fetch(PDO::FETCH_ASSOC)['messages'] ?? 0;
            ?>
                <tr>
                    <th><?php echo $i; ?></th>
                    <td>
                        <?php if(!empty($row['img_name'])): ?>
                            <img src="../uploads/profile/<?php echo $row['img_name']; ?>" class="rounded-circle" width="50" height="50">
                        <?php else: ?>
                            <span class="text-muted">No img</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['customer_id']; ?></td>
                    <td><?php echo $row['fname']." ".$row['lname']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['store']; ?></td>
                    <td><?php echo $row['mobile']; ?></td>
                    <td><?php echo $row['bank_account'] ?: "xxx-x0"; ?></td>
                    <td><?php echo $row['bank_username'] ?: "empty"; ?></td>
                    <td><?php echo $row['bank_name'] ?: "empty"; ?></td>
                    <td>
                        <span class="badge bg-info text-dark"><?php echo $vipText; ?></span>
                    </td>
                    <td>
                        <span class="badge bg-success">$<?php echo number_format($walletBalance); ?></span><br>
                        </td>
                    <td>
                    <a href="?page=customer&method=edit&id=<?php echo $cust_id; ?>" class="btn btn-sm btn-outline-success mb-1">จัดการ</a>
                    </td>
                    <td>
                        
                        <a href="?page=customer&method=delet&id=<?php echo $cust_id; ?>" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?');" class="btn btn-sm btn-outline-danger mb-1">ลบ</a>
                    </td>
                    <td>
                        <a href="?page=chat&id=<?php echo $cust_id; ?>" class="btn btn-sm btn-dark">
                            แชท<?php echo $msgCount; ?>
                        </a>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="customer_id" value="<?php echo $cust_id; ?>">
                            <button type="submit" name="toggle_lock" class="btn btn-sm <?php echo $currentLock===1?'btn-danger':'btn-primary'; ?>">
                                <?php echo $currentLock===1?'Locked':'Lock'; ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>