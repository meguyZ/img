<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="logo.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OVEZ CLOUD | กฏการใช้เว็บ</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* เพิ่มสไตล์ในส่วนนี้เพื่อซ่อนหน้าต่างของ rules และกำหนดขนาด */
        body {
            overflow: hidden;
        }

        .popup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* สีพื้นหลังของ overlay */
            z-index: 999;
        }

        .rules-popup {
            background-color: #fff; /* สีพื้นหลังของ popup */
            padding: 20px;
            border-radius: 10px;
            max-width: 80%; /* กำหนดความกว้างของ popup */
            max-height: 80%; /* กำหนดความสูงของ popup */
            overflow-y: auto;
        }

        .close-popup {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;

            /* ในไฟล์ style.css */
.back-to-index {
    display: block;
    margin-top: 10px;
    padding: 10px;
    text-align: center;
    background-color: #3498db;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
}

.back-to-index:hover {
    background-color: #2980b9;
}

        }
    </style>
</head>
<body class="rules-page">
    <div class="popup-container">
<!-- ในส่วนของ .rules-popup ในไฟล์ rules.php -->
<div class="rules-popup">
    <span class="close-popup" onclick="closePopup()">&times;</span>
    <img src="logo.png" alt="Logo" class="logo">
    <h1 id="dynamic-text" class="rules-title"></h1>

    <div class="rules-text">
        <p>1. ห้ามอัปโหลดภาพที่มีเนื้อหาที่ไม่เหมาะสม หรือละเมิดลิขสิทธิ์</p>
        <p>2. ให้ใช้บริการนี้อย่างรับผิดชอบ</p>
        <p>3. หากกระทำผิดกฏ ทางเราจะทำการแบน</p>
        <!-- เพิ่มกฏอื่น ๆ ตามต้องการ -->
    </div>

    <!-- เพิ่มปุ่มย้อนกลับ -->
    <a href="index.php" class="back-to-index">ย้อนกลับ</a>
</div>

</div>
        </div>
    </div>
    <footer>
        &copy; 2024 • <a href="http://www.ovez.shop">OVEZ CLOUD</a>❤️ สงวนลิขสิทธิ์ทุกประการ
    </footer>

    <!-- เพิ่มโค้ด JavaScript เพื่อป้องกันการรีเฟรชเว็บไซต์ -->
    <script>
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
        });

        document.addEventListener('keydown', function (e) {
            // ป้องกันการใช้งาน Ctrl+S (หรือ Command+S ใน macOS)
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
            }

            // ป้องกันการใช้งาน F12
            if (e.key === 'F12') {
                e.preventDefault();
            }
        });

        // เพิ่มฟังก์ชันเพื่อปิด popup
        function closePopup() {
            document.body.style.overflow = 'auto';
            document.querySelector('.popup-container').style.display = 'none';
        }

        // แสดง popup เมื่อโหลดหน้าเว็บ
        window.onload = function () {
            document.body.style.overflow = 'hidden';
            document.querySelector('.popup-container').style.display = 'flex';
        };
    </script>
</body>
</html>
