<?php
$uisp_url = 'https://10.0.10.130';
$uisp_token = '32fc12c8-bf0b-4a2c-9c1d-504ad1df54c4';
$url = rtrim($uisp_url, '/') . '/nms/openapi.json';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-auth-token: ' . $uisp_token,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$response = curl_exec($ch);
file_put_contents('openapi.json', $response);
echo "Saved to openapi.json\n";
