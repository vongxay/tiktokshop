<?php
if (!isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ

    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ

    echo '<script> location.replace("?page=login"); </script>';
}

$profile = getCustomerBy($_SESSION['admin_loggedId']);
$custom_id = $profile['id'];


// ✅ ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
    session_destroy(); // ลบ session
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
    exit;
}

// ดึงข้อมูล tb_usd ล่าสุด
$stmt = $db->query("SELECT * FROM tb_usd ORDER BY created DESC");
$usd_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// อัปเดตข้อมูล tb_usd
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_usd'])) {
    $usd_id = intval($_POST['usd_id']);
    $code_name = $_POST['code_name'];
    $code = $_POST['code'];

    // อัปโหลดรูปถ้ามี
    $img_name = "";
    if (isset($_FILES['img_name']) && $_FILES['img_name']['error'] === 0) {
        $ext = pathinfo($_FILES['img_name']['name'], PATHINFO_EXTENSION);
        $img_name = "slip_" . time() . "." . $ext;
        move_uploaded_file($_FILES['img_name']['tmp_name'], "../pages/uploads/slip/" . $img_name);
    }

    // อัปเดตฐานข้อมูล
    if ($img_name) {
        $stmt = $db->prepare("UPDATE tb_usd SET code_name = :code_name, code = :code, img_name = :img_name WHERE id = :id");
        $stmt->execute([
            ':code_name' => $code_name,
            ':code' => $code,
            ':img_name' => $img_name,
            ':id' => $usd_id
        ]);
    } else {
        $stmt = $db->prepare("UPDATE tb_usd SET code_name = :code_name, code = :code WHERE id = :id");
        $stmt->execute([
            ':code_name' => $code_name,
            ':code' => $code,
            ':id' => $usd_id
        ]);
    }

    echo "<script>alert('อัปเดตข้อมูลเรียบร้อย'); window.location.href='?page=usdt';</script>";
}
?>

<div class="container px-4 py-5" id="featured-3">

    <div class="container rounded bg-white mt-5 mb-5 shadow-lg p-3 mb-3 bg-body rounded">
         <!-- ปุ่มย้อนกลับ -->
    <div style="margin-bottom:15px;">
        <a href="?page=home" class="" style="text-decoration:none; padding:8px 12px; border-radius:6px; color:#000;">
            ← ย้อนกลับ
        </a>
    </div>
        <?php if (!empty($usd_list)): ?>
            <div style="display:flex; flex-direction:column; gap:15px; margin-top:20px;">
                <?php foreach ($usd_list as $usd): ?>
                    <form action="" method="post" enctype="multipart/form-data" style="
                display:flex; 
                align-items:center; 
                gap:15px; 
                padding:12px 15px; 
                border:1px solid #e0e0e0; 
                border-radius:10px; 
                background:#ffffff;
                box-shadow:0 4px 8px rgba(0,0,0,0.05);
                transition: transform 0.2s, box-shadow 0.2s;
            " onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.05)';">

                        <input type="hidden" name="usd_id" value="<?php echo $usd['id']; ?>">

                        <input type="text" name="code_name" value="<?php echo htmlspecialchars($usd['code_name']); ?>"
                            style="width:140px; padding:6px 8px; border:1px solid #ccc; border-radius:6px; font-size:14px;"
                            placeholder="ชื่อสกุลเงิน">

                        <input type="text" name="code" value="<?php echo htmlspecialchars($usd['code']); ?>"
                            style="width:160px; padding:6px 8px; border:1px solid #ccc; border-radius:6px; font-size:14px;"
                            placeholder="ค่าเงิน">

                        <input type="file" name="img_name" style="width:130px; padding:5px; border:1px solid #ccc; border-radius:6px; font-size:12px;">

                        <button type="submit" name="update_usd"
                            style="padding:6px 14px; background:#28a745; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold; transition: background 0.2s;"
                            onmouseover="this.style.background='#218838';"
                            onmouseout="this.style.background='#28a745';">
                            💾 อัปเดต
                        </button>

                        <?php if (!empty($usd['img_name'])): ?>
                            <img src="../pages/uploads/slip/<?php echo $usd['img_name']; ?>"
                                alt="logo"
                                style="width:40px; height:40px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">
                        <?php endif; ?>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>