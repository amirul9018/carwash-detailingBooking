<?php
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'shop_db');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete order
    $sql = "DELETE FROM orders WHERE order_id='$order_id'";
    $sql_items = "DELETE FROM order_items WHERE order_id='$order_id'";

    if ($conn->query($sql) === TRUE && $conn->query($sql_items) === TRUE) {
        echo "Order deleted successfully.";
        header("Location: manage_orders.php");
    } else {
        echo "Error deleting order: " . $conn->error;
    }

    $conn->close();
} else {
    echo "No order ID provided.";
}
?>
