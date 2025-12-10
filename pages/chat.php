<?php
if (!isset($_SESSION['username'])) { 
    echo '<script> location.replace("?page=login"); </script>';
    exit;
}

$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$_SESSION['user_id'] = $customer_id; // ตั้งค่า user_id ชั่วคราวสำหรับตัวอย่าง
$receiver_id = $_GET['user'];
?>

<div class="chat-container">
    <div class="chat-messages">
        <div class="messages" id="messages"></div>

    </div>

    <footer class="chat-footer">
    <label for="fileInput" class="custom-file-upload">
    <i class="bi bi-image-alt"></i>
        </label>
        <input type="file" id="fileInput" />
        <input type="text" id="messageInput" placeholder="<?php echo $T['chat_placeholder']; ?>" class="chat-input">
        <button class="send-button" onclick="sendMessage()"><i class="bi bi-send-fill"></i></button>
    </footer>
</div>

<script>
    const userId = <?php echo $_SESSION['user_id']; ?>;
    const messagesContainer = document.getElementById('messages');
    
    // ✅ นำข้อความแปลมาใช้ใน JS
    const chatNoPartnerText = '<?php echo $T['chat_no_partner']; ?>';
    const chatAlertEmptyText = '<?php echo $T['chat_alert_empty']; ?>';
    const chatAlertNoReceiverText = '<?php echo $T['chat_alert_no_receiver']; ?>';
    const chatAlertFileType = '<?php echo $T['chat_alert_file_type']; ?>';


    // ✅ ดึง user id จาก URL
    const urlParams = new URLSearchParams(window.location.search);
    const chatPartnerId = urlParams.get('user');

    function loadMessages() {
        if (!chatPartnerId) {
            // ใช้ตัวแปรแปล
            messagesContainer.innerHTML = `<p style="text-align:center;color:#999;">${chatNoPartnerText}</p>`;
            return;
        }
        // ... (โค้ด loadMessages ที่เหลือเหมือนเดิม)
        
        const isScrolledToBottom =
            messagesContainer.scrollHeight - messagesContainer.scrollTop === messagesContainer.clientHeight;

        fetch(`get_messages.php?user=${chatPartnerId}`)
            .then(response => response.json())
            .then(data => {
                const currentScrollHeight = messagesContainer.scrollHeight;
                messagesContainer.innerHTML = '';

                data.forEach(msg => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message ' + (msg.sender_id == userId ? 'me' : 'other');

                    // แสดงไอคอนคนถ้าไม่มีรูปโปรไฟล์
                    const hasAvatar = msg.img_name && msg.img_name.trim() !== '' && msg.img_name !== 'null';
                    const avatarHTML = hasAvatar 
                        ? `<img src="uploads/profile/${msg.img_name}" alt="" class="chat-avatar" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"><div class="chat-avatar-icon" style="display:none"><i class="bi bi-person-fill"></i></div>` 
                        : `<div class="chat-avatar-icon"><i class="bi bi-person-fill"></i></div>`;

                    const profileHTML = `
                        <div class="chat-header">
                            ${avatarHTML}
                            <span class="chat-username">${msg.username || 'User'}</span>
                        </div>`;

                    let messageContent = '';
                    if (msg.type === 'text') {
                        messageContent = `<div class="chat-text">${msg.message}</div>`;
                    } else if (msg.type === 'file') {
                        messageContent = `
                            <div class="img-chat">
                                <a href="uploads/chat/${msg.file_name}" target="_blank">
                                    <img src="uploads/chat/${msg.file_name}" class="img" alt="">
                                </a>
                                <p>${msg.message}</p>
                            </div>`;
                    }

                    // แสดงติ๊กสำหรับข้อความที่เราส่ง (สีฟ้าถ้าอ่านแล้ว)
                    let readStatusHTML = '';
                    if (msg.sender_id == userId) {
                        if (msg.status == 1) {
                            // อ่านแล้ว - ติ๊กสีฟ้า
                            readStatusHTML = `<span class="read-status read"><i class="bi bi-check2-all"></i></span>`;
                        } else {
                            // ยังไม่อ่าน - ติ๊กเทา
                            readStatusHTML = `<span class="read-status unread"><i class="bi bi-check2"></i></span>`;
                        }
                    }

                    messageDiv.innerHTML = profileHTML + messageContent + readStatusHTML;
                    messagesContainer.appendChild(messageDiv);
                });

                if (isScrolledToBottom) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                } else {
                    messagesContainer.scrollTop += messagesContainer.scrollHeight - currentScrollHeight;
                }
            });
    }

    // ✅ โหลดทุก 2 วินาที
    setInterval(loadMessages, 2000);
    loadMessages();





    // โหลดข้อความครั้งแรกและตั้งค่ารีเฟรช
    function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const fileInput = document.getElementById('fileInput');

    const message = messageInput.value.trim();
    const file = fileInput.files[0];

    if (!message && !file) {
        // ใช้ตัวแปรแปล
        alert(chatAlertEmptyText);
        return;
    }

    // ดึง user id คู่สนทนาจาก URL
    const urlParams = new URLSearchParams(window.location.search);
    const chatPartnerId = parseInt(urlParams.get('user')); // ✅ ดึงจาก URL
    if (!chatPartnerId || isNaN(chatPartnerId)) {
        // ใช้ตัวแปรแปล
        alert(chatAlertNoReceiverText);
        return;
    }

    const formData = new FormData();
    if (message) formData.append('message', message);
    if (file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            // ใช้ตัวแปรแปล
            alert(chatAlertFileType);
            return;
        }
        formData.append('file', file);
    }

    // ✅ ส่ง user ไปใน query string
    fetch('send_message.php?user=' + chatPartnerId, {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        console.log('Raw Response:', data);
        try {
            const jsonData = JSON.parse(data);
            console.log('Parsed JSON:', jsonData);
        } catch (error) {
            console.error('JSON Parse Error:', error);
        }
    })
    .catch(err => console.error('Fetch error:', err))
    .then(() => {
        messageInput.value = ''; // ล้างช่องข้อความ
        fileInput.value = '';    // ล้างไฟล์
        loadMessages();          // โหลดข้อความใหม่
    });
}


    loadMessages();
    setInterval(loadMessages, 3000); // โหลดข้อความทุก 3 วินาที
</script>


<style>
    .chat-avatar {
    width: 36px;               /* ขนาดใหญ่ขึ้นเล็กน้อย */
    height: 36px;
    border-radius: 50%;        /* ทำให้เป็นวงกลม */
    object-fit: cover;         /* ครอบรูปให้พอดี */
    border: 2px solid #fff;    /* ขอบสีขาว (ช่วยให้เด่นบนพื้นเข้ม) */
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2); /* เงาเล็กน้อย */
    background: #f5f5f5;  animation: fadeIn 0.4s ease forwards;
    transition: transform 0.3s ease, background-color 0.3s ease;     /* สีพื้นหลัง ถ้ารูปไม่โหลด */
    
}

    .chat-container {
        display: flex;
        flex-direction: column;
        max-width: 500px;
        width: 100%;
        height: 800px;
        max-height: 900;
        margin: 0 auto;
        border: 1px solid #ddd;
        /* background: #fff; */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-top: 65px;
        animation: fadeIn 0.4s ease forwards;
    transition: transform 0.3s ease, background-color 0.3s ease;     /* สีพื้นหลัง ถ้ารูปไม่โหลด */
        
    }


    .message.received {
        justify-content: flex-start;
    }

    .message.sent {
        justify-content: flex-end;
    }

    .bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 14px;
        line-height: 1.5;
    }

    .message.received .bubble {
        background-color: #eaeaea;
        color: #333;
    }

    .message.sent .bubble {
        background-color: #ff2e63;
        color: #fff;
    }

    /* Footer */
    .chat-footer {
        display: flex;
        align-items: center;
        padding: 10px;
        border-top: 1px solid #ddd;
        background-color: #f7f7f7;
    }

    .chat-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        width: 50%;
    }

    .send-button {
        background-color: #ff2e63;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 10%;
        margin-left: 10px;
        cursor: pointer;
        width: auto;
    }

    .send-button:hover {
        background-color: #e06666;
    }

    .messages {
        margin-top: 50;
        overflow: hidden;
        min-height: 450px;
        max-height: 450px;
        overflow-y: scroll;
        /* padding: 15px; */
        border-bottom: none;
        background-color: whitesmoke;
        /* font-size: 18px; */
        max-width: 100%;
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 14px;
        line-height: 1.5;
        
    }

    .messages::-webkit-scrollbar {
        display: none;
        /* ซ่อน Scrollbar */
    }

    .message {
        overflow: hidden;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 8px;
        max-width: 70%;
        word-wrap: break-word;
        font-size: 16px;
        font-weight: bold;


    }

    .message.me {
        /* background: #e0f7fa; */
        text-align: left; /* กระจายข้อความให้เต็มบล็อก */
        margin-left: 150px;
        border-radius: 10px;
        color: black;
        font-style:inherit;
        font-weight: bolder;
        border-bottom: 1px solid #ff2e63; /* เส้นขีดใต้ */
    }

    .message.other {
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 14px;
        background-color: #eaeaea;
        color: #333;
        max-width: 50%;
        padding: 10px 15px;
        line-height: 1.5;
       

    }

    .file-upload-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .custom-file-upload {
        display: inline-block;
        /* background-color: #007bff; */
        /* color: ; */
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    /* .custom-file-upload:hover {
        background-color: #0056b3;
    }

    .custom-file-upload:active {
        background-color: #003f7f;
    } */

    #fileInput {
        display: none;
        /* ซ่อนอินพุตแบบดั้งเดิม */
    }

    .file-name {
        font-size: 14px;
        color: #555;
        font-style: italic;
    }

    .img {
        width: 100%;
        height: fit-content;
        background: none;
    }

    /* หัวข้อแชท (รูป + ชื่อ) */
    .chat-header {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .chat-username {
        font-weight: bold;
        font-size: 14px;
        margin-left: 8px;
    }

    /* ไอคอนคนแทนรูปโปรไฟล์ */
    .chat-avatar-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        min-height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex !important;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        border: 2px solid #fff;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }
    
    .chat-avatar-icon i {
        font-size: 18px;
        line-height: 1;
    }

    /* สถานะอ่านข้อความ */
    .read-status {
        display: block;
        text-align: right;
        font-size: 14px;
        margin-top: 4px;
    }

    .read-status.unread {
        color: #999;  /* สีเทา - ยังไม่อ่าน */
    }

    .read-status.read {
        color: #00a8ff;  /* สีฟ้า - อ่านแล้ว */
    }

    .read-status i {
        font-size: 16px;
    }

</style>
