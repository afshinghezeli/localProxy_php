<?php


// URL APIs
$url = 'https://jsonplaceholder.typicode.com/posts';

// تنظیمات cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json; charset=utf-8',
]);


// اجرای درخواست و دریافت پاسخ
$response = curl_exec($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// بررسی موفقیت دریافت پاسخ
if ($responseCode >= 200 && $responseCode < 300) {
    // نمایش پاسخ در خروجی به صورت JSON
    header('Content-Type: application/json');
    echo $response;
} else {
    // نمایش خطا در صورت عدم موفقیت
    echo "Error: Failed to retrieve data. Response code: $responseCode";
}

// بستن اتصال cURL
curl_close($ch);