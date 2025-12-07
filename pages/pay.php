<div class="product-box">
    <?php

    if (isset($_POST['pay'])) {
        // กำหนดค่าหรือข้อมูลที่จะอัปเดต
        $new_status = "1"; // หรือค่าที่คุณต้องการเปลี่ยน เช่น เปิด/ปิด, ยืนยันแล้ว/ยังไม่ยืนยัน, ฯลฯ
        $product_id = $_GET['id'];
        $order_id = $_GET['order_id'];
        $user_product_id = $_GET['user_product_id'];
        $price_qty = $_GET['price_qty'];
        $name = $_GET['name'];
        // $order_date = $_GET['order_date'];
        // echo $price_qty;

        $db = connect();
        $stmt = $db->query("SELECT * FROM tb_wallet WHERE customer_id = $user_product_id");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $w_price = $row['w_price'];
            if ($w_price > $price_qty) {

                $drop_price = $w_price - $price_qty;
            }
        }

        $stmt = $db->prepare("UPDATE tb_order_detail SET product_id = '$product_id', user_product_id = $user_product_id, statust = $new_status WHERE product_id = '$product_id' AND order_id = '$order_id'");
        if ($stmt->execute()) {
            $stmt = $db->prepare("UPDATE tb_wallet SET w_price = $drop_price WHERE customer_id = $user_product_id");
            if ($stmt->execute()) {
    ?>

                <div class="receipt" style="width: 100%;">
                    <h1>Receipt</h1>
                    <?php
                    echo '<script>
             Swal.fire({
           
                 text: "success!",
                 icon: "success",
                 showConfirmButton: false, // ซ่อนปุ่ม
                 timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
             });
         </script>';
                    echo '
                     <script type="text/javascript">
                         setTimeout(function() {
                             location.href = "?page=order";
                         }, 4000);
                     </script>
                 ';
                    ?>

                    <p>Store Name: TKShop</p>
                    <p id="date"></p>
                    <p>Order ID: <?php echo $order_id; ?></p>

                    <hr>

                    <div class="items">
                        <p><span><?php echo $name; ?></span><span style="margin:10px">$<?php echo $price_qty; ?></span></p>
                    </div>

                    <hr>

                    <p class="total">Total: $<?php echo $price_qty; ?></p>

                    <hr>

                    <div class="footer">
                        <p>Thank you for shopping with us!</p>
                        <p>www.tkshop.com</p>
                    </div>
                </div>
    <?php
            } else {
                echo "No updated";
            }
        } else {
            echo "No";
        }
    }
    ?>


</div>


<script>
    // รับวันที่ปัจจุบัน
    const currentDate = new Date();

    // จัดรูปแบบวันที่ให้เป็นภาษาไทย
    const thaiDateFormatter = new Intl.DateTimeFormat('th-TH', {
        year: 'numeric',
        month: 'long', // แสดงชื่อเดือน
        day: 'numeric',
        weekday: 'long' // แสดงวันของสัปดาห์
    });

    // นำวันที่ที่จัดรูปแบบมาแสดง
    document.getElementById("date").innerText = thaiDateFormatter.format(currentDate);
</script>

<!-- css -->
<style>
    /* สไตล์สำหรับใบเสร็จ */


    .receipt {
        width: 320px;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .receipt h1 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .receipt p {
        font-size: 14px;
        margin: 5px 0;
    }

    .receipt hr {
        border: none;
        border-top: 1px dashed #ccc;
        margin: 10px 0;
    }

    .receipt .total {
        font-size: 18px;
        font-weight: bold;
        text-align: right;
    }

    .receipt .items {
        margin-bottom: 20px;
    }

    .receipt .items p {
        display: flex;
        justify-content: space-between;
    }

    .receipt .footer {
        text-align: center;
        font-size: 12px;
        color: #777;
    }

    .receipt .footer p {
        margin: 5px 0;
    }
</style>