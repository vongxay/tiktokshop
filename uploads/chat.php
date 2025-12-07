<?php
if (!isset($_SESSION['username'])) {
    echo '<script> location.replace("?page=login"); </script>';
}
$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
$_SESSION['user_id'] = $customer_id; // ตั้งค่า user_id ชั่วคราวสำหรับตัวอย่าง
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
        <input type="text" id="messageInput" placeholder="Type a message..." class="chat-input">
        <button class="send-button" onclick="sendMessage()"><i class="bi bi-send-fill"></i></button>
    </footer>
</div>

<script>
    const userId = <?php echo $_SESSION['user_id']; ?>; // User ID ของผู้ใช้
    const messagesContainer = document.getElementById('messages');
 
    function loadMessages() {
    const isScrolledToBottom =
        messagesContainer.scrollHeight - messagesContainer.scrollTop === messagesContainer.clientHeight;

    fetch('get_messages.php')
        .then(response => response.json())
        .then(data => {
            const currentScrollHeight = messagesContainer.scrollHeight;

            messagesContainer.innerHTML = ''; // ล้างข้อความเก่า
            data.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message ' + (msg.id == userId ? 'me' : 'other');
                if (msg.type === 'text') {
                    messageDiv.innerHTML = `<div>${msg.message}</div>`;
                } else if (msg.type === 'file') {
                    messageDiv.innerHTML = `
                        <div class="img-chat">
                            <a href="uploads/chat/${msg.file_name}" target="_blank">
                                <img src="uploads/chat/${msg.file_name}" class="img" alt="">
                            </a>
                            <p>${msg.message}</p>
                        </div>`;
                }
                messagesContainer.appendChild(messageDiv);
            });

            if (isScrolledToBottom) {
                // ถ้า Scrollbar อยู่ล่างสุด ให้เลื่อนลงล่างสุดหลังโหลด
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            } else {
                // คงตำแหน่ง Scrollbar หากผู้ใช้กำลังดูข้อความเก่า
                messagesContainer.scrollTop += messagesContainer.scrollHeight - currentScrollHeight;
            }
        });
}


    // โหลดข้อความครั้งแรกและตั้งค่ารีเฟรช
    // ฟังก์ชันส่งข้อความและไฟล์
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

            .then(() => {

                messageInput.value = ''; // ล้างช่องข้อความ
                fileInput.value = ''; // ล้างไฟล์
                loadMessages(); // โหลดข้อความใหม่

            })
            .catch(error => console.error('Error sending message or file:', error));
    }

    loadMessages();
    setInterval(loadMessages, 3000); // โหลดข้อความทุก 3 วินาที
</script>


<style>
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
</style>