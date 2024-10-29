<?php
// Database connection (adjust your connection details)
$host = 'localhost';
$db = 'carwash'; // Change to your actual database name
$user = 'root'; // Change to your actual DB username
$pass = ''; // Change to your actual DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Check if user is logged in and is an admin
session_start();


// Fetch all orders
$stmt_orders = $pdo->query("SELECT o.order_id, o.user_id, o.order_date, o.total, o.status, u.name 
                             FROM orders o 
                             JOIN users u ON o.user_id = u.user_id");
$result_orders = $stmt_orders->fetchAll();

// Check if there are any orders
if (empty($result_orders)) {
    echo "No orders found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 28px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 15px 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 10px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #0056b3;
            transition: background-color 0.3s;
        }
        nav ul li a.active {
            background-color: #003f7f;
        }
        nav ul li a:hover {
            background-color: #003f7f;
        }
        main {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        section h2 {
            color: #007bff;
            font-size: 22px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
            color: #007bff;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .update-btn {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
        }
        .update-btn:hover {
            background-color: #218838;
        }
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Orders</h1>
        <nav>
            <ul>
                <li><a href="../ITP/dashboard.php">Dashboard</a></li>
                <li><a href="../ITP/manage_product.php">Manage Products</a></li>
                <li><a href="../ITP/manage_user.php">Manage Users</a></li>
                <li><a href="../ITP/manage_bookings.php">Manage Bookings</a></li>
                <li><a href="../ITP/view_reports.php">Customer Reports</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Orders List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Order Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result_orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']); ?></td>
                        <td><?= htmlspecialchars($order['name']); ?></td>
                        <td><?= htmlspecialchars($order['order_date']); ?></td>
                        <td>RM<?= number_format($order['total'], 2); ?></td>
                        <td><?= htmlspecialchars($order['status']); ?></td>
                        <td>
                            <select onchange="location.href='update_order.php?order_id=<?= $order['order_id']; ?>&status=' + this.value">
                                <option value='Pending' <?= ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value='Processing' <?= ($order['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                <option value='Completed' <?= ($order['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value='Cancelled' <?= ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <a href="update_order.php?order_id=<?= $order['order_id']; ?>" class="update-btn">Update</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
