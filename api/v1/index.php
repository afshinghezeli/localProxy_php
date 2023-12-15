<?php
//CACHE SETTING
const CACHE_DIR = 'cache/';
const CACHE_TIME = 60;

//GET CLIENT REQUEST
$method = $_SERVER['REQUEST_METHOD'];
$url =  $_GET['url'];
$headers = getallheaders();
$body = file_get_contents('php://input');

//GENERATE CACHE
$cacheKey = md5($method . $url . serialize($headers) . $body);
$cacheFile = CACHE_DIR . $cacheKey;

//IF Cache Exists File
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < CACHE_TIME) {
    //CACHE RESPONSE
    $response = file_get_contents($cacheFile);
    $responseCode = 200;
    echo $response;
}


//function
function setHeaders($status_code)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    header("HTTP/1.1 $status_code " . statusMessage["$status_code"]);
}
function apiResponse(array $data, $status_code)
{
    setHeaders($status_code);
    $data = [
        'http_status' => $status_code,
        'http_message' => statusMessage["$status_code"],
        'data' => [$data ? $data : ['message' => 'no data found !']],
    ];
    return json_encode($data);
}

if (isset($_GET['users'])){
    echo apiResponse([], 200);
}else{
    echo apiResponse([], 404);
}



