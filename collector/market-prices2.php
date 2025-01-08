<?php
// Read JSON file
$jsonFile = __DIR__ . '/market-prices.json';
if (!file_exists($jsonFile)) {
    die("Market prices data not found.");
}

// Decode JSON data
$jsonData = json_decode(file_get_contents($jsonFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error decoding market prices data.");
}

// Get product ID from URL
$productId = $_GET['id'] ?? null;
if (!$productId || !isset($jsonData[$productId])) {
    die("Invalid product ID or product not found.");
}

// Get the product data
$productData = $jsonData[$productId];
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
    <link rel="stylesheet" href="marketprice.css">
    <title>Market Prices - <?php echo htmlspecialchars($productId); ?></title>
    <style>
        /* Styling for input incrementor to reduce size */
        .input-group {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 120px; /* Adjust width here */
            margin: auto;
        }

        .input-group input {
            text-align: center;
            width: 60px; /* Adjust input box width */
            font-size: 0.9rem;
        }

        .input-group button {
            width: 25px; /* Smaller buttons */
            font-size: 0.9rem;
        }

        /* Adjust table column sizes */
        .table tbody td:nth-child(1) {
            width: 30%; /* Product Name Column - Longest */
        }

        .table tbody td:nth-child(3) {
            width: 30%; /* Incrementor Column */
        }
        .select-btn{
        	background-color: #2ecc71;
        	color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="CSE309MidProject/assets/logo.png" alt="logo">
            <h2>GreenBasket</h2>

            <a href="dashboard.php">Dashboard</a>
            <a href="notifications.php">Notifications</a>
            <a href="cart2.php">My Cart</a>
            <br><br><br>
            <a href="edit_profile.php">Edit Profile</a>
            <a href="settings.php">Settings</a>
            <a href="how_it_works.php">How it works</a>
        </div>

        <!-- Content Section -->
        <div class="content">
            <div class="top-section">
                <div class="user-info">
                    <h1>Market Prices</h1>
                </div>
            </div>

            <div class="table-container">
                <h2 class="text-center mb-4">Market Prices for <?php echo htmlspecialchars($productId); ?></h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Region</th>
                            <th>Market Price</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productData as $entry): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($entry['region']); ?></td>
                            <td><?php echo htmlspecialchars($entry['price']); ?> BDT</td>
                            <td>
                                <button class="btn-green select-btn" 
                                    onclick="selectRegion('<?php echo htmlspecialchars($entry['region']); ?>', '<?php echo htmlspecialchars($entry['price']); ?>')">
                                    Select
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="selected-products-section">
                    <h3>Selected Products</h3>
                    <table class="table table-bordered" id="selectedProductsTable">
                        <thead>
                            <tr>
                                <th>Region</th>
                                <th>Market Price</th>
                                <th>Quantity</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Selected products will be shown here -->
                        </tbody>
                    </table>
                </div>
                <button class="confirm-btn" onclick="confirmSelection()">Confirm this product for procurement</button>
            </div>
        </div>
    </div>

    <script>
        const selectedProductsTable = document.getElementById('selectedProductsTable').querySelector('tbody');

        // Helper function to generate a random value between 1 and 20
        function getRandomQuantity() {
            return Math.floor(Math.random() * 20) + 1; // Generate random integer
        }

        // Helper function to create an incrementor
        function createQuantityInput(initialValue) {
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group';

            const decrementBtn = document.createElement('button');
            decrementBtn.className = 'btn btn-outline-secondary btn-sm';
            decrementBtn.textContent = '-';
            decrementBtn.onclick = () => {
                let currentValue = parseFloat(quantityInput.value);
                if (currentValue > 0) {
                    quantityInput.value = (currentValue - 0.5).toFixed(1);
                }
            };

            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.className = 'form-control';
            quantityInput.value = initialValue;
            quantityInput.step = 0.5;
            quantityInput.min = 0;

            const incrementBtn = document.createElement('button');
            incrementBtn.className = 'btn btn-outline-secondary btn-sm';
            incrementBtn.textContent = '+';
            incrementBtn.onclick = () => {
                let currentValue = parseFloat(quantityInput.value);
                quantityInput.value = (currentValue + 0.5).toFixed(1);
            };

            inputGroup.appendChild(decrementBtn);
            inputGroup.appendChild(quantityInput);
            inputGroup.appendChild(incrementBtn);

            return inputGroup;
        }

        function selectRegion(region, price) {
            const row = document.createElement('tr');

            const quantity = getRandomQuantity(); // Generate random quantity

            row.innerHTML = `
                <td>${region}</td>
                <td>${price} BDT</td>
                <td></td>
                <td><button class="btn btn-danger btn-sm remove-btn">Remove</button></td>
            `;

            const quantityCell = row.querySelector('td:nth-child(3)');
            quantityCell.appendChild(createQuantityInput(quantity));

            row.querySelector('.remove-btn').addEventListener('click', () => row.remove());
            selectedProductsTable.appendChild(row);
        }

        function confirmSelection() {
    const selectedRegions = [];
    selectedProductsTable.querySelectorAll('tr').forEach(row => {
        const region = row.cells[0].innerText.trim();
        const price = row.cells[1].innerText.trim().replace(" BDT", ""); // Remove " BDT"
        const quantity = row.querySelector('input').value.trim();
        selectedRegions.push({ productName: "<?php echo htmlspecialchars($productId); ?>" , region, price, quantity });
    });

    // Send the selected regions to the server to save to a file
    fetch('save_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(selectedRegions),
    })
        .then(response => {
            if (response.ok) {
                alert('Procurement confirmed and saved to the cart.');
            } else {
                alert('Failed to save the data. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error saving data:', error);
            alert('An error occurred. Please try again.');
        });
}

    </script>
</body>
</html>
