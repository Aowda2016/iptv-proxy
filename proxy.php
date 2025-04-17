<?php
// السماح بالوصول من أي مصدر
header("Access-Control-Allow-Origin: *");

// التحقق من وجود الرابط المشفر
if (!isset($_GET['url'])) {
    http_response_code(400);
    die("Missing URL");
}

// فك تشفير الرابط من base64
$decoded_url = base64_decode($_GET['url']);

// التحقق من أن الرابط يبدأ بـ http
if (!preg_match('/^https?:\/\//', $decoded_url)) {
    http_response_code(400);
    die("Invalid URL");
}

// إعداد الطلب بـ cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $decoded_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// ترويسات وهمية لتخطي الحماية
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
    "Referer: $decoded_url",
    "Origin: *"
]);

// نوع المحتوى حسب امتداد الملف
$ext = pathinfo(parse_url($decoded_url, PHP_URL_PATH), PATHINFO_EXTENSION);
$content_types = [
    "m3u8" => "application/vnd.apple.mpegurl",
    "ts"   => "video/mp2t",
];
if (isset($content_types[$ext])) {
    header("Content-Type: " . $content_types[$ext]);
} else {
    header("Content-Type: application/octet-stream");
}

// تنفيذ الطلب وإظهار النتيجة
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// إرسال النتيجة أو الخطأ
if ($http_code == 200 && $response !== false) {
    echo $response;
} else {
    http_response_code($http_code);
    echo "Error fetching the stream.";
}
