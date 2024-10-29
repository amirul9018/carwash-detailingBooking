<?php
// Start the session
session_start();

// Include the database connection file
require('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p class='error'>User not logged in. Please <a href='login.php'>log in</a> to continue.</p>";
    exit;
}

// Retrieve user_id from session
$user_id = $_SESSION['user_id'];

// Check if the payment was successful
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    // Retrieve data from the query string
    $selected_address = $_GET['address_id'];
    $payment_method = $_GET['payment_method'];
    $total = $_GET['total_amount'];

    // Insert the order into the orders table
    $sql_order = "INSERT INTO orders (user_id, address_id, order_date, total, payment_method, status) 
                  VALUES ('$user_id', '$selected_address', NOW(), '$total', '$payment_method', 'pending')";

    if ($conn->query($sql_order) === TRUE) {
        $order_id = $conn->insert_id;  // Get the last inserted order ID

        // Fetch cart items for the user
        $result_cart = $conn->query("SELECT product_id, quantity, price FROM cart WHERE user_id = '$user_id'");

        // Insert each cart item into the order_items table
        while ($cart_item = $result_cart->fetch_assoc()) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $price = $cart_item['price'];

            $sql_order_item = "INSERT INTO order_items (order_id, product_id, quantity, price)
                               VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            $conn->query($sql_order_item);
        }

        // Clear the cart after the order is placed
        $sql_clear_cart = "DELETE FROM cart WHERE user_id = '$user_id'";
        $conn->query($sql_clear_cart);

        // Redirect to the confirmation page
        header("Location: order_confirmation.php?order_id=$order_id");
        exit();
    } else {
        echo "Error placing order: " . $conn->error;
    }
} else {
    echo "Invalid request or payment not successful.";
}
?>
