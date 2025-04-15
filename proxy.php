<?php
if (!isset($_GET['url'])) {
    http_response_code(400);
    echo "Missing 'url' parameter";
    exit;
}

$url = urldecode($_GET['url']);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/vnd.apple.mpegurl");

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
