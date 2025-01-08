<?php
// Get the raw POST data
$rawData = file_get_contents('php://input');
echo $rawData;

// Decode the JSON data
$selectedRegions = json_decode($rawData, true);

if (json_last_error() !== JSON_ERROR_NONE || empty($selectedRegions)) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid or empty data.']);
    exit;
}

// Path to the JSON file
$cartFile = __DIR__ . '/cart.json';

// If the file already exists, read and merge data
$cartData = file_exists($cartFile) ? json_decode(file_get_contents($cartFile), true) : [];
if (json_last_error() !== JSON_ERROR_NONE) {
    $cartData = [];
}

// Merge new data into the cart
$cartData = array_merge($cartData, $selectedRegions);

// Save the merged data back to the file
if (file_put_contents($cartFile, json_encode($cartData, JSON_PRETTY_PRINT))) {
    echo json_encode(['message' => 'Cart updated successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to update the cart.']);
}

