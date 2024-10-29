<?php
session_start(); // Start session

// Check if a session message exists and display it
if (isset($_SESSION['message'])) {
    $session_message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying
} else {
    $session_message = '';
}

// Establish the connection to the database
$conn = new mysqli('localhost', 'root', '', 'carwash');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modified SQL query to join bookings, service_packages, and cars
$sql = "SELECT b.booking_id, b.user_id, b.car_id, b.date, b.time, s.name AS service_name, b.created_at, c.license_plate 
        FROM bookings b 
        LEFT JOIN service_packages s ON b.service_id = s.service_id
        LEFT JOIN cars c ON b.car_id = c.car_id"; // Join with cars table to get the license plate

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            padding: 20px 0;
            text-align: center;
        }

        header h1 {
            font-size: 28px;
            letter-spacing: 1px;
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
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 30px;
        }

        section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #3aafa9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        }

        /* Custom Button Styles */
        .action-approve {
            background-color: #28a745; /* Green */
        }

        .action-approve:hover {
            background-color: #218838; /* Darker green on hover */
        }

        .action-cancel {
            background-color: #dc3545; /* Red */
        }

        .action-cancel:hover {
            background-color: #c82333; /* Darker red on hover */
        }

        .action-view {
            background-color: #007bff; /* Blue */
        }

        .action-view:hover {
            background-color: #0069d9; /* Darker blue on hover */
        }

        footer {
            background-color: #17252a;
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 30px;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            table th, table td {
                font-size: 14px;
            }

            nav ul {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 22px;
            }

            nav ul li a {
                font-size: 14px;
                padding: 8px 16px;
            }

            table th, table td {
                padding: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Bookings</h1>
        <nav>
            <ul>
                <li><a href="../ITP/dashboard.php">Dashboard</a></li>
                <li><a href="../ITP/manage_user.php">Manage Users</a></li>
                <li><a href="../ITP/manage_bookings.php" class="active">Manage Bookings</a></li>
                <li><a href="../ITP/view_reports.php">Customer Reports</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Booking List</h2>
            <?php if ($session_message): ?>
                <div style='color: green; text-align: center;'><?= htmlspecialchars($session_message) ?></div>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User ID</th>
                        <th>Car ID</th>
                        <th>License Plate</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Service</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are results
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['booking_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['car_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['license_plate']) . "</td>"; // Display the license plate
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['service_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td class='actions'>";
                            echo "<a href='approve_booking.php?id=" . urlencode($row['booking_id']) . "' class='action-btn action-approve'>Approve</a>";
                            echo "<a href='cancel_booking.php?id=" . urlencode($row['booking_id']) . "' class='action-btn action-cancel'>Cancel</a>";
                            echo "<a href='view_booking.php?id=" . urlencode($row['booking_id']) . "' class='action-btn action-view'>View</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No bookings found</td></tr>";
                    }

                    // Close the connection
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
