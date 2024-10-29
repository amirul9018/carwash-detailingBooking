<?php
session_start();
require 'config.php'; // Assuming you have a config file for database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch booking ID from the URL
if (!isset($_GET['id'])) {
    echo "Booking ID is required.";
    exit();
}

$booking_id = $_GET['id'];

// Fetch booking details
function fetchBookingDetails($conn, $booking_id) {
    $stmt = $conn->prepare("SELECT booking_id, date, time, service_id, car_id, customer_request FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc(); // Return a single row
}

// Fetch car details
function fetchCarDetails($conn, $car_id) {
    $stmt = $conn->prepare("SELECT car_id, make, model, year, color, license_plate, car_photo FROM cars WHERE car_id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc(); // Return a single row
}

// Fetch service details
function fetchServiceDetails($conn, $service_id) {
    $stmt = $conn->prepare("SELECT service_id, name, description, price FROM service_packages WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc(); // Return a single row
}

$bookingDetails = fetchBookingDetails($conn, $booking_id);
if (!$bookingDetails) {
    echo "No booking found.";
    exit();
}

$carDetails = fetchCarDetails($conn, $bookingDetails['car_id']);
if (!$carDetails) {
    echo "No car found for this booking.";
    exit();
}

$serviceDetails = fetchServiceDetails($conn, $bookingDetails['service_id']);
if (!$serviceDetails) {
    echo "No service found for this booking.";
    exit();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
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
            max-width: 800px;
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
            margin-bottom: 10px;
            color: #007bff;
        }

        .details-list {
            list-style-type: none;
            padding: 0;
            font-size: 18px;
        }

        .details-list li {
            margin-bottom: 10px;
        }

        .car-photo {
            width: 150px;
            height: auto;
            border-radius: 5px;
        }

        .main-page-btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        .main-page-btn:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: relative; /* Changed to relative */
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
    <header>
        <h1>Booking Details</h1>
    </header>

    <main>
        <section>
            <h2>Booking Information</h2>
            <ul class="details-list">
                <li><strong>Booking ID:</strong> <?php echo htmlspecialchars($bookingDetails['booking_id']); ?></li>
                <li><strong>Date:</strong> <?php echo htmlspecialchars($bookingDetails['date']); ?></li>
                <li><strong>Time:</strong> <?php echo htmlspecialchars($bookingDetails['time']); ?></li>
                <li><strong>Service ID:</strong> <?php echo htmlspecialchars($bookingDetails['service_id']); ?></li>
				<li><strong>Request:</strong> <?php echo htmlspecialchars($bookingDetails['customer_request']); ?></li>
            </ul>
        </section>

        <section>
            <h2>Car Information</h2>
            <ul class="details-list">
                <li><strong>Make:</strong> <?php echo htmlspecialchars($carDetails['make']); ?></li>
                <li><strong>Model:</strong> <?php echo htmlspecialchars($carDetails['model']); ?></li>
                <li><strong>Year:</strong> <?php echo htmlspecialchars($carDetails['year']); ?></li>
                <li><strong>Color:</strong> <?php echo htmlspecialchars($carDetails['color']); ?></li>
                <li><strong>License Plate:</strong> <?php echo htmlspecialchars($carDetails['license_plate']); ?></li>
                <li><strong>Car Photo:</strong></li>
                <li>
                    <img src='<?php echo htmlspecialchars($carDetails["car_photo"]); ?>' alt='Car Photo' class='car-photo'>
                </li>
            </ul>
        </section>

        <section>
            <h2>Service Information</h2>
            <ul class="details-list">
                <li><strong>Service Name:</strong> <?php echo htmlspecialchars($serviceDetails['name']); ?></li>
                <li><strong>Description:</strong> <?php echo htmlspecialchars($serviceDetails['description']); ?></li>
                <li><strong>Price:</strong> RM <?php echo htmlspecialchars($serviceDetails['price']); ?></li>
            </ul>
        </section>

        <!-- Button to navigate back to mainpage.php -->
        <a href="mainpage.php" class="main-page-btn">Back to Main Page</a>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
