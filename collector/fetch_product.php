<?php
$servername = "localhost";
$username = "akib2048";
$password = "Ak!32009";
$dbname = "market_prices";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from query parameter
$productId = $_GET['id'] ?? null;

if ($productId) {
    // Fetch product details
    $stmt = $conn->prepare("
        SELECT p.product_name, p.unit, pr.region, pr.price
        FROM products p
        INNER JOIN prices pr ON p.id = pr.product_id
        WHERE p.product_name = ?
    ");
    $stmt->bind_param("s", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $productData = [];
    while ($row = $result->fetch_assoc()) {
        $productData[] = $row;
    }

    // Build JSON response
    if (!empty($productData)) {
        $response = [
            "name" => $productData[0]['product_name'],
            "unit" => $productData[0]['unit'],
            "prices" => array_map(function ($item) {
                return [
                    "region" => $item['region'],
                    "price" => $item['price']
                ];
            }, $productData)
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo json_encode(["error" => "No product found"]);
    }
} else {
    echo json_encode(["error" => "Invalid product ID"]);
}

$conn->close();
?>
