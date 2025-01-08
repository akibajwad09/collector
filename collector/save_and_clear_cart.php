<?php
// Path to the JSON files
$cartFile = __DIR__ . '/cart.json';
$deliveryFile = __DIR__ . '/delivery.json';

// Get the raw POST data
$rawData = file_get_contents('php://input');
$deliveryData = json_decode($rawData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo 'Invalid JSON data.';
    exit;
}

// Save delivery data to delivery.json
file_put_contents($deliveryFile, json_encode($deliveryData, JSON_PRETTY_PRINT));

// Clear the cart by saving an empty array to cart.json
file_put_contents($cartFile, json_encode([], JSON_PRETTY_PRINT));

// Return success response
http_response_code(200);
echo 'Delivery data saved and cart cleared successfully.';
?>

