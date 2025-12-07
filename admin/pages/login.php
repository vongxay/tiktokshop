<style>
    .bd-placeholder-img {

        font-size: 1.125rem;

        text-anchor: middle;

        -webkit-user-select: none;

        -moz-user-select: none;

        user-select: none;

    }



    @media (min-width: 768px) {

        .bd-placeholder-img-lg {

            font-size: 3.5rem;

        }

    }



    html,

    body {

        height: 100%;

    }





    .form-signin {

        width: 100%;

        max-width: 330px;

        padding: 15px;

        margin: auto;

    }



    .form-signin .checkbox {

        font-weight: 400;

    }



    .form-signin .form-floating:focus-within {

        z-index: 2;

    }



    .form-signin input[type="email"] {

        margin-bottom: -1px;

        border-bottom-right-radius: 0;

        border-bottom-left-radius: 0;

    }



    .form-signin input[type="password"] {

        margin-bottom: 10px;

        border-top-left-radius: 0;

        border-top-right-radius: 0;

    }
</style>

<br>

<?php

if (isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ

    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ

    echo '<script> location.replace("index.php"); </script>';
}

?>

<body class="text-center">

    <center>

        <main class="form-signin mb-4">



            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-bounding-box" viewBox="0 0 16 16">

                <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z" />

                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />

            </svg>

            <?php



            if (isset($_POST['login-btn'])) { // ກວດສອບວ່າໄດ້ມີການກົດປຸ່ມ ເຂົ້າລະບົບແລ້ວບໍ



                $username = htmlentities($_POST['username']);

                $password = htmlentities($_POST['password']);

                $db = connect();

                $stmt = $db->prepare("SELECT * FROM tb_customer WHERE username = :username AND password = :password ");

                $stmt->bindParam("username", $username);

                $password = sha1($password);

                $stmt->bindParam("password", $password);

                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($result) > 0) {

                    $_SESSION['admin_loggedIn'] = $result[0]['username'] . $result[0]['password'];

                    $_SESSION['admin_loggedId'] = $result[0]['id'];

                    $_SESSION['admin_username'] = $result[0]['username'];

                    echo '<div class="alert alert-success">เสร็จแล้ว.... </div>';

                    echo '

							<script type="text/javascript">

								setTimeout(function() {

									location.href = "index.php";

								}, 1000);

							</script>

						';
                    echo " <script>
                        // ส่งคำขอไปยัง notify.php เมื่อหน้าเว็บถูกเปิด
                        fetch('?page=notify', {
                            method: 'POST',
                        })
                        .then(response => response.text())
                        .then(data => {
                            console.log('แจ้งเตือนสำเร็จ:', data);
                        })
                        .catch((error) => {
                            console.error('เกิดข้อผิดพลาด:', error);
                        });
                        </script> ";
                } else {

                    echo '<div class="alert alert-warning">ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง โปรดตรวจสอบและลองอีกครั้ง</div>';
                }
            } else {





            ?>

                <h1 class="h3 mb-3 fw-normal">กรอกข้อมูลเพื่อเข้าสู่ระบบ</h1>

            <?php

            }

            ?>

            <form action="?page=login" method="POST">

                <div class="form-floating">

                    <input type="text" class="form-control" id="floatingInput" name="username" placeholder="username" required>

                    <label for="floatingInput">ชื่อผู้ใช้</label>

                </div>

                <br>

                <div class="form-floating">

                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>

                    <label for="floatingPassword">รหัสผ่าน</label>

                </div>





                <br>

                <button class="w-100 btn btn-lg btn-primary" type="submit" name="login-btn">เข้าสู่ระบบ</button>

                <br>

                <!-- <a href="?page=register">สมัครใหม่</a> -->

                <p class="mt-5 mb-3 text-muted">&copy; 2023–<?php

                                                            $month = date("d.m.Y");

                                                            $last_day_this_month  = date('Y-' . $month . '-t');

                                                            echo  $month;

                                                            ?></p>

            </form>



        </main>



    </center>



</body>



</html>