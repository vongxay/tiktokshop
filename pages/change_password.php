<?php
if (!isset($_SESSION['username'])) { 
    echo '<script> location.replace("?page=login"); </script>';
    exit;
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];

?>

    <style>
        .form-box {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            background: #fff;
            margin-top: 100px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: none;
            background: teal;
            color: white;
            font-weight: bold;
        }
    </style>

    <div class="form-box">
        <h2><?php echo $T['change_pass_title']; ?></h2>
        
        <?php
            if (isset($_POST['changePassword'])) {
                $oldpassword = htmlentities($_POST['oldpassword']);
                $newpassword = htmlentities($_POST['newpassword']);
                $confirmnewpassword = htmlentities($_POST['confirmnewpassword']);
                
                if ($newpassword != $confirmnewpassword) {
                    // ใช้ $T['pass_mismatch_error']
                    echo '<div class="alert alert-danger">'.$T['pass_mismatch_error'].'</div>';
                } else {
                    $db = connect();
                    // (ควรมีการตรวจสอบรหัสผ่านเดิมก่อน แต่ในโค้ดเดิมไม่มี ผมจะไม่เพิ่มฟังก์ชันใหม่)
                    
                    $stmt = $db->prepare("UPDATE tb_customer SET password = :password WHERE id = :id");
                    $password = sha1($newpassword);
                    $id = $_SESSION['loggedId'];
                    $stmt->bindParam("password", $password);
                    $stmt->bindParam("id", $id);
                    if ($stmt->execute()) {
                        // ใช้ $T['change_pass_success']
                        echo '<div class="alert alert-success">'.$T['change_pass_success'].'</div>';
                        echo '
                        <script type="text/javascript">
                        setTimeout(function() {
                            location.href = "?page=logout";
                        }, 1000);
                        </script>
                ';
                    } else {
                        // ใช้ $T['change_pass_fail']
                        echo '<div class="alert alert-danger">'.$T['change_pass_fail'].'</div>';
                    }
                }
            }
            ?>
        <form method="POST">
            <input type="password" name="oldpassword" placeholder="<?php echo $T['pass_old_placeholder']; ?>" required>
            <input type="password" name="newpassword" placeholder="<?php echo $T['pass_new_placeholder']; ?>" required>
            <input type="password" name="confirmnewpassword" placeholder="<?php echo $T['pass_confirm_placeholder']; ?>" required>
            <button type="submit" name="changePassword"><?php echo $T['change_pass_btn']; ?></button>
        </form>
    </div>