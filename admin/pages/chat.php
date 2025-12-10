<?php
// ตรวจสอบสถานะการล็อกอินแอดมินและดึงข้อมูลโปรไฟล์
if (!isset($_SESSION['admin_loggedId'])) {
    // หากไม่ได้ล็อกอิน
    echo "<script>window.location='?page=login';</script>";
    exit;
}

$profile = getCustomerBy($_SESSION['admin_loggedId']);
$customer_id = $profile['id']; // ID ของแอดมิน
$_SESSION['user_id'] = $customer_id; // ใช้ user_id เป็น ID ผู้ส่ง

//   echo $customer_id;
$u_id = $_GET['id'] ?? null; // ID ของผู้ใช้งานที่กำลังแชทด้วย
if (empty($u_id)) {
    // หากไม่มี ID ผู้ใช้ที่แชทด้วย ให้กลับไปห้องรวมแชท
    echo "<script>window.location='?page=chat_room';</script>";
    exit;
}
$_SESSION['u_id'] = $u_id; // เก็บ ID ผู้ใช้งานที่กำลังแชทด้วยใน session

// ✅ ตรวจสอบสิทธิ์ (สถานะ 1 คือแอดมิน)
if ($profile['statust_log'] != 1) {
    session_destroy();
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');window.location='?page=logout';</script>";
    exit;
}
?>

<style>
    /* ใช้ CSS ที่ปรับปรุงแล้วด้านบนนี้ */
    html,
    body {
        overflow-y: scroll;
        /* ทำให้เลื่อนได้ */
    }

    body::-webkit-scrollbar {
        display: none;
        /* ซ่อน Scrollbar สำหรับเบราว์เซอร์ที่รองรับ Webkit */
    }

    .chat-container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        overflow: hidden;
    }

    .messages {
        overflow-y: scroll;
        height: 400px;
        padding: 15px;
        border-bottom: 1px solid #ddd;
        display: flex;
        flex-direction: column;
    }

    .message {
        display: inline-block;
        /* ให้ขนาดตามเนื้อหา */
        margin-bottom: 15px;
        padding: 10px 15px;
        border-radius: 25px;
        word-wrap: break-word;
        max-width: 90%;
        /* จำกัดไม่ให้เกิน container */
        position: relative;
        /* สำคัญมาก: เพื่อให้ปุ่มลบ position: absolute ได้ */
        padding-right: 35px;
        /* เพิ่มช่องว่างด้านขวาสำหรับปุ่มลบ */
    }

    .message.me {
        background: #e0f7fa;
        text-align: right;
        align-self: flex-end;
        /* จัดข้อความด้านขวา */
        padding-right: 35px;
        padding-left: 15px;
    }

    .message.other {
        background: #ddd;
        text-align: left;
        align-self: flex-start;
        /* จัดข้อความด้านซ้าย */
    }

    .input-container {
        display: flex;
        padding: 10px;
    }

    .input-container input[type="text"] {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .input-container button {
        padding: 10px 15px;
        margin-left: 10px;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .input-container button:hover {
        background: #0056b3;
    }

    .img {
        width: 150px;
        height: auto;
    }

    .custom-file {
        display: inline-block;
        background: rgb(205, 207, 209);
        color: #fff;
        padding: 8px 12px;
        border-radius: 2px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.2s;
    }

    .custom-file:hover {
        background: rgb(242, 243, 245);
    }

        /* --- สไตล์สำหรับปุ่มลบ --- */
    .delete-btn {
        position: absolute;
        bottom: 0%;
        transform: translateY(-50%);
        font-size: 14px;
        cursor: pointer;
        color: #ff4d4d;
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    /* ตำแหน่งปุ่มลบสำหรับข้อความของแอดมิน (ฝั่งขวา) */
    .message.me .delete-btn {
        right: 10px;
    }

    /* ตำแหน่งปุ่มลบสำหรับข้อความของคู่สนทนา (ฝั่งซ้าย) */
    .message.other .delete-btn {
        
        right: 10px; /* ล้างค่า right */
    }

    .message.me:hover .delete-btn,
    .message.other:hover .delete-btn { /* ให้แสดงชัดเจนเมื่อโฮเวอร์ทั้งสองฝั่ง */
        opacity: 1;
    }

    /* ปรับ Padding ของข้อความอื่นๆ เพื่อให้มีช่องว่างสำหรับปุ่มลบทางซ้าย */
    .message.other {
        background: #ddd;
        text-align: left;
        align-self: flex-start;
        padding-left: 35px; /* เพิ่มช่องว่างด้านซ้าย */
        padding-right: 15px; /* ล้างค่า padding-right ที่เพิ่มไว้ก่อนหน้า */
    }

    /* ไอคอนคนแทนรูปโปรไฟล์ */
    .chat-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 8px;
        vertical-align: middle;
        border: 2px solid #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .chat-avatar-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        min-height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        margin-right: 8px;
        vertical-align: middle;
        border: 2px solid #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .chat-avatar-icon i {
        font-size: 16px;
        line-height: 1;
    }

    /* สถานะอ่านข้อความ */
    .read-status {
        display: inline-block;
        margin-left: 8px;
        font-size: 14px;
    }

    .read-status.unread {
        color: #999;  /* สีเทา - ยังไม่อ่าน */
    }

    .read-status.read {
        color: #00a8ff;  /* สีฟ้า - อ่านแล้ว */
    }
</style>

<div class="chat-container">
    <a href="?page=chat_room">
        < ย้อนกลับ</a>
            <h2>
                <center>ห้องแชัท</center>
            </h2>
            <hr>

            <div class="messages" id="messages"></div>
            <div class="input-container">
                <label for="fileInput" class="custom-file">
                    📎
                </label>
                <input type="file" id="fileInput" accept="image/*,application/pdf" style="display:none;">
                <input type="text" id="messageInput" placeholder="Type a message" onkeypress="if(event.key==='Enter'){sendMessage();}">
                <button onclick="sendMessage()">Send</button>
            </div>
</div>

<script>
    const userId = <?php echo json_encode($_SESSION['user_id']); ?>;
    const messagesContainer = document.getElementById('messages');

    function isScrolledToBottom() {
        return messagesContainer.scrollHeight - messagesContainer.scrollTop <= messagesContainer.clientHeight + 10;
    }


    // ฟังก์ชันโหลดข้อความ (มีการปรับปรุงเพื่อเพิ่มปุ่มลบ)
    async function loadMessages() {
        try {
            // **NOTE:** get_messages.php ต้องส่ง message_id หรือ id กลับมาด้วย
            const response = await fetch('get_messages.php');
            const data = await response.json();

            const wasScrolledToBottom = isScrolledToBottom();

            messagesContainer.innerHTML = '';
            data.forEach(msg => {
                const messageDiv = document.createElement('div');
                // ตรวจสอบว่าเป็นข้อความของแอดมิน (userId) หรือไม่
                messageDiv.className = 'message ' + (msg.sender_id == userId ? 'me' : 'other');

                // แสดงไอคอนคนถ้าไม่มีรูปโปรไฟล์
                const hasAvatar = msg.img_name && msg.img_name.trim() !== '' && msg.img_name !== 'null';
                const avatarHTML = hasAvatar 
                    ? `<img src="../uploads/profile/${msg.img_name}" alt="" class="chat-avatar" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'"><span class="chat-avatar-icon" style="display:none"><i class="bi bi-person-fill"></i></span>` 
                    : `<span class="chat-avatar-icon"><i class="bi bi-person-fill"></i></span>`;

                let contentHTML = '';

                // ⭐ แก้ไขตรงนี้: ใช้ msg.id เพื่อส่ง ID ข้อความที่ถูกต้อง
                if (msg.sender_id == userId || msg.sender_id == <?php echo json_encode($_SESSION['u_id']); ?>) {
                    contentHTML += `<span class="delete-btn" onclick="deleteMessage(${msg.message_id})">🗑️</span>`;
                }

                // ติ๊กสถานะอ่าน (สำหรับข้อความที่เราส่ง)
                let readStatusHTML = '';
                if (msg.sender_id == userId) {
                    if (msg.status == 1) {
                        readStatusHTML = `<span class="read-status read"><i class="bi bi-check2-all"></i></span>`;
                    } else {
                        readStatusHTML = `<span class="read-status unread"><i class="bi bi-check2"></i></span>`;
                    }
                }

                // ... (โค้ดแสดงผลข้อความเดิม) ...
                if (msg.type === 'text') {
                    contentHTML += `
                    ${avatarHTML}<strong>${msg.username}</strong>: <small>${msg.customer_id}</small>
                    <div>${msg.message}</div>
                    <small>${new Date(msg.timestamp).toLocaleTimeString('th-TH')}</small>${readStatusHTML}
                `;
                } else if (msg.type === 'file') {
                    // ตรวจสอบว่าเป็นรูปภาพหรือ PDF เพื่อแสดงผล
                    const isImage = msg.file_name.match(/\.(jpeg|jpg|png|gif)$/i);
                    const fileDisplay = isImage ?
                        `<img src="../uploads/chat/${msg.file_name}" alt="Attached Image" class="img">` :
                        `<p><a href="../uploads/chat/${msg.file_name}" target="_blank">📥 ${msg.file_name} (PDF)</a></p>`;

                    contentHTML += `
                    ${avatarHTML}<strong>${msg.username}</strong>: <small>${msg.customer_id}</small>
                    <div>
                        ${fileDisplay}
                        <div style="color: #000; font-size:16px">${msg.message}</div>
                        <small>${new Date(msg.timestamp).toLocaleTimeString('th-TH')}</small>${readStatusHTML}
                    </div>
                `;
                }

                messageDiv.innerHTML = contentHTML;
                messagesContainer.appendChild(messageDiv);
            });

            if (wasScrolledToBottom) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    async function deleteMessage(messageId) {
        if (!confirm('คุณต้องการลบข้อความนี้หรือไม่?')) return;

        try {
            const response = await fetch('delete_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'message_id=' + encodeURIComponent(messageId)
            });

            const data = await response.json();

            if (data.success) {
                alert('ลบข้อความเรียบร้อยแล้ว');
                loadMessages(); // โหลดข้อความใหม่
            } else {
                alert('ไม่สามารถลบข้อความนี้ได้: ' + (data.error || ''));
            }
        } catch (error) {
            console.error('Error deleting message:', error);
            alert('เกิดข้อผิดพลาดในการลบข้อความ');
        }
    }


    // ฟังก์ชันส่งข้อความ
    function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const fileInput = document.getElementById('fileInput');

        const message = messageInput.value.trim();
        const file = fileInput.files[0];

        if (!message && !file) {
            alert('กรุณาพิมพ์ข้อความหรือเลือกไฟล์!');
            return;
        }

        const formData = new FormData();
        if (message) formData.append('message', message);
        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                alert('ชนิดไฟล์ไม่รองรับ (รองรับเฉพาะ JPG, PNG และ PDF)');
                return;
            }
            formData.append('file', file);
        }

        fetch('send_message.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // (Optional: ตรวจสอบ response จาก send_message.php)
                // console.log('Raw Response:', data); 
            })
            .then(() => {
                messageInput.value = '';
                fileInput.value = '';
                loadMessages();
            })
            .catch(error => console.error('Error sending message or file:', error));
    }


    // โหลดข้อความครั้งแรกและตั้งค่ารีเฟรช
    loadMessages();
    setInterval(loadMessages, 3000); // โหลดข้อความทุก 3 วินาที
</script>