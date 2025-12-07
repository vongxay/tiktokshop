<?php
if (isset($_SESSION['username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ
  // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ
  echo '<script> location.replace("index.php"); </script>';
}
?>
<script>
  function validatePassword() {
    var password = document.getElementById("password").value;

    // ตรวจสอบว่ารหัสผ่านมีความยาวไม่น้อยกว่า 6 ตัว และเป็นตัวเลขทั้งหมด
    if (password.length < 6 || isNaN(password)) {
      Swal.fire({
        // ใช้ $T['reg_js_pass_min']
        text: "<?php echo $T['reg_js_pass_min']; ?>",
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
  $password = htmlentities($_POST['password']);
  $cpassword = htmlentities($_POST['password_confirmation']);

  if ($password != $cpassword) {
    // ใช้ $T['reg_pass_mismatch_alert']
    echo '<script>
                    Swal.fire({
                  
                        text: "'.$T['reg_pass_mismatch_alert'].'",
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
        // ใช้ $T['reg_username_taken']
        echo '<script>
                            Swal.fire({
                          
                                text: "'.$T['reg_username_taken'].'",
                                icon: "error",
                                showConfirmButton: false, // ซ่อนปุ่ม
                                timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                            });
                        </script>';
      } else {

        $stmt = $db->prepare("INSERT INTO tb_customer (customer_id, username, password, mobile, created) VALUES (:customer_id, :username, :password, :mobile, NOW())");
        $customer_id = generateCustom();
        $stmt->bindParam("customer_id", $customer_id);
        $stmt->bindParam("username", $username);
        $password = sha1($password);
        $stmt->bindParam("password", $password);
        $stmt->bindParam("mobile", $mobile);

        if ($stmt->execute()) {
          // ใช้ $T['reg_success']
          echo '<script>
                              Swal.fire({
                            
                                  text: "'.$T['reg_success'].'",
                                  icon: "success",
                                  showConfirmButton: false, // ซ่อนปุ่ม
                                  timer: 2000 // ตั้งเวลาปิดอัตโนมัติ (มิลลิวินาที)
                              });
                          </script>';
          echo '
                            ...</div>';
          $_SESSION['username'] = $username;
          $_SESSION['mobile'] = $mobile;
          $_SESSION['username'] = $username . $password;
          $customer = getCustomerBy($username);
          $_SESSION['loggedId']  = $customer['id'];
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
      // ใช้ $T['reg_fill_all_fields']
      echo '<div class="alert alert-danger"> 
                    '.$T['reg_fill_all_fields'].'</div>';
    }
  }
}
?>

<div class="container" style="margin-top: 10px; margin-bottom: 20px;">
  <div class="form-container">
    <img src="img/logo/logo02.png" alt="Logo" class="logo">
    <div class="tab">
      <span class="active"><?php echo $T['reg_tab_mobile']; ?></span>
      <span><?php echo $T['reg_tab_email']; ?></span>
    </div>
    <form action="?page=registor" method="POST" onsubmit="return validatePassword()">
      
      <div class="input-container">
        <input type="tel" id="phone" name="mobile" list="countryCodes" id="phoneCode" placeholder="<?php echo $T['reg_mobile_placeholder']; ?>">
        <datalist id="countryCodes">
          </datalist>
      </div>
      <div class="form-group">
        <label for="name"><?php echo $T['reg_name_label']; ?></label>
        <input type="text" id="name" name="username" placeholder="<?php echo $T['reg_name_placeholder']; ?>" required>
      </div>
      <div class="form-group">
        <label for="password"><?php echo $T['reg_password_label']; ?></label>
        <input type="password" id="password" name="password" placeholder="<?php echo $T['reg_password_placeholder']; ?>" required>
      </div>
      <div class="form-group">
        <label for="confirm-password"><?php echo $T['reg_confirm_label']; ?></label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="<?php echo $T['reg_confirm_placeholder']; ?>" required>
      </div>
      
      <button type="submit" name="register-btn" class="btn-register"><?php echo $T['reg_btn']; ?></button>
      
      <a href="?page=login" style="margin-top: 10px;" class="btn btn-login"><?php echo $T['reg_link_login']; ?></a>
    </form>
    <p class="terms"><?php echo $T['reg_terms']; ?></p>
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

<script>
  function handleCredentialResponse(response) {
    // ส่ง token ไปให้ backend ตรวจสอบ
    fetch('google-login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          credential: response.credential
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            text: "เข้าสู่ระบบด้วย Google สำเร็จ!",
            icon: "success",
            showConfirmButton: false,
            timer: 1500
          });
          setTimeout(() => {
            window.location.href = "index.php";
          }, 1000);
        } else {
          Swal.fire({
            text: "เกิดข้อผิดพลาดในการเข้าสู่ระบบ",
            icon: "error",
            timer: 1500
          });
        }
      });
  }
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