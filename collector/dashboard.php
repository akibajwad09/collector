<?php
// Sample JSON orders data
$orders = [
    [
        "_id" => "677b9160b5706ea7450b799c",
        "customer" => [
            "name" => "Mehedi",
            "email" => "mehedi2hasan7@gmail.com",
            "address" => "123 Main Street",
            "city" => "Dhaka",
            "zip" => "1230"
        ],
        "subscription" => "7",
        "cartItems" => [
            ["id" => "677aac88edb15050779fced8", "name" => "Organic Tomatoes", "price" => "60", "quantity" => 1, "totalPrice" => "60.00"],
            ["id" => "677b78c85c62093775121594", "name" => "Organic Tomatoes", "price" => "60", "quantity" => 1, "totalPrice" => "60.00"],
            ["id" => "677b78ca5c62093775121595", "name" => "Organic Bell Pepper", "price" => "70", "quantity" => 1, "totalPrice" => "70.00"]
        ],
        "totalAmount" => "190.00"
    ],
    [
        "_id" => "677d1f45ebc03ea0b337f174",
        "customer" => [
            "name" => "Ajwad Akib",
            "email" => "akib.ajwad09@gmail.com",
            "address" => "Kazi bari, Mohammadpur, Chandanaish, East Zoara-4380, Chattogram",
            "city" => "Chattogram",
            "zip" => "4380"
        ],
        "subscription" => "1",
        "cartItems" => [
            ["id" => "677d1f1aebc03ea0b337f171", "name" => "Organic Spinach", "price" => "50", "quantity" => 1, "totalPrice" => "50.00"],
            ["id" => "677d1f24ebc03ea0b337f172", "name" => "Organic Tomatoes", "price" => "60", "quantity" => 2, "totalPrice" => "120.00"]
        ],
        "totalAmount" => "350.00"
    ]
];

// Calculate product frequencies
$productFrequencies = [];
foreach ($orders as $order) {
    foreach ($order['cartItems'] as $item) {
        $productName = $item['name'];
        $productFrequencies[$productName] = ($productFrequencies[$productName] ?? 0) + $item['quantity'];
    }
}

$nameMapping = [
    "Organic Tomatoes" => "Tomato",
    "Organic Potatoes" => "Potato",
    "Organic Spinach" => "Spinach",
];

// Generate product URLs dynamically based on the name mapping
$productUrls = [];
foreach ($nameMapping as $jsonName => $urlName) {
    $productUrls[$jsonName] = "http://localhost/market-prices2.php?id=" . urlencode($urlName);
}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>GreenBasket Dashboard</title>
    
    <style>
        .container {
            display: flex; /* Flexbox for layout */
        }

        .sidebar {
            position: fixed; /* Sidebar stays at the left */
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #2ecc71;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Optional for visual improvement */
        }

        .sidebar img {
            width: 85px;
            height: 80px;
            padding-left: 2%;
        }

        .sidebar h2 {
            padding-left: 2%;
            color: white;
            font-family: 'Courier New', Courier, monospace;
            margin-bottom: 75px;
            font-size: 1.8rem;
        }

        .sidebar a {
            display: block;
            padding-left: 2%;
            padding-top: 10px;
            padding-bottom: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            color: white;
            font-family: 'Roboto Mono', serif;
            font-weight: 500;
        }

        .sidebar a:hover {
            background-color: #1e874b;
        }

        .content {
            flex-grow: 1;
            margin-left: 150px; /* Shift content to the right */
            padding: 50px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="sidebar">
        <h2>GreenBasket</h2>
        
            <a href="/dashboard.php">Dashboard</a></li>
            <a href="#">Notifications</a></li>
            <a href="/cart2.php">My Cart</a></li>
            <br><br><br>
            <a href="#">Edit Profile</a></li>
            <a href="#">Settings</a></li>
            <a href="#">How it Works</a></li>
        
    </div>
    <div class="content">
        <h1>Market Prices for Products</h1>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Demand</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productFrequencies as $productName => $frequency): ?>
                    <tr>
                        <td>
                            <?php if (isset($productUrls[$productName])): ?>
                                <a href="<?= $productUrls[$productName]; ?>" target="_blank" style="color: #28a745;"><?= htmlspecialchars($productName); ?></a>
                            <?php else: ?>
                                <?= htmlspecialchars($productName); ?>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($frequency); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    	</div>
    </div>
</body>
</html>

