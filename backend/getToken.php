<?php
    require_once "amoClient.php";
    $setting = require_once 'setting.php';
    $subdomain = 'qnb55226';

    $link = 'https://' . $subdomain . '.kommo.com/oauth2/access_token';

    $data = array_merge($setting,[
        'grant_type' => 'authorization_code',
        //'code' => filter_input(INPUT_GET,'code',FILTER_SANITIZE_STRING),
        'redirect_uri' => 'https://test.mebcrm.ru/getToken.php',
    ]);
    var_dump($data);
    $header = ['Content-Type:application/json'];

try {
    $token = amoClient::request("POST", $link, $header,$data);
    file_put_contents('token.json',json_encode($token,JSON_UNESCAPED_UNICODE));
} catch (Exception $e) {
    header('HTTP/1.1 400 Bad Request');
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

//запрос на получение токена
https://www.kommo.com/oauth?client_id=e8c31fed-8cf5-4b0a-9b43-6ba18fa1c87f&state=123&mode=post_message