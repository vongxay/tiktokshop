<?php
if (!isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ
    echo '<script> location.replace("?page=login"); </script>';
}
?>
<br>
<br>

<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">

                <script>
                    function validatePassword() {
                        var password = document.getElementById("password").value;

                        // ตรวจสอบว่ารหัสผ่านมีความยาวไม่น้อยกว่า 6 ตัว และเป็นตัวเลขทั้งหมด
                        if (password.length < 6 || isNaN(password)) {
                            Swal.fire({

                                text: "รหัสผ่านอย่างน้อย 6 ตัวเลษ!",
                                icon: "error",
                                showConfirmButton: false, // ซ่อนปุ่ม
                                timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                            });
                            return false;
                        } else {
                            return true;
                        }
                    }
                </script>
                <?php

                $db = connect();
                if (isset($_POST['register-btn'])) {
                    $username = htmlentities($_POST['username']);
                    $mobile = htmlentities($_POST['mobile']);
                    $statust_log = 1;
                    $password = htmlentities($_POST['password']);
                    $cpassword = htmlentities($_POST['password_confirmation']);

                    if ($password != $cpassword) {
                        // echo '<div class="alert alert-danger">รหัสผ่านไม่ตรงกัน โปรดตรวจสอบและลองอีกครั้ง</div>';
                        echo '<script>
                    Swal.fire({
                  
                            text: "รหัสผ่านไม่ตรงกัน โปรดตรวจสอบและลองอีกครั้ง!",
                            icon: "error",
                            showConfirmButton: false, // ซ่อนปุ่ม
                            timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                        });
                    </script>';
                    } else {
                        if (!empty($password) && !empty($username) && !empty($mobile)) {
                            // ກວດສອບຊື່ບັນຊີກ່ອນລົງທະບຽນ
                            $stmt = $db->prepare("SELECT COUNT(*) as count FROM tb_customer WHERE username = :username");
                            $stmt->bindParam("username", $username);
                            $stmt->execute();
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ((int)$result['count'] > 0) {

                                echo '<script>
                                        Swal.fire({
                                    
                                            text: "ชื่อบัญชีนี้ถูกใช้งานแล้ว โปรดเลือกชื่ออื่น!",
                                            icon: "error",
                                            showConfirmButton: false, // ซ่อนปุ่ม
                                            timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                                        });
                                    </script>';
                            } else {

                                $stmt = $db->prepare("INSERT INTO tb_customer (customer_id, username, password,  mobile, statust_log, created) VALUES (:customer_id, :username, :password, :mobile, :statust_log, NOW())");
                                $customer_id = generateCustom();
                                $stmt->bindParam("customer_id", $customer_id);
                                $stmt->bindParam("username", $username);
                                $password = sha1($password);
                                $stmt->bindParam("password", $password);
                                $stmt->bindParam("mobile", $mobile);
                                $stmt->bindParam("statust_log", $statust_log);

                                if ($stmt->execute()) {
                                    echo '<script>
                              Swal.fire({
                            
                                  text: "Registor success!",
                                  icon: "success",
                                  showConfirmButton: false, // ซ่อนปุ่ม
                                  timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                              });
                          </script>';
                                    echo '
                            ...</div>';
                                    $_SESSION['admin_username'] = $username;
                                    $_SESSION['admin_mobile'] = $mobile;
                                    $_SESSION['admin_username'] = $username . $password;
                                    $customer = getCustomerBy($username);
                                    $_SESSION['admin_loggedId']  = $customer['id'];
                                    echo '
                                    <script type="text/javascript">
                                        setTimeout(function() {
                                            location.href = "index.php";
                                        }, 1000);
                                    </script>
                                ';
                                }
                            }
                        } else {
                            echo '<div class="alert alert-danger"> 
                               โปรดป้อนข้อมูลทั้งหมดให้ครบแล้วลองอีกครั้ง</div>';
                        }
                    }
                }
                ?>

                <div class="container" style="margin-top: 10px; margin-bottom: 20px;">
                    <div class="form-container">
                        <img src="../img/logo/logo02.png" alt="Logo" class="logo">
                       
                        <form action="?page=register" method="POST" onsubmit="return validatePassword()">
                            <div class="form-group">
                                <!-- <label for="phoneCode">Select Country Code:</label> -->
                                <input type="tel" id="phone" name="mobile" list="countryCodes" id="phoneCode" placeholder="เบอร์มือถือ">
                                <datalist id="countryCodes">
                                    <!-- Options จะถูกเพิ่มที่นี่ -->
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label for="name">*ชื่อ</label>
                                <input type="text" id="name" name="username" placeholder="ชื่อ" required>
                            </div>
                            <div class="form-group">
                                <label for="password">*รหัสผ่าน</label>
                                <input type="password" id="password" name="password" placeholder="รหัสผ่าน" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm-password">*ยืนยัน</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="ยืนยัน" required>
                            </div>     
                            <button type="submit" name="register-btn" class="btn-register">ลงทะเบียน</button>
                            <a href="?page=login" style="margin-top: 10px;" class="btn btn-login">เข้าสู่ระบบ</a>
                        </form>
                        <p class="terms">✔ ยืนยัน "ข้อตกลง"</p>
                    </div>
                </div>
                <script>
                    // รายชื่อประเทศและรหัสโทรศัพท์
                    const countryPhoneCodes = [{
                            name: "Thailand",
                            code: "+66"
                        },
                        {
                            name: "United States",
                            code: "+1"
                        },
                        {
                            name: "United Kingdom",
                            code: "+44"
                        },
                        {
                            name: "Canada",
                            code: "+1"
                        },
                        {
                            name: "Australia",
                            code: "+61"
                        },
                        {
                            name: "India",
                            code: "+91"
                        },
                        {
                            name: "Lao People",
                            code: "+856"
                        },
                        {
                            name: "Japan",
                            code: "+81"
                        },
                        {
                            name: "South Korea",
                            code: "+82"
                        },
                        {
                            name: "Germany",
                            code: "+49"
                        },
                        {
                            name: "France",
                            code: "+33"
                        }
                    ];

                    // ดึง datalist
                    const dataList = document.getElementById("countryCodes");

                    // เพิ่มตัวเลือกใน datalist
                    countryPhoneCodes.forEach(country => {
                        const option = document.createElement("option");
                        option.value = `${country.name} (${country.code})`;
                        dataList.appendChild(option);
                    });
                </script>

                

                <style>
                    .container {
                        width: 100%;
                        max-width: 400px;
                        background: #fff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 20px;
                        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                        text-align: center;
                    }

                    .logo {
                        width: 80px;
                        height: auto;
                        margin-bottom: 20px;
                        border-radius: 50%;
                    }

                    .tab {
                        display: flex;
                        justify-content: space-around;
                        margin-bottom: 20px;
                    }

                    .tab span {
                        font-size: 1em;
                        color: #888;
                        cursor: pointer;
                    }

                    .tab .active {
                        color: #ff2e63;
                        font-weight: bold;
                        border-bottom: 2px solid #ff2e63;
                    }

                    form {
                        width: 100%;
                    }

                    .form-group {
                        margin-bottom: 15px;
                        text-align: left;
                    }

                    .form-group label {
                        display: block;
                        font-size: 0.9em;
                        margin-bottom: 5px;
                        color: #555;
                    }

                    .form-group input {
                        width: 100%;
                        padding: 10px;
                        font-size: 1em;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        box-sizing: border-box;
                    }

                    button {
                        width: 100%;
                        padding: 10px;
                        font-size: 1em;
                        margin: 10px 0;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                    }

                    .btn-register {
                        background-color: #ff2e63;
                        color: #fff;
                    }

                    .btn-login {
                        background-color: #ddd;
                        color: #555;
                        width: 100%;
                    }

                    .terms {
                        font-size: 0.8em;
                        color: #888;
                        margin-top: 15px;
                    }
                </style>

            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="card shadow-lg p-3 mb-3 bg-body rounded">
            <div class="card-body">
                <h5 class="card-title">การเข้าถึงระบบจำเป็นต้องมีบัญชีผู้ใช้ก่อน</h5>
                <p class="card-text">
                    <li>User ที่มีสิทธิ</li>
                </p>
                <div class="table-responsive">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th scope="col">NO</th>
                                <th scope="col">username</th>
                                <th scope="col">store</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $conn = connect();
                            $smt = $conn->query("SELECT * FROM tb_customer");
                            $result = $smt->fetchAll(PDO::FETCH_ASSOC);
                            $i = 0;
                            foreach ($result as $row) {
                                $i++;
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $i; ?></th>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['store']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <center><a href="?page=home">หน้าหลัก</a></center>
    </div>
</div>

<hr>