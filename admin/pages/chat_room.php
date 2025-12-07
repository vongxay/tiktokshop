<?php

if (!isset($_SESSION['admin_username'])) { // ກວດສອບວ່າໄດ້ມີການເຊັດຄ່າ SESSION['username'] ແລ້ວ

    // ຖ້າມີ SESSION['username'] ແລ້ວໃຫ້ກັບໄປທີ່ໜ້າຫຼັກເລີຍ

    echo '<script> location.replace("?page=login"); </script>';
}

$profile = getCustomerBy($_SESSION['admin_loggedId']);

$customer_id = $profile['id'];

// ✅ ตรวจสอบสิทธิ์
if ($profile['statust_log'] != 1) {
    session_destroy(); // ลบ session
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
    exit;
}

?>

<div id="dataDisplay">กำลังดึงข้อมูล...</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ฟังก์ชันโหลดข้อมูล
        async function fetchData() {
            try {
                const response = await fetch("?page=tatable_online");
                if (!response.ok) throw new Error("Network response was not ok");
                
                const data = await response.text();
                document.getElementById("dataDisplay").innerHTML = data;
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        }

        // โหลดข้อมูลครั้งแรก
        fetchData();

        // โหลดข้อมูลใหม่ทุก 2 วินาที
        setInterval(fetchData, 2000);
    });
</script>

