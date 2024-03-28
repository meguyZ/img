<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="logo.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OVEZ CLOUD | IP ของคุณถูกแบน</title>
    <link rel="stylesheet" href="ban.css">
</head>
<body class="ban-page">
    <div class="ban-container">
        <img src="logo.png" alt="Logo" class="logo">
        <h1 class="ban-title">IP ของคุณถูกแบน</h1>
        <p class="ban-text">เนื่องจากการกระทำผิดข้อกำหนดการใช้งาน</p>
        <p class="ban-text">โปรดติดต่อแอดมิน เพื่อปลดแบน</p>
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
    </script>
</body>
</html>
