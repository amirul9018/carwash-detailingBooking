<?php
session_start();
require 'config.php'; // Assuming you have a config file for database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all bookings for the logged-in user, joining with service_packages to get service name
$stmt = $conn->prepare("
    SELECT b.booking_id, b.date, b.time, sp.name AS service_name 
    FROM bookings b 
    JOIN service_packages sp ON b.service_id = sp.service_id 
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
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
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

        footer {
            background-color: #007bff;
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
        <h1>My Bookings</h1>
    </header>

    <main>
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
            
        </section>
		<a href="mainpage.php">Go Back</a>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
