<?php
// webhook.php

// Get the raw POST data
$rawData = file_get_contents("php://input");

// Decode JSON payload (if applicable)
$data = json_decode($rawData, true);

// Log the data to a file
file_put_contents("webhook_log.txt", print_r($data, true), FILE_APPEND);

// Respond to sender
http_response_code(200);
echo "Webhook received successfully.";
?>