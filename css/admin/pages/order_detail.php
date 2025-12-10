<?php
if (!isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ
    echo '<script> location.replace("?page=login"); </script>';
}

$profile = getCustomerBy($_SESSION['admin_loggedId']);
$od = $_GET['id'];


// ✅ ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
    session_destroy(); // ลบ session
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
    exit;
}

?>
<div class="table-responsive">
    <div class="container px-4 py-5" id="featured-3">
    <div class="product-box">
        <a href="?page=order_statust" class="btn btn-dark"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5z" />
            </svg> ย้อนกลับ <span class="badge bg-secondary" style="color: yellow;"><?php echo $od; ?></span></a>
        <hr>

        <table class="table table-user-information shadow-lg p-3 mb-5 bg-body rounded">
            <thead>
                <th>No</th>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>ราคา</th>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach (getOrderedProducts_detail($_REQUEST['id']) as $row) {
                    $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['qty']; ?></td>
                        <td>$<?php echo number_format($row['price_qty']); ?></td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
 
    </div>