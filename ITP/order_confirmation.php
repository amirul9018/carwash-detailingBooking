<?php
// Start the session
session_start();

// Include the database connection file
require('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit;
}

// Fetch user ID and order ID from session or URL
$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    echo "Invalid order ID.";
    exit;
}

// Fetch the order details
$stmt_order = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt_order->bind_param('ii', $order_id, $user_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

if ($result_order->num_rows === 0) {
    echo "Order not found.";
    exit;
}

$order = $result_order->fetch_assoc();

// Fetch the order items
$stmt_items = $conn->prepare("SELECT oi.*, p.name 
                              FROM order_items oi 
                              JOIN product p ON oi.product_id = p.product_id 
                              WHERE oi.order_id = ?");
$stmt_items->bind_param('i', $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .order-details {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .order-details h2 {
            margin-top: 0;
            color: #333;
        }
        .order-details p {
            font-size: 16px;
            color: #555;
        }
        .order-items {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .order-items th, .order-items td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .order-items th {
            background-color: #f4f4f4;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="order-details">
            <h2>Order Confirmation</h2>
            <p>Thank you for your order!</p>
            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
            <p><strong>Total Amount:</strong> RM<?php echo number_format($order['total'], 2); ?></p>
        </div>

        <h3>Order Items</h3>
        <table class="order-items">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($item = $result_items->fetch_assoc()) {
                    $subtotal = $item['quantity'] * $item['price'];
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                    echo "<td>" . $item['quantity'] . "</td>";
                    echo "<td>RM" . number_format($item['price'], 2) . "</td>";
                    echo "<td>RM" . number_format($subtotal, 2) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <p class="total">Grand Total: RM<?php echo number_format($order['total'], 2); ?></p>

        <a href="mainpage.php" class="btn-back">Back to Main Page</a>
    </div>
</body>
</html>
