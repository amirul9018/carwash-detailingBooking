<?php
// Database configuration
$servername = "localhost"; // Change if your database is hosted elsewhere
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "carwash"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve reports
$sql = "SELECT * FROM support_requests"; // Ensure your table name is 'support_requests'
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Customer Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: rgba(0, 86, 179, 0.9);
            padding: 10px 0;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        nav ul li a:hover,
        nav ul li a.active {
            background-color: #003f7f;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
            color: #0056b3;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: relative;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
        }
        footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
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
    <h1>Customer Reports</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data for each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['subject']}</td>
                            <td>{$row['message']}</td>
                            <td>{$row['created_at']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No reports found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
