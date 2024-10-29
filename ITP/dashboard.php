<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: rgba(0, 123, 255, 0.9); /* Slight transparency */
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        }

        header h1 {
            margin: 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 20px 0 0 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        main {
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .stats {
            display: flex;
            gap: 20px;
            justify-content: space-around;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .stat-card h3 {
            color: #2d3e50;
            font-size: 20px;
            margin: 0 0 10px 0;
        }

        .stat-card p {
            font-size: 18px;
            color: #666666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f1f3f5;
            color: #3aafa9;
        }

        table tbody tr:hover {
            background-color: #f9fafc;
        }

        table td.actions {
            text-align: center;
        }

        .action-btn {
            padding: 8px 15px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            margin: 0 5px;
        }

        .action-approve {
            background-color: #28a745;
        }

        .action-approve:hover {
            background-color: #218838;
        }

        .action-cancel {
            background-color: #dc3545;
        }

        .action-cancel:hover {
            background-color: #c82333;
        }

        .action-view {
            background-color: #007bff;
        }

        .action-view:hover {
            background-color: #0069d9;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #34495e;
            color: #ffffff;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php
    include 'config.php';
    $conn = new mysqli($servername, $username, $password, $dbname);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userCountQuery = "SELECT COUNT(*) as total_users FROM users";
    $bookingCountQuery = "SELECT COUNT(*) as total_bookings FROM bookings";

    $userCountResult = $conn->query($userCountQuery);
    $bookingCountResult = $conn->query($bookingCountQuery);

    $totalUsers = $userCountResult->fetch_assoc()['total_users'];
    $totalBookings = $bookingCountResult->fetch_assoc()['total_bookings'];
    ?>

    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="../ITP/dashboard.php" class="active">Home</a></li>
                <li><a href="../ITP/manage_user.php">Manage Users</a></li>          
                <li><a href="../ITP/manage_order.php">Manage Orders</a></li>
                <li><a href="../ITP/manage_cust.php">Manage Customers</a></li>
                <li><a href="../ITP/view_reports.php">Customer Reports</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $totalUsers; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p><?php echo $totalBookings; ?></p>
            </div>
        </section>

        <section>
    <h2>Booking List</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Car ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Service</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $conn = new mysqli('localhost', 'root', '', 'carwash');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Modified SQL query to join bookings and service_packages
                $sql = "SELECT b.booking_id, b.user_id, b.car_id, b.date, b.time, s.name AS service_name, b.created_at 
                        FROM bookings b 
                        JOIN service_packages s ON b.service_id = s.service_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['booking_id'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['car_id'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['time'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['service_name']) . "</td>"; // Display service name
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td class='actions'>";
                        echo "<a href='approve_booking.php?id=" . $row['booking_id'] . "' class='action-btn action-approve'>Approve</a>";
                        echo "<a href='cancel_booking.php?id=" . $row['booking_id'] . "' class='action-btn action-cancel'>Cancel</a>";
                        echo "<a href='view_booking.php?id=" . $row['booking_id'] . "' class='action-btn action-view'>View</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No bookings found</td></tr>";
                }

                $conn->close();
            ?>
        </tbody>
    </table>
    <a href="manage_bookings.php" class="button">Manage Booking</a>
</section>

		
		
		
		
		
		<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'carwash');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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

        table thead {
            background-color: #007bff;
            color: white;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            font-weight: bold;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .button {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 24px;
            }

            nav ul li a {
                font-size: 14px;
                padding: 8px 16px;
            }

            main {
                padding: 15px;
            }

            section {
                padding: 15px;
            }

            section h2 {
                font-size: 20px;
            }

            table th, table td {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 20px;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav ul li {
                margin-bottom: 10px;
            }

            nav ul li a {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Products</h1>
    </header>
    
    <main>
        <section>
            <h2>Product List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["product_id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["category"] . "</td>";
                            echo "<td>RM" . $row["price"] . "</td>";
                            echo "<td>" . $row["stock"] . "</td>";
                            echo "<td>
                                <a href='edit_product.php?id=" . $row["product_id"] . "' class='button'>Edit</a>
                                <a href='delete_product.php?id=" . $row["product_id"] . "' class='button' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a>
                              </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No products found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="add_product.php" class="button">Add New Product</a>
        </section>
		
		<?php
// Include database configuration
include 'config.php';

// Fetch all service packages from the database
$sql = "SELECT * FROM service_packages";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Service Packages</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .top-bar {
            width: 100%;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: left;
            position: fixed;
            top: 0;
            left: 0;
        }
        .top-bar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #0056b3;
            transition: background-color 0.3s ease;
            float: right;
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
    
    <div class="container">
    <h2>Available Service Packages</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Package Name</th>
                    <th>Description</th>
                    <th>Price (RM)</th>
                    <th>Actions</th> <!-- Added Actions header -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <!-- Delete Form -->
                            <form action="delete_package.php" method="POST" style="display:inline;">
                                <input type="hidden" name="service_id" value="<?php echo $row['service_id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this package?');" class="button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No service packages available.</p>
    <?php endif; ?>
    <a href="add_package.php" class="button">Manage Services Package</a>
</div>

    
</body>


    
</body>
</html>


    </main>
	

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>