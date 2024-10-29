<?php
// Start the session
session_start();

// Include the database connection file
require('config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to perform this action.";
    exit;
}

// Check if the order ID and status are provided either via POST or GET
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
} elseif (isset($_GET['order_id']) && isset($_GET['status'])) {
    $order_id = $_GET['order_id'];
    $status = $_GET['status'];
} else {
    echo "Order ID or status is missing.";
    exit;
}

// Update the order status in the database
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    // Redirect back to the manage orders page with a success message
    header("Location: manage_orders.php?update=success");
    exit;
} else {
    // Display an error message if the update fails
    echo "Error updating order: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
