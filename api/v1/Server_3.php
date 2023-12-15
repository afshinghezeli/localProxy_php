<?php

// File: proxy.php

// تنظیمات کش
define('CACHE_DIR', 'cache/');
define('CACHE_TIME', 60); // زمان کش بر حسب ثانیه

// دریافت درخواست از کلاینت
$method = $_SERVER['REQUEST_METHOD'];
$url = 'https://jsonplaceholder.typicode.com/users';
$headers = getallheaders();
$body = file_get_contents('php://input');

// تولید کلید کش بر اساس ویژگی‌های درخواست
$cacheKey = md5($method . $url . serialize($headers) . $body);
$cacheFile = CACHE_DIR . $cacheKey;

// بررسی وجود کش معتبر
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < CACHE_TIME) {
    // بازگرداندن نتیجه از کش
    $response = file_get_contents($cacheFile);
    $responseCode = 200;

    // چاپ نتیجه
    echo $response;
} else {
    // انجام درخواست به سرور مورد نظر
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // ذخیره نتیجه در کش
    file_put_contents($cacheFile, $response);

    // چاپ نتیجه
    echo $response;
}

// ارسال پاسخ سرور به کلاینت
http_response_code($responseCode);
foreach ($headers as $header => $value) {
    header("$header: $value");
}