<?php
require('connect.php');
$uisp_url = 'https://10.0.10.130';
$uisp_token = '32fc12c8-bf0b-4a2c-9c1d-504ad1df54c4';
$endpoint = rtrim($uisp_url, '/') . '/nms/api/v2.1/devices';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-auth-token: ' . $uisp_token,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$response = curl_exec($ch);
$devices = json_decode($response, true);
if(is_array($devices) && count($devices) > 0) {
  print_r($devices[0]);
  // let's look for a station specifically
  foreach($devices as $dev) {
      if(strpos(strtolower(json_encode($dev)), 'station') !== false) {
          echo "\nFound a station:\n";
          print_r($dev);
          break;
      }
  }
}
