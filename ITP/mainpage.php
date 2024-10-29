<?php
session_start();
require 'config.php'; // Assuming you have a config file for database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch bookings for the logged-in user
// Fetch bookings for the logged-in user along with the service name
$stmt = $conn->prepare("
    SELECT b.booking_id, b.date, b.time, s.name AS service_name 
    FROM bookings b 
    JOIN service_packages s ON b.service_id = s.service_id 
    WHERE b.user_id = ? 
    ORDER BY b.date, b.time
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Close the statement after fetching bookings
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
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

        header img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
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

        nav ul li a.active,
        nav ul li a:hover {
            background-color: #0056b3;
        }

        main {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        section {
            margin-bottom: 40px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #007bff;
        }

        section p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
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

        table th,
        table td {
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

        .view-btn,
        .cancel-btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            transition: background-color 0.3s ease;
        }

        .view-btn {
            background-color: #28a745;
        }

        .view-btn:hover {
            background-color: #218838;
        }

        .cancel-btn {
            background-color: #dc3545;
        }

        .cancel-btn:hover {
            background-color: #c82333;
        }

        .view-all-btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .view-all-btn:hover {
            background-color: #0056b3;
        }

        .quick-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .action-btn {
            display: block;
            width: 100%;
            text-align: center;
            text-decoration: none;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <!-- Add logo at the top -->

        <h1>Customer Dashboard</h1>
        <nav>
            <ul>
                <div class="quick-actions">
                    <li><a href="mainpage.php" class="active">Dashboard</a></li>
                    <li><a href="bookawash.php">Book a Wash</a></li>
                    <li><a href="shop.php">Online Shop</a></li>
                    <li><a href="support.php">Support</a></li>
					<li><a href="customer_settings.php">Account Settings</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </div>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Welcome, User!</h2>
            <p>Here is a quick overview of your recent activity and upcoming bookings.</p>
        </section>

        <section>
    <h2>Upcoming Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Service</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are bookings
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["booking_id"]) . "</td>
                            <td>" . htmlspecialchars($row["date"]) . "</td>
                            <td>" . htmlspecialchars($row["time"]) . "</td>
                            <td>" . htmlspecialchars($row["service_name"]) . "</td>
                            <td>
                                <a href='view_booking.php?id=" . htmlspecialchars($row["booking_id"]) . "' class='view-btn'>View</a>
                                <a href='edit_book.php?id=" . htmlspecialchars($row["booking_id"]) . "' class='cancel-btn'>Edit</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No upcoming bookings.</td></tr>";
            }

            // Close the connection
            $conn->close();
            ?>
        </tbody>
    </table>
    <a href="my_bookings.php" class="view-all-btn">View All Bookings</a>
</section>

		
		<?php
// Include the database connection file
require('config.php'); // Ensure this file contains the proper database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch products from the database
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Shop</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed; /* Set your wallpaper image path */
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        header {
            background-color: rgba(0, 123, 255, 0.9); /* Add transparency for a more modern look */
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
            font-size: 32px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 15px 0 0 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: 1px solid transparent;
        }

        nav ul li a.active,
        nav ul li a:hover {
            background-color: #0056b3;
            border: 1px solid white;
        }

        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.85); /* Slightly transparent white background */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        section h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .product-item {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: calc(25% - 20px); /* Smaller box: Adjust to 25% */
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-5px);
        }

        .product-item img {
    width: 200px; /* Set a fixed width */
    height: 200px; /* Set a fixed height */
    object-fit: cover; /* Ensures the image fills the area without distortion */
    display: block;
    margin: 0 auto; /* Centers the image horizontally */
}


        .product-details {
            padding: 10px;
        }

        .product-details h3 {
            font-size: 18px; /* Adjusted to be slightly smaller */
            color: #007bff;
            margin-bottom: 8px;
        }

        .product-details p {
            font-size: 13px; /* Smaller text for description */
            color: #555;
            margin-bottom: 10px;
        }

        .product-details .price {
            font-size: 16px; /* Smaller font size for price */
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-details .buy-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 12px; /* Smaller button */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: none; /* Remove border for a cleaner button */
        }

        .product-details .buy-btn:hover {
            background-color: #218838;
        }

        footer {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.2);
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .product-item {
                width: calc(50% - 20px); /* 2 items per row on smaller screens */
            }
        }

        @media (max-width: 480px) {
            .product-item {
                width: 100%; /* 1 item per row on extra small screens */
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Online Shop</h1>
        
    </header>

    <main>
        <section>
            <h2>Featured Products</h2>
            <div class="product-list">

                <?php
                // Check if any products are returned from the database
                if ($result->num_rows > 0) {
                    // Loop through the products and display them
                    while ($row = $result->fetch_assoc()) {
                        // Ensure product has a 'product_id' field to display
                        if (!empty($row['product_id'])) {
                        ?>
                        <div class="product-item">
                            <img src="<?php echo !empty($row['image_path']) ? $row['image_path'] : 'default-image.jpg'; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div class="product-details">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p><?php echo htmlspecialchars($row['description']); ?></p>
                                <div class="price">RM<?php echo number_format($row['price'], 2); ?></div>
                                <a href="../ITP/product_detail.php?product_id=<?php echo urlencode($row['product_id']); ?>" class="buy-btn">Buy Now</a>

                            </div>
                        </div>
                        <?php
                        } else {
                            echo "<p>Product ID missing for {$row['name']}.</p>";
                        }
                    }
                } else {
                    // If no products found
                    echo "<p>No products available at the moment.</p>";
                }
                ?>

            </div>
        </section>
    </main>
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
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo number_format($row['price'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
				
            </table>
        <?php else: ?>
            <p>No service packages available.</p>
        <?php endif; ?>
    </div>
	
    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>


    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>


        <section>
            <h2>Quick Actions</h2>
            <div class="quick-actions">
                <a href="bookawash.php" class="action-btn">Book a Wash</a>
                <a href="customer_settings.php" class="action-btn">Update Account Details</a>
                <a href="support.php" class="action-btn">Contact Support</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
