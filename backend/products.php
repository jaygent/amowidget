<?php

header("Access-Control-Allow-Origin: https://qnb55226.kommo.com");
require_once "amoClient.php";
$subdomain = 'qnb55226';
$leadId = filter_input(INPUT_GET, 'lead_id', FILTER_VALIDATE_INT) ?? 2395056;
$link = "https://{$subdomain}.kommo.com/api/v4/leads/{$leadId}?with=catalog_elements";
$token = json_decode(file_get_contents('token.json'),true);
$header = ['Content-Type:application/json', "Authorization:Bearer {$token['access_token']}"];

function getProducts($product)
{
    global $subdomain;
    global $header;
    $link = "https://{$subdomain}.kommo.com/api/v4/catalogs/{$product['metadata']['catalog_id']}/elements/{$product['id']}";
    $elementProduct = amoClient::request("GET", $link, $header);
    return ['name' => $elementProduct['name'], 'quantity' => $product["metadata"]["quantity"],];
}

try {
    $response = amoClient::request('GET', $link, $header);
    echo json_encode(array_map('getProducts', $response['_embedded']['catalog_elements']), JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
