<?php
// Start the session
session_start();

// Include the database connection file
require('config.php'); // Ensure this points to your config file with DB credentials

// Initialize variables
$users = [];
$total = 0;

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user details
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result_user = $stmt->get_result();

    if ($result_user->num_rows > 0) {
        $users = $result_user->fetch_assoc();
    } else {
        echo "<p class='error'>User not found.</p>";
        exit;
    }

    // Fetch addresses associated with the user
    $stmt = $conn->prepare("SELECT address_id, address, city, postal_code, country FROM addressbook WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result_addresses = $stmt->get_result();

    // Fetch cart items
    $stmt = $conn->prepare("SELECT p.product_id, p.name, c.quantity, p.price FROM cart c JOIN product p ON c.product_id = p.product_id WHERE c.user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result_cart = $stmt->get_result();

    // Close the statement
    $stmt->close();
} else {
    echo "<p class='error'>User not logged in. Please <a href='login.php'>log in</a> to continue.</p>";
    exit;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        /* Include your CSS styles here */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        /* Header Styles */
        header {
            background-color: #007BFF;
            color: white;
            padding: 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #ffdd57;
        }

        /* Main Container */
        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007BFF;
            margin-bottom: 15px;
            font-size: 22px;
        }
        .section {
            margin-bottom: 40px;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, select:focus {
            border-color: #007BFF;
            outline: none;
        }

        /* Order Summary Table */
        .order-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            text-align: left;
        }
        .order-summary table th, .order-summary table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .order-summary table th {
            background-color: #007BFF;
            color: white;
            font-size: 18px;
        }
        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #28A745;
        }

        .address-btn, .checkout-btn, .continue-shopping-btn {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .address-btn {
            background-color: #D3D3D3;
            color: black;
        }
        .address-btn:hover {
            background-color: #A9A9A9;
        }

        .checkout-btn {
            background-color: #28A745;
            color: white;
        }
        .checkout-btn:hover {
            background-color: #218838;
        }

        .continue-shopping-btn {
            background-color: #007BFF;
            color: white;
            margin-left: 10px;
        }
        .continue-shopping-btn:hover {
            background-color: #0056b3;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            background-color: #007BFF;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
                gap: 10px;
            }
            main {
                padding: 15px;
            }
            header h1 {
                font-size: 20px;
            }
        }

        /* Quantity Control */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-control button {
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .quantity-control button:hover {
            background-color: #0056b3;
        }
        .quantity-display {
            width: 40px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Checkout</h1>
        <nav>
            <ul>
                <li><a href="../ITP/mainpage.php">Dashboard</a></li>
                <li><a href="../ITP/checkout.php">Checkout</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <form action="process_payment2.php" method="post">
            <!-- Customer Information Section -->
            <div class="section">
                <h2>Customer Information</h2>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($users['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($users['email']); ?></p>
            </div>

            <!-- Address Section -->
            <div class="section">
                <h2>Select Shipping Address</h2>
                <label for="address_id">Choose an Address:</label>
                <select name="address_id" id="address_id" required>
                    <?php
                    if ($result_addresses->num_rows > 0) {
                        while ($address = $result_addresses->fetch_assoc()) {
                            echo "<option value='{$address['address_id']}'>{$address['address']}, {$address['city']}, {$address['postal_code']}, {$address['country']}</option>";
                        }
                    } else {
                        echo "<option value=''>No saved addresses available</option>";
                    }
                    ?>
                </select>
                <div>
                    <a href="../ITP/address_book.php" class="address-btn">Add Address</a>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="section">
                <h2>Payment Information</h2>
                <label for="paymentMethod">Payment Method:</label>
                <select id="paymentMethod" name="payment_method" required>
                    <option value="">Choose</option>
                    <option value="fpx">Online Banking (FPX)</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                </select>
                <div id="bankOptions" class="hidden">
                    <label for="bankSelection">Select Bank:</label>
                    <select name="bank" id="bankSelection">
                        <option value="">Choose Bank</option>
                        <option value="maybank">Maybank</option>
                        <option value="cimb">CIMB</option>
                        <option value="rhb">RHB Bank</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <!-- Order Summary Section -->
            <div class="section order-summary">
                <h2>Your Order Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price (RM)</th>
                            <th>Subtotal (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_cart->num_rows > 0) {
                            while ($item = $result_cart->fetch_assoc()) {
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal; // Calculate total for the order
                                echo "<tr id='product_row_" . $item['product_id'] . "'>
                                        <td>" . htmlspecialchars($item['name']) . "</td>
                                        <td>
                                            <div class='quantity-control'>
                                                <button type='button' onclick='changeQuantity(" . $item['product_id'] . ", -1)'>-</button>
                                                <input type='text' class='quantity-display' id='quantity_" . $item['product_id'] . "' value='" . $item['quantity'] . "' readonly>
                                                <button type='button' onclick='changeQuantity(" . $item['product_id'] . ", 1)'>+</button>
                                            </div>
                                        </td>
                                        <td>" . number_format($item['price'], 2) . "</td>
                                        <td class='subtotal' id='subtotal_" . $item['product_id'] . "'>" . number_format($subtotal, 2) . "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='error'>No items in the cart.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="total">
                    Total: RM <span id="totalAmount"><?php echo number_format($total, 2); ?></span>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="checkout-btn">Proceed to Payment</button>
                <a href="../ITP/shop.php" class="continue-shopping-btn">Continue Shopping</a>
            </div>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 918 Garage. All rights reserved.</p>
    </footer>

    <script>
        // Show bank options based on payment method
        document.getElementById('paymentMethod').addEventListener('change', function() {
            const bankOptions = document.getElementById('bankOptions');
            bankOptions.classList.toggle('hidden', this.value !== 'fpx');
        });

        // Function to update quantity and subtotal
        function changeQuantity(productId, change) {
            const quantityInput = document.getElementById('quantity_' + productId);
            const subtotalCell = document.getElementById('subtotal_' + productId);
            let currentQuantity = parseInt(quantityInput.value);
            const price = parseFloat(subtotalCell.innerText) / currentQuantity; // Get the price per item

            // Update the quantity
            currentQuantity += change;

            // Check if quantity is less than 1
            if (currentQuantity < 1) {
                // Remove product if quantity is 1 and user clicks -
                removeProduct(productId);
                return; // Exit the function
            }
            quantityInput.value = currentQuantity;

            // Update the subtotal
            const newSubtotal = price * currentQuantity;
            subtotalCell.innerText = newSubtotal.toFixed(2);

            // Update the total amount
            updateTotal();
        }

        // Function to remove a product from the cart
        function removeProduct(productId) {
            const row = document.getElementById('product_row_' + productId);
            if (row) {
                row.remove(); // Remove the row from the table
            }
            updateTotal(); // Update the total after removal
        }

        // Function to update the total amount
        function updateTotal() {
            let total = 0;
            const subtotalCells = document.querySelectorAll('.subtotal');
            subtotalCells.forEach(cell => {
                total += parseFloat(cell.innerText);
            });
            document.getElementById('totalAmount').innerText = total.toFixed(2);
        }
    </script>
</body>
</html>
