<?php
// Path to the JSON file
$cartFile = __DIR__ . '/cart.json';

// Read and decode the JSON data
$cartData = file_exists($cartFile) ? json_decode(file_get_contents($cartFile), true) : [];
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Error reading the cart data.');
}

// Calculate the total price
$totalPrice = 0;
foreach ($cartData as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Cart</title>
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

        .confirm-btn {
            margin-top: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            align-items: center;
        }

        .confirm-btn:hover {
            background-color: #1e874b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <img src="CSE309MidProject/assets/logo.png" alt="logo">
            <h2>GreenBasket</h2>

            <a href="dashboard.php">Dashboard</a>
            <a href="notifications.php">Notifications</a>
            <a href="cart.php">My Cart</a>
            <br><br><br>
            <a href="edit_profile.php">Edit Profile</a>
            <a href="settings.php">Settings</a>
            <a href="how_it_works.php">How it works</a>
        </div>
        <div class="content">
            <h1 class="mt-4">My Cart</h1>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Region</th>
                        <th>Market Price (BDT)</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cartData)): ?>
                        <?php foreach ($cartData as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['productName']); ?></td>
                                <td><?php echo htmlspecialchars($item['region']); ?></td>
                                <td><?php echo htmlspecialchars($item['price']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Your cart is empty.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h2>Total Price: <?php echo htmlspecialchars($totalPrice); ?> BDT</h2>

            <button class="confirm-btn" onclick="confirmToDeliver()">Confirm to Deliver</button>
        </div>
    </div>

    <script>
        function confirmToDeliver() {
            // Gather cart data
            const cartData = <?php echo json_encode($cartData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
            
            // Send the data to a server-side script to save in a JSON file
            fetch('save_delivery.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cartData),
            })
            .then(response => {
                if (response.ok) {
                    alert('Cart confirmed for delivery!');
                } else {
                    alert('Error confirming delivery. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html>

