<div class="container" style="margin-top: 50px;">

<div id="tiktok-videos"></div>

<script>
    // รายการลิงก์วิดีโอ TikTok
    const tiktokLinks = [
        "https://www.tiktok.com/@filmffy/video/7421948803361541394",
        "https://www.tiktok.com/@filmffy/video/7440362553659772178",
        "https://www.tiktok.com/@filmffy/video/7381123895413181716",
        "https://www.tiktok.com/@nekomeowly/video/7245301075123637510",
        "https://www.tiktok.com/@nekomeowly/video/7228228774905515270",
        "https://www.tiktok.com/@filmffy/video/7452007494223809800",
        "https://www.tiktok.com/@zeeni.ootd/video/7263410892555980038",
        "https://www.tiktok.com/@taataa_d/video/7392107222286765329",
        "https://www.tiktok.com/@wilaiwan_mj/video/7159005904900230426",
        "https://www.tiktok.com/@filmffy/video/7456084522203958536",
        "https://www.tiktok.com/@maisy.clothing/video/7192487090053975322",

        "https://www.tiktok.com/@milkbiancakes/video/7384790473551908101?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@styleme.kh/video/7269404684102814977?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@banhgao.nho/video/7319453554983390482?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@wareviews/video/7309785845970603272?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@dianpebrylaa/video/7297919930882706694?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@createdpinkk/video/7413361141151190280?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@mystiicmeep/video/7365931030471052586?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@picchagaming/video/7277373131684138246?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@enhypen/video/7393221546900196625?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@zel.kleyr/video/7242175270797561093?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@yeuzvi/video/7415037136866381098?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",

        "https://www.tiktok.com/@tiemgiayboot.vn/video/7319856785416899842?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@catkin.floss/video/7383955004517846277?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@fanginta0440/video/7392100813910461714?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@thewatchbyo.lim/video/7396955045532585233?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@kooklyk/video/7446911563308600582?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@cherieekh/video/7456659853088410898?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",


        "https://www.tiktok.com/@shoesdiary3566/video/7426228778977774856?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@littlesoulmate_/video/7423035914709978376",

        "https://www.tiktok.com/@chie_diary/video/7274367411120393514?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@chibestgovap/video/7433385412699114759?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@kassandrascloset/video/7230738649090903302?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@aylaa_horshop/video/7409216671652597010?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@ynfashionshop1/video/7407717264918580498?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@jennyfashionhousee/video/7424162945501990145?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@lushy._shopp/video/7353475051275980040?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@moy_outfit/video/7238825709056183557?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@lushy._shopp/video/7428813705724333330?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@imiew_mobiles89/video/7419245346502741266?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@brandnametoday/video/7315027861155482886?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@bagnifique.megabangna/video/7360206355468176648?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@thepolishedperceptions/video/7327492980120636677?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@nariwatch/video/7372893371494370565?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@mrvoproskin/video/7370005248963857697?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@jastipkoreaastri08/video/7410300149982924039?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@milkmaewww/video/7353149967017512200?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@bovi.viphada/video/7419953736480476434?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@style.maew/video/7351837637180738834?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114",
        "https://www.tiktok.com/@jookstogo/video/6932921216353225985?is_from_webapp=1&sender_device=pc&web_id=7463098414155204114"
        
    ];

      // สุ่มลำดับวิดีโอในอาร์เรย์
      const shuffledLinks = tiktokLinks.sort(() => Math.random() - 0.5);

// แสดงวิดีโอใน div#tiktok-videos
const videoContainer = document.getElementById('tiktok-videos');
const iframeElements = [];

shuffledLinks.forEach(link => {
    // ใช้ Regular Expression ดึง video id
    const videoIdMatch = link.match(/\/video\/(\d+)/);
    if (videoIdMatch && videoIdMatch[1]) {
        const videoId = videoIdMatch[1];

        const iframe = document.createElement('iframe');
        iframe.src = `https://www.tiktok.com/embed/v2/${videoId}?autoplay=1&loop=1`;
        iframe.width = "100%";
        iframe.height = "790";
        iframe.style.border = "none";
        iframe.style.outline = "none"; // ลบเส้นขอบเมื่อคลิก
        iframe.style.boxShadow = "none"; // ลบเงา
        iframe.allow = "autoplay";
        iframe.allowFullscreen = true;

        // ฟังก์ชันที่ใช้เมื่อวิดีโอเล่นจบ
        iframe.onload = () => {
            const style = document.createElement('style');
            style.innerHTML = `
                .tiktok-embed-div > a {
                    display: none !important; /* ซ่อนลิงก์ "Watch Now" */
                }
            `;
            document.head.appendChild(style);
        };

        // เพิ่ม iframe ลงในอาร์เรย์
        iframeElements.push(iframe);

        // ตรวจจับเหตุการณ์ ended เพื่อสลับลำดับเมื่อวิดีโอเล่นจบ
        iframe.addEventListener('load', () => {
            iframe.contentWindow.document.addEventListener('ended', () => {
                // เมื่อวิดีโอเล่นจบให้สลับตำแหน่ง
                const nextIframe = iframeElements.shift(); // เอาวิดีโอที่เล่นเสร็จออกจากลิสต์
                iframeElements.push(nextIframe); // เพิ่มไปยังท้ายรายการ
                videoContainer.innerHTML = ''; // ล้าง div ก่อน
                iframeElements.forEach(iframe => videoContainer.appendChild(iframe)); // แสดงวิดีโอใหม่ตามลำดับ
            });
        });

        videoContainer.appendChild(iframe);
    }
});
</script>

</div>