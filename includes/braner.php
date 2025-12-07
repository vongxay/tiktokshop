<main>
    <section id="products">

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="margin-top: 55px;">
    <div class="carousel-inner">

        <div class="carousel-item active">
            <img src="img/branner/01.png" class="d-block w-100 img-fluid" alt="..."
                style="height: 200px; object-fit: cover;">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center p-2"
                style="background: rgba(0,0,0,0.6); border-radius: 10px;">
                <h5 style="font-weight: bold; color: #fff;"><?php echo $T['promo_title']; ?></h5> 
                <p style="color: #f1f1f1;"><?php echo $T['promo_desc']; ?></p>
                <a href="?page=product" class="btn btn-warning btn-sm" style="border-radius: 20px; font-weight: bold;">
                    <?php echo $T['promo_btn']; ?>
                </a>
            </div>
        </div>

        <div class="carousel-item">
            <img src="img/branner/02.png" class="d-block w-100 img-fluid" alt="..."
                style="height: 200px; object-fit: cover;">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center p-2"
                style="background: rgba(0,0,0,0.6); border-radius: 10px;">
                <h5 style="font-weight: bold; color: #fff;"><?php echo $T['new_title']; ?></h5>
                <p style="color: #f1f1f1;"><?php echo $T['new_desc']; ?></p>
                <a href="?page=product" class="btn btn-danger btn-sm" style="border-radius: 20px; font-weight: bold;">
                    <?php echo $T['new_btn']; ?>
                </a>
            </div>
        </div>

        <div class="carousel-item">
            <img src="img/branner/03.png" class="d-block w-100 img-fluid" alt="..."
                style="height: 200px; object-fit: cover;">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center p-2"
                style="background: rgba(0,0,0,0.6); border-radius: 10px;">
                <h5 style="font-weight: bold; color: #fff;"><?php echo $T['recommend_title']; ?></h5>
                <p style="color: #f1f1f1;"><?php echo $T['recommend_desc']; ?></p>
                <a href="?page=product" class="btn btn-danger btn-sm" style="border-radius: 20px; font-weight: bold;">
                    <?php echo $T['recommend_btn']; ?>
                </a>
            </div>
        </div>

    </div>
</div>



        <div class="service">
            <a href="?page=market" class="icon" style="color:#333; font-size: 20px; margin: 7px;">
                <i class="bi bi-shop-window"></i>
                <p style="font-size: 14px; margin-top: -5px; font-weight: bolder;"><?php echo $T['store']; ?></p>
            </a>
            <a href="?page=video" class="icon" style="color:#333; font-size: 20px; margin: 7px;">
                <i class="bi bi-play-btn"></i>
                <p style="font-size: 14px; margin-top: -5px; font-weight: bolder;"><?php echo $T['video']; ?></p>
            </a>
            <a href="?page=chat&user=17" class="icon" style="color:#333; font-size: 20px; margin: 7px;">
                <i class="bi bi-headset"></i>
                <p style="font-size: 14px; margin-top: -5px; font-weight: bolder"><?php echo $T['customer_service']; ?></p>
            </a>
            <a href="https://www.channelengine.com/en/blog/tiktok-shop-what-is-it-and-how-does-it-work#:~:text=TikTok%20Shop%20is%20the%20social,start%20selling%20on%20TikTok%20Shop." class="icon" style="color:#333; font-size: 20px; margin: 7px;" target="_blank">
                <i class="bi bi-bag-heart-fill"></i>
                <p style="font-size: 14px; margin-top: -5px; font-weight: bolder"><?php echo $T['about']; ?></p>
            </a>

            <div class="lang-dropdown" style="position: relative; margin: 7px;">
                <a href="#" class="icon lang-btn" style="color:#333; font-size: 20px;">
                    <i class="bi bi-globe"></i>
                    <p style="font-size: 14px; margin-top: -5px; font-weight: bolder; color: #000;">
                        <?php echo strtoupper($current_lang); ?>
                    </p>
                </a>

                <div class="lang-content" style="
            display: none; 
            position: absolute; 
            background-color: #f9f9f9; 
            min-width: 120px; 
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); 
            z-index: 1000;
            top: 50px; /* ปรับตำแหน่งให้ดรอปดาวน์ลงมาด้านล่าง */
            left: 0;
            border-radius: 5px;
            padding: 5px;
        ">
                    <a href="?lang=th" style="color: #333; padding: 5px 10px; text-decoration: none; display: block; font-size: 14px;">
                        <?php echo $T['lang_th']; ?>
                    </a>

                    <a href="?lang=en" style="color: #333; padding: 5px 10px; text-decoration: none; display: block; font-size: 14px;">
                        <?php echo $T['lang_en']; ?>
                    </a>

                </div>
            </div>
        </div>

        <style>
            /* การตั้งค่าสำหรับ Dropdown ภาษา */
            .lang-dropdown {
                /* ทำให้ Dropdown อยู่ในตำแหน่งที่สัมพันธ์กับมันเอง */
                position: relative;
                display: inline-block;
                /* เพื่อให้มันอยู่ติดกับปุ่มอื่น ๆ */

            }

            /* เมื่อผู้ใช้นำเมาส์มาชี้ที่ div หลักของ Dropdown */
            .lang-dropdown:hover .lang-content {
                /* ให้แสดงเมนูย่อยขึ้นมา */
                display: block !important;
                /* ใช้ !important เพื่อ override style inline */
                text-align: left;
            }

            /* จัดรูปแบบเมนูย่อยให้สวยงามขึ้น */
            .lang-content a:hover {
                background-color: #ddd;
                border-radius: 3px;
                text-align: left;
            }
        </style>