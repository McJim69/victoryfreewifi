<?php
$uisp_url = 'https://10.0.10.130';
$uisp_token = '32fc12c8-bf0b-4a2c-9c1d-504ad1df54c4';
$id = '00218bba-4f3c-48cd-b8b7-44795012f0b3';
$url = rtrim($uisp_url, '/') . '/nms/api/v2.1/devices/' . $id . '/configuration';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['name' => 'TIGB-Lacupayan-BAP-STN']));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-auth-token: ' . $uisp_token,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "PUT configuration: $http_code\n";
echo $response;
