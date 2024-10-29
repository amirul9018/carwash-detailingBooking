<?php
session_start();
require('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Remove the product from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Product removed from cart.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove product from cart.']);
    }
    $stmt->close();
}

$conn->close();
?>