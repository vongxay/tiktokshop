
<script>
    // เรียกใช้งาน fetchData เมื่อหน้าเว็บโหลด
    fetchData();
</script>
<?php

if (!isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ

    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ

    echo '<script> location.replace("?page=login"); </script>';
}

$profile = getCustomerBy($_SESSION['admin_loggedId']);
$customer_id = $profile['id'];

// ✅ ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
  session_destroy(); // ลบ session
  echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
  exit;
}


?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-4">

  <!-- ปุ่มย้อนกลับ -->

  <h2 class="text-center mb-4">ข้อความรอการตอบกลับ</h2>
  <div class="mb-4">
    <a href="?page=home" class="">
      <i class="bi bi-arrow-left"></i> ย้อนกลับ
    </a>
  </div>
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
  
    <?php
      $db = connect();
      $stmt = $db->query("SELECT m.*, c.customer_id, c.username, c.img_name 
                          FROM tb_messages m 
                          INNER JOIN tb_customer c ON m.user_id = c.id 
                          WHERE m.status = 0 AND m.receiver_id = $customer_id 
                          ORDER BY m.timestamp DESC");
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($result as $row):
    ?>
      <div class="col">
        <div class="card h-100 shadow-sm border-0" style="background: #fff7f7; border-radius: 16px;">
          <div class="card-body d-flex flex-column">
            
            <!-- ชื่อผู้ส่ง + Avatar -->
            <div class="d-flex align-items-center mb-2">
              <?php if (!empty($row['img_name'])): ?>
                <img src="../uploads/profile/<?= htmlspecialchars($row['img_name']) ?>" alt="Avatar" class="rounded-circle me-2" style="width:40px; height:40px;">
              <?php else: ?>
                <div class="rounded-circle bg-secondary me-2" style="width:40px; height:40px;"></div>
              <?php endif; ?>
              <h6 class="card-title mb-0 fw-bold text-dark">
                ผู้ใช้: <?= htmlspecialchars($row['username']) ?> (ID: <?= $row['customer_id'] ?>)
              </h6>
            </div>

            <!-- ข้อความ -->
            <p class="card-text text-dark flex-grow-1" style="white-space: pre-line;"><?= nl2br(htmlspecialchars($row['message'])) ?></p>

            <!-- ไฟล์แนบ -->
            <?php if (!empty($row['file_name'])): ?>
              <div class="text-center mt-2 mb-2">
                <a href="../uploads/chat/<?= htmlspecialchars($row['file_name']) ?>" target="_blank">
                  <img src="../uploads/chat/<?= htmlspecialchars($row['file_name']) ?>" alt="แนบรูป" class="img-fluid rounded" style="max-height:120px; border:1px solid #ddd;">
                </a>
              </div>
            <?php endif; ?>

            <!-- วันที่ -->
            <p class="text-muted small mb-3">
              📅 <?= date('d.m.Y', strtotime($row['timestamp'])) ?>
            </p>

            <!-- ปุ่มตอบกลับ -->
            <a href="?page=chat&id=<?= $row['sender_id'] ?>" class="btn btn-outline-danger btn-sm w-100 mt-auto">
              <i class="bi bi-chat-dots-fill me-1"></i> ตอบกลับ
            </a>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>


