<?php include_once('includes/braner.php'); ?>


<?php
if (!isset($_SESSION['username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລຍ
    echo '<script> location.replace("?page=login"); </script>';
}
// ต้องแน่ใจว่าได้เรียก include header.php หรือไฟล์ที่กำหนดตัวแปร $T และ session_start() แล้ว
// ตัวอย่าง: include_once('header.php'); 

$profile = getCustomerBy($_SESSION['loggedId']);
$id = $profile['id'];
error_reporting(0);
$product_id = $_GET['id'];
$act = $_GET['act'];

if ($act == 'add' && !empty($product_id)) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    } ?>
    <script type="text/javascript">
        setTimeout(function() {
            location.href = "?page=home&id=<?php echo $product_id; ?>";
        }, 10);
    </script>
<?php
}
if ($act == 'put' && !empty($product_id))  //ลบลายกานที่ละ1
{
    $_SESSION['cart'][$product_id] -= 1;
} elseif ($_SESSION['cart'][$product_id] < 1) {
    unset($_SESSION['cart'][$product_id]);
}

if ($act == 'plus' && !empty($product_id))  //เพีมลายกานที่ละ1
{
    $_SESSION['cart'][$product_id] += 1;
} elseif ($_SESSION['cart'][$product_id] < 1) {
    unset($_SESSION['cart'][$product_id]);
}


if ($act == 'remove' && !empty($product_id))  //ยกเลิกการสั่งซื้อ
{
    unset($_SESSION['cart'][$product_id]);
}

if ($act == 'update') {
    $amount_array = $_POST['amount'];
    foreach ($amount_array as $p_id => $amount) {
        $_SESSION['cart'][$p_id] = $amount;
    }
}

?>

<div class="card">
    <div class="row">
        <div class="col-md-8 cart">
            <div class="title">
                <div class="row">
                    <div class="col">
                        </div>
                    <div class="col align-self-center text-right text-muted">
                        <?php if (isset($_SESSION['cart'])) {
                            echo count($_SESSION['cart']);
                        } ?> <?php echo $T['cart_items_count']; ?>
                    </div>
                </div>
            </div>

            <?php
            if (!empty($_SESSION['cart'])) {
                $total = 0;
                foreach ($_SESSION['cart'] as $cart_id => $qty) { // เปลี่ยน $id เป็น $cart_id เพื่อไม่ให้ชนกับ $profile['id']
                    $db = connect();
                    $smt = $db->query("SELECT * FROM tb_product  WHERE product_id = '" . $cart_id . "'");
                    $result = $smt->fetchAll(PDO::FETCH_ASSOC);
                    $i = 0;
                    foreach ($result as $row) {
                        $i++;
                        $count = $smt->rowCount();
                        $sum = $row['price'] * $qty;
                        $total += $sum;
                        $pqty = $row['qty'];

                        $string = $row['name'];
                        $string = strip_tags($string);
                        if (strlen($string) > 0) {
    
                            // truncate string
                            $stringCut = substr($string, 0, 25);
                            $endPoint = strrpos($stringCut, ' ');
    
                            //if the string doesn't contain any space then it will cut without word basis.
                            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                        }

            ?>
                        <div class="card mb-3 shadow-sm rounded-3">
                            <div class="card-body">
                                <div class="row align-items-center text-center text-md-start">
                                    
                                    <div class="col-3 col-md-2">
                                        <img src="uploads/<?php echo $row['img_name']; ?>" 
                                            alt="product" 
                                            class="img-fluid rounded-circle shadow-sm"
                                            style="width:60px; height:60px; object-fit:cover;">
                                    </div>

                                    <div class="col-9 col-md-4 mb-2 mb-md-0">
                                        <div class="fw-semibold"><?php echo $string; ?></div>
                                    </div>

                                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                                        <div class="d-flex justify-content-center justify-content-md-start align-items-center gap-2">
                                            <a href="?page=cart&id=<?php echo $cart_id ?>&act=put" 
                                            class="btn btn-outline-danger btn-sm">-</a>

                                            <?php if ($qty >= $pqty) { ?>
                                                <input type="text" value="<?php echo $pqty; ?>" 
                                                    class="form-control text-center" style="max-width:60px;" disabled>
                                                <a href="#" class="btn btn-outline-secondary btn-sm disabled">+</a>
                                            <?php } else { ?>
                                                <input type="text" value="<?php echo $qty; ?>" 
                                                    class="form-control text-center" style="max-width:60px;" disabled>
                                                <a href="?page=cart&id=<?php echo $cart_id ?>&act=plus" 
                                                class="btn btn-outline-success btn-sm">+</a>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-2">
                                        <div class="fw-bold text-success">
                                            <?php echo number_format($sum); ?> ฿
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-1" style="color: red;">
                                        <a href="?page=cart&id=<?php echo $cart_id ?>&act=remove" 
                                        class="delet"><?php echo $T['cart_remove_btn']; ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                          <style>
                            .delet{
                                color:#ff2e63;
                            
                            }
                          </style>                              
                        <?php

                        if ($qty > $pqty) {
                            if (isset($_SESSION['cart'][$product_id])) {
                                $_SESSION['cart'][$product_id] -= 1;
                            }
                        ?>
                            <script>
                                // ใช้ $T['alert_not_enough_stock']
                                alert('<?php echo $T['alert_not_enough_stock']; ?>'), ok;
                            </script>
                        <?php } ?>
            <?php

                    }
                }
            } else {
                if (!isset($_REQUEST['alert'])) {
                    //otherwise tell the user they have no items in their cart
                    // echo "<center><img src='img/av.gif' class='img-fluid' alt=''></center>";
                    echo '<script type="text/javascript">
                      setTimeout(function() {
                          location.href = "?page=home";
                      }, 10);
                  </script>';
                }
            }

            ?>

            <div class="back-to-shop"><a href="?page=product">&leftarrow;</a><span class="text-muted">
                <?php echo $T['cart_back_to_shop']; ?>
            </span></div>
        </div>
        <?php
        if (!empty($_SESSION['cart'])) {
            $pid = $_SESSION['id'];
        ?>
            <div class="col-md-4 summary" style="background-color:#333; color:aliceblue">
              
                <h4><b><?php echo $T['cart_summary']; ?></b></h4>
      
                <div class="row">
                    <div class="col" style="padding-left:0;"><?php echo $T['summary_items_label']; ?> <?php if (isset($_SESSION['cart'])) {
                                                                            echo count($_SESSION['cart']);
                                                                        } ?></div>
                    <div class="col text-right"><?php echo $row['money']; ?><?php echo number_format($total); ?> </div>
                </div>
                <br>

                <form method="POST" action="?page=save_user&id=<?php echo $id; ?>">

                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color:#333; border:#333; color:#ddd" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                            </svg></span>
                        <input type="text" class="form-control" style="background-color:#333; border:#333; color:#ddd" placeholder="<?php echo $T['form_name_placeholder']; ?>" aria-label="Username" aria-describedby="basic-addon1" name="fname" value="<?php echo $profile['fname']; ?>" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color:#333; border:#333; color:#ddd" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                            </svg></span>
                        <input type="text" class="form-control" style="background-color:#333; border:#333; color:#ddd" placeholder="<?php echo $T['form_phone_placeholder']; ?>" name="mobile" value="<?php echo $profile['mobile']; ?>" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color:#333; border:#333; color:#ddd" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg></span>
                        <input type="text" class="form-control" style="background-color:#333; border:#333; color:#ddd" placeholder="<?php echo $T['form_address_placeholder']; ?>" name="address" value="<?php echo $profile['address']; ?>" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color:#333; border:#333; color:#ddd" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-joystick" viewBox="0 0 16 16">
                                <path d="M10 2a2 2 0 0 1-1.5 1.937v5.087c.863.083 1.5.377 1.5.726 0 .414-.895.75-2 .75s-2-.336-2-.75c0-.35.637-.643 1.5-.726V3.937A2 2 0 1 1 10 2" />
                                <path d="M0 9.665v1.717a1 1 0 0 0 .553.894l6.553 3.277a2 2 0 0 0 1.788 0l6.553-3.277a1 1 0 0 0 .553-.894V9.665c0-.1-.06-.19-.152-.23L9.5 6.715v.993l5.227 2.178a.125.125 0 0 1 .001.23l-5.94 2.546a2 2 0 0 1-1.576 0l-5.94-2.546a.125.125 0 0 1 .001-.23L6.5 7.708l-.013-.988L.152 9.435a.25.25 0 0 0-.152.23" />
                            </svg></span>
                        <input type="text" class="form-control" style="background-color:#333; border:#333; color:#ddd" placeholder="<?php echo $T['form_distric_placeholder']; ?>" name="distric" value="<?php echo $profile['distric']; ?>" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    <p id="result"></p>
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color:#333; border:#333; color:#ddd; font-size:22px" id="basic-addon1"><i class="bi bi-key-fill"></i> </span>
                        <input type="number" class="form-control" style="background-color:#333; border:#333; color:#ddd" placeholder="<?php echo $T['form_password_placeholder']; ?>" aria-label="Username" id="passwordInput" aria-describedby="basic-addon1" required>
                    </div>
                    <script>
                     function checkPassword() {
                        // รหัสผ่านที่ถูกต้อง (ตัวอย่าง)
                        const correctPassword = "0123";

                        // รับค่าจาก input
                        const passwordInput = document.getElementById("passwordInput").value;

                        // ตรวจสอบว่ารหัสผ่านที่กรอกตรงกับรหัสผ่านที่ถูกต้องหรือไม่
                        if (passwordInput === correctPassword) {
                            // ใช้ $T['password_correct']
                            document.getElementById("result").innerText = "<?php echo $T['password_correct']; ?>";
                            document.getElementById("result").style.color = "whitesmoke";
                            // แสดงปุ่มที่ถูกซ่อน
                            document.getElementById("hiddenButton").style.display = "block";
                            document.getElementById("check").style.display = "none";
                        } else {
                            // ใช้ $T['password_incorrect']
                            document.getElementById("result").innerText = "<?php echo $T['password_incorrect']; ?>";
                            document.getElementById("result").style.color = "#ff2e63";
                            // ซ่อนปุ่มหากรหัสผ่านไม่ถูกต้อง
                            document.getElementById("hiddenButton").style.display = "none";
                        }
                    }
                        
                    </script>                                                
                        <style>
                        /* ซ่อนปุ่มเริ่มต้น */
                        #hiddenButton {
                            display: none;
                        }
                    </style>
                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col"><?php echo $T['summary_total_price']; ?></div>
                        <div class="col text-right"><?php echo $row['money']; ?><?php echo number_format($total); ?></div>
                    </div>
                    <button class="btn" type="submit" name="submit" id="hiddenButton"><?php echo $T['checkout_btn']; ?></button>
                </form>
                <button class="btn" onclick="checkPassword()" id="check" style="font-size:14px; background-color:#ff2e63; border-radius: 50px; "><?php echo $T['check_password_btn']; ?></button>
            </div>
    </div>
<?php }else{  ?>
</div>
<div class="product-image" style="margin-top:-60px">
  <i class="bi bi-bag-x"></i>
</div>
<?php } ?>

<style>

  .product-image {
    position: relative;
    width: auto;
    /* กำหนดขนาดความกว้าง */
    height: 300px;
    /* กำหนดขนาดความสูง */
    /* background-image: url('img/logo/logo.png');  */
    background-size: cover;
    /* ปรับพื้นหลังให้เต็ม */
    background-repeat: no-repeat;
    /* ไม่ให้พื้นหลังซ้ำ */
    background-position: center;
    /* จัดพื้นหลังให้อยู่ตรงกลาง */
    border-radius: 15px;
    /* เพิ่มมุมโค้ง */
    overflow: hidden;
    /* ซ่อนส่วนเกิน */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    /* เพิ่มเงา */
    display: flex;
    justify-content: center;
    /* จัดตำแหน่งเนื้อหาแนวนอนให้อยู่ตรงกลาง */
    align-items: center;
    /* จัดตำแหน่งเนื้อหาแนวตั้งให้อยู่ตรงกลาง */
    opacity: 10%;
    font-size: 120px;
  }

  .product-image img {
    max-width: 100%;
    /* ปรับขนาดภาพให้เหมาะสม */
    max-height: 100%;
    transition: transform 0.3s ease;
    /* เพิ่มเอฟเฟกต์เวลา hover */
   
  }

  .product-image:hover img {
    transform: scale(1.05);
    /* ขยายภาพเล็กน้อยเมื่อ hover */
  }

  .card{
    margin: auto;
    max-width: 950px;
    width: 90%;
    border-radius: 1rem;
    border: transparent;
    margin-bottom: 50px;
}

.cart{
    background-color: #fff;
    padding: 4vh 5vh;
    border-bottom-left-radius: 1rem;
    border-top-left-radius: 1rem;
}

.summary{
    background-color: #ddd;
    border-top-right-radius: 1rem;
    border-bottom-right-radius: 1rem;
    padding: 4vh;
    color: rgb(65, 65, 65);
    text-align: left;
}

.summary .col-2{
    padding: 0;
    text-align: left;
}
.summary .col-10
{
    padding: 0;
}

/* 
.back-to-shop{
    margin-top: 4.5rem;
}

 */


.btn{
    background-color: #000;
    border-color: #000;
    color: white;
    width: 100%;
    font-size: 0.7rem;
    margin-top: 4vh;
    padding: 1vh;
    border-radius: 0;
}
.btn:focus{
    box-shadow: none;
    outline: none;
    box-shadow: none;
    color: white;
    -webkit-box-shadow: none;
   
    transition: none; 
}
.btn:hover{
    color: white;
}
a{
    color: black; 
}
a:hover{
    color: black;
    text-decoration: none;
}

</style>