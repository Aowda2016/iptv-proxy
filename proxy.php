<?php
// التحقق من وجود رابط مشفر
if (!isset($_GET['url'])) {
    http_response_code(400);
    die("❌ مفقود الرابط (url)");
}

// فك تشفير الرابط
$url = base64_decode($_GET['url']);

// رؤوس http مهمة جدًا لتجاوز الحماية
$headers = [
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/122 Safari/537.36",
    "Referer: https://google.com",
    "Origin: https://google.com",
    "Accept: */*"
];

// إعداد CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // مهم لإعادة التوجيه
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // لتجاوز SSL errors
curl_setopt($ch, CURLOPT_HEADER, false);

// تنفيذ الطلب
$response = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: "application/vnd.apple.mpegurl";
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// إعادة التوجيه للرأس الصحيح
header("Access-Control-Allow-Origin: *");
header("Content-Type: " . $contentType);

// عرض النتيجة
if ($httpCode === 200) {
    echo $response;
} else {
    http_response_code($httpCode);
    echo "⚠️ تعذر جلب البث. (رمز: $httpCode)";
}
