<?php
if (!isset($_SESSION['username'])) {
  echo '<script> location.replace("?page=login"); </script>';
}
$profile = getCustomerBy($_SESSION['loggedId']);
$id = $profile['id'];

if (isset($_POST['market-add'])) {
  $db = connect();
  $store = htmlentities($_POST['store']);
  $fname = htmlentities($_POST['fname']);
  $lname = htmlentities($_POST['lname']);
  $stmt = $db->prepare("UPDATE tb_customer SET store = :store, fname = :fname, lname = :lname WHERE id = :id");
  $stmt->bindParam("store", $store);
  $stmt->bindParam("fname", $fname);
  $stmt->bindParam("lname", $lname);
  $stmt->bindParam("id", $id);

  if ($stmt->execute()) {
    echo '<script>
            Swal.fire({
       
                text: "'.$T['market_open_success'].'",
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
  }
}

?>
<div class="container" style="margin-top: 70px; margin-bottom: 20px;">
  <div class="form-container">
    <?php
    if (!empty($profile['img_name'])) {
    ?>
      <img src="uploads/profile/<?php echo $profile['img_name']; ?>" alt="Logo" class="profile-logo">
    <?php } else { ?>
      <img src="img/logo/logo02.png" alt="Logo" class="logo">
    <?php
    }
    ?>

    <div class="tab">
      <span class="active"><?php echo $T['market_join_title']; ?></span>
      </div>
    <form action="?page=market" method="POST">
      <div class="form-group">
        <label for="phone"><?php echo $T['market_mobile_label']; ?></label>
        <input type="tel" value="<?php echo $profile['mobile']; ?>" id="phone" placeholder="<?php echo $T['market_mobile_placeholder']; ?>" readonly>
      </div>
      <div class="form-group">
        <label for="name"><?php echo $T['market_store_label']; ?></label>
        <input type="text" name="store" value="<?php echo $profile['username']; ?>@store" id="name" placeholder="<?php echo $T['market_store_placeholder']; ?>" required>
      </div>
      <div class="form-group">
        <label for="password"><?php echo $T['market_fname_label']; ?></label>
        <input type="text" name="fname" value="<?php echo $profile['fname']; ?>" id="password" placeholder="<?php echo $T['market_fname_placeholder']; ?>" required>
      </div>
      <div class="form-group">
        <label for="confirm-password"><?php echo $T['market_lname_label']; ?></label>
        <input type="text" name="lname" value="<?php echo $profile['lname']; ?>" id="confirm-password" placeholder="<?php echo $T['market_lname_placeholder']; ?>" required>
      </div>

      <button type="submit" name="market-add" class="btn-register"><?php echo $T['market_open_btn']; ?></button>
    </form>
  </div>
</div>

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
    border: none;
    text-align: right;
  }

  input[type="text"] {
    width: 100%;
    padding: 10px 0;
    font-size: 16px;
    color: #333;
    border: none;
    border-bottom: 2px solid #ccc;
    outline: none;
    transition: border-color 0.3s;
  }

  input[type="tel"] {
    width: 100%;
    padding: 10px 0;
    font-size: 16px;
    color: #333;
    border: none;
    border-bottom: 2px solid #ccc;
    outline: none;
    transition: border-color 0.3s;
  }

  input[type="text"]:focus {
    border-bottom-color: #ff2e63;
  }

  input[type="tel"]:focus {
    border-bottom-color: #ff2e63;
  }

  .underline {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #ff2e63;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
  }

  input[type="text"]:focus~.underline {
    transform: scaleX(1);
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

  .profile-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 10px;
  }
</style>