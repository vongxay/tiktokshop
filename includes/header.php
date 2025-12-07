<?php
/**
 * Header - ต้องเรียก session_start() ก่อน output ใดๆ
 */
session_start();
error_reporting(0);

include_once('functions/init.php');
include_once('includes/lang.php'); 

// ตรวจสอบและเปลี่ยนภาษา
if (isset($_REQUEST['lang'])) {
    $selected_lang = strtolower($_REQUEST['lang']);
    if (isset($lang[$selected_lang])) {
        $_SESSION['lang'] = $selected_lang;
    }
}

// กำหนดภาษาปัจจุบัน
$current_lang = $_SESSION['lang'] ?? 'th';
if (!isset($lang[$current_lang])) {
    $current_lang = 'th';
}
$T = $lang[$current_lang];
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>tkshop</title>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#007BFF">
    <script>
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('service-worker.js')
          .then(reg => console.log("✅ Service Worker registered"))
          .catch(err => console.error("❌ SW failed:", err));
      }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="shortcut icon" href="img/logo/logo02.png" type="image/x-icon">

    <script src="https://accounts.google.com/gsi/client" async defer></script>

</head>

<body>

<div id="a2hs-prompt" style="display:none; color:black; position:fixed; bottom:20px; left:20px; right:20px; background:#fff; padding:15px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.2); z-index:9999;">
  
  <strong><?php echo $T['a2hs_title']; ?></strong><br>
  
  <?php echo $T['a2hs_desc']; ?>
  
  <br><br>
  
  <button id="btn-install" style="padding:8px 15px; background:#007BFF; color:white; border:none; border-radius:6px;"><?php echo $T['a2hs_btn']; ?></button>
</div>


<script>
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  // บล็อก default prompt แล้วเก็บไว้
  e.preventDefault();
  deferredPrompt = e;

  // แสดงปุ่มเราเอง
  document.getElementById('a2hs-prompt').style.display = 'block';

  document.getElementById('btn-install').addEventListener('click', () => {
    document.getElementById('a2hs-prompt').style.display = 'none';

    // แสดง prompt จริง
    deferredPrompt.prompt();

    deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        console.log('✅ ผู้ใช้ยอมรับ');
      } else {
        console.log('❌ ผู้ใช้ปฏิเสธ');
      }
      deferredPrompt = null;
    });
  });
});


if (window.matchMedia('(display-mode: standalone)').matches) {
  document.getElementById('a2hs-prompt').style.display = 'none';
}
</script>