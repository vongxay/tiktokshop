<!-- Product Infinite Slider Section -->
<?php
$db = connect();
$stmt = $db->query("SELECT product_id, name, img_name, price FROM tb_product WHERE img_name IS NOT NULL AND img_name != '' ORDER BY RAND() LIMIT 15");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalProducts = count($products);
?>

<div class="product-slider-container">
    <div class="product-slider-wrapper">
        <div class="product-slider-track" id="productSliderTrack">
            <?php 
            // สร้าง items สำหรับ infinite loop (เพิ่ม 3 รูปแรกไว้ท้าย และ 3 รูปท้ายไว้หน้า)
            $cloneFirst = array_slice($products, 0, 3);
            $cloneLast = array_slice($products, -3);
            $allProducts = array_merge($cloneLast, $products, $cloneFirst);
            
            foreach ($allProducts as $index => $product): 
            ?>
            <a href="?page=product_detail&id=<?php echo $product['product_id']; ?>" class="product-circle-item">
                <div class="product-circle-img">
                    <img src="uploads/<?php echo htmlspecialchars($product['img_name']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         loading="lazy">
                </div>
                <p class="product-circle-price">$<?php echo number_format($product['price'], 2); ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
/* Product Slider Container */
.product-slider-container {
    margin: 15px 10px;
    padding: 20px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.product-slider-wrapper {
    overflow: hidden;
    position: relative;
    padding: 0 5px;
}

.product-slider-track {
    display: flex;
    transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}

/* Product Circle Item */
.product-circle-item {
    flex: 0 0 calc(100% / 3);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    transition: transform 0.3s ease;
    padding: 5px;
    box-sizing: border-box;
}

.product-circle-item:hover {
    transform: scale(1.05);
}

.product-circle-img {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    background: #fff;
    transition: all 0.3s ease;
}

.product-circle-img:hover {
    border-color: #ff6b6b;
    box-shadow: 0 6px 20px rgba(255,107,107,0.3);
}

.product-circle-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-circle-price {
    margin-top: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #e74c3c;
    text-align: center;
}

/* Responsive */
@media (max-width: 480px) {
    .product-circle-img {
        width: 75px;
        height: 75px;
    }
    
    .product-circle-price {
        font-size: 11px;
    }
}

@media (max-width: 360px) {
    .product-circle-img {
        width: 65px;
        height: 65px;
    }
    
    .product-circle-price {
        font-size: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('productSliderTrack');
    const items = track.querySelectorAll('.product-circle-item');
    const totalItems = <?php echo $totalProducts; ?>; // จำนวนสินค้าจริง
    const cloneCount = 3; // จำนวนที่ clone ไว้หน้าและหลัง
    const itemWidth = 100 / 3; // แต่ละ item กว้าง 1/3 ของ container
    
    let currentIndex = cloneCount; // เริ่มที่ตำแหน่งหลัง clone
    let isTransitioning = false;
    
    // ตั้งตำแหน่งเริ่มต้น (ไม่มี transition)
    track.style.transition = 'none';
    track.style.transform = `translateX(-${currentIndex * itemWidth}%)`;
    
    // Force reflow
    track.offsetHeight;
    
    // เปิด transition กลับ
    track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    
    function slideNext() {
        if (isTransitioning) return;
        isTransitioning = true;
        currentIndex++;
        track.style.transform = `translateX(-${currentIndex * itemWidth}%)`;
    }
    
    function slidePrev() {
        if (isTransitioning) return;
        isTransitioning = true;
        currentIndex--;
        track.style.transform = `translateX(-${currentIndex * itemWidth}%)`;
    }
    
    // Handle infinite loop - เมื่อ transition จบ
    track.addEventListener('transitionend', () => {
        isTransitioning = false;
        
        // ถ้าเลื่อนไปถึง clone ท้าย ให้กระโดดกลับไปต้น
        if (currentIndex >= totalItems + cloneCount) {
            track.style.transition = 'none';
            currentIndex = cloneCount;
            track.style.transform = `translateX(-${currentIndex * itemWidth}%)`;
            track.offsetHeight; // Force reflow
            track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        }
        
        // ถ้าเลื่อนกลับไปถึง clone หน้า ให้กระโดดไปท้าย
        if (currentIndex < cloneCount) {
            track.style.transition = 'none';
            currentIndex = totalItems + cloneCount - 1;
            track.style.transform = `translateX(-${currentIndex * itemWidth}%)`;
            track.offsetHeight; // Force reflow
            track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        }
    });
    
    // Auto slide every 3 seconds
    let autoSlide = setInterval(slideNext, 3000);
    
    // Pause on hover
    track.addEventListener('mouseenter', () => {
        clearInterval(autoSlide);
    });
    
    track.addEventListener('mouseleave', () => {
        autoSlide = setInterval(slideNext, 3000);
    });
    
    // Touch/Swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        clearInterval(autoSlide);
    }, {passive: true});
    
    track.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
        autoSlide = setInterval(slideNext, 3000);
    }, {passive: true});
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                slideNext();
            } else {
                slidePrev();
            }
        }
    }
});
</script>
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