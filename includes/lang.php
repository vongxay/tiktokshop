<?php
// lang.php

$lang = [];

// ===============================================
// 🔑 ภาษาไทย (TH)
// ===============================================
$lang['th'] = [
    // เมนูหลัก
    'store'           => 'ร้านค้า',
    'video'           => 'วิดีโอ',
    'customer_service' => 'ฝ่ายบริการ',
    'about'           => 'เกี่ยวกับ',
    'lang_th'         => '🇹🇭 ไทย',
    'lang_en'         => '🇺🇸 English',
    
    // ⭐ ข้อความ Carousel ที่เพิ่มใหม่ ⭐
    'promo_title'     => 'โปรโมชั่นพิเศษ',
    'promo_desc'      => 'ลดราคาสินค้าสูงสุด 50%',
    'promo_btn'       => '🛒 เลือกซื้อสินค้า',
    'new_title'       => 'สินค้ามาใหม่',
    'new_desc'        => 'อัปเดตทุกวัน สดใหม่เสมอ',
    'new_btn'         => '✨ ดูสินค้าใหม่',
    'recommend_title' => 'สินค้าแนะนำ',
    'recommend_desc'  => 'ขายดีอันดับ 1',
    'recommend_btn'   => '⭐ ช้อปเลย',

    // ⭐ ข้อความ Crypto Payment ที่เพิ่มใหม่ ⭐
    'crypto_desc'     => 'ผู้ใช้ TkTok Mall ทั่วโลกจาก 103 ประเทศ ใช้ USDT/ETH/BTC ในการชำระเงิน ซึ่งเป็นวิธีการทำธุรกรรมแบบไร้พรมแดนที่ประหยัดและรวดเร็ว ไม่มีค่าธรรมเนียมระหว่างประเทศ',

    // ⭐ ข้อความ A2HS Prompt ที่เพิ่มใหม่ ⭐
    'a2hs_title'      => '📱 ติดตั้งแอปนี้',
    'a2hs_desc'       => 'เพิ่มระบบลงในหน้าจอหลักเพื่อใช้งานสะดวกยิ่งขึ้น!',
    'a2hs_btn'        => 'ติดตั้งเลย',
    // ⭐ ข้อความ Footer Nav ที่เพิ่มใหม่ ⭐
    'nav_home'        => 'หน้าแรก',
    'nav_product'     => 'ผลิตภัณฑ์',
    'nav_cart'        => 'รถเข็น',
    'nav_profiles'    => 'โปรไฟล์',
    // ⭐ ข้อความ Search & Chat ที่เพิ่มใหม่ ⭐
    'category'        => 'หมวดหมู่',
    'search_placeholder' => 'ค้นหา...',
    'no_new_message'  => 'ไม่มีข้อความใหม่',
    // ⭐ ข้อความ Promo Banners ที่เพิ่มใหม่ ⭐
    'promo_women_title' => '👗 แฟชั่นเสื้อผ้าผู้หญิง',
    'promo_women_desc'  => 'สวย หรู ดูดี ทุกโอกาส ✨ โปรโมชั่นลดสูงสุด 50% วันนี้!',
    'promo_women_btn'   => '🛍️ ช้อปเลย',

    'promo_men_title'   => '👔 แฟชั่นเสื้อผ้าผู้ชาย',
    'promo_men_desc'    => 'เท่ มีสไตล์ ทุกโอกาส ✨ ลดสูงสุด 40% วันนี้!',
    'promo_men_btn'     => '🛒 เลือกซื้อเลย',

    'promo_all_title'   => '🛍️ ช้อปปิ้งสุดคุ้ม',
    'promo_all_desc'    => 'สินค้าหลากหลาย ครบทุกหมวด ✨ ลดราคาสุดพิเศษ 30% ทันที!',
    'promo_all_btn'     => '⭐ เริ่มช้อปเลย',

    // ⭐ ข้อความ Lock Screen/SweetAlert ⭐
    'lock_title'        => 'บัญชีของคุณถูกล็อก',
    'lock_text'         => 'กรุณาติดต่อฝ่ายบริการลูกค้าเพื่อดำเนินการปลดล็อก',
    'lock_btn'          => 'ติดต่อฝ่ายบริการ',

    // ⭐ ข้อมูลโปรไฟล์/สถิติ ⭐
    'user_id_label'     => 'รหัสผู้ใช้',
    'vip_level_0'       => 'ลูกค้าทั่วไป',
    'vip_level_1'       => 'VIP 1',
    'vip_level_2'       => 'VIP 2',
    'vip_level_3'       => 'VIP 3',
    'vip_level_4'       => 'VIP 4',
    'vip_level_5'       => 'VIP 5',
    'visits_1day'       => 'เข้าชมร้าน 1 วันผ่านมา',
    'visits_7day'       => '7 วัน',
    'visits_less_7day'  => 'ยังไม่ครบ 7 วัน',
    'wallet_balance'    => 'ยอดเงินในกระเป๋า',
    'total_sales'       => 'ยอดขายรวม',
    'profit'            => 'กำไร',
    'product_count'     => 'จำนวนสินค้า',
    'pending_payment'   => 'ยอดค้างชำระ',
    'edit_profile_btn'  => 'แก้ไขโปรไฟล์',
    'logout_btn'        => 'ออกจากระบบ',
    'debug_visits_created'=> 'สร้างข้อมูลจำลองผู้เข้าชมร้าน 7 วันที่ผ่านมาเรียบร้อยแล้ว!', // ควรลบใน Production
    // ⭐ ข้อความ Order List (order_list.php) ⭐
    'order_list_title'          => 'รายการคำสั่งซื้อ',

    // ⭐ สถานะคำสั่งซื้อ (Stats) ⭐
    'status_0'          => 'ค้างชำระ',
    'status_1'          => 'รอจัดส่ง',
    'status_2'          => 'ระหว่างการจัดส่ง',
    'status_3'          => 'เสร็จ',

    // ⭐ เมนู Grid (การเงิน) ⭐
    'menu_payment'      => 'การชำระเงิน',
    'menu_withdraw'     => 'ถอนเงิน',
    'menu_money_log'    => 'บันทึกการเงิน',

    // ⭐ เมนู Grid (คำสั่งซื้อ) ⭐
    'menu_order_history'=> 'ประวัติคำสั่งซื้อ',
    'menu_daily_list'   => 'รายการประจำวัน',

    // ⭐ เมนู Grid (โปรไฟล์) ⭐
    'menu_my_profile'   => 'โปรไฟล์ของฉัน',
    'menu_address'      => 'ที่อยู่',
    'menu_change_password'=> 'รหัสผ่าน',

    // ⭐ เมนู Grid (สินค้า/โปรโมชัน/แชท) ⭐
    'menu_add_product'  => 'เพิ่มสินค้า',
    'menu_promo_card'   => 'บัตรโปรโมชัน',
    'menu_live_chat'    => 'แชทสด',

    // ⭐ ข้อความ Add Product (add_product.php) ⭐
    'admin_title'       => 'คุณเป็น Admin อยู่แล้ว',
    'admin_text'        => 'ไม่จำเป็นต้องเพิ่มสินค้า',
    
    'limit_title'       => 'ถึงจำนวนสูงสุดแล้ว!',
    'limit_text'        => 'สิทธิ์ VIP ของคุณสามารถเพิ่มสินค้าได้สูงสุด {limit} รายการ', // {limit} จะถูกแทนที่ด้วย PHP
    
    'save_success_title' => 'บันทึกสำเร็จ!',
    'save_success_text'  => 'สินค้าของคุณถูกเพิ่มเรียบร้อยแล้ว',
    'save_fail_text'    => 'กรุณากรอกข้อมูลให้ครบถ้วน',
    
    'product_exists_title'=> 'สินค้านี้มีอยู่แล้ว!',
    'product_exists_text' => 'คุณได้เพิ่มสินค้านี้ไปแล้วในร้านของคุณ',
    
    'modal_details'     => 'รายละเอียดสินค้า',
    'modal_add_list'    => 'เพิ่มเข้าในรายการของคุณ',
    // ⭐ ข้อความ Product Detail (product_detail.php) ⭐
    'confirm_add_cart'  => 'กดเพื่อตกลงยืนยัน', // ข้อความใน confirm box
    'status_quantity'   => 'ปริมาณ',
    'status_stock'      => 'คลังสินค้า',
    'status_shop'       => 'ร้านค้า',
    'status_no_info'    => 'ไม่มีข้อมูล',
    'status_comment'    => 'ความคิดเห็น',
    'status_rate'       => 'อัตรา',
    'rate_percent'      => '100%', // หากตัวเลขไม่เปลี่ยน ให้แปลเฉพาะ %
    'add_to_cart_btn'   => 'Add To Cart', // ปุ่มหลัก
    'related_products'  => 'สินค้าที่คล้ายกัน',
    // ⭐ ข้อความ Cart Page (ตะกร้าสินค้า) ⭐
    'cart_title'          => 'ตะกร้าสินค้า',
    'cart_items_count'    => 'รายการ', // สำหรับแสดงจำนวนรายการในตะกร้า
    'cart_remove_btn'     => 'ลบ',
    'cart_back_to_shop'   => 'กลับไปเลือกซื้อ',
    'cart_summary'        => 'สรุปคำสั่งซื้อ',
    'summary_items_label' => 'รายการที่', // 'รายการที่ X'
    'summary_total_price' => 'ราคารวม',
    'checkout_btn'        => 'เช็คเอาท์',
    
    // ⭐ ข้อความ Form เช็คเอาท์ ⭐
    'form_name_placeholder'   => 'ชื่อ',
    'form_phone_placeholder'  => 'เบอโทร',
    'form_address_placeholder'=> 'สถานที่จัดส่ง',
    'form_distric_placeholder'=> 'อำเภอ',
    'form_password_placeholder'=> 'ให้รหัส',
    
    // ⭐ ข้อความตรวจสอบรหัสผ่าน (JS/CSS) ⭐
    'check_password_btn'  => 'ตรวจสอบรหัสผ่าน',
    'password_correct'    => 'รหัสถูกต้อง!',
    'password_incorrect'  => 'รหัสไม่ถูกต้อง!',
    
    // ⭐ ข้อความ Alert ⭐
    'alert_not_enough_stock'=> 'สินค้าในระบบไม่พอคะ',
    // ⭐ ข้อความ Change Password (change_password.php) ⭐
    'change_pass_title'         => '🔑 เปลี่ยนรหัสผ่าน',
    'pass_mismatch_error'       => 'รหัสผ่านใหม่ ไม่ตรงกัน',
    'pass_old_placeholder'      => 'รหัสผ่านเดิม',
    'pass_new_placeholder'      => 'รหัสผ่านใหม่',
    'pass_confirm_placeholder'  => 'ยืนยันรหัสผ่านใหม่',
    'change_pass_success'       => 'เปลี่ยนรหัสผ่านเสร็จ',
    'change_pass_fail'          => 'เปลี่ยนรหัสผ่านไม่ได้ลองไหม่อีกครั้ง',
    'change_pass_btn'           => 'บันทึกการเปลี่ยนแปลง',
    // ⭐ ข้อความ Chat Page ⭐
    'chat_placeholder'        => 'พิมพ์ข้อความ...',
    'chat_no_partner'         => 'กรุณาเลือกคู่สนทนา',
    'chat_alert_empty'        => 'กรุณาพิมพ์ข้อความหรือเลือกไฟล์!',
    'chat_alert_no_receiver'  => 'ไม่พบคู่สนทนา',
    'chat_alert_file_type'    => 'ชนิดไฟล์ไม่รองรับ (รองรับเฉพาะ JPG, PNG และ PDF)',
    // ⭐ ข้อความ Edit Profile (edit_profiles.php) ⭐
    'update_success_img'    => 'อัปเดตข้อมูลพร้อมรูปภาพสำเร็จ!',
    'update_success_no_img' => 'อัปเดตข้อมูลสำเร็จ!',
    'update_file_error'     => 'อัปโหลดไฟล์ไม่สำเร็จ หรือไฟล์ไม่รองรับ!',
    'update_save_error'     => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล!',
    'input_mobile_placeholder'=> 'เบอร์มือถือ',
    'input_username_label'  => '*ชื่อผู้ใช้งาน',
    'input_username_placeholder'=> 'ชื่อผู้ใช้งาน',
    'input_address_label'   => '*ที่อยู่',
    'input_address_placeholder'=> 'ที่อยู่',
    'input_distric_label'   => '*เขต/อำเภอ',
    'input_distric_placeholder'=> 'เขต/อำเภอ',
    'input_province_label'  => '*จังหวัด',
    'input_province_placeholder'=> 'จังหวัด',
    'input_last_login_label'=> '*เข้าระบบครั้งล่าสุด',
    'input_last_login_placeholder'=> 'เข้าระบบครั้งล่าสุด',
    'save_changes_btn'      => 'บันทึก',
    // ⭐ ข้อความ Forgot Password (forgot.php) ⭐
    'forgot_title'              => 'ลืมรหัสผ่าน',
    'reset_title'               => 'รีเซ็ตรหัสผ่าน',
    'mobile_placeholder'        => 'เบอร์มือถือ',
    'confirm_btn'               => 'ยืนยัน',
    'new_pass_placeholder'      => 'รหัสผ่านใหม่',
    'confirm_pass_placeholder'  => 'ยืนยัน',
    'change_pass_btn'           => 'เปลี่ยนรหัสผ่าน',
    
    // ⭐ ข้อความแจ้งเตือน (SweetAlert/JS) ⭐
    'js_pass_min_length'        => 'รหัสผ่านอย่างน้อย 6 ตัวเลขอี!', // แก้ไขให้ถูกต้องตามต้นฉบับ
    'pass_mismatch_alert'       => 'รหัสผ่านไม่ตรงกัน โปรดตรวจสอบและลองอีกครั้ง!',
    'reset_success'             => 'รีเซ็ตรหัสผ่านเสร็จ!',
    'mobile_not_found'          => 'ไม่พบเบอร์โทรศัพท์!', // แปลจาก "not fond phone number!"
    // ⭐ ข้อความ Login Page (login.php) ⭐
    'login_header'              => 'เข้าสู่ระบบด้วยบัญชีของคุณ',
    'username_placeholder'      => 'ชื่อบัญชี',
    'password_placeholder'      => 'รหัสผ่าน',
    'login_btn'                 => 'เข้าสู่ระบบ',
    'link_register'             => 'ลงทะเบียน',
    'link_forgot'               => 'ลืมรหัสผ่าน',
    
    // ⭐ ข้อความแจ้งเตือน Login (SweetAlert) ⭐
    'alert_login_success'       => 'Login success!',
    'alert_login_update_fail'   => 'Not success!', // ข้อความเดิม: Not success!
    'alert_login_fail'          => 'not fond username and password!', // ข้อความเดิม: not fond username and password!
    // ⭐ ข้อความ Market Registration (market.php) ⭐
    'market_join_title'         => 'เข้าร่วมร้านค้าของเรา',
    'market_mobile_label'       => '*เบอร์มือถือ',
    'market_mobile_placeholder' => 'เบอร์มือถือ',
    'market_store_label'        => '*ชื่อร้าน',
    'market_store_placeholder'  => 'ชื่อร้าน',
    'market_fname_label'        => '*ชื่อจริง',
    'market_fname_placeholder'  => 'ชื่อจริง',
    'market_lname_label'        => '*นามสกุล',
    'market_lname_placeholder'  => 'นามสกุล',
    'market_open_btn'           => 'เปิดร้าน',
    'market_open_success'       => 'Open Maket success!', // ใช้ข้อความเดิมตามโค้ดต้นฉบับ
    // ⭐ ข้อความ Dashboard Cards (Metrics) ⭐
    'metric_wallet_balance'     => 'ยอดเงินในกระเป๋า',
    'metric_total_sales'        => 'ยอดขายรวม',
    'metric_total_profit'       => 'กำไรรวม',
    'metric_pending_order'      => 'ออเดอร์ค้าง',
    'metric_income_today'       => 'ยอดขายวันนี้',
    'metric_expense_today'      => 'รายจ่ายวันนี้',
    // ⭐ ข้อความ My Orders / Order List ⭐
    'my_sales_list_title'       => 'รายการขายของฉัน',
    'order_qty_label'           => 'จำนวน',
    // ⭐ ข้อความ Payment Confirmation (pay_confirm.php) ⭐
    'payment_back'              => 'ย้อนกลับ',
    'payment_alert_balance'     => '❌ วงเงินไม่พอ กรุณาเติมเงินก่อน', // ข้อความ Alert
    
    // รายละเอียดก่อนชำระ
    'payment_qty_label'         => 'จำนวนสินค้า',
    'payment_price_label'       => 'ราคาสินค้า',
    'payment_profit_label'      => 'กำไร',
    'payment_total_pay_label'   => 'ยอดที่ต้องชำระจริง',
    'payment_buyer_label'       => 'ผู้สั่งซื้อ',
    'payment_address_label'     => 'ที่อยู่',
    'payment_wallet_label'      => 'ยอดเงินใน Wallet',
    'payment_btn_pay'           => '💳 ชำระเงิน',
    'payment_btn_topup'         => 'เติมเงินก่อน',
    
    // ใบเสร็จ (Receipt)
    'receipt_title'             => '🧾 ใบเสร็จรับเงิน',
    'receipt_order_id'          => 'เลขที่ออเดอร์',
    'receipt_product_name'      => 'สินค้า',
    'receipt_qty_unit'          => 'ชิ้น',
    'receipt_paid_by'           => 'ผู้ชำระเงิน',
    'receipt_shipping_address'  => 'ที่อยู่จัดส่ง',
    'receipt_remaining_balance' => 'ยอดคงเหลือใน Wallet',
    'receipt_btn_done'          => '✅ เสร็จสิ้น',
    'receipt_btn_print'         => '🖨 พิมพ์ใบเสร็จ',
    // ⭐ ข้อความ Invoice / Money Log (order_today.php) ⭐
    'invoice_title'             => 'Invoice รายการสั่งซื้อทั้งหมด',
    'invoice_date_label'        => 'วันที่',
    'invoice_qty_unit'          => 'จำนวน',
    'invoice_unit_price'        => 'ราคา/หน่วย',
    'invoice_time_label'        => 'เวลา',
    'invoice_subtotal'          => 'รวม',
    'invoice_profit'            => 'กำไร',
    'invoice_daily_total'       => 'ยอดรวมวัน',
    'invoice_daily_profit'      => 'กำไรรวมวัน',
    'invoice_grand_total'       => 'ยอดรวมทั้งหมด',
    'invoice_grand_profit'      => 'กำไรรวมทั้งหมด',
    'invoice_no_order'          => 'ยังไม่มีรายการสั่งซื้อ',
    // ⭐ ข้อความ Payment/Top-up (payment.php) ⭐
    'payment_form_title'        => 'ชำระเงิน',
    'payment_alert_upload_fail' => '❌ ไม่สามารถอัปโหลดสลิปได้ ตรวจสอบ permission ของโฟลเดอร์ \'uploads/slip/\'',
    'payment_alert_success'     => 'ส่งคำขอเติมเงินเรียบร้อย รอการตรวจสอบ',
    'payment_amount_label'      => 'ยอดที่ต้องชำระ',
    'payment_amount_placeholder'=> '00.00',
    'payment_method_label'      => 'เลือกช่องทางชำระเงิน',
    'payment_copy_btn'          => '📋 คัดลอก',
    'payment_copied_btn'        => '✅ คัดลอกแล้ว',
    'payment_upload_label'      => 'อัปโหลดสลิปการโอน',
    'payment_slip_preview'      => 'ตัวอย่างสลิป',
    'payment_confirm_btn'       => 'ยืนยันการชำระเงิน',
    
    // ⭐ ประวัติการชำระเงิน ⭐
    'payment_history_title'     => 'ประวัติการชำระเงิน',
    'history_status_0'          => 'รออนุมัติ',
    'history_status_1'          => 'สำเร็จ',
    'history_status_2'          => 'ยกเลิก',
    'history_amount_label'      => 'จำนวนเงิน',
    'history_date_label'        => 'วันที่',
    'history_no_record'         => 'ยังไม่มีประวัติการชำระเงิน',
    // ⭐ ข้อความ Registration Page (registor.php) ⭐
    'reg_tab_mobile'            => 'เบอร์มือถือ',
    'reg_tab_email'             => 'อีเมล', // แม้จะถูกคอมเมนต์ไว้
    'reg_mobile_placeholder'    => 'เบอร์มือถือ',
    'reg_name_label'            => '*ชื่อ',
    'reg_name_placeholder'      => 'ชื่อ',
    'reg_password_label'        => '*รหัสผ่าน',
    'reg_password_placeholder'  => 'รหัสผ่าน',
    'reg_confirm_label'         => '*ยืนยัน',
    'reg_confirm_placeholder'   => 'ยืนยัน',
    'reg_btn'                   => 'ลงทะเบียน',
    'reg_link_login'            => 'เข้าสู่ระบบ',
    'reg_terms'                 => '✔ ยืนยัน "ข้อตกลง"',

    // ⭐ ข้อความแจ้งเตือน Registration (SweetAlert/JS) ⭐
    'reg_js_pass_min'           => 'รหัสผ่านอย่างน้อย 6 ตัวเลขอี!',
    'reg_pass_mismatch_alert'   => 'รหัสผ่านไม่ตรงกัน โปรดตรวจสอบและลองอีกครั้ง!',
    'reg_username_taken'        => 'ชื่อบัญชีนี้ถูกใช้งานแล้ว โปรดเลือกชื่ออื่น!',
    'reg_success'               => 'Registor success!', // ใช้ข้อความเดิม
    'reg_fill_all_fields'       => 'โปรดป้อนข้อมูลทั้งหมดให้ครบแล้วลองอีกครั้ง',
    // ⭐ ข้อความ Invoice (invoice.php) ⭐
    'invoice_header_title'      => 'ใบแจ้งหนี้',
    'invoice_order_number'      => 'เลขที่ออเดอร์',
    'invoice_date'              => 'วันที่',
    'invoice_customer_info'     => 'ข้อมูลลูกค้า',
    'invoice_customer_name'     => 'ชื่อผู้สั่ง',
    'invoice_customer_address'  => 'ที่อยู่',
    'invoice_customer_phone'    => 'โทร',
    'invoice_seller'            => 'ผู้ขาย',
    'invoice_seller_name'       => 'ร้าน TKShop', // ชื่อร้าน
    'invoice_seller_address'    => 'กรุงเทพฯ',    // ที่อยู่ร้าน
    'invoice_qty_label_short'   => 'จำนวน', // ใช้ใน product card
    'invoice_unit_price_label'  => 'ราคา/หน่วย',
    'invoice_subtotal_short'    => 'รวม',
    'invoice_profit_short'      => 'กำไร',
    'invoice_pay_short'         => 'ชำระจริง',
    'invoice_summary_title'     => 'สรุปยอดรวม',
    'invoice_total_sales'       => 'ยอดขายรวม',
    'invoice_total_profit'      => 'กำไรรวม',
    'invoice_total_pay'         => 'ชำระรวม',
    'invoice_btn_back'          => 'กลับไปหน้าสั่งซื้อ',
    // ⭐ ข้อความ Withdraw Page (withdraw.php) ⭐
    'withdraw_title'            => 'ถอนเงิน',
    'withdraw_balance_label'    => '💰 ยอดเงินคงเหลือในกระเป๋า',
    'withdraw_amount_label'     => 'จำนวนเงินที่ต้องการถอน',
    'withdraw_btn_confirm'      => 'ยืนยันการถอน',
    'withdraw_history_title'    => '📜 ประวัติการถอนของคุณ',
    
    // ⭐ แจ้งเตือน/ข้อความสถานะ ⭐
    'alert_amount_invalid'      => '❌ กรุณากรอกจำนวนเงินที่ถูกต้อง',
    'alert_insufficient_funds'  => '❌ ยอดเงินไม่เพียงพอ',
    'alert_request_pending'     => '⏳ คำขอถอนของคุณกำลังรอการอนุมัติจากแอดมิน',
    'alert_has_pending'         => '⏳ คุณมีคำขอถอนที่กำลังรอการอนุมัติ โปรดรอแอดมินตรวจสอบ',
    
    // ⭐ สถานะในตาราง ⭐
    'status_pending_badge'      => 'รออนุมัติ',
    'status_approved_badge'     => 'อนุมัติ',
    'status_rejected_badge'     => 'ปฏิเสธ',
    'table_header_amount'       => 'จำนวนเงิน',
    'table_header_status'       => 'สถานะ',
    'table_header_date_req'     => 'วันที่ขอถอน',
    'table_header_date_proc'    => 'วันที่ดำเนินการ',
    'table_no_history'          => '- ยังไม่มีประวัติการถอน -',

    'payment_upload_btn' => '📁 เลือกสลิป',
];

// ===============================================
// 🔑 ภาษาอังกฤษ (EN)
// ===============================================
$lang['en'] = [
    // เมนูหลัก
    'store'           => 'Store',
    'video'           => 'Video',
    'customer_service' => 'Service',
    'about'           => 'About',
    'lang_th'         => '🇹🇭 Thai',
    'lang_en'         => '🇺🇸 English',
    
    // ⭐ ข้อความ Carousel ที่เพิ่มใหม่ ⭐
    'promo_title'     => 'Special Promotion',
    'promo_desc'      => 'Up to 50% discount on products',
    'promo_btn'       => '🛒 Shop Now',
    'new_title'       => 'New Arrivals',
    'new_desc'        => 'Updated daily, always fresh',
    'new_btn'         => '✨ View New Products',
    'recommend_title' => 'Recommended Products',
    'recommend_desc'  => 'Best Seller No. 1',
    'recommend_btn'   => '⭐ Shop Now',
    // ⭐ ข้อความ Crypto Payment ที่เพิ่มใหม่ ⭐
    'crypto_desc'     => 'TkTok Mall users from 103 countries worldwide use USDT/ETH/BTC for payment. This method provides borderless, economical, and fast transactions without international fees.',

    'a2hs_title'      => '📱 Install this App',
    'a2hs_desc'       => 'Add the system to your home screen for easier access!',
    'a2hs_btn'        => 'Install Now',
    // ⭐ ข้อความ Footer Nav ที่เพิ่มใหม่ ⭐
    'nav_home'        => 'Home',
    'nav_product'     => 'Products',
    'nav_cart'        => 'Cart',
    'nav_profiles'    => 'Profiles',
    // ⭐ ข้อความ Search & Chat ที่เพิ่มใหม่ ⭐
    'category'        => 'Category',
    'search_placeholder' => 'Search...',
    'no_new_message'  => 'No new messages',
    // ⭐ ข้อความ Promo Banners ที่เพิ่มใหม่ ⭐
    'promo_women_title' => '👗 Women\'s Fashion',
    'promo_women_desc'  => 'Elegant, chic for every occasion ✨ Special promotion up to 50% off today!',
    'promo_women_btn'   => '🛍️ Shop Now',

    'promo_men_title'   => '👔 Men\'s Fashion',
    'promo_men_desc'    => 'Cool, stylish for every occasion ✨ Up to 40% discount today!',
    'promo_men_btn'     => '🛒 Buy Now',

    'promo_all_title'   => '🛍️ Great Value Shopping',
    'promo_all_desc'    => 'Diverse products, all categories available ✨ Instant special discount of 30%!',
    'promo_all_btn'     => '⭐ Start Shopping',

    // ⭐ ข้อความ Lock Screen/SweetAlert ⭐
    'lock_title'        => 'Your account is locked',
    'lock_text'         => 'Please contact customer service to unlock your account',
    'lock_btn'          => 'Contact Customer Service',

    // ⭐ ข้อมูลโปรไฟล์/สถิติ ⭐
    'user_id_label'     => 'User ID',
    'vip_level_0'       => 'General Customer',
    'vip_level_1'       => 'VIP 1',
    'vip_level_2'       => 'VIP 2',
    'vip_level_3'       => 'VIP 3',
    'vip_level_4'       => 'VIP 4',
    'vip_level_5'       => 'VIP 5',
    'visits_1day'       => 'Shop Visits 1 Day Ago',
    'visits_7day'       => '7 Days',
    'visits_less_7day'  => 'Less than 7 days',
    'wallet_balance'    => 'Wallet Balance',
    'total_sales'       => 'Total Sales',
    'profit'            => 'Profit',
    'product_count'     => 'Product Count',
    'pending_payment'   => 'Pending Payment',
    'edit_profile_btn'  => 'Edit Profile',
    'logout_btn'        => 'Log Out',
    'debug_visits_created'=> 'Mock shop visits data for 7 days created!', // Should be removed in Production
    // ⭐ ข้อความ Order List (order_list.php) ⭐
    'order_list_title'          => 'Order List',
    
    // ⭐ สถานะคำสั่งซื้อ (Stats) ⭐
    'status_0'          => 'Pending',
    'status_1'          => 'Waiting',
    'status_2'          => 'Shipping',
    'status_3'          => 'Completed',

    // ⭐ เมนู Grid (การเงิน) ⭐
    'menu_payment'      => 'Payment',
    'menu_withdraw'     => 'Withdraw',
    'menu_money_log'    => 'Financial Log',

    // ⭐ เมนู Grid (คำสั่งซื้อ) ⭐
    'menu_order_history'=> 'Order History',
    'menu_daily_list'   => 'Daily List',

    // ⭐ เมนู Grid (โปรไฟล์) ⭐
    'menu_my_profile'   => 'My Profile',
    'menu_address'      => 'Address',
    'menu_change_password'=> 'Password',

    // ⭐ เมนู Grid (สินค้า/โปรโมชัน/แชท) ⭐
    'menu_add_product'  => 'Add Product',
    'menu_promo_card'   => 'Promotion Card',
    'menu_live_chat'    => 'Live Chat',

    // ⭐ ข้อความ Add Product (add_product.php) ⭐
    'admin_title'       => 'You are already an Admin',
    'admin_text'        => 'It is not necessary to add products.',
    
    'limit_title'       => 'Maximum Limit Reached!',
    'limit_text'        => 'Your VIP privilege allows you to add a maximum of {limit} products.',
    
    'save_success_title' => 'Saved Successfully!',
    'save_success_text'  => 'Your product has been added.',
    'save_fail_text'    => 'Please fill in all required fields.',
    
    'product_exists_title'=> 'Product already exists!',
    'product_exists_text' => 'You have already added this product to your shop.',
    
    'modal_details'     => 'Product Details',
    'modal_add_list'    => 'Add to your List',
    // ⭐ ข้อความ Product Detail (product_detail.php) ⭐
    'confirm_add_cart'  => 'Click to confirm adding',
    'status_quantity'   => 'Quantity',
    'status_stock'      => 'Stock',
    'status_shop'       => 'Shop',
    'status_no_info'    => 'No information',
    'status_comment'    => 'Comments',
    'status_rate'       => 'Rate',
    'rate_percent'      => '100%',
    'add_to_cart_btn'   => 'Add To Cart',
    'related_products'  => 'Related Products',
    // ⭐ ข้อความ Cart Page (ตะกร้าสินค้า) ⭐
    'cart_title'          => 'Shopping Cart',
    'cart_items_count'    => 'items',
    'cart_remove_btn'     => 'Remove',
    'cart_back_to_shop'   => 'Back to shop',
    'cart_summary'        => 'Order Summary',
    'summary_items_label' => 'Items in the cart', // 'Items in the cart X'
    'summary_total_price' => 'Total Price',
    'checkout_btn'        => 'Checkout',

    // ⭐ ข้อความ Form เช็คเอาท์ ⭐
    'form_name_placeholder'   => 'Name',
    'form_phone_placeholder'  => 'Phone number',
    'form_address_placeholder'=> 'Shipping address',
    'form_distric_placeholder'=> 'District',
    'form_password_placeholder'=> 'Enter code',
    
    // ⭐ ข้อความตรวจสอบรหัสผ่าน (JS/CSS) ⭐
    'check_password_btn'  => 'Check Password',
    'password_correct'    => 'Code correct!',
    'password_incorrect'  => 'Code incorrect!',
    
    // ⭐ ข้อความ Alert ⭐
    'alert_not_enough_stock'=> 'Not enough stock in the system.',
    // ⭐ ข้อความ Change Password (change_password.php) ⭐
    'change_pass_title'         => '🔑 Change Password',
    'pass_mismatch_error'       => 'New passwords do not match',
    'pass_old_placeholder'      => 'Old Password',
    'pass_new_placeholder'      => 'New Password',
    'pass_confirm_placeholder'  => 'Confirm New Password',
    'change_pass_success'       => 'Password changed successfully',
    'change_pass_fail'          => 'Failed to change password. Please try again.',
    'change_pass_btn'           => 'Save Changes',
    // ⭐ ข้อความ Chat Page ⭐
    'chat_placeholder'        => 'Type a message...',
    'chat_no_partner'         => 'Please select a chat partner',
    'chat_alert_empty'        => 'Please type a message or select a file!',
    'chat_alert_no_receiver'  => 'Chat partner not found',
    'chat_alert_file_type'    => 'Unsupported file type (Only JPG, PNG, and PDF are allowed)',
    // ⭐ ข้อความ Edit Profile (edit_profiles.php) ⭐
    'update_success_img'    => 'Data updated successfully with image!',
    'update_success_no_img' => 'Data updated successfully!',
    'update_file_error'     => 'File upload failed or file type is unsupported!',
    'update_save_error'     => 'Error occurred while saving data!',
    'input_mobile_placeholder'=> 'Mobile Phone',
    'input_username_label'  => '*Username',
    'input_username_placeholder'=> 'Username',
    'input_address_label'   => '*Address',
    'input_address_placeholder'=> 'Address',
    'input_distric_label'   => '*District',
    'input_distric_placeholder'=> 'District',
    'input_province_label'  => '*Province',
    'input_province_placeholder'=> 'Province',
    'input_last_login_label'=> '*Last Login',
    'input_last_login_placeholder'=> 'Last Login',
    'save_changes_btn'      => 'Save',
    // ⭐ ข้อความ Forgot Password (forgot.php) ⭐
    'forgot_title'              => 'Forgot Password',
    'reset_title'               => 'Reset Password',
    'mobile_placeholder'        => 'Mobile Phone',
    'confirm_btn'               => 'Confirm',
    'new_pass_placeholder'      => 'New Password',
    'confirm_pass_placeholder'  => 'Confirm',
    'change_pass_btn'           => 'Change Password',
    
    // ⭐ ข้อความแจ้งเตือน (SweetAlert/JS) ⭐
    'js_pass_min_length'        => 'Password must be at least 6 digits!',
    'pass_mismatch_alert'       => 'Passwords do not match. Please check and try again!',
    'reset_success'             => 'Password reset complete!',
    'mobile_not_found'          => 'Phone number not found!',
    // ⭐ ข้อความ Login Page (login.php) ⭐
    'login_header'              => 'Sign in with your account',
    'username_placeholder'      => 'Username',
    'password_placeholder'      => 'Password',
    'login_btn'                 => 'Sign In',
    'link_register'             => 'Register',
    'link_forgot'               => 'Forgot Password',

    // ⭐ ข้อความแจ้งเตือน Login (SweetAlert) ⭐
    'alert_login_success'       => 'Login successful!',
    'alert_login_update_fail'   => 'Login successful but status update failed!',
    'alert_login_fail'          => 'Username or password not found!',
    // ⭐ ข้อความ Market Registration (market.php) ⭐
    'market_join_title'         => 'Join Our Shop',
    'market_mobile_label'       => '*Mobile Phone',
    'market_mobile_placeholder' => 'Mobile Phone',
    'market_store_label'        => '*Shop Name',
    'market_store_placeholder'  => 'Shop Name',
    'market_fname_label'        => '*First Name',
    'market_fname_placeholder'  => 'First Name',
    'market_lname_label'        => '*Last Name',
    'market_lname_placeholder'  => 'Last Name',
    'market_open_btn'           => 'Open Shop',
    'market_open_success'       => 'Shop opened successfully!',
    // ⭐ ข้อความ Dashboard Cards (Metrics) ⭐
    'metric_wallet_balance'     => 'Wallet Balance',
    'metric_total_sales'        => 'Total Sales',
    'metric_total_profit'       => 'Total Profit',
    'metric_pending_order'      => 'Pending Orders',
    'metric_income_today'       => 'Income Today',
    'metric_expense_today'      => 'Expense Today',
    // ⭐ ข้อความ My Orders / Order List ⭐
    'my_sales_list_title'       => 'My Sales List',
    'order_qty_label'           => 'Quantity',
    // ⭐ ข้อความ Payment Confirmation (pay_confirm.php) ⭐
    'payment_back'              => 'Go Back',
    'payment_alert_balance'     => '❌ Insufficient balance. Please top up first.',
    
    // รายละเอียดก่อนชำระ
    'payment_qty_label'         => 'Product Quantity',
    'payment_price_label'       => 'Product Price',
    'payment_profit_label'      => 'Profit',
    'payment_total_pay_label'   => 'Actual Amount Due',
    'payment_buyer_label'       => 'Buyer',
    'payment_address_label'     => 'Address',
    'payment_wallet_label'      => 'Wallet Balance',
    'payment_btn_pay'           => '💳 Pay Now',
    'payment_btn_topup'         => 'Top Up First',
    
    // ใบเสร็จ (Receipt)
    'receipt_title'             => '🧾 Payment Receipt',
    'receipt_order_id'          => 'Order ID',
    'receipt_product_name'      => 'Product',
    'receipt_qty_unit'          => 'pcs',
    'receipt_paid_by'           => 'Payer',
    'receipt_shipping_address'  => 'Shipping Address',
    'receipt_remaining_balance' => 'Remaining Wallet Balance',
    'receipt_btn_done'          => '✅ Done',
    'receipt_btn_print'         => '🖨 Print Receipt',
    // ⭐ ข้อความ Invoice / Money Log (order_today.php) ⭐
    'invoice_title'             => 'Invoice: All Orders List',
    'invoice_date_label'        => 'Date',
    'invoice_qty_unit'          => 'Quantity',
    'invoice_unit_price'        => 'Price/Unit',
    'invoice_time_label'        => 'Time',
    'invoice_subtotal'          => 'Subtotal',
    'invoice_profit'            => 'Profit',
    'invoice_daily_total'       => 'Daily Total Sales',
    'invoice_daily_profit'      => 'Daily Total Profit',
    'invoice_grand_total'       => 'Grand Total Sales',
    'invoice_grand_profit'      => 'Grand Total Profit',
    'invoice_no_order'          => 'No orders available',
    // ⭐ ข้อความ Payment/Top-up (payment.php) ⭐
    'payment_form_title'        => 'Payment',
    'payment_alert_upload_fail' => '❌ Failed to upload slip. Check permissions for the \'uploads/slip/\' folder.',
    'payment_alert_success'     => 'Top-up request sent. Waiting for review.',
    'payment_amount_label'      => 'Amount Due',
    'payment_amount_placeholder'=> '00.00',
    'payment_method_label'      => 'Select Payment Channel',
    'payment_copy_btn'          => '📋 Copy',
    'payment_copied_btn'        => '✅ Copied',
    'payment_upload_label'      => 'Upload Transfer Slip',
    'payment_slip_preview'      => 'Slip Preview',
    'payment_confirm_btn'       => 'Confirm Payment',

    // ⭐ ประวัติการชำระเงิน ⭐
    'payment_history_title'     => 'Payment History',
    'history_status_0'          => 'Pending',
    'history_status_1'          => 'Success',
    'history_status_2'          => 'Canceled',
    'history_amount_label'      => 'Amount',
    'history_date_label'        => 'Date',
    'history_no_record'         => 'No payment history yet',
    // ⭐ ข้อความ Registration Page (registor.php) ⭐
    'reg_tab_mobile'            => 'Mobile Phone',
    'reg_tab_email'             => 'Email',
    'reg_mobile_placeholder'    => 'Mobile Phone',
    'reg_name_label'            => '*Name',
    'reg_name_placeholder'      => 'Name',
    'reg_password_label'        => '*Password',
    'reg_password_placeholder'  => 'Password',
    'reg_confirm_label'         => '*Confirm',
    'reg_confirm_placeholder'   => 'Confirm',
    'reg_btn'                   => 'Register',
    'reg_link_login'            => 'Sign In',
    'reg_terms'                 => '✔ I confirm the "Terms and Conditions"',

    // ⭐ ข้อความแจ้งเตือน Registration (SweetAlert/JS) ⭐
    'reg_js_pass_min'           => 'Password must be at least 6 digits!',
    'reg_pass_mismatch_alert'   => 'Passwords do not match. Please check and try again!',
    'reg_username_taken'        => 'This username is already taken. Please choose another!',
    'reg_success'               => 'Registration successful!',
    'reg_fill_all_fields'       => 'Please fill in all fields and try again.',
    // ⭐ ข้อความ Invoice (invoice.php) ⭐
    'invoice_header_title'      => 'Invoice',
    'invoice_order_number'      => 'Order Number',
    'invoice_date'              => 'Date',
    'invoice_customer_info'     => 'Customer Information',
    'invoice_customer_name'     => 'Customer Name',
    'invoice_customer_address'  => 'Address',
    'invoice_customer_phone'    => 'Phone',
    'invoice_seller'            => 'Seller',
    'invoice_seller_name'       => 'TKShop',
    'invoice_seller_address'    => 'Bangkok',
    'invoice_qty_label_short'   => 'Qty',
    'invoice_unit_price_label'  => 'Price/Unit',
    'invoice_subtotal_short'    => 'Total',
    'invoice_profit_short'      => 'Profit',
    'invoice_pay_short'         => 'Actual Payment',
    'invoice_summary_title'     => 'Total Summary',
    'invoice_total_sales'       => 'Total Sales',
    'invoice_total_profit'      => 'Total Profit',
    'invoice_total_pay'         => 'Total Payment',
    'invoice_btn_back'          => 'Go back to Orders',
    // ⭐ ข้อความ Withdraw Page (withdraw.php) ⭐
    'withdraw_title'            => 'Withdraw Funds',
    'withdraw_balance_label'    => '💰 Wallet Balance',
    'withdraw_amount_label'     => 'Amount to Withdraw',
    'withdraw_btn_confirm'      => 'Confirm Withdrawal',

    // ⭐ แจ้งเตือน/ข้อความสถานะ ⭐
    'alert_amount_invalid'      => '❌ Please enter a valid amount',
    'alert_insufficient_funds'  => '❌ Insufficient funds',
    'alert_request_pending'     => '⏳ Your withdrawal request is pending admin approval',
    'alert_has_pending'         => '⏳ You have a pending withdrawal request. Please wait for admin review.',
    
    // ⭐ ประวัติการถอน ⭐
    'withdraw_history_title'    => '📜 Your Withdrawal History',
    // ⭐ สถานะในตาราง ⭐
    'status_pending_badge'      => 'Pending',
    'status_approved_badge'     => 'Approved',
    'status_rejected_badge'     => 'Rejected',
    'table_header_amount'       => 'Amount',
    'table_header_status'       => 'Status',
    'table_header_date_req'     => 'Date Requested',
    'table_header_date_proc'    => 'Date Processed',
    'table_no_history'          => '- No withdrawal history yet -',

    'payment_upload_btn' => '📁 Choose Slip',

];

// ... สามารถเพิ่มภาษาอื่น ๆ ได้ที่นี่ ...

?>