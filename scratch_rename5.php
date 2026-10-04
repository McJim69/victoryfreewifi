<?php
$uisp_url = 'https://10.0.10.130';
$uisp_token = '32fc12c8-bf0b-4a2c-9c1d-504ad1df54c4';
$id = '00218bba-4f3c-48cd-b8b7-44795012f0b3';
$url = rtrim($uisp_url, '/') . '/nms/api/v2.1/devices/' . $id . '/configuration';

$payloads = [
    ['system' => ['name' => 'TIGB-Lacupayan-BAP-STN-TEST']],
    ['meta' => ['alias' => 'TIGB-Lacupayan-BAP-STN-TEST']],
    ['identification' => ['name' => 'TIGB-Lacupayan-BAP-STN-TEST']]
];

foreach($payloads as $payload) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-auth-token: ' . $uisp_token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $res = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "Payload: " . json_encode($payload) . "\nHTTP: $http\n$res\n\n";
}
