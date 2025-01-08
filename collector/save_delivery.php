<?php
// Path to save delivery data
$deliveryFile = __DIR__ . '/delivery.json';

// Get the raw POST data
$rawData = file_get_contents('php://input');
$deliveryData = json_decode($rawData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo 'Invalid JSON data.';
    exit;
}

// Save to the JSON file
file_put_contents($deliveryFile, json_encode($deliveryData, JSON_PRETTY_PRINT));

http_response_code(200);
echo 'Delivery data saved successfully.';
?>

