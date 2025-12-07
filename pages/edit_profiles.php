<?php
// ต้องมั่นใจว่าไฟล์ SweetAlert2 ถูกรวมไว้ใน Header ของคุณแล้ว

if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
}
// สมมติว่าฟังก์ชัน getCustomerBy และ connect ถูกกำหนดไว้แล้ว
$profile = getCustomerBy($_SESSION['loggedId']);
$id = $profile['id'];

function compress_image($source_url, $destination_url, $quality)
{
    // ... (โค้ดฟังก์ชัน compress_image เดิม)
    $info = getimagesize($source_url);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source_url);
        imagejpeg($image, $destination_url, $quality);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source_url);
        imagegif($image, $destination_url);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source_url);
        // ค่าการบีบอัดของ PNG อยู่ที่ 0 (ไม่มีการบีบอัด) ถึง 9 (บีบอัดสูงสุด)
        $compression = floor((100 - $quality) / 10); 
        imagepng($image, $destination_url, $compression);
    } else {
        return false; // ถ้าไฟล์ไม่รองรับ ให้ return false
    }

    if (isset($image)) {
        imagedestroy($image); // คืนหน่วยความจำ
    }
    return $destination_url;
}

if (isset($_POST['edit-product-btn'])) {

    // ตรวจสอบว่ามีข้อมูลเบอร์มือถือส่งมาหรือไม่ (ตามโค้ดเดิม)
    if (!empty($_POST['mobile'])) {
        // ใช้ htmlentities เพื่อความปลอดภัย
        $mobile = htmlentities($_POST['mobile']);
        $address = htmlentities($_POST['address']);
        $province = htmlentities($_POST['province']); // จังหวัด
        $distric = htmlentities($_POST['distric']);   // เขต/อำเภอ
        $file_name = $_FILES['img_name']['name'] ?? ''; // ใช้ ?? เพื่อจัดการกรณีไม่มีไฟล์

        $db = connect(); // เชื่อมต่อฐานข้อมูล

        if (!empty($file_name)) {
            // โหมดอัปโหลดพร้อมรูปภาพ
            
            // ตั้งชื่อไฟล์แบบสุ่มเพื่อป้องกันชื่อซ้ำ
            $file_name = uniqid() . '-' . strtolower(str_replace([' ', '(', ')'], '-', $file_name));
            $uploadPath = './uploads/profile/' . $file_name;
        
            // บีบอัดรูปภาพและบันทึก
            if (compress_image($_FILES['img_name']['tmp_name'], $uploadPath, 80)) {
                // อัปเดตข้อมูลในฐานข้อมูล
                $stmt = $db->prepare("UPDATE tb_customer SET 
                    mobile = :mobile,
                    address = :address,
                    distric = :distric,
                    province = :province,
                    img_name = :img_name
                    WHERE id = :id");
                
                $stmt->bindParam(":mobile", $mobile);
                $stmt->bindParam(":address", $address);
                $stmt->bindParam(":distric", $distric);
                $stmt->bindParam(":province", $province);
                $stmt->bindParam(":img_name", $file_name);
                $stmt->bindParam(":id", $id);
        
                if ($stmt->execute()) {
                    echo '<script>
                        Swal.fire({
                         
                            text: "'.$T['update_success_img'].'",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = "?page=profiles";
                        });
                    </script>';
                }
            } else {
                // ใช้ $T['update_file_error']
                echo '<script>alert("'.$T['update_file_error'].'");</script>';
            }
        }else {
            // โหมดอัปเดตข้อมูลโดยไม่มีการเปลี่ยนรูปภาพ
            $stmt = $db->prepare("UPDATE tb_customer SET 
                mobile = :mobile, 
                address = :address, 
                distric = :distric, 
                province = :province 
                WHERE id = :id");
                
            $stmt->bindParam(":mobile", $mobile); // แก้ไขการใช้ bindParam
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":distric", $distric);
            $stmt->bindParam(":province", $province);
            $stmt->bindParam(":id", $id);
            
            if ($stmt->execute()) {
                echo '<script>
                    Swal.fire({
                     
                        text: "'.$T['update_success_no_img'].'",
                        icon: "success",
                        showConfirmButton: false, 
                        timer: 1500
                    }).then(() => {
                        window.location.href = "?page=profiles";
                    });
                </script>';
            } else {
                // ใช้ $T['update_save_error']
                echo '<script>alert("'.$T['update_save_error'].'");</script>';
            }
        }
    }
}
?>

<div class="profile-container" style="margin-top:65px; margin-bottom:65px">
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const profileImg = document.getElementById('profileImg');
                profileImg.src = reader.result; // แสดงภาพใหม่
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <form action="?page=edit_profiles" method="POST" role="form" class="form-horizontal form-label-left" enctype="multipart/form-data">

        <a href="?page=profiles" class="back" style="margin-left:10px; font-weight:bolder; color:#555; margin-top:5px"><i class="bi bi-arrow-left"></i></a>
        <div class="profile-header">
            <?php
            // ตรวจสอบรูปภาพโปรไฟล์เพื่อแสดงผล
            $img_source = (!empty($profile['img_name'])) ? './uploads/profile/' . $profile['img_name'] : 'img/logo/logo02.png';
            ?>
            <label for="upload" class="upload-btn">
                <img src="<?php echo $img_source; ?>" alt="Logo" class="profile-logo" id="profileImg">
                <input type="file" id="upload" name="img_name" accept="image/png, image/jpeg, image/gif" onchange="previewImage(event)">
            </label>
            <p class="id" style="font-size:16px; margin-bottom:-7px; font-weight:bolder"><?php echo $profile['customer_id']; ?></p>
            <p class="username">@<?php echo $profile['username']; ?> </p>
        </div>

        <div class="form-group">
            <input type="tel" id="phoneCode" name="mobile" list="countryCodes" value="<?php echo $profile['mobile']; ?>" placeholder="<?php echo $T['input_mobile_placeholder']; ?>">
            <datalist id="countryCodes">
                </datalist>
        </div>

        <div class="form-group">
            <label for="username_readonly"><?php echo $T['input_username_label']; ?></label>
            <input type="text" id="username_readonly" value="<?php echo $profile['username']; ?>" placeholder="<?php echo $T['input_username_placeholder']; ?>" readonly>
        </div>
        
        <div class="form-group">
            <label for="address_input"><?php echo $T['input_address_label']; ?></label>
            <input type="text" id="address_input" name="address" value="<?php echo $profile['address']; ?>" placeholder="<?php echo $T['input_address_placeholder']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="distric_input"><?php echo $T['input_distric_label']; ?></label>
            <input type="text" id="distric_input" name="distric" value="<?php echo $profile['distric']; ?>" placeholder="<?php echo $T['input_distric_placeholder']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="province_input"><?php echo $T['input_province_label']; ?></label>
            <input type="text" id="province_input" name="province" value="<?php echo $profile['province']; ?>" placeholder="<?php echo $T['input_province_placeholder']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="created_log_input"><?php echo $T['input_last_login_label']; ?></label>
            <input type="text" id="created_log_input" value="<?php echo $profile['created_log']; ?>" placeholder="<?php echo $T['input_last_login_placeholder']; ?>" readonly>
        </div>
        
        <button type="submit" name="edit-product-btn" class="btn-register"><?php echo $T['save_changes_btn']; ?></button>
    </form>
</div>
<script>
    // ... (โค้ด JavaScript สำหรับ datalist เดิม) ...
    // รายชื่อประเทศและรหัสโทรศัพท์
    const countryPhoneCodes = [{
            name: "Thailand",
            code: "+66"
        },
        {
            name: "United States",
            code: "+1"
        },
        // ... (ตัวเลือกอื่น ๆ) ...
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
    /* ... (โค้ด CSS เดิมของคุณ) ... */
    input[type="file"] {
        display: none;
        /* ซ่อนปุ่มอัปโหลด */
    }

    .profile-container {
        width: 100%;

    }

    .profile-header {
        text-align: center;
        padding: 20px;

    }

    .profile-logo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .username {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    form {
        width: 100%;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
        margin: 10px;
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
        outline: none;
        transition: border-color 0.3s;
    }

    input[type="tel"] {
        width: 100%;
        padding: 10px 0;
        font-size: 16px;
        color: #333;
        border: none;
        outline: none;
        transition: border-color 0.3s;
    }

    input[type="text"]:focus {
        /* border-bottom-color: #ff2e63; */
        border-bottom: 2px solid #ccc;
    }

    input[type="tel"]:focus {
        /* border-bottom-color: #ff2e63; */
        border-bottom: 2px solid #ccc;
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
</style>