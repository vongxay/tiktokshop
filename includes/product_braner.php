<div class="promo-list" style="display:flex; flex-direction:column; gap:20px; margin:20px 0;">
    <div style="position:relative; border-radius:12px; overflow:hidden;">
        <img src="img/branner/br03.png" class="img-fluid"
            style="width:100%; height:auto; object-fit:cover; filter:brightness(70%);">
        <div style="position:absolute; top:50%; right:10px; transform:translateY(-50%);
            max-width:320px; text-align:right;
            border-radius:10px; padding:12px;">
            <h5 style="color:#fff; font-weight:bold; font-size:18px; text-shadow:1px 1px 4px rgba(0,0,0,0.6);">
                <?php echo $T['promo_women_title']; ?>
            </h5>
            <p style="color:#fff; font-size:13px; margin:4px 0; line-height:1.4; text-shadow:1px 1px 3px rgba(0,0,0,0.5);">
                <?php echo $T['promo_women_desc']; ?>
            </p>
            <a href="?page=product&cat=women"
                style="color:#fff; border:none; border-radius:20px; padding:6px 15px; font-size:13px; font-weight:bold; text-decoration:none;">
                <?php echo $T['promo_women_btn']; ?>
            </a>
        </div>

    </div>

    <div style="position:relative; border-radius:12px; overflow:hidden;">
        <img src="img/branner/br02.png" class="img-fluid"
            style="width:100%; height:auto; object-fit:cover; filter:brightness(70%);">
        <div style="position:absolute; top:50%; left:10px; transform:translateY(-50%);
            max-width:320px; text-align:left; padding:12px;">
            <h5 style="color:#fff; font-weight:bold; font-size:18px; text-shadow:1px 1px 4px rgba(0,0,0,0.6);">
                <?php echo $T['promo_men_title']; ?>
            </h5>
            <p style="color:#fff; font-size:13px; margin:4px 0; line-height:1.4; text-shadow:1px 1px 3px rgba(0,0,0,0.5);">
                <?php echo $T['promo_men_desc']; ?>
            </p>
            <a href="?page=product&cat=men"
                style="color:#fff; border:none; border-radius:20px; padding:6px 15px; font-size:13px; font-weight:bold; text-decoration:none;">
                <?php echo $T['promo_men_btn']; ?>
            </a>
        </div>

    </div>

    <div style="position:relative; border-radius:12px; overflow:hidden;">
        <img src="img/branner/br01.png" class="img-fluid"
            style="width:100%; height:auto; object-fit:cover; filter:brightness(70%);">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);
                max-width:320px; text-align:center; 
                border-radius:10px; padding:12px;">
            <h5 style="color:#FFD700; font-weight:bold; font-size:18px; text-shadow:1px 1px 4px rgba(0,0,0,0.6);">
                <?php echo $T['promo_all_title']; ?>
            </h5>
            <p style="color:#fff; font-size:13px; margin:4px 0; line-height:1.4; text-shadow:1px 1px 3px rgba(0,0,0,0.5);">
                <?php echo $T['promo_all_desc']; ?>
            </p>
            <a href="?page=product"
                style="color:#fff; border:none; border-radius:20px; padding:6px 15px; font-size:13px; font-weight:bold; text-decoration:none;">
                <?php echo $T['promo_all_btn']; ?>
            </a>
        </div>
    </div>

</div>
<style>
    /* Animation fade-in + slide up */
    @keyframes fadeSlideUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ใส่ให้ทุกสไลด์ */
    .promo-list>div {
        animation: fadeSlideUp 0.8s ease forwards;
    }

    /* เพิ่ม delay ให้แต่ละสไลด์เรียงกัน */
    .promo-list>div:nth-child(1) {
        animation-delay: 0.2s;
    }

    .promo-list>div:nth-child(2) {
        animation-delay: 0.4s;
    }

    .promo-list>div:nth-child(3) {
        animation-delay: 0.6s;
    }

    /* Animation สำหรับ product list */
    .product-list .product {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeSlideUp 0.6s ease forwards;
    }

    /* เพิ่ม delay ให้แต่ละสินค้าเรียงกัน */
    .product-list .product:nth-child(n) {
        animation-delay: calc(0.05s * var(--i));
    }
</style>

<script>
    // กำหนดค่า --i ให้แต่ละ product
    document.addEventListener("DOMContentLoaded", function() {
        const products = document.querySelectorAll('.product-list .product');
        products.forEach((el, i) => {
            el.style.setProperty('--i', i + 1);
        });
    });
</script>