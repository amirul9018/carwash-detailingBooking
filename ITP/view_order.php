<?php
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'shop_db');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch order details
    $sql = "SELECT * FROM orders WHERE order_id='$order_id'";
    $result = $conn->query($sql);
    $order = $result->fetch_assoc();

    if ($order) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>View Order Details</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: url('images/wallpaper.png') no-repeat center center fixed;
                    background-size: cover;
                    margin: 0;
                    padding: 0;
                }
                header {
                    background-color: #333;
                    color: #fff;
                    padding: 15px;
                    text-align: center;
                }
                header nav ul {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }
                header nav ul li {
                    display: inline;
                    margin: 0 10px;
                }
                header nav ul li a {
                    color: #fff;
                    text-decoration: none;
                }
                main {
                    padding: 20px;
                    max-width: 800px;
                    margin: 0 auto;
                }
                section {
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                section h2 {
                    color: #007bff;
                    margin-bottom: 20px;
                }
                section p {
                    font-size: 16px;
                    margin-bottom: 10px;
                }
                section table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                section table, th, td {
                    border: 1px solid #ddd;
                }
                section th, td {
                    padding: 10px;
                    text-align: left;
                }
                section th {
                    background-color: #f4f4f4;
                }
                footer {
                    background-color: #333;
                    color: #fff;
                    text-align: center;
                    padding: 10px;
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                }
            </style>
        </head>
        <body>
            <header>
                <h1>Order Details</h1>
                <nav>
                    <ul>
                        <li><a href="manage_orders.php">Back to Order List</a></li>
                    </ul>
                </nav>
            </header>
            
            <main>
                <section>
                    <h2>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h2>
                    <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                    <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($order['total_amount']); ?></p>
                    <h3>Order Items</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch order items
                            $sql_items = "SELECT * FROM order_items WHERE order_id='$order_id'";
                            $result_items = $conn->query($sql_items);

                            if ($result_items->num_rows > 0) {
                                while ($item = $result_items->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . htmlspecialchars($item['product_name']) . "</td>
                                        <td>" . htmlspecialchars($item['quantity']) . "</td>
                                        <td>$" . htmlspecialchars($item['price']) . "</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No items found</td></tr>";
                            }

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </section>
            </main>

            <footer>
                <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
            </footer>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Order not found.</p>";
    }

    $conn->close();
} else {
    echo "<p>No order ID provided.</p>";
}
?>
