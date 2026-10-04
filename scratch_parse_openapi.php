<?php
$data = json_decode(file_get_contents('openapi.json'), true);
if (!$data || !isset($data['paths'])) {
    echo 'Not a valid OpenAPI JSON: ' . substr(file_get_contents('openapi.json'), 0, 100);
    exit;
}
foreach($data['paths'] as $path => $methods) {
    if (strpos($path, 'devices') !== false) {
        foreach($methods as $method => $info) {
            if ($method === 'put' || $method === 'patch' || $method === 'post') {
                // check if it updates device name
                echo strtoupper($method) . ' ' . $path . ' - ' . (isset($info['summary']) ? $info['summary'] : '') . "\n";
            }
        }
    }
}
