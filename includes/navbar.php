<?php
$profile = getCustomerBy($_SESSION['loggedId']);
$customer_id = $profile['id'];
?>
<header style="background-color:aliceblue;">
  <style>
    a {
      text-decoration: none;
    }

    .dropdown-toggle {
      text-decoration: none;
    }

    .dropdown-toggle:hover {
      text-decoration: none;
    }
  </style>
  <nav>

    <form class="d-flex" action="?page=product" method="POST">

      <div class="dropdown">
        <a class="dropdown-toggle" style="color: #141414;" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-bag-heart-fill"></i>
        <?php echo $T['category']; ?>
        </a>
        <ul class="dropdown-menu" style="font-size: 12px;">
          <?php
          $db = connect();
          $stmt = $db->query("SELECT * FROM tb_category ORDER BY name ASC");
          $resualt = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach ($resualt as $row) { ?>
            <li><a class="dropdown-item" href="?page=add_product&category_id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></li>
          <?php } ?>
        </ul>
      </div>

      <div class="d-flex align-items-center" style="gap: 10px;">

        <!-- ปุ่ม search -->
        <a href="javascript:void(0)" id="searchToggle" style="color:#333; font-size: larger;">
          <i class="bi bi-search"></i>
        </a>

        <!-- กล่อง input (เริ่มซ่อน) -->
        <input class="form-control search-box"
          id="searchBox"
          type="search"
          name="search"
          value="<?php if (isset($_POST['search'])) {
                    echo $_POST['search'];
                  } ?>"
          placeholder="<?php echo $T['search_placeholder']; ?>"
          aria-label="Search"
          style="border-radius: 50px; width: 0; opacity: 0; transition: all 0.4s ease; font-size: 13px; padding: 6px 12px;">

        <!-- เมนูอื่น ๆ -->
        <div id="menuItems" class="d-flex align-items-center" style="gap: 10px;">
          <?php
          // ดึงจำนวนข้อความยังไม่ได้อ่าน
          $stmt = $db->prepare("
                  SELECT COUNT(id) as unread_count 
                  FROM tb_messages 
                  WHERE status = 0 AND receiver_id = :customer_id
              ");
          $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $unread_count = $row['unread_count'] ?? 0;

          // ✅ ดึงรายชื่อแชท + ข้อความล่าสุด + รูป
          $stmt = $db->prepare("
                  SELECT 
                      CASE 
                          WHEN m.sender_id = :customer_id THEN m.receiver_id
                          ELSE m.sender_id
                      END AS chat_partner_id,
                      u.username,
                      u.fname,
                      u.img_name,
                      MAX(m.timestamp) AS last_time,
                      (
                          SELECT m2.message 
                          FROM tb_messages m2
                          WHERE 
                              (m2.sender_id = CASE WHEN m.sender_id = :customer_id THEN m.receiver_id ELSE m.sender_id END 
                              AND m2.receiver_id = :customer_id)
                              OR 
                              (m2.sender_id = :customer_id 
                              AND m2.receiver_id = CASE WHEN m.sender_id = :customer_id THEN m.receiver_id ELSE m.sender_id END)
                          ORDER BY m2.timestamp DESC
                          LIMIT 1
                      ) AS last_message,
                      (
                          SELECT COUNT(*) 
                          FROM tb_messages m3
                          WHERE m3.status = 0 
                          AND m3.sender_id = CASE WHEN m.sender_id = :customer_id THEN m.receiver_id ELSE m.sender_id END
                          AND m3.receiver_id = :customer_id
                      ) AS unread_for_user
                  FROM tb_messages m
                  JOIN tb_customer u ON u.id = CASE 
                                                  WHEN m.sender_id = :customer_id THEN m.receiver_id
                                                  ELSE m.sender_id
                                              END
                  WHERE m.sender_id = :customer_id OR m.receiver_id = :customer_id
                  GROUP BY chat_partner_id, u.username, u.fname, u.img_name
                  ORDER BY last_time DESC
              ");
          $stmt->execute([':customer_id' => $customer_id]);
          $chat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <script>
            function fetchMessageCount() {
              fetch('get_count.php')
                .then(response => response.json())
                .then(data => {
                  if (data.error) {
                    console.error('Error:', data.error);
                    return;
                  }
                  document.getElementById('messageCount').textContent = data.count;
                })
                .catch(error => console.error('Error fetching message count:', error));
            }

            // เรียก fetchMessageCount ทุก 2 วินาที
            setInterval(fetchMessageCount, 2000);

            // เรียกครั้งแรกทันที
            fetchMessageCount();
          </script>
          <!-- 🔔 ไอคอนแชท + Badge + Dropdown -->
          <div class="chat-dropdown-wrapper" style="position: relative; display: inline-block;">
            <a href="#" style="color:#333; font-size: larger; position: relative;">
              <i class="bi bi-chat-square-dots"></i>

              <?php if ($unread_count > 0): ?>
                <span class="chat-badge" id="messageCount"></span>
              <?php endif; ?>
            </a>

            <!-- Dropdown -->
            <div class="chat-dropdown">
              <?php if (!empty($chat_list)): ?>
                <?php foreach ($chat_list as $chat): ?>
                  <a href="?page=chat&user=<?php echo $chat['chat_partner_id']; ?>" class="chat-item">
                    <img src="uploads/profile/<?php echo $chat['img_name'] ?: 'logo02.png'; ?>" alt="avatar">
                    <strong><?php echo htmlspecialchars($chat['username'] ?: $chat['username']); ?></strong>
                    <div class="chat-text">
                      <li>
                        <small><?php echo htmlspecialchars($chat['last_message']); ?></small>
                      </li>
                    </div>
                    <?php if ($chat['unread_for_user'] > 0): ?>
                      <span class="chat-unread"><?php echo $chat['unread_for_user']; ?></span>
                    <?php endif; ?>
                  </a>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="chat-empty"><?php echo $T['no_new_message']; ?></div>
              <?php endif; ?>
            </div>
          </div>

          <style>
            .chat-badge {
              position: absolute;
              top: -5px;
              right: -10px;
              background: red;
              color: white;
              font-size: 10px;
              font-weight: bold;
              padding: 2px 5px;
              border-radius: 50%;
              min-width: 16px;
              text-align: center;
              line-height: 1;
              box-shadow: 0 0 2px rgba(0, 0, 0, 0.3);
            }

            /* Dropdown */
            .chat-dropdown-wrapper:hover .chat-dropdown {
              display: block;
            }

            .chat-dropdown {
              display: none;
              position: absolute;
              right: 0;
              top: 120%;
              background: #fff;
              border: 1px solid #ddd;
              border-radius: 8px;
              box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
              width: 280px;
              z-index: 999;
              overflow: hidden;
            }

            .chat-item {
              display: flex;
              align-items: center;
              padding: 8px;
              text-decoration: none;
              color: #333;
              border-bottom: 1px solid #f1f1f1;
              transition: background 0.2s;
            }

            .chat-item:hover {
              background: #f7f7f7;
            }

            .chat-item img {
              width: 40px;
              height: 40px;
              border-radius: 50%;
              margin-right: 10px;
              object-fit: cover;
            }

            .chat-text {
              flex: 1;
              display: flex;
              flex-direction: column;
              font-size: 14px;
            }

            .chat-text strong {
              font-weight: bold;
              margin-bottom: 2px;
            }

            .chat-text small {
              font-size: 12px;
              color: #666;
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
            }

            .chat-unread {
              background: red;
              color: white;
              font-size: 10px;
              font-weight: bold;
              padding: 2px 6px;
              border-radius: 12px;
              min-width: 18px;
              text-align: center;
            }

            .chat-empty {
              padding: 10px;
              text-align: center;
              color: #666;
            }
          </style>

          <!-- <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:#333; font-size: larger;">
            <i class="bi bi-translate"></i>
          </a>
          <ul class="dropdown-menu" style="font-size: 16px; color:aliceblue; background-color:#141414;" aria-labelledby="navbarDropdown">
            <a href="set_language.php?lang=en" style="margin: 7px"> English</a>
            <a href="set_language.php?lang=th" style="margin: 7px"> ไทย</a>
          </ul> -->
        </div>

      </div>

      <script>
        const searchToggle = document.getElementById('searchToggle');
        const searchBox = document.getElementById('searchBox');
        const menuItems = document.getElementById('menuItems');

        searchToggle.addEventListener('click', () => {
          // ซ่อนเมนู
          menuItems.style.display = "none";
          // ขยาย input พร้อม fade in
          searchBox.style.width = "100%";
          searchBox.style.opacity = "1";
          searchBox.focus();
        });

        // เวลากดออก (blur) ให้กลับสภาพเดิม
        searchBox.addEventListener('blur', () => {
          searchBox.style.width = "0";
          searchBox.style.opacity = "0";
          setTimeout(() => {
            menuItems.style.display = "flex";
          }, 400); // รอ animation เสร็จก่อน
        });
      </script>
    </form>
  </nav>
</header>