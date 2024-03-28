<link rel="stylesheet" href="upload.css">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = 'uploads/';
    $maxFileSize = 1 * 1024 * 1024; // 1 MB
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    $hwidFilePath = 'log_hwid_ban.txt';
    $banList = file($hwidFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $currentHWID = getHWID();

    $ipAddress = $_SERVER['REMOTE_ADDR']; // เพิ่มบรรทัดนี้

    if (in_array($currentHWID, $banList)) {
        $banReason = file_get_contents("ban_reason.txt");
        echo "ไอควายมึงโดนแบนเพราะ สาเหตุ : $banReason";
        exit();
    }

    $hwidLogFilePath = 'hwid_log.txt';
    $hwidLog = json_decode(file_get_contents($hwidLogFilePath), true);
    $dailyLimit = 5000;

    // เพิ่มส่วนนี้เพื่อตรวจสอบ Limit ตาม IP
    if (!isset($hwidLog[$ipAddress])) {
        $hwidLog[$ipAddress] = 0;
    }

    if ($hwidLog[$ipAddress] >= $dailyLimit) {
        echo "Error: IP Address has exceeded the daily limit of $dailyLimit images.";
        exit();
    }

    $currentHWIDCount = isset($hwidLog[$currentHWID]) ? $hwidLog[$currentHWID] : 0;

    if ($currentHWIDCount >= $dailyLimit) {
        echo "Error: HWID has exceeded the daily limit of $dailyLimit images.";
        // เพิ่ม HWID และสาเหตุที่แบนลงใน hwid_ban.txt
        $banReason = "Daily limit exceeded";
        file_put_contents($hwidFilePath, $currentHWID . "|" . $banReason . "\n", FILE_APPEND);
        exit();
    }

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFile = $_FILES['image'];
    $fileType = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));

    if ($uploadedFile['size'] > $maxFileSize) {
        echo "Error: File size exceeds the limit.";
        exit();
    }

    if (!in_array($fileType, $allowedTypes)) {
        echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
        exit();
    }

    $uniqueFileName = 'OVEZ-' . generateRandomString() . '.' . $fileType;

    move_uploaded_file($uploadedFile['tmp_name'], $uploadDir . $uniqueFileName);

    if (isImageNSFW($uploadDir . $uniqueFileName)) {
        unlink($uploadDir . $uniqueFileName);
        echo "Error: Image contains adult content.";
        // เพิ่ม HWID และสาเหตุที่แบนลงใน hwid_ban.txt
        $banReason = "NSFW content";
        file_put_contents($hwidFilePath, $currentHWID . "|" . $banReason . "\n", FILE_APPEND);
        exit();
    }

    compressImage($uploadDir . $uniqueFileName);

    $imageUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $uniqueFileName;

    $discordWebhookURL = 'https://discord.com/api/webhooks/1211004463054192680/X30INf9-gAJ47UexNgf9A8UFVHul7YOL9vCz3AVRFdGZ_MkGoVtR3ha0iHP8jWGSknaF';

    if (!empty($discordWebhookURL)) {
        $time = date('Y-m-d H:i:s');
        $fileSizeFormatted = formatBytes($uploadedFile['size']);
        $imageSize = getimagesize($uploadDir . $uniqueFileName);
        $imageSizeFormatted = $imageSize ? $imageSize[0] . 'x' . $imageSize[1] : 'N/A';

        $embedData = [
            'title' => 'LINK OVEZ | บริการแปลงภาพเป็นลิ้งฟรี!!',
            'url' => 'https://link.ovez.shop/',
            'color' => 47359,
            'fields' => [
                [
                    'name' => "**IP** 🏚️  : || $ipAddress ||",
                    'value' => "**Time** ⏰ : `$time`"
                ],
                [
                    'name' => "Limit⛔",
                    'value' => "[+] `$hwidLog[$ipAddress] / $dailyLimit`"
                ],
                [
                    'name' => "File size 📁       |   Image size 📷",
                    'value' => "[+] `$fileSizeFormatted`   **|** [+] `$imageSizeFormatted`"
                ],
                [
                    'name' => "Link 🔗",
                    'value' => "**[+]**  ` $imageUrl `"
                ],
                [
                    'name' => "HWID 🖥️",
                    'value' => "**[+]**  || $currentHWID ||"
                ]
            ],
            'footer' => [
                'text' => '©2024 OVEZ CLOUD'
            ],
            'image' => [
                'url' => $imageUrl
            ]
        ];

        $postData = [
            'content' => null,
            'embeds' => [$embedData],
            'attachments' => []
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($postData),
            ],
        ];

        $context = stream_context_create($options);
        file_get_contents($discordWebhookURL, false, $context);
    }

    // เพิ่มส่วนนี้เพื่อนับจำนวนตาม IP address
    $hwidLog[$ipAddress]++;
    file_put_contents($hwidLogFilePath, json_encode($hwidLog));

    echo "อัปโหลดรูปภาพสำเร็จ!<br>";
    echo "ลิงค์โดยตรงไปยังรูป: <a href='$imageUrl'>$imageUrl</a>";

    // เพิ่มปุ่มคัดลอกลิงค์
    echo "<br><button onclick='copyToClipboard(\"$imageUrl\")'>คัดลอกลิงค์</button>";
}

function isImageNSFW($imagePath) {
    $imageData = base64_encode(file_get_contents($imagePath));
    $result = shell_exec("node nsfwjs_check.js $imageData");
    return $result === "true";
}

function formatBytes($bytes, $precision = 1) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    return round($bytes / (1 << (10 * $pow)), $precision) . ' ' . $units[$pow];
}

function compressImage($filePath) {
    $image = imagecreatefromstring(file_get_contents($filePath));
    imagejpeg($image, $filePath, 60);
    imagedestroy($image);
}

function generateRandomString($length = 4) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

function getHWID() {
    return hash('sha256', $_SERVER['HTTP_USER_AGENT'] . $_SERVER['COMPUTERNAME'] . $_SERVER['PROCESSOR_IDENTIFIER']);
}
?>
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

    // ฟังก์ชั่นคัดลอกลิงค์
    function copyToClipboard(text) {
        var input = document.createElement('textarea');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        alert('คัดลอกลิงค์แล้ว: ' + text);
    }
</script>
