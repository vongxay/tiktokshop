<div class="container" style="margin-top: 10px; margin-bottom: 100%">
    <div class="form-box">
        <img src="img/logo/logo02.png" alt="Logo" class="logo">
        <?php

        if (isset($_POST['login-btn'])) { // ກວດສອບວ່າໄດ້ມີການກົດປຸ່ມ ເຂົ້າລະບົບແລ້ວບໍ

            $username = htmlentities($_POST['username']);
            $password = htmlentities($_POST['password']);

            $db = connect();
            $stmt = $db->prepare("SELECT * FROM tb_customer WHERE username = :username AND password = :password");
            $stmt->bindParam("username", $username);
            $password = sha1($password);
            $stmt->bindParam("password", $password);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $_SESSION['loggedIn'] = $result[0]['username'] . $result[0]['password'];
                $_SESSION['loggedId'] = $result[0]['id'];
                $_SESSION['username'] = $result[0]['username'];

                // อัปเดต created_log
                $stmt = $db->prepare("UPDATE tb_customer SET created_log = NOW() WHERE id = :id");
                $stmt->bindParam(":id", $_SESSION['loggedId']);
                
                if ($stmt->execute()) {
                    echo '<script>
                        Swal.fire({
                       
                            text: "'.$T['alert_login_success'].'",
                            icon: "success",
                            showConfirmButton: false, // ซ่อนปุ่ม
                            timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                        });
                    </script>';
                    echo '
                        <script type="text/javascript">
                            setTimeout(function() {
                                location.href = "?page=profiles";
                            }, 1000);
                        </script>
                    ';
                    $profile = getCustomerBy($_SESSION['loggedId']);
                    $id = $profile['id'];
                    $db = connect();
                    $stmt = $db->query("SELECT COUNT(statust) as statust FROM tb_order_detail WHERE statust = 0 AND user_product_id = $id");
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $show) {
                        $_SESSION['alert'] = $show['statust'];
                    }
                } else {
                    echo '<script>
                    Swal.fire({
                 
                        text: "'.$T['alert_login_update_fail'].'",
                        icon: "error",
                        showConfirmButton: false, // ซ่อนปุ่ม
                        timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                    });
                </script>';
                }
            } else {
                echo '<script>
                Swal.fire({
                 
                    text: "'.$T['alert_login_fail'].'",
                    icon: "error",
                    showConfirmButton: false, // ซ่อนปุ่ม
                    timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                });
            </script>';
            }
        }
        ?>
        <h3 style="color: #ff2e63;"><?php echo $T['login_header']; ?></h3>
        <br>

        <form action="?page=login" method="POST">
            <div class="form-group">
                <input type="text" placeholder="<?php echo $T['username_placeholder']; ?>" name="username" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="<?php echo $T['password_placeholder']; ?>" name="password" required>
            </div>
            <button type="submit" class="btn-login" name="login-btn"><?php echo $T['login_btn']; ?></button>
        </form>
        <div class="links">
            <a href="?page=registor"><?php echo $T['link_register']; ?></a>
            <a href="?page=forgot"><?php echo $T['link_forgot']; ?></a>
        </div>
    </div>
</div>



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
</style>