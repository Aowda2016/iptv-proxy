<?php
// بيانات Xtream API
$host = "http://tera4k.com:8880";
$username = "34075602712993";
$password = "25195250247729";

// استقبال معرف القناة من الرابط (مثال: watch.php?stream=123)
$stream_id = isset($_GET['stream']) ? $_GET['stream'] : null;

// تحقق من وجود stream_id
if (!$stream_id) {
    die("Stream ID not found.");
}

// إنشاء رابط البث من Xtream API
$stream_url = "$host/live/$username/$password/$stream_id.ts";

// تشفير الرابط باستخدام base64
$encoded_url = base64_encode($stream_url);

// توليد الرابط النهائي إلى proxy.php
$final_url = "proxy.php?url=" . urlencode($encoded_url);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>مشاهدة البث المباشر</title>
  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
  <style>
    body { margin: 0; background-color: #000; display: flex; justify-content: center; align-items: center; height: 100vh; }
    video { width: 100%; max-width: 1000px; height: auto; border-radius: 12px; }
  </style>
</head>
<body>
  <video id="video" controls autoplay></video>

  <script>
    const video = document.getElementById('video');
    const videoSrc = "<?php echo $final_url; ?>";

    if (Hls.isSupported()) {
      const hls = new Hls();
      hls.loadSource(videoSrc);
      hls.attachMedia(video);
      hls.on(Hls.Events.MANIFEST_PARSED, function () {
        video.play();
      });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
      video.src = videoSrc;
      video.addEventListener('loadedmetadata', function () {
        video.play();
      });
    } else {
      alert("متصفحك لا يدعم البث المباشر.");
    }
  </script>
</body>
</html>
