<?php
// ดักจับข้อผิดพลาดและแสดงข้อมูลการเกิดข้อผิดพลาดทั้งหมด
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ตัวอย่างฟังก์ชัน getHWID() (คำนวณ HWID ของผู้ใช้)
function getHWID() {
    // ตัวอย่างโค้ดที่ควรจะให้ค่า HWID ของผู้ใช้
    // สามารถใช้รายละเอียดของเครื่องหรือข้อมูลอื่น ๆ ที่ไม่ซ้ำกัน
    // ในกรณีนี้ให้ใช้ session_id() เป็นตัวอย่าง
    return md5(session_id());
}

$hwid = getHWID();
$hwidBanFilePath = 'log_hwid_ban.txt';

// ตรวจสอบว่าไฟล์ "log_hwid_ban.txt" มีหรือไม่
if (!file_exists($hwidBanFilePath)) {
    // หากไม่มีให้สร้างไฟล์เปล่า ๆ
    file_put_contents($hwidBanFilePath, "");
}

// อ่านรายการที่ถูกแบนจากไฟล์
$hwidBanList = file($hwidBanFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// ตรวจสอบว่า HWID ถูกแบนหรือไม่
if (in_array($hwid, $hwidBanList)) {
    header("Location: ban.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="https://ovez.shop/logo.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OVEZ CLOUD | แปลงภาพเป็นลิ้งฟรี!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="index-page">
    <div class="index-container">
        <img src="https://ovez.shop/logo.png" alt="Logo" class="logo">
        <h1 id="dynamic-text" class="index-title"></h1>

        <!-- เพิ่มฟอร์มอัพโหลดรูปภาพ -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="image" class="label-for-file">เลือกรูปภาพ</label>
            <div class="file-input-container">
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>
            <input type="submit" value="อัปโหลด">
            <input type="hidden" name="hwid" value="<?php echo $hwid; ?>">
            <p class="upload-text">⚠️ <a href='rules.php'>กฏการใช้งาน (อ่านก่อนใช้งาน)</a></p>
            <p class="upload-text">✅ <a href='https://www.facebook.com/profile.php?id=61555838225147'>ปลดล็อคขีดจำกัดต่อวัน ซื้อพรีเมี่ยมได้ที่เพจ</a></p>
        </form>
    </div>
    <footer>
        &copy; 2024 • <a href="http://www.ovez.shop">OVEZ CLOUD</a>❤️ สงวนลิขสิทธิ์ทุกประการ
    </footer>

    <!-- เพิ่มโค้ด JavaScript เพื่อทำให้ข้อความขึ้นทีละตัวอักษร -->
    <script>
        const dynamicText = document.getElementById('dynamic-text');
        const originalText = 'แปลงภาพเป็นลิ้งฟรี!';
        let index = 0;

        function displayText() {
            dynamicText.textContent = originalText.substring(0, index++);
            if (index <= originalText.length) {
                setTimeout(displayText, 150); // เพิ่มความล่าช้าตรงนี้
            }
        }

        displayText();
    </script>
<!-- เพิ่มโค้ด JavaScript เพื่อป้องกันการรีเฟรชเว็บไซต์ -->
<script>
    document.addEventListener('keydown', function (e) {
        // ป้องกันการใช้งาน Ctrl+S (หรือ Command+S ใน macOS)
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
        }

        // ป้องกันการใช้งาน F12
        if (e.key === 'F12') {
            e.preventDefault();
        }

        // ป้องกันการใช้งาน Ctrl+ฏ
        if ((e.ctrlKey || e.metaKey) && e.key === 'ฏ') {
            e.preventDefault();
        }

        // ป้องกันการใช้งาน Ctrl+ก
        if ((e.ctrlKey || e.metaKey) && e.key === 'ก') {
            e.preventDefault();
        }
    });
</script>

</body>
</html>
