<div class="container" style="margin-top: 10px; margin-bottom: 100%; border:none">
    <a href="?page=login" class="back" style="margin-left:-100%; font-weight:bolder; color:#555"><i class="bi bi-arrow-left"></i></a>
    <div class="form-box">

        <script>
            function validatePassword() {
                var password = document.getElementById("password").value;

                // ตรวจสอบว่ารหัสผ่านมีความยาวไม่น้อยกว่า 6 ตัว และเป็นตัวเลขทั้งหมด
                if (password.length < 6 || isNaN(password)) {
                    Swal.fire({
                        // ใช้ $T['js_pass_min_length']
                        text: "<?php echo $T['js_pass_min_length']; ?>",
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
        if (isset($_POST['confirm'])) {

            $password = htmlentities($_POST['password']);
            $cpassword = htmlentities($_POST['password_confirmation']);
            $id = $_SESSION['id'];
            if ($password != $cpassword) {
                // ใช้ $T['pass_mismatch_alert']
                echo '<script>
                                Swal.fire({
                                    text: "'.$T['pass_mismatch_alert'].'",
                                    icon: "error",
                                    showConfirmButton: false, // ซ่อนปุ่ม
                                    timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                                });
                            </script>';
            } else {
                $db = connect();
                $stmt = $db->prepare("UPDATE tb_customer SET password = :password WHERE id = :id");
                $password = sha1($password);
                $stmt->bindParam("password", $password);
                $stmt->bindParam("id", $id);
                if ($stmt->execute()) {
                    // ใช้ $T['reset_success']
                    echo '<script>
                Swal.fire({
                    text: "'.$T['reset_success'].'",
                    icon: "success",
                    showConfirmButton: false, // ซ่อนปุ่ม
                    timer: 3000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                });
            </script>';
                    echo '
            <script type="text/javascript">
                setTimeout(function() {
                    location.href = "?page=login";
                }, 3000);
            </script>
        ';
                }
            }
        }
        if (isset($_POST['check'])) {
            $mobile = $_POST['mobile'];
            // ตรวจสอบอีเมลในฐานข้อมูล
            $db = connect();
            $stmt = $db->prepare("SELECT * FROM tb_customer WHERE mobile = :mobile");
            $stmt->bindParam(':mobile', $mobile);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $customer_id = $user['id'];
                $img_name = $user['img_name'];
                $_SESSION['id'] = $customer_id;
                // echo $customer_id;
                if (!empty($img_name)) {
                    // echo $_SESSION['id'];


        ?>
                    <img src="uploads/profile/<?php echo $img_name; ?>" alt="Logo" class="profile-logo">
                    <p style="color:#555; margin-top: -10px; font-weight:bolder"><?php echo $user['username']; ?></p>
                <?php } else { ?>
                    <img src="img/logo/logo02.png" alt="Logo" class="logo">
                <?php } ?>
                <h3 style="color: #ff2e63;"><?php echo $T['reset_title']; ?></h3>
                <br>

                <form action="?page=forgot" method="POST" onsubmit="return validatePassword()">
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="<?php echo $T['new_pass_placeholder']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="<?php echo $T['confirm_pass_placeholder']; ?>" required>
                    </div>
                    <button type="submit" name="confirm" class="btn-login"><?php echo $T['change_pass_btn']; ?></button>
                </form>
            <?php
            } else {
                // ใช้ $T['mobile_not_found']
                echo '<script>
                    Swal.fire({
                    
                        text: "'.$T['mobile_not_found'].'",
                        icon: "error",
                        showConfirmButton: false, // ซ่อนปุ่ม
                        timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                    });
                </script>';
            ?>
                <img src="img/logo/logo02.png" alt="Logo" class="logo">
                <h3 style="color: #ff2e63;"><?php echo $T['forgot_title']; ?></h3>
                <br>

                <form action="?page=forgot" method="POST">

                    <div class="input-container">
                        <input type="tel" id="phone" name="mobile" list="countryCodes" id="phoneCode" placeholder="<?php echo $T['mobile_placeholder']; ?>" required>
                        <datalist id="countryCodes">
                            </datalist>
                    </div>
                    <button type="submit" name="check" class="btn-login"><?php echo $T['confirm_btn']; ?></button>
                </form>
            <?php
            }
        } else {
            ?>
            <img src="img/logo/logo02.png" alt="Logo" class="logo">
            <h3 style="color: #ff2e63;"><?php echo $T['forgot_title']; ?></h3>
            <br>

            <form action="?page=forgot" method="POST">

                <div class="input-container">
                    <input type="tel" id="phone" name="mobile" list="countryCodes" id="phoneCode" placeholder="<?php echo $T['mobile_placeholder']; ?>" required>
                    <datalist id="countryCodes">
                        </datalist>
                </div>
                <button type="submit" name="check" class="btn-login"><?php echo $T['confirm_btn']; ?></button>
            </form>
        <?php } ?>

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

        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-align: center
    }

    .logo {
        width: 80px;
        height: auto;
        margin-bottom: 20px;
        border-radius: 50%;
    }

    .tab {
        display: flex;
        justify-content: center;
        gap: 20px;
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
    }

    .input-select,
    form input {
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

    .btn-login {
        background-color: #ff2e63;
        color: #fff;
    }

    .links {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        font-size: 0.9em;
    }

    .links a {
        color: #ff2e63;
        text-decoration: none;
        cursor: pointer;
    }

    .links a:hover {
        text-decoration: underline;
    }

    .profile-logo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 10px;
    }
</style>