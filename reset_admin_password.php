<?php
/**
 * สคริปต์ Reset รหัสผ่าน Admin
 * วิธีใช้: เปิดไฟล์นี้ผ่าน browser เช่น http://localhost/reset_admin_password.php
 * หลังใช้งานเสร็จให้ลบไฟล์นี้ทิ้งเพื่อความปลอดภัย!
 */

include_once('functions/config.php');

echo "<h1>Reset Admin Password</h1>";

try {
    $db = connect();
    
    // Reset password ของ Admin (id: 17) เป็น 123456
    $new_password = sha1('123456');
    $admin_id = 17;
    
    $stmt = $db->prepare("UPDATE tb_customer SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $new_password);
    $stmt->bindParam(':id', $admin_id);
    
    if ($stmt->execute()) {
        echo "<div style='color: green; font-size: 18px;'>";
        echo "✅ Reset รหัสผ่านสำเร็จ!<br><br>";
        echo "<strong>ข้อมูลเข้าสู่ระบบ:</strong><br>";
        echo "- URL: <a href='admin/index.php?page=login'>admin/index.php?page=login</a><br>";
        echo "- Username: <strong>Admin</strong><br>";
        echo "- Password: <strong>123456</strong><br>";
        echo "</div>";
    } else {
        echo "<div style='color: red;'>❌ Reset ไม่สำเร็จ</div>";
    }
    
    // แสดงรายชื่อ admin ทั้งหมดที่มี vip_id = 5 (ระดับสูงสุด)
    echo "<h2>บัญชีระดับ VIP 5 (สิทธิสูงสุด)</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Store</th><th>VIP</th><th>Password (ที่ใช้ได้)</th></tr>";
    
    $stmt = $db->query("SELECT id, username, store, vip_id, password FROM tb_customer WHERE vip_id = '5' ORDER BY id");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $sha1_123456 = '7c4a8d09ca3762af61e59520943dc26494f8941b';
    $sha1_123456789 = '3acd0be86de7dcccdbf91b20f94a68cea535922d';
    
    foreach ($results as $row) {
        $known_pass = '-';
        if ($row['password'] == $sha1_123456) {
            $known_pass = '123456';
        } elseif ($row['password'] == $sha1_123456789) {
            $known_pass = '123456789';
        } elseif ($row['id'] == 17) {
            $known_pass = '123456 (reset แล้ว)';
        }
        
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><strong>{$row['username']}</strong></td>";
        echo "<td>{$row['store']}</td>";
        echo "<td>{$row['vip_id']}</td>";
        echo "<td style='color: blue;'>{$known_pass}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><div style='color: red; font-weight: bold;'>";
    echo "⚠️ สำคัญ: กรุณาลบไฟล์นี้หลังใช้งานเสร็จ!";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
}
?>
