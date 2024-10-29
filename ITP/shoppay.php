<?php
ob_start(); // Start output buffering
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php'; // Assuming you have a config file for database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if shopping cart data is available in the session
if (!isset($_SESSION['cart'])) {
    echo "<script>alert('No items in the cart. Please add items to your cart.'); window.location.href='shop.php';</script>";
    exit();
}

// Retrieve cart details from the session
$cart = $_SESSION['cart'];
$total_amount = 0;

// Fetch the details of each item in the cart
$item_details = [];
foreach ($cart as $item) {
    $item_id = $item['item_id'];
    $quantity = $item['quantity'];

    $item_stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
    $item_stmt->bind_param("i", $item_id);
    $item_stmt->execute();
    $item_stmt->bind_result($item_name, $item_price);
    $item_stmt->fetch();
    $item_stmt->close();

    $total_amount += $item_price * $quantity;
    $item_details[] = [
        'name' => $item_name,
        'price' => $item_price,
        'quantity' => $quantity
    ];
}

ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Payment - Online Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
            color: #333;
        }
        p {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            color: #666;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #333;
        }
        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .hidden {
            display: none;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Complete Your Payment</h3>
        <?php foreach ($item_details as $detail): ?>
            <p><?= htmlspecialchars($detail['name']) ?> (x<?= htmlspecialchars($detail['quantity']) ?>): RM <?= number_format($detail['price'], 2) ?></p>
        <?php endforeach; ?>
        <p>Total Amount: RM <?= number_format($total_amount, 2) ?></p>
        <form action="process_payment_action.php" method="POST">
            <input type="hidden" name="total_amount" value="<?= htmlspecialchars($total_amount) ?>">
            
            <label for="paymentMethod">Payment Method:</label>
            <select id="paymentMethod" name="payment_method" required>
                <option value="">Choose</option>
                <option value="fpx">Online Banking (FPX)</option>
                <option value="credit_card">Credit/Debit Card</option>
            </select>
            
            <div id="bankOptions" class="hidden">
                <label for="bankSelection">Select Your Bank:</label>
                <select id="bankSelection" name="bank_selection">
                    <option value="">Choose Bank</option>
                    <option value="maybank">Maybank</option>
                    <option value="cimb">CIMB</option>
                    <option value="rhb">RHB</option>
                    <option value="bank_islam">Bank Islam</option>
                </select>
            </div>
            
            <button type="submit">Proceed to Pay</button>
        </form>
    </div>
    <script>
        document.getElementById("paymentMethod").addEventListener("change", function () {
            const bankOptions = document.getElementById("bankOptions");
            if (this.value === "fpx") {
                bankOptions.classList.remove("hidden");
            } else {
                bankOptions.classList.add("hidden");
            }
        });
    </script>
</body>
</html>
