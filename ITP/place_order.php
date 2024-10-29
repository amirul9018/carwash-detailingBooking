<?php
// Start the session
session_start();

// Include the database connection file
require('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    exit;
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $address_id = $_POST['address_id'];
    $payment_method = $_POST['payment_method'];

    // Check if cart is not empty
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result_cart = $stmt->get_result();

    if ($result_cart->num_rows === 0) {
        echo "Your cart is empty.";
        exit;
    }

    // Insert the order into the `orders` table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, address_id, payment_method, total, order_date) VALUES (?, ?, ?, ?, NOW())");
    
    // Calculate total order amount
    $total = 0;
    while ($row = $result_cart->fetch_assoc()) {
        $total += $row['quantity'] * $row['price'];
    }
    $stmt->bind_param('iisd', $user_id, $address_id, $payment_method, $total);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;  // Get the generated order ID

        // Insert each item from cart into the `order_items` table
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

        foreach ($result_cart as $cart_item) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $price = $cart_item['price'];
            $stmt_items->bind_param('iiid', $order_id, $product_id, $quantity, $price);
            $stmt_items->execute();
        }

        // Clear the cart for the user
        $stmt_clear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt_clear->bind_param('i', $user_id);
        $stmt_clear->execute();

        // Redirect to a confirmation page
        header("Location: order_confirmation.php?order_id=" . $order_id);
        exit;

    } else {
        echo "Error placing order. Please try again.";
    }
}
?>
